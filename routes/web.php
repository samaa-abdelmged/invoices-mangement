<?php

use App\Http\Controllers\ArchiveInvoicesController;
use App\Http\Controllers\CustomersReportController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\InvoicesAttachmentsController;
use App\Http\Controllers\InvoicesController;
use App\Http\Controllers\InvoicesDetailsController;
use App\Http\Controllers\InvoicesReportController;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SectionsController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VerifyController;
use Illuminate\Support\Facades\Auth;
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

Route::group(['middleware' => ['auth']], function () {
    Route::resource('/roles', RoleController::class);
    Route::resource('/users', UserController::class);

});

Auth::routes();

Route::get('/home', [HomeController::class, 'index'])->middleware('verify');

//verify
Route::resource('/verify', VerifyController::class);
Route::post('/update_verify', [VerifyController::class, 'update'])->name('update_verify');
//

Route::resource('/invoices', InvoicesController::class);
Route::resource('/sections', SectionsController::class);
Route::resource('/products', ProductsController::class);
//
Route::get('/section{id}', [InvoicesController::class, 'getProducts']);
Route::get('/GetInvoicesDetails{id}', [InvoicesDetailsController::class, 'GetInvoicesDetails']);
//
Route::get('/ViewFile/{invoice_number}/{file_name}', [InvoicesDetailsController::class, 'ViewFile']);
Route::get('/DownloadFile/{invoice_number}/{file_name}', [InvoicesDetailsController::class, 'DownloadFile']);
Route::post('/DeleteFile', [InvoicesDetailsController::class, 'DeleteFile'])->name('DeleteFile');
//
Route::resource('/InvoicesAttachments', InvoicesAttachmentsController::class);
//
Route::get('/edit_invoices/{id}', [InvoicesController::class, 'edit'])->name('edit_invoices');
Route::post('/update_invoices', [InvoicesController::class, 'update'])->name('update_invoices');
//
Route::get('/payment_status_show/{id}', [InvoicesController::class, 'payment_status_show'])->name('payment_status_show');
Route::post('/payment_status_update/{id}', [InvoicesController::class, 'payment_status_update'])->name('payment_status_update');
//
Route::get('/invoice_paid', [InvoicesController::class, 'invoice_paid'])->name('invoice_paid');
Route::get('/invoice_unpaid', [InvoicesController::class, 'invoice_unpaid'])->name('invoice_unpaid');
Route::get('/invoice_partial', [InvoicesController::class, 'invoice_partial'])->name('invoice_partial');
//
Route::get('/archive_show', [ArchiveInvoicesController::class, 'show']);
Route::post('/restore', [ArchiveInvoicesController::class, 'restore'])->name('archive.restore');
Route::post('/destroy', [ArchiveInvoicesController::class, 'destroy'])->name('archive.destroy');
Route::get('/print_invoice/{id}', [InvoicesController::class, 'print_invoice']);

//excel
Route::get('/invoices_list_excel', [InvoicesController::class, 'ExportExcel']);
Route::get('/invoices_report_excel', [InvoicesReportController::class, 'ExportExcel']);
Route::get('/customers_report_excel', [CustomersReportController::class, 'ExportExcel']);

//

Route::get('/invoices_report', [InvoicesReportController::class, 'index']);
Route::post('/invoices_search', [InvoicesReportController::class, 'Search_invoices']);
//

Route::get('/customers_report', [CustomersReportController::class, 'index']);
Route::post('/customers_search', [CustomersReportController::class, 'Search_customers']);
//
Route::get('/MarkAsReadAll', [InvoicesController::class, 'MarkAsRead_all']);