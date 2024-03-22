<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\InvoicesController;
use App\Http\Controllers\InvoicesDetailsController;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\SectionsController;
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


Auth::routes(['register' => false]);

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');


Route::resource("/invoices", InvoicesController::class);
Route::resource('/sections', SectionsController::class);
Route::resource('/products', ProductsController::class);

Route::get('getProducts/{id}',[InvoicesController::class,"getProducts"]);
Route::get('InvoicesDetails/{id}', [InvoicesDetailsController::class,"edit"]);


Route::get('download/{invoices_number}/{file_name}',[InvoicesDetailsController::class,"getFile"]);
Route::get('View_file/{invoices_number}/{file_name}',[InvoicesDetailsController::class,"openFile"]);

Route::post('delete_file', [InvoicesDetailsController::class,"destroy"])->name('delete_file');
Route::get('/{page}', AdminController::class. '@index');
