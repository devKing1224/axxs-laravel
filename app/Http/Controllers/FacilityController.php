<?php

/**
 * Register Admin To manage the Application 
 * 
 * PHP version 7.2
 * 
 * @category Controller
 * @package  Laravel
 * @author   Display Name <username@example.com>
 * @license  http://theaxxstablet.com/index.php/licence.txt BSD Licence
 * @link     http://theaxxstablet.com/index.php
 */

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use Illuminate\Support\Facades\Hash as Hash;
use Illuminate\Support\Facades\Auth;
use App\InmateSetMaxContact;
use Twilio\Rest\Client;
use App\Facility;
use App\User;
use App\InmateConfiguration;
use App\Service;
use App\Device;
use App\Role;
use DB;
use File;
use App\DefaultServicePermission;
use App\ServiceChargeByFacility;
//use Response;
use Spatie\Permission\Models\Role as Roles;

/**
 * Register Admin To manage the Application 
 * 
 * @category FacilityController
 * @package  Laravel
 * @author   Display Name <username@example.com>
 * @license  http://theaxxstablet.com/index.php/licence.txt BSD Licence
 * @link     http://theaxxstablet.com/index.php
 */
class FacilityController extends Controller
{

    /**
     * Function for dashboard UI.
     * 
     * @return NULL
     */
    public function facilityDashboard() {

        if (Auth::user()) {
            $device_status = '';
            if (Auth::user()->hasAnyRole(['Facility Staff', 'Facility Admin'])) {
                if (Auth::user()->hasRole('Facility Staff')) {
                    $maxcontactInfo = InmateSetMaxContact::where('user_id', Auth::user()->admin_id)->first();
                    $facility_id =  Facility::where('facility_user_id',Auth::user()->admin_id)->first();
                    $auth_id = Auth::user()->admin_id;

                } else {
                    $maxcontactInfo = InmateSetMaxContact::where('user_id', Auth::id())->first();
                    $facility_id =  Facility::where('facility_user_id',Auth::user()->id)->first();
                    $auth_id = Auth::id();
                    $device_status = $facility_id->device_status;
                }
                $userList = User::where('is_deleted', config('axxs.active'))
                                ->where('role_id', config('axxs.inmateadmin'))
                                ->where('admin_id', $auth_id)->get();
                $freeServiceInfo = Service::where('type', config('axxs.active'))
                                ->where('is_deleted', config('axxs.active'))->get();
                $paidServiceInfo = Service::where('type', 1)
                                ->where('is_deleted', config('axxs.active'))->get();
                $deviceInfo = Device::where('facility_id', $facility_id->id)
                                ->where('is_deleted', config('axxs.active'))->get();
                $roleInfo = Role::where('is_default', config('axxs.active'))
                        ->find(Auth::user()->role_id);

                return View('facilitydashboard', array('userList' => $userList, 'freeServiceInfo' => $freeServiceInfo, 'paidServiceInfo' => $paidServiceInfo, 'roleInfo' => $roleInfo, 'deviceInfo' => $deviceInfo, 'maxcontactInfo' => $maxcontactInfo, 'auth_id' => $auth_id,'deviceStatus' => $device_status));
            } else {
                return View('errors.404');
            }
        }return redirect(route('login'));
    }

    /**
     * Function for load facility Add UI.
     * 
     * @param object Request $request The inmate details keyed facility ID.
     * 
     * @return NULL.
     */
    public function addFacilityUI(Request $request) 
    {
        if (isset($request->id)) {
            $obj_facility = new Facility();
            $facilityInfo = $obj_facility->getFacilityAllInfor($request->id);
            if ($facilityInfo) {
                return View('facility.addfacility', array('facilityInfo' => $facilityInfo));
            } else {
                return redirect(route('facility.list'));
            }
        } else {
            return View('facility.addfacility');
        }
    }

    /**
     * Create function for list facility UI.
     * 
     * @return NULL
     */
    public function facilityListUI() 
    {   
        $facilityList = Facility::with('fa_admin')->where('is_deleted', config('axxs.active'));
        if (Auth::user()->hasRole('Facility Staff')) {
            $facilityList = $facilityList->where('facility_user_id',Auth::user()->admin_id);
        } else if (Auth::user()->hasRole('Facility Admin')) {
         $facilityList = $facilityList->where('facility_user_id',Auth::user()->id);
        }  else if (Auth::user()->hasRole('Facility Administrator')) {
            $fa_id = \App\FacilityAdmin::where('fa_user_id',Auth::user()->id)->value('id');
           $facilityList = $facilityList->where('facility_admin',$fa_id);
        }

        $facilityList = $facilityList->orderBy('facility_name','ASC')->get();

        
        $obj_config = new InmateConfiguration;
        $getInfo = $obj_config->getConfiguration('device_off');
        $email_create = $obj_config->getConfiguration('email_create');
        $tb_charge = $obj_config->getConfiguration('tablet_charge_on_off');
        
        return View('facility.facilitylist', array('facilityList' => $facilityList, 'device_off' => $getInfo, 'email_create' => $email_create , 'tb_charge' => $tb_charge ));
    }

    /**
     * Function for load facility view UI.
     * 
     * @param object Request $request The inmate details keyed facility ID.
     * 
     * @return NULL
     */
    public function viewFacilityUI(Request $request) 
    {
        if (isset($request->id)) {
            $obj_facility = new Facility();
            $facilityInfo = $obj_facility->getFacilityAllInfor($request->id);
            if ($facilityInfo) {
                $maxlimits = InmateSetMaxContact::where('user_id', $facilityInfo->facility_user_id)->first();
                return View('facility.viewfacility', array('facilityInfo' => $facilityInfo, 'maxlimits' => $maxlimits));
            } else {
                return redirect(route('facility.list'));
            }
        }
    }

    /**
     * Function for load facility list UI.
     * 
     * @return NULL
     */
    public function facilityInactiveListUI() 
    {

        $facilityList = Facility::where('is_deleted', config('axxs.inactive'))->get();
        return View('facility.facilityinactivelist', array('facilityList' => $facilityList));
    }

    /**
     * Function for load facility Add UI.
     * 
     * @return NULL.
     */
    public function facilityForgetPasswordUI() 
    {

        if (Auth::user()) {
            return View('facilityforgetpassword');
        }
        return redirect(route('login'));
    }

    /**
     * Create a new facility instance after a valid registration
     *
     * @param object Request $request The facility details keyed facility_id, 
     *                                name, email, phone, address_line_1,
     *                                address_line_2, city, state, zip
     *                                total_inmate, facility_admin, password
     *                                
     * @return json The id of newly registered facility keyed id in Response
     */
    public function registerFacility(Request $request) 
    {
        $data = $request->input();

        $rules = array(
            'facility_id' => 'required|unique:facilitys|max:11',
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email|unique:facilitys',
            'twilio_number' => 'sometimes|nullable|unique:facilitys',
            'phone' => 'unique:facilitys',
            'total_inmate' => 'required',
            'username' => 'required|unique:users',
            'password' => 'required',
            'tablet_charge' => 'required|numeric',
            'attachment_charge' => 'required|numeric',
            'facility_name' => 'required|unique:facilitys',
            'free_minutes' => 'required',
            'incoming_email_charge' => 'required',
        );

        $validate = Validator::make($data, $rules);

        if ($validate->fails()) {
            return response()->json(
                array(
                                'Code' => 400,
                                'Status' => \Lang::get('common.success'),
                                'Message' => $validate->errors()->all(),
                            )
            );
        } else {
            $validnumber = false;
            if(isset( $data['twilio_number']) && !empty( $data['twilio_number'])){
                $AccountSid = env('SMS_ACCOUNT_SID');
            $AuthToken = env('SMS_AUTH_TOKEN');
            $twilio = new Client($AccountSid, $AuthToken);
            $incoming_phone_number = $twilio->incomingPhoneNumbers->read();
            
                foreach ($incoming_phone_number as $record) {
                    if ($record->phoneNumber == $data['twilio_number']) {
                        $validnumber = true;
                    }
                }
            } else {
                 $validnumber = true;
            }
            
            if ($validnumber) {
                $user_insert = User::create(
                    [
                                    'username' => $data['username'],
                                    'password' => bcrypt($data['password']),
                                    'role_id' => 2,
                                    'status' => 0,
                                    'is_deleted' => config('axxs.active'),
                                ]
                );

                $role_r = Roles::where('id', 2)->firstOrFail();
                $user_insert->assignRole($role_r); //Assigning role to user

                if (isset($user_insert->id) && !empty($user_insert->id)) {
                    $facility_users_detail_insert = Facility::create(
                        [
                                        'facility_id' => $data['facility_id'],
                                        'facility_user_id' => $user_insert->id,
                                        'facility_admin' => isset($data['facility_admin']) ? $data['facility_admin'] : null,
                                        'name' => $data['first_name'] . ' ' . $data['last_name'],
                                        'first_name' => $data['first_name'],
                                        'last_name' => $data['last_name'],
                                        'email' => $data['email'],
                                        'twilio_number' => $data['twilio_number'],
                                        'phone' => isset($data['phone']) ? $data['phone'] : '',
                                        'address_line_1' => isset($data['address_line_1']) ? $data['address_line_1'] : '',
                                        'address_line_2' => isset($data['address_line_2']) ? $data['address_line_2'] : '',
                                        'city' => isset($data['city']) ? $data['city'] : '',
                                        'state' => isset($data['state']) ? $data['state'] : '',
                                        'zip' => isset($data['zip']) ? $data['zip'] : null,
                                        'total_inmate' => isset($data['total_inmate']) ? $data['total_inmate'] : '',
                                         'tablet_charge' => isset($data['tablet_charge']) ? $data['tablet_charge'] : '',
                                           'sms_charges' => isset($data['sms_charges']) ? $data['sms_charges'] : NULL,
                                        'email_charges' => isset($data['email_charges']) ? $data['email_charges'] : NULL,
                                        'show_email' => isset($data['show_email']) ? $data['show_email'] : 0,
                                        'is_deleted' => config('axxs.active'),
                                        'cpc_funding' => $data['cpc_funding'],
                                        'free_minutes' => $data['free_minutes'],
                                        'cntct_approval' => $data['cntct_approval'],
                                        'device_status' => $data['device_status'],
                                        'tablet_charges' => $data['tb_charge'],
                                        'create_email' => $data['create_email'],
                                        'facility_name' =>  $data['facility_name'],
                                        'location' => $data['location'],
                                        'incoming_email_charge' => $data['incoming_email_charge'],
                                        'terms_condition' => isset($data['terms_condition']) ? $data['terms_condition'] : null,
                                        'attachment_charge' => isset($data['attachment_charge']) ? $data['attachment_charge'] : null,
                                        'welcome_msg' => isset($data['welcome_msg']) ? $data['welcome_msg'] : null,
                                        'in_sms_charge' => isset($data['in_sms_charge']) ? $data['in_sms_charge'] : NULL,
                                       
                                    ]
                    );
                    if (isset($facility_users_detail_insert->id) && !empty($facility_users_detail_insert->id)) {
                        return response()->json(
                            array(
                                            'Code' => 201,
                                            'Message' => \Lang::get('common.success'),
                                            'Status' => \Lang::get('facility.facility_created'),
                                            'Data' => array('id' => $user_insert->id)
                                        )
                        );
                    }
                } else {
                    return response()->json(
                        array(
                                        'Code' => 401,
                                        'Message' => \Lang::get('common.success'),
                                        'Status' => array(\Lang::get('inmate.facility_not_created')),
                                        'Response' => array('id' => null)
                                    )
                    );
                }
            } else {
                return response()->json(
                    array(
                                    'Code' => 400,
                                    'Status' => \Lang::get('common.success'),
                                    'Message' => \Lang::get('facility.invalid_twilio'),
                                )
                );
            }
        }
    }

    /**
     * Create function for update facukity details behalf on facility id.
     *
     * @param object Request $request The facility id keyed facility_id, 
     * 
     * @return NULL
     */
    public function updateFacility(Request $request) 
    {
        $data = $request->input();

    $rules = array(
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|unique:facilitys,email,' . $data['id'] . ',id',
            'total_inmate' => 'required',
            'twilio_number' => 'sometimes|nullable|numeric|unique:facilitys,twilio_number,' . $data['id'] . ',id',
            'tablet_charge' => 'required|numeric',
            'attachment_charge' => 'required|numeric',
            'facility_name' => 'required|unique:facilitys,facility_name,' .  $data['id'],
            'free_minutes'  => 'required',
            'incoming_email_charge' => 'required',
        );

        

        $validate = Validator::make($data, $rules);

        if ($validate->fails()) {
            return response()->json(
                array(
                                'Status' => \Lang::get('common.success'),
                                'Code' => 400,
                                'Message' => $validate->errors()->all()
                            )
            );
        } else {
             $validnumber = true;
            if(isset( $data['twilio_number']) && !empty( $data['twilio_number'])){
                $AccountSid = env('SMS_ACCOUNT_SID');
            $AuthToken = env('SMS_AUTH_TOKEN');
            $twilio = new Client($AccountSid, $AuthToken);
            $incoming_phone_number = $twilio->incomingPhoneNumbers->read();
            
                foreach ($incoming_phone_number as $record) {
                    if ($record->phoneNumber == $data['twilio_number']) {
                        $validnumber = true;
                    }
                }
            } else {
                 $validnumber = true;
            }

            // $AccountSid = env('SMS_ACCOUNT_SID');
            // $AuthToken = env('SMS_AUTH_TOKEN');
            // $twilio = new Client($AccountSid, $AuthToken);
            // $incoming_phone_number = $twilio->incomingPhoneNumbers->read();
            // $validnumber = false;
            // foreach ($incoming_phone_number as $record) {
            //     if ($record->phoneNumber == $data['twilio_number']) {
            //         $validnumber = true;
            //     }
            // }
            if ($validnumber) {
                $updateData = array(
                    'name' => $data['first_name'] . ' ' . $data['last_name'],
                    'first_name' => $data['first_name'],
                    'last_name' => $data['last_name'],
                    'email' => $data['email'],
                    'twilio_number' => $data['twilio_number'],
                    'phone' => isset($data['phone']) ? $data['phone'] : '',
                    'facility_admin' => isset($data['facility_admin']) ? $data['facility_admin'] : null,
                    'address_line_1' => isset($data['address_line_1']) ? $data['address_line_1'] : '',
                    'address_line_2' => isset($data['address_line_2']) ? $data['address_line_2'] : '',
                    'city' => isset($data['city']) ? $data['city'] : '',
                    'state' => isset($data['state']) ? $data['state'] : '',
                    'zip' => isset($data['zip']) ? $data['zip'] : null,
                    'total_inmate' => isset($data['total_inmate']) ? $data['total_inmate'] : '',
                    'tablet_charge' => $data['tablet_charge'],
                    'sms_charges' => $data['sms_charges'],
                    'email_charges' => $data['email_charges'],
                    'show_email' => $data['show_email'],
                    'cpc_funding' => $data['cpc_funding'],
                    'free_minutes' => $data['free_minutes'],
                    'cntct_approval' => $data['cntct_approval'],
                    'device_status' => $data['device_status'],
                    'tablet_charges' => $data['tb_charge'],
                    'create_email' => $data['create_email'],
                    'facility_name' =>  $data['facility_name'],
                    'location' => $data['location'],
                    'terms_condition' => isset($data['terms_condition']) ? $data['terms_condition'] : null,
                    'incoming_email_charge' => $data['incoming_email_charge'],
                    'attachment_charge' => isset($data['attachment_charge']) ? $data['attachment_charge'] : null,
                    'welcome_msg' => isset($data['welcome_msg']) ? $data['welcome_msg'] : null,
                    'in_sms_charge' => isset($data['in_sms_charge']) ? $data['in_sms_charge'] : NULL,
                );
                $facilityUpdateInfo = Facility::where(array('id' => $data['id']))->update($updateData);
                if (isset($facilityUpdateInfo) && !empty($facilityUpdateInfo)) {

                    return response()->json(
                        array(
                                        'Status' => \Lang::get('common.success'),
                                        'Code' => 200,
                                        'Message' => \Lang::get('facility.facility_edit_success'),
                                    )
                    );
                } else {
                    return response()->json(
                        array(
                                        'Status' => \Lang::get('common.success'),
                                        'Code' => 400,
                                        'Message' => \Lang::get('facility.facility_edit_unsuccess')
                                    )
                    );
                }
            } else {
                return response()->json(
                    array(
                                    'Code' => 400,
                                    'Status' => \Lang::get('common.success'),
                                    'Message' => \Lang::get('facility.invalid_twilio'),
                                )
                );
            }
        }
    }

    /**
     * Create function for get all facility list and his informations
     *                                
     * @return json all facility information in response
     */
    public function getFacilityList() 
    {
        $facilityList = User::get();
        if (count($facilityList) > 0) {
            return response()->json(
                array(
                                'Status' => \Lang::get('facility.facility_details'),
                                'Code' => 200,
                                'Message' => \Lang::get('common.success'),
                                'Data' => $facilityList
                            )
            );
        } else {
            return response()->json(
                array(
                                'Status' => \Lang::get('facility.facility_not_found'),
                                'Code' => 400,
                                'Message' => \Lang::get('common.success'),
                            )
            );
        }
    }

    /**
     * Create function for Get facility details behalf on facility id.
     *
     * @param object Request $request The facility id keyed facility_id,
     * 
     * @return Json facility information return in response
     */
    public function getFacility(Request $request) 
    {
        $data = $request->input();

        $rules = array(
            'facility_id' => 'required',
        );

        $validate = Validator::make($data, $rules);

        if ($validate->fails()) {
            return response()->json(
                array(
                                'Status' => \Lang::get('common.success'),
                                'Code' => 400,
                                'Message' => $validate->errors()->all(),
                            )
            );
        } else {
            $failityInfo = User::where('id', $data['facility_id'])->get();
            if (count($failityInfo) > 0) {
                return response()->json(
                    array(
                                    'Status' => \Lang::get('common.success'),
                                    'Code' => 200,
                                    'Message' => \Lang::get('facility.facility_details'),
                                    'Data' => $failityInfo
                                )
                );
            } else {
                return response()->json(
                    array(
                                    'Status' => \Lang::get('common.success'),
                                    'Code' => 400,
                                    'Message' => \Lang::get('facility.facility_not_found')
                                )
                );
            }
        }
    }

    /**
     * Create function for soft delete facility details behalf on facility id.
     *
     * @param object Request $request The facility id keyed facility_id.
     * 
     * @return NULL
     */
    public function deleteFacility(Request $request) 
    {
        $data = $request->id;
        $objFacility = new Facility();
        $deleteFacility = $objFacility->deleteFacility($data);
        if (isset($deleteFacility) && !empty($deleteFacility)) {
            return response()->json(
                array(
                                'Status' => \Lang::get('common.success'),
                                'Code' => 200,
                                'Message' => \Lang::get('facility.facility_delete_success'),
                            )
            );
        } else {
            return response()->json(
                array(
                                'Status' => \Lang::get('common.success'),
                                'Code' => 400,
                                'Message' => \Lang::get('facility.facility_delete_unsuccess')
                            )
            );
        }
    }

    /**
     * Create function for update facility details behalf on facility id.
     *
     * @param object Request $request The facility id keyed facility_id, 
     * 
     * @return NULL
     */
    public function activeFacility(Request $request) 
    {
        $data = $request->input();

        $rules = array(
            'facility_id' => 'required'
        );

        $validate = Validator::make($data, $rules);

        if ($validate->fails()) {
            return response()->json(
                array(
                                'Status' => \Lang::get('common.success'),
                                'Code' => 400,
                                'Message' => $validate->errors()->all()
                            )
            );
        } else {
            $updateData = array(
                'is_deleted' => config('axxs.active'),
            );
            $facilityUpdateInfo = Facility::where(array('id' => $data['facility_id']))->update($updateData);
            if (isset($facilityUpdateInfo) && !empty($facilityUpdateInfo)) {

                return response()->json(
                    array(
                                    'Status' => \Lang::get('common.success'),
                                    'Code' => 200,
                                    'Message' => \Lang::get('facility.facility_edit_success'),
                                )
                );
            } else {
                return response()->json(
                    array(
                                    'Status' => \Lang::get('common.success'),
                                    'Code' => 400,
                                    'Message' => \Lang::get('facility.facility_edit_unsuccess')
                                )
                );
            }
        }
    }

    /**
     * Create function for update password inmate details behalf on inmate id.
     *
     * @param object Request $request The inmate id keyed inmate_id. 
     * 
     * @return NULL
     */
    public function changeFacilityPassword(Request $request) 
    {

        $data = $request->input();
        $rules = array(
            'facility_user_id' => 'required|exists:users,id',
            'current_password' => 'required',
            'new_password' => 'required',
            'confirm_password' => 'required|same:new_password',
        );

        $validate = Validator::make($data, $rules);

        if ($validate->fails()) {
            return response()->json(
                array(
                                'Status' => \Lang::get('common.failure'),
                                'Code' => 400,
                                'Data' => \Lang::get('common.validation_error'),
                                'Message' => $validate->errors()->all()
                            )
            );
        } else {
            $current_password = User::find($data['facility_user_id'])->password;

            if (Hash::check($data['current_password'], $current_password)) {

                $obj_user = User::find($data['facility_user_id']);
                $obj_user->password = Hash::make($data['new_password']);
                $obj_user->save();

                return response()->json(
                    array(
                                    'Status' => \Lang::get('common.success'),
                                    'Code' => 200,
                                    'Message' => \Lang::get('facility.password_changed')
                                )
                );
            } else {

                return response()->json(
                    array(
                                    'Status' => \Lang::get('common.success'),
                                    'Code' => 401,
                                    'Message' => \Lang::get('facility.incorrect_current_password'),
                                )
                );
            }
        }
    }

    public function autocomplete(Request $request) {
        $term = $request->term;

        $queries = Service::where('name', 'like', '%' . $term . '%')->get();

        if (count($queries) > 0) {

            $results[] =  ['id' => '', 'value' => "ALL"];
            foreach ($queries as $query) {
                $results[] = ['id' => $query->id, 'value' => $query->name]; //you can take custom values as you want
            }
           
        } else {
             $results[]=  ['value'=>'No Result Found','id'=>''];
        }        
        return response()->json($results);
    }

    /**
     * Get Facility List.
     *
     * @param NULL. 
     * 
     * @return array $facilityList
     */
    public function facilityList(){
        $facilityList = Facility::where('is_deleted', config('axxs.active'))->get();
        return $facilityList;
    }

    /**
     * Dwonload facility service.
     *
     * @param $facility_id. 
     * 
     * @return response
     */
    public function downloadFacilityservice($facility_id){
        $objService = new Service();
        $inmateServiceList = $objService->getFacilityServiceID($facility_id);
        $objDefaultService = new DefaultServicePermission();
        $inmateDefaultServiceList = $objDefaultService->getFacilityDefaultServiceInfo($facility_id);
        $actual_facility_id = Facility::where('facility_user_id',$facility_id)->value('id');
        
        $custom_charge = ServiceChargeByFacility::leftjoin('services','services.id','=','service_charge_by_facilities.service_id')
                        ->select('service_charge_by_facilities.'.'type','service_charge_by_facilities.'.'charge','services.'.'name')
                        ->where('facility_id',$actual_facility_id)->get()->toArray();
        $service_array = array(
            'seviceList' => $inmateServiceList,
            'defaultseviceList' =>  $inmateDefaultServiceList,
            'custom_charge' => $custom_charge
        );
         $data = json_encode($service_array);
         $file = time() . '_file.json';
        return response($data)
            ->withHeaders([
                'Content-Type' => 'text/plain',
                'Cache-Control' => 'no-store, no-cache',
                'Content-Disposition' => 'attachment; filename='.$file.'',
            ]);
    }

}
