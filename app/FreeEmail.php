<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FreeEmail extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'free_emails';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'provider', 'email'
    ];
}
