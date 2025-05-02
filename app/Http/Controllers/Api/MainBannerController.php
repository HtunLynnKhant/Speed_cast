<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use App\Http\Resources\BannerCollection;
use App\Interfaces\Services\BannerServiceInterface;
use App\Interfaces\Services\SubbannerServiceInterface;

class MainBannerController extends Controller
{
    public function __construct(
        private readonly BannerServiceInterface $service,
        private readonly SubbannerServiceInterface $subbannerservice
    ) {
    }

    public function index(): JsonResponse
    {
        $banners = $this->service->getAllActive();
        $subbanners = $this->subbannerservice->getAllActive();

        $combined = $banners->merge($subbanners);

        // Return JSON response
        return response()->json(new BannerCollection($combined));
    }
}
