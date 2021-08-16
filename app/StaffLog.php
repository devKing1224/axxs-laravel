<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;


class StaffLog extends Model
{
     use Notifiable;
     public $timestamps = false;
     
     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'staff_id', 'action', 'page', 'action_id','description'
    ];
    
    
    
    /*
     * Function to get insert activity performed bv staff
     * 
     * @param  $staff_id is the staff id,
     * @param  $action is the view, delete, add or update,
     * @param  $page is the page type like user, device,
     * @param  $action_id is the id for action
     * @param  $description is the message to describe the action,
     * 
     * @return bollean
     */

    public function staffLogInsert($staff_id, $action, $page, $action_id, $description) {

        $staff = StaffLog::create([
                    'staff_id'      => $staff_id,
                    'action'        => $action,
                    'page'          => $page,
                    'action_id'     => $action_id,
                    'description'   => $description
        ]);

        if (isset($staff->id)) {
            return $staff->id;
        } else {
            return false;
        }
    }
}