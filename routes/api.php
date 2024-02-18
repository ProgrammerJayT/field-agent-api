<?php

use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\CustomerViewController;
use App\Http\Controllers\UserController;

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

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return response()->json([
        'user' => new UserResource($request->user())
    ], 200);
});

Route::group(['prefix' => 'v1'], function () {

    Route::post('/login', [AuthenticatedSessionController::class, 'store'])
        ->middleware('guest')
        ->name('login');

    Route::group(['middleware' => 'auth:sanctum'], function () {
        Route::get('/verify-token', function () {
            return response()->json(['message' => 'Token is valid']);
        });

        Route::apiResource('customers', CustomerController::class);

        Route::get('view-customer', [CustomerController::class, 'viewCustomer']);

        Route::get('create-customers-views/{id}', [CustomerViewController::class, "createView"]);

        Route::apiResource('users', UserController::class);
    });
});
