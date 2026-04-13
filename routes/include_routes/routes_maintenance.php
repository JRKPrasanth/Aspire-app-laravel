<?php

use App\Http\Controllers\TicketsystemmaintenanceController;
use App\Http\Controllers\MachinechecklistController;
use App\Http\Controllers\SpareController;
use App\Http\Controllers\SparequantityController;
use App\Http\Controllers\AddmachineController;
use App\Http\Controllers\AmcController;
use App\Http\Controllers\MachinefilesController;
use App\Http\Controllers\FrequencyController;
use App\Http\Controllers\AgencyController;
use App\Http\Controllers\VendorController;
use App\Http\Controllers\DrawingController;
use App\Http\Controllers\BreakdowntypeController;
use App\Http\Controllers\BreakdownseverityController;
use App\Http\Controllers\CheckController;
use App\Http\Controllers\MachinerptController;
use App\Http\Controllers\PreventivemaintenancereptController;
use App\Http\Controllers\MachinelogController;
use App\Http\Controllers\InitiatepmController;
use App\Http\Controllers\BreakdownmaintenanceController;

// Ticket System Maintenance
Route::get('ticketsystemdashboard', [TicketsystemmaintenanceController::class, 'dashboardindex'])->name('ticketsystemdashboard');
Route::get('ticketrequest', [TicketsystemmaintenanceController::class, 'ticketrequestindex'])->name('ticketrequest');
Route::get('ticketrequestData', [TicketsystemmaintenanceController::class, 'ticketrequestData'])->name('ticketrequestData');
Route::get('ticketrequestcreate', [TicketsystemmaintenanceController::class, 'create'])->name('ticketrequestcreate');

// Machine Checklist
Route::get('pmchecksheet', [MachinechecklistController::class, 'index'])->name('pmchecksheet');
Route::get('getchecklistdata', [MachinechecklistController::class, 'getGridData'])->name('getchecklistdata');
Route::get('machinechklistcreate', [MachinechecklistController::class, 'create'])->name('machinechklistcreate');
Route::get('machinechecklistedit/{id}', [MachinechecklistController::class, 'create'])->name('machinechecklistedit');
Route::get('machinechecklistview/{id}', [MachinechecklistController::class, 'show'])->name('machinechecklistview');
Route::post('machinechklistsave', [MachinechecklistController::class, 'save'])->name('machinechklistsave');

//Spare Management
Route::get('sparescreation', [SpareController::class, 'index'])->name('sparescreation');
Route::get('sparechkname', [SpareController::class, 'getCheckname'])->name('sparechkname');
Route::post('spare/save', [SpareController::class, 'save'])->name('spare.save');
Route::get('sparegrid', [SpareController::class, 'sparesgrids'])->name('sparegrid');
Route::get('sparedelete/{id}', [SpareController::class, 'destroy'])->name('sparedelete');
Route::get('spareedit/{id}', [SpareController::class, 'edit'])->name('spareedit');

// Spare Quantity
Route::get('sparequantity', [SparequantityController::class, 'index'])->name('sparequantity');
Route::post('sparequantitysave', [SparequantityController::class, 'save'])->name('sparequantitysave');
Route::get('sparegriddata', [SparequantityController::class, 'sparegriddata'])->name('sparegriddata');
Route::get('sparedelete/{id}', [SparequantityController::class, 'destroy'])->name('sparequantitydelete');

// Machine Routes
Route::get('addmachine', [AddmachineController::class, 'index'])->name('addmachine');
Route::get('createmachine', [AddmachineController::class, 'create'])->name('createmachine');
Route::get('getmachineData', [AddmachineController::class, 'getmachineData'])->name('getmachineData');
Route::get('createamc', [AmcController::class, 'index'])->name('createamc');
Route::get('assettransfer', [AddmachineController::class, 'assetindex'])->name('assettransfer');
Route::get('getmachinetbl', [AddmachineController::class, 'getmachinetbl'])->name('getmachinetbl');
Route::post('assettransfersave', [AddmachineController::class, 'assetsave'])->name('assettransfersave');

// Machine Files Routes
Route::get('filemachine', [MachinefilesController::class, 'index'])->name('filemachine');
Route::get('machinefilescreate', [MachinefilesController::class, 'create'])->name('machinefilescreate');
Route::get('machinefilesData', [MachinefilesController::class, 'getmachinefilesData'])->name('machinefilesData');
Route::get('machinefilesnamechk', [MachinefilesController::class, 'machinefilesnamechk'])->name('machinefilesnamechk');
Route::post('machinefilessave', [MachinefilesController::class, 'save'])->name('machinefilessave');
Route::get('machinefilesedit/{id}', [MachinefilesController::class, 'create'])->name('machinefilesedit');
Route::get('machinefilesdelete/{id}', [MachinefilesController::class, 'destroy'])->name('machinefilesdelete');
Route::get('machinefilescreate/{id}', [MachinefilesController::class, 'create'])->name('machinefilescreate');

// Frequency Routes
Route::get('frequency', [FrequencyController::class, 'index'])->name('frequency');
Route::post('frequencysave/{id}', [FrequencyController::class, 'save'])->name('frequencysave/{id}');
Route::get('frequencygrid', [FrequencyController::class, 'frequencygrid'])->name('frequencygrid');
Route::get('frequencydelete/{id}', [FrequencyController::class, 'destroy'])->name('frequencydelete');

//Agency Routes
Route::get('agency', [AgencyController::class, 'index'])->name('agency');
Route::get('agencycreate', [AgencyController::class, 'create'])->name('agencycreate');
Route::get('agencyData', [AgencyController::class, 'getagencyData'])->name('agencyData');
Route::get('agencynamechk', [AgencyController::class, 'agencynamechk'])->name('agencynamechk');
Route::post('agencysave', [AgencyController::class, 'save'])->name('agencysave');
Route::get('agencycreate/{id}', [AgencyController::class, 'create'])->name('agencycreate');
Route::get('agencydelete/{id}', [AgencyController::class, 'destroy'])->name('agencydelete');
Route::get('agencyview/{id}', [AgencyController::class, 'view'])->name('agencyview');

// Vendor Routes 
Route::get('newvendor', [VendorController::class, 'index'])->name('newvendor');
Route::get('getvendorData', [VendorController::class, 'getvendorData'])->name('getvendorData');
Route::get('createvendor/{id}', [VendorController::class, 'vendorform'])->name('createvendor');
Route::get('editvendor/{id}', [VendorController::class, 'vendorform'])->name('editvendor');
Route::get('vendordelete/{id}', [VendorController::class, 'vendordelete'])->name('vendordelete');
Route::get('vendorview/{id}', [VendorController::class, 'vendorview'])->name('vendorview');
Route::post('vendorsave', [VendorController::class, 'vendorsave'])->name('vendorsave');
Route::get('vendorcheckname', [VendorController::class, 'vendorcheckname'])->name('vendorcheckname');

//Drawing Files Routes
Route::get('drawingfiles', [DrawingController::class, 'index'])->name('drawingfiles');
Route::post('drawingsave', [DrawingController::class, 'store'])->name('drawingfiles');
Route::get('filegrid', [DrawingController::class, 'filegrids'])->name('filegrid');
Route::get('drawingdelete/{id}', [DrawingController::class, 'destroy'])->name('drawingfiles');

//Breakdown Type Routes
Route::get('breakdowntype', [BreakdowntypeController::class, 'index'])->name('breakdowntype');
Route::get('breakdowntypechkname', [BreakdowntypeController::class, 'getCheckname'])->name('breakdowntypechkname');
Route::post('breakdowntype/save', [BreakdowntypeController::class, 'save'])->name('breakdowntype');
Route::get('getbreakdowntypegridData', [BreakdowntypeController::class, 'getGridData'])->name('getbreakdowntypegridData');
Route::get('bkdwntypeedit/{id}', [BreakdowntypeController::class, 'edit'])->name('bkdwntypeedit');
Route::get('bkdwntypedelete/{id}', [BreakdowntypeController::class, 'destroy'])->name('bkdwntypedelete');

//Breakdown Severity Routes
Route::get('breakdownseverity', [BreakdownseverityController::class, 'index'])->name('breakdownseverity');
Route::post('severitysave', [BreakdownseverityController::class, 'save'])->name('severitysave');
Route::get('griddata', [BreakdownseverityController::class, 'severitygrids'])->name('griddata');
Route::get('severitydelete/{id}', [BreakdownseverityController::class, 'destroy'])->name('breakdownseverity');

//Checklist Routes
Route::get('checklist', [CheckController::class, 'index'])->name('checklist');
Route::post('checklistsave', [CheckController::class, 'save'])->name('checklistsave');
Route::get('checklistgrid', [CheckController::class, 'checklistgrid'])->name('checklistgrid');
Route::get('checkdelete/{id}', [CheckController::class, 'destroy'])->name('checkdelete');

// Machine Log
Route::get('machinelog', [MachinelogController::class, 'index'])->name('machinelog');
Route::get('getmachinelogData', [MachinelogController::class, 'getmachinelogData'])->name('getmachinelogData');
Route::get('machinelogcreate', [MachinelogController::class, 'machinelogcreate'])->name('machinelogcreate');
Route::get('machinelogcreate/{id}', [MachinelogController::class, 'machinelogcreate'])->name('machinelogcreate.id');
Route::get('machinelogedit/{id}', [MachinelogController::class, 'machinelogedit'])->name('machinelogedit');
Route::get('machinelogview/{id}', [MachinelogController::class, 'machinelogview'])->name('machinelogview');
Route::post('machinelogsave', [MachinelogController::class, 'save'])->name('machinelogsave');

// Machine Report
Route::get('mainmachinereport', [MachinerptController::class, 'index'])->name('mainmachinereport');
Route::get('mainmachinedetails', [MachinerptController::class, 'machinedetails'])->name('mainmachinedetails');
Route::get('preventivemaintenancereport', [PreventivemaintenancereptController::class, 'index'])->name('preventivemaintenancereport');
Route::get('datapreventivemaintenancereportget', [PreventivemaintenancereptController::class, 'datapreventivemaintenancereportget'])->name('datapreventivemaintenancereportget');
Route::get('breakdownmaintenancereport', [PreventivemaintenancereptController::class, 'breakdownindex'])->name('breakdownmaintenancereport');
Route::get('databreakdownmaintenancereportget', [PreventivemaintenancereptController::class, 'databreakdownmaintenancereportget'])->name('databreakdownmaintenancereportget');
Route::get('machinelogdetailsrpt', [MachinelogController::class, 'machinelogindex'])->name('machinelogdetailsrpt');
Route::get('getmachinelogdetails', [MachinelogController::class, 'getmachinelogdetails'])->name('getmachinelogdetails');
Route::get('machineconsolidatedreport', [MachinelogController::class, 'machinelogindex1'])->name('machineconsolidatedreport');
Route::get('getmachineconsolidatedreport', [MachinelogController::class, 'getmachineconsolidatedreport'])->name('getmachineconsolidatedreport');

// INITIATE PM
Route::get('initiatepm', [InitiatepmController::class, 'index'])->name('initiatepm');
Route::get('initiatepmData', [InitiatepmController::class, 'initiatepmData'])->name('initiatepmData');
Route::get('initiatepmcreate/{id}', [InitiatepmController::class, 'create'])->name('initiatepmcreate');
Route::post('initiatepmsave', [InitiatepmController::class, 'save'])->name('initiatepmsave');
Route::get('pmedit', [InitiatepmController::class, 'pmedit'])->name('pmedit');
Route::get('pmupdate', [InitiatepmController::class, 'pmupdate'])->name('pmupdate');

// USER CLEARANCE
Route::get('pmclearance', [InitiatepmController::class, 'pmclearanceindex'])->name('pmclearance');
Route::get('pmclearanceData', [InitiatepmController::class, 'pmclearanceData'])->name('pmclearanceData');
Route::get('pmclearancecreate/{id}', [InitiatepmController::class, 'pmclearancecreate'])->name('pmclearancecreate');
Route::post('pmclearancesave', [InitiatepmController::class, 'pmclearancesave'])->name('pmclearancesave');

// AGENCY ALLOCATION
Route::get('pmagencyallocation', [InitiatepmController::class, 'pmagencyallocationindex'])->name('pmagencyallocation');
Route::get('pmagencyallocationData', [InitiatepmController::class, 'pmagencyallocationData'])->name('pmagencyallocationData');
Route::get('pmagencyallocationcreate/{id}', [InitiatepmController::class, 'pmagencyallocationcreate'])->name('pmagencyallocationcreate');
Route::post('pmagencyallocationsave', [InitiatepmController::class, 'pmagencyallocationsave'])->name('pmagencyallocationsave');

// MONTHLY CHECK
Route::get('pmmonthlycheck', [InitiatepmController::class, 'pmmonthlycheckindex'])->name('pmmonthlycheck');
Route::get('pmmonthlycheckData', [InitiatepmController::class, 'pmmonthlycheckData'])->name('pmmonthlycheckData');
Route::get('pmmonthlycheckcreate/{id}', [InitiatepmController::class, 'pmmonthlycheckcreate'])->name('pmmonthlycheckcreate');
Route::post('pmmonthlychecksave', [InitiatepmController::class, 'pmmonthlychecksave'])->name('pmmonthlychecksave');

// MONTHLY CHECK APPROVAL
Route::get('pmmonthlycheckapproval', [InitiatepmController::class, 'pmmonthlycheckindex'])->name('pmmonthlycheckapproval');
Route::get('pmmonthlycheckapprovalData', [InitiatepmController::class, 'pmmonthlycheckapprovalData'])->name('pmmonthlycheckapprovalData');
Route::get('pmmonthlycheckapprove/{id}', [InitiatepmController::class, 'pmmonthlycheckcreate'])->name('pmmonthlycheckapprove');

// Create Issue
Route::get('createissue', [BreakdownmaintenanceController::class, 'index'])->name('createissue');
Route::get('issuecreate', [BreakdownmaintenanceController::class, 'create'])->name('createissue');
Route::get('issueData', [BreakdownmaintenanceController::class, 'issueData'])->name('createissue');
Route::post('issuesave', [BreakdownmaintenanceController::class, 'save'])->name('issuesave');
Route::get('userview/{id}', [BreakdownmaintenanceController::class, 'view'])->name('createissue');
Route::get('sopview/{id}', [BreakdownmaintenanceController::class, 'show'])->name('sopview');

// ALLOCATE ENGINEER
Route::get('allocateengineer', [BreakdownmaintenanceController::class, 'index'])->name('allocateengineer');
Route::get('engineerallocate/{id}', [BreakdownmaintenanceController::class, 'create'])->name('allocateengineer');

// ALLOCATE TECHNICIAN
Route::get('allocatetechnician', [BreakdownmaintenanceController::class, 'index'])->name('allocatetechnician');

// RAISE REQUEST
Route::get('requestraise', [BreakdownmaintenanceController::class, 'index'])->name('requestraise');

// APPROVE REQUEST
Route::get('approverequest', [BreakdownmaintenanceController::class, 'index'])->name('approverequest');

// CLOSE REQUEST
Route::get('closerequest', [BreakdownmaintenanceController::class, 'index'])->name('closerequest');
Route::get('getspareqty/{id}', [BreakdownmaintenanceController::class, 'getspareqty'])->name('getspareqty');

// SOP UPLOAD
Route::get('sopupload', [BreakdownmaintenanceController::class, 'index'])->name('sopupload');






