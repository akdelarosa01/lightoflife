<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class QuizItem extends Model
{
    protected $fillable = [
    	'quiz_id',
    	'question',
    	'answer',
    	'points',
    	'question_num',
    	'quiz_type'
    ];
}
