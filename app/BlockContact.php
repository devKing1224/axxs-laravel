<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class BlockContact extends Model
{
    use Notifiable;
    
    /**
     * The table associated with the model.
     *
     * @var string
    */
    protected $table = 'block_contacts';
    
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'email', 'block','inmate_id', 'is_deleted',
    ];
}