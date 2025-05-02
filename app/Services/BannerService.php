<?php

namespace App\Services;

use App\Models\Banner;
use App\Enums\ContentTypes;
use Illuminate\Http\Request;
use App\Enums\ContentFolders;
use App\Models\BannerContent;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Collection;
use App\Interfaces\Services\BannerServiceInterface;
use App\Interfaces\Services\StorageServiceInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection as SupportCollection;

class BannerService implements BannerServiceInterface
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
        $banners = Banner::active()->get();

        return $banners;
    }

    /**
     * Get all banners including inactive.
     *
     * @return Collection
     */
    public function getAll(Request $request): LengthAwarePaginator
    {
        $banners = Banner::query();

        // If a search term is provided, filter by title
        if ($request->has('title') && $request->title !== '') {
            $banners = $banners->where('title', 'like', '%' . $request->title . '%');
        }
        // Sorting setup
        $sortableColumns = ['id', 'title'];
        $sortBy = $request->get('sort_by', 'id');
        $sortOrder = $request->get('sort_order', 'asc');

        // Validate sorting parameters
        if (!in_array($sortBy, $sortableColumns)) {
            $sortBy = 'id';
        }
        if (!in_array($sortOrder, ['asc', 'desc'])) {
            $sortOrder = 'asc';
        }

        // Apply sorting
        $banners->orderBy($sortBy, $sortOrder);

        // Paginate while preserving query parameters
        return $banners->paginate(config('settings.pagination.default_per_page'))
                    ->appends(['sort_by' => $sortBy, 'sort_order' => $sortOrder]);
    }

    /**
     * Create banner.
     *
     * @param Request $request
     * @return Banner|null
     */
    public function create(Request $request): ?Banner
    {
        DB::beginTransaction();

        try {
            // Check if an active banner already exists
            if ($request->input('status') == 1 && Banner::where('status', 1)->exists()) {
                // Throw an exception to prevent creation
                throw new \Exception('An active banner already exists. Only one active banner is allowed.');
            }

            $contents = $this->getContentPaths($request);

            // Create the new banner
            $banner = Banner::create([
                'title' => $request->input('title'),
                'description' => $request->input('description'),
                'status' => $request->input('status', 0),
                'video_link' => $request->input('video_link'),
            ]);

            $contents->each(function ($path, $type) use ($banner) {
                BannerContent::firstOrCreate(['banner_id' => $banner->id, 'type' => $type], [
                    'type' => $type,
                    'path' => $path,
                    'banner_id' => $banner->id,
                ]);
            });

            DB::commit();

            return $banner;
        } catch (\Throwable $e) {
            DB::rollBack();

            if (isset($contents)) {
                $contents->each(function ($path) {
                    $this->storage->delete($path);
                });
            }

            Log::error('Failed to create banner. ErrMsg : ' . $e->getMessage());

            // Optionally, rethrow the exception for the controller to handle
            throw $e;
        }

        return null;
    }

    /**
     * Get banner byID.
     *
     * @param integer $id
     * @return Banner|null
     */
    public function findById(string $id): ?Banner
    {
        return Banner::find($id);
    }

    /**
     * Update banner.
     *
     * @param Request $request
     * @param integer $id
     * @return bool
     */
    public function update(Request $request, int $id): bool
    {
        $isUpdated = false;
        DB::beginTransaction();

        try {
            $banner = Banner::findOrFail($id);

            if ($request->hasFile('image')) {
                // Delete old image from banner_content
                $oldImage = $banner->contents->firstWhere('type', \App\Enums\ContentTypes::IMAGE->value);
                if ($oldImage) {
                    $this->storage->delete($oldImage->path);
                    $oldImage->delete();
                }

                // Upload new image
                $path = $this->storage->put(ContentFolders::CONTENT, $request->file('image'));
                BannerContent::create([
                    'banner_id' => $banner->id,
                    'type' => \App\Enums\ContentTypes::IMAGE->value,
                    'path' => $path,
                ]);

                // Clear video link
                $banner->video_link = null;
            } elseif ($request->filled('video_link')) {
                // Delete old image if video link is provided
                $oldImage = $banner->contents->firstWhere('type', \App\Enums\ContentTypes::IMAGE->value);
                if ($oldImage) {
                    $this->storage->delete($oldImage->path);
                    $oldImage->delete();
                }

                // Update video link
                $banner->video_link = $request->input('video_link');
            }

            $requestData = $request->only(['title', 'status', 'description', 'video_link']);

            if ($request->input('status') == 1) {
                $alreadyActive = Banner::where('status', 1)->where('id', '!=', $id)->exists();
                if ($alreadyActive) {
                    throw new \Exception('An active banner already exists. Only one active banner is allowed.');
                }
            }

            $isUpdated = $banner->update($requestData);
            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Failed to update banner. ErrMsg: ' . $e->getMessage());

            throw $e;
        }

        return $isUpdated;
    }

    /**
     * Upload content and collect path.
     *
     * @param Request $request
     * @return SupportCollection
     */
    private function getContentPaths(Request $request): SupportCollection
    {
        $contents = collect();
        if ($request->hasFile('image')) {
            $path = $this->storage->put(ContentFolders::CONTENT, $request->file('image'));
            $contents->put(ContentTypes::IMAGE->value, $path);
            Log::info("Uploaded image path: {$path}");
        }
        if ($request->input('video_link')) {
            Log::info("Video link provided: " . $request->input('video_link'));
        }

        return $contents;
    }

    /**
     * Delete banner.
     *
     * @param string $id
     * @return boolean
     */
    public function delete(string $id): bool
    {
        $category = Banner::find($id);

        return $category->delete();
    }
}
