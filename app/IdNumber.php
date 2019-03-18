<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class IdNumber extends Model
{
    protected $fillable = [
    	'code',
		'description',
		'prefix',
		'next_no',
		'next_no_length',
		'month',
		'create_user',
		'update_user'
	];
}
