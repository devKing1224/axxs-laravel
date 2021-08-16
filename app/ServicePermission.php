<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;
use DB;
use App\DefaultServicePermission;

class ServicePermission extends Model
{
    use Notifiable;
    
    
    /**
     * The table associated with the model.
     *
     * @var string
    */
    protected $table = 'service_permissions';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
    */
    protected $fillable = [
        'service_id', 'inmate_id', 'is_default'
    ];
    
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
    */
    protected $hidden = [
        ''
    ];
    
    
     /**
     * Function to return the inmate service list
     * 
     * @param integer $inmateID The id of inmate
     * 
     * @return array The list of service
    */
    public function registerPermission($data) {
        
        $service_insert = '';
        for($i = 0; $i < count($data['seviceList']); $i++ ) {
            $service_insert = $data['seviceList'][$i];
            $service_insert = ServicePermission::create([
                'inmate_id' => $data['inmate_id'],
                'service_id' => $data['seviceList'][$i],
            ]);
        }

        //Save the service default permissions data into database
        if(!isset($data['defaultseviceList']) || empty($data['defaultseviceList'])) {
            $data['defaultseviceList'] = [];   
        }
            $service_obj = new DefaultServicePermission;
            $service_obj->registerDefaultPermission($data);
        return $service_insert;
    }
    
     /**
     * Function to return the inmate service list
     * 
     * @param integer $inmateID The id of inmate
      *               $service_details is array of service
     * 
     * @return array The list of service
    */
    public function registerAllServicePermission($service_details, $inmate_id) {
        $service_insert = '';
        for($i = 0; $i < count($service_details); $i++ ) {
            $service_insert = $service_details[$i]->id;
            $service_insert = ServicePermission::create([
                'inmate_id' => $inmate_id,
                'service_id' => $service_details[$i]->id,
            ]);
        }
        return $service_insert;
    }
    
    /**
     * Function to return the inmate service list
     * 
     * @param integer $inmateId The id of inmate
     * 
     * @return array The list of service
    */
    public function getInmateServiceList($inmateId) {
        return DB::table($this->table)
                        ->leftJoin('services', $this->table . '.service_id', '=', 'services.id')
                        ->select(
                                'services.*'
                        )
                        ->where($this->table . '.inmate_id', $inmateId)
                        ->get();
    }
    
   

    
    
}
