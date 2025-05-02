<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Requests\ProjectRequest;
use Illuminate\Contracts\View\View;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use App\Interfaces\Services\ProjectServiceInterface;

class ProjectController extends Controller
{
    public function __construct(
        private readonly ProjectServiceInterface $service,
    ) {
    }

    /**
     * project list view
     *
     * @return View
     */
    public function list(Request $request): View
    {
        $projects = $this->service->getAll($request);

        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');

        return view('admin.project.list', compact('projects', 'sortBy', 'sortOrder'));
    }


    /**
     * create project view.
     *
     * @return View
     */
    public function new(): View
    {
        return view('admin.project.new');
    }

    /**
     * Create category.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        // Validate incoming request
        $request->validate([
            'name' => 'required|string|max:125',
        ]);
        $this->service->create($request);

        return redirect()->route(auth()->user()->hasRole('admin') ? 'admin.project.list' : 'superadmin.project.list')->with('success', __('response.admin.project.create.success'));

    }

    /**
    * Edit category view.
    *
    * @return void
    */
    public function edit(string $id): View
    {
        $project = $this->service->findById($id);

        return view('admin.project.edit', compact('project'));
    }

    /**
     * Update category.
     *
     * @param Request $project
     * @param string $id
     * @return void
     */
    public function update(ProjectRequest $request, string $id)
    {
        $this->service->update($id, $request);

        return redirect()->route(auth()->user()->hasRole('admin') ? 'admin.project.list' : 'superadmin.project.list')->with(
            'success',
            __('response.admin.project.update.success')
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
            return redirect()->route(auth()->user()->hasRole('admin') ? 'admin.project.list' : 'superadmin.project.list')->with(
                'error',
                __('response.admin.project.delete.fail')
            );
        }

        return redirect()->route(auth()->user()->hasRole('admin') ? 'admin.project.list' : 'superadmin.project.list')->with(
            'success',
            __('response.admin.project.delete.success')
        );
    }
}
