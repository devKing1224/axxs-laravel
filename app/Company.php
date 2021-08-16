<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class Company extends Model
{	

	protected $table = 'companies';

    protected $fillable = [
        'name', 'description',
    ];

    /**
     * Function to return the company all information 
     * 
     * @param integer $companyID The id of facility
     * 
     * @return array The information of facility 
     */
    public function getCompanyAllInfor($company_id) {
        return DB::table($this->table)
                        ->select($this->table . '.*')
                        ->where($this->table . '.id', $company_id)->first();
    }
}
