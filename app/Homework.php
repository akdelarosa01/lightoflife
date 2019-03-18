<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Homework extends Model
{
    protected $fillable = [
    	'user_id',
    	'id_number',
    	'subject_id',
    	'title',
    	'question',
    	'points',
    	'create_user',
    	'update_user'
    ];
}
