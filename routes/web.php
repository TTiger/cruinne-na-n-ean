<?php

use App\Shop\Products\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

Route::get('/', ProductController::class)->name('home');
