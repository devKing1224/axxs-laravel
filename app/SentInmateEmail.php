<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;
use DB;

class SentInmateEmail extends Model {

    use Notifiable;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'inmate_sent_email';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'inmate_id', 'to', 'body', 'subject', 'is_deleted','blacklisted','is_ignored','is_viewed'
    ];

    /**
     * Function  move sent mail with user details
     * 
     * @return object 
     */
    public function inmate() {
        return $this->belongsTo('App\User', 'inmate_id', 'id');
    }

    /**
     * Function  move sent mail to trash
     * 
     * @return object 
     */
    public function deleteEmail($email_id) {
        return DB::table($this->table)
                        ->where('id', $email_id)
                        ->update(['is_deleted' => '1']);
    }

    /**
     * Function get the list of all sent emails
     * 
     * @return object 
     */
    public function getInmateEmailList($inmateId) {
        $type = 'sent';
        $sentemails = DB::table($this->table)
                ->where('inmate_id', $inmateId)
                ->select('id', 'inmate_id','blacklisted','to as emailid', 'body as message', 'subject','created_at', DB::raw('(1) as type'));


        $recievedemail = DB::table('incoming_emails')
                ->where('to_inmateid', $inmateId)
                ->select('id', 'to_inmateid as inmate_id','is_blacklisted as blacklisted', 'from as emailid', 'html as message', 'subject','created_at', DB::raw('(2) as type'))
                ->union($sentemails)
                ->orderBy('created_at', 'desc')
                ->get();
        return $recievedemail;
    }

    /**
     * Function get the list of all sent emails
     * 
     * @return object 
     */
    public static function getBlacklistedFunction($email_id) {
        $data = SentInmateEmail::find($email_id);
        $blacklisted = "";
        if(!empty($data->blacklisted) && empty($data->is_ignored)) {
            $blacklisted = $data->blacklisted;
        }
        return $blacklisted;
    }

        /**
         * Function get the list of all sent emails
         * 
         * @return object 
         */
        public static function getBlacklistedbyinmate($id) {
            $data = SentInmateEmail::where(['inmate_id' => $id,'is_ignored' => 0, 'is_deleted' => 0, 'blacklisted' =>1])->get()->toArray();
            return $data;
        }



}
