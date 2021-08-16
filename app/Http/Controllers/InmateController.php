<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash as Hash;
use \App\Mail\OrderShipped;
use Mail;
use Validator;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use App\User;
use App\Service;
use App\Admin;
use App\Facility;
use App\Family;
use App\Device;
use App\InmateReportHistory;
use Lang;
use App\InmateContacts;
use App\InmateConfiguration;
use Twilio\Rest\Client;
use App\InmateChargesHistory;
use App\InmateLoggedHistory;
use App\InmateSetMaxContact;
use App\InmateDetails;
use App\InmateSMS;
use App\StaffLog;
use App\DefaultServicePermission;
use App\ServicePermission;
use App\SecurityQuestion;
use App\UserAnswer;
use App\BlackListedWord;
use App\PreApprovedContacts;
use Spatie\Permission\Models\Role as Roles;
use DB;
use Log;
use App\IncomingMail;
Use Exception;
use Yajra\Datatables\Facades\Datatables;
use View;
use App\ServiceChargeByFacility;
use App\FreeMinute;
use Illuminate\Support\Facades\URL;

/**
 * User Controller
 */
class InmateController extends Controller {

    /**
     * Function for load inmate Add UI.
     * 
     * @param object Request $request The inmate details keyed inmate ID.
     * 
     * @return NULL.
     */
    public function addInmateUI(Request $request) {

        loggedInUser();
        //$alldevices = User::select('device_id')->where('device_id', "<>", null)->distinct()->get();
        $alldevices = [];
        $edit ='';
        if (Auth::user()->hasAnyRole(['Facility Admin', 'Facility Staff'])) {
            /* Role for facility admin */
            if (Auth::user()->hasRole('Facility Staff')) {
                $admin_id = Auth::user()->admin_id;
            } else {
               $admin_id = Auth::user()->id; 
            }
            
            $facilityList = Facility::where('is_deleted', config('axxs.active'))
                            ->where('facility_user_id', $admin_id)->get();
            
             $stafffacility = Facility::where('is_deleted', config('axxs.active'))->where('facility_user_id',$admin_id)->first();
            $alldevices = Device::where('is_deleted', config('axxs.active'))
                            ->where('facility_id', $stafffacility->id)->get();
            $edit= 1;
        } else {
            /* Role super admin and specialist */

            $facilityList = Facility::where('is_deleted', config('axxs.active'))->get();
            $deviceList = Device::where('is_deleted', config('axxs.active'))
                    ->get();

        }

        if (isset($request->id)) {
            $userInfo = User::where('id', $request->id)
                    ->where('role_id', 4)
                    ->where('is_deleted', config('axxs.active'))
                    ->first();

            $getfacilityid = Facility::where('facility_user_id', $userInfo->admin_id)->value('id');
            $alldevices = Device::where('facility_id', $getfacilityid)->where('is_deleted', config('axxs.active'))->get();
            $edit= 1;
            $validateReturn = new User();
            $isValidate = $validateReturn->validateInmateStaffFacility($userInfo, Auth::user());
            if ($isValidate) {
                $Inmateemail = InmateDetails::where('inmate_id', $request->id)->first();
                return View('inmate.addinmate', array('facilityList' => $facilityList, 'userInfo' => $userInfo,'Inmateemail' => $Inmateemail, 'alldevices' => $alldevices, 'edit' => $edit));
            } else {
                return redirect(route('inmate.inmatelist'));
            }
        } else {
            
            return View('inmate.addinmate', array('facilityList' => $facilityList, 'alldevices' => $alldevices,'edit' => $edit));
        }
    }

    /**
     * Function for load inmate view UI.
     * 
     * @param object Request $request The inmate details keyed inmate ID.
     * 
     * @return NULL
     */
   public function viewInmateUI(Request $request) {
        if (Auth::user()->hasAnyRole(['Facility Admin', 'Facility Staff'])) {
            /* Role for facility admin */
            if (Auth::user()->hasRole('Facility Staff')) {
                $admin_id = Auth::user()->admin_id;
            } else {
               $admin_id = Auth::user()->id; 
            }
                $facilityList = Facility::where('is_deleted', config('axxs.active'))->where('facility_user_id', $admin_id)->get();
                $stafffacility = Facility::where('is_deleted', config('axxs.active'))->where('facility_user_id',$admin_id)->first();
            $deviceList = Device::where('is_deleted', config('axxs.active'))
                            ->where('facility_id', $stafffacility->id)->get();
            
        } else {
            /* Role for family and super admin */
            $facilityList = Facility::where('is_deleted', config('axxs.active'))->get();
            $deviceList = Device::where('is_deleted', config('axxs.active'))->get();
        }
        if (isset($request->id)) {
            $userInfo = User::with(['inmateFacility', 'inmateEmail'])->where('id', $request->id)
                    ->where('is_deleted', config('axxs.active'))
                    ->where('role_id', 4)
                    ->first();
            $validateReturn = new User();
            $isValidate = $validateReturn->validateInmateStaffFacility($userInfo, Auth::user());
            if ($isValidate) {
                return View('inmate.viewinmate', ['facilityList' => $facilityList, 'userInfo' => $userInfo, 'deviceList' => $deviceList]);
            } else {
                return redirect(route('inmate.inmatelist'));
            }
        }
    }

    public function inmateListUI($facility_id = null) {
            
            if (Auth::user()->hasAnyRole(['Facility Admin', 'Facility Staff'])) {
                 if (Auth::user()->hasRole('Facility Staff')) {
                    $admin_id = Auth::user()->admin_id;
                } else {
                   $admin_id = Auth::user()->id; 
                }
                    $maxLimitbyfacility = InmateSetMaxContact::where('user_id', $admin_id)->first();
                    $userList = User::select('users.*',DB::raw('(CASE WHEN (SELECT count(id) as dispute_id from inmate_report_history WHERE view = 0 && inmate_report_history.inmate_id = users.id)  > 0 THEN 1 ELSE 0 END) AS is_view'))->where('users.inmate_id', '!=', NULL)
                                    ->where('admin_id', $admin_id)
                                    ->leftjoin('inmate_report_history' ,'users.id' , 'inmate_report_history.inmate_id')
                                    ->distinct('userd.id')
                                    ->get();
                
                         
                return View('inmate.inmatelistnew', ['userList' => $userList, 'maxLimitbyfacility' => $maxLimitbyfacility]);
            } else {
                $facility = Facility::select('facility_name','facility_user_id')->where('is_deleted',config('axxs.active'));
                if (Auth::user()->hasRole('Facility Administrator')) {

                    $fa_id = \App\FacilityAdmin::where('fa_user_id',Auth::user()->id)->value('id');
                    $facility = $facility->where('facility_admin',$fa_id);
                }
                $userList = [];
                if ($facility_id) {
                    $objUser = new User();
                    $userList = $objUser->getUsersInfo($facility_id);
                }
                $maxLimitbyfacility = InmateSetMaxContact::where('user_id', Auth::user()->id)->first();
                $facility = $facility->orderBy('facility_name')->get();
                return View('inmate.inmatelistnew', ['userList' => $userList, 'maxLimitbyfacility' => $maxLimitbyfacility,'facility' => $facility ,'facility_id' => $facility_id]);
            }
        }

    /**
     * Function for load inmate list UI.
     * 
     * @param object Request $request The inmate details keyed inmate ID 
     * 
     * @return NULL
     */
    /*public function inmateListUI($facility_id = null) {
        $all = false;
        if (Auth::user()->hasAnyRole(['Facility Admin', 'Facility Staff'])) {
             if (Auth::user()->hasRole('Facility Staff')) {
                $admin_id = Auth::user()->admin_id;
            } else {
               $admin_id = Auth::user()->id; 
            }
                $maxLimitbyfacility = InmateSetMaxContact::where('user_id', $admin_id)->first();
                $userList = User::select('users.*',DB::raw('(CASE WHEN (SELECT count(id) as dispute_id from inmate_report_history WHERE view = 0 && inmate_report_history.inmate_id = users.id)  > 0 THEN 1 ELSE 0 END) AS is_view'))->where('users.inmate_id', '!=', NULL)
                                ->where('admin_id', $admin_id)
                                ->leftjoin('inmate_report_history' ,'users.id' , 'inmate_report_history.inmate_id')
                                ->distinct('userd.id')
                                ->get();
            
                     
            return View('inmate.inmatelist', ['userList' => $userList, 'maxLimitbyfacility' => $maxLimitbyfacility]);
        } else {
            
            $facility = Facility::select('facility_name','facility_user_id')->where('is_deleted',config('axxs.active'));
            if (Auth::user()->hasRole('Facility Administrator')) {

                $fa_id = \App\FacilityAdmin::where('fa_user_id',Auth::user()->id)->value('id');
                $facility = $facility->where('facility_admin',$fa_id);
            }
            $userList = [];
            if ($facility_id) {
                $objUser = new User();
                if ($facility_id == 'all') {
                    $all = true;
                    $all_facilityId = Facility::select('facility_user_id')->where('facility_admin',$fa_id)->pluck('facility_user_id')->toArray();
                    $userList = $objUser->getUsersInfo($all_facilityId,$all);

                }else{
                    $userList = $objUser->getUsersInfo($facility_id);
                }
                
            }

            $maxLimitbyfacility = InmateSetMaxContact::where('user_id', Auth::user()->id)->first();
            $facility = $facility->orderBy('facility_name')->get();
            
            return View('inmate.inmatelist', ['userList' => $userList, 'maxLimitbyfacility' => $maxLimitbyfacility,'facility' => $facility ,'facility_id' => $facility_id, 'all' => $all]);
        }
    }*/

    public function inmateListdata(Request $request,$id){
       if ($request->ajax()) {
         if (Auth::user()->hasRole('Facility Staff')) {
            $admin_id = Auth::user()->admin_id;
        } else {
           $admin_id = Auth::user()->id; 
        }

        $maxLimitbyfacility = InmateSetMaxContact::where('user_id', $admin_id)->first();  
           $objUser = new User();
                   $userList = $objUser->getUsersInfo($id);
                   return Datatables::of($userList)
                   ->addIndexColumn()
                   ->addColumn('log_check',function($userList){
                    if ($userList->is_log == 1) {
                        return '<input type="checkbox" onclick="boxDisable('.$userList->id.');" checked id="lc'.$userList->id.'" name="log_check" value="'.$userList->id.'">';
                    }else{
                        return '<input type="checkbox" onclick="boxDisable('.$userList->id.');"  id="lc'.$userList->id.'" name="log_check" value="'.$userList->id.'">';
                    }
                    
                   })
                   ->addColumn('action', function ($userList) use ($maxLimitbyfacility) {
                       $html = View::make('inmate.inmateaction', [
                           'val' => $userList,'maxLimitbyfacility' => $maxLimitbyfacility
                       ]);
                       return $html;
                              })
                   ->editColumn('balance',function($userList){
                    return round($userList->balance,2);
                   })
                   ->blacklist(['DT_RowIndex','facility_name','log_check'])
                   ->make(true);
       }
        
    }
    /**
     * Function for load inmate list UI.
     * 
     * @param object Request $request The inmate details keyed inmate ID 
     * 
     * @return NULL
     */
    public function inmateInactiveListUI() {

       if (Auth::user()->hasAnyRole(['Facility Admin', 'Facility Staff'])) {
            if (Auth::user()->hasRole('Facility Staff')) {
                $userList = User::with(['inmateEmail', 'inmateFacility'])->where('is_deleted', config('axxs.inactive'))
                        ->where('admin_id', Auth::user()->admin_id)
                        ->where('role_id', 4)
                        ->get();
            } else {
                $userList = User::with(['inmateEmail', 'inmateFacility'])->where('is_deleted', config('axxs.inactive'))
                        ->where('admin_id', Auth::user()->id)
                        ->where('role_id', 4)
                        ->get();
            }
        } else {
            $userList = User::with(['inmateEmail', 'inmateFacility'])->where('is_deleted', config('axxs.inactive'))->where('role_id', 4)->get();
        }

        return View('inmate.inmateinactivelist', array('userList' => $userList));
    }

    /**
     * Function for load inmate loggedlist UI.
     * 
     * @param object Request $request The inmate details keyed inmate ID 
     * 
     * @return NULL
     */
    public function inmateLoggedHistoryUI($inmate_id) {
        $facilityid = User::where('id', $inmate_id)
                ->where('role_id', 4)
                ->where('is_deleted', config('axxs.active'))
                ->first();

        $validateReturn = new User;
        $isValidate = $validateReturn->validateInmateStaffFacility($facilityid, Auth::user());
        if ($isValidate) {
           $inmateLoggedList = InmateLoggedHistory::where('inmate_id', $inmate_id)->orderBy('created_at','desc')->get();
            
            return View('inmate.inmateloggedhistory', ['inmateLoggedList' => $inmateLoggedList]);
        } else {
            return redirect(route('inmate.inmatelist'));
        }
    }

    /**
     * Handle an authentication attempt for user controller.
     * 
     * @param object Request $request The inmate details keyed email
     *                               and password,
     * 
     * @return \Illuminate\Http\JsonResponse user information send as a format of JSON in response
     */
      public function authenticateInmate(Request $request) {

        try {
            $data = $request->input();

            $rules = array(
                'email' => 'required',
                'password' => 'required',
                'deviceimei' => 'required'
            );
            $validate = Validator::make($data, $rules);

            if ($validate->fails()) {
                return response()->json(array(
                            'Status' => Lang::get('common.success'),
                            'Code' => Response::HTTP_OK,
                            'Message' => $validate->errors()->all(),
                ));
            } else {
                $device = Device::where('imei', $data['deviceimei'])->first();
                if (empty($device)) {
                    return response()->json(array('Code' => Response::HTTP_OK,
                                'Status' => Lang::get('common.success'),
                                'Message' => Lang::get('inmate.divice_not_found')
                    ));
                }

                //newloginlogic
                if ($device->facility_id) {
                    
                    $get_facility_userid = Facility::where('id',$device->facility_id)->value('facility_user_id');
                    $device_check = $this->deviceCheck($device->facility_id);
                    if (!$device_check) {
                        return response()->json(array('Code' => Response::HTTP_OK,
                                'Status' => Lang::get('common.success'),
                                'Message' => 'Device is off'
                            ));
                    }
                    if ($get_facility_userid != null) {
                        $check_for_password = User::where(['username' => trim($data['email']), 'admin_id' => $get_facility_userid])->first();

                        if ($check_for_password == null) {
                            $check_for_password = User::where(['username' => trim($data['email']),'role_id' => 2])->first();
                        }
                            //dd(json_encode($check_for_password->password));
                            if (isset($check_for_password->password)) {
                                $check = Hash::check($data['password'],$check_for_password->password);
                                
                                if ($check == true) {
                                    $user_id = $check_for_password->id;
                                } else{
                                    //paswword incorrect
                                    return response()->json(array('Code' => Response::HTTP_OK,
                                                'Status' => Lang::get('common.failure'),
                                                'Message' => 'Incorrect Password'
                                    ));
                                }
                            } else {
                                //no password found
                                return response()->json(array('Code' => Response::HTTP_OK,
                                            'Status' => Lang::get('common.failure'),
                                            'Message' => 'Device is not authorized'
                                ));
                            }

                    } else {
                        //no facility user id found
                        return response()->json(array('Code' => Response::HTTP_OK,
                                     'Status' => Lang::get('common.failure'),
                                     'Message' => 'Unable to find facilty user id'
                        ));
                    }
                    
                } else {
                    //no facilty id found
                    return response()->json(array('Code' => Response::HTTP_OK,
                                'Status' => Lang::get('common.failure'),
                                'Message' => 'No facility associated with this device'
                                            ));
                }

                if (isset($device)) {
                    $randomPassword = str_random(10);
                    $device->device_password = $randomPassword;
                    $device->save();
                    $datanew['NewPassword'] = $randomPassword;
                    $datanew['DeviceImei Number'] = $data['deviceimei'];
                }
                
                
                //endlogic
                if (Auth::loginUsingId($user_id,true)) {
                    $token = auth()->user()->createToken('logintoken')->accessToken;
                    $user = Auth::user();
                    if (isset($user['attributes']['status']) && !empty($user['attributes']['status']) && $user['attributes']['status'] === 1) {
                        return response()->json(array('Code' => Response::HTTP_OK,
                                    'Status' => Lang::get('common.failure'),
                                    'Data' => $datanew,
                                    'Message' => Lang::get('inmate.inmate_block_status')
                        ));
                    } else {
                        $isinmateactive = new InmateDetails();
                        if ($isinmateactive->inmateActive($user->id)) {

                            /* API token update in login success case */
                            $apiToken = str_random(256);
                            $obj_user_api_token = User::find($user['attributes']['id']);
                            $obj_user_api_token->api_token = $apiToken;
                            $obj_user_api_token->save();

                            //getnegative balance
                            $negBal = InmateConfiguration::where('key','negative_balance')->value('value');
                            $freeMin = (int)InmateConfiguration::where('key',\Config::get('axxs.free_minutes_key'))->value('value');
                            $tab_chg_off = (int)InmateConfiguration::where('key',\Config::get('axxs.tablet_charge_on_off'))->value('content');
                            
                            if (Auth::Check()) {
                                //$totalLefTime1 = $this->calculateFreeLeftTime($user->id);
                                $freeMinutesleft = $this->freeMinutesLeft($user->id,$freeMin);
                                $fac_details = Facility::where('facility_user_id', $user->admin_id)->first();
                                //checking facility tablet charge and tablet charge on /off
                                if ($fac_details->tablet_charge == 0 || $fac_details->tablet_charges == 0 || $tab_chg_off == 0) {
                                    $lg = 1; //can login
                                } else {
                                    $lg = 0; //can not login
                                }
                                if ($negBal == '0.00') {
                                   $negativeBal = abs($negBal);
                                    }else{
                                     $negativeBal = -1 * abs($negBal);   
                                    }

                                 if(Auth::user()->role_id == 4 && Auth::user()->balance < 0.01 && $freeMinutesleft == 0 && $lg == 0 ) {
                                         return response()->json(array('Code' => Response::HTTP_OK,
                                        'Status' => Lang::get('common.failure'),
                                        'Data' => $datanew,
                                        'Message' => "Insufficient Balance to use the service."
                                    ));
                                 }
                                $tabletAutoLoggedTime = InmateConfiguration::where('id', config('axxs.auto_logged_time'))
                                                ->where('is_deleted', config('axxs.active'))->first();
                                /* here we have update inmate login time */
                                $inmateLoggedRequierValue = [];
                                $inmateLoggedRequierValue['api_token'] = $apiToken;
                                $inmateLoggedRequierValue['inmate_id'] = $user['attributes']['id'];
                                $inmateLoggedRequierValue['device_id'] = $device->imei;

                                $obj_inmate_logged = new InmateLoggedHistory();
                                $obj_inmate_logged->createLoginTime($inmateLoggedRequierValue);


                                /* here we Genrate API token for logged user */
                                $users = loggedInUser();

                                if ($user->role_id != 4) {
                                    if ($user->hasPermissionTo('Tablet Launcher Setting')) {
                                        $launcher = 1;
                                    } else {
                                        $launcher = 0;
                                    }
                                    if ($user->hasPermissionTo('Tablet Enable Applications')) {
                                        $app_access = 1;
                                    } else {
                                        $app_access = 0;
                                    }
                                    
                                    if ($user->hasPermissionTo('Tablet Clear Database')) {
                                        $clear_db = 1;
                                    } else {
                                        $clear_db = 0;
                                    }
                                    if ($user->hasPermissionTo('Tablet Edit Setting')) {
                                        $edit_setting = 1;
                                    } else {
                                        $edit_setting = 0;
                                    }

                                    $users->roleDetail = [
                                        'launcher' => $launcher,
                                        'setting' => $edit_setting,
                                        'app access' => $app_access,
                                        'clear db' =>$clear_db
                                    ];

                                    $user = array_except($users, array('roles', 'permissions'));
                                }
                               
                                if ($user->role_id == 4) {
                                    if(!(empty($user->device_id))){
                                        $device_validate = Device::where('imei', $data['deviceimei'])
                                                    ->where('id', $user->device_id )->first();
                                    }
                                    else {
                                    $facility = Facility::where('facility_user_id', $user->admin_id)->first();
                                    $device_validate = Device::where('imei', $data['deviceimei'])
                                                    ->where('facility_id', $facility->id)->first();
                                    }
                                    
                                    if (!isset($device_validate)) {
                                        return response()->json(array('Code' => 200,
                                                    'Status' => Lang::get('common.failure'),
                                                    'Data' => $datanew,
                                                    'Message' => Lang::get('inmate.device_not_found')
                                        ));
                                    }
                                    
                                    $freeTabletCharge = InmateConfiguration::where('id', config('axxs.tablet_charges'))
                                                    ->where('is_deleted', config('axxs.active'))->first();
                                    $low_bl_msg = InmateConfiguration::where('key', config('axxs.low_balance_key'))
                                                    ->where('is_deleted', config('axxs.active'))->first();
                                    $free_min_exp_msg = InmateConfiguration::where('key', config('axxs.free_min_exp_key'))
                                                    ->where('is_deleted', config('axxs.active'))->first();
                                    $totalLefTimeinmate = $this->calculateFreeLeftTime($user->id);
                                    if (isset($facility->tablet_charge) && !empty($facility->tablet_charge)) {
                                    $users['facility_charge']= (float)$facility->tablet_charge;
                                    } else{
                                        $users['facility_charge']= (float)$freeTabletCharge->value;
                                    }
                                    $users['Free Time_per_12hr_in_min'] = $totalLefTimeinmate;
                                    $negativeBalance = InmateConfiguration::where('id', config('axxs.negative_balance'))
                                                    ->where('is_deleted', config('axxs.active'))->first();

                                  
                                    $users['Nagetive Balance'] = $negativeBalance->value;
                                }

                                if ($fac_details->tablet_charges == 0 || $tab_chg_off == 0) {
                                    $charge_on_off = 0;
                                } else {
                                    $charge_on_off = 1;
                                }
                                $users['auto_logged_time'] = $tabletAutoLoggedTime->value;
                                $users['tablet_charge_on_off'] = $charge_on_off;
                                /* auto logged time insert */
                                $users['api_token'] = $apiToken;
                                $users['NewPassword'] = $randomPassword;
                                $users['DeviceImei Number'] = $data['deviceimei'];
                               $users['first_login'] = Auth::user()->first_login;
                               $users['token'] = $token;
                               $users['facility_id'] = $device->facility_id;
                               $user['active_free_minutes'] = $freeMinutesleft;
                               $user['recharge_url'] = URL::to('/user_recharge');
                               $user['low_balance_msg'] = (isset($low_bl_msg) ? $low_bl_msg['content'] : Null);
                               $user['free_min_exp_msg'] = (isset($free_min_exp_msg) ? $free_min_exp_msg['content'] : Null);


                               
                               //updating app version in database
                               if (isset($data['app_version'])) {
                                   Device::where('imei', $data['deviceimei'])->update(['app_version_date' => $data['app_version'] ]);
                                   //calculating last login
                               if ($user->last_login_history != null) {
                                   $last_login = $this->calculateLastlogin($user->last_login_history);
                                   $user['last_login_history'] = $last_login;
                               }
                               }

                               //getconfiguration details
                               $wlcm_message = InmateConfiguration::select('content','is_active')->where('is_deleted','0')->where('key','welcome_msg')->first();

                               $tos = InmateConfiguration::select('content','is_active')->where('is_deleted','0')->where('key','terms_of_service')->first();
                               
                                $users['welcome_message_isactive'] = isset($wlcm_message) ? $wlcm_message['is_active'] : null;
                                $facility = Facility::where('facility_user_id', $user->admin_id)->first();
                                if (isset($facility->welcome_msg) && $facility->welcome_msg !== null) {
                                   $users['welcome_message'] = $facility->welcome_msg;
                                }else{
                                    $users['welcome_message'] = isset($wlcm_message) ? $wlcm_message['content'] : null;
                                }
                                if (isset($facility->terms_condition) && !empty($facility->terms_condition)) {
                                    $users['terms_of_service'] = $facility->terms_condition;
                                } else{
                                    $users['terms_of_service'] =isset($tos) ? $tos['content'] : null;
                                }
                                $jsonResponse = response()->json(array('Code' => Response::HTTP_OK,
                                    'Status' => Lang::get('common.success'),
                                    'Message' => Lang::get('inmate.inmate_logged_success'),
                                    'Data' => $users
                                ));

                                /* Logged history update for user date and time update in UTC format */
//                                $obj_user_logged_history = User::find($user['attributes']['id']);
//                                $obj_user_logged_history->last_login_history = strtotime(gmdate("Y-m-d H:i:s"));
//                                $obj_user_logged_history->save();
                                return $jsonResponse;
                            } else {
                                return response()->json(array('Code' => 200,
                                            'Status' => Lang::get('common.failure'),
                                            'Data' => $datanew,
                                            'Message' => Lang::get('inmate.inmate_not_found')
                                ));
                            }
                        } else {
                            return response()->json(array('Code' => Response::HTTP_OK,
                                        'Status' => Lang::get('common.failure'),
                                        'Data' => $datanew,
                                        'Message' => Lang::get('inmate.inmate_block_status')
                            ));
                        }
                    }
                } else {
                    return response()->json(array('Code' => 200,
                                'Status' => Lang::get('common.failure'),
                               'Data' => $datanew,
                                'Message' => Lang::get('inmate.inmate_login_failed')
                    ));
                }
            }
        } catch (\Exception $ex) {
            return errorLog($ex);
        }
    }

    /**
     * Function for load inmate service list UI.
     * 
     * @param object Request $request The inmate details keyed inmate ID 
     * 
     * @return NULL
     */
    public function inmateServiceDetailsUI(Request $request) {

        if (isset($request->id)) {
            $userInfo = User::where('id', $request->id)->where('role_id', 4)->first();
            $validateReturn = new User();
            $isValidate = $validateReturn->validateInmateStaffFacility($userInfo, Auth::user());
            if ($isValidate) {
                $objService = new Service();
                $serviceList = $objService->getServiceFacilityListInfo($userInfo->admin_id);
                $inmateServiceList = $objService->getInmateServiceInfo($request->id);

                $inmateinfo = InmateDetails::where('inmate_id', $request->id)->first();
                $facility = Facility::where('facility_user_id', $userInfo->admin_id)->first();
                $facilityCharge = ServiceChargeByFacility::select('service_id','type','charge')->where('facility_id',$facility->id)->get()->toArray();

                return View('inmateservicedetails', array('serviceList' => $serviceList, 'inmateServiceList' => $inmateServiceList, 'inmate_id' => $request->id, 'inmateinfo' => $inmateinfo,'userinfo' =>$userInfo,'facilityCharge' => $facilityCharge));
            } else {
                return redirect(route('inmate.inmatelist'));
            }
        } else {
            return redirect(route('inmate.inmatelist'));
        }
    }

    /**
     * Function for load facility service list UI.
     * 
     * @param object Request $request The facility details keyed facility ID 
     * 
     * @return NULL
     */
   public function facilityServiceDetailsUI(Request $request) {

        if (isset($request->id)) {
            $userInfo = User::where('id', $request->id)->where('role_id', 2)->first();
            $facility = Facility::where('facility_user_id', $request->id)->first();
            if(isset($facility ) && $facility->twilio_number != Null && !empty($facility->twilio_number)){
                $twilio_exist = 1;
            } else {
                $twilio_exist = 0;
            }
            
          
            if ($userInfo && $userInfo->role_id == 2) {
                $objService = new Service();
                $serviceList = $objService->getServiceListInfo(config('axxs.active'))->where('user_id', 1);
                $inmateServiceList = $objService->getInmateServiceInfo($request->id);
                
                $facilityCharge = ServiceChargeByFacility::select('service_id','type','charge')->where('facility_id',$facility->id)->get()->toArray();
                $objDefaultService = new DefaultServicePermission();
                $inmateDefaultServiceList = $objDefaultService->getInmateDefaultServiceInfo($request->id);
                $facility_name = $facility->facility_name;
                $inmateinfo = 'facility';
                return View('inmateservicedetails', array('serviceList' => $serviceList, 'inmateServiceList' => $inmateServiceList, 'inmate_id' => $request->id, 'inmateinfo' => $inmateinfo, 'twilio_exist' => $twilio_exist,'inmateDefaultServiceList' => $inmateDefaultServiceList,'facilityCharge' =>$facilityCharge,'facilityName' => $facility_name));

                 return View('inmateservicedetails', array('serviceList' => $serviceList, 'inmateServiceList' => $inmateServiceList, 'inmate_id' => $request->id, 'inmateinfo' => $inmateinfo, 'facilityCharge' =>$facilityCharge));
            } else {
                return redirect(route('facility.list'));
            }
        }
    }

    /**
     * Create authorization for API request.
     * 
     * @param NULL
     * 
     * @return JSON in message and status in response
     */
    public function unauthorizedRequest() {
        return response()->json(array('Code' => 503,
                    'Status' => Lang::get('common.success'),
                    'Message' => Lang::get('inmate.service_not_found')
        ));
    }

    /**
     * Create a new inmate instance after a valid registration
     * @param object Request $request The inmate details keyed inmate_id, 
     *                                first_name, facility_name, last_name, email,
     *                                password, device_id, phone, address_line_1,
     *                                address_line_2, zip, city and state
     *                                
     * @return json The id of newly registered inmate keyed id in Response
     */
     public function registerInmate(Request $request) {
        try {
            $data = $request->input(); 
            $rules = array(
                'first_name' => 'required|alpha',
                'last_name' => 'required|alpha',
                'middle_name' => 'nullable|alpha',
                'date_of_birth' => 'required|date',
                'admin_id' => 'required',
                'username' => 'required|unique:users,username,null,id,admin_id,' . $data['admin_id'],
               
            );
            
            $validate = Validator::make($data, $rules);

            if ($validate->fails()) {
                return response()->json(array(
                            'Code' => 400,
                            'Status' => Lang::get('common.success'),
                            'Message' => $validate->errors()->all(),
                            'Response' => array('id' => null)
                ));
            } else {
                if(isset($data['date_of_birth'])){
                     $dateofbirth=date("Y-m-d ",strtotime($data['date_of_birth']));
                }
                  $dateofbirth=date("Y-m-d ",strtotime($data['date_of_birth']));
                  
                $totalusers = User::where('admin_id', $data['admin_id'])->where('is_deleted', 0)->count();
                $facility_total_user = Facility::where('facility_user_id', $data['admin_id'])->first();

                if ($facility_total_user->total_inmate > $totalusers) {
                    $randomPassword=date("mdY",strtotime($data['date_of_birth']));
                   // $randomPassword = substr(md5(microtime()), rand(0, 26), 10);
                    $apiToken = str_random(256); 
                    $user_insert = User::create([
                                'api_token' => $apiToken,
                                'balance' => 0,
                                'first_name' => $data['first_name'],
                                'middle_name' => isset($data['middle_name']) ? $data['middle_name'] : NULL,
                                'date_of_birth' => isset($data['date_of_birth']) ? $dateofbirth : NULL,
                                'last_name' => $data['last_name'],
                                'admin_id' => isset($data['admin_id']) ? $data['admin_id'] : NULL,
                                'first_login' => 0,
                                'username' => $data['username'],
                                'status' => 0,
                                'role_id' => 4,
                                'device_id' => $data['device_id'],
                                'password' => bcrypt($randomPassword),
                                'phone' => isset($data['phone']) ? $data['phone'] : '',
                                'address_line_1' => isset($data['address_line_1']) ? $data['address_line_1'] : '',
                                'address_line_2' => isset($data['address_line_2']) ? $data['address_line_2'] : '',
                                'city' => isset($data['city']) ? $data['city'] : '',
                                'state' => isset($data['state']) ? $data['state'] : '',
                                'zip' => isset($data['zip']) ? $data['zip'] : NULL,
                                'is_deleted' => config('axxs.active'),
                                'location' =>  $data['location'],
                    ]);

                    $role_r = Roles::where('id', 4)->firstOrFail();
                    $user_insert->assignRole($role_r); //Assigning role to user

                    //creating email address
                    $obj_config = new InmateConfiguration;
                    $email_create = $obj_config->getConfiguration('email_create');
                    if (isset($email_create) && $email_create->content == 1 ) {
                        if (isset($facility_total_user) && $facility_total_user->create_email ==1 ) {
                            if (isset($user_insert->id) && !empty($user_insert->id)) {
                                $facility_id = Facility::where('facility_user_id',$user_insert->admin_id)->value('facility_id');
                                $create_email = $this->createEmailadd($user_insert->inmate_id ,$facility_id );
                                if (isset($create_email) && !empty($create_email)) {
                                    User::where('id',$user_insert->id)->update(['email' => $create_email ]);
                                }
                            }
                        }
                    }

                    if (isset($user_insert->id) && !empty($user_insert->id)) {
                        
                        if (Auth::user()->hasRole('Facility Staff')) {
                             $staff_log = new StaffLog();
                             $staff_log_create = $staff_log->staffLogInsert(Auth::user()->id, config('axxs.action.create'), config('axxs.page.inmate'), $user_insert->id, 'Created new user');
                        }
                        /*$facilityInfo = Facility::where('facility_user_id', $data['admin_id'])->first();
                        if (isset($facilityInfo)) {
                            $emailValue = array(
                                'to' => $facilityInfo->email,
                                'title' => Lang::get('email.email_title_inmate_create', [
                                    'inmate_name' => $data['first_name']. " ".$data['last_name'],
                                    'inmate_id' => $data['username']
                                ]),
                                'body' => Lang::get('email.email_inmate_create_password') . $randomPassword
                            );
                        }
                        $request->merge($emailValue);
                        $objSendMail = new SendMailController();
                        $sendEmail = $objSendMail->sendMail($request);*/
                        if (isset($sendEmail)) {
                            //To assign default services
                            $this->assignDefaultServices($data['admin_id'], $user_insert->id);

                            return response()->json(array(
                                        'Code' => 201,
                                        'Message' => Lang::get('common.success'),
                                        'Status' => Lang::get('inmate.inmate_created'),
                                        'Data' => array('id' => $user_insert->id)
                            ));
                        } else {

                           $this->assignDefaultServices($data['admin_id'], $user_insert->id);

                            return response()->json(array(
                                        'Code' => 201,
                                        'Message' => Lang::get('common.success'),
                                        'Status' => Lang::get('inmate.inmate_created'),
                                        'Data' => array('id' => $user_insert->id)
                            ));
                        }
                    }
                    return response()->json(array(
                                'Code' => 401,
                                'Message' => Lang::get('common.success'),
                                'Status' => array(Lang::get('inmate.inmate_not_created')),
                    ));
                } else {
                    return response()->json(array(
                                'Code' => 400,
                                'Status' => Lang::get('common.success'),
                                'Message' => Lang::get('inmate.limited_users', ['totaluser' => $facility_total_user->total_inmate]),
                    ));
                }
            }
        } catch (Exception $ex) {
            return errorLog($ex);
        }
    }

    /**
     * Create function for get all inmate list and his informations.
     * 
     * @param NULL.
     *                                
     * @return NULL.
     */
    public function getAllInmate() {
        $inmateList = User::where('is_deleted', config('axxs.active'))->where('role_id', 4)->get();
        if (count($inmateList) > 0) {
            return response()->json(array(
                        'Status' => Lang::get('inmate.inmate_details'),
                        'Code' => 200,
                        'Message' => Lang::get('common.success'),
                        'Data' => $inmateList
            ));
        }

        return response()->json(array(
                    'Status' => Lang::get('inmate.inmate_not_found'),
                    'Code' => 400,
                    'Message' => Lang::get('common.success'),
        ));
    }

    /**
     * Create function for Get inmate details behalf on inmate id.
     *
     * @param object Request $request The inmate id keyed inmate_id,
     * 
     * @return Json inmate information return in response
     */
    public function getInmate(Request $request) {
        $data = $request->input();
        $rules = array(
            'inmate_id' => 'required',
        );
        $validate = Validator::make($data, $rules);

        if ($validate->fails()) {
            return response()->json(array(
                        'Status' => Lang::get('common.success'),
                        'Code' => 400,
                        'Message' => $validate->errors()->all(),
            ));
        } else {
            $inmateInfo = User::where('id', $data['inmate_id'])->get();
            if (count($inmateInfo) > 0) {
                return response()->json(array(
                            'Status' => Lang::get('common.success'),
                            'Code' => 200,
                            'Message' => Lang::get('inmate.inmate_details'),
                            'Data' => $inmateInfo
                ));
            } else {
                return response()->json(array(
                            'Status' => Lang::get('common.success'),
                            'Code' => 400,
                            'Message' => Lang::get('inmate.inmate_not_found')
                ));
            }
        }
    }

    /**
     * Create function for update inmate details behalf on inmate id.
     *
     * @param object Request $request The inmate id keyed inmate_id, 
     * 
     * @return NULL
     */
    public function updateInmate(Request $request) {
        

        $data = $request->input();
        $inmateemail = InmateDetails::where('inmate_id', $data['id'])->first();

        if ($inmateemail) {
            $rules = array(
                /*'inmate_id' => 'required|unique:users,inmate_id,null,id,admin_id,' . $data['admin_id'],*/
                'first_name' => 'required|alpha',
                'last_name' => 'required|alpha',
                'middle_name' => 'nullable|alpha',
                'date_of_birth' => 'required|date',
                'Useremail' => 'required|email|unique:inmatedetails,email,' . $data['id'] . ',inmate_id',
                'Userpassword' => 'required'
                
            );
        } else {
            $rules = array(
                //'inmate_id' => 'required|unique:users,inmate_id,null,id,admin_id,' . $data['admin_id'],
                'first_name' => 'required|alpha',
                'last_name' => 'required|alpha',
                'date_of_birth' => 'required|date',
                'middle_name' => 'nullable|alpha',
               
            );
        }
        $messages = [
            /*'inmate_id.required' => 'User id is required.',
            'inmate_id.unique' => 'User id has already been assigned',*/

        ];
        $validate = Validator::make($data, $rules, $messages);

        if ($validate->fails()) {
            return response()->json(array(
                        'Status' => Lang::get('common.success'),
                        'Code' => 400,
                        'Message' => $validate->errors()->all()
            ));
        } else {
            
             if(isset($data['date_of_birth'])){
                     $dateofbirth=date("Y-m-d ",strtotime($data['date_of_birth']));
                }
                
            $updateData = array(
                /*'inmate_id' => $data['inmate_id'],*/
                'first_name' => $data['first_name'],
                'middle_name' => isset($data['middle_name']) ? $data['middle_name'] : NULL,
                'date_of_birth' => isset($data['date_of_birth']) ? $dateofbirth : NULL,
                'last_name' => $data['last_name'],
                'admin_id' => $data['admin_id'],
                'device_id' => $data['device_id'],
                'phone' => $data['phone'],
                'address_line_1' => $data['address_line_1'],
                'address_line_2' => $data['address_line_2'],
                'city' => $data['city'],
                'state' => $data['state'],
                'zip' => $data['zip'],
            );

            if ($inmateemail) {
                if ($data['Useremail']) {
                    $inmateemailupdate = InmateDetails::where('inmate_id', $data['id'])->update([
                        'email' => $data['Useremail'],
                        'password' => $data['Userpassword'],
                    ]);
                }
            }
            
            
            $inmateUpdateInfo = User::where(array('id' => $data['id']))->update($updateData);
            if (isset($inmateUpdateInfo) && !empty($inmateUpdateInfo)) {
                
                if (Auth::user()->hasRole('Facility Staff')) {
                    $staff_log = new StaffLog();

                    $staff_log_create = $staff_log->staffLogInsert(Auth::user()->id, config('axxs.action.update'), config('axxs.page.inmate'), $data['id'], 'Updated existing user');
                }

                return response()->json(array(
                            'Status' => Lang::get('common.success'),
                            'Code' => 200,
                            'Message' => Lang::get('inmate.inmate_update'),
                ));
            } else {
                return response()->json(array(
                            'Status' => Lang::get('common.success'),
                            'Code' => 400,
                            'Message' => Lang::get('service.inmate_update_error')
                ));
            }
        }
    }

    /**
     * Create function for update inmate details behalf on inmate id.
     *
     * @param object Request $request The inmate id keyed inmate_id, 
     * 
     * @return NULL
     */
    public function activeInmate(Request $request) {
        $data = $request->input();

        $rules = array(
            'inmate_id' => 'required'
        );
        $validate = Validator::make($data, $rules);

        if ($validate->fails()) {
            return response()->json(array(
                        'Status' => Lang::get('common.success'),
                        'Code' => 400,
                        'Message' => $validate->errors()->all()
            ));
        } else {
            $admin = User::where('id', $data['inmate_id'])->first();
            $totaluser = User::where('admin_id', $admin->admin_id)->where('is_deleted', 0)->count();
            $facility = Facility::where('facility_user_id', $admin->admin_id)->first();
            
            if ($facility->total_inmate > $totaluser) {
                $updateData = array(
                    'is_deleted' => config('axxs.active'),
                );
                $inmateUpdateInfo = User::where(array('id' => $data['inmate_id']))->update($updateData);
                if (isset($inmateUpdateInfo) && !empty($inmateUpdateInfo)) {
                     if (Auth::user()->hasRole('Facility Staff')) {
                             $staff_log = new StaffLog();
                             $staff_log_create = $staff_log->staffLogInsert(Auth::user()->id, config('axxs.action.activate'), config('axxs.page.inmate'), $data['inmate_id'], 'Activated existing user');
                        }
                    return response()->json(array(
                                'Status' => Lang::get('common.success'),
                                'Code' => 200,
                                'Message' => Lang::get('inmate.inmate_update'),
                    ));
                } else {
                    return response()->json(array(
                                'Status' => Lang::get('common.success'),
                                'Code' => 400,
                                'Message' => Lang::get('inmate.inmate_update_error')
                    ));
                }
            }
            else {
                return response()->json(array(
                                'Status' => Lang::get('common.success'),
                                'Code' => 400,
                                'Message' => Lang::get('inmate.inmate_max_limit_error', ['totaluser' => $facility->total_inmate])
                    ));
            }
        }
    }

    /**
     * Create function for soft delete inmate details behalf on inmate id.
     *
     * @param object Request $request The inmate id keyed inmate_id.
     * 
     * @return NULL
     */
    public function deleteInmate(Request $request) {
        $data = $request->id;
        $objUser = new User();
        $deletUser = $objUser->deleteUser($data);
        if (isset($deletUser) && !empty($deletUser)) {
             if (Auth::user()->hasRole('Facility Staff')) {
                             $staff_log = new StaffLog();
                             $staff_log_create = $staff_log->staffLogInsert(Auth::user()->id, config('axxs.action.deactivate'), config('axxs.page.inmate'), $data, 'De-Activated existing user');
                        }
            return response()->json(array(
                        'Status' => Lang::get('common.success'),
                        'Code' => 200,
                        'Message' => Lang::get('inmate.inmate_delete'),
            ));
        } else {
            return response()->json(array(
                        'Status' => Lang::get('common.success'),
                        'Code' => 400,
                        'Message' => Lang::get('service.inmate_delete_error')
            ));
        }
    }

    /**
     * Create function for update password inmate details behalf on inmate id(this API we will used for android case).
     *
     * @param object Request $request The inmate id keyed inmate_id. 
     * 
     * @return NULL
     */
    public function changePasswordAPI(Request $request) {
        try {
            $data = $request->input();
            $data['inmate_id'] = (isset($data['inmate_id']) && !empty($data['inmate_id'])) ? $data['inmate_id'] : '';
            $rules = array(
                'inmate_id' => 'required|exists:users,id',
                'current_password' => 'required',
                'new_password' => 'required',
                'confirm_password' => 'required|same:new_password',
            );

            $validate = Validator::make($data, $rules);

            if ($validate->fails()) {
                return response()->json(array(
                            'Status' => Lang::get('common.failure'),
                            'Code' => 200,
                            'Message' => $validate->errors()->all()
                                //'Data' => $validate->errors()->all()
                ));
            } else {
                $current_password = User::find($data['inmate_id'])->password;

                if (Hash::check($data['current_password'], $current_password)) {

                    $obj_user = User::find($data['inmate_id']);
                    $obj_user->password = Hash::make($data['new_password']);
                    $obj_user->save();

                    return response()->json(array(
                                'Status' => Lang::get('common.success'),
                                'Code' => 200,
                                'Message' => Lang::get('inmate.password_changed')
                    ));
                } else {
                    return response()->json(array(
                                'Status' => Lang::get('common.failure'),
                                'Code' => 200,
                                'Message' => Lang::get('inmate.incorrect_current_password'),
                    ));
                }
            }
        } catch (Exception $ex) {
            return errorLog($ex);
        }
    }

    /**
     * Create function for update password inmate details behalf on inmate id.
     *
     * @param object Request $request The inmate id keyed inmate_id. 
     * 
     * @return NULL
     */
    public function changePassword(Request $request) {

        try {
            $data = $request->input();
            //$data['inmate_id'] = (isset($data['inmate_id']) && !empty($data['inmate_id'])) ? $data['inmate_id'] : '';

            $rules = array(
                'username' => 'exists:users,username',
                'deviceimei' => 'required',
                //'inmate_id' => 'required|exists:users,id',
                //'current_password' => 'required',
                'new_password' => 'required',
                'confirm_password' => 'required|same:new_password',
            );


            $validate = Validator::make($data, $rules);

            if ($validate->fails()) {
                return response()->json(array(
                            'Status' => Lang::get('common.failure'),
                            'Code' => 400,
                            'Message' => Lang::get('common.validation_error'),
                            'Data' => $validate->errors()->all()
                ));
            } else {
                $device = Device::where('imei', $data['deviceimei'])->first();
                if (isset($device)) {
                    $get_facility_userid = Facility::where('id',$device->facility_id)->value('facility_user_id');
    
                } else {
                    return response()->json(array('Code' => Response::HTTP_OK,
                                'Status' => Lang::get('common.success'),
                                'Message' => Lang::get('inmate.divice_not_found')
                    ));
                }

                $user = User::where('username', $data['username'])->where('admin_id',$get_facility_userid)->first(); 
                
                if(isset($data['current_password'])){
                    $current_password = User::find($user->id)->password;
                    if($data['new_password'] != date("mdY",strtotime($user->date_of_birth)))
                    {
                        if (Hash::check($data['current_password'], $current_password)) 
                        {

                            $obj_user = User::find($user->id);
                            $obj_user->password = Hash::make($data['new_password']);
                            $obj_user->save();

                    return response()->json(array(
                                'Status' => Lang::get('common.success'),
                                'Code' => 200,
                                'Message' => Lang::get('inmate.password_changed')
                    ));
                        } 
                        else 
                        {
                            return response()->json(array(
                                        'Status' => Lang::get('common.success'),
                                        'Code' => 400,
                                        'Message' => Lang::get('inmate.incorrect_current_password'),
                            ));
                        }
                    }
                    else 
                    {
                        return response()->json(array(
                                    'Status' => Lang::get('common.success'),
                                    'Code' => 400,
                                    'Message' => Lang::get('inmate.chnage_password_dob'),
                        ));
                    }
                }
                else
                {
                     if($data['new_password'] != date("mdY",strtotime($user->date_of_birth)))
                     {
                        $obj_user = User::find($user->id);
                        $obj_user->password = Hash::make($data['new_password']);
                        if($obj_user->first_login == 0)
                        {
                            $obj_user->first_login = 1;
                        }
                        $obj_user->save();

                        return response()->json(array(
                                'Status' => Lang::get('common.success'),
                                'Code' => 200,
                                'Message' => Lang::get('inmate.password_changed')
                        ));
                    }
                    else 
                    {
                        return response()->json(array(
                                    'Status' => Lang::get('common.success'),
                                    'Code' => 400,
                                    'Message' => Lang::get('inmate.chnage_password_dob'),
                        ));
                    }
                }
            }
        } catch (Exception $ex) {
            return errorLog($ex);
        }
    }

    /**
     * Create function for reset password of inmate
     *
     * @param object Request $request The inmate email keyed email key 1 is only used for androide json return case 
     * 
     * @return JSON in success message, code, password and status in response
     */
    public function resetInmatePassword(Request $request) {

        try {
            $data = $request->input();

            $rules = array(
                'username' => 'exists:users,username',
            );

            $validate = Validator::make($data, $rules);
            if (isset($data['key']) && $data['key'] == 1 && $validate->fails()) {
                return response()->json(array(
                            'Status' => Lang::get('common.failure'),
                            'Code' => 200,
                            'Message' => $validate->errors()->getMessages()['username'],
                ));
            } elseif ($validate->fails()) {
                return response()->json(array(
                            'Status' => Lang::get('common.failure'),
                            'Code' => 400,
                            'Message' => Lang::get('common.validation_error'),
                            'Data' => $validate->errors()->all()
                ));
            } else {

                $randomPassword = str_random(10);

                /* start code for Email Send for facility admin */
                if (isset($data['username'])) {
                    /**/
                    $userRoleInfo = User::where('username', $data['username'])->first(); 
                    if ($userRoleInfo->role_id == config('axxs.superadmin')) {
                        $objAdmin = new Admin();
                        $emailInfo = $objAdmin->getSuperAdminInfoByUserNameEmail($data['username']);
                        $title = Lang::get('superadmin.superadmin_reset_password_title');
                        $body = Lang::get('superadmin.superadmin_reset_password_body');
                    } elseif ($userRoleInfo->role_id == config('axxs.facilityadmin')) {
                        $objFacility = new Facility();
                        $emailInfo = $objFacility->getFacilityInfoByusername($data['username']);
                        $title = Lang::get('facility.facility_reset_password_title');
                        $body = Lang::get('facility.facility_reset_password_body');
                    } elseif ($userRoleInfo->role_id == config('axxs.familiyadmin')) {
                        $objFamily = new Family();
                        $emailInfo = $objFamily->getFamilyInfoByUsername($data['username']);

                        $title = Lang::get('family.family_reset_password_title');
                        $body = Lang::get('family.family_reset_password_body');
                    } elseif ($userRoleInfo->role_id == config('axxs.inmateadmin')) {
                        $objFacility = new Facility();
                        $emailInfo = $objFacility->getFacilityInfoByInmateEmail($data['username']);

                        $title = 'User reset the password for ' . $emailInfo->inmate_name . "( $emailInfo->inmate_id )";
                        $body= Lang::get('inmate.inmate_reset_report_password_title',[
                                    'inmate_name' => $emailInfo->inmate_name. " ". $emailInfo->inmate_lastname,
                                    'inmate_username' =>  $emailInfo->inmate_username]);
                    } elseif ($userRoleInfo->hasAnyRole(Roles::all()) && (!$userRoleInfo->hasAnyRole(['Super Admin', 'Facility Admin', 'Family Admin', 'Inmate']))) {
                        $objSpecialist = new User();
                        $emailInfo = $objSpecialist->getSpecialAdminInfoByUserNameEmail($data['username']);
                        $title = Lang::get('superadmin.specialadmin_reset_password_title');
                        $body = Lang::get('superadmin.specialadmin_reset_password_body');
                    }
                } else {
                    $objInmateReportHistory = new InmateReportHistory();
                    $inmateReportStatusInfo = $objInmateReportHistory->updateInmateReportStatus($data['report_id']);
                    if (isset($inmateReportStatusInfo)) {
                        $objUser = new User();
                        $emailInfo = $objUser->getFacilityInfoByInmateReportId($data['report_id']);
                        $userDataStatusUpdate = array(
                            'inmate_id' => $emailInfo->id,
                            'status' => config('axxs.status.unblock')
                        );
                        $_user = User::where('id' ,$emailInfo->id)->first();
                        $objUser->updateStatus($userDataStatusUpdate);
                        $title = Lang::get('inmate.inmate_reset_report_password_title',[
                                    'inmate_name' => $_user->first_name. " ". $_user->last_name,
                                    'inmate_username' =>  $_user->username]);
                        $body = Lang::get('inmate.inmate_reset_report_password_body');
                    }
                }
                
                /* start code for Email Send for facility admin */
                if (!empty($_user->date_of_birth)) {
                    $dobPassword = date("mdY", strtotime($_user->date_of_birth));
                    if (!empty($dobPassword)) {
                        $randomPassword = $dobPassword;
                    }
                }

                if ($emailInfo) {
                    $emailValue = array(
                        'to' => $emailInfo->email,
                        'title' => $title,
                        'body' => $body . ' ' . $randomPassword
                    );
                }

                $request->merge($emailValue);
                $objSendMail = new SendMailController();
                $sendEmail = $objSendMail->sendMail($request);

                /* start code for Email Send for facility admin */
                //$obj_user = User::where('id', $emailInfo->id)->first();
                $obj_user = User::where('id', $emailInfo->id)->first();
                $obj_user->password = bcrypt($randomPassword);
                //$obj_user->password = Hash::make($randomPassword);
                $obj_user->save();
                return response()->json(array(
                            'Status' => Lang::get('common.success'),
                            'Code' => 200,
                            'Message' => Lang::get('inmate.password_changed'),
                            'password' => $randomPassword
                ));
            }
        } catch (Exception $ex) {
            return errorLog($ex);
        }
    }

    /**
     * Create function for inmate block
     *
     * @param object Request $request The inmate id keyed inmate_id
     * 
     * @return JSON in success message, code and status in response
     */
    public function registerInmateBlock(Request $request) {
        $data = $request->input();

        $rules = array(
            'inmate_id' => 'required|exists:users,id',
        );

        $validate = Validator::make($data, $rules);

        if ($validate->fails()) {
            return response()->json(array(
                        'Status' => Lang::get('common.failure'),
                        'Code' => 400,
                        'Message' => Lang::get('common.validation_error'),
                        'Data' => $validate->errors()->all()
            ));
        } else {

            $obj_user = User::updateStatus($data['inmate_id']);
            return response()->json(array(
                        'Status' => Lang::get('common.success'),
                        'Code' => 200,
                        'Message' => Lang::get('inmate.inmate_block'),
            ));
        }
    }

    /**
     * Create function for inmate block
     *
     * @param object Request $request The inmate id keyed inmate_id
     * 
     * @return JSON in success message, code and status in response
     */
    public function registerInmateReport(Request $request) {
        $data = $request->input();

        $rules = array(
            'inmate_id' => 'required|exists:users,id',
        );

        $validate = Validator::make($data, $rules);

        if ($validate->fails()) {
            return response()->json(array(
                        'Status' => Lang::get('common.failure'),
                        'Code' => 400,
                        'Message' => Lang::get('common.validation_error'),
                        'Data' => $validate->errors()->all()
            ));
        } else {

            $report_insert = InmateReportHistory::create([
                        'inmate_id' => $data['inmate_id'],
                        'report_time' => date('m-d-Y H:i:s'),
                        'status' => config('axxs.status.block'),
                        'is_deleted' => config('axxs.active'),
            ]);
            if (isset($report_insert)) {
               // $data['status'] = config('axxs.status.block');
               // $userStatus = new User();
               // $userStatus->updateStatus($data);
                return response()->json(array(
                            'Status' => Lang::get('common.success'),
                            'Code' => 200,
                            'Message' => Lang::get('inmate.inmate_report_success'),
                ));
            } else {
                return response()->json(array(
                            'Status' => Lang::get('common.success'),
                            'Code' => 200,
                            'Message' => Lang::get('inmate.inmate_report_error_success'),
                ));
            }
        }
    }

    /**
     * Create function for inmate balance information.
     *
     * @param object Request $request The inmate id keyed inmate_id, 
     * 
     * @return JSON response and inmate balance
     */
    public function inmateBalance(Request $request) {
        try {
            $data = $request->input();

            $rules = array(
                'inmate_id' => 'required|exists:users,id',
            );

            $validate = Validator::make($data, $rules);

            if ($validate->fails()) {
                return response()->json(array(
                            'Status' => Lang::get('common.failure'),
                            'Code' => 400,
                            'Message' => Lang::get('common.validation_error'),
                            'Data' => $validate->errors()->all()
                ));
            } else {

                $obj_user = User::find($data['inmate_id']);

                $obj_facility = Facility::where('facility_user_id' , $obj_user->admin_id)->first();

                if(!empty($obj_facility->tablet_charge > 0)){
                     $tablet_charge = $obj_facility->tablet_charge;
                }else{
                        $configTabletCharge = InmateConfiguration::where('id', config('axxs.tablet_charges'))
                        ->where('is_deleted', config('axxs.active'))->first();
                        $tablet_charge = (float)$configTabletCharge->value;
                }
                return response()->json(array(
                            'Status' => Lang::get('common.success'),
                            'Code' => 200,
                            'Message' => Lang::get('inmate.inmate_balance'),
                            'Data' => array('balance' => $obj_user->balance,'tablet_charge'=>  $tablet_charge)
                ));
            }
        } catch (Exception $ex) {
            return errorLog($ex);
        }
    }

    /**
     * Log out Inmate.
     *
     * @param object Request $request The inmate id keyed inmate_id, 
     * 
     * @return JSON response success message and code
     */
     public function logoutInmate(Request $request) {
        
        try {
            $data = $request->input();
            $rules = array(
                'inmate_id' => 'required|exists:users,id',
                'end_datetime' => 'required',
                'api_token' => 'required',
            );
            $validate = Validator::make($data, $rules);
            if ($validate->fails()) {
                return response()->json(array(
                            'Status' => Lang::get('common.failure'),
                            'Code' => 400,
                            'Message' => Lang::get('common.validation_error'),
                            'Data' => $validate->errors()->all()
                ));
            } else {
                $check_lastlogout = InmateLoggedHistory::where('api_token', $data['api_token'])->first();
                
                if (!empty($check_lastlogout) && !empty($check_lastlogout->end_date_time)) {
                    return response()->json(array(
                                'Status' => Lang::get('common.success'),
                                'Code' => 400,
                                'Message' => Lang::get('inmate.inmate_logout_unsuccess'),
                    ));
                } else {
                    $obj_user = new User();
                    $obj_inmate_logged = new InmateLoggedHistory();
                    $obj_free_minutes = new FreeMinute();
                    $chargeStatus = 0;
                    /* here we have update inmate logout time */
                    //$totalLefTime1 = $this->calculateFreeLeftTime($data['inmate_id']);
                    //above code commnented to turn of free minutes /12 hour
                    $free_minutes = FreeMinute::where('inmate_id',$data['inmate_id'])->first();
                    $totalLefTime1 = $free_minutes->left_minutes;

                    $responseLogged = $obj_inmate_logged->updateLogutTime($data);
                    $inmateLoggedDetails = $obj_inmate_logged->calculateLoginTime($data['inmate_id']);
                    $totalLoginTime = round($totalLefTime1*60) - $inmateLoggedDetails->total;
                    
                    
                    if ($totalLefTime1 == 0 || $totalLoginTime < 0 || $totalLoginTime == 0) {
                        $totalLefTime = $inmateLoggedDetails->total - round($totalLefTime1*60);
                        

                        $deductCharge = $this->calculateLoggedCharge($totalLefTime, $inmateLoggedDetails->total, $data['inmate_id']);
           
                 
                        $chargeStatus = 1;
                        $totalLoginTime = 0;

                        $responseLogged = $obj_free_minutes->updateCalculatedLeftTime($data, floor($totalLoginTime/60));
                        
           
                    
                        return response()->json(array(
                                    'Status' => Lang::get('common.success'),
                                    'Code' => 200,
                                    'Message' => Lang::get('inmate.inmate_logout_success'),
                                    'Deduction_charge' => $deductCharge,
                        ));
                    } else {
                        $deductCharge = 0;

                        $responseLogged = $obj_free_minutes->updateCalculatedLeftTime($data, floor($totalLoginTime/60));
                 
                        
                        return response()->json(array(
                                    'Status' => Lang::get('common.success'),
                                    'Code' => 200,
                                    'Message' => Lang::get('inmate.inmate_logout_success'),
                                    'Total_login_duration' => ceil($inmateLoggedDetails->total/60),
                                    'Total_left_time' => floor($totalLoginTime/60),
                        ));
                        
                    }
                    return response()->json(array(
                                'Status' => Lang::get('common.success'),
                                'Code' => 400,
                                'Message' => Lang::get('inmate.inmate_logout_unsuccess'),
                    ));
                }
            }
        } catch (Exception $ex) {
            return errorLog($ex);
        }
    }

    /**
     * Function to calculation time logged time
     *
     * @param object $inmate_id
     * 
     * @return array
     */
    function calculateFreeLeftTime($inmate_id) {
        /* right now we have assumed to diducation charge hourly bases so we have put 60 minute */
        $objInmateLogged = new InmateLoggedHistory();
        $totalFreeLeftTime = $objInmateLogged->checkLoggedTimeCurDate($inmate_id);
        
                 // log something to storage/logs/debug.log
                 Log::useDailyFiles(storage_path().'/logs/charges.log');
                 Log::info(['logout_totalFreeLeftTime'=>$totalFreeLeftTime]);
        if (!isset($totalFreeLeftTime)) {
            //getFacilityfreeMinutes
            $get_facility_userid = User::select('admin_id')->where('id',$inmate_id)->first();
            $admin_id = $get_facility_userid->admin_id;
            $getFreeminutes = Facility::select('free_minutes')->where('facility_user_id',$admin_id)->first();
            
            if (isset($getFreeminutes->free_minutes) && $getFreeminutes->free_minutes != null) {
                $tabletFreeTime = $getFreeminutes->free_minutes;


            } else {
                $tabletFreeTime = InmateConfiguration::where('id', config('axxs.free_hours'))
                            ->where('is_deleted', config('axxs.active'))->first();
                $tabletFreeTime = $tabletFreeTime->value;
            }
            

            return $tabletFreeTime;
        }
        return $totalFreeLeftTime->calculated_left_time;
    }

    /**
     * Function to calculation time logged time
     *
     * @param object $divisor $dividend
     * 
     * @return array
     */
    function calculateLoggedCharge($divisor, $tabletCharge, $inmate_id) {

        $freeTabletCharge = InmateConfiguration::where('id', config('axxs.tablet_charges'))
                        ->where('is_deleted', config('axxs.active'))->first();

         $facility = new Facility;
         $facility_charge = $facility->getFacilityTableChargeByInmateID($inmate_id);

            if(isset($facility_charge->tablet_charge) && $facility_charge->tablet_charge >= 0){
                 $freeTabletCharge->value = $facility_charge->tablet_charge;
            }              
                 
                 
        $charge = $divisor * ($freeTabletCharge->value/60);
       
        $obj_user = new User();
        $inmateBalnce = $obj_user->updateBalance($inmate_id, $charge);
        $userinmate = User::where('id', $inmate_id)->first();
        $balanceTabletCharge = InmateConfiguration::where('id', config('axxs.balance_left'))
                        ->where('is_deleted', config('axxs.active'))->first();
        if ($userinmate->balance < $balanceTabletCharge->value) {
            $name = $userinmate->first_name . ' ' . $userinmate->last_name;
            $this->sendFamilyEmail($name, $inmate_id, $userinmate->balance);
        }
        
        return round($charge,3);
    }

    /**
     * Function to send sms UI
     *
     * @param object $inamte_id $service_id The inmate id keyed inmate_id and service_id.
     * 
     * @return NULL
     */
    public function sendSMSUI($inmate_id, $service_id) {
        $smsCharge = InmateConfiguration::where('id', config('axxs.sms_charges'))
                        ->select('value')->first();
        $inmate_details = User::where('id', $inmate_id)->first();
        if ($inmate_details && $inmate_details->role_id == '4') {
            $limitstart = new InmateSetMaxContact;
            $limitinfo = $limitstart->FetchInmateNumberLimit($inmate_id)['max_phone'];
            $limitleft = $limitstart->LimitLeftForNumber($inmate_id);

            $contactnumber = InmateContacts::where('inmate_id', $inmate_id)
                    ->where('type', 'phone')
                    ->where('is_approved', 1)
                    ->where('is_deleted', 0)
                    ->where('varified', '1')
                    ->select('name','email_phone')
                    ->get();

             $PreApprovedContacts = PreApprovedContacts::where('facility_id', $inmate_details->admin_id)
                   ->where('is_deleted', 0)
                   ->where('status', 0)
                    ->get();
                    
            $facility = new Facility;
            $facility_charge = $facility->getFacilityEmailSMSChargeByInmateID($inmate_id);
            //SMS charge per facility
            if (isset($facility_charge->sms_charges) && $facility_charge->sms_charges >= 0) {
                $smsCharge->value = $facility_charge->sms_charges;
            }

            return View('sendsms', ['inmate_id' => $inmate_id, 'service_id' => $service_id, 'smsCharge' => $smsCharge->value, 'contactnumber' => $contactnumber, 'limitinfo' => $limitinfo, 'limitleft' => $limitleft, 'inmate_details' => $inmate_details,'PreApprovedContacts'=>$PreApprovedContacts]);
        } else {
            return View('errors.404');
        }
    }

    /**
     * Function to send the twillio SMS by inmate.
     *
     * @param object Request $request The inmate id keyed inmate_id.
     * 
     * @return NULL
     */
    public function sendSms(Request $request) {
        $data = $request->input();

        $rules = array(
            'inmate_id' => 'required',
            'number' => 'required',
            'body' => 'required',
            'service_id' => 'required',
        );

        $messages = [

            'number.required' => 'Contact number is required.',
            'body.required' => 'Please write some text to send.',
        ];


        $validate = Validator::make($data, $rules, $messages);

        if ($validate->fails()) {
            return response()->json(array(
                        'Status' => Lang::get('common.failure'),
                        'Code' => 400,
                        'Data' => Lang::get('common.validation_error'),
                        'Message' => $validate->errors()->all()
            ));
        } else {

            $facility_details = new Facility;
            $twilio_number = $facility_details->getFacilityInfoByInmateID($data['inmate_id']);

            if ($twilio_number->twilio_number) {
                $AccountSid = env('SMS_ACCOUNT_SID');
                $AuthToken = env('SMS_AUTH_TOKEN');
                $client = new Client($AccountSid, $AuthToken);
                $from = $twilio_number->twilio_number;
                $name = !empty($data['name']) ? $data['name'] : '';
                $number = $data['number'];
                $body = $data['body'];
                $people = array(
                    $number => $name,
                );

                try {
                    $objBlackListedWord = new BlackListedWord(); 
                    $blackListedWords = $objBlackListedWord->getBlacklistedWord();

                    foreach ($people as $number => $name) {
                        $sms = $client->account->messages->create(
                                $number, array(
                            'from' => $from,
                            'body' => $body
                                )
                        );
                        if (isset($sms) && !empty($sms)) {
                             $blacklisted = 0;
                             if(!empty($blackListedWords)){
                                foreach ($blackListedWords as $blackListedWord) {
                                        $blacklisted_word = $blackListedWord->blacklisted_words;
                                    if (stripos($body, $blacklisted_word) !== false) {
                                        $blacklisted = 1;
                                    } 
                                }
                            }
                      
                            $inmate_sms = InmateSMS::create([
                                        'inmate_id' => $data['inmate_id'],
                                        'contact_number' => $data['number'],
                                        'message' => $data['body'],
                                        'bound' => 'out',
                                        'blacklisted' => $blacklisted
                            ]);


                            $data['configuration_id'] = config('axxs.sms_charges');
                            $objInmateChargesHistory = new InmateChargesHistory();
                            $objInmateChargesHistory->chargesService($data);
                            return response()->json(array(
                                        'Status' => Lang::get('common.success'),
                                        'Code' => 200,
                                        'Message' => Lang::get('sms.sms_send'),
                            ));
                        }
                    }
                } catch (\Exception $e) {
                    errorLog($e);
                    if (strpos($e->getMessage(), '[HTTP 400] Unable to create record') !== false) {
                        return response()->json(array(
                                    'Status' => Lang::get('common.success'),
                                    'Code' => 400,
                                    'Message' => Lang::get('sms.sms_not_send_number_issue', ['number' => $number]),
                        ));
                    }
                    return response()->json(array(
                                'Status' => Lang::get('common.success'),
                                'Code' => 400,
                                'Message' => Lang::get('sms.sms_inmate_create_password'),
                    ));
                }
            } else {
                return response()->json(array(
                            'Status' => Lang::get('common.success'),
                            'Code' => 400,
                            'Message' => Lang::get('sms.sms_inmate_facility_issue'),
                ));
            }
        }
    }

    /**
     * Fuction for Twilio incomming SMS test messages.
     *
     * @return NULL
     */
    public function smsReply() {
        $inmateall = User::where([
                    ['role_id', '=', '4'],
                    ['is_deleted', '=', '0'],
                ])->get();

        $AccountSid = env('SMS_ACCOUNT_SID');
        $AuthToken = env('SMS_AUTH_TOKEN');
        $twilio = new Client($AccountSid, $AuthToken);

        $messages = $twilio->messages
                ->read();

        foreach ($messages as $record) {
            if ($record->from == '+19388888432') {

                echo "<br>";
                print($record->body);

                echo "<br>";
                print_r($record->dateCreated);
                echo "<br>";
            }
        }
    }

    /**
     * Webhook for Twilio incomming SMS.
     *
     * @param object Request $request The contact person number.
     * 
     * @return NULL
     */
    public function getAllSMS(Request $request) {
        $from = $request->From;
        $to = $request->To;
        $facility = Facility::where('twilio_number', $to)->first();
        if ($facility) {
            $inmate = InmateContacts::where('email_phone', $from)
                            ->where('facility_id', $facility->facility_user_id)->first();
            if ($inmate) {
                $inmate_id = $inmate->inmate_id;
            } else {
                $inmate_id = "";
            }
        } else {
            $inmate_id = "";
        }
        
        if ($facility->in_sms_charge != null) {
            $charge = $facility->in_sms_charge;
        }else{
            $charge = InmateConfiguration::where('key','in_sms_charge')->pluck('value')->first();
        }
        if ($charge != null) {
            $this->deductSMScharge($charge,$inmate_id);
        }
        
        InmateSMS::create([
            'inmate_id' => $inmate_id,
            'contact_number' => $from,
            'message' => $request->Body,
            'bound' => 'in'
        ]);
    }

    /**
     * Function for sending emails
     *
     * @param object Request $name  name of the inmate.
     *                       $inmate_id is the inmate_id
     *                       $balance left balance less then 10
     * 
     * @return NULL
     */
    public function sendFamilyEmail($name, $inmate_id, $balance) {
        $user = Family::where('inmate_id', $inmate_id)
                ->where('is_deleted', 0)
                ->where('email', '<>', '')
                ->get();

        if (count($user) > 0) {
            $content = [
                'title' => 'Recharge the account',
                'body' => 'The account balance of ' . $name . ' is very low.Only ' . $balance . ' $ is left in the account. Please Recharge for continuing the services else services will be halted.',
            ];
            foreach ($user as $family) {
                $receiverAddress = $family->email;
                $var = Mail::to($receiverAddress)->send(new OrderShipped($content));
            }
        }
    }

    /**
     * Function for sending emails
     *
     * @param object Request $name  name of the inmate.
     *                       $inmate_id is the inmate_id
     *                       $balance left balance less then 10
     * 
     * @return NULL
     */
    public function assignDefaultServices($facility_id, $inmate_id) {

            $objDefaultService = new DefaultServicePermission(); 
            $defaultServices = $objDefaultService->getInmateDefaultServiceInfo($facility_id);

           if(count($defaultServices) > 0)
           {
                foreach($defaultServices as $defaultService) {
                    ServicePermission::create(['inmate_id' => $inmate_id,
                                         'service_id' => $defaultService->service_id,
                                         'is_default' => 1
                    ]);
                }
            }
    }

    /**
         * Create function for get security question.
         *
         * 
         * @return NULL
         */
    public function getSecurityQuestion(Request $request) {
        
       $data = $request->input();
       if(isset($data['deviceimei'])){
           $device = Device::where('imei', $data['deviceimei'])->first();
                if (isset($device)) {
                   $get_facility_userid = Facility::where('id',$device->facility_id)->value('facility_user_id');
                    
                } else {
                    return response()->json(array('Code' => Response::HTTP_OK,
                                'Status' => Lang::get('common.success'),
                                'Message' => Lang::get('inmate.divice_not_found')
                    ));
                }
       } else{
           return response()->json(array(
                        'Status' =>Lang::get('common.success'), 
                        'Code' => 400,
                        'Message' => 'device imei required',
                ));
       }
       $user = User::where('username', $data['username'])->where('admin_id',$get_facility_userid)->first();

       if($user){
        if(isset($data['forgot_password'])){
            if($user->first_login == 0){
                 return response()->json(array(
                        'Status' =>Lang::get('common.success'), 
                        'Code' => 200,
                        'Message' => Lang::get('inmate.login_error_first'),
                ));
            }
        }

       $securtyQuestions = SecurityQuestion::all();
       $userQuestions = UserAnswer::where('user_id', $user->id)->get()->toArray();

       foreach($securtyQuestions as $securtyQuestion)
       {
            $securtyQuestion->selected = false;
            foreach($userQuestions as $userQuestion)
            {
                if($securtyQuestion->id == $userQuestion['question_id'])
                {
                    $securtyQuestion->selected = true;
                    $securtyQuestion->answer = $userQuestion['answer'];
                }
            }
       }

       if ($securtyQuestions) {
            return response()->json(array(
                        'Status' =>Lang::get('common.success'), 
                        'Code' => 200,
                        'Message' => Lang::get('inmate.question_details'),
                        'Data' => $securtyQuestions
            ));
        }
        
    }else{
        return response()->json(array(
                    'Status' => Lang::get('common.success'),
                    'Code' => 400,
                    'Message' =>Lang::get('inmate.inmate_not_found'), 
        ));
    }
 
    }

    /**
         * Create function for save security answer 
         *
         * @param object Request $request The user id question id. 
         * 
         * @return NULL
         */
    public function saveAnswer(Request $request) {
            $user_obj = $request->user();
            
         try{ 
                $data = $request->json()->all();
                $user = User::where('id', $user_obj->id)->first(); 
            if($user){
                       UserAnswer::where('user_id', $user->id)->delete();
                foreach ($data['Questions'] as $key => $value) {

                        $userAnswer  = UserAnswer::create(
                        ['user_id' =>$user->id, 'question_id' => $value['question_id'],
                        'answer' =>  $value['answer']]
                    ); 
                }

                return response()->json(array(
                        'Status' => Lang::get('common.success'),
                        'Code' => 200,
                        'Message' => Lang::get('inmate.answer'),
                    ));                      


           }else{
            return response()->json(array(
                    'Status' => Lang::get('common.success'),
                    'Code' => 400,
                    'Message' =>Lang::get('inmate.inmate_not_found'), 
        ));


           }
            }catch (Exception $ex) {
                return errorLog($ex);
            }

    }



    /**
         * Create function for check security answer 
         *
         * @param object Request $request The user id question id. 
         * 
         * @return NULL
         */
    public function checkAnswer(Request $request) {

         $data = $request->json()->all();
         if(isset($data['deviceimei'])){
           $device = Device::where('imei', $data['deviceimei'])->first();
                if (isset($device)) {
                   $get_facility_userid = Facility::where('id',$device->facility_id)->value('facility_user_id');
                    
                } else {
                    return response()->json(array('Code' => Response::HTTP_OK,
                                'Status' => Lang::get('common.success'),
                                'Message' => Lang::get('inmate.divice_not_found')
                    ));
                }
       } else{
           return response()->json(array(
                        'Status' =>Lang::get('common.success'), 
                        'Code' => 400,
                        'Message' => 'device imei required',
                ));
       }
         $user = User::where('username', $data['username'])->where('admin_id',$get_facility_userid)->first();

    if($user){
            $checkAnswer = [];        
            foreach ($data['Questions'] as $key => $value) {
               
                    $userans = UserAnswer::where('user_id',$user->id)
                            ->where('question_id', $value['question_id'])
                            ->where('answer',$value['answer'])->first();

                    $checkAnswer[$value['question_id']] = false;
                    if($userans)
                    {
                        $checkAnswer[$value['question_id']] = true;
                    }
                }

                $messages = Lang::get('inmate.checkanswer');
                $code = 200;
                foreach ($checkAnswer as $key => $value) {
                   if(!$value)
                   {
                        $messages = Lang::get('inmate.checkanswer_error');
                          $code = 400;
                   }
                }
             
               if ($checkAnswer) {
                    return response()->json(array(
                    'Status' => Lang::get('common.success'),
                    'Code' => $code,
                     'Message' => $messages,
                    ));
                } 
        }else{
        return response()->json(array(
                    'Status' => Lang::get('common.success'),
                    'Code' => 400,
                    'Message' =>Lang::get('inmate.inmate_not_found'), 
        ));


    }
 }
 
 
     /**
     * Create function for reset password of inmate with DOB and make it first login
     *
     * @param object Request $request  
     * 
     * @return JSON in success message, code, password and status in response
     */
    public function resetPassword(Request $request)
    {
        try {
            $data = $request->input();

            $rules = array(
                'username' => 'exists:users,username',
                'dob' => 'required',
                'deviceimei' => 'required',
            );

            $validate = Validator::make($data, $rules);
            if (isset($data['key']) && $data['key'] == 1 && $validate->fails()) {
                return response()->json(array(
                            'Status' => Lang::get('common.failure'),
                            'Code' => 200,
                            'Message' => $validate->errors()->getMessages()['username'],
                ));
            } elseif ($validate->fails()) {
                return response()->json(array(
                            'Status' => Lang::get('common.failure'),
                            'Code' => 400,
                            'Message' => Lang::get('common.validation_error'),
                            'Data' => $validate->errors()->all()
                ));
            } else {
                /* start code for Email Send for facility admin */
                if (isset($data['username'])) {
                    /**/
                   $device = Device::where('imei', $data['deviceimei'])->first();
                            if (isset($device)) {
                                $get_facility_userid = Facility::where('id',$device->facility_id)->value('facility_user_id');
                                } else {
                                    return response()->json(array('Code' => Response::HTTP_OK,
                                                'Status' => Lang::get('common.success'),
                                                'Message' => Lang::get('inmate.divice_not_found')
                                   ));
                            }
                    $user = User::where('username', $data['username'])->where('admin_id',$get_facility_userid)->first();
                    $dobPassword = date("mdY", strtotime($user->date_of_birth));


                    if ($data['dob'] == $dobPassword) {
                        /* start code for Email Send for facility admin */
                        $obj_user = User::where('id', $user->id)->first();
                        $obj_user->password = bcrypt($dobPassword);
                        $obj_user->first_login = 0;
                        $obj_user->save();
                        return response()->json(array(
                                    'Status' => Lang::get('common.success'),
                                    'Code' => 200,
                                    'Message' => Lang::get('inmate.password_changed'),
                                    'password' => $dobPassword
                        ));
                    }
                }
                return response()->json(array(
                            'Status' => Lang::get('common.failure'),
                            'Code' => 400,
                            'Message' => Lang::get('common.invalid_dob')
                ));
            }
        } catch (Exception $ex) {
            return errorLog($ex);
        }
    }

    /**
     * Function to change the view to 1 
     * 
     * @param  report dispute id
     * 
     * @return json
    */
     public function changeViewstatus(Request $request){
        try {
            $inmateReportHistory = new InmateReportHistory;
                $inmateReportHistory->where('inmate_id',$request['inmate_id'])->where('view',0)->update(['view' => 1]);
                return response()->json(array(
                                    'Status' => Lang::get('common.success'),
                                    'Code' => 200,
                                    'Message' => 'Report Viewed'
                        ));
        } catch (\Exception $e) {
            return $e->getMessage();        }
        
    }

    /**
     *Check whether the device is off or not
     *@param $facility_id
     *@return true or false
    */
    public function deviceCheck($facility_id){

        $facility_device = Facility::where('id',$facility_id)->value('device_status');
        $adminDevice = InmateConfiguration::where('key','device_off')->value('content');
        if ($facility_device == 1 && $adminDevice == 1) {
            return true;
        } else {
            return false;
        }
    }

    public function getIncomingsms(Request $request ){

        //$data = $request->all();
        $to = $_POST['envelope']['to'];
        $from = $_POST['envelope']['from'];
        $subject = $_POST['headers']['Subject'];
        $plain = $_POST['plain'];
        $html = $_POST['html'];
        $reply = $_POST['reply_plain'];
        $attachment = isset($_POST['attachments']) ? $_POST['attachments'] : null;

        //checking if user exists
        $user_object = User::select('id','admin_id')->where('email',$to)->first();

        if ($user_object == null) {
            return false;
        }
        $inmate_id = $user_object['id'];
        $admin_id = $user_object['admin_id'];
        $is_fm = 0;
        //checking if mail from already verified email address
        $freeMails = \App\FreeEmail::pluck('email')->toArray();
        if (in_array($from, $freeMails)) {
            $is_fm = 1;
            $status = 1;
        }
        if ($attachment == null )
            {
             $status = 1;
            }
            else
            {
            $status = 0;
            }
        //checking if sender exists in inmate contact
        $inmateContactlist = InmateContacts::where(['inmate_id' => $inmate_id, 'type' => 'email' ,'varified' => 1])->pluck('email_phone')->toArray();
        if (!in_array($from, $inmateContactlist) && !in_array($from, $freeMails) )
            {
                $mac_id = "1234";
            Mail::send('mail.revertmail', ['mac_id' => $mac_id], function ($m) use ($mac_id,$from) {
            $m->from('noreply@theaxxstablet.com', 'TheAxxstablet');

            $m->to($from)->subject('Email Rejected!');
            });
            

            } else{
                  $getName = InmateContacts::where(['inmate_id' => $inmate_id, 'type' => 'email' ,'varified' => 1,'email_phone' =>$from ])->first();
                try {
                  //check if mail contains blacklisted word
                  $blacklisted_word = \App\BlackListedWord::select('blacklisted_words')->where('addedbyuser_id',null)->OrWhere('addedbyuser_id',$admin_id)->pluck('blacklisted_words')->toArray();
                  $blacklisted = 0;
                  foreach ($blacklisted_word as $key => $value) {

                      if (stripos($plain,$value) !== false) {
                                $blacklisted = 1;
                                $status = 0;
                                 break;            
                        } 
                  }  

                  if($status = 1 && $is_fm == 0) {
                      $this->deductIncomingmailCharge($admin_id,$inmate_id);
                    }

                  $email = new IncomingMail;
                  $email->to = $to;
                  $email->to_inmateid = $inmate_id;
                  $email->subject = $subject;
                  $email->from = $from;
                  $email->plain = $plain;
                  $email->html = $html;
                  $email->reply = $reply;
                  $email->name = isset($getName) ? $getName['name'] : null;
                  $email->status = $status;
                  $email->is_blacklisted = $blacklisted;
                  $email->save();
                  
                  if (isset($attachment)) {
                     $allowed_file = array(
                        'image/jpeg',
                        'image/png',
                        'application/pdf'
                        );
                      foreach ($attachment as $key => $attach) {

                         if (in_array($attach['content_type'], $allowed_file)) {
                                $email_attach = array(
                                    'link' => $attach['url'],
                                    'email_id' => $email->id,
                                    'type' => $attach['content_type']
                                     );
                                \App\EmailAttachment::create($email_attach);
                            }   
                          

                      }
                  }


                 /*Log::info(['attachment'=>$attachment]);*/
                            /*Log::info(['incoming_mail'=>$data]); */
                } catch (\Exception $e) {
                    Log::info(['error'=>$e->getMessage()]); 
                }
            }
            
          

        
    }

    /**
     *optout from device- send email
     *@param $mac_address
     *@return null
    */
    public function optOutDevice($mac_id){
        try {
            Mail::send('mail.optoutdevice', ['mac_id' => $mac_id], function ($m) use ($mac_id) {
            $m->from('noreply@theaxxstablet.com', 'TheAxxstablet');

            $m->to('raghavenderm@chetu.com')->subject('Device Opt Out');
            });
            return response()->json(array(
                                    'Status' => Lang::get('common.success'),
                                    'Code' => 200,
                                    'Message' => 'Email has been sent to admin for Mac Address '.$mac_id
                        ));
            
        } catch (Exception $e) {
                return json_encode($e->exception);
        }
    }

    /**
     *create email address function
     *@param $facility_id , $inmate_id
     *@return null
    */
     public function createEmailadd($inmate_id , $facility_id){
            $facility_id = str_replace(' ', '', $facility_id);
            $address = $inmate_id.'.'.$facility_id.'@theaxxstablet.com';

            $headers = [
                'Accept: application/json',
                'Content-Type: application/json',
                'X-API-Key: ba2c87e4-3fd4-d714-42ba-f1635b7eaf40'
            ];
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, "https://18.214.219.150:8443/api/v2/cli/mail/call");
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");  
            curl_setopt($ch, CURLOPT_POSTFIELDS, '{"params":["--create","'.$address.'"]}');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1); 
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_exec($ch);

            if (curl_error($ch)) {
                $error_msg = curl_error($ch);
                curl_close($ch);
                
            }else{
                curl_close($ch);
                return $address;
            }
            
    }

    public function calculateLastlogin($timestamp){
        $date1 = $timestamp;
        $date2 = strtotime(gmdate("Y-m-d H:i:s"));
        $diff = abs($date2 - $date1);
        $last = ''; 
        $years = floor($diff / (365*60*60*24));  
        $months = floor(($diff - $years * 365*60*60*24) 
                                       / (30*60*60*24));  
        $days = floor(($diff - $years * 365*60*60*24 -  
                     $months*30*60*60*24)/ (60*60*24));  
        $hours = floor(($diff - $years * 365*60*60*24  
               - $months*30*60*60*24 - $days*60*60*24) 
                                           / (60*60));  
        $minutes = floor(($diff - $years * 365*60*60*24  
                 - $months*30*60*60*24 - $days*60*60*24  
                                  - $hours*60*60)/ 60);  
          
          
        // To get the minutes, subtract it with years, 
        // months, seconds, hours and minutes  
        $seconds = floor(($diff - $years * 365*60*60*24  
                 - $months*30*60*60*24 - $days*60*60*24 
                        - $hours*60*60 - $minutes*60));  
        if($years > 0){
            $last .= $years.' '.'years';  
        }
        if($months > 0){
            $last .= ' '.$months.' '.'months';
        }
        if($days > 0){
            $last .= ' '.$days.' '.'days';
        }
        if($hours > 0){
            $last .= ' '.$hours.' '.'hours';
        }
        if($minutes > 0){
            $last .= ' '.$minutes.' '.'minutes';
        }
        if($seconds > 0){
            $last .= ' '.$seconds.' '.'seconds';
        }
        return $last.' '.'ago';
    }

    /**
     *create email address manually function
     *@param $admin_id , $inmate_id , $user_id
     *@return json
    */
     public function generateEmail(Request $request){
        $req = $request['data'];
        //get facilityid
        $facility_id = \App\Facility::where('facility_user_id',$req['admin_id'])->value('facility_id');
        $data = [];
        try {
            $email_address = $this->createEmailadd($req['inmate_id'],$facility_id);
            User::where('id',$req['user_id'])->update(['email' => $email_address]);
            $data['status'] = 'success';
            $data['msg'] = 'email has been created';
        } catch (Illuminate\Database\QueryException $e){
            $data['status'] = 'error';
            $data['msg'] = $e->getMessage();
        }
         catch (Exception $e) {
            $data['status'] = 'error';
            $data['msg'] = $e->getMessage();
        }

        return response()->json($data);
        
    }

    public function appdownloadurl(){
        return response()->json(['url' => 'https://theaxxstablet.com/index.php/api/downloadapklink3']);
    }

    /**
     *Function for deducting incoming email charge
     *@param $admin_id , $inmate_id
     *@return null
    */
    public function deductIncomingmailCharge($admin_id,$inmate_id){
            $inc_charge_fac = Facility::where('facility_user_id',$admin_id)->pluck('incoming_email_charge')->first();
            if (isset($inc_charge_fac) && $inc_charge_fac!= null) {
                $user  = User::find($inmate_id);
                $user_balance = (($user->balance)-$inc_charge_fac);
                $user->update(['balance'=>$user_balance]);
            }
    }

    /**
     *Function for deducting incoming sms_charge
     *@param $charge , $inmate_id
     *@return null
    */
    public function deductSMScharge($charge,$inmate_id){
            if (isset($charge) && $charge!= null) {
                $user  = User::find($inmate_id);
                $user_balance = (($user->balance)-$charge);
                $user->update(['balance'=>$user_balance]);
            }
    }

    public function freeMinutesleft($inmate_id,$fm){
        
        $fminutes = FreeMinute::where('inmate_id',$inmate_id)->first();
        
        if ($fminutes == null) {
            $fminutes = FreeMinute::create(['inmate_id'=>$inmate_id,'left_minutes' => $fm ]);          
        }
        return $fminutes->left_minutes;
    }

    public function updateLogcheck(Request $request){
        //Find a user with a given id and make him inactive
        $user = User::findOrFail($request->user_id);
        $user->is_log = $request->is_log;
        $user->save();
        $message = \Lang::get('roles.user_activate');
        if ($request->is_log == 1) {
            $msg = 'Logs enabled';
        }else{
            $msg = 'Logs disabled';
        }
        return response()->json(array(
                    'Code' => 200,
                    'Message' => $msg,
                    'Status' => Lang::get('common.success'),
        ));
    }
}
