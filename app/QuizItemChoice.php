<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class QuizItemChoice extends Model
{
    protected $fillable = [
    	'quiz_id',
    	'quiz_item_id',
    	'choice',
    	'choice_desc'
    ];
}
