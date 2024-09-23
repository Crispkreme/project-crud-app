<?php

use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;


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

    Route::get('/fetch/employee', [EmployeeController::class, 'fetchEmployee'])->name('fetch.employee');
    Route::get('/edit/employee', [EmployeeController::class, 'editEmployee'])->name('edit.employee');
    Route::post('/store/employee', [EmployeeController::class, 'storeEmployee'])->name('store.employee');
    Route::delete('/delete/employee', [EmployeeController::class, 'deleteEmployee'])->name('delete.employee');
});

require __DIR__.'/auth.php';
