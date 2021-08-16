<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;
use DB;

class Device extends Model {

    use Notifiable;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'devices';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'imei', 'facility_id', 'device_id', 'device_provider', 'is_deleted', 'device_password'
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
     * Function to delete device data by id
     * 
     * @return Message device deleted
     */

    public function deleteDevice($device_id) {
        $device = DB::table($this->table)
                ->where('id', $device_id)
                ->first();
        $user = User::where('device_id', $device->id)->get();
        if(count($user)>0) {
            return null;
        }
        else {
            return DB::table($this->table)
                        ->where('id', $device_id)
                        ->update(['is_deleted' => '1']);
        }
         
    }

    /**
     * Function to return the users of inmate
     * 
     * @param integer $inmateId The id of inmate
     * 
     * @return array The list of services of a inmate 
     */
    public function getDeviceInfo($facility_id) {
        return DB::table($this->table)
                        ->join('facilitys', 'facilitys.id', '=', $this->table . '.facility_id')
                        ->select($this->table . '.*', 'facilitys.facility_name')
                        //->where( $this->table.'.facility_id', 'facilitys.id' )
                        ->where($this->table . '.is_deleted', 0)
                        ->where('devices.facility_id',$facility_id)
                        ->orderBy('facility_name','ASC')
                        ->get();
    }
    /**
     * Function to return the users of inmate
     * 
     * @param integer $search key 
     * 
     * @return array The list of services of a inmate 
     */
    public function searchDevice($keyword) {
        return DB::table($this->table)
                        ->join('facilitys', 'facilitys.id', '=', $this->table . '.facility_id')
                        ->select($this->table . '.*', 'facilitys.facility_name')
                        ->where(function ($query) use($keyword){
                            $query->where('imei', 'like', '%' . $keyword . '%')
                            ->orWhere('device_provider', 'like', '%' . $keyword . '%');
                        })
                        //->where( $this->table.'.facility_id', 'facilitys.id' )
                        ->where($this->table . '.is_deleted', 0)
                        ->orderBy('facility_name','ASC')
                        ->get();
    }

}
