<?php
	
use App\Http\Controllers\HrmsHomeController;	
use App\Http\Controllers\PurchasedashboardController;		
use App\Http\Controllers\OperationperformancelogController;
use App\Http\Controllers\OveralloperationperformancerptController;
use App\Http\Controllers\operationProductController;
use App\Http\Controllers\OperationanalyserptController;
use App\Http\Controllers\MachinemaintenancelogController;
use App\Http\Controllers\ProductmaintenancelogController;
use App\Http\Controllers\ProductiondashboardController;
use App\Http\Controllers\ProductionproductrptController;
use App\Http\Controllers\ProductionperformdbController;
use App\Http\Controllers\OverallproductionrptController;
use App\Http\Controllers\AccountsdashboardController;


// HRMS DASHBOARD 
Route::get('hrmshome', [HrmsHomeController::class, 'index'])->name('hrmshome');
Route::get('leavebalmail/{id}/{start_date}/{end_date}', [HrmsHomeController::class, 'sendEmail'])->name('hrmshome');
Route::get('attendance-events', [HrmsHomeController::class, 'getAttendanceEvents'])->name('attendance-events');	
	
//PURCHASE DASHBOARD
Route::get('purchasedashboard', [PurchasedashboardController::class, 'index'])->name('purchasedashboard');
Route::get('purchasedashboardproduct', [PurchasedashboardController::class, 'Productbase'])->name('purchasedashboardproduct');
Route::get('purchasedashboardqty-val', [PurchasedashboardController::class, 'Topqtyvalue'])->name('purchasedashboardqtyval');
Route::get('purchasedashboardpricetrend', [PurchasedashboardController::class, 'Pricetrend'])->name('purchasedashboardpricetrend');
Route::get('purchasedashboardpackdelay', [PurchasedashboardController::class, 'Packdelay'])->name('purchasedashboardpackdelay');
Route::get('purchasedashboardpricediff', [PurchasedashboardController::class, 'Pricediffer'])->name('purchasedashboardpricediff');
Route::get('purchaseproductexceptionrpt', [PurchasedashboardController::class, 'Exceptionrpt'])->name('purchaseproductexceptionrpt');
Route::get('consumptionquantityreport', [PurchasedashboardController::class, 'Consumptiorpt'])->name('consumptionquantityreport');
Route::get('purchaseproductmostspend', [PurchasedashboardController::class, 'Mostspendpro'])->name('purchaseproductmostspend');
Route::get('mostvalueproduct', [PurchasedashboardController::class, 'Mostvalproduct'])->name('mostvalueproduct');
Route::get('rejectedpuritems', [PurchasedashboardController::class, 'Rejecteditems'])->name('rejectedpuritems');
Route::get('movementreport', [PurchasedashboardController::class, 'Movementrpt'])->name('movementreport');
Route::get('purchase-supplier-summary', [App\Http\Controllers\PurchasedashboardController::class, 'supplierSummary'])->name('purchase.supplier.summary');
Route::get('suppliersummary', [App\Http\Controllers\PurchasedashboardController::class, 'supplierSummary'])
    ->name('suppliersummary');
Route::get('purchase-product-summary', [App\Http\Controllers\PurchasedashboardController::class, 'productSummary'])->name('purchase.product.summary');
Route::get('productsummary', [App\Http\Controllers\PurchasedashboardController::class, 'productSummary'])
    ->name('productsummary');
Route::get('productissuedelay', [App\Http\Controllers\PurchasedashboardController::class, 'productissuedelay'])
    ->name('productissuedelay');


// OPERATION DASHBOARD
Route::get('operationperformancelogreport', [OperationperformancelogController::class, 'operationperformanceindex'])->name('operationperformance');
Route::get('overalloperationperformancereport', [OveralloperationperformancerptController::class, 'Overallrpt'])->name('operationperformance');
Route::get('employewrkpopup', [OperationanalyserptController::class, 'employeehrspopup'])->name('employewrkpopup');
Route::get('employemachinewrkpopup', [OveralloperationperformancerptController::class, 'Emppropopup'])->name('employemachinewrkpopup');
Route::get('employeprocesspopup', [OveralloperationperformancerptController::class, 'Emprocess'])->name('employeprocesspopup');
Route::get('employetypepopup', [OperationperformancelogController::class, 'Emptypedtls'])->name('employetypepopup');
Route::get('employeproductpopup', [OperationperformancelogController::class, 'Emproductdtls'])->name('employeproductpopup');
Route::get('employemachinepopup', [OperationperformancelogController::class, 'EmpMachinedtls'])->name('employemachinepopup');
Route::get('employecatepopup', [OperationperformancelogController::class, 'EmpCategorydtls'])->name('employecatepopup');
Route::get('employepropopup', [OperationperformancelogController::class, 'Empprodtls'])->name('employepropopup');
Route::get('totalwrkemp', [OperationperformancelogController::class, 'Emptotalwrk'])->name('totalwrkemp');
Route::get('operationprobasereport', [operationProductController::class, 'Productbase'])->name('operationprobasereport');
Route::get('protypepopup', [operationProductController::class, 'Producttype'])->name('protypepopup');
Route::get('machinepropopup', [operationProductController::class, 'Machinepopup'])->name('machinepropopup');
Route::get('operationanalyspopup', [OperationanalyserptController::class, 'Analysepopup'])->name('operationanalyspopup');
Route::get('operationanalyserpt', [OperationanalyserptController::class, 'Analysepro'])->name('operationanalyserpt');
// MAINTAINANCE DASHBOARD
Route::get('machinemintenancelogreport', [MachinemaintenancelogController::class, 'machinelogdtsindex'])->name('machinemintenancelogreport');
Route::get('productmaintenancelogreport', [ProductmaintenancelogController::class, 'productlog'])->name('productmaintenancelogreport');
// PRODUCTION DASHBOARD
Route::get('productiondashboard', [ProductiondashboardController::class, 'Analyseproduction'])->name('productiondashboard');
Route::get('productionproductdashboard', [ProductionproductrptController::class, 'Productbase'])->name('productionproductdashboard');
Route::get('productionprotype', [ProductionproductrptController::class, 'Producttype'])->name('productionprotype');
Route::get('productionmachinepopup', [ProductionproductrptController::class, 'Machinepopup'])->name('productionmachinepopup');
Route::get('productionformancelogreport', [ProductionperformdbController::class, 'operationperformanceindex'])->name('productionformancelogreport');
Route::get('productionprotypepopup', [ProductionperformdbController::class, 'Emproductdtls'])->name('productionprotypepopup');
Route::get('productionmacpopup', [ProductionperformdbController::class, 'EmpMachinedtls'])->name('productionmacpopup');
Route::get('productioncatpopup', [ProductionperformdbController::class, 'EmpCategorydtls'])->name('productioncatpopup');
Route::get('productionpropopup', [ProductionperformdbController::class, 'Emppronamedtls'])->name('productionpropopup');
Route::get('overallprodctionperformancereport', [OverallproductionrptController::class, 'Overallrpt'])->name('overallprodctionperformancereport');
Route::get('productionemployewrk}', [OverallproductionrptController::class, 'employeehrspopup'])->name('productionemployewrk');
Route::get('productionemployemachinewrk}', [OverallproductionrptController::class, 'Emppropopup'])->name('productionemployemachinewrk');
Route::get('productionanalyspopup', [ProductiondashboardController::class, 'Analysepopup'])->name('productionanalyspopup');

// Accounts Dashbord
Route::get('accountsdashboard', [AccountsdashboardController::class, 'index'])->name('accountsdashboard');
