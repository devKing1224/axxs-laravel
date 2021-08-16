<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class InmateConfiguration extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
    */
    protected $table = 'inmate_configurations';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'key', 'value', 'is_deleted',
    ];
    
    /**
     * Function to set the inmate configuration value
     * 
     * @param integer $value 
     * 
     * @return string information set or not
    */
    public function updateConfiguration($key, $data) {
        return DB::table($this->table)
                        ->where('id', $key)
                        ->update([
                            'value' => $data,
        ]);
    }

    /**
     * Function to get specific row by key
     * 
     * @param integer $value 
     * 
     * @return string information set or not
    */
    public function getConfiguration($key) {
        return DB::table($this->table)
                        ->where('key', $key)
                        ->first();
        
    }
}
