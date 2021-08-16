<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use DB;

class UserAnswer extends Model
{

     use Notifiable;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'user_answers';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
         'id', 'user_id', 'question_id','answer',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        ''
    ];

    

}
