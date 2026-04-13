<?php

use App\Http\Controllers\DepreciationmethodController;
use App\Http\Controllers\RolreportController;
use App\Http\Controllers\PoreportController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\QohreportController;
use App\Http\Controllers\PaymentdetailsController;
use App\Http\Controllers\PendingpurchasepaymentController;
use App\Http\Controllers\ReceiptdetailsController;
use App\Http\Controllers\PendingsalesreceiptController;
use App\Http\Controllers\VendorbalancesrptController;
use App\Http\Controllers\PoagingsummaryrptController;
use App\Http\Controllers\PodetailsrptController;
use App\Http\Controllers\PurchasereportController;
use App\Http\Controllers\JournalentryController;
use App\Http\Controllers\CustomerbalancesController;
use App\Http\Controllers\SalesorderdetailsrptController;
use App\Http\Controllers\SoagingsummaryrptController;
use App\Http\Controllers\ReceivablesagingsummaryController;
use App\Http\Controllers\PayablesagingsummaryController;
use App\Http\Controllers\DcdetailsrptController;
use App\Http\Controllers\TrialbalancesrptController;
use App\Http\Controllers\PobyvendorrptController;
use App\Http\Controllers\BalancesheetrptController;
use App\Http\Controllers\JobcardController;
use App\Http\Controllers\PurchasetranscationController;
use App\Http\Controllers\PurchasetaxaccountrptController;
use App\Http\Controllers\SalestaxaccountrptController;
use App\Http\Controllers\SoorderController;
use App\Http\Controllers\DispatchController;
use App\Http\Controllers\SalesinvoiceController;
use App\Http\Controllers\PurchaseregisterController;
use App\Http\Controllers\ExpensesrptController;
use App\Http\Controllers\RtvreportController;
use App\Http\Controllers\SalesreturnsummaryController;
use App\Http\Controllers\SalesinquiryController;
use App\Http\Controllers\SupplierbasedcostreportController;
use App\Http\Controllers\ProductbasedcostreportController;
use App\Http\Controllers\productbasedquantityrpt;
use App\Http\Controllers\SalesreportController;
use App\Http\Controllers\ProductionreportController;
use App\Http\Controllers\ProductdetailreportController;
use App\Http\Controllers\SalesorderdetailreportController;
use App\Http\Controllers\Sales1reportController;
use App\Http\Controllers\BatchwiseqtyrptController;
use App\Http\Controllers\BatchwisecostrptController;
use App\Http\Controllers\EmployeebalancerptController;
use App\Http\Controllers\SupplierbalancerptController;
use App\Http\Controllers\CustomerbalancerptController;
use App\Http\Controllers\MaterialbomreportController;
use App\Http\Controllers\MachinerptController;
use App\Http\Controllers\SalesregisterController;
use App\Http\Controllers\MaterialreturndetailsrptController;
use App\Http\Controllers\LedgerreportController;
use App\Http\Controllers\StockledgerreportController;
use App\Http\Controllers\ConsumableController;
use App\Http\Controllers\AttendancereportController;
use App\Http\Controllers\BankstatementuploadController;
use App\Http\Controllers\MonthwiseqohrptController;
use App\Http\Controllers\B2breportController;
use App\Http\Controllers\B2creportController;
use App\Http\Controllers\StkstmtgenerateController;
use App\Http\Controllers\StkstmtbankController;
use App\Http\Controllers\ItccredittakenController;
use App\Http\Controllers\Gstr3b2bcomController;
use App\Http\Controllers\TcsapplicablereportController;
use App\Http\Controllers\TdsapplicablereportController;
use App\Http\Controllers\ManufactringdatarptController;
use App\Http\Controllers\NewtrialbalanceController;
use App\Http\Controllers\ProfitandlosscompareController;
use App\Http\Controllers\BasicreportController;
use App\Http\Controllers\MonthlyReportController;
use App\Http\Controllers\StockmovementController;
use App\Http\Controllers\BomEstimateController;
use App\Http\Controllers\ItcreversalController;


// Depreciation & Movement Reports
Route::get('depreciationmethodreport', [DepreciationmethodController::class, 'index1']);
Route::get('getdepreciationreportData', [DepreciationmethodController::class, 'getdepricisionData']);
Route::get('movementanalysis', [RolreportController::class, 'movementindex']);
Route::get('movementanalysisdata', [RolreportController::class, 'movementanalysisdata']);
Route::get('movement', [RolreportController::class, 'movementdataindex']);
Route::get('movementdata', [RolreportController::class, 'movementdata']);

// PO Report
Route::get('poreport', [PoreportController::class, 'index']);
Route::get('dashboard_data', [HomeController::class, 'dashboard_data']);

// Inventory Report (QOH)
Route::get('qohrpt', [QohreportController::class, 'index'])->name('qohrpt');
Route::get('getrmqohreport', [QohreportController::class, 'getrmqohreport'])->name('getrmqohreport');
Route::get('finishedgoodrpt', [QohreportController::class, 'index'])->name('finishedgoodrpt');
Route::get('samplegoodrpt', [QohreportController::class, 'index'])->name('samplegoodrpt');
Route::get('wiprpt', [QohreportController::class, 'index'])->name('wiprpt');
Route::get('controlsamplesrpt', [QohreportController::class, 'index'])->name('controlsamplesrpt');
Route::get('pggoodrpt', [QohreportController::class, 'index'])->name('pggoodrpt');
Route::get('semifinishedgoodrpt', [QohreportController::class, 'index'])->name('semifinishedgoodrpt');
Route::get('wpggoodrpt', [QohreportController::class, 'index'])->name('wpggoodrpt');
Route::get('wpmgoodrpt', [QohreportController::class, 'index'])->name('wpmgoodrpt');
Route::get('consumablerpt', [QohreportController::class, 'index'])->name('consumablerpt');
Route::get('promotionalrpt', [QohreportController::class, 'index'])->name('promotionalrpt');
Route::get('accessoriesrpt', [QohreportController::class, 'index'])->name('accessoriesrpt');
Route::get('packingmaterialstockrpt', [QohreportController::class, 'index'])->name('packingmaterialstockrpt');
Route::get('verdurapackingmaterialstockrpt', [QohreportController::class, 'index'])->name('verdurapackingmaterialstockrpt');
Route::get('verduraqohrpt', [QohreportController::class, 'index'])->name('verduraqohrpt');
Route::get('labqohrpt', [QohreportController::class, 'index'])->name('labqohrpt');
Route::get('labpmqohrpt', [QohreportController::class, 'index'])->name('labpmqohrpt');

Route::get('sfgqcytaqohrpt', [QohreportController::class, 'sfgqcytaqohrptindex'])->name('sfgqcytaqohrpt');
Route::get('getsfgqcytaqohrpt', [QohreportController::class, 'getsfgqcytaqohrpt'])->name('getsfgqcytaqohrpt');

Route::get('allproductstockrpt', [QohreportController::class, 'index'])->name('allproductstockrpt');
Route::get('getproductqohreport', [QohreportController::class, 'getproductqohreport'])->name('getproductqohreport');
Route::get('negativeproductstockrpt', [QohreportController::class, 'index'])->name('negativeproductstockrpt');
Route::get('getproductnegativeqohreport', [QohreportController::class, 'getproductnegativeqohreport'])->name('getproductnegativeqohreport');
Route::get('allproductstockwithaccrpt', [QohreportController::class, 'index'])->name('allproductstockwithaccrpt');
Route::get('getproductqohwithaccreport', [QohreportController::class, 'getproductqohwithaccreport'])->name('getproductqohwithaccreport');

// Payment Details Report
Route::get('paymentdetails', [PaymentdetailsController::class, 'reportindex'])->name('paymentdetails');
Route::get('getpaymentdetailsData', [PaymentdetailsController::class, 'getpaymentdetailsData']);
Route::get('getpaymentdetailsDataforpayment', [PaymentdetailsController::class, 'getpaymentdetailsDataforpayment']);
Route::get('getpaymentdetailsreportData', [PaymentdetailsController::class, 'getpaymentdetailsreportData']);
Route::get('payablereport/{id}', [PaymentdetailsController::class, 'getpayablesreport'])->name('payablereport');

// Pending Purchase Invoice Report
Route::get('pendingpurchasepayment', [PendingpurchasepaymentController::class, 'index']);
Route::get('getpendingpaymentData', [PendingpurchasepaymentController::class, 'getpendingpaymentData'])->name('getpendingpaymentData');
Route::get('getpaymentpendingreportData', [PendingpurchasepaymentController::class, 'getpaymentpendingreportData']);

// Receipt Details Report
Route::get('receiptdetails', [ReceiptdetailsController::class, 'index'])->name('receiptdetails');
Route::get('getReceiptrptData', [ReceiptdetailsController::class, 'getReceiptdetailssData']);
Route::get('receivablesreport/{id}', [ReceiptdetailsController::class, 'getreceivablesreport'])->name('receivablesreport');

// Pending Invoice Details Report
Route::get('pendingsalesreceipt', [PendingsalesreceiptController::class, 'index']);
Route::get('getpendingreceiptData', [PendingsalesreceiptController::class, 'getpendingreceiptData'])->name('getpendingreceiptData');

// Vendor Balances Report
Route::get('vendorbalancesrpt', [VendorbalancesrptController::class, 'index']);
Route::get('getvendorbalanceData', [VendorbalancesrptController::class, 'getvendorbalanceData']);

// PO Aging Summary Report
Route::get('poinvoiceagingsummaryrpt', [PoagingsummaryrptController::class, 'index']);
Route::get('poinvoiceagingsummaryrptdate', [PoagingsummaryrptController::class, 'index']);
Route::get('getpoagingsummaryData', [PoagingsummaryrptController::class, 'getpoagingsummaryData']);

// PO Details Report
Route::get('podetailsrpt', [PodetailsrptController::class, 'index'])->name('podetailsrpt');
Route::get('getpodetailsData', [PodetailsrptController::class, 'getpodetailsData']);
Route::get('PodetailsreportData', [PodetailsrptController::class, 'PodetailsreportData']);

// Purchase Pending Order Qty Report
Route::get('popendingqtyrpt', [PurchasereportController::class, 'pendingindex'])->name('popendingqtyrpt');
Route::get('getpopendingqty', [PurchasereportController::class, 'getpopendingqty'])->name('getpopendingqty');

// Cost Center Ledger Report
Route::get('costcenterledgerreport', [JournalentryController::class, 'costcenterledgerreport']);
Route::get('getcostcenterreportData', [JournalentryController::class, 'getcostcenterreportData']);

// Purchase by Vendor Report
Route::get('pobyvendorrpt', [PobyvendorrptController::class, 'index']);
Route::get('getpobyvendorData', [PobyvendorrptController::class, 'getpobyvendorData']);

// Customer Balances Reports
Route::get('customerbalancesrpt', [CustomerbalancesController::class, 'index']);
Route::get('getcustomerbalanceData', [CustomerbalancesController::class, 'getcustomerbalanceData']);

Route::get('customerwisereport', [CustomerbalancesController::class, 'customerwisereport']);
Route::get('customerwisereportdata', [CustomerbalancesController::class, 'customerwisereportdata']);

Route::get('supplierwisereport', [CustomerbalancesController::class, 'supplierwisereport']);
Route::get('supplierwisereportdata', [CustomerbalancesController::class, 'supplierwisereportdata']);

Route::get('supplierwiseinvexpreport', [CustomerbalancesController::class, 'supplierwiseinvexpreport']);
Route::get('supplierwiseinvexpreportdata',  [CustomerbalancesController::class, 'supplierwiseinvexpreportdata']);


// Sales Order Details Report
Route::get('salesorderdetailsrpt', [SalesorderdetailsrptController::class, 'index']);
Route::get('getsodetailsData', [SalesorderdetailsrptController::class, 'getsodetailsData']);
Route::get('SodetailsreportData', [SalesorderdetailsrptController::class, 'SodetailsreportData']);
Route::get('targetvsorder', [SalesorderdetailsrptController::class, 'targetindex']);
Route::get('gettargetvsso', [SalesorderdetailsrptController::class, 'gettargetso']);

// SO Aging Summary
Route::get('soinvoiceagingsummaryrpt', [SoagingsummaryrptController::class, 'index']);
Route::get('soinvoiceagingsummaryrptdate', [SoagingsummaryrptController::class, 'index']);
Route::get('getsoagingsummaryData', [SoagingsummaryrptController::class, 'getsoagingsummaryData']);

// Receivables Aging Summary
Route::get('receivablesagingsummary', [ReceivablesagingsummaryController::class, 'index']);
Route::post('get-recagingtransactions', [ReceivablesagingsummaryController::class, 'getrecagingtransactions']);

// Payables Aging Summary
Route::get('payablesageingsummary', [PayablesagingsummaryController::class, 'index'])->name('payablesageingsummary');
Route::post('payablesagingsummarypopup', [PayablesagingsummaryController::class, 'getrecagingtransactions']);

// Delivery Challan Report
Route::get('deliverychallandetailsrpt', [DcdetailsrptController::class, 'index']);
Route::get('getdcdetailsData', [DcdetailsrptController::class, 'getdcdetailsData']);

// Trial Balance & Ledger
Route::get('accounttrx', [TrialbalancesrptController::class, 'accounttransaction'])->name('accounttrx');
Route::get('getaccounttransaction', [TrialbalancesrptController::class, 'getaccounttransaction'])->name('getaccounttransaction');
Route::get('getgeneralledger', [TrialbalancesrptController::class, 'getgeneralledger'])->name('getgeneralledger');

Route::get('trialbalancesrpt', [TrialbalancesrptController::class, 'trialbalance']);
Route::get('gettrialbalance', [TrialbalancesrptController::class, 'gettrialbalance'])->name('gettrialbalance');

Route::get('trialbalancesrptnew', [TrialbalancesrptController::class, 'trialbalancenew']);
Route::get('gettrialbalancenew', [TrialbalancesrptController::class, 'gettrialbalancenew'])->name('gettrialbalance');

Route::get('trialbalancesrptjrk', [TrialbalancesrptController::class, 'trialbalancejrk']);
Route::get('gettrialbalancejrk', [TrialbalancesrptController::class, 'gettrialbalancejrk'])->name('gettrialbalancejrk');
Route::get('getTrialBalanceDetailsjrk/{account_description}', [TrialbalancesrptController::class, 'getTrialBalanceDetailsjrk'])->name('gettrialbalanceDetailsjrk');
Route::get('getledgerbalancejrk', [TrialbalancesrptController::class, 'getledgerbalancejrk'])->name('getledgerbalancejrk');

// Balance Sheet
Route::get('balancesheetreport', [TrialbalancesrptController::class, 'balancesheet']);
Route::get('getbalancesheetdata1', [TrialbalancesrptController::class, 'getbalancesheetdata1']);
Route::get('balancesheetreportnew', [TrialbalancesrptController::class, 'balancesheetnew']);
Route::get('getbalancesheetdata1new', [TrialbalancesrptController::class, 'getbalancesheetdata1new']);

// Profit & Loss Reports
Route::get('profitandlossrpt', [TrialbalancesrptController::class, 'profitandloss']);
Route::get('getprofitandloss', [TrialbalancesrptController::class, 'getprofitandloss']);

Route::get('profitandlossstdtrpt', [TrialbalancesrptController::class, 'profitandlossstdtindex']);
Route::get('getprofitandlossstd', [TrialbalancesrptController::class, 'getprofitandlossstd']);

Route::get('profitandlossbalstdtrpt', [TrialbalancesrptController::class, 'profitandlossbalstdtindex']);
Route::get('getprofitandlossbalstd', [TrialbalancesrptController::class, 'getprofitandlossbalstd']);

// Bank Transactions & Ledger
Route::get('accountbanktrx', [TrialbalancesrptController::class, 'accountbanktransaction'])->name('accountbanktrx');
Route::get('getaccountbanktransaction', [TrialbalancesrptController::class, 'getaccountbanktransaction'])->name('getaccountbanktransaction');

Route::get('generalledger', [TrialbalancesrptController::class, 'generalledger'])->name('generalledger');

// Journal Report
Route::get('journalreport', [JournalentryController::class, 'journalreport']);

// SO By Customer
Route::get('sobycustomerrpt', [PobyvendorrptController::class, 'index1'])->name('sobyvendorrpt');

// Ledger & Journal Reports
Route::get('ledgerreport', [JournalentryController::class, 'ledgerreport']);
Route::get('journalledgerreport', [JournalentryController::class, 'getledgerreport']);
Route::get('journalrpt', [JournalentryController::class, 'journalrpt']);
Route::get('journalreport', [JournalentryController::class, 'journalreport']);
Route::get('journalreport/export', [JournalentryController::class, 'journalreportExport']);



// Balance Sheet & Job Card
Route::get('balancesheetrpt', [BalancesheetrptController::class, 'balancesheet'])->name('balancesheetrpt');
Route::get('jobcardresult/{id}', [BalancesheetrptController::class, 'jobcardresult']);
Route::get('jobcardreport', [JobcardController::class, 'report'])->name('jobcardreport');
//Route::get('productcostestimate', [JobcardController::class, 'productcostest'])->name('productcostestimate');

//bom cost estimate
Route::match(['get','post'],'bom-estimate', [BomEstimateController::class,'index']);
//Route::get('bom-estimate', [BomEstimateController::class,'index']);
//Route::post('bom-estimate/get', [BomEstimateController::class,'getEstimate']);

// Purchase Transactions
Route::get('purchasetransaction', [PurchasetranscationController::class, 'index']);
Route::get('pruchasetransreport', [PurchasetranscationController::class, 'report']);

// Purchase & Sales Tax Reports
Route::get('purchasetaxaccountrpt', [PurchasetaxaccountrptController::class, 'index'])->name('purchasetaxaccountrpt');
Route::get('taxaccountrpt', [PurchasetaxaccountrptController::class, 'potaxaccount']);
Route::get('salestaxaccountrpt', [SalestaxaccountrptController::class, 'index'])->name('salestaxaccountrpt');
Route::get('salestaxaccount', [SalestaxaccountrptController::class, 'salestaxaccount']);

// Sales Reports
Route::get('salesreport', [SoorderController::class, 'salesreport'])->name('salesreport');
Route::get('salesreportsearch/{id}', [SoorderController::class, 'salesreportsearch'])->name('salesreportsearch');

// Purchase Reports
Route::get('purchasereport/{id}', [PurchasereportController::class, 'index'])->name('purchasereport');
Route::get('dispatch_view/{id}', [DispatchController::class, 'show'])->name('dispatch_view');
Route::get('soinvoice_view/{id}', [SalesinvoiceController::class, 'show'])->name('soinvoice_view');
Route::get('so_view/{id}', [SoorderController::class, 'show'])->name('so_view');

// Sales Order Template
Route::get('salesordertemp', [SupplierbasedcostreportController::class, 'salesorderindex'])->name('salesordertemp');
Route::get('getsalesorder', [SupplierbasedcostreportController::class, 'getsalesorder'])->name('getsalesorder');

// Samples Order Template
Route::get('samplesordertemp', [SupplierbasedcostreportController::class, 'samplesorderindex'])->name('samplesordertemp');
Route::get('getsamplesorder', [SupplierbasedcostreportController::class, 'getsamplesorder'])->name('getsamplesorder');

// Distributor Target Report
Route::get('distributortargetrpt', [SupplierbasedcostreportController::class, 'distributortargetindex'])->name('distributortargetrpt');
Route::get('getdistributortarget', [SupplierbasedcostreportController::class, 'getdistributortarget'])->name('getdistributortarget');

// e-Invoice Reports
Route::get('einvoicerptforhdr', [SupplierbasedcostreportController::class, 'einvoicerptforhdrindex'])->name('einvoicerptforhdr');
Route::get('geteinvoicerptforhdr', [SupplierbasedcostreportController::class, 'geteinvoicerptforhdr'])->name('geteinvoicerptforhdr');

Route::get('einvoicerptforcrd', [SupplierbasedcostreportController::class, 'einvoicerptforcrdindex'])->name('einvoicerptforcrd');
Route::get('geteinvoicerptforcrd', [SupplierbasedcostreportController::class, 'geteinvoicerptforcrd'])->name('geteinvoicerptforcrd');

Route::get('einvoicerptforline', [SupplierbasedcostreportController::class, 'einvoicerptforlineindex'])->name('einvoicerptforline');
Route::get('geteinvoicerptforline', [SupplierbasedcostreportController::class, 'geteinvoicerptforline'])->name('geteinvoicerptforline');

// GST HSN Wise Report
Route::get('gsthsnrpt', [SupplierbasedcostreportController::class, 'gsthsnrptindex'])->name('gsthsnrpt');
Route::get('getgsthsnrpt', [SupplierbasedcostreportController::class, 'getgsthsnrpt'])->name('getgsthsnrpt');

// Price List Report
Route::get('pricelistdetailsrpt', [SupplierbasedcostreportController::class, 'pricelistdetailsindex'])->name('pricelistdetailsrpt');
Route::get('getpricelistdetails', [SupplierbasedcostreportController::class, 'getpricelistdetails'])->name('getpricelistdetails');

// Sales Return Details Report
Route::get('salesreturndetailsrpt', [SupplierbasedcostreportController::class, 'salesreturndetailsindex'])->name('salesreturndetailsrpt');
Route::get('getsalesreturndetails', [SupplierbasedcostreportController::class, 'getsalesreturndetails'])->name('getsalesreturndetails');

// Sales Return Summary Dashboard
Route::get('salesreturnsummaryreport', [SalesreturnsummaryController::class, 'salesreturnsummaryindex'])->name('salesreturnsummaryreport');

// Sales Inquiry Details Report
Route::get('salesinquirydetailsrpt', [SalesinquiryController::class, 'salesinquirydetailsindex'])->name('salesinquirydetailsrpt');
Route::get('getsalesinquirydetails', [SalesinquiryController::class, 'getsalesinquirydetails'])->name('getsalesinquirydetails');

// Move GRN Status Report
Route::get('movegrnstatusrpt', [SupplierbasedcostreportController::class, 'movegrnstatusindex'])->name('movegrnstatusrpt');
Route::get('getmovegrnstatus', [SupplierbasedcostreportController::class, 'getmovegrnstatus'])->name('getmovegrnstatus');

// Job Activity Details Report
Route::get('jobactivitydetailsrpt', [SupplierbasedcostreportController::class, 'jobactivitydetailsindex'])->name('jobactivitydetailsrpt');
Route::get('getjobactivitydetails', [SupplierbasedcostreportController::class, 'getjobactivitydetails'])->name('getjobactivitydetails');

// Operation Employee Activity Report
Route::get('operationemployeeactivityrpt', [SupplierbasedcostreportController::class, 'operationemployeeactivityindex'])->name('operationemployeeactivityrpt');
Route::get('getoperationemployeeactivity', [SupplierbasedcostreportController::class, 'getoperationemployeeactivity'])->name('getoperationemployeeactivity');

Route::get('smartuploaddetailsrpt', [SupplierbasedcostreportController::class, 'smartuploaddetailsindex'])->name('smartuploaddetailsrpt');
Route::get('getsmartuploaddetails', [SupplierbasedcostreportController::class, 'getsmartuploaddetails'])->name('getsmartuploaddetails');
Route::get('transporttrackrpt', [SupplierbasedcostreportController::class, 'transporttrackindex'])->name('transporttrackrpt');
Route::get('gettransporttrack', [SupplierbasedcostreportController::class, 'gettransporttrack'])->name('gettransporttrack');
Route::get('paymentdetailsrpt', [SupplierbasedcostreportController::class, 'paymentdetailsindex'])->name('paymentdetailsrpt');
Route::get('getpaymentdetails', [SupplierbasedcostreportController::class, 'getpaymentdetails'])->name('getpaymentdetails');

Route::get('productionstoremoverpt', [SupplierbasedcostreportController::class, 'productstoremoveindex'])->name('productionstoremoverpt');
Route::get('getproductstoremove', [SupplierbasedcostreportController::class, 'getproductstoremove'])->name('getproductstoremove');

Route::get('productbasedcostrpt', [ProductbasedcostreportController::class, 'index'])->name('productbasedcostrpt');
Route::get('getproductbasedcost', [ProductbasedcostreportController::class, 'getproductbasedcost'])->name('getproductbasedcost');

Route::get('productbasedqtyrpt', [productbasedquantityrpt::class, 'index'])->name('productbasedqtyrpt');
Route::get('getproductbasedqty', [productbasedquantityrpt::class, 'getproductbasedqty'])->name('getproductbasedqty');

Route::get('sopendingqtyrpt', [SalesreportController::class, 'pendingsoindex'])->name('sopendingqtyrpt');
Route::get('getsopendingqty', [SalesreportController::class, 'getsopendingqty'])->name('getsopendingqty');

Route::get('salesorderrpt/{id}', [SalesreportController::class, 'Salesorderdetailsreport'])->name('salesorderrpt');
Route::get('purchaseapprovedreport', [PurchasereportController::class, 'purchaseapprovedreport'])->name('purchaseapprovedreport');
Route::get('soorderapprovegriddatareport', [PurchasereportController::class, 'soorderapprovegriddata'])->name('soorderapprovegriddata');

Route::get('productionreport', [ProductionreportController::class, 'pendingsoindex'])->name('productionreport');
Route::get('productionreport1', [ProductionreportController::class, 'pendingsoindex1'])->name('productionreport1');
Route::get('mcwisereport', [ProductionreportController::class, 'mcwiseindex'])->name('mcwisereport');
Route::get('employeerpt', [ProductionreportController::class, 'employeerptindex'])->name('employeerpt');
Route::get('workreport', [ProductionreportController::class, 'employeerptindex'])->name('workreport');
Route::get('mcdtls', [ProductionreportController::class, 'mcdtlsindex'])->name('mcdtls');

Route::get('productdetailrpt', [ProductdetailreportController::class, 'index'])->name('productdetailrpt');
Route::get('salesorderdetailrpt', [SalesorderdetailreportController::class, 'index'])->name('salesorderdetailrpt');
Route::get('sales1rpt', [Sales1reportController::class, 'index'])->name('sales1rpt');

Route::get('batchwiseqtyrpt', [BatchwiseqtyrptController::class, 'index'])->name('batchwiseqtyrpt');
Route::get('getbatchwiseqty', [BatchwiseqtyrptController::class, 'getbatchwiseqty'])->name('getbatchwiseqty');

 Route::get('stockmovementrpt',[StockmovementController::class, 'index1'])->name('stockmovement');
 Route::get('getstockmovement',[StockmovementController::class, 'getstockmovement'])->name('getstockmovement');
 Route::get('getstockbreakup', [StockmovementController::class, 'getstockbreakup'])->name('getstockbreakup');

Route::get('batchwisecostrpt', [BatchwisecostrptController::class, 'index'])->name('batchwisecostrpt');
Route::get('getbatchwisecost', [BatchwisecostrptController::class, 'getbatchwisecost'])->name('getbatchwisecost');

Route::get('rolreport', [RolreportController::class, 'index'])->name('rolreport');
Route::get('getrolreport', [RolreportController::class, 'getrolreport'])->name('getrolreport');

Route::get('rolfgreport', [RolreportController::class, 'fgindex'])->name('rolfgreport');
Route::get('getrolfgreport', [RolreportController::class, 'getrolfgreport'])->name('getrolfgreport');

Route::get('rolrmreport', [RolreportController::class, 'rolrmindex'])->name('rolrmreport');
Route::get('getrolrmreport', [RolreportController::class, 'getrolrmreport'])->name('getrolrmreport');

Route::get('rolvrmreport', [RolreportController::class, 'rolvrmindex'])->name('rolvrmreport');
Route::get('getrolvrmreport', [RolreportController::class, 'getrolvrmreport'])->name('getrolvrmreport');

Route::get('rolpmreport', [RolreportController::class, 'rolpmindex'])->name('rolpmreport');
Route::get('getrolpmreport', [RolreportController::class, 'getrolpmreport'])->name('getrolpmreport');

Route::get('rolsfgreport', [RolreportController::class, 'sfgindex'])->name('rolsfgreport');
Route::get('getrolsfgreport', [RolreportController::class, 'getrolsfgreport'])->name('getrolsfgreport');

Route::get('employeebalancerpt', [EmployeebalancerptController::class, 'index'])->name('employeebalancerpt');
Route::get('getemployeebalance', [EmployeebalancerptController::class, 'employeebalance'])->name('getemployeebalance');

Route::get('customerbalancerptall', [EmployeebalancerptController::class, 'cusindex'])->name('customerbalancerptall');
Route::get('getcustomerbalanceall', [EmployeebalancerptController::class, 'custttomerbalanceall'])->name('getcustomerbalanceall');

Route::get('supplierbalancerptall', [EmployeebalancerptController::class, 'supindex'])->name('supplierbalancerptall');
Route::get('getsupplierbalanceall', [EmployeebalancerptController::class, 'getsupplierbalanceall'])->name('getsupplierbalanceall');

Route::get('employeebalancerptall', [EmployeebalancerptController::class, 'index'])->name('employeebalancerptall');
Route::get('getemployeebalanceall', [EmployeebalancerptController::class, 'employeebalanceall'])->name('getemployeebalanceall');

Route::get('supplierbalancerpt', [SupplierbalancerptController::class, 'index'])->name('supplierbalancerpt');
Route::get('getsupplierbalance', [SupplierbalancerptController::class, 'supplierbalance'])->name('getsupplierbalance');
Route::get('getsuppliercurrentbalance', [SupplierbalancerptController::class, 'getCurrentSupplierBalance']);

Route::get('customerbalancerpt', [CustomerbalancerptController::class, 'index'])->name('customerbalancerpt');
Route::get('getcustomerbalance', [CustomerbalancerptController::class, 'customerbalance'])->name('getcustomerbalance');

Route::get('expensesrpt', [ExpensesrptController::class, 'index'])->name('expensesrpt');
Route::get('getExpenseData', [ExpensesrptController::class, 'getexpenserptdata'])->name('getExpenseData');

Route::get('multipleempexpensesrpt', [ExpensesrptController::class, 'empexpenseindex'])->name('multipleempexpensesrpt');
Route::get('getmultipleEmpExpenseData', [ExpensesrptController::class, 'getmultipleempexpenserptdata'])->name('getmultipleEmpExpenseData');

Route::get('creditdebitnoterpt', [ExpensesrptController::class, 'creditdebitnoteindex'])->name('creditdebitnoterpt');
Route::get('getcreditdebitnoteData', [ExpensesrptController::class, 'getcreditdebitnoterptdata'])->name('getcreditdebitnoteData');

Route::get('materialbomreport', [MaterialbomreportController::class, 'index'])->name('materialbomreport');
Route::get('getmaterialbomreport', [MaterialbomreportController::class, 'getmaterialbomreport'])->name('getmaterialbomreport');

Route::get('materialbomhistoryreport', [MachinerptController::class, 'bomhistoryindex'])->name('materialbomhistoryreport');
Route::get('getmaterialbomhistoryreport', [MachinerptController::class, 'getmaterialbomhistoryreport'])->name('getmaterialbomhistoryreport');

Route::get('salesregister', [SalesregisterController::class, 'index'])->name('salesregister');
Route::get('getsalesregister', [SalesregisterController::class, 'getsalesregister'])->name('getsalesregister');

Route::get('mrdrpt', [MaterialreturndetailsrptController::class, 'index'])->name('mrdrpt');
Route::get('getmrdrpt', [MaterialreturndetailsrptController::class, 'getmrdrpt'])->name('getmrdrpt');

Route::get('ledgerrpt', [LedgerreportController::class, 'index'])->name('ledgerrpt');
Route::get('getledgerbalance', [LedgerreportController::class, 'ledgerbalance'])->name('getledgerbalance');
Route::get('getledgerpandlData', [TrialbalancesrptController::class, 'getledgerpandlData']);

Route::get('pmstockledgerreport', [StockledgerreportController::class, 'pmstockledgerreportindex']);
Route::get('getpmstockledgerrpt', [StockledgerreportController::class, 'getpmstockledgerrpt']);

Route::get('optpmstockledgerreport', [StockledgerreportController::class, 'optpmstockledgerreportindex']);
Route::get('getoptpmstockledgerrpt', [StockledgerreportController::class, 'getoptpmstockledgerrpt']);

Route::get('wipstockledgerreport', [StockledgerreportController::class, 'wipstockledgerreportindex']);
Route::get('getwipstockledgerrpt', [StockledgerreportController::class, 'getwipstockledgerrpt']);

Route::get('semifinishedgoodsrpt', [StockledgerreportController::class, 'rawfinishedgoodsrptindex']);
Route::get('getsemifinishedgoodsrpt', [StockledgerreportController::class, 'getsemifinishedgoodsrpt']);

Route::get('extractsemifgrpt', [StockledgerreportController::class, 'extractrawfinishedgoodsrptindex']);
Route::get('getextractsemifinishedgoodsrpt', [StockledgerreportController::class, 'getextractsemifinishedgoodsrpt']);

Route::get('rawmaterialrpt', [StockledgerreportController::class, 'rawmaterialrptindex']);
Route::get('getproductionstockledgerrpt', [StockledgerreportController::class, 'getrawmaterialstockledgerrpt']);

Route::get('productionrawmaterialrpt', [StockledgerreportController::class, 'productionrawmaterialrptindex']);
Route::get('getproductionrawmaterialstockledgerrpt', [StockledgerreportController::class, 'getproductionrawmaterialstockledgerrpt']);

Route::get('fgstockledgerreport', [StockledgerreportController::class, 'index2']);
Route::get('getfgstockledgerrpt', [StockledgerreportController::class, 'getfgstockledgerrpt']);

Route::get('promotionalstockledgerreport', [StockledgerreportController::class, 'index4']);
Route::get('getpromotionalstockledgerrpt', [StockledgerreportController::class, 'getpromotionalstockledgerrpt']);

Route::get('rmstockledgerreport', [StockledgerreportController::class, 'index3']);
Route::get('getrmstockledgerrpt', [StockledgerreportController::class, 'getrmstockledgerrpt']);

Route::get('machinewiserpt', [MachinerptController::class, 'machineindex'])->name('machinewiserpt');
Route::get('machinewiserptsearch', [MachinerptController::class, 'machinewiserptsearch'])->name('machinewiserptsearch');

Route::get('posupplierrpt', [MachinerptController::class, 'posupplierindex'])->name('posupplierrpt');
Route::get('posupplierrptsearch', [MachinerptController::class, 'posupplierrptsearch'])->name('posupplierrptsearch');

Route::get('emp', [EmployeebalancerptController::class, 'emppindex'])->name('employeebalancerpt');
Route::get('getemp', [EmployeebalancerptController::class, 'getemp'])->name('getemp');

Route::get('soqtysuppliedrpt', [SalesregisterController::class, 'index'])->name('soqtysuppliedrpt');
Route::get('getsalesqtysupplieddata', [SalesregisterController::class, 'getsalesqtysupplieddata'])->name('getsalesqtysupplieddata');

Route::get('lossrpt', [QohreportController::class, 'index'])->name('lossrpt');
Route::get('getproductionlossdata', [QohreportController::class, 'getproductionlossdata'])->name('getproductionlossdata');

Route::get('batchrpt', [QohreportController::class, 'index'])->name('batchrpt');
Route::get('getbatchdetailsdata', [QohreportController::class, 'getbatchdetailsdata'])->name('getbatchdetailsdata');

Route::get('podatasrpt', [PodetailsrptController::class, 'index1'])->name('podatasrpt');
Route::get('getpodatasrpt', [PodetailsrptController::class, 'getpodatasrpt'])->name('getpodatasrpt');

Route::get('pinvdatasrpt', [PodetailsrptController::class, 'index1'])->name('pinvdatasrpt');
Route::get('getpinvdatasrpt', [PodetailsrptController::class, 'getpinvdatasrpt'])->name('getpinvdatasrpt');

Route::get('pinvdataswithbnorpt', [PodetailsrptController::class, 'index1'])->name('pinvdataswithbnorpt');
Route::get('getpinvdataswithbnorpt', [PodetailsrptController::class, 'getpinvdataswithbnorpt'])->name('getpinvdataswithbnorpt');

Route::get('pinvdataswithgrndaterpt', [PodetailsrptController::class, 'index1'])->name('pinvdataswithgrndaterpt');
Route::get('getpinvdataswithgrndaterpt', [PodetailsrptController::class, 'getpinvdataswithgrndaterpt'])->name('getpinvdataswithgrndaterpt');

Route::get('sodatasrpt', [SalesorderdetailsrptController::class, 'index1'])->name('sodatasrpt');
Route::get('getsodatasrpt', [SalesorderdetailsrptController::class, 'getsodatasrpt'])->name('getsodatasrpt');

Route::get('soinvoicedatarpt', [SalesorderdetailsrptController::class, 'index1'])->name('soinvoicedatarpt');
Route::get('getsoinvoicedatarpt', [SalesorderdetailsrptController::class, 'getsoinvoicedatarpt'])->name('getsoinvoicedatarpt');

Route::get('invoice-price-validation', [SalesorderdetailsrptController::class, 'index2'])->name('invoice-price-validation');


Route::get('wipdatasrpt', [MaterialbomreportController::class, 'index'])->name('wipdatasrpt');
Route::get('getwipdatasrpt', [MaterialbomreportController::class, 'getwipdatasrpt'])->name('getwipdatasrpt');

Route::get('packingdatasrpt', [MaterialbomreportController::class, 'index'])->name('packingdatasrpt');
Route::get('getpackingdatasrpt', [MaterialbomreportController::class, 'getpackingdatasrpt'])->name('getpackingdatasrpt');

Route::get('packingcostrpt', [MaterialbomreportController::class, 'index'])->name('packingcostrpt');
Route::get('getpackingcostrpt', [MaterialbomreportController::class, 'getpackingcostrpt'])->name('getpackingcostrpt');

Route::get('workorderreport', [MaterialbomreportController::class, 'index'])->name('workorderreport');
Route::get('workorderreportdata', [MaterialbomreportController::class, 'workorderreportdata'])->name('workorderreportdata');

Route::get('subinventorytransferreport', [MaterialbomreportController::class, 'index'])->name('subinventorytransferreport');
Route::get('subinventorytransferreportdata', [MaterialbomreportController::class, 'subinventorytransferreportdata'])->name('subinventorytransferreportdata');

Route::get('specificationreport', [ProductdetailreportController::class, 'productspecindex'])->name('specificationreport');
Route::get('getproductspecdata', [ProductdetailreportController::class, 'getproductspecdata'])->name('getproductspecdata');

Route::get('operationreport', [MaterialbomreportController::class, 'index'])->name('operationreport');
Route::get('operationreportdata', [MaterialbomreportController::class, 'operationreportdata'])->name('operationreportdata');

Route::get('operationreportnew', [MaterialbomreportController::class, 'index'])->name('operationreportnew');
Route::get('operationreportdetails', [MaterialbomreportController::class, 'operationreportdetails'])->name('operationreportdetails');

Route::get('productionreportnew', [MaterialbomreportController::class, 'index'])->name('productionreportnew');
Route::get('productionreportdetails', [MaterialbomreportController::class, 'productionreportdetails'])->name('productionreportdetails');

Route::get('mnthrpt', [MaterialbomreportController::class, 'index'])->name('mnthrpt');
Route::get('monthrptdetails', [MaterialbomreportController::class, 'monthrptdetails'])->name('monthrptdetails');

Route::get('mnthrptsummary', [MonthlyReportController::class, 'index'])->name('mnthrptsummary');
Route::get('/outward/by-class-ajax', [MonthlyReportController::class, 'outwardByClassAjax'])->name('outward.byClassAjax');

Route::get('consolempactreportnew', [MaterialbomreportController::class, 'index'])->name('consolempactreportnew');
Route::get('consolempactreportdetails', [MaterialbomreportController::class, 'consolempactreportdetails'])->name('consolempactreportdetails');

Route::get('jobcostreport', [ProductdetailreportController::class, 'jobcostreportindex'])->name('jobcostreport');
Route::get('getjobcostreportget', [ProductdetailreportController::class, 'getjobcostreportget'])->name('getjobcostreportget');

Route::get('bankstmtuploadrpt', [ProductdetailreportController::class, 'bankstmtuploadrptindex'])->name('bankstmtuploadrpt');
Route::get('getbankstmtuploadrpt', [ProductdetailreportController::class, 'getbankstmtuploadrpt'])->name('getbankstmtuploadrpt');

Route::get('productrmrpt', [CustomerbalancesController::class, 'productrmrpt']);
Route::get('getproductrmrpt1Data', [CustomerbalancesController::class, 'getproductrmrpt1Data']);

Route::get('brspaymentrpt', [BankstatementuploadController::class, 'brspaymentrpt'])->name('brspaymentrpt');
Route::get('getbrssummaryreport', [BankstatementuploadController::class, 'getbrssummaryreport'])->name('getbrssummaryreport');
Route::get('getbrsmappedreport', [BankstatementuploadController::class, 'getbrsmappedreport'])->name('getbrsmappedreport');
Route::get('getbrsunmapped', [BankstatementuploadController::class, 'getbrsunmapped'])->name('getbrsunmapped');
Route::get('getbrssummarydeatilsrpt', [BankstatementuploadController::class, 'getbrssummarydeatilsrpt'])->name('getbrssummarydeatilsrpt');

Route::get('jcconsolidatereport', [ProductionreportController::class, 'jcconsolidateindex'])->name('jcconsolidatereport');
Route::get('getjcconsolidatedata', [ProductionreportController::class, 'getjcconsolidatedata'])->name('getjcconsolidatedata');

Route::get('jobcardstatusdetailedrpt', [ProductionreportController::class, 'jobcardstatusdetailedrpt'])->name('jobcardstatusdetailedrpt');
Route::get('getjobcardstatusdetaileddata', [ProductionreportController::class, 'getjobcardstatusdetaileddata'])->name('getjobcardstatusdetaileddata');

Route::get('jobcardresultnew/{id}', [JobcardController::class, 'jobcardresultnew']);
Route::get('jobcardnewreport', [JobcardController::class, 'reportnew'])->name('jobcardnewreport');

Route::get('productioncostrpt', [BatchwisecostrptController::class, 'wipcostindex'])->name('productioncostrpt');
Route::get('jobbasedwipcostdetails/{id}', [BatchwisecostrptController::class, 'jobbasedwipcostdetails'])->name('jobbasedwipcostdetails');

Route::get('productionbeforecostrpt', [BatchwisecostrptController::class, 'wipcostindex'])->name('productionbeforecostrpt');
Route::get('prdbasedwipcostdetails/{id}', [BatchwisecostrptController::class, 'prdbasedwipcostdetails'])->name('prdbasedwipcostdetails');

Route::get('productioncostrptdetails', [BatchwisecostrptController::class, 'costrptindex'])->name('productioncostrptdetails');
Route::get('getcostrptData', [BatchwisecostrptController::class, 'getcostrptData'])->name('getcostrptData');
Route::get('getcostrptData/export', [BatchwisecostrptController::class, 'Export']);

Route::get('itccostrptdetails', [ItcreversalController::class, 'costrptindex'])->name('itccostrptdetails');
Route::get('getitccostData', [ItcreversalController::class, 'getitccostData'])->name('getitccostData');
Route::get('itcsummary', [ItcreversalController::class, 'costrptindex1'])->name('itcsummary');
Route::get('getitcsummary', [ItcreversalController::class, 'getitcsummary'])->name('getitcsummary');


Route::get('monthwiseqohrpt', [MonthwiseqohrptController::class, 'monthwiseqohrpt'])->name('monthwiseqohrpt');

Route::get('accountsbtwobreport', [B2breportController::class, 'index'])->name('accountsbtwobreport');
Route::get('getbtwobreport', [B2breportController::class, 'getb2breport'])->name('getbtwobreport');
Route::get('accountsbtwocreport', [B2creportController::class, 'index'])->name('accountsbtwocreport');
Route::get('getbtwocreport', [B2creportController::class, 'getb2creport'])->name('getbtwocreport');

Route::get('gstpurchasereport', [B2breportController::class, 'index1'])->name('gstpurchasereport');
Route::get('getgstpurchasereport', [B2breportController::class, 'getgstpurcreport'])->name('getgstpurchasereport');
Route::get('getexpenseregreport', [B2breportController::class, 'getExpenserpt'])->name('getexpenseregreport');

Route::get('btwoereport', [B2breportController::class, 'index2'])->name('btwoereport');
Route::get('getbtwoereport', [B2breportController::class, 'getBtwoerpt'])->name('getbtwoereport');

Route::get('stockstatementgenerate', [StkstmtgenerateController::class, 'Create'])->name('stockstatementgenerate');
Route::post('stockstatementgeneratesave', [StkstmtgenerateController::class, 'save'])->name('stockstatementgeneratesave');
Route::post('stockstatementgeneratefinalsave', [StkstmtgenerateController::class, 'finalsave'])->name('stockstatementgeneratefinalsave');

Route::get('stockstatementforbank', [StkstmtbankController::class, 'index'])->name('stockstatementforbank');

Route::get('itccredittakensummaryrpt', [ItccredittakenController::class, 'index'])->name('itccredittakensummaryrpt');
Route::get('getitcreport', [ItccredittakenController::class, 'getitcreport'])->name('getitcreport');

Route::get('gstr3brpt', [Gstr3b2bcomController::class, 'index'])->name('gstr3brpt');
Route::get('getgst3report', [Gstr3b2bcomController::class, 'getgst3report'])->name('getgst3report');
Route::get('getpurchasereg', [Gstr3b2bcomController::class, 'getpurchasereg'])->name('getpurchasereg');

Route::get('tcsapplyreport', [TcsapplicablereportController::class, 'index'])->name('tcsapplyreport');
Route::get('tdsapplyreport', [TdsapplicablereportController::class, 'index'])->name('tdsapplyreport');

Route::get('manufacturingstockdatarpt', [ManufactringdatarptController::class, 'index'])->name('manufacturingstockdatarpt');

Route::get('trialbalancenew', [NewtrialbalanceController::class, 'index'])->name('trialbalancenew');
Route::get('trialbalpopup', [NewtrialbalanceController::class, 'trialbalpopup'])->name('trialbalpopup');

Route::get('profitandlosscomparerpt', [ProfitandlosscompareController::class, 'profitandlosscompare']);
Route::get('getprofitandlosscompare', [ProfitandlosscompareController::class, 'getprofitandlosscompare']);

Route::get('balancesheetcomparereport', [ProfitandlosscompareController::class, 'balancesheetcompare']);
Route::get('getbalancesheetcomparereport', [ProfitandlosscompareController::class, 'getbalancesheetcomparedata']);

Route::get('purchaseregister', [PurchaseregisterController::class, 'index'])->name('purchaseregister');
Route::get('getpurchaseregister', [PurchaseregisterController::class, 'getpurchaseregister'])->name('getpurchaseregister');

Route::get('productwisepo', [PurchasereportController::class, 'productwisepoindex'])->name('productwisepo');
Route::get('getproductwisepo', [PurchasereportController::class, 'getproductwisepo'])->name('getproductwisepo');
Route::get('portvrpt', [RtvreportController::class, 'index'])->name('portvrpt');
Route::get('getrtv', [RtvreportController::class, 'getrtv'])->name('getrtv');

Route::get('Supplierbasedcostrpt', [SupplierbasedcostreportController::class, 'index'])->name('Supplierbasedcostrpt');
Route::get('getsupplierbasedcost', [SupplierbasedcostreportController::class, 'getsupplierbasedcost'])->name('getsupplierbasedcost');

Route::get('supplierdetailsrpt', [SupplierbasedcostreportController::class, 'supplierdetailsindex'])->name('supplierdetailsrpt');
Route::get('getsupplierdetails', [SupplierbasedcostreportController::class, 'getsupplierdetails'])->name('getsupplierdetails');
Route::get('purchaseregistertds', [PurchaseregisterController::class, 'purchaseregistertdsindex'])->name('purchaseregistertds');
Route::get('getpurchaseregistertds', [PurchaseregisterController::class, 'getpurchaseregistertds'])->name('getpurchaseregistertds');
Route::get('purchaseinvoverduerpt', [SupplierbasedcostreportController::class, 'purchaseinvoverduesindex'])->name('purchaseinvoverduesrpt');
Route::get('getpurchaseinvoverdues', [SupplierbasedcostreportController::class, 'getpurchaseinvoverdue'])->name('getpurchaseinvoverdues');

Route::get('targetvsinvoice', [SalesorderdetailsrptController::class, 'targetinvoice'])->name('targetvsinvoice');
Route::get('getpurchaseinvoverdue', [SalesorderdetailsrptController::class, 'gettargetinvoice'])->name('getpurchaseinvoverdue');

Route::get('machinereport', [MachinerptController::class, 'index'])->name('machinereport');
Route::get('machinedetails', [MachinerptController::class, 'machinedetails'])->name('machinedetails');

Route::get('machinecapacity', [MachinerptController::class, 'index1'])->name('machinecapacity');
Route::get('machinecapacitydetails', [MachinerptController::class, 'machinecapacitydetails'])->name('machinecapacitydetails');
Route::get('customerdetailsrpt', [SupplierbasedcostreportController::class, 'customerdetailsindex'])->name('customerdetailsrpt');
Route::get('getcustomerdetails', [SupplierbasedcostreportController::class, 'getcustomerdetails'])->name('getcustomerdetails');
Route::get('salesvssamplereport', [SalesreportController::class, 'salesvssamplereport'])->name('salesvssamplereport');
Route::get('salesvssamplereportdata', [SalesreportController::class, 'salesvssamplereportdata'])->name('salesvssamplereportdata');
Route::get('dispatchdetailsrpt', [SupplierbasedcostreportController::class, 'dispatchdetailsindex'])->name('dispatchdetailsrpt');
Route::get('getdispatchdetails', [SupplierbasedcostreportController::class, 'getdispatchdetails'])->name('getdispatchdetails');

// Summary 1
Route::get('targetvsordersummaryone', [BasicreportController::class, 'sumoneindex'])->name('targetvsordersummaryone');
Route::get('gettargetvssummaryone', [BasicreportController::class, 'gettargetvssummaryone'])->name('gettargetvssummaryone');

// Summary 2
Route::get('targetvsordersummarytwo', [BasicreportController::class, 'summarytwoindex'])->name('targetvsordersummarytwo');
Route::get('gettargetvssummarytwo', [BasicreportController::class, 'gettargetvssummarytwo'])->name('gettargetvssummarytwo');
Route::get('getsummarycolumn', [BasicreportController::class, 'getsummarycolumn'])->name('getsummarycolumn');

// Summary 3
Route::get('targetvsordersummarythree', [BasicreportController::class, 'summarythreeindex'])->name('targetvsordersummarythree');
Route::get('gettargetvssummarythree', [BasicreportController::class, 'gettargetvssummarythree'])->name('gettargetvssummarythree');

// Summary 4
Route::get('targetvsordersummaryfour', [BasicreportController::class, 'summaryfourindex'])->name('targetvsordersummaryfour');
Route::get('gettargetvssummaryfour', [BasicreportController::class, 'gettargetvssummaryfour'])->name('gettargetvssummaryfour');

// Invoice Summary 1
Route::get('targetvsinvoicesummaryone', [BasicreportController::class, 'invsumoneindex'])->name('targetvsinvoicesummaryone');
Route::get('getinvtargetvssummaryone', [BasicreportController::class, 'getinvtargetvssummaryone'])->name('getinvtargetvssummaryone');

// Invoice Summary 2
Route::get('targetvsinvoicesummarytwo', [BasicreportController::class, 'invoicesummarytwoindex'])->name('targetvsinvoicesummarytwo');
Route::get('gettargetvsinvsummarytwo', [BasicreportController::class, 'gettargetvsinvsummarytwo'])->name('gettargetvsinvsummarytwo');

// Invoice Summary 3
Route::get('targetvsinvoicesummarythree', [BasicreportController::class, 'invsummarythreeindex'])->name('targetvsinvoicesummarythree');
Route::get('gettargetvssummarythreeinv', [BasicreportController::class, 'gettargetvssummarythreeinv'])->name('gettargetvssummarythreeinv');

// Invoice Summary 4
Route::get('targetvsinvoicesummaryfour', [BasicreportController::class, 'summaryfourindexinv'])->name('targetvsinvoicesummaryfour');
Route::get('gettargetvssummaryfourinv', [BasicreportController::class, 'gettargetvssummaryfourinv'])->name('gettargetvssummaryfourinv');

// Target vs Invoice
Route::get('gettargetvsinvoice', [SalesorderdetailsrptController::class, 'gettargetinvoice'])->name('gettargetvsinvoice');

Route::get('credittakenrpt', [PurchaseregisterController::class, 'credittakenindex'])->name('credittakenrpt');
Route::get('getcredittaken', [PurchaseregisterController::class, 'getcredittaken'])->name('getcredittaken');
Route::get('credittakenexpenserpt', [ExpensesrptController::class, 'credittakenexpenseindex'])->name('credittakenexpenserpt');
Route::get('getcredittakenexpense', [ExpensesrptController::class, 'getcredittakenexpense'])->name('getcredittakenexpense');
Route::get('credittakenrtvrpt', [RtvreportController::class, 'credittakenrtvindex'])->name('credittakenrtvrpt');
Route::get('getcredittakenrtv', [RtvreportController::class, 'getcredittakenrtv'])->name('getcredittakenrtv');
Route::get('consolidatedempexpensesrpt', [ExpensesrptController::class, 'consolidatedempexpenseindex'])->name('consolidatedempexpensesrpt');
Route::get('getconsolidatedEmpExpenseData', [ExpensesrptController::class, 'getconsolidatedempexpenserptdata'])->name('getconsolidatedEmpExpenseData');

Route::get('wippmstockdetailsrpt', [SupplierbasedcostreportController::class, 'wippmstockdetailsindex'])->name('wippmstockdetailsrpt');
Route::get('getwippmstockdetails', [SupplierbasedcostreportController::class, 'getwippmstockdetails'])->name('getwippmstockdetails');
Route::get('productdetailsrpt', [SupplierbasedcostreportController::class, 'productdetailsindex'])->name('productdetailsrpt');
Route::get('getproductdetails', [SupplierbasedcostreportController::class, 'getproductdetails'])->name('getproductdetails');
