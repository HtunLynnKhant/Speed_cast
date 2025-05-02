<?php

use App\Http\Controllers\Admin\AdsController;
use App\Http\Controllers\Admin\BannerController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\AdstypeController;
use App\Http\Controllers\Admin\ClientManagementController;
use App\Http\Controllers\Admin\PageController;
use App\Http\Controllers\Admin\PagesController;
use App\Http\Controllers\Admin\ProjectController;
use App\Http\Controllers\Admin\TabletsController;
use App\Http\Controllers\Admin\UserMangementController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\SuperAdminLoginController;
use App\Http\Controllers\Admin\DriverManagementController;
use App\Http\Controllers\Admin\SubbannerController;
use App\Http\Controllers\Auth\ClientLoginController;
use App\Http\Controllers\Auth\DriverLoginController;
use App\Http\Middleware\SuperAdmin;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\RoleBasedRoute;

// Public Routes
Route::get('/admin/', [LoginController::class, 'showLogin'])->name('login');
Route::post('/admin/', [LoginController::class, 'postLogin'])->name('login.post');

Route::get('/superadmin/', [SuperAdminLoginController::class, 'showLogin'])->name('superadminlogin');
Route::post('/superadmin/', [SuperAdminLoginController::class, 'postLogin'])->name('superadminlogin.post');

Route::get('/client/', [ClientLoginController::class, 'showLogin'])->name('clientlogin');
Route::post('/client/', [ClientLoginController::class, 'postLogin'])->name('clientlogin.post');

Route::get('/driver/', [DriverLoginController::class, 'showLogin'])->name('driverlogin');
Route::post('/driver/', [DriverLoginController::class, 'postLogin'])->name('driverlogin.post');
// Admin and SuperAdmin Routes with dynamic prefix based on role
Route::middleware([RoleBasedRoute::class])->group(function () {
    // Admin routes (will use admin prefix if role is 'admin')
    Route::prefix('admin')
        ->as('admin.')  // You can customize this middleware for Admin logic
        ->group(function () {
            // Authentication and Logout
            Route::post('logout', [LoginController::class, 'logout'])->name('logout');
            Route::get('dashboard', [PageController::class, 'dashboard'])->name('dashboard');

            // Banner routes
            Route::prefix('banner')
            ->as('banner.')
            ->controller(BannerController::class)
            ->group(function () {
                Route::get('/', 'list')->name('list');
                Route::get('/edit/{id}', 'edit')->name('edit');
                Route::get('/add-new', 'new')->name('add-new');
                Route::post('/store', 'store')->name('store');
                Route::put('/{id}', 'update')->name('update');
                Route::delete('/delete/{id}', 'destroy')->name('delete');
                Route::get('/search', 'search')->name('search');
            });

            // subBanner routes
            Route::prefix('subbanner')
            ->as('subbanner.')
            ->controller(SubbannerController::class)
            ->group(function () {
                Route::get('/', 'list')->name('list');
                Route::get('/edit/{id}', 'edit')->name('edit');
                Route::get('/add-new', 'new')->name('add-new');
                Route::post('/store', 'store')->name('store');
                Route::put('/{id}', 'update')->name('update');
                Route::delete('/delete/{id}', 'destroy')->name('delete');
                Route::get('/search', 'search')->name('search');
            });

            // Category routes
            Route::prefix('category')
            ->as('category.')
            ->controller(CategoryController::class)
            ->group(function () {
                Route::get('/', 'list')->name('list');
                Route::get('/edit/{id}', 'edit')->name('edit');
                Route::get('/add-new', 'new')->name('add-new');
                Route::post('/store', 'store')->name('store');
                Route::put('/{id}', 'update')->name('update');
                Route::delete('/delete/{id}', 'destroy')->name('delete');
                Route::get('/search', 'search')->name('search');
            });

            Route::prefix('category/page')
            ->as('category.page')
            ->controller(PagesController::class)
            ->group(function () {
                Route::get('/add-new-page/{categoryId}', 'addpage')->name('.addpage');
                Route::post('/store-page', 'store_Page')->name('.storepage');
                Route::get('/edit/{id}', 'edit')->name('.edit');
                Route::put('/{id}', 'update')->name('.update');
                Route::delete('/delete/{id}', 'destroy')->name('.delete');
                Route::get('/search', 'search')->name('search');

            });
            Route::get('/types', [AdstypeController::class,'list'])->name('type.list');
            // project routes
            Route::prefix('project')
                ->as('project.')
                ->controller(ProjectController::class)
                ->group(function () {
                    Route::get('/', 'list')->name('list');
                    Route::get('/edit/{id}', 'edit')->name('edit');
                    Route::get('/add-new', 'new')->name('add-new');
                    Route::post('/store', 'store')->name('store');
                    Route::put('/{id}', 'update')->name('update');
                    Route::delete('/delete/{id}', 'destroy')->name('delete');
                    Route::get('/search', 'search')->name('search');
                });

            Route::prefix('ads') // Add the prefix here
            ->as('ads.') // Set the route name prefix
            ->controller(AdsController::class) // Specify the controller
            ->group(function () {
                Route::get('/add-new', 'new')->name('add-new');
                Route::post('/store', 'store')->name('store');
                Route::get('/', 'list')->name('list'); // List ads
                Route::get('/edit/{id}', 'edit')->name('edit'); // Edit ad
                Route::put('/{id}', 'update')->name('update'); // Update ad
                Route::delete('/delete/{id}', 'destroy')->name('delete'); // Delete ad
                Route::get('/detail/{id}', 'detail')->name('detail');
                Route::get('/search', 'search')->name('search');

                Route::put('/update_approval/{id}', 'updateapprove')->name('updateapprove');

                Route::get('/edit/{id}/payment', 'viewPayment')->name('view.payment');
                Route::post('/edit/{id}/payment/store', 'storePayment')->name('payment.store');
                Route::get('/edit/{id}/payment/{paymentId}', 'editPayment')->name('payment.edit');
                Route::put('/edit/{id}/payment/{paymentId}', 'updatePayment')->name('payment.update');
            });
            // In your web.php routes file

            Route::get('/get-pages/{categoryId}', [AdsController::class, 'getPagesByCategory'])->name('get.pages.by.category');



            // User Management Routes
            // Route::prefix('user')
            //     ->as('user.')
            //     ->controller(UserMangementController::class)
            //     ->group(function () {
            //         Route::get('/', 'list')->name('list');
            //         Route::get('/edit/{user}', 'edit')->name('edit');
            //         Route::get('/add-new', 'new')->name('add-new');
            //         Route::post('/store', 'store')->name('store');
            //         Route::put('/{user}', 'update')->name('update');
            //         Route::delete('/delete/{user}', 'destroy')->name('delete');
            //         Route::get('/search', 'search')->name('search');
            //     });

            // tables Management Routes
            Route::prefix('tablets')
            ->as('tablets.')
            ->controller(TabletsController::class)
            ->group(function () {
                Route::get('/', 'list')->name('list');
                Route::get('/edit/{id}', 'edit')->name('edit');
                Route::get('/add-new', 'new')->name('add-new');
                Route::post('/store', 'store')->name('store');
                Route::put('/{id}', 'update')->name('update');
                Route::delete('/delete/{id}', 'destroy')->name('delete');
                Route::get('/search', 'search')->name('search');
            });

            Route::prefix('drivers')
            ->as('drivers.')
            ->controller(DriverManagementController::class)
            ->group(function () {
                Route::get('/', 'list')->name('list');
                Route::get('/edit/{id}', 'edit')->name('edit');
                Route::get('/add-new', 'new')->name('add-new');
                Route::post('/store', 'store')->name('store');
                Route::put('/{id}', 'update')->name('update');
                Route::delete('/delete/{id}', 'destroy')->name('delete');
                Route::get('/search', 'search')->name('search');
            });

            //driver routers
            Route::prefix('clients')
            ->as('clients.')
            ->controller(ClientManagementController::class)
            ->group(function () {
                Route::get('/', 'list')->name('list');
                Route::get('/edit/{id}', 'edit')->name('edit');
                Route::get('/add-new', 'new')->name('add-new');
                Route::post('/store', 'store')->name('store');
                Route::put('/{id}', 'update')->name('update');
                Route::delete('/delete/{id}', 'destroy')->name('delete');
                Route::get('/search', 'search')->name('search');
            });
        });

    /////////////////////////////////////////////////
    // SuperAdmin routes (will use superadmin prefix if role is 'superadmin')
    Route::prefix('superadmin')
        ->as('superadmin.') // You can customize this middleware for SuperAdmin logic
        ->group(function () {
            // SuperAdmin routes can be similar to Admin but with more privileges
            Route::post('logout', [LoginController::class, 'sadminlogout'])->name('sadminlogout');
            Route::get('dashboard', [PageController::class, 'dashboard'])->name('dashboard');

            // SuperAdmin-specific routes go here...
            Route::prefix('banner')
            ->as('banner.')
            ->controller(BannerController::class)
            ->group(function () {
                Route::get('/', 'list')->name('list');
                Route::get('/edit/{id}', 'edit')->name('edit');
                Route::get('/add-new', 'new')->name('add-new');
                Route::post('/store', 'store')->name('store');
                Route::put('/{id}', 'update')->name('update');
                Route::delete('/delete/{id}', 'destroy')->name('delete');
                Route::get('/search', 'search')->name('search');
            });

            // subBanner routes
            Route::prefix('subbanner')
            ->as('subbanner.')
            ->controller(SubbannerController::class)
            ->group(function () {
                Route::get('/', 'list')->name('list');
                Route::get('/edit/{id}', 'edit')->name('edit');
                Route::get('/add-new', 'new')->name('add-new');
                Route::post('/store', 'store')->name('store');
                Route::put('/{id}', 'update')->name('update');
                Route::delete('/delete/{id}', 'destroy')->name('delete');
                Route::get('/search', 'search')->name('search');
            });

            // Category routes
            Route::prefix('category')
            ->as('category.')
            ->controller(CategoryController::class)
            ->group(function () {
                Route::get('/', 'list')->name('list');
                Route::get('/edit/{id}', 'edit')->name('edit');
                Route::get('/add-new', 'new')->name('add-new');
                Route::post('/store', 'store')->name('store');
                Route::put('/{id}', 'update')->name('update');
                Route::delete('/delete/{id}', 'destroy')->name('delete');
                Route::get('/search', 'search')->name('search');
            });

            Route::prefix('category/page')
            ->as('category.page')
            ->controller(PagesController::class)
            ->group(function () {
                Route::get('/add-new-page/{categoryId}', 'addpage')->name('.addpage');
                Route::post('/store-page', 'store_Page')->name('.storepage');
                Route::get('/edit/{id}', 'edit')->name('.edit');
                Route::put('/{id}', 'update')->name('.update');
                Route::delete('/delete/{id}', 'destroy')->name('.delete');

            });
            Route::get('/types', [AdstypeController::class,'list'])->name('type.list');
            // project routes
            Route::prefix('project')
                ->as('project.')
                ->controller(ProjectController::class)
                ->group(function () {
                    Route::get('/', 'list')->name('list');
                    Route::get('/edit/{id}', 'edit')->name('edit');
                    Route::get('/add-new', 'new')->name('add-new');
                    Route::post('/store', 'store')->name('store');
                    Route::put('/{id}', 'update')->name('update');
                    Route::delete('/delete/{id}', 'destroy')->name('delete');
                    Route::get('/search', 'search')->name('search');
                });

            Route::prefix('ads') // Add the prefix here
            ->as('ads.') // Set the route name prefix
            ->controller(AdsController::class) // Specify the controller
            ->group(function () {
                Route::get('/add-new', 'new')->name('add-new');
                Route::post('/store', 'store')->name('store');
                Route::get('/', 'list')->name('list'); // List ads
                Route::get('/edit/{id}', 'edit')->name('edit'); // Edit ad
                Route::put('/{id}', 'update')->name('update'); // Update ad
                Route::delete('/delete/{id}', 'destroy')->name('delete'); // Delete ad
                Route::get('/detail/{id}', 'detail')->name('detail');
                Route::get('/search', 'search')->name('search');

                Route::get('/edit/{id}/payment', 'viewPayment')->name('view.payment');
                Route::post('/edit/{id}/payment/store', 'storePayment')->name('payment.store');
                Route::get('/edit/{id}/payment/{paymentId}', 'editPayment')->name('payment.edit');
                Route::put('/edit/{id}/payment/{paymentId}', 'updatePayment')->name('payment.update');
            });
            // In your web.php routes file

            Route::get('/get-pages/{categoryId}', [AdsController::class, 'getPagesByCategory'])->name('get.pages.by.category');

            // User Management Routes
            Route::prefix('user')
            ->as('user.')
            ->controller(UserMangementController::class)
            ->group(function () {
                Route::get('/', 'list')->name('list');
                Route::get('/edit/{user}', 'edit')->name('edit');
                Route::get('/add-new', 'new')->name('add-new');
                Route::post('/store', 'store')->name('store');
                Route::put('/{user}', 'update')->name('update');
                Route::delete('/delete/{user}', 'destroy')->name('delete');
                Route::get('/search', 'search')->name('search');
            });

            // tables Management Routes
            Route::prefix('tablets')
            ->as('tablets.')
            ->controller(TabletsController::class)
            ->group(function () {
                Route::get('/', 'list')->name('list');
                Route::get('/edit/{id}', 'edit')->name('edit');
                Route::get('/add-new', 'new')->name('add-new');
                Route::post('/store', 'store')->name('store');
                Route::put('/{id}', 'update')->name('update');
                Route::delete('/delete/{id}', 'destroy')->name('delete');
                Route::get('/search', 'search')->name('search');
            });

            //driver routers
            Route::prefix('drivers')
            ->as('drivers.')
            ->controller(DriverManagementController::class)
            ->group(function () {
                Route::get('/', 'list')->name('list');
                Route::get('/edit/{id}', 'edit')->name('edit');
                Route::get('/add-new', 'new')->name('add-new');
                Route::post('/store', 'store')->name('store');
                Route::put('/{id}', 'update')->name('update');
                Route::delete('/delete/{id}', 'destroy')->name('delete');
                Route::get('/search', 'search')->name('search');
            });

            //driver routers
            Route::prefix('clients')
            ->as('clients.')
            ->controller(ClientManagementController::class)
            ->group(function () {
                Route::get('/', 'list')->name('list');
                Route::get('/edit/{id}', 'edit')->name('edit');
                Route::get('/add-new', 'new')->name('add-new');
                Route::post('/store', 'store')->name('store');
                Route::put('/{id}', 'update')->name('update');
                Route::delete('/delete/{id}', 'destroy')->name('delete');
                Route::get('/search', 'search')->name('search');
            });

        });
    /////////////////////////////////////////////
    // client routes (will use client prefix if role is 'superadmin')
    Route::prefix('client')
    ->as('client.')
    ->group(function () {
        // SuperAdmin routes can be similar to Admin but with more privileges
        Route::post('logout', [LoginController::class, 'clientogout'])->name('clientogout');
        // Route
        Route::post('/password/change', [ClientLoginController::class, 'updatePassword'])->name('password.update');
        Route::get('dashboard', [PageController::class, 'dashboard'])->name('dashboard');

        // SuperAdmin-specific routes go here...
        Route::prefix('banner')
        ->as('banner.')
        ->controller(BannerController::class)
        ->group(function () {
            Route::get('/', 'list')->name('list');
            Route::get('/edit/{id}', 'edit')->name('edit');
            Route::get('/add-new', 'new')->name('add-new');
            Route::post('/store', 'store')->name('store');
            Route::put('/{id}', 'update')->name('update');
            Route::delete('/delete/{id}', 'destroy')->name('delete');
            Route::get('/search', 'search')->name('search');
        });

        // subBanner routes
        Route::prefix('subbanner')
        ->as('subbanner.')
        ->controller(SubbannerController::class)
        ->group(function () {
            Route::get('/', 'list')->name('list');
            Route::get('/edit/{id}', 'edit')->name('edit');
            Route::get('/add-new', 'new')->name('add-new');
            Route::post('/store', 'store')->name('store');
            Route::put('/{id}', 'update')->name('update');
            Route::delete('/delete/{id}', 'destroy')->name('delete');
            Route::get('/search', 'search')->name('search');
        });

        // Category routes
        Route::prefix('category')
        ->as('category.')
        ->controller(CategoryController::class)
        ->group(function () {
            Route::get('/', 'list')->name('list');
            Route::get('/edit/{id}', 'edit')->name('edit');
            Route::get('/add-new', 'new')->name('add-new');
            Route::post('/store', 'store')->name('store');
            Route::put('/{id}', 'update')->name('update');
            Route::delete('/delete/{id}', 'destroy')->name('delete');
            Route::get('/search', 'search')->name('search');
        });

        Route::prefix('category/page')
        ->as('category.page')
        ->controller(PagesController::class)
        ->group(function () {
            Route::get('/add-new-page/{categoryId}', 'addpage')->name('.addpage');
            Route::post('/store-page', 'store_Page')->name('.storepage');
            Route::get('/edit/{id}', 'edit')->name('.edit');
            Route::put('/{id}', 'update')->name('.update');
            Route::delete('/delete/{id}', 'destroy')->name('.delete');

        });
        Route::get('/types', [AdstypeController::class,'list'])->name('type.list');
        // project routes
        // Route::prefix('project')
        //     ->as('project.')
        //     ->controller(ProjectController::class)
        //     ->group(function () {
        //         Route::get('/', 'list')->name('list');
        //         Route::get('/search', 'search')->name('search');
        //     });

        Route::prefix('ads') // Add the prefix here
        ->as('ads.') // Set the route name prefix
        ->controller(AdsController::class) // Specify the controller
        ->group(function () {
            Route::get('/add-new', 'new')->name('add-new');
            Route::post('/store', 'store')->name('store');
            Route::get('/', 'list')->name('list'); // List ads
            Route::get('/edit/{id}', 'edit')->name('edit'); // Edit ad
            Route::put('/{id}', 'update')->name('update'); // Update ad
            Route::delete('/delete/{id}', 'destroy')->name('delete'); // Delete ad
            Route::get('/detail/{id}', 'detail')->name('detail');
            Route::get('/search', 'search')->name('search');
        });
        // In your web.php routes file

        Route::get('/get-pages/{categoryId}', [AdsController::class, 'getPagesByCategory'])->name('get.pages.by.category');

        // User Management Routes
        Route::prefix('user')
        ->as('user.')
        ->controller(UserMangementController::class)
        ->group(function () {
            Route::get('/', 'list')->name('list');
            Route::get('/edit/{user}', 'edit')->name('edit');
            Route::get('/add-new', 'new')->name('add-new');
            Route::post('/store', 'store')->name('store');
            Route::put('/{user}', 'update')->name('update');
            Route::delete('/delete/{user}', 'destroy')->name('delete');
            Route::get('/search', 'search')->name('search');
        });

        // tables Management Routes
        Route::prefix('tablets')
        ->as('tablets.')
        ->controller(TabletsController::class)
        ->group(function () {
            Route::get('/', 'list')->name('list');
            Route::get('/edit/{id}', 'edit')->name('edit');
            Route::get('/add-new', 'new')->name('add-new');
            Route::post('/store', 'store')->name('store');
            Route::put('/{id}', 'update')->name('update');
            Route::delete('/delete/{id}', 'destroy')->name('delete');
            Route::get('/search', 'search')->name('search');
        });

        //driver routers
        Route::prefix('drivers')
        ->as('drivers.')
        ->controller(DriverManagementController::class)
        ->group(function () {
            Route::get('/', 'list')->name('list');
            Route::get('/edit/{id}', 'edit')->name('edit');
            Route::get('/add-new', 'new')->name('add-new');
            Route::post('/store', 'store')->name('store');
            Route::put('/{id}', 'update')->name('update');
            Route::delete('/delete/{id}', 'destroy')->name('delete');
            Route::get('/search', 'search')->name('search');
        });

        //driver routers
        Route::prefix('clients')
        ->as('clients.')
        ->controller(ClientManagementController::class)
        ->group(function () {
            Route::get('/', 'list')->name('list');
            Route::get('/edit/{id}', 'edit')->name('edit');
            Route::get('/add-new', 'new')->name('add-new');
            Route::post('/store', 'store')->name('store');
            Route::put('/{id}', 'update')->name('update');
            Route::delete('/delete/{id}', 'destroy')->name('delete');
            Route::get('/search', 'search')->name('search');
        });

    });
    /////////////////////////////////////////////
    // client routes (will use client prefix if role is 'superadmin')
    Route::prefix('driver')
    ->as('driver.')
    ->group(function () {
        // SuperAdmin routes can be similar to Admin but with more privileges
        Route::post('logout', [LoginController::class, 'driverlogout'])->name('driverlogout');
        // Route
        Route::post('/password/change', [ClientLoginController::class, 'updatePassword'])->name('password.update');
        Route::get('dashboard', [PageController::class, 'dashboard'])->name('dashboard');


    });
});
