<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class QuizGivenResultItem extends Model
{
    protected $fillable = [
        'qgr_id',
    	'student_id',
    	'section_id',
    	'subject_id',
    	'teacher_id',
    	'quiz_id',
        'question_num',
    	'question',
    	'correct_answer',
    	'student_answer',
    	'score',
    	'max_score'
    ];
}
