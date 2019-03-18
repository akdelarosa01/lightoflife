<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SubjectHandle extends Model
{
    protected $fillable = [
        'id_number',
    	'teacher_name',
    	'user_id',
    	'dept_id',
    	'subject_id',
    	'program_id',
    	'program',
    	'section_id',
    	'create_user',
    	'update_user'
    ];
}
