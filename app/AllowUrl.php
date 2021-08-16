<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use DB;
class AllowUrl extends Model
{
    use Notifiable;
    
    /**
     * The table associated with the model.
     *
     * @var string
    */
    protected $table = 'allow_urls';
    
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'url',  'is_deleted',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        ''
    ];

        public function getUrlList() {

            return DB::table($this->table)->where('is_deleted', 0)
                                      ->select($this->table . '.*')->get();
        }

        public function getUrlinfo($id){

            return DB::table($this->table)->select($this->table . '.*')->where('id',$id)->get();

        }

        public function deleteUrl($id) {

               return DB::table($this->table)
                        ->where('id', $id)
                        ->update(['is_deleted' => '1']);
                        
    }


     public function getInactiveUrlList() {

            return DB::table($this->table)->where('is_deleted', 1)
                                      ->select($this->table . '.*')->get();
        }


    public function getAllowUrlList() {

        return DB::table($this->table)->where('is_deleted', 0)
                                  ->select('url')->get();
    }

}