<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use DB;

class BlackListedWord extends Model
{
     use Notifiable;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'black_listed_words';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
         'id', 'blacklisted_words','addedbyuser_id',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        ''
    ];
		public function getBlacklistedWord() {
			return DB::table($this->table)->select($this->table . '.*')->get();
		}

		public function getBlacklistedinfo($id){
			return DB::table($this->table)->select($this->table . '.*')->where('id',$id)->get();


		}


		public function deleteBlacklistedWord($id) {
        return DB::table($this->table)->where('id', $id)->delete();
                        
    }


}
