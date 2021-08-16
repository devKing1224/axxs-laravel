<?php

namespace App;

use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;
use DB;

class User extends Authenticatable {

    use Notifiable;
    use HasRoles;
    use HasApiTokens;
    protected $guard_name = 'admin';
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'inmate_id', 'api_token', 'balance', 'status', 'first_name', 'last_name','middle_name', 'date_of_birth', 'admin_id', 'username', 'device_id',
        'password', 'phone', 'address_line_1', 'address_line_2', 'city', 'state', 'zip', 'is_deleted', 'role_id', 'email', 'user_image','first_login', 'location','site_id',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
    ];


    /**
     * Function to return the users of inmate
     * 
     * @return array The list of inmate contact
     */
    public function inmate() {
        return $this->belongsTo('App\InmateContacts', 'id', 'inmate_id');
    }

    /**
     * Function to return the users with contact list
     * 
     * @return array The list of inmate contact
     */
    public function contactList() {
        return $this->hasMany('App\InmateContacts', 'inmate_id');
    }

    /**
     * Function to return the users with contact list of all email
     * 
     * @return array The list of inmate contact
     */
    public function contactEmailList() {
        return $this->hasMany('App\InmateContacts', 'inmate_id')->where('type', 'email');
    }

    /**
     * Function to return the users with contact list of all numbers
     * 
     * @return array The list of inmate contact
     */
    public function contactNumberList() {
        return $this->hasMany('App\InmateContacts', 'inmate_id')->where('type', 'phone');
    }

    /**
     * Function to return the facility with contact list of all users
     * 
     * @return array The list of inmate contact
     */
    public function facility() {
        return $this->belongsTo('App\InmateContacts', 'id', 'facility_id');
    }
    
     /**
     * Function to return the facility 
     * 
     * @return facility details
     */
    public function staffFacility() {
        return $this->hasOne('App\Facility', 'facility_user_id', 'admin_id');
    }
     /**
     * Function to return the email with with inmate details
     * 
     * @return array The inmate emails details
     */
    public function inmateEmail() {
        return $this->hasOne('App\InmateDetails', 'inmate_id', 'id');
    }

    /**
     * Function to return the history of active of inmate with all the services details
     * 
     * @return array The list of service history
     */
    public function vendorInfo() {
        return $this->hasMany('App\InmateActivityHistory', 'inmate_id')->with('vendorDetails');
    }
    
     /**
     * Function to return the history of active of inmate with all the services details
     * 
     * @return array The list of service history
     */
    public function LoggedReportInfo() {
        return $this->hasMany('App\InmateLoggedHistory', 'inmate_id');
    }
    

   /**
     * Function to return the history of active of inmate with all the services details
     * 
     * @return array The list of service history
     */
    public function EmailTextCharges() {
        return $this->hasMany('App\InmateChargesHistory', 'inmate_id');
    }


 /**
     * Function to return the history of active of inmate with all the services details
     * 
     * @return array The list of service history
     */
    public function TextCharges() {
        return $this->hasMany('App\InmateChargesHistory', 'inmate_id');
    }

  /**
     * Function to return the history of active of inmate with all the services details
     * 
     * @return array The list of service history
     */
    public function ServiceHistoryDetails() {
        return $this->hasMany('App\ServiceHistory', 'inmate_id');
    } 



    /**
     * Function to return the history of active of inmate with all the services details
     * 
     * @return array The list of service history
     */
    public function vendorsInfoHistory() {
        return $this->hasMany('App\InmateActivityHistory', 'inmate_id')
                ->with(['vendorDetails', 'vendorDetailsNames'])
                ->orderBy('id', 'DESC');
    }

    /**
     * Function to return the family details 
     * 
     * @return array The list family with users
     */
    public function familyInfo() {
        return $this->hasMany('App\Family', 'inmate_id');
    }

    /**
     * Function to return the facility 
     * 
     * @return facility details
     */
    public function inmateFacility() {
        return $this->hasOne('App\Facility', 'facility_user_id', 'admin_id');
    }

    /**
     * Function to return the facility with his own detailos form facility table
     * 
     * @return array The facility details
     */
    public function detailFacility() {
        return $this->hasOne('App\Facility', 'facility_user_id', 'id');
    }

    /**
     * Function to return the users with details under facility
     * 
     * @return array The list of users under facility
     */
    public function allInmateByFacility() {
        return $this->hasMany('App\User', 'admin_id');
    }

    /**
     * Function to return the details of services permission of user
     * 
     * @return array The list of services which are enabled for user
     */
    public function inmateService() {
        return $this->belongsTo('App\ServicePermission', 'id', 'inmate_id');
    }

    /**
     * Function to return the facility service info 
     * 
     * @return array The list of services which are enabled for facility by admin
     */
    public function getFacilityService() {
        return $this->hasMany('App\ServicePermission', 'inmate_id');
    }

    /**
     * Function to return the facility with user details
     * 
     * @return array The list facility with its users
     */
    public function getUsersInfo($facility_id,$all = false) {

        $data =  DB::table($this->table)
                        ->leftJoin('facilitys', 'facilitys.facility_user_id', '=', $this->table . '.admin_id')
                        ->leftjoin('inmate_report_history' ,'users.id' , 'inmate_report_history.inmate_id')
                        ->leftjoin('block_services' ,'users.id' , 'block_services.inmate_id')
                        ->select($this->table . '.*', 'facilitys.facility_name', DB::raw('(CASE WHEN (SELECT count(id) as dispute_id from inmate_report_history WHERE view = 0 && inmate_report_history.inmate_id = users.id)  > 0 THEN 1 ELSE 0 END) AS is_view'),'block_services.start_date as bs_start_date','block_services.end_date as bs_end_date')
                        ->where($this->table . '.is_deleted', 0)
                        ->where($this->table . '.role_id', 4)
                        ->orderBy('facility_name','ASC')
                        ->GroupBY('username');
        if ($all == true) {
            $data = $data->whereIn('users.admin_id',$facility_id);
        }else{
            $data = $data->where('users.admin_id',$facility_id);
        }

        return $data;
    }

    /**
     * Function to update the status active or inactive
     * 
     * @param array contained in $data are inmateid and status
     * 
     * @return array The updated data of inmate 
     */
    public function updateStatus($data) {
        return DB::table($this->table)
                        ->where('id', $data['inmate_id'])
                        ->update([
                            'status' => $data['status'],
        ]);
    }

    /**
     * Function to get the details of inmate's balance
     * 
     * @param array contained in $data are inmateid 
     * 
     * @return array of inmate (user)
     */
    public function getBalance($data) {
        return DB::table($this->table)
                        ->where('id', $data['inmate_id'])
                        ->first();
    }

    /*
     * Function to delete provider data by id
     * 
     * @return Message staff deleted
     */

    public function deleteUser($user_id) {
        return DB::table($this->table)
                        ->where('id', $user_id)
                        ->update(['is_deleted' => '1']);
    }

    /**
     * Function for update api token
     * 
     * @param integer $data  
     * 
     * @return string 
     */
    public function updateApiToken($data) {
        return DB::table($this->table)
                        ->where('id', $data['inmate_id'])
                        ->update([
                            'api_token' => 0,
        ]);
    }

    /**
     * Function for update balance
     * 
     * @param integer $inmate_id and $balance 
     * 
     * @return string 
     */
    public function updateBalance($inmate_id, $balance) {
        return DB::table($this->table)
                        ->where('id', $inmate_id)
                        ->update([
                            'balance' => DB::raw('balance-' . $balance),
        ]);
    }

    /**
     * Function for update balance with add money
     * 
     * @param integer $inmate_id and $balance 
     * 
     * @return string 
     */
    public function updateAddBalance($inmate_id, $balance) {
        return DB::table($this->table)
                        ->where('id', $inmate_id)
                        ->update([
                            'balance' => DB::raw('balance+' . $balance),
        ]);
    }

    /**
     * Function to return the facility information of inmate
     * 
     * @param integer $email The email of inmate
     * 
     * @return array The information of facility 
     */
    public function getFacilityInfoByInmateEmail($email) {
        return DB::table($this->table)
                        ->leftJoin('facilitys', $this->table . '.id', '=', 'facilitys.facility_user_id')
                        ->select('facilitys.email', $this->table . '.id')
                        ->where($this->table . '.username', $email)->first();
    }

    /**
     * Function to return the facility information of inmate
     * 
     * @param integer $reportID The report login of inmate
     * 
     * @return array The information of facility 
     */
    public function getFacilityInfoByInmateReportId($reportID) {
        $table = $this->table;
        return static::leftJoin('inmate_report_history', 'inmate_report_history.inmate_id', '=', 'users.id')
                        ->leftJoin('facilitys', $table . '.admin_id', '=', 'facilitys.facility_user_id')
                        ->select('facilitys.email', $table . '.id')
                        ->where('inmate_report_history.id', $reportID)->first();
    }

    /**
     * Function to return the facility information of inmate
     * 
     * @param integer $active The report login of inmate
     * 
     * @return array The information of facility 
     */
    public function getinmateListLowBalanceInfo($active) {
        return static::whereHas('admin', function($q) {
                            $q->where('balance', '<', 10)
                            ->where('role_id', '=', 4)
                            ->where('is_deleted', '=', 0);
                        })
                        ->with(['admin' => function($q) {
                                $q->select('balance', 'first_name as inmate_name', 'id');
                            }])
                        ->leftJoin('familys', $this->table . '.id', '=', 'familys.family_user_id')
                        ->select('users.admin_id', 'familys.email', 'familys.first_name as family_name')
                        ->where($this->table . '.is_deleted', $active)->get();
    }

    /**
     * Function to return admin details
     * 
     * @return array The information of facility 
     */
    public function admin() {
        return $this->belongsTo('\App\User', 'admin_id', 'id');
    }
    
    /*
     * Function to get Specialist information by username
     * 
     * @param  $username is the username of Specialist
     * 
     * @return details in object
    */
     public function getSpecialAdminInfoByUserNameEmail($username){
        return DB::table($this->table)
            ->where('username', $username)
            ->first(); 
        
    }
        /*
     * Function to get varify for staff/facility/admin
     * 
     * @param  $details is the inmate details,
     * @param  $authexist is the auth details,
     * 
     * @return details in object
     */

    public function validateInmateStaffFacility($userInfo, $authexist) {
        if ($userInfo) {
            if ($authexist->hasRole('Super Admin')) {
                return true;
            }
            if (($authexist->hasRole('Facility Admin') && $authexist->id == $userInfo->admin_id)) {
                return true;
            }
            if (($authexist->hasRole('Facility Staff') && $authexist->admin_id == $userInfo->admin_id)) {
                return true;
            }
            if (!$authexist->hasAnyRole(['Facility Staff', 'Facility Admin'])) {
               return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    /**
    * Function to get the details of inmate's 
    * 
    * @param array contained in $data are inmateid 
    * 
    * @return array of inmate (user)
    */
        public function getUsers($data) {
        return DB::table($this->table)
                        ->select('id')
                        ->where('admin_id', $data)
                        ->get()->toArray();
        }
        
    /**
    * Function to check if user exists 
    * 
    * @param email
    * 
    * @return array of inmate (user)
    */
        public function userExists($email) {
        return DB::table($this->table)
                        ->where('email', $email)
                        ->first();
        }

}
