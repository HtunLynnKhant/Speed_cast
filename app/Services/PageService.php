<?php

namespace App\Services;

use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\Collection;
use App\Interfaces\Services\PageServiceInterface;
use App\Interfaces\Services\StorageServiceInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\JsonResponse;

class PageService implements PageServiceInterface
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
        $page = Page::active()->get();

        return $page;
    }

    /**
     * Get all Page including inactive.
     *
     * @return Collection
     */
    public function getAll(): LengthAwarePaginator
    {
        $pages = Page::with('category')->paginate(
            config('settings.pagination.default_per_page')
        );

        return $pages;
    }

    public function getAllByCategoryId(int $categoryId): LengthAwarePaginator
    {
        $pages = Page::with('category')
            ->where('category_id', $categoryId) // Filter by category ID
            ->paginate(config('settings.pagination.default_per_page'));

        return $pages;
    }

    /**
     * Get Page byID.
     *
     * @param integer $id
     * @return Page|null
     */
    public function findById(string $id): ?Page
    {
        return Page::find($id);
    }

    /**
     * Create Page.
     *
     * @param Request $request
     * @return Page|null
     */
    public function create(Request $request): ?Page
    {
        // Define validation rules based on your Page model's requirements
        $validator = Validator::make($request->all(), [
            'category_id' => 'required|exists:categories,id',
            'status' => 'required|in:0,1',
        ]);

        if ($validator->fails()) {
            return null;
        }

        // Automatically assign the next page number for the selected category
        $maxPageNo = Page::where('category_id', $request->input('category_id'))->max('page_no');
        $nextPageNo = is_null($maxPageNo) ? 1 : $maxPageNo + 1;

        // Create the new Page
        return Page::create([
            'page_no' => $nextPageNo,
            'category_id' => $request->input('category_id'),
            'description' => $request->input('description'),
            'status' => $request->input('status'),
        ]);
    }

    public function update(Request $request, int $id): ?Page
    {
        $validator = Validator::make($request->all(), [
            'category_id' => 'required|exists:categories,id',
        ]);

        if ($validator->fails()) {
            return null;
        }

        $page = Page::find($id);
        if (!$page) {
            return null; // Return null if the page is not found
        }

        // Update the page attributes
        $page->category_id = $request->input('category_id');
        $page->description = $request->input('description');
        $page->status = $request->input('status');
        // Save and check for success
        if ($page->save()) {
            return $page; // Return the updated Page object
        }

        return null;
    }

    /**
     * Get page byID.
     *
     * @param integer $id
     *
     */

    public function find(int $id): ?Page
    {
        return Page::find($id);
    }

    public function delete(int $id): ?bool
    {
        $page = Page::find($id);
        if (!$page) {
            return null;
        }

        // Delete the page and return true for success
        return $page->delete() ? true : false;
    }

    public function getPagesByCategory($categoryId): JsonResponse
    {
        // Fetch pages based on the category ID
        $pages = Page::where('category_id', $categoryId)->get(['id', 'page_no']);

        // Return the pages as a JSON response
        return response()->json($pages);
    }
}
