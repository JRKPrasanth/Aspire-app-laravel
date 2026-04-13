<?php
//api routes
Route::post('tourprogram','MobileapputilityController@Tourprogram');
Route::get('api-attendance-status', 'MobileapputilityController@getAttendance');
Route::post('mobileLogin','MobileapputilityController@mobileLogin');
Route::post('gettourprogram','MobileapputilityController@gettourprogram');
Route::post('adddoctor','MobileapputilityController@adddoctor');
Route::post('getextraacitivitydata','MobileapputilityController@getextraacitivitydata');
Route::post('extraacitivitydata','MobileapputilityController@extraacitivitydata');
Route::post('adddoctordcr','MobileapputilityController@adddoctordcr');
Route::post('getdoctordcr','MobileapputilityController@getdoctordcr');
Route::post('addchemistdcr','MobileapputilityController@addchemistdcr');
Route::post('getchemistdcr','MobileapputilityController@getchemistdcr');
Route::post('addstockistdcr','MobileapputilityController@addstockistdcr');
Route::post('getstockistdcr','MobileapputilityController@getstockistdcr');
Route::post('mobileGeoUpdate','MobileapputilityController@mobileGeoUpdate');
Route::post('updateBulkGeo','MobileapputilityController@updateBulkGeo');
Route::get('listAllDatas','MobileapputilityController@listAllDatas');
Route::post('leaveentry','MobileapputilityController@leaveentry');
Route::post('myLeaveRequestList','MobileapputilityController@myLeaveRequestList');
Route::post('gettourprograms','MobileapputilityController@gettourprograms');
Route::post('addchemist','MobileapputilityController@addchemist');
Route::post('addstockist','MobileapputilityController@addstockist');
Route::post('getlistGiftSamples','MobileapputilityController@getlistGiftSamples');
Route::post('addexpense','MobileapputilityController@addexpense');
Route::post('getexpense','MobileapputilityController@getexpense');
Route::get('getLeavetype','MobileapputilityController@getLeavetype');
Route::post('postLeaveentry','MobileapputilityController@postLeaveentry');
Route::post('gettourdatalist','MobileapputilityController@gettourdatalist');
Route::post('getdetailtourview','MobileapputilityController@getdetailtourview');
Route::post('getapprovedemp','MobileapputilityController@getapprovedemp');
Route::post('leaveapprovallist','MobileapputilityController@leaveapprovallist');
Route::post('leaveapprovallistcount','MobileapputilityController@leaveapprovallistcount');
Route::get('getarea','MobileapputilityController@getarea');
Route::get('gettourtype','MobileapputilityController@gettourtype');
Route::post('getdoctor','MobileapputilityController@getdoctor');
Route::post('getlistSamplesProduct','MobileapputilityController@getlistSamplesProduct');
Route::post('getlistposterProduct','MobileapputilityController@getlistposterProduct');
Route::post('getAllProducts','MobileapputilityController@getAllProducts');
Route::post('getdatearealist','MobileapputilityController@getdatearealist');
Route::post('getdoctorlist','MobileapputilityController@getdoctorlist');
Route::post('getchemistlist','MobileapputilityController@getchemistlist');
Route::post('getstockistlist','MobileapputilityController@getstockistlist');
Route::post('getactivitylist','MobileapputilityController@getactivitylist');
Route::post('getoutcomelist','MobileapputilityController@getoutcomelist');
Route::post('getMissCallReport','MobileapputilityController@getMissCallReport');
Route::post('leaveapprovedcount','MobileapputilityController@leaveapprovedcount');
Route::post('tourapproved','MobileapputilityController@tourapproved');
Route::post('tourpending','MobileapputilityController@tourpending');
Route::post('tourpendinglist','MobileapputilityController@tourpendinglist');
Route::post('doctordetails','MobileapputilityController@doctordetails');
Route::post('chemistdetails','MobileapputilityController@chemistdetails');
Route::post('stockistdetails','MobileapputilityController@stockistdetails');
Route::post('areadetails','MobileapputilityController@areadetails');
Route::post('leaveapprovedlist','MobileapputilityController@leaveapprovedlist');
Route::post('leaveapproval','MobileapputilityController@leaveapproval');
Route::post('tourplanapproval','MobileapputilityController@tourplanapproval');
Route::post('location','MobileapputilityController@location');
Route::post('overallAttendance','MobileapputilityController@overallAttendance');
Route::post('getdoctordata','MobileapputilityController@getdoctordata');
Route::post('getchemistdata','MobileapputilityController@getchemistdata');
Route::post('getstockistdata','MobileapputilityController@getstockistdata');
Route::post('getdoctordcrdata','MobileapputilityController@doctordcrdata');
Route::post('getchemistdcrdata','MobileapputilityController@getchemistdcrdata');
Route::post('getstockistdcrdata','MobileapputilityController@getstockistdcrdata');
Route::post('getmremployeedata','MobileapputilityController@getmremployeedata');
Route::post('listMyUserAttendance','MobileapputilityController@listMyUserAttendance');
Route::get('getLastGeoLocation','MobileapputilityController@getLastGeoLocation');
Route::post('listDatas', 'MobileapputilityController@listDatas');
Route::post('superstockist', 'MobileapputilityController@superstockist');





Route::post('/api-mobile-login','APIController@mobileLogin');
Route::post('/api-update-fcm-token','APIController@updateFCMToken');
Route::post('/api-send-notification','APIController@sendNotification');


Route::post('/api-sales-quote-list','APIController@salesQuoteList');
Route::post('/api-sales-quote','APIController@salesQuote');
Route::post('/api-sale-quote-update','APIController@salesQuoteUpdate');

Route::post('/api-sales-order-list','APIController@saleOrderList');

Route::post('/api-sales-order-list-new','APIController@saleOrderListnew');


Route::post('/api-sale-order','APIController@saleOrder');
Route::post('/api-sale-order-update','APIController@saleOrderUpdate');

Route::post('/api-sales-invoice-list','APIController@saleInvoiceList');
Route::post('/api-sale-invoice','APIController@saleInvoice');
Route::post('/api-sale-invoice-update','APIController@saleInvoiceUpdate');

Route::post('/api-purchase-quote-list','APIController@purchaseQuoteList');
Route::post('/api-purchase-quote','APIController@purchaseQuote');
Route::post('/api-purchase-quote-update','APIController@purchaseQuoteUpdate');

Route::post('/api-purchase-order-list','APIController@purchaseOrderList');
Route::post('/api-purchase-order','APIController@purchaseOrder');
Route::post('/api-purchase-order-update','APIController@purchaseOrderUpdate');

Route::post('/api-purchase-req-list','APIController@purchaseReqList');
Route::post('/api-purchase-req','APIController@purchaseReq');
Route::post('/api-purchase-req-update','APIController@purchaseReqUpdate');

Route::post('/api-purchase-invoice-list','APIController@purchaseInvoiceList');
Route::post('/api-purchase-invoice','APIController@purchaseInvoice');
Route::post('/api-purchase-invoice-update','APIController@purchaseInvoiceUpdate');

Route::post('/api-purchase-return-list','APIController@purchaseReturnList');
Route::post('/api-purchase-return','APIController@purchaseReturn');
Route::post('/api-purchase-return-update','APIController@purchaseReturnUpdate');

Route::post('/api-po-cancel-list','APIController@poCancelList');
Route::post('/api-po-cancel','APIController@poCancel');
Route::post('/api-po-cancel-update','APIController@poCancelUpdate');

Route::post('/api-so-cancel-list','APIController@soCancelList');
Route::post('/api-so-cancel','APIController@soCancel');
Route::post('/api-so-cancel-update','APIController@soCancelUpdate');

Route::post('/api-purchase-quality-list','APIController@purchaseQualityList');
Route::post('/api-purchase-quality','APIController@purchaseQuality');
Route::post('/api-purchase-quality-update','APIController@purchaseQualityUpdate');


Route::post('/api-leave-type','APIController@leavetype');
Route::post('/api-leave-mode','APIController@leavemode');
Route::post('/api-leave-forward','APIController@forwardto');
Route::post('/api-leave-request','APIController@leaverequest');
Route::post('/api-leavelist','APIController@leavelist');
Route::post('/api-leavedataupdate','APIController@leavedataupdate');

Route::post('/api-createsoorder','APIController@createsoorder');

Route::post('/api-createsoorder-list','APIController@createsoorderlist');


Route::post('/api-customerlist','APIController@customerlist');
Route::post('/api-soorderproductlist','APIController@soorderproductlist');
Route::post('/api-save-so','APIController@saveso');
Route::post('/api-save-so-new','APIController@savesonew');
Route::post('/api-job-list','APIController@joblist');
Route::post('/api-create-job','APIController@createjoblist');
Route::post('/api-create-jobcard','APIController@createjobcard');
Route::post('/api-submit-jobcard','APIController@submitjobcard');
Route::post('/api-production-jobcard-status','APIController@productionjobcardstatus');
//end
?>