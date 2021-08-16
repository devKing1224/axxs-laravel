<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MusicGenre extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'music_genres';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'genres',
    ];
}
