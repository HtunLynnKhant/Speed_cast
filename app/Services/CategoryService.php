<?php

namespace App\Services;

use App\Models\Banner;
use App\Models\Company;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Enums\ContentFolders;
use Illuminate\Database\Eloquent\Collection;
use App\Interfaces\Services\StorageServiceInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use App\Interfaces\Services\CategoryServiceInterface;
use Illuminate\Support\Facades\Log;

class CategoryService implements CategoryServiceInterface
{
    public function __construct(
        private readonly StorageServiceInterface $storage
    ) {
    }

    /**
     * Get all active categories.
     *
     * @return Collection
     */
    public function getAllActive(): Collection
    {
        $categories = Category::active()->get();

        return $categories;
    }

    /**
     * Get all categories including inactive.
     *
     * @return Collection
     */
    public function getAll(Request $request): LengthAwarePaginator
    {
        $categories = Category::query();

        // If a search term is provided, filter by name
        if ($request->has('name') && $request->name !== '') {
            $categories = $categories->where('name', 'like', '%' . $request->name . '%');
        }
        // Sorting logic
        $sortBy = $request->get('sort_by', 'name'); // Default to 'name'
        $sortOrder = $request->get('sort_order', 'asc');

        if (!in_array($sortOrder, ['asc', 'desc'])) {
            $sortOrder = 'asc';
        }

        // Apply sorting
        $categories = $categories->orderBy($sortBy, $sortOrder);

        // Paginate results while keeping query parameters
        return $categories->paginate(config('settings.pagination.default_per_page'))
        ->appends(['sort_by' => $sortBy, 'sort_order' => $sortOrder]);
    }

    /**
     * Get banners by category ID.
     * ? Should remove or not.
     *
     * @param string $id
     * @return Collection
     */
    public function getBannersById(string $id): Collection
    {
        $bannersByCategories = Banner::where('category_id', $id)
            ->active()
            ->get();

        return $bannersByCategories;
    }

    /**
     * Get companies by category ID.
     *
     * @param string $id
     * @return Collection
     */
    public function getCompaniesById(string $id): Collection
    {
        $companies = Company::whereCategoryId($id)
            ->active()
            ->get();

        return $companies;
    }

    /**
     * Get category by ID.
     *
     * @param string $id
     * @return Category
     */
    public function findById(string $id): Category
    {
        return Category::findOrFail($id);
    }

    /**
     * Create category.
     *
     * @param Request $request
     * @return Category|null
     */
    public function create(Request $request): ?Category
    {
        try {
            // Validate the incoming request data
            $request->validate([
                'name' => 'required|string|max:255',
                'active_icon_path' => 'required|file|mimes:png,jpg,jpeg',
                'default_icon_path' => 'required|file|mimes:png,jpg,jpeg',
                'description' => 'nullable|string|max:1000',
            ]);

            // Store the icon paths
            $active_icon_path = $this->storage->put(ContentFolders::LOGO, $request->file('active_icon_path'));
            $default_icon_path = $this->storage->put(ContentFolders::LOGO, $request->file('default_icon_path'));

            // Create or retrieve the category
            return Category::firstOrCreate(['name' => $request->input('name')], [
                'active_icon_path' => $active_icon_path,
                'default_icon_path' => $default_icon_path,
                'description' => $request->input('description') ?? ''
            ]);
        } catch (\Exception $e) {
            // Log the exception message
            Log::error('Error creating category: ' . $e->getMessage());

            // Optionally, you can return null or handle it as per your application's error handling strategy
            return null;
        }
    }
    /**
     * Update category.
     *
     * @param string $id
     * @param Request $data
     * @return void
     */
    public function update(string $id, Request $request): Category
    {
        try {
            // Find the category or fail
            $category = Category::findOrFail($id);
            $requestData = $request->only('name', 'description', 'status');

            // Handle file uploads if provided
            if ($request->hasFile('active_icon_path')) {
                // Store the new active icon and delete the old one
                $requestData['active_icon_path'] = $this->storage->put(ContentFolders::LOGO, $request->file('active_icon_path'));
                $this->storage->delete($category->active_icon_path);
            }

            if ($request->hasFile('default_icon_path')) {
                // Store the new default icon and delete the old one
                $requestData['default_icon_path'] = $this->storage->put(ContentFolders::LOGO, $request->file('default_icon_path'));
                $this->storage->delete($category->default_icon_path);
            }

            // Check for dirty fields and update
            if ($category->fill($requestData)->isDirty()) {
                $category->update($requestData);
            }

            return $category;

        } catch (\Exception $e) {
            // Log the exception message
            Log::error('Error updating category: ' . $e->getMessage());

            // Optionally, you can return a specific error response or throw a custom exception
            throw new \RuntimeException('Failed to update category.'); // or return null, depending on your error handling strategy
        }
    }

    /**
     * Soft delete the category.
     *
     * @param string $id
     * @return boolean
     */
    public function delete(string $id): bool
    {
        $category = Category::find($id);

        return $category->delete();
    }


}
