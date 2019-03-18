<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    protected $fillable = [
    	'code',
    	'description',
    	'program_id',
    	'dept_id',
    	'create_user',
    	'update_user'
    ];
}
