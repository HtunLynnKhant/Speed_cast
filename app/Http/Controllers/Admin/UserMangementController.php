<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Interfaces\Services\UserServiceInterface;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class UserMangementController extends Controller
{
    public function __construct(
        private readonly UserServiceInterface $service,
    ) {
    }

    /**
     * user list view
     *
     * @return View
     */
    public function list(Request $request): View
    {
        $users = $this->service->getAll($request);
        if ($request->ajax()) {
            return view('admin.user.partials.table_rows', compact('users'));
        }

        return view('admin.user.list', compact('users'));
    }

    /**
     * create user view.
     *
     * @return View
     */
    public function new(): View
    {
        return view('admin.user.new');
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
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
            'role' => 'required|in:admin',
        ]);
        $this->service->create($request);

        return redirect()->route(auth()->user()->hasRole('admin') ? 'admin.user.list' : 'superadmin.user.list')->with('success', __('response.admin.user.create.success'));

    }

    public function edit(User $user): View
    {
        return view('admin.user.edit', compact('user'));
    }

    /**
     * Update user information.
     *
     * @param Request $request
     * @param User $user
     * @return RedirectResponse
     */
    public function update(Request $request, User $user): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|min:8',
            'role' => 'required|in:superadmin,admin,client,driver',
        ]);

        $this->service->update($user, $request);

        return redirect()->route(auth()->user()->hasRole('admin') ? 'admin.user.list' : 'superadmin.user.list')
                        ->with('success', __('response.admin.user.update.success'));
    }


    /**
     * Delete a user.
     *
     * @param User $user
     * @return RedirectResponse
     */
    public function destroy(User $user): RedirectResponse
    {
        $this->service->delete($user);

        return redirect()->route(auth()->user()->hasRole('admin') ? 'admin.user.list' : 'superadmin.user.list')
                         ->with('success', __('response.admin.user.delete.success'));
    }
}
