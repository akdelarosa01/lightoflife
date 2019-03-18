<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class QuizGivenResults extends Model
{
    protected $fillable = [
    	'student_id',
    	'section_id',
    	'subject_id',
    	'teacher_id',
    	'quiz_id',
        'quiz_given_id',
    	'quiz_title',
    	'quiz_type',
    	'date_taken',
    	'time_taken',
    	'date_submitted',
    	'time_submitted',
    	'total_points',
    	'max_score',
    	'grade_percent',
    	'attempt_no',
    	'remarks'
    ];
}
