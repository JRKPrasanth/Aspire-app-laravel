<?php

use App\Http\Controllers\MachineController;
use App\Http\Controllers\MachineequipmentshdrController;
use App\Http\Controllers\MaterialbomController;
use App\Http\Controllers\MaterialbomuploadController;
use App\Http\Controllers\JobworkoutorderController;
use App\Http\Controllers\WorkorderController;
use App\Http\Controllers\productionplananalyseController;
use App\Http\Controllers\ProductionplanController;
use App\Http\Controllers\JobcardController;
use App\Http\Controllers\qasubmitstageController;
use App\Http\Controllers\materialissueController;
use App\Http\Controllers\MaterialreceivehdrController;
use App\Http\Controllers\ProcesshdrController;
use App\Http\Controllers\PurchaseqcController;
use App\Http\Controllers\ProductionmovetoinventoryController;
use App\Http\Controllers\ProductionindenthdrController;
use App\Http\Controllers\JobactivityController;
use App\Http\Controllers\MaterialissueaginstjcController;
use App\Http\Controllers\MaterialreackknowledgeController;

// LARAVEL 8 ROUTES

// Machine
Route::get('machine', [MachineController::class, 'index'])->name('materialbom');
Route::get('machinecreate', [MachineController::class, 'create'])->name('machinecreate');
Route::get('machineedit/{id}', [MachineController::class, 'create'])->name('machineedit');
Route::get('machineview/{id}', [MachineController::class, 'show'])->name('machineview');
Route::get('machinedelete/{id}', [MachineController::class, 'destroy'])->name('machinedelete');
Route::get('machineData', [MachineController::class, 'getmachineData'])->name('machineData');
Route::get('machinenamechk', [MachineController::class, 'machinenamechk'])->name('machinenamechk');
Route::post('machinesave', [MachineController::class, 'save'])->name('machinesave');

// Material Equipments

Route::get('materialequipments', [MachineequipmentshdrController::class, 'index'])->name('materialequipments');
Route::get('materialequipmentsData', [MachineequipmentshdrController::class, 'getmaterialequipmentsData'])->name('materialequipmentsData');
Route::get('materialequipmentscreate', [MachineequipmentshdrController::class, 'create'])->name('materialequipmentscreate');
Route::get('materialequipmentsedit/{id}', [MachineequipmentshdrController::class, 'create'])->name('materialequipmentsedit');
Route::get('materialequipmentsview/{id}', [MachineequipmentshdrController::class, 'show'])->name('materialequipmentsview');
Route::get('prdmachinedetails/{id}', [MachineequipmentshdrController::class, 'prdmachinedetails'])->name('prdmachinedetails');
Route::post('materialequipmentssave', [MachineequipmentshdrController::class, 'save'])->name('materialequipmentssave');

// Material BOM
Route::get('materialbom', [MaterialbomController::class, 'index'])->name('materialbom');
Route::get('materialbomcreate', [MaterialbomController::class, 'create'])->name('materialbomcreate');
Route::get('materialbomedit/{id}', [MaterialbomController::class, 'create'])->name('materialbomedit');
Route::get('materialbomview/{id}', [MaterialbomController::class, 'show'])->name('materialbomview');
Route::get('materialbomdelete/{id}', [MaterialbomController::class, 'destroy'])->name('materialbomdelete');
Route::get('materialbomData', [MaterialbomController::class, 'getmaterialbomData'])->name('materialbomData');
Route::post('materialbomsave', [MaterialbomController::class, 'save'])->name('materialbomsave');
Route::get('productuomdetails/{id}', [MaterialbomController::class, 'productuomdetails'])->name('productuomdetails');
Route::get('materialbomchk', [MaterialbomController::class, 'materialbomchk'])->name('materialbomchk');

// materialbomupload
Route::get('materialbomupload', [MaterialbomuploadController::class, 'index'])->name('materialbomupload');
Route::get('getMaterialbomuploadData', [MaterialbomuploadController::class, 'getMaterialbomuploadData'])->name('getMaterialbomuploadData');
Route::get('getBomvalidate', [MaterialbomuploadController::class, 'getBomvalidate'])->name('getBomvalidate');
Route::post('materialbomuploaddata', [MaterialbomuploadController::class, 'Uploadexcel'])->name('materialbomuploaddata');
Route::get('materialbomuploadedit/{id}', [MaterialbomuploadController::class, 'create'])->name('materialbomuploadedit');
Route::post('materialbomuploadsave', [MaterialbomuploadController::class, 'save'])->name('materialbomuploadsave');

// Jobwork Out order
Route::get('jobworkoutorder', [JobworkoutorderController::class, 'index'])->name('jobworkoutorder');
Route::get('jobworkoutordercreate/{id}', [JobworkoutorderController::class, 'create'])->name('jobworkoutordercreate');
Route::post('jobworkoutordersave', [JobworkoutorderController::class, 'save'])->name('jobworkoutordersave');
Route::get('getjobworkoutorderData', [JobworkoutorderController::class, 'getjobworkoutorderData'])->name('getjobworkoutorderData');
Route::get('jobworkoutorderdelete/{id}', [JobworkoutorderController::class, 'delete'])->name('jobworkoutorderdelete');
Route::get('jobworkoutorderedit/{id}', [JobworkoutorderController::class, 'create'])->name('jobworkoutorderedit');
Route::get('jobworkoutorderview/{id}', [JobworkoutorderController::class, 'show'])->name('jobworkoutorderview');
Route::get('jobworkoutorderuom/{id}', [JobworkoutorderController::class, 'uomcode'])->name('jobworkoutorderuom');

// Workorder
Route::get('workorder', [WorkorderController::class, 'index'])->name('Workorder');
Route::get('workordercreate/{id}', [WorkorderController::class, 'create'])->name('workordercreate');
Route::post('workordersave', [WorkorderController::class, 'save'])->name('workordersave');
Route::get('getWorkorderData', [WorkorderController::class, 'getWorkorderDatas'])->name('getWorkorderData');
Route::get('workorderedit/{id}', [WorkorderController::class, 'create'])->name('workorderedit');
Route::get('workorderview/{id}', [WorkorderController::class, 'show'])->name('workorderview');
Route::get('workorderuom/{id}', [WorkorderController::class, 'uomcode'])->name('workorderuom');
Route::get('editcheck/{id}', [WorkorderController::class, 'editcheck'])->name('editcheck');

// Production Plan Analyse
Route::get('packingproductionplananalyse', [productionplananalyseController::class, 'index'])->name('packingproductionplananalyse');
Route::get('productionplananalyse', [productionplananalyseController::class, 'index'])->name('productionplananalyse');
Route::get('getWorkorderDatas', [productionplananalyseController::class, 'getWorkorderData'])->name('getWorkorderDatas');
Route::get('getWorkorderproductData', [productionplananalyseController::class, 'getWorkorderproductData'])->name('getWorkorderproductData');

//mrp plan
Route::get('mrpplan', [productionplananalyseController::class, 'indexmrp'])->name('mrpplan');
Route::get('createmrplan', [productionplananalyseController::class, 'create'])->name('createmrplan');


// Workorder From SO
Route::get('workorderfromso', [WorkorderController::class, 'soindex'])->name('workorderfromso');
Route::get('sogriddata', [WorkorderController::class, 'sogriddata'])->name('sogriddata');
Route::get('getWorkorderstatusData', [WorkorderController::class, 'getWorkorderstatusData'])->name('getWorkorderstatusData');
Route::get('workorderstatus', [WorkorderController::class, 'woindex'])->name('workorderstatus');


// Production Plan
Route::get('productionplancreate/{id}', [ProductionplanController::class, 'create'])->name('productionplancreate');
Route::get('productionplanedit/{id}', [ProductionplanController::class, 'create'])->name('productionplanedit');
Route::get('productionplan', [ProductionplanController::class, 'index'])->name('productionplan');
Route::get('bomdetails/{id}', [ProductionplanController::class, 'bomdetails'])->name('bomdetails');
Route::get('subbomdetails/{id}', [ProductionplanController::class, 'subbomdetails'])->name('subbomdetails');
Route::get('subbomdetail/{id}', [ProductionplanController::class, 'subbomdetail'])->name('subbomdetail');
Route::get('getproductionplanData', [ProductionplanController::class, 'getproductionplanData'])->name('getproductionplanData');
Route::post('productionplansave', [ProductionplanController::class, 'save'])->name('productionplansave');
Route::get('planproductdetails/{id}/{wid}', [ProductionplanController::class, 'planproductdetails'])->name('planproductdetails');
Route::get('machinetypedetails/{id}/{ptid}', [ProductionplanController::class, 'machinetypedetails'])->name('machinetypedetails');
Route::get('getprdtype/{id}', [JobcardController::class, 'getprdtype'])->name('getprdtype');
Route::get('productionplanlines/{hid}/{pid}', [ProductionplanController::class, 'productionplanlines'])->name('productionplanlines');
Route::get('jobbomdetails/{hid}/{pid}', [ProductionplanController::class, 'jobbomdetails'])->name('jobbomdetails');
Route::get('planview/{id}', [ProductionplanController::class, 'planview'])->name('planview');
Route::get('updateplanstatus/{id}', [ProductionplanController::class, 'updatestatus'])->name('updateplanstatus');
Route::get('approveplandetails/{id}', [ProductionplanController::class, 'approveplandetails'])->name('approveplandetails');

// Plan Approvals
Route::get('packingplanapproval', [ProductionplanController::class, 'index'])->name('packingplanapproval');
Route::get('planapproval', [ProductionplanController::class, 'index'])->name('planapproval');
Route::get('planapprovalcreate/{id}', [ProductionplanController::class, 'approval'])->name('planapprovalcreate');
Route::get('planschedulemail/{id}', [ProductionplanController::class, 'planschedulemail'])->name('planapproval');

// Packing Job Card
Route::get('packingjobcard', [ProductionplanController::class, 'index'])->name('packingjobcard');

//Job Card
Route::get('jobcard', [JobcardController::class, 'index'])->name('jobcard');
Route::get('packingjobcardstatus', [JobcardController::class, 'index'])->name('packingjobcardstatus');
Route::get('jobcardcreate/{id}', [JobcardController::class, 'create'])->name('jobcardcreate');
Route::get('jobcardedit/{id}', [JobcardController::class, 'create'])->name('jobcardedit');
Route::get('jobcardview/{id}', [JobcardController::class, 'show'])->name('jobcardview');
Route::post('jobcardsave', [JobcardController::class, 'save'])->name('jobcardsave');
Route::post('traydryersave/{id}', [JobcardController::class, 'traydryersave'])->name('traydryersave');
Route::get('jobcardfromplan/{id}', [JobcardController::class, 'create'])->name('jobcardfromplan');
Route::get('getjobcardData', [JobcardController::class, 'getjobcardData'])->name('getjobcardData');
Route::get('machinedetails/{id}', [JobcardController::class, 'machinedetails'])->name('machinedetails');
Route::get('machinehourdetails/{id}/{id1}/{id2}', [JobcardController::class, 'machinehourdetails'])->name('machinehourdetails');
Route::get('machinepmdetails/{id}', [JobcardController::class, 'machinepmdetails'])->name('machinepmdetails');
Route::post('movetostore/{jobid}', [JobcardController::class, 'movetostore'])->name('movetostore');
Route::get('moveqty/{id}', [JobcardController::class, 'moveqty'])->name('moveqty');
Route::get('prsdetails/{pid}', [JobcardController::class, 'prsdetails'])->name('prsdetails');
Route::get('employeeworkdetails/{id}', [JobcardController::class, 'employeeworkdetails'])->name('employeeworkdetails');
Route::get('jobcardcloedit', [JobcardController::class, 'jobcardcloedit'])->name('jobcardcloedit');
Route::get('jobcloseupdate', [JobcardController::class, 'jobcloseupdate'])->name('jobcloseupdate');

// QA Submit Stage 
Route::get('qasubmitstage', [qasubmitstageController::class, 'index'])->name('qasubmitstage');
Route::get('packingqasubmitstage', [qasubmitstageController::class, 'index'])->name('packingqasubmitstage');
Route::get('qasubmitstagecreate/{id}', [qasubmitstageController::class, 'create'])->name('qasubmitstagecreate');
Route::post('qasubmitstagesave', [qasubmitstageController::class, 'save'])->name('qasubmitstagesave');
Route::get('prdmtlsubinventorydetails', [qasubmitstageController::class, 'prdmtlsubinventorydetails'])->name('prdmtlsubinventorydetails');
Route::get('mtlbatchdetails/{id}', [qasubmitstageController::class, 'mtlbatchdetails'])->name('mtlbatchdetails');
Route::get('getqasubmitstageData', [qasubmitstageController::class, 'getqasubmitData'])->name('getqasubmitstageData');
Route::get('qasubmitstagedelete/{id}', [qasubmitstageController::class, 'delete'])->name('qasubmitstagedelete');
Route::get('employeedetails/{id}', [qasubmitstageController::class, 'employeedetails'])->name('employeedetails');
Route::get('qasubmitstageview/{id}', [qasubmitstageController::class, 'show'])->name('qasubmitstageview');
Route::get('jobcardcompletiondetails', [qasubmitstageController::class, 'index'])->name('jobcardcompletiondetails');
Route::get('packingjobcardcompletiondetails', [qasubmitstageController::class, 'index'])->name('packingjobcardcompletiondetails');
Route::get('getallmachinedetails/{id}', [qasubmitstageController::class, 'getallmachinedetails'])->name('getallmachinedetails');

//Material Issues
Route::get('materialissues', [MaterialissueController::class, 'index'])->name('materialissues');
Route::get('packingmaterialissues', [MaterialissueController::class, 'index'])->name('packingmaterialissues');
Route::get('materialissuescreate/{id}', [MaterialissueController::class, 'create'])->name('materialissuescreate');
Route::post('materialissuesave', [MaterialissueController::class, 'save'])->name('materialissuesave');
Route::get('getmaterialData', [MaterialissueController::class, 'getmaterialissueData'])->name('getmaterialData');
Route::get('materialissueedit/{id}', [MaterialissueController::class, 'create'])->name('materialissueedit');
Route::get('materialprint/{id}', [MaterialissueController::class, 'materialprint'])->name('materialprint');
Route::get('mtlissuedetails', [MaterialissueController::class, 'index'])->name('mtlissuedetails');
Route::get('packingmtlissuedetails', [MaterialissueController::class, 'index'])->name('packingmtlissuedetails');
Route::get('materialissueview/{id}', [MaterialissueController::class, 'show'])->name('materialissueview');
Route::get('prdstocksubinvdata/{id}', [MaterialissueController::class, 'prdstocksubinvdata'])->name('prdstocksubinvdata');
Route::get('prdstocksublocdata/{id}', [MaterialissueController::class, 'prdstocksublocdata'])->name('prdstocksublocdata');

// Material Receive 
Route::get('materialreceive', [MaterialreceivehdrController::class, 'index'])->name('materialreceive');
Route::get('packingmaterialreceive', [MaterialreceivehdrController::class, 'index'])->name('packingmaterialreceive');
Route::get('materialreceivecreate/{id}', [MaterialreceivehdrController::class, 'create'])->name('materialreceivecreate');
Route::post('materialreceivesave', [MaterialreceivehdrController::class, 'save'])->name('materialreceivesave');
Route::get('getmaterialreceiveData', [MaterialreceivehdrController::class, 'getmaterialreceiveData'])->name('getmaterialreceiveData');
Route::get('materialreceiveedit/{id}', [MaterialreceivehdrController::class, 'create'])->name('materialreceiveedit');
Route::get('prdsubinventoryreceivedetails/{id}/{id1}', [MaterialreceivehdrController::class, 'prdsubinventoryreceivedetails'])->name('prdsubinventoryreceivedetails');
Route::get('materialrecieveview/{id}', [MaterialreceivehdrController::class, 'show'])->name('materialrecieveview');
Route::get('getprcsdetails/{pid}/{id}', [MaterialreceivehdrController::class, 'getprcsdetails'])->name('getprcsdetails');

// Processing 
Route::get('process', [ProcesshdrController::class, 'index'])->name('processinghdr');
Route::post('processhdrsave', [ProcesshdrController::class, 'save'])->name('processhdrsave');

//  Material Receive Details
Route::get('mtlreceivedetails', [MaterialreceivehdrController::class, 'recieveindex'])->name('mtlreceivedetails');
Route::get('packingmtlreceivedetails', [MaterialreceivehdrController::class, 'recieveindex'])->name('packingmtlreceivedetails');
Route::get('getmtlrecievedetails', [MaterialreceivehdrController::class, 'getmtlrecievedetails'])->name('getmtlrecievedetails');

// Material Request
Route::get('materialrequest', [MaterialissueController::class, 'index'])->name('materialrequest');
Route::get('materialrequestview/{id}', [MaterialissueController::class, 'create'])->name('materialrequestview');
Route::get('prdsubinventorydetails', [MaterialissueController::class, 'prdsubinventorydetails'])->name('prdsubinventorydetails');

// Jobcard Rework
Route::get('jobcardrework', [JobcardController::class, 'index'])->name('jobcardrework');
Route::get('jobcardreworkedit/{id}', [JobcardController::class, 'create'])->name('jobcardreworkedit');
Route::get('prduom/{id}', [MaterialissueController::class, 'productuom'])->name('prduom');
Route::get('prdstockdata/{id}', [MaterialissueController::class, 'prdstockdata'])->name('prdstockdata');


// Move to Inventory 
Route::get('prodmovetoinventory', [ProductionmovetoinventoryController::class, 'index'])->name('prodmovetoinventory');
Route::get('msqasubmitstageappData', [ProductionmovetoinventoryController::class, 'qasubmitstageappData'])->name('msqasubmitstageappData');
Route::get('movetocreate/{id}', [ProductionmovetoinventoryController::class, 'create'])->name('movetocreate');
Route::post('pmovetoinventorysave', [ProductionmovetoinventoryController::class, 'save'])->name('pmovetoinventorysave');

//Issue List
Route::get('packingissuelist', [MaterialissueController::class, 'issuelist'])->name('packingissuelist');
Route::get('issuelist', [MaterialissueController::class, 'issuelist'])->name('issuelist');
Route::get('issuelistdata', [MaterialissueController::class, 'issuelistdata'])->name('issuelistdata');

// Production Indent
Route::get('productionindent', [ProductionindenthdrController::class, 'index'])->name('productionindent');
Route::get('productionindentcreate/{id}', [ProductionindenthdrController::class, 'create'])->name('productionindentcreate');
Route::get('productionindentview/{id}', [ProductionindenthdrController::class, 'show'])->name('productionindentview');
Route::post('productionindentsave', [ProductionindenthdrController::class, 'save'])->name('productionindentsave');
Route::get('getProductionindentData', [ProductionindenthdrController::class, 'getProductionindentData'])->name('getProductionindentData');

// Operations Job Activity
Route::get('jobactivity', [JobactivityController::class, 'index'])->name('jobactivity');
Route::get('getjobactivitydata', [JobactivityController::class, 'getjobactivitydata'])->name('getjobactivitydata');
Route::get('jobactivityedit/{id}', [JobactivityController::class, 'create'])->name('jobactivityedit');
Route::post('jobactivitysave', [JobactivityController::class, 'save'])->name('jobactivitysave');
Route::get('jobactivitydelete/{id}', [JobactivityController::class, 'destroy'])->name('jobactivitydelete');
Route::get('jobactivityview/{id}', [JobactivityController::class, 'show'])->name('jobactivityview');

// Material Issue Against JC
Route::get('materialissueagainstjc', [MaterialissueaginstjcController::class, 'index'])->name('materialissueagainstjc');
Route::get('getmatissuejcdata', [MaterialissueaginstjcController::class, 'getmatissuejcData'])->name('getmatissuejcdata');
Route::get('materialreissue/{id}', [MaterialissueaginstjcController::class, 'create'])->name('materialreissue');
Route::post('materialreissuesave', [MaterialissueaginstjcController::class, 'save'])->name('materialreissuesave');
Route::get('materialreissueview/{id}', [MaterialissueaginstjcController::class, 'show'])->name('materialreissueview');

// Material Acknowledgement
Route::get('materialackgainstjc', [MaterialreackknowledgeController::class, 'index'])->name('materialackgainstjc');
Route::get('materialrereceivecreate/{id}', [MaterialreackknowledgeController::class, 'create'])->name('materialrereceivecreate');	
Route::get('getmaterialrereceiveData', [MaterialreackknowledgeController::class, 'getmaterialrereceiveData'])->name('getmaterialrereceiveData');
Route::post('materialrereceivesave', [MaterialreackknowledgeController::class, 'save'])->name('materialrereceivesave');
Route::get('matrerecivedetails/{id}/{id1}', [MaterialreackknowledgeController::class, 'matrerecivedetails'])->name('matrerecivedetails');

?>