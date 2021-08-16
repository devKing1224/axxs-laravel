<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class InmateSetMaxContact extends Model {

    /**
     * The table payment_information with the model.
     *
     * @var string
     */
    protected $table = 'set_max_contact';
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'max_email', 'max_phone'
    ];

    /**
     * Function get facility details of max contact details
     * 
     * @return object 
     */
    public function facility() {
        return $this->belongsTo('App\User', 'user_id', 'id');
    }

    /**
     * Function get retrieve inmate number limit set by facility
     * 
     * @return object 
     */
    public function FetchInmateNumberLimit($id) {
        $facility_id = DB::table('users')->where('id', $id)->select('admin_id')->first();
        $fetchLimitfacility = DB::table($this->table)
                ->where('user_id', $facility_id->admin_id)
                ->first();
        $fetchMaxInmate = DB::table($this->table)
                ->where('user_id', $id)
                ->first();
        if ($fetchLimitfacility) {
            if ($fetchLimitfacility->max_phone) {
                return (array(
                    'max_phone' => $fetchLimitfacility->max_phone
                ));
            } else {
                if ($fetchMaxInmate) {
                    if ($fetchMaxInmate->max_phone) {
                        return (array(
                            'max_phone' => $fetchMaxInmate->max_phone,
                        ));
                    }
                } else {
                    return (array(
                        'max_phone' => '0',
                    ));
                }
            }
        } else {
            if ($fetchMaxInmate) {
                return (array(
                    'max_phone' => $fetchMaxInmate->max_phone,
                ));
            } else {
                return (array(
                    'max_phone' => '0',
                ));
            }
        }
    }

    /**
     * Function get retrieve inmate email limit set by facility
     * 
     * @return object 
     */
    public function FetchInmateEmailLimit($id) {
        $facility_id = DB::table('users')->where('id', $id)->select('admin_id')->first();
        $fetchLimitfacility = DB::table($this->table)
                ->where('user_id', $facility_id->admin_id)
                ->first();
        $fetchMaxInmate = DB::table($this->table)
                ->where('user_id', $id)
                ->first();
        if ($fetchLimitfacility) {
            if ($fetchLimitfacility->max_email) {
                return (array(
                    'max_email' => $fetchLimitfacility->max_email
                ));
            } else {
                if ($fetchMaxInmate) {
                    if ($fetchMaxInmate->max_email) {
                        return (array(
                            'max_email' => $fetchMaxInmate->max_email,
                        ));
                    }
                } else {
                    return (array(
                        'max_email' => '0',
                    ));
                }
            }
        } else {
            if ($fetchMaxInmate) {
                return (array(
                    'max_email' => $fetchMaxInmate->max_email,
                ));
            } else {
                return (array(
                    'max_email' => '0',
                ));
            }
        }
    }

    /**
     * Function to calculate limit left for email
     * 
     * @return object 
     */
    public function LimitLeftForEmail($id) {
        $totalemail = DB::table('inmate_contacts')
                ->where('inmate_id', $id)
                ->where('type', 'email')
                ->where('is_deleted', 0)
                ->count('email_phone');
        $emailmaxlimit = $this->FetchInmateEmailLimit($id)['max_email'];
        if ($emailmaxlimit) {
            return ($emailmaxlimit - $totalemail);
        } else {
            return $emailmaxlimit;
        }
    }

    /**
     * Function to calculate limit left for number
     * 
     * @return object 
     */
    public function LimitLeftForNumber($id) {
        $totalnumber = DB::table('inmate_contacts')
                ->where('inmate_id', $id)
                ->where('type', 'phone')
                ->where('is_deleted', 0)
                ->count('email_phone');
        $phonemaxlimit = $this->FetchInmateNumberLimit($id)['max_phone'];
        if ($phonemaxlimit) {
            return ($phonemaxlimit - $totalnumber);
        } else {
            return $phonemaxlimit;
        }
    }

}
