<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
use App\InmateConfiguration;
use App\Facility;

class InmateChargesHistory extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
    */
    protected $table = 'inmate_charges_history';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'inmate_id', 'service_id', 'inmate_configurations_id', 'transaction', 'transaction_time', 'transaction_date', 'is_deleted'
    ];
    
    /**
     * Function to set the inmate configuration value
     * 
     * @param integer $value 
     * 
     * @return string information set or not
    */
   public function chargesService($data) {
        //return $data['configuration_id'];
        $chargesDetails = InmateConfiguration::where('id', $data['configuration_id'])
                                               ->where('is_deleted', 0)->first();

        if (($data['configuration_id'] == config('axxs.sms_charges')) || ($data['configuration_id'] == config('axxs.email_charges'))) {
            $facility = new Facility;
            $facility_charge = $facility->getFacilityEmailSMSChargeByInmateID($data['inmate_id']);
            //SMS charge per facility
            if ($data['configuration_id'] == config('axxs.sms_charges')) {

                if(isset($facility_charge->sms_charges) && $facility_charge->sms_charges >= 0 )
                {
                    $chargesDetails->value = $facility_charge->sms_charges;
                }
            }
            //Email charge per facility
            if ($data['configuration_id'] == config('axxs.email_charges')) {
                if(isset($facility_charge->email_charges) && $facility_charge->email_charges >= 0 )
                {
                    $chargesDetails->value = $facility_charge->email_charges;
                }
            }
        }

        DB::table('users')
                    ->where('id', $data['inmate_id'])
                    ->update([
                        'balance' => DB::raw('balance-'.$chargesDetails->value),
        ]);
        $chargesDetails->inmate_id = $data['inmate_id'];
        $chargesDetails->service_id = $data['service_id'];
        return $this->registerLog($chargesDetails);
    }
    
    /**
     * Function to set the inmate configuration value
     * 
     * @param integer $data 
     * 
     * @return string information set or not
    */
    public function registerLog($data) {
        $inmte_charges_history = InmateChargesHistory::create([
            'inmate_id' => $data['inmate_id'],
            'service_id' => $data['service_id'],
            'inmate_configurations_id' => $data['id'],
            'transaction_id' => bin2hex(openssl_random_pseudo_bytes(16)),
            'transaction' => $data['value'],
            'transaction_time' => date('H:i:s'),
            'transaction_date' => date('m-Y-d'),
            'is_deleted' => 0,
        ]);
        if($inmte_charges_history->id) {
           return $inmte_charges_history->id;
        }return 'error';
    }
    
    /**
     * Function get the history of all charges 
     * 
     * @param integer $inmate_id keyed to inmat ID 
     *                $date for date
     *                $startTime for start time 
     *                $endTime for end time log
     * 
     * @return object
    */
    public function getChargeHistory($inmate_id, $date, $startTime, $endTime ) {
        return DB::table($this->table)
            ->whereRaw("transaction_time between '$startTime' and '$endTime'")
            ->where(['transaction_date' => $date, 'inmate_id' => $inmate_id])->sum('transaction');
    }
}
