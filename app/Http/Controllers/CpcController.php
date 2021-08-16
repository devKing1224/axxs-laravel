<?php

namespace App\Http\Controllers;

use App\PurchaseInmate;
use Validator;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use App\User;
use Lang;
use Illuminate\Http\Request;
use App\AllowUrl;
use App\Facility;
use DateTime;
use App\Device;
use App\PaymentInformation;
use App\InmateConfiguration;
use DB;

class CpcController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function ImportCpcUser(Request $request)
    {    
        $api_token = $request->header('Token');
        if ($api_token == null) {
          return response()->json(array(
                              'Status' => Lang::get('common.success'),
                              'statuscode' => 400,
                              'Message' =>  'Token is required',
                  ));
          
        }
         
              $rules = array(
            'sitecode' => 'required',
            'requestid' => 'required',
            'payload' => 'required'
        );
        $messages = [

                'sitecode.required' => 'Site code is required.',
                'requestid.required' => 'Request id is required.',
                'payload.required' => 'Payload  is required.',
            ];

        $validate = Validator::make($request->all(), $rules, $messages);
        
        if ($validate->fails()) {
            return response()->json(array(
                        'Status' => Lang::get('common.failure'),
                        'statuscode' => 468,
                        'Data' => Lang::get('common.validation_error'),
                        'Message' => $validate->errors()->all()
            ));
        } else {
          try {
            $serve_data = [];
        $json_data=$request->all();

        if(config('axxs.api_token') == $api_token ){
    
        if (count($json_data['payload']) > 0) {
            $count_in=0;
            $count_up=0;
            $new_user=0;
            foreach ($json_data['payload'] as $key => $userdata) {

               $facility=Facility::where('facility_id',$userdata['siteid'])->first();
               if($facility == null){
                continue;
                /*return response()->json(array(
                                'Status' => Lang::get('common.success'),
                                'statuscode' => 400,
                                'Message' =>  'Facility not exists',
                    ));*/
               }
               
               //Formating dob
                $serve_data[]=$userdata['apin'];
                $dob = new DateTime($userdata['dateofbirth']);
                $date_of_birth = $dob->format('Y-m-d');

                $randomPassword=date("mdY",strtotime($date_of_birth));
                
                
                $apiToken = str_random(256);
                $data=array(
                    'api_token' => $apiToken,
                    'balance' => 0,
                    'first_name' => $userdata['firstname'],
                    'date_of_birth' => isset($date_of_birth) ? $date_of_birth : NULL,
                    'last_name' => $userdata['lastname'],
                    'first_login' => 0,
                    'username' => $userdata['apin'],
                    'status' => 0,
                    'role_id' => 4,
                    'password' => bcrypt($randomPassword),
                    'location' =>isset($userdata['locationid']) ? $userdata['locationid'] : NULL ,
                    'admin_id' => $facility->facility_user_id,
                    'is_deleted' => (isset($userdata['enabled']) && $userdata['enabled']== true) ? 0 : 1
                );
                $user = User::where(['username' =>$userdata['apin'], 'admin_id' =>$facility->facility_user_id  ])->first();

                if ($user === null) {
                  $obj_config = new InmateConfiguration;
                  $email_create = $obj_config->getConfiguration('email_create');
                  if (isset($email_create) && $email_create->content == 1) {
                    if (isset($facility) && $facility->create_email == 1 ) {
                        $in_id = $userdata['apin'];
                        $email = app('App\Http\Controllers\InmateController')->createEmailadd($in_id,$userdata['siteid']);
                       $data['email'] = $email;
                    }
                  }
                  
                  
                  
                     //check if inmate id exists
                  /*$in_id = isset($userdata['user_id']) ? $userdata['user_id'] : $userdata['apin'];
                  $check_inmateID = User::where('inmate_id',$in_id)->exists();
                  if ($check_inmateID) {
                            continue;
                           }*/         
                    $user_insert = User::create($data);
                    //getDefaulfacilityServices
                    $getServices = \App\DefaultServicePermission::select('service_id')->where('facility_id',$facility->facility_user_id)->get()->toArray();
                    if (count($getServices) > 0) {
                      
                        $defaultlist = [];
                      foreach ($getServices as $key => $value) {
                        $defaultlist[] = [
                              'service_id' => $value['service_id'],
                              'inmate_id' => $user_insert->id
                          ];
                      }
                      DB::table('service_permissions')->insert($defaultlist);
                      //$new_user++;
                      
                      

                    }
                    

                }else{
                  unset($data['password']);
                  unset($data['first_login']);
                  unset($data['api_token']);
                  unset($data['balance']);
                  unset($data['status']);
                  unset($data['role_id']);
                  unset($data['email']);
                  
                   $user_insert = User::updateOrCreate(['username'   => $userdata['apin'],'admin_id' =>$facility->facility_user_id],$data);
                }

                $wasCreated = $user_insert->wasRecentlyCreated;
                
                if ($wasCreated) {
                    $count_in++;
                }else{
                    $count_up++;
                }          
            }
            //dd($json_data['payload']);
            //Releasing users
            $all_users=User::where(['admin_id'=>$facility['facility_user_id'],'is_deleted' => 0])->pluck('username')->toArray();
            $dif=array_diff($all_users, $serve_data);
            
            $count_rel=0;
            if(count($dif) > 0){
              foreach ($dif as $key => $username) {
                $rel=User::where('username',$username)->update(['is_deleted' => 1]);
                if($rel){
                   $count_rel++;
                }

              }
            }
            $msg['countadded']= $count_in;
            $msg['countupdated'] = $count_up;
            $msg['countreleased'] = $count_rel;

            return response()->json(array(
                              'Status' => Lang::get('common.success'),
                              'statuscode' => 200,
                              'Message' => $msg,
                              'requestid' => $json_data['requestid'],
                  ));

        }else{
            return response()->json(array(
                              'Status' => Lang::get('common.success'),
                              'statuscode' => 400,
                              'Message' =>  'Payload Empty',
                  ));
        }
      }else{
          return response()->json(array(
                              'Status' => Lang::get('common.success'),
                              'statuscode' => 400,
                              'Message' =>  'Invalid  token',
                  ));

      }
        
          } catch (\Exception $e) {
            return response()->json(array(
                                          'Status' => Lang::get('common.failure'),
                                          'statuscode' => 400,
                                          'Message' =>  $e->getmessage(),
                              ));
              
            }
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\PurchaseInmate  $purchaseInmate
     * @return \Illuminate\Http\Response
     */
    public function show(PurchaseInmate $purchaseInmate)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\PurchaseInmate  $purchaseInmate
     * @return \Illuminate\Http\Response
     */
    public function edit(PurchaseInmate $purchaseInmate)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\PurchaseInmate  $purchaseInmate
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PurchaseInmate $purchaseInmate)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\PurchaseInmate  $purchaseInmate
     * @return \Illuminate\Http\Response
     */
    public function destroy(PurchaseInmate $purchaseInmate)
    {
        //
    }

  public function Purchase(Request $request){

    $data = $request->input();
    if( $data['auth_key'] == config('axxs.auth_key')){
        $rules = array(
            'siteId' => 'required',
            'product' => 'required',
            'customerTransactionId' => 'required',
            'purchaseDate' => 'required',
            'apin' => 'required',
            'amount' => 'required',
            'paymentType' => 'required',
        );

            $messages = [

                'siteId.required' => 'Site ID is required.',
                'product.required' => 'Product is required.',
                'customerTransactionId.required' => 'Customer Transaction ID is required.',
                'purchaseDate.required' => 'Purchase Date is required.',
                'apin.required' => 'apin is required.',
                'amount.required' => 'Amount is required.',
                'paymentType.required' => 'Payment Type is required.',
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
               
               try{
                    $transactionId = str_random(32);
                    $facility=Facility::where('facility_id',$data['siteId'])->first();
                          
                          
                          if ($facility == null) {
                            return response()->json(array(
                                                    'Status' => Lang::get('common.bad_request'),
                                                    'Code' => 400,
                                                    'Message' =>  'Facility not available in database',
                                        ));
                          }
                          
                    $inmate_sms = PurchaseInmate::create([

                                'siteId' => $data['siteId'],
                                'product' => $data['product'],
                                'customerTransactionId' => $data['customerTransactionId'],
                                 'purchaseDate' =>  $data['purchaseDate'],
                                 'apin' => $data['apin'],
                                 'paymentType' => $data['paymentType'],
                                 'amount' => $data['amount'],
                                 'transactionId' => $transactionId,
                            ]);
                          
                          
                          $user = User::where('username', $data['apin'])->where('admin_id', $facility->facility_user_id)->first();

                          if($user){
                                $objUser = new User();
                                $addBalance = $objUser->updateAddBalance( $user->id,
                                    $data['amount']);

                                        return response()->json(array(
                                                    'Status' => Lang::get('common.success'),
                                                    'Code' => 200,
                                                    'Message' =>  'Transaction submit successfully',
                                                    'TransactionId' => $transactionId
                                        ));
                             }
                             else{

                                  return response()->json(array(
                                                    'Status' => Lang::get('common.bad_request'),
                                                    'Code' => 400,
                                                    'Message' =>  'User not available in database',
                                        ));
                             }
                      }
                        catch (\Exception $e) {
                                return response()->json(array(
                                          'Status' => Lang::get('common.failure'),
                                          'statuscode' => 400,
                                          'Message' =>  $e->getmessage(),
                              ));
                       }
        }

    }
        else {
            return response()->json(array(
                            'Status' => Lang::get('common.success'),
                            'Code' => 400,
                            'Message' =>  'Unauthorized user',
                ));
         }
 
}

        public function allowUrl(){

            $allowUrl = new AllowUrl();
            $allurl = $allowUrl->getAllowUrlList();

            return response()->json(array(
                                'Status' => Lang::get('common.success'),
                                'Code' => 200,
                                'Data' => $allurl,
                                'Message' =>  'M&S URL',
                    ));

       }

       //get userslist by facilityid

       public function getusersbyfacilityid(Request $request){
        $api_token = $request->header('Token');
        if ($api_token == null) {
          return response()->json(array(
                              'Status' => Lang::get('common.success'),
                              'statuscode' => 400,
                              'Message' =>  'Token is required',
                  ));
        }

        $data=$request->all();

        $validate = Validator::make($request->all(), ['facility_id' => 'required']);

        if ($validate->fails()) {
            return response()->json(array(
                        'Status' => Lang::get('common.failure'),
                        'statuscode' => 468,
                        'Data' => Lang::get('common.validation_error'),
                        'Message' => $validate->errors()->all()
            ));
        } else {
          //check facility exists
          try {
          $facility=Facility::where('facility_id',$data['facility_id'])->first();

          if ($facility != null) {
              $users=User::select('first_name','last_name','username','date_of_birth','is_active','location')
             ->where('admin_id', $facility->facility_user_id)->get();
             if (count($users) > 0) {
                  return response()->json(array(
                                      'Status' => Lang::get('common.success'),
                                      'Code' => 200,
                                      'data' => $users,
                                      'Message' =>  'User List',
                          ));
             }else{
              return response()->json(array(
                              'Status' => Lang::get('common.success'),
                              'statuscode' => 400,
                              'Message' =>  'No user found',
                  ));
             }
             
          }else{
              return response()->json(array(
                              'Status' => Lang::get('common.success'),
                              'statuscode' => 400,
                              'Message' =>  'Facility not exists',
                  ));
          }
        } catch (\Exception $e) {
              return response()->json(array(
                                          'Status' => Lang::get('common.failure'),
                                          'statuscode' => 400,
                                          'Message' =>  $e->getmessage(),
                              ));
          }
          
        }

       }

       /**
        * Show user details
        *
        * @param  inamte id
        * @return json
        */
       public function getUserdetails(Request $request){
        $api_token = $request->header('Token');
        if ($api_token == null) {

          return response()->json(array(
                              'Status' => Lang::get('common.success'),
                              'statuscode' => 400,
                              'Message' =>  'Token is required',
                  ));
          
        } elseif (config('axxs.api_token') == $api_token ){
           
            $rules = array(
                'username' => 'required',
                'facility_name' => 'required'
            );

            $validate = Validator::make($request->all(), $rules);
            if ($validate->fails()) {
            return response()->json(array(
                        'Status' => Lang::get('common.failure'),
                        'statuscode' => 468,
                        'Data' => Lang::get('common.validation_error'),
                        'Message' => $validate->errors()->all()
            ));
            } else {
              try {
                
              
                 $data = $request->all();
                 $getFacility_userid = Facility::where('facility_name',$data['facility_name'])->value('facility_user_id');
                 if ($getFacility_userid == null) {
                     return response()->json(array(
                              'Status' => Lang::get('common.success'),
                              'statuscode' => 400,
                              'Message' =>  'No facility Found',
                  ));
                 }

                 
                 $userDetails = User::where('username',$data['username'])->where('admin_id',$getFacility_userid)->first();
                 
                 if ($userDetails != null) {
                      return response()->json(array(
                                          'Status' => Lang::get('common.success'),
                                          'Code' => 200,
                                          'data' => $userDetails,
                                          'Message' =>  'user details',
                              ));
                 }else{
                    return response()->json(array(
                              'Status' => Lang::get('common.success'),
                              'statuscode' => 400,
                              'Message' =>  'No user found',
                  ));
                 }
                 } catch (\Exception $e) {
                      return response()->json(array(
                                          'Status' => Lang::get('common.failure'),
                                          'statuscode' => 400,
                                          'Message' =>  $e->getmessage(),
                              ));
                }
             }
        }else {

            return response()->json(array(
                                'Status' => Lang::get('common.success'),
                                'statuscode' => 400,
                                'Message' =>  'Invalid  token',
                    ));
        }
      }

      /**
        * Assign Device to User
        *
        * @param  device id , username ,facility id
        * @return json
        */

      public function assignDevice(Request $request){
        $api_token = $request->header('Token');
        if ($api_token == null) {
                return response()->json(array(
                                    'Status' => Lang::get('common.success'),
                                    'statuscode' => 400,
                                    'Message' =>  'Token is required',
                        ));
          
        } elseif (config('axxs.api_token') == $api_token ){

            $rules = array(
              'device_id'   => 'required',
              'username'    => 'required',
              'facility_id' => 'required'
            );

            $messages = [ 'device_id.required'   => 'Device ID is required.',
                          'username.required'    => 'Username is required',
                          'facility_id.required' => 'Facility Id is required'
                        ];

            $validate = Validator::make($request->all(), $rules, $messages);

            if ($validate->fails()) {

              return response()->json(array(
                          'Status' => Lang::get('common.failure'),
                          'statuscode' => 468,
                          'Data' => Lang::get('common.validation_error'),
                          'Message' => $validate->errors()->all()
              ));

            } else {
              $data = $request->all();
              $username = User::where('username' , $data['username'])->first();
              if ($username != null) {
                  $facility = Facility::where('facility_id' , $data['facility_id'])->first();

                  if ($facility != null) {
                    if ($username->admin_id == $facility->facility_user_id) {
                      $device_exists =Device::where('device_id',$data['device_id'])->exists();
                      if ($device_exists) {
                        //start
                          $device = Device::where(['device_id' => $data['device_id'] , 'facility_id' => $facility->id])->exists();
                                if ($device) {
                                      //assigning device
                                  $user = $username->update(['device_id' =>$data['device_id'] ]);
                                  if ($user) {
                                              return response()->json(array(
                                                        'Status' => Lang::get('common.success'),
                                                        'statuscode' => 200,
                                                        'Message' =>  'assigned device successfully',
                                               ));

                                  } else {
                                          return response()->json(array(
                                                    'Status' => Lang::get('common.failure'),
                                                    'statuscode' => 400,
                                                    'Message' =>  'Something went wrong',
                                           ));
                                  }
                                } else {
                                        return response()->json(array(
                                                'Status' => Lang::get('common.success'),
                                                'statuscode' => 400,
                                                'Message' =>  'Device is not asscoiated with facility',
                                       ));
                                }
                      //end
                      } else {
                              return response()->json(array(
                                    'Status' => Lang::get('common.success'),
                                    'statuscode' => 400,
                                    'Message' =>  'Device not exists',
                                    ));
                      }
                      
                        
                    }else{
                          return response()->json(array(
                                  'Status' => Lang::get('common.success'),
                                  'statuscode' => 400,
                                  'Message' =>  'Facility is not assoiated with user or vice-versa',
                      ));
                    }
                    
                  }else{
                        return response()->json(array(
                                  'Status' => Lang::get('common.success'),
                                  'statuscode' => 400,
                                  'Message' =>  'No facility found',
                      ));
                  }
                  
              }else{
                    return response()->json(array(
                              'Status' => Lang::get('common.success'),
                              'statuscode' => 400,
                              'Message' =>  'No user found',
                  ));
              }
            }

        }else{

              return response()->json(array(
                                'Status' => Lang::get('common.success'),
                                'statuscode' => 400,
                                'Message' =>  'Invalid  token',
                    ));
        }
      }

      /**
     * User device usage
     * @param username
     * @return json
     */
      public function getUserusage(Request $request){
        $api_token = $request->header('Token');
        if ($api_token == null) {
                return response()->json(array(
                                    'Status' => Lang::get('common.success'),
                                    'statuscode' => 400,
                                    'Message' =>  'Token is required',
                        ));
          
        } elseif (config('axxs.api_token') == $api_token ){
                  $rules = array(
                      'username' => 'required',
                      'facility_name' => 'required'
                  );
                  
                  $validate = Validator::make($request->all(), $rules);

                  if ($validate->fails()) {
                        return response()->json(array(
                                    'Status' => Lang::get('common.failure'),
                                    'statuscode' => 468,
                                    'Data' => Lang::get('common.validation_error'),
                                    'Message' => $validate->errors()->all()
                        ));
                  } else {
                    try {

                       $data = $request->all();
                       $getFacility_userid = Facility::where('facility_name',$data['facility_name'])->value('facility_user_id');
                         if ($getFacility_userid == null) {
                             return response()->json(array(
                                      'Status' => Lang::get('common.success'),
                                      'statuscode' => 400,
                                      'Message' =>  'No facility Found',
                          ));
                         }
                       $user = User::where('username' ,$data['username'])->where('admin_id' ,$getFacility_userid)->where('is_deleted', config('axxs.active'))->first();
                       if ($user != null) {
                       $inmateLoggedList = \App\InmateLoggedHistory::select('id','start_date_time as login_time','end_date_time as logout_time','charges')->where('inmate_id', $user->id)->orderBy('created_at','desc')->get();
                          return response()->json(array(
                              'Status' => Lang::get('common.success'),
                              'statuscode' => 200,
                              'data' => $inmateLoggedList,
                              'Message' =>  'user usage history',
                            ));

                       }else{
                            return response()->json(array(
                                'Status' => Lang::get('common.success'),
                                'statuscode' => 400,
                                'Message' =>  'No user found',
                              ));
                       } 
                         } catch (\Exception $e) {
                                return response()->json(array(
                                    'Status' => Lang::get('common.failure'),
                                    'statuscode' => 400,
                                    'Message' =>  $e->getMessage(),
                                  ));
                         }
                     }
        } else{
               return response()->json(array(
                            'Status' => Lang::get('common.success'),
                            'statuscode' => 400,
                            'Message' =>  'Invalid  token',
                ));

        }

      }


      /**
     * User CPC Purchase history
     * @param username
     * @return json
     */
      public function getUserpurchaseHistory(Request $request){
        $api_token = $request->header('Token');
        if ($api_token == null) {
                return response()->json(array(
                                    'Status' => Lang::get('common.success'),
                                    'statuscode' => 400,
                                    'Message' =>  'Token is required',
                        ));
          
        } elseif (config('axxs.api_token') == $api_token ){
                  $rules = array(
                      'username' => 'required',
                      'facility_name' => 'required'
                  );
                  
                  $validate = Validator::make($request->all(), $rules);

                  if ($validate->fails()) {
                        return response()->json(array(
                                    'Status' => Lang::get('common.failure'),
                                    'statuscode' => 468,
                                    'Data' => Lang::get('common.validation_error'),
                                    'Message' => $validate->errors()->all()
                        ));
                  } else {

                    try {
                       $data = $request->all();
                       $getFacility_userid = Facility::where('facility_name',$data['facility_name'])->value('facility_user_id');
                         if ($getFacility_userid == null) {
                             return response()->json(array(
                                      'Status' => Lang::get('common.success'),
                                      'statuscode' => 400,
                                      'Message' =>  'No facility Found',
                          ));
                         }
                       $user = User::where('username' ,$data['username'])->where('admin_id' ,$getFacility_userid)->where('is_deleted', config('axxs.active'))->first();
                       if ($user != null) {
                       $inmateLoggedList =PurchaseInmate::where('apin', $data['username'])->orderBy('created_at','desc')->get();
                          return response()->json(array(
                              'Status' => Lang::get('common.success'),
                              'statuscode' => 200,
                              'data' => $inmateLoggedList,
                              'Message' =>  'user cpc purchase history',
                            ));

                       }else{
                            return response()->json(array(
                                'Status' => Lang::get('common.success'),
                                'statuscode' => 400,
                                'Message' =>  'No user found',
                              ));
                       }
                        } catch (\Exception $e) {
                                return response()->json(array(
                                'Status' => Lang::get('common.failure'),
                                'statuscode' => 400,
                                'Message' =>  $e->getMessage(),
                              ));
                         }
                     }
        } else{
               return response()->json(array(
                            'Status' => Lang::get('common.success'),
                            'statuscode' => 400,
                            'Message' =>  'Invalid  token',
                ));

        }

      }
      
      /**
     * Inmate fund Report
     * @param username, facility name
     * @return json
     */
      public function getInmateFund(Request $request){
        $api_token = $request->header('Token');
        if ($api_token == null) {
                return response()->json(array(
                                    'Status' => Lang::get('common.success'),
                                    'statuscode' => 400,
                                    'Message' =>  'Token is required',
                        ));
          
        } elseif (config('axxs.api_token') == $api_token ){
                  $rules = array(
                      'username' => 'required',
                      'facility_name' => 'required'
                  );
                  
                  $validate = Validator::make($request->all(), $rules);

                  if ($validate->fails()) {
                        return response()->json(array(
                                    'Status' => Lang::get('common.failure'),
                                    'statuscode' => 468,
                                    'Data' => Lang::get('common.validation_error'),
                                    'Message' => $validate->errors()->all()
                        ));
                  } else {
                    try {
                      
                    
                       $data = $request->all();
                       $getFacility_userid = Facility::where('facility_name',$data['facility_name'])->value('facility_user_id');
                         if ($getFacility_userid == null) {
                             return response()->json(array(
                                      'Status' => Lang::get('common.success'),
                                      'statuscode' => 400,
                                      'Message' =>  'No facility Found',
                          ));
                         }
                       $user = User::where('username' ,$data['username'])->where('admin_id' ,$getFacility_userid)->where('is_deleted', config('axxs.active'))->first();

                       if ($user != null) {
                       $pymnt_info = PaymentInformation::select('id','family_id','payment_status','transaction_id','client_email','client_name','amount','inmate_id')->where('inmate_id',$user->id)->orderBy('id', 'DESC')->get();
                          return response()->json(array(
                              'Status' => Lang::get('common.success'),
                              'statuscode' => 200,
                              'data' => $pymnt_info,
                              'Message' =>  'inmate fund details',
                            ));

                       }else{
                            return response()->json(array(
                                'Status' => Lang::get('common.success'),
                                'statuscode' => 400,
                                'Message' =>  'No user found',
                              ));
                       }
                        } catch (\Exception $e) {
                          return response()->json(array(
                                'Status' => Lang::get('common.failure'),
                                'statuscode' => 400,
                                'Message' =>  $e->getMessage(),
                              ));
                      }
                     }
        } else{
               return response()->json(array(
                            'Status' => Lang::get('common.success'),
                            'statuscode' => 400,
                            'Message' =>  'Invalid  token',
                ));

        }

      }

      /**
     * adding user to database.
     * this function doing same job as ImportCpcUser()
     * but the request parameters are different
     * 
     * @param \Illuminate\Http\Request
     * @return \Illuminate\Http\Response
     */
      public function importUser(Request $request){
          $api_token = $request->header('Token');
          if ($api_token == null) {
            return response()->json(array(
                                'Status' => Lang::get('common.success'),
                                'statuscode' => 400,
                                'Message' =>  'Token is required',
                    ));
            
          }
           
                $rules = array(
              'requestId' => 'required',
              'siteId' => 'required',
              'inmates' => 'required'
          );
          $messages = [

                  'siteId.required' => 'Site id is required.',
                  'requestId.required' => 'Request id is required.',
                  'inmates.required' => 'inmates is required.',
              ];

          $validate = Validator::make($request->all(), $rules, $messages);
          
          if ($validate->fails()) {
              return response()->json(array(
                          'Status' => Lang::get('common.failure'),
                          'statuscode' => 468,
                          'Data' => Lang::get('common.validation_error'),
                          'Message' => $validate->errors()->all()
              ));
          } else {
            try {
              $serve_data = [];
          $json_data=$request->all();

          if(config('axxs.api_token') == $api_token ){
          $facility=Facility::where('facility_id',$json_data['siteId'])->first();
          if ($facility == null) {
             return response()->json(array(
                          'Status' => Lang::get('common.failure'),
                          'statuscode' => 404,
                          'Message' => Lang::get('facility.facility_not_found')
              ));
          }

          if (count($json_data['inmates']) > 0) {
              $count_in=0;
              $new_user=0;
              foreach ($json_data['inmates'] as $key => $userdata) {
                 //Formating dob
                  $serve_data[]=$userdata['apin'];
                  $dob = new DateTime($userdata['dateofbirth']);
                  $date_of_birth = $dob->format('Y-m-d');

                  $randomPassword=date("mdY",strtotime($date_of_birth));

                  $apiToken = str_random(256);
                  $data=array(
                      'api_token' => $apiToken,
                      'balance' => 0,
                      'first_name' => $userdata['firstname'],
                      'middle_name' => isset($userdata['middlename']) ? $userdata['middlename'] : NULL,
                      'date_of_birth' => isset($date_of_birth) ? $date_of_birth : NULL,
                      'last_name' =>isset($userdata['lastname']) ? $userdata['lastname'] : NULL,
                      'first_login' => 0,
                      'username' => $userdata['apin'],
                      'status' => 0,
                      'role_id' => 4,
                      'password' => bcrypt($randomPassword),
                      'location' =>isset($userdata['locationid']) ? $userdata['locationid'] : NULL ,
                      'admin_id' => $facility->facility_user_id
                  );
                  $user = User::where(['username' =>$userdata['apin'], 'admin_id' =>$facility->facility_user_id  ])->first();

                  if ($user === null) {
                    $obj_config = new InmateConfiguration;
                    $email_create = $obj_config->getConfiguration('email_create');
                    if (isset($email_create) && $email_create->content == 1) {
                      if (isset($facility) && $facility->create_email == 1 ) {
                          $in_id = $userdata['apin'];
                          $email = app('App\Http\Controllers\InmateController')->createEmailadd($in_id,$json_data['siteId']);
                         $data['email'] = $email;
                      }
                    }
                    
                    
                    
                                 
                      $user_insert = User::create($data);
                      //getDefaulfacilityServices
                      $getServices = \App\DefaultServicePermission::select('service_id')->where('facility_id',$facility->facility_user_id)->get()->toArray();
                      if (count($getServices) > 0) {
                        
                          $defaultlist = [];
                        foreach ($getServices as $key => $value) {
                          $defaultlist[] = [
                                'service_id' => $value['service_id'],
                                'inmate_id' => $user_insert->id
                            ];
                        }
                        DB::table('service_permissions')->insert($defaultlist);
                        //$new_user++;            

                      }
                      

                  } else {
                    continue;
                  }

                  $wasCreated = $user_insert->wasRecentlyCreated;
                  
                  if ($wasCreated) {
                      $count_in++;
                  }         
              }
              
              $msg['countadded']= $count_in;

              return response()->json(array(
                                'Status' => Lang::get('common.success'),
                                'statuscode' => 200,
                                'Message' => $msg,
                                'requestid' => $json_data['requestId'],
                    ));

          }else{
              return response()->json(array(
                                'Status' => Lang::get('common.success'),
                                'statuscode' => 400,
                                'Message' =>  'Payload Empty',
                    ));
          }
        }else{
            return response()->json(array(
                                'Status' => Lang::get('common.success'),
                                'statuscode' => 400,
                                'Message' =>  'Invalid  token',
                    ));

        }
          
            } catch (\Exception $e) {
              return response()->json(array(
                                            'Status' => Lang::get('common.failure'),
                                            'statuscode' => 400,
                                            'Message' =>  $e->getmessage(),
                                ));
                
              }
          }
      }


       /**
      * update user to database.
      * 
      * @param \Illuminate\Http\Request
      * @return \Illuminate\Http\Response
      */
       public function updateUser(Request $request){
           $api_token = $request->header('Token');
           if ($api_token == null) {
             return response()->json(array(
                                 'Status' => Lang::get('common.success'),
                                 'statuscode' => 400,
                                 'Message' =>  'Token is required',
                     ));
             
           }
            
                 $rules = array(
               'requestId' => 'required',
               'siteId' => 'required',
               'inmates' => 'required'
           );
           $messages = [

                   'siteId.required' => 'Site id is required.',
                   'requestId.required' => 'Request id is required.',
                   'inmates.required' => 'inmates is required.',
               ];

           $validate = Validator::make($request->all(), $rules, $messages);
           
           if ($validate->fails()) {
               return response()->json(array(
                           'Status' => Lang::get('common.failure'),
                           'statuscode' => 468,
                           'Data' => Lang::get('common.validation_error'),
                           'Message' => $validate->errors()->all()
               ));
           } else {
             try {
               $serve_data = [];
           $json_data=$request->all();

           if(config('axxs.api_token') == $api_token ){
           $facility=Facility::where('facility_id',$json_data['siteId'])->first();
           if ($facility == null) {
              return response()->json(array(
                           'Status' => Lang::get('common.failure'),
                           'statuscode' => 404,
                           'Message' => Lang::get('facility.facility_not_found')
               ));
           }

           if (count($json_data['inmates']) > 0) {
               $count_up=0;

               foreach ($json_data['inmates'] as $key => $userdata) {
                $update_data = [];
                if (isset($userdata['firstName'])) {
                    $update_data['first_name'] = $userdata['firstname'];
                }
                if (isset($userdata['lastName'])) {
                  $update_data['last_name'] = $userdata['lastname'];
                }
                if (isset($userdata['dateOfBirth'])) {
                  $update_data['date_of_birth'] = $userdata['dateofbirth'];
                }
                if (isset($userdata['middleName'])) {
                  $update_data['middle_name'] = $userdata['middlename'];
                }
                  $user_up = User::where(['username'=>$userdata['apin'],'admin_id' => $facility['facility_user_id'] ])
                  ->update($update_data);
                  if ($user_up) {
                      $count_up++;
                     }   
               }
               
               $msg['countupdated']= $count_up;

               return response()->json(array(
                                 'Status' => Lang::get('common.success'),
                                 'statuscode' => 200,
                                 'Message' => $msg,
                                 'requestid' => $json_data['requestId'],
                     ));

           }else{
               return response()->json(array(
                                 'Status' => Lang::get('common.success'),
                                 'statuscode' => 400,
                                 'Message' =>  'Payload Empty',
                     ));
           }
         }else{
             return response()->json(array(
                                 'Status' => Lang::get('common.success'),
                                 'statuscode' => 400,
                                 'Message' =>  'Invalid  token',
                     ));

         }
           
             } catch (\Exception $e) {
               return response()->json(array(
                                             'Status' => Lang::get('common.failure'),
                                             'statuscode' => 400,
                                             'Message' =>  $e->getmessage(),
                                 ));
                 
               }
           }
       }
  
      /**
     * releasing user to database.
     * 
     * @param \Illuminate\Http\Request
     * @return \Illuminate\Http\Response
     */

      public function releaseUser(Request $request){
        $api_token = $request->header('Token');
        if ($api_token == null) {
          return response()->json(array(
                              'Status' => Lang::get('common.success'),
                              'statuscode' => 400,
                              'Message' =>  'Token is required',
                  ));
          
        }
         
              $rules = array(
            'requestId' => 'required',
            'siteId' => 'required',
            'inmates' => 'required'
        );
        $messages = [

                'siteId.required' => 'Site id is required.',
                'requestId.required' => 'Request id is required.',
                'inmates.required' => 'inmates is required.',
            ];

        $validate = Validator::make($request->all(), $rules, $messages);
        if ($validate->fails()) {
            return response()->json(array(
                        'Status' => Lang::get('common.failure'),
                        'statuscode' => 468,
                        'Data' => Lang::get('common.validation_error'),
                        'Message' => $validate->errors()->all()
            ));
        } else {
            try {
                $json_data=$request->all();

                if(config('axxs.api_token') == $api_token ){
                    $facility=Facility::where('facility_id',$json_data['siteId'])->first();
                    if ($facility == null) {
                       return response()->json(array(
                                    'Status' => Lang::get('common.failure'),
                                    'statuscode' => 404,
                                    'Message' => Lang::get('facility.facility_not_found')
                        ));
                    }

                    if (count($json_data['inmates']) > 0) {
                        $count_rel=0;
                        foreach ($json_data['inmates'] as $key => $userdata) {
                          $username[] = $userdata['apin'];

                        }
                        
                        $count_rel = User::whereIn('username',$username)
                                        ->where('admin_id',$facility['facility_user_id'])
                                        ->update(['is_deleted' => 1]);
                        $msg['countrelease']= $count_rel;

                        return response()->json(array(
                                          'Status' => Lang::get('common.success'),
                                          'statuscode' => 200,
                                          'Message' => $msg,
                                          'requestid' => $json_data['requestId'],
                              ));

                    }else{

                        return response()->json(array(
                                'Status' => Lang::get('common.success'),
                                'statuscode' => 400,
                                'Message' =>  'Payload Empty',
                    ));

                    }


                }else{
                  return response()->json(array(
                                'Status' => Lang::get('common.success'),
                                'statuscode' => 400,
                                'Message' =>  'Invalid  token',
                    ));
                }
            } catch (Exception $e) {
                return response()->json(array(
                                              'Status' => Lang::get('common.failure'),
                                              'statuscode' => 400,
                                              'Message' =>  $e->getmessage(),
                                  ));
            }

        }
      }

}
