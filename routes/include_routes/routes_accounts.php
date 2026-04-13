<?php

use App\Http\Controllers\AccountclassController;
use App\Http\Controllers\AccountcodesnewController;
use App\Http\Controllers\AccountstructureController;
use App\Http\Controllers\AccountuploadController;
use App\Http\Controllers\AccountperiodsController;
use App\Http\Controllers\AccountcurrencyController;
use App\Http\Controllers\AccountexchangeratesController;
use App\Http\Controllers\AccountsettingsController;
use App\Http\Controllers\ProductaccountsettingsController;
use App\Http\Controllers\ShiftdetailController;
use App\Http\Controllers\InvestmenttypeController;
use App\Http\Controllers\AccountbankchargesController;
use App\Http\Controllers\ItccredittakenController;
use App\Http\Controllers\Gstr3b2bcomController;
use App\Http\Controllers\CreditupdateController;
use App\Http\Controllers\TdstcsoutputController;
use App\Http\Controllers\TcstcsoutputController;
use App\Http\Controllers\TdsslabController;
use App\Http\Controllers\TaxcategoryController;
use App\Http\Controllers\TaxcodeController;
use App\Http\Controllers\TaxgroupController;
use App\Http\Controllers\TcsslabController;
use App\Http\Controllers\AccountrptgrpController;
use App\Http\Controllers\GstcodeController;
use App\Http\Controllers\TcsoutputController;
use App\Http\Controllers\BankaccountController;
use App\Http\Controllers\BankchequeController;
use App\Http\Controllers\BankstatementuploadController;
use App\Http\Controllers\EmicalculatorController;
use App\Http\Controllers\ContraentryController;
use App\Http\Controllers\FormsixteenupldController;
use App\Http\Controllers\BrsautomationController;
use App\Http\Controllers\JournalentryController;
use App\Http\Controllers\JournalpostingController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\TravelclaimController;
use App\Http\Controllers\ImprestController;
use App\Http\Controllers\JournalreverseController;
use App\Http\Controllers\SubledgerController;
use App\Http\Controllers\GlbalancesController;
use App\Http\Controllers\JournaladjustmentsController;
use App\Http\Controllers\AssettypesController;
use App\Http\Controllers\AssetcategoryController;
use App\Http\Controllers\DepreciationmethodController;
use App\Http\Controllers\ExpensesController;
use App\Http\Controllers\PurchaseinvoiceController;
use App\Http\Controllers\DebitcreditController;
use App\Http\Controllers\EmployeeexpensesController;
use App\Http\Controllers\PaymentforinvoiceController;
use App\Http\Controllers\EmpexpuploadController;
use App\Http\Controllers\EmpincentiveuploadController;
use App\Http\Controllers\AdvancepaymentController;
use App\Http\Controllers\PaymentforbonusController;
use App\Http\Controllers\PaymentforelencashmentController;
use App\Http\Controllers\PaymentforfandfController;
use App\Http\Controllers\PaymentdetailsController;

// LARAVEL 8 NEW ROUTES

//Account Class
Route::get('accountclass', [AccountclassController::class, 'create'])->name('accountclass');
Route::get('getAccountclassData', [AccountclassController::class, 'getAccountclassData'])->name('getAccountclassData');
Route::post('accountclasssave', [AccountclassController::class, 'save'])->name('accountclasssave');
Route::get('accountclasscheckname', [AccountclassController::class, 'getCheckname'])->name('accountclasscheckname');
Route::get('accountclassdelete/{id}', [AccountclassController::class, 'delete'])->name('accountclassdelete');

//Account Build code
Route::get('accountcodesnew', [AccountcodesnewController::class, 'index'])->name('accountcodesnew');
Route::get('subaccountcode/{id}', [AccountcodesnewController::class, 'subaccountcode'])->name('subaccountcode');
Route::get('getAccountcodesnewData', [AccountcodesnewController::class, 'getAccountcodesnewData'])->name('getAccountcodesnewData');
Route::get('accountcodesnewcreate/{id}/{ids}', [AccountcodesnewController::class, 'detailCreate'])->name('accountcodesnewcreate');
Route::post('accountcodesnewsave', [AccountcodesnewController::class, 'save'])->name('accountcodesnewsave');
Route::get('accountcodesnewdelete/{id}', [AccountcodesnewController::class, 'delete'])->name('accountcodesnewdelete');
Route::get('accountbuildcheckname', [AccountcodesnewController::class, 'getCheckname'])->name('accountbuildcheckname');

//Account Structure
Route::get('accountstructure', [AccountstructureController::class, 'index'])->name('accountstructure');
Route::get('accountstructurecreate', [AccountstructureController::class, 'create'])->name('accountstructurecreate');
Route::get('accountstructurecreate/{id}', [AccountstructureController::class, 'create'])->name('accountstructurecreate'); 
Route::get('accountstructureview/{id}', [AccountstructureController::class, 'show'])->name('accountstructureview');
Route::post('accountstructuresave', [AccountstructureController::class, 'save'])->name('accountstructuresave');
Route::get('getAccountstructData', [AccountstructureController::class, 'getAccountstructData'])->name('getAccountstructData');
Route::get('accountstructuredelete/{id}', [AccountstructureController::class, 'delete'])->name('accountstructuredelete');
Route::get('getrptgrpdetails', [AccountstructureController::class, 'getrptgrpdetails'])->name('getrptgrpdetails');
Route::get('jcomboform', 'AccountstructureController@jcomboformDynamic');
Route::get('jcomboformcomp', 'AccountstructureController@jcomboformcompDynamic');

// Account Structure
Route::get('accountstructure', [AccountstructureController::class, 'index'])->name('accountstructure');
Route::get('accountstructurecreate', [AccountstructureController::class, 'create'])->name('accountstructurecreate');
Route::get('accountstructurecreate/{id}', [AccountstructureController::class, 'create'])->name('accountstructurecreate');
Route::get('accountstructureview/{id}', [AccountstructureController::class, 'show'])->name('accountstructureview');
Route::post('accountstructuresave', [AccountstructureController::class, 'save'])->name('accountstructuresave');
Route::get('getAccountstructData', [AccountstructureController::class, 'getAccountstructData'])->name('getAccountstructData');
Route::get('accountstructuredelete/{id}', [AccountstructureController::class, 'delete'])->name('accountstructuredelete');
Route::get('getrptgrpdetails', [AccountstructureController::class, 'getrptgrpdetails'])->name('getrptgrpdetails');

// Account Upload
Route::get('accountupload', [AccountuploadController::class, 'index'])->name('accountupload');
Route::get('getAccountuploadData', [AccountuploadController::class, 'getAccountuploadData'])->name('getAccountuploadData');
Route::post('accountupload', [AccountuploadController::class, 'Uploadexcel'])->name('accountupload');
Route::get('accountuploadvalidate', [AccountuploadController::class, 'getaccountvalidate'])->name('accountuploadvalidate');
Route::get('getaccountvalidate', [AccountuploadController::class, 'getaccountvalidate'])->name('getaccountvalidate');

// Account Periods
Route::get('accountperiods', [AccountperiodsController::class, 'index'])->name('accountperiods');
Route::get('accountperiodscreate', [AccountperiodsController::class, 'create'])->name('accountperiodscreate');
Route::get('accountperiodscreate/{id}', [AccountperiodsController::class, 'create'])->name('accountperiodscreate');
Route::get('accountperiodsview/{id}', [AccountperiodsController::class, 'show'])->name('accountperiodsview');
Route::post('accountperiodssave', [AccountperiodsController::class, 'save'])->name('accountperiodssave');
Route::get('getAccountperiodsData', [AccountperiodsController::class, 'getAccountperiodsData'])->name('getAccountperiodsData');
Route::get('accountperiodsdelete/{id}', [AccountperiodsController::class, 'delete'])->name('accountperiodsdelete');
Route::get('getcurrentdate/{id}/{ids}', [AccountperiodsController::class, 'getcurrentdate']);

// Account Currency
Route::get('accountcurrency', [AccountcurrencyController::class, 'create'])->name('accountcurrency');
Route::get('accountcurrencydelete/{id}', [AccountcurrencyController::class, 'delete'])->name('accountcurrencydelete');
Route::post('accountcurrencysave', [AccountcurrencyController::class, 'save'])->name('accountcurrencysave');
Route::get('getAccountcurrencyData', [AccountcurrencyController::class, 'getAccountcurrencyData'])->name('getAccountcurrencyData');
Route::get('accountcurrencycheckname', [AccountcurrencyController::class, 'getCheckname']);

// Account Exchangerates
Route::get('accountexchangerates', [AccountexchangeratesController::class, 'create'])->name('accountexchangerates');
Route::get('accountexchangeratescreate', [AccountexchangeratesController::class, 'create'])->name('accountexchangeratescreate');
Route::get('accountexchangeratesdelete/{id}', [AccountexchangeratesController::class, 'delete'])->name('accountexchangeratesdelete');
Route::post('accountexchangeratessave', [AccountexchangeratesController::class, 'save'])->name('accountexchangeratessave');
Route::get('getAccountexchangeratesData', [AccountexchangeratesController::class, 'getAccountexchangeratesData'])->name('getAccountexchangeratesData');
Route::get('accountexchangeratescheckname', [AccountexchangeratesController::class, 'getCheckname']);

// Account Settings
Route::get('accountsettings', [AccountsettingsController::class, 'index'])->name('accountsettings');
Route::post('accountsettingssave', [AccountsettingsController::class, 'save'])->name('accountsettingssave');
Route::get('getaccounts/{id}', [AccountsettingsController::class, 'getaccounts'])->name('getaccounts');
Route::post('accountsettingsave', [AccountsettingsController::class, 'accountsettingsave'])->name('accountsettingsave');
Route::get('getaccountsetting', [AccountsettingsController::class, 'getaccountsetting'])->name('getaccountsetting');

// Product Account Settings
Route::get('productaccountsettings', [ProductaccountsettingsController::class, 'index'])->name('productaccountsettings');
Route::get('getProductaccountsettingData', [ProductaccountsettingsController::class, 'getProductaccountsettingData'])->name('getProductaccountsettingData');
Route::get('prdaccsettingcreate', [ProductaccountsettingsController::class, 'create'])->name('prdaccsettingcreate');
Route::get('prdaccsettingcreate/{id}', [ProductaccountsettingsController::class, 'create'])->name('prdaccsettingedit');
Route::post('productaccountstngsave', [ProductaccountsettingsController::class, 'save'])->name('productaccountstngsave');
Route::get('prdaccsettingview/{id}', [ProductaccountsettingsController::class, 'show'])->name('prdaccsettingview');

// Account Settings 
Route::get('hrmsaccountsettings', [ShiftdetailController::class, 'hrmsaccountsettingsindex'])->name('hrmsaccountsettings');
Route::get('hrmsaccountsettingsdata', [ShiftdetailController::class, 'hrmsaccountsettingsdata'])->name('hrmsaccountsettingsdata');
Route::post('hrmsaccountsettingssave', [ShiftdetailController::class, 'hrmsaccountsettingssave'])->name('hrmsaccountsettingssave');
Route::get('hrmsaccountsettingsdelete/{id}', [ShiftdetailController::class, 'hrmsaccountsettingsdelete'])->name('hrmsaccountsettingsdelete');

/** HRMS Allowance Settings **/
Route::get('hrmsallowancesettings', [InvestmenttypeController::class, 'hrmsallowancesettings'])->name('hrmsallowancesettings');
Route::get('hrmsallowancesettings/{id}', [InvestmenttypeController::class, 'hrmsallowancesettingsindex'])->name('hrmsallowancesettingsindex');
Route::post('hrmsallowancesettingssave', [InvestmenttypeController::class, 'hrmsallowancesettingsindexstore'])->name('hrmsallowancesettingsindexstore');
Route::get('hrmsallowancesettingsgrid', [InvestmenttypeController::class, 'hrmsallowancesettingsgrid'])->name('hrmsallowancesettingsgrid');
Route::get('hrmsallowancesettingsdelete/{id}', [InvestmenttypeController::class, 'hrmsallowancesettingsdelete'])->name('hrmsallowancesettingsdelete');


// Account Bankcharges
Route::get('accountbankcharges', [AccountbankchargesController::class, 'create'])->name('accountbankcharges');
Route::get('accountbankchargescreate', [AccountbankchargesController::class, 'create'])->name('accountbankchargescreate');
Route::get('accountbankchargesdelete/{id}', [AccountbankchargesController::class, 'delete'])->name('accountbankchargesdelete');
Route::post('accountbankchargessave', [AccountbankchargesController::class, 'save'])->name('accountbankchargessave');
Route::get('getAccountbankchargesData', [AccountbankchargesController::class, 'getAccountbankchargesData'])->name('getAccountbankchargesData');
Route::get('accountbankchargescheckname', [AccountbankchargesController::class, 'getbankchargesCheckname']);


// gstr 2b upload
Route::get('gstrtwobupload', [ItccredittakenController::class, 'gstrtwobupload'])->name('gstrtwobupload');
Route::get('getgstdata', [ItccredittakenController::class, 'getgst2bdata'])->name('getgstdata');
Route::post('gstrtwobdataupload', [ItccredittakenController::class, 'Uploadexcel'])->name('gstrtwobdataupload');
Route::get('gstulpdreport', [ItccredittakenController::class, 'gstulpdreport'])->name('getitcreport');
Route::get('gstcomparereport', [ItccredittakenController::class, 'gstcomparereport'])->name('gstcomparereport');
Route::get('getcommanreport', [ItccredittakenController::class, 'getcomaprereport'])->name('getcommanreport');

// gstr 3b upload
Route::get('gstrthreebupload', [Gstr3b2bcomController::class, 'gstrthreebupload'])->name('gstrthreebupload');
Route::get('getgst3data', [Gstr3b2bcomController::class, 'getgst3bdata'])->name('getgst3data');
Route::post('gstrthreebdataupload', [Gstr3b2bcomController::class, 'Uploadexcel'])->name('gstrthreebdataupload');
Route::get('gstupldreport', [Gstr3b2bcomController::class, 'gstupldreport'])->name('getgst3report');
Route::get('gstcomprreport', [Gstr3b2bcomController::class, 'gstcomprreport'])->name('gstcomprreport');
Route::get('getcommreport', [Gstr3b2bcomController::class, 'getcomprreport'])->name('getcommreport');

// credit update module
Route::get('creditupdate', [CreditupdateController::class, 'index'])->name('creditupdate');
Route::get('getcreditData', [CreditupdateController::class, 'getcreditData'])->name('getcreditData');
Route::post('creditupdatesave', [CreditupdateController::class, 'creditupdatesave'])->name('creditupdatesave');

// TDS Workings
Route::get('tdstcsoutput', [TdstcsoutputController::class, 'Index'])->name('tdstcsoutput');

// TCS Workings
Route::get('tcsoutput', [TcsoutputController::class, 'Index'])->name('tcsoutput');

// TDS Slab
Route::get('tdsslab', [TdsslabController::class, 'create'])->name('tdsslab');
Route::get('tdsslabcreate', [TdsslabController::class, 'create'])->name('tdsslabcreate');
Route::get('tdsslabdelete/{id}', [TdsslabController::class, 'delete'])->name('tdsslabdelete');
Route::post('tdsslabsave', [TdsslabController::class, 'save'])->name('tdsslabsave');
Route::get('getTdsslabData', [TdsslabController::class, 'getTdsslabData'])->name('getTdsslabData');
Route::get('tdsslabcheckname', [TdsslabController::class, 'getCheckname'])->name('tdsslabcheckname');
Route::get('tdsslabedit/{id}', [TdsslabController::class, 'getedit'])->name('tdsslabedit');

// TAX Category
Route::get('taxcategory', [TaxcategoryController::class, 'index'])->name('taxcategory');
Route::get('taxcategorytedit2/{id}', [TaxcategoryController::class, 'getedit'])->name('taxcategorytedit2');
Route::get('taxcategorydelete/{id}', [TaxcategoryController::class, 'delete'])->name('taxcategorydelete');
Route::post('taxcategorysave', [TaxcategoryController::class, 'save'])->name('taxcategorysave');
Route::get('getTaxcategoryData', [TaxcategoryController::class, 'getTaxcategoryData'])->name('getTaxcategoryData');
Route::get('taxcategorycheckname', [TaxcategoryController::class, 'getCheckname'])->name('taxcategorycheckname');

// TAX Code
Route::get('taxcode', [TaxcodeController::class, 'create'])->name('taxcode');
Route::get('taxcodecreate', [TaxcodeController::class, 'create'])->name('taxcodecreate');
Route::get('taxcodedelete/{id}', [TaxcodeController::class, 'delete'])->name('taxcodedelete');
Route::post('taxcodesave', [TaxcodeController::class, 'save'])->name('taxcodesave');
Route::get('getTaxcodeData', [TaxcodeController::class, 'getTaxcodeData'])->name('getTaxcodeData');
Route::get('taxcodecheckname', [TaxcodeController::class, 'getCheckname'])->name('taxcodecheckname');

// TAX Group
Route::get('taxgroup', [TaxgroupController::class, 'index'])->name('taxgroup');
Route::get('taxgroupcreate', [TaxgroupController::class, 'create'])->name('taxgroupcreate');
Route::get('taxgroupcreate/{id}', [TaxgroupController::class, 'create'])->name('taxgroupcreate');
Route::get('taxgroupview/{id}', [TaxgroupController::class, 'show'])->name('taxgroupview');
Route::post('taxgroupsave', [TaxgroupController::class, 'save'])->name('taxgroupsave');
Route::get('getTaxgroupData', [TaxgroupController::class, 'getTaxgroupData'])->name('getTaxgroupData');
Route::get('taxgroupdelete/{id}', [TaxgroupController::class, 'delete'])->name('taxgroupdelete');
Route::get('taxgroupcheckname', [TaxgroupController::class, 'getCheckname'])->name('taxgroupcheckname');

// TCS Slab
Route::get('tcsslab', [TcsslabController::class, 'create'])->name('tcsslab');
Route::get('tcsslabcreate', [TcsslabController::class, 'create'])->name('tcsslabcreate');
Route::get('tcsslabdelete/{id}', [TcsslabController::class, 'delete'])->name('tcsslabdelete');
Route::post('tcsslabsave', [TcsslabController::class, 'save'])->name('bulk_pair');
Route::get('getTcsslabData', [TcsslabController::class, 'getTdsslabData'])->name('getTcsslabData');
Route::get('tcsslabcheckname', [TcsslabController::class, 'getCheckname'])->name('tcsslabcheckname');
Route::get('tcsslabedit/{id}', [TcsslabController::class, 'getedit'])->name('tcsslabedit');

// Account Reporting Group
Route::get('accountrptgrp', [AccountrptgrpController::class, 'create'])->name('accountrptgrp');
Route::get('accountrptgrpcreate', [AccountrptgrpController::class, 'create'])->name('accountrptgrpcreate');
Route::get('accountrptgrpdelete/{id}', [AccountrptgrpController::class, 'delete'])->name('accountrptgrpdelete');
Route::post('accountrptgrpsave', [AccountrptgrpController::class, 'save'])->name('accountrptgrpsave');
Route::get('getAccountrptgrpData', [AccountrptgrpController::class, 'getAccountrptgrpData'])->name('getAccountrptgrpData');
Route::get('accountrptgrpcheckname', [AccountrptgrpController::class, 'getCheckname'])->name('accountrptgrpcheckname');
Route::get('getrptgrpdetails', [AccountstructureController::class, 'getrptgrpdetails'])->name('getrptgrpdetails');

// GST Code
Route::get('gstcode', [GstcodeController::class, 'index'])->name('gstcode');
Route::get('gstcodecreate', [GstcodeController::class, 'create'])->name('gstcodecreate');
Route::get('gstcodecreate/{id}', [GstcodeController::class, 'create'])->name('gstcodecreate');
Route::get('gstcodeview/{id}', [GstcodeController::class, 'show'])->name('gstcodeview');
Route::post('gstcodesave', [GstcodeController::class, 'save'])->name('gstcodesave');
Route::get('getGstcodeData', [GstcodeController::class, 'getGstcodeData'])->name('getGstcodeData');
Route::get('gstcodedelete/{id}', [GstcodeController::class, 'delete'])->name('gstcodedelete');
Route::get('gstcodecheckname', [GstcodeController::class, 'gstcodecheckname'])->name('gstcodecheckname');

// Bank Account
Route::get('bankaccount', [BankaccountController::class, 'index'])->name('bankaccount');
Route::get('bankaccountcreate', [BankaccountController::class, 'create'])->name('bankaccountcreate');
Route::get('bankaccountcreate/{id}', [BankaccountController::class, 'create'])->name('bankaccountcreate');
Route::get('bankaccountview/{id}', [BankaccountController::class, 'show'])->name('bankaccountview');
Route::post('bankaccountsave', [BankaccountController::class, 'save'])->name('bankaccountsave');
Route::get('getBankaccountData', [BankaccountController::class, 'getBankaccountData'])->name('getBankaccountData');
Route::get('bankaccountdelete/{id}', [BankaccountController::class, 'delete'])->name('bankaccountdelete');

// Bank Cheque
Route::get('bankcheque', [BankchequeController::class, 'index'])->name('bankcheque');
Route::get('bankchequecreate', [BankchequeController::class, 'create'])->name('bankchequecreate');
Route::get('bankchequecreate/{id}/{ids}', [BankchequeController::class, 'create'])->name('bankchequecreate');
Route::get('bankchequeview/{id}', [BankchequeController::class, 'show'])->name('bankchequeview');
Route::post('bankchequesave', [BankchequeController::class, 'save'])->name('bankchequesave');
Route::get('getBankchequeData', [BankchequeController::class, 'getBankchequeData'])->name('getBankchequeData');

// Bank Statement Upload
Route::get('bankstmtupload', [BankstatementuploadController::class, 'index'])->name('bankstmtupload');
Route::get('getstmtuploaddata', [BankstatementuploadController::class, 'getstmtuploaddata'])->name('getstmtuploaddata');
Route::post('stmtuploaddata', [BankstatementuploadController::class, 'Uploadexcel'])->name('stmtuploaddata');
Route::get('stmtuploadview', [BankstatementuploadController::class, 'view'])->name('stmtuploadview');
Route::get('getReceiptsData', [BankstatementuploadController::class, 'getReceiptsData'])->name('getReceiptsData');
Route::get('getpoInvoiceData', [BankstatementuploadController::class, 'getpoInvoiceData'])->name('getpoInvoiceData');
Route::post('move-statement', [BankstatementuploadController::class, 'moveStatement']);

// Statement View
Route::get('viewstatement/{bank_name}/{account_no}/{from_date}/{to_date}', [BankstatementuploadController::class, 'viewstatement'])->name('viewstatement');

// BRS Details
Route::get('viewstatementdetails', [BankstatementuploadController::class, 'viewstatementdetailsindex'])->name('viewstatementdetails');
Route::get('getviewstatementdetails', [BankstatementuploadController::class, 'getviewstatementdetails'])->name('getviewstatementdetails');

// Unmatched Statement
Route::get('unmatchedstatment/{id}', [BankstatementuploadController::class, 'unmatchedstatment'])->name('unmatchedstatment');

// Emi calc
Route::get('emicalculator', [EmicalculatorController::class, 'index'])->name('emicalculator');

// contraentry
Route::get('contraentry', [ContraentryController::class, 'index'])->name('contraentry');
Route::get('contraentrydata', [ContraentryController::class, 'contraentrydata'])->name('contraentrydata');
Route::post('contraentrysave', [ContraentryController::class, 'save'])->name('contraentrysave');

// Form16 Upload
Route::get('formsixteenupld', [FormsixteenupldController::class, 'index'])->name('formsixteenupld');
Route::get('formsixteenupldcreate', [FormsixteenupldController::class, 'create'])->name('formsixteenupldcreate');
Route::get('formsixteenupldcreate/{id}', [FormsixteenupldController::class, 'create'])->name('formsixteenupldcreate');
Route::get('formsixteenupldview/{id}', [FormsixteenupldController::class, 'show'])->name('formsixteenupldview');
Route::post('formsixteenupldsave', [FormsixteenupldController::class, 'save'])->name('formsixteenupldsave');
Route::get('getFormsixteenupldData', [FormsixteenupldController::class, 'getFormsixteenupldData'])->name('getFormsixteenupldData');
Route::get('formsixteenuplddelete/{id}', [FormsixteenupldController::class, 'delete'])->name('formsixteenuplddelete');
Route::get('form16upldedit', [FormsixteenupldController::class, 'form16upldedit'])->name('form16upldedit');
Route::post('form16upldupdate', [FormsixteenupldController::class, 'form16upldupdate'])->name('form16upldupdate');

// BRS automation
Route::get('brsautomation', [BrsautomationController::class, 'index'])->name('brsautomation');
Route::get('getbrsData', [BrsautomationController::class, 'getbrsData'])->name('getbrsData');
Route::post('brsupdatesave', [BrsautomationController::class, 'brsupdatesave'])->name('brsupdatesave');

// Journal Entry
Route::get('journalentry', [JournalentryController::class, 'index'])->name('journalentry');
Route::get('journalentrycreate', [JournalentryController::class, 'create'])->name('journalentrycreate');
Route::get('journalentrycreate/{id}', [JournalentryController::class, 'create'])->name('journalentrycreate');
Route::get('journalentrycreate/{id}/{idss}', [JournalentryController::class, 'create'])->name('journalentrycreate');
Route::get('journalentryview/{id}', [JournalentryController::class, 'show'])->name('journalentryview');
Route::post('journalentrysave', [JournalentryController::class, 'save'])->name('journalentrysave');
Route::get('getJournalentryData', [JournalentryController::class, 'getJournalentryData'])->name('getJournalentryData');

// Journal Approval
Route::get('journalapproval', [JournalentryController::class, 'index'])->name('journalapproval');
Route::get('journalapproval/{id}/{aprv}', [JournalentryController::class, 'journalapproval'])->name('journalapproval');

// Get Month (general)
Route::get('getmonth', [Controller::class, 'getmonth'])->name('getmonth');

// Journal Posting
Route::get('journalposting', [JournalpostingController::class, 'index'])->name('journalposting');
Route::get('getJournalpostingData', [JournalpostingController::class, 'getJournalpostingData'])->name('getJournalpostingData');
Route::get('getJournalpost/{id}/{ids}', [JournalpostingController::class, 'getJournalpost'])->name('journalposting');
Route::get('journalpostingview/{id}', [JournalpostingController::class, 'show'])->name('journalpostingview');

// Travel Journal
Route::get('traveljournal', [TravelclaimController::class, 'approveindex'])->name('traveljournal');
Route::get('traveljournalcreate/{id}', [TravelclaimController::class, 'traveljournalcreate'])->name('traveljournalcreate');
Route::get('travelexpensecreate/{id}', [TravelclaimController::class, 'travelexpensecreate'])->name('travelexpensecreate');

// Imprest Journal
Route::get('imprestjournal', [ImprestController::class, 'index'])->name('imprestjournal');
Route::get('imprestjournalcreate/{id}', [ImprestController::class, 'imprestjournalcreate'])->name('imprestjournalcreate');
Route::get('imprestexpensecreate/{id}', [ImprestController::class, 'imprestexpensecreate'])->name('imprestexpensecreate');

// Journal Reverse
Route::get('journalreverse', [JournalreverseController::class, 'index'])->name('journalreverse');
Route::get('getJournalreverseData', [JournalreverseController::class, 'getJournalreverseData'])->name('getJournalreverseData');
Route::get('journalreverseview/{id}', [JournalreverseController::class, 'show'])->name('journalreverseview');
Route::get('reversejournal/{id}', [JournalreverseController::class, 'reversejournal'])->name('reversejournal');

// Sub Ledger
Route::get('subledgerload', [SubledgerController::class, 'index'])->name('subledgerload');
Route::get('subledger', [SubledgerController::class, 'filterindex'])->name('subledger');
Route::get('getsubledgerfilterData', [SubledgerController::class, 'getsubledgerfilterData'])->name('getsubledgerfilterData');
Route::get('getsubledgerload', [SubledgerController::class, 'getsubledgerload'])->name('getsubledgerload');
Route::get('getsearch', [SubledgerController::class, 'getsearch'])->name('getsearch');
Route::get('getledgerpost/{id}', [SubledgerController::class, 'getledgerpost'])->name('getledgerpost');
Route::get('subledgerview/{id}', [SubledgerController::class, 'show'])->name('subledgerview');

// GL Balances
Route::get('glbalances', [GlbalancesController::class, 'index'])->name('glbalances');
Route::get('getglbalanceData', [GlbalancesController::class, 'getglbalanceData'])->name('getglbalanceData');

// Journal Adjustments
Route::get('journaladjustments', [JournaladjustmentsController::class, 'index'])->name('journaladjustments');
Route::get('journaladjustmentscreate', [JournaladjustmentsController::class, 'create'])->name('journaladjustmentscreate');
Route::get('journaladjustmentscreate/{id}', [JournaladjustmentsController::class, 'create'])->name('journaladjustmentscreate');
Route::get('journaladjustmentsview/{id}', [JournaladjustmentsController::class, 'show'])->name('journaladjustmentsview');
Route::post('journaladjustmentssave', [JournaladjustmentsController::class, 'save'])->name('journaladjustmentssave');
Route::get('getJournalAdjustmentsData', [JournaladjustmentsController::class, 'getJournalAdjustmentsData'])->name('getJournalAdjustmentsData');
Route::get('journaladjustmentsdelete/{id}', [JournaladjustmentsController::class, 'delete'])->name('journaladjustmentsdelete');

// Adjustment Approval
Route::get('adjustmentapproval', [JournaladjustmentsController::class, 'index'])->name('adjustmentapproval');
Route::get('adjustmentsapproval/{id}/{aprv}/{date}', [JournaladjustmentsController::class, 'adjustmentsapproval'])->name('adjustmentsapproval');
Route::get('adjustmentsapprovalview/{id}', [JournaladjustmentsController::class, 'approvalview'])->name('adjustmentsapprovalview');

// Asset Types
Route::get('assettypes', [AssettypesController::class, 'create'])->name('assettypes');
Route::get('assettypescreate', [AssettypesController::class, 'create'])->name('assettypescreate');
Route::get('assettypesdelete/{id}', [AssettypesController::class, 'delete'])->name('assettypesdelete');
Route::post('assettypessave', [AssettypesController::class, 'save'])->name('assettypessave');
Route::get('getAssettypesData', [AssettypesController::class, 'getAssettypesData'])->name('getAssettypesData');
Route::get('assettypescheckname', [AssettypesController::class, 'getCheckname'])->name('assettypescheckname');

// Asset Category
Route::get('assetcategory', [AssetcategoryController::class, 'create'])->name('assetcategory');
Route::get('assetcategorycreate', [AssetcategoryController::class, 'create'])->name('assetcategorycreate');
Route::get('assetcategorydelete/{id}', [AssetcategoryController::class, 'delete'])->name('assetcategorydelete');
Route::post('assetcategorysave', [AssetcategoryController::class, 'save'])->name('assetcategorysave');
Route::get('getAssetcategoryData', [AssetcategoryController::class, 'getAssetcategoryData'])->name('getAssetcategoryData');
Route::get('assetcategorycheckname', [AssetcategoryController::class, 'getCheckname'])->name('assetcategorycheckname');

// Depreciation Method
Route::get('depreciationmethod', [DepreciationmethodController::class, 'index'])->name('depreciationmethod');
Route::get('depreciationmethodcreate', [DepreciationmethodController::class, 'create'])->name('depreciationmethodcreate');
Route::get('depreciationmethodcreate/{id}', [DepreciationmethodController::class, 'create'])->name('depreciationmethodcreate');
Route::get('depreciationmethodview/{id}', [DepreciationmethodController::class, 'show'])->name('depreciationmethodview');
Route::post('depreciationmethodsave', [DepreciationmethodController::class, 'save'])->name('depreciationmethodsave');
Route::get('getdepreciationData', [DepreciationmethodController::class, 'getdepreciationData'])->name('getdepreciationData');
Route::get('depreciationmethoddelete/{id}', [DepreciationmethodController::class, 'delete'])->name('depreciationmethoddelete');
Route::get('getpodetails/{id}', [DepreciationmethodController::class, 'getpodetails'])->name('getpodetails');
Route::get('getprice/{id}', [DepreciationmethodController::class, 'getprice'])->name('getprice');

// Expenses
Route::get('expenses', [ExpensesController::class, 'index'])->name('expenses');
Route::get('expensescreate', [ExpensesController::class, 'create'])->name('expensescreate');
Route::get('expensescreate/{id}', [ExpensesController::class, 'create'])->name('expensescreate');
Route::get('expensescreate/{id}/{idss}', [ExpensesController::class, 'create'])->name('expensescreate');
Route::get('expensesview/{id}', [ExpensesController::class, 'show'])->name('expensesview');
Route::post('expensessave', [ExpensesController::class, 'save'])->name('expensessave');
Route::get('getExpenseindexData', [ExpensesController::class, 'getExpenseindexData'])->name('getExpenseindexData');
Route::get('expensesdelete/{id}', [ExpensesController::class, 'delete'])->name('expensesdelete');
Route::get('expensespaycreate/{id}', [ExpensesController::class, 'expensespaycreate'])->name('expensespaycreate');
Route::post('expensecreditupdate/{id}', [PurchaseinvoiceController::class, 'expensecreditupdate'])->name('expensecreditupdate');
Route::get('expansenamechk', [ExpensesController::class, 'expansenamechk'])->name('expansenamechk');

// DEBIT/CREDIT Note
Route::get('debitcreditnote', [DebitcreditController::class, 'index'])->name('debitcreditnote');
Route::get('debitcreditnotecreate', [DebitcreditController::class, 'create'])->name('debitcreditnotecreate');
Route::get('debitcreditnotecreate/{id}', [DebitcreditController::class, 'create'])->name('debitcreditnotcreate');
Route::get('debitcreditview/{id}', [DebitcreditController::class, 'show'])->name('debitcreditview');
Route::post('debitcreditsave', [DebitcreditController::class, 'save'])->name('debitcreditsave');
Route::get('getdebitcreditData', [DebitcreditController::class, 'getdebitcreditData'])->name('getdebitcreditData');
Route::get('debitprint/{id}', [DebitcreditController::class, 'debitprint'])->name('debitprint');
Route::get('debitcreditrefno', [DebitcreditController::class, 'debitcreditrefno'])->name('debitcreditrefno');
Route::get('debitcreditrefnoupdate', [DebitcreditController::class, 'debitcreditrefnoupdate'])->name('debitcreditrefnoupdate');
Route::post('crdrcreditupdate/{id}', [DebitcreditController::class, 'crdrcreditupdate'])->name('crdrcreditupdate');

// DEBIT/CREDIT Approval
Route::get('debitcreditapproval', [DebitcreditController::class, 'index'])->name('debitcreditapproval');
Route::get('debitcreditapproval/{id}/{aprv}', [DebitcreditController::class, 'debitcreditapproval'])->name('debitcreditapproval.approve');


// Expense Approval
Route::get('expenseapproval', [ExpensesController::class, 'index'])->name('expenseapproval');
Route::get('expenseapproval/{id}/{aprv}', [ExpensesController::class, 'expenseapproval'])->name('expenseapproval.approve');
Route::get('getpaymentamount/{id}', [JournalentryController::class, 'getpaymentamount'])->name('getpaymentamount');

// Employee Expense
Route::get('empexpenses', [EmployeeexpensesController::class, 'index'])->name('empexpenses');
Route::get('empexpensescreate', [EmployeeexpensesController::class, 'create'])->name('empexpensescreate');
Route::get('empexpensescreate/{id}', [EmployeeexpensesController::class, 'create'])->name('empexpensescreate.edit');
Route::get('empexpensescreate/{id}/{idss}', [EmployeeexpensesController::class, 'create'])->name('empexpensescreate.multi');
Route::get('empexpensesview/{id}', [EmployeeexpensesController::class, 'show'])->name('empexpensesview');
Route::post('empexpensessave', [EmployeeexpensesController::class, 'save'])->name('empexpensessave');
Route::get('getempExpenseindexData', [EmployeeexpensesController::class, 'getempExpenseindexData'])->name('getempExpenseindexData');
Route::get('empexpensesdelete/{id}', [EmployeeexpensesController::class, 'delete'])->name('empexpensesdelete');
Route::get('empexpensespaycreate/{id}', [EmployeeexpensesController::class, 'expensespaycreate'])->name('empexpensespaycreate');
Route::get('empexpansenamechk', [EmployeeexpensesController::class, 'expansenamechk'])->name('empexpansenamechk');

// Employee Expense Approval
Route::get('empexpenseapproval', [EmployeeexpensesController::class, 'index'])->name('empexpenseapproval');
Route::get('empexpenseapproval/{id}/{aprv}', [EmployeeexpensesController::class, 'expenseapproval'])->name('empexpenseapproval.approve');


// Payment for Employee Expense
Route::get('paymentforempexpense', [PaymentforinvoiceController::class, 'empexpenseindex'])->name('paymentforempexpense');
Route::get('paymentforempexpensecreate/{id}', [PaymentforinvoiceController::class, 'paymentforempexpensecreate'])->name('paymentforempexpensecreate');
Route::get('getpaymentempExpenseData', [PaymentforinvoiceController::class, 'getpaymentempExpenseData'])->name('getpaymentempExpenseData');
Route::post('paymentempExpensesave', [PaymentforinvoiceController::class, 'paymentempExpensesave'])->name('paymentempExpensesave');


// Employee Expense Payment Request
Route::get('empexpensepaymentrequest', [PaymentforinvoiceController::class, 'empexpensepaymentrequestindex'])->name('empexpensepaymentrequest');
Route::get('getempexpenseRequestforpaymentData', [PaymentforinvoiceController::class, 'getempexpenseRequestforpaymentData'])->name('getempexpenseRequestforpaymentData');
Route::get('getempexpensepaymentreq/{id}', [PaymentforinvoiceController::class, 'getempexpensepaymentreq'])->name('getempexpensepaymentreq');
Route::get('empexpensepaymentrequestapproval', [PaymentforinvoiceController::class, 'empexpensepaymentrequestindex'])->name('empexpensepaymentrequestapproval');


// Expense upload
Route::get('empexpupload', [EmpexpuploadController::class, 'index'])->name('empexpupload');
Route::get('getempexpuploaddata', [EmpexpuploadController::class, 'getempexpuploaddata'])->name('getempexpuploaddata');
Route::get('getempexpvalidate', [EmpexpuploadController::class, 'getempexpvalidate'])->name('getempexpvalidate');
Route::post('empexpuploaddata', [EmpexpuploadController::class, 'Uploadexcel'])->name('empexpuploaddata');
Route::post('docsupload', [EmpexpuploadController::class, 'docsupload'])->name('docsupload');
Route::get('empexpuploadedit/{id}', [EmpexpuploadController::class, 'create'])->name('empexpuploadedit');
Route::post('empexpuploadsave', [EmpexpuploadController::class, 'save'])->name('empexpuploadsave');

// Employee Incentive Upload
Route::get('empincentiveupload', [EmpincentiveuploadController::class, 'index'])->name('empincentiveupload');
Route::post('empinsuploaddata', [EmpincentiveuploadController::class, 'Uploadexcel'])->name('empinsuploaddata');
Route::get('getempinsuploaddata', [EmpincentiveuploadController::class, 'getempinsuploaddata'])->name('getempinsuploaddata');
Route::get('getempinsvalidate', [EmpincentiveuploadController::class, 'getempinsvalidate'])->name('getempinsvalidate');

// Payment For Employee
Route::get('paymentforemployee', [PaymentforinvoiceController::class, 'employeepayrolforpayindex'])->name('paymentforemployee');
Route::get('paymentforemployeecreate/{id}', [PaymentforinvoiceController::class, 'paymentforemployeecreate'])->name('paymentforemployeecreate');
Route::get('employeepayrolforpaygrid', [PaymentforinvoiceController::class, 'employeepayrolforpaygrid'])->name('employeepayrolforpaygrid');
Route::get('holdpaymentfremp', [PaymentforinvoiceController::class, 'holdpaymentfremp'])->name('holdpaymentfremp');
Route::get('releasedpayment', [PaymentforinvoiceController::class, 'releasedpayment'])->name('releasedpayment');
Route::get('releasepaymentfremp', [PaymentforinvoiceController::class, 'releaseindex'])->name('releasepaymentfremp');
Route::get('approvereleasepaymentforemployee', [PaymentforinvoiceController::class, 'releaseindex'])->name('approvereleasepaymentforemployee');
Route::get('approvepaymentfremp', [PaymentforinvoiceController::class, 'approvepaymentfremp'])->name('approvepaymentfremp');

// Advance Payment
Route::get('advancepayment', [AdvancepaymentController::class, 'index'])->name('advancepayment');
Route::get('advancepaymentcreate/{id}', [AdvancepaymentController::class, 'create'])->name('advancepaymentcreate');
Route::get('advancepaymentview/{id}', [AdvancepaymentController::class, 'show'])->name('advancepaymentview');
Route::post('advancepaymentsave', [AdvancepaymentController::class, 'save'])->name('accountstructuresave');
Route::get('getPodetailsData', [AdvancepaymentController::class, 'getPodetailsData'])->name('getPodetailsData');
Route::get('advancepaymentdelete/{id}', [AdvancepaymentController::class, 'delete'])->name('advancepaymentdelete');
Route::get('getpodetails/{id}', [AdvancepaymentController::class, 'getpodetails'])->name('getpodetails');
Route::get('getpaymentchequeno/{id}', [AdvancepaymentController::class, 'getpaymentchequeno'])->name('getpaymentchequeno');

// Payment For Invoice
Route::get('paymentrequest', [PaymentforinvoiceController::class, 'paymentrequestindex']) ->name('paymentrequest');
Route::get('getRequestforpaymentData', [PaymentforinvoiceController::class, 'getRequestforpaymentData']);
Route::get('getpaymentreq/{id}', [PaymentforinvoiceController::class, 'getpaymentreq']) ->name('getpaymentreq');
Route::get('paymentrequestapproval', [PaymentforinvoiceController::class, 'paymentrequestindex'])->name('paymentrequestapproval');

// Tax Group
Route::get('taxdetails/{id}/{ssid}/{type}', [PaymentforinvoiceController::class, 'taxdetails'])->name('taxdetails');

// Payment For Invoice

Route::get('paymentforinvoice', [PaymentforinvoiceController::class, 'index'])->name('paymentforinvoice');
Route::get('paymentforinvoicecreate/{id}', [PaymentforinvoiceController::class, 'create'])->name('paymentforinvoicecreate');
Route::get('paymentforinvoicecreate/{invid}/{poid}/{idss}', [PaymentforinvoiceController::class, 'create'])->name('paymentforinvoicecreate');
Route::get('paymentforinvoicecreate/{id}/{idss}', [PaymentforinvoiceController::class, 'createfromstatement'])->name('paymentforinvoicecreate');
Route::post('paymentforinvoicesave', [PaymentforinvoiceController::class, 'save'])->name('paymentforinvoicesave');
Route::post('paymentforinvoiceview/{id}', [PaymentforinvoiceController::class, 'view'])->name('paymentforinvoiceview');
Route::get('getInvoicedetailsData', [PaymentforinvoiceController::class, 'getInvoicedetailsData']);
Route::get('getchequeno/{id}', [PaymentforinvoiceController::class, 'getchequeno'])->name('getchequeno');
Route::get('getadvance/{id}', [PaymentforinvoiceController::class, 'getadvance'])->name('getadvance');
Route::get('getaccountdetails/{id}', [PaymentforinvoiceController::class, 'getaccountdetails'])->name('getaccountdetails');
Route::get('getcashaccount', [PaymentforinvoiceController::class, 'getcashaccount'])->name('getcashaccount');
Route::get('getimprestaccount', [PaymentforinvoiceController::class, 'getimprestaccount'])->name('getimprestaccount');
Route::get('balcloedit', [PaymentforinvoiceController::class, 'balcloedit'])->name('balcloedit');
Route::get('balcloseupdate', [PaymentforinvoiceController::class, 'balcloseupdate'])->name('balcloseupdate');
Route::get('balcloexpedit', [PaymentforinvoiceController::class, 'balcloexpedit'])->name('balcloexpedit');
Route::get('balcloseexpupdate', [PaymentforinvoiceController::class, 'balcloseexpupdate'])->name('balcloseexpupdate');


// Payment For Expense
Route::get('paymentforexpense', [PaymentforinvoiceController::class, 'expenseindex'])->name('paymentforexpense');
Route::get('paymentforexpensecreate/{id}', [PaymentforinvoiceController::class, 'paymentforexpensecreate'])->name('paymentforexpensecreate');
Route::get('getpaymentExpenseData', [PaymentforinvoiceController::class, 'getpaymentExpenseData'])->name('getpaymentExpenseData');

// Direct Expense Payment
Route::get('createdirectexpense', [PaymentforinvoiceController::class, 'createdirectexpense'])->name('createdirectexpense');
Route::get('getemployeebankdetails/{id}', [PaymentforinvoiceController::class, 'getemployeebankdetails'])->name('getemployeebankdetails');
Route::post('directexpensesave', [PaymentforinvoiceController::class, 'directexpensesave'])->name('directexpensesave');

// ---------------- Payment For Imprest ----------------
Route::get('paymentimprest', [ImprestController::class, 'paymentimprest'])->name('paymentimprest');
Route::get('paymentimprestData', [ImprestController::class, 'paymentimprestData'])->name('paymentimprestData');
Route::get('imprestpaymentcreate', [ImprestController::class, 'imprestpaymentcreate'])->name('imprestpaymentcreate');
Route::get('createdirectpayment', [ImprestController::class, 'imprestpaymentcreate'])->name('createdirectpayment');
Route::post('accountimprestsave', [ImprestController::class, 'accountimprestsave'])->name('accountimprestsave');

// ---------------- Payment For Travel Claim ----------------
Route::get('paymenttravelclaim', [TravelclaimController::class, 'approveindex'])->name('paymenttravelclaim');
Route::get('travelpaymentcreate', [TravelclaimController::class, 'travelpaymentcreate'])->name('travelpaymentcreate');
Route::get('directtravelclaimpayment', [TravelclaimController::class, 'travelpaymentcreate'])->name('directtravelclaimpayment');

// ---------------- Payment For ESI ----------------
Route::get('paymentesi', [ImprestController::class, 'paymentesi'])->name('paymentesi');
Route::get('paymentesiData', [ImprestController::class, 'paymentesiData'])->name('paymentesiData');
Route::get('esipaymentcreate', [ImprestController::class, 'esipaymentcreate'])->name('esipaymentcreate');
Route::get('createdirectesipayment', [ImprestController::class, 'esipaymentcreate'])->name('createdirectesipayment');

// ---------------- Payment For PF ----------------
Route::get('paymentpf', [ImprestController::class, 'paymentpf'])->name('paymentpf');
Route::get('paymentpfData', [ImprestController::class, 'paymentpfData'])->name('paymentpfData');
Route::get('pfpaymentcreate', [ImprestController::class, 'pfpaymentcreate'])->name('pfpaymentcreate');
Route::get('createdirectpfpayment', [ImprestController::class, 'createdirectpfpayment'])->name('createdirectpfpayment');

// ---------------- Investment Type ----------------
Route::get('investmenttype', [InvestmenttypeController::class, 'indextable'])->name('investmenttype');
Route::get('createinvestmenttype/{id}', [InvestmenttypeController::class, 'index'])->name('investmenttype.create');
Route::post('investmenttypesave', [InvestmenttypeController::class, 'store'])->name('investmenttype.save');
Route::get('investmenttypegrid', [InvestmenttypeController::class, 'investmenttypetaxgrid'])->name('investmenttype.grid');
Route::get('investmenttypedelete', [InvestmenttypeController::class, 'destroy'])->name('investmenttype.delete');

// ---------------- Payment for Sales Invoice Balance ----------------
Route::get('paymentforinvoiceblnce', [PaymentforinvoiceController::class, 'indexblnc'])->name('paymentforinvoiceblnce');
Route::get('getBlnceInvoicedetailsData', [PaymentforinvoiceController::class, 'getBlnceInvoicedetailsData'])->name('getBlnceInvoicedetailsData');
Route::get('paymentforinvoiceblncecreate/{id}', [PaymentforinvoiceController::class, 'createblnce'])->name('paymentforinvoiceblncecreate');
Route::post('paymentforinvoiceblncesave', [PaymentforinvoiceController::class, 'saveblnce'])->name('paymentforinvoiceblncesave');

// ---------------- Payment for Sales Return (RMA) ----------------
Route::get('paymentformcn', [PaymentforinvoiceController::class, 'indexsalret'])->name('paymentformcn');
Route::get('getsalesrtndetailsData', [PaymentforinvoiceController::class, 'getsalesrtndetailsData'])->name('getsalesrtndetailsData');
Route::get('paymentforsalesrtncreate/{id}', [PaymentforinvoiceController::class, 'createblncesale'])->name('paymentforsalesrtncreate');
Route::post('paymentforrmablncesave', [PaymentforinvoiceController::class, 'savermablnce'])->name('paymentforrmablncesave');

// ---------------- Advance Payment Status ----------------
Route::get('advancestatusview', [AdvancepaymentController::class, 'advancestatusview'])->name('advancestatusview');
Route::get('getadvancestatusviewData', [AdvancepaymentController::class, 'getadvancestatusviewData'])->name('getadvancestatusviewData');

// ---------------- Expense Payment Request ----------------
Route::get('expensepaymentrequest', [PaymentforinvoiceController::class, 'expensepaymentrequestindex'])->name('expensepaymentrequest');
Route::get('getexpenseRequestforpaymentData', [PaymentforinvoiceController::class, 'getexpenseRequestforpaymentData'])->name('getexpenseRequestforpaymentData');
Route::get('getexpensepaymentreq/{id}', [PaymentforinvoiceController::class, 'getexpensepaymentreq'])->name('getexpensepaymentreq');
Route::get('expensepaymentrequestapproval', [PaymentforinvoiceController::class, 'expensepaymentrequestindex'])->name('expensepaymentrequestapproval');

// ---------------- Payment for Bonus ----------------
Route::get('paymentforbonus', [PaymentforbonusController::class, 'employeebonuspayindex'])->name('paymentforbonus');
Route::get('employeebonusforpaygrid', [PaymentforbonusController::class, 'employeebonusforpaygrid'])->name('employeebonusforpaygrid');
Route::get('empbonuspaymentrequest', [PaymentforbonusController::class, 'empbonuspaymentrequest'])->name('empbonuspaymentrequest');
Route::get('getempbonusRequestforpaymentData', [PaymentforbonusController::class, 'getempbonusRequestforpaymentData'])->name('getempbonusRequestforpaymentData');
Route::get('getempbonuspaymentreq/{id}', [PaymentforbonusController::class, 'getempbonuspaymentreq'])->name('getempbonuspaymentreq');
Route::get('empbonuspaymentrequestapproval', [PaymentforbonusController::class, 'empbonuspaymentrequest'])->name('empbonuspaymentrequestapproval');
Route::get('paymentforemployeebonuscreate/{id}', [PaymentforbonusController::class, 'paymentforemployeebonuscreate'])->name('paymentforemployeebonuscreate');
Route::get('getempbonuspaymentreqapprove/{id}', [PaymentforbonusController::class, 'getempbonuspaymentreqapprove'])->name('getempbonuspaymentreqapprove');
Route::post('bonuspaysave', [PaymentforbonusController::class, 'bonuspaysave'])->name('bonuspaysave');

// ---------------- Payment for EL Encashment ----------------
Route::get('paymentrequestelencash', [PaymentforelencashmentController::class, 'empelencashrequest'])->name('paymentrequestelencash');
Route::get('elpaymentreqapproval', [PaymentforelencashmentController::class, 'elpaymentreqapproval'])->name('elpaymentreqapproval');
Route::get('requesteldata', [PaymentforelencashmentController::class, 'requesteldata'])->name('requesteldata');
Route::get('elencashreqapprove/{id}', [PaymentforelencashmentController::class, 'elencashreqapprove'])->name('elencashreqapprove');
Route::get('getelpaymentreq/{id}', [PaymentforelencashmentController::class, 'getelpaymentreq'])->name('getelpaymentreq');
Route::get('paymentrequestapproveelencash', [PaymentforelencashmentController::class, 'empelencashrequest'])->name('paymentrequestapproveelencash');
Route::get('paymentforelencashment', [PaymentforelencashmentController::class, 'paymentindex'])->name('paymentforelencashment');
Route::get('elencashpayment', [PaymentforelencashmentController::class, 'elencashpayment'])->name('elencashpayment');
Route::post('elpaysave', [PaymentforelencashmentController::class, 'elpaysave'])->name('elpaysave');
Route::get('paymentforelencashcreate/{id}', [PaymentforelencashmentController::class, 'paymentforelencashcreate'])->name('paymentforelencashcreate');

// ---------------- Payment for F&F ----------------
Route::get('paymentforfandf', [PaymentforfandfController::class, 'employeepayindex'])->name('paymentforfandf');
Route::get('employeefandfgrid', [PaymentforfandfController::class, 'employeefandfgrid'])->name('employeefandfgrid');
Route::get('paymentforfandfcreate/{id}', [PaymentforfandfController::class, 'paymentforfandfcreate'])->name('paymentforfandfcreate');
Route::post('fandfpaysave', [PaymentforfandfController::class, 'fandfpaysave'])->name('fandfpaysave');

// Payment Details
Route::get('paymentsindex', [PaymentdetailsController::class, 'index'])->name('paymentsindex');
Route::get('paymentsamedownloaddata', [PaymentdetailsController::class, 'paymentsamedownloaddata']);
Route::get('paymentotherdownloaddata', [PaymentdetailsController::class, 'paymentotherdownloaddata']);
Route::get('getPaymentcheque/{id}', [PaymentdetailsController::class, 'getPaymentcheque'])->name('getPaymentcheque');
Route::get('getPaymentvoucher/{id}', [PaymentdetailsController::class, 'getPaymentvoucher'])->name('getPaymentvoucher');
Route::get('paymentreference', [PaymentdetailsController::class, 'paymentreference'])->name('paymentreference');
Route::get('paymentreferenceupdate', [PaymentdetailsController::class, 'paymentreferenceupdate'])->name('paymentreferenceupdate');
Route::get('paymentreferenceview/{id}', [PaymentdetailsController::class, 'view'])->name('paymentreferenceview');
Route::get('paymentchequecancellation', [PaymentdetailsController::class, 'chequecancellation'])->name('paymentchequecancellation');
Route::get('getPaycanclconfirm/{id}', [PaymentdetailsController::class, 'getpaymentcanclconfirm'])->name('getPaycanclconfirm');
Route::get('directpaymentcreate', [PaymentdetailsController::class, 'directpaymentcreate'])->name('directpaymentcreate');
Route::post('directpaymentsave', [PaymentdetailsController::class, 'directpaymentsave'])->name('directpaymentsave');
Route::get('paymentadvice/{id}', [PaymentdetailsController::class, 'paymentadvicedata'])->name('paymentadvice');

// payment Details
Route::get('paymentdetailindex', [PaymentdetailsController::class, 'paymentindex'])->name('paymentdetailindex');
Route::get('getpaymentdetail', [PaymentdetailsController::class, 'getpaymentdetail'])->name('getpaymentdetail');

// payments edit 

Route::get('paymentsedit/{id}', [PaymentdetailsController::class, 'editpayment'])->name('paymentsedit');
Route::post('updatepayment', [PaymentdetailsController::class, 'updatepayment'])->name('updatepayment');


?>