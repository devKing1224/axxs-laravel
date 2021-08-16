<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use DB;

class Admin extends Model
{
    
    use Notifiable;
    
    /**
     * The table associated with the model.
     *
     * @var string
    */
    protected $table = 'admins';
    
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'email', 'admin_user_id', 'name', 'auto_logged_time', 'phone'
    ];
    
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password'
    ];
    
    
    /*
     * Function to update auto logged time data by id
     * 
     * @return Message staff deleted
    */
    public function updateAutoLoggedTime ($time) {
        return DB::table($this->table)
            ->where('id', 1)
            ->update(['auto_logged_time' => $time]);    
    }
    
    /*
     * Function to get super admin information by username
     * 
     * @param  $username is the username of superadmin
     * 
     * @return details in object
    */
    public function getSuperAdminInfoByUserNameEmail($username){
        return DB::table($this->table)
            ->where('id', 1)
            ->first(); 
        
    }
}
