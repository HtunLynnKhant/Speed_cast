<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;
use App\Http\Resources\BannerResourceCollection;
use App\Interfaces\Services\CategoryServiceInterface;

class CategoryController extends Controller
{
    public function __construct(
        private readonly CategoryServiceInterface $service
    ) {
    }

    /**
     * Action for category list.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $categories = $this->service->getAllActive();
        if ($categories->isEmpty()) {
            return Response::failed(__('response.failed.not_found'), 404);
        }

        return Response::success($categories);
    }

    /**
     * Action for getting banners with categoryID.
     *
     * @param string $id
     * @return JsonResponse
     */
    public function getBanners(string $id): JsonResponse
    {
        $banners = $this->service->getBannersById($id);
        if ($banners->isEmpty()) {
            return Response::failed(__('response.failed.not_found'), 404);
        }

        return Response::success(new BannerResourceCollection($banners));
    }

    /**
     * Action for getting companies by categoryID.
     *
     * @param string $id
     * @return JsonResponse
     */
    public function getCompanies(string $id): JsonResponse
    {
        $companies = $this->service->getCompaniesById($id);
        if ($companies->isEmpty()) {
            return Response::failed(__('response.failed.not_found'), 404);
        }

        return Response::success($companies);
    }
}
