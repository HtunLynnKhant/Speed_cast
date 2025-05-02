<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;
use App\Interfaces\Services\CompanyServiceInterface;

class CompanyController extends Controller
{
    public function __construct(
        private readonly CompanyServiceInterface $service
    ) {
    }

    /**
     * Action for retrieving company list.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $companies = $this->service->getAll();
        if ($companies->isEmpty()) {
            return Response::failed(__('response.failed.not_found'), 404);
        }

        return Response::success($companies);
    }
}
