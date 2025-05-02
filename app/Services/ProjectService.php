<?php

namespace App\Services;

use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Collection;
use App\Interfaces\Services\ProjectServiceInterface;
use App\Interfaces\Services\StorageServiceInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Log;

class ProjectService implements ProjectServiceInterface
{
    public function __construct(
        private readonly StorageServiceInterface $storage
    ) {
    }

    /**
     * Get all projects including inactive.
     *
     * @return Collection
     */
    public function getAll(Request $request): LengthAwarePaginator
    {
        $projects = Project::query();

        // If a search term is provided, filter by title
        if ($request->has('name') && $request->name !== '') {
            $projects = $projects->where('name', 'like', '%' . $request->name . '%');
        }

        //sort function
        $sortableColumn = ['name','status', 'created_at', 'updated_at'];
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');

        if (!in_array($sortBy, $sortableColumn)) {
            $sortBy = 'created_at';
        }
        if (!in_array($sortOrder, ['asc','desc'])) {
            $sortOrder = 'desc';
        }
        // Apply sorting
        $projects->orderBy($sortBy, $sortOrder);

        // Paginate and preserve sorting
        return $projects->paginate(config('settings.pagination.default_per_page'))
                        ->appends([
                            'sort_by' => $sortBy,
                            'sort_order' => $sortOrder
                        ]);
    }

    /**
     * Get all active projects.
     *
     * @return Collection
     */
    public function getAllActive(): Collection
    {
        $projects = Project::active()->get();

        return $projects;
    }

    /**
     * Create projects.
     *
     * @param Request $request
     * @return Project|null
        */
    public function create(Request $request): ?Project
    {
        try {
            return Project::firstOrCreate(
                ['name' => $request->input('name')],
                [
                    'name' => $request->input('name'),
                    'description' => $request->input('description'),
                ]
            );
        } catch (\Exception $e) {
            // Log the error for debugging
            Log::error('Project creation failed: ' . $e->getMessage());

            return null;
        }
    }

    /**
     * Get Project by ID.
     *
     * @param string $id
     * @return Project
     */
    public function findById(string $id): Project
    {
        return Project::findOrFail($id);
    }

    public function update(string $id, Request $request): Project
    {
        try {
            // Find the project or fail if it doesn't exist
            $project = Project::findOrFail($id);
            $requestData = $request->only('name', 'description', 'status');

            // Update the project with the new data
            $project->update($requestData);

            // Return the updated project
            return $project;
        } catch (\Exception $e) {
            // Log the error for debugging
            Log::error('Project update failed: ' . $e->getMessage());

            return null;
        }
    }

    /**
     * Soft delete the category.
     *
     * @param string $id
     * @return boolean
     */
    public function delete(string $id): bool
    {
        $project = Project::find($id);

        return $project->delete();
    }
}
