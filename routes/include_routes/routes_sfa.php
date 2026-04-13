<?php
//outcome
Route::get('outcome', array('as' => '','check'=>'','menu'=>'outcome','label'=>'','uses' =>'OutcomeController@create'))->name('outcome');
Route::post('outcomenamesave',array('as' => '','check'=>'','menu'=>'outcome','label'=>'','uses' =>'OutcomeController@save'))->name('outcomenamesave'); 
Route::get('outcomegrid',array('as' => '','check'=>'','menu'=>'outcome','label'=>'','uses' =>'OutcomeController@outcomenamegriddata'))->name('outcomegrid'); 
Route::get('outcomeeditchk/{id}',array('as' => '','check'=>'','menu'=>'outcome','label'=>'','uses' =>'OutcomeController@outcomeeditchk'))->name('outcomeeditchk'); 
Route::get('outcometypecheckname',array('as' => '','check'=>'','menu'=>'outcome','label'=>'','uses' =>'OutcomeController@outcometypecheckname'))->name('outcometypecheckname'); 
Route::get('outcomedelete/{id}',array('as' => '','check'=>'delete','menu'=>'outcome','label'=>'','uses' =>'OutcomeController@delete'))->name('outcomedelete'); 

//institution
Route::get('institution', array('as' => '','check'=>'','menu'=>'institution','label'=>'','uses' =>'InstitutionController@index'))->name('institution');
Route::get('createinstitution', array('as' => 'createinstitute', 'check'=>'create','menu'=>'institution','label'=>'create', 'uses' => 'InstitutionController@create'));
Route::post('institutionsave', array('as' => '','check'=>'','menu'=>'','label'=>'save','uses' =>'InstitutionController@save'));
Route::get('institutionData',array('as' => '','check'=>'','menu'=>'','label'=>'','uses' =>'InstitutionController@institutionData'));
Route::get('institutionedit/{id}',array('as' => '','check'=>'Edit','menu'=>'institution','label'=>'edit','uses' =>'InstitutionController@create'));
//Route::get('userprofile/{id}',array('as' => '','check'=>'edit','menu'=>'userprofile','label'=>'edit','uses' =>'CreateuserController@create'));
Route::get('institutionview/{id}', array('as' => '','check'=>'View','menu'=>'user','label'=>'view','uses' =>'InstitutionController@show'))->name('institutionview');
// Tour Plan
Route::get('tourplan', array('as' => '','check'=>'','menu'=>'tourplan','label'=>'','uses' =>'TourplanController@index'))->name('tourplan');
Route::get('tourplanapproval', array('as' => '','check'=>'approve','menu'=>'tourplanapproval','label'=>'approve','uses' =>'TourplanController@approvalindex'))->name('tourplanapproval');
Route::get('gettourplanData', array('as' => '','check'=>'','menu'=>'','label'=>'','uses' =>'TourplanController@gettourplanData'))->name('gettourplanData');
Route::get('getapprovaltourplanData', array('as' => '','check'=>'','menu'=>'','label'=>'','uses' =>'TourplanController@getapprovaltourplanData'))->name('getapprovaltourplanData');
Route::get('tourplanapproval/gettourapprovaldata', array('as' => '','check'=>'','menu'=>'','label'=>'','uses' =>'TourplanController@gettourapprovaldata'));
Route::get('areadoctor/{id}', array('as' => '','check'=>'','menu'=>'','label'=>'','uses' =>'TourplanController@areadoctor'));
Route::get('calendar/{mth}/{yr}', array('as' => '','check'=>'','menu'=>'','label'=>'','uses' =>'TourplanController@calendar'));
Route::get('disdata', array('as' => '','check'=>'','menu'=>'','label'=>'','uses' =>'TourplanController@disdata'));

// Tour Plan Approval
Route::get('tourplancreate', array('as' => '','check'=>'create','menu'=>'tourplan','label'=>'create','uses' =>'TourplanController@create'))->name('tourplancreate');
Route::get('tourplanapproval/{id}', array('as' => '','check'=>'','menu'=>'','label'=>'','uses' =>'TourplanController@detailindex'))->name('tourplanapproval');
Route::get('touraeditdata/{dt}', array('as' => '','check'=>'','menu'=>'','label'=>'','uses' =>'TourplanController@touraeditdata'))->name('touraeditdata');
Route::get('tourplanapproval/tourplanedit/{id}', array('as' => '','check'=>'','menu'=>'','label'=>'','uses' =>'TourplanController@create'))->name('tourapproval');
Route::get('tourplanedit/{id}', array('as' => '','check'=>'','menu'=>'','label'=>'edit','uses' =>'TourplanController@create'))->name('tourapproval');
Route::get('tourplandelete/{id}', array('as' => '','check'=>'','menu'=>'','label'=>'','uses' =>'TourplanController@delete'));
Route::post('tourplansave', array('as' => '','check'=>'','menu'=>'','label'=>'','uses' =>'TourplanController@save'));
Route::get('tourplanview/{id}', array('as' => '','check'=>'view','menu'=>'','label'=>'view','uses' =>'TourplanController@show'));

Route::get('areareport/{id}', array('as' => '','check'=>'','menu'=>'','label'=>'','uses' =>'TourplanController@areareport'))->name('areareport');

//Doctor DCR
Route::get('doctordcr', array('as' => '','check'=>'','menu'=>'doctordcr','label'=>'','uses' =>'DoctordcrController@index'))->name('doctordcr');
Route::get('getdoctordcrData', array('as' => '','check'=>'','menu'=>'','label'=>'','uses' =>'DoctordcrController@getdoctordcrData'))->name('getdoctordcrData');
Route::get('doctordcrcreate/{id}', array('as' => '','check'=>'create','menu'=>'doctordcr','label'=>'create','uses' =>'DoctordcrController@create'))->name('doctordcrcreate');
Route::post('doctordcrsave', array('as' => '','check'=>'','menu'=>'','label'=>'','uses' =>'DoctordcrController@save'))->name('doctordcrsave');
Route::get('doctordcredit/{id}', array('as' => '','check'=>'edit','menu'=>'doctordcr','label'=>'edit','uses' =>'DoctordcrController@create'))->name('doctordcredit');
Route::get('doctordcrdelete/{id}', array('as' => '','check'=>'delete','menu'=>'','label'=>'delete','uses' =>'DoctordcrController@destroy'))->name('doctordcrdelete');
Route::get('doctordcrview/{id}', array('as' => '','check'=>'view','menu'=>'doctordcrview','label'=>'view','uses' =>'DoctordcrController@show'))->name('doctordcrview');
Route::get('focusproductdata/{id}',array('as' => '','check'=>'','menu'=>'','label'=>'','uses' =>'DoctordcrController@focusproductdata'));
Route::get('areas/{dt}/{id}',array('as' => '','check'=>'','menu'=>'','label'=>'','uses' =>'DoctordcrController@areas'));
Route::get('tourapprove/{dt}',array('as' => '','check'=>'','menu'=>'','label'=>'','uses' =>'DoctordcrController@tourapprove'));
Route::get('areawisedoctor/{id}/{dt}', array('as' => '','check'=>'','menu'=>'','label'=>'','uses' =>'DoctordcrController@areawisedoctor'));

//Doctor DCR
Route::get('doctor', array('as' => '','check'=>'','menu'=>'doctor','label'=>'','uses' =>'DoctorController@index'))->name('doctor');
Route::get('getdoctorData', array('as' => '','check'=>'','menu'=>'','label'=>'create','uses' =>'DoctorController@getdoctorData'))->name('getdoctorData');
Route::get('doctorcreate/{id}', array('as' => '','check'=>'create','menu'=>'doctor','label'=>'doctorcreate','uses' =>'DoctorController@create'))->name('doctorcreate');
Route::post('doctorsave', array('as' => '','check'=>'','menu'=>'','label'=>'','uses' =>'DoctorController@save'))->name('doctorsave');
Route::get('doctoredit/{id}', array('as' => '','check'=>'edit','menu'=>'doctor','label'=>'edit','uses' =>'DoctorController@create'))->name('doctoredit');
Route::get('doctorview/{id}', array('as' => '','check'=>'view','menu'=>'doctor','label'=>'view','uses' =>'DoctorController@show'))->name('doctorview');
Route::get('doctordelete/{id}', array('as' => '','check'=>'delete','menu'=>'doctor','label'=>'delete','uses' =>'DoctorController@destroy'))->name('doctordelete');
Route::get('/drphonechk/', array('as' => '','check'=>'','menu'=>'','label'=>'','uses' =>'DoctorController@getDrphonechk'));

// Area Report

Route::get('areareport', array('as' => '','check'=>'','menu'=>'','label'=>'','uses' =>'AreareportController@index'))->name('areareport');
Route::get('doctorreport',array('as' => '','check'=>'','menu'=>'','label'=>'','uses' =>'AreareportController@doctorreport'));
Route::get('chemistreport',array('as' => '','check'=>'','menu'=>'','label'=>'','uses' =>'AreareportController@chemistreport'));
Route::get('stockistreport',array('as' => '','check'=>'','menu'=>'','label'=>'','uses' =>'AreareportController@stockistreport'));
Route::get('tourplanreport',array('as' => '','check'=>'','menu'=>'','label'=>'','uses' =>'AreareportController@tourplanreport'));
Route::get('doccategory/{mth}',array('as' => '','check'=>'','menu'=>'','label'=>'','uses' =>'AreareportController@doccategory'));
Route::get('areachangerpt/{mth}',array('as' => '','check'=>'','menu'=>'','label'=>'','uses' =>'AreareportController@areachangerpt'));


// Chemist DCR
Route::get('chemistdcr', array('as' => '','check'=>'','menu'=>'chemistdcr','label'=>'','uses' =>'ChemistdcrController@index'))->name('chemistdcr');
Route::get('getchemistdcrData', array('as' => '','check'=>'','menu'=>'','label'=>'','uses' =>'ChemistdcrController@getchemistdcrData'))->name('getchemistdcrData');
Route::get('chemistdcrcreate/{id}', array('as' => '','check'=>'create','menu'=>'chemistdcr','label'=>'create','uses' =>'ChemistdcrController@create'))->name('chemistdcrcreate');
Route::post('chemistdcrsave', array('as' => '','check'=>'','menu'=>'','label'=>'','uses' =>'ChemistdcrController@save'))->name('chemistdcrsave');
Route::get('chemistdcredit/{id}', array('as' => '','check'=>'edit','menu'=>'chemistdcredit','label'=>'edit','uses' =>'ChemistdcrController@create'))->name('chemistdcredit');
Route::get('chemistdcrdelete/{id}', array('as' => '','check'=>'delete','menu'=>'chemistdcrdelete','label'=>'delete','uses' =>'ChemistdcrController@destroy'))->name('chemistdcrdelete');
Route::get('chemistdcrview/{id}', array('as' => '','check'=>'view','menu'=>'chemistdcrview','label'=>'view','uses' =>'ChemistdcrController@show'))->name('chemistdcrview');
Route::get('chemistdetail/{id}', array('as' => '','check'=>'','menu'=>'','label'=>'','uses' =>'ChemistdcrController@chemistdetail'))->name('chemistdetail');
Route::get('chemistareas/{dt}/{id}',array('as' => '','check'=>'','menu'=>'','label'=>'','uses' =>'ChemistdcrController@chemistareas'));

// Stockist DCR
Route::get('stockistdcr', array('as' => '','check'=>'','menu'=>'stockistdcr','label'=>'','uses' =>'StockistdcrController@index'))->name('stockistdcr');
Route::get('getstockistdcrData', array('as' => '','check'=>'','menu'=>'','label'=>'','uses' =>'StockistdcrController@getstockistdcrData'))->name('getstockistdcrData');
Route::get('stockistdcrcreate/{id}', array('as' => '','check'=>'create','menu'=>'stockistdcrcreate','label'=>'create','uses' =>'StockistdcrController@create'))->name('stockistdcrcreate');
Route::post('stockistdcrsave', array('as' => '','check'=>'','menu'=>'','label'=>'','uses' =>'StockistdcrController@save'))->name('stockistdcrsave');
Route::get('stockistdcredit/{id}', array('as' => '','check'=>'edit','menu'=>'stockistdcredit','label'=>'edit','uses' =>'StockistdcrController@create'))->name('stockistdcredit');
Route::get('stockistdcrdelete/{id}', array('as' => '','check'=>'delete','menu'=>'stockistdcrdelete','label'=>'delete','uses' =>'StockistdcrController@destroy'))->name('stockistdcrdelete');
Route::get('stockistdcrview/{id}', array('as' => '','check'=>'view','menu'=>'stockistdcrview','label'=>'view','uses' =>'StockistdcrController@show'))->name('stockistdcrview');
Route::get('stockistareas/{dt}/{id}',array('as' => '','check'=>'','menu'=>'','label'=>'','uses' =>'StockistdcrController@stockistareas'));
Route::get('stockistdetail/{id}',array('as' => '','check'=>'','menu'=>'','label'=>'','uses' =>'StockistdcrController@stockistdetail'));


// Stockist 
Route::get('stockist', array('as' => '','check'=>'','menu'=>'stockist','label'=>'','uses' =>'StockistController@index'))->name('stockist');
Route::get('getstockistData', array('as' => '','check'=>'','menu'=>'','label'=>'','uses' =>'StockistController@getstockistData'))->name('getstockistData');
Route::get('stockistcreate/{id}', array('as' => '','check'=>'create','menu'=>'stockist','label'=>'create','uses' =>'StockistController@create'))->name('stockistcreate');
Route::post('stockistsave', array('as' => '','check'=>'','menu'=>'','label'=>'','uses' =>'StockistController@save'))->name('stockistsave');
Route::get('stockistedit/{id}', array('as' => '','check'=>'edit','menu'=>'stockistedit','label'=>'edit','uses' =>'StockistController@create'))->name('stockistedit');
Route::get('stockistdelete/{id}', array('as' => '','check'=>'delete','menu'=>'stockistdelete','label'=>'delete','uses' =>'StockistController@destroy'))->name('stockistdelete');
Route::get('stockistview/{id}', array('as' => '','check'=>'view','menu'=>'stockistview','label'=>'view','uses' =>'StockistController@show'))->name('stockistview');
Route::get('stockistareadoctor/{id}', array('as' => '','check'=>'','menu'=>'','label'=>'','uses' =>'StockistController@stockistareadoctor'))->name('stockistareadoctor');


// Chemist 
Route::get('chemist', array('as' => '','check'=>'','menu'=>'chemist','label'=>'','uses' =>'ChemistController@index'))->name('chemist');
Route::get('getchemistData', array('as' => '','check'=>'','menu'=>'','label'=>'','uses' =>'ChemistController@getchemistData'))->name('getchemistData');
Route::get('chemistcreate/{id}', array('as' => '','check'=>'create','menu'=>'chemist','label'=>'create','uses' =>'ChemistController@create'))->name('chemistcreate');
Route::post('chemistsave', array('as' => '','check'=>'','menu'=>'','label'=>'','uses' =>'ChemistController@save'))->name('chemistsave');
Route::get('chemistedit/{id}', array('as' => '','check'=>'edit','menu'=>'chemistedit','label'=>'edit','uses' =>'ChemistController@create'))->name('chemistedit');
Route::get('chemistdelete/{id}', array('as' => '','check'=>'delete','menu'=>'chemistdelete','label'=>'delete','uses' =>'ChemistController@destroy'))->name('chemistdelete');
Route::get('chemistview/{id}', array('as' => '','check'=>'view','menu'=>'chemistview','label'=>'view','uses' =>'ChemistController@show'))->name('chemistview');
Route::get('chemistareadoctor/{id}', array('as' => '','check'=>'','menu'=>'','label'=>'','uses' =>'ChemistController@chemistareadoctor'))->name('chemistareadoctor');
Route::get('chemistdoctordetail/{id}/{area}', array('as' => '','check'=>'','menu'=>'','label'=>'','uses' =>'ChemistController@chemistdoctordetail'))->name('chemistdoctordetail');

// missed call report
Route::get('missedcallreport', array('as' => '','check'=>'','menu'=>'','label'=>'','uses' =>'MissedcallreportController@index'))->name('missedcallreport');
Route::get('getmcrData', array('as' => '','check'=>'','menu'=>'','label'=>'','uses' =>'MissedcallreportController@getmcrData'))->name('getmcrData');
Route::get('missedcallreportcreate/{id}', array('as' => '','check'=>'create','menu'=>'missedcallreportcreate','label'=>'create','uses' =>'MissedcallreportController@create'))->name('missedcallreportcreate');
Route::get('mcrreport/{date}', array('as' => '','check'=>'','menu'=>'','label'=>'','uses' =>'MissedcallreportController@mcrreport'));

// Chemist Stockist Report 

Route::get('stockreport/{id}/{mth}',array('as' => '','check'=>'','menu'=>'','label'=>'','uses' =>'ChemiststockreportController@stockreport'));
Route::get('stockiststockreport',array('as' => '','check'=>'','menu'=>'','label'=>'','uses' =>'ChemiststockreportController@index'));
Route::post('stockreportsave', array('as' => '','check'=>'','menu'=>'','label'=>'','uses' =>'ChemiststockreportController@save'));

// Mapping
Route::get('mapping',array('as' => '','check'=>'','menu'=>'mapping','label'=>'','uses' =>'MappingController@index'));
Route::get('locationdata/{id}/{dt}',array('as' => '','check'=>'','menu'=>'','label'=>'','uses' =>'MappingController@locationdata'));

// Expenses 

Route::get('sfaexpenses',array('as' => '','check'=>'','menu'=>'sfaexpenses','label'=>'','uses' =>'SfaexpensesController@index'))->name('sfaexpenses');
Route::get('getsfaexpensesData',array('as' => '','check'=>'','menu'=>'','label'=>'','uses' =>'SfaexpensesController@getsfaexpensesData'))->name('getsfaexpensesData');
Route::get('sfaexpensesdata/{mth}/{yr}',array('as' => '','check'=>'','menu'=>'','label'=>'','uses' =>'SfaexpensesController@sfaexpensesdata'))->name('sfaexpensesdata');
Route::get('sfaexpensescreate',array('as' => '','check'=>'create','menu'=>'sfaexpensescreate','label'=>'create','uses' =>'SfaexpensesController@create'))->name('sfaexpensescreate');
Route::post('sfaexpensessave',array('as' => '','check'=>'','menu'=>'','label'=>'','uses' =>'SfaexpensesController@save'))->name('sfaexpensessave');
Route::get('sfaexpensesview/{id}',array('as' => '','check'=>'view','menu'=>'sfaexpensesview','label'=>'view','uses' =>'SfaexpensesController@view'))->name('sfaexpensesview');


/*Activity*/
 //Route::get('gstrequired/{id}',array('as' => '','check'=>'','menu'=>'activity', 'label'=>'','uses' =>'ActivityController@gstrequired'));
 Route::get('activity',array('as' => '','check'=>'','menu'=>'', 'label'=>'','uses' =>'ActivityController@index'));
 Route::get('activitydata',array('as' => '','check'=>'','menu'=>'activity', 'label'=>'','uses' =>'ActivityController@getactivitydata'));  Route::get('/activity/edit/',array('as' => '','check'=>'editdata','menu'=>'activity', 'label'=>'','uses' =>'ActivityController@getShow'));
 Route::get('/activity/checkname/',array('as' => '','check'=>'','menu'=>'activity', 'label'=>'','uses' =>'ActivityController@getCheckname'));
  Route::post('activity/save',array('as' => '','check'=>'','menu'=>'activity', 'label'=>'','uses' =>'ActivityController@save'));
  Route::get('activity/{id}', array('as' => '','check'=>'','menu'=>'activity', 'label'=>'','uses' =>'ActivityController@show'));
  Route::get('activity/delete/{id}',array('as' => '','check'=>'delete','menu'=>'activity', 'label'=>'','uses' =>'ActivityController@getRemove'));
 Route::get('activityedit/{id}',array('as' => '','check'=>'edit','menu'=>'activity', 'label'=>'','uses' =>'ActivityController@getedit'))->name('activityedit');
/*Activity*/

// Institution Dcr
Route::get('institutiondcr', array('as' => '','check'=>'','menu'=>'institutiondcr','label'=>'','uses' =>'InstitutiondcrController@index'))->name('user');
Route::get('createinstitutiondcr', array('as' => 'createuser', 'check'=>'create','menu'=>'institutiondcr','label'=>'create', 'uses' => 'InstitutiondcrController@create'));
Route::post('institutiondcrsave', array('as' => '','check'=>'','menu'=>'','label'=>'save','uses' =>'InstitutiondcrController@save'));
Route::get('institutiondcrData',array('as' => '','check'=>'','menu'=>'','label'=>'','uses' =>'InstitutiondcrController@institutiondcrData'));
Route::get('institutiondcredit/{id}',array('as' => '','check'=>'Edit','menu'=>'user','label'=>'edit','uses' =>'InstitutiondcrController@create'));
// Route::get('userprofile/{id}',array('as' => '','check'=>'edit','menu'=>'userprofile','label'=>'edit','uses' =>'CreateuserController@create'));
// Route::get('userview/{id}', array('as' => '','check'=>'View','menu'=>'user','label'=>'view','uses' =>'CreateuserController@show'))->name('userview');















//HRMS PARTIAL DAY
Route::get('partialday',array('as' => '','check'=>'','menu'=>'partialday','label'=>'','uses' =>'LeaveapplicationController@indexpartial'))->name('partialday');
Route::get('partialday/delete',array('as' => '','check'=>'','menu'=>'partialday','label'=>'','uses' =>'LeaveapplicationController@partialdestroy'))->name('partialday');
Route::post('partialsave',array('as' => '','check'=>'','menu'=>'partialday','label'=>'','uses' =>'LeaveapplicationController@partialsave'))->name('partialday');




/***casual  leave Entry **/

 Route::get('casualleave',array('as' => '','check'=>'','menu'=>'casualleave','label'=>'','uses' =>'CasualleaveController@index'))->name('casualleave');
Route::post('casualleave/save',array('as' => '','check'=>'saveform','menu'=>'casualleave','label'=>'','uses' =>'CasualleaveController@save'))->name('casualleave');
Route::get('casualleavegrid',array('as' => '','check'=>'','menu'=>'casualleave','label'=>'','uses' =>'CasualleaveController@getcasualleave'))->name('casualleave');
Route::get('/casualleave/checkname/',array('as' => '','check'=>'','menu'=>'casualleave','label'=>'checkname','uses' =>'CasualleaveController@getCheckyear'))->name('casualleave');
Route::get('/casualeave/delete/',array('as' => '','check'=>'delete','menu'=>'casualleave','label'=>'delete','uses' =>'CasualleaveController@destroy'))->name('casualleave');
Route::get('casualeave/{id}',array('as' => '','check'=>'edit','menu'=>'casualleave','label'=>'edit','uses' =>'CasualleaveController@show'))->name('casualleave');

/***






/*** Attendance Setting  ***/

Route::post('shifttimingssave',array('as' => '','check'=>'','menu'=>'shifttimingssave','label'=>'','uses' =>'ShiftdetailController@store'))->name('attendancesync');
Route::get('ot', array('as' => '','check'=>'','menu'=>'ot','label'=>'','uses' =>'ShiftdetailController@otindex'))->name('ot');
Route::get('otcategorycheckid',array('as' => '','check'=>'','menu'=>'','label'=>'','uses' =>'ShiftdetailController@Checkcategory'))->name('ot');
Route::get('otData', array('as' => '','check'=>'','menu'=>'','label'=>'','uses' =>'ShiftdetailController@otData'))->name('otData');
Route::post('otsave', array('as' => '','check'=>'','menu'=>'','label'=>'','uses' =>'ShiftdetailController@otsave'))->name('otsave');
Route::get('otdelete/{id}', array('as' => '','check'=>'','menu'=>'','label'=>'','uses' =>'ShiftdetailController@otdelete'))->name('otdelete');


/** investment declaration***/
Route::get('declareinvestment',array('as' => '','check'=>'','menu'=>'Investment Declaration','label'=>'','uses' =>'InvestmentController@index'))->name('declareinvestment');
Route::get('getDeclaration/{id}',array('as' => '','check'=>'','menu'=>'Investment Declaration','label'=>'edit','uses' =>'InvestmentController@getDeclaration'))->name('');
Route::post('investmentsave',array('as' => '','check'=>'','menu'=>'Investment Declaration','label'=>'','uses' =>'InvestmentController@store'))->name('');

//Proof Submission
Route::get('proofsubmission',array('as' => '','check'=>'','menu'=>'upload Investment Proof','label'=>'','uses' =>'InvestmentController@proofsubmission'))->name('');
Route::get('proofview',array('as' => '','check'=>'','menu'=>'View Uploaded Documents','label'=>'','uses' =>'InvestmentController@proofview'))->name('');
Route::get('proofviewgriddata',array('as' => '','check'=>'','menu'=>'proofsubmission','label'=>'','uses' =>'InvestmentController@proofviewgriddata'))->name('');
Route::get('empview/{id}',array('as' => '','check'=>'','menu'=>'View Uploaded Documents','label'=>'edit','uses' =>'InvestmentController@empview'))->name('');
Route::post('proofsubmissionsave',array('as' => '','check'=>'','menu'=>'View Uploaded Documents','label'=>'','uses' =>'InvestmentController@storeproof'))->name('');

/** setting ***/

/** attendancereport ***/
Route::get('attendancereport',array('as' => '','check'=>'','menu'=>'attendancereport','label'=>'','uses' =>'AttendancereportController@index'))->name('');
Route::get('/attendancemonthly/',array('as' => '','check'=>'','menu'=>'attendancereport','label'=>'','uses' =>'AttendancereportController@attendancemonthlyreport'))->name('');








//employee permission slip
Route::get('permissionslip/{id}', array('as' => '','check'=>'','menu'=>'leave','label'=>'','uses' =>'LeaveapplicationController@permissionslipprint'))->name('permissionslipprint');






//Payment Batches
Route::get('paymentbatches',array('as' => '','check'=>'','menu'=>'paymentbatches','label'=>'','uses' => 'PaymentbatchesController@index'))->name('paymentbatches');
Route::get('paymentbatchescreate', array('as' => '','check'=>'create','menu'=>'paymentbatches','label'=>'create','uses' =>'PaymentbatchesController@create'))->name('paymentbatchescreate');
Route::get('paymentbatchescreate/{id}',array('as' => '','check'=>'create','menu'=>'paymentbatches','label'=>'Create','uses' => 'PaymentbatchesController@create'))->name('paymentbatchescreate');
Route::get('paymentbatchesview/{id}', array('as' => '','check'=>'view','menu'=>'paymentbatches','label'=>'View','uses' =>'PaymentbatchesController@show'))->name('paymentbatchesview');
Route::post('paymentbatchessave', array('as' => '','check'=>'','menu'=>'paymentbatches','label'=>'','uses' =>'PaymentbatchesController@save'))->name('paymentbatchessave');
Route::get('getPaymentbatchesData', array('as' => '','check'=>'','menu'=>'paymentbatches','label'=>'','uses' =>'PaymentbatchesController@getPaymentbatchesData'));
Route::get('paymentbatchesdelete/{id}',array('as' => '','check'=>'delete','menu'=>'paymentbatches','label'=>'Delete','uses' =>'PaymentbatchesController@delete'))->name('paymentbatchesdelete');
Route::get('getpoinvamount/{id}', array('as' => '','check'=>'','menu'=>'paymentbatches','label'=>'','uses' =>'PaymentbatchesController@getpoinvamount'))->name('getpoinvamount');

//Payments

Route::get('payments',array('as' => '','check'=>'','menu'=>'payments','label'=>'','uses' => 'PaymentsController@index'))->name('payments');
Route::get('paymentscreate',array('as' => '','check'=>'','menu'=>'payments','label'=>'','uses' => 'PaymentsController@create'))->name('paymentscreate');
Route::get('paymentscreate/{id}',array('as' => '','check'=>'','menu'=>'payments','label'=>'','uses' => 'PaymentsController@create'))->name('paymentscreate');
Route::get('paymentsview/{id}', array('as' => '','check'=>'','menu'=>'payments','label'=>'','uses' =>'PaymentsController@show'))->name('paymentsview');
Route::post('paymentssave', array('as' => '','check'=>'','menu'=>'payments','label'=>'','uses' =>'PaymentsController@save'))->name('paymentssave');
Route::get('getPaymentsData', array('as' => '','check'=>'','menu'=>'payments','label'=>'','uses' =>'PaymentsController@getPaymentsData'));
Route::get('getbatchdetails/{id}', array('as' => '','check'=>'','menu'=>'getbatchdetails','label'=>'','uses' =>'PaymentsController@getbatchdetails'))->name('getbatchdetails');
Route::get('batchinvoicedetails/{id}', array('as' => '','check'=>'','menu'=>'batchinvoicedetails','label'=>'','uses' =>'PaymentsController@batchinvoicedetails'))->name('batchinvoicedetails');
Route::get('getPaymentdetails/{id}/{ids}/{idss}/{idsss}', array('as' => '','check'=>'','menu'=>'payments','label'=>'','uses' =>'PaymentsController@getPaymentdetails'))->name('getPaymentdetails');
Route::get('challanreference', array('as' => '','check'=>'','menu'=>'','label'=>'','uses' =>'PaymentdetailsController@challanreference'))->name('challanreference');
Route::get('batchinvoicedetails/{id}',array('as' => '','check'=>'','menu'=>'batchinvoicedetails','label'=>'','uses' => 'PaymentsController@batchinvoicedetails'))->name('batchinvoicedetails');
Route::get('getPaymentdetails/{id}/{ids}/{idss}/{idsss}', array('as' => '','check'=>'','menu'=>'payments','label'=>'','uses' =>'PaymentsController@getPaymentdetails'))->name('getPaymentdetails');
Route::get('getPaymentsData', array('as' => '','check'=>'','menu'=>'payments','label'=>'','uses' =>'PaymentsController@getPaymentsData'));
Route::get('getbatchdetails/{id}', array('as' => '','check'=>'','menu'=>'getbatchdetails','label'=>'','uses' =>'PaymentsController@getbatchdetails'))->name('getbatchdetails');
Route::get('challanrefupdate', array('as' => '','check'=>'','menu'=>'','label'=>'','uses' =>'PaymentdetailsController@challanrefupdate'))->name('challanrefupdate');


/* Debit Note */
Route::get('accountdebitnote', array('as' => '','check'=>'','menu'=>'accountdebitnote','label'=>'','uses' =>'AccountdebitnoteController@index'));
Route::get('getaccountdebitData', array('as' => '','check'=>'','menu'=>'accountdebitnote','label'=>'','uses' =>'AccountdebitnoteController@getaccountdebitData'));
Route::get('poinvoiceData', array('as' => '','check'=>'','menu'=>'accountdebitnote','label'=>'','uses' =>'AccountdebitnoteController@poinvoiceData'));
Route::get('poinvoicetable', array('as' => '','check'=>'','menu'=>'accountdebitnote','label'=>'','uses' =>'AccountdebitnoteController@poinvoicetable'));
Route::get('accountdebitnotecreate/{id}', array('as' => '','check'=>'','menu'=>'accountdebitnote','label'=>'','uses' =>'AccountdebitnoteController@accountdebitnotecreate'));
Route::post('accountdebitnotesave', array('as' => '','check'=>'','menu'=>'accountdebitnote','label'=>'','uses' =>'AccountdebitnoteController@accountdebitnotesave'));
Route::get('accountdebitnoteedit/{id}', array('as' => '','check'=>'edit','menu'=>'accountdebitnote','label'=>'edit','uses' =>'AccountdebitnoteController@accountdebitnoteedit'));
Route::get('accountdebitnoteview/{id}', array('as' => '','check'=>'view','menu'=>'accountdebitnote','label'=>'view','uses' =>'AccountdebitnoteController@accountdebitnoteview'));





//Quote Attachment
Route::post('poquoteattachment',array('as' => '','check'=>'','menu'=>'poquoteattachment','label'=>'','uses' =>'PurchasequotationController@poquoteattachment'))->name('poquoteattachment');
Route::get('poquoteattachmentdata/{id}',array('as' => '','check'=>'','menu'=>'poquoteattachment','label'=>'','uses' =>'PurchasequotationController@poquoteattachmentdata'))->name('poquoteattachment');

Route::get('getpodetailss/{id}',array('as' => '','check'=>'','menu'=>'','label'=>'','uses' =>'GoodsreceiptnoteController@getpodetails'))->name('getpodetails');

/*Po Amendment*/
Route::get('poamendment', array('as' => '','check'=>'','menu'=>'poamendment', 'label'=>'poamendment','uses' =>'PurchaseorderController@index'))->name('poamendment');
Route::get('poamendmentcreate/{id}/{type}/{condition}', array('as' => '','check'=>'amendment','menu'=>'poamendment','label'=>'', 'uses' => 'PurchaseorderController@create'));
Route::get('poamendmentcreate/{id}/{condition}', array('as' => '','check'=>'','menu'=>'poamendment','label'=>'','uses' =>'PoamendmentController@create'))->name('poamendmentcreate');
Route::get('poamendmentcreate/{id}',array('as' => '','check'=>'','menu'=>'poamendment','label'=>'','uses' => 'PoamendmentController@create'))->name('poamendmentcreate');
Route::post('poamendmentsave', array('as' => '','check'=>'','menu'=>'poamendment','label'=>'','uses' =>'PoamendmentController@save'));


Route::get('getpartno', array('as' => '','check'=>'','menu'=>'','label'=>'','uses' =>'PurchaseenquiryController@getpartno'));
Route::get('getaddress', array('as' => '','check'=>'','menu'=>'','label'=>'','uses' =>'PurchaseorderController@getaddress'));

Route::get('/supplierpartno/',array('as' => '','check'=>'','menu'=>'supplierpartno','label'=>'','uses' =>'PurchaseorderController@supplierpartno'));



//Budgets
Route::get('budgets',array('as' => '','check'=>'','menu'=>'budgets','label'=>'','uses' =>  'BudgetsController@index'))->name('budgets');
Route::get('budgetscreate',array('as' => '','check'=>'create','menu'=>'budgets','label'=>'Create','uses' =>  'BudgetsController@create'))->name('budgetscreate');
Route::get('budgetsdetailcreate/{id}',array('as' => '','check'=>'detailcreate','menu'=>'budgets','label'=>'detailcreate','uses' =>'BudgetsController@detailcreate'))->name('budgetsdetailcreate');
Route::get('budgetscreate/{id}',array('as' => '','check'=>'edit','menu'=>'budgets','label'=>'edit','uses' =>'BudgetsController@create'))->name('budgetscreate');
Route::get('budgetsview/{id}', array('as' => '','check'=>'','menu'=>'budgets','label'=>'','uses' =>'BudgetsController@show'))->name('budgetsview');
Route::post('budgetssave', array('as' => '','check'=>'','menu'=>'budgets','label'=>'','uses' =>'BudgetsController@save'))->name('budgetssave');
Route::post('budamtsave', array('as' => '','check'=>'','menu'=>'budgets','label'=>'','uses' =>'BudgetsController@budamtsave'))->name('budamtsave');
Route::get('getBudgetsData', array('as' => '','check'=>'','menu'=>'budgets','label'=>'','uses' =>'BudgetsController@getBudgetsData'));
Route::get('getBudgetsData',array('as' => '','check'=>'','menu'=>'budgets','label'=>'','uses' =>'BudgetsController@getBudgetsData'));
Route::get('monthwisebudget/{id}/{ids}',array('as' => '','check'=>'budgets','menu'=>'','label'=>'','uses' =>'BudgetsController@monthwisebudget'));
Route::get('budgetdata/{id}',array('as' => '','check'=>'','menu'=>'budgets','label'=>'','uses' =>'BudgetsController@budgetdata'));
Route::get('budgetlines/{id}',array('as' => '','check'=>'','menu'=>'budgets','label'=>'','uses' =>'BudgetsController@budgetlines'));
Route::get('budgetdetails/{id}/{ids}',array('as' => '','check'=>'detailcreate','menu'=>'budgets','label'=>'detailcreate','uses' =>'BudgetsController@budgetdetails'));

//Budgets Approval
Route::get('budgetsapproval',array('as' => '','check'=>'','menu'=>'budgetsapproval','label'=>'','uses' => 'BudgetsapprovalController@index'))->name('budgetsapproval');
Route::get('getBudgetsapprovalData',array('as' => '','check'=>'','menu'=>'budgetsapproval','label'=>'','uses' =>  'BudgetsapprovalController@getBudgetsapprovalData'));
Route::get('getbudgetapproval/{id}/{ids}',array('as' => '','check'=>'approve','menu'=>'budgetsapproval','label'=>'approve','uses' => 'BudgetsapprovalController@getbudgetapproval'))->name('budgetsapproval');


//Payment Statement Update
Route::get('statementupdate',array('as' => '','check'=>'','menu'=>'', 'label'=>'','uses' =>'PaymentdetailsController@statementupdate'))->name('productaccountsettings');
Route::get('receiptupdate',array('as' => '','check'=>'','menu'=>'', 'label'=>'','uses' =>'PaymentdetailsController@receiptupdate'))->name('productaccountsettings');


Route::get('abc',array('as' => '','check'=>'','menu'=>'abc','label'=>'','uses' =>'DepreciationmethodController@index'))->name('abc');

//Budgets Review
Route::get('budgetreview',array('as' => '','check'=>'','menu'=>'budgetreview','label'=>'','uses' => 'BudgetsapprovalController@index'))->name('budgetreview');
Route::get('getBudgetsreviewData', array('as' => '','check'=>'','menu'=>'budgetreview','label'=>'','uses' =>'BudgetsapprovalController@getBudgetsreviewData'));
Route::get('budgetreview/{id}',array('as' => '','check'=>'view','menu'=>'budgetreview','label'=>'view','uses' =>'BudgetsapprovalController@show'))->name('budgetreview');
//Route::get('getbudgetapproval/{id}/{ids}', 'BudgetsapprovalController@getbudgetapproval')->name('budgetsapproval');

//qa stage approval
Route::get('qualitycheck',array('as' => '','check'=>'','menu'=>'qualitycheck','label'=>'Quality Check','uses' =>'QualitycheckController@index'))->name('qualitycheck');
Route::get('totalqualitycheck',array('as' => '','check'=>'','menu'=>'qualitycheck','label'=>'Quality Check','uses' =>'QualitycheckController@index'))->name('totalqualitycheck');
Route::get('qasubmitstageappData',array('as' => '','check'=>'','menu'=>'','label'=>'','uses' =>'QualitycheckController@qasubmitstageappData'))->name('qasubmitstageappData');
Route::get('qualitycheckcreate/{id}/{type}',array('as' => '','check'=>'qualitycheck','menu'=>'qualitycheck','label'=>'qualitycheck','uses' =>'QualitycheckController@create'))->name('qualitycheckcreate');
Route::get('qualitycheckappcreate/{id}/{type}',array('as' => '','check'=>'approve','menu'=>'qaapproval','label'=>'Approve','uses' =>'QualitycheckController@create'))->name('qualitycheckcreate');
//quality analytical
Route::get('qcanalytical',array('as' => '','check'=>'','menu'=>'qcanalytical','label'=>'Quality Analytical','uses' =>'QualitycheckController@index'))->name('qcanalytical');

Route::post('qualitychecksave',array('as' => '','check'=>'','menu'=>'producttype','label'=>'','uses' =>'QualitycheckController@save'));
Route::get('qualitycheckview',array('as' => '','check'=>'','menu'=>'qualitycheckview','label'=>'','uses' =>'QualitycheckController@viewindex'))->name('qualitycheckview');
Route::get('qualitycheckview/{id}',array('as' => '','check'=>'','menu'=>'qualitycheckview','label'=>'','uses' =>'QualitycheckController@view'))->name('qualitycheckview');
Route::get('qualitycheckprint/{id}','QualitycheckController@print')->name('qualitycheckprint');
Route::get('qualitycheckData',array('as' => '','check'=>'','menu'=>'qualitycheckdetails','label'=>'','uses' =>'QualitycheckController@qualitycheckData'))->name('qualitycheckData');

Route::get('producttypeeditchk/{id}',array('as' => '','check'=>'editdata','menu'=>'producttype','label'=>'Edit','uses' =>'ProducttypeController@producttypeeditchk'))->name('producttypeeditchk');
Route::get('productpackeditchk/{id}',array('as' => '','check'=>'editdata','menu'=>'producttype','label'=>'Edit', 'uses' =>'ProductpackController@productpackeditchk'))->name('productpackeditchk');
Route::get('productvarianteditchk/{id}',array('as' => '','check'=>'editdata','menu'=>'productvariant','label'=>'Edit','uses'=>'ProductvariantController@productvarianteditchk'))->name('productvarianteditchk');



//indent material issue
Route::get('indentmaterialissue',array('as' => '','check'=>'','menu'=>'indentmaterialissue','label'=>'','uses' => 'QualityindentController@indentmaterialissue'))->name('indentmaterialissue');
Route::get('indentmaterialissuedata',array('as' => '','check'=>'','menu'=>'indentmaterialissue','label'=>'','uses' =>'QualityindentController@indentmaterialissuedata'))->name('indentmaterialissuedata');
Route::get('indentmaterialissuecreate/{id}', array('as' => '','check'=>'qualityindentcreate','label'=>'Qualityindentcreate','menu'=>'indentmaterialissue','uses' =>'QualityindentController@create'))->name('create');
Route::get('getindentquantity/{id}',array('as' => '','check'=>'','menu'=>'','label'=>'','uses' =>'QualityindentController@getindentquantity'))->name('getindentquantity');




Route::get('materialissueindentview/{id}',array('as' => '','check'=>'view','menu'=>'indentmaterialissue','label'=>'View','uses' =>'MaterialrequirementController@show'))->name('indentmaterialissueview');

// Material Requirement
Route::get('materialrequirement',array('as' => '','check'=>'','menu'=>'materialrequirement','label'=>'Material Requirement','uses' =>'MaterialrequirementController@index'))->name('materialrequirement');
Route::get('getmaterialreqdata',array('as' => '','check'=>'','menu'=>'materialrequirement','label'=>'','uses' =>'MaterialrequirementController@getmaterialreqdata'))->name('getmaterialreqdata');
Route::post('materialrequirementsave',array('as' => '','check'=>'','menu'=>'materialrequirement','label'=>'','uses' =>'MaterialrequirementController@save'))->name('materialrequirementsave');
Route::get('uomvalue/{id}',array('as' => '','check'=>'','menu'=>'','label'=>'','uses' =>'MaterialrequirementController@uomvalue'))->name('uomvalue');
Route::get('materialreqview/{id}',array('as' => '','check'=>'view','menu'=>'materialrequirement','label'=>'View','uses' =>'MaterialrequirementController@show'))->name('materialreqview');
Route::get('materialreqdelete/{id}', array('as' => '','check'=>'delete','label'=>'Delete','menu'=>'materialrequirement','uses' =>'MaterialrequirementController@destroy'))->name('materialreqdelete');
Route::get('materialedit/{id}',array('as' => '','check'=>'edit','menu'=>'materialrequirement','label'=>'Edit','uses' => 'MaterialrequirementController@getedit'))->name('materialedit');

Route::get('concatenateproductcheck', array('as' => '','check'=>'','menu'=>'materialrequirement','label'=>'','uses' => 'ProductController@concatenateproductcheck'))->name('concatenateproductcheck');
Route::get('concatenateproductchecksingle',array('as' => '','check'=>'','menu'=>'materialrequirement','label'=>'','uses' => 'ProductController@concatenateproductchecksingle'))->name('concatenateproductcheck');
Route::get('productcodecheck',array('as' => '','check'=>'','menu'=>'materialrequirement','label'=>'','uses' => 'ProductController@productcodecheck'))->name('productcodecheck');
Route::get('productcodechecklines',array('as' => '','check'=>'','menu'=>'materialrequirement','label'=>'','uses' => 'ProductController@productcodechecklines'))->name('productcodechecklines');

/*Create SUBCONTRACT Supplier*/
Route::get('subcontractsupplier',array('as' => '','check'=>'','menu'=>'subcontractsupplier','label'=>'Subcontract Supplier','uses' =>'SubcontractsupplierController@index'))->name('supplier');
Route::get('subcontractcreate/{id}', array('as' => '','check'=>'create','menu'=>'subcontractsupplier','label'=>'Create','uses' => 'SubcontractsupplierController@create'));
Route::get('subcontractedit/{id}',array('as' => '','check'=>'edit','menu'=>'subcontractsupplier','label'=>'Edit', 'uses' => 'SubcontractsupplierController@edit'));
Route::get('subcontractview/{id}',array('as' => '','check'=>'view','menu'=>'subcontractsupplier','label'=>'View','uses' => 'SubcontractsupplierController@show'));
Route::get('subcontractdelete/{id}',array('as' => '','check'=>'delete','menu'=>'subcontractsupplier','label'=>'Delete','uses' =>'SubcontractsupplierController@delete'))->name('supplierdelete');

Route::get('gst_value/{id}', array('as' => '','check'=>'','menu'=>'subcontractsupplier','label'=>'','uses' =>'SubcontractsupplierController@gst_value'))->name('gst_value');
Route::get('suppliertypegst/{id}', array('as' => '','check'=>'','menu'=>'subcontractsupplier','label'=>'','uses' =>'SubcontractsupplierController@suppliertypegst'))->name('suppliertypegst');
Route::post('subcontractsave',array('as' => '','check'=>'','menu'=>'subcontractsupplier','label'=>'','uses' =>'SubcontractsupplierController@resave'))->name('suppliersave');
Route::get('getsubcontractsupplierData',array('as' => '','check'=>'','menu'=>'subcontractsupplier','label'=>'','uses' =>'SubcontractsupplierController@getsubcontractsupplierData'));
Route::get('gstvalidation/{id}',array('as' => '','check'=>'','menu'=>'subcontractsupplier','label'=>'','uses' =>'SubcontractsupplierController@gstvalidation'));
Route::get('gstduplicate/{id}',array('as' => '','check'=>'','menu'=>'subcontractsupplier','label'=>'','uses' =>'SubcontractsupplierController@gstduplicate'))->name('gstduplicate');
Route::get('customerdetails/{id}',array('as' => '','check'=>'','menu'=>'subcontractsupplier','label'=>'','uses' =>'SubcontractsupplierController@customerdetails'))->name('customerdetails');
Route::get('suppliernamechk/{id}',array('as' => '','check'=>'','menu'=>'subcontractsupplier','label'=>'', 'uses' =>'SubcontractsupplierController@suppliernamechk'))->name('suppliernamechk');

//Jobworkoutorder Dispatch
Route::get('jobwoodispatch', array('as' => '','check'=>'','menu'=>'jobwoodispatch','label'=>'Job Workorder Dispatch', 'uses' =>'JobwoodispatchController@index'))->name('jobwoodispatch');
Route::get('getJobwoodispatchData/{id}',array('as' => '','check'=>'','menu'=>'jobwoodispatch','label'=>'', 'uses' =>'JobwoodispatchController@getJobwoodispatchData'));
Route::get('jobdispatchcreate/{id}',array('as' => '','check'=>'','menu'=>'jobwoodispatch','label'=>'', 'uses' =>'JobwoodispatchController@create'))->name('jobdispatchcreate');
Route::post('jobdispatchsave',array('as' => '','check'=>'','menu'=>'jobwoodispatch','label'=>'', 'uses' =>'JobwoodispatchController@save'))->name('jobdispatchsave');


//indent requisition
Route::get('indentrequisition',array('as' => '','check'=>'','menu'=>'indentrequisition','label'=>'Indent Requisition', 'uses' =>'ProductionindenthdrController@index'))->name('indentrequisition');





// maintainance
Route::post('targetuploaddata',array('as' => '','check'=>'','menu'=>'Targets','label'=>'','uses' =>'TargetsController@Uploadexcel'));
Route::get('gettargetuploaddata',array('as' => '','check'=>'','menu'=>'productupload','label'=>'', 'uses' =>'TargetsController@targetuploaddata'))->name('gettargetuploaddata');

Route::post('targetuploadsave',array('as' => '','check'=>'','label' => '','menu'=>'Targets','uses' => 'TargetsController@store'))->name('targetuploadsave');
Route::get('getawdvalidate',array('as' => '','check'=>'','label' => '','menu'=>'','uses' => 'TargetsController@getawdvalidate'))->name('getawdvalidate');
Route::get('getdistributorvalidate',array('as' => '','check'=>'','label' => '','menu'=>'','uses' => 'TargetsController@getdistributorvalidate'))->name('getawdvalidate');



Route::get('timeformatsview',array('as' => '','check'=>'','menu'=>'','label'=>'','uses' =>'TimeformatsController@view'));
Route::get('timeformatdelete/{id}',array('as' => '','check'=>'delete','menu'=>'','label'=>'delete','uses' =>'TimeformatsController@timeformatdelete'))->name('timeformatdelete');
Route::post('timeformatsave',array('as' => '','check'=>'','menu'=>'','label'=>'','uses' =>'TimeformatsController@save'))->name('timeformatsave');
Route::post('Login/signin',array('as' => '','check'=>'','menu'=>'signin','label'=>'','uses' =>'LoginController@Signin'));

Route::get('columnpermission',array('as' => '','check'=>'','menu'=>'columnpermission','label'=>'','uses' =>'ColumnpermissionController@index'))->name('columnpermission');
Route::post('coloumnsave',array('as' => '','check'=>'','menu'=>'','label'=>'','uses' =>'ColumnpermissionController@save'));
Route::get('getcolumns',array('as' => '','check'=>'','menu'=>'','label'=>'','uses' =>'ColumnpermissionController@getcolumns'));


//PROJECTTYPE
Route::get('projecttype', array('as' => '','check'=>'','menu'=>'projecttype','label'=>'','uses' =>'ProjecttypesController@index'))->name('projecttype');
Route::get('getprojecttypeData', array('as' => '','check'=>'','menu'=>'','label'=>'','uses' =>'ProjecttypesController@getprojecttypeData'))->name('getprojecttypeData');
Route::get('projecttypedelete/{id}', array('as' => '','check'=>'','menu'=>'','label'=>'','uses' =>'ProjecttypesController@projecttypedelete'))->name('projecttypedelete');
Route::post('projecttypesave', array('as' => '','check'=>'','menu'=>'','label'=>'','uses' =>'ProjecttypesController@projecttypesave'))->name('projecttypesave');
Route::get('getCheckprojecttype', array('as' => '','check'=>'','menu'=>'','label'=>'','uses' =>'ProjecttypesController@getCheckprojecttype'))->name('getCheckprojecttype');


// PROJECT
Route::get('project', array('as' => '','check'=>'','menu'=>'project','label'=>'','uses' =>'ProjectController@index'))->name('ProjectController');
Route::get('getprojectData', array('as' => '','check'=>'','menu'=>'','label'=>'','uses' =>'ProjectController@getprojectData'))->name('getprojectData');
Route::get('projectdelete/{id}', array('as' => '','check'=>'','menu'=>'','label'=>'','uses' =>'ProjectController@projectdelete'))->name('projectdelete');
Route::post('projectsave', array('as' => '','check'=>'','menu'=>'','label'=>'','uses' =>'ProjectController@projectsave'))->name('projectsave');
Route::get('getCheckprojectname', array('as' => '','check'=>'','menu'=>'','label'=>'','uses' =>'ProjectController@getCheckname'))->name('getCheckprojectname');



/*QC Approval Setting*/
Route::get('getqcapprovaldata',array('as' => '','check'=>'','menu'=>'','label'=>'','uses' =>'QcapprovalsettingsController@getqcapprovaldata'))->name('getqcapprovaldata');
Route::get('qcapprovalsettings',array('as' => '','check'=>'','menu'=>'qcapprovalsettings','label'=>'','uses' =>'QcapprovalsettingsController@create'))->name('qcapprovalsettings');
Route::get('productcategorycheck/{id}',array('as' => '','check'=>'','menu'=>'productcategorycheck','label'=>'','uses' =>'QcapprovalsettingsController@check'))->name('productcategorycheck');
Route::post('qcapprovalsettingssave',array('as' => '','check'=>'','menu'=>'qcapprovalsettings','label'=>'','uses' =>'QcapprovalsettingsController@qcapprovalsettingssave'))->name('qcapprovalsave');
/*End*/
 // Notification
 Route::get('notification', array('as' => '','check'=>'','menu'=>'notification','label'=>'','uses' =>'NotificationController@index'))->name('notification');
 Route::get('module_data/{id}', array('as' => '','check'=>'','menu'=>'module_data','label'=>'','uses' =>'NotificationController@show'))->name('notification');
 Route::post('userdata', array('as' => '','check'=>'','menu'=>'userdata','label'=>'','uses' =>'NotificationController@store'))->name('notification');
 Route::get('editnotificationsetting', array('as' => '','check'=>'','menu'=>'editnotificationsetting','label'=>'','uses' =>'NotificationController@update'))->name('notification');
 Route::get('triggernotification', array('as' => '','check'=>'','menu'=>'triggernotification','label'=>'','uses' =>'TriggernotificationController@data'))->name('notification');
 
//EOD Report Tracker
Route::get('eodreporttracker', array('as' => '','check'=>'','menu'=>'eodreporttracker','label'=>'','uses'=>'EodreportController@index'))->name('eodreporttracker');
Route::get('geteodreporttrackerdata', array('as'=>'','check'=>'','menu'=>'eodreporttracker','label'=>'','uses'=>'EodreportController@getdocumentinterntransferdata'))->name('geteodreporttrackerdata');
Route::get('eodreporttrackeredit/{id}',array('as' => '','check'=>'edit','menu'=>'eodreporttracker','label'=>'','uses' =>'EodreportController@create'))->name('eodreporttracker');
Route::post('eodreporttrackersave',array('as' => '','check'=>'','menu'=>'eodreporttracker','label'=>'','uses' =>'EodreportController@save'));
Route::get('eodreporttrackerdelete/{id}',array('as' => '','check'=>'','menu'=>'eodreporttracker','label'=>'delete','uses' =>'EodreportController@destroy'))->name('eodreporttracker');
Route::get('eodreporttrackerview/{id}',array('as' => '','check'=>'','menu'=>'eodreporttracker','label'=>'delete','uses' =>'EodreportController@show'))->name('eodreporttracker');

//Document Internal Transfer
Route::get('documentinterntransfer', array('as' => '','check'=>'','menu'=>'documentinterntransfer','label'=>'','uses'=>'DocumentinterntransferController@index'))->name('documentinterntransfer');
Route::get('getdocumentinterntransferdata', array('as'=>'','check'=>'','menu'=>'documentinterntransfer','label'=>'','uses'=>'DocumentinterntransferController@getdocumentinterntransferdata'))->name('getdocumentinterntransferdata');
Route::get('documentinterntransferedit/{id}',array('as' => '','check'=>'edit','menu'=>'documentinterntransfer','label'=>'','uses' =>'DocumentinterntransferController@create'))->name('documentinterntransfer');
Route::post('documentinterntransfersave',array('as' => '','check'=>'','menu'=>'documentinterntransfer','label'=>'','uses' =>'DocumentinterntransferController@save'));
Route::get('documentinterntransferdelete/{id}',array('as' => '','check'=>'','menu'=>'documentinterntransfer','label'=>'delete','uses' =>'DocumentinterntransferController@destroy'))->name('documentinterntransfer');
Route::get('documentinterntransferview/{id}',array('as' => '','check'=>'','menu'=>'documentinterntransfer','label'=>'delete','uses' =>'DocumentinterntransferController@show'))->name('documentinterntransfer');

Route::get('reportelements',array('as' => '','check'=>'','menu'=>'reportelements','label'=>'','uses' =>'RptdisplayelementshdrController@index'))->name('reportelements');
Route::get('rptdisplayelementsdelete/{id}',array('as' => '','check'=>'delete','menu'=>'reportelements','label'=>'delete','uses' =>'RptdisplayelementshdrController@rptdisplayelementsdelete'));
Route::get('rptdisplayelementscreate/{id}',array('as' => '','check'=>'create','menu'=>'reportelements','label'=>'create','uses' =>'RptdisplayelementshdrController@create'))->name('rptdisplayelementscreate');
Route::post('rptdisplayelementsave',array('as' => '','check'=>'save','menu'=>'','label'=>'save','uses' =>'RptdisplayelementshdrController@save'))->name('rptdisplayelementsave');
Route::get('getrptdispdata',array('as' => '','check'=>'','menu'=>'','label'=>'','uses' =>'RptdisplayelementshdrController@getrptdispdata'))->name('getrptdispdata');
Route::get('rptdisplayelementsview/{id}',array('as' => '','check'=>'view','menu'=>'reportelements','label'=>'view','uses' =>'RptdisplayelementshdrController@view'))->name('rptdisplayelementsview');

//Time Out Check
Route::get('timeoutcheck',array('as' => '','check'=>'','menu'=>'','label'=>'','uses' =>'ApprovalsettingsController@timeoutcheck'))->name('timeoutcheck');

Route::get('jobreportsettings',array('as' => '','check'=>'','menu'=>'jobreportsettings','label'=>'','uses' =>'JobreportsettingsController@index'));
Route::post('jobreportsave',array('as' => '','check'=>'','menu'=>'jobreportsettings','label'=>'','uses' =>'JobreportsettingsController@store'));

/* need help menu */

Route ::get('needhelp', array('as' => '','check'=>'','menu'=>'Need Help','label'=>'','uses' =>'NeedhelpController@index'))->name('needhelp');
Route ::get('createsop/{id}', array('as' => '','check'=>'create','menu'=>'Need Help','label'=>'','uses' =>'NeedhelpController@create'))->name('createsop');
Route ::post('needhelpsave', array('as' => '','check'=>'','menu'=>'Need Help','label'=>'','uses' =>'NeedhelpController@save'))->name('needhelpsave');
Route ::get('needhelpview/{id}', array('as' => '','check'=>'view','menu'=>'Need Help','label'=>'','uses' =>'NeedhelpController@view'))->name('needhelpview');
Route ::get('needhelpdelete/{id}', array('as' => '','check'=>'delete','menu'=>'','label'=>'delete','uses' =>'NeedhelpController@delete'))->name('needhelpdelete');

/* END */

Route::get('productsyncsfgconst', array('as' => '','check'=>'','menu'=>'Month Wise Attendance Report','label'=>'','uses' =>'CreateuserController@productsyncsfgconst'))->name('productsyncsfgconst');






















?>