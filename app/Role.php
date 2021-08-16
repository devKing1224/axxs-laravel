<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    const SUPER_ADMIN = 1;
    const FACILITY_ADMIN = 2;
    const FAMILY_ADMIN = 3;

    /**
     * The table associated with the model.
     *
     * @var string
    */
    protected $table = 'roles';
    
    
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',  'is_deleted',
    ];
    
    
}
