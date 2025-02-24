<?php

use App\Http\Controllers\PurchaseController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/purchase', [PurchaseController::class, 'index'])->name('purchase.index');

Route::get('/purchase/create', [PurchaseController::class, 'create'])->name('purchase.create');
Route::post('/purchase', [PurchaseController::class, 'store'])->name('purchase.store');
