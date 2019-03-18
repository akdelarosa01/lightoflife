<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SubjectToSectionDetail extends Model
{
    protected $fillable = [
    	'sts_id',
    	'subj_id',
    	'code'
    ];
}
