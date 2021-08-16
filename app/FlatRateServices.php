<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;
use DB;

class FlatRateServices extends Model
{
     use Notifiable;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'flat_rate_services';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
         'user_id', 'service_id','flate_rate',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        ''
    ];

}
