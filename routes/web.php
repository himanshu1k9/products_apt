<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductsController;

// Route::get('/products', [ProductsController::class, 'index']);
Route::get('/', [ProductsController::class, 'index']);
Route::get('/store', [ProductsController::class, 'store']);
Route::post('/addproduct', [ProductsController::class, 'create']);
Route::get('/getsingle/{id}', [ProductsController::class, 'getSingleProduct']);
Route::post('/update/{id}', [ProductsController::class, 'update']);;
Route::post('/destroy/{id}', [ProductsController::class, 'destroy']);
