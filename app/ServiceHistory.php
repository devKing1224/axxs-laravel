<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use DB;

class ServiceHistory extends Authenticatable
{
    use Notifiable;

    /**
     * The table associated with the model.
     *
     * @var string
    */
    protected $table = 'service_history';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'inmate_activity_history_id', 'inmate_id', 'service_id', 'transaction_id', 'type', 'duration', 'rate', 'charges', 'free_minutes_used'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
    ];
}
