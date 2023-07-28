<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CartController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ShipmentController;
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

Route::get('/productTable', [ProductController::class, 'productIndex']);
Route::get('/product/create', [ProductController::class, 'create']);
Route::post('/product/store', [ProductController::class, 'store']);
Route::get('/product/edit/{id}', [ProductController::class, 'edit']);
Route::put('/product/update/{id}', [ProductController::class, 'update']);
Route::delete('/product/delete/{id}', [ProductController::class, 'destroy']);

Route::get('/shipmentTable', [ShipmentController::class, 'shipmentIndex']);