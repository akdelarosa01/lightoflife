<?php

namespace App\Http\Controllers\Transaction;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\GlobalController;
use App\Http\Controllers\Monitoring\LogsController;
use App\Subject;
use App\Section;
use App\SubjectHandle;
use App\SubjectHandleDetail;
use DB;

class SubjectHandleController extends Controller
{
    protected $_global;
    protected $_log;

    public function __construct()
    {
        $this->_global = new GlobalController;
        $this->_log = new LogsController;
    }

    public function index()
    {
    	return view('transaction.subject_handle');
    }

    public function getTeachers()
    {
    	$teacher = DB::select("SELECT u.id as id,
										u.username as id_number,
								        concat(u.firstname,' ',u.lastname) as fullname,
								        d.department as department,
								        t.dept_id as dept_id
								FROM users as u
								join teachers as t on t.user_id = u.id
								join departments as d on d.id = t.dept_id
                                left join subject_handles as sh on sh.user_id = u.id
                                group by u.id,
                                        u.username,
                                        u.firstname,
                                        u.lastname,
                                        concat(u.firstname,' ',u.lastname),
                                        d.department,
                                        t.dept_id");

    	return response()->json($teacher);
    }

    public function getSubjects(Request $req)
    {
    	$data = [
    		'subjects' => ''
    	];

    	$subs = DB::select("SELECT s.id as id,
									s.code as code,
							        s.description as description,
							        d.department as department,
                                    s.dept_id as dept_id,
                                    s.program_id as program_id,
                                    p.program as program
							FROM subjects as s
							join departments as d on d.id = s.dept_id
                            join programs as p on p.id = s.program_id
							where s.dept_id = '".$req->dept_id."'
                            group by s.id,
                                    s.code,
                                    s.description,
                                    d.department,
                                    s.dept_id,
                                    s.program_id,
                                    p.program");

    	$data = [
    		'subjects' => $subs
    	];

    	return response()->json($data);
    }

    public function getSections(Request $req)
    {
        $prog_id = [];
        $subj_id = [];
        $data = [];

        if (isset($req->sub_data)) {
            foreach ($req->sub_data as $key => $subs) {
                array_push($prog_id, $subs['program_id']);
                array_push($subj_id, $subs['id']);
            }

            $progs = implode(',', $prog_id);
            $subjs = implode(',', $subj_id);

            $data = DB::select("SELECT s.id as id,
                                    p.program as program,
                                    s.section as section
                                FROM subject_to_sections as ss
                                join subject_to_section_details as ssd on ss.id = ssd.sts_id
                                join sections as s on s.id = ss.section_id
                                join programs as p on p.id = ss.program_id
                                where ssd.subj_id in (".$subjs.")
                                and p.id in (".$progs.")
                                group by s.id,
                                    p.program,
                                    s.section");
            
        }
        return response()->json($data);
    }

    public function save(Request $req)
    {
        $data = [
            'msg' => 'Saving failed',
            'status' => 'failed'
        ];

        if (isset($req->section)) {
            SubjectHandle::where('user_id', $req->user_id)
                        ->where('id_number', $req->id_number)
                        ->where('teacher_name', $req->fullname)
                        ->where('program_id', $req->program_id)
                        ->where('dept_id', $req->dept_id)
                        ->where('subject_id', $req->subj_id)
                        ->delete();

            foreach ($req->section as $key => $sec_id) {
                SubjectHandle::create([
                    'id_number' => $req->id_number,
                    'teacher_name' => $req->fullname,
                    'user_id' => $req->user_id,
                    'dept_id' => $req->dept_id,
                    'subject_id' => $req->subj_id,
                    'program_id' => $req->program_id,
                    'program' => $req->program,
                    'section_id' => $sec_id,
                    'create_user' => Auth::id(),
                    'update_user' => Auth::id()
                ]);

                $data = [
                    'msg' => 'Successfully saved.',
                    'status' => 'success'
                ];
            }
        }    

        return response()->json($data);    
    }

    private function getSectionName($id)
    {
        $sec = DB::table('sections')->select('section')->where('id',$id)->first();
        return $sec->section;
    }

    public function getHandled(Request $req)
    {
        $handles = DB::select("SELECT sh.section_id as section_id,
                                        sc.section as section,
                                        sh.subject_id as subject_id,
                                        concat(sb.code,' - ',sb.description) as `subject`,
                                        sh.program as program
                                FROM subject_handles as sh
                                join subjects as sb on sb.id = sh.subject_id
                                join sections as sc on sc.id = sh.section_id
                                where sh.user_id = ".$req->id."
                                and sh.dept_id = ".$req->dept_id."");

        return response()->json($handles);
    }
}
