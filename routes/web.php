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
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ReturnFormController;
use App\Http\Controllers\SerialNumberController;
use App\Http\Controllers\WarrantyLookupController;
use Illuminate\Support\Facades\Route;

// Products
Route::resource('products', ProductController::class);
Route::resource('users', UserController::class);
Route::resource('warehouses', WarehouseController::class);
Route::resource('receivings', ReceivingController::class);
Route::resource('returnforms', ReturnFormController::class);
Route::resource('quotations', QuotationController::class);

// 
Route::get('/get-info-receiving', [ReceivingController::class, 'getReceiving'])->name('getReceiving');
// Check SN
Route::get('/checkSNImport', [SerialNumberController::class, 'checkSNImport'])->name('checkSNImport');
// Filter
Route::get('/filter-customer', [CustomersController::class, 'filterData'])->name('filter-customer');
Route::get('/filter-provides', [ProvidersController::class, 'filterData'])->name('filter-provides');
Route::get('/filter-products', [ProductController::class, 'filterData'])->name('filter-products');
Route::get('/filter-users', [UserController::class, 'filterData'])->name('filter-users');
Route::get('/filter-warehouse', [WarehouseController::class, 'filterData'])->name('filter-warehouse');
Route::get('/filter-imports', [ImportsController::class, 'filterData'])->name('filter-imports');
Route::get('/filter-exports', [ExportsController::class, 'filterData'])->name('filter-exports');
Route::get('/filter-receivings', [ReceivingController::class, 'filterData'])->name('filter-receivings');
Route::get('/filter-quotations', [QuotationController::class, 'filterData'])->name('filter-quotations');
Route::get('/filter-returnforms', [ReturnFormController::class, 'filterData'])->name('filter-returnforms');

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
Route::resource('inventoryLookup', InventoryLookupController::class);
//Check S/N exist
Route::get('/checkSN', [SerialNumberController::class, 'checkSN'])->name('checkSN');
//Warranty
Route::resource('warrantyLookup', WarrantyLookupController::class);
//
Route::get('/searchMiniView', [ImportsController::class, 'searchMiniView'])->name('searchMiniView');
//report overview
Route::get('/reportOverview', [ReportController::class, 'reportOverview'])->name('reportOverview');
//report export import
Route::get('/reportExportImport', [ReportController::class, 'reportExportImport'])->name('reportExportImport');
//report receipt return
Route::get('/reportReceiptReturn', [ReportController::class, 'reportReceiptReturn'])->name('reportReceiptReturn');
//report quotation
Route::get('/reportQuotation', [ReportController::class, 'reportQuotation'])->name('reportQuotation');
//
Route::get('/filterReportOverview', [ReportController::class, 'filterReportOverview'])->name('filterReportOverview');
Route::get('/filterReportPeriodTime', [ReportController::class, 'filterReportPeriodTime'])->name('filterReportPeriodTime');

require __DIR__ . '/auth.php';
