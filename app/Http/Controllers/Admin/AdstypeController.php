<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;
use App\Http\Controllers\Controller;
use App\Interfaces\Services\AdstypeServiceInterface;

class AdstypeController extends Controller
{
    public function __construct(
        private readonly AdstypeServiceInterface $service
    ) {
    }

    /**
     * Category list view.
     *
     * @return View
     */
    public function list(Request $request): View
    {
        $adstypes = $this->service->getAll($request);

        return view('admin.adstype.list', compact('adstypes'));
    }
}
