<?php

namespace App;
use DB;
use Illuminate\Database\Eloquent\Model;

class InmateSMS extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
    */
    protected $table = 'inmate_sms';
    public $timestamps = false; 
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'inmate_id' , 'contact_number', 'message', 'bound','blacklisted','is_ignored','is_viewed'
    ];
    
    /**
     * Function to get inmate sms with all his details
     * 
     * @return object of user and users's sms
    */
     public function inmate(){
        return $this->belongsTo('App\User', 'inmate_id', 'id');
    }
    
    /**
     * Function to get list of inmate contact person where phone numbers are same
     * 
     * @return object of user's sms details with contact person 
    */
    public function contactperson(){
        return $this->belongsTo('App\InmateContacts', 'contact_number', 'email_phone');
    }
    
    /**
     * Function to make the SMS inactive
     * 
     * @return object of delted SMS
     */
    public function deleteSMS ($sms_id, $delete) {
        return DB::table($this->table)
            ->where('id', $sms_id)
            ->update(['is_deleted' => $delete]);    
    }

    /**
         * Function get the list of all sent emails
         * 
         * @return object 
         */
        public static function getBlacklistedsmsbyinmate($id) {
            $data = InmateSMS::where(['inmate_id' => $id,'is_ignored' => 0, 'is_deleted' => 0, 'blacklisted' =>1])->get()->toArray();
            return $data;
        }


}
