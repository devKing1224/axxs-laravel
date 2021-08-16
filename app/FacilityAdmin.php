<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
class FacilityAdmin extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'facility_admins';
    protected $fillable = [
        'fa_id', 'name', 'first_name', 'last_name', 'email', 'phone','address_line','city', 'state', 'zip', 'total_facility','password', 'is_deleted', 'fa_user_id','fa_name' , 'location' , 'company_id'
    ];

    /**
     * Function to return the facility all information 
     * 
     * @param integer $facilityID The id of facility
     * 
     * @return array The information of facility 
     */
    public function getFacilityAllInfor($facilityID) {
        return DB::table($this->table)
                        ->leftJoin('users', $this->table . '.fa_user_id', '=', 'users.id')
                        ->select([ 'users.username', $this->table . '.*'])
                        ->where($this->table . '.id', $facilityID)->first();
    }

    public function company(){
        return $this->belongsTo('App\Company','company_id');
    }
}
