<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Quiz extends Model
{
    protected $fillable = [
    	'subject_id',
    	'user_id',
    	'id_number',
    	'quiz_title',
    	'quiz_type',
    	'no_of_items',
    	'no_of_choices',
    	'create_user',
    	'update_user'
    ];
}
