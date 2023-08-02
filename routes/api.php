<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CartController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ShipmentController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\BrandController;
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
Route::get('/search', [SearchController::class, 'search'])->name('search');

Route::get('/increment/{id}', [CartController::class, 'increment']);
Route::get('/decrement/{id}', [CartController::class, 'decrement']);

Route::get('/productChart', [ProductController::class, 'indexChart']);
Route::get('/productTable', [ProductController::class, 'productIndex']);
Route::get('/product/create', [ProductController::class, 'create']);
Route::post('/product/store', [ProductController::class, 'store']);
Route::get('/product/edit/{id}', [ProductController::class, 'edit']);
Route::put('/product/update/{id}', [ProductController::class, 'update']);
Route::delete('/product/delete/{id}', [ProductController::class, 'destroy']);

Route::get('/shipmentChart', [ShipmentController::class, 'indexChart']);
Route::get('/shipmentTable', [ShipmentController::class, 'shipmentIndex']);
Route::get('/shipment/create', [ShipmentController::class, 'create']);
Route::post('/shipment/store', [ShipmentController::class, 'store']);
Route::get('/shipment/edit/{id}', [ShipmentController::class, 'edit']);
Route::put('/shipment/update/{id}', [ShipmentController::class, 'update']);
Route::delete('/shipment/delete/{id}', [ShipmentController::class, 'destroy']);

Route::get('/paymentChart', [PaymentController::class, 'indexChart']);
Route::get('/paymentTable', [PaymentController::class, 'paymentIndex']);
Route::get('/payment/create', [PaymentController::class, 'create']);
Route::post('/payment/store', [PaymentController::class, 'store']);
Route::get('/payment/edit/{id}', [PaymentController::class, 'edit']);
Route::put('/payment/update/{id}', [PaymentController::class, 'update']);
Route::delete('/payment/delete/{id}', [PaymentController::class, 'destroy']);

Route::get('/brandChart', [BrandController::class, 'indexChart']);
Route::get('/brandTable', [BrandController::class, 'brandIndex']);
Route::get('/brand/create', [BrandController::class, 'create']);
Route::post('/brand/store', [BrandController::class, 'store']);
Route::get('/brand/edit/{id}', [BrandController::class, 'edit']);
Route::put('/brand/update/{id}', [BrandController::class, 'update']);
Route::delete('/brand/delete/{id}', [BrandController::class, 'destroy']);