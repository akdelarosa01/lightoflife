<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HomeworkGiven extends Model
{
    protected $fillable = [
    	'hw_id',
    	'section_id',
    	'subject_id',
    	'teacher_id',
    	'title',
    	'due_date',
    	'due_time',
    	'date_given',
    	'create_user',
    	'update_user'
    ];
}
