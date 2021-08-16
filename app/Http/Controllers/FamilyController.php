<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\User;
use App\ServiceHistory;
use App\Family;
use App\Role;
use Auth;
use App\PaymentInformation;
use App\StaffLog;
use Spatie\Permission\Models\Role as Roles;
use App\Mail\FundTransferEmail;
use App\BlockContact;
use App\Facility;
use Mail;

/**
 * Register Admin To manage the Application
 * @category FamilyController
 * @package  Laravel
 * @author   Display Name <username@example.com>
 * @license  http://theaxxstablet.com/index.php/licence.txt BSD Licence
 * @link     http://theaxxstablet.com/index.php
 */
class FamilyController extends Controller {

    /**
     * Function for dashboard UI.
     * 
     * @return NULL
     */
    public function familyDashboard() {
        try {
            if (Auth::user()) {
                if (Auth::user()->role_id == 3) {
                    $familyInmateInfo = User::where('id', loggedInUser()->admin_id)->first();
                    $freeServiceInfo = ServiceHistory::where('inmate_id', loggedInUser()->admin_id)
                                    ->where('type', config('axxs.active'))->get();
                    $paidServiceInfo = ServiceHistory::where('inmate_id', loggedInUser()->admin_id)
                                    ->where('type', 1)->get();
                    $roleInfo = Role::where('is_default', config('axxs.active'))
                            ->find(Auth::user()->role_id);
                    return View('familydashboard', array('familyInmateInfo' => $familyInmateInfo, 'freeServiceInfo' => $freeServiceInfo, 'paidServiceInfo' => $paidServiceInfo, 'roleInfo' => $roleInfo));
                } else {
                    return View('errors.404');
                }
            }
            return redirect(route('login'));
        } catch (Exception $ex) {
            return errorLog($ex);
        }
    }

    /**
     * Create function for add family UI.
     * 
     * @param object Request $request has edit details
     *                                $inmate_id has inmate id
     *                                $family_id has family id
     * 
     * @return NULL
     */
    public function addFamilyUI(Request $request, $inmate_id, $family_id = null) {
        try {
            $inmate_exists = User::where('id', $inmate_id)
                    ->where('role_id', 4)
                    ->where('is_deleted', 0)
                    ->first();
            $admin_validate = new User();
            if ($family_id != null) {
                $familyInfo = Family::where('id', $family_id)->where('inmate_id', $inmate_id)->first();
                if (!empty($familyInfo) && $admin_validate->validateInmateStaffFacility($inmate_exists, Auth::user())) {
                    return view('family.addfamily', array('familyInfo' => $familyInfo, 'inmate_id' => $inmate_id));
                } else {
                    return redirect(route('family.list', ['inmate_id' => $inmate_id]));
                }
            } else {
                if ($inmate_exists && $admin_validate->validateInmateStaffFacility($inmate_exists, Auth::user())) {
                    return view('family.addfamily', array('inmate_id' => $inmate_id));
                } else {
                    return redirect(route('inmate.inmatelist'));
                }
            }
        } catch (Exception $ex) {
            return errorLog($ex);
        }
    }

    /**
     * Create function for list family UI.
     * 
     * @param object Request $request The family details keyed to inmate id
     * 
     * @return NULL
     */
    public function familyListUI(Request $request) {
        try {
            $inmate_exists = User::where('id', $request->inmate_id)
                    ->where('role_id', 4)
                    ->where('is_deleted', 0)
                    ->first();
            $admin_validate = new User();
            if ($inmate_exists && $admin_validate->validateInmateStaffFacility($inmate_exists, Auth::user())) {
                $inmate_id = $request->inmate_id;
                $familyObj = new Family();
                $familyList = $familyObj->getFamilyInfo($inmate_id);
                return View('family.familylist', array('familyList' => $familyList, 'inmate_id' => $inmate_id));
            } else {
                return redirect(route('inmate.inmatelist'));
            }
        } catch (Exception $ex) {
            return errorLog($ex);
        }
    }

    /**
     * Function for load family list UI.
     * 
     * @param object Request $request The family details keyed family ID 
     * 
     * @return NULL
     */
    public function familyInactiveListUI(Request $request) {
        try {
            $inmate_id = $request->inmate_id;
            $inmate_exists = User::where('id', $inmate_id)
                    ->where('role_id', 4)
                    ->where('is_deleted', 0)
                    ->first();
            $admin_validate = new User();
            if ($inmate_exists && $admin_validate->validateInmateStaffFacility($inmate_exists, Auth::user())) {
                $familyObj = new Family();
                $familyList = $familyObj->getFamilyInactiveInfo($inmate_id);
                return View('family.familyinactivelist', array('familyList' => $familyList, 'inmate_id' => $inmate_id));
            } else {
                return redirect(route('inmate.inmatelist'));
            }
        } catch (Exception $ex) {
            return errorLog($ex);
        }
    }

    /**
     * Function for load inmate family view UI.
     * 
     * @return NULL
     */
    public function viewInmateFamilyUI() {
        if (Auth::user() && Auth::user()->role_id == 3) {
            $familyInmateInfo = User::where('id', loggedInUser()->admin_id)->first();
            $check_cpc=$this->checkcpcfunding($familyInmateInfo['admin_id']);
            return View('family.viewfamilyinmate', ['familyInmateInfo' => $familyInmateInfo,'cpc_funding' => $check_cpc]);
        } return redirect(route('login'));
    }

    /**
     * Function for load inmate family view UI.
     * 
     * @param object Request $response The inmate details keyed inmate ID.
     *                                 $response keyed to move with response
     * 
     * @return NULL
     */
    public function paymentStatusScreenUIShow($response, $amount) {
        return View('paymentstatusscreen', ['amount' => $amount, 'response' => $response]);
    }

    public function paymentStatusScreenExternalUIShow($response, $amount, $user_id) {
        $user = User::where('id', $user_id)->first();
        return View('family.registerfriend', ['amount' => $amount, 'response' => $response, 'user' => $user]);
    }

    /**
     * Function for load payment screen.
     * 
     * @param object Request $request The inmate details keyed inmate ID.
     * 
     * @return NULL
     */
    public function paymentStatusScreenUI(Request $request) {
        try {
            $data = $request->all();

            $response = $this->registerPaymentInfo($data);
            $amount = $data['x_amount'];
            if (isset($data['x_description']) && $data['x_description'] == "guest") {
                return redirect()->action('FamilyController@paymentStatusScreenExternalUIShow', [$response, $amount, $data['x_cust_id']]);
            } else {
                return redirect()->action('FamilyController@paymentStatusScreenUIShow', [$response, $amount]);
            }
        } catch (Exception $ex) {
            return errorLog($ex);
        }
    }

    /**
     * Function for register payment information.
     * 
     * @param object Request $data payment information. 
     * 
     * @return NULL
     */
    protected function registerPaymentInfo($data) {
        try {
            if ($data['x_description'] == "guest") {
                $family_id = 0;
                $client_email = $data['x_email'];
                $client_name = ucwords($data['x_first_name'] . " " . $data['x_last_name']);
                $inmate_id = $data['x_cust_id'];
            } else {
                $family_id = loggedInUser()->detail['id'];
                $client_name = ucwords(loggedInUser()->detail['first_name'] . " " . loggedInUser()->detail['last_name']);
                $client_email = loggedInUser()->detail['email'];
                $inmate_id = loggedInUser()->detail['inmate_id'];
            }

            $paymentInfo = PaymentInformation::create(
                            [
                                'family_id' => $family_id,
                                'payment_status' => $data['Transaction_Approved'],
                                'transaction_id' => $data['x_trans_id'],
                                'client_email' => $client_email,
                                'client_name' => $client_name,
                                'inmate_id' => $inmate_id,
                                'amount' => $data['x_amount'],
                                'payemet_details' => serialize($data),
                            ]
            );

            $this->sendFamilyRechargeEmail($client_name, $inmate_id, $data, $client_email);

            if (isset($data['Transaction_Approved']) && $data['Transaction_Approved'] == 'YES') {
                $objUser = new User();
                if ($data['x_description'] == "guest") {
                    $userAddBalanceStatus = $objUser->updateAddBalance($data['x_cust_id'], $data['x_amount']);
                } else {
                    $userAddBalanceStatus = $objUser->updateAddBalance(loggedInUser()->admin_id, $data['x_amount']);
                }

                if ($userAddBalanceStatus) {
                    return 0;
                } else {
                    return 1;
                }
            } else {
                return 2;
            }
        } catch (Exception $ex) {
            return errorLog($ex);
        }
    }

    /**
     * Function for load inmate family view UI.
     * 
     * @param object Request $request The inmate details keyed inmate ID.
     * 
     * @return NULL
     */
    public function inmatePaymentScreen(Request $request) {
        try {
            $amount = $request->amount;
            $xlogin = env('FIRST_DATA_X_LOGIN');
            $trans_key = env('FIRST_DATA_TRANSACTION_KEY');
            $sequence = rand();
            $time = time();
            $str = "$xlogin^$sequence^$time^$amount^";
            $hash = hash_hmac('md5', $str, $trans_key);
            $paymentInformationArray = array('login' => $xlogin, 'key' => $trans_key, 'sequence' => $sequence, 'time' => $time, 'hash' => $hash, 'amount' => $amount);
            return View('inmatepaymentscreen', ['paymentInformationArray' => $paymentInformationArray]);
        } catch (Exception $ex) {
            return errorLog($ex);
        }
    }

    /**
     * Function for load family view UI.
     * 
     * @param  object Request $request The family details keyed family ID and inmate ID.
     *                                 $inmate_id for inmate keyed inmate ID
     *                                 $family_id for family keyed family id
     * @return NULL
     */
    public function viewFamilyUI(Request $request, $inmate_id, $family_id) {
        try {

            $familyInfo = Family::where('id', $family_id)->where('inmate_id', $inmate_id)->get()->first();
            $inmate_exists = User::where('id', $inmate_id)->where('role_id', 4)->first();
            $admin_validate = new User();
            if ($inmate_exists && $admin_validate->validateInmateStaffFacility($inmate_exists, Auth::user())) {
                if ($familyInfo) {
                    return View('family.viewfamily', array('familyInfo' => $familyInfo, 'inmate_id' => $inmate_id));
                } else {
                    return redirect(route('family.list', ['inmate_id' => $inmate_id]));
                }
            } else {
                return redirect(route('inmate.inmatelist'));
            }
        } catch (Exception $ex) {
            return errorLog($ex);
        }
    }

    /**
     * Create a new family instance after a valid registration
     *
     * @param object Request $request The family details keyed facility_id, 
     *                                name, email, phone, address_line_1,
     *                                address_line_2, city, state, zip,
     *                                password
     *                                
     * @return json The id of newly registered family keyed id in Response
     */
    public function registerFamily(Request $request) {
        try {
            $data = $request->input();

            $rules = array(
                'first_name' => 'required',
                'last_name' => 'required',
                'email' => 'required|unique:familys',
                'inmate_id' => 'required',
                'username' => 'required|unique:users',
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

                $inmatefacilityid = User::where('id', $data['inmate_id'])->select('admin_id')->first();

                $user_insert = User::create(
                                [
                                    'admin_id' => $data['inmate_id'],
                                    'username' => $data['username'],
                                    'email' => $data['email'],
                                    'password' => bcrypt($data['password']),
                                    'role_id' => config('axxs.familiyadmin'),
                                    'status' => config('axxs.status.unblock'),
                                    'is_deleted' => config('axxs.active'),
                                ]
                );


                $role_r = Roles::where('id', '=', config('axxs.familiyadmin'))->firstOrFail();
                $user_insert->assignRole($role_r); //Assigning role to user

                if (isset($user_insert->id) && !empty($user_insert->id)) {



                    $family_insert = Family::create(
                                    [
                                        'inmate_id' => $data['inmate_id'],
                                        'facility_user_id' => $inmatefacilityid->admin_id,
                                        'family_user_id' => $user_insert->id,
                                        'first_name' => $data['first_name'],
                                        'last_name' => $data['last_name'],
                                        'email' => $data['email'],
                                        'phone' => isset($data['phone']) ? $data['phone'] : '',
                                        'address_line_1' => isset($data['address_line_1']) ? $data['address_line_1'] : '',
                                        'address_line_2' => isset($data['address_line_2']) ? $data['address_line_2'] : '',
                                        'city' => isset($data['city']) ? $data['city'] : '',
                                        'state' => isset($data['state']) ? $data['state'] : '',
                                        'zip' => isset($data['zip']) ? $data['zip'] : null,
                                        'is_deleted' => config('axxs.active'),
                                    ]
                    );
                    if (isset($family_insert->id) && !empty($family_insert->id)) {

                        if (Auth::user()->hasRole('Facility Staff')) {
                            $staff_log = new StaffLog();

                            $staff_log_create = $staff_log->staffLogInsert(Auth::user()->id, config('axxs.action.create'), config('axxs.page.family'), $family_insert->id, 'Created new family');
                        }
                        return response()->json(
                                        array(
                                            'Code' => 201,
                                            'Message' => \Lang::get('common.success'),
                                            'Status' => \Lang::get('inmate.family_created'),
                                            'Data' => array('id' => $family_insert->id)
                                        )
                        );
                    }
                }
                return response()->json(
                                array(
                                    'Code' => 401,
                                    'Message' => \Lang::get('common.success'),
                                    'Status' => array(\Lang::get('inmate.inmate_not_created')),
                                    'Response' => array('id' => null)
                                )
                );
            }
        } catch (Exception $ex) {
            return errorLog($ex);
        }
    }

    /**
     * Create function for update family details behalf on family id.
     *
     * @param object Request $request The family id keyed family_id, 
     * 
     * @return NULL
     */
    public function updateFamily(Request $request) {
        try {
            $data = $request->input();

            $rules = array(
                'id' => 'required',
                'email' => 'required|unique:familys,email,' . $data['id']
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
                    'first_name' => $data['first_name'],
                    'last_name' => $data['last_name'],
                    'phone' => $data['phone'],
                    'email' => $data['email'],
                    'address_line_1' => isset($data['address_line_1']) ? $data['address_line_1'] : '',
                    'address_line_2' => isset($data['address_line_2']) ? $data['address_line_2'] : '',
                    'city' => isset($data['city']) ? $data['city'] : '',
                    'state' => isset($data['state']) ? $data['state'] : '',
                    'zip' => isset($data['zip']) ? $data['zip'] : null,
                );
                $familyUpdateInfo = Family::where(array('id' => $data['id']))->update($updateData);
                if (isset($familyUpdateInfo) && !empty($familyUpdateInfo)) {
                    if (Auth::user()->hasRole('Facility Staff')) {
                        $staff_log = new StaffLog();

                        $staff_log_create = $staff_log->staffLogInsert(Auth::user()->id, config('axxs.action.update'), config('axxs.page.family'), $data['id'], 'Updated existing family');
                    }
                    return response()->json(
                                    array(
                                        'Status' => \Lang::get('common.success'),
                                        'Code' => 200,
                                        'Message' => \Lang::get('inmate.inmate_update'),
                                    )
                    );
                }
                return response()->json(
                                array(
                                    'Status' => \Lang::get('common.success'),
                                    'Code' => 400,
                                    'Message' => \Lang::get('service.inmate_update_error')
                                )
                );
            }
        } catch (Exception $ex) {
            return errorLog($ex);
        }
    }

    /**
     * Create function for get all facility list and his informations
     *                                
     * @return json all facility information in response
     */
    public function getFamilyList() {
        try {
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
        } catch (Exception $ex) {
            return errorLog($ex);
        }
    }

    /**
     * Create function for Get facility details behalf on facility id.
     *
     * @param object Request $request The facility id keyed facility_id,
     * 
     * @return Json facility information return in response
     */
    public function getFamily(Request $request) {
        try {
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
            }
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
            }
            return response()->json(
                            array(
                                'Status' => \Lang::get('common.success'),
                                'Code' => 400,
                                'Message' => \Lang::get('facility.facility_not_found')
                            )
            );
        } catch (Exception $ex) {
            return errorLog($ex);
        }
    }

    /**
     * Create function for soft delete family details behalf on family id.
     *
     * @param object Request $request The family id keyed family_id.
     * 
     * @return NULL
     */
    public function deleteFamily(Request $request) {
        try {
            $data = $request->id;
            $objFamily = new Family();
            $deleteFamily = $objFamily->deleteFamily($data);
            if (isset($deleteFamily) && !empty($deleteFamily)) {

                if (Auth::user()->hasRole('Facility Staff')) {
                    $staff_log = new StaffLog();

                    $staff_log_create = $staff_log->staffLogInsert(Auth::user()->id, config('axxs.action.deactivate'), config('axxs.page.family'), $data, 'De-Activated existing family');
                }
                return response()->json(
                                array(
                                    'Status' => \Lang::get('common.success'),
                                    'Code' => 200,
                                    'Message' => \Lang::get('inmate.inmate_delete'),
                                )
                );
            }
            return response()->json(
                            array(
                                'Status' => \Lang::get('common.success'),
                                'Code' => 400,
                                'Message' => \Lang::get('service.inmate_delete_error')
                            )
            );
        } catch (Exception $ex) {
            return errorLog($ex);
        }
    }

    /**
     * Create function for update family details behalf on family id.
     *
     * @param object Request $request The facility id keyed family_id, 
     * 
     * @return NULL
     */
    public function activeFamily(Request $request) {
        try {
            $data = $request->input();

            $rules = array(
                'family_id' => 'required'
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
            }
            $updateData = array(
                'is_deleted' => config('axxs.active'),
            );
            $familyUpdateInfo = Family::where(array('id' => $data['family_id']))->update($updateData);
            if (isset($familyUpdateInfo) && !empty($familyUpdateInfo)) {
                if (Auth::user()->hasRole('Facility Staff')) {
                    $staff_log = new StaffLog();

                    $staff_log_create = $staff_log->staffLogInsert(Auth::user()->id, config('axxs.action.activate'), config('axxs.page.family'), $data['family_id'], 'Activated existing family');
                }

                return response()->json(
                                array(
                                    'Status' => \Lang::get('common.success'),
                                    'Code' => 200,
                                    'Message' => \Lang::get('family.facility_edit_success'),
                                )
                );
            }
            return response()->json(
                            array(
                                'Status' => \Lang::get('common.success'),
                                'Code' => 400,
                                'Message' => \Lang::get('family.facility_edit_unsuccess')
                            )
            );
        } catch (Exception $ex) {
            return errorLog($ex);
        }
    }

    /**
     * Function for load family view UI.
     * 
     * @param object Request $request The family details keyed family ID and inmate ID.
     * 
     * @return NULL
     */
    public function viewFamilyRechargeActivityUI() {
        try {
            if (Auth::user() && Auth::user()->role_id == 3) {
                $obj_payment = new PaymentInformation();
                $inmate_id = loggedInUser()->admin_id;
                $payemtnInformation = $obj_payment->getPaymentInformation($inmate_id);
                return View('family.familyrechargeactivity', array('payemtnInformation' => $payemtnInformation));
            }
            return redirect(route('login'));
        } catch (Exception $ex) {
            return errorLog($ex);
        }
    }

    /**
     * Function for payment information for inmate.
     * 
     * @param type integer $family_id
     * 
     * @return type json_string payment information 
     */
    public function getPaymentInformation($family_id) {
        try {
            $obj_payment = new PaymentInformation();
            $payemtnInformation = $obj_payment->getPaymentInformation($family_id);
            if (isset($payemtnInformation) && !empty($payemtnInformation)) {

                return response()->json(
                                array(
                                    'Status' => \Lang::get('common.success'),
                                    'Code' => 200,
                                    'Message' => \Lang::get('payment_history.payment_get_data'),
                                    'Data' => $payemtnInformation
                                )
                );
            }
            return response()->json(
                            array(
                                'Status' => \Lang::get('common.success'),
                                'Code' => 400,
                                'Message' => \Lang::get('payment_does_not_have_data.payment_does_not_have_data'),
                            )
            );
        } catch (Exception $ex) {
            return errorLog($ex);
        }
    }

    public function familyRechargeFromOutsideUI() {
        return View('family.inmaterecharge_api');
    }

    public function verifyInmateAPI(Request $request) {
        
        try {
            $data = $request->input();
            $rules = array(
                'first_name' => 'required|exists:users,first_name',
                'last_name' => 'required|exists:users,last_name',
                'user_id' => 'required|exists:users,inmate_id',
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
                $user_exist = User::where('first_name', $data['first_name'])
                                ->where('last_name', $data['last_name'])
                                ->where('inmate_id', $data['user_id'])->first();
                //Check cpc funding enabled or not
                $check_cpc=$this->checkcpcfunding($user_exist['admin_id']);
                if ($check_cpc) {
                    return response()->json(
                                    array(
                                        'Status' => \Lang::get('common.success'),
                                        'Code' => 403,
                                    )
                    );
                }
                if (!$user_exist) {
                    return response()->json(
                                    array(
                                        'Status' => \Lang::get('common.success'),
                                        'Code' => 401,
                                    )
                    );
                } else {
                    return response()->json(
                                    array(
                                        'Status' => \Lang::get('common.success'),
                                        'Code' => 200,
                                        'data' => $user_exist->id
                                    )
                    );
                }
            }
        } catch (Exception $ex) {
            return errorLog($ex);
        }
    }

    public function inmateExternalPaymentScreen(Request $request) {

        try {
            $amount = $request['amount'];

            $xlogin = env('FIRST_DATA_X_LOGIN');
            $trans_key = env('FIRST_DATA_TRANSACTION_KEY');
            $sequence = rand();
            $time = time();
            $str = "$xlogin^$sequence^$time^$amount^";
            $hash = hash_hmac('md5', $str, $trans_key);
            $paymentInformationArray = array('login' => $xlogin, 'key' => $trans_key, 'sequence' => $sequence, 'time' => $time, 'hash' => $hash, 'amount' => $amount);

            return response()->json(
                            array(
                                'Code' => 200,
                                'Message' => \Lang::get('common.success'),
                                'Data' => $paymentInformationArray
                            )
            );
        } catch (Exception $ex) {
            return errorLog($ex);
        }
    }

    public function sendFamilyRechargeEmail($name, $inmate_id, $data, $email) {
        try {
         
             $removeEmail = BlockContact::where('email', $email)->exists();
                if(empty($removeEmail)){
                $user = User::where('id', $inmate_id)->first();
                    if (!empty($user)) {
                        $content = [
                            'title' => 'Recharged Successfully.',
                            'client_name' => $name,
                            'username' => $user->first_name . ' ' . $user->last_name,
                            'data' => $data,
                            'email' => $email,
                        ];
        
                        $receiverAddress = $email;
                        $var = Mail::to($receiverAddress)->send(new FundTransferEmail($content));
                      }
                }    
            } catch (Exception $ex) {
                return errorLog($ex);
            }
    }

     public function emailBlock($email) {
     
      try{  
            
            $email =  base64_decode($email);
            $emailExists = BlockContact::where('email', $email)->exists();
            if(empty($emailExists)){
                 BlockContact::Create([
                            'email' => $email,
                            'block' => 1,
                ]);
                
                 $message  = 'Email ID has been removed Successfully from database';
                   return view('removecontact', array('message' => $message));
        

                }else{
                   
                    $message  = 'Email ID already removed';
                   return view('removecontact', array('message' => $message));
            }
        }catch (Exception $ex) {
                return errorLog($ex);
            }

    }

    public function checkcpcfunding($admin_id){
        $facility=Facility::where(['facility_user_id'=>$admin_id,'cpc_funding' =>1])->get();
        if(count($facility) > 0){
            return true;
        }else{
            return false;
        }
    }

}
