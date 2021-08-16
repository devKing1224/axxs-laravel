<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use DB;
use Carbon\Carbon;

class InmateLoggedHistory extends Authenticatable
{
    use Notifiable;

    /**
     * The table associated with the model.
     *
     * @var string
    */
    protected $table = 'inmate_logged_history';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'inmate_id', 'start_date_time', 'api_token' , 'device_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        
    ];
    
    protected $dates = [
        'start_date_time', 'end_date_time', 'created_at', 'updated_at'
    ];
    
    /**
     * Function inmate login time add
     * 
     * @param integer $data The id of inmate
     * 
     * @return array The list of services of a inmate 
    */
    public function createLoginTime($data) {
        InmateLoggedHistory::create([
            'api_token' =>$data['api_token'],
            'device_id' =>$data['device_id'],
            'inmate_id' => $data['inmate_id'],
            'start_date_time' => new Carbon,
        ]);
    }
    
    /**
     * Function to return the users of inmate
     * 
     * @param integer $inmateId The id of inmate
     * 
     * @return array The list of services of a inmate 
    */
 public function updateLogutTime($data) {
         $EndTimeStamp = date('Y-m-d H:i:s', (int) ($data['end_datetime'] / 1000));
        return DB::table($this->table)
                        ->where('api_token', $data['api_token'])
                        ->update([
                            'end_date_time' => $EndTimeStamp,
        ]);
    }
    
    /**
     * Function to return the users of inmate
     * 
     * @param integer $inmate_id The id of inmate
     * 
     * @return array The list of services of a inmate 
    */
    public function calculateLoginTime($inmate_id) {
        return static::
             where('inmate_id', $inmate_id)
            ->whereRaw('date(start_date_time)="'.date('Y-m-d').'"')
            ->whereRaw('end_date_time IS NOT NULL')
            ->selectRaw('TIMESTAMPDIFF(SECOND, start_date_time, end_date_time ) as total')
            ->orderBy('id', 'desc')->first();
    }
    
    /**
     * Function to return the users of inmate
     * 
     * @param integer $inmate_id The id of inmate
     * 
     * @return array The list of services of a inmate 
    */
    public function checkLoggedTimeCurDate($inmate_id) {
        $currentdate = date('Y-m-d H:i:s');
        $currentTime = strtotime($currentdate);

        $firsthalfstart = date('Y-m-d') . " 00:00:00";
        $firsthalfend = date('Y-m-d') . " 11:59:59";
        $starttime = strtotime($firsthalfstart);
        $endtime = strtotime($firsthalfend);

        $secondhalfstart = date('Y-m-d') . " 12:00:00";
        $secondhalfend = date('Y-m-d') . " 23:59:59";

        if (($starttime <= $currentTime && $currentTime <= $endtime)) {
            return static::
                            where('inmate_id', $inmate_id)
                            ->whereRaw('start_date_time >= "' . $firsthalfstart . '" AND start_date_time <= "' . $firsthalfend . '"')
                            ->orderBy('id', 'desc')->first();
        } else {
            return static::
                            where('inmate_id', $inmate_id)
                            ->whereRaw('start_date_time >= "' . $secondhalfstart . '" AND start_date_time <= "' . $secondhalfend . '"')
                            ->orderBy('id', 'desc')->first();
        }

    }
}
