<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;
use App\Http\Resources\BannerResourceCollection;
use App\Interfaces\Services\BannerServiceInterface;

class BannerController extends Controller
{
    public function __construct(
        private readonly BannerServiceInterface $service
    ) {
    }

    /**
     * Action for retrieving banner list.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $banners = $this->service->getAllActive();
        if ($banners->isEmpty()) {
            return Response::failed(__('response.failed.not_found'), 404);
        }

        return Response::success(new BannerResourceCollection($banners));
    }
}
