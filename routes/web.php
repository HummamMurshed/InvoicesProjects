<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\CustomerReportControoler;
use App\Http\Controllers\InvoiceAttchmentsController;
use App\Http\Controllers\InvoicesArchiveController;
use App\Http\Controllers\InvoicesController;
use App\Http\Controllers\InvoicesDetailsController;
use App\Http\Controllers\InvoicesReportController;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SectionsController;


use App\Http\Controllers\UserController;
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

Route::get('/', function () {
    return view('auth.login');
});


Auth::routes(['register' => true]);

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');


Route::resource("/invoices", InvoicesController::class);


Route::resource('/sections', SectionsController::class);
Route::resource('/products', ProductsController::class);

Route::get('getProducts/{id}',[InvoicesController::class,"getProducts"]);
Route::get('InvoicesDetails/{id}', [InvoicesDetailsController::class,"edit"]);

Route::resource('InvoiceAttachments', InvoiceAttchmentsController::class);

Route::get('download/{invoices_number}/{file_name}',[InvoicesDetailsController::class,"getFile"]);
Route::get('View_file/{invoices_number}/{file_name}',[InvoicesDetailsController::class,"openFile"]);
Route::get('Status_show/{id}', [InvoicesController::class,"show"])->name('Status_show');
Route::post('Status_Update/{id}', [InvoicesController::class, 'status_update'])->name('Status_Update');
Route::get('edit_invoice/{id}', [InvoicesController::class,"edit"]);
Route::post('delete_file', [InvoicesDetailsController::class,"destroy"])->name('delete_file');

Route::get('invoices_paid', [InvoicesController::class,"invoicesPaid"]);
Route::get('invoices_unpaid', [InvoicesController::class,"invoicesUnpaid"]);
Route::get('invoices_partialPaid', [InvoicesController::class,"invoicesPartialPaid"]);

Route::resource('invoices_archive',InvoicesArchiveController::class);
Route::get('Print_invoice/{id}',[InvoicesController::class, "Print_invoice"]);
Route::get('export_invoices/', [InvoicesController::class, 'export']);

// Route For Admin For Test Permissiion
Route::group(['middleware' => ["auth","role:owner"]], function (){
    Route::resource('users',UserController::class);
    Route::resource('roles', RoleController::class);
});

Route::get('invoices_report',[InvoicesReportController::class,"index"]);
Route::post('Search_invoices',[InvoicesReportController::class,"Search_invoices"]);

Route::get('customer_report',[CustomerReportControoler::class,"index"]);
Route::post('Search_customers',[CustomerReportControoler::class,"Search_customers"]);

Route::get('MarkAsRead_all',[InvoicesController::class, "MarkAsRead_all"])->name('MarkAsRead_all');
Route::get('/{page}', AdminController::class. '@index');
