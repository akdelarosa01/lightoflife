<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class QuizGiven extends Model
{
    protected $fillable = [
    	'student_id',
    	'section_id',
    	'subject_id',
    	'quiz_id',
    	'quiz_title',
        'quiz_type',
    	'teacher_id',
    	'start_date',
    	'start_time',
    	'due_date',
    	'due_time',
    	'timer',
    	'max_attempt',
    	'user_attempt',
    	'instruction',
    	'late_submission',
    	'max_score',
    	'status',
    	'create_user',
    	'update_user'
    ];
}
