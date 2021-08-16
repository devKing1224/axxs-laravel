<?php

// Inmate Email ID for Email Communicatio.

namespace App;

use Illuminate\Database\Eloquent\Model;

class InmateDetails extends Model {

    protected $table = 'inmatedetails';
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'inmate_id', 'email', 'password'
    ];

    /**
     * Function to get inmate details with his all its emails id
     * 
     * @return object of user details with email id and password
     */
    public function inmate() {
        return $this->belongsTo('App\User', 'inmate_id', 'id');
    }

    /**
     * Function to check whether the inmate is active or not for authentication
     * 
     * @return object of user details with contact person
     */
    public function inmateActive($id) {
        $user = User::where('id', $id)->first();
    
        if ($user && ($user->hasRole('Facility Admin') && $user->hasAnyPermission(['Tablet Launcher Setting', 'Tablet Enable Applications', 'Tablet Edit Setting']) )) {
            $facility = Facility::where('facility_user_id', $user->id)->first();
            if ($facility && $facility->is_deleted == 0) {
                return true;
            } else {
                return false;
            }
        } elseif ($user && (($user->role_id == 4))) {
            $facility = Facility::where('facility_user_id', $user->admin_id)->first();
            if ($facility) {
                if ($user->is_deleted == 1 || $facility->is_deleted == 1) {
                    return false;
                } else {
                    return true;
                }
            } else {
                return false;
            }
        } elseif ($user && ($user->is_deleted == 0) && ($user->hasRole('Super Admin') || $user->hasAnyPermission(['Tablet Launcher Setting', 'Tablet Enable Applications', 'Tablet Edit Setting']))) {
            return true;
        } else {
             return false;
        }
    }

}
