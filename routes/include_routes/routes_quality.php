<?php

use App\Http\Controllers\QualityindentController;
use App\Http\Controllers\PurchaseqcController;
use App\Http\Controllers\QualitycheckController;
use App\Http\Controllers\MovetoinventoryController;
use App\Http\Controllers\ProductionmovetoinventoryController;


// Quality Indent
Route::get('qualityindent', [QualityindentController::class, 'index'])->name('qualityindent');
Route::get('qualityindentdata', [QualityindentController::class, 'qualityindentdata'])->name('qualityindentdata');
Route::get('qualityindentcreate/{id}', [QualityindentController::class, 'create'])->name('qualityindentcreate');
Route::get('qualityindentshow/{id}', [QualityindentController::class, 'show'])->name('qualityindentshow');
Route::post('qualityindentsave', [QualityindentController::class, 'save'])->name('qualityindentsave');
Route::get('materialissueindentsave', [QualityindentController::class, 'materialissueindentsave'])->name('materialissueindentsave');
Route::get('indentreducesave', [QualityindentController::class, 'indentreducesave'])->name('indentreducesave');

// purchase QC
Route::get('purchaseqc', [PurchaseqcController::class, 'index'])->name('purchaseqc');
Route::get('getQCData', [PurchaseqcController::class, 'QCData'])->name('purchaseqc');
Route::get('qcgrntable', [PurchaseqcController::class, 'grntable'])->name('purchaseqc');
Route::get('/getGrntabledata', [PurchaseqcController::class, 'Grndata'])->name('getGrntabledata');
Route::get('qualitychecking/{id}/{id1}', [PurchaseqcController::class, 'create'])->name('qualitychecking');
Route::get('qualitychecking/{id}/{id1}/{id2}/{type}', [PurchaseqcController::class, 'create'])->name('qualitychecking');
Route::post('qcsave', [PurchaseqcController::class, 'save']);
Route::get('purchaseqcedit/{id}', [PurchaseqcController::class, 'edit']);
Route::get('purchaseqcview/{id}/{ids}', [PurchaseqcController::class, 'edit']);
Route::get('quality_view/{id}/{ids}', [PurchaseqcController::class, 'edit']);
Route::get('productqcserialdetails/{id}', [PurchaseqcController::class, 'productqcserialdetails'])->name('productqcserialdetails');
Route::get('productqcspecdetails/{id}', [PurchaseqcController::class, 'productqcspecdetails'])->name('productqcspecdetails');

// Qc approve
Route::get('qcapproval', [PurchaseqcController::class, 'index'])->name('qcapproval');
Route::get('qcapprovalcreate/{id}', [PurchaseqcController::class, 'edit'])->name('qcapproval');
Route::get('qcapprovalsave', [PurchaseqcController::class, 'qcapprovalsave']);
Route::get('qcapprovalstatussave', [PurchaseqcController::class, 'qcapprovalstatussave']);
Route::get('qaapproval', [QualitycheckController::class, 'index'])->name('qaapproval');

// Move to Inventory
Route::get('movetoinventory', [MovetoinventoryController::class, 'index'])->name('movetoinventory');
Route::get('/MovinventoryData', [MovetoinventoryController::class, 'MovinventoryData'])->name('MovinventoryData');
Route::get('movetoinventoryData', [MovetoinventoryController::class, 'movetoinventorydata'])->name('movetoinventoryData');
Route::get('movetoinvform/{id}/{check}', [MovetoinventoryController::class, 'create'])->name('movetoinvform');
Route::get('movetoinvformpopup/{id}/{check}', [MovetoinventoryController::class, 'popupcreate'])->name('movetoinvformpopup');
Route::get('movetoinventoryupdate/{id}/{ids}/{check}', [MovetoinventoryController::class, 'movetoinventoryupdate'])->name('movetoinventoryupdate');
Route::post('inventorysave', [MovetoinventoryController::class, 'inventorysave'])->name('inventorysave');

// QA Stage Approval
Route::get('qualitycheck', [QualitycheckController::class, 'index'])->name('qualitycheck');
Route::get('totalqualitycheck', [QualitycheckController::class, 'index'])->name('totalqualitycheck');
Route::get('qasubmitstageappData', [QualitycheckController::class, 'qasubmitstageappData'])->name('qasubmitstageappData');
Route::get('qualitycheckcreate/{id}/{type}', [QualitycheckController::class, 'create'])->name('qualitycheckcreate');
Route::get('qualitycheckappcreate/{id}/{type}', [QualitycheckController::class, 'create'])->name('qualitycheckappcreate'); // changed to match 

// Production Qc
Route::get('qcanalytical', [QualitycheckController::class, 'index'])->name('qcanalytical');
Route::post('qualitychecksave', [QualitycheckController::class, 'save'])->name('qualitychecksave');
Route::get('qualitycheckview', [QualitycheckController::class, 'viewindex'])->name('qualitycheckview');
Route::get('qualitycheckview/{id}', [QualitycheckController::class, 'view'])->name('qualitycheckviewid');
Route::get('qualitycheckprint/{id}', [QualitycheckController::class, 'print'])->name('qualitycheckprint');
Route::get('qualitycheckData', [QualitycheckController::class, 'qualitycheckData'])->name('qualitycheckData');

// Move to Inventory (Production)
Route::get('prodmovetoinventory', [ProductionmovetoinventoryController::class, 'index'])->name('prodmovetoinventory');
Route::get('msqasubmitstageappData', [ProductionmovetoinventoryController::class, 'qasubmitstageappData'])->name('msqasubmitstageappData');
Route::get('movetocreate/{id}', [ProductionmovetoinventoryController::class, 'create'])->name('movetocreate');
Route::post('pmovetoinventorysave', [ProductionmovetoinventoryController::class, 'save'])->name('pmovetoinventorysave');

// Material QC
Route::get('qualitymr', [PurchaseqcController::class, 'index'])->name('qualitymr');
Route::get('getMRData', [PurchaseqcController::class, 'MRData'])->name('getMRData');
Route::get('MRQCData', [PurchaseqcController::class, 'MRQCData'])->name('MRQCData');
Route::get('mrqctable', [PurchaseqcController::class, 'mrqctable'])->name('mrqctable');
Route::get('mrcqchecking/{id}', [PurchaseqcController::class, 'create'])->name('mrcqchecking');
Route::get('mrapproval', [PurchaseqcController::class, 'index'])->name('mrapproval');
Route::get('mrapprovalcreate/{id}', [PurchaseqcController::class, 'edit'])->name('mrapprovalcreate');





