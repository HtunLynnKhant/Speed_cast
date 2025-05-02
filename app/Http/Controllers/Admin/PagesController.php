<?php

namespace App\Http\Controllers\Admin;

use App\Enums\RecordStatus;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use App\Interfaces\Services\PageServiceInterface;
use App\Interfaces\Services\CategoryServiceInterface;
use App\Models\Page;

class PagesController extends Controller
{
    public function __construct(
        private readonly PageServiceInterface $service,
        private readonly CategoryServiceInterface $categoryService
    ) {
    }

    public function addpage(int $categoryId): View
    {
        $category = $this->categoryService->findById($categoryId); // Fetch the specific category by ID
        $maxPageNo = Page::where('category_id', $categoryId)->max('page_no');
        $nextPageNo = is_null($maxPageNo) ? 1 : $maxPageNo + 1;


        return view('admin.pages.add', compact('category', 'nextPageNo'));
    }


    /**
     * store page.
     *
     * @param Request $request
     * @param int $id
     * @return RedirectResponse
     */
    public function store_Page(Request $request): RedirectResponse
    {
        $request->validate([
            'status' => 'required|in:' . collect(RecordStatus::cases())->pluck('value')->implode(','),
        ]);
        $page = $this->service->create($request);

        if ($page) {
            return redirect()->route(
                auth()->user()->hasRole('superadmin') ? 'superadmin.category.edit' :
                (auth()->user()->hasRole('admin') ? 'admin.category.edit' : 'client.category.edit'),
                ['id' => $page->category_id]
            )->with('success', 'Page added successfully.');
        }

        return back()->withErrors('Failed to add page. Please check your input.');
    }

    /**
     * page edit view.
     *
     * @param integer $id
     * @return View
     */
    public function edit(int $id): View
    {
        // Fetch the specific page by ID
        $page = $this->service->findById($id);
        if (!$page) {
            return redirect()->route(
                auth()->user()->hasRole('superadmin') ? 'superadmin.category.list' :
                (auth()->user()->hasRole('admin') ? 'admin.category.list' : 'client.category.list')
            )->withErrors('Page not found.');
        }

        $category = $this->categoryService->findById($page->category_id); // Fetch the category for this page

        return view('admin.pages.edit', compact('page', 'category'));
    }

    /**
     * Update page.
     *
     * @param Request $request
     * @param int $id
     * @return RedirectResponse
     */
    public function update(Request $request, int $id): RedirectResponse
    {
        $request->validate([
            'status' => 'required|in:' . collect(RecordStatus::cases())->pluck('value')->implode(','),
        ]);
        // Use the service to update the page
        $page = $this->service->update($request, $id);

        // Check if the page was updated successfully
        if ($page) {
            return redirect()->route(
                auth()->user()->hasRole('superadmin') ? 'superadmin.category.edit' :
                (auth()->user()->hasRole('admin') ? 'admin.category.edit' : 'client.category.edit'),
                ['id' => $page->category_id]
            )->with('success', 'Page updated successfully.');
        }

        // If the update failed, redirect back with an error message
        return back()->withErrors('Failed to update page. Please check your input.');
    }

    /**
     * Delete page.
     *
     * @param int $id
     * @return RedirectResponse
     */
    public function destroy(int $id): RedirectResponse
    {
        // Attempt to find the page before deletion to retrieve the category_id
        $page = $this->service->find($id);

        // If the page was not found, redirect back with an error message
        if (!$page) {
            return redirect()->route('admin.category.list')
                ->with('error', 'Page not found.');
        }

        $categoryId = $page->category_id;
        $result = $this->service->delete($id);
        if ($result === null) {
            return redirect()->route(
                auth()->user()->hasRole('superadmin') ? 'superadmin.category.edit' :
                (auth()->user()->hasRole('admin') ? 'admin.category.edit' : 'client.category.edit'),
                ['id' => $page->category_id]
            )->with('success', 'Page updated successfully.');
        }

        return redirect()->route(
            auth()->user()->hasRole('superadmin') ? 'superadmin.category.edit' :
            (auth()->user()->hasRole('admin') ? 'admin.category.edit' : 'client.category.edit'),
            ['id' => $categoryId]
        )->with('success', 'Page deleted successfully.');
    }
}
