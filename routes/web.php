<?php

/*
  |--------------------------------------------------------------------------
  | Web Routes
  |--------------------------------------------------------------------------
  |
  | Here is where you can register web routes for your application. These
  | routes are loaded by the RouteServiceProvider within a group which
  | contains the "web" middleware group. Now create something great!
  |
 */

Route::get('/', function () {

    return redirect('index.php/login');
    
});
Route::get('/privacy_policy', function () {

    return view('privacy_policy');
    
});
Route::group(['middleware' => ['auth']], function () {

    Route::resource('users', 'UserController');
    Route::resource('roles', 'RoleController');
    Route::resource('permissions', 'PermissionController');
    Route::resource('staffs', 'StaffController');
    Route::post('/action_staff/{id}', 'StaffController@staffActiveDeactive');
    Route::get('stafflog/{id}', 'StaffController@staffActiveDetails')->name('staffs.activity');
     /* Device Functionality route start********************************************************** */
    Route::post('activedevice', 'DeviceController@activeDevice');
    Route::post('registerdevice', 'DeviceController@registerDevice');
    Route::post('updatedevice', 'DeviceController@updateDevice');
    Route::delete('deletedevice/{id?}', 'DeviceController@deleteDevice')->name('device.delete');
    Route::get('inusers','UserController@inactiveSpec');
    Route::put('activateuser/{id}','UserController@activateUser');
    /* Facility Functionality route start******************************************************** */
    Route::post('registerfacility', 'FacilityController@registerFacility');
    Route::post('updatefacility/{id?}', 'FacilityController@updateFacility');
    Route::delete('deletefacility/{id?}', 'FacilityController@deleteFacility');
    Route::post('activefacility', 'FacilityController@activeFacility');
    
    /* Facility Admin Functionality route start*/
    Route::post('registerfadmin', 'FacilityAdminController@registerFacilityadmin');
    /* Family Functionality route start******************************************************** */
    Route::post('registerfamily', 'FamilyController@registerFamily');
    Route::post('updatefamily', 'FamilyController@updateFamily');
    Route::delete('deletefamily/{id?}', 'FamilyController@deleteFamily');
    Route::post('activefamily', 'FamilyController@activeFamily');
    
    /* Inmate User Functionality route start******************************************************** */
    Route::post('/registeruser', 'InmateController@registerInmate');
    Route::post('updateuser', [
        'as' => 'updateuser',
        'uses' => 'InmateController@updateInmate',
    ]);
    Route::post('deleteuser/{id?}', 'InmateController@deleteInmate');
    Route::post('setmaxlimit', 'InmateContactController@setMaxLimitByfacilty');
    Route::post('getmaxlimitval', 'InmateContactController@getMaxLimitVal');
    Route::post('activeuser', 'InmateController@activeInmate');
    
    /* Service and category functionality route start*************************************************** */
    Route::get('deleteservice/{id?}', 'ServiceController@deleteService');
    Route::get('deletecategory/{id?}', 'ServiceController@deleteCategory');
    Route::post('registercategory', 'ServiceController@registerCategory');
    Route::post('updateservice', 'ServiceController@updateService');
    Route::post('updatecategory', 'ServiceController@updateCategory');
    Route::post('activeservice', 'ServiceController@activeService');
    
     /* Download monthly report link*************************************************** */
       Route::post('monthly_report', 'ExcelController@monthlyRental');
    });

Route::group(['middleware' => ['web']], function () {

    /* Super admin route Start */
    Route::get('get_cat_services/{id}', 'ServiceController@categoryServiceList');
    Route::get('dashboard', 'SuperadminController@index')->name('superadmin.index');
    Route::get('test', 'HomeController@index');
    Route::get('useracitivityhistorylist/{id?}', 'InmateActivityHistoryController@inmateAcitivityHistoryListUI')->name('inmate.inmateactivity');
    Route::get('logout', 'SuperadminController@Logout')->name('inmate.logout');
    Route::get('/getloginreportuserlist', 'InmateReportHistoryController@getLoginInmateReportListUI')->name('inmate.reportlogin');
    Route::get('sendSMS/{inmate_id}/{service_id}', 'InmateController@sendSMSUI')->name('sendSMS');

    Route::post('/registerpermission', 'ServicePermissionController@registerPermission');
    Route::post('/import_service', 'ServicePermissionController@importService');

    Route::group(['middleware' => ['auth', 'common']], function () {
        //Route For Managing 3rd Part API
    Route::get('/api_news','APIController@apiNews');
    Route::post('/update_api','APIController@updateAPI');
    Route::get('/api_soundcloud','APIController@apiSoundcloud');

    Route::get('/getServicechargeby_facility/{facility_id?}/{service_id?}','ServiceController@getFacilityserviceCharge');
    Route::get('/get_blword/{facility_id?}','APIController@getMusicblWord');
    Route::post('/addbl_word/{facility_id?}','APIController@addBLword');
    Route::post('/update_bl_word','APIController@update_bl_word');
    Route::get('/scfacilitysetting/{facility_id?}','APIController@getSCfacilitySetting');
    Route::get('/scfacilitynewssetting/{facility_id?}','APIController@getSCfacilityNewssetting');
    Route::get('/getgenres','APIController@getGenres');
    Route::post('/addgenre','APIController@addGenres');
    
    Route::post('/sc_config','APIController@addSCconfig');
    Route::post('/addnews_config','APIController@addNewsconfig');     
        //Route for Managing Service
        Route::post('/registerservice', 'ServiceController@registerService');
        Route::get('services', 'ServiceController@serviceListUI')->name('service.list');
        Route::get('viewservice/{id?}', 'ServiceController@viewServiceUI')->name('service.view');
        Route::get('addservice/{id?}', 'ServiceController@addServiceUI')->name('service.add');
        Route::get('serviceinactivelist', 'ServiceController@serviceInactiveListUI')->name('service.inactivelist');
        //Route for Managing Movie
        Route::post('/registermovie', 'MovieController@registerMovie');
        Route::get('addmovie/{id?}', 'MovieController@addMovieUI')->name('movie.add');
        Route::get('movies', 'MovieController@movieListUI')->name('movie.list');
        Route::get('inactivemovies', 'MovieController@inactiveMovielist')->name('inactivemovie.list');
        Route::get('getmovielist', 'MovieController@movieList');
        Route::get('getinactivemovie', 'MovieController@getInactivemovie');
        Route::get('movie/edit/{id?}', 'MovieController@editMovie')->name('movie.edit');

        Route::post('movie/update', 'MovieController@updateMovie')->name('movie.update');
        Route::post('movie/delete/{id?}', 'MovieController@deleteMovie');
        Route::post('movie/makeactive/{id?}', 'MovieController@makeMovieactive');
        
        //Route for Managing Music
        Route::post('/getfiledetails', 'MusicController@musicFileDetails');
        Route::post('/registermusic', 'MusicController@registerMusic');
        Route::get('addmusic/{id?}', 'MusicController@addMusicUI')->name('music.add');
        Route::get('musics', 'MusicController@musicListUI')->name('music.list');
        Route::get('music/edit/{id?}', 'MusicController@editMusic')->name('music.edit');
        Route::post('music/update', 'MusicController@updateMusic')->name('music.update');
        Route::post('music/delete/{id?}', 'MusicController@deleteMusic');
        Route::get('inactivemusics', 'MusicController@inactiveMusiclist')->name('inactivemusic.list');
        Route::get('getinactivemusic', 'MusicController@getInactivemusic');
        Route::post('music/makeactive/{id?}', 'MusicController@makeMusicactive');
        //end

        //Reset Services for users
        Route::get('resetuserservices', 'ServiceController@resetUserServices');

        //Route for managing export import services

        Route::get('get_facilitylist','FacilityController@facilityList');
        Route::get('download_service/{facility_id?}','FacilityController@downloadFacilityservice');

        Route::get('categories', 'ServiceController@listcategoryUI')->name('category.list');

        //Route for Managing Inmate
        Route::get('allusers/{facility_id?}', 'InmateController@inmateListUI')->name('inmate.inmatelist');
        Route::get('getfacilityuser/{facility_id?}', 'InmateController@inmateListdata');
        Route::get('userinactivelist', 'InmateController@inmateInactiveListUI')->name('inmate.inactivelist');
        Route::get('adduser/{id?}', 'InmateController@addInmateUI')->name('inmate.add');
        Route::get('viewuser/{id?}', 'InmateController@viewInmateUI')->name('inmate.view');
        Route::get('userservicedetails/{id?}', 'InmateController@inmateServiceDetailsUI')->name('inmate.servicedetails');
        Route::get('sentuseremaillist/{id?}', 'SendMailController@sentInmateEmailListUI')->name('inmate.sentinmateemaillist');
        Route::get('contactlist/{id}', 'InmateContactController@index')->name('contactlist');
        Route::get('userloggedhistory/{inmate_id}', 'InmateController@inmateLoggedHistoryUI')->name('inmate.inmateloggedhistory');
        Route::post('blockuserservice', 'SuperadminController@blockUserservice')->name('blockuserservice');
        Route::get('getbsdetails/{id}', 'SuperadminController@getBlockserviceDetails');
        Route::get('configuration', 'SuperadminController@configurationUI')->name('superadmin.configuration');
        Route::post('termsupdate', 'SuperadminController@updateTerms');
        Route::post('deviceoff_status', 'SuperadminController@updateDeviceoff');
        Route::post('changviewstatus', 'InmateController@changeViewstatus');
        //Route for Managing Facility
        Route::get('facilityservicedetails/{id?}', 'InmateController@facilityServiceDetailsUI')->name('facility.servicedetails');
        Route::get('facilities', 'FacilityController@facilityListUI')->name('facility.list');
        Route::get('addfacility/{id?}', 'FacilityController@addFacilityUI')->name('facility.add');
        Route::get('viewfacility/{id?}', 'FacilityController@viewFacilityUI')->name('facility.view');
        Route::get('facilityinactivelist', 'FacilityController@facilityInactiveListUI')->name('facility.inactivelist');
        Route::get('tracelogin', 'SuperadminController@traceUserlogin')->name('tracelogin.list');
        Route::get('getuserlogindetails/{id?}', 'SuperadminController@getUserloginlist');

        //Route for Managing Facility Administrator
        Route::get('facilityadmindashboard', 'FacilityAdminController@fadminDashboard')->name('fadmin.dashboard');
        Route::get('addfadmin/{id?}', 'FacilityAdminController@addFacilityadminUI')->name('fadmin.add');
        Route::get('fadmins', 'FacilityAdminController@facilityAdminlistUI')->name('fadmin.list');
        Route::get('fadmininactivelist', 'FacilityAdminController@facilityAdmininactiveListUI')->name('fadmin.inactivelist');
        Route::get('getfadminlist', 'FacilityAdminController@facilityAdminlist');
        Route::get('getfadmininactivelist', 'FacilityAdminController@FAinactiveList');
        Route::get('getfadmindata/{id?}', 'FacilityAdminController@getFacilityadmin');
        Route::post('assignfacility', 'FacilityAdminController@assignFacility')->name('assign.facility');
        Route::post('deletefadmin/{id?}', 'FacilityAdminController@deleteFacilityadmin')->name('delete.fadmin');
        Route::post('updatefacilityadmin', 'FacilityAdminController@updateFacilityadmin')->name('update.fadmin');
        Route::post('activatefadmin/{id?}', 'FacilityAdminController@activateFacilityadmin');
        Route::get('getfa_list', 'FacilityAdminController@getFAlist');
        //Route for Managing Company
        Route::get('add_company/{id?}', 'CompanyController@addCompanyUI')->name('cmpy.add');
        Route::get('company_list', 'CompanyController@compnayListUI')->name('cmpy.list');
        Route::post('register_company', 'CompanyController@registerCompany');
        Route::get('getcompanylist', 'CompanyController@getCompanydata');
        Route::get('getcompany_list', 'CompanyController@getCompanylist');
        Route::post('updateorganization', 'CompanyController@updateOrg');
        /*Email Functionality route start************************************************************ */
        Route::post('deletewh_email/{id}','EmailController@deleteWhemail');
        Route::get('getwhemaildetail/{id}','EmailController@getEmialdetail');
        Route::post('updatewh_email','EmailController@updateWhEmail');
        Route::get('whemaillist','EmailController@whitelistedEmail')->name('email.list ');
        Route::post('addwhemail','EmailController@addWhitelistedEmail');
        Route::get('getwhemaildata','EmailController@whitelistedEmaildata');
        
        Route::get('whemaillist','EmailController@whitelistedEmail')->name('email.list ');
        Route::post('generateemail','InmateController@generateEmail');
        Route::get('get_user_email','EmailController@listEmailUI');
        Route::get('getemaildata','EmailController@getEmaildata');
        Route::post('view_useremail/{id}','EmailController@viewUserEmail');
        Route::post('approve_email/{id}','EmailController@approveEmail');
        Route::post('approve_attach/{id}','EmailController@approveAttachment');

         Route::get('get_rejected_mail','EmailController@rejectedemailUI');
         Route::get('get_approved_mail','EmailController@approvedemailUI');
         Route::get('getrejectedemaildata','EmailController@getRejectedmail');
         Route::get('getapprovedemaildata','EmailController@getApprovedmail');
         Route::get('send_email','EmailController@sendEmailUI');
         Route::post('send_mailto','EmailController@sendEmail');

         Route::post('view_attachment/{email_id}','EmailController@viewAttachment');
        //  For Downloading and showing Excel Reports using ExcelController  
         Route::get('download_facilitylist/{facilityadmin_id}', 'ExcelController@facilityListreportFAdmin')->name('fadmin.facilitylist');
        Route::get('all_users_service_history_report/{id}/{date?}', 'ExcelController@allInmateServiceHistoryReport')->name('all_user_service_history_report');
        Route::get('estimateserviceuses/{facility_id}/{date?}', 'ExcelController@estimateServiceuses')->name('get_used_servic_data');
        Route::get('user_fund_history_report/{id}', 'ExcelController@inmateFundhistory')->name('user_fund_history_report');
        Route::get('device_list_report/{id}', 'ExcelController@deviceListreport')->name('device_list_report');
        Route::get('all_user_service_history_details/{id}/{s_date}/{e_date}', 'ExcelController@allInmateServiceHistoryDetails')->name('all_user_service_history_details');
        Route::get('user_service_history_report/{id}', 'ExcelController@inmateServiceHistoryReport')->name('user_service_history_report');
        Route::get('user_service_report_detail/{id}', 'ExcelController@inmateServicereport')->name('user_service_report_details');
        Route::get('userreport/{id}', 'ExcelController@userReport')->name('userreport');
        Route::get('servicereport', 'ExcelController@serviceReport')->name('servicereport');
        Route::get('facilityreport', 'ExcelController@facilityReport')->name('facilityreport');
        Route::get('perfacilityreport/{id}', 'ExcelController@singleFacilityReport')->name('perfacilityreport');
        Route::get('facilityusersreport/{id}', 'ExcelController@facilityUsersReport')->name('facilityusersreport');
        Route::get('inactivefacilityreport', 'ExcelController@inactiveFacilityReport')->name('inactivefacilityreport');
        Route::get('inactiveuser_facilityreport/{id}', 'ExcelController@inactiveUsersFacilityReport')->name('inactiveuser_facilityreport');
        Route::get('user_email_report/{id}', 'ExcelController@userEmailReport')->name('user_email_report');
        Route::get('user_contact_report/{id}', 'ExcelController@userContactReport')->name('user_contact_report');
        Route::get('vendor_report/{service_id}', 'ExcelController@vendorReport')->name('vendor_report');
        Route::get('user_family_report', 'ExcelController@familyReport')->name('user_family_report');
        Route::get('facility_service_report/{facility_id}', 'ExcelController@serviceFacilityReport')->name('facility_service_report');

        //Routes for Category Ordering.
        Route::get('category_up/{id}', 'ServicePermissionController@moveCategoryUp')->name('category_up');
        Route::get('category_down/{id}', 'ServicePermissionController@moveCategoryDown')->name('category_down');
        Route::get('service_up/{id}', 'ServicePermissionController@moveServiceUp')->name('service_up');
        Route::get('service_down/{id}', 'ServicePermissionController@moveServiceDown')->name('service_down');

        //for facility admin to update or delete inmate contact details
        Route::post('update_logcheck','InmateController@updateLogcheck');
        
        Route::get('updatecontact/{id}', 'InmateContactController@edit')->name('updatecontact');

        /* Device route start */
        Route::get('adddevice/{id?}', 'DeviceController@addDeviceUI')->name('device.add');
        Route::get('viewdevice/{id?}', 'DeviceController@viewDeviceUI')->name('device.view');
        Route::get('devices/{id?}', 'DeviceController@deviceListUI')->name('device.list');
        Route::get('deviceinactivelist', 'DeviceController@deviceInactiveListUI')->name('device.inactivelist');
        Route::post('getdevicelist', 'DeviceController@getDevicebyFacility');
        Route::post('change_deviceStatus', 'DeviceController@ChangeDeviceStatus');
        Route::post('appupdate', 'DeviceController@appUpdate');
        Route::post('checkuncheckapp/{id}/{value}', 'DeviceController@enableAppupdate')->name('device.enableupdate');
        /* Family route start */
        Route::get('addfamily/{inmate_id}/{family_id?}', 'FamilyController@addFamilyUI')->name('family.add');
        Route::get('families/{inmate_id?}', 'FamilyController@familyListUI')->name('family.list');
        Route::get('viewfamily/{inmate_id}/{family_id?}', 'FamilyController@viewFamilyUI')->name('family.view');
        Route::get('familiyinactivelist/{inmate_id?}', 'FamilyController@familyInactiveListUI')->name('family.inactivelist');
    });
    
    Route::get('deletecontact/{id}', 'InmateContactController@destroy');

    /* Facility route start */
    Route::get('facilitydashboard', 'FacilityController@facilityDashboard')->name('facility.dashboard');
    Route::get('facilityforgetpassword', 'FacilityController@facilityForgetPasswordUI')->name('facility.forgetpasseord');
    Route::get('userdisputereport/{user_id}', 'InmateReportHistoryController@getLoginInmateReportListUI')->name('user_dispute_report');
    /* Facility route end */

    /* Family Route Start */
    route::post('inmatepaymentscreen', 'FamilyController@inmatePaymentScreen')->name('inmatepayment.screen');
    route::post('paymentstatusscreen', 'FamilyController@paymentStatusScreenUI');
    route::get('paymentstatusscreenshow/{amount?}/{response?}', 'FamilyController@paymentStatusScreenUIShow');
     route::get('paymentstatusscreenshowexternal/{amount?}/{response?}/{user?}', 'FamilyController@paymentStatusScreenExternalUIShow');

    Route::get('familydashboard', 'FamilyController@familyDashboard')->name('family.dashboard');
    Route::get('viewinmatefamily', 'FamilyController@viewInmateFamilyUI')->name('family.viewinmatefamily');
    route::get('familyrechargeactivity', 'FamilyController@viewFamilyRechargeActivityUI')->name('family.familyrechargeactivity');
    /* Family Route end */
});
Auth::routes();

Route::get('getusersms/{inmate_id}', 'InmateContactController@listInmateSMS')->name('inmate.sms');
// Inbox emails read and save to database
Route::get('/inboxmails/{inmate_id}', 'RecievedMailController@getInboxMail')->name('inboxmails');
Route::get('smsreply', 'InmateController@smsReply');

//For android app page where inmate can add add contact number and email
Route::get('contactnumber/{inmate_id}/{service_id}', 'InmateContactController@androidPhoneIndex')->name('contactnumber');
Route::get('emailid/{inmate_id}/{service_id}', 'InmateContactController@androidEmailIndex')->name('emailid');

//for Phone number verification 
Route::get('pnv/{token}', 'InmateContactController@phoneNumberVerification');

//For showing inmate their emails and delete email.
Route::get('generalinbox/{inmate_id}/{service_id}', 'SendMailController@generalMailUI')->name('generalinbox');
Route::get('sendemail/{inmate_id}/{service_id}', 'SendMailController@sendEmailUI')->name('inmate.sendmail');
Route::get('/viewdeletedemail/{inmate_id}/{service_id}', 'RecievedMailController@viewInmateDeletedMail')->name('viewdeletedemail');
Route::get('/viewallemails/{inmate_id}/{service_id}', 'RecievedMailController@viewInmateInboxMail')->name('viewallemails');
Route::get('sentbox/{inmate_id}/{service_id}', 'SendMailController@sentEmailDetail')->name('inmate.sentbox');
Route::get('inboxemailview/{inmate_id}/{service_id}/{mail_id}', 'SendMailController@inboxEmailView')->name('inmate.inboxemailview');
Route::get('generalinboxemailview/{inmate_id}/{service_id}/{mail_id}', 'SendMailController@generalEmailView')->name('inmate.generalemailview');
Route::get('sentemailview/{inmate_id}/{service_id}/{mail_id}', 'SendMailController@sentmailView')->name('inmate.sentemailview');
Route::get('/deletesentmail/{inmate_id}/{service_id}/{mail_id}', 'RecievedMailController@deleteInmateSentMail')->name('deletesentmail');
Route::get('/deleteinboxmail/{inmate_id}/{service_id}/{mail_id}/{delete}', 'RecievedMailController@deleteInmateInboxMail')->name('deleteinboxmail');

//For showing inmate SMS details.  
Route::get('/deletesms/{inmate_id}/{service_id}/{sms_id}/{delete}', 'InmateContactController@deleteInmateSMS')->name('deletesms');
Route::get('/viewnumberlist/{inmate_id}/{service_id}', 'InmateContactController@getInmateContactNumber')->name('viewnumberlist');
Route::get('/viewdeletedsms/{inmate_id}/{service_id}', 'InmateContactController@getInmateDeletedSMS')->name('viewdeletedsms');
Route::get('/viewdeletedchat/{inmate_id}/{service_id}/{number_id}', 'InmateContactController@getInmateDeletedChatSMS')->name('viewdeletedchat');
Route::get('/viewchat/{inmate_id}/{service_id}/{number_id}', 'InmateContactController@getInmateChatSMS')->name('viewchat');

// for blacklist words
Route::get('blacklist', 'BlackListedWordController@create')->name('blacklist.createadd');
Route::get('blacklist_word', 'BlackListedWordController@index');
Route::post('blacklistcreate', 'BlackListedWordController@addblacklistWord');
Route::delete('deleteblacklistedword/{id?}', 'BlackListedWordController@deleteBlacklisted');
 Route::get('blacklist_word/{id?}', 'BlackListedWordController@create')->name('blacklist.create');
 Route::post('updateblacklistword/{id?}', 'BlackListedWordController@updateBlacklist');

 Route::post('ignoreblacklisted','SendMailController@ignoreblacklisted');
  Route::post('ignoreblacklistedsms','SendMailController@ignoreblacklistedSms');

// User recharge by friend without regsitering details

Route::get('user_recharge', 'FamilyController@familyRechargeFromOutsideUI');

Route::post('user_image', 'UserController@userImageUpload');
Route::get('search/autocomplete', 'FacilityController@autocomplete');

// add pre approved email

Route::get('addemail', 'PreApprovedEmailController@addEmailUI')->name('preapprovedemail.addemail');
Route::post('addemailid', 'PreApprovedEmailController@addEmailId');
Route::get('emaillist', 'PreApprovedEmailController@allPreEmail');
Route::get('preapprovedemail/{id?}', 'PreApprovedEmailController@create')->name('approvedemail.create');
 Route::post('updatepreemail/{id?}', 'PreApprovedEmailController@updatePreEmail');
Route::delete('preapprovedemaildelete/{id?}', 'PreApprovedEmailController@preApprovedEmailDelete');

Route::get('emailinactivelist', 'PreApprovedEmailController@emailInactiveListUI');
Route::post('activeemail', 'PreApprovedEmailController@activeEmail');

// add pre approved contacts

Route::get('contactlist', 'PreApprovedEmailController@allPreContact');
Route::get('addcontact', 'PreApprovedEmailController@addContactUI')->name('preapprovedemail.addcontact');

Route::post('addcontact', 'PreApprovedEmailController@addContact');
Route::delete('contactdelete/{id?}', 'PreApprovedEmailController@preContactDelete');

Route::get('preapprovedcontact/{id?}', 'PreApprovedEmailController@edit')->name('approvedcontact.edit');
 Route::post('updateprecontact/{id?}', 'PreApprovedEmailController@updatePreContact');
Route::get('contactinactivelist', 'PreApprovedEmailController@contactInactiveListUI');
Route::post('activecontact', 'PreApprovedEmailController@activeContact');
Route::post('/defaultpermissionbyfacility', 'ServicePermissionController@defaultPermissionByFacility');

// M&S url

Route::get('urllist', 'BlackListedWordController@urllist')->name('urllist');;
Route::get('addurl', 'BlackListedWordController@addUrl')->name('add.addurl');
Route::post('addallowurl', 'BlackListedWordController@addAllowUrl');
 Route::post('updateurl/{id?}', 'BlackListedWordController@updateUrl');

 Route::delete('deleteurl/{id?}', 'BlackListedWordController@deleteUrl');
 Route::get('inactive', 'BlackListedWordController@inactivList')->name('inactive.inactivelist');
 Route::post('activeurl', 'BlackListedWordController@activeUrl');
 
 Route::get('resendvarification', 'InmateContactController@resendVarification');
Route::get('playmovies/{id?}', 'MovieController@playMovies')->name('movie.play');
Route::get('/clear-cache', function() {

    Artisan::call('route:clear');
    Artisan::call('view:clear');
  
    Artisan::call('cache:clear');
   
});


Route::post('/getnews','NewsController@getNews');




