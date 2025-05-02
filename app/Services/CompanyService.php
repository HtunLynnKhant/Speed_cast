<?php

namespace App\Services;

use App\Models\Company;
use Illuminate\Http\Request;
use App\Enums\ContentFolders;
use Illuminate\Database\Eloquent\Collection;
use App\Interfaces\Services\CompanyServiceInterface;
use App\Interfaces\Services\StorageServiceInterface;

class CompanyService implements CompanyServiceInterface
{
    public function __construct(
        private readonly StorageServiceInterface $storage
    ) {
    }

    public function getAll(): Collection
    {
        $companies = Company::active()->get();

        return $companies;
    }

    public function create(Request $request): ?Company
    {
        $logoPath = $this->storage->put(ContentFolders::LOGO, $request->file('logo'));

        return Company::firstOrCreate(['name' => $request->input('name')], [
            'name' => $request->input('name'),
            'logo' => $logoPath,
            'category_id' => $request->input('category_id', 0),
            'description' => $request->input('description', '')
        ]);
    }
}
