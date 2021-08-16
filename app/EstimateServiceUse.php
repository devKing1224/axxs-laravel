<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EstimateServiceUse extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'estimate_service_uses';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'facility_id', 'service_id', 'inmate_id', 'date_time' , 'date'
    ];
}
