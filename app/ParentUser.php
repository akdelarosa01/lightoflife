<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ParentUser extends Model
{
    protected $fillable = [
    	'user_id',
    	'id_number',
    	'program_id',
    	'student_user_id',
    ];
}
