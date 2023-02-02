<?php

use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\DadataController;
use App\Http\Controllers\Delivery\CatalogProductsAnalyticsController;
use App\Http\Controllers\Delivery\DeliveryController;
use App\Http\Controllers\Payments\MkbCallbackController;
use App\Http\Controllers\Payments\PaymentTypeController;
use App\Http\Controllers\Payments\TransactionController;
use App\Http\Controllers\Payments\YandexCallbackController;
use App\Http\Controllers\UserAddressesController;
use App\Http\Controllers\UserController;
use App\Modules\Authorization\Http\Controllers\ApiAuthController;
use App\Modules\Authorization\Http\Controllers\UserController as AuthUserController;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
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

Route::withoutMiddleware(["auth"])->middleware('access_token')->prefix('/v1/')->group(function () {
    Route::post('/set_remote_data', [CheckoutController::class, 'saveTmpCheckout']);
    Route::post('/get_remote_data', [CheckoutController::class, 'findTmpCheckout']);
    Route::get('/get_checkout', [CheckoutController::class, 'getCheckout']);
    Route::post('/checkout_success', [CheckoutController::class, 'checkoutSuccess']);
    Route::get('/check_products_count', [CheckoutController::class, 'checkProductsCount']);

    Route::get('/delivery/ping', [DeliveryController::class, 'ping']);
    Route::get('/delivery/pvz', [DeliveryController::class, 'pvz']);
    Route::get('/delivery/courier', [DeliveryController::class, 'courier']);
    Route::get('/delivery/employee', [DeliveryController::class, 'employee']);

    Route::get('/get_suggestions_by_query', [DadataController::class, 'getSuggestionsByQuery']);
    Route::get('/get_address_by_coords', [DadataController::class, 'getAddressByCoords']);

    Route::get('/user/check_email_exists', [UserController::class, 'checkEmailExists']);

    Route::post('/user_addresses', [UserAddressesController::class, 'store']);
    Route::put('/user_addresses/{external_id}', [UserAddressesController::class, 'update']);
    Route::put('/user_addresses/{external_id}/active', [UserAddressesController::class, 'setActive']);
    Route::delete('/user_addresses/{external_id}', [UserAddressesController::class, 'delete']);

    Route::get('/ping', function () {
        return new JsonResponse(['data' => "pong!"]);
    });
});

Route::withoutMiddleware(["auth"])->prefix('/v1/')->group(function () {
    Route::get('/get_payment_list', [CheckoutController::class, 'getPaymentList']);

    Route::get('/create_order', [CheckoutController::class, 'createOrder']);

    Route::get('/get_status', [CheckoutController::class, 'getStatusPayment']);

    Route::prefix('payments')->group(function () {
        Route::get('/get_payment_types', [PaymentTypeController::class, 'getPaymentTypes']);
        Route::get('/get_order_transaction', [TransactionController::class, 'getOrderTransaction']);
        Route::post('/fiscalize_order_transaction', [TransactionController::class, 'fiscalizeOrderTransaction']);
        Route::post('/mkb_callback', MkbCallbackController::class)->name('payments.mkb_callback');
        Route::post('/yandex_callback', YandexCallbackController::class);
    });

    Route::get('/catalog_products', [CatalogProductsAnalyticsController::class, 'index']);
    Route::get('/catalog_products/types', [CatalogProductsAnalyticsController::class, 'types']);
    Route::get('/catalog_products/dropshipping', [CatalogProductsAnalyticsController::class, 'dropshipping']);

    Route::get('/cache/clear', function () {
        Cache::flush();
        return new JsonResponse(['data' => 'Cache cleared!']);
    });
});

// API user authorization
Route::withoutMiddleware('auth')->prefix('/v1/')->group(function () {
    Route::post('/get_verification_code', [ApiAuthController::class, 'getVerificationCode']);
    Route::post('/register_anonymous', [ApiAuthController::class, 'registerAndLoginAnonymous']);
    Route::post('/get_authorization', [ApiAuthController::class, 'getAuthorization']);
    Route::post('/get_token_by_refresh', [ApiAuthController::class, 'getOauthTokenByRefresh']);
});

// API user
Route::withoutMiddleware('auth')->prefix('/v1/')->group(function () {
    Route::post('/user/profile', [AuthUserController::class, 'profile']);
});

// TODO Удалить после тестирования
Route::withoutMiddleware('auth')->prefix('/v1/')->group(function () {
    Route::get('/test_ip', [ApiAuthController::class, 'testIp']);
});
