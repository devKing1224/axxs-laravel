<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EmailAttachment extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'email_attachments';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'email_id', 'link', 'status', 'type'
    ];
}
