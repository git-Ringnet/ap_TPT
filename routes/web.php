<?php

use App\Http\Controllers\CustomersController;
use App\Http\Controllers\ExportsController;
use App\Http\Controllers\GroupsController;
use App\Http\Controllers\ImportsController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\InventoryLookupController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductReturnController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\QuotationController;
use App\Http\Controllers\ReceivingController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WarehouseController;
use App\Http\Controllers\ProvidersController;
use App\Http\Controllers\ReturnFormController;
use App\Http\Controllers\SerialNumberController;
use Illuminate\Support\Facades\Route;

// Products
Route::resource('products', ProductController::class);
Route::resource('users', UserController::class);
Route::resource('warehouses', WarehouseController::class);
Route::resource('receivings', ReceivingController::class);
Route::resource('quotations', QuotationController::class);
Route::resource('returnforms', ReturnFormController::class);
// 
Route::get('/get-info-receiving', [ReceivingController::class, 'getReceiving'])->name('getReceiving');


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
//Imports
Route::resource('imports', ImportsController::class);
//Exports
Route::resource('exports', ExportsController::class);
//Inventory
Route::resource('invenroryLookup', InventoryLookupController::class);
//Check S/N exist
Route::get('/checkSN', [SerialNumberController::class, 'checkSN'])->name('checkSN');

require __DIR__ . '/auth.php';
