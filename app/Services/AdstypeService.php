<?php

namespace App\Services;

use App\Interfaces\Services\AdstypeServiceInterface;
use App\Models\AdsType;
use Illuminate\Http\Request;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class AdstypeService implements AdstypeServiceInterface
{
    /**
     * Get all active ad types.
     *
     * @return Collection
     */
    public function getAllActive(): Collection
    {
        $adstype = AdsType::active()->get();

        return $adstype;
    }

    /**
     * Get all ad types with pagination and optional filters.
     *
     * @param Request $request
     * @return LengthAwarePaginator
     */
    public function getAll(Request $request): LengthAwarePaginator
    {
        $query = AdsType::query();

        // Filter by name if provided
        if ($request->has('name') && $request->name !== '') {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        // Paginate the results
        return $query->paginate(config('settings.pagination.default_per_page'));
    }
}
