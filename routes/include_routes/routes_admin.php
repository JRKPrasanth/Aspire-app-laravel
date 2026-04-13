<?php
use App\Http\Controllers\OrganizationController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\GroupmenuaccessController;
use App\Http\Controllers\UseraccessController;
use App\Http\Controllers\GroupaccessController;
use App\Http\Controllers\DashboardaccessController;
use App\Http\Controllers\OtherDashboardaccessController;
use App\Http\Controllers\CreateuserController;
use App\Http\Controllers\MenuarrangementController;
use App\Http\Controllers\AdminreportController;
use App\Http\Controllers\LookuphdrController;
use App\Http\Controllers\ProductsettingController;
use App\Http\Controllers\ApprovalsettingsController;
use App\Http\Controllers\FloatingpointsettingsController;
use App\Http\Controllers\DateformatsController;
use App\Http\Controllers\TimeformatsController;
use App\Http\Controllers\CityController;
use App\Http\Controllers\countryController;
use App\Http\Controllers\StateController;
use App\Http\Controllers\AreaController;
use App\Http\Controllers\BatchwisecostrptController;
use App\Http\Controllers\AppviewersreportController;
use App\Http\Controllers\DispatchController;
use App\Http\Controllers\TaskmanagerController;
use App\Http\Controllers\TaskcategoryController;
use App\Http\Controllers\TasksubcategoryController;
use App\Http\Controllers\toolsmenuconfighdrController;
use App\Http\Controllers\CreatemenuController;
use App\Http\Controllers\CreatebuttonController;

// LARAVEL 8 NEW ROUTE

//ORGANIZATION MENU
Route::get('/getOrganizationData', [OrganizationController::class, 'getOrganizationData'])->name('getOrganizationData');
Route::post('/organizationsave', [OrganizationController::class, 'organizationsave'])->name('organizationsave');
Route::get('organizationdelete/{id}', [OrganizationController::class, 'organizationdelete'])->name('organizationdelete');
Route::get('orgcheckname', [OrganizationController::class, 'orgcheckname'])->name('orgcheckname');
Route::get('organization', [OrganizationController::class, 'index'])->name('organization');
/* COMPANY MENU */
Route::get('company', [CompanyController::class, 'index'])->name('company');
Route::get('/getCompanyData', [CompanyController::class, 'getCompanyData'])->name('getCompanyData');
Route::get('companyform/{id}', [CompanyController::class, 'companyform'])->name('companyform');
Route::get('companydelete/{id}', [CompanyController::class, 'companydelete'])->name('companydelete');
Route::get('companyview/{id}', [CompanyController::class, 'companyview'])->name('companyview');
Route::post('/companysave', [CompanyController::class, 'companysave'])->name('companysave');
Route::get('companycheckname', [CompanyController::class, 'companycheckname'])->name('companycheckname');
// LOCATION
Route::get('location', [LocationController::class, 'index'])->name('location');
Route::get('getlocationData', [LocationController::class, 'getlocationData'])->name('getlocationData');
Route::get('locationdelete/{id}', [LocationController::class, 'locationdelete'])->name('locationdelete');
Route::post('locationsave', [LocationController::class, 'locationsave'])->name('locationsave');
Route::get('loccheckname', [LocationController::class, 'loccheckname'])->name('loccheckname');
// COMPANY MENU ACCESS
Route::get('companyaccess', [GroupmenuaccessController::class, 'indexcompany'])->name('companyaccess');
Route::get('createcompanyaccess', [GroupmenuaccessController::class, 'createcompany'])->name('createcompanyaccess');
Route::post('companyaccesssave',  [GroupmenuaccessController::class, 'companyaccesssave'])->name('companyaccesssave');
Route::get('companyaccessedit/{id}', [GroupmenuaccessController::class, 'createcompany']) ->name('companyaccess');
Route::get('companyaccess/{id}',  [GroupmenuaccessController::class, 'menudata'])->name('companyaccess');
Route::get('buttonnameusers/{id}', [GroupmenuaccessController::class, 'buttonnameuser'])->name('companyaccess');
// GROUP MENU ACCESS
Route::get('groupmenuaccess',[GroupmenuaccessController::class, 'index'])->name('groupmenuaccess');
Route::get('groupaccessedit/{id}', [GroupmenuaccessController::class, 'create'])->name('groupaccess.edit');
Route::get('creategroupaccess', [GroupmenuaccessController::class, 'create']) ->name('creategroupaccess');
Route::get('subheadmenu/{id}', [GroupmenuaccessController::class, 'subheadmenu']) ->name('groupaccess.subheadmenu');
Route::post('groupaccess',[GroupmenuaccessController::class, 'save']) ->name('groupaccess.save');
// USER MENU ACCESS
Route::get('useraccess',          [UseraccessController::class, 'index'])->name('useraccess');
Route::get('createuseraccess',    [UseraccessController::class, 'create'])->name('createuseraccess');
Route::get('groupaccess/{id}',    [UseraccessController::class, 'menudata'])->name('useraccess.group.menudata');
Route::get('subheadname/{id}/{ids}', [UseraccessController::class, 'subheaddata']) ->name('useraccess.subheaddata');
Route::get('buttonname/{id}',     [UseraccessController::class, 'buttonname'])->name('useraccess.buttonname');
Route::get('buttonnameuser/{id}', [UseraccessController::class, 'buttonnameuser']) ->name('useraccess.buttonnameuser');
Route::get('useraccessedit/{id}', [UseraccessController::class, 'create'])->name('useraccess.edit');
Route::post('saves', [UseraccessController::class, 'save'])->name('useraccess.save');
Route::get('/getCompanyaccessData', [GroupmenuaccessController::class, 'getCompanyaccessData'])->name('getCompanyaccessData');
Route::get('/getGroupaccessData', [GroupmenuaccessController::class, 'getGroupaccessData'])->name('getGroupaccessData');
Route::get('/getUseraccessData', [UseraccessController::class, 'getUseraccessData'])->name('getUseraccessData');
Route::get('GroupsData', [GroupaccessController::class, 'GroupsData'])->name('GroupsData');
// GROUP
Route::get('group', [GroupaccessController::class, 'index'])->name('group.index');
Route::get('/groupname/checkname', [GroupaccessController::class, 'getCheckname'])->name('group.checkname');
Route::post('/groupaccess/save', [GroupaccessController::class, 'save'])->name('group.save');
Route::get('/groupaccess/edit', [GroupaccessController::class, 'show'])->name('group.edit');
Route::get('/groupaccess/delete/{id}', [GroupaccessController::class, 'remove']) ->name('group.delete');
Route::get('getgroup', [GroupaccessController::class, 'getgroupgriddata'])->name('group.getgriddata');
//PRODUCT ACCESS
Route::get('productaccess', [GroupmenuaccessController::class, 'indexproduct'])->name('productaccess');
Route::get('ProductaccessData', [GroupmenuaccessController::class, 'ProductaccessData'])->name('ProductaccessData');
Route::get('createproductaccess', [GroupmenuaccessController::class, 'createproductaccess'])->name('createproductaccess');
Route::post('productaccesssave', [GroupmenuaccessController::class, 'productaccesssave'])->name('productaccesssave');
Route::get('productaccessedit/{id}', [GroupmenuaccessController::class, 'createproductaccess']) ->name('productaccessedit');
// DASHBOARD ACCESS
Route::get('dashboardaccess', [DashboardaccessController::class, 'index'])->name('dashboardaccess');
Route::get('DashboardData', [DashboardaccessController::class, 'DashboardData'])->name('DashboardData');
Route::get('createdashboardaccess/{id}', [DashboardaccessController::class, 'create'])->name('createdashboardaccess');
Route::post('dashboardaccesssave', [DashboardaccessController::class, 'save'])->name('createdashboardaccess');
// OTHER DASHBOARD ACCESS
Route::get('otherdashboardaccess', [OtherDashboardaccessController::class, 'index'])->name('otherdashboardaccess');
Route::get('otherDashboardData', [OtherDashboardaccessController::class, 'otherDashboardData'])->name('otherDashboardData');
Route::get('createotherdashboardaccess/{id}', [OtherDashboardaccessController::class, 'create'])->name('createotherdashboardaccess');
Route::post('otherdashboardaccesssave', [OtherDashboardaccessController::class, 'save'])->name('otherdashboardaccesssave');
// CREATE USER
Route::get('user', [CreateuserController::class, 'index'])->name('user');
Route::get('createuser', [CreateuserController::class, 'create'])->name('createuser');
Route::post('usersave', [CreateuserController::class, 'save']) ->name('usersave');
Route::get('Useraccessdata', [CreateuserController::class, 'userdata'])->name('Useraccessdata');
Route::get('userprofile/{id}', [CreateuserController::class, 'create'])->name('userprofile');
Route::get('useredit/{id}', [CreateuserController::class, 'create'])->name('useredit');
Route::get('userview/{id}', [CreateuserController::class, 'show']) ->name('userview');
// MENU ARRANGEMENT
Route::get('menuarrangement', [MenuarrangementController::class, 'index'])->name('menuarrangement');
Route::post('menuarrange', [MenuarrangementController::class, 'create']) ->name('menuarrange');
//USER PERMISSION REPORT
Route::get('userpermissionrpt', [AdminreportController::class, 'index'])->name('userpermissionrpt');
Route::get('getuserpermissionrptdata', [AdminreportController::class, 'getuserpermissionrptdata']) ->name('getuserpermissionrptdata');
Route::get('permissionreport', [UseraccessController::class, 'permissionreport'])->name('permissionreport');
Route::get('permissionreportdata', [UseraccessController::class, 'permissionreportdata']) ->name('permissionreportdata');
//LOOK UPS
Route::get('lookup', [LookuphdrController::class, 'index'])->name('lookup');
Route::post('getlookupdata', [LookuphdrController::class, 'lookupdata'])->name('getlookupdata');
Route::get('lookcreate', [LookuphdrController::class, 'create'])->name('lookcreate');
Route::get('lookupcreate/{id}', [LookuphdrController::class, 'create'])->name('lookcreate');
Route::post('lookupsave', [LookuphdrController::class, 'save'])->name('lookupsave');
Route::get('lookupsaveview/{id}', [LookuphdrController::class, 'getShow'])->name('lookupsaveview');
Route::get('lookuptblData', [LookuphdrController::class, 'lookuptblData'])->name('lookuptblData');
//PRODUCT SETTINGS
Route::get('productsetting', [ProductsettingController::class, 'index'])->name('productsetting');
Route::post('productsettingsave', [ProductsettingController::class, 'productsettingsave'])->name('productsetting');
Route::get('getproductsetting', [ProductsettingController::class, 'getproductsetting'])->name('productsetting');
//APPPROVAL SETTINGS
Route::get('approvalsettings', [ApprovalsettingsController::class, 'index'])->name('approvalsettings');
Route::get('approvalsettingscreate', [ApprovalsettingsController::class, 'create'])->name('approvalsettingscreate');
Route::get('approvalsettingsedit/{id}', [ApprovalsettingsController::class, 'create'])->name('approvalsettingscreate');
Route::post('approvalsettingssave', [ApprovalsettingsController::class, 'save'])->name('approvalsettingssave');
Route::get('approvalsettingsview/{id}', [ApprovalsettingsController::class, 'show'])->name('approvalsettingsview');
Route::get('getApprovalsettingsData', [ApprovalsettingsController::class, 'getApprovalsettingsData'])->name('getApprovalsettingsData');
Route::get('approvalsettingschk', [ApprovalsettingsController::class, 'approvalsettingschk'])->name('approvalsettingschk');
// DECIMAL POINT SETTINGS
Route::get('floatingpointsettings', [FloatingpointsettingsController::class, 'index'])->name('floatingpointsettings');
Route::post('fpointsave', [FloatingpointsettingsController::class, 'save'])->name('fpointsave');
Route::get('dateformate', [Controller::class, 'dateform'])->name('dateformate');
// DATE FORMAT SETTINGS
Route::get('dateformatssettings', [DateformatsController::class, 'index'])->name('dateformatssettings');
Route::get('dateformatssettingscreate', [DateformatsController::class, 'create'])->name('dateformatssettingscreate');
Route::get('dateformatssettingsedit/{id}', [DateformatsController::class, 'create'])->name('dateformatssettingscreate');
Route::get('dateformatssettingsdelete/{id}', [DateformatsController::class, 'dateformatssettingsdelete'])->name('dateformatssettingsdelete');
Route::get('dateformatssettingssave', [DateformatsController::class, 'save'])->name('dateformatssettingssave');
Route::get('getDateformatsData', [DateformatsController::class, 'getDateformatsData'])->name('getDateformatsData');
Route::get('dateformatcheck', [DateformatsController::class, 'dateformatcheck'])->name('dateformatcheck');
// TIME FORMAT SETTINGS
Route::get('timeformats', [TimeformatsController::class, 'index'])->name('timeformats');
Route::get('timeformatscreate', [TimeformatsController::class, 'create'])->name('timeformatscreate');
Route::get('timeformatsedit/{id}', [TimeformatsController::class, 'create'])->name('dateformatssettingscreate');
Route::get('timeformatcheck', [TimeformatsController::class, 'timeformatcheck'])->name('timeformatcheck');
Route::get('gettimeformatsData', [TimeformatsController::class, 'gettimeformatsData'])->name('gettimeformatsData');
// CITY
Route::get('city', [CityController::class, 'create'])->name('city');
Route::post('citysave', [CityController::class, 'save'])->name('citysave');
Route::get('getcityData', [CityController::class, 'getcityData'])->name('getcityData');
Route::get('citydelete/{id}', [CityController::class, 'destroy'])->name('citydelete');
//COUNTRY
Route::get('country', [countryController::class, 'create'])->name('country');
Route::post('countrysave', [countryController::class, 'save'])->name('countrysave');
Route::get('getcountryData', [countryController::class, 'getcountryData'])->name('getcountryData');
Route::get('countrydelete/{id}', [countryController::class, 'destroy'])->name('countrydelete');
// STATE
Route::get('state', [StateController::class, 'create'])->name('state');
Route::post('statedatasave', [StateController::class, 'save'])->name('statedatasave');
Route::get('getstatedata', [StateController::class, 'getstatedata'])->name('getstatedata');
Route::get('statedelete/{id}', [StateController::class, 'destroy'])->name('statedelete');
// AREA
Route::get('area', [AreaController::class, 'create'])->name('area');
Route::post('areasave', [AreaController::class, 'save'])->name('areassave');
Route::get('areagrddataedit', [AreaController::class, 'areagrddataedit'])->name('areagrddataedit');
Route::get('areadelete/{id}', [AreaController::class, 'destroy'])->name('areadelete');
Route::get('getareagrid', [AreaController::class, 'getareagrid'])->name('getareagrid');
 // BALANCE SHEET DEATIL REPORT
Route::get('balancesheetrptdetails', [BatchwisecostrptController::class, 'balancesheetrptindex'])->name('balancesheetrptdetails');
Route::get('getbalancesheetrptData', [BatchwisecostrptController::class, 'getbalancesheetrptData'])->name('getbalancesheetrptData');
//APPLICATION VIEWERS REPORT
Route::get('appviewerreport', [AppviewersreportController::class, 'Index'])->name('appviewerreport');
Route::get('getviewdata', [AppviewersreportController::class, 'getviewdata'])->name('getviewdata');
Route::get('userlastlogin', [AppviewersreportController::class, 'userlastlogin'])->name('userlastlogin');
Route::get('GetloginData', [AppviewersreportController::class, 'GetloginData'])->name('GetloginData');
// MONTH END FREEZE
Route::get('monthendfreeze', [DispatchController::class, 'monthendfreeze'])->name('monthendfreeze');
Route::get('getmonthendfreezegriddata', [DispatchController::class, 'getmonthendfreezegriddata'])->name('getmonthendfreezegriddata');
Route::post('monthendfreezesave/save', [DispatchController::class, 'monthendfreezesave'])->name('monthendfreeze');
Route::get('/monthendfreeze/delete/{id}', [DispatchController::class, 'monthendfreezedestroy'])->name('monthendfreeze');
//TASK MANAGER(WEB OPS)
Route::get('taskmanager', [TaskmanagerController::class, 'index'])->name('taskmanager');
Route::get('gettaskmanData', [TaskmanagerController::class, 'gettaskmanData'])->name('gettaskmanData');
Route::get('taskmanagercreate/{id}', [TaskmanagerController::class, 'create'])->name('taskmanagercreate');
Route::post('taskmanagersave', [TaskmanagerController::class, 'save'])->name('taskmanagersave');
Route::get('taskmanageredit/{id}', [TaskmanagerController::class, 'create'])->name('taskmanageredit');
Route::get('taskmanagerview/{id}', [TaskmanagerController::class, 'show'])->name('taskmanagerview');
Route::get('taskmanagerdelete/{id}', [TaskmanagerController::class, 'destroy'])->name('taskmanagerdelete');
Route::get('taskmanagerapproval', [TaskmanagerController::class, 'index'])->name('taskmanagerapproval');
Route::get('taskmanagerfinalapproval', [TaskmanagerController::class, 'index'])->name('taskmanagerfinalapproval');
Route::get('taskmanagerupdate', [TaskmanagerController::class, 'index'])->name('taskmanagerupdate');
Route::get('leaddepartment', [TaskmanagerController::class, 'leaddepartment'])->name('leaddepartment');
Route::get('taskmanagerdashboard', [TaskmanagerController::class, 'dashboardindex'])->name('taskmanagerdashboard');
// TASK CATEGORY
Route::get('taskcategorycheckname', [TaskcategoryController::class, 'getCheckname']);
Route::get('taskcategory', [TaskcategoryController::class, 'create'])->name('taskcategorycreate');
Route::post('taskcategorysave', [TaskcategoryController::class, 'save'])->name('taskcategorysave');
Route::get('taskcategoryedit/{id}/{type}', [TaskcategoryController::class, 'create'])->name('taskcategoryedit');
Route::get('taskcategoryview/{id}', [TaskcategoryController::class, 'view'])->name('taskcategoryview');
Route::get('taskcategorydelete/{id}', [TaskcategoryController::class, 'getRemove']);
Route::get('getTaskcategory', [TaskcategoryController::class, 'getTaskcategoryData'])->name('getTaskcategory');
Route::get('taskcategoryeditchk', [TaskcategoryController::class, 'taskcategoryeditchk'])->name('taskcategoryeditchk');
// TASK SUBCATEGORY
Route::get('tasksubcategory', [TasksubcategoryController::class, 'create'])->name('tasksubcategory');
Route::post('tasksubcategorysave', [TasksubcategoryController::class, 'save'])->name('tasksubcategorysave');
Route::get('tasksubcategoryedit', [TasksubcategoryController::class, 'tasksubcategoryedit'])->name('tasksubcategoryedit');
Route::get('tasksubcategoryview/{id}', [TasksubcategoryController::class, 'view'])->name('tasksubcategoryview');
Route::get('tasksubcategorydelete/{id}', [TasksubcategoryController::class, 'destroy'])->name('tasksubcategorydelete');
Route::get('tasksubcategorycheckname', [TasksubcategoryController::class, 'getcheckname'])->name('tasksubcategorycheckname');
Route::get('getTasksubcategoryData', [TasksubcategoryController::class, 'getTasksubcategoryData'])->name('getTasksubcategoryData');
//PRODUCT SYNC
Route::get('productsyncfg', [CreateuserController::class, 'productsyncfg'])->name('productsyncfg');
Route::get('productsyncsfg', [CreateuserController::class, 'productsyncsfg'])->name('productsyncsfg');
Route::get('shipmentssync', [CreateuserController::class, 'shipmentssync'])->name('shipmentssync');
Route::get('shipmentsrevsync', [CreateuserController::class, 'shipmentsrevsync'])->name('shipmentsrevsync');
Route::get('qohprocessinsert', [CreateuserController::class, 'qohprocessinsert'])->name('qohprocessinsert');
Route::get('productsyncfgcost', [CreateuserController::class, 'productsyncfgcost'])->name('productsyncfgcost');
Route::get('productsyncsfgcost', [CreateuserController::class, 'productsyncsfgcost'])->name('productsyncsfgcost');
//TOOL MENU CONFIG
Route::get('toolsmenuconfig', [ToolsmenuconfighdrController::class, 'index'])->name('toolsmenuconfig');
Route::get('toolsmenucheck', [ToolsmenuconfighdrController::class, 'toolsmenucheck'])->name('toolsmenucheck');
Route::get('getToolData', [ToolsmenuconfighdrController::class, 'getToolData'])->name('getToolData');
Route::get('toolsmenuconfighdrcreate/{id}', [ToolsmenuconfighdrController::class, 'create'])->name('toolsmenuconfighdrcreate');
Route::get('toolsmenuconfighdrview/{id}', [ToolsmenuconfighdrController::class, 'show'])->name('toolsmenuconfighdrview');
Route::get('toolsmenuconfighdredit/{id}', [ToolsmenuconfighdrController::class, 'edit'])->name('toolsmenuconfighdredit');
Route::get('toolsmenuconfighdrdelete/{id}', [ToolsmenuconfighdrController::class, 'delete'])->name('toolsmenuconfighdrdelete');
Route::post('toolsmenuconfighdrsave', [ToolsmenuconfighdrController::class, 'save'])->name('toolsmenuconfighdrsave');

// Menu Creation
Route::get('menucreation', [CreatemenuController::class, 'create'])->name('menucreation');
Route::get('getMenuData', [CreatemenuController::class, 'getMenuData'])->name('getMenuData');
Route::post('savemenu', [CreatemenuController::class, 'save'])->name('savemenu');
Route::get('menudelete/{id}', [CreatemenuController::class, 'delete'])->name('menudelete');

// Menus Button 

Route::get('buttoncreation', [CreatebuttonController::class, 'create'])->name('buttoncreation');
Route::get('getbuttonData', [CreatebuttonController::class, 'getbuttonData'])->name('getbuttonData');
Route::post('savebutton', [CreatebuttonController::class, 'save'])->name('savebutton');
Route::get('buttondelete/{id}', [CreatebuttonController::class, 'delete'])->name('buttondelete');




?>