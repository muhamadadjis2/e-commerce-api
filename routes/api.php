<?php

use Illuminate\Http\Request;
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

//API route for register new user
Route::post('/register', [App\Http\Controllers\API\AuthController::class, 'register']);
//API route for login user
Route::post('/login', [App\Http\Controllers\API\AuthController::class, 'login']);

//Protecting Routes
Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::get('/profile', function(Request $request) {
        return auth()->user();
    });

    Route::resource('customers', App\Http\Controllers\API\CustomersController::class);
    Route::resource('products', App\Http\Controllers\API\ProductsController::class);
    Route::resource('payment-methods', App\Http\Controllers\API\PaymentMethodsController::class);
    Route::resource('customer-address', App\Http\Controllers\API\CustomerAddressesController::class);
    Route::resource('transactions', App\Http\Controllers\API\TransactionsController::class);

    // API route for logout user
    Route::post('/logout', [App\Http\Controllers\API\AuthController::class, 'logout']);
});
