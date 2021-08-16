<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BlockService extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
    */
    protected $table = 'block_services';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'inmate_id','start_date', 'end_date', 'status'
    ];
}
