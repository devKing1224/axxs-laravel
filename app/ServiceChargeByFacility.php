<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ServiceChargeByFacility extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'service_charge_by_facilities';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['service_id', 'type', 'facility_id', 'charge', 'service_msg'];
}
