<?php

use App\Http\Controllers\ProductgroupController;
use App\Http\Controllers\ProductcategoryController;
use App\Http\Controllers\ProductsubcategoryController;
use App\Http\Controllers\ProducttypeController;
use App\Http\Controllers\ProductvariantController;
use App\Http\Controllers\ProductpacktypeController;
use App\Http\Controllers\ProductpackController;
use App\Http\Controllers\SubinventorytransferController;
use App\Http\Controllers\SubinventoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\AssetproductconfigController;
use App\Http\Controllers\ConsumableController;
use App\Http\Controllers\ManufacturerpartnoController;
use App\Http\Controllers\ProductuploadController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\CustomersController;
use App\Http\Controllers\PurchasepricelistController;
use App\Http\Controllers\MaterialbomController;
use App\Http\Controllers\SchemesController;
use App\Http\Controllers\MtltransactiontypesController;
use App\Http\Controllers\QuantityonhandController;
use App\Http\Controllers\OpenstockController;
use App\Http\Controllers\PricelistuploadController;
use App\Http\Controllers\BatchconversionController;
use App\Http\Controllers\VerdurabatchconversionController;
use App\Http\Controllers\materialreturnController;
use App\Http\Controllers\SupplierbasedcostreportController;
use App\Http\Controllers\UomcodesController;
use App\Http\Controllers\UomconversionController;


// 	LARAVEL 8 ROUTE


// Product Group
Route::get('productgroupcheckname', [ProductgroupController::class, 'Checkname'])->name('productgroupcheckname');
Route::get('productgroup', [ProductgroupController::class, 'create'])->name('productgroup');
Route::post('productgroupsave', [ProductgroupController::class, 'save'])->name('productgroupsave');
Route::get('productgroupdelete/{id}', [ProductgroupController::class, 'delete'])->name('productgroupdelete');
Route::get('productgroupedit/{id}', [ProductgroupController::class, 'getedit'])->name('productgroupedit');
Route::get('getProductgroupData', [ProductgroupController::class, 'getProductgroupData'])->name('getProductgroupData');

// Product Category
Route::get('productcategorycheckname', [ProductcategoryController::class, 'getCheckname'])->name('productcategorycheckname');
Route::get('productcategory', [ProductcategoryController::class, 'create'])->name('productcategorycreate');
Route::post('productcategorysave', [ProductcategoryController::class, 'save'])->name('productcategorysave');
Route::get('productcategoryedit/{id}/{type}', [ProductcategoryController::class, 'create'])->name('productcategoryedit');
Route::get('productcategoryview/{id}', [ProductcategoryController::class, 'view'])->name('productcategoryview');
Route::get('productcategorydelete/{id}', [ProductcategoryController::class, 'getRemove'])->name('productcategorydelete');
Route::get('jcomboforminv', [Controller::class, 'jcomboforminv'])->name('jcomboforminv');
Route::get('jcomboform1', [Controller::class, 'jcomboform1'])->name('jcomboform1');
Route::get('getProductcategory', [ProductcategoryController::class, 'getProductcategoryData'])->name('getProductcategory');
Route::get('productcategoryeditchk', [ProductcategoryController::class, 'productcategoryeditchk'])->name('productcategoryeditchk');
Route::get('getGridData', [ProductcategoryController::class, 'getGridData'])->name('getGridData');


// Product Subcategory
Route::get('productsubcategory', [ProductsubcategoryController::class, 'create'])->name('productsubcategory');
Route::post('productsubcategorysave', [ProductsubcategoryController::class, 'save'])->name('productsubcategorysave');
Route::get('productsubcategoryedit', [ProductsubcategoryController::class, 'productsubcategoryedit'])->name('productsubcategoryedit');
Route::get('productsubcategoryview/{id}', [ProductsubcategoryController::class, 'view'])->name('productsubcategoryview');
Route::get('productsubcategorydelete/{id}', [ProductsubcategoryController::class, 'destroy'])->name('productsubcategorydelete');
Route::get('productsubcategorycheckname', [ProductsubcategoryController::class, 'getcheckname'])->name('productsubcategorycheckname');
Route::get('getProductsubcategoryData', [ProductsubcategoryController::class, 'getProductsubcategoryData'])
	->name('getProductsubcategoryData');


// product type
Route::get('producttype', [ProducttypeController::class, 'create'])->name('producttype');
Route::post('producttypesave', [ProducttypeController::class, 'save'])->name('producttypesave');
Route::get('getProducttype', [ProducttypeController::class, 'producttypegriddata'])->name('getProducttype');
Route::get('producttypecheckname', [ProducttypeController::class, 'producttypecheckname'])->name('producttypecheckname');
Route::get('producttypedelete/{id}', [ProducttypeController::class, 'delete'])->name('producttypedelete');

// Product Variant
Route::get('productvariant', [ProductvariantController::class, 'create'])->name('productvariant');
Route::post('productvariantsave', [ProductvariantController::class, 'save'])->name('productvariantsave');
Route::get('productvariantedit/{id}', [ProductvariantController::class, 'create'])->name('productvariantedit');
Route::get('getproductvariantData', [ProductvariantController::class, 'getproductvariantData'])->name('getproductvariantData');
Route::get('productvariantdelete/{id}', [ProductvariantController::class, 'delete'])->name('productvariantdelete');
Route::get('productvariantcheckname', [ProductvariantController::class, 'getProductvariantcheckname'])->name('productvariantcheckname');

// productpacktype
Route::get('productpacktype', [ProductpacktypeController::class, 'create'])->name('productpacktype');
Route::post('productpacktypesave', [ProductpacktypeController::class, 'save'])->name('productpacktypesave');
Route::get('getpacktype', [ProductpacktypeController::class, 'packtypegriddata'])->name('getpacktype');
Route::get('packtypecheckname', [ProductpacktypeController::class, 'packtypecheckname'])->name('packtypecheckname');
Route::get('productpacktypedelete/{id}', [ProductpacktypeController::class, 'delete'])->name('productpacktypedelete');
Route::get('productpacktypeeditchk/{id}', [ProductpacktypeController::class, 'productpacktypeeditchk'])->name('productpacktypeeditchk');

// productpack
Route::get('productpack', [ProductpackController::class, 'create'])->name('productpack');
Route::post('productpacksave', [ProductpackController::class, 'save'])->name('productpacksave');
Route::get('getproductpack', [ProductpackController::class, 'productpackgriddata'])->name('getproductpack');
Route::get('productpackcheckname', [ProductpackController::class, 'productpackcheckname'])->name('productpackcheckname');
Route::get('productpackdelete/{id}', [ProductpackController::class, 'delete'])->name('productpackdelete');


// Subinventory Transfer
Route::get('subinventorytransfers', [SubinventorytransferController::class, 'create'])->name('subinventorytransfers');
Route::post('subinventorytransfersave', [SubinventorytransferController::class, 'save'])->name('subinventorytransfersave');
Route::post('transfersave', [SubinventorytransferController::class, 'transfersave'])->name('transfersave');
Route::get('prddetails', [SubinventorytransferController::class, 'prddetails'])->name('prddetails');
Route::get('productqohdetails', [SubinventorytransferController::class, 'productqohdetails'])->name('productqohdetails');
Route::get('subinventorybatchno', [SubinventorytransferController::class, 'productbatchno'])->name('productbatchno');
Route::get('subinventorybatchno1', [SubinventorytransferController::class, 'productbatchno1'])->name('productbatchno1');
Route::get('toproduct', [BatchconversionController::class, 'toproduct'])->name('toproduct');
Route::get('toproductkgs', [BatchconversionController::class, 'toproductkgs'])->name('toproductkgs');

// Subinventory Transfer Receive
Route::get('subinventorytransferreceive', [SubinventorytransferController::class, 'receiveindex'])->name('subinventorytransferreceive');
Route::get('subinventorytransfercreate/{id}', [SubinventorytransferController::class, 'receivecreate'])->name('subinventorytransfercreate');
Route::get('getreceiveData', [SubinventorytransferController::class, 'getreceiveData'])->name('getreceiveData');
Route::get('Receiveupdate/{id}/{rcvqty}', [SubinventorytransferController::class, 'Receiveupdate'])->name('Receiveupdate');

// Subinventory
Route::get('subinventory', [SubinventoryController::class, 'index'])->name('subinventory');
Route::post('subinventorysave', [SubinventoryController::class, 'save'])->name('subinventorysave');
Route::get('subinventorycreate', [SubinventoryController::class, 'create'])->name('subinventorycreate');
Route::get('subinventoryedit/{id}', [SubinventoryController::class, 'create'])->name('subinventoryedit');
Route::get('subinventoryview/{id}', [SubinventoryController::class, 'view'])->name('subinventoryview');
Route::get('getinvlocData', [SubinventoryController::class, 'getinvlocData'])->name('getinvlocData');
Route::get('subinventorycheckname', [SubinventoryController::class, 'Checkname'])->name('subinventorycheckname');
Route::get('subinventorydelete/{id}', [SubinventoryController::class, 'destroy'])->name('subinventorydelete');
Route::get('subinventoryeditchk/{id}', [SubinventoryController::class, 'getedit'])->name('subinventoryeditchk');


// Product
Route::get('product', [ProductController::class, 'index'])->name('product');
Route::get('productcreate', [ProductController::class, 'create'])->name('productcreate');
Route::get('ProductgridData', [ProductController::class, 'ProductgridData'])->name('ProductgridData');
Route::post('productsave', [ProductController::class, 'save'])->name('productsave');
Route::get('productedit/{id}', [ProductController::class, 'create'])->name('productedit'); // ⚠️ same name as create
Route::get('productdelete/{id}', [ProductController::class, 'delete'])->name('productdelete');
Route::get('productview/{id}', [ProductController::class, 'show']);
Route::get('product/companydetails', [ProductController::class, 'companydetails']);
Route::get('rolupdate', [ProductController::class, 'rolupdate'])->name('rolupdate');
Route::get('prdroledit', [ProductController::class, 'prdroledit'])->name('prdroledit');

// Asset Product Config
Route::get('assetproductconfig', [AssetproductconfigController::class, 'assetproductconfigindex'])->name('product'); // ⚠️ duplicated with product
Route::get('assetproductconfigcreate', [AssetproductconfigController::class, 'assetproductconfigcreate'])->name('assetproductconfigcreate');
Route::get('assetproductconfiggridData', [AssetproductconfigController::class, 'assetproductconfiggridData'])->name('assetproductconfiggridData');
Route::post('assetproductconfigsave', [AssetproductconfigController::class, 'assetproductconfigsave'])->name('assetproductconfigsave');
Route::get('assetproductconfigedit/{id}', [AssetproductconfigController::class, 'assetproductconfigcreate'])->name('assetproductconfigcreate');
Route::get('assetproductconfigdelete/{id}', [AssetproductconfigController::class, 'assetproductconfigdelete'])->name('assetproductconfigdelete');
Route::get('assetproductconfigview/{id}', [AssetproductconfigController::class, 'assetproductconfigshow'])->name('assetproductconfigview');

// Other Product related
Route::get('accountassign', [ProductController::class, 'accountassign'])->name('accountassign');
Route::get('product/companyassign/{id}/{ids}', [ProductController::class, 'companyassign'])->name('product/companyassign');
Route::get('getPriceData', [ProductController::class, 'getPriceData'])->name('getPriceData');
Route::get('productspec/{pid}/{id}', [ProductController::class, 'productspec'])->name('productspec');
Route::post('productspecsave', [ProductController::class, 'productspecsave'])->name('productspecsave');
Route::get('batchnoedit/{id}', [ProductController::class, 'batchnoedit'])->name('batchnoedit');

// Manufacturer Part No.
Route::get('manufacturerpartno', [ManufacturerpartnoController::class, 'index'])->name('manufacturerpartno');
Route::get('manufacturerpartnocreate/{id}', [ManufacturerpartnoController::class, 'create'])->name('manufacturerpartnocreate');
Route::get('manufacturerpartnoview/{id}', [ManufacturerpartnoController::class, 'show'])->name('manufacturerpartnoview');
Route::get('manufacturerpartnoedit/{id}', [ManufacturerpartnoController::class, 'edit'])->name('manufacturerpartnoedit');
Route::post('manufacturerpartnosave', [ManufacturerpartnoController::class, 'save'])->name('manufacturerpartnosave');
Route::post('manunocheck/{id}', [ManufacturerpartnoController::class, 'manunocheck'])->name('manunocheck');
Route::get('getGridmfgData', [ManufacturerpartnoController::class, 'getGridmfgData'])->name('getGridmfgData');
Route::get('mfgsourcevalidate', [ManufacturerpartnoController::class, 'mfgsourcevalidate']);
Route::get('mfgdelete/{id}', [ManufacturerpartnoController::class, 'delete'])->name('mfgdelete');

// Product Upload
Route::get('productupload', [ProductuploadController::class, 'index'])->name('productupload');
Route::get('getproductuploaddata', [ProductuploadController::class, 'getproductuploaddata'])->name('getproductuploaddata');
Route::get('getProductvalidate', [ProductuploadController::class, 'getProductvalidate'])->name('getProductvalidate');
Route::post('productuploaddata', [ProductuploadController::class, 'Uploadexcel'])->name('productuploaddata');
Route::get('productuploadedit/{id}', [ProductuploadController::class, 'create'])->name('productuploadedit');
Route::post('productuploadsave', [ProductuploadController::class, 'save'])->name('productuploadsave');

// Purchase & Sales Price List Approval
Route::get('purchasepricelistapproval', [PurchasepricelistController::class, 'index'])->name('purchasepricelistapproval');
Route::get('salespricelistapproval', [PurchasepricelistController::class, 'index'])->name('salespricelistapproval');
Route::get('salespricelistapprovaledit/{id}', [PurchasepricelistController::class, 'edit'])->name('salespricelistapproval');
Route::get('purchasepricelistapprovaledit/{id}', [PurchasepricelistController::class, 'edit'])->name('purchasepricelistapproval');

// Product Approval
Route::get('productapproval', [ProductController::class, 'index'])->name('productapproval');
Route::get('productapproved/{id}', [ProductController::class, 'create'])->name('productapproved');

// Material BOM Approval
Route::get('materialbomapproval', [MaterialbomController::class, 'index'])->name('materialbomapproval');
Route::get('materialbomapprovalcreate/{id}', [MaterialbomController::class, 'create'])->name('materialbomapprovalcreate');

// Mtl Transaction Types
Route::get('mtltransactiontypes', [MtltransactiontypesController::class, 'create'])->name('mtltransactiontypes');
Route::get('mtltransactiontypesedit', [MtltransactiontypesController::class, 'edit'])->name('mtltransactiontypesedit');
Route::post('mtltransactiontypessave', [MtltransactiontypesController::class, 'save'])->name('mtltransactiontypessave');
Route::get('mtltransactiontypesview/{id}', [MtltransactiontypesController::class, 'view'])->name('mtltransactiontypes');
Route::get('getGridMtlData', [MtltransactiontypesController::class, 'getGridMtlData'])->name('getGridMtlData');
Route::get('transactiondelete/{id}', [MtltransactiontypesController::class, 'destroy'])->name('transactiondelete');
Route::get('transactioncheckname', [MtltransactiontypesController::class, 'getCheckname'])->name('transactioncheckname');
Route::get('transactionedit/{id}', [MtltransactiontypesController::class, 'getedit'])->name('transactionedit');
Route::get('getTransactionData', [MtltransactiontypesController::class, 'getTransactionData'])->name('getTransactionData');

// Product History View
Route::get('producthistoryview/{id}', [ProductController::class, 'imageshow'])->name('producthistoryview');

// Consumable
Route::get('consumableedit/{id}', [ConsumableController::class, 'create'])->name('consumableedit');
Route::get('qohdatas', [ConsumableController::class, 'getproductissueqoh'])->name('qohdatas');
Route::get('consumableview/{id}', [ConsumableController::class, 'consumableshow'])->name('consumableview');
Route::get('productbatchno', [ConsumableController::class, 'productbatchno'])->name('productbatchno');
Route::get('getinventlocator', [ConsumableController::class, 'getinventlocator']);
Route::get('consumable', [ConsumableController::class, 'index'])->name('consumable');
Route::get('consumablecreate', [ConsumableController::class, 'create'])->name('consumablecreate');
Route::get('approveconsumable', [ConsumableController::class, 'create'])->name('approveconsumable');
Route::post('consumablesave', [ConsumableController::class, 'save'])->name('consumablesave');
Route::get('getconsumableData', [ConsumableController::class, 'getconsumableData'])->name('getconsumableData');
Route::get('consumableapproval', [ConsumableController::class, 'index'])->name('consumableapproval');
Route::get('consumablereport', [ConsumableController::class, 'consumablereportindex'])->name('consumablereport');
Route::get('getconsumablereportdata', [ConsumableController::class, 'getconsumablereportdata'])->name('getconsumablereportdata');


// QOH Grid 
Route::get('rawquantityonhand', [QuantityonhandController::class, 'index'])->name('rawquantityonhand');
Route::get('fgquantityonhand', [QuantityonhandController::class, 'index'])->name('fgquantityonhand');
Route::get('quantityonhand', [QuantityonhandController::class, 'index'])->name('quantityonhand');
Route::get('getSubGridqohData', [QuantityonhandController::class, 'getSubGridqohData'])->name('getSubGridqohData');
Route::get('getGridqohData', [QuantityonhandController::class, 'getGridqohData'])->name('getGridqohData');


// Open Stock 
Route::get('openstockupload', [OpenstockController::class, 'index'])->name('openstockupload');
Route::get('getOpenstockData', [OpenstockController::class, 'getOpenstockData'])->name('getOpenstockData');
Route::get('getStockvalidate', [OpenstockController::class, 'getvalidateload'])->name('getStockvalidate');
Route::post('stockupload', [OpenstockController::class, 'Uploadexcel'])->name('stockupload');
Route::get('openstockview/{id}', [OpenstockController::class, 'show']);
Route::get('openstockedit/{id}', [OpenstockController::class, 'create']);
Route::post('openstocksave', [OpenstockController::class, 'save'])->name('openstocksave');


// Pricelist Upload 
Route::get('pricelistupload', [PricelistuploadController::class, 'index'])->name('pricelistupload');
Route::get('getpricelistuploaddata', [PricelistuploadController::class, 'getpricelistuploaddata'])->name('getpricelistuploaddata');
Route::get('getPricevalidate', [PricelistuploadController::class, 'getPricevalidate'])->name('getPricevalidate');
Route::post('pricelistdataupload', [PricelistuploadController::class, 'Uploadexcel'])->name('pricelistdataupload');
Route::get('pricelistuploadedit/{id}', [PricelistuploadController::class, 'create']);
Route::post('pricelistuploadsave', [PricelistuploadController::class, 'save'])->name('pricelistuploadsave');

//  Batch Conversion 
Route::get('batchconversionapproval', [BatchconversionController::class, 'index'])->name('batchconversionapproval');
Route::get('batchconversion', [BatchconversionController::class, 'create'])->name('batchconversion');
Route::post('conversionsave', [BatchconversionController::class, 'save'])->name('conversionsave');
Route::get('getconversionData', [BatchconversionController::class, 'getconversionData'])->name('getconversionData');
Route::get('approveconversion', [BatchconversionController::class, 'create'])->name('approveconversion');
Route::post('approveconversionsave', [BatchconversionController::class, 'Approve'])->name('approveconversionsave');


//  Verdura Batch Conversion 
Route::get('verdurabatchconversion', [VerdurabatchconversionController::class, 'create'])->name('verdurabatchconversion');
Route::get('verdurabatchconversionapproval', [VerdurabatchconversionController::class, 'index'])->name('verdurabatchconversionapproval');
Route::get('verduraapproveconversion', [VerdurabatchconversionController::class, 'create'])->name('verduraapproveconversion');
Route::post('verduraconversionsave', [VerdurabatchconversionController::class, 'save'])->name('verduraconversionsave');
Route::post('verduraapproveconversionsave', [VerdurabatchconversionController::class, 'Approve'])->name('verduraapproveconversionsave');
Route::get('verduragetconversionData', [VerdurabatchconversionController::class, 'getconversionData'])->name('verduragetconversionData');

// Material Return
Route::get('materialreturn', [MaterialreturnController::class, 'index'])->name('materialreturn');
Route::get('getmaterialreturnData', [MaterialreturnController::class, 'getmaterialreturnData'])->name('getmaterialreturnData');
Route::get('materialreqcreate/{id}', [MaterialreturnController::class, 'create'])->name('materialreqcreate');
Route::post('materialreturnsave', [MaterialreturnController::class, 'save'])->name('jobcardsave');

// Reports
Route::get('inventoryageingrpt', [SupplierbasedcostreportController::class, 'inventoryageingindex'])->name('inventoryageingrpt');
Route::get('getinventoryageing', [SupplierbasedcostreportController::class, 'getinventoryageing'])->name('getinventoryageing');
Route::get('expiryprdnearbyrpt', [SupplierbasedcostreportController::class, 'expiryprdnearbyindex'])->name('expiryprdnearbyrpt');
Route::get('getexpiryprdnearby', [SupplierbasedcostreportController::class, 'getexpiryprdnearby'])->name('getexpiryprdnearby');
Route::get('productassetdetailsrpt', [SupplierbasedcostreportController::class, 'productassetdetailsindex'])->name('productassetdetailsrpt');
Route::get('getproductassetdetails', [SupplierbasedcostreportController::class, 'getproductassetdetails'])->name('getproductassetdetails');
Route::get('slowmovingproduct', [SupplierbasedcostreportController::class, 'slowmovingproductindex'])->name('slowmovingproduct');
Route::get('getslowmovingproduct', [SupplierbasedcostreportController::class, 'getslowmovingproduct'])->name('getslowmovingproduct');

// Purchase Price List 
Route::get('purchasepricelist', [PurchasepricelistController::class, 'index'])->name('purchasepricelist');
Route::get('purchasepricelistcreate', [PurchasepricelistController::class, 'create'])->name('purchasepricelist');
Route::get('purchasepricelistedit/{id}', [PurchasepricelistController::class, 'edit'])->name('purchasepricelistedit');
Route::get('getPurchasepricelistData/{id}', [PurchasepricelistController::class, 'getGridmfgData'])->name('getPurchasepricelistData');
Route::post('purchasepricelistsave', [PurchasepricelistController::class, 'save'])->name('purchasepricelistsave');
Route::get('purchasepricelistview/{id}', [PurchasepricelistController::class, 'view'])->name('purchasepricelistview');
Route::get('purchasepricelistdelete/destroy/{id}/{type}', [PurchasepricelistController::class, 'destroy'])->name('purchasepricelistdelete');
Route::get('purchasepricelist/pricelistcheckname', [PurchasepricelistController::class, 'pricelistcheckname'])->name('pricelistcheckname');

// Sales Price List 
Route::get('salespricelist', [PurchasepricelistController::class, 'index'])->name('salespricelist');
Route::get('salespricelistcreate', [PurchasepricelistController::class, 'create'])->name('salespricelistcreate');
Route::get('salespricelistedit/{id}', [PurchasepricelistController::class, 'edit'])->name('salespricelistedit');
Route::get('salespricelistview/{id}', [PurchasepricelistController::class, 'view'])->name('salespricelistview');
Route::get('salespricelistbatch/{id}', [PurchasepricelistController::class, 'salespricelistbatch'])->name('salespricelistbatch');

// Purchase Price List Copy 
Route::get('purchasepricelistcopy', [PurchasepricelistController::class, 'index'])->name('purchasepricelistcopy');
Route::get('purchasepricelistcopyedit/{id}', [PurchasepricelistController::class, 'edit'])->name('purchasepricelistcopyedit');
Route::get('purchasepricelistcopyview/{id}', [PurchasepricelistController::class, 'view'])->name('purchasepricelistcopyview');

// Sales Price List Copy 
Route::get('salespricelistcopy', [PurchasepricelistController::class, 'index'])->name('salespricelistcopy');
Route::get('salespricelistcopyedit/{id}', [PurchasepricelistController::class, 'edit'])->name('salespricelistcopyedit');
Route::get('salespricelistcopyview/{id}', [PurchasepricelistController::class, 'view'])->name('salespricelistcopyview');
Route::get('getProductgridData', [Controller::class, 'getProductgridData'])->name('getProductgridData');

// UOM Codes
Route::get('uomcodes', [UomcodesController::class, 'create'])->name('uomcodes');
Route::post('uomcodessave', [UomcodesController::class, 'save'])->name('uomcodessave');
Route::get('uomcodesview/{id}', [UomcodesController::class, 'view'])->name('uomcodesview');
Route::get('uomcodesedit/{id}/{type}', [UomcodesController::class, 'create'])->name('uomcodesedit');
Route::get('getGridUomData', [UomcodesController::class, 'getGridUomData'])->name('getGriduomData');
Route::get('uomcodescheckname', [UomcodesController::class, 'getCheckname']);
Route::get('uomcodesdelete/{id}', [UomcodesController::class, 'destroy'])->name('uomcodesdelete');

Route::get('uomedit/{id}', [UomcodesController::class, 'getedit'])->name('uomedit');


// UOM Conversion 
Route::get('uomconversion', [UomconversionController::class, 'create'])->name('uomconversion');
Route::get('uomconversionedit/{id}/{type}', [UomconversionController::class, 'create'])->name('uomconversionedit');
Route::post('uomconversionsave', [UomconversionController::class, 'save'])->name('uomconversionsave');
Route::get('uomconversionview/{id}', [UomconversionController::class, 'view'])->name('uomconversionview');
Route::get('getGridUomconData', [UomconversionController::class, 'getGridUomconData'])->name('getGridUomconData');
Route::get('uomconversiondelete/{id}', [UomconversionController::class, 'destroy'])->name('uomconversiondelete');
Route::get('uomconversioncheckname', [UomconversionController::class, 'uomconversioncheckname'])->name('uomconversioncheckname');
Route::get('productprimaryuom/{id}', [UomconversionController::class, 'productprimaryuom']);
