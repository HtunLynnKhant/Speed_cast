<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Interfaces\Services\SubbannerServiceInterface;
use Illuminate\Http\Request;
use App\Http\Requests\StoreSubbannerRequest;
use App\Http\Requests\UpdateSubbannerRequest;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class SubbannerController extends Controller
{
    public function __construct(
        private readonly SubbannerServiceInterface $service,
    ) {
    }


    /**
     * subbanners list view
     *
     * @return View
     */
    public function list(Request $request): View
    {
        $sortOrder = $request->get('sort_order', 'asc'); // Default to 'asc'

        $subbanners = $this->service->getAll($request);

        return view('admin.Subbanner.list', compact('subbanners', 'sortOrder'));
    }

    /**
     * New subbanners view.
     *
     * @return View
     */
    public function new(Request $request): View
    {

        return view('admin.Subbanner.new');
    }

    public function store(StoreSubbannerRequest $request)
    {

        try {
            $subbanner = $this->service->create($request);

            if (!$subbanner) {
                return redirect()->back()->withErrors(['msg' => 'Failed to create subbanner.']);
            }

            return redirect()->route(
                auth()->user()->hasRole('superadmin') ? 'superadmin.subbanner.list' :
                (auth()->user()->hasRole('admin') ? 'admin.subbanner.list' : 'client.subbanner.list')
            )->with(
                'success',
                __('response.admin.Subbanner.create.success')
            );
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function edit(string $id): View
    {
        $subbanner = $this->service->findById($id);

        return view('admin.Subbanner.edit', compact('subbanner'));
    }

    public function update(UpdateSubbannerRequest $request, string $id)
    {

        try {
            $subbanner = $this->service->update($id, $request);

            return redirect()->route(
                auth()->user()->hasRole('superadmin') ? 'superadmin.subbanner.list' :
                (auth()->user()->hasRole('admin') ? 'admin.subbanner.list' : 'client.subbanner.list')
            )->with('success', __('response.admin.Subbanner.update.success'));
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    /**
     * Delete banner.
     *
     * @param string $id
     * @return RedirectResponse
     */
    public function destroy(string $id): RedirectResponse
    {
        $this->service->delete($id);

        return redirect()->route(
            auth()->user()->hasRole('superadmin') ? 'superadmin.subbanner.list' :
            (auth()->user()->hasRole('admin') ? 'admin.subbanner.list' : 'client.subbanner.list')
        )->with(
            'success',
            __('response.admin.Subbanner.delete.success')
        );
    }
}
