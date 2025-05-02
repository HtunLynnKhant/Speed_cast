<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use App\Interfaces\Services\PageServiceInterface;
use App\Interfaces\Services\CategoryServiceInterface;

class CategoryController extends Controller
{
    public function __construct(
        private readonly CategoryServiceInterface $service,
        private readonly PageServiceInterface $pageservice
    ) {
    }

    /**
     * Category create view.
     *
     * @return View
     */
    public function new(): View
    {
        return view('admin.category.new');
    }

    /**
     * Category list view.
     *
     * @return View
     */
    public function list(Request $request): View
    {
        $categories = $this->service->getAll($request);

        $sortOrder = $request->get('sort_order', 'asc');

        return view('admin.category.list', compact('categories', 'sortOrder'));
    }

    /**
     * Create category.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'active_icon_path' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'default_icon_path' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'description' => 'nullable|string',
        ]);
        $this->service->create($request);

        return redirect()->route(
            auth()->user()->hasRole('superadmin') ? 'superadmin.category.list' :
            (auth()->user()->hasRole('admin') ? 'admin.category.list' : 'client.category.list')
        )->with(
            'success',
            __('response.admin.category.create.success')
        );
    }

    /**
     * Edit category view.
     *
     * @return void
     */
    public function edit(string $id): View
    {
        $category = $this->service->findById($id);
        $pages = $this->pageservice->getAllByCategoryId($category->id);

        return view('admin.category.edit', compact('category', 'pages'));
    }

    /**
     * Update category.
     *
     * @param Request $request
     * @param string $id
     * @return void
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|boolean',
        ]);
        $this->service->update($id, $request);


        return redirect()->route(
            auth()->user()->hasRole('superadmin') ? 'superadmin.category.list' :
            (auth()->user()->hasRole('admin') ? 'admin.category.list' : 'client.category.list')
        )->with(
            'success',
            __('response.admin.category.update.success')
        );
    }

    /**
     * Delete category.
     *
     * @param string $id
     * @return void
     */
    public function destroy(string $id)
    {
        $isDeleted = $this->service->delete($id);
        if (!$isDeleted) {
            return redirect()->route('admin.category.list')->with(
                'error',
                __('response.admin.category.delete.fail')
            );
        }

        return redirect()->route(
            auth()->user()->hasRole('superadmin') ? 'superadmin.category.list' :
            (auth()->user()->hasRole('admin') ? 'admin.category.list' : 'client.category.list')
        )->with(
            'success',
            __('response.admin.category.delete.success')
        );
    }
}
