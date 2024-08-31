<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmployeeController;

Route::get('/', [EmployeeController::class, 'index']);
Route::get('/fetch-employee', [EmployeeController::class, 'fetchEmployee'])->name('fetch-employee');
Route::post('/store', [EmployeeController::class, 'store'])->name('store');
Route::post('/edit/{id}', [EmployeeController::class, 'edit'])->name('edit');
Route::delete('/delete/{id}', [EmployeeController::class, 'delete'])->name('delete');
