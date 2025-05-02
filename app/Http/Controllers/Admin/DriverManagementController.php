<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Interfaces\Services\DriverServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class DriverManagementController extends Controller
{
    public function __construct(
        private readonly DriverServiceInterface $service
    ) {
    }

    /**
     * Category create view.
     *
     * @return View
     */
    public function new(): View
    {
        return view('admin.driver.new');
    }

    /**
     * drivers list view.
     *
     * @return View
     */
    public function list(Request $request): View
    {
        $drivers = $this->service->getAll($request);

        return view('admin.driver.list', compact('drivers'));
    }


    /**
     * Create Driver.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        $this->service->create($request);

        return redirect()->route(auth()->user()->hasRole('admin') ? 'admin.drivers.list' : 'superadmin.drivers.list')->with(
            'success',
            __('response.admin.driver.create.success')
        );
    }

    /**
     * Edit drivers view.
     *
     * @return void
     */
    public function edit(string $id): View
    {
        $drivers = $this->service->findById($id);

        return view('admin.driver.edit', compact('drivers'));
    }

    /**
     * Update drivers.
     *
     * @param Request $request
     * @param string $id
     * @return void
     */
    public function update(Request $request, string $id)
    {
        $driver = $this->service->findById($id);
        $userId = $driver->user_id;
        $request->validate([
             'name' => 'required|string|max:255',
             'email' => 'required|email|unique:users,email,' . $userId,
             'password' => 'nullable|string|min:4',
             'is_active' => 'required|boolean',
             'driver_ic_number' => 'required|string|max:155',
             'driver_car_plate' => 'required|string|max:155',
         ]);
        $this->service->update($id, $request);


        return redirect()->route(auth()->user()->hasRole('admin') ? 'admin.drivers.list' : 'superadmin.drivers.list')->with(
            'success',
            __('response.admin.driver.update.success')
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
            return redirect()->route('admin.drivers.list')->with(
                'error',
                __('response.admin.driver.delete.fail')
            );
        }

        return redirect()->route(auth()->user()->hasRole('admin') ? 'admin.drivers.list' : 'superadmin.drivers.list')->with(
            'success',
            __('response.admin.driver.delete.success')
        );
    }
}
