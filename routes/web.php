<?php

use App\Http\Controllers\ShipmentController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CartController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Mail;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', [ProductController::class, 'home'])->name('products.index');
Route::get('/search', [SearchController::class, 'search'])->name('search');
Route::get('/product-detail/{id}', [ProductController::class, 'show'])->name('products.detail');

Route::middleware(['admin','auth'])->group(function () { 
    Route::resource('/brand', BrandController::class);
    Route::resource('/product', ProductController::class);
    Route::resource('/shipment', ShipmentController::class);
    Route::resource('/payment', PaymentController::class);
    Route::resource('/order', OrderController::class);
    Route::get('/sales', [AdminController::class, 'sales']);
    Route::get('/create-payment/{id}',[PaymentController::class, 'create']);
    Route::get('/date-range',[AdminController::class, 'daterange']);
});

Route::get('/approve-order/{id}',[AdminController::class, 'approveorder']);

Route::middleware(['auth'])->group(function () { 
    Route::resource('/profile', UserController::class);
    Route::get('/profile/{id}', [UserController::class, 'userorders']);
    Route::get('/edit-profile/{id}', [UserController::class, 'edit']);
    Route::get('/shoppingcart/{id}', [CartController::class, 'shoppingcart']);
    Route::get('/increment/{id}', [CartController::class, 'increment']);
    Route::get('/decrement/{id}', [CartController::class, 'decrement']);
    Route::post('/addcart/{id}', [CartController::class,'addcart']);
    Route::get('/delete/{id}', [CartController::class, 'deletecart']);
    Route::post('/checkout/{id}', [CartController::class,'checkout']);
    Route::get('/cancelorder/{id}', [OrderController::class, 'cancelorder']);
    Route::get('/edit-form/{id}', [OrderController::class, 'editform']);
    Route::put('/edit-order/{id}', [OrderController::class, 'editorder']);
    Route::get('/feedback-form/{id}', [OrderController::class, 'form']);
    Route::put('/rateorder/{id}', [OrderController::class, 'rateorder']);
    Route::get('/receipt/{id}', [UserController::class, 'receipt']);
});

// Route::resource('/order', OrderController::class);
// Route::get('/restore/{id}', [CartController::class, 'restorecart']);
// Route::get('/cartbin', [CartController::class, 'deletedcart']);




Auth::routes();

// Route::get('/home', [HomeController::class, 'index'])->name('home');
