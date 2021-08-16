<?php

/**
 * Controller generating Excel sheet reports 
 * 
 * PHP version 7.2
 * 
 * @category ExcelController
 * @package  Laravel
 * @author   Display Name <username@example.com>
 * @license  http://theaxxstablet.com/index.php/licence.txt BSD Licence
 * @link     http://theaxxstablet.com/index.php
 */

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Validator;
use Maatwebsite\Excel\Facades\Excel;
use App\InmateActivityHistory;
use App\PaymentInformation;
use App\Device;
use App\Facility;
use App\User;
use App\PurchaseInmate;
use Redirect;
use Auth;
use App\ServicePermission;
use App\EstimateServiceUse;
use DateTime;

/**
 * Controller generating Excel sheet reports 
 * 
 * @category ExcelController
 * @package  Laravel
 * @author   Display Name <username@example.com>
 * @license  http://theaxxstablet.com/index.php/licence.txt BSD Licence
 * @link     http://theaxxstablet.com/index.php
 */
class ExcelController extends Controller {

    public $date;

    /**
     * Function defining the date globally 
     * 
     * @return date
     */
    public function __construct() {
        $this->date = date('m-d-Y');
    }

    /**
     * Function for generating User details With their service on/off 
     * 
     * @param object Request $id of the user to rerive detials
     * 
     * @return report
     */
    public function userReport($id) {
        $users = \App\User::with('inmateFacility')->where('id', $id)->first();
        $objService = new \App\Service();
        $serviceList = $objService->getServiceFacilityListInfo($users->admin_id);
        $list = $objService->getInmateServiceInfo($id);
        $useremailinfo = \App\InmateDetails::where('inmate_id', $id)->first();
        Excel::create(
                $users->first_name . '_' . $this->date . '_report', function ($excel)
                use ($users, $list, $useremailinfo, $serviceList) {
            $excel->sheet(
                    'user', function ($sheet) use ($users, $list, $useremailinfo, $serviceList) {
                $sheet->loadView('reports.user_with_service', array('users' => $users, 'list' => $list, 'useremailinfo' => $useremailinfo, 'serviceList' => $serviceList));
            }
            );
        }
        )->download('xlsx');
    }

    /**
     * Function for downloading all service by facility /Admin
     * 
     * @return report
     */
    public function serviceReport() {
        $objService = new \App\Service();
       if (Auth::user()->hasRole('Facility Admin')) {
            $serviceList = $objService->getServiceFacilityListInfo(Auth::user()->id);
        }
       else if (Auth::user()->hasRole('Facility Staff')) {
            $serviceList = $objService->getServiceFacilityListInfo(Auth::user()->admin_id);
        } else {
            $serviceList = $objService->getServiceListInfo(config('axxs.active'));
        }
        Excel::create(
                'service_report' . $this->date, function ($excel) use ( $serviceList) {
            $excel->sheet(
                    'services', function ($sheet) use ($serviceList) {
                $sheet->loadView('reports.service_with_category', array('serviceList' => $serviceList));
            }
            );
        }
        )->download('xlsx');
    }

    /**
     * Function for generating facility list report with their details 
     * 
     * @return report
     */
    public function facilityReport() {
        $facility = \App\Facility::with('facilityuser')->get();

        Excel::create(
                'facility_report' . $this->date, function ($excel) use ( $facility) {
            $excel->sheet(
                    'facilities', function ($sheet) use ($facility) {
                $sheet->loadView('reports.facility', array('facility' => $facility));
            }
            );
        }
        )->download('xlsx');
    }

    /**
     * Function for generating facility details With their details only
     * 
     * @param object Request $id of the facility to retrieve detials
     * 
     * @return report
     */
    public function singleFacilityReport($id) {
        $facility = \App\Facility::with('facilityuser')->where('id', $id)->first();

        Excel::create(
                $facility->name . '_report' . $this->date, function ($excel) use ( $facility) {
            $excel->sheet(
                    'facility', function ($sheet) use ($facility) {
                $sheet->loadView('reports.single_facility', array('facility' => $facility));
            }
            );
        }
        )->download('xlsx');
    }

    /**
     * Function for generating facility users list details 
     * 
     * @param object Request $id of the facility to retrieve details of its users
     * 
     * @return report
     */
    public function facilityUsersReport($id) {
        $facility = \App\Facility::with('facilityusers')->where('id', $id)->first();

        Excel::create(
                $facility->name . '_userslist_report' . $this->date, function ($excel) use ( $facility) {
            $excel->sheet(
                    'facility', function ($sheet) use ($facility) {
                $sheet->loadView('reports.facility_users', array('facility' => $facility));
            }
            );
        }
        )->download('xlsx');
    }

    /**
     * Function for generating inactive facility users list details 
     * 
     * @return report
     */
    public function inactiveFacilityReport() {
        $facility = \App\Facility::with('facilityuser')->where('is_deleted', 1)->get();

        Excel::create(
                'inactive_facility_report' . $this->date, function ($excel) use ( $facility) {
            $excel->sheet(
                    'inactivefacility', function ($sheet) use ($facility) {
                $sheet->loadView('reports.facility', array('facility' => $facility));
            }
            );
        }
        )->download('xlsx');
    }

    /**
     * Function for generating facility's inactive users list details 
     * 
     * @param object Request $id of the facility to retrieve details of its users
     * 
     * @return report
     */
    public function inactiveUsersFacilityReport($id) {
        $facility = \App\Facility::with('facilityinactiveusers')->where('id', $id)->first();
        $useremailinfo = \App\InmateDetails::get();
        Excel::create(
                $facility->name . 'inactive_users_' . $this->date, function ($excel) use ( $facility, $useremailinfo) {
            $excel->sheet(
                    'inactive_users', function ($sheet) use ($facility, $useremailinfo) {
                $sheet->loadView('reports.inactive_users_perfacility', array('facility' => $facility, 'useremailinfo' => $useremailinfo));
            }
            );
        }
        )->download('xlsx');
    }

    /**
     * Function for generating approved email list of this user 
     * 
     * @param object Request $id of the user to retrieve details
     * 
     * @return report
     */
    public function userEmailReport($id) {
        $user = \App\User::with(
                        ['contactEmailList' => function ($query) {
                                $query->where('is_approved', 1);
                            }, 'inmateFacility']
                )->where('id', $id)->first();

        $useremailinfo = \App\InmateDetails::where('inmate_id', $id)->first();
        $serviceinfo = \App\Service::with(
                        ['ServiceByUser' => function ($query) use ($id) {
                                $query->where('inmate_id', $id);
                            }]
                )->where('name', 'Email')->first();
        Excel::create(
                $user->first_name . 'email_report_' . $this->date, function ($excel) use
                ( $user, $useremailinfo, $serviceinfo) {
            $excel->sheet(
                    'inactive_users', function ($sheet) use
                    ($user, $useremailinfo, $serviceinfo) {
                $sheet->loadView(
                        'reports.user_emails', array('user' => $user, 'useremailinfo' => $useremailinfo, 'serviceinfo' => $serviceinfo)
                );
            }
            );
        }
        )->download('xlsx');
    }

    /**
     * Function for generating approved number list of this user 
     * 
     * @param object Request $id of the user to retrieve details
     * 
     * @return report
     */
    public function userContactReport($id) {
        $user = \App\User::with(
                        ['contactNumberList' => function ($query) {
                                $query->where('is_approved', 1);
                            }, 'inmateFacility']
                )->where('id', $id)->first();

        $serviceinfo = \App\Service::with(
                        ['ServiceByUser' => function ($query) use ($id) {
                                $query->where('inmate_id', $id);
                            }]
                )->where('name', 'Text')->first();
        Excel::create(
                $user->first_name . 'contact_report_' . $this->date, function ($excel) use ( $user, $serviceinfo) {
            $excel->sheet(
                    'inactive_users', function ($sheet) use ($user, $serviceinfo) {
                $sheet->loadView('reports.user_contact', array('user' => $user, 'serviceinfo' => $serviceinfo));
            }
            );
        }
        )->download('xlsx');
    }

    /**
     * Function for generating each service details includes user's details
     * 
     * @param object Request $service_id of the service to retrieve details
     * 
     * @return report
     */
    public function vendorReport($service_id) {
        $service = \App\Service::with('serviceCategory')->where('id', $service_id)->first();
       if (Auth::user()->hasAnyRole(['Facility Admin', 'Facility Staff'])) {
            if(Auth::user()->hasRole('Facility Admin')){
                $admin_id = Auth::user()->id;
            } else {
                $admin_id = Auth::user()->admin_id;
            }
            $user = \App\User::with(
                            ['allInmateByFacility.vendorInfo' =>
                                function ($query) use ($service_id) {
                                    $query->where('service_id', $service_id);
                                }, 'detailFacility', 'getFacilityService' => function ($q) use ($service_id) {
                                    $q->where('service_id', $service_id);
                                }]
                    )->where('role_id', 2)->where('id', $admin_id)->get();
        } else {
            $user = \App\User::with(
                            ['allInmateByFacility.vendorInfo' =>
                                function ($query) use ($service_id) {
                                    $query->where('service_id', $service_id);
                                }, 'detailFacility', 'getFacilityService' => function ($q) use ($service_id) {
                                    $q->where('service_id', $service_id);
                                }]
                    )->where('role_id', 2)->get();
        }
        
        Excel::create(
                $service->name . '_vendor_report_' . $this->date, function ($excel) use ( $user, $service) {
            $excel->sheet(
                    'vendor_report', function ($sheet) use ($user, $service) {
                $sheet->loadView('reports.vendor_report', array('user' => $user, 'service' => $service));
            }
            );
        }
        )->download('xlsx');
    }

    /**
     * Function for generating family list with active /inactive details 
     * 
     * @return report
     */
    public function familyReport() {
        if (Auth::user()->hasRole('Facility Admin')) {
           
            $user = \App\Facility::with('facilityUsersWithFamily')
                            ->where('facility_user_id', Auth::user()->id)->get();
        }
       else if (Auth::user()->hasRole('Facility Staff')){
         
           $user = \App\Facility::with('facilityUsersWithFamily')
                            ->where('facility_user_id', Auth::user()->admin_id)->get(); 
        }
        else {
             
            $user = \App\Facility::with('facilityUsersWithFamily')->get();
        }
   
     
        Excel::create(
                'Facility_Users_family_report_' . $this->date, function ($excel) use ( $user) {
            $excel->sheet(
                    'family_report', function ($sheet) use ($user) {
                $sheet->loadView('reports.user_family', array('user' => $user));
            }
            );
        }
        )->download('xlsx');
    }

    /**
     * Function for downloadable facilty services list which are enabled
     * 
     * @param object Request $facility_id of the facility to retrieve details
     * 
     * @return report
     */
    public function serviceFacilityReport($facility_id) {
        $user = \App\Facility::where('id', $facility_id)->first();
        $objService = new \App\Service();
        $facilityServiceList = $objService->getInmateServiceInfo($user->facility_user_id);
        Excel::create(
                $user->name . '_service_report_' . $this->date, function ($excel) use
                ( $user, $facilityServiceList) {
            $excel->sheet(
                    'facility_Service_report', function ($sheet) use
                    ($user, $facilityServiceList) {
                $sheet->loadView(
                        'reports.facility_service', array(
                    'user' => $user, 'facilityServiceList' => $facilityServiceList)
                );
            }
            );
        }
        )->download('xlsx');
    }
    
    /**
     * Function for downloadable inmate services history list
     * 
     * @param object Request $inmate_id of the facility to retrieve details
     * 
     * @return report
     */
    public function inmateServiceHistoryReport($inmate_id) {

        $user = \App\User::with(['inmateFacility','inmateEmail'])->where('id', $inmate_id)
                ->where('is_deleted', 0)
                ->first();
        $diffrentServiceID = getDiffrentServicesID();
        $objInmateActivity = new InmateActivityHistory();
        $inmate_activity_history = $objInmateActivity->getInmateActivityHistory($inmate_id);
        Excel::create(
                $user->first_name . '_service_report_' . $this->date, function ($excel) use
                ( $user, $diffrentServiceID, $inmate_activity_history) {
            $excel->sheet(
                    'user_ServiceHistory_report', function ($sheet) use
                    ($user, $diffrentServiceID, $inmate_activity_history) {
                $sheet->loadView(
                        'reports.inmate_history', array(
                    'user' => $user, 'diffrentServiceID' => $diffrentServiceID, 'inmate_activity_history' => $inmate_activity_history)
                );
            }
            );
        }
        )->download('xlsx');
    }
    
    /**
     * Function for downloadable inmate services history list
     * 
     * @param object Request $inmate_id of the facility to retrieve details
     * 
     * @return report
     */
    public function allInmateServiceHistoryReport($facility_id,$date) {
        $facility_name = Facility::where('facility_user_id',$facility_id)->value('facility_name');
        
        $users = \App\User::with(['vendorsInfoHistory' => function($q) use ($date){$q->whereDate('start_datetime', $date);}, 'inmateEmail'])
                ->where('admin_id', $facility_id)
                ->where('role_id', 4)
                ->where('is_deleted', 0)
                ->get();
                
                Excel::create(
                'UsersServiceHistory_service_report_' . $date, function ($excel) use
                ( $users, $facility_name, $date) {
            $excel->sheet(
                    'user_ServiceHistory_report', function ($sheet) use
                    ($users,$facility_name, $date) {
                $sheet->loadView(
                        'reports.alluser_history', array(
                    'users' => $users, 'fac_name' => $facility_name,'date' => $date)
                );
            }
            );
        }
        )->download('xlsx');
    }

    /**
     * Function for downloadable inmate services history list
     * 
     * @param object Request $inmate_id of the facility to retrieve details
     * 
     * @return report
     */
    public function monthlyRental(Request $request) {
        $data = $request->input();
       
        if (isset($data['vendor_name']) && !empty($data['vendor_name']) && $data['vendor_name'] != 'ALL' && $data['service_id'] != '') {
            $rules = array(
                'vendor_name' => 'exists:services,name',
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
            }
        }
        switch ($data['report_type']) {
            case 'rental' :
                return $this->rentalReport($data);
                break;
            case 'emailing' :
                 return $this->emailReport($data);
                break;
            case 'texting' :
                return $this->textReport($data);
                break;
            case 'vendor' :
                 return $this->paidServiceReport($data);
                break;
            default :
                return response()->json(
                                array(
                                    'Code' => 400,
                                    'Status' => \Lang::get('common.failure'),
                                    'Message' => 'No report found',
                                )
                );
        }
    }

    /**
     * Function for downloadable inmate services history list
     * 
     * @param object Request $inmate_id of the facility to retrieve details
     * 
     * @return report
     */
    public function rentalReport($data) {
        
        $end = date("Y-m-d",strtotime($data['end_date']));
        $start = date("Y-m-d",strtotime($data['start_date']));

        try {
                       
                $facility = \App\Facility::with(['facilityusers.LoggedReportInfo'=>
                            function ($query) use ( $start,$end) { $query
                                      ->where('created_at', '>=', $start)
                                       ->where('created_at', '<=', $end);
                                     }])->where('id', $data['facility_id'])->get();
              
                    $facilityTotal = 0;
                    foreach($facility as $facilities){
                    $total = 0;
                        foreach($facilities->facilityusers as $facilitysuser){
                           $total =  $facilitysuser->LoggedReportInfo->sum('charges');
                           $facilitysuser->charges = $total;
                           $facilityTotal += $total;

                        }
                    }

            }
            catch (\Exception $e) {
                return $e->getMessage();
        }

            return $this->generateChargeReport($facility, $facilityTotal, 'RentalReport');

        
    }

        /**
     * Function for downloadable inmate services history list
     * 
     * @param object Request $inmate_id of the facility to retrieve details
     * 
     * @return report
     */
    public function emailReport($data) {
       $start =date("Y-m-d",strtotime($data['start_date']));
        $end =date("Y-m-d",strtotime($data['end_date']));
   
              $facility = \App\Facility::with(['facilityusers.EmailTextCharges'=>
                               function ($query) use ( $start,$end) { $query
                                        ->where('created_at', '>=', $start)
                                        ->where('created_at', '<=', $end)
                                       ->where('service_id', '=', 8) ;
                                     }])->where('id',$data['facility_id'])->get();

                $facilityTotal = 0;
                foreach($facility as $facilities){
                    $total = 0;
                        foreach($facilities->facilityusers as $facilitysuser){
                           $total =  $facilitysuser->EmailTextCharges->sum('transaction');
                           $facilitysuser->charges = $total;
                           $facilityTotal += $total;

                        }
                    }

          return $this->generateChargeReport($facility, $facilityTotal, 'EmailReport');
    }


    /**
     * Function for downloadable inmate services history list
     * 
     * @param object Request $inmate_id of the facility to retrieve details
     * 
     * @return report
     */
    public function textReport($data) {
         $start =date("Y-m-d",strtotime($data['start_date']));
         $end =date("Y-m-d",strtotime($data['end_date']));
   
         $facility = \App\Facility::with(['facilityusers.TextCharges'=>
                           function ($query) use ( $start,$end) { $query
                                    ->where('created_at', '>=', $start)
                                    ->where('created_at', '<=', $end)
                                   ->where('service_id', '=', 7);
                            }])->where('id', $data['facility_id'])->get();

            $facilityTotal = 0;
            foreach($facility as $facilities){
                $total = 0;
                    foreach($facilities->facilityusers as $facilitysuser){
                       $total =  $facilitysuser->TextCharges->sum('transaction');
                       $facilitysuser->charges = $total;
                       $facilityTotal += $total;

                    }
            }

        return $this->generateChargeReport($facility, $facilityTotal, 'TextReport');
    }

 /**
     * Function for downloadable inmate services history list
     * 
     * @param object Request $inmate_id of the facility to retrieve details
     * 
     * @return report
     */
    public function paidServiceReport($data) {
      
        $start =date("Y-m-d",strtotime($data['start_date']));
        $end =date("Y-m-d",strtotime($data['end_date']));
        $service = $data['service_id'];

           if(!empty($service)) {
            $facility = \App\Facility::with(['facilityusers.ServiceHistoryDetails'=>
                           function ($query) use ( $start,$end,$service) { $query
                                    ->where('created_at', '>=', $start)
                                    ->where('created_at', '<=', $end) 
                                    ->where('service_id', '=',  $service);
                                 }])->where('id', $data['facility_id'])->get();
        }else{

             $facility = \App\Facility::with(['facilityusers.ServiceHistoryDetails'=>
                                       function ($query) use ( $start,$end) { $query
                                                ->where('created_at', '>=', $start)
                                                ->where('created_at', '<=', $end);
                                             }])->where('id',$data['facility_id'])->get();

        }

         $facilityTotal = 0;
            foreach($facility as $facilities){
                $total = 0;
                    foreach($facilities->facilityusers as $facilitysuser){
                       $total =  $facilitysuser->ServiceHistoryDetails->sum('charges');
                       $facilitysuser->charges = $total;
                       $facilityTotal += $total;

                    }
            } 
             
            return $this->generateChargeReport($facility, $facilityTotal, 'PaidServiceReport');
    }

    /**
     * Function for downloadable inmate services history list
     * 
     * @param object Request $inmate_id of the facility to retrieve details
     * 
     * @return report
     */
    public function generateChargeReport($facility, $facilityTotal, $reportName) {

            $myFile =  Excel::create(
                $reportName. $this->date, function ($excel) use ($facility, $facilityTotal) {
                    $excel->sheet(
                        'MonthlyRentalReport', function ($sheet) use ( $facility, $facilityTotal) {
                            //$sheet->fromArray($users);
                            $sheet->loadView(
                                'reports.rental_report', array(
                                'facility' => $facility, 
                                'facilityTotal' => $facilityTotal)
                            );
                        }
                    );
                }
            );

                $myFile   = $myFile->string('xlsx'); 
                $response = array(
                'name' =>$reportName. $this->date, 
                'file' => "data:application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;base64," . base64_encode($myFile), 
                );

            return response()->json($response);
    }

    /**
     * Function for downloadable inmate fund history
     * 
     * @param object Request $inmate_id of the facility to retrieve details
     * 
     * @return report
     */

    public function inmateFundhistory($id){

        $pymnt_info = PaymentInformation::where('inmate_id',$id)->orderBy('id', 'DESC')->get();
        $inmate_details = User::where('id' , $id)->first();
        $site_id = Facility::where('facility_user_id',$inmate_details['admin_id'])->first();
        $purchase_inmate = PurchaseInmate::where(['siteId' => $site_id['facility_id'],'apin' => $inmate_details['username']])->get();

            
        Excel::create(
                'fund_report' . $this->date, function ($excel) use ( $pymnt_info , $inmate_details , $purchase_inmate) {
            $excel->sheet(
                    'funds', function ($sheet) use ($pymnt_info , $inmate_details, $purchase_inmate) {
                $sheet->loadView('reports.user_fund_report', array('pymnt_info' => $pymnt_info,'inmate' => $inmate_details ,'purchase_inmate' =>$purchase_inmate));
            }
            );
        }
        )->download('xlsx');
    }

    /**
     * Function for downloadable device list report
     * 
     * @param object Request $inmate_id of the facility to retrieve details
     * 
     * @return report
     */

    public function deviceListreport($facility_id = null){
        
        

        if(Auth::check() && Auth::user()->role_id == 2 || Auth::user()->role_id == 1  ){
            $auth_id = Auth::user()->id;
            $get_Facility_id = Facility::select('id')->where('facility_user_id' ,$auth_id)->first();
            
            if ($get_Facility_id['id'] == null && Auth::user()->role_id != 1 ) {
                return redirect()->back()->with('error', 'No Facility associated with your User Id');   
            }
            $is_admin = '';
            if (Auth::user()->role_id == 1) {
                $device = new Device;
                $device =$device->getDeviceInfo($facility_id);
                $is_admin = 1;
            }

            else{
                $device = Device::where('facility_id',$get_Facility_id['id'])->where('is_deleted' , 0)->orderBy('id', 'DESC')->get();
            }
            
        Excel::create(
                'device_list' . $this->date, function ($excel) use ( $device ,$is_admin) {
            $excel->sheet(
                    'funds', function ($sheet) use ($device,$is_admin) {
            $sheet->loadView('reports.device_list_report', array('device' => $device,'is_admin' =>$is_admin));
            }
            );
        }
        )->download('xlsx');

        }
        else{
            return view('errors.401');
        }
        
    }

    /**
     * Function for generating User service report having consumed minutes on each service
     * 
     * @param object Request $id of the inmate to retrieve details
     * 
     * @return report
     */
    public function inmateServicereport($id) {
        $user = \App\User::where('id', $id)->first();
        $objInmateActivityHistory = new \App\InmateActivityHistory();
        $service_history_details =   $objInmateActivityHistory->getservicehistorydetails($id);
        $facility = false;
        Excel::create(
                $user->first_name . '_service_report' . $this->date, function ($excel) use ($user, $service_history_details,$facility) {
            $excel->sheet(
                    'facility', function ($sheet) use ($user, $service_history_details,$facility) {
                $sheet->loadView('reports.user_service_history_details', array('user' => $user,'service' => $service_history_details,'facility' => $facility));
            }
            );
          
        }
        )->download('xlsx');
    }

    /**
     * Function for generating User service report having consumed minutes on each service facility level
     * 
     * @param object Request $id of the inmate to retrieve details
     * 
     * @return report
     */
    public function allInmateServiceHistoryDetails($facility_id,$s_date,$e_date) {
        
        $user = \App\Facility::where('facility_user_id', $facility_id)->first();
        $objInmateActivityHistory = new \App\InmateActivityHistory();
        $service_history_details =   $objInmateActivityHistory->getservicehistorydetailsfacility($facility_id,$s_date,$e_date);
        $facility = true;
        $s_date = $s_date;
        $e_date = $e_date;
        $ufm  = DB::table('users')
                ->join('free_minutes', 'users.id', '=', 'free_minutes.inmate_id')
                ->where('users.admin_id',$facility_id)
                ->whereBetween('free_minutes.updated_at', [$s_date." 00:00:00", $e_date." 23:59:59"]);
        

        $fm = ($ufm->count() * 5 - ($ufm->sum('free_minutes.left_minutes')));
        
        //$sum = \App\ServiceHistory::where('inmate_id',$id)->sum('spent_duration');
        Excel::create(
                $user->first_name . '_service_report' . $s_date.'_'.$e_date, function ($excel) use ($user, $service_history_details,$facility,$s_date,$e_date,$fm) {
            $excel->sheet(
                    'facility', function ($sheet) use ($user, $service_history_details,$facility,$s_date,$e_date,$fm) {
                $sheet->loadView('reports.user_service_history_details', array('user' => $user,'service' => $service_history_details,'facility' => $facility ,'s_date' => $s_date ,'e_date' => $e_date,'fm'=>$fm));
            }
            );
          
        }
        )->download('xlsx');
    }

    /**
     * Function for generating User service report having consumed minutes on each service facility level
     * 
     * @param object Request $id of the inmate to retrieve details
     * 
     * @return report
     */
    public function facilityListreportFAdmin($fa_id) {
        $fadmin = \App\FacilityAdmin::where('id',$fa_id)->first();
        $facility = \App\Facility::where('facility_admin', $fa_id)->get();
        
        //$sum = \App\ServiceHistory::where('inmate_id',$id)->sum('spent_duration');
        Excel::create(
                $fadmin->first_name . '_facility_list' . $this->date, function ($excel) use ($fadmin, $facility) {
            $excel->sheet(
                    'facility', function ($sheet) use ($fadmin,$facility) {
                $sheet->loadView('reports.fa_facilitylist', array('fadmin' => $fadmin,'facility' => $facility));
            }
            );
          
        }
        )->download('xlsx');
    }

    /**
     * Function for generating User service report having consumed minutes on each service facility level
     * 
     * @param object Request $id of the inmate to retrieve details
     * 
     * @return report
     */
    public function estimateServiceuses($facility_id,$date = null) {
        
        $time_slots = array('12:01 am-2:00 am','2:01 am-4:00 am','4:01 am-6:00 am','6:01 am-8:00 am','8:01 am-10:00 am','10:01 am-12:00 pm','12:01 pm-2:00 pm','2:01 pm-4:00 pm','4:01 pm-6:00 pm','6:01 pm-8:00 pm','8:01 pm-10:00 pm','10:01 pm-12:00 pm'
        );
        $fac_user_id = Facility::where('id',$facility_id)->first();
       $facility_service = ServicePermission::select('services.id','services.name')->leftjoin('services','services.id','service_permissions.service_id')->where('inmate_id',$fac_user_id->facility_user_id)->get()->toArray();
        $service_details = EstimateServiceUse::select('estimate_service_uses.inmate_id','estimate_service_uses.date_time','services.name','services.id as service_id')->leftjoin('services','services.id','=','estimate_service_uses.service_id')->where(['facility_id' => $facility_id, 'date' => $date])->get();
        $service = EstimateServiceUse::select('services.name','services.id')->leftjoin('services','services.id','=','estimate_service_uses.service_id')->where('facility_id',$facility_id)->groupBy('service_id')->get()->toArray();
        //dd($service);
        $finalarray = [];
        foreach ($service as $key => $val) {
                $s_data = $service_details->where('service_id',$val['id']);
                $blank_slot= array(0,0,0,0,0,0,0,0,0,0,0,0);
                
              
                foreach ($s_data as $k => $v) {
                   $cmp_time = date('h:i a', strtotime($v['date_time']));

                    foreach ($time_slots as $key => $value) {
                        $time = explode('-', $value);
                        $s_time = DateTime::createFromFormat('H:i a', $time['0']);
                        $e_time = DateTime::createFromFormat('H:i a', $time['1']);
                        $c_time = DateTime::createFromFormat('H:i a', $cmp_time);

                        if ($c_time > $s_time && $c_time < $e_time)
                        {   
                           $blank_slot[$key] = $blank_slot[$key]+1;
                         }

                   }
                }
                $data['service_id']= $val['id'];
                $data['user_data'] = $blank_slot;
                $finalarray[] =$data;
        }
        //dd($finalarray);
        $blank_timeslot= array(0,1,2,3,4,5,6,7,8,9,10,11);
        $services_detail = $service_details->map(function($service_data) use ($time_slots,$blank_timeslot){
                        $cmp_time = date('h:i a', strtotime($service_data['date_time']));
                        
                        $time_key = '';

                        foreach ($time_slots as $key => $value) {
                            $time = explode('-', $value);
                            $s_time = DateTime::createFromFormat('H:i a', $time['0']);
                            $e_time = DateTime::createFromFormat('H:i a', $time['1']);
                            $c_time = DateTime::createFromFormat('H:i a', $cmp_time);

                            if ($c_time > $s_time && $c_time < $e_time)
                            {   
                               $time_key = $key;
                             }

                       }
                       $service_detail['users'] =   count(explode(',',$service_data['inmate_id']));
                       $service_detail['time_order'] = $time_key;
                       $service_detail['service_name'] = $service_data['name'];
                       $service_detail['service_id'] = $service_data['id'];
                       $service_detail['timeslot'] = $blank_timeslot;

                       return $service_detail;
                
        });
        //dd($services_detail,$time_slots, $blank_timeslot);

       /* return view('reports.users_on_service', array('time_slots' => $time_slots,'service_details' => $services_detail,'blank_timeslot'=>$blank_timeslot,'facility_service'=>$facility_service,'service'=>$service,'user_data'=>$finalarray));*/

        Excel::create(
                $date. '_facility_list' . $this->date, function ($excel) use ($time_slots,$services_detail,$blank_timeslot,$facility_service,$service,$finalarray) {
            $excel->sheet(
                    'facility', function ($sheet) use ($time_slots,$services_detail,$blank_timeslot,$facility_service,$service,$finalarray) {
                $sheet->loadView('reports.users_on_service', array('time_slots' => $time_slots,'service_details' => $services_detail,'blank_timeslot'=>$blank_timeslot,'facility_service'=>$facility_service,'service'=>$service,'user_data'=>$finalarray));
            }
            );
          
        }
        )->download('xlsx');
    }
}
