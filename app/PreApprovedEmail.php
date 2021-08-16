<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use DB;

class PreApprovedEmail extends Model
{
     use Notifiable;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'pre_approved_emails';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
         'id', 'facility_id','email_phone','is_approved','varified','name'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
   protected $hidden = [
        ''
    ];

		public function getEmailList($id) {

			return DB::table($this->table)
			 ->where('is_deleted', 0)
			 ->where('facility_id',$id)
			->select($this->table . '.*')->get();
		}

       /*
     * Function to delete provider data by id
     * 
     * @return deleted
     */

    public function preApprovedEmail($id) {
        return DB::table($this->table)
                        ->where('id', $id)
                        ->update(['is_deleted' => '1','status' => '1']);
    }


    public function getpreApprovedEmail($id){
			return DB::table($this->table)->select($this->table . '.*')->where('id',$id)->get();


		}



}
