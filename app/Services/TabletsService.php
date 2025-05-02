<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Collection;
use App\Interfaces\Services\StorageServiceInterface;
use App\Interfaces\Services\TabletsServiceInterface;
use App\Models\Tablet;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class TabletsService implements TabletsServiceInterface
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
        $tablets = Tablet::query();

        // If a search term is provided, filter by name
        if ($request->has('name') && $request->name !== '') {
            $tablets = $tablets->where('name', 'like', '%' . $request->name . '%');
        }
        $tablets = $tablets->paginate(
            config('settings.pagination.default_per_page')
        );

        return $tablets;
    }

    /**
     * Get all active Tablet.
     *
     * @return Collection
     */
    public function getAllActive(): Collection
    {
        $tablet = Tablet::active()->get();

        return $tablet;
    }

    /**
     * Create Tablet.
     *
     * @param Request $request
     * @return Tablet|null
        */
    public function create(Request $request): ?Tablet
    {

        return Tablet::firstOrCreate(
            ['name' => $request->input('name')], // Search for the existing project by name
            [
                'name' => $request->input('name'),
                'description' => $request->input('description'),
            ]
        );
    }

    /**
     * Get Tablet by ID.
     *
     * @param string $id
     * @return Tablet
     */
    public function findById(string $id): Tablet
    {
        return Tablet::findOrFail($id);
    }

    public function update(string $id, Request $request): Tablet
    {
        // Find the project or fail if it doesn't exist
        $tablet = Tablet::findOrFail($id);
        $requestData = $request->only('name', 'description', 'status');

        // Update the project with the new data
        $tablet->update($requestData);

        // Return the updated project
        return $tablet;
    }

    /**
     * Soft delete the category.
     *
     * @param string $id
     * @return boolean
     */
    public function delete(string $id): bool
    {
        $project = Tablet::find($id);

        return $project->delete();
    }

}
