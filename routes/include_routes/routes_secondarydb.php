<?php
use App\Http\Controllers\SecondarydataController;
use App\Http\Controllers\SecondarydatauploadController;
use App\Http\Controllers\PrmyscdyuploadController;
use App\Http\Controllers\PrimarydatauploadController;
use App\Http\Controllers\SecondarysalesreportController;
use App\Http\Controllers\SecondarysalesandtargetController;
use App\Http\Controllers\PrimarysalesandtargetController;
use App\Http\Controllers\PrimarysaleratioController;
use App\Http\Controllers\PrimarycostratioController;
use App\Http\Controllers\PrimarysalesreturnController;
use App\Http\Controllers\BusinessmisController;
use App\Http\Controllers\AsmmisreportController;
use App\Http\Controllers\MisemployeedetailController;
use App\Http\Controllers\GlobalmisController;
use App\Http\Controllers\ProductivitymisController;
use App\Http\Controllers\TargetmisController;
use App\Http\Controllers\MisviewersController;
use App\Http\Controllers\BestofmonthmisController;
use App\Http\Controllers\DoctorcallavguploadController;
use App\Http\Controllers\MismanagerchangeController;

// LARAVEL 8 ROUTE
// SALES DASHBOARD

Route::get('/ssdb_dashboard', [SecondarydataController::class, 'ssdb_dashboard'])->name('ssdb_dashboard');
Route::get('dashboard_secondarydata', [SecondarydataController::class, 'dashboard_secondarydata']);
// secondarydataupload

Route::get('secondarydataupload', [SecondarydatauploadController::class, 'index'])->name('secondarydataupload');
Route::get('getsecondarydatauploadData', [SecondarydatauploadController::class, 'getsecondarydatauploadData'])->name('getsecondarydatauploadData');
Route::get('getsecondarydatavalidate', [SecondarydatauploadController::class, 'getsecondarydatavalidate'])->name('getsecondarydatavalidate');
Route::post('secondarydatauploaddata', [SecondarydatauploadController::class, 'secondarydataUploadexcel']);
Route::get('secondarydatauploadedit/{id}', [SecondarydatauploadController::class, 'create']);
Route::post('secondarydatauploadsave', [SecondarydatauploadController::class, 'save'])->name('secondarydatauploadsave');
// prmyscdyupload
Route::get('prmyscdyupload', [PrmyscdyuploadController::class, 'index'])->name('prmyscdyupload');
Route::get('getprmyscdyuploadData', [PrmyscdyuploadController::class, 'getprmyscdyuploadData'])->name('getprmyscdyuploadData');
Route::get('getprmyscdydatavalidate', [PrmyscdyuploadController::class, 'getprmyscdydatavalidate'])->name('getprmyscdydatavalidate');
Route::post('prmyscdyuploaddata', [PrmyscdyuploadController::class, 'prmyscdyuploadexcel']);
Route::get('prmyscdyuploadedit/{id}', [PrmyscdyuploadController::class, 'prmyscdyuploadcreate']);
Route::post('prmyscdyuploadsave', [PrmyscdyuploadController::class, 'prmyscdyuploadsave'])->name('prmyscdyuploadsave');

// Primary Data Upload
Route::get('primarydataupload', [PrimarydatauploadController::class, 'index'])->name('primarydataupload');
Route::get('getprimarydatauploadData', [PrimarydatauploadController::class, 'getprimarydatauploadData'])->name('getprimarydatauploadData');
Route::get('getprimarydatavalidate', [PrimarydatauploadController::class, 'getprimarydatavalidate'])->name('getprimarydatavalidate');
Route::post('primarydatauploaddata', [PrimarydatauploadController::class, 'primarydataUploadexcel']);
Route::get('primarydatauploadedit/{id}', [PrimarydatauploadController::class, 'create']);
Route::post('primarydatauploadsave', [PrimarydatauploadController::class, 'save'])->name('primarydatauploadsave');
// Secondary Sales Report - All India One Glance
Route::get('secondarysalesreport', [SecondarysalesreportController::class, 'Monthwise'])->name('secondarysalesreport');
Route::get('zonesalesreport', [SecondarysalesreportController::class, 'Zonewise'])->name('zonesalesreport');
Route::get('zoneproductreport', [SecondarysalesreportController::class, 'ZoneProductwise'])->name('zoneproductreport');
Route::get('productsalesreport', [SecondarysalesreportController::class, 'Productwise'])->name('productsalesreport');
Route::get('statesalesreport', [SecondarysalesreportController::class, 'Statewise'])->name('statesalesreport');
Route::get('lastsalereport', [SecondarysalesreportController::class, 'LastSale'])->name('lastsalereport');
// Secondary Sales Report – Sales and Target
Route::get('salesandtargetmonthindex', [SecondarysalesandtargetController::class, 'Index'])->name('salesandtargetmonthindex');
Route::get('salesandtargetmonth', [SecondarysalesandtargetController::class, 'MonthWise'])->name('salesandtargetmonth');
Route::get('salesandtargetregion', [SecondarysalesandtargetController::class, 'RegionWise'])->name('salesandtarget');
Route::get('salesandtargetzone', [SecondarysalesandtargetController::class, 'ZoneWise'])->name('salesandtargetzone');
Route::get('salesandtargetstate', [SecondarysalesandtargetController::class, 'StateWise'])->name('salesandtargetstate');
Route::get('salesandtargetdistributor', [SecondarysalesandtargetController::class, 'DistributorWise'])->name('salesandtargetdistributor');
Route::get('salesandtargethq', [SecondarysalesandtargetController::class, 'HeadquatWise'])->name('salesandtargethq');
Route::get('salesandtargetperson', [SecondarysalesandtargetController::class, 'PersonWise'])->name('salesandtargetperson');
Route::get('salesandtargetproduct', [SecondarysalesandtargetController::class, 'ProductWise'])->name('salesandtargetproduct');
Route::get('salesandtargetteam', [SecondarysalesandtargetController::class, 'Teamtarget'])->name('salesandtargetteam');
Route::get('salesandtargetperclobal', [SecondarysalesandtargetController::class, 'Personcolbal'])->name('salesandtargetperclobal');
Route::get('salesandtargetpayout', [SecondarysalesandtargetController::class, 'Paymentout'])->name('salesandtargetpayout');
Route::get('salesandtargetmtstrend', [SecondarysalesandtargetController::class, 'Salestrend'])->name('salesandtargetmtstrend');
Route::get('salesandtargetclobal', [SecondarysalesandtargetController::class, 'Distcolbal'])->name('salesandtargetclobal');
Route::get('salesandtargetteammonth', [SecondarysalesandtargetController::class, 'Teamtargetmonth'])->name('salesandtargetteammonth');
// Primary Sales Report – Sales and Target
Route::get('primarysalesandtargetindex', [PrimarysalesandtargetController::class, 'index'])->name('primarysalesandtargetindex');
Route::get('primarysalesandtargetregion', [PrimarysalesandtargetController::class, 'RegionWise'])->name('primarysalesandtargetregion');
Route::get('primarysalesandtarget', [PrimarysalesandtargetController::class, 'ZoneWise'])->name('primarysalesandtarget');
Route::get('primarysalesandtargetstate', [PrimarysalesandtargetController::class, 'StateWise'])->name('primarysalesandtargetstate');
Route::get('primarysalesandtargetarea', [PrimarysalesandtargetController::class, 'AreaWise'])->name('primarysalesandtargetarea');
Route::get('primarysalesdistributor', [PrimarysalesandtargetController::class, 'DistributorWise'])->name('primarysalesdistributor');
Route::get('primarysalesandtargetproduct', [PrimarysalesandtargetController::class, 'ProductWise'])->name('primarysalesandtargetproduct');
Route::get('primarysalesdistributorpropack', [PrimarysalesandtargetController::class, 'ProductpackWise'])->name('primarysalesdistributorpropack');
Route::get('primarysalesplanandshort', [PrimarysalesandtargetController::class, 'PlanandShort'])->name('primarysalesplanandshort');
Route::get('primarysalesmonachive', [PrimarysalesandtargetController::class, 'Monthachivement'])->name('primarysalesmonachive');
Route::get('primarysaleszonepro', [PrimarysalesandtargetController::class, 'Zoneproduct'])->name('primarysaleszonepro');
Route::get('primarysalesstockdts', [PrimarysalesandtargetController::class, 'StateMonthWise'])->name('primarysalesstockdts');
Route::get('primarysalesdistwise', [PrimarysalesandtargetController::class, 'DistributormonthWise'])->name('primarysalesdistwise');
// Primary Sales Report — Sample to Sale Ratio
Route::get('primarysaleratioindex', [PrimarysaleratioController::class, 'Index'])->name('primarysaleratioindex');
Route::get('primarysaleratio', [PrimarysaleratioController::class, 'MonzoneWise'])->name('primarysaleratio');
Route::get('primarysaleratioregion', [PrimarysaleratioController::class, 'RegionWise'])->name('primarysaleratioregion');
Route::get('primarysaleratiostate', [PrimarysaleratioController::class, 'StateWise'])->name('primarysaleratiostate');
Route::get('primarysaleregratio', [PrimarysaleratioController::class, 'Regionratio'])->name('primarysaleregratio');
Route::get('primarysalestaratio', [PrimarysaleratioController::class, 'Stateratio'])->name('primarysalestaratio');
Route::get('primarysaleratiopro', [PrimarysaleratioController::class, 'ProductWise'])->name('primarysaleratiopro');
Route::get('primarysalezoneratio', [PrimarysaleratioController::class, 'Zoneratio'])->name('primarysalezoneratio');
Route::get('primarymonthsample', [PrimarysaleratioController::class, 'Monthsample'])->name('primarymonthsample');
Route::get('primaryzonesample', [PrimarysaleratioController::class, 'Zonesample'])->name('primaryzonesample');
Route::get('primaryregionsample', [PrimarysaleratioController::class, 'Regionsample'])->name('primaryregionsample');
Route::get('primarystatesample', [PrimarysaleratioController::class, 'Statesample'])->name('primarystatesample');
Route::get('primarypersonsample', [PrimarysaleratioController::class, 'Personsample'])->name('primarypersonsample');
// primary sales report  --- COST TO SALE RATIO
Route::get('primarycostsaleratioindex', [PrimarycostratioController::class, 'Index'])->name('primarycostsaleratioindex');
Route::get('primarycostsaleratio', [PrimarycostratioController::class, 'MonthWise'])->name('primarycostsaleratio');
Route::get('primarycostsaleratiozone', [PrimarycostratioController::class, 'ZoneWise'])->name('primarycostsaleratiozone');
Route::get('primarycostsaleratioregion', [PrimarycostratioController::class, 'RegionWise'])->name('primarycostsaleratioregion');
Route::get('primarycostsaleratiostate', [PrimarycostratioController::class, 'StateWise'])->name('primarycostsaleratiostate');
Route::get('primarycostratiodist', [PrimarycostratioController::class, 'Costdistribution'])->name('primarycostratiodist');
Route::get('primarycostratiodistsaletrend', [PrimarycostratioController::class, 'Saletrend'])->name('primarycostratiodistsaletrend');
Route::get('primarycostratiomanpower', [PrimarycostratioController::class, 'Manpower'])->name('primarycostratiomanpower');
Route::get('primarycostratiomanpowerstate', [PrimarycostratioController::class, 'ManpowerState'])->name('primarycostratiomanpowerstate');
// PRIMARY SALES RETURN
Route::get('primarysalesreturnindex', [PrimarysalesreturnController::class, 'Index'])->name('primarysalesreturnindex');
Route::get('primarysalesreturn', [PrimarysalesreturnController::class, 'MonthWise'])->name('primarysalesreturn');
Route::get('primarysalesreturnregion', [PrimarysalesreturnController::class, 'RegionWise'])->name('primarysalesreturnregion');
Route::get('primarysalesreturnstate', [PrimarysalesreturnController::class, 'StateWise'])->name('primarysalesreturnstate');
Route::get('primarysalesreturnproduct', [PrimarysalesreturnController::class, 'ProductWise'])->name('primarysalesreturnproduct');
Route::get('primarysalesreturnzoneratio', [PrimarysalesreturnController::class, 'Zoneratio'])->name('primarysalesreturnzoneratio');
Route::get('primarysalesreturnregionratio', [PrimarysalesreturnController::class, 'Regionratio'])->name('primarysalesreturnregionratio');
Route::get('primarysalesreturnstateratio', [PrimarysalesreturnController::class, 'Stateratio'])->name('primarysalesreturnstateratio');
Route::get('primarysalesreturnpermonth', [PrimarysalesreturnController::class, 'Monthreturn'])->name('primarysalesreturnpermonth');
Route::get('primarysalesreturperzone', [PrimarysalesreturnController::class, 'Zonereturn'])->name('primarysalesreturperzone');
Route::get('primarysalesreturnsperregion', [PrimarysalesreturnController::class, 'Regionreturn'])->name('primarysalesreturnsperregion');
Route::get('primarysalesreturperstate', [PrimarysalesreturnController::class, 'Statereturn'])->name('primarysalesreturperstate');
Route::get('primarysalesreturnsperperson', [PrimarysalesreturnController::class, 'Personreturn'])->name('primarysalesreturnsperperson');
// BUSSINESS MIS
Route::get('primarybusinessmis', [BusinessmisController::class, 'ZoneWise'])->name('primarybusinessmis');
Route::get('primarybusinessmisstate', [BusinessmisController::class, 'StateWise'])->name('primarybusinessmisstate');
Route::get('primarybusinessmisperson', [BusinessmisController::class, 'PersonWise'])->name('primarybusinessmisperson');
// ASM MIS
Route::get('misreportindex', [AsmmisreportController::class, 'Index'])->name('misreportindex');
Route::get('misreport', [AsmmisreportController::class, 'Personwise'])->name('misreport');
Route::get('misreportsaleandpurc', [AsmmisreportController::class, 'Salestrend'])->name('misreportsaleandpurc');
Route::get('misreporthq', [AsmmisreportController::class, 'HeadquatWise'])->name('misreporthq');
Route::get('misreporthqpro', [AsmmisreportController::class, 'HqProduct'])->name('misreporthqpro');
Route::get('misreporthqclobal', [AsmmisreportController::class, 'Hqclobal'])->name('misreporthqclobal');
Route::get('misreportcolbal', [AsmmisreportController::class, 'ClosingBal'])->name('misreportcolbal');
Route::get('misreportpayout', [AsmmisreportController::class, 'Paymentout'])->name('misreportpayout');
Route::get('misreportprohqsale', [AsmmisreportController::class, 'Prosalehq'])->name('misreportprohqsale');
Route::get('overallprowise', [AsmmisreportController::class, 'Overallproduct'])->name('overallprowise');
Route::get('prosearchrpt', [AsmmisreportController::class, 'Productsearch'])->name('prosearchrpt');
Route::get('asmpersonproduction', [AsmmisreportController::class, 'Personproductivity'])->name('asmpersonproduction');
Route::get('salesmisreportsdetil', [MisemployeedetailController::class, 'index'])->name('salesmisreportsdetil');
Route::get('salesmisreportsdetildata', [MisemployeedetailController::class, 'salesmisreportsdetildata'])->name('salesmisreportsdetildata');
Route::get('createmaremployeedetail/{id}', [MisemployeedetailController::class, 'Create'])->name('createmaremployeedetail');
Route::post('salesmisreportsdetilsave', [MisemployeedetailController::class, 'save'])->name('salesmisreportsdetilsave');
Route::get('salesmisreportsdetildelete/{id}', [MisemployeedetailController::class, 'Delete'])->name('salesmisreportsdetildelete');
Route::get('salesandtargetmonthproductivity', [SecondarysalesandtargetController::class, 'Monthproductivity'])->name('salesandtargetmonthproductivity');
// GLOBAL MIS
Route::get('globalmisreportindex', [GlobalmisController::class, 'Index'])->name('globalmisreportindex');
Route::get('globalmisreport', [GlobalmisController::class, 'Monthwise'])->name('globalmisreport');
Route::get('globalmisreportzone', [GlobalmisController::class, 'Zonewise'])->name('globalmisreportzone');
Route::get('globalmisreportregion', [GlobalmisController::class, 'Regionwise'])->name('globalmisreportregion');
Route::get('globalmisreportdistributor', [GlobalmisController::class, 'Distributorwise'])->name('globalmisreportdistributor');
Route::get('globalmisproduct', [GlobalmisController::class, 'Productwise'])->name('globalmisproduct');
Route::get('globalmisdistrend', [GlobalmisController::class, 'Distrend'])->name('globalmisdistrend');
Route::get('globalmisreportsaletrend', [GlobalmisController::class, 'Salestrend'])->name('globalmisreportsaletrend');

// TARGET MIS
Route::get('productivitymis', [ProductivitymisController::class, 'Salestrend'])->name('productivitymis');
Route::get('personwiseproductivity', [ProductivitymisController::class, 'Personproductivity'])->name('personwiseproductivity');
Route::get('productivityhqwise', [ProductivitymisController::class, 'HeadquatWise'])->name('productivityhqwise');
Route::get('targetmis', [TargetmisController::class, 'index'])->name('targetmis');
Route::get('gettargetData', [TargetmisController::class, 'gettargetData'])->name('gettargetData');
Route::post('targetmissave', [TargetmisController::class, 'save'])->name('targetmissave');
Route::get('salesmisviewers', [MisviewersController::class, 'index'])->name('salesmisviewers');

// Best of month MIS
Route::get('bestofmonthmis', [BestofmonthmisController::class, 'index'])->name('bestofmonthmis');
Route::get('topsellingmis', [BestofmonthmisController::class, 'topsellingmis'])->name('topsellingmis');

// doctor call averge upload

Route::get('docavgupload', [DoctorcallavguploadController::class, 'Index'])->name('docavgupload');
Route::get('docavguploaddata', [DoctorcallavguploadController::class, 'docavguploaddata'])->name('docavguploaddata');
Route::post('docuploaddatasave', [DoctorcallavguploadController::class, 'docUploadexcel']);

// manager update

Route::get('primanagerchange', [MismanagerchangeController::class, 'index1'])->name('primanagerchange');
Route::get('secmanagerchange', [MismanagerchangeController::class, 'index'])->name('secmanagerchange');
Route::post('secmanagersave', [MismanagerchangeController::class, 'save'])->name('secmanagersave');