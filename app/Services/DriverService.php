<?php

namespace App\Services;

use App\Enums\ContentFolders;
use App\Interfaces\Services\DriverServiceInterface;
use App\Models\Driver;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Collection;
use App\Interfaces\Services\StorageServiceInterface;
use App\Models\Client;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Log;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class DriverService implements DriverServiceInterface
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
        $drivers = Driver::query();

        // If a search term is provided, filter by name
        if ($request->has('driver') && $request->driver !== '') {
            $drivers = $drivers->join('users', 'drivers.user_id', '=', 'users.id')
                                ->where('users.role', 'driver')
                                ->where('users.name', 'like', '%' . $request->driver . '%');
        } else {
            // Join the users table without the filter if no driver search term is provided
            $drivers = $drivers->join('users', 'drivers.user_id', '=', 'users.id');
        }

        // Select all columns from drivers and the 'name' column from users as 'user_name'
        $drivers = $drivers->select('drivers.*', 'users.name as user_name', 'users.is_active', 'users.email')
                            ->paginate(config('settings.pagination.default_per_page'));

        return $drivers;
    }

    /**
     * Get all active Driver.
     *
     * @return Collection
     */
    public function getAllActive(): Collection
    {
        $drivers = Driver::active()->get();

        return $drivers;
    }

    /**
     * Get driver by ID.
     *
     * @param string $id
     * @return driver
     */
    public function findById(string $id): Driver
    {
        return Driver::findOrFail($id);
    }

    /**
     * Create Driver.
     *
     * @param Request $request
     * @return Driver|null
     */
    public function create(Request $request): ?Driver
    {
        // Validate request
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:5',
            'driver_ic_number' => 'required|string|max:155',
            'driver_car_plate' => 'required|string|max:155',
            'drivers_license' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'drivers_car_license' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ]);

        DB::beginTransaction();

        try {
            // Create User
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => bcrypt($validated['password']),
                'role' => 'driver',
            ]);
            // Store files in storage
            $drivers_license = $this->storage->put(ContentFolders::Driver, $request->file('drivers_license'));
            $drivers_car_license = $this->storage->put(ContentFolders::Driver, $request->file('drivers_car_license'));

            // Create Driver Profile
            $driver = Driver::create([
                'user_id' => $user->id,
                'driver_ic_number' => $request->input('driver_ic_number'),
                'driver_car_plate' => $request->input('driver_car_plate'),
                'drivers_license' => $drivers_license,
                'drivers_car_license' => $drivers_car_license,
            ]);

            DB::commit();

            return $driver;
        } catch (\Exception $e) {
            DB::rollBack();

            throw $e;
        }
    }

    public function update(string $id, Request $request): Driver
    {
        try {
            // Find the driver and associated user
            $driver = Driver::findOrFail($id);
            $user = $driver->user;

            // Get only required request data
            $requestData = $request->only([
                'driver_name',
                'driver_ic_number',
                'driver_car_plate',
                'status'
            ]);

            // Handle file updates (only delete old ones if new files are uploaded)
            if ($request->hasFile('drivers_license')) {
                $this->storage->delete($driver->drivers_license); // Delete old file
                $requestData['drivers_license'] = $this->storage->put(ContentFolders::Driver, $request->file('drivers_license'));
            }

            if ($request->hasFile('drivers_car_license')) {
                $this->storage->delete($driver->drivers_car_license); // Delete old file
                $requestData['drivers_car_license'] = $this->storage->put(ContentFolders::Driver, $request->file('drivers_car_license'));
            }

            // Check for dirty fields and update only if necessary
            if ($driver->fill($requestData)->isDirty()) {
                $driver->update($requestData);
            }

            // Prepare user update data
            $userUpdateData = [
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'is_active' => $request->input('is_active'),
            ];

            // Update password if provided
            if (!empty($request->input('password'))) {
                $userUpdateData['password'] = Hash::make($request->input('password'));
            }

            // Check for dirty fields and update only if necessary
            if ($user->fill($userUpdateData)->isDirty()) {
                $user->update($userUpdateData);
            }

            return $driver;
        } catch (ModelNotFoundException $e) {
            Log::error('Driver not found: ' . $e->getMessage());

            throw $e;
        } catch (\Exception $e) {
            Log::error('Error updating driver: ' . $e->getMessage());

            throw $e;
        }
    }


    /**
     * Soft delete the Driver.
     *
     * @param string $id
     * @return boolean
     */
    public function delete(string $id): bool
    {
        try {
            $drivers = Driver::find($id);

            if (!$drivers) {
                return false;
            }
            $user = $drivers->user;
            if ($user) {
                $user->delete();
            }

            // Finally, delete the client
            return $drivers->delete();

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
