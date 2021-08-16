<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class ServiceBooks extends Model
{
    use Notifiable;
    
    /**
     * The table associated with the model.
     *
     * @var string
    */
    protected $table = 'service_books';
    
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'url', 'is_deleted',
    ];
}
    