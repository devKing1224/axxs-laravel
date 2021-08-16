<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SoundCloudAPI extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'soundcloud_settings';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'facility_id', 'allow_search', 'genres', 's_uptodate', 's_limit'
    ];
}
