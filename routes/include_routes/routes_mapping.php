<?php
	
use App\Http\Controllers\DistributormappingController;
use App\Http\Controllers\ProductmappingController;
use App\Http\Controllers\DistributormappingreportController;
use App\Http\Controllers\PayreportController;
use App\Http\Controllers\SupplierbasedcostreportController;

// Distributor Mapping
Route::get('distributorbeatmapping', [DistributormappingController::class, 'index'])->name('distributorbeatmapping');
Route::get('distributormappingdata', [DistributormappingController::class, 'indexbeat'])->name('distributorbeatmappingdata');
Route::get('distributormappingdata/{id}', [DistributormappingController::class, 'distributorbeatmappingdata'])->name('distributorbeatmappingdata_id');
Route::get('distributorbeatmappinggrid', [DistributormappingController::class, 'distributorbeatmappinggrid'])->name('distributorbeatmappinggrid');
Route::get('distributormappinggriddata', [DistributormappingController::class, 'distributormappinggriddata'])->name('distributormappinggriddata');
Route::get('distributormappinggrid', [DistributormappingController::class, 'distributormappinggrid'])->name('distributormappinggrid');
Route::post('distributormappingsave', [DistributormappingController::class, 'save'])->name('distributormappingsave');
Route::get('getdistrigridData', [DistributormappingController::class, 'getdistrigridData'])->name('getdistrigridData');
Route::get('getdistributorname/{id}', [DistributormappingController::class, 'getdistributorname'])->name('getdistributorname');
Route::get('distributormappingedit/{id}', [DistributormappingController::class, 'create'])->name('distributormappingedit');
Route::get('distributormappingdelete/{id}', [DistributormappingController::class, 'destroy'])->name('distributormappingdelete');
Route::get('distributormappingview/{id}', [DistributormappingController::class, 'show'])->name('distributormappingview');
Route::get('sodistributordetails/{id}', [DistributormappingController::class, 'sodistributordetails'])->name('sodistributordetails');
//PRODUCT MAPPING
Route::get('productmapping', [ProductmappingController::class, 'index'])->name('productmapping');
Route::get('getproductmappingdata', [ProductmappingController::class, 'getproductmappingdata'])->name('getproductmappingdata');
Route::get('productmappingedit/{id}', [ProductmappingController::class, 'create'])->name('productmappingedit');
Route::post('productmappingsave', [ProductmappingController::class, 'save'])->name('productmappingsave');
Route::get('productmappingdelete/{id}', [ProductmappingController::class, 'destroy'])->name('productmappingdelete');
Route::get('productmappingview/{id}', [ProductmappingController::class, 'show'])->name('productmappingview');
Route::get('getproductname/{id}', [ProductmappingController::class, 'getproductname'])->name('getproductname');
Route::get('soproductdetails/{id}', [ProductmappingController::class, 'soproductdetails'])->name('soproductdetails');
Route::get('getselectproductgridData', [ProductmappingController::class, 'getselectproductgridData'])->name('getselectproductgridData');
//MAPPING REPORTS
Route::get('distributormappingdetailrpt', [DistributormappingreportController::class, 'index'])->name('distributormappingdetailrpt');
Route::get('getdistributormappingdetailrpt', [DistributormappingreportController::class, 'getdistributormappingdetailrpt'])->name('getdistributormappingdetailrpt');
Route::get('productmappingdetailrpt', [DistributormappingreportController::class, 'productmaprptindex'])->name('productmappingdetailrpt');
Route::get('getproductmappingdetailrpt', [DistributormappingreportController::class, 'getproductmappingdetailrpt'])->name('getproductmappingdetailrpt');
// HRMS Allowance Settings Report
Route::get('hrmsallowancesettingsrpt', [PayreportController::class, 'hrmsallowancesettingsrpt'])->name('hrmsallowancesettingsrpt');
Route::get('gethrmsallowancesettingsrpt', [PayreportController::class, 'gethrmsallowancesettingsrpt'])->name('gethrmsallowancesettingsrpt');
// Product Account Structure Report
Route::get('prdaccstructurerpt', [SupplierbasedcostreportController::class, 'prdaccstructurerptindex'])->name('prdaccstructurerpt');
Route::get('getprdaccstructurerpt', [SupplierbasedcostreportController::class, 'getprdaccstructurerpt'])->name('getprdaccstructurerpt');
// Customer Account Structure Report
Route::get('cusaccstructurerpt', [SupplierbasedcostreportController::class, 'cusaccstructurerptindex'])->name('cusaccstructurerpt');
Route::get('getcusaccstructurerpt', [SupplierbasedcostreportController::class, 'getcusaccstructurerpt'])->name('getcusaccstructurerpt');
// Supplier Account Structure Report
Route::get('supaccstructurerpt', [SupplierbasedcostreportController::class, 'supaccstructurerptindex'])->name('supaccstructurerpt');
Route::get('getsupaccstructurerpt', [SupplierbasedcostreportController::class, 'getsupaccstructurerpt'])->name('getsupaccstructurerpt');
// Employee Account Structure Report
Route::get('empaccstructurerpt', [SupplierbasedcostreportController::class, 'empaccstructurerptindex'])->name('empaccstructurerpt');
Route::get('getempaccstructurerpt', [SupplierbasedcostreportController::class, 'getempaccstructurerpt'])->name('getempaccstructurerpt');
// Expense Account Structure Report
Route::get('expaccstructurerpt', [SupplierbasedcostreportController::class, 'expaccstructurerptindex'])->name('expaccstructurerpt');
Route::get('getexpaccstructurerpt', [SupplierbasedcostreportController::class, 'getexpaccstructurerpt'])->name('getexpaccstructurerpt');
