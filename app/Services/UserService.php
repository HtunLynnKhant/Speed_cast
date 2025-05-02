<?php

namespace App\Services;

use App\Interfaces\Services\UserServiceInterface;
use App\Models\User;
use Illuminate\Http\Request;
use App\Interfaces\Services\StorageServiceInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Exception;

class UserService implements UserServiceInterface
{
    public function __construct(
        private readonly StorageServiceInterface $storage
    ) {
    }

    /**
     * Get all users, paginated.
     *
     * @return LengthAwarePaginator
     */
    public function getAll(Request $request): LengthAwarePaginator
    {
        $users = User::query();

        $users->where('role', 'admin');

        // If a search term is provided, filter by username
        if ($request->has('name') && $request->name !== '') {
            $users->where('name', 'like', '%' . $request->name . '%');
        }

        $users = $users->paginate(config('settings.pagination.default_per_page'));

        return $users;
    }

    /**
     * Create user.
     *
     * @param Request $request
     * @return User|null
        */
    public function create(Request $request): ?User
    {

        return User::firstOrCreate(
            [
                'name' => $request->input('name')
            ],
            [
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'password' => Hash::make($request->input('password')), // Hash the password before saving
                'role' => $request->input('role', 'admin'), // Default to 'admin' if no role is provided
                'is_active' => $request->input('is_active', 1),
            ]
        );
    }


    public function update(User $user, Request $request): ?User
    {
        try {
            $user->name = $request->input('name');
            $user->email = $request->input('email');

            if ($request->filled('password')) {
                $user->password = Hash::make($request->input('password'));
            }

            $user->role = $request->input('role');
            $user->save();

            return $user;

        } catch (ModelNotFoundException $e) {
            // Log the exception message for not found
            Log::error('User  not found: ' . $e->getMessage());

            return null;

        } catch (Exception $e) {
            // Log any other exceptions
            Log::error('Error updating user: ' . $e->getMessage());

            return null;
        }
    }

    public function delete(User $user): bool
    {
        return $user->delete();
    }
}
