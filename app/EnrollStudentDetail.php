<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EnrollStudentDetail extends Model
{
    protected $fillable = [
    	'es_id',
    	'program_id',
    	'section_id',
    	'user_id',
    	'id_number'
    ];
}
