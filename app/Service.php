<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;
use DB;

class Service extends Model {

    use Notifiable;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'services';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'service_category_id', 'user_id', 'name', 'base_url', 'logo_url', 'type', 'charge', 'flat_rate', 'flat_rate_charge', 'auto_logout', 'msg', 'sequence', 'is_deleted', 'keyboardEnabled'
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
     * Function to get service and service permission list 
     * 
     * @return  object 
     */
    public function ServiceByUser() {
        return $this->hasMany('App\ServicePermission', 'service_id');
    }

    /**
     * Function to get service and which has service permission 
     * 
     * @return  object 
     */
    public function permission() {
        return $this->hasOne('App\ServicePermission', 'service_id', 'id');
    }

    /**
     * Function to get service and service category
     * 
     * @return  object 
     */
    public function serviceCategory() {
        return $this->belongsto('App\ServiceCategory', 'service_category_id', 'id');
    }

    /*
     * Function to delete service data by id
     * 
     * @return Message staff deleted
     */

    public function deleteService($service_id) {
        return DB::table($this->table)
                        ->where('id', $service_id)
                        ->update(['is_deleted' => '1']);
    }

    /**
     * Function to get 
     * 
     * @return object  service information with category name and logo url
     */
    public function getServiceCategoryInfo() {
        return $this->hasOne('App\ServiceCategory', 'id', 'service_category_id')
                        ->select('name')
                        ->where('is_deleted', 0);
    }

    /**
     * Function to return the services of inmate
     * 
     * @param integer $inmateId The id of inmate
     * 
     * @return array The list of services of a inmate 
     */
    public function getInmateServiceInfo($inmateId) {
        return DB::table($this->table)
                        ->leftJoin('service_permissions', 'service_permissions.service_id', '=', $this->table . '.id')
                        ->leftJoin('service_category', 'service_category.id', '=', $this->table . '.service_category_id')
                        ->select($this->table . '.*', 'service_category.name as Service_category_name')
                        ->where('service_permissions.inmate_id', $inmateId)
                        ->orWhere('service_permissions.inmate_id', 0)
                        ->where($this->table . '.is_deleted', 0)
                        ->orderBy('service_category_id', 'asc')
                        ->get();
    }

    public function getInmateServiceInfoNew($inmateId) {
        return DB::table($this->table)
                        ->leftJoin('service_permissions', 'service_permissions.service_id', '=', $this->table . '.id')
                        ->leftJoin('service_category', 'service_category.id', '=', $this->table . '.service_category_id')
                        ->select($this->table . '.id', $this->table . '.name', $this->table . '.base_url', $this->table . '.logo_url as icon_url', 'service_category.name as subcategory')
                        ->where('service_permissions.inmate_id', $inmateId)
                        ->where($this->table . '.is_deleted', 0)
                        ->orderBy('service_category_id', 'asc')
                        ->get();
    }

    /**
     * Function to return the services of facility set by admin.
     * 
     * @param integer $id The id of facility
     * 
     * @return array The list of services of a inmate 
     */
    public function getServiceFacilityListInfo($id) {
        $permission = DB::table($this->table)
                ->leftJoin('service_permissions', 'service_permissions.service_id', '=', $this->table . '.id')
                ->leftJoin('service_category', 'service_category.id', '=', $this->table . '.service_category_id')
                ->select($this->table . '.*', 'service_category.name as Service_category_name')
                ->where('service_permissions.inmate_id', $id)
                ->where($this->table . '.is_deleted', 0);

        return DB::table($this->table)
                        ->leftJoin('service_category', 'service_category.id', '=', $this->table . '.service_category_id')
                        ->select($this->table . '.*', 'service_category.name as Service_category_name')
                        ->where($this->table . '.is_deleted', 0)
                        ->where($this->table . '.user_id', $id)
                        ->union($permission)
                        ->orderBy('Service_category_name', 'asc')
                        ->orderBy('name', 'asc')
                        ->get();
    }

    /**
     * Function to return the services lsit
     * 
     * @param $active as active 
     * 
     * @return array The list of services
     */
    public function getServiceListInfo($active) {
        return DB::table($this->table)
                        ->leftJoin('service_category', 'service_category.id', '=', $this->table . '.service_category_id')
                        ->select($this->table . '.*', 'service_category.name as Service_category_name')
                        ->where($this->table . '.is_deleted', $active)
                        ->orderBy('name', 'asc')
                        ->get();
    }

    /**
     * Function to return the services info 
     * 
     * @param $service_id for service id 
     *        $active to get all active record
     * 
     * @return array The list of services
     */
    public function getServiceInfo($service_id, $active) {
        return DB::table($this->table)
                        ->leftJoin('service_category', 'service_category.id', '=', $this->table . '.service_category_id')
                        ->select($this->table . '.*', 'service_category.name as Service_category_name')
                        ->where($this->table . '.is_deleted', $active)
                        ->where($this->table . '.id', $service_id)
                        ->first();
    }

    /**
     * Function to return the services info of facility with category name
     * 
     * @param $service_id  for service id 
     *        $active      to get all active record
     *        $facility_id for facility id
     * 
     * @return array The list of services
     */
    public function getfacilityServiceInfo($service_id, $active, $facility_id) {
        return DB::table($this->table)
                        ->leftJoin('service_category', 'service_category.id', '=', $this->table . '.service_category_id')
                        ->select($this->table . '.*', 'service_category.name as Service_category_name')
                        ->where($this->table . '.is_deleted', $active)
                        ->where($this->table . '.id', $service_id)
                        ->where($this->table . '.user_id', $facility_id)
                        ->first();
    }

    /**
     * Function to return the service_id of facility
     * 
     * @param integer $facility_id The id of inmate
     * 
     * @return array The list of services of a facility 
     */
    public function getFacilityServiceID($facility_id) {
        return DB::table($this->table)
                        ->leftJoin('service_permissions', 'service_permissions.service_id', '=', $this->table . '.id')
                        ->leftJoin('service_category', 'service_category.id', '=', $this->table . '.service_category_id')
                        ->select($this->table . '.name',$this->table . '.base_url',$this->table . '.logo_url',$this->table . '.type' ,$this->table . '.charge' ,$this->table . '.auto_logout' ,$this->table . '.msg' , $this->table . '.is_deleted' ,'service_category.name as Service_category_name')
                        ->where('service_permissions.inmate_id', $facility_id)
                        ->orWhere('service_permissions.inmate_id', 0)
                        ->where($this->table . '.is_deleted', 0)
                        ->orderBy('service_category_id', 'asc')
                        ->get();
    }

}
