<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use DB;

class PreApprovedContacts extends Model
{
    
     use Notifiable;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'pre_approved_contacts';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
         'id', 'facility_id','contact_number','name'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
   protected $hidden = [''];


		public function getContactList($id) {

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

    public function preApprovedContact($id) {
        return DB::table($this->table)
                        ->where('id', $id)
                        ->update(['is_deleted' => '1','status' => '1']);
    }


    public function getpreApprovedContact($id){
			return DB::table($this->table)->select($this->table . '.*')->where('id',$id)->get();


		}

     /*
     * Function to delete provider data by id
     * 
     * @return deleted
     */

    public function deleteApprovedContact($id) {
        return DB::table($this->table)
                        ->where('id', $id)
                        ->update(['is_deleted' => '1','status' => '1']);
    }

 
}
