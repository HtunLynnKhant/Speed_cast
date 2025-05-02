<?php

namespace App\Services;

use App\Interfaces\Services\ClientServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Collection;
use App\Interfaces\Services\StorageServiceInterface;
use App\Models\Client;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ClientService implements ClientServiceInterface
{
    public function __construct(
        private readonly StorageServiceInterface $storage
    ) {

    }

    /**
     * Get all drivers including inactive.
     *
     * @return Collection
     */
    public function getAll(Request $request): LengthAwarePaginator
    {
        $clients = Client::query();

        // If a client search term is provided, apply the filter on the user's name
        if ($request->has('client') && $request->client !== '') {
            $clients = $clients->join('users', 'clients.user_id', '=', 'users.id')
                                ->where('users.role', 'client')
                                ->where('users.name', 'like', '%' . $request->client . '%');
        } else {
            // Join the users table without the filter if no client search term is provided
            $clients = $clients->join('users', 'clients.user_id', '=', 'users.id');
        }

        // Select all columns from clients and the 'name' column from users as 'user_name'
        $clients = $clients->select('clients.*', 'users.name as user_name', 'users.is_active', 'users.email')
                            ->paginate(config('settings.pagination.default_per_page'));

        return $clients;
    }

    public function create(Request $request): ?Client
    {
        // Validate the incoming request data
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:5|confirmed',
            'company_name' => 'required|string|max:255',
            'registration_number' => 'required|string|max:255',
            'allow_type_of_ads_set' => 'nullable|array', // Allow null if no ads settings
        ]);

        // Start a transaction
        DB::beginTransaction();

        try {
            // Hash the password and create the user
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => bcrypt($validated['password']),
                'role' => 'client', // Assuming you have a 'role' column for the user
            ]);

            // Ensure ads settings is an array and encode it to JSON format, or set to null if not selected
            $adsSettings = isset($validated['allow_type_of_ads_set']) && is_array($validated['allow_type_of_ads_set'])
                            ? json_encode($validated['allow_type_of_ads_set'])
                            : null;

            // Create the client
            $client = Client::create([
                'user_id' => $user->id,
                'company_name' => $validated['company_name'],
                'registration_number' => $validated['registration_number'],
                'allow_type_of_ads_set' => $adsSettings,
            ]);

            // Commit the transaction
            DB::commit();

            return $client;
        } catch (\Exception $e) {
            // Rollback the transaction in case of error
            DB::rollBack();

            throw $e;
        }
    }

    /**
     * Get client by ID.
     *
     * @param string $id
     * @return driver
     */
    public function findById(string $id): Client
    {
        return Client::findOrFail($id);
    }

    /**
     * Update client details.
     *
     * @param array $data
     * @param string $id
     * @return bool
     */
    public function update(array $data, string $id): bool
    {
        try {
            // Retrieve the client by ID
            $client = Client::findOrFail($id);

            // Update the client-related data (e.g., company name, registration number)
            $client->update([
                'company_name' => $data['company_name'],
                'registration_number' => $data['registration_number'],
                'allow_type_of_ads_set' => isset($data['allow_type_of_ads_set']) ? json_encode($data['allow_type_of_ads_set']) : null,
            ]);

            // Prepare the user data to update
            $userUpdateData = [
                'name' => $data['name'],
                'email' => $data['email'],
                'is_active' => $data['is_active'],
            ];

            // Update password if it's provided
            if (!empty($data['password'])) {
                $userUpdateData['password'] = Hash::make($data['password']);
            }

            // Update the related user's data
            $client->user->update($userUpdateData);

            return true;

        } catch (ModelNotFoundException $e) {
            // Log the exception message for not found
            Log::error('Client not found: ' . $e->getMessage());

            return false; // or handle it as per your application's error handling strategy

        } catch (\Exception $e) {
            // Log any other exceptions
            Log::error('Error updating client: ' . $e->getMessage());

            return false; // or handle it as per your application's error handling strategy
        }
    }

    public function delete(string $id): bool
    {
        try {
            // Find the client by id
            $client = Client::find($id);

            // Check if client exists
            if (!$client) {
                return false; // Client not found
            }

            $user = $client->user;

            if ($user) {
                $user->delete();  // Delete the related user
            }

            // Finally, delete the client
            return $client->delete();

        } catch (ModelNotFoundException $e) {
            // Log the exception message for not found
            Log::error('Client not found: ' . $e->getMessage());

            return false;

        } catch (\Exception $e) {
            // Log any other exceptions
            Log::error('Error deleting client: ' . $e->getMessage());

            return false;
        }
    }
}
