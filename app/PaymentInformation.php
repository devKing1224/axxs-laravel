<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class PaymentInformation extends Model
{
    /**
     * The table payment_information with the model.
     *
     * @var string
    */
    protected $table = 'payment_information';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'family_id' , 'payment_status', 'transaction_id', 'client_email','client_name', 'inmate_id', 'amount', 'payemet_details'
    ];
    
     /**
     * Function to return the facility all information 
     * 
     * @param integer $inmate_id The id of facility
     * 
     * @return array The information of facility 
    */
    public function getPaymentInformation($inmate_id) {
        return DB::table($this->table)
                        ->select('id','family_id','client_name as family_name','amount','inmate_id')
                        ->where($this->table.'.inmate_id', $inmate_id)->get();               
    }
    
}
