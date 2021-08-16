<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use DB;

class InmateActivityHistory extends Authenticatable {

    use Notifiable;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'inmate_activity_history';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'inmate_id', 'service_id', 'start_datetime', 'end_datetime', 'inmate_logged_history_id', 'exit_reason'
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
     * Function to return  the inmate details of all the services
     * 
     * @return array The list of services of a inmate 
     */
    public function vendorDetails() {
        return $this->hasOne('App\ServiceHistory', 'inmate_activity_history_id', 'id');
    }
    
    /**
     * Function to return  the inmate details of all the services
     * 
     * @return array The list of services of a inmate 
     */
    public function vendorDetailsNames() {
        return $this->hasOne('App\Service', 'id', 'service_id');
    }

    /**
     * Function to return the inmate activity history.
     * 
     * @param integer $inmateId The id of inmate
     * 
     * @return array The list of services of a inmate 
     */
    public function getInmateActivityHistory($inmateId) {
        $table_name = $this->table;
        $inmate_activity_history = DB::table($table_name)
                ->leftJoin('services', 'services.id', '=', $table_name . '.service_id')
                /*->leftJoin('inmate_charges_history', function($join) use($table_name) {
                    $join->on('inmate_charges_history.service_id', $table_name . '.service_id');
                    $join->on('inmate_charges_history.inmate_id', $table_name . '.inmate_id');
                })*/
                ->leftJoin('service_history', 'service_history.inmate_activity_history_id', $this->table . '.id')
                ->select($this->table . '.*', 'service_history.charges', 'service_history.type','service_history.duration','service_history.free_minutes_used','service_history.rate','services.name','service_history.transaction_id')
                ->where($this->table . '.inmate_id', $inmateId)
                ->get();
        
        return $inmate_activity_history;
    }

     /**
     * Function to get service history with consumed minutes in details 
     * 
     * @return  object 
     */
    public function getservicehistorydetailsfacility($facility_id,$s_date = null,$e_date = null){
        $query = DB::table($this->table)
                ->select('services.name',DB::raw("ROUND(SUM(service_history.duration)/60,2) as total_duration"), DB::raw("SUM(service_history.charges) as charges"),DB::raw("SUM(service_history.free_minutes_used) as free_minutes_used"))
                ->join('users','users.id','=',$this->table.'.inmate_id')
                ->join('services','services.id','=',$this->table.'.service_id')
                ->join('service_history','service_history.inmate_activity_history_id','=',$this->table.'.id')
                ->where('users.admin_id', '=', $facility_id)
                ->whereBetween('inmate_activity_history.start_datetime', [$s_date, $e_date])
                ->groupBy('services.id')
                ->orderBy('services.name', 'asc')
                ->get();

                return $query;
        }

    public function getservicehistorydetails($id){
        $query = DB::table($this->table)
                ->select('services.name',DB::raw("ROUND(SUM(service_history.duration)/60,2) as total_duration"), DB::raw("SUM(service_history.charges) as charges"),DB::raw("SUM(service_history.free_minutes_used) as free_minutes_used"))
                ->join('users','users.id','=',$this->table.'.inmate_id')
                ->join('services','services.id','=',$this->table.'.service_id')
                ->join('service_history','service_history.inmate_activity_history_id','=',$this->table.'.id')
                ->where($this->table.'.inmate_id', '=', $id)
                ->groupBy('services.id')
                ->orderBy('services.name', 'asc')
                ->get();

                return $query;
        }
    

}
