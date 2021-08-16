<?php

/**
 * Handling Device view and  To manage the Devices 
 * 
 * PHP version 7.2
 * 
 * @category Controller
 * @package  Laravel
 * @author   Display Name <username@example.com>
 * @license  http://theaxxstablet.com/index.php/licence.txt BSD Licence
 * @link     http://theaxxstablet.com/index.php/devicelist
 */

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Validator;
use App\Device;
use App\StaffLog;
use App\Facility;
use DB;
use Lang;

/**
 * Handling Device view and  To manage the Devices 
 * 
 * @category Controller
 * @package  Laravel
 * @author   Display Name <username@example.com>
 * @license  http://theaxxstablet.com/index.php/licence.txt BSD Licence
 * @link     http://theaxxstablet.com/index.php/devicelist
 */
class DeviceController extends Controller {

    /**
     * Function for load device Add UI.
     * 
     * @param object Request $request The facility details keyed device ID 
     * 
     * @return NULL
     * @link   http://theaxxstablet.com/index.php/adddevice
     */
     public function addDeviceUI(Request $request) {

        if (Auth::user()->hasAnyRole(['Facility Admin', 'Facility Staff'])) {
            if (Auth::user()->hasRole('Facility Admin')) {
                $admin_id = Auth::user()->id;
            } else {
                $admin_id = Auth::user()->admin_id;
            }
            /* Role for facility admin */
            $facilityid = Facility::where('facility_user_id', $admin_id)->first();
            $facilityList = Facility::where('is_deleted', config('axxs.active'))
                            ->where('facility_user_id', $admin_id)->get();
            $deviceInfo = Device::where('is_deleted', config('axxs.active'))
                            ->where('id', $request->id)->where('facility_id', $facilityid->id)->first();
        } else {
            /* Role for super admin and specialist who have permission */
            $facilityList = Facility::where('is_deleted', config('axxs.active'))->get();
            $deviceInfo = Device::where('is_deleted', config('axxs.active'))
                            ->where('id', $request->id)->first();
        }
        if (isset($request->id)) {
            if ($deviceInfo) {
                return View('device.adddevice', array('facilityList' => $facilityList, 'deviceInfo' => $deviceInfo));
            } else {
                return redirect(route('device.list'));
            }
        } else {
            return View('device.adddevice', array('facilityList' => $facilityList));
        }
    }

    /**
     * Create a new device instance after a valid registration
     * 
     * @param object Request $request The device details keyed imei, facility_id
     *                                device_provider
     *                                
     * @return json The id of newly registered device keyed id in Response
     * @link   http://theaxxstablet.com/index.php/api/registerdevice
     */
    public function registerDevice(Request $request) {
        $data = $request->input();

        $rules = array(
            'device_id' => 'required|unique:devices|alpha_num|max:15',
            'imei' => 'required|unique:devices',
            'facility_id' => 'required',
            'device_provider' => 'required',
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
            $device_insert = Device::create(
                            [
                                'device_id' => $data['device_id'],
                                'imei' => $data['imei'],
                                'facility_id' => $data['facility_id'],
                                'device_provider' => $data['device_provider'],
                                'is_deleted' => config('axxs.active'),
                            ]
            );

            if (isset($device_insert->id) && !empty($device_insert->id)) {
                 
                if (Auth::user()->hasRole('Facility Staff')) {
                    $staff_log = new StaffLog();

                    $staff_log_create = $staff_log->staffLogInsert(Auth::user()->id, config('axxs.action.create'), config('axxs.page.device'), $device_insert->id, 'Created new device');
                }
                return response()->json(
                                array(
                                    'Status' => \Lang::get('device.device_created'),
                                    'Code' => 201,
                                    'Message' => \Lang::get('common.success'),
                                    'Data' => array('id' => $device_insert->id)
                                )
                );
            } else {
                return response()->json(
                                array(
                                    'Status' => array(\Lang::get('device.device_not_created')),
                                    'Code' => 401,
                                    'Message' => \Lang::get('common.success'),
                                )
                );
            }
        }
    }

    /**
     * Function for load device list UI.
     * 
     * @return Array of device list
     * @link   http://theaxxstablet.com/index.php/devicelist
     */
     public function deviceListUI($facility_id = null) {
            $device_status = '';
            $facility = [];
         if (Auth::user()->hasAnyRole(['Facility Admin', 'Facility Staff'])) {
             if (Auth::user()->hasRole('Facility Admin')) {
                $facility_id = Facility::where('facility_user_id',Auth::user()->id)->first();
                $admin_id = $facility_id->id;
                $device_status = $facility_id->device_status;
            } else {
                $facility_id = Facility::where('facility_user_id',Auth::user()->admin_id)->first();
                $admin_id = $facility_id->id;
            }
            /* Role for facility admin */
            $deviceList = Device::where('is_deleted', config('axxs.active'))
                            ->where('facility_id', $admin_id)->orderBy('device_provider','ASC')->get();
        } else {
            /* Role for family and super admin */
            $facility = Facility::select('facility_name','id')->where('is_deleted',config('axxs.active'))->orderBy('facility_name')->get();
            if (isset($_GET['search']) && !empty($_GET['search'])) {
               $key = $_GET['search'];
               $objDevice = new Device();
               $deviceList = $objDevice->searchDevice($key);
               
           }else{
            $objDevice = new Device();
            $deviceList = $objDevice->getDeviceInfo($facility_id);
           }
        }

        return View('device.devicelist', ['deviceList' => $deviceList, 'deviceStatus' => $device_status,'facility' => $facility ,'facility_id' => $facility_id ]);
    }

    /**
     * Function for load device view.
     * 
     * @param object Request $request The facility details keyed device ID 
     * 
     * @return NULL
     * @link   http://theaxxstablet.com/index.php/viewdevice/{id}
     */
    public function viewDeviceUI(Request $request) {

        if (Auth::user()->hasAnyRole(['Facility Admin', 'Facility Staff'])) {
             if (Auth::user()->hasRole('Facility Admin')) {
                $facility_id = Facility::where('facility_user_id',Auth::user()->id)->first();
                $admin_id = $facility_id->id;
            } else {
                $facility_id = Facility::where('facility_user_id',Auth::user()->admin_id)->first();
                $admin_id = $facility_id->id;
            }
            /* Role for facility admin */
          
            $facilityList = Facility::where('is_deleted', config('axxs.active'))
                            ->where('id', $admin_id)->get();
            $deviceInfo = Device::where('is_deleted', config('axxs.active'))
                            ->where('id', $request->id)->first();
            $deviceInfopresent = Device::where('is_deleted', config('axxs.active'))
                            ->where('id', $request->id)->where('facility_id', $admin_id)->first();
            if ($deviceInfopresent) {
                $deviceInfo = $deviceInfopresent;
            } else {
                return redirect(route('device.list'));
            }
        } else {
            /* Role for family and super admin */
            $facilityList = Facility::where('is_deleted', config('axxs.active'))->get();
        }
        if (isset($request->id)) {
            $deviceInfo = Device::where('id', $request->id)->get()->first();
            return View('device.viewdevice', array('deviceInfo' => $deviceInfo, 'facilityList' => $facilityList));
        }
    }

    /**
     * Function for load Inactive device UI.
     * 
     * @return array or null
     * @link   http://theaxxstablet.com/index.php/deviceinactivelist
     */
    public function deviceInactiveListUI() {
         if (Auth::user()->hasAnyRole(['Facility Admin', 'Facility Staff'])) {
             if (Auth::user()->hasRole('Facility Admin')) {
                $facility_id = Facility::where('facility_user_id',Auth::user()->id)->first();
                $admin_id = $facility_id->id;
            } else {
                $facility_id = Facility::where('facility_user_id',Auth::user()->admin_id)->first();
                $admin_id = $facility_id->id;
            }
          
            if ($facility_id) {
                $deviceList = Device::where('is_deleted', config('axxs.inactive'))
                        ->where('facility_id',$admin_id)
                        ->get();
            } else {
                $deviceList = [];
            }
            return View('device.deviceinactivelist', array('userList' => $deviceList));
        } else {
            $deviceList = Device::where('is_deleted', config('axxs.inactive'))->get();
            return View('device.deviceinactivelist', array('userList' => $deviceList));
        }
    }

    /**
     * Update service details behalf on device id.
     *
     * @param object Request $request The devide details keyed device_id,imei
     *                                facility_id,device_provider 
     * 
     * @return NULL
     * @link   http://theaxxstablet.com/index.php/api/updatedevice
     */
    public function updateDevice(Request $request) {
        $data = $request->input();

        $rules = array(
            'device_id' => 'required|alpha_num|max:15|unique:devices,device_id,'. $data['id'],
            'imei' => 'required|unique:devices,imei,' . $data['id'],
            'facility_id' => 'required',
            'device_provider' => 'required',
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
                'device_id' => $data['device_id'],
                'imei' => $data['imei'],
                'facility_id' => $data['facility_id'],
                'device_provider' => $data['device_provider'],
                'is_deleted' => config('axxs.active'),
            );
            $deviceUpdateInfo = Device::where(array('id' => $request->id))->update($updateData);
            if (isset($deviceUpdateInfo) && !empty($deviceUpdateInfo)) {
                
                 if (Auth::user()->hasRole('Facility Staff')) {
                    $staff_log = new StaffLog();

                    $staff_log_create = $staff_log->staffLogInsert(Auth::user()->id, config('axxs.action.update'), config('axxs.page.device'), $request->id, 'Updated existing device');
                }
                return response()->json(
                                array(
                                    'Status' => \Lang::get('common.success'),
                                    'Code' => 200,
                                    'Message' => \Lang::get('device.device_update'),
                                    'Data' => $deviceUpdateInfo
                                )
                );
            } else {
                return response()->json(
                                array(
                                    'Status' => \Lang::get('common.success'),
                                    'Code' => 400,
                                    'Message' => \Lang::get('device.device_not_update')
                                )
                );
            }
        }
    }

    /**
     * Create function for update inmate details behalf on inmate id.
     *
     * @param object Request $request The service id keyed inmate_id
     *  
     * @return NULL
     */
    public function deleteDevice(Request $request) {
        $data = $request->id;
        $objDevice = new Device();
        $deleteDevice = $objDevice->deleteDevice($data);
        if (isset($deleteDevice) && !empty($deleteDevice)) {
                if (Auth::user()->hasRole('Facility Staff')) {
                    $staff_log = new StaffLog();

                    $staff_log_create = $staff_log->staffLogInsert(Auth::user()->id, config('axxs.action.deactivate'), config('axxs.page.device'), $request->id, 'De-Activated existing device');
                }
            return response()->json(
                            array(
                                'Status' => \Lang::get('common.success'),
                                'Code' => 200,
                                'Message' => \Lang::get('device.device_delete'),
                            )
            );
        } else {
            return response()->json(
                            array(
                                'Status' => \Lang::get('common.success'),
                                'Code' => 400,
                                'Message' => \Lang::get('device.device_not_delete')
                            )
            );
        }
    }

    /**
     * Create function for update device details behalf on device id.
     *
     * @param object Request $request The device id keyed device_id
     *  
     * @return NULL
     */
    public function activeDevice(Request $request) {
        $data = $request->input();

        $rules = array(
            'device_id' => 'required'
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
            $deviceUpdateInfo = Device::where(array('id' => $data['device_id']))->update($updateData);
            if (isset($deviceUpdateInfo) && !empty($deviceUpdateInfo)) {
                if (Auth::user()->hasRole('Facility Staff')) {
                    $staff_log = new StaffLog();

                    $staff_log_create = $staff_log->staffLogInsert(Auth::user()->id, config('axxs.action.activate'), config('axxs.page.device'), $data['device_id'], 'Activated existing device');
                }
                return response()->json(
                                array(
                                    'Status' => \Lang::get('common.success'),
                                    'Code' => 200,
                                    'Message' => \Lang::get('device.device_update'),
                                )
                );
            } else {
                return response()->json(
                                array(
                                    'Status' => \Lang::get('common.success'),
                                    'Code' => 400,
                                    'Message' => \Lang::get('device.device_update_error')
                                )
                );
            }
        }
    }

    /**
     * Create function for update device details behalf on device id.
     *
     * @param object Request $facility_id The device id keyed facility_id
     *  
     * @return NULL if devicelist is 0
     */
    function getDeviceListBehalfFacilityId($facility_id) {
        $deviceList = Device::where('is_deleted', config('axxs.active'))
                        ->where('facility_id', $facility_id)->get();
        if (count($deviceList) > 0) {
            return response()->json(
                            array(
                                'Status' => \Lang::get('common.success'),
                                'Code' => 200,
                                'Data' => $deviceList
                            )
            );
        } else {
            return response()->json(
                            array(
                                'Status' => \Lang::get('common.success'),
                                'Code' => 400,
                                'Data' => []
                            )
            );
        }
    }

    public function getDevicebyFacility(Request $request){
        try {
            $getFacilityId = Facility::where('facility_user_id',$request['facilityUser_id'])->value('id');
            $DeviceList = Device::select('id',DB::raw("CONCAT(device_id, '(', device_provider , ')') AS device_name"))->where('facility_id', $getFacilityId)->where('is_deleted', 0)->get();
            
            return response()->json(
                            array(
                                'Status' => \Lang::get('common.success'),
                                'Code' => 200,
                                'Data' => $DeviceList
                            )
            );
        } catch (Exception $e) {
            return response()->json(array('error'=>$e));
        }
    }

    /**
     *Function to make device on / off
     *
     *@param object Request $request Facility id and Status
     *
     *@return Message 
    */
    public function ChangeDeviceStatus(Request $request){
            try {
                Facility::where('facility_user_id',$request['id'])->update(['device_status' => $request['status']]);
                if ($request['status'] ==1) {
                    $msg = 'Devices are turned On';
                } else{
                    $msg = 'Devices are turned Off';
                }
                return response()->json(
                                array(
                                    'Status' => \Lang::get('common.success'),
                                    'Code' => 200,
                                    'Status' => $msg
                                )
                );
            } catch(\Illuminate\Database\QueryException $ex){ 
                 return response()->json(
                                             array(
                                                 'Status' => \Lang::get('common.success'),
                                                 'Code' => 400,
                                                 'Status' => 'Something Went Wrong'
                                             )
                             );
            } catch (Exception $e) {
                return response()->json(
                                             array(
                                                 'Status' => \Lang::get('common.success'),
                                                 'Code' => 400,
                                                 'Status' => 'Something Went Wrong'
                                             )
                             );
            }
    }

    public function appUpdate(Request $request){
        $data = $request->all();
        try {
            Device::where('id',$data['id'])->update(['update_app'=>$data['value']]);
            return response()->json(
                                array(
                                    'Status' => \Lang::get('common.success'),
                                    'Code' => 200
                                )
                );
        } catch (\Illuminate\Database\QueryException $ex) {
            return response()->json(
                                             array(
                                                 'Status' => \Lang::get('common.success'),
                                                 'Code' => 400,
                                                 'Status' => \Lang::get('common.something_wrong')
                                             )
                             );
        } catch(\Exception $ex) {
                return response()->json(
                                             array(
                                                 'Status' => \Lang::get('common.success'),
                                                 'Code' => 400,
                                                 'Status' =>  \Lang::get('common.something_wrong')
                                             )
                             );
            }
        
    }

    public function checkUpdate(){
        $imei = isset($_GET['mac_address'])?$_GET['mac_address']:null;
        if (isset($imei)) {
            $device = Device::where('imei',$imei)->value('update_app');
            
            if (!isset($device)) {
                return response()->json(
                                             array(
                                                 'Status' => \Lang::get('common.success'),
                                                 'Code' => 404,
                                                 'Message' =>  'Incorrect mac address or device is not registered'
                                             )
                             );
            } else {
                    return response()->json(
                                array(
                                    'Status' => \Lang::get('common.success'),
                                    'Code' => 200,
                                    'Data' => $device
                                )
                );
            }
        }else{
            return response()->json(
                                             array(
                                                 'Status' => \Lang::get('common.error'),
                                                 'Code' => 400,
                                                 'Message' =>  'mac address required'
                                             )
                             );
        }
        
    }
    /**
     *Function to make enable /disable update for all facility device
     *
     *@param object Request $request Facility id and value
     *
     *@return Message 
    */
    public function enableAppupdate($facility_id,$value){
        try {
            Device::where('facility_id',$facility_id)->update(['update_app' => $value]);
            return "Success";
        } catch (\Exception $e) {
                
                return $e->getMessage();
        }
        
    }

    /**
     *Function to get device id
     *
     *@param object Request $request imei and token
     *
     *@return json 
    */
    public function getDeviceid(Request $request){
        $api_token = $request->header('Token');
        if ($api_token == null) {
          return response()->json(array(
                              'Status' => Lang::get('common.success'),
                              'statuscode' => 400,
                              'Message' =>  'Token is required',
                  ));
          
        }
        $validate = Validator::make($request->all(),[ 'device_imei' => 'required']);
        if ($validate->fails()) {
            return response()->json(array(
                        'Status' => Lang::get('common.failure'),
                        'statuscode' => 468,
                        'Data' => Lang::get('common.validation_error'),
                        'Message' => $validate->errors()->all()
            ));
        } else {
            if(config('axxs.api_token') == $api_token ){
                $device_id = Device::where('imei',$request['device_imei'])->pluck('id')->first();
                if ($device_id != null) {
                    return response()->json(array(
                                        'Status' => Lang::get('common.success'),
                                        'statuscode' => 200,
                                        'Data' => array('device_id' => 'Device_'. $device_id),
                                        'Message' =>  'Device ID',
                            ));
                }else{
                    return response()->json(array(
                                        'Status' => Lang::get('common.success'),
                                        'statuscode' => 400,
                                        'Message' =>  'Device is not registered',
                            ));
                }

            }else{
                return response()->json(array(
                                    'Status' => Lang::get('common.success'),
                                    'statuscode' => 400,
                                    'Message' =>  'Invalid  token',
                        ));
            }
        }
    }
}
