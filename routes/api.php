<?php

use Illuminate\Http\Request;

/*
  |--------------------------------------------------------------------------
  | API Routes
  |--------------------------------------------------------------------------
  |
  | Here is where you can register API routes for your application. These
  | routes are loaded by the RouteServiceProvider within a group which
  | is assigned the "api" middleware group. Enjoy building your API!
  |
 */

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


Route::post('/getincomingsms', 'InmateController@getIncomingsms');
Route::middleware('auth:api')->group(function () {
    Route::get('/getinmateservice', 'ServiceController@getInmateService');
    
    Route::post('/changepasswordapi', 'InmateController@changePasswordAPI');
    Route::post('/timeapi/checkbalanceforpaid', 'TimeController@checkBalanceForPaid');
    Route::post('/timeapi/checkflatpaid', 'TimeController@checkFlatPaid');

    Route::post('/timeapi/freeserviceminute', 'TimeController@freeServiceMinute');
    Route::post('/timeapi/spendminute', 'TimeController@spendminute');
    Route::post('/timeapi/endsession', 'TimeController@endsession');
   
    Route::post('/inmatebalance', 'InmateController@inmateBalance');
    Route::post('saveanswer', 'InmateController@saveAnswer');


Route::post('/registerinmateendtimeactivityhistory', 'InmateActivityHistoryController@registerInmateEndTimeActivityHistory');
Route::post('/logoutinmate', 'InmateController@logoutInmate');
Route::get('/getinmateactivitydetails', 'InmateActivityHistoryController@getInmateActivityDetails');
Route::get('getallservicebooks', 'ServiceController@getAllServiceBooks');
Route::get('getpaymentinformation/{family_id}', 'FamilyController@getPaymentInformation');
Route::post('/registerinmatereporthistory', 'InmateReportHistoryController@registerInmateReportHistory');


});
Route::get('allowurl','CpcController@allowUrl');
Route::get('checkappupdate','DeviceController@checkUpdate');
 Route::post('/changepassword', 'InmateController@changePassword');



Route::post('/logininmate', 'InmateController@authenticateInmate');

Route::post('/resetinmatepassword', 'InmateController@resetInmatePassword');

Route::post('/resetpassword', 'InmateController@resetPassword');
Route::get('/optoutdevice/{mac_id}', 'InmateController@optOutDevice');
Route::get('/createmail', 'EmailController@createEmail');
//Route::group(['middleware' => 'auth:api','except' => ['unauthorizedrequests']],   function () {   







Route::post('/registerinmatestarttimeactivityhistory', 'InmateActivityHistoryController@registerInmateStartTimeActivityHistory');





Route::post('/registerinmateblock', 'InmateController@registerInmateBlock');







Route::post('/registerinmatereport', 'InmateController@registerInmateReport');

//});

Route::post('/userinmatelogout', 'InmateController@userInmateLogout');

Route::get('unauthorizedrequests', ['as' => 'login', 'uses' => 'InmateController@unauthorizedRequest']);

Route::get('/getallinmate', 'InmateController@getAllInmate');



Route::get('/sentmail', 'SendMailController@sendMail');

Route::post('/getuseremail', 'SendMailController@getInmateEmail');

Route::post('/authenticateSuperAdmin', 'SuperadminController@authenticateSuperAdmin');

Route::post('sendinmatemail', 'SendMailController@sendInmateMail')->name('email.sendfunctionality');

Route::post('/activeinmate', 'InmateController@activeInmate');

Route::get('deleteinmateloginreport/{id?}', 'InmateReportHistoryController@deleteInmateLoginReport');

Route::post('/registerconfiguration', 'SuperadminController@registerConfiguration');
Route::post('/updatenegativebalance', 'SuperadminController@updateNegativebalance');
Route::post('/updatewelcomemsg', 'SuperadminController@updateWelcomemsg');
Route::post('/updatelowblmsg', 'SuperadminController@updateLowbalanceMsg');
Route::post('/updateapiurl', 'SuperadminController@updateAPIurl');
Route::post('/updatefreeminexpmsg', 'SuperadminController@updateFreeminExpmsg');

Route::post('/updatelgtime', 'SuperadminController@updateLgtime');


Route::post('registertabletfreetimetabletchargeconfiguration', 'SuperadminController@registerTabletfreeTimeTabletChargeConfiguration');



Route::get('/getallservice', 'ServiceController@getAllService');

Route::get('/getservice', 'ServiceController@getService');

//Route::post('/registerservice', 'ServiceController@registerService')->middleware('auth');



/* Service functionality route end******************************************************* */




Route::get('getreminderinmatebalanceLow', 'FamilyController@getReminderInmateBalanceLow');
/* Family Functionlity route end************************************************************ */


Route::post('changefacilitypassword', 'FacilityController@changeFacilityPassword');

/* Facility Functionlity route end************************************************************ */



Route::POST('sendsms', 'InmateController@sendSms');

Route::get('getdevicelistbehalffacilityId/{id?}', 'DeviceController@getDeviceListBehalfFacilityId')->name('device.devicelistbehalfacility');

Route::get('downloadapklink', 'SuperadminController@downloadAPKLink')->name('downlaod.apk');
Route::get('downloadapklink1', 'SuperadminController@downloadAPKLink1')->name('downlaod.apk1');

Route::get('downloadapklink2', 'SuperadminController@downloadAPKLink2')->name('downlaod.apk2');

Route::get('downloadapklink3', 'SuperadminController@downloadAPKLink3')->name('downlaod.apk3');

Route::get('downloadbackroommanuallink', 'SuperadminController@downloadUserManualLinkBackroom')->name('downlaod.backroommanual');

Route::get('downloadtwowaycomlink', 'SuperadminController@downloadUserManualTwoWayCommunicationBackroom')->name('downlaod.twowaymanual');

Route::get('downloadandroidemanuallink', 'SuperadminController@downloadUserManualLinkAndroide')->name('downlaod.androidemanual');
/* Device Functionlity route start*********************************************************** */


//emails 
Route::get('deleteemail/{id?}', 'RecievedMailController@deleteEmail');
Route::POST('CreateEmail', 'InmateContactController@createEmail');

//contact by inmate
Route::POST('sendphone', 'InmateContactController@storeContactNumber');
Route::POST('sendemail', 'InmateContactController@storeContactEmail');

//varification for eamil 
Route::get('getAuthUser', 'InmateContactController@getAuthUser');

//for setting maximum number of email.
Route::POST('setmaxlimit', 'InmateContactController@setMaxLimitByfacilty');
Route::POST('getmaxlimitval', 'InmateContactController@getMaxLimitVal');


Route::post('sms/inbound', 'InmateController@getAllSMS');

// Backup Controller function BackupData_DailyBasis

Route::get('inactivedata_backup', 'BackupController@backupDataDailyBasis');
Route::post('forward_email', 'SendMailController@emailForward')->name('email.forward_email');


route::post('payment_response', 'FamilyController@inmateExternalPaymentScreenResponse')->name('payment_response');

Route::post('verify_inmate_by_api', 'FamilyController@verifyInmateAPI');

route::post('inmatepaymentscreenexternal', 'FamilyController@inmateExternalPaymentScreen')->name('inmatepayment.screen2');
Route::get('inmate_emails/{id?}', 'SendMailController@forwardEmail');

//  function sequerty questions

Route::get('getsecurityquestion', 'InmateController@getSecurityQuestion');



Route::post('checkanswer', 'InmateController@checkAnswer');
// CPC API

Route::post('purchase','CpcController@Purchase');
Route::post('getusersbyfacility','CpcController@getusersbyfacilityid');

//Get user Details by id
Route::post('getusersdetailsbyid','CpcController@getUserdetails');

//Get Inmate Fund
Route::post('getinmatefund','CpcController@getInmatefund');

//Get user device usage
Route::post('getuserusage','CpcController@getUserusage');

//Get User Purchase History
Route::post('getpurchasehistory','CpcController@getUserpurchaseHistory');

// Assign Device
Route::post('assigndevice','CpcController@assignDevice');

// M&S url


Route::post('importcpcuser','CpcController@ImportCpcUser');
//Axxs Syncing API
Route::post('importuser','CpcController@importUser');
Route::post('releaseuser','CpcController@releaseUser');

Route::get('emailblock/{email}', 'FamilyController@emailBlock');
Route::post('updateuser','CpcController@UpdateUser');
Route::get('/getminutes/{inmate_id}', 'InmateController@calculateFreeLeftTime');
Route::get('/appdownloadurl', 'InmateController@appDownloadURL');
Route::post('/getdeviceid', 'DeviceController@getDeviceId');
Route::match(['get', 'post'],'/axxs_news/top_headlines/{facility_id?}','NewsController@index');
Route::get('/sc_api/{facility_id?}','APIController@soundcloudView');
Route::post('/getapiurl', 'SuperadminController@getAPIurl');