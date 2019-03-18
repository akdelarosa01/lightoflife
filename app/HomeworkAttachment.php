<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HomeworkAttachment extends Model
{
    protected $fillable = [
    	'hw_id',
    	'filename',
    	'fullpath',
    ];
}
