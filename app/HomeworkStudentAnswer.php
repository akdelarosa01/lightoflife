<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HomeworkStudentAnswer extends Model
{
    protected $fillable = [
    	'hw_given_id',
    	'hw_id',
    	'student_id',
    	'section_id',
    	'answer',
    	'status',
    	'date_given',
    	'date_submitted'
    ];
}
