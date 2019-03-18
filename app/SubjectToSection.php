<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SubjectToSection extends Model
{
    protected $fillable = [
    	'program_id',
    	'section_id',
    	'create_user',
    	'update_user'
    ];
}
