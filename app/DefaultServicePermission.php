<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;
use DB;
use App\User;
use App\ServicePermission;
use Carbon\Carbon;

class DefaultServicePermission extends Model
{
    use Notifiable;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'default_service_permissions';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
         'service_id', 'facility_id',
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
    public function registerDefaultPermission($data) {
        
        $service_insert = '';
        $user_obj = new User;
        

        $existingDefault = DefaultServicePermission::select('service_id')->where('facility_id', $data['inmate_id'])->get()->toArray();
    
        $existingDefaultService = [];
        foreach ($existingDefault as $defaultsevice) {

            $existingDefaultService[] = $defaultsevice['service_id'];
    
        }
        $deleteService = array_diff($existingDefaultService,$data['defaultseviceList']);
        $createServices = array_diff($data['defaultseviceList'],$existingDefaultService);

        if (count($deleteService)) {
            DefaultServicePermission::where('facility_id', $data['inmate_id'])
            ->whereIn('service_id',$deleteService)->delete();
        }

       if (count($createServices)) {
           foreach($createServices as $createService){
                    $service_insert = DefaultServicePermission::create([
                    'facility_id' => $data['inmate_id'],
                    'service_id' => $createService,
                 ]);
            }
        }

        if(count($deleteService) || count($createServices))
        {   
            $facility_services = DefaultServicePermission::select('service_id')->where('facility_id', $data['inmate_id'])->get()->toArray();
            $facility_users = User::select('id as users_id')->where('admin_id', $data['inmate_id'])->get()->toArray();
            
            //initialize array
            $inserts = [];
            $delete = [];
            ini_set('memory_limit', '-1');
            if(count($facility_users)>0){
                foreach ($facility_users as $key => $users) {
                        $delete[]=$users['users_id'];
                        
                    foreach ($facility_services as $key => $services) {
                                        
                                        $service_id =$services['service_id'];
                                        $inserts[] =[
                                            'service_id' => $service_id,
                                            'inmate_id' => $users['users_id'],
                                            'is_default' => 1,
                                            'created_at'        => Carbon::now(),
                                            'updated_at'        => Carbon::now(),
                                        ];
                            }
                }

            $inserts = collect($inserts);
            $chunks = $inserts->chunk(500);

            DB::table('service_permissions')->whereIn('inmate_id',$delete )->delete();
            foreach ($chunks as $chunk)
            {
               DB::table('service_permissions')->insert($chunk->toArray());
            }
                
            }

        }

        return $service_insert;
    }
    
   /**
     * Function to return the services of inmate
     * 
     * @param integer $inmateId The id of inmate
     * 
     * @return array The list of services of a inmate 
     */
    public function getInmateDefaultServiceInfo($inmateId) {
        return DB::table($this->table)
                        ->where('facility_id', $inmateId)->get();
    }

    /**
     * Function to return the services of facility
     * 
     * @param integer $facility_id The id of inmate
     * 
     * @return array The list of services of a facility 
     */
    public function getFacilityDefaultServiceInfo($facility_id) {
        return DB::table($this->table)
                        ->leftjoin('services','services.id',$this->table.'.service_id')
                        ->where('facility_id', $facility_id)->get()->pluck('name');
    }


}
