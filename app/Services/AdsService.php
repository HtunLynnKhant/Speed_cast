<?php

namespace App\Services;

use App\Models\Ads;
use App\Models\Payment;
use App\Models\Subscription;
use Illuminate\Http\Request;
use App\Enums\ContentFolders;
use Illuminate\Database\Eloquent\Collection;
use App\Interfaces\Services\AdsServiceInterface;
use App\Interfaces\Services\StorageServiceInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use App\Enums\ContentTypes;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AdsService implements AdsServiceInterface
{
    public function __construct(
        private readonly StorageServiceInterface $storage
    ) {
    }

    /**
     * Get all active Page.
     *
     * @return Collection
     */
    public function getAllActive(): Collection
    {
        $ads = Ads::active()->get();

        return $ads;
    }
    /**
     * Get all Ads including inactive.
     *
     * @return Collection
     */
    public function getAll(Request $request): LengthAwarePaginator
    {
        $adss = Ads::with('category');

        // Apply search filter if 'title' is provided
        if ($request->has('title') && $request->title !== '') {
            $adss = $adss->where('title', 'like', '%' . $request->title . '%');
        }
        $adss = $adss->paginate(config('settings.pagination.default_per_page'));

        // Paginate results
        return $adss;

    }

    /**
     * Create a new Ads with subscription.
     *
     * @param Request $request
     * @return Ads|null
     */
    public function create(Request $request): ?Ads
    {
        try {
            // Retrieve the ad type ID for MAIN_BANNER from the database
            $mainBannerTypeId = DB::table('ads_types')
                ->where('name', 'Main Banner')
                ->value('id');

            // File validation based on content type
            $contentType = $request->input('content_type_id');
            $file = $request->file('content_path');
            $validFile = false;

            if ($contentType == ContentTypes::IMAGE->value) {
                $validFile = $file->isValid() &&
                    in_array($file->getClientMimeType(), ['image/jpeg', 'image/png', 'image/gif']);
            } elseif ($contentType == ContentTypes::VIDEO->value) {
                $validFile = $file->isValid() &&
                    in_array($file->getClientMimeType(), ['video/mp4', 'video/x-msvideo', 'video/x-flv']);
            }

            if (!$validFile) {
                throw new \InvalidArgumentException(
                    'The uploaded file must be a valid ' .
                    ($contentType == ContentTypes::IMAGE->value ? 'image' : 'video') .
                    ' file.'
                );
            }

            // Check for main banner advertisement conflict
            $isMainBanner = $request->input('ads_type_id') == $mainBannerTypeId;

            if ($isMainBanner) {
                $existingAds = Ads::where('ads_type_id', $mainBannerTypeId)
                    ->where('category_id', $request->input('category_id'))
                    ->where('page_id', $request->input('page_id'))
                    ->whereHas('subscription', function ($query) use ($request) {
                        $query->where(function ($query) use ($request) {
                            $query->whereBetween('active_from', [$request->input('active_from'), $request->input('end_on')])
                                ->orWhereBetween('end_on', [$request->input('active_from'), $request->input('end_on')])
                                ->orWhere(function ($query) use ($request) {
                                    $query->where('active_from', '<=', $request->input('active_from'))
                                        ->where('end_on', '>=', $request->input('end_on'));
                                });
                        });
                    })
                    ->exists();

                if ($existingAds) {
                    throw new \LogicException('A main banner ad already exists in the selected category and page within the specified period.');
                }
            } else {
                // Check for other banners limitation (maximum 4 banners per category and page)
                $existingAdsCount = Ads::where('ads_type_id', $request->input('ads_type_id'))
                    ->where('category_id', $request->input('category_id'))
                    ->where('page_id', $request->input('page_id'))
                    ->whereHas('subscription', function ($query) use ($request) {
                        $query->where(function ($query) use ($request) {
                            $query->whereBetween('active_from', [$request->input('active_from'), $request->input('end_on')])
                                ->orWhereBetween('end_on', [$request->input('active_from'), $request->input('end_on')])
                                ->orWhere(function ($query) use ($request) {
                                    $query->where('active_from', '<=', $request->input('active_from'))
                                        ->where('end_on', '>=', $request->input('end_on'));
                                });
                        });
                    })
                    ->count();

                if ($existingAdsCount >= 4) {
                    throw new \LogicException('Only 4 advertisements are allowed per category per page within the specified period.');
                }
            }

            $content_path = $this->storage->put(ContentFolders::CONTENT, $request->file('content_path'));

            $ads = Ads::create([
                'title' => $request->input('title'),
                'project_id' => $request->input('project_id'),
                'total_price' => $request->input('totalprice'),
                'ads_type_id' => $request->input('ads_type_id'),
                'content_type_id' => $request->input('content_type_id'),
                'discount' => $request->input('discount', 0),
                'content_path' => $content_path,
                'page_id' => $request->input('page_id'),
                'category_id' => $request->input('category_id'),
                'Is_Approve' => 'pending',
            ]);

            $subscription = Subscription::create([
                'active_from' => $request->input('active_from'),
                'end_on' => $request->input('end_on'),
                'ads_id' => $ads->id,
            ]);

            // Update the ads record with the subscription_id
            $ads->update(['subscription_id' => $subscription->id]);

            return $ads;
        } catch (\Exception $e) {
            // Log the error for debugging
            Log::error('Ad creation failed: ' . $e->getMessage(), ['exception' => $e]);

            // Return a response with the error message
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    /**
     * Get ads byID.
     *
     * @param integer $id
     * @return Banner|null
     */
    public function findById(string $id): ?Ads
    {
        return Ads::with('subscription')->find($id);
    }

    public function update(Request $request, int $id): bool
    {
        try {
            $mainBannerTypeId = DB::table('ads_types')
                                ->where('name', 'Main Banner') // Adjust 'name' to your actual column name
                                ->value('id');
            // Find the Ads record by ID
            $ads = Ads::find($id);
            if (!$ads) {
                return false;
            }

            // Prepare an array for updates
            $requestData = [
                'project_id' => $request->input('project_id'),
                'title' => $request->input('title'),
                'total_price' => $request->input('totalprice'),
                'ads_type_id' => $request->input('ads_type_id'),
                'content_type_id' => $request->input('content_type_id'),
                'discount' => $request->input('discount', 0),
                'status' => $request->input('status', $ads->status),
                'payment_status' => $request->input('payment_status', $ads->payment_status),
                'approval_status' => $ads->approval_status,
                'remark' => $ads->remark,
                'category_id' => $request->input('category_id', $ads->category_id), // Updated category_id from request
                'page_id' => $request->input('page_id', $ads->page_id)
            ];

            // Handle file upload if provided
            if ($request->hasFile('content_path')) {
                // Validate file based on content type
                $contentType = $request->input('content_type_id');
                $file = $request->file('content_path');
                $validFile = false;

                if ($contentType == ContentTypes::IMAGE->value) {
                    $validFile = $file->isValid() &&
                                in_array($file->getClientMimeType(), ['image/jpeg', 'image/png', 'image/gif']);
                } elseif ($contentType == ContentTypes::VIDEO->value) {
                    $validFile = $file->isValid() &&
                                in_array($file->getClientMimeType(), ['video/mp4', 'video/x-msvideo', 'video/x-flv']);
                }

                if (!$validFile) {
                    return back()->withErrors(['content_path' => 'The uploaded file must be a valid ' . ($contentType == ContentTypes::IMAGE->value ? 'image' : 'video') . ' file.']);
                }

                // Store the new file and set the content path
                $requestData['content_path'] = $this->storage->put(ContentFolders::CONTENT, $file);

                // Delete the old content path if it exists
                if ($ads->content_path) {
                    $this->storage->delete($ads->content_path);
                }
            }

            // Check for ad type and validate conflicts
            $isMainBanner = $request->input('ads_type_id') == $mainBannerTypeId;

            if ($isMainBanner) {
                // Check for main banner advertisement conflict
                $existingAds = Ads::where('ads_type_id', $mainBannerTypeId)
                    ->where('category_id', $request->input('category_id'))
                    ->where('page_id', $request->input('page_id'))
                    ->whereHas('subscription', function ($query) use ($request) {
                        $query->where(function ($query) use ($request) {
                            $query->whereBetween('active_from', [$request->input('active_from'), $request->input('end_on')])
                                ->orWhereBetween('end_on', [$request->input('active_from'), $request->input('end_on')])
                                ->orWhere(function ($query) use ($request) {
                                    $query->where('active_from', '<=', $request->input('active_from'))
                                            ->where('end_on', '>=', $request->input('end_on'));
                                });
                        });
                    })
                    ->exists();

                if ($existingAds) {
                    throw new \LogicException('A main banner ad already exists in the selected category and page within the specified period.');
                }
            } else {
                // Check for other banners limitation (maximum 4 banners per category and page)
                $existingAdsCount = Ads::where('ads_type_id', $request->input('ads_type_id'))
                    ->where('category_id', $request->input('category_id'))
                    ->where('page_id', $request->input('page_id'))
                    ->where('id', '!=', $id) // Exclude the current ad
                    ->whereHas('subscription', function ($query) use ($request) {
                        $query->where(function ($query) use ($request) {
                            $query->whereBetween('active_from', [$request->input('active_from'), $request->input('end_on')])
                                ->orWhereBetween('end_on', [$request->input('active_from'), $request->input('end_on')])
                                ->orWhere(function ($query) use ($request) {
                                    $query->where('active_from', '<=', $request->input('active_from'))
                                            ->where('end_on', '>=', $request->input('end_on'));
                                });
                        });
                    })
                    ->count();

                if ($existingAdsCount >= 4) {
                    throw new \LogicException('Only 4 advertisements are allowed per category per page within the specified period.');
                }
            }

            // Update the ads model with the request data
            $ads->fill($requestData);
            $ads->save();

            $subscription = Subscription::where('id', $ads->subscription_id)->first();
            // Update the subscription data
            if ($subscription) {
                $subscription->active_from = $request->input('active_from', $subscription->active_from);
                $subscription->end_on = $request->input('end_on', $subscription->end_on);
                $subscription->status = $request->input('status', $subscription->status);
                $subscription->save();
            } else {
                // If subscription does not exist, create a new one
                Subscription::create([
                    'active_from' => $request->input('active_from'),
                    'end_on' => $request->input('end_on'),
                    'ads_id' => $ads->id,
                    'status' => 1,
                ]);
            }

            return true;

        } catch (\Exception $e) {
            // Log the exception message
            Log::error('Error updating ad: ' . $e->getMessage());

            // Optionally, you can return a specific error response
            return false; // or handle it as per your application's error handling strategy
        }
    }


    /**
     * Delete Ads.
     *
     * @param string $id
     * @return boolean
     */
    public function delete(string $id): bool
    {
        try {
            $ads = Ads::find($id);
            if (!$ads) {
                return false;
            }

            // Delete the associated subscription
            $subscription = Subscription::where('ads_id', $ads->id)->first(); // Adjusted to use 'ads_id' for the relationship
            if ($subscription) {
                $subscription->delete();
            }

            return $ads->delete();
        } catch (\Exception $e) {
            // Log the exception message
            Log::error('Error deleting ad: ' . $e->getMessage());

            // Optionally, you can return a specific error response
            return false; // or handle it as per your application's error handling strategy
        }
    }

    public function getAdWithPayments(int $id): Ads
    {
        // Eager load the payments for the ad
        return Ads::with('payments', 'currency')->findOrFail($id);
    }

    public function storePayment(Request $request, int $id)
    {
        // Create the payment
        $payment = app(Payment::class);
        $payment->ads_id = $id;
        $payment->amount = $request->amount;
        $payment->payment_type = $request->payment_type;
        $payment->currency_id = $request->currency_id;
        $payment->date = $request->date;
        $payment->is_fully_paid = $request->has('is_fully_paid');

        $payment->save();

        return $payment; // Optionally return the payment instance
    }

    public function updatePayment(Request $request, int $paymentId): ?Payment
    {
        $payment = Payment::findOrFail($paymentId);

        $payment->amount = $request->amount;
        $payment->payment_type = $request->payment_type;
        $payment->currency_id = $request->currency_id;
        $payment->date = $request->date;
        $payment->is_fully_paid = $request->has('is_fully_paid');
        $payment->save();

        return $payment;
    }
}
