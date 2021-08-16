<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ServiceCategory extends Model {

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'service_category';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'is_deleted', 'icon_url', 'sequence'];
    
    public function permission(){
       return Service::hasOne('App\ServicePermission', 'service_id');
    }

    public function subcategory() {
        return $this->hasMany('App\Service', 'service_category_id')
                ->where('is_deleted', 0)
                ->orderBy('sequence');
    }

}
