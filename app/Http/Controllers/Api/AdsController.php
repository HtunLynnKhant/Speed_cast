<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;
use App\Http\Resources\AdsResourceCollection;
use App\Interfaces\Services\AdsServiceInterface;

class AdsController extends Controller
{
    public function __construct(
        private readonly AdsServiceInterface $service
    ) {
    }

    /**
     * Retrieve all active ads.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $ads = $this->service->getAllActive();

        return Response::success(new AdsResourceCollection($ads));
    }
}
