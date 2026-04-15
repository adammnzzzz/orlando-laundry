<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\TypeOfServiceController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('auth.login');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // ADMIN ROUTES
    Route::middleware(['level:Admin'])->group(function() {
        Route::resource('services', TypeOfServiceController::class);
        Route::post('users/{user}/reset-password', [UserController::class, 'resetPasswordDefault'])->name('users.reset-password');
        Route::resource('users', UserController::class);
    });

    // OPERATOR ROUTES
    Route::middleware(['level:Operator,Admin'])->group(function() {
        Route::resource('customers', CustomerController::class);
        Route::get('/transactions/create', [TransactionController::class, 'create'])->name('transactions.create');
        Route::post('/transactions', [TransactionController::class, 'store'])->name('transactions.store');
        Route::get('/transactions/{transaction}', [TransactionController::class, 'show'])->name('transactions.show');
        
        // PENGAMBILAN
        Route::get('/pickups', [TransactionController::class, 'pickups'])->name('transactions.pickups');
        Route::patch('/transactions/{transaction}/complete', [TransactionController::class, 'complete'])->name('transactions.complete');
    });

    // PIMPINAN ROUTES
    Route::middleware(['level:Pimpinan,Admin'])->group(function() {
        Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    });
});

require __DIR__.'/auth.php';
