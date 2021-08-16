<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class APISetting extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'api_settings';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'api_name', 'key_name', 'value',
    ];
}
