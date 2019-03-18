<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
    protected $fillable = [
    	'subject_id',
    	'user_id_from',
    	'user_id_to',
    	'to_read',
    	'message',
    	'date_sent',
    	'create_user',
    	'update_user'
    ];
}
