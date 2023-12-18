<?php

use App\Http\Controllers\AddressController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CampaignController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\HomepageController;
use App\Http\Controllers\UserController;
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

Route::post('login', [AuthController::class, 'login']);
Route::post('register', [AuthController::class, 'register']);
Route::post('verify-account', [AuthController::class, 'verifyAccount']);
Route::post('forgot-password', [AuthController::class, 'forgotPassword']);
Route::post('reset-password', [AuthController::class, 'resetPassword']);
Route::post('verify-account', [AuthController::class, 'verifyAccount']);
Route::get('login/{provider}', [AuthController::class,'redirectToProvider']);
Route::get('login/{provider}/callback', [AuthController::class,'handleProviderCallback']);

Route::get('fetch-featured-campaigns', [HomepageController::class, 'fetchFeaturedCampaigns']);
Route::get('fetch-current-campaigns', [HomepageController::class, 'fetchCurrentCampaigns']);
Route::get('campaign-category/list', [HomepageController::class, 'campaignCategoryList']);
Route::get('fetch-products', [HomepageController::class, 'productList']);
Route::get('fetch-campaign-winners', [HomepageController::class, 'fetchCampaignWinners']);
Route::get('fetch-carousel-banners', [HomepageController::class, 'fetchAllCarousel']);
Route::get('fetch-sub-banners', [HomepageController::class, 'fetchAllSubBanners']);

Route::get('campaign/{id}', [CampaignController::class, 'findCampaignDetails']);
Route::get('campaign/slug/{slug}', [CampaignController::class, 'findCampaignDetailsBySlug']);
Route::get('fetch-suggested-campaigns/{id}', [CampaignController::class, 'fetchSuggestedCampaigns']);
Route::get('product/campaigns/{id}', [CampaignController::class, 'fetchProductCampaigns']);
Route::get('product/{id}', [CampaignController::class, 'findProduct']);
Route::get('product/slug/{id}', [CampaignController::class, 'findProductBySlug']);
Route::get('address-types/list', [AddressController::class, 'fetchAddressTypeList']);
Route::post('contact-us', [HomepageController::class, 'contactUs']);

Route::get('paymaya/merchant/webhook', [HomepageController::class, 'paymayaWebhook']);
Route::post('paymaya/merchant/webhook', [HomepageController::class, 'paymayaWebhook']);

Route::middleware('auth:sanctum')->group(function() {
    Route::post('add-to-cart', [CartController::class, 'addToCart']);
    Route::get('cart', [CartController::class, 'fetchCart']);
    Route::get('cart/suggested-campaigns', [CartController::class, 'suggestedCampaigns']);
    Route::put('cart/{id}', [CartController::class, 'updateCartItem']);
    Route::delete('cart/{id}', [CartController::class, 'removeCartItem']);
    Route::get('fetch-cart-shipping-fee', [CartController::class, 'cartShippingFee']);
    Route::post('checkout', [CartController::class, 'checkout']);

    Route::prefix('account')->group(function () {
        Route::post('password', [AuthController::class, 'changePassword']);
        Route::get('address', [UserController::class, 'fetchAccountAddress']);
        Route::post('address', [UserController::class, 'changeAddress']);
        Route::put('address', [UserController::class, 'updateAddresses']);
        Route::post('details', [UserController::class, 'updateAccountDetails']);
        Route::get('details', [UserController::class, 'fetchAccountDetails']);
        Route::get('active-tickets', [UserController::class, 'fetchAccountActiveTickets']);
        Route::get('purchase-history', [UserController::class, 'fetchAccountOrders']);
    });

    Route::post('logout', [AuthController::class, 'logout']);
});
