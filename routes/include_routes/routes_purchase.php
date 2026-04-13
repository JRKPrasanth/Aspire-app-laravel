<?php

use App\Http\Controllers\Controller;
use App\Http\Controllers\PaymenttermsController;
use App\Http\Controllers\PaymentmethodsController;
use App\Http\Controllers\DeliverytermsController;
use App\Http\Controllers\FreighttermsController;
use App\Http\Controllers\InsurancestermsController;
use App\Http\Controllers\ArfreightcarriershdrController;
use App\Http\Controllers\SuppliertypesController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\SupplieruploadController;
use App\Http\Controllers\SuppliersiteuploadController;
use App\Http\Controllers\PurchaseenquiryController;
use App\Http\Controllers\PurchaseorderController;
use App\Http\Controllers\PurchaserequisitionController;
use App\Http\Controllers\PurchasequotationController;
use App\Http\Controllers\PurchaseinvoiceController;
use App\Http\Controllers\QuotationcompareController;
use App\Http\Controllers\GoodsinwardnoteController;
use App\Http\Controllers\GoodsreceiptnoteController;
use App\Http\Controllers\ServicereceiptnoteController;
use App\Http\Controllers\PurchasereturnController;
use App\Http\Controllers\PurchasedcController;
use App\Http\Controllers\PurchasereplacementController;


// LARAVEL 8 NEW ROUTE


// Payment Terms
Route::get('paymenttermschkname', [PaymenttermsController::class, 'getCheckname'])->name('paymenttermschkname');
Route::get('paymentterms', [PaymenttermsController::class, 'create'])->name('paymentterms');
Route::get('purchasepaymentterms', [PaymenttermsController::class, 'create'])->name('purchasepaymentterms');
Route::post('paymentterms/save', [PaymenttermsController::class, 'save'])->name('paymentterms');
Route::get('paymenttermsedit/{id}', [PaymenttermsController::class, 'edit'])->name('paymenttermsedit');
Route::get('paymenttermsview/{id}', [PaymenttermsController::class, 'show'])->name('paymenttermsview');
Route::get('paymenttermsdelete/{id}', [PaymenttermsController::class, 'getRemove'])->name('paymenttermsdelete');
Route::get('getpaytermGridData', [PaymenttermsController::class, 'getGridData'])->name('getpaytermGridData');

// Payment Methods
Route::get('paymentmethods', [PaymentmethodsController::class, 'index'])->name('paymentmethods');
Route::get('paymentmethodscreate', [PaymentmethodsController::class, 'create'])->name('paymentmethodscreate');
Route::get('paymentmethodsedit2/{id}/{payment_method_type}', [PaymentmethodsController::class, 'getedit'])->name('paymentmethodsedit2');
Route::get('paymentmethodsview/{id}', [PaymentmethodsController::class, 'show'])->name('paymentmethodsview');
Route::get('paymentmethodsdelete/{id}', [PaymentmethodsController::class, 'delete'])->name('paymentmethodsdelete');
Route::post('paymentmethodssave', [PaymentmethodsController::class, 'save'])->name('paymentmethodssave');
Route::get('paymentnamechk', [PaymentmethodsController::class, 'Checkname'])->name('paymentnamechk');
Route::get('getPaymentmethodsData', [PaymentmethodsController::class, 'getPaymentmethodsData'])->name('getPaymentmethodsData');


// PURCHASE Delivery Terms
Route::get('purchasedeliveryterms', [DeliverytermsController::class, 'create'])->name('purchasedeliveryterms');
Route::get('purchasedeliveryterms/edit/{id}', [DeliverytermsController::class, 'getShow'])->name('purchasedeliveryterms.edit');
Route::get('purchasedeliveryterms/view/{id}', [DeliverytermsController::class, 'show'])->name('purchasedeliveryterms.view');

// SALES Delivery Terms
Route::get('salesdeliveryterms', [DeliverytermsController::class, 'create'])->name('salesdeliveryterms');

// Generic Delivery Terms
Route::get('deliveryterms', [DeliverytermsController::class, 'create'])->name('deliveryterms');
Route::post('deliveryterms/save', [DeliverytermsController::class, 'save'])->name('deliveryterms');
Route::get('deliveryterms/edit/{id}', [DeliverytermsController::class, 'getShow'])->name('deliveryterms');
Route::get('deliverytermedit2/{id}/{source_type_id}', [DeliverytermsController::class, 'getedit'])->name('deliverytermedit2');
Route::get('deliveryterms/view/{id}', [DeliverytermsController::class, 'show'])->name('deliveryterms');
Route::get('deliveryterms/checkname', [DeliverytermsController::class, 'getCheckname'])->name('deliveryterms');
Route::get('deliveryterms/delete/{id}/{type}', [DeliverytermsController::class, 'delete'])->name('deliveryterms');
Route::get('deliverytermsdelete', [DeliverytermsController::class, 'delete'])->name('deliverytermsdelete');
Route::get('getDeliverytermsData/{type}', [DeliverytermsController::class, 'getGridData'])->name('getDeliverytermsData');

//Freight terms
Route::get('getfreighttermdata/{type}', [FreighttermsController::class, 'getfreightGridData'])->name('getfreighttermdata');
Route::get('freighttermsedit/{id}', [FreighttermsController::class, 'getShow'])->name('freighttermsedit');
Route::get('freighttermedit2/{id}/{type}', [FreighttermsController::class, 'getedit'])->name('freighttermedit2');
Route::post('freightterms/save', [FreighttermsController::class, 'save'])->name('freightterms');
Route::get('freighttermsdelete/{id}/{type}', [FreighttermsController::class, 'getRemove'])->name('freighttermsdelete');
Route::get('freightterms/checkname', [FreighttermsController::class, 'getCheckname'])->name('freighttermscheckname');
Route::get('freighttermsview/{id}', [FreighttermsController::class, 'show'])->name('freighttermsview');
Route::get('freightterms', [FreighttermsController::class, 'create'])->name('freightterms');
Route::get('salesfreightterms', [FreighttermsController::class, 'create'])->name('salesfreightterms');
Route::get('purchasefreightterms', [FreighttermsController::class, 'create'])->name('purchasefreightterms');


// Insurance Terms
Route::get('insurancetermschkname', [InsurancestermsController::class, 'getCheckname'])->name('insuchkname');
Route::get('insurancesterms', [InsurancestermsController::class, 'create'])->name('insurancesterms');
Route::get('purchaseinsuranceterms', [InsurancestermsController::class, 'create'])->name('purchaseinsuranceterms');
Route::post('insurancesterm/save', [InsurancestermsController::class, 'save'])->name('insurancestermssave');
Route::get('insurancetermsedit/{id}', [InsurancestermsController::class, 'getshow']);
Route::get('insurancetermsview/{id}', [InsurancestermsController::class, 'show']);
Route::get('insurancetermsdelete/{id}', [InsurancestermsController::class, 'getRemove'])->name('insurancetermsdelete');
Route::get('insurancetermsedit2/{id}', [InsurancestermsController::class, 'getedit'])->name('insurancetermsedit2');
Route::get('getinsurancetermsgrid', [InsurancestermsController::class, 'getinsurancetermsgrid'])->name('getinsurancetermsgrid');

//Freight Carriers
Route::get('purchasefreightcarriershdrview/{id}', [ArfreightcarriershdrController::class, 'show'])->name('purchasefreightcarriershdrview');
Route::get('purchasefreightcarriershdredit/{id}/{source}', [ArfreightcarriershdrController::class, 'edit'])->name('purchasefreightcarriershdredit');
Route::get('freightcarriesourcersedit2/{id}/{type}', [ArfreightcarriershdrController::class, 'getedit'])->name('freightcarriershdredit2');
Route::get('purchasefreightcarriershdr', [ArfreightcarriershdrController::class, 'index'])->name('purchasefreightcarriershdr');
Route::get('purchasefreightcarriershdrcreate/{id}', [ArfreightcarriershdrController::class, 'create'])->name('purchasefreightcarriershdrcreate');
Route::get('freightcarriershdrdelete/{id}/{type}', [ArfreightcarriershdrController::class, 'delete'])->name('freightcarriershdrdelete');


// Freight freightcarriers
Route::get('locationget', [ArfreightcarriershdrController::class, 'locationget'])->name('locationget');
Route::get('freightcarriershdr', [ArfreightcarriershdrController::class, 'index'])->name('freightcarriershdr');


/* Supplier Types */
Route::get('suppliertypes', [SuppliertypesController::class, 'index'])->name('suppliertypes');
Route::post('suppliertypes/save', [SuppliertypesController::class, 'save']);
Route::get('getSuppliertypesData', [SuppliertypesController::class, 'getSuppliertypesData'])->name('getSuppliertypesData');
Route::get('suppliertypes/checkname', [SuppliertypesController::class, 'getCheckname']);
Route::get('suppliertypescreate', [SuppliertypesController::class, 'create'])->name('suppliertypescreate');
Route::get('suppliertypeedit1/{id}', [SuppliertypesController::class, 'getedit']);
Route::get('suppliertypesdelete/{id}', [SuppliertypesController::class, 'delete'])->name('suppliertypesdelete');
Route::get('freightcarriershdrcreate/{id}', [ArfreightcarriershdrController::class, 'create'])->name('freightcarriershdrcreate');
Route::get('freightcarriershdrview/{id}', [ArfreightcarriershdrController::class, 'show'])->name('freightcarriershdrview');
Route::get('freightcarriershdredit/{id}/{type}', [ArfreightcarriershdrController::class, 'edit'])->name('freightcarriershdredit');
Route::get('freightcarriersedit2/{id}/{type}', [ArfreightcarriershdrController::class, 'getedit'])->name('freightcarriershdredit2');
Route::get('freightcarriershdrdelete/{id}/{type}', [ArfreightcarriershdrController::class, 'delete'])->name('freightcarriershdrdelete');
Route::post('freightcarrierssave', [ArfreightcarriershdrController::class, 'save'])->name('freightcarrierssave');
Route::get('getfreightcarrierData/{type}', [ArfreightcarriershdrController::class, 'getfreightGridData'])->name('getfreightcarrierData');
Route::get('freightcarnamechk/{id}', [ArfreightcarriershdrController::class, 'freightcarnamechk'])->name('freightcarnamechk');

/* Create Supplier */
Route::get('supplier', [SupplierController::class, 'index'])->name('supplier');
Route::get('suppliercreate/{id}', [SupplierController::class, 'create']);
Route::get('supplieredit/{id}', [SupplierController::class, 'edit']);
Route::get('supplierview/{id}', [SupplierController::class, 'show']);
Route::delete('supplierdelete/{id}', [SupplierController::class, 'delete'])->name('supplier.delete');

Route::get('gst_value/{id}', [SupplierController::class, 'gst_value'])->name('gst_value');
Route::get('suppliertypegst/{id}', [SupplierController::class, 'suppliertypegst'])->name('suppliertypegst');
Route::get('getsupplierData', [SupplierController::class, 'getsupplierData'])->name('getsupplierData');
Route::post('suppliersave', [SupplierController::class, 'resave'])->name('suppliersave');
Route::get('gstvalidation/{id}', [SupplierController::class, 'gstvalidation']);
Route::get('gstduplicate/{id}', [SupplierController::class, 'gstduplicate'])->name('gstduplicate');
Route::get('customerdetailsforsupplier/{id}', [SupplierController::class, 'customerdetailsforsupplier'])->name('customerdetailsforsupplier');
Route::get('suppliernamechkused/{id}', [SupplierController::class, 'suppliernamechk'])->name('suppliernamechkused');

// SUPPLIER UPLOAD
Route::get('supplierupload', [SupplieruploadController::class, 'index'])->name('supplierupload');
Route::get('getSupplieruploaddata', [SupplieruploadController::class, 'getSupplieruploaddata'])->name('getSupplieruploaddata');
Route::post('supplierdataupload', [SupplieruploadController::class, 'Uploadexcel'])->name('supplierdataupload');
Route::get('supplieruploadview/{id}', [SupplieruploadController::class, 'show'])->name('supplieruploadview');
Route::get('supplieruploadedit/{id}', [SupplieruploadController::class, 'create'])->name('supplieruploadedit');
Route::get('getSuppliervalidate', [SupplieruploadController::class, 'getSuppliervalidate'])->name('getSuppliervalidate');
Route::post('supplieruploadsave', [SupplieruploadController::class, 'save'])->name('supplieruploadsave');

//SUPPLIER SITE UPLOAD
Route::get('suppliersiteupload', [SuppliersiteuploadController::class, 'index'])->name('suppliersiteupload');
Route::get('getSuppliersiteuploaddata', [SuppliersiteuploadController::class, 'getSuppliersiteuploaddata'])->name('getSuppliersiteuploaddata');
Route::post('suppliersitedataupload', [SuppliersiteuploadController::class, 'Uploadexcel'])->name('suppliersitedataupload');
Route::get('suppliersiteuploadview/{id}', [SuppliersiteuploadController::class, 'show'])->name('suppliersiteuploadview');
Route::get('suppliersiteuploadedit/{id}', [SuppliersiteuploadController::class, 'create'])->name('suppliersiteuploadedit');
Route::get('getSuppliersitevalidate', [SuppliersiteuploadController::class, 'getSuppliersitevalidate'])->name('getSuppliersitevalidate');
Route::post('suppliersiteuploadsave', [SuppliersiteuploadController::class, 'save'])->name('suppliersiteuploadsave');

//SUPPLIER APPROVAL
Route::get('supplierapproval', [SupplierController::class, 'index'])->name('supplierapproval');
Route::get('supplierapprovalcreate/{id}', [SupplierController::class, 'edit'])->name('supplierapprovalcreate');


// Purchase Enquiry
Route::get('purchaseenquiry', [PurchaseenquiryController::class, 'index'])->name('purchaseenquiry');
Route::get('purchaseenquirycreate/{id}/{type}', [PurchaseenquiryController::class, 'create'])->name('purchaseenquirycreate');
Route::get('purchaseenquirylabourcreate/{id}/{type}', [PurchaseenquiryController::class, 'create'])->name('purchaseenquirylabourcreate');
Route::get('purchaseenquiryview/{id}', [PurchaseenquiryController::class, 'view'])->name('purchaseenquiryview');
Route::get('purchaseenquirydelete/{id}', [PurchaseenquiryController::class, 'delete'])->name('purchaseenquirydelete');
Route::post('purchaseenquirysave', [PurchaseenquiryController::class, 'save'])->name('purchaseenquirysave');
Route::get('poenquiryuom/{id}', [PurchaseenquiryController::class, 'poenquiryuom'])->name('poenquiryuom');
Route::get('getenquiryData', [PurchaseenquiryController::class, 'getenquiryData'])->name('getenquiryData');
Route::get('suppliermaildetails/{id}', [PurchaseorderController::class, 'getSuppliermaildetails'])->name('suppliermaildetails');
Route::post('pofilesave', [PurchaseorderController::class, 'pofilesave'])->name('pofilesave');
Route::get('poenquiryprint/{id}', [PurchaseenquiryController::class, 'poenquiryprint'])->name('poenquiryprint');
Route::post('poenquiryprint', [PurchaseenquiryController::class, 'poenquiryprint'])->name('poenquiryprintpost');
Route::post('purchaseenquiryfilesave', [PurchaseenquiryController::class, 'purchaseenquiryfilesave'])->name('purchaseenquiryfilesave');

// Purchase Copy Enquiry
Route::get('purchasecopyenquiry', [PurchaseenquiryController::class, 'index'])->name('purchasecopyenquiry');
Route::get('purchasecopyenquirycreate/{id}/{type}', [PurchaseenquiryController::class, 'create'])->name('purchasecopyenquirycreate');
Route::get('purchasecopyenquirycreate/{id}', [PurchaseenquiryController::class, 'create'])->name('purchasecopyenquirycreateid');

// Purchase Enquiry from Requisition
Route::get('purchaseenquirytorequestion', [PurchaserequisitionController::class, 'index'])->name('purchaseenquirytorequestion');
Route::get('purchaseenquirytorequestioncreate/{id}/{type}', [PurchaseenquiryController::class, 'create'])->name('purchaseenquirytorequestioncreate');
Route::get('purchaseenquirytorequestioncreate/{id}', [PurchaseenquiryController::class, 'create'])->name('purchaseenquirytorequestioncreateid');
Route::get('getSupplierData', [Controller::class, 'getSuppliergridData'])->name('getSupplierData');
Route::get('getProductgridData', [Controller::class, 'getProductgridData'])->name('getProductgridData');


// Purchase Requisition
Route::get('purchaserequisition', [PurchaserequisitionController::class, 'index'])->name('purchaserequisition');
Route::get('purchaserequisitioncreate/{id}', [PurchaserequisitionController::class, 'create'])->name('purchaserequisitioncreate');
Route::get('purchaserequisitioncreate', [PurchaserequisitionController::class, 'create'])->name('purchaserequisitioncreateform');
Route::get('purchaserequisitioncreate/{id}/{type}', [PurchaserequisitionController::class, 'create'])->name('purchaserequisitioncreatewithtype');
Route::get('considerpoqty/{id}', [PurchaserequisitionController::class, 'considerpoqty'])->name('considerpoqty');
Route::get('purchaserequisitionview/{id}', [PurchaserequisitionController::class, 'show'])->name('purchaserequisitionview');
Route::get('purchaserequisitiondelete/{id}', [PurchaserequisitionController::class, 'delete'])->name('purchaserequisitiondelete');
Route::post('purchaserequisitionsave', [PurchaserequisitionController::class, 'save'])->name('purchaserequisitionsave');
Route::get('porequom/{id}', [PurchaserequisitionController::class, 'porequom'])->name('porequom');
Route::get('getPurchaserequisitionData', [PurchaserequisitionController::class, 'getPurchaserequisitionData'])->name('getPurchaserequisitionData');


// Purchase Quote
Route::get('purchasequotation', [PurchasequotationController::class, 'index'])->name('purchasequotation');
Route::get('purchasequotationcreate/{id}/{type}', [PurchasequotationController::class, 'create'])->name('purchasequotation');
Route::get('purchaselabourquotationcreate/{id}/{type}', [PurchasequotationController::class, 'create'])->name('purchasequotation');
Route::get('purchasequotationcreate/{id}', [PurchasequotationController::class, 'create'])->name('purchasequotationapprove');
Route::get('purchasequotationview/{id}', [PurchasequotationController::class, 'show'])->name('purchasequotationview');
Route::get('getproductpvscost/{id}', [PurchasequotationController::class, 'getproductpvscost'])->name('getproductpvscost');
Route::get('purchasequotationdelete/{id}', [PurchasequotationController::class, 'delete'])->name('purchasequotationdelete');
Route::post('purchasequotationsave', [PurchasequotationController::class, 'save'])->name('purchasequotationsave');
Route::get('getPurchasequotationData', [PurchasequotationController::class, 'getPurchasequotationData'])->name('getPurchasequotationData');

// Supplier Price List
Route::get('supplierpricelist/{id}', [Controller::class, 'supplierpricelist'])->name('supplierpricelist');
Route::get('getpriceproduct/{id}/{pid}', [Controller::class, 'getpriceproduct'])->name('getpriceproduct');

// Purchase Invoice Approval
Route::get('poinvoiceapproval', [PurchaseinvoiceController::class, 'index'])->name('poinvoiceapproval');
Route::get('poinvapprove/{pohdrid}/{aprv}', [PurchaseinvoiceController::class, 'poinvapprovdatashow'])->name('poinvapprove');

// Purchase Copy Requisition
Route::get('purchasecopyrequisition', [PurchaserequisitionController::class, 'index'])->name('purchasecopyrequisition');
Route::get('purchasereqstatus/{id}', [PurchaserequisitionController::class, 'getStatus'])->name('purchasereqstatus');
Route::get('purchasecopyrequisition/{id}/{type}', [PurchaserequisitionController::class, 'create'])->name('purchasecopyrequisitioncreate');

// Purchase Requisition Approval
Route::get('purchaserequisitionapprove', [PurchaserequisitionController::class, 'index'])->name('purchaserequisitionapprove');

// Purchase Copy Quote
Route::get('purchasecopyquotation', [PurchasequotationController::class, 'index'])->name('purchasecopyquotation');
Route::get('purchasecopyquotecreate/{id}/{type}', [PurchasequotationController::class, 'create'])->name('purchasecopyquotationcreate');

// Purchase Quotation Approval
Route::get('purchasequtoetionapprove', [PurchasequotationController::class, 'index'])->name('purchasequtoetionapprove');

// Purchase Quotation from Enquiry
Route::get('purchaseenquirytoquote', [PurchaseenquiryController::class, 'index'])->name('purchaseenquirytoquote');
Route::get('purchaseenquirytoquotecreate/{id}/{type}', [PurchasequotationController::class, 'create'])->name('purchaseenquirytoquotecreate');
Route::get('purchaseenquirytoquotecreate/{id}', [PurchasequotationController::class, 'create'])->name('purchaseenquirytoquotecreateid');

// Purchase Quotation from Requisition
Route::get('purchaserequestiontoquote', [PurchaserequisitionController::class, 'index'])->name('purchaserequestiontoquote');
Route::get('purchasequtoetorequestioncreate/{id}/{type}', [PurchasequotationController::class, 'create'])->name('purchaserequestiontoquotecreate');

// Quotation Compare
Route::get('quotationcompare', [QuotationcompareController::class, 'index'])->name('quotationcompare');
Route::get('quotecomparsiondata', [QuotationcompareController::class, 'quotecomparsiondata'])->name('quotecomparsiondata');

// Purchase Order
Route::get('purchaseorder', [PurchaseorderController::class, 'index'])->name('purchaseorder');

Route::get('purchaseordercreate/{id}/{type}/{condition}', [PurchaseorderController::class, 'create'])->name('purchaseordercreate');
Route::get('purchaselabourordercreate/{id}/{type}/{condition}', [PurchaseorderController::class, 'create'])->name('purchaseordercreate');
Route::get('purchaseorderapprovcreate/{id}/{type}/{condition}', [PurchaseorderController::class, 'create'])->name('purchaseordercreate');

Route::get('purchaseordercreate/{id}/{condition}', [PurchaseorderController::class, 'create'])->name('purchaseordercreate');
Route::get('purchaselabourordercreate/{id}/{condition}', [PurchaseorderController::class, 'create'])->name('purchaseordercreate');
Route::get('purchaseorderapprovcreate/{id}/{condition}', [PurchaseorderController::class, 'create'])->name('purchaseordercreate');

Route::get('poorderupdatestatus/{id}', [PurchaseorderController::class, 'poorderupdatestatus'])->name('poorderupdatestatus');
Route::get('purchaseordercreate/{id}', [PurchaseorderController::class, 'create'])->name('purchaseordercreate');

Route::get('purchaseorderview/{id}', [PurchaseorderController::class, 'show']);
Route::get('getpoaltdatedatas/{id}', [PurchaseorderController::class, 'getpoaltdatedatas'])->name('getpoaltdatedatas');
Route::post('alternatedatesave', [PurchaseorderController::class, 'alternatedatesave'])->name('alternatedatesave');

Route::get('poviewinvoice/{id}', [PurchaseorderController::class, 'show']);
Route::get('purchaseorderdelete/{id}', [PurchaseorderController::class, 'delete'])->name('purchaseorderdelete');

Route::get('pouom/{id}', [PurchaseorderController::class, 'uomcode']);
Route::get('pricelistdetail/{id}/{pid}', [PurchaseorderController::class, 'pricelistdetail']);
Route::get('popricelist/{id}', [PurchaseorderController::class, 'getpricelist']);

Route::get('getPurchaseorderData/{id}', [PurchaseorderController::class, 'getPurchaseorderData']);
Route::get('getPurchaseorderData', [PurchaseorderController::class, 'getPurchaseorderData']);

Route::get('poapproval', [PurchaseorderController::class, 'index'])->name('poapproval');
Route::get('pocancellation', [PurchaseorderController::class, 'index'])->name('pocancellation');

Route::get('getsupplierdetails/{id}', [PurchaseorderController::class, 'getsupplierdetails'])->name('getsupplierdetails');
Route::get('productprice/{id}', [PurchaseorderController::class, 'productprice'])->name('productprice');
Route::get('getproductpricelist/{id}/{pid}', [PurchaseorderController::class, 'getproductpricelist'])->name('getproductpricelist');

Route::post('purchaseordersave', [PurchaseorderController::class, 'save']);
Route::get('approvalsave', [PurchaseorderController::class, 'approvalsave'])->name('approvalsave');
Route::get('poprint/{id}', [PurchaseorderController::class, 'poprint'])->name('poprint');

/* purchase po from enquiry */
Route::get('purchaseenquirytopo', [PurchaseenquiryController::class, 'index'])->name('purchaseenquirytopo');
Route::get('purchaseenquirytopocreate/{id}/{type}', [PurchaseorderController::class, 'create']);
Route::get('purchaseenquirytopocreate/{id}', [PurchaseorderController::class, 'create']);

/* purchase po from requisition */
Route::get('purchaserequestiontopo', [PurchaserequisitionController::class, 'index'])->name('purchaserequestiontopo');
Route::get('purchaserequestiontopocreate/{id}/{type}', [PurchaseorderController::class, 'create']);

/* purchase po from quote */
Route::get('purchasequtoetopo', [PurchasequotationController::class, 'index'])->name('purchasequtoetopo');
Route::get('purchasequtoetopocreate/{id}/{type}', [PurchaseorderController::class, 'create']);

/* purchase copy po */
Route::get('purchasecopypo', [PurchaseorderController::class, 'index'])->name('purchasecopypo');
Route::get('purchasecopypocreate/{id}/{type}', [PurchaseorderController::class, 'create'])->name('purchasecopypo');

// GIN 
Route::get('goodsinwardnote', [GoodsinwardnoteController::class, 'index'])->name('goodsinwardnote');
Route::get('getgoodsinwardData', [GoodsinwardnoteController::class, 'getgoodsinwardData'])->name('getgoodsinwardData');
Route::get('goodsinwnotecreatetbl/{id}', [GoodsinwardnoteController::class, 'goodsinwardnoteedit'])->name('goodsinwnotecreatetbl');
Route::get('goodsinwnoteviewtbl/{id}', [GoodsinwardnoteController::class, 'goodsinwardnoteview'])->name('goodsinwnoteviewtbl');
Route::get('goodsinwardnotecreate/{id}', [GoodsinwardnoteController::class, 'goodsinwardnotecreate'])->name('goodsinwardnotecreate');
Route::post('ginsave', [GoodsinwardnoteController::class, 'save'])->name('ginsave');
Route::get('goodsinwardnoteedit/{id}', [GoodsinwardnoteController::class, 'goodsinwardnoteedit'])->name('goodsinwardnoteedit');
Route::get('goodsinwardnotecreate', [GoodsinwardnoteController::class, 'goodsinwardnotecreate'])->name('goodsinwardnotecreate');

// GRN 
Route::get('grn', [GoodsreceiptnoteController::class, 'index'])->name('grn');
Route::get('getGrnData', [GoodsreceiptnoteController::class, 'getGrnData'])->name('getGrnData');
Route::get('goodsinwardData', [GoodsreceiptnoteController::class, 'getgoodsinwardData'])->name('goodsinwardData');
Route::get('genrategrns/{id}/{ids}', [GoodsreceiptnoteController::class, 'create'])->name('genrategrns');
Route::get('purchasetable', [GoodsreceiptnoteController::class, 'purchasetable'])->name('purchasetable');
Route::get('ApprovalData', [GoodsreceiptnoteController::class, 'getPurchaseorderData'])->name('ApprovalData');
Route::get('Gintable', [GoodsreceiptnoteController::class, 'gintable'])->name('Gintable');
Route::get('genrategrn/{id}', [GoodsreceiptnoteController::class, 'create'])->name('genrategrn');
Route::get('grnedit/{id}', [GoodsreceiptnoteController::class, 'edit'])->name('grnedit');
Route::get('grnview/{id}/{ids}', [GoodsreceiptnoteController::class, 'edit'])->name('grnview');
Route::get('grn_view/{id}/{ids}', [GoodsreceiptnoteController::class, 'edit'])->name('grn_view');
Route::get('checkgrnqty/{id}', [GoodsreceiptnoteController::class, 'checkgrnqty'])->name('checkgrnqty');
Route::post('genratesave', [GoodsreceiptnoteController::class, 'save'])->name('genratesave');
Route::get('boxdetails/{id}/{i}/{i1}/{i2}', [GoodsreceiptnoteController::class, 'boxdetails'])->name('boxdetails');
Route::get('productuom/{id}', [GoodsreceiptnoteController::class, 'productuom'])->name('productuom');
Route::get('getpoproduct/{id}/{pid}', [GoodsreceiptnoteController::class, 'getpoproduct'])->name('getpoproduct');
Route::get('dccheckname', [GoodsreceiptnoteController::class, 'Checkname'])->name('dccheckname');

// SRN 
Route::get('srn', [ServicereceiptnoteController::class, 'index'])->name('srn');
Route::get('getSrnData', [ServicereceiptnoteController::class, 'getSrnData'])->name('getSrnData');
Route::get('genratesrn/{id}/{ids}', [ServicereceiptnoteController::class, 'create'])->name('genratesrn');
Route::get('purchaselabourtable', [ServicereceiptnoteController::class, 'purchaselabourtable'])->name('purchaselabourtable');
Route::get('genratesrn/{id}', [ServicereceiptnoteController::class, 'create'])->name('genratesrn.single');
Route::get('getPurchaselabourData', [ServicereceiptnoteController::class, 'getPurchaselabourData'])->name('getPurchaselabourData');
Route::post('genratesrnsave', [ServicereceiptnoteController::class, 'save'])->name('genratesrnsave');
Route::get('srnedit/{id}', [ServicereceiptnoteController::class, 'edit'])->name('srnedit');

/* Purchase Invoice */
Route::get('purchaseinvoice', [PurchaseinvoiceController::class, 'index'])->name('purchaseinvoice');
Route::get('purchaseinvoicetable', [PurchaseinvoiceController::class, 'purchaseinvoicecreatefrompo'])->name('purchaseinvoicetable');
Route::get('createpoinvoice/{id}', [PurchaseinvoiceController::class, 'createpoinvoice'])->name('createpoinvoice');
Route::get('loadtds/{id}/{idss}', [PurchaseinvoiceController::class, 'loadtds'])->name('loadtds');
Route::get('loadtdssubcontract/{id}/{idss}', [PurchaseinvoiceController::class, 'loadtdssubcontract'])->name('loadtdssubcontract');
Route::get('poData', [PurchaseinvoiceController::class, 'getpoData'])->name('poData');
Route::get('getinvoiceData', [PurchaseinvoiceController::class, 'getinvoiceData'])->name('getinvoiceData');
Route::post('creditupdate/{id}', [PurchaseinvoiceController::class, 'creditupdate'])->name('creditupdate');
Route::get('invoiceDataedit/{id}', [PurchaseinvoiceController::class, 'invoiceDataedit'])->name('invoiceDataedit');
Route::get('invoiceDataview/{id}', [PurchaseinvoiceController::class, 'invoiceDataview'])->name('invoiceDataview');
Route::get('invoice_view/{id}', [PurchaseinvoiceController::class, 'invoiceDataview'])->name('invoice_view');
Route::post('poinvoiceformsave', [PurchaseinvoiceController::class, 'poinvoiceformsave'])->name('poinvoiceformsave');
Route::get('poinvoiceapproval', [PurchaseinvoiceController::class, 'index'])->name('poinvoiceapproval');
Route::post('poinvoiceReverse/{id}', [PurchaseInvoiceController::class, 'reverseInvoice']);

/* Due Date Calculate */
Route::get('duedatecal', [PurchaseinvoiceController::class, 'duedatecal'])->name('duedatecal');

/* Labour Invoice */
Route::get('getPolabourData', [PurchaseinvoiceController::class, 'getPolabourData'])->name('getPolabourData');
Route::get('polabourtable', [PurchaseinvoiceController::class, 'polabourtable'])->name('polabourtable');
Route::get('createpolabourinvoice/{id}', [PurchaseinvoiceController::class, 'createpolabourinvoice'])->name('createpolabourinvoice');
Route::get('createpolabourinvoice', [PurchaseinvoiceController::class, 'createpolabourinvoice'])->name('createpolabourinvoice.direct');

/* Purchase Return */
Route::get('purchasereturn', [PurchasereturnController::class, 'index'])->name('purchasereturn');
Route::get('getpurchasereturnData', [PurchasereturnController::class, 'purchasereturnData'])->name('getpurchasereturnData');
Route::get('invoiceData', [PurchasereturnController::class, 'invoiceData'])->name('invoiceData');
Route::get('purchasesuppliermaildetails/{id}', [PurchasereturnController::class, 'getSuppliermaildetails'])->name('suppliermaildetails');
Route::post('returncreditupdate/{id}', [PurchasereturnController::class, 'returncreditupdate'])->name('returncreditupdate');
Route::get('invoicetable', [PurchasereturnController::class, 'invoicetable'])->name('invoicetable');
Route::get('purchasereturncreate/{id}', [PurchasereturnController::class, 'purchasereturncreate'])->name('purchasereturncreate');
Route::post('purchasereturnsave', [PurchasereturnController::class, 'purchasereturnsave'])->name('purchasereturnsave');
Route::get('purchasereturnedit/{id}', [PurchasereturnController::class, 'purchasereturnedit'])->name('purchasereturnedit');
Route::get('purchasereturnedit/{id}/{idss}', [PurchasereturnController::class, 'purchasereturnedit'])->name('purchasereturnedit.multi');
Route::get('purchasereturnview/{id}', [PurchasereturnController::class, 'purchasereturnview'])->name('purchasereturnview');
Route::get('purchasereturn_view/{id}', [PurchasereturnController::class, 'purchasereturnview'])->name('purchasereturn_view');
Route::get('purchasereturnprint/{id}', [PurchasereturnController::class, 'getprint'])->name('purchasereturnprint');
Route::post('purchasefilesave', [PurchasereturnController::class, 'purchasefilesave'])->name('purchasefilesave');

/* Purchase DC */
Route::get('purchasedc', [PurchasedcController::class, 'index'])->name('purchasedc');
Route::get('purchasedcData', [PurchasedcController::class, 'purchasedcData'])->name('purchasedcData');
Route::get('grntablefordc', [PurchasedcController::class, 'grntablefordc'])->name('grntablefordc');
Route::get('purchasedccreate/{id}', [PurchasedcController::class, 'purchasedccreate'])->name('purchasedccreate');
Route::post('purchasedcsave', [PurchasedcController::class, 'purchasedcsave'])->name('purchasedcsave');
Route::get('purchasedcview/{id}', [PurchasedcController::class, 'purchasedcview'])->name('purchasedcview');
Route::get('grnData', [PurchasedcController::class, 'grnData'])->name('grnData');
Route::get('purchasedcprint/{id}', [PurchasedcController::class, 'getprint'])->name('purchasedcprint');

/* GRN Print */
Route::get('grnprint/{id}', [GoodsreceiptnoteController::class, 'pogrnprint'])->name('grnprint');

/* PO Invoice Print */
Route::get('poinvoiceprint/{id}', [PurchaseinvoiceController::class, 'poinvoiceprint'])->name('poinvoiceprint');

/*Return To Vendor*/
Route::get('purchasereturnapproval', [PurchasereturnController::class, 'index'])->name('purchasereturnapproval');
Route::get('purchasereturnapprove/{pohdrid}/{aprv}', [PurchasereturnController::class, 'purchasereturnapproval'])->name('purchasereturnapprove');


/*Subcontract Search Popup in GIN*/
Route::get('getSubcontractgridData', [GoodsinwardnoteController::class, 'getSubcontractgridData'])->name('getSubcontractgridData');

/*Purchase Replacement*/
Route::get('purchasereplacement', [PurchasereplacementController::class, 'index'])->name('purchasereplacement');
Route::get('getpurchasereplacementData', [PurchasereplacementController::class, 'purchasereplacementData'])->name('getpurchasereplacementData');
Route::get('invoiceDatarplt', [PurchasereplacementController::class, 'invoiceData'])->name('invoiceDatarplt');
Route::get('purchasesuppliermaildetails/{id}', [PurchasereplacementController::class, 'getSuppliermaildetails'])->name('suppliermaildetails');
Route::get('grntablerplt', [PurchasereplacementController::class, 'invoicetable'])->name('grntablerplt');
Route::get('purchasereplacementcreate/{id}', [PurchasereplacementController::class, 'purchasereplacementcreate'])->name('purchasereplacementcreate');
Route::post('purchasereplacementsave', [PurchasereplacementController::class, 'purchasereplacementsave'])->name('purchasereplacementsave');
Route::get('purchasereplacementedit/{id}', [PurchasereplacementController::class, 'purchasereplacementedit'])->name('purchasereplacementedit');
Route::get('purchasereplacementedit/{id}/{idss}', [PurchasereplacementController::class, 'purchasereplacementedit'])->name('purchasereplacementeditwithidss');
Route::get('purchasereplacementview/{id}', [PurchasereplacementController::class, 'purchasereplacementview'])->name('purchasereplacementview');
Route::get('purchasereplacement_view/{id}', [PurchasereplacementController::class, 'purchasereplacementview'])->name('purchasereplacement_view');
Route::get('purchasereplacementprint/{id}/{ids}', [PurchasereplacementController::class, 'getprint'])->name('purchasereplacementprint');

/*Purchase Replacement Approval*/
Route::get('purchasereplacementapproval', [PurchasereplacementController::class, 'index'])->name('purchasereplacementapproval');
Route::get('purchasereplacementapprove/{pohdrid}/{aprv}', [PurchasereplacementController::class, 'purchasereplacementapproval'])->name('purchasereplacementapprove');
