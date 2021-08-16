<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;
use DB;

class Facility extends Model {

    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'facilitys';
    protected $fillable = [
        'facility_id', 'name', 'first_name', 'last_name', 'email', 'phone', 'twilio_number', 'address_line_1', 'address_line_2', 'device_id',
        'city', 'state', 'zip', 'total_inmate', 'facility_admin', 'password', 'is_deleted', 'facility_user_id','tablet_charge','email_charges','sms_charges','cpc_funding', 'cntct_approval' ,'facility_name' , 'location' , 'free_minutes', 'device_status', 'incoming_email_charge', 'create_email', 'terms_condition' ,'attachment_charge','show_email' , 'welcome_msg' , 'in_sms_charge' ,'tablet_charges'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password'
    ];

    /*
     * Function to fetch facility details with facility details
     * 
     * @return object
     */

    public function facilityuser() {
        return $this->belongsTo('App\User', 'facility_user_id', 'id');
    }

    /*
     * Function to retrive details of all users for all facility
     * 
     * @return  object
     */

    public function facilityusers() {
        return $this->hasMany('App\User', 'admin_id', 'facility_user_id')->where('role_id',4);
    }

    /*
     * Function to retrive details of all users with their family member for all facility
     * 
     * @return  object
     */

    public function facilityUsersWithFamily() {
        return $this->hasMany('App\User', 'admin_id', 'facility_user_id')->with('familyInfo')->where('role_id',4);
    }

    /*
     * Function to retrive details of all inactive users
     * 
     * @return  object
     */

    public function facilityinactiveusers() {
        return $this->hasMany('App\User', 'admin_id', 'facility_user_id')->where('is_deleted', 1)->where('role_id',4);
    }

    /*
     * Function to make a facility inactive
     * 
     * @return  object
     */

    public function deleteFacility($facility_id) {
        return DB::table($this->table)
                        ->where('id', $facility_id)
                        ->update(['is_deleted' => '1']);
    }

    /*
     * Function to retrive facility details usinf inmate email id 
     * 
     * @param integer $email The email of inmate
     * 
     * @return  object
     */

    public function getFacilityInfoByInmateEmail($email) {

        return DB::table($this->table)
                        ->leftJoin('users', $this->table . '.facility_user_id', '=', 'users.admin_id')
                        ->select($this->table . '.email', 'users.first_name as inmate_name','users.last_name as inmate_lastname','users.username as inmate_username', 'users.inmate_id as inmate_id', 'users.id')
                        ->where('users.username', $email)->first();
    }

    /*
     * Function to retrive facility details using username only
     * 
     * @param integer $username The username of facility
     * 
     * @return  object
     */

    public function getFacilityInfoByusername($username) {
        return DB::table($this->table)
                        ->leftJoin('users', $this->table . '.facility_user_id', '=', 'users.id')
                        ->select($this->table . '.email', 'users.id')
                        ->where('users.username', $username)->first();
    }

    /**
     * Function to return the facility information of inmate
     * 
     * @param integer $inmate_id The id of inmate
     * 
     * @return array The information of facility 
     */
    public function getFacilityInfoByInmateID($inmate_id) {
        return DB::table($this->table)
                        ->leftJoin('users', $this->table . '.facility_user_id', '=', 'users.admin_id')
                        ->select($this->table . '.email', $this->table . '.twilio_number', 'users.first_name as inmate_name','users.username as username', 'users.inmate_id as inmate_id', 'users.id')
                        ->where('users.id', $inmate_id)->first();
    }

    /**
     * Function to return the facility all information 
     * 
     * @param integer $facilityID The id of facility
     * 
     * @return array The information of facility 
     */
    public function getFacilityAllInfor($facilityID) {
        return DB::table($this->table)
                        ->leftJoin('users', $this->table . '.facility_user_id', '=', 'users.id')
                        ->select([ 'users.username', $this->table . '.*'])
                        ->where($this->table . '.id', $facilityID)->first();
    }


     /**
     * Function to return the facility information of inmate
     * 
     * @param integer $inmate_id The id of inmate
     * 
     * @return array The information of facility 
     */
    public function getFacilityTableChargeByInmateID($inmate_id) {
        return DB::table($this->table)
                        ->leftJoin('users', $this->table . '.facility_user_id', '=', 'users.admin_id')
                        ->select($this->table . '.tablet_charges',$this->table . '.tablet_charge', 'users.inmate_id as inmate_id', 'users.id')
                        ->where('users.id', $inmate_id)->first();
    }

     /**
     * Function to return the facility information of inmate
     * 
     * @param integer $inmate_id The id of inmate
     * 
     * @return array The information of facility 
     */
    public function getFacilityEmailSMSChargeByInmateID($inmate_id) {
        return DB::table($this->table)
                        ->leftJoin('users', $this->table . '.facility_user_id', '=', 'users.admin_id')
                        ->select($this->table . '.email_charges', $this->table . '.sms_charges', 'users.inmate_id as inmate_id', 'users.id')
                        ->where('users.id', $inmate_id)->first();
    }    

    public function fa_admin(){
        return $this->belongsTo('App\FacilityAdmin','facility_admin');
    }


}
