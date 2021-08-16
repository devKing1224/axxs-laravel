<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class RecievedInmateEmail extends Model {

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'inmate_recieved_email';
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'inmate_id', 'recieved_time', 'is_deleted', 'subject', 'from_name', 'from_email', 'message',
    ];

    /**
     * Function get recieved mails with user details
     * 
     * @return object 
     */
    public function inmate() {
        return $this->belongsTo('App\User', 'inmate_id', 'id');
    }

    /**
     * Function  move recieved mail to trash
     * 
     * @return object 
     */
    public function deleteEmail($email_id, $delete) {
        return DB::table($this->table)
                        ->where('id', $email_id)
                        ->update(['is_deleted' => $delete]);
    }

}
