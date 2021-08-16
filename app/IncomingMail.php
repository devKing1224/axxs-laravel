<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
class IncomingMail extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'incoming_emails';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'to', 'subject', 'from', 'plain', 'html', 'reply', 'is_blacklisted',
    ];

    public function attachments(){

        return $this->hasMany('App\EmailAttachment', 'email_id', 'id');

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

    /**
     * Function get recieved mails with user details
     * 
     * @return object 
     */
    public function inmate() {
        return $this->belongsTo('App\User', 'to_inmateid', 'id');
    }

    public function fac_attach(){

        return $this->hasMany('App\EmailAttachment', 'attachment_id', 'attachment_id');

    }
}
