<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $fillable = [
        'subject_id',
    	'subject_msg',
    	'user_id_from',
    	'to_read',
    	'date_sent',
    	'deleted_from',
    	'deleted_to',
    	'create_user',
    	'update_user'
    ];
}
