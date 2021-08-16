<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InmateContacts extends Model {

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'relation', 'email_phone', 'urltoken', 'type', 'inmate_id', 'facility_id', 'is_approved', 'varified'];
    public $timestamps = false;

    /**
     * Function to get inmate details with his all contacts
     * 
     * @return object of user details with contact person
     */
    public function inmate() {
        return $this->belongsTo('App\User', 'inmate_id', 'id');
    }

    /**
     * Function to get facility details
     * 
     * @return object of facility details with all the contact person of his all inmates
     */
    public function facility() {
        return $this->belongsTo('App\User', 'facility_id', 'id');
    }

    /**
        
         * 
         * @return object 
         */
        public static function getVarificationInmate($id) {
            $data = InmateContacts::where(['inmate_id' => $id,'is_approved' => 0, 'varified' => 1])->get()->toArray();
            return $data;
        }


}
