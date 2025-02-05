<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\CompaniesController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\PosController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductImageController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\PurchaseReturController;
use App\Http\Controllers\ReturController;
use App\Http\Controllers\SalesController;
use App\Http\Controllers\SalesReturController;
use App\Http\Controllers\SuppliersController;
use App\Http\Controllers\WarehouseController;
use Illuminate\Support\Facades\Route;

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

Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::post('/login', [AuthController::class, 'loginPost'])->name('loginPost');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


Route::middleware('auth')->group(function () {

    Route::resource('customer', CustomerController::class);

    Route::post('/customer/store/new', [CustomerController::class, 'storeNew'])->name('customer.store.new');
    Route::resource('employe', EmployeeController::class);
    Route::resource('companies', CompaniesController::class);
    Route::resource('categories', CategoriesController::class);
    Route::resource('warehouse', WarehouseController::class);
    Route::resource('product', ProductController::class);
    Route::resource('suppliers', SuppliersController::class);
    Route::resource('sales', SalesController::class);
    Route::resource('purchase', PurchaseController::class);
    Route::resource('salesRetur', SalesReturController::class);
    Route::resource('purchaseRetur', PurchaseReturController::class);
    // Route::resource('image', ProductImageController::class);

    Route::get('/sales/invoice/{noNota}', [SalesController::class, 'showByNoNota'])->name('sales.showByNoNota');

    Route::get('/sales/{id}/detail', [SalesController::class, 'detail'])->name('sales.detail');
    Route::get('/purchase/{id}/detail', [PurchaseController::class, 'detail'])->name('purchase.detail');

    Route::get('/', function () {
        return view('layouts.conquer');
    });

    Route::get('/penjualan', function () {
        return view('sales.nota');
    });

    Route::get('/salesKonfigurasi', [SalesController::class, 'dataKonfigurasi'])->name('sales.konfigurasi');
    Route::post('/sales/updateConfiguration', [SalesController::class, 'updateConfiguration'])->name('sales.updateConfiguration');

    Route::get('/purchaseKonfigurasi', [PurchaseController::class, 'dataKonfigurasi'])->name('purchase.konfigurasi');
    Route::post('/purchase/updateConfiguration', [PurchaseController::class, 'updateConfiguration'])->name('purchase.updateConfiguration');

    Route::get('/inventoryKonfigurasi', [WarehouseController::class, 'dataKonfigurasi'])->name('warehouse.konfigurasi');  
    Route::post('/inventory/updateConfiguration', [WarehouseController::class, 'updateConfiguration'])->name('warehouse.updateConfiguration');

    Route::get('/get-product-max-return', [SalesReturController::class, 'getProductMaxReturn']);
    Route::get('/get-total-quantity', [SalesReturController::class, 'getTotalQuantity']);
    Route::get('/retur/{id}/detailSales', [SalesReturController::class, 'detail'])->name('salesRetur.detail');
    Route::post('/retur/{id}/update-status', [SalesReturController::class, 'updateStatus'])->name('salesRetur.updateStatus');

    Route::get('/retur/{id}/detailPurchase', [PurchaseReturController::class, 'detail'])->name('purchaseRetur.detail');
    Route::get('/purchase/{id}/details', [PurchaseReturController::class, 'getPurchaseDetails']);
    Route::get('/purchase/{purchaseId}/details/{productId}', [PurchaseReturController::class, 'getProductDetails']);


    Route::get('product/uploadPhoto/{id}', [ProductImageController::class, 'uploadPhoto'])->name('image.formUploadPhoto');
    Route::post('product/simpanPhoto', [ProductImageController::class, 'simpanPhoto']);


    Route::post('/products/create-shipping', [ProductController::class, 'createShipping'])->name('products.create-shipping');
    Route::post('/products/{product}/confirm-receipt', [ProductController::class, 'confirmReceipt'])->name('products.confirm-receipt');
    Route::get('/salesShipping', [SalesController::class, 'shipping'])->name('sales.shipping');
    Route::get('/salesCreateShipping', [SalesController::class, 'createShipping'])->name('sales.createShipping');


    Route::get('pos', [PosController::class, 'index'])->name('pos.index');
    Route::post('pos/store', [PosController::class, 'store'])->name('pos.store');
    Route::get('/pos/{id}', [PosController::class, 'print'])->name('pos.print');
});
