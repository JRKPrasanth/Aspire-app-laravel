<?php

use App\Http\Controllers\ArdiscountshdrController;
use App\Http\Controllers\SchemesController;
use App\Http\Controllers\TargetsController;
use App\Http\Controllers\ConsumableController;
use App\Http\Controllers\CustomersController;
use App\Http\Controllers\CustomertypesController;
use App\Http\Controllers\SoorderController;
use App\Http\Controllers\CustomeruploadController;
use App\Http\Controllers\CustomersiteuploadController;
use App\Http\Controllers\SalesenquiryController;
use App\Http\Controllers\SalesinquiryController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\SalesreturncheckController;
use App\Http\Controllers\SoquoteController;
use App\Http\Controllers\SalesquoteapprovalController;
use App\Http\Controllers\SalesreplacementController;
use App\Http\Controllers\SalesinvoiceController;
use App\Http\Controllers\SalespersonController;
use App\Http\Controllers\SalesdispatchshipconfirmController;
use App\Http\Controllers\ArfreightcarriershdrController;
use App\Http\Controllers\DispatchController;
use App\Http\Controllers\SalesreturnController;
use App\Http\Controllers\SalesinvoicefromorderController;
use App\Http\Controllers\TransportcalculationController;
use App\Http\Controllers\AdvancereceiptController;
use App\Http\Controllers\ReceiptdetailsController;
use App\Http\Controllers\ReceiptforinvoiceController;
use App\Http\Controllers\msalesreceipthdrController;
use App\Http\Controllers\SoquotecopyController;
use App\Http\Controllers\SoquotecopymdController;


// 	LARAVEL 8 ROUTE


// Discounts 
Route::get('ardiscountshdr', [ArdiscountshdrController::class, 'index'])->name('ardiscountshdr');
Route::get('discountchkname', [ArdiscountshdrController::class, 'discountchkname'])->name('discountchkname');
Route::get('ardiscountshdrcreate/{id}', [ArdiscountshdrController::class, 'create'])->name('ardiscountshdrcreate');
Route::get('ardiscountshdrview/{id}', [ArdiscountshdrController::class, 'show'])->name('ardiscountshdrview');
Route::get('ardiscountshdredit/{id}', [ArdiscountshdrController::class, 'edit'])->name('ardiscountshdredit');
Route::get('editdata/{id}', [ArdiscountshdrController::class, 'editdata'])->name('ardiscounteditdata');
Route::get('ardiscountshdrdelete/{id}', [ArdiscountshdrController::class, 'delete'])->name('ardiscountshdrdelete');
Route::post('ardiscountshdrsave', [ArdiscountshdrController::class, 'save'])->name('ardiscountshdrsave');
Route::get('getDiscountsData', [ArdiscountshdrController::class, 'getGridData'])->name('getDiscountsData');

//Schemes
Route::get('schemes', [SchemesController::class, 'index'])->name('schemes');
Route::get('schemescreate/{id}', [SchemesController::class, 'create'])->name('schemescreate');
Route::get('schemesedit/{id}', [SchemesController::class, 'create'])->name('schemesedit');
Route::get('schemes/checkname', [SchemesController::class, 'getCheckname'])->name('schemescheckname');
Route::get('schemesdelete/{id}', [SchemesController::class, 'destroy'])->name('schemesdelete');
Route::get('schemesview/{id}', [SchemesController::class, 'show'])->name('schemesview');
Route::get('getSchemesData', [SchemesController::class, 'getGridData'])->name('getSchemesData');
Route::post('schemessave', [SchemesController::class, 'save'])->name('schemessave');

// Schemes Approval
Route::get('schemesapproval', [SchemesController::class, 'index'])->name('schemesapproval');
Route::get('schemesapprovalcreate/{id}', [SchemesController::class, 'create'])->name('schemesapprovalcreate');
Route::get('schemeslevel2approval', [SchemesController::class, 'index'])->name('schemeslevel2approval');
Route::get('schemeslevel2approvalcreate/{id}', [SchemesController::class, 'create'])->name('schemeslevel2approvalcreate');
Route::get('schemesreport', [ConsumableController::class, 'schemesreportindex'])->name('schemesreport');
Route::get('getschemesreportdata', [ConsumableController::class, 'getschemesreportdata'])->name('getschemesreportdata');

// Distributor Target
Route::get('distributortargets', [TargetsController::class, 'index'])->name('distributortargets');

// Customer Types
Route::get('gstrequired/{id}', [CustomersController::class, 'gstrequired'])->name('gstrequired');
Route::get('mcustomertypes', [CustomertypesController::class, 'index'])->name('mcustomertypes');
Route::get('mcustomertypesdata', [CustomertypesController::class, 'getmcustomertypesData'])->name('mcustomertypesdata');
Route::get('mcustomertypes/edit', [CustomertypesController::class, 'getShow'])->name('mcustomertypeseditshow');
Route::get('mcustomertypes/checkname', [CustomertypesController::class, 'getCheckname'])->name('mcustomertypescheckname');
Route::post('mcustomertypes/save', [CustomertypesController::class, 'save'])->name('mcustomertypessave');
Route::get('mcustomertypes/{id}', [CustomertypesController::class, 'show'])->name('mcustomertypeshow');
Route::get('mcustomertypes/delete/{id}', [CustomertypesController::class, 'getRemove'])->name('mcustomertypesdelete');
Route::get('mcustomertypesedit/{id}', [CustomertypesController::class, 'getedit'])->name('mcustomertypesedit');

// Customers
Route::get('customerscreate/{id}', [CustomersController::class, 'create'])->name('customers');
Route::get('customers', [CustomersController::class, 'index'])->name('customers');
Route::post('customerssave', [CustomersController::class, 'save'])->name('customerssave');
Route::get('customersedit/{id}', [CustomersController::class, 'edit'])->name('customersedit');
Route::get('customersdelete/{id}', [CustomersController::class, 'delete'])->name('customersdelete');
Route::get('customersview/{id}', [CustomersController::class, 'show'])->name('customersview');
Route::get('geteditcustomer/{id}', [CustomersController::class, 'getedit'])->name('geteditcustomer');
Route::get('getcustomerData', [CustomersController::class, 'getGridData'])->name('getcustomerData');
Route::get('siteno/{id}', [SoorderController::class, 'siteno'])->name('siteno');
Route::get('cusschemesupdate', [CustomersController::class, 'cusschemesupdate'])->name('cusschemesupdate');
Route::get('cusschmesedit', [CustomersController::class, 'cusschmesedit'])->name('cusschmesedit');

//  Quick Customers
Route::get('quickcustomer', [CustomersController::class, 'index'])->name('quickcustomer');
Route::get('quickcustomer/{id}', [CustomersController::class, 'create'])->name('quickcustomer');

// Customer Upload 
Route::get('customerupload', [CustomeruploadController::class, 'index'])->name('customerupload');
Route::get('getCustomeruploadData', [CustomeruploadController::class, 'getCustomeruploadData'])->name('getCustomeruploadData');
Route::post('customerdataupload', [CustomeruploadController::class, 'Uploadexcel'])->name('customerdataupload');
Route::get('customeruploadview/{id}', [CustomeruploadController::class, 'show'])->name('customeruploadview');
Route::get('customeruploadedit/{id}', [CustomeruploadController::class, 'create'])->name('customeruploadedit');
Route::get('getCustomervalidate', [CustomeruploadController::class, 'getCustomervalidate'])->name('getCustomervalidate');
Route::post('customeruploadsave', [CustomeruploadController::class, 'save'])->name('customeruploadsave');

// Customer Site Upload 
Route::get('customersiteupload', [CustomersiteuploadController::class, 'index'])->name('customersiteupload');
Route::get('getCustomersiteuploadData', [CustomersiteuploadController::class, 'getCustomersiteuploadData'])->name('getCustomersiteuploadData');
Route::post('customersitedataupload', [CustomersiteuploadController::class, 'Uploadexcel'])->name('customersitedataupload');
Route::get('customersiteuploadview/{id}', [CustomersiteuploadController::class, 'show'])->name('customersiteuploadview');
Route::get('customersiteuploadedit/{id}', [CustomersiteuploadController::class, 'create'])->name('customersiteuploadedit');
Route::get('getCustomersitevalidate', [CustomersiteuploadController::class, 'getCustomersitevalidate'])->name('getCustomersitevalidate');
Route::post('customersiteuploadsave', [CustomersiteuploadController::class, 'save'])->name('customersiteuploadsave');

// Customers Approval
Route::get('customersapproval', [CustomersController::class, 'index'])->name('customersapproval');
Route::get('customersapprovalcreate/{id}', [CustomersController::class, 'edit'])->name('customersapprovalcreate');

// Sales Enquiry 

Route::get('salesenquiryedit/{id}', [SalesenquiryController::class, 'edit'])->name('salesenquiryedit');
Route::post('tabledata', [SalesenquiryController::class, 'tabledata'])->name('salesenquiry');
Route::get('salesinquiry', [SalesinquiryController::class, 'index'])->name('salesinquiry');
Route::get('salesenquirycreate', [SalesenquiryController::class, 'create'])->name('salesenquirycreate');
Route::post('salesenquirysave', [SalesenquiryController::class, 'save'])->name('salesenquirysave');
Route::get('getSalesinquiryData', [SalesinquiryController::class, 'getGridData'])->name('getSalesinquiryData');
Route::get('getshowcolumns', [SalesinquiryController::class, 'getshowcolumns'])->name('getshowcolumns');
Route::post('salesinquiryfilesave', [SalesinquiryController::class, 'salesinquiryfilesave'])->name('salesinquiryfilesave');
Route::get('salesinquiryupdatestatus/{id}', [SalesinquiryController::class, 'salesinquiryupdatestatus'])->name('salesinquiryupdatestatus');
Route::get('manufacturepart/{id}/{id1}', [SalesinquiryController::class, 'manufacturepart'])->name('manufacturepart');
Route::get('salesinquirycreate/{id}', [SalesinquiryController::class, 'create'])->name('salesinquirycreate.withid');
Route::get('salesinquirycreate/{id}/{type}', [SalesinquiryController::class, 'create'])->name('salesinquirycreate.withtype');
Route::get('salesinquiryprint/{id}', [SalesinquiryController::class, 'getPrint'])->name('salesinquiryprint');
Route::get('salesinquiryview/{id}', [SalesinquiryController::class, 'view'])->name('salesinquiryview');
Route::get('salesinquirydelete/{id}', [SalesinquiryController::class, 'delete'])->name('salesinquirydelete');
Route::get('salesinquirystatus/{id}', [SalesinquiryController::class, 'getStatus'])->name('salesinquirystatus');
Route::get('salesinquiryconfirmstatus/{id}', [SalesinquiryController::class, 'getConfirmstatus'])->name('salesinquiryconfirmstatus');
Route::get('copysalesinquiry', [SalesinquiryController::class, 'index'])->name('copysalesinquiry');
Route::get('copysalesinquiry/{id}', [SalesinquiryController::class, 'create'])->name('copysalesinquiry');
Route::get('copysalesinquiryview/{id}', [SalesinquiryController::class, 'view'])->name('copysalesinquiryview');
Route::post('salesinquirysave', [SalesinquiryController::class, 'save'])->name('salesinquirysave');

// SO Quote

Route::get('customerpricelist/{id}', [Controller::class, 'customerpricelist'])->name('customerpricelist');
Route::post('rtnmovetoinventory', [SalesreturncheckController::class, 'movetoinventory1'])->name('rtnmovetoinventory');
Route::get('soquote', [SoquoteController::class, 'index'])->name('soquote');
Route::get('soquotegriddata', [SoquoteController::class, 'soquotegriddata'])->name('soquotegriddata');
Route::get('soquotecreate/{id}/{type}', [SoquoteController::class, 'create'])->name('soquote');
Route::get('soquotecreatelab/{id}/{type}', [SoquoteController::class, 'create'])->name('soquotecreatelab');
Route::get('soquoteuom/{id}', [SoquoteController::class, 'uomcode'])->name('soquoteuom');
Route::get('soquoteuoms/{id}/{ids}', [SoquoteController::class, 'uomcodes'])->name('soquoteuoms');
Route::get('customermaildetails/{id}', [SoquoteController::class, 'customermaildetails'])->name('customermaildetails');
Route::post('soquotefilesave', [SoquoteController::class, 'soquotefilesave'])->name('soquotefilesave');
Route::get('soquoteprint/{id}', [SoquoteController::class, 'getPrint'])->name('soquoteprint');
Route::post('soquotesave', [SoquoteController::class, 'save'])->name('soquotesave');
Route::post('soquotesave1', [SoquoteController::class, 'store'])->name('soquotesave1');
Route::get('salesenquiryconvert/{id}', [SoquoteController::class, 'create'])->name('salesenquiryconvert');
Route::get('getsocustomeralldata', [SoquoteController::class, 'getsocustomeralldata'])->name('getsocustomeralldata');
Route::get('soquoteview/{id}/{ty}', [SoquoteController::class, 'view'])->name('soquoteview');
Route::get('soquotestatus/{id}/{type}', [SoquoteController::class, 'getStatus'])->name('soquotestatus');
Route::get('soquotedelete/{id}', [SoquoteController::class, 'delete'])->name('soquotedelete');
Route::get('copysalesquote', [SoquoteController::class, 'copysalesquote'])->name('copysalesquote');
Route::get('copysalesquote/{id}', [SoquoteController::class, 'create'])->name('copysalesquote.withid');
Route::get('salesquotefromenquiry', [SalesinquiryController::class, 'index'])->name('salesquotefromenquiry');
Route::get('soquotefromenquiry', [SalesquotefromenquiryController::class, 'getSalesquotefromenquiryData'])->name('soquotefromenquiry');
Route::get('soquotefromenquiryview/{id}', [SalesquotefromenquiryController::class, 'view'])->name('soquotefromenquiryview');
Route::get('getshowcol', [SoquoteController::class, 'getshowcol'])->name('getshowcol');
Route::post('soquoteupload', [SoquoteController::class, 'soquoteupload'])->name('soquoteupload');
Route::get('soquoteuploaddata/{id}', [SoquoteController::class, 'soquoteuploaddata'])->name('soquoteuploaddata');
Route::get('showcoloumnsave', [SalesinquiryController::class, 'saveshowcolumn'])->name('showcoloumnsave');

// Sales Replacement 
Route::get('salesreplacement', [SalesreplacementController::class, 'index'])->name('salesreplacement');
Route::get('getSalesreplacementData', [SalesreplacementController::class, 'getSalesreplacementData'])->name('getSalesreplacementData');
Route::get('salesreplacementcreate', [SalesreplacementController::class, 'replaceindex'])->name('salesreplacementcreate');
Route::get('getSalesreplacementinvoiceData', [SalesreplacementController::class, 'getSalesreplacementinvoiceData'])->name('getSalesreplacementinvoiceData');
Route::get('salesinvoicereplacement/{id}/{type}', [SalesreplacementController::class, 'replace'])->name('salesinvoicereplacement');
Route::get('createreplacement', [SalesreplacementController::class, 'create'])->name('salesinvoicereplacement');
Route::post('salesreplacementsave', [SalesreplacementController::class, 'save'])->name('salesreplacementsave');
Route::get('salesreplacecreate/{id}', [SalesreplacementController::class, 'create'])->name('salesreplacecreate');
Route::get('salesinvoicereplacementapprve/{id}', [SalesreplacementController::class, 'create'])->name('salesinvoicereplacementapprve');
Route::get('salesreplacementapproval', [SalesreplacementController::class, 'index'])->name('salesreplacementapproval');
Route::get('dispatchfrmreplace', [SalesreplacementController::class, 'index'])->name('dispatchfrmreplace');
Route::get('salesreplacementfromindispatch/{id}', [SalesinvoiceController::class, 'replace'])->name('salesinvoicereplacement');

//  SO Quote Approval
Route::get('salesquoteapproval', [SoquoteController::class, 'index'])->name('salesquoteapproval');
Route::get('soquoteapprovalgriddata', [SalesquoteapprovalController::class, 'soquoteapprovalgriddata'])->name('soquoteapprovalgriddata');
Route::get('salesquoteapprovalview/{id}', [SoquoteController::class, 'create'])->name('salesquoteapprovalview');
Route::get('salesquoteapprovalview/{id}/{quotetype}', [SoquoteController::class, 'create'])->name('salesquoteapprovalview');

// Sales Person
Route::get('employeecheck/{id}/{ids}', [SalespersonController::class, 'employeecheck'])->name('employeecheck');

// SOORDER
Route::get('soorder', [SoorderController::class, 'index'])->name('soorder');
Route::get('soorderdelete', [SoorderController::class, 'delete'])->name('soorderdelete');
Route::get('soorderdelete/{id}', [SoorderController::class, 'delete'])->name('soorderdeleteid');
Route::get('soordergriddata', [SoorderController::class, 'soordergriddata'])->name('soordergriddata');
Route::get('soordercreate', [SoorderController::class, 'update'])->name('soordercreate');
Route::get('soordercreate/{id}', [SoorderController::class, 'update'])->name('soordercreate');
Route::get('soordercreate/{id}/{type}', [SoorderController::class, 'update'])->name('soordercreate');
Route::get('soorderprint/{id}', [SoorderController::class, 'getPrint'])->name('soorderprint');
Route::get('proformastdprint/{id}', [SoorderController::class, 'getproformastdprint'])->name('proformastdprint');
Route::get('proformaexpprint/{id}', [SoorderController::class, 'getproformaexpprint'])->name('proformaexpprint');
Route::get('soorderview/{id}', [SoorderController::class, 'show'])->name('soorderview');
Route::get('soorderfromqo', [SoorderController::class, 'quotedatum'])->name('soorderfromqo');
Route::get('soorderfromqo/{id}', [SoorderController::class, 'view'])->name('soorderfromqo');
Route::get('salesorderfromqo/{id}', [SoorderController::class, 'quoteupdate'])->name('salesorderfromqo');
Route::get('getordcustomeralldata', [SoorderController::class, 'getordcustomeralldata'])->name('getordcustomeralldata');
Route::get('schemesavailable', [SoorderController::class, 'schemesavailable'])->name('schemesavailable');
Route::get('employeeaddress/{id}', [SoorderController::class, 'employeeaddress'])->name('employeeaddress');
Route::get('soorderupdatestatus/{id}', [SoorderController::class, 'soorderupdatestatus'])->name('soorderupdatestatus');
Route::get('conversionexchangecurrency/{id}', [SoorderController::class, 'conversionexchangecurrency'])->name('conversionexchangecurrency');

// Copy SO
Route::get('copysoorder', [SoorderController::class, 'index'])->name('copysoorder');

// Uploads
Route::post('salesorderupload/{id}', [SoorderController::class, 'salesorderupload'])->name('salesorderupload');
Route::post('sofilesave', [SoorderController::class, 'sofilesave'])->name('sofilesave');
Route::get('salesorderuploaddata/{id}', [SoorderController::class, 'salesorderuploaddata'])->name('salesorderuploaddata');
Route::post('schemesdata/{id}/{pid}', [SoorderController::class, 'schemesdata'])->name('schemesdata');

// Order Save
Route::post('saveorder', [SoorderController::class, 'store'])->name('saveorder');

// Product / Price
Route::get('pricelistdetail_so/{id}/{pid}', [SoorderController::class, 'pricelistdetail_so'])->name('pricelistdetail_so');
Route::get('productdetails_so/{pid}/{plid}/{cussite}/{type}', [SoorderController::class, 'productdetails_so'])->name('productdetailsso');

Route::get('sodispatchaddress/{id}', [SoorderController::class, 'getaddress'])->name('sodispatchaddress');
Route::get('sopricelist/{id}', [SoorderController::class, 'getpricelist'])->name('sopricelist');
Route::get('soprdunitprice/{id}', [SoorderController::class, 'getsoprdunitprice'])->name('soprdunitprice');

// Status
Route::get('soorderstatus/{id}/{type}', [SoorderController::class, 'getStatus'])->name('soorderstatus');
Route::get('soorderstatsourceus/{id}/{type}', [SoorderController::class, 'getStatus'])->name('soorderstatsourceus');

// Approvals
Route::get('salesorderapproval', [SoorderController::class, 'index'])->name('salesorderapproval');
Route::get('salesorderdownload/{id}/{ids}', [SoorderController::class, 'download'])->name('salesorderdownload');
Route::get('invoicefromorder', [SoorderController::class, 'index'])->name('invoicefromorder');
Route::get('invoicefromdispatch', [SoorderController::class, 'index'])->name('invoicefromdispatch');
Route::get('soorderapproved/{id}', [SoorderController::class, 'update'])->name('soorderapproved');

// Cancellation
Route::get('socancellation', [SoorderController::class, 'index'])->name('socancellation');
Route::get('socancellation/{id}/{type}', [SoorderController::class, 'update'])->name('socancellation');
Route::get('socancellationcancel/{id}/{status}', [SoorderController::class, 'cancel'])->name('socancellationcancel');

// Pricelist Download
Route::get('recentpricedownload', [SoorderController::class, 'pricelistindex'])->name('recentpricedownload');

// enquiry to order
Route::get('soorderqtycheck/{id}', [SoorderController::class, 'soorderqtycheck'])->name('soorderqtycheck');
Route::get('salesenquiryconvert1/{id}', [SoorderController::class, 'enquiryupdate'])->name('salesenquiryconvert1');
Route::get('salesorderfromenquiry', [SalesinquiryController::class, 'index'])->name('salesorderfromenquiry');
Route::get('salesorderfromenquiry/{id}', [SoorderController::class, 'enquiryupdate'])->name('salesorderfromenquiry');
Route::get('salesinquirystatus/{id}', [SalesinquiryController::class, 'getStatus'])->name('salesinquirystatus');
Route::get('custaddress/{id}', [SalesinquiryController::class, 'custaddress'])->name('custaddress');
Route::get('custaddress/{id}/{type}', [SoorderController::class, 'custaddresstype'])->name('custaddress.type');


// SO invoice approval
Route::get('salesinvoiceapproval', [SalesinvoiceController::class, 'index'])->name('salesinvoiceapproval');
Route::get('salesinvoiceapprovalview/{id}', [SalesinvoiceController::class, 'show'])->name('salesinvoiceapproval');
Route::get('salesinvoiceapprovalcreate/{id}', [SalesinvoiceController::class, 'create'])->name('salesinvoiceapproval');
Route::get('getcustomeralldata', [SalesinvoiceController::class, 'getcustomeralldata'])->name('getcustomeralldata');
Route::get('lrupdate', [SalesinvoiceController::class, 'lrupdate'])->name('lrupdate');
Route::get('ewayupdate', [SalesinvoiceController::class, 'ewayupdate'])->name('ewayupdate');

// Ship confirm
Route::get('salesdispatchshipconfirm', [SalesdispatchshipconfirmController::class, 'create'])->name('salesdispatchshipconfirm');
Route::get('getshipdata', [SalesdispatchshipconfirmController::class, 'getshipgriddata'])->name('getshipdata');
Route::get('shipmentstatus/{id}', [SalesdispatchshipconfirmController::class, 'getshipstatus'])->name('shipmentstatus');

/* Code for freightcarriers duplicate chkname */
Route::get('carrierchck', [ArfreightcarriershdrController::class, 'carrierchck'])->name('carrierchck');

// Dispatch
Route::get('dispatch', [DispatchController::class, 'index'])->name('dispatch');
Route::get('dispatchcreate/{id}', [DispatchController::class, 'create'])->name('dispatch');
Route::get('dispatchdata', [DispatchController::class, 'dispatchdata'])->name('dispatchdata');
Route::get('dispatchview/{id}', [DispatchController::class, 'show'])->name('dispatchview');
Route::post('dispatchsave', [DispatchController::class, 'save'])->name('dispatchsave');
Route::get('dispatchsoorderview/{id}', [SoorderController::class, 'show'])->name('dispatchsoorderview');
Route::get('dispatchstatussave', [DispatchController::class, 'Editsave'])->name('dispatchstatussave');

// Return Order
Route::get('salesreturn', [SalesreturnController::class, 'index'])->name('salesreturn');
Route::get('returnData', [SalesreturnController::class, 'returnData'])->name('returnData');
Route::get('returndataApproval', [SalesreturnController::class, 'returndataApproval'])->name('returndataApproval');
Route::get('getSalesreturnData', [SalesreturnController::class, 'getSalesreturnData'])->name('getSalesreturnData');
Route::get('salesreturnfrominvoice/{id}', [SalesreturnController::class, 'create'])->name('salesreturnfrominvoice');
Route::get('salesreturnprint/{id}', [SalesreturnController::class, 'getPrint'])->name('salesreturnprint');
Route::get('salesreturncreate', [SalesreturnController::class, 'create'])->name('salesreturncreate');
Route::get('salesreturncreate/{id}', [SalesreturnController::class, 'create'])->name('salesreturncreate');
Route::get('salesreturnapprovalcreate/{id}', [SalesreturnController::class, 'create'])->name('salesreturnapprovalcreate');
Route::post('salesreturnsave', [SalesreturnController::class, 'save'])->name('salesreturnsave');
Route::get('salesreturnaddress/{id}', [SalesreturnController::class, 'getaddress'])->name('salesreturnaddress');
Route::get('salesreturnview', [SalesreturnController::class, 'inn'])->name('salesreturnview');
Route::get('salesreturnviewdata', [SalesreturnController::class, 'salesreturnviewdata'])->name('salesreturnviewdata');
Route::get('salesreturnviews/{id}', [SalesreturnController::class, 'show'])->name('salesreturnviews');
Route::get('salesreturnapproval', [SalesreturnController::class, 'approvalindex'])->name('salesreturnapproval');
Route::get('salesreturnfrominvoice', [SalesreturnController::class, 'invoiceindex'])->name('salesreturnfrominvoice');
Route::get('salesinvoicereturndata', [SalesreturnController::class, 'salesinvoicereturndata'])->name('salesinvoicereturndata');

// Sales Return check
Route::get('salesreturncheck', [SalesreturncheckController::class, 'index'])->name('salesreturncheck');
Route::get('getSalesreturncheckData', [SalesreturncheckController::class, 'getSalesreturncheckData'])->name('getSalesreturncheckData');
Route::get('movetoinventory/{id}/{ids}/{subid}/{locid}', [SalesreturncheckController::class, 'movetoinventory'])->name('movetoinventory');
Route::get('dispatchreturndata/{id}', [SalesreturncheckController::class, 'dispatchreturndata'])->name('dispatchreturndata');
Route::get('dispatchreturndatadetails/{invoiceid}/{productid}', [SalesreturncheckController::class, 'dispatchreturndatadetails'])->name('dispatchreturndatadetails');


// Sales Invoice
Route::get('salesinvoicecurrency/{id}', [SalesinvoiceController::class, 'salesinvoicecurrency'])->name('salesinvoicecurrency');
Route::get('salesinvoicecreate/{id}/{type}', [SalesinvoiceController::class, 'create'])->name('salesinvoice');
Route::get('salesinvoicecreate/{id}', [SalesinvoiceController::class, 'create'])->name('salesinvoice');
Route::get('salesinvoiceview/{id}', [SalesinvoiceController::class, 'show'])->name('salesinvoiceview');
Route::get('salesinvoiceprint/{id}', [SalesinvoiceController::class, 'getPrint'])->name('salesinvoiceprint');
Route::get('ewaybillgenerate/{id}/{cid}', [SalesinvoiceController::class, 'ewaybillgenerate'])->name('ewaybillgenerate');
Route::get('pickorderfrominvoicecreate/{id}', [SalesinvoiceController::class, 'getpickorderupdate'])->name('pickorderfrominvoicecreate');
Route::get('createinvoicefromorder/{id}', [SalesinvoiceController::class, 'getorderupdate'])->name('createinvoicefromorder');
Route::get('invoicefromdispatch/{id}', [SalesinvoiceController::class, 'getdispatchupdate'])->name('invoicefromdispatch');
Route::get('salesinvoiceuploaddata/{id}', [SalesinvoiceController::class, 'salesinvoiceuploaddata'])->name('invoicefrompickorder');
Route::get('invoicesoqty/{id}/{id1}/{id2}', [SalesinvoiceController::class, 'invoicesoqty'])->name('invoicesoqty');
Route::get('salesinvoice', [SalesinvoiceController::class, 'index'])->name('salesinvoice');
Route::get('getSalesinvoiceData', [SalesinvoiceController::class, 'getSalesinvoiceData'])->name('getSalesinvoiceData');
Route::post('salesinvoicesave', [SalesinvoiceController::class, 'save'])->name('salesinvoicesave');
Route::post('salesinvoiceuploads', [SalesinvoiceController::class, 'salesinvoiceuploads'])->name('salesinvoiceuploads');
Route::get('salesinvoiceconvert/{id}', [SalesinvoiceController::class, 'getorderupdate'])->name('salesinvoiceconvert');
Route::get('lredit', [SalesinvoiceController::class, 'lredit'])->name('lredit');
Route::get('ewayedit', [SalesinvoiceController::class, 'ewayedit'])->name('ewayedit');
Route::get('salesloadtds/{id}/{idss}', [SalesinvoiceController::class, 'salesloadtds'])->name('salesloadtds');
Route::get('salesinvoicedelete/{id}', [SalesinvoiceController::class, 'delete'])->name('salesinvoicedelete');
Route::get('salesinvoicestatus/{id}/{type}', [SalesinvoiceController::class, 'getStatus'])->name('salesinvoicestatus');

// Sales Invoice From Order
Route::get('salesinvoicefromorder', [SalesinvoicefromorderController::class, 'index'])->name('salesinvoicefromorder.index');
Route::get('salesinvoicefromordercreate', [SalesinvoicefromorderController::class, 'create'])->name('salesinvoicefromorder.create');
Route::get('salesinvoicefromorderedit/{id}', [SalesinvoicefromorderController::class, 'edit'])->name('salesinvoicefromorder.edit');
Route::get('getsalesinvoicefromorderData', [SalesinvoicefromorderController::class, 'getGridData'])->name('salesinvoicefromorder.data');

// Add new ship-to customer from invoice
Route::post('newshiptocustomer/{id}', [SalesinvoiceController::class, 'newshiptocustomer'])->name('salesinvoice.newshiptocustomer');

// Sales Invoice From Dispatch
Route::get('salesinvoicefromdispatch', [DispatchController::class, 'index'])->name('salesinvoicefromdispatch');
Route::get('salesinvoicefromdispatchcreate', [DispatchController::class, 'create'])->name('salesinvoicefromdispatchcreate');

// Packing / Stock related
Route::get('packingslip/{id}', [DispatchController::class, 'getPackslipprint'])->name('dispatch.packingslip');
Route::get('dispatchproductqoh/{id}', [DispatchController::class, 'dispatchproductqoh'])->name('dispatchproductqoh');
Route::get('dispatchproductexpiry', [DispatchController::class, 'dispatchproductexpiry']);
Route::get('productstockdetails/{id}', [DispatchController::class, 'productstockdetails'])->name('productstockdetails');

// Customer state code
Route::get('getstatecode/{id}', [CustomersController::class, 'getstatecode'])->name('customers.getstatecode');

// Dispatch 
Route::get('dispatchlines/{pid}', [DispatchController::class, 'Dispatchlines'])->name('dispatchlines');
Route::get('dispatchqty/{pid}/{soid}', [DispatchController::class, 'dispatchqty'])->name('dispatchqty');
Route::get('dispatchfrminvoicedata', [TransportcalculationController::class, 'Loadinvoicedata'])->name('dispatchfrminvoicedata');
Route::get('dispatchfrmso', [SoorderController::class, 'index'])->name('dispatchfrmso');
Route::get('dispatchfrminvoice', [SalesinvoiceController::class, 'index'])->name('dispatchfrminvoice');
Route::get('invoiceshipconfirm/{id}', [SalesinvoiceController::class, 'invoiceshipconfirm'])->name('invoiceshipconfirm');
Route::get('exportinvratevalidation/{invdate}/{invcurrency}', [SalesinvoiceController::class, 'exportinvratevalidation'])->name('exportinvratevalidation');
Route::post('invoicedocupload', [SalesinvoiceController::class, 'invoicedocupload'])->name('invoicedocupload');


// transport calculation
Route::get('transportcalculation', [TransportcalculationController::class, 'index'])->name('transportcalculation');


// Advance Receipt
Route::get('advancereceipt', [AdvancereceiptController::class, 'index'])->name('advancereceipt');
Route::get('advancereceiptcreate/{id}', [AdvancereceiptController::class, 'create'])->name('advancereceiptcreate');
Route::post('advancereceiptsave', [AdvancereceiptController::class, 'save'])->name('advancereceiptsave');
Route::get('advancesodata', [AdvancereceiptController::class, 'soorderdetailsgriddata'])->name('advancesodata');
Route::get('getchequeno/{id}', [AdvancereceiptController::class, 'getchequeno'])->name('getchequeno');
Route::get('exchangerategetval', [AdvancereceiptController::class, 'exchangerategetval'])->name('exchangerategetval');

// Receipt Report Details
Route::get('receiptdetails', [ReceiptdetailsController::class, 'index'])->name('receiptdetails');
Route::get('getReceiptdetailssData', [ReceiptdetailsController::class, 'getReceiptdetailssData']);
Route::get('receivablesreport/{id}', [ReceiptdetailsController::class, 'getreceivablesreport'])->name('receivablesreport');

// Receipt For Invoice
Route::get('receiptforinvoice', [ReceiptforinvoiceController::class, 'index'])->name('receiptforinvoice');
Route::get('receiptforinvoicecrt/{id}', [ReceiptforinvoiceController::class, 'create'])->name('receiptforinvoicecreate');
Route::get('receiptforinvoicecrt/{id}/{idss}/{invid}', [ReceiptforinvoiceController::class, 'create'])->name('receiptforinvoicecreate');
Route::get('receiptforinvoicecreate/{id}/{idss}', [ReceiptforinvoiceController::class, 'createsostatement'])->name('receiptforinvoicecreate');
Route::get('receiptforinvoicecreate/{invid}/{soid}/{id}', [ReceiptforinvoiceController::class, 'createsostatement'])->name('receiptforinvoicecreate');
Route::post('receiptforinvoicesave', [ReceiptforinvoiceController::class, 'save'])->name('receiptforinvoicesave');
Route::get('getsfiData', [ReceiptforinvoiceController::class, 'getSalesinvoicedetailsData'])->name('getsfiData');
Route::get('getchequeno/{id}', [ReceiptforinvoiceController::class, 'getchequeno'])->name('getchequeno');
Route::get('getadvance/{id}', [ReceiptforinvoiceController::class, 'getadvance'])->name('getadvance');


// Receipt Index
Route::get('receiptsindex', [ReceiptdetailsController::class, 'receiptindex'])->name('receiptsindex');
Route::get('getreceiptgridData', [ReceiptdetailsController::class, 'getreceiptgridData']);
Route::get('getReceiptvoucher/{id}', [ReceiptdetailsController::class, 'getReceiptvoucher'])->name('getReceiptvoucher');
Route::get('receiptsindexview/{id}', [ReceiptdetailsController::class, 'show']);
Route::get('directreceiptcreate', [ReceiptdetailsController::class, 'directreceiptcreate']);
Route::post('directreceiptsave', [ReceiptdetailsController::class, 'directreceiptsave'])->name('directreceiptsave');

// Pending Receipt Details
Route::get('pendingsalesreceipt', [PendingsalesreceiptController::class, 'index']);
Route::get('getpendingreceiptData', [PendingsalesreceiptController::class, 'getpendingreceiptData']);
Route::get('getpendingreceiptreportData', [PendingsalesreceiptController::class, 'getpendingreceiptreportData']);

// Dispatch / Invoice files
Route::get('dispatchprint/{id}', [DispatchController::class, 'dispatchprint'])->name('dispatchprint');
Route::post('dispatchfilesave', [DispatchController::class, 'dispatchfilesave']);
Route::post('invoicefilesave', [SalesinvoiceController::class, 'invoicefilesave']);
Route::get('soemployeedetails/{id}', [SoorderController::class, 'soemployeedetails']);
Route::get('schemesordercheck', [SalesinvoiceController::class, 'schemesordercheck']);

// Receipt actions
Route::get('getReceiptcanclconfirm/{id}', [ReceiptdetailsController::class, 'getReceiptcanclconfirm'])->name('getReceiptcanclconfirm');
Route::get('getReceiptbounceconfirm/{id}', [ReceiptdetailsController::class, 'getReceiptbounceconfirm'])->name('getReceiptbounceconfirm');

// msalesreceipthdr
Route::get('msalesreceipthdr', [msalesreceipthdrController::class, 'index1'])->name('msalesreceipthdr');
Route::get('msalesreceipthdrindex', [msalesreceipthdrController::class, 'index'])->name('msalesreceipthdr');
Route::get('msalesreceipthdrcreate/{id}', [msalesreceipthdrController::class, 'create'])->name('msalesreceipthdrcreate');
Route::get('mreceiptforinvoicecrt/{id}', [msalesreceipthdrController::class, 'create'])->name('msalesreceipthdrcreate');
Route::get('msalesreceipthdrview/{id}', [msalesreceipthdrController::class, 'show']);
Route::get('msalesreceipthdrdelete/{id}', [msalesreceipthdrController::class, 'delete'])->name('msalesreceipthdrdelete');
Route::get('getmsalesreceipthdrData', [msalesreceipthdrController::class, 'getmsalesreceipthdrData']);
Route::post('msalesreceipthdrsave', [msalesreceipthdrController::class, 'save']);

// --- SoquotecopyController ---
Route::get('soquotecopy1', [SoquotecopymdController::class, 'index'])->name('soquotecopy1');
Route::get('soquotegriddatacopy', [SoquotecopymdController::class, 'soquotegriddata'])->name('soquotegriddatacopy');
Route::get('soquotecreatecopy1', [SoquotecopymdController::class, 'create'])->name('soquotecreatecopy1');
Route::get('soquoteeditcopy/{id}', [SoquotecopymdController::class, 'update'])->name('soquoteeditcopy');
Route::post('savesoquotecopy', [SoquotecopymdController::class, 'store'])->name('savesoquotecopy');

// --- ArfreightcarriershdrController ---
Route::get('locationget', [ArfreightcarriershdrController::class, 'locationget'])->name('locationget');
Route::get('freightcarriershdr', [ArfreightcarriershdrController::class, 'index'])->name('freightcarriershdr');
Route::get('freightcarriershdrcreate/{id}', [ArfreightcarriershdrController::class, 'create'])->name('freightcarriershdrcreate');
Route::get('freightcarriershdrview/{id}', [ArfreightcarriershdrController::class, 'show'])->name('freightcarriershdrview');
Route::get('freightcarriershdredit/{id}/{type}', [ArfreightcarriershdrController::class, 'edit']);
Route::get('freightcarriersedit2/{id}/{type}', [ArfreightcarriershdrController::class, 'getedit'])->name('freightcarriershdredit2');
Route::get('freightcarriershdrdelete/{id}/{type}', [ArfreightcarriershdrController::class, 'delete'])->name('freightcarriershdrdelete');
Route::post('freightcarrierssave', [ArfreightcarriershdrController::class, 'save'])->name('freightcarrierssave');
Route::get('getfreightcarrierData/{type}', [ArfreightcarriershdrController::class, 'getfreightGridData']);
Route::get('freightcarnamechk/{id}', [ArfreightcarriershdrController::class, 'freightcarnamechk'])->name('freightcarnamechk');


































Route::get('salesperson', array('as' => '', 'check' => '', 'menu' => 'salesperson', 'label' => 'Sales Person', 'uses' => 'SalespersonController@index'))->name('salesperson');
Route::get('salespersoncreate', array('as' => '', 'check' => '', 'menu' => 'salesperson', 'label' => '', 'uses' => 'SalespersonController@create'))->name('salespersoncreate');
Route::post('salesperson/save', array('as' => '', 'check' => '', 'menu' => 'salesperson', 'label' => '', 'uses' => 'SalespersonController@save'));
Route::get('/salesperson/edit/', array('as' => '', 'check' => 'editdata', 'menu' => 'salesperson', 'label' => '', 'uses' => 'SalespersonController@getShow'));
Route::get('salespersonedit2/{id}', array('as' => '', 'check' => 'editdata', 'menu' => 'salesperson', 'label' => '', 'uses' => 'SalespersonController@getedit'))->name('salesperson');
Route::get('/salesperson/checkname/', array('as' => '', 'check' => '', 'menu' => 'salesperson', 'label' => '', 'uses' => 'SalespersonController@getCheckname'));
Route::get('salespersondelete/{id}', array('as' => '', 'check' => 'delete', 'menu' => 'salesperson', 'label' => '', 'uses' => 'SalespersonController@delete'));
Route::get('salesperson/{id}', array('as' => '', 'check' => '', 'menu' => 'salesperson', 'label' => '', 'uses' => 'SalespersonController@show'));
Route::get('getSalepersonData', array('as' => '', 'check' => '', 'menu' => 'salesperson', 'label' => '', 'uses' => 'SalespersonController@getGridData'));











Route::get('arfreightcarrierstrackhdr', array('as' => '', 'check' => '', 'menu' => '', 'label' => '', 'uses' => 'ArfreightcarrierstrackhdrController@index'))->name('arfreightcarrierstrackhdr');
Route::get('arfreightcarrierstrackhdrcreate', array('as' => '', 'check' => '', 'menu' => '', 'label' => '', 'uses' => 'ArfreightcarrierstrackhdrController@create'))->name('arfreightcarrierstrackhdrcreate');
Route::post('arfreightcarrierstrackhdrsave', array('as' => '', 'check' => '', 'menu' => '', 'label' => '', 'uses' => 'ArfreightcarrierstrackhdrController@save'))->name('arfreightcarrierstrackhdrsave');


/*Payment Methods*/

Route::get('paymentmethods', array('as' => '', 'check' => '', 'menu' => 'paymentmethods', 'label' => '', 'uses' => 'PaymentmethodsController@create'))->name('paymentmethods');
Route::get('paymentmethods/edit/', array('as' => '', 'check' => 'editdata', 'menu' => 'paymentmethods', 'label' => 'Edit', 'uses' => 'PaymentmethodsController@getShow'));
Route::get('paymentmethodsview/{id}', array('as' => '', 'check' => '', 'menu' => 'paymentmethods', 'label' => '', 'uses' => 'PaymentmethodsController@show'))->name('paymentmethodsview');
Route::get('paymentmethodsdelete/{id}/{ids}', array('as' => '', 'check' => 'delete', 'menu' => 'paymentmethods', 'label' => '', 'uses' => 'PaymentmethodsController@delete'));
Route::post('paymentmethods/save', array('as' => '', 'check' => '', 'menu' => 'paymentmethods', 'label' => '', 'uses' => 'PaymentmethodsController@save'))->name('paymentmethodssave');
Route::get('getPaymentmethodsData/{type}', array('as' => '', 'check' => '', 'menu' => 'paymentmethods', 'label' => '', 'uses' => 'PaymentmethodsController@getPaymentmethodsData'));

Route::get('purchasepaymentmethods', array('as' => '', 'check' => '', 'menu' => 'paymentmethods', 'label' => '', 'uses' => 'PaymentmethodsController@create'))->name('purchasepaymentmethods');



//deepika routes for pickorder
Route::get('invoicefrompickorder', array('as' => '', 'check' => '', 'menu' => 'invoicefrompickorder', 'label' => 'Invoice From Pick Order', 'uses' => 'SalespickorderController@index'))->name('invoicefrompickorder');
Route::get('dispatchfrmpickorder', array('as' => '', 'check' => '', 'menu' => 'pickorder', 'label' => '', 'uses' => 'SalespickorderController@index'))->name('dispatchfrmpickorder');
Route::get('pickorder', array('as' => '', 'check' => '', 'menu' => 'pickorder', 'label' => '', 'uses' => 'SalespickorderController@pick'))->name('pickorder');
Route::get('pickordercreate/{id}', array('as' => '', 'check' => '', 'menu' => 'pickorder', 'label' => '', 'uses' => 'SalespickorderController@create'))->name('pickordercreate');
Route::get('pickordercreate/{id}/{type}', array('as' => '', 'check' => '', 'menu' => 'pickorder', 'label' => '', 'uses' => 'SalespickorderController@create'))->name('pickordercreate');
Route::get('soorderproductgriddata', array('as' => '', 'check' => '', 'menu' => 'pickorder', 'label' => '', 'uses' => 'SalespickorderController@soorderproductgriddata'))->name('soorderproductgriddata');
Route::get('sopickprodqohdata', array('as' => '', 'check' => '', 'menu' => 'pickorder', 'label' => '', 'uses' => 'SalespickorderController@sopickprodqohdata'))->name('sopickprodqohdata');
Route::get('pickordersublines/{id}/{pid}', array('as' => '', 'check' => '', 'menu' => 'pickorder', 'label' => '', 'uses' => 'SalespickorderController@Picksublines'))->name('pickordersublines');
Route::post('pickordersave', array('as' => '', 'check' => '', 'menu' => 'pickorder', 'label' => '', 'uses' => 'SalespickorderController@save'));
Route::get('pickorderdata', array('as' => '', 'check' => '', 'menu' => 'pickorder', 'label' => '', 'uses' => 'SalespickorderController@pickorderdata'))->name('pickorderdata');
Route::get('pickordercheck/{id}', array('as' => '', 'check' => '', 'menu' => 'pickorder', 'label' => '', 'uses' => 'SalespickorderController@pickordercheck'))->name('pickordercheck');




//Receipts Header
Route::get('receiptbatches', array('as' => '', 'check' => '', 'menu' => 'receiptbatches', 'label' => 'Receipt Batches', 'uses' => 'ReceiptbatcheshdrController@index'))->name('receiptbatches');
Route::get('getBatchData', array('as' => '', 'check' => '', 'menu' => 'receiptbatches', 'label' => '', 'uses' => 'ReceiptbatcheshdrController@getBatchData'));
Route::get('receiptbatchcreate/{id}', array('as' => '', 'check' => 'create', 'menu' => 'receiptbatches', 'label' => 'Create', 'uses' => 'ReceiptbatcheshdrController@create'))->name('receiptbatchcreate');
Route::get('receiptbatchesview/{id}', array('as' => '', 'check' => 'view', 'menu' => 'receiptbatches', 'label' => 'View', 'uses' => 'ReceiptbatcheshdrController@show'))->name('receiptbatchesview');
Route::post('batchsave', array('as' => '', 'check' => '', 'menu' => 'receiptbatches', 'label' => '', 'uses' => 'ReceiptbatcheshdrController@save'))->name('batchsave');
Route::get('receiptbatchesdelete/{id}', array('as' => '', 'check' => 'delete', 'menu' => 'receiptbatches', 'label' => '', 'uses' => 'ReceiptbatcheshdrController@delete'))->name('receiptbatchesdelete');
Route::get('getinvamount/{id}', array('as' => '', 'check' => '', 'menu' => 'receiptbatches', 'label' => '', 'uses' => 'ReceiptbatcheshdrController@getinvamount'))->name('getinvamount');

//Receipts
Route::get('receipts', array('as' => '', 'check' => '', 'menu' => 'receipts', 'label' => 'Receipts', 'uses' => 'ReceiptsController@index'))->name('receipts');
Route::get('receiptscreate', array('as' => '', 'check' => 'create', 'menu' => 'receipts', 'label' => 'Create', 'uses' => 'ReceiptsController@create'))->name('receiptscreate');
Route::get('receiptscreate/{id}', array('as' => '', 'check' => 'edit', 'menu' => 'receipts', 'label' => 'Edit', 'uses' => 'ReceiptsController@create'))->name('receiptscreate');
Route::get('receiptsview/{id}', array('as' => '', 'check' => 'view', 'menu' => 'receipts', 'label' => 'View', 'uses' => 'ReceiptsController@show'))->name('receiptsview');
Route::post('receiptssave', array('as' => '', 'check' => '', 'menu' => 'receipts', 'label' => '', 'uses' => 'ReceiptsController@save'))->name('receiptssave');
Route::get('getReceiptsData', array('as' => '', 'check' => '', 'menu' => 'receipts', 'label' => '', 'uses' => 'ReceiptsController@getReceiptsData'));
Route::get('getreceiptbatchdetails/{id}', array('as' => '', 'check' => '', 'menu' => 'receipts', 'label' => '', 'uses' => 'ReceiptsController@getreceiptbatchdetails'))->name('getreceiptbatchdetails');
Route::get('rcptinvoicedetails/{id}', array('as' => '', 'check' => '', 'menu' => 'receipts', 'label' => '', 'uses' => 'ReceiptsController@rcptinvoicedetails'))->name('rcptinvoicedetails');



/* Credit Note */
Route::get('accountcreditnote', array('as' => '', 'check' => '', 'menu' => 'salesreturncheck', 'label' => 'Credit  Note', 'uses' => 'AccountcreditnoteController@index'));
Route::get('getaccountcreditData', array('as' => '', 'check' => '', 'menu' => 'salesreturncheck', 'label' => '', 'uses' => 'AccountcreditnoteController@getaccountcreditData'));
Route::get('salesinvoiceData', array('as' => '', 'check' => '', 'menu' => 'salesreturncheck', 'label' => '', 'uses' => 'AccountcreditnoteController@salesinvoiceData'));
Route::get('soinvoicetable', array('as' => '', 'check' => '', 'menu' => 'salesreturncheck', 'label' => '', 'uses' => 'AccountcreditnoteController@soinvoicetable'));
Route::get('accountcreditnotecreate/{id}', array('as' => '', 'check' => 'create', 'menu' => 'salesreturncheck', 'label' => 'Create', 'uses' => 'AccountcreditnoteController@accountcreditnotecreate'));
Route::post('accountcreditnotesave', array('as' => '', 'check' => '', 'menu' => 'salesreturncheck', 'label' => '', 'uses' => 'AccountcreditnoteController@accountcreditnotesave'));
Route::get('accountcreditnoteedit/{id}', array('as' => '', 'check' => 'edit', 'menu' => 'salesreturncheck', 'label' => 'Edit', 'uses' => 'AccountcreditnoteController@accountcreditnoteedit'));
Route::get('accountcreditnoteview/{id}', array('as' => '', 'check' => 'view', 'menu' => 'salesreturncheck', 'label' => 'View', 'uses' => 'AccountcreditnoteController@accountcreditnoteview'));


