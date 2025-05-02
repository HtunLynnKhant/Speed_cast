<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Interfaces\Services\AdsServiceInterface;
use Illuminate\Http\Request;
use App\Http\Requests\StoreAdsRequest;
use App\Http\Requests\UpdateAdsRequest;
use App\Interfaces\Services\AdstypeServiceInterface;
use App\Interfaces\Services\CurrenciesServiceInterface;
use App\Interfaces\Services\ProjectServiceInterface;
use App\Interfaces\Services\SubscriptionServiceInterface;
use App\Interfaces\Services\CategoryServiceInterface;
use App\Interfaces\Services\PageServiceInterface;
use App\Interfaces\Services\PaymentServiceInterface;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\PaymentRequest;
use App\Models\Ads;
use Illuminate\Support\Facades\Log;

class AdsController extends Controller
{
    public function __construct(
        private readonly AdsServiceInterface $service,
        private readonly CategoryServiceInterface $categoryservice,
        private readonly PageServiceInterface $pageservice,
        private readonly AdstypeServiceInterface $adstypeservice,
        private readonly ProjectServiceInterface $projecteservice,
        private readonly CurrenciesServiceInterface $currencyService,
        private readonly SubscriptionServiceInterface $subscriptionService,
        private readonly PaymentServiceInterface $paymentService,
    ) {
    }

    /**
     * Ads list view
     *
     * @return View
     */
    public function list(Request $request): View
    {
        $adss = $this->service->getAll($request);

        if ($request->ajax()) {
            return view('admin.Ads.partials.table_rows', compact('adss'));
        }

        return view('admin.Ads.list', compact('adss'));
    }

    /**
     * Ads detail view
     *
     * @param int $id
     * @return View
     */
    public function detail(int $id): View
    {
        // Retrieve the specific ad by ID using the service
        $ad = $this->service->findById($id);

        // Check if the ad exists
        if (!$ad) {
            return redirect()->route(auth()->user()->hasRole('admin') ? 'admin.ads.list' : 'superadmin.ads.list')->with('error', 'Ads not found');
        }

        // Pass the ad to the detail view
        return view('admin.Ads.detail', compact('ad'));
    }


    /**
     * New ads view.
     *
     * @return View
     */
    public function new(Request $request)
    {
        $projects = $this->projecteservice->getAllActive();
        $subscriptions = $this->subscriptionService->getAllActive();
        $adsTypes = $this->adstypeservice->getAllActive();
        $currencies = $this->currencyService->getAllActive();
        $categories = $this->categoryservice->getAllActive();

        return view('admin.Ads.new', compact('projects', 'subscriptions', 'adsTypes', 'categories'));
    }
    // In your AdsController or a dedicated controller for handling ads

    public function getPagesByCategory($categoryId)
    {
        $pages = $this->pageservice->getPagesByCategory($categoryId);

        return response()->json($pages); // Return the pages in JSON format
    }
    /**
     * Create new Ads.
     *
     * @param Request $request
     * @return void
     */
    public function store(StoreAdsRequest $request)
    {
        try {
            $ads = $this->service->create($request);

            return redirect()
                ->route(auth()->user()->hasRole('superadmin') ? 'superadmin.ads.list' :
                    (auth()->user()->hasRole('admin') ? 'admin.ads.list' : 'client.ads.list'))
                ->with('success', __('response.admin.ads.create.success'));
        } catch (\LogicException | \InvalidArgumentException $e) {
            return back()->with('error', $e->getMessage())->withInput();
        } catch (\Exception $e) {
            return back()->with('error', __('An unexpected error occurred. Please try again.'))->withInput();
        }
    }


    /**
     * Ads edit view.
     *
     * @param integer $id
     * @return View
     */
    public function edit(Request $request, int $id)
    {
        $ads = $this->service->findById($id);
        $projects = $this->projecteservice->getAllActive();
        $subscriptions = $this->subscriptionService->getAllActive();
        $adsTypes = $this->adstypeservice->getAllActive();
        $currencies = $this->currencyService->getAllActive();
        $categories = $this->categoryservice->getAllActive();
        $pages = $this->pageservice->getALL();
        if (!$ads) {
            return redirect()->route(auth()->user()->hasRole('admin') ? 'admin.ads.list' : 'superadmin.ads.list')->with('error', 'Ads not found');
        }

        return view('admin.Ads.edit', compact('pages', 'ads', 'projects', 'subscriptions', 'adsTypes', 'currencies', 'categories'));
    }


    /**
     * Update ads.
     *
     * @param Request $request
     * @param string $id
     * @return RedirectResponse
     */
    public function update(UpdateAdsRequest $request, int $id)
    {

        try {
            // Perform the update operation
            $updated = $this->service->update($request, $id);

            if (!$updated) {
                return redirect()->route(auth()->user()->hasRole('admin') ? 'admin.ads.edit' : 'superadmin.ads.edit', $id)
                    ->with('error', 'Ads update failed');
            }

            // Success redirect
            return redirect()->route(auth()->user()->hasRole('admin') ? 'admin.ads.list' : 'superadmin.ads.list')
                ->with('success', __('response.admin.banner.update.success'));
        } catch (\LogicException $e) {
            // Logic exception
            return back()->with('error', $e->getMessage())->withInput();
        } catch (\InvalidArgumentException $e) {
            // Invalid argument exception
            return back()->with('error', $e->getMessage())->withInput();
        } catch (\Exception $e) {
            // Catch any other unexpected errors
            return back()->with('error', __('An unexpected error occurred. Please try again.'))->withInput();
        }
    }

    /**
     * Delete ads.
     *
     * @param string $id
     * @return RedirectResponse
     */
    public function destroy(string $id): RedirectResponse
    {
        $this->service->delete($id);

        return redirect()->route(auth()->user()->hasRole('admin') ? 'admin.ads.list' : 'superadmin.ads.list')->with(
            'success',
            __('response.admin.ads.delete.success')
        );
    }

    public function viewPayment($id)
    {
        $ads = $this->service->getAdWithPayments($id);
        $currencies = $this->currencyService->getAllActive();

        return view('admin.adspayment.list', compact('ads', 'currencies'));
    }

    public function storePayment(PaymentRequest $request, int $id)
    {
        if ($request->session()->has('errors')) {
            return redirect()->back()->withInput()->with('showModal', true);
        }
        // Call the service to store the payment
        $this->service->storePayment($request, $id);

        return redirect()->route(auth()->user()->hasRole('admin') ? 'admin.ads.view.payment' : 'superadmin.ads.view.payment', $id)->with('success', 'Payment added successfully.');
    }



    public function updatePayment(Request $request, $id, $paymentId)
    {
        // Perform validation
        $request->validate([
            'amount' => 'required|numeric|min:0',
            'payment_type' => 'required|integer',
            'currency_id' => 'required|exists:currencies,id',
            'date' => 'required|date',
        ]);

        // Call the service to update the payment
        $this->service->updatePayment($request, $paymentId);

        return redirect()->route(auth()->user()->hasRole('admin') ? 'admin.ads.view.payment' : 'superadmin.ads.view.payment', $id)->with('success', 'Payment updated successfully.');
    }
    public function updateapprove(Request $request, $id)
    {
        try {
            $ads = Ads::findOrFail($id);

            // // Validate the status
            // $request->validate([
            //     'is_approve' => 'required|in:pending,approved,declined',
            // ]);

            // Update the ad status
            $ads->Is_Approve = $request->input('is_approve');
            $ads->save();

            return redirect()->back()->with('success', 'Ads admin approve updated successfully.');
        } catch (\Exception $e) {
            Log::error('Ad status update failed: ' . $e->getMessage());

            return back()->with('error', $e->getMessage());
        }
    }
}
