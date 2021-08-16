<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Validator;
use App\FacilityAdmin;
use App\Facility;
use App\InmateConfiguration;
use App\Service;
use App\Device;
use App\User;
use App\Role;
use Yajra\Datatables\Facades\Datatables;
use Spatie\Permission\Models\Role as Roles;

class FacilityAdminController extends Controller
{   
    /**
     * Function for dashboard UI.
     * 
     * @return NULL
     */
    public function fadminDashboard() {
        
        if (Auth::user()) {
            if (Auth::user()->hasAnyRole(['Facility Administrator'])) {
                $auth_id = Auth::id();
                $fa_admin = FacilityAdmin::where('fa_user_id',Auth::user()->id)->first();
                $userList = User::where('is_deleted', config('axxs.active'))
                                ->where('role_id', config('axxs.inmateadmin'))
                                ->where('admin_id', $auth_id)->get();
                $freeServiceInfo = Service::where('type', config('axxs.active'))
                                ->where('is_deleted', config('axxs.active'))->get();
                $paidServiceInfo = Service::where('type', 1)
                                ->where('is_deleted', config('axxs.active'))->get();
                $deviceInfo = [''];
                $maxcontactInfo= 10;
                $device_status = 1;
                $roleInfo = Role::where('is_default', config('axxs.active'))
                        ->find(Auth::user()->role_id);

                return View('fadmindashboard',array('fa_admin' => $fa_admin, 'freeServiceInfo' => $freeServiceInfo, 'paidServiceInfo' => $paidServiceInfo, 'roleInfo' => $roleInfo, 'deviceInfo' => $deviceInfo, 'maxcontactInfo' => $maxcontactInfo, 'auth_id' => $auth_id,'deviceStatus' => $device_status));
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
    public function addFacilityadminUI(Request $request) 
    {   
        
        if (isset($request->id)) {
            $obj_facility = new FacilityAdmin();
            $facilityInfo = $obj_facility->getFacilityAllInfor($request->id);
            if ($facilityInfo) {
                return View('fadmin.addfadmin', array('facilityInfo' => $facilityInfo));
            } else {
                return redirect(route('facility.list'));
            }
        } else {
            $fac_id = rand(10000, 99999);
            return View('fadmin.addfadmin',compact('fac_id'));
        }
    }

    /**
     * Create function for list facility UI.
     * 
     * @return NULL
     */
    public function facilityAdminlistUI() 
    {   
        return view('fadmin.fadminlist');
    }

    /**
     * Function for load facility list UI.
     * 
     * @return NULL
     */
    public function facilityAdmininactiveListUI() 
    {

        $facilityList = Facility::where('is_deleted', config('axxs.inactive'))->get();
        return View('fadmin.fadmininactivelist', array('facilityList' => $facilityList));
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
    public function registerFacilityadmin(Request $request) 
    {
        $data = $request->input();
        $rules = array(
            'fa_id' => 'required|unique:facility_admins|max:11',
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email|unique:facility_admins',
            'phone' => 'unique:facility_admins',
            'total_facility' => 'required',
            'username' => 'required|unique:users',
            'password' => 'required',
            'fa_name' => 'required|unique:facility_admins',
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
                $user_insert = User::create(
                    [
                                    'username' => $data['username'],
                                    'password' => bcrypt($data['password']),
                                    'first_name' => $data['first_name'],
                                    'last_name' => $data['last_name'],
                                    'role_id' => 11,
                                    'status' => 0,
                                    'is_deleted' => config('axxs.active'),
                                ]);


                $role_r = Roles::where('id', 11)->firstOrFail();
                $user_insert->assignRole($role_r); //Assigning role to user

                if (isset($user_insert->id) && !empty($user_insert->id)) {
                    $fa_users_detail_insert = FacilityAdmin::create(
                        [
                                        'fa_id' => $data['fa_id'],
                                        'fa_user_id' => $user_insert->id,
                                        'name' => $data['first_name'] . ' ' . $data['last_name'],
                                        'first_name' => $data['first_name'],
                                        'last_name' => $data['last_name'],
                                        'email' => $data['email'],
                                        'phone' => isset($data['phone']) ? $data['phone'] : '',
                                        'state' => isset($data['state']) ? $data['state'] : '',
                                        'zip' => isset($data['zip']) ? $data['zip'] : null,
                                        'city' => isset($data['city']) ? $data['city'] : null,
                                        'address_line' => isset($data['address_line']) ? $data['address_line'] : null,
                                        'company_id' => isset($data['fa_company']) ? $data['fa_company'] : null,
                                        'total_facility' => isset($data['total_facility']) ? $data['total_facility'] : '',
                                        'is_deleted' => config('axxs.active'),
                                        'fa_name' =>  $data['fa_name'],
                                        'location' => $data['location']
                                       
                                    ]
                    );

                    if (isset($fa_users_detail_insert->id) && !empty($fa_users_detail_insert->id)) {
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
            } 
    }

    public function facilityAdminlist(){
    	$get_movie = FacilityAdmin::with('company')->where('is_deleted',0);
        
    	return Datatables::of($get_movie)
    	->addIndexColumn()
    	->addColumn('action', function ($get_movie) {
    		return '<a href="'.route('fadmin.add',$get_movie->id).'"><i class="fa fa-pencil" data-toggle="tooltip" title="Edit"></i>&nbsp;&nbsp;</a> <a onClick="assignFacility('.$get_movie->id.')" style="cursor:pointer"><i class="fa fa-plus-circle" data-toggle="tooltip" title="Assign Facility"></i>&nbsp;&nbsp;</a>
                   <a href="JavaScript:Void(0)" onClick="deletefa('.$get_movie->id.')"><i class="glyphicon glyphicon-trash" data-toggle="tooltip" title="Delete"></i></a>&nbsp;&nbsp;</a>
                   <a href="'.route('fadmin.facilitylist',$get_movie->id).'"><i class="fa fa-file-excel-o" data-toggle="tooltip" title="Download Facility List"></i></a>';
                   })
        ->addColumn('company', function($get_movie){
            return $get_movie->company['name'];
        })
    	->blacklist(['DT_RowIndex','action','company'])
    	->make(true);
    }

    public function getFacilityadmin($id){
    	$fa_data = FacilityAdmin::select('id','fa_name')->find($id);
    	$facility_list = \App\Facility::select('id','name','facility_admin')->where(['facility_admin' => null,'is_deleted' => config('axxs.active')])->orwhere('facility_admin',$id)->get();
    	$data['facilityadmin'] = $fa_data;
    	$data['facility_list'] = $facility_list;
    	return $data;
    }

    public function assignFacility(Request $request){
    	$data = $request->all();
        //Unassigning Facility
            Facility::where('facility_admin',$data['fa_id'])->update(['facility_admin' => null]);
        $msg = 'Oops ! No Facility Assigned';
        if (isset($data['facility_id'])) {
            
            $msg = 'Facility Assigned Successfully !';
            //Assigning Facility Loop
            foreach ($data['facility_id'] as $key => $value) {
                Facility::where('id',$value)->update(['facility_admin' =>$data['fa_id'] ]);
            }
        }
        
    	 return redirect()->back()->with('flash_message', $msg);   
    }

    public function deleteFacilityadmin($id){
    	$f_admin = FacilityAdmin::find($id);
    	$result= array();
    	if ($f_admin) {
    		$f_admin->is_deleted = 1;
    		$f_admin->save();
    		$result['code'] = 201; 
    	} else{
    		$result['code'] = 400; 
    	}
    	return $result;
    }

    public function FAinactiveList(){
    	$get_movie = FacilityAdmin::where('is_deleted',1);

    	return Datatables::of($get_movie)
    	->addIndexColumn()
    	->addColumn('action', function ($get_movie) {
    		return '<a href="JavaScript:Void(0)" onClick="activatefa('.$get_movie->id.')"><i class="fa fa-thumbs-up" data-toggle="tooltip" title="Click to Activate"></i>&nbsp;&nbsp;&nbsp;</a>';
                   })
    	->blacklist(['DT_RowIndex','action'])
    	->make(true);
    }

    /**
     * Create function for update facility admin details behalf on facility id.
     *
     * @param object Request $request The facility id keyed facility_id, 
     * 
     * @return NULL
     */
    public function updateFacilityadmin(Request $request) 
    {
        $data = $request->input();
        
        

    $rules = array(
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|unique:facility_admins,email,' . $data['id'] . ',id',
            'total_facility' => 'required',
            'fa_name' => 'required|unique:facility_admins,fa_name,' .  $data['id'],
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
                    'name' => $data['first_name'] . ' ' . $data['last_name'],
                    'first_name' => $data['first_name'],
                    'last_name' => $data['last_name'],
                    'email' => $data['email'],
                    'phone' => isset($data['phone']) ? $data['phone'] : '',
                    'address_line' => isset($data['address_line']) ? $data['address_line'] : '',
                    'city' => isset($data['city']) ? $data['city'] : '',
                    'state' => isset($data['state']) ? $data['state'] : '',
                    'zip' => isset($data['zip']) ? $data['zip'] : null,
                    'company_id' => isset($data['fa_company']) ? $data['fa_company'] : null,
                    'total_facility' => isset($data['total_facility']) ? $data['total_facility'] : '',
                    'fa_name' =>  $data['fa_name'],
                    'location' => $data['location'],
                );
                $facilityUpdateInfo = FacilityAdmin::where(array('id' => $data['id']))->update($updateData);
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

    public function activateFacilityadmin($id){
        $f_admin = FacilityAdmin::find($id);
        $result= array();
        if ($f_admin) {
            $f_admin->is_deleted = 0;
            $f_admin->save();
            $result['code'] = 201; 
        } else{
            $result['code'] = 400; 
        }
        return $result;
    }

    public function getFAlist(Request $request){
        if($request->ajax()){
                $cmpny_data = FacilityAdmin::select('id','name')->where('is_deleted',config('axxs.active'))->get();
                                $data['fa_list'] = $cmpny_data;
                                
                                return $data;
            }
        return View('errors.404');
        }
}
