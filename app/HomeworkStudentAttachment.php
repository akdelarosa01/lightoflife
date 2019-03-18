<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HomeworkStudentAttachment extends Model
{
    protected $fillable = [
    	'hw_id',
    	'student_id',
    	'filename',
    	'fullpath',
    ];
}
