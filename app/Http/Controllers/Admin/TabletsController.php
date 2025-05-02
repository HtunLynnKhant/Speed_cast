<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Interfaces\Services\TabletsServiceInterface;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class TabletsController extends Controller
{
    public function __construct(
        private readonly TabletsServiceInterface $service,
    ) {
    }

    /**
     * tablets list view
     *
     * @return View
     */
    public function list(Request $request): View
    {
        $tablets = $this->service->getAll($request);


        return view('admin.tablets.list', compact('tablets'));
    }

    /**
     * tablets project view.
     *
     * @return View
     */
    public function new(): View
    {
        return view('admin.tablets.new');
    }

    /**
     * Create Tablets.
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

        return redirect()->route(auth()->user()->hasRole('admin') ? 'admin.tablets.list' : 'superadmin.tablets.list')->with('success', __('response.admin.tablets.create.success'));

    }

    /**
    * Edit tablets view.
    *
    * @return void
    */
    public function edit(string $id): View
    {
        $tablet = $this->service->findById($id);

        return view('admin.tablets.edit', compact('tablet'));
    }

    /**
     * Update tablets.
     *
     * @param Request $table
     * @param string $id
     * @return void
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required|string|max:125',
            'status' => 'required|boolean',
        ]);
        $this->service->update($id, $request);

        return redirect()->route(auth()->user()->hasRole('admin') ? 'admin.tablets.list' : 'superadmin.tablets.list')->with(
            'success',
            __('response.admin.tablets.update.success')
        );
    }

    /**
     * Delete tablets.
     *
     * @param string $id
     * @return void
     */
    public function destroy(string $id)
    {
        $isDeleted = $this->service->delete($id);
        if (!$isDeleted) {
            return redirect()->route(auth()->user()->hasRole('admin') ? 'admin.tablets.list' : 'superadmin.tablets.list')->with(
                'error',
                __('response.admin.tablets.delete.fail')
            );
        }

        return redirect()->route(auth()->user()->hasRole('admin') ? 'admin.tablets.list' : 'superadmin.tablets.list')->with(
            'success',
            __('response.admin.tablets.delete.success')
        );
    }
}
