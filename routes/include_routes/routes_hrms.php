<?php

use App\Http\Controllers\EmployeecreateController;
use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\EmployeeuploadController;
use App\Http\Controllers\EmployeedepartmentController;
use App\Http\Controllers\JobtitleController;
use App\Http\Controllers\JobpositionController;
use App\Http\Controllers\EmployeedocumentController;
use App\Http\Controllers\CompanydocumentController;
use App\Http\Controllers\EmployeeseparationController;
use App\Http\Controllers\InterviewprocessController;
use App\Http\Controllers\JobdescriptionController;
use App\Http\Controllers\PayproposalController;
use App\Http\Controllers\LettercontentController;
use App\Http\Controllers\viewemployeeController;
use App\Http\Controllers\ShiftdetailController;
use App\Http\Controllers\ProfessionaltaxController;
use App\Http\Controllers\OnboardprocessController;
use App\Http\Controllers\ImprestController;
use App\Http\Controllers\NewleavereportController;
use App\Http\Controllers\paySlipController;
use App\Http\Controllers\PayreportController;
use App\Http\Controllers\EmployeeadvanceController;
use App\Http\Controllers\fandfController;
use App\Http\Controllers\CompanyorganogramController;
use App\Http\Controllers\FormsixteendownloadController;
use App\Http\Controllers\LeaveController;
use App\Http\Controllers\LeaveapplicationController;
use App\Http\Controllers\MisspunchController;
use App\Http\Controllers\AttendancereportController;
use App\Http\Controllers\CompoffapplicationController;
use App\Http\Controllers\ElencashmentController;
use App\Http\Controllers\TravelclaimController;
use App\Http\Controllers\AttendanceimportController;
use App\Http\Controllers\AttendancesettingController;
use App\Http\Controllers\MonthlyattendanceController;
use App\Http\Controllers\ShiftuploadController;
use App\Http\Controllers\AttendancesyncController;
use App\Http\Controllers\LeavereminderController;
use App\Http\Controllers\AccessrestictedController;
use App\Http\Controllers\EmployeeotController;
use App\Http\Controllers\ApproveemployeeotController;
use App\Http\Controllers\ReleaseempotController;
use App\Http\Controllers\OtdailycheckreportController;
use App\Http\Controllers\EpemployeeamountController;
use App\Http\Controllers\DeductionController;
use App\Http\Controllers\PsettingController;
use App\Http\Controllers\EsidumpController;
use App\Http\Controllers\GeneratepayrollController;
use App\Http\Controllers\ApprovepayrollController;
use App\Http\Controllers\GeneratebonusController;
use App\Http\Controllers\ApprovebonusController;
use App\Http\Controllers\ResumecollectionController;
use App\Http\Controllers\InterviewscheduleController;
use App\Http\Controllers\EmployeedocumentuploadController;
use App\Http\Controllers\SearchcandidateController;
use App\Http\Controllers\SearchscheduledinterviewController;
use App\Http\Controllers\MarketingAttritionrptController;

// LARAVEL 8 NEW ROUTE

//EMPLOYEE CREATE
Route::get('createemployee', [EmployeecreateController::class, 'index'])->name('createemployee');
Route::get('getage', [EmployeecreateController::class, 'getage'])->name('createemployee');
Route::get('passport', [EmployeecreateController::class, 'passport'])->name('createemployee');
Route::post('/employee/save', [EmployeecreateController::class, 'save'])->name('createemployee');
Route::post('employee/personal', [EmployeecreateController::class, 'save'])->name('createemployee');
Route::get('/employee/delete/', [EmployeecreateController::class, 'getRemove'])->name('createemployee');
Route::get('/employee/checkusermail/', [EmployeecreateController::class, 'getCheckname'])->name('createemployee');
Route::get('/employee/checkemployee/', [EmployeecreateController::class, 'getCheckname'])->name('createemployee');
Route::get('fetch_information/{form_name}/{edit_id}', [EmployeecreateController::class, 'fetchinformation'])->name('createemployee');
Route::get('delete_information/{form_name}/{edit_id}', [EmployeecreateController::class, 'deleteformation'])->name('createemployee');

//Announcement
Route::get('announcement', [AnnouncementController::class, 'index'])->name('announcement');
Route::get('getannData', [AnnouncementController::class, 'getannData'])->name('getannData');
Route::get('createannouncement/{id}', [AnnouncementController::class, 'create'])->name('createannouncement');
Route::post('announcementsave', [AnnouncementController::class, 'save'])->name('announcementsave');
Route::get('announcementview/{id}', [AnnouncementController::class, 'view'])->name('announcementview');
Route::get('announcementdelete/{id}', [AnnouncementController::class, 'delete'])->name('announcementdelete');

// HRMS EMPLOYEE UPLOAD
Route::get('employeeupload', [EmployeeuploadController::class, 'index'])->name('employeeupload');
Route::post('employeeuploadsave', [EmployeeuploadController::class, 'save'])->name('employeeuploadsave');
Route::get('employeeedit/{id}', [EmployeeuploadController::class, 'edit'])->name('employeeedit');
Route::post('employeeuploadupdate', [EmployeeuploadController::class, 'update'])->name('employeeuploadupdate');
Route::get('employeeupload/{id}', [EmployeeuploadController::class, 'show'])->name('employeeuploadshow');
Route::get('employeeuploaddelete/{id}', [EmployeeuploadController::class, 'employeeuploaddelete'])->name('employeeuploaddelete');
Route::get('getEmployeevalidate', [EmployeeuploadController::class, 'getEmployeevalidate'])->name('getEmployeevalidate');
Route::get('getEmployeeuploaddata', [EmployeeuploadController::class, 'getemployeeuploaddata'])->name('getEmployeeuploaddata');


// HRMS DEPARTMENT
Route::post('departmentnewsave', [EmployeedepartmentController::class, 'savenew'])->name('departmentnewsave');
Route::get('employeedepartmentnew', [EmployeedepartmentController::class, 'createnew'])->name('employeedepartmentnew');
Route::get('subdepartment/{id}', [EmployeedepartmentController::class, 'subdepartment'])->name('subdepartment');

// JOB TITTLE
Route::get('employeejobtitle', [JobtitleController::class, 'index'])->name('employeejobtitle');
Route::get('employeejobtitle/edit', [JobtitleController::class, 'getShow'])->name('employeejobtitle');
Route::post('employeejobtitle/save', [JobtitleController::class, 'save'])->name('employeejobtitle');
Route::get('employeejobtitledelete/{id}', [JobtitleController::class, 'getRemove'])->name('employeejobtitle');
Route::get('employeejobtitle/checkname', [JobtitleController::class, 'getCheckname'])->name('employeejobtitle');
Route::get('employeejobtitle/{id}', [JobtitleController::class, 'show'])->name('employeejobtitle');
Route::get('employeejobtitlegrid', [JobtitleController::class, 'employeejobtitlegrid'])->name('employeejobtitlegrid');

// JOB POSITION
Route::get('employeeposition', [JobpositionController::class, 'index'])->name('employeeposition');
Route::get('employeeposition/edit', [JobpositionController::class, 'getShow'])->name('employeeposition');
Route::post('employeeposition/save', [JobpositionController::class, 'save'])->name('employeeposition');
Route::get('employeeposition/delete/{id}', [JobpositionController::class, 'getRemove'])->name('employeeposition');
Route::get('employeeposition/checkname', [JobpositionController::class, 'getCheckname'])->name('employeeposition');
Route::get('employeeposition/{id}', [JobpositionController::class, 'show'])->name('employeeposition');
Route::get('getposition', [JobpositionController::class, 'getposition'])->name('getposition');

// EMPLOYEE DOCUMENT
Route::get('employeedocument', [EmployeedocumentController::class, 'index'])->name('employeedocument');
Route::post('employeedocument/save', [EmployeedocumentController::class, 'save'])->name('employeedocument');
Route::get('employeedocument/delete/{id}', [EmployeedocumentController::class, 'getRemove'])->name('employeedocument');
Route::get('employeedocument/checkname', [EmployeedocumentController::class, 'getCheckname'])->name('employeedocument');
Route::get('employeedocumentgrid', [EmployeedocumentController::class, 'employeedocumentgrid'])->name('employeedocumentgrid');

// COMPANY DOCUMENT
Route::get('companydocument', [CompanydocumentController::class, 'index'])->name('companydocument');
Route::post('companydocument/save', [CompanydocumentController::class, 'save'])->name('companydocument');
Route::get('companydocument/delete/{id}', [CompanydocumentController::class, 'getRemove'])->name('companydocument');
Route::get('companydocument/checkname', [CompanydocumentController::class, 'getCheckname'])->name('companydocument');
Route::get('companydocumentgrid', [CompanydocumentController::class, 'companydocumentgrid'])->name('companydocumentgrid');
Route::get('employeesepsearch', [EmployeeseparationController::class, 'employeesearch'])->name('approveindex');

// INTERVIEW PROCESS
Route::get('interviewprocess', [InterviewprocessController::class, 'index'])->name('interviewprocess');
Route::post('employeeinterviewprocess/save', [InterviewprocessController::class, 'store'])->name('interviewprocess');
Route::get('employeeinterviewprocess/edit', [InterviewprocessController::class, 'getShow'])->name('interviewprocess');
Route::get('interviewprocessgrid', [InterviewprocessController::class, 'interviewprocessgrid'])->name('interviewprocessgrid');
Route::get('interviewprocess/delete/{id}', [InterviewprocessController::class, 'getRemove'])->name('interviewprocess');
Route::get('employeeinterviewprocess/checkname', [InterviewprocessController::class, 'getCheckname'])->name('interviewprocess');

// HRMS EMPLOYEE ALLOWANCE
Route::get('employeetypeallowance', [JobtitleController::class, 'indextype'])->name('employeetypeallowance');
Route::post('employeallowance/save', [JobtitleController::class, 'allowancesave'])->name('employeetypeallowance');
Route::get('employeeallowance/checkname', [JobtitleController::class, 'getChecknameallowance'])->name('employeetypeallowance');
Route::get('employeeallowancegrid', [JobtitleController::class, 'employeeallowancegrid'])->name('employeeallowancegrid');
Route::get('employeeallowance/delete/{id}', [JobtitleController::class, 'getRemoveallowance'])->name('employeetypeallowance');
Route::get('employeetypegetallowance/{id}', [PayproposalController::class, 'employeetypegetallowance'])->name('employeetypeallowance');
Route::get('allowancegetid/{id}', [PayproposalController::class, 'allowancegetid'])->name('allowancegetid');

// HRMS EMPLOYEE UPDATE UPLOAD
Route::get('employeeupdateupload', [EmployeeuploadController::class, 'indexupdate'])->name('employeeuploadupdate');
Route::post('employeeuploadupdatesave', [EmployeeuploadController::class, 'updatesave'])->name('employeeuploadupdate');
Route::get('getEmployeeupdateuploaddata', [EmployeeuploadController::class, 'getEmployeeupdateuploaddata'])->name('getEmployeeupdateuploaddata');
Route::get('getEmployeeupdatevalidate', [EmployeeuploadController::class, 'getEmployeeupdatevalidate'])->name('getEmployeeupdatevalidate');
Route::get('employeeupdateedit/{id}', [EmployeeuploadController::class, 'updateedit'])->name('employeeupdateedit');
Route::post('employeeupdateuploadupdate', [EmployeeuploadController::class, 'update2'])->name('employeeupdateuploadupdate');
Route::get('employeeupdateupload/{id}', [EmployeeuploadController::class, 'showupdate'])->name('employeeupdateupload');
Route::get('employeeuploadupdatedelete/{id}', [EmployeeuploadController::class, 'employeeuploadupdatedelete'])->name('employeeuploadupdatedelete');

// LETTER CONTENT
Route::get('lettercontent', [LettercontentController::class, 'index'])->name('lettercontent');
Route::get('lettercontentgriddata', [LettercontentController::class, 'lettercontentgriddata'])->name('lettercontentgriddata');
Route::get('lettercontentcreate', [LettercontentController::class, 'create'])->name('lettercontentcreate');
Route::post('lettercontentsave', [LettercontentController::class, 'save'])->name('lettercontentsave');
Route::get('lettercontentedit/{id}', [LettercontentController::class, 'edit'])->name('lettercontentedit');
Route::get('changestatus', [LettercontentController::class, 'updatestatus'])->name('changestatus');

// VIEW PROFILE
Route::get('profileview/{type}/{id}', [viewemployeeController::class, 'profileview'])->name('profileview');
Route::get('viewprofile', [viewemployeeController::class, 'index'])->name('viewprofile');
Route::get('updateprofile', [EmployeecreateController::class, 'edit'])->name('updateprofile');
Route::post('employeeload', [viewemployeeController::class, 'getLoad'])->name('employeeload');
Route::get('editprofile/{id}', [EmployeecreateController::class, 'edit'])->name('updateprofile');
Route::get('editprofile/{id}/{ids}', [EmployeecreateController::class, 'edit'])->name('editprofile');

// EMPLOYEE HIERARCHY
Route::get('employeehierarchy', [EmployeeseparationController::class, 'hierarchylist'])->name('employeehierarchy');

// SHIFT TIMING CHECK NAME
Route::get('/shifttiming/checkname', [ShiftdetailController::class, 'getCheckname'])->name('shifttiming');

// PROFESSIONAL TAX CHECK NAME
Route::get('ptcheckname', [ProfessionaltaxController::class, 'getCheckname'])->name('professional');

// EMPLOYEE TYPE ALLOWANCE GET
Route::get('employeetypegetallowanceget/{id}', [OnboardprocessController::class, 'employeetypegetallowanceget'])->name('onboardprocess');

// IMPREST
Route::get('imprest', [ImprestController::class, 'index'])->name('imprest');
Route::post('imprest/save', [ImprestController::class, 'store'])->name('imprest');
Route::post('imprestapprovesave', [ImprestController::class, 'imprestapprovesave'])->name('imprestapprovesave');
Route::get('imprestgrid', [ImprestController::class, 'imprestgrid'])->name('imprestgrid');
Route::get('imprestgriddata', [ImprestController::class, 'imprestgriddata'])->name('imprestgriddata');
Route::get('imprestdelete/{id}', [ImprestController::class, 'getRemove'])->name('imprestdelete');

// IMPREST APPROVAL
Route::get('imprestapproval', [ImprestController::class, 'index'])->name('imprestapproval');
Route::get('approveimprest/{id}', [ImprestController::class, 'approveimprest'])->name('approveimprest');

// IMPREST REPORT
Route::get('imprestreport', [ImprestController::class, 'imprestreport'])->name('imprestreport');
Route::get('imprestreportData', [ImprestController::class, 'imprestreportData'])->name('imprestreportData');

// CHANGE REPORTING

Route::get('changereportinggriddata', [EmployeeseparationController::class, 'employeeseparationchange'])->name('changereportinggriddata');
Route::get('approveseperation/{id}', [EmployeeseparationController::class, 'employeeseparationrequest'])->name('approveindex');
Route::post('releiveprocessupdate', [EmployeeseparationController::class, 'releiveprocessupdate'])->name('approveindex');
Route::get('changereporting', [EmployeeseparationController::class, 'reportingsindex'])->name('changereporting');
Route::get('reportingchange', [EmployeeseparationController::class, 'reportingschange'])->name('changereporting');
Route::post('savereportingchanges', [EmployeeseparationController::class, 'store'])->name('changereporting');
Route::get('changereportingdata', [EmployeeseparationController::class, 'changereportingdata'])->name('changereportingdata');

// EMPLOYEE ACTIVE
Route::get('employeeactive', [paySlipController::class, 'employeeactive'])->name('employeeactive');
Route::get('employeegrid', [paySlipController::class, 'employeeData'])->name('employeegrid');

// EMPLOYEE RELIEVE
Route::get('employeerelieve', [PaySlipController::class, 'employeerelieve'])->name('employeerelieve');
Route::get('employeerelievereportgrid', [PaySlipController::class, 'employeerelievereportgrid'])->name('employeerelievereport');

// EMPLOYEE ACTIVE REPORT
Route::get('employeeactivereport', [PaySlipController::class, 'employeeactivereport'])->name('employeeactivereport');
Route::get('employeeactivereportgrid', [PaySlipController::class, 'employeeactivereportgrid'])->name('employeeactivereportgrid');

// EMPLOYEE DETAIL REPORT
Route::get('employeedetailindex', [PayreportController::class, 'employeedetailindex'])->name('employeedetailindex');
Route::get('getemployeedetail', [PayreportController::class, 'getemployeedetail'])->name('getemployeedetail');

// F AND F
Route::get('emplyegen', [EmployeeadvanceController::class, 'employeeindex'])->name('emplyegen');
Route::get('getemployeegenData', [EmployeeadvanceController::class, 'getemployeegenData'])->name('getemployeegenData');
Route::get('employeestatusupdate/{result}/{id1}/{id2}', [EmployeeadvanceController::class, 'employeestatusupdate'])->name('employeestatusupdate');

// F&F Main
Route::get('fandf', [fandfController::class, 'index'])->name('fandf');
Route::get('fandfcreate/{id}', [fandfController::class, 'create'])->name('fandfcreate');
Route::get('fandfview/{id}', [fandfController::class, 'show']);
Route::get('fandfdelete/{id}', [fandfController::class, 'delete'])->name('fandfdelete');
Route::get('getfandfData', [fandfController::class, 'getfandfData'])->name('getfandfData');
;
Route::post('fandfsave', [fandfController::class, 'save']);

// Final Settlement
Route::get('finalsettlement', [fandfController::class, 'finalsettlement'])->name('finalsettlement');
Route::get('getfinalsettlementData', [fandfController::class, 'getfinalsettlementData'])->name('getfinalsettlementData');
Route::get('fandfprint/{id}', [fandfController::class, 'print']);

// Employee Document Report
Route::get('employeedocumentindex', [PayreportController::class, 'employeedocumentindex'])->name('employeedocumentindex');
Route::get('getemployeedocument', [PayreportController::class, 'getemployeedocument'])->name('getemployeedocument');

// Employee Organogram
Route::get('companyorganogram', [CompanyorganogramController::class, 'index'])->name('companyorganogram');
Route::get('companyorganogramdata', [CompanyorganogramController::class, 'data'])->name('companyorganogramdata');

// FORM 16 DOWNLOAD
Route::get('formsixteendownload', [FormsixteendownloadController::class, 'index'])->name('formsixteendownload');
Route::get('getFormsixteendnlodData', [FormsixteendownloadController::class, 'getFormsixteendnlodData'])->name('getFormsixteendnlodData');

// LEAVE REQUEST
Route::get('leaves', [LeaveController::class, 'index'])->name('leaves');
Route::post('earnleavesave', [LeaveapplicationController::class, 'earnleavesave'])->name('earnleavesave');
Route::post('overallleavesave', [LeaveapplicationController::class, 'overallleavesave'])->name('overallleavesave');
Route::post('leavesaves', [LeaveController::class, 'save'])->name('leave');
Route::get('leave', [LeaveapplicationController::class, 'index'])->name('leave');
Route::get('leaveData', [LeaveapplicationController::class, 'leaveData'])->name('leaveData');
Route::get('leavereport', [LeaveapplicationController::class, 'leavereportindex'])->name('leave');
Route::get('getleavegrid', [LeaveapplicationController::class, 'getleavegrid']);
Route::get('leavereportoverall', [LeaveapplicationController::class, 'leavereportoverallindex'])->name('leave');
Route::get('getleaveoverallgrid', [LeaveapplicationController::class, 'getleaveoverallgrid']);
Route::get('leavetypebase/{id}', [LeaveapplicationController::class, 'leavetypebase'])->name('leavetypebase');
Route::get('leavescreen', [LeaveapplicationController::class, 'overallleaverequest'])->name('overallleaverequest');
Route::get('leavebalancereport', [LeaveapplicationController::class, 'leavebalancereportindex'])->name('leave');
Route::get('getleavebalancegrid', [LeaveapplicationController::class, 'getleavebalancegrid'])->name('getleavebalancegrid');
Route::get('/leave/delete/', [LeaveapplicationController::class, 'getRemove'])->name('leave');
Route::get('leavesgriddata', [LeaveapplicationController::class, 'leavesgriddata'])->name('leave');
Route::post('leavesave', [LeaveapplicationController::class, 'save'])->name('leave');
Route::get('leaveapproval', [LeaveapplicationController::class, 'leaveapprovalindex'])->name('leaveapproval');
Route::get('leaveapproveData', [LeaveapplicationController::class, 'leaveapproveData'])->name('leaveapproveData');
Route::get('leavesapprovegriddata', [LeaveapplicationController::class, 'leavesapprovegriddata'])->name('leaveapproval');
Route::get('approveleave/{id}', [LeaveapplicationController::class, 'leaveapprove'])->name('leaveapproval');
Route::get('employeleavepopup', [LeaveapplicationController::class, 'employeleavepopup'])->name('employeleavepopup');
Route::post('approvesave', [LeaveapplicationController::class, 'leaveapprovesave'])->name('leaveapproval');
Route::get('leaveapprover', [LeaveapplicationController::class, 'leaveapprover'])->name('leaveapprover');
Route::get('leavedatecheck', [LeaveapplicationController::class, 'leavedatecheck'])->name('leavedatecheck');
Route::get('leaveinitiatecheck', [LeaveapplicationController::class, 'leaveinitiatecheck'])->name('leaveinitiatecheck');
Route::get('permissioninitiatecheck', [LeaveapplicationController::class, 'permissioninitiatecheck'])->name('permissioninitiatecheck');
Route::get('earnleavedatecountcheck', [LeaveapplicationController::class, 'earnleavedatecountcheck'])->name('earnleavedatecountcheck');
Route::get('leaveapprovalreport', [LeaveapplicationController::class, 'leaveapprovalreport'])->name('leaveapprovalreport');
Route::get('gridleavesapprovereportdata', [LeaveapplicationController::class, 'gridleavesapprovereportdata'])->name('gridleavesapprovereportdata');
Route::get('/employeeleavescheck/', [LeaveapplicationController::class, 'leavecheck'])->name('leave');
Route::get('leavesummary', [LeaveapplicationController::class, 'leavesummary'])->name('leavesummary');
Route::get('leavesummaryreportgrid', [LeaveapplicationController::class, 'leavesummaryreportgrid'])->name('leavesummary');
Route::get('getcombodate/{id}/{eid}', [LeaveapplicationController::class, 'getcombodate'])->name('getcombodate');

/* EMPLOYEE SEPARATION */
Route::get('separation', [EmployeeseparationController::class, 'index'])->name('approveindex');
Route::get('viewseperation/{id}', [EmployeeseparationController::class, 'view'])->name('approveindex');
Route::get('relivecheck', [EmployeeseparationController::class, 'relivecheck'])->name('relivecheck');
Route::get('relievecheckreassign', [EmployeeseparationController::class, 'relievecheckreassign'])->name('relivecheck');
Route::get('separationrequest', [EmployeeseparationController::class, 'separationindex'])->name('separationrequest');
Route::get('separationapproval', [EmployeeseparationController::class, 'approveindex'])->name('separationapproval');
Route::get('/employee/checkreport/', [EmployeeseparationController::class, 'checkreporting'])->name('separationrequest');
Route::post('/save/releive/', [EmployeeseparationController::class, 'releiveprocess'])->name('approveindex');
Route::post('/save/releiverequest/', [EmployeeseparationController::class, 'releiverequest'])->name('releiverequest');
Route::get('employeeseparationdata', [EmployeeseparationController::class, 'employeeseperationgrid'])->name('employeeseparationdata');
Route::get('employeeseperationgrid', [EmployeeseparationController::class, 'employeeseparationdata'])->name('employeeseperationgrid');

/* HOLIDAY */
Route::get('holiday', [LeaveapplicationController::class, 'holiday'])->name('holiday');
Route::get('getholidaygriddata', [LeaveapplicationController::class, 'getholidaygriddata'])->name('getholidaygriddata');
Route::post('holidaysave/save', [LeaveapplicationController::class, 'holidaysave'])->name('holiday');
Route::get('holiday/delete/{id}', [LeaveapplicationController::class, 'destroy'])->name('holiday');

// Travel Claim Amount
Route::get('travelamount', [ImprestController::class, 'travelamountindex'])->name('travelamount');
Route::post('travelamount/save', [ImprestController::class, 'savetravel'])->name('travelamount');
Route::get('travelamountgriddata', [ImprestController::class, 'travelamountgriddata'])->name('travelamountgriddata');
Route::get('travelamountdelete/{id}', [ImprestController::class, 'traveldelete'])->name('travelamountdelete');
Route::get('travelamountget/{id}', [ImprestController::class, 'travelamountget'])->name('travelamountget');

//daily attendance report
Route::get('dailyattendancereport', [AttendancereportController::class, 'dailyattendancereport'])->name('dailyattendancereport');
Route::get('datadailyattendancereportget', [AttendancereportController::class, 'datadailyattendancereportget'])->name('datadailyattendancereportget');

// Miss Punch / Punch Request
Route::get('punchrequest', [MisspunchController::class, 'index'])->name('punchrequest');
Route::post('misssave', [MisspunchController::class, 'punchsave'])->name('punchrequest');
Route::post('misspunchsaveap', [MisspunchController::class, 'misspunchsaveap'])->name('punchrequest');
Route::get('missdata', [MisspunchController::class, 'punchdata'])->name('missdata');
Route::get('punchrequestapproval', [MisspunchController::class, 'missapprove'])->name('punchrequestapproval');
Route::get('punchrequestapprovaldata', [MisspunchController::class, 'punchrequestapprovaldata'])->name('punchrequestapprovaldata');
Route::get('approvepunch/{id}', [MisspunchController::class, 'punchmiss'])->name('approvepunch');
Route::get('punchoverallrequest', [MisspunchController::class, 'punchoverallrequest'])->name('punchoverallrequest');
Route::get('misspunchdatecheck', [MisspunchController::class, 'misspunchdatecheck'])->name('misspunchdatecheck');

// ATTENDANCE REPORT
Route::get('attendance', [paySlipController::class, 'attendancereport'])->name('attendance');
Route::get('attendancereportdata', [paySlipController::class, 'attendancereportdata'])->name('attendancereportdata');

// LEAVE BALANCE
Route::get('leavebalance', [LeaveapplicationController::class, 'leavebalance'])->name('leavebalance');
Route::get('getleavebalancegriddata', [LeaveapplicationController::class, 'getleavebalancegriddata'])->name('getleavebalancegriddata');
Route::post('leavebalancesave', [LeaveapplicationController::class, 'leavebalancesave'])->name('leavebalancesave');
Route::get('leavebalance/delete/{id}', [LeaveapplicationController::class, 'leavebalancedestroy'])->name('leavebalance');
Route::get('empleavebalancecheck', [LeaveapplicationController::class, 'empleavebalancecheck'])->name('empleavebalancecheck');

// Compoff Request
Route::get('compoff', [CompoffapplicationController::class, 'index'])->name('compoff');
Route::get('compoffData', [CompoffapplicationController::class, 'compoffData'])->name('compoffData');
Route::get('compoffdatecheck', [CompoffapplicationController::class, 'compoffdatecheck'])->name('compoffdatecheck');
Route::get('compoffinitiatecheck', [CompoffapplicationController::class, 'compoffinitiatecheck'])->name('compoffinitiatecheck');
Route::post('compoffsave', [CompoffapplicationController::class, 'save'])->name('compoff');
Route::get('compoffapprover', [CompoffapplicationController::class, 'compoffapprover'])->name('compoffapprover');
Route::get('/compoff/delete/', [CompoffapplicationController::class, 'getRemove'])->name('compoff');
Route::get('compoffapproval', [CompoffapplicationController::class, 'compoffapprovalindex'])->name('compoffapproval');
Route::get('compoffapprovalData', [CompoffapplicationController::class, 'compoffapprovalData'])->name('compoffapprovalData');
Route::get('approvecompoff/{id}', [CompoffapplicationController::class, 'compoffapprove'])->name('compoffapproval');
Route::post('approvecompoffsave', [CompoffapplicationController::class, 'compoffapprovesave'])->name('compoffapproval');

// EL Encashment
Route::get('elencashment', [ElencashmentController::class, 'index'])->name('elencashment');
Route::get('getencashmentlist', [ElencashmentController::class, 'getencashmentlist'])->name('getencashmentlist');
Route::get('elencashmentapprove', [ElencashmentController::class, 'approvegrid'])->name('elencashmentapprove');
Route::get('elapprovelist', [ElencashmentController::class, 'elapprovelist'])->name('elapprovelist');
Route::get('requestelencashment', [ElencashmentController::class, 'requestelencashment'])->name('requestelencashment');
Route::get('approveelencashment', [ElencashmentController::class, 'approveelencashment'])->name('approveelencashment');

/*** ADVANCE ***/
Route::get('advancecreate', [EmployeeadvanceController::class, 'index'])->name('advancecreate');
Route::get('employeeadvancegriddata', [EmployeeadvanceController::class, 'employeeadvancegriddata'])->name('employeeadvancegriddata');
Route::get('getgrosspay', [EmployeeadvanceController::class, 'employeegrosspay'])->name('documentcreate');
Route::get('addvancereprot', [EmployeeadvanceController::class, 'addvancereport'])->name('addvancereprot');
Route::get('advancemonthreport', [EmployeeadvanceController::class, 'advancemonthreport'])->name('advancemonthreport');
Route::get('advancemonthreportData', [EmployeeadvanceController::class, 'advancemonthreportData'])->name('advancemonthreportData');
Route::post('advancesave', [EmployeeadvanceController::class, 'store'])->name('advancecreate');
Route::get('employeeadvance/delete/{id}', [EmployeeadvanceController::class, 'destroy'])->name('advancecreate');
Route::get('employeeadvanceapprovedatagrid', [EmployeeadvanceController::class, 'employeeadvanceapprovedatagrid'])->name('advancecreate');
Route::get('addvanceapproval', [EmployeeadvanceController::class, 'approve'])->name('addvanceapproval');
Route::get('approveData', [EmployeeadvanceController::class, 'approveData'])->name('approveData');
Route::get('approverptData', [EmployeeadvanceController::class, 'approverptData'])->name('approverptData');
Route::get('advancededuction', [EmployeeadvanceController::class, 'advancededuction'])->name('advancededuction');
Route::get('advancedeductionData', [EmployeeadvanceController::class, 'advancedeductionData'])->name('advancedeductionData');
Route::get('getstatus', [EmployeeadvanceController::class, 'getstatus'])->name('advancecreate');
Route::get('advancestatus', [EmployeeadvanceController::class, 'statuschanges'])->name('advancecreate');
Route::get('advanceapprove/{id}', [EmployeeadvanceController::class, 'approveform'])->name('advanceapprove');
Route::get('advancededuct/{id}', [EmployeeadvanceController::class, 'approveform'])->name('advancededuct');

/**** Advance deductions ***/
Route::get('employeededuction', [EmployeeadvanceController::class, 'deductiondetails'])->name('advancededuction');
Route::post('advancedeductionsave', [EmployeeadvanceController::class, 'deductionsave'])->name('advancededuction');
Route::get('employeeadvancedeductiongriddata', [EmployeeadvanceController::class, 'employeeadvancedeductiongriddata'])->name('advancededuction');

/**** Travel Claim Request ***/
Route::get('travelclaimrequest', [TravelclaimController::class, 'index'])->name('travelclaimrequest');
Route::get('requestclaim/{id}', [TravelclaimController::class, 'create'])->name('travelclaimrequest');
Route::get('employeetravelclaimgriddata', [TravelclaimController::class, 'employeetravelclaimgriddata'])->name('travelclaimrequest');
Route::post('travelclaimsave', [TravelclaimController::class, 'store'])->name('travelclaimrequest');
Route::get('travelclaim/delete/{id}', [TravelclaimController::class, 'traveldelete'])->name('travelclaimrequest');

/*** Claim Approval ***/
Route::get('claimapproval', [TravelclaimController::class, 'approveindex'])->name('claimapproval');
Route::get('travelclaimreport', [TravelclaimController::class, 'travelreport_index'])->name('travelclaimreport');
Route::get('employeetravelclaimapprovegriddata', [TravelclaimController::class, 'employeetravelclaimapprovegriddata'])->name('claimapproval');
Route::get('approvetravelclaim/{id}', [TravelclaimController::class, 'approvetravelclaim'])->name('claimapproval');
Route::post('travelclaimapprove', [TravelclaimController::class, 'claimapprove'])->name('claimapproval');
Route::get('claimdelete', [TravelclaimController::class, 'destroy'])->name('claimapproval');
Route::get('travelclaimgriddata', [TravelclaimController::class, 'travelclaimgriddata'])->name('travelclaimgriddata');
Route::get('travelapproveclaim', [TravelclaimController::class, 'travelapproveclaimgrid'])->name('travelapproveclaim');
Route::get('travelreportdata', [TravelclaimController::class, 'travelreportgridgrid'])->name('travelreportdata');

/** Attendance Import **/
Route::get('attendanceimport', [AttendanceimportController::class, 'index'])->name('attendanceimport');
Route::get('attendanceimportdatagrid', [AttendanceimportController::class, 'attendanceimportdatagrid'])->name('attendanceimportdatagrid');
Route::post('attendanceupload', [AttendanceimportController::class, 'attendanceupload'])->name('attendanceupload');
Route::get('monthapproval', [AttendanceimportController::class, 'monthapproval'])->name('monthapproval');

/** Attendance Setting **/
Route::get('atscreate', [AttendancesettingController::class, 'create'])->name('atscreate');
Route::get('getAttenancesettingData', [AttendancesettingController::class, 'attedancesettinggriddata'])->name('getattenancesettingdata');
Route::get('attendancesettingdelete/{id}', [AttendancesettingController::class, 'destroy'])->name('atscreate');
Route::post('attendancesettingsave', [AttendancesettingController::class, 'save'])->name('atscreate');
Route::get('emp_type', [AttendancesettingController::class, 'emp_typecheck'])->name('atscreate');

// Monthly
Route::get('monthly', [MonthlyattendanceController::class, 'index'])->name('monthly');
Route::get('monthlytblData', [MonthlyattendanceController::class, 'monthlytblData'])->name('monthlytblData');
Route::get('employeemonthlygriddata', [MonthlyattendanceController::class, 'employeemonthlygriddata'])->name('employeemonthlygriddata');
Route::post('monthlysave', [MonthlyattendanceController::class, 'store'])->name('monthlysave');
Route::get('monthlydelete/{id}', [MonthlyattendanceController::class, 'destroy'])->name('monthlydelete');
Route::get('employeecheckpayrolltype/{id}', [MonthlyattendanceController::class, 'employeecheckpayrolltype'])->name('employeecheckpayrolltype');
Route::get('checkclslel/{id}/{id1}', [MonthlyattendanceController::class, 'checkclslel'])->name('checkclslel');

//shift 
Route::get('shifttiming', [ShiftdetailController::class, 'index'])->name('shifttiming');
Route::get('shifttiminggrid', [ShiftdetailController::class, 'ShiftdetailControllergriddata'])->name('shifttiminggrid');
Route::post('shifttimingssave', [ShiftdetailController::class, 'store'])->name('shifttimingssave');
Route::get('timingdelete/{id}', [ShiftdetailController::class, 'delete'])->name('timingdelete');

//shift upload
Route::get('shiftupload', [ShiftuploadController::class, 'index'])->name('shiftupload');
Route::post('shiftuploadsave', [ShiftuploadController::class, 'save'])->name('shiftuploadsave');
Route::post('shiftuploadupdate', [ShiftuploadController::class, 'shiftuploadupdate'])->name('shiftuploadupdate');
Route::get('employeeshiftdetailsgrid', [ShiftuploadController::class, 'employeeshiftdetailsgriddata'])->name('employeeshiftdetailsgrid');
Route::get('employeeshiftview/{id}', [ShiftuploadController::class, 'employeeshiftview'])->name('employeeshiftview');
Route::get('employeeshiftedit/{id}', [ShiftuploadController::class, 'employeeshiftedit'])->name('employeeshiftedit');
Route::get('shiftuploaddelete/{id}', [ShiftuploadController::class, 'destroy'])->name('shiftuploaddelete');

// attendance sync
Route::get('attendancesync', [AttendancesyncController::class, 'index'])->name('attendancesync');
Route::get('attedancesyncdata', [AttendancesyncController::class, 'attedancesync'])->name('attedancesyncdata');
Route::get('attendancedetailsgrid', [AttendancesyncController::class, 'attendancedetailsgriddata'])->name('attendancedetailsgrid');


//attendance reports
Route::get('attendancereports', [PayreportController::class, 'attendancereport_index'])->name('attendancereports');
Route::get('getattendancereportdata', [PayreportController::class, 'getattendancereportdata'])->name('getattendancereportdata');
Route::get('attendancemonthreports', [PayreportController::class, 'attenindex'])->name('attendancemonthreports');
Route::get('calendar', [PayreportController::class, 'calendar'])->name('calendar');
Route::get('calendarjsondata', [PayreportController::class, 'jsondata'])->name('calendarjsondata');
Route::get('attendancecharts', [PayreportController::class, 'attendancecharts'])->name('attendancecharts');
Route::get('attendancechartsreport/{mid}/{eid}/{yid}', [PayreportController::class, 'attendancecharts'])->name('attendancechartsreport');
Route::get('reportmonthatten/{eid}/{mid}/{yid}', [PayreportController::class, 'reportmonthatten'])->name('reportmonthatten');


// Monthly Upload
Route::get('monthlyupload', [MonthlyattendanceController::class, 'monthlyuploadindex'])->name('monthlyuploadindex');
Route::post('attendancemonthupload', [MonthlyattendanceController::class, 'attendancemonthupload'])->name('attendancemonthupload');
Route::get('monthuploadapproval', [MonthlyattendanceController::class, 'monthuploadapproval'])->name('monthuploadapproval');
Route::get('monthlyuploadgrid', [MonthlyattendanceController::class, 'monthlyuploadgrid'])->name('monthlyuploadgrid');

// Leave Reminder Mail
Route::get('leaveremindermail', [LeavereminderController::class, 'index'])->name('leaveremindermail');
Route::get('punchmissdatagrid', [LeavereminderController::class, 'punchmissdata'])->name('punchmissdatagrid');
Route::get('leaveremindmail/{id}', [LeavereminderController::class, 'sendreminder'])->name('leaveremindmail');


Route::get('accessresticted', [AccessrestictedController::class, 'Index'])->name('accessresticted');

// OT
Route::get('employeeotcal', [EmployeeotController::class, 'index'])->name('employeeotcal');
Route::get('generateot', [EmployeeotController::class, 'generateot'])->name('generateot');
Route::get('employeeotgrid', [EmployeeotController::class, 'employeeotgrid'])->name('employeeotgrid');
Route::get('validateot', [EmployeeotController::class, 'validateot'])->name('validateot');
Route::post('empep_update/{id}', [EmployeeotController::class, 'empepupdate'])->name('empep_update');

// Approve OT
Route::get('approveot', [ApproveemployeeotController::class, 'index'])->name('approveot');
Route::get('employeeotgrid1', [ApproveemployeeotController::class, 'employeeotgrid1'])->name('employeeotgrid1');
Route::get('approvedot', [ApproveemployeeotController::class, 'approveot'])->name('approvedot');

// Release OT
Route::get('otrelease', [ReleaseempotController::class, 'index'])->name('otrelease');
Route::get('employeeotgrid2', [ReleaseempotController::class, 'employeeotgrid2'])->name('employeeotgrid2');
Route::get('releasedot', [ReleaseempotController::class, 'releaseot'])->name('releasedot');

// OT Report
Route::get('dailyotreport', [OtdailycheckreportController::class, 'index'])->name('dailyotreport');
Route::get('otreportgrid', [OtdailycheckreportController::class, 'otreportgrid'])->name('otreportgrid');
Route::post('otmailsend', [EmployeeotController::class, 'otmailsend'])->name('otmailsend');

// OT Amount Entry
Route::get('epamountentry', [EpemployeeamountController::class, 'index'])->name('epamountentry');
Route::get('epamountentrydata', [EpemployeeamountController::class, 'epamountentrydata'])->name('epamountentrydata');
Route::get('createepamountentry/{id}', [EpemployeeamountController::class, 'Create'])->name('createepamountentry');
Route::post('epamountentrysave', [EpemployeeamountController::class, 'save'])->name('epamountentrysave');
Route::get('epamountentrydelete/{id}', [EpemployeeamountController::class, 'Delete'])->name('epamountentrydelete');

// professional tax 
Route::get('professional', [ProfessionaltaxController::class, 'indextable'])->name('professional');
Route::get('createprofessional/{id}', [ProfessionaltaxController::class, 'index'])->name('professional');
Route::post('professionaltaxsave', [ProfessionaltaxController::class, 'store'])->name('professional');
Route::get('professionaltaxgrid', [ProfessionaltaxController::class, 'professionaltaxgrid'])->name('professionaltaxgrid');
Route::get('professionaldelete/{id}', [ProfessionaltaxController::class, 'destroy'])->name('professionaldelete');

// employee adjust payproposal 
Route::get('allowanceindex', [PayproposalController::class, 'allowanceindex'])->name('allowanceindex');
Route::get('getemployeetypeallowancedata/{id}', [PayproposalController::class, 'getemployeetypeallowancedata'])->name('getemployeetypeallowancedata');
Route::post('employeemanualallowance', [PayproposalController::class, 'employeemanualallowance'])->name('employeemanualallowance');
Route::get('allowanceindexgriddata', [PayproposalController::class, 'allowanceindexgriddata'])->name('allowanceindexgriddata');
Route::get('allowancegetidasjust/{id}', [PayproposalController::class, 'allowancegetidasjust'])->name('allowancegetidasjust');
Route::get('allowancepayproposaldelete/{id}', [PayproposalController::class, 'allowancepayproposaldelete'])->name('allowancepayproposaldelete');

// DEDUCTIONS
Route::get('deductions', [DeductionController::class, 'create'])->name('deductions');
Route::get('deductiongriddata', [DeductionController::class, 'deductiongriddata'])->name('deductiongriddata');
Route::get('deductioncategory/checkid', [DeductionController::class, 'Checkcategory'])->name('deduction.checkid');
Route::post('deductions/save', [DeductionController::class, 'store'])->name('deduction.save');
Route::get('deductiondelete/{id}', [DeductionController::class, 'destroy'])->name('deductiondelete');
Route::get('componenetcheck', [DeductionController::class, 'componenetcheckdedcution'])->name('deduction.componentcheck');

// PAYPROPOSAL
Route::get('payproposal', [PayproposalController::class, 'index'])->name('payproposal');
Route::get('deductiondetails', [PayproposalController::class, 'getdeduction'])->name('deductiondetails');
Route::get('payproposalcheck', [PayproposalController::class, 'payproposalcheck'])->name('payproposalcheck');
Route::get('getesipf', [PayproposalController::class, 'getesipf'])->name('getesipf');
Route::get('employeepayproposalgriddata', [PayproposalController::class, 'employeepayproposalgriddata'])->name('employeepayproposalgriddata');
Route::post('employeepayproposal', [PayproposalController::class, 'store'])->name('employeepayproposal');
Route::get('payproposaldelete/{id}', [PayproposalController::class, 'delete'])->name('payproposaldelete');

// payroll cutoff
Route::get('payrollcuttoff', [PsettingController::class, 'create'])->name('payrollcuttoff');
Route::get('payrollcutoffgriddata', [PsettingController::class, 'payrollcutoffgriddata'])->name('payrollcutoffgriddata');
Route::post('payrollcuttoffsave', [PsettingController::class, 'save'])->name('payrollcuttoffsave');
Route::get('payrollcutoffdelete/{id}', [PsettingController::class, 'payrolldelete'])->name('payrollcutoffdelete');

//SETTING
Route::get('setting', [PsettingController::class, 'index'])->name('setting');
Route::post('psettingsave', [PsettingController::class, 'store'])->name('psettingsave');
Route::get('attendancesettingdel', [PsettingController::class, 'destroy'])->name('attendancesettingdel');
Route::get('companycheckcutoff/{id}', [PsettingController::class, 'companycheckcutoff'])->name('companycheckcutoff');

/** Pay Report **/
Route::get('payreport', [PayreportController::class, 'employeepayreportindex'])->name('payreport');
Route::get('getemployeepayrpt', [PayreportController::class, 'employeepayreport'])->name('getemployeepayrpt');
Route::get('stdpayreport', [PayreportController::class, 'employeestdpayreportindex'])->name('stdpayreport');
Route::get('getemployeestdpayrpt', [PayreportController::class, 'employeestdpayreport'])->name('getemployeestdpayrpt');
Route::get('payreportgrid', [PayreportController::class, 'payreportgrid'])->name('payreportgrid');

/** Delimiters **/
Route::get('delimiter', [PayreportController::class, 'index'])->name('delimiter');
Route::get('esidelimiter', [PayreportController::class, 'esiindex'])->name('esidelimiter');
Route::get('ptdelimiter', [PayreportController::class, 'ptindex'])->name('ptdelimiter');
Route::get('arrearpfdelimiter', [PayreportController::class, 'arrearpfindex'])->name('arrearpfdelimiter');

/** Delimiter Reports **/
Route::get('delimeterreport/{id}/{ids}', [PayreportController::class, 'delimeterreport'])->name('delimeterreport');
Route::get('delimeterreportesi/{id}/{ids}', [PayreportController::class, 'delimeterreportesi'])->name('delimeterreportesi');
Route::get('delimeterreportpt/{id}/{ids}/{idss}', [PayreportController::class, 'delimeterreportpt'])->name('delimeterreportpt');
Route::get('arrearpfdelimeterreport/{id}/{ids}', [PayreportController::class, 'arrearpfdelimeterreport'])->name('arrearpfdelimeterreport');

// ESI DUMP 
Route::get('esidump', [EsidumpController::class, 'index'])->name('esidump');
Route::get('dumpreportesi/{id}/{ids}', [EsidumpController::class, 'dumpreportesi'])->name('dumpreportesi');

// Payroll Generate
Route::get('payrollgenerate', [GeneratepayrollController::class, 'index'])->name('payrollgenerate');
Route::get('generatepayroll', [GeneratepayrollController::class, 'payrollgenerate'])->name('payrollgenerate');
Route::get('approvepayroll', [ApprovepayrollController::class, 'approvepayroll'])->name('approvepayroll');
Route::get('employeepayrollgrid1', [ApprovepayrollController::class, 'employeepayrollgriddataapprove'])->name('employeepayrollgrid1');
Route::get('payrollapprove', [ApprovepayrollController::class, 'payrollapprove'])->name('payrollapprove');
Route::get('approvedpayroll', [ApprovepayrollController::class, 'approvedpayroll'])->name('approvedpayroll');
Route::get('approvepayrollsource', [ApprovepayrollController::class, 'approvepayrollsource'])->name('monthly');
Route::get('hrmsjournal', [GeneratepayrollController::class, 'hrmsjournal'])->name('monthly');

// Payroll Release
Route::get('releasepayroll', [ApprovepayrollController::class, 'releasepayroll'])->name('approvepayroll');
Route::get('employeepayrollreleasegrid', [ApprovepayrollController::class, 'employeepayrollgriddatarelease'])->name('employeepayrollreleasegrid');
Route::get('releasedpayroll', [ApprovepayrollController::class, 'releasedpayroll'])->name('approvedpayroll');
Route::get('releasepayrollsource', [ApprovepayrollController::class, 'releasepayrollsource'])->name('releasepayrollsource');

// Pay Slip
Route::get('payslip', [paySlipController::class, 'index'])->name('payslip');
Route::get('payslipgenerate/{id}', [paySlipController::class, 'generatepayslip'])->name('payslipgenerate');
Route::get('employeepayrollgrid', [paySlipController::class, 'employeepayrollgriddata'])->name('employeepayrollgrid');
Route::post('payslipfilesave', [paySlipController::class, 'payslipfilesave'])->name('payslipfilesave');

/** ESI REPORT **/
Route::get('esireport', [PayreportController::class, 'esireportindex'])->name('esireport');
Route::get('esireportgrid', [PayreportController::class, 'esireportgrid'])->name('esireportgrid');

/** PF REPORT **/
Route::get('pfreport', [PayreportController::class, 'pfreportindex'])->name('pfreport');
Route::get('pfreportgrid', [PayreportController::class, 'pfreportgrid'])->name('pfreportgrid');


// PT REPORT
Route::get('ptreportindex', [PayreportController::class, 'ptreportindex'])->name('ptreportindex');
Route::get('getemployeeptrpt', [PayreportController::class, 'ptreport'])->name('getemployeeptrpt');

// Arrear Payroll
Route::get('arrearpayroll', [ApprovepayrollController::class, 'arrearpayroll'])->name('arrearpayroll.index');
Route::get('arrearpayrollgriddata', [ApprovepayrollController::class, 'arrearpayrollgriddata'])->name('arrearpayrollgriddata');
Route::get('arrearpayrollgenerate', [ApprovepayrollController::class, 'arrearpayrollgenerate'])->name('arrearpayrollgenerate');


// Bonus Generation
Route::get('bonusgenerate', [GeneratebonusController::class, 'index'])->name('bonusgenerate');
Route::get('generatebonus', [GeneratebonusController::class, 'bonusgenerate'])->name('generatebonus');
Route::get('employeebonusgrid1', [GeneratebonusController::class, 'employeebonusgriddataapprove'])->name('employeebonusgrid1');

// Approve Bonus
Route::get('approvebonus', [ApprovebonusController::class, 'index'])->name('approvebonus');
Route::get('approvedbonus', [ApprovebonusController::class, 'approvedbonus'])->name('approvedbonus');
Route::get('releasebonus', [ApprovebonusController::class, 'releasebonus'])->name('releasebonus');
Route::get('employeebonusreleasegrid', [ApprovebonusController::class, 'employeebonusreleasegrid'])->name('employeebonusreleasegrid');
Route::get('releasedbonus', [ApprovebonusController::class, 'releasedbonus'])->name('releasedbonus');

// RESUME COLLECTION
Route::get('resumecollection', [ResumecollectionController::class, 'index'])->name('resumecollection');
Route::get('addresume', [ResumecollectionController::class, 'create'])->name('resumecollection');
Route::post('resumesave', [ResumecollectionController::class, 'store'])->name('resumecollection');
Route::get('editresume/{id}', [ResumecollectionController::class, 'edit'])->name('resumecollection');
Route::get('cancel_schedule/{id}', [ResumecollectionController::class, 'cancel'])->name('resumecollection');
Route::get(
    'resumesearch/{firstkeyword}/{secondkeyword}/{thirdsthird}',
    [ResumecollectionController::class, 'resumesearch']
)->name('resumecollection');
Route::post('resumecollectionchkup', [ResumecollectionController::class, 'chkfile'])->name('resumecollection');

//JOB DESCRIPTION
Route::get('jobdescriptionfile', [JobdescriptionController::class, 'jobdescriptionfile'])->name('createjobdescription');
Route::get('jobdescriptiongriddata', [JobdescriptionController::class, 'jobdescriptiongriddata'])->name('jobdescriptiongriddata');
Route::get('jobdescriptionformgriddata', [JobdescriptionController::class, 'jobdescriptionformgriddata'])->name('jobdescriptionformgriddata');
Route::get('jobdescriptionapproval', [JobdescriptionController::class, 'index'])->name('jobdescriptionapproval');
Route::get('jobdescriptionapprovalhr', [JobdescriptionController::class, 'indexhr'])->name('jobdescriptionapprovalhr');
Route::get('createjobdescription', [JobdescriptionController::class, 'create'])->name('createjobdescription');
Route::get('createjobdescription/{id}', [JobdescriptionController::class, 'createnew'])->name('jobdescriptionapproval');
Route::get('createjobdescriptionhr/{id}', [JobdescriptionController::class, 'createnew'])->name('jobdescriptionapprovalhr');
Route::post('jobdescriptionsave', [JobdescriptionController::class, 'store'])->name('createjobdescription');
Route::post('jobdescriptionapprove', [JobdescriptionController::class, 'store'])->name('createjobdescription');
Route::get('approvedescriptionhr/{id}/{status}', [JobdescriptionController::class, 'descriptionapprovehr'])->name('createjobdescription');
Route::get('approvedescription/{id}/{status}', [JobdescriptionController::class, 'descriptionapprove'])->name('createjobdescription');
Route::get('jobdescription/delete/{id}', [JobdescriptionController::class, 'remove'])->name('createjobdescription');

//DESCRIPTION
Route::get('description', [JobdescriptionController::class, 'description'])->name('description');
Route::get('descriptionformgriddata', [JobdescriptionController::class, 'descriptionformgriddata'])->name('descriptionformgriddata');
Route::get('jobdescriptiondesc/delete/{id}', [JobdescriptionController::class, 'removedes'])->name('description');
Route::get('description/checkname', [JobdescriptionController::class, 'getCheckname'])->name('description');
Route::post('jobdescriptionsavedesc', [JobdescriptionController::class, 'storedesc'])->name('description');

//CREATE INTERVIEW
Route::get('createinterview', [InterviewscheduleController::class, 'create'])->name('createinterview');
Route::get('createinterview/{id}', [InterviewscheduleController::class, 'createsearch'])->name('createinterview');
Route::post('scheduleinterviewsave', [InterviewscheduleController::class, 'store'])->name('createinterview');
Route::get('getempdesignation/{id}', [InterviewscheduleController::class, 'getempdesignation'])->name('getempdesignation');
Route::get('interveiwschedulregriddata', [InterviewscheduleController::class, 'interveiwschedulregriddata'])->name('createinterview');
Route::post('saveselectionstatus', [InterviewscheduleController::class, 'save'])->name('createinterview');
Route::get('deleteschedule/delete', [InterviewscheduleController::class, 'destroy'])->name('createinterview');
Route::get('schedule_cancel/{id}', [InterviewscheduleController::class, 'cancelschedule'])->name('createinterview');

//ONBOARD PROCESS
Route::get('onboardprocess', [OnboardprocessController::class, 'index'])->name('onboardprocess');
Route::get('onboardlistdata', [OnboardprocessController::class, 'onboardlistdata'])->name('onboardlistdata');
Route::get('viewoffer/{id}/{type}', [OnboardprocessController::class, 'getprint'])->name('onboardprocess');
Route::get('costsheet/{id}/{type}', [OnboardprocessController::class, 'getctcprint'])->name('onboardprocess');
Route::get('viewappointment/{id}/{type}', [OnboardprocessController::class, 'getappoinmentprint'])->name('onboardprocess');
Route::get('offerdetails', [OnboardprocessController::class, 'getoffer'])->name('onboardprocess');

//PAY PROPOSAL & DOCUMENT UPLOAD
Route::post('savepayproposal', [OnboardprocessController::class, 'savepayproposal'])->name('payproposal');
Route::get(
    'acceptanceletter/{result}/{interviewid}',
    [OnboardprocessController::class, 'acceptanceletterupdate']
)->name('acceptanceletter');
Route::get(
    'convertemployee/{result}/{id1}/{id2}',
    [OnboardprocessController::class, 'converttoemployee']
)->name('convertemployee');
Route::get('empuploaddoc', [EmployeedocumentuploadController::class, 'index'])->name('empuploaddoc');
Route::get('employeedocumentgriddata', [EmployeedocumentuploadController::class, 'employeedocumentgriddata'])->name('employeedocumentgriddata');
Route::get('documentcreate/{id}', [EmployeedocumentuploadController::class, 'create'])->name('documentcreate');
Route::get('employeedocummentview/{id}', [EmployeedocumentuploadController::class, 'show'])->name('employeedocummentview');
Route::post('documentssave', [EmployeedocumentuploadController::class, 'store'])->name('employeedocument');

//Search Candidate
Route::get('searchcandidate', [SearchcandidateController::class, 'index'])->name('searchcandidate');
Route::get('searchcandidategrid', [SearchcandidateController::class, 'searchcandidategrid'])->name('searchcandidategrid');

Route::get('Interviewscheduled', [SearchscheduledinterviewController::class, 'index'])->name('Interviewscheduled');
Route::get('searchscheduledinterview', [SearchscheduledinterviewController::class, 'searchdata'])->name('searchscheduledinterview');
Route::get('changeinterviewdate/{id}/{ids}', [SearchscheduledinterviewController::class, 'changeinterviewdate'])->name('changeinterviewdate');

//New Leave Balance Report
Route::get('newleaverpt', [NewleavereportController::class, 'index'])->name('newleaverpt');
Route::get('Leavedata', [NewleavereportController::class, 'Leavedata'])->name('Leavedata');

// Marketing Attrition Report
Route::get('marketingattrition', [MarketingAttritionrptController::class, 'index'])->name('marketingattrition');
