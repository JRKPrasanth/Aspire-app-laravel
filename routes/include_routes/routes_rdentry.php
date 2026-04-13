<?php

use App\Http\Controllers\Controller;
use App\Http\Controllers\RdentryController;
use App\Http\Controllers\RddashboardController;
use App\Http\Controllers\CreatemenuController;
use App\Http\Controllers\CreatebuttonController;


    
    Route::get('rd', [RdentryController::class, 'index'])->name('rd');
    Route::get('data', [RdentryController::class, 'getData'])->name('data');

    Route::get('create', [RdentryController::class, 'create'])->name('create');
    Route::post('store', [RdentryController::class, 'store'])->name('store');

    Route::get('edit/{id}', [RdentryController::class, 'edit'])->name('edit');
    Route::post('update/{id}', [RdentryController::class, 'update'])->name('update');

    Route::post('approve/{id}', [RdentryController::class, 'approve'])->name('approve');
    Route::post('reject/{id}', [RdentryController::class, 'reject'])->name('reject');

    Route::get('pdf/{id}', [RdentryController::class, 'pdf'])->name('pdf');

    Route::get('dashboard', [RddashboardController::class,'index'])->name('dashboard');