<?php

namespace App\Services;

use App\Interfaces\Services\StorageServiceInterface;
use App\Interfaces\Services\SubbannerServiceInterface;
use App\Models\Subbanner;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use App\Enums\ContentFolders;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SubbannerService implements SubbannerServiceInterface
{
    public function __construct(
        private readonly StorageServiceInterface $storage
    ) {
    }

    /**
     * Get all active banners.
     *
     * @return Collection
     */
    public function getAllActive(): Collection
    {
        $subbanners = Subbanner::active()->get();

        return $subbanners;
    }
    /**
     * Get all subbanners including inactive.
     *
     * @return Collection
     */
    public function getAll(Request $request): LengthAwarePaginator
    {
        $subbanners = Subbanner::query();

        // If a search term is provided, filter by title
        if ($request->has('title') && $request->title !== '') {
            $subbanners = $subbanners->where('title', 'like', '%' . $request->title . '%');
        }

        // Sorting
        $sortOrder = $request->get('sort_order', 'asc');

        if (!in_array($sortOrder, ['asc', 'desc'])) {
            $sortOrder = 'asc';
        }

        // Apply sorting
        $subbanners->orderBy('title', $sortOrder);

        // Paginate while preserving query parameters
        return $subbanners->paginate(config('settings.pagination.default_per_page'))
                        ->appends(['sort_order' => $sortOrder]);
    }


    public function create(Request $request): ?Subbanner
    {
        DB::beginTransaction();

        try {
            // Handle image upload if provided
            $imagePath = null;
            if ($request->hasFile('image')) {
                $imagePath = $this->storage->put(ContentFolders::SUBBANNER, $request->file('image'));
            }

            // Get the video link (if provided)
            $videoLink = $request->input('video_link', null);

            // Check if there are already 4 active sub-banners
            $activeCount = Subbanner::where('status', 1)->count();
            if ($request->input('status') == 1 && $activeCount >= 4) {
                throw new \Exception('Only 4 active sub-banners are allowed.');
            }

            // Create the sub-banner
            $subbanner = Subbanner::create([
                'title' => $request->input('title'),
                'description' => $request->input('description'),
                'status' => $request->input('status', 0),
                'image_path' => $imagePath, // Set image path if available
                'video_link' => $request->input('video_link'), // Set video link if available
            ]);

            DB::commit();

            return $subbanner;
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Failed to create subbanner. ErrMsg: ' . $e->getMessage());

            throw $e; // Optionally rethrow for the controller to handle
        }
    }

    public function findById(string $id): Subbanner
    {
        return Subbanner::findOrFail($id);
    }

    public function update(string $id, Request $request): Subbanner
    {
        DB::beginTransaction();

        try {
            $subbanner = Subbanner::findOrFail($id);

            // Check if the user uploaded a new image
            if ($request->hasFile('image')) {
                // Delete the old image if exists
                if ($subbanner->image_path) {
                    $this->storage->delete($subbanner->image_path);
                }

                // Upload the new image
                $subbanner->image_path = $this->storage->put(ContentFolders::SUBBANNER, $request->file('image'));

                // If an image is provided, clear the video link
                $subbanner->video_link = null;
            }

            // Check if the user provided a new video link
            if ($request->filled('video_link')) {
                // Clear the image if a video link is provided
                if ($subbanner->image_path) {
                    $this->storage->delete($subbanner->image_path);
                    $subbanner->image_path = null;
                }

                $subbanner->video_link = $request->input('video_link');
            }

            // Update other fields
            $subbanner->title = $request->input('title', $subbanner->title);
            $subbanner->description = $request->input('description', $subbanner->description);
            $subbanner->status = $request->input('status', $subbanner->status);

            // Custom validation for active sub-banners
            if ($request->input('status') == 1) {
                $activeCount = Subbanner::where('status', 1)->where('id', '!=', $subbanner->id)->count();
                if ($activeCount >= 4) {
                    throw new \Exception('Only 4 active sub-banners are allowed.');
                }
            }

            $subbanner->save();

            DB::commit();

            return $subbanner;
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Failed to update subbanner. ErrMsg: ' . $e->getMessage());

            throw $e;
        }
    }

    public function delete(string $id): bool
    {
        $subbanners = Subbanner::find($id);

        return $subbanners->delete();
    }

}
