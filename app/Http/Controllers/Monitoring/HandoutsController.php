<?php

namespace App\Http\Controllers\Monitoring;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\GlobalController;
use App\Handout;
use DB;

class HandoutsController extends Controller
{
    protected $_global;

    public function __construct()
    {
        $this->_global = new GlobalController;
    }

    public function index()
    {
    	return view('monitoring.handouts');
    }

    public function getHandouts()
    {
    	$hnds = DB::select("SELECT p.program as program,
									sc.section as section,
									concat(sb.code,'-',sb.description) as `subject`,
							        h.title as title,
							        h.description as description,
							        h.file_path
							FROM handouts as h
							join subjects as sb on sb.id = h.subject_id
							join sections as sc on sc.id = h.section_id
							join programs as p on p.id = h.program_id");
		return response()->json($hnds);
    }

    public function getHandoutsFiles(Request $req)
    {
    	$files = DB::select("SELECT 
    							title,
								file_path
							FROM handout_files
							WHERE subj_id = '".$req->subj_id."'
							AND program_id = '".$req->program_id."'
							");
   		return response()->json($files);
    }
}
