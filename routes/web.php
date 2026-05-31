<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\AdminEmployeeController;
use App\Http\Controllers\AdminTransactionController;
use App\Http\Controllers\AdminTaskController;
use App\Http\Controllers\AdminSalaryController;
use App\Http\Controllers\SmsSettingsController;
use App\Http\Controllers\EmployeeDashboardController;
use App\Http\Controllers\EmployeeTransactionController;
use App\Http\Controllers\EmployeeTaskController;
use App\Http\Controllers\EmployeeProfileController;

// Public routes
Route::get('/', fn() => redirect('/login'));
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Protected routes
Route::middleware(['auth'])->group(function () {
    Route::get('/calculator', function () {
        return view('calculator');
    });
});

// Admin routes (middleware: auth, admin)
Route::prefix('admin')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index']);
    Route::resource('/employees', AdminEmployeeController::class);
    Route::resource('/transactions', AdminTransactionController::class);
    Route::resource('/tasks', AdminTaskController::class);
    Route::get('/salary', [AdminSalaryController::class, 'index']);
    Route::post('/salary/pay/{employee}', [AdminSalaryController::class, 'pay']);
    Route::post('/salary/pay-all', [AdminSalaryController::class, 'payAll']);
    Route::get('/settings/sms', [SmsSettingsController::class, 'index']);
    Route::post('/settings/sms', [SmsSettingsController::class, 'update']);
    Route::post('/settings/sms/test', [SmsSettingsController::class, 'test']);
    Route::post('/settings/reset-data', [SmsSettingsController::class, 'resetData']);
    Route::patch('/expenses/{expense}/status', [App\Http\Controllers\AdminExpenseController::class, 'update']);
    Route::get('/report', [App\Http\Controllers\AdminReportController::class, 'index']);
    
    // Incomes
    Route::get('/incomes', [App\Http\Controllers\AdminIncomeController::class, 'index']);
    Route::post('/incomes', [App\Http\Controllers\AdminIncomeController::class, 'store']);
    
    // Invoices
    Route::get('/invoices', [\App\Http\Controllers\AdminInvoiceController::class, 'index']);
    Route::get('/invoices/{invoice}', [\App\Http\Controllers\AdminInvoiceController::class, 'show']);
    Route::post('/invoices/{invoice}/pay', [\App\Http\Controllers\AdminInvoiceController::class, 'pay']);
});

// Employee routes (middleware: auth, employee)
Route::prefix('employee')->middleware(['auth', 'employee'])->group(function () {
    Route::get('/dashboard', [EmployeeDashboardController::class, 'index']);
    Route::get('/transactions', [EmployeeTransactionController::class, 'index']);
    Route::get('/tasks', [EmployeeTaskController::class, 'index']);
    Route::patch('/tasks/{task}/status', [EmployeeTaskController::class, 'updateStatus']);
    Route::get('/profile', [EmployeeProfileController::class, 'index']);
    Route::resource('/expenses', App\Http\Controllers\EmployeeExpenseController::class)->only(['index', 'create', 'store']);
    Route::get('/report', [App\Http\Controllers\EmployeeReportController::class, 'index']);
    
    // Invoices
    Route::get('/invoices', [\App\Http\Controllers\EmployeeInvoiceController::class, 'index']);
    Route::get('/invoices/create', [\App\Http\Controllers\EmployeeInvoiceController::class, 'create']);
    Route::post('/invoices', [\App\Http\Controllers\EmployeeInvoiceController::class, 'store']);
});
