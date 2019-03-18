<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EnrollStudent extends Model
{
    protected $fillable = [
    	'program_id',
    	'section_id',
    	'school_year',
    	'no_of_students',
    	'no_of_students_enrolled',
    	'create_user',
    	'update_user'
    ];
}
