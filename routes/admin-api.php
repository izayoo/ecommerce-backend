<?php

use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\BannerController;
use App\Http\Controllers\Admin\CampaignCategoryController;
use App\Http\Controllers\Admin\CampaignController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\MediaController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\ProductCategoryController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\CampaignTicketController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::prefix('admin')->group(function() {
    Route::post('login', [AdminAuthController::class, 'login']);
    Route::middleware('auth:sanctum')->group(function() {
        Route::prefix('account')->group(function () {
            Route::get('details', [AdminAuthController::class, 'fetchAccountDetails']);
            Route::post('password', [AdminAuthController::class, 'changePassword']);
        });

        Route::post('logout', [AdminAuthController::class, 'logout']);

        Route::prefix('media')->group(function() {
            Route::post('upload-image', [MediaController::class, 'storeImage']);
        });

        Route::prefix('overview')->group(function() {
            Route::get('general-info', [DashboardController::class, 'getGeneralInfoOverview']);
            Route::get('transactions', [DashboardController::class, 'getTransactionsOverview']);
        });

        Route::prefix('product')->group(function() {
            Route::get('list', [ProductController::class, 'fetchAll']);
            Route::get('/', [ProductController::class, 'fetchAllPaginated']);
            Route::get('/export', [ProductController::class, 'export'])->middleware('exports');
            Route::get('/{id}', [ProductController::class, 'fetchOne']);
            Route::post('/', [ProductController::class, 'create']);
            Route::put('/{id}', [ProductController::class, 'update']);
            Route::delete('/{id}', [ProductController::class, 'delete']);
        });

        Route::prefix('product-category')->group(function() {
            Route::get('list', [ProductCategoryController::class, 'fetchAll']);
        });

        Route::prefix('campaign-category')->group(function() {
            Route::get('list', [CampaignCategoryController::class, 'fetchAll']);
        });

        Route::prefix('campaign')->group(function() {
            Route::get('list', [CampaignController::class, 'fetchAll']);
            Route::get('/', [CampaignController::class, 'fetchAllPaginated']);
            Route::get('/export', [CampaignController::class, 'export'])->middleware('exports');
            Route::get('/{id}', [CampaignController::class, 'fetchOne']);
            Route::post('/', [CampaignController::class, 'create']);
            Route::put('/{id}', [CampaignController::class, 'update']);
            Route::delete('/{id}', [CampaignController::class, 'delete']);
            Route::prefix('/{id}/ticket')->group(function() {
                Route::get('list', [CampaignTicketController::class, 'fetchAll']);
                Route::get('/', [CampaignTicketController::class, 'fetchAllPaginated']);
                Route::get('/export', [CampaignTicketController::class, 'export']);
                Route::get('/{ticketId}', [CampaignTicketController::class, 'fetchOne']);
                Route::post('/{ticketId}', [CampaignTicketController::class, 'setAsWinner']);
            });
        });

        Route::prefix('order')->group(function() {
            Route::get('list', [OrderController::class, 'fetchAll']);
            Route::get('/', [OrderController::class, 'fetchAllPaginated']);
            Route::get('/export', [OrderController::class, 'export'])->middleware('exports');
            Route::get('/{id}', [OrderController::class, 'fetchOne']);
        });

        Route::prefix('user')->group(function() {
            Route::get('list', [UserController::class, 'fetchAll']);
            Route::get('/', [UserController::class, 'fetchAllPaginated']);
            Route::get('/export', [UserController::class, 'export'])->middleware('exports');
            Route::get('/{id}', [UserController::class, 'fetchOne']);
            Route::delete('/{id}', [UserController::class, 'delete']);
        });

        Route::prefix('banner')->group(function() {
            Route::get('/carousel', [BannerController::class, 'listCarousel']);
            Route::get('/sub-banner', [BannerController::class, 'listSubBanner']);
            Route::post('/carousel', [BannerController::class, 'updateCarousel']);
            Route::post('/sub-banner', [BannerController::class, 'updateSubBanner']);

        });

        Route::prefix('banner-type')->group(function() {
            Route::get('list', [BannerController::class, 'fetchBannerTypeList']);
        });
    });
});
