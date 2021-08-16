<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;
use DB;
use App\User;
use App\Facility;

class Family extends Model {

    use Notifiable;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'familys';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'inmate_id', 'first_name', 'last_name', 'email', 'password', 'phone', 'family_user_id', 'facility_user_id',
        'address_line_1', 'address_line_2', 'city', 'state', 'zip', 'is_deleted',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        ''
    ];

    /*
     * Function to delete family data by id
     * 
     * @return Message device deleted
     */

    public function deleteFamily($family_id) {
        return DB::table($this->table)
                        ->where('id', $family_id)
                        ->update(['is_deleted' => '1']);
    }

    /**
     * Function to return the family of inmate
     * 
     * @param integer $inmate_id The id of inmate
     * 
     * @return array The list of family memeber 
     */
    public function getFamilyInfo($inmate_id) {
        return DB::table($this->table)
                        ->leftJoin('users', 'users.id', '=', $this->table . '.family_user_id')
                        ->select($this->table . '.*')
                        ->where($this->table . '.is_deleted', 0)
                        ->where('users.admin_id', $inmate_id)
                        ->where('users.is_deleted', 0)
                        ->get();
    }

    /**
     * Function to return the family information of inmate
     * 
     * @param integer $email The email of inmate
     * 
     * @return array The information of family
     */
    public function getFamilyInfoByUserEmail($email) {
        return DB::table($this->table)
                        ->leftJoin('users', $this->table . '.family_user_id', '=', 'users.id')
                        ->select([ 'users.id', $this->table . '.email'])
                        ->where('users.username', $email)->first();
    }

    /**
     * Function to return the family information of inmate
     * 
     * @param integer $username The username of family
     * 
     * @return array The information of family
     */
    public function getFamilyInfoByUsername($username) {
        $inmate_id = DB::table($this->table)
                        ->leftJoin('users', $this->table . '.family_user_id', '=', 'users.id')
                        ->select('users.admin_id as inmateid', 'users.id as id')
                        ->where('users.username', $username)->first();


        $facility_id = User::where('id', $inmate_id->inmateid)->select('admin_id')->first();
        $email = Facility::where('facility_user_id', $facility_id->admin_id)->select('email')->first();
        return (object) (['id' => $inmate_id->id, 'email' => $email->email]);
    }

    /**
     * Function to return the inactive family of inmate
     * 
     * @param integer $inmate_id The id of inmate
     * 
     * @return array The list of family memeber 
     */
    public function getFamilyInactiveInfo($inmate_id) {
        return DB::table($this->table)
                        ->leftJoin('users', 'users.id', '=', $this->table . '.family_user_id')
                        ->select($this->table . '.*')
                        ->where($this->table . '.is_deleted', 1)
                        ->where('users.admin_id', $inmate_id)
                        ->where('users.is_deleted', 0)
                        ->get();
    }

}
