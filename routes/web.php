<?php

use App\Http\Controllers\CustomersController;
use App\Http\Controllers\GroupsController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WarehouseController;
use App\Http\Controllers\ProvidersController;
use Illuminate\Support\Facades\Route;

// Products
Route::resource('products', ProductController::class);
Route::resource('users', UserController::class);
Route::resource('warehouses', WarehouseController::class);

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

//Groups
Route::resource('groups', GroupsController::class);
Route::get('/dataObj', [GroupsController::class, 'dataObj'])->name('dataObj');
Route::get('/updateDataGroup', [GroupsController::class, 'updateDataGroup'])->name('updateDataGroup');
Route::get('/deleteOJ', [GroupsController::class, 'deleteOJ'])->name('deleteOJ');
//Customers
Route::resource('customers', CustomersController::class);
//Providers
Route::resource('providers', ProvidersController::class);

require __DIR__ . '/auth.php';
