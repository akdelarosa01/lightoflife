<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MessageAttachment extends Model
{
    protected $fillable = [
    	'subject_id',
    	'filename',
    	'fullpath'
    ];
}
