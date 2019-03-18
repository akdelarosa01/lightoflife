<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Handout extends Model
{
    protected $fillable = [
    	'user_id',
    	'id_number',
    	'program_id',
    	'subject_id',
    	'section_id',
    	'title',
        'description',
    	'file_path',
    	'create_user',
    	'update_user'
    ];
}
