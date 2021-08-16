<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Validator;
use App\Facility;
use App\Service;
use App\User;
use App\InmateConfiguration;
use Yajra\Datatables\Facades\Datatables;
use Session;
class SuperadminController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
    */
    public function __construct()
    {

    }
    
    
    /**
     * Log out the patient.
     *
     * @return Response
     */
    public function Logout() {
        if (Auth::guard()->Check()) {
            auth()->logout();
            Auth::guard()->logout();
            return redirect('login');
        } else {
            return redirect('login');
        }
    }
    
    /**
     * Function for super admin dashboard UI.
     * 
     * @param object Request $request The inmate details keyed inmate ID 
     * 
     * @return NULL
    */
    public function index () {
        if (Auth::guard()->Check()) {
            loggedInUser();
            $userList = User::where('is_deleted', config('axxs.active'))
                            ->where('inmate_id', '!=', 0)->get();
            $facilityList = Facility::where('is_deleted', config('axxs.active'))->get();
            $freeServiceInfo = Service::where('type', 0)
                                        ->where('is_deleted', config('axxs.active'))->get();
            $paidServiceInfo = Service::where('type', 1)
                                        ->where('is_deleted', config('axxs.active'))->get();
            $tos = \App\InmateConfiguration::where('id',9)->first();
            $logout_obj = \App\InmateConfiguration::where('key','admin_logout_time')->first();
            $lgtime = isset($logout_obj->content) ? $logout_obj->content : null ;
            //\Cookie::queue('logout_time',$lgtime, 15000);

            
            return View('dashboard', ['facilityList' => $facilityList, 'userList' => $userList, 'freeServiceInfo' => $freeServiceInfo, 'paidServiceInfo' => $paidServiceInfo ,'tos' => $tos, 'logout_time' =>$lgtime]);
        } else {
            return redirect('login');
        } 
            
    }
    
    /**
     * Function for super admin dashboard UI.
     * 
     * @param object Request $request The inmate details keyed inmate ID 
     * 
     * @return NULL
    */
    public function configurationUI ()
    {   
        if (Auth::guard()->Check()) {
            loggedInUser();
            $configurationDetails = InmateConfiguration::get();
            $pro_api_url = $configurationDetails->where('key','pro_api_url')->first();
            $qa_api_url = $configurationDetails->where('key','qa_api_url')->first();
            $test_api_url = $configurationDetails->where('key','test_api_url')->first();
            
            return View('configuration', ['configurationDetails' => $configurationDetails,'pro_api_url' => $pro_api_url, 'qa_api_url' => $qa_api_url, 'test_api_url' => $test_api_url]);
        } else {
            return redirect('login');
        }    
    }
    
    /**
     * Create a function for auto logged time configure for tablet
     * 
     * @param object Request $request 
     *                                
     * @return json
     */
    public function registerConfiguration (Request $request)
    {
        $data = $request->input();
        
        $rules = array(
            'id' => 'required',
            'value' => 'required|numeric',
        );
        
        $validate = Validator::make($data, $rules);

        if ($validate->fails()) {
            return response()->json(array(
                'Code' => 400,
                'Status' => \Lang::get('common.success'),
                'Message' => $validate->errors()->all(),
            ));
        } else {
            $objImateConfiguration = new InmateConfiguration();
            $configuration_insert = $objImateConfiguration->updateConfiguration($data['id'], $data['value']);
            if (isset($configuration_insert) && !empty($configuration_insert)) {
                return response()->json(array(
                    'Code' => 200,
                    'Message' => \Lang::get('common.success'),
                    'Status' => \Lang::get('inmate_configuration.inmate_configuration_created'),
                ));
            } 
            return response()->json(array(
            'Code' => 401,
            'Message' => \Lang::get('common.success'), 
            'Status' => array(\Lang::get('inmate_configuration.inmate_configuration_not_created')),
            ));
        }
    }
    
    /**
     * Create a function for free tablet time cionfigure
     * 
     * @param object Request $request 
     *                                
     * @return json
     */
    public function registerTabletfreeTimeTabletChargeConfiguration (Request $request)
    {
        $data = $request->input();
        
        $rules = array(
            'freeTimeID' => 'required',
            'freeTimeValue' => 'required',
            'tabletChargeID' => 'required',
            'tabletChargeValue' => 'required'
        );
        
        $validate = Validator::make($data, $rules);

        if ($validate->fails()) {
            return response()->json(array(
                'Code' => 400,
                'Status' => \Lang::get('common.success'),
                'Message' => $validate->errors()->all(),
            ));
        } else {
            $objImateConfiguration = new InmateConfiguration();
            if($data['freeTimeValue']) {
                $configuration_insert = $objImateConfiguration->updateConfiguration($data['freeTimeID'], $data['freeTimeValue']);
            } if($data['tabletChargeValue']) {
                $configuration_insert = $objImateConfiguration->updateConfiguration($data['tabletChargeID'], $data['tabletChargeValue']);
            }
            if (isset($data['tabletChargeValue']) && !empty($data['tabletChargeValue'])) {
                return response()->json(array(
                    'Code' => 200,
                    'Message' => \Lang::get('common.success'),
                    'Status' => \Lang::get('inmate_configuration.inmate_configuration_created'),
                ));
            } 
            return response()->json(array(
            'Code' => 401,
            'Message' => \Lang::get('common.success'), 
            'Status' => array(\Lang::get('inmate_configuration.inmate_configuration_not_created')),
            ));
        }
    }
    
     /**
     * Create a function for downlaod apk link provide 
     * 
     * @param object Request $request
     *                                
     * @return json
     */
    public function downloadAPKLink (Request $request)
    {
        $filename = 'TheAxxsTablet_v51.apk';
        return response()->download(public_path().'/assets/apk/'. $filename);
    }
    
     public function downloadAPKLink1 (Request $request)
    {
        $filename = 'TheAxxsTablet-5.7.apk';
        return response()->download(public_path().'/assets/apk/'. $filename);
    }
    
       public function downloadAPKLink2 (Request $request)
    {
        $filename = 'TheAxxsTablet-5.7.apk';
        return response()->download(public_path().'/assets/apk/'. $filename);
    }
    
        public function downloadAPKLink3 (Request $request)
    {
        $filename = 'TheAxxsTablet-6.2.apk';
        return response()->download(public_path().'/assets/apk/'. $filename);
    }
    
    /**
     * Create a function for downlaod apk link provide 
     * 
     * @param object Request $request
     *                                
     * @return json
     */
    public function downloadUserManualLinkBackroom (Request $request)
    {
        $filename = 'AxxSTablet_Backroom_UserManual.docx';
        return response()->download(public_path().'/assets/userManual/'. $filename);
    }
    
     /**
     * Create a function for downlaod apk link provide 
     * 
     * @param object Request $request
     *                                
     * @return json
     */
    public function downloadUserManualTwoWayCommunicationBackroom (Request $request)
    {
        $filename = 'AxxSTablet_Backroom_UserManual_TwoWayCommunication.docx';
        return response()->download(public_path().'/assets/userManual/'. $filename);
    }
    
    /**
     * Create a function for downlaod apk link provide 
     * 
     * @param object Request $request
     *                                
     * @return json
     */
    public function downloadUserManualLinkAndroide (Request $request)
    {
        $filename = 'AxxSTablet_Android_UserManual.doc';
        return response()->download(public_path().'/assets/userManual/'. $filename);
    }

    /**
     * Create a function for updating negative balance 
     * 
     * @param object Request $request
     *                                
     * @return json
     */
    public function updateNegativebalance(Request $request){
        $data=$request->all();
        
         $objConf = new InmateConfiguration();
         $objConf->updateConfiguration($data['id'],$data['value']);
         return response()->json(array(
             'Code' => 200,
             'Message' => \Lang::get('common.success'),
             'Status' => 'Configuration Updated',
         ));
     }

     /**
     * Create a function for updating welcome message 
     * 
     * @param object Request $request
     *                                
     * @return json
     */
    public function updateWelcomemsg(Request $request){
        $data=$request->all();
        
         $objConf =InmateConfiguration::where('id','=',$data['id'])->update(['is_active' => $data['welcomemsg_status'] ,'content' => $data['msgcontent']]);
         return response()->json(array(
             'Code' => 200,
             'Message' => \Lang::get('common.success'),
             'Status' => 'Configuration Updated',
         ));
     }

     /**
     * Create a function for updating terms of servcic 
     * 
     * @param object Request $request
     *                                
     * @return redirect back with message
     */
    public function updateTerms(Request $request){
        $data=$request->all();
        try {
             $objConf =InmateConfiguration::where('id','=',$data['id'])->update(['content' => $data['tos']]);

         } catch (\Illuminate\Database\QueryException  $e) {
                            Session::flash('error', "Something Went Wrong !");
                            return redirect()->back();
         } catch (\Exception $e) {
                        Session::flash('error', "Something Went Wrong !");
                        return redirect()->back();
        }
        Session::flash('message', "Terms & Conditions Updated Succesfully !");

        return redirect()->back();   
     }

     /**
     * function for updating logout time 
     * 
     * @param object Request $request
     *                                
     * @return redirect back with message
     */
    public function updateLgtime(Request $request){
        $data=$request->all();
        
        try {
             $objConf =InmateConfiguration::where('id','=',$data['id'])->update(['content' => $data['logouttime']]);

         } catch (\Illuminate\Database\QueryException  $e) {
                            Session::flash('error', "Something Went Wrong !");
                            return redirect()->back();
         } catch (\Exception $e) {
                        Session::flash('error', "Something Went Wrong !");
                        return redirect()->back();
        }
        Session::flash('message', "Logout Time Updated Succesfully !");
        return response()->json(array(
             'Code' => 200,
             'Message' => \Lang::get('common.success'),
             'Status' => 'Configuration Updated',
         ));  
     }

     public function updateDeviceoff(Request $request){
      try {
              $obj_update = InmateConfiguration::where('id',$request['id'])->update(['content' => $request['status']]);
         } catch (\Illuminate\Database\QueryException  $e) {
             //echo $e->getMessage();
             return response()->json(array(
                     'Code' => 400,
                     'Message' => \Lang::get('common.error'),
                     'Status' => 'Something went wrong',
                 ));
         } catch (\Exception $e) {
            
                        return response()->json(array(
                                             'Code' => 200,
                                             'Message' => \Lang::get('common.error'),
                                             'Status' => 'Something went wrong',
                                         )); 
        }
        if ($request['status'] == 1) {
             if ($request['tag'] == 'email') {
                $msg = 'Automatic Email Creation Activated';
            }else if($request['tag'] == 'device'){
                $msg = 'All Device Turned on';
            }else{
                $msg = 'Tablet Charges are Turned On';
            }
         } else {
             if ($request['tag'] == 'email') {
                $msg = 'Automatic Email Creation Deactivated';
            }else if($request['tag'] == 'email'){
                $msg = 'All Device Turned off';
            }else{
                 $msg = 'Tablet Charges are Turned Off';
            }
         }
                return response()->json(array(
                     'Code' => 200,
                     'Message' => \Lang::get('common.success'),
                     'Status' => $msg,
                 ));  
     }

     public function traceUserlogin($facility_id = null){
        $user = Auth::user();
        if (isset($user) && $user->hasRole('Super Admin')) {
            $facility = Facility::select('facility_name','id')->where('is_deleted',config('axxs.active'))->orderBy('facility_name')->get();
            $facilityUser = false;
        } else if ( isset($user) && $user->hasAnyRole(['Facility Staff', 'Facility Admin', 'Facility Staff'])){
            $facility = [$user->staffFacility];
            $facilityUser = true;
            $facility_id = $facility[0]->id;
        }

        return view('traceuser',['facility' => $facility, 'facility_id' => $facility_id, 'facilityUser' => $facilityUser]);
        
     }

     public function getUserloginlist($facility_id = null, $full = false){
         $user = Auth::user();

         if (isset($user) && isset($user->staffFacility) && !$user->hasRole('Super Admin') && $facility_id != $user->staffFacility->id) {
             abort(403);
         }

         $get_login = \App\InmateLoggedHistory::select('devices.device_id',
             'inmate_logged_history.device_id as imei',
             'inmate_logged_history.start_date_time',
             'inmate_logged_history.inmate_id',
             'users.first_name',
             'users.middle_name',
             'users.last_name')
             ->join('users', 'inmate_logged_history.inmate_id', 'users.id')
             ->join('devices', 'inmate_logged_history.device_id', 'devices.imei')
             ->where('devices.facility_id', $facility_id)
             ->whereRaw('inmate_logged_history.id IN
                (SELECT ilh1.id FROM inmate_logged_history as ilh1 
                	INNER JOIN (SELECT i.device_id, MAX(i.start_date_time) as start_date_time FROM inmate_logged_history as i
                		JOIN devices as d ON i.device_id = d.imei
                		WHERE i.device_id IS NOT NULL
                		AND i.inmate_id IS NOT NULL
                		AND i.start_date_time IS NOT NULL
                		AND d.facility_id = '.$facility_id.'
                		GROUP BY device_id)
                	as ilh2 ON ilh1.device_id = ilh2.device_id AND ilh1.start_date_time = ilh2.start_date_time)')
             ->get();
        
        return Datatables::of($get_login)->blacklist(['username', 'created_at'])->make(true);;
     }

     /**
     * function for blocking user service
     * 
     * @param $inmate_id,$start_date,$end_date
     *                                
     * @return json
     */
     public function blockUserservice(Request $request){
        $matchThese = array('inmate_id'=>$request->inmate_id);
        try {
            \App\BlockService::updateOrCreate($matchThese,$request->all());
            $data = array(
                'msg' => 'Users Services are blocked from' .' '.$request->start_date.' to'.' '.$request->end_date,
                'status' => 'success'
            );
            return response()->json($data);
        } catch (\Exception $e) {
            return response()->json([
                'msg' => \Lang::get('common.something_wrong'),
                'status' => 'error'
            ]);
        }
        
     }

     public function getBlockserviceDetails(Request $request,$id){
        if ($request->ajax()) {
            return response()->json([
                        'data' => \App\BlockService::where('inmate_id',$id)->first(),
                        'msg' => 'block service details',
                        'status' => 'success'
                    ]);
        }
        
     }

      /**
      *  function for updating low balance message 
      * 
      * @param object Request $request
      *                                
      * @return json
      */
     public function updateLowbalanceMsg(Request $request){
         $data=$request->all();
         
          $objConf =InmateConfiguration::where('id','=',$data['id'])->update(['content' => $data['low_bl_msg']]);
          return response()->json(array(
              'Code' => 200,
              'Message' => \Lang::get('common.success'),
              'Status' => \Lang::get('inmate.config_updated'),
          ));
      }

      /**
      * function for updating free minutes comsume message 
      * 
      * @param object Request $request
      *                                
      * @return json
      */
     public function updateFreeminExpmsg(Request $request){
         $data=$request->all();
          $objConf =InmateConfiguration::where('id','=',$data['id'])->update(['content' => $data['exp_freemin_msg']]);
          return response()->json(array(
              'Code' => 200,
              'Message' => \Lang::get('common.success'),
              'Status' => \Lang::get('inmate.config_updated'),
          ));
      } 

       /**
       *  function for updating api url 
       * 
       * @param object Request $request
       *                                
       * @return json
       */
      public function updateAPIurl(Request $request){
          $data =$request->all();
           $objConfs =InmateConfiguration::where('id','=',$data['pro_id'])->update(['content' => $data['pro_api_url']]);
           $objConf =InmateConfiguration::where('id','=',$data['qa_id'])->update(['content' => $data['qa_api_url']]);
           $objConfg =InmateConfiguration::where('id','=',$data['test_id'])->update(['content' => $data['test_api_url']]);
           
           return response()->json(array(
               'Code' => 200,
               'Message' => \Lang::get('common.success'),
               'Status' => \Lang::get('inmate.config_updated'),
           ));
       }

       /**
       *  function for gettting api url 
       * 
       * @param object Request $request
       *                                
       * @return json
       */
      public function getAPIurl(Request $request){
          $api_token = $request->header('Token');
          if ($api_token == null) {
            return response()->json(array(
                                'Status' => \Lang::get('common.success'),
                                'statuscode' => 400,
                                'Message' =>  'Token is required',
            ));
            
          }
          if(config('axxs.api_token') == $api_token ){
            $api_url = InmateConfiguration::whereIn('key',['pro_api_url','qa_api_url','test_api_url','env'])->select('key','content')->get()->toArray();

            $data = array(
                'Status' => \Lang::get('common.success'),
                'statuscode' => 200,
                'Data' => array('pro_api_url' =>$api_url[0]['content'],'qa_api_url' =>$api_url[1]['content'],'test_api_url' =>$api_url[3]['content']),
                'Message' => \Lang::get('inmate.api_url_details'),
            );
            return response()->json($data, 200);
            
          } else {
            return response()->json(
                array(
                    'Status' => \Lang::get('common.bad_request'),
                    'statuscode' => 400,
                    'Message' => \Lang::get('common.invalid_token') )
                , 400);
        } 
    }  


}