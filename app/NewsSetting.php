<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NewsSetting extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'news_settings';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'facility_id', 'allow_search', 'country', 'category', 'n_limit', 'news_per_page'
    ];
}
