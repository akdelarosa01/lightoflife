<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Program extends Model
{
    protected $fillable = [
    	'program',
    	'create_user',
    	'update_user'
    ];
}
