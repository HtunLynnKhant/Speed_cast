<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Response;
use App\Services\AdsService;
use App\Services\PageService;
use App\Services\BannerService;
use App\Services\AdstypeService;
use App\Services\CompanyService;
use App\Services\ContentService;
use App\Services\PaymentService;
use App\Services\ProjectService;
use App\Services\StorageService;
use App\Services\CategoryService;
use App\Services\CurrencyService;
use App\Services\SubscriptionService;
use App\Services\UserService;
use App\Interfaces\Services\AdsServiceInterface;
use App\Interfaces\Services\PageServiceInterface;
use App\Interfaces\Services\BannerServiceInterface;
use App\Interfaces\Services\AdstypeServiceInterface;
use App\Interfaces\Services\CompanyServiceInterface;
use App\Interfaces\Services\ContentServiceInterface;
use App\Interfaces\Services\PaymentServiceInterface;
use App\Interfaces\Services\ProjectServiceInterface;
use App\Interfaces\Services\StorageServiceInterface;
use App\Interfaces\Services\CategoryServiceInterface;
use App\Interfaces\Services\CurrenciesServiceInterface;
use App\Interfaces\Services\SubscriptionServiceInterface;
use App\Interfaces\Services\TabletsServiceInterface;
use App\Interfaces\Services\UserServiceInterface;
use App\Services\TabletsService;
use App\Interfaces\Services\DriverServiceInterface;
use App\Services\DriverService;
use App\Interfaces\Services\ClientServiceInterface;
use App\Services\ClientService;
use App\Interfaces\Services\SubbannerServiceInterface;
use App\Services\SubbannerService;
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        /** Bind services */
        $this->app->bind(AdsServiceInterface::class, AdsService::class);
        $this->app->bind(PageServiceInterface::class, PageService::class);
        $this->app->bind(BannerServiceInterface::class, BannerService::class);
        $this->app->bind(AdstypeServiceInterface::class, AdstypeService::class);
        $this->app->bind(ProjectServiceInterface::class, ProjectService::class);
        $this->app->bind(CompanyServiceInterface::class, CompanyService::class);
        $this->app->bind(StorageServiceInterface::class, StorageService::class);
        $this->app->bind(ContentServiceInterface::class, ContentService::class);
        $this->app->bind(PaymentServiceInterface::class, PaymentService::class);
        $this->app->bind(CategoryServiceInterface::class, CategoryService::class);
        $this->app->bind(CurrenciesServiceInterface::class, CurrencyService::class);
        $this->app->bind(SubscriptionServiceInterface::class, SubscriptionService::class);
        $this->app->bind(UserServiceInterface::class, UserService::class);
        $this->app->bind(TabletsServiceInterface::class, TabletsService::class);
        $this->app->bind(DriverServiceInterface::class, DriverService::class);
        $this->app->bind(ClientServiceInterface::class, ClientService::class);
        $this->app->bind(SubbannerServiceInterface::class, SubbannerService::class);
    }

    public function boot(): void
    {

        if (!app()->environment('local') && isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') {
            URL::forceScheme('https'); // Force HTTPS when available
        } else {
            $this->app['request']->server->set('HTTPS', 'off'); // Set HTTP fallback
        }
        /** Success response macro */
        Response::macro('success', function (mixed $data) {
            return Response::json([
                'success' => true,
                'data' => $data,
                'error' => null
            ], 200);
        });

        /** Failed response macro */
        Response::macro('failed', function (string $errMsg, int $status = 500) {
            return Response::json([
                'success' => false,
                'data' => null,
                'error' => $errMsg
            ], $status);
        });

    }
}
