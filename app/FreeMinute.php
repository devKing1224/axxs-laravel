<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class FreeMinute extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
    */
    protected $table = 'free_minutes';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'inmate_id', 'left_minutes'
    ];

    /**
     * Function to return the users of inmate
     * 
     * @param integer $inmateId The id of inmate
     * 
     * @return array The list of services of a inmate 
    */
    public function updateCalculatedLeftTime($data, $totalLefTime) {
        return DB::table($this->table)
                        ->where('inmate_id', $data['inmate_id'])
                        ->update([
                            'left_minutes' => $totalLefTime
        ]);
    }
}
