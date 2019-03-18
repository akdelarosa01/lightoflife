<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    protected $fillable = [
    	'program_id',
    	'section',
    	'create_user',
    	'update_user'
    ];
}
