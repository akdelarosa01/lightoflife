<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserLog extends Model
{
    protected $fillable = [
    	'user_id',
    	'log_type',
    	'logged_at'
    ];
}
