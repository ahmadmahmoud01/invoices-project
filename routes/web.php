<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SectionController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\InvoiceArchiveController;
use App\Http\Controllers\InvoiceAttachmentController;

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
//     return view('auth.login');
// });

Route::get('/', [HomeController::class, 'index'])->name('home');

// Route::middleware('auth')->group(function () {
//     Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
//     Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
//     Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
// });

require __DIR__.'/auth.php';


Route::resource('invoices', InvoiceController::class);
Route::resource('sections', SectionController::class);
Route::resource('products', ProductController::class);

Route::get('/section/{id}', [SectionController::class, 'getProducts'])->name('sections.products');


Route::get('download/{invoice_number}/{file_name}', [InvoiceAttachmentController::class, 'get_file']);
Route::get('view_file/{invoice_number}/{file_name}', [InvoiceAttachmentController::class, 'open_file']);
Route::post('delete-file', [InvoiceAttachmentController::class, 'destroy'])->name('delete_file');
Route::post('InvoiceAttachments', [InvoiceAttachmentController::class, 'store'])->name('InvoiceAttachments.store');


// update invoice status
Route::get('update-invoice-status/{id}', [InvoiceController::class, 'editStatus'])->name('edit-invoice-status');
Route::post('update-invoice-status/{id}', [InvoiceController::class, 'updateStatus'])->name('update-invoice-status');

// paid, unpaid and partial paid invoices
Route::get('invoices-paid',[InvoiceController::class,'paidInvoices'])->name('invoices.paid');
Route::get('invoices-unpaid',[InvoiceController::class,'unPaidInvoices'])->name('invoices.unpaid');
Route::get('invoices-partially-paid',[InvoiceController::class,'partiallyPaidInvoices'])->name('invoices.partially-paid');
// archived invoices
Route::get('invoices-archived',[InvoiceController::class,'archivedInvoices'])->name('invoices.archived');

// delete archived invoice
Route::delete('invoice-archived/{id}', [InvoiceArchiveController::class, 'destroy'])->name('invoice.archived.destroy');
// restore archived invoice
Route::post('invoice-restore/{id}', [InvoiceArchiveController::class, 'restore'])->name('invoice.archived.restore');

//print invoice
Route::get('invoice-print/{invoice}', [InvoiceController::class, 'printInvoice'])->name('invoice.print');

// export invoice excel
Route::get('inovices/export', [InvoiceController::class, 'export'])->name('invoices.export');

// for roles and permission
Route::resource('roles',RoleController::class);
Route::resource('users',UserController::class);

// reports
Route::get('invoices-reports', [ReportController::class, 'index'])->name('reports.invoices');
Route::post('invoices/reports/search', [ReportController::class, 'search'])->name('reports.invoices.search');

// notification
// routes/web.php
Route::post('/notifications/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.markAllAsRead');

Route::get('/notifications', [NotificationController::class, 'getNotifications'])->name('notifications.get');



Route::get('/{page}', [AdminController::class, 'index']);
