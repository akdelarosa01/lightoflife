<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SchoolYear extends Model
{
    protected $fillable = [
    	'from',
    	'to',
    	'create_user',
    	'update_user'
    ];
}
