<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use DB;

class InmateReportHistory extends Authenticatable
{
    use Notifiable;

    /**
     * The table associated with the model.
     *
     * @var string
    */
    protected $table = 'inmate_report_history';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'inmate_id', 'report_time', 'is_deleted', 'status'
    ];

   
    
    /**
     * Function to return the inmate login report historty but behalf on login facility id 
     * 
     * @param integer $facilityId The id of facility.
     * 
     * @return array The list of report login of facility.
    */
    public function getInmateLoginReportList($facilityId,$inmate_id =null) {
        $getDisputereport = DB::table($this->table)
                        ->leftJoin('users', 'users.id',  '=', $this->table.'.inmate_id')
                        ->leftJoin('facilitys', 'facilitys.facility_user_id',  '=', 'users.admin_id')
                         ->select( $this->table.'.*', 'facilitys.name as facility_name', 'users.first_name','users.last_name','users.middle_name','users.date_of_birth' )
                        ->where('users.admin_id', $facilityId)
                        ->where('facilitys.facility_user_id', $facilityId)
                        ->where($this->table.'.is_deleted', 0);
                        
        if ($inmate_id != null) {
                          $getDisputereport = $getDisputereport->where($this->table.'.inmate_id', '=' ,$inmate_id);
                      }              
        return $getDisputereport->get();
    }
    
    /**
     * Function to return the inmate login report historty for superadmin case.
     * 
     * @param 
     * 
     * @return array The list of all inmate login report.
    */
    public function getAllInmateLoginReportList($inmate_id =null) {
        $getDisputereport =  DB::table($this->table)
                        ->leftJoin('users', 'users.id',  '=', $this->table.'.inmate_id')
                        ->leftJoin('facilitys', 'facilitys.facility_user_id',  '=', 'users.admin_id')
                        ->select( $this->table.'.*', 'facilitys.name as facility_name', 'users.first_name','users.last_name','users.middle_name','users.date_of_birth')
                        ->where($this->table.'.is_deleted', 0)
                        ->where('facilitys.is_deleted', 0);
        if ($inmate_id != null) {
                          $getDisputereport = $getDisputereport->where($this->table.'.inmate_id', '=' ,$inmate_id);
        }
        return $getDisputereport->orderby('id','DESC')->get();
    }
    
    /*
     * Function to delete inmate login report data by id
     * 
     * @return Message rport history deleted
     */
    public function deleteInmateLoginReport ($report_id) {
        return DB::table($this->table)
            ->where('id', $report_id)
            ->update(['is_deleted' => '1']);
    }
    
     /*
     * Function to update status inmate report data by id
     * 
     * @return value(success/failure) rport status update 
     */
        public function updateInmateReportStatus ($report_id) {
        return DB::table($this->table)
            ->where('id', $report_id)
            ->update(['status' => '0', 'active_time' => date('Y-m-d H:i:s')]);    
    }   
}
