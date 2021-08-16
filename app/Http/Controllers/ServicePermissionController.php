<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;
use App\ServicePermission;
use Illuminate\Support\Facades\Redirect;
use Session;
use Lang;
use App\Service;
use App\ServiceCategory;
use App\DefaultServicePermission;

class ServicePermissionController extends Controller {

    /**
     * Create a new service permission instance after a valid registration
     * 
     * @param object Request $request The service permission details keyed inmate_id,
     *                                                  service_list(array).
     *                           
     * @return json The id of newly registered service permission keyed id in Response
     */
    public function registerPermission(Request $request) {
        $data = $request->input();
        $result = '';
        
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

            $inmateDeletePreviousPermissions = ServicePermission::where('inmate_id', $data['inmate_id'])->forcedelete();
            if (isset($data['seviceList']) && !empty($data['seviceList'])) {
                $objServicePermission = new ServicePermission();
                $result = $objServicePermission->registerPermission($data);
            }

            if (($result) || !isset($data['seviceList'])) {
                Session::flash('flash_message', Lang::get('service.permission_changed'));
                return redirect()->back();
            } else {
                Session::flash('flash_message', Lang::get('service.permission_not_changed'));
                return redirect()->back();
            }
        }
    }


 public function defaultPermissionByFacility(Request $request) {
        $data = $request->input();
 
        $result = '';

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
            if(!empty($data['defaultseviceList'])) {
            $objServicePermission = new DefaultServicePermission();
             $result = $objServicePermission->registerDefaultPermission($data);

                Session::flash('flash_message', Lang::get('service.permission_changed'));
                return redirect()->back();
            } 
        else{
                Session::flash('flash_message', Lang::get('service.permission_changed_error'));
                return redirect()->back();

        }

      }  
    }
 




    /**
     * Function for moving category folder one step down
     *
     * @param object Request $id The service id keyed id.
     * 
     * @return NULL
     */
    public function moveCategoryDown($id) {
        $servicecat = \App\ServiceCategory::where('id', $id)->first();
        $servicecatall = \App\ServiceCategory::get();
        $newseq = $servicecat->sequence + 1;

        for ($i = $newseq; $i <= count($servicecatall); $i++) {
            $service_up = \App\ServiceCategory::where('sequence', $i)->where('is_deleted', 0)->first();
            if ($service_up) {
                $newseq = $service_up->sequence;
                break;
            }
        }

        if ($service_up) {
            $service_up->sequence = $servicecat->sequence;
            $service_up->save();
            $servicecat->sequence = $newseq;
            $servicecat->save();
        }

        return Redirect::back();
    }

    /**
     * Function for moving category folder one step up
     *
     * @param object Request $id The service id keyed id.
     * 
     * @return NULL
     */
    public function moveCategoryUp($id) {
        $servicecat = \App\ServiceCategory::where('id', $id)->first();
        $newseq = $servicecat->sequence - 1;
        for ($i = $newseq; $i >= 1; $i--) {
            $service_up = \App\ServiceCategory::where('sequence', $i)->where('is_deleted', 0)->first();
            if ($service_up) {
                $newseq = $service_up->sequence;
                break;
            }
        }
        if ($service_up) {
            $service_up->sequence = $servicecat->sequence;
            $service_up->save();
            $servicecat->sequence = $newseq;
            $servicecat->save();
        }

        return Redirect::back();
    }

    /**
     * Function for moving service folder one step down
     *
     * @param object Request $id The service id keyed id.
     * 
     * @return NULL
     */
    public function moveServiceDown($id) {
        $servicecat = \App\Service::where('id', $id)->first();
        $servicecatall = \App\Service::get();
        $newseq = $servicecat->sequence + 1;

        for ($i = $newseq; $i <= count($servicecatall); $i++) {
            $service_up = \App\Service::where('sequence', $i)
                    ->where('is_deleted', 0)
                    ->where('service_category_id', $servicecat->service_category_id)
                    ->first();
            if ($service_up) {
                $newseq = $service_up->sequence;
                break;
            }
        }
        if ($service_up) {
            $service_up->sequence = $servicecat->sequence;
            $service_up->save();
            $servicecat->sequence = $newseq;
            $servicecat->save();
        }
        $services = \App\Service::where('service_category_id', $servicecat->service_category_id)->where('is_deleted', 0)->orderBy('sequence')->get();
        return response()->json(array(
                    'Status' => \Lang::get('common.success'),
                    'Code' => 200,
                    'Message' => $services
        ));
    }

    /**
     * Function for moving services folder one step up
     *
     * @param object Request $id The service id keyed id.
     * 
     * @return NULL
     */
    public function moveServiceUp($id) {
        $servicecat = \App\Service::where('id', $id)->first();
        $newseq = $servicecat->sequence - 1;
        for ($i = $newseq; $i >= 1; $i--) {
            $service_up = \App\Service::where('sequence', $i)
                    ->where('service_category_id', $servicecat->service_category_id)
                    ->where('is_deleted', 0)
                    ->first();
            if ($service_up) {
                $newseq = $service_up->sequence;
                break;
            }
        }
        if ($service_up) {
            $service_up->sequence = $servicecat->sequence;
            $service_up->save();
            $servicecat->sequence = $newseq;
            $servicecat->save();
        }

        $services = \App\Service::where('service_category_id', $servicecat->service_category_id)->where('is_deleted', 0)->orderBy('sequence')->get();
        return response()->json(array(
                    'Status' => \Lang::get('common.success'),
                    'Code' => 200,
                    'Message' => $services
        ));
    }

    /**
     * Create a new service permission instance after a valid registration
     * 
     * @param object Request $request The service permission details keyed inmate_id,
     *                                                  service_list(array).
     *                           
     * @return json The id of newly registered service permission keyed id in Response
     */
    public function ImportService(Request $request) {
        $data = $request->input();
        if ($request->hasFile('import_file')) {
             $import_file = file_get_contents($_FILES['import_file']['tmp_name']);
             $file_type = $request->file('import_file')->clientExtension();
             if ($file_type !== 'json') {
                 return response()->json(array(
                         'Status' => \Lang::get('common.error'),
                         'Code' => 400,
                         'Message' =>  Lang::get('service.service_invalid_extension'),
                 ));
             }
             $json = json_decode($import_file, true);
             if (!isset($json['seviceList']) || !isset($json['defaultseviceList'])) {
                 return response()->json(array(
                         'Status' => \Lang::get('common.error'),
                         'Code' => 400,
                         'Message' =>  Lang::get('service.sevice_invalid_format'),
                 ));
             }


             $actual_facility_id = \App\Facility::where('facility_user_id',$request['facility_id'])->value('id');
             $service_list = $this->getAndcreateServiceid($json['seviceList']);
             $defaultseviceList = $this->getServiceidByname($json['defaultseviceList']);
             if (!empty($json['custom_charge'])) {
                 $customChargeService = $this->createCustomChargeData($json['custom_charge'],$actual_facility_id);
             }
             
             
            $data['inmate_id'] = $request['facility_id'];
            $data['seviceList'] = $service_list;
            $data['defaultseviceList'] = $defaultseviceList;
        }

        $result = '';
        
        $rules = array(
            'facility_id' => 'required',
        );
        $validate = Validator::make($data, $rules);

        if ($validate->fails()) {
            return response()->json(array(
                        'Status' => \Lang::get('common.success'),
                        'Code' => 400,
                        'Message' => $validate->errors()->all(),
            ));
        } else {

            $inmateDeletePreviousPermissions = ServicePermission::where('inmate_id', $data['inmate_id'])->forcedelete();
            if (isset($data['seviceList']) && !empty($data['seviceList'])) {
                $objServicePermission = new ServicePermission();
                $result = $objServicePermission->registerPermission($data);
            }

            if (($result) || !isset($data['seviceList'])) {
                return response()->json(array(
                        'Status' => \Lang::get('common.success'),
                        'Code' => 200,
                        'Message' =>  Lang::get('service.service_imported'),
                ));
            } else {
                return response()->json(array(
                        'Status' => \Lang::get('common.error'),
                        'Code' => 400,
                        'Message' =>  Lang::get('service.service_not_imported'),
                ));
                
            }
        }
    }

    public function getAndcreateServiceid($service_data){
        
        if (!empty($service_data)) {
            foreach ($service_data as $key => $service) {
            
            if (!isset($service['name'])) {
                continue;
            }
            $id= Service::where('name',$service['name'])->value('id');
            
                $service_category_id =$service['Service_category_name'];
                if ($service['Service_category_name'] != null) {
                    //getservicecategoryid
                   $service_category_id = ServiceCategory::where('name',$service['Service_category_name'])->value('id');
                   //checkifservicecategory is not created on import environment
                                  if ($service_category_id == null) {
                                      //creating new service category
                                    $serviceCatobj = new ServiceCategory();
                                    $serviceCatobj->name = $service['Service_category_name'];
                                    $serviceCatobj->save();
                                    $service_category_id = $serviceCatobj->id;
                                  }
                }
               
                //creating new service
                $ser_data = array(
                    'service_category_id' => $service_category_id,
                    'user_id' => 1,
                    'name' => $service['name'],
                    'base_url' => (isset($service['base_url'])) ? $service['base_url'] : null,
                    'logo_url' => (isset($service['logo_url'])) ? $service['logo_url'] : null,
                    'type' => (isset($service['type'])) ? $service['type'] : null ,
                    'charge' => (isset($service['charge'])) ? $service['charge'] : null,
                    'auto_logout' => (isset($service['auto_logout'])) ? $service['auto_logout'] : null,
                    'msg' => (isset($service['msg'])) ? $service['msg'] : null,
                    'is_deleted' => (isset($service['is_deleted'])) ? $service['is_deleted'] : 0,
                    'sequence' => Service::count()+1


                );
                
                if (!$id) {
                    $new_service = Service::create($ser_data);
                    $id = $new_service['id'];
                }
                


            
            $service_id[] = $id;
        }
        return $service_id;
        } else{
            return null;
        }
    }

    public function getServiceidByname($service_name){
        $services_id = [];
        foreach ($service_name as $key => $sname) {
                $id= Service::where('name',$sname)->value('id');
                $services_id[] = $id;
        }
       
        return $services_id;
    }

    public function createCustomChargeData($custom_service_charge,$facility_id){
        $final_array = [];
        foreach ($custom_service_charge as $key => $csc) {
            
            $s_id= Service::where('name',$csc['name'])->value('id');
            $custom_service_ch['service_id'] = $s_id;
            $custom_service_ch['type'] = $csc['type'];
            $custom_service_ch['charge'] = $csc['charge'];
            $final_array[] = $custom_service_ch;
        }
        foreach ($final_array as $key => $value) {
            $value['facility_id'] = $facility_id;
            $chargeup = \App\ServiceChargeByFacility::updateOrCreate(['facility_id' => $facility_id,'service_id' => $value['service_id']], $value);
        }
        
    }

}
