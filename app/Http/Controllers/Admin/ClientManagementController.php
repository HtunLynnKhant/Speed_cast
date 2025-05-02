<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Interfaces\Services\ClientServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class ClientManagementController extends Controller
{
    public function __construct(
        private readonly ClientServiceInterface $service
    ) {
    }

    /**
     * client list view.
     *
     * @return View
     */
    public function list(Request $request): View
    {
        $clients = $this->service->getAll($request);


        return view('admin.client.list', compact('clients'));
    }

    /**
     * client create view.
     *
     * @return View
     */
    public function new(): View
    {
        return view('admin.client.new');
    }

    /**
     * Create clients.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        $this->service->create($request);

        return redirect()->route(auth()->user()->hasRole('admin') ? 'admin.clients.list' : 'superadmin.clients.list')->with(
            'success',
            __('response.admin.client.create.success')
        );
    }

    /**
     * Edit drivers view.
     *
     * @return void
     */
    public function edit(string $id): View
    {
        $clients = $this->service->findById($id);
        $allowedAds = json_decode($clients->allow_type_of_ads_set ?? '[]', true); // Decode the allow_type_of_ads_set

        return view('admin.client.edit', compact('clients', 'allowedAds'));
    }

    /**
     * Update client details.
     *
     * @param Request $request
     * @param string $id
     * @return RedirectResponse
     */
    public function update(Request $request, string $id): RedirectResponse
    {
        $client = $this->service->findById($id);
        $userId = $client->user_id;
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $userId,
            'company_name' => 'required|string|max:255',
            'registration_number' => 'required|string|max:255',
            'allow_type_of_ads_set' => 'required|array',
            'password' => 'nullable|string|min:4',
            'is_active' => 'required|boolean',
        ]);

        $this->service->update($validated, $id); // Use service for updating logic

        return redirect()->route(auth()->user()->hasRole('admin') ? 'admin.clients.list' : 'superadmin.clients.list')->with(
            'success',
            __('response.admin.client.update.success')
        );
    }

    public function destroy(string $id): RedirectResponse
    {
        $isDeleted = $this->service->delete($id);
        if (!$isDeleted) {
            return redirect()->route('admin.clients.list')->with(
                'error',
                __('response.admin.client.delete.fail')
            );
        }

        return redirect()->route(auth()->user()->hasRole('admin') ? 'admin.clients.list' : 'superadmin.clients.list')->with(
            'success',
            __('response.admin.client.delete.success')
        );
    }

}
