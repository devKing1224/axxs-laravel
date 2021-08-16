<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\InmateActivityHistory;
use App\Service;
use App\ServiceHistory;
use App\User;
use Auth;
use App\InmateConfiguration;
use App\Facility;
use App\FlatRateServices;
use Illuminate\Support\Facades\Hash as Hash;
use Log;
use DB;
use App\ServiceChargeByFacility;
use App\EstimateServiceUse;
use DateTime;

class InmateActivityHistoryController extends Controller {

    /**
     * Create start spent time activity history in both case (paid or free services)
     * 
     * @param object Request $request The facility details keyed facility_id and service_id, 
     *                              
     * @return json in new create activity history id, type and
     *         total duration keyed max_available_duration in Response
     */
   public function registerInmateStartTimeActivityHistory(Request $request) {
        $obj_services = new Service();
        $data = $request->input();

        $rules = array(
            'inmate_id' => 'required',
            'service_id' => 'required',
            'facility_id' => 'required'
        );

        $validate = Validator::make($data, $rules);

        if ($validate->fails()) {
            return response()->json(array(
                        'Code' => 200,
                        'Status' => \Lang::get('common.success'),
                        'Message' => $validate->errors()->all(),
            ));
        } else {

            $curuntDateStamp = date('Y-m-d');
            $curuntTimeStamp = date('H:i:s');
            $currentDateTime = date('Y-m-d H:i:s');
            $user_bal = User::where('id', $data['inmate_id'])
                            ->where('is_deleted', config('axxs.active'))
                            ->select('balance')->first();

            //Get service charge if set by facility
            $fac_service = ServiceChargeByFacility::where(['facility_id'=> $data['facility_id'],'service_id' => $data['service_id']])->first();
            $obj_services = Service::find($data['service_id']);

            if ($fac_service != null) {
                $obj_services->charge = $fac_service->charge;
                $obj_services->type =  $fac_service->type;
            }

            /* Get all details for services behalf on service id */

            if ($obj_services->type == 1) {
                if ($user_bal['balance'] <= 0 || $user_bal['balance'] < $obj_services->charge ) {
                    return response()->json(array(
                                'Code' => 200,
                                'Message' => \Lang::get('inmate_activity.low_balance'),
                                'Status' => \Lang::get('common.success')
                    ));
                }
            }
            $data['is_buy'] = 0;
            if( $obj_services->type == 2){

                    $flatService =FlatRateServices::where('service_id', $obj_services->id)->where('user_id', $data['inmate_id'])->exists();
                    if ($flatService) {
                        $data['is_buy'] = 1;
                    }
                    if (empty($flatService)) {
                        if ($user_bal['balance'] <= 0 || $user_bal['balance'] < $obj_services->charge ) {
                            return response()->json(array(
                                        'Code' => 200,
                                        'Message' => \Lang::get('inmate_activity.low_balance'),
                                        'Status' => \Lang::get('common.success')
                            ));
                        }
                        $user_insert = FlatRateServices::create([
                             'user_id' => $data['inmate_id'],
                             'service_id' =>  $obj_services->id,
                             'flate_rate' => $obj_services->charge
                        ]);
                        $obj_user = new User();
                        $inmateBalnce = $obj_user->updateBalance( $data['inmate_id'],  $obj_services->charge);
                    }
             }
            
            /* get all detials for service history */
            $obj_services_history = ServiceHistory::where('inmate_id', $data['inmate_id'])
                            ->where('type', $obj_services->type)
                            ->where('date', $curuntDateStamp)->first();

            /* Here Check service history and also it is blocked or not */
            if ((!empty($obj_services_history) && $obj_services_history->status == 0) || empty($obj_services_history)) {
                 $user_bal = User::where('id', $data['inmate_id'])
                                ->select('balance')->first();
                if (isset($user_bal) && $user_bal->balance <= 0 && $obj_services->type == 1) {
                    return response()->json(array(
                                'Code' => 200,
                                'Message' => \Lang::get('common.failure'),
                                'Status' => \Lang::get('inmate_activity.low_balance'),
                                'Data' => array('max_available_duration' => 0)
                    ));
                }

                /* Here we store value in inmate activity table  */
                $inmate_activity_history_insert = InmateActivityHistory::create([
                            'inmate_id' => $data['inmate_id'],
                            'service_id' => $data['service_id'],
                            'start_datetime' => $currentDateTime,
                            'end_datetime' => ''
                ]);

                if (isset($inmate_activity_history_insert->id) && !empty($inmate_activity_history_insert->id)) {
                    return $this->inmateServiceStartTime($data, $obj_services, $inmate_activity_history_insert->id);
//
                } else {
                    return response()->json(array(
                                'Code' => 401,
                                'Message' => \Lang::get('common.success'),
                                'Status' => \Lang::get('inmate_activity.inmate_activity_not_created'),
                    ));
                }
            } else {
                return response()->json(array(
                            'Code' => 200,
                            'Message' => \Lang::get('common.success'),
                            'Status' => \Lang::get('inmate_activity.inmate_service_block'),
                            'Data' => array('max_available_duration' => 0)
                ));
            }
        }
    }
    
        /**
     * Function for inmate free services start activity history.
     * 
     * @param object $data, $obj_services for service and inmate information
     * 
     * @return JSON
     */
   public function inmateServiceStartTime($data, $obj_services, $inmate_activity_history_insert_id) {
        /* Get inmate balance details behalf on inmate id */
        $obj_user = User::find($data['inmate_id']);

        if ($obj_services->type == 0 || $obj_services->charge == 0) {
            $totalMaxDuration = 1000000;
            $total_duration = $totalMaxDuration;
        } else {
            $totalMaxDuration = (3600 / $obj_services->charge) * $obj_user->balance;
            $total_duration = intVal($totalMaxDuration);
        }

        $service_history_insert = ServiceHistory::create([
                    'inmate_id' => $data['inmate_id'],
                    'inmate_activity_history_id' => $inmate_activity_history_insert_id,
                    'service_id' => $obj_services->id,
                    'type' => $obj_services->type,
                    'date' => date('Y-m-d'),
                    'total_duration' => $total_duration,
                    'charges' => 0,
                    'spent_duration' => 0,
                    'available_duration' => $total_duration,
                    'status' => 0
        ]);
        if ($data['is_buy'] === 1) {
            $obj_services->charge = 0;
        }
        /* Here we store value in service history table  */
        if (isset($service_history_insert->id) && !empty($service_history_insert->id)) {

            //inserting data into estimateservice
            $this->insertEstimateserviceData($data);
            return response()->json(array(
                        'Code' => 200,
                        'Message' => \Lang::get('common.success'),
                        'Status' => \Lang::get('inmate_activity.inmate_activity_created'),
                        'Data' => array('inmate_activity_history_id' => $inmate_activity_history_insert_id, 'service_charge' => $obj_services->charge, 'available_balance' => $obj_user->balance, 'max_available_duration' => intVal($totalMaxDuration), 'type' => $obj_services->type)
            ));
        }
        return response()->json(array(
                    'Code' => 401,
                    'Message' => \Lang::get('common.success'),
                    'Status' => \Lang::get('inmate_activity.inmate_activity_not_created'),
        ));
    }
    

    /**
     * Create End time activity history in both case (paid or free services)
     * 
     * @param object Request $request The facility details keyed facility_id, inmate_activity_history_id and service_id, 
     *                              
     * @return json in new create available duration, type and
     *         spent time keyed available_duration in Response
     */
     public function registerInmateEndTimeActivityHistory(Request $request) {
        $data = $request->input();

        $rules = array(
            'inmate_activity_history_id' => 'required',
            'inmate_id' => 'required',
            'service_id' => 'required',
            'end_datetime' => 'required',
            'start_datetime' => 'required',
            'facility_id' => 'required'
        );

        $validate = Validator::make($data, $rules);
        
        

        if ($validate->fails()) {
            return response()->json(array(
                        'Code' => 400,
                        'Status' => \Lang::get('common.success'),
                        'Message' => $validate->errors()->all(),
            ));
        } else {
            if (isset($data['status']) && !empty($data['status']) && $data['status'] == 1) {
                $this->sendEmailToFacilityAdmin($request);
                return response()->json(array(
                            'Code' => 200,
                            'Message' => \Lang::get('common.success'),
                            'Status' => \Lang::get('inmate_activity.inmate_service_block'),
                ));
            }

            // log something to storage/logs/debug.log
            Log::useDailyFiles(storage_path().'/logs/charge.log');
            Log::info(['end_datetime'=>$data['end_datetime']]);
            /* Get information for services behalf on service ID */
            $obj_services = Service::find($data['service_id']);


            $curuntDateStamp = date('Y-m-d', (int) ($data['end_datetime'] / 1000));
            $curuntTimeStamp = date('H:i:s', (int) ($data['end_datetime'] / 1000));
            
            $curuntStartTimeStamp = date('H:i:s', (int) ($data['start_datetime'] / 1000));

           
                /* here we change for spent time code and we have updated for testing purpose */
                $obj_services_history = ServiceHistory::where('inmate_id', $data['inmate_id'])
                                ->where('inmate_activity_history_id', $data['inmate_activity_history_id'])
                                ->where('date', $curuntDateStamp)->first();
            
            // log something to storage/logs/debug.log
            Log::useDailyFiles(storage_path().'/logs/charge.log');
            Log::info(['obj_services_history'=>$obj_services_history]);
            
            Log::useDailyFiles(storage_path().'/logs/chargeData.log');
            Log::info(['services_history_data'=>$data]);
            
            if (!empty($obj_services_history)) {

                /* Here we have check status */
                if ($obj_services_history->status == 0) {
                    /* Update end time in activity history */
                    $obj_inmate_activity_history = InmateActivityHistory::find($data['inmate_activity_history_id']);
                    $obj_inmate_activity_history->end_datetime = $curuntTimeStamp;
                    $obj_inmate_activity_history->save();
                    
                    Log::useDailyFiles(storage_path().'/logs/chargeData.log');
                    Log::info(['obj_inmate_activity_history'=>$obj_inmate_activity_history]);

                    return $this->inmateServiceEndTime($data, $obj_services_history);
                } else {
                    return response()->json(array(
                                'Code' => 200,
                                'Message' => \Lang::get('common.success'),
                                'Status' => \Lang::get('inmate_activity.inmate_service_block'),
                                'Data' => array('max_available_duration' => 0)
                    ));
                }
            } else {
                return response()->json(array(
                            'Code' => 200,
                            'Message' => \Lang::get('common.success'),
                            'Status' => \Lang::get('inmate_activity.inmate_end_time_update'),
                ));
            }
        }
    }

    /**
     * Function for load inmate list UI.
     * 
     * @param object Request $request The facility details keyed facility ID 
     * 
     * @return NULL
     */
    public function inmateAcitivityHistoryListUI(Request $request) {

        if (Auth::user()) {

            $roleID = Auth::user()->role_id;
            if ($roleID == 3) {
                $inmatefamily = \App\Family::where('inmate_id', $request->id)->where('family_user_id', Auth::id())->first();
                if ($inmatefamily) {
                    $diffrentServiceID = getDiffrentServicesID();
                    $objInmateActivity = new InmateActivityHistory();
                    $inmateActivityHistory = $objInmateActivity->getInmateActivityHistory($request->id);
                } else {
                    return redirect(route('inmate.inmatelist'));
                }
            } elseif (Auth::user()->hasAnyRole(['Facility Staff', 'Facility Admin'])) {
                if(Auth::user()->hasRole('Facility Staff')){
                    $inmatefacility = User::where('id', $request->id)
                        ->where('admin_id', Auth::user()->admin_id)
                        ->first();
                } else {
                    $inmatefacility = User::where('id', $request->id)
                        ->where('admin_id', Auth::id())
                        ->first();
                }
                
                if ($inmatefacility) {
                    $diffrentServiceID = getDiffrentServicesID();
                    $objInmateActivity = new InmateActivityHistory();
                    $inmateActivityHistory = $objInmateActivity->getInmateActivityHistory($request->id);
                } else {
                    return redirect(route('inmate.inmatelist'));
                }
            } elseif (Auth::user()->hasRole('Super Admin') || Auth::user()->hasPermissionTo('Manage Users')) {
                $inmateadmin = User::where('id', $request->id)
                        ->where('is_deleted', 0)
                        ->first();
                if ($inmateadmin) {
                    $diffrentServiceID = getDiffrentServicesID();
                    $objInmateActivity = new InmateActivityHistory();
                    $inmateActivityHistory = $objInmateActivity->getInmateActivityHistory($request->id);
                } else {
                    return redirect(route('inmate.inmatelist'));
                }
            }
            $depo_history= DB::table('payment_information')
                    ->where('inmate_id',$request->id)
                    ->get()->toArray();
            return View('inmateativityhistory', array('inmateActivityInformation' => $inmateActivityHistory, 'diffrentServiceID' => $diffrentServiceID,'depo_history' => $depo_history));
        }
        return redirect(route('login'));
    }



    /**
     * Function for inmate paid services End activity history.
     * 
     * @param object $data, $obj_services for service and inmate information
     * 
     * @return JSON
     */
      public function inmateServiceEndTime($data, $obj_services_history) {

        $obj_user = User::where('id', $data['inmate_id'])
                        ->select('balance')->first();

        $fac_service = ServiceChargeByFacility::where(['facility_id'=> $data['facility_id'],'service_id' => $data['service_id']])->first();
        $obj_services = Service::where('id', $data['service_id'])->first();

        if ($fac_service != null) {
            $obj_services->charge = $fac_service->charge;
            $obj_services->type =  $fac_service->type;
        }
        
        /* Get inmate activity history */
        $NEWobj_inmate_activity_history = InmateActivityHistory::find($data['inmate_activity_history_id']);
        
        $totalSpentTime = (strtotime($NEWobj_inmate_activity_history->end_datetime) - strtotime($NEWobj_inmate_activity_history->start_datetime));

        $StartDate_api = date('Y-m-d H:i:s', (int) ($data['start_datetime'] / 1000));
        $StartTime_api = date('Y-m-d H:i:s', (int) ($data['start_datetime'] / 1000));
        $StartTimeStamp = date('Y-m-d H:i:s', (int) ($data['start_datetime'] / 1000));
        $totalSpentLatestTime = (strtotime($NEWobj_inmate_activity_history->end_datetime) - strtotime($StartTime_api));
        
         
        $chargePerHour = $obj_services->charge;
         // set flat rate service
        if ($obj_services->type == 2)
        {
            $chargePerHour = 0;
        }
        if ($obj_services->type == 0 || $chargePerHour == 0) {
            $availableSecond = 1000000;
        } 
        else{
            $availableSecond = (3600 / $chargePerHour) * $obj_user->balance;
        }
        /* Get amount deduct behalf on second */
        $remainingTime = $availableSecond - $totalSpentTime;
        
        $total_amount_local = 0;
        if ($obj_services->type == 1) {
            $charge_deduction_local = round(($totalSpentLatestTime * $chargePerHour) / 3600, 3);

            $total_extra_amount = \App\InmateChargesHistory::whereBetween('created_at', [$StartTimeStamp, $NEWobj_inmate_activity_history->updated_at])
                            ->where('inmate_id', $data['inmate_id'])->where('service_id', $data['service_id'])->sum('transaction');
            // $totalamount = $total_extra_amount + $data['charges_deduction_in_doller'];
            $total_amount_local = $charge_deduction_local + $total_extra_amount;
        }
        

            $obj_userbalance =  new User();
            $obj_user_new = $obj_userbalance->updateBalance($data['inmate_id'],$total_amount_local);
            /* here we add code for entry inmate activity history column */
            $user_balance = User::find($data['inmate_id']);
            /* hear we update charges and spent time */
            $obj_services_history->available_duration = intVal($remainingTime);
            $obj_services_history->charges = $obj_services_history->charges + ($total_amount_local);
            $obj_services_history->spent_duration = $totalSpentTime;
            $obj_services->transaction_id = bin2hex(openssl_random_pseudo_bytes(16));
            $obj_services_history->save();
            $in_total_charges_deducted =  round($obj_services_history->charges, 3);
            return response()->json(array(
                        'Code' => 200,
                        'Message' => \Lang::get('common.success'),
                        'Status' => \Lang::get('inmate_activity.inmate_end_time_update'),
                        'Data' => array(
                            'available_balance' => $user_balance->balance,
                            'available_duration' => intVal($remainingTime),
                            'spent_time' => $totalSpentTime,
                            'deduct_charges' => $in_total_charges_deducted,
                            'type' => $obj_services->type
                        ),
            ));
   
    }

    /**
     * Create End time activity history in both case (paid or free services)
     * 
     * @param object Request $request The facility details keyed facility_id, inmate_activity_history_id and service_id, 
     *                              
     * @return json in new create available duration, type and
     *         spent time keyed available_duration in Response
     */
    public function getInmateActivityDetails(Request $request) {
        
        try {

            $data = $request->input();

            $rules = array(
                'inmate_id' => 'required',
            );

            $validate = Validator::make($data, $rules);

            if ($validate->fails()) {
                return response()->json(array(
                            'Status' => \Lang::get('common.success'),
                            'Code' => 400,
                            'Message' => $validate->errors()->all(),
                ));
            } else {
                $objInmateActivity = new InmateActivityHistory();
                $inmateActivityHistory = $objInmateActivity->getInmateActivityHistory($data['inmate_id']);
                if (count($inmateActivityHistory) > 0) {
                    return response()->json(array(
                                'Status' => \Lang::get('common.success'),
                                'Code' => 200,
                                'Message' => \Lang::get('inmate.inmate_details'),
                                'Data' => $inmateActivityHistory
                    ));
                } else {
                    return response()->json(array(
                                'Status' => \Lang::get('common.success'),
                                'Code' => 200,
                                'Message' => \Lang::get('inmate.inmate_not_found'),
                                'Data' => []
                    ));
                }
            }
        } catch (Exception $ex) {
            return errorLog($ex);
        }
    }

    /**
     * Send mail to facililty for resetting the password
     * 
     * @param  object Request $request the inmate details like inmate_id
     *                              
     * @return saved data.
     */
    function sendEmailToFacilityAdmin(Request $request) {
        $data = $request->input();
        $randomPassword = str_random(10);
        $objFacility = new Facility();
        $emailInfo = $objFacility->getFacilityInfoByInmateID($data['inmate_id']);
        $title = 'Password has been reset for username:' . $emailInfo->username ;
        $body = 'User password has been reset for clicking on an unauthorized  link, users new password is:- ';
        $emailValue = array(
            'to' => $emailInfo->email,
            'title' => $title,
            'body' => $body . ' ' . $randomPassword
        );
        $request->merge($emailValue);
        $objSendMail = new SendMailController();
        $sendEmail = $objSendMail->sendMail($request);

        /* start code for Email Send for facility admin */
        $obj_user = User::where('id', $data['inmate_id'])->first();
        $obj_user->password = Hash::make($randomPassword);
        return $obj_user->save();
    }

    /**
     * Insert data in Estimate Service Use Table
     * 
     * @param  $data $service_id ,$facility_id ,$inmate_id
     *                              
     * @return saved data.
     */
    public function insertEstimateserviceData($data){
        $time_slots = array('12:01 am-2:00 am','2:01 am-4:00 am','4:01 am-6:00 am','6:01 am-8:00 am','8:01 am-10:00 am','10:01 am-12:00 pm','12:01 pm-2:00 pm','2:01 pm-4:00 pm','4:01 pm-6:00 pm','6:01 pm-8:00 pm','8:01 pm-10:00 pm','10:01 pm-12:00 pm'
        );
        $current_date =  date('Y-m-d h:i:s', time());
        $blank_slot= array(0,0,0,0,0,0,0,0,0,0,0,0);
        $cmp_time = date('h:i a', strtotime($current_date));
        //getall service that exist in current data
       foreach ($time_slots as $key => $value) {
                        $time = explode('-', $value);
                        $s_time = DateTime::createFromFormat('H:i a', $time['0']);
                        $e_time = DateTime::createFromFormat('H:i a', $time['1']);
                        $c_time = DateTime::createFromFormat('H:i a', $cmp_time);

                        if ($c_time > $s_time && $c_time < $e_time)
                        {   
                           $slot_key = $key;
                           break;
                         }

                   }
        $get_time_slot = explode('-', $time_slots[$slot_key]);
        $s_time = DateTime::createFromFormat('H:i a', $get_time_slot['0']);
        $e_time = DateTime::createFromFormat('H:i a', $get_time_slot['1']);
        //dd($e_time);
        
        
        $today_used_Service = EstimateServiceUse::where(['inmate_id'=> $data['inmate_id'] ,'facility_id' => $data['facility_id'] , 'service_id' => $data['service_id'] , 'date' => date('Y-m-d')])->whereBetween('date_time', [$s_time, $e_time])->exists();
        if (!$today_used_Service) {
            $data['date_time'] = $current_date;
            $data['date'] = date('Y-m-d');
                try {
                      EstimateServiceUse::create($data);
                  } catch (Exception $e) {
                      
                  }  
        }
    }

}
