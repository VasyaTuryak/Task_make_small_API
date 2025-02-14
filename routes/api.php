<?php

use App\Http\Controllers\Api\OrdersController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

//TODO:sanctum, only a user could delete orders.

Route::apiResource('orders', OrdersController::class)->except(['update']);;
Route::post('/orders/{id}/pay', [OrdersController::class, 'pay'])->name('orders.pay');;
Route::post('/orders/{id}/cancel', [OrdersController::class, 'cancel'])->name('orders.cancel');;
Route::fallback(function () {
    return response()->json(['message' => 'Route Not Found'], 404);
})->name('api.fallback.404');

