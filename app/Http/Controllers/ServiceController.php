<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\Service;
use App\User;
use App\ServiceBooks;
use Auth;
use DB;
use File;
use App\ServiceCategory;
use App\FlatRateServices;
use App\ServicePermission;
use App\DefaultServicePermission;
use Storage;
use Carbon\Carbon;
use App\ServiceChargeByFacility;
use Lang;


/**
 * To manage service and assign to facility and user
 * @category ServiceController
 * @package  Laravel
 * @author   Display Name <username@example.com>
 * @license  http://theaxxstablet.com/index.php/licence.txt BSD Licence
 * @link     http://theaxxstablet.com/index.php
 */
class ServiceController extends Controller {

    /**
     * Create a new service instance after a valid registration
     * 
     * @param object Request $request The service details keyed name,
     *                              base_url, logo_url, type, charge       
     *                                
     * @return json The id of newly registered service keyed id in Response
     */
    public function registerService(Request $request) {
        $data = $request->input();

        
  
        if ($request->hasFile('logo_url')) {

            $image = $request->file('logo_url');
            $logo_url = time() . '.' . $image->getClientOriginalExtension();
            $t = Storage::disk('s3Images')->put('service/'.$logo_url , file_get_contents($image), 'public');
            $data['logo_url'] = Storage::disk('s3Images')->url('service/'.$logo_url);
            /*$destinationPath = public_path('images');
            $image->move($destinationPath, $logo_url);
            $data['logo_url'] = asset('/images') . "/" . $logo_url;*/
            $rules = array(
                'name' => 'required|unique:services,name,NULL,id,service_category_id,' . $data['service_category_id'],
                'base_url' => 'required',
                'type' => 'required',
                'charge' => 'required',
            );
        } else {
            $rules = array(
                'name' => 'required|unique:services,name,NULL,id,service_category_id,' . $data['service_category_id'],
                'base_url' => 'required',
                'logo_url' => 'required',
                'type' => 'required',
                'charge' => 'required',
            );
        }

        $validate = Validator::make($data, $rules);

        if ($validate->fails()) {
            return response()->json(array(
                        'Status' => \Lang::get('common.success'),
                        'Code' => 400,
                        'Message' => $validate->errors()->all(),
            ));
        } else {
            if ($request->hasFile('base_urlfile')) {
                $pdf = $request->file('base_urlfile');
                $base_url_pdf = time() . '.' . $pdf->getClientOriginalExtension();
                $t = Storage::disk('s3Images')->put('pdf/'.$base_url_pdf , file_get_contents($pdf), 'public');
                $data['base_url'] = Storage::disk('s3Images')->url('pdf/'.$base_url_pdf);
                /*$destinationPathbase = public_path('pdf');
                $pdf->move($destinationPathbase, $base_url_pdf);
                $data['base_url'] = asset('/pdf') . "/" . $base_url_pdf;*/
            }
            $service_seq= Service::select('sequence')->orderBy('sequence', 'desc')->first();
            $sequence =  $service_seq->sequence + 1;
            if(Auth::user()->hasRole('Facility Admin')){
                $user_id = Auth::user()->id;
            }else {
                $user_id = 1;
            }
            $service_insert = Service::create([
                        'name' => $data['name'],
                        'service_category_id' => isset($data['service_category_id']) ? $data['service_category_id'] : NULL,
                        'base_url' => $data['base_url'],
                        'logo_url' => $data['logo_url'],
                        'type' => $data['type'],
                        'charge' => $data['charge'],
                        'sequence' => $sequence,
                        'user_id' => $user_id,
                        'auto_logout' =>$data['auto_logout'],
                        'msg' => $data['msg'],
                        'is_deleted' => config('axxs.active'),
            ]);

            if (isset($service_insert->id) && !empty($service_insert->id)) {

                return response()->json(array(
                            'Status' => \Lang::get('service.service_created'),
                            'Code' => 201,
                            'Message' => \Lang::get('common.success'),
                            'Data' => array('id' => $service_insert->id)
                ));
            } else {
                return response()->json(array(
                            'Status' => array(\Lang::get('service.service_not_created')),
                            'Code' => 401,
                            'Message' => \Lang::get('common.success'),
                ));
            }
        }
    }

    /**
     * Function for load service Add UI.
     * 
     * @param object Request $request The service details keyed service ID 
     * 
     * @return NULL
     */
    public function addServiceUI(Request $request) {
        $facilityList = '';
        $service_id  = '';
        if (isset($request->id)) {
            if (Auth::user()->hasRole('Facility Admin')) {
                
                $objService = new Service();
                $serviceInfo = $objService->getfacilityServiceInfo($request->id, config('axxs.active'), Auth::user()->id);
                if ($serviceInfo) {
                    $serviceCategory = ServiceCategory::where('is_deleted', config('axxs.active'))->get();
                    return View('service.addservice', ['serviceInfo' => $serviceInfo, 'serviceCategory' => $serviceCategory, 'facilityList' => $facilityList ,'service_id' => $service_id]);
                } else {
                    return redirect(route('service.list'));
                }
            } else {
                $facilityList = \App\Facility::select('id','facility_name')->where('is_deleted', config('axxs.active'))->get();
                $serviceInfo = Service::where('id', $request->id)->first();
                $service_id = $request->id;
                if ($serviceInfo) {
                    $serviceCategory = ServiceCategory::where('is_deleted', config('axxs.active'))->get();
                    return View('service.addservice', ['serviceInfo' => $serviceInfo, 'serviceCategory' => $serviceCategory, 'facilityList' => $facilityList,'service_id' => $service_id ]);
                } else {
                    return redirect(route('service.list'));
                }
            }
        } else {

            $serviceCategory = ServiceCategory::where('is_deleted', config('axxs.active'))->get();
            return View('service.addservice', ['serviceCategory' => $serviceCategory,'facilityList' => $facilityList,'service_id' => $service_id ]);
        }
    }

    /**
     * Function for load service list UI.
     * 
     * @param object Request $request The service details keyed service ID 
     * 
     * @return NULL
     */
    public function serviceListUI() {
        if (Auth::user()->hasRole('Facility Admin')) {
            $id = Auth::user()->id;

            $objService = new Service();
            $serviceList = $objService->getServiceFacilityListInfo(Auth::user()->id);
            $inmateServiceList = $objService->getInmateServiceInfo(Auth::user()->id);
            $objDefaultService = new DefaultServicePermission();
            $inmateDefaultServiceList = $objDefaultService->getInmateDefaultServiceInfo(Auth::user()->id);
            return View('service.servicelist', ['serviceList' => $serviceList, 'inmateServiceList' => $inmateServiceList , 'inmateDefaultServiceList' => $inmateDefaultServiceList,'id'=>$id]);
        } else {
            $objService = new Service();
            $inmateServiceList = [];
            $serviceList = $objService->getServiceListInfo(config('axxs.active'));
            return View('service.servicelist', ['serviceList' => $serviceList, 'inmateServiceList' => $inmateServiceList]);
        }
    }

    /**
     * Function for load Service view UI.
     * 
     * @param object Request $request The service details keyed service ID.
     * 
     * @return NULL
     */
    public function viewServiceUI(Request $request) {

        if (isset($request->id)) {
            if (Auth::user()->hasRole('Facility Admin')) {
                $objService = new Service();
                $serviceInfo = $objService->getfacilityServiceInfo($request->id, config('axxs.active'), Auth::user()->id);
                if ($serviceInfo) {
                    return View('service.viewservice', ['serviceInfo' => $serviceInfo]);
                } else {
                    return redirect(route('service.list'));
                }
            } else {
                $objService = new Service();
                $serviceInfo = $objService->getServiceInfo($request->id, config('axxs.active'));
                if ($serviceInfo) {
                    return View('service.viewservice', ['serviceInfo' => $serviceInfo]);
                } else {
                    return redirect(route('service.list'));
                }
            }
        }
    }

    /**
     * Function for load service list UI.
     * 
     * @param object Request $request The service details keyed service ID 
     * 
     * @return NULL
     */
    public function serviceInactiveListUI(Request $request) {

        if (Auth::user()->hasRole('Facility Admin')) {
            $serviceList = Service::select('services.*','service_category.name as cat_name')->where('services.is_deleted', config('axxs.inactive'))->where('user_id', Auth::user()->id)->leftjoin('service_category','services.service_category_id','service_category.id')->get();
            return View('service.serviceinactivelist', array('serviceList' => $serviceList));
        } else {
            $serviceList = Service::select('services.*','service_category.name as cat_name')->where('services.is_deleted', config('axxs.inactive'))->where('user_id', 1)->leftjoin('service_category','services.service_category_id','service_category.id')->orderBy('name', 'asc')->get();

            return View('service.serviceinactivelist', array('serviceList' => $serviceList));
        }
    }

    /**
     * Create function for get all service list and his informations.
     * 
     * @param NULL.
     *                                
     * @return json all services in response. 
     */
    public function getAllService() {
        if (Auth::user()) {
            $service_get = Service::get();
            if (isset($service_get) && !empty($service_get)) {
                return response()->json(array(
                            'Status' => \Lang::get('service.service_details'),
                            'Code' => 200,
                            'Message' => \Lang::get('common.success'),
                            'Data' => $service_get
                ));
            }
            return response()->json(array(
                        'Status' => array(\Lang::get('service.service_not_found')),
                        'Code' => 400,
                        'Message' => \Lang::get('common.success'),
            ));
        } return redirect(route('login'));
    }

    /**
     * Get Services behalf on inmate id
     * 
     * @param object Request $request The inmate id keyed inmate_id, 
     *                               
     * @return json all inmate services in response
     */
    function getInmateService(Request $request) {
        try {
            $data = $request->input();
            $inmateServiceObj = new Service();
            $usersObj = new User();

            $rules = array(
                'inmate_id' => 'required',
            );

            $validate = Validator::make($data, $rules);

            if ($validate->fails()) {
                return response()->json(array(
                            'Status' => \Lang::get('common.success'),
                            'Code' => 200,
                            'Message' => $validate->errors()->all(),
                ));
            } else {
                $inmateid = $data['inmate_id'];
                $facility_id = $this->getFacilityID($data['inmate_id']);
                $inmateServiceInfo = $inmateServiceObj->getInmateServiceInfo($data['inmate_id']);
                $serviceall = ServiceCategory::with(['subcategory' => function($cat) use($inmateid) {
                                        $cat->whereHas('permission', function ($query) use ($inmateid) {
                                                    $query->where('inmate_id', $inmateid);
                                                });
                                    }])->whereHas('subcategory.permission', function ($query) use ($inmateid) {
                                    $query->where('inmate_id', $inmateid);
                                })->where('is_deleted', 0)
                                ->orderBy('sequence')
                                ->select('id', DB::raw('0 as service_category_id'), DB::raw('1 as user_id'), 'name', DB::raw('Null as base_url'), 'icon_url as logo_url', DB::raw('0 as type'), DB::raw('0 as charge'), 'is_deleted', 'created_at', 'updated_at', 'name as Service_category_name')
                                ->get()->toArray();


                $serviceallWithFlat = [];
                foreach ($serviceall as $key => $services) {
                    foreach ($services['subcategory'] as $key => $service) {
                        if($services['subcategory'][$key]['keyboardEnabled'] == 1) {
                            $services['subcategory'][$key]['keyboardEnabled'] = true;
                        } else {
                            $services['subcategory'][$key]['keyboardEnabled'] = false;
                        }
                        $services['subcategory'][$key]['flat_charged'] = 0;                   
                        if ($service['type'] == 2) {
//                            $flateServiceInfo = FlatRateServices::where('service_id', $service['id'])->where('user_id', $data['inmate_id'])->exists();
//                            if($flateServiceInfo)
//                            {
//                                $services['subcategory'][$key]['flat_charged'] = 1;
//                            }
                        }
                        $getCustomserviceDetails = ServiceChargeByFacility::where(['facility_id' =>$facility_id, 'service_id' =>  $service['id']])->first();
                        if ($getCustomserviceDetails) {
                            $services['subcategory'][$key]['msg'] = $getCustomserviceDetails['service_msg'];
                        }

                    }
                    $serviceallWithFlat[] = $services;
                }

                $all_category = [];
                /* Json create for androide requierment */

                $all_category['Category'] = $serviceallWithFlat;

              /*
               * old flat charge logic
              foreach ($inmateServiceInfo as $val) {
                  $val->flat_charged = 0;
                  if ($val->Service_category_name == NULL) {
                      if ($val->type == 2) {
                           $flateServiceInfo = FlatRateServices::where('service_id', $val->id)
                                                   ->where('user_id', $data['inmate_id'])->exists();
                              if($flateServiceInfo)
                              {
                                  $val->flat_charged = 1;
                              }
                      }

                      $all_category['Category'][] = $val;
                  }
              }
              */

                //checking if user services are blocked
                $check_blockservice = $this->isServiceblock($inmateid);
                if ($check_blockservice) {
                     $all_category['block_service']['is_block'] = true;
                }else{
                     $all_category['block_service']['is_block'] = false;
                }
                $all_category['block_service']['msg'] = 'All Services are blocked Please contact administrator'; 

                /* as per androide requierment we have add static key */
                if (count($inmateServiceInfo) > 0) {
                    return response()->json(array(
                                'Status' => \Lang::get('service.service_details'),
                                'Code' => 200,
                                'Message' => \Lang::get('common.success'),
                                'Data' => $all_category
                    ));
                } else {
                    return response()->json(array(
                                'Status' => \Lang::get('common.success'),
                                'Code' => 200,
                                'Message' => \Lang::get('service.service_not_found')
                    ));
                }
            }
        } catch (Exception $ex) {
            return errorLog($ex);
        }
    }

    /**
     * Get service details behalf on service id.
     *
     * @param object Request $request The service id keyed service_id and user_id, 
     * 
     * @return Json service information return in response
     */
    public function getService(Request $request) {
        $data = $request->input();

        $rules = array(
            'service_id' => 'required',
            'user_id' => 'required'
        );

        $validate = Validator::make($data, $rules);

        if ($validate->fails()) {
            return response()->json(array(
                        'Status' => \Lang::get('common.success'),
                        'Code' => 400,
                        'Message' => $validate->errors()->all(),
            ));
        } else {
            $serviceInfo = Service::where('id', $data['service_id'])
                            ->where('is_deleted', config('axxs.active'))->get();

            if (count($serviceInfo ) > 0) {
                

                return response()->json(array(
                            'Status' => \Lang::get('common.success'),
                            'Code' => 200,
                            'Message' => \Lang::get('service.service_details'),
                            'Data' => $serviceInfo
                ));
            } else {
                return response()->json(array(
                            'Status' => \Lang::get('common.success'),
                            'Code' => 400,
                            'Message' => \Lang::get('service.service_not_found')
                ));
            }
        }
    }

    /**
     * Create function for update service and his informations.
     *
     * @param object Request $request The service id keyed service_id, 
     * 
     * @return NULL
     * */
    public function updateService(Request $request) {
        $data = $request->input();

        if ($data['facility_id'] !== null) {
            $inuparray = array(
                'service_id' => $data['service_id'],
                'facility_id' => $data['facility_id'],
                'type' => $data['type'],
                'charge' => $data['charge'],
                'service_msg' => $data['msg']
            );
            $chargeup = ServiceChargeByFacility::updateOrCreate(['facility_id' => $data['facility_id'],'service_id' => $data['service_id']],$inuparray);
            $wasRecentlyCreated = $chargeup->wasRecentlyCreated;
            if($wasRecentlyCreated){
              $msg = 'added';
            }else{
               $msg = 'updated';
            }

            return response()->json(array(
                        'Status' => \Lang::get('common.success'),
                        'Code' => 200,
                        'Message' => 'Facility Service Charge'.' '. $msg ,
            ));
        } else{
            
        
        $deleteimage = Service::where('id', $data['service_id'])->first();
        if ($request->hasFile('logo_url')) {
            
            $image_path = $deleteimage->logo_url;
            $s3 = \Storage::disk('s3Images');
            //$existingImagePath = $studentPortfolios[$i]->portfolio_link; // this returns the path of the file stored in the db
            if($s3->exists($image_path)) {
                $s3->delete($image_path);
            }
            /*$pathimg = public_path('images/') . substr($image_path, strrpos($image_path, '/') + 1);

            File::delete($pathimg);*/

            $image = $request->file('logo_url');
            $logo_url = time() . '.' . $image->getClientOriginalExtension();
            $t = Storage::disk('s3Images')->put('service/'.$logo_url , file_get_contents($image), 'public');
            $data['logo_url'] = Storage::disk('s3Images')->url('service/'.$logo_url);
            /*$destinationPath = public_path('images');
            $image->move($destinationPath, $logo_url);*/
            /*$data['logo_url'] = asset('/images') . "/" . $logo_url;*/

            $id = $data['service_id'];
            $rules = array(
                'service_id' => 'required',
                'name' => 'required|unique:services,name,' . $id . ',id,service_category_id,' . $data['service_category_id'],
                'base_url' => 'required',
                'type' => 'required',
                'charge' => 'required',
            );
        } else {
            $id = $data['service_id'];
            $rules = array(
                'service_id' => 'required',
                'name' => 'required|unique:services,name,' . $id . ',id,service_category_id,' . $data['service_category_id'],
                'base_url' => 'required',
                'logo_url' => 'required',
                'type' => 'required',
                'charge' => 'required',
            );
        }

        $validate = Validator::make($data, $rules);

        if ($validate->fails()) {
            return response()->json(array(
                        'Status' => \Lang::get('common.success'),
                        'Code' => 400,
                        'Message' => $validate->errors()->all()
            ));
        } else {
            if ($request->hasFile('base_urlfile')) {
                $image_path_base = $deleteimage->base_url;
                /*$pathimg_url = public_path('pdf/') . substr($image_path_base, strrpos($image_path_base, '/') + 1);

                File::delete($pathimg_url);*/
                $s3 = \Storage::disk('s3Images');
                if($s3->exists($image_path_base)) {
                                $s3->delete($image_path_base);
                            }
                $pdf = $request->file('base_urlfile');
                $base_url_pdf = time() . '.' . $pdf->getClientOriginalExtension();
                $t = Storage::disk('s3Images')->put('pdf/'.$base_url_pdf , file_get_contents($pdf), 'public');
                $data['base_url'] = Storage::disk('s3Images')->url('pdf/'.$base_url_pdf);
                /*$destinationPathbase = public_path('pdf');
                $pdf->move($destinationPathbase, $base_url_pdf);
                $data['base_url'] = asset('/pdf') . "/" . $base_url_pdf;*/
               
            }
            $updateData = array(
                'name' => $data['name'],
                'service_category_id' => isset($data['service_category_id']) ? $data['service_category_id'] : NULL,
                'base_url' => $data['base_url'],
                'logo_url' => $data['logo_url'],
                'type' => $data['type'],
                'charge' => $data['charge'],
                'flat_rate' => $data['flat_rate'],
                'flat_rate_charge' => $data['flat_rate_charge'],
                'keyboardEnabled' => $data['keyboardEnabled'],
                'auto_logout' => $data['auto_logout'],
                'msg' => $data['msg']
            );
            $serviceUpdateInfo = Service::where(array('id' => $data['service_id']))->update($updateData);
            if (isset($serviceUpdateInfo) && !empty($serviceUpdateInfo)) {
                return response()->json(array(
                            'Status' => \Lang::get('common.success'),
                            'Code' => 200,
                            'Message' => \Lang::get('service.service_details'),
                            'Data' => $serviceUpdateInfo
                ));
            } else {
                return response()->json(array(
                            'Status' => \Lang::get('common.success'),
                            'Code' => 400,
                            'Message' => \Lang::get('service.service_not_found')
                ));
            }
        }
        }
    }

    /**
     * Create function for soft delete service details behalf on service id.
     *
     * @param object Request $request The service id keyed service_id. 
     * 
     * @return NULL
     */
    public function deleteService(Request $request) {
        $data = $request->id;
        $objService = new Service();
        $deletService = $objService->deleteService($data);
        if (isset($deletService) && !empty($deletService)) {
            return response()->json(array(
                        'Status' => \Lang::get('common.success'),
                        'Code' => 200,
                        'Message' => \Lang::get('service.service_delete'),
            ));
        } else {
            return response()->json(array(
                        'Status' => \Lang::get('common.success'),
                        'Code' => 400,
                        'Message' => \Lang::get('service.service_delete_error')
            ));
        }
    }

    /**
     * Create function for update service details behalf on service id.
     *
     * @param object Request $request The service id keyed service_id, 
     * 
     * @return NULL
     */
    public function activeService(Request $request) {
        $data = $request->input();

        $rules = array(
            'service_id' => 'required'
        );

        $validate = Validator::make($data, $rules);

        if ($validate->fails()) {
            return response()->json(array(
                        'Status' => \Lang::get('common.success'),
                        'Code' => 400,
                        'Message' => $validate->errors()->all()
            ));
        } else {
            $updateData = array(
                'is_deleted' => config('axxs.active'),
            );
            $serviceUpdateInfo = Service::where(array('id' => $data['service_id']))->update($updateData);
            if (isset($serviceUpdateInfo) && !empty($serviceUpdateInfo)) {

                return response()->json(array(
                            'Status' => \Lang::get('common.success'),
                            'Code' => 200,
                            'Message' => \Lang::get('inmate.inmate_update'),
                ));
            } else {
                return response()->json(array(
                            'Status' => \Lang::get('common.success'),
                            'Code' => 400,
                            'Message' => \Lang::get('service.inmate_update_error')
                ));
            }
        }
    }

    /**
     * Create function for get all book of service list and his informations.
     * 
     * @param NULL.
     *                                
     * @return json all books of service in response. 
     */
    public function getAllServiceBooks() {
        $serviceBooks = ServiceBooks::where('is_deleted', config('axxs.active'))->get();
        if (isset($serviceBooks) && !empty($serviceBooks)) {
            return response()->json(array(
                        'Status' => \Lang::get('serviceBooks.service_books_details'),
                        'Code' => 200,
                        'Message' => \Lang::get('common.success'),
                        'Data' => $serviceBooks
            ));
        } else {
            return response()->json(array(
                        'Status' => array(\Lang::get('serviceBooks.service_books_not_found')),
                        'Code' => 200,
                        'Message' => \Lang::get('common.success'),
                        'Data' => []
            ));
        }
    }

    /**
     * Create function for Registring a category 
     *
     * @param object $request keyed to icon_url,name
     * 
     * @return to js with data
     */
    public function registerCategory(Request $request) {
        $data = $request->input();

        if ($request->hasFile('icon_url')) {
            $image = $request->file('icon_url');
            $imageName  = time() . '.' . $image->getClientOriginalExtension();
            //$destinationPath = public_path('images');
            //$image->move($destinationPath, $icon_url);
            //$data['icon_url'] = asset('/images') . "/" . $icon_url;
            $t = Storage::disk('s3Images')->put('service_category/'.$imageName , file_get_contents($image), 'public');
            $imageName = Storage::disk('s3Images')->url('service_category/'.$imageName);
            $rules = array(
                'name' => 'unique:service_category',
            );
        } else {
            $rules = array(
                'icon_url' => 'required',
                'name' => 'unique:service_category',
            );
        }
        $validate = Validator::make($data, $rules);
        if ($validate->fails()) {
            return response()->json(array(
                        'Status' => \Lang::get('common.success'),
                        'Code' => 400,
                        'Message' => $validate->errors()->all()
            ));
        } else {
            $lastsequence = ServiceCategory::select('sequence')->orderBy('sequence', 'desc')->first();
            $nextseq = $lastsequence->sequence + 1;

            $category_insert = ServiceCategory::create([
                        'sequence' => $nextseq,
                        'name' => $data['name'],
                        'icon_url' => $imageName,
            ]);

            if (isset($category_insert->id) && !empty($category_insert->id)) {

                return response()->json(array(
                            'Status' => \Lang::get('service.category_created'),
                            'Code' => 201,
                            'Message' => \Lang::get('common.success'),
                            'Data' => array('id' => $category_insert->id)
                ));
            } else {
                return response()->json(array(
                            'Status' => array(\Lang::get('service.category_not_created')),
                            'Code' => 401,
                            'Message' => \Lang::get('common.success'),
                ));
            }
        }
    }

    /**
     * Function to showing all the categories to facility/Admin User
     * 
     * @return view
     */
    public function listcategoryUI() {
        $serviceCategory = ServiceCategory::where('is_deleted', config('axxs.active'))
                        ->orderBy('sequence', 'asc')->get();
        return View('categorylist', ['categoryList' => $serviceCategory]);
    }

    /**
     * Function to for updating a category 
     *
     * @param object $request keyed to category id,icon_url,name
     * 
     * @return to js with data
     */
    public function updateCategory(Request $request) {
        $data = $request->input();

        if ($request->hasFile('icon_url')) {
            $deleteimage = ServiceCategory::where('id', $data['id'])->first();
            $image_path = $deleteimage->icon_url;
            $s3 = \Storage::disk('s3Images');
            //$existingImagePath = $studentPortfolios[$i]->portfolio_link; // this returns the path of the file stored in the db
            if($s3->exists($image_path)) {
                $s3->delete($image_path);
            }
            
            /*$pathimg = public_path('images/') . substr($image_path, strrpos($image_path, '/') + 1);
            File::delete($pathimg);*/

            $image = $request->file('icon_url');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $t = Storage::disk('s3Images')->put('service_category/'.$imageName , file_get_contents($image), 'public');
            $imageName = Storage::disk('s3Images')->url('service_category/'.$imageName);
            /*$destinationPath = public_path('images');
            $image->move($destinationPath, $icon_url);
            $data['icon_url'] = asset('/images') . "/" . $icon_url;*/
            $rules = array(
                'id' => 'required',
                'name' => 'unique:service_category,name,' . $data['id'],
            );
        } else {
            $rules = array(
                'id' => 'required',
                'name' => 'unique:service_category,name,' . $data['id'],
            );
        }


        $validate = Validator::make($data, $rules);

        if ($validate->fails()) {
            return response()->json(array(
                        'Status' => \Lang::get('common.success'),
                        'Code' => 400,
                        'Message' => $validate->errors()->all()
            ));
        } else {
            $updateData = array(
                'name' => $data['name'],
                'icon_url' => $imageName,
            );
            $categoryUpdateInfo = ServiceCategory::where(array('id' => $data['id']))->update($updateData);
            if (isset($categoryUpdateInfo) && !empty($categoryUpdateInfo)) {
                return response()->json(array(
                            'Status' => \Lang::get('common.success'),
                            'Code' => 200,
                            'Message' => \Lang::get('service.category_update'),
                            'Data' => $categoryUpdateInfo
                ));
            } else {
                return response()->json(array(
                            'Status' => \Lang::get('common.success'),
                            'Code' => 400,
                            'Message' => \Lang::get('service.category_not_found')
                ));
            }
        }
    }

    /**
     * Function to Deacvivate a category
     *
     * @param object $request keyed to category id.
     * 
     * @return to js with data
     */
    public function deleteCategory(Request $request) {
        $data = $request->id;
        $deleteCategory = ServiceCategory::where('id', $data)->update(['is_deleted' => '1']);
        if (isset($deleteCategory) && !empty($deleteCategory)) {
            return response()->json(array(
                        'Status' => \Lang::get('common.success'),
                        'Code' => 200,
                        'Message' => \Lang::get('service.category_delete'),
            ));
        } else {
            return response()->json(array(
                        'Status' => \Lang::get('common.success'),
                        'Code' => 400,
                        'Message' => \Lang::get('service.category_delete_error')
            ));
        }
    }
    
    /**
     * Function for load service list UI using Category ID.
     * 
     * @param object Request $id The category details keyed category ID 
     * 
     * @return json
     */
    public function categoryServiceList($id) {
        $services= Service::where('service_category_id', $id)->where('is_deleted', 0)->orderBy('sequence')->get();
       return response()->json(array(
                        'Status' => \Lang::get('common.success'),
                        'Code' => 200,
                        'Message' => $services
            ));
    }

    //Reset User Services Function
    /**
     *Function Reset services for all users
    */
    public function resetUserServices(){

        if (Auth::user()->hasRole('Facility Admin')) {
            $facility_services = DefaultServicePermission::select('service_id')->where('facility_id', Auth::user()->id)->get()->toArray();

            $facility_users = User::select('id as users_id')->where('admin_id', Auth::user()->id)->get()->toArray();
            //initialize array
            $inserts = [];
            $delete = [];
            ini_set('memory_limit', '-1');
        if(count($facility_users)>0){
            foreach ($facility_users as $key => $users) {
                    $delete[]=$users['users_id'];
                    
                foreach ($facility_services as $key => $services) {
                                    
                                    $service_id =$services['service_id'];
                                    $inserts[] =[
                                        'service_id' => $service_id,
                                        'inmate_id' => $users['users_id'],
                                        'is_default' => 1,
                                        'created_at'        => Carbon::now(),
                                        'updated_at'        => Carbon::now(),
                                    ];
                        }
            }

            $inserts = collect($inserts);
            $chunks = $inserts->chunk(500);

            DB::table('service_permissions')->whereIn('inmate_id',$delete )->delete();
            foreach ($chunks as $chunk)
            {
               DB::table('service_permissions')->insert($chunk->toArray());
            }
            
        }else{
            return redirect()->back()->with('flash_message', 'There are No Users Added to reset services');
        }
                return redirect()->back()->with('flash_message', 'Services Set to Default for all Users Succcessfully!');;
        }
        

    }

    public function isServiceblock($inmate_id){
        $bs_details = \App\BlockService::where('inmate_id',$inmate_id)->first();
        if ($bs_details != null) {
            $cur_date = date('Y-m-d');
            if ($cur_date == $bs_details->start_date || $cur_date == $bs_details->end_date || $cur_date > $bs_details->start_date && $cur_date < $bs_details->end_date) {
                return true;
            }
                return false;
            
        }
        return false;
    }

    /**
     * Function to get charage by facility.
     * 
     * @param $facility_id , $service_id
     * 
     * @return json
     */
    public function getFacilityserviceCharge(Request $req,$facility_id,$service_id){
        if ($req->ajax()) {

            
            if ($facility_id == 'default') {
                $default_charge = Service::where('id',$service_id)->first();
                return response()->json(array(
                    'Status' => Lang::get('common.success'),
                    'Code' => 200,
                    'Data' => $default_charge,
                    'Message' => Lang::get('facility.facility_service_charge_detail')
                ));
            }
            $facility_charge = ServiceChargeByFacility::where(['facility_id' =>$facility_id, 'service_id' => $service_id])->first();
            if ($facility_charge == null) {
              return response()->json(array(
                'Status' => Lang::get('common.failure'),
                'Code' => 404,
                'Message' => Lang::get('facility.facility_service_charge_not_set')
            ));
          } else {
                  return response()->json(array(
                    'Status' => Lang::get('common.success'),
                    'Code' => 200,
                    'Data' => $facility_charge,
                    'Message' => Lang::get('facility.facility_service_charge_detail')
                ));
          }
      }
  }

  /**
     * Function to get facility_id.
     * 
     * @param $inmate_id 
     * 
     * @return return
     */
  public function getFacilityID($inmate_id){
        return \App\Facility::select('facilitys.id')->join('users','facilitys.facility_user_id','=','users.admin_id')->where('users.id',$inmate_id)->value('id');
  }

}
