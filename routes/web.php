<?php
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use Illuminate\Support\Facades\Password;
use App\Http\Controllers\Controller;

Route::get('/', function () {

    if (!auth()->check()) {
        return redirect()->route('login');
    }

    if (!session()->has('supply_year_id')) {
        auth()->logout();
        session()->invalidate();
        session()->regenerateToken();
        return redirect()->route('login');
    }

    return redirect()->route('home', [
        'id' => session('supply_year_id')
    ]);
});



Route::get('permissioindenied', function () {    return view('error.403'); });

Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [LoginController::class, 'login']);
Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
Route::post('password/reset', 'Auth\ResetPasswordController@reset')->name('password.update');

Route::get('logout', 'Auth\LoginController@logout')->name('logout');
Route::get('decvalue','Controller@decvalue');

Route::get('/check-session', function () {
    return auth()->check()
        ? response()->json(['status' => 'valid'])
        : response()->json(['status' => 'expired']);
});

@include('include_routes/routes_sales.php');
@include('include_routes/routes_purchase.php');
@include('include_routes/routes_inventory.php');
@include('include_routes/routes_hrms.php');
@include('include_routes/routes_accounts.php');
@include('include_routes/routes_wip.php');
@include('include_routes/routes_api.php');
@include('include_routes/routes_sfa.php');
@include('include_routes/routes_reports.php');
@include('include_routes/routes_training.php');
@include('include_routes/routes_secondarydb.php');
@include('include_routes/routes_quality.php');
@include('include_routes/routes_admin.php');
@include('include_routes/routes_dashboard.php');
@include('include_routes/routes_mapping.php');
@include('include_routes/routes_maintenance.php');
@include('include_routes/routes_rdentry.php');

Route::get('/soquote/{product_id}', 'SoquoteController@uomcode');

Route::get('productdetails/{pid}/{plid}/{ssid}/{type}', 'Controller@productDetails');
Route::get('jcombojoinselect',array('as' => '','check'=>'','menu'=>'','label'=>'','uses' =>'Controller@jcombojoinselect'));
//Supplier Search
Route::get('hrmssaveinsert', 'Controller@hrmssaveinsert');
Route::get('getSuppliergridData', 'Controller@getSuppliergridData');
//customer Search
Route::get('getCustomergridData', 'Controller@getCustomergridData');
//product search

Route::get('getProductgridDatasubinventory', 'Controller@getProductgridDatasubinventory')->name('getProductgridDatasubinventory');
Route::get('jcomboformlogin', [Controller::class, 'jcomboformlogin'])->name('jcomboformlogin');
Route::get('jcombocomp', 'Controller@jcombocomp');
Route::get('jcomboformcomp', 'Controller@jcomboformcomp');
Route::get('jcomboformallcheck', 'Controller@jcomboformallcheck');
Route::get('jcomboformallchecknew', 'Controller@jcomboformallchecknew');
Route::get('jcomboformrerule', 'Controller@jcomboformrerule');
Route::get('jcustomselectcomp', 'Controller@jcustomselectcomp');
Route::get('jcustomselectactive', 'Controller@jcustomselectactive');
Route::get('/home', 'HomeController@index')->name('home');
Route::get('jcomboproduct', 'Controller@jcomboproduct');
Route::get('jcomboproductvar', 'Controller@jcomboproductvar');
Route::get('jcomboproductunit', 'Controller@jcomboproductunit');
Route::get('jcomboproductname', 'Controller@jcomboproductname');
Route::get('jcomboprodt', 'Controller@jcomboprodt');
Route::get('jcombosecondsales', 'Controller@jcombosecondsales');
Route::get('jcomboprotypejoinselect', 'Controller@jcomboprotypejoinselect');
Route::get('/productgroupid/', 'Controller@productgroupid')->name('home');
Route::get('jcomboformtax',array('as' => '','check'=>'','menu'=>'','label'=>'','uses' =>'Controller@jcomboformtax'));
//notification
Route::get('Productstock', 'NotificationController@Productstock');
Route::get('jcomboformcompwithref', 'Controller@jcomboformcompwithref');
Route::get('jcomboform',array('as' => '','check'=>'','menu'=>'','label'=>'','uses' =>'Controller@jcomboform'));

Route::get('/home', 'HomeController@index')->name('home');
Route::get('supplieryear/{id}', 'HomeController@index1')->name('home');
Route::get('reportdatemaster', 'reportdatemasterController@reportdatemaster')->name('reportdatemaster');
Route::get('getreportdatemasterData', 'reportdatemasterController@getreportdatemasterData')->name('getreportdatemasterData');
Route::post('datemastersave',array('as' => '','check'=>'','menu'=>'','label'=>'','uses' =>'reportdatemasterController@save'))->name('datemastersave');

Route::get('grnjournal', 'reportdatemasterController@grnjournal')->name('grnjournal');


?>
