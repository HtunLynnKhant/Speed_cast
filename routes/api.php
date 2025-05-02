<?php

use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Api\AdsController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\BannerController;
use App\Http\Controllers\Api\MainBannerController;
use App\Http\Controllers\Api\CompanyController;
use App\Http\Controllers\Api\CategoryController;

Route::as('api.v1')
    ->prefix('v1')
    ->group(function () {

        Route::post('test', [AdminCategoryController::class, 'store']);


        /** Company routes */
        Route::controller(CompanyController::class)
            ->as('.companies')
            ->prefix('companies')
            ->group(function () {
                Route::get('/', 'index');
            });

        /** Banner routes */
        Route::controller(BannerController::class)
            ->as('.banner')
            ->prefix('banners')
            ->group(function () {
                Route::get('/', 'index')->name('.list');
                Route::post('/upload', 'upload')->name('.upload');
            });

        /** Category routes */
        Route::controller(CategoryController::class)
            ->as('.categories')
            ->prefix('categories')
            ->group(function () {
                Route::get('/', 'index')->name('.list');
                Route::get('/{id}/banners', 'getBanners')->name('.banners');
                Route::get('/{id}/companies', 'getCompanies')->name('.companies');
            });

        /** Ads routes */
        Route::controller(AdsController::class)
            ->as('.ads')
            ->prefix('ads')
            ->group(function () {
                Route::get('/', 'index')->name('.list');
            });

        Route::controller(MainBannerController::class)
        ->as('.bannercollectinon')
        ->prefix('bannercollection')
        ->group(function () {
            Route::get('/', 'index')->name('.list');
        });

    });
