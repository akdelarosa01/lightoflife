<?php

namespace App\Http\Controllers\Transaction;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\GlobalController;
use App\Http\Controllers\Monitoring\LogsController;
use App\Subject;
use App\EnrollStudent;
use App\EnrollStudentDetail;
use App\User;
use DB;

class EnrollStudentController extends Controller
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
    	return view('transaction.enroll_students');
    }

    public function getEnrolled()
    {
    	$enrolled = DB::select("SELECT es.id as id,
										p.program as program,
										p.id as program_id,
								        s.section as section,
								        s.id as section_id,
								        ifnull(es.school_year,'') as school_year,
								        ifnull(es.no_of_students,0) as no_of_students,
								        ifnull(es.no_of_students_enrolled,0) as no_of_students_enrolled
								FROM sections as s
								left join enroll_students as es on es.section_id = s.id
								left join programs as p on s.program_id = p.id
								group by es.id,
										p.program,
								        s.section,
								        es.school_year,
								        es.no_of_students,
								        es.no_of_students_enrolled,
								        p.id,
								        s.id
								order by p.id asc");
    	return response()->json($enrolled);
    }

    public function getSubjects(Request $req)
    {
        $sy = Subject::select('code','description')
                    ->where('program_id',$req->prog_id)->get();
        return response()->json($sy);
    }

    public function getStudents(Request $req)
    {
    	$students = DB::select("SELECT u.id as id,
										s.id_number as id_number,
										if(u.middlename is null,
											concat(u.firstname,' ',u.lastname),
											concat(u.firstname,' ',u.middlename,' ',u.lastname)
										) as fullname,
								        ifnull(esd.section_id,0) as section_id
								FROM users as u
								left join students as s on u.id = s.user_id
								left join enroll_student_details as esd on esd.user_id = s.user_id
								where s.program_id=".$req->prog_id."
								group by u.id,
										s.id_number,
										if(u.middlename is null,
											concat(u.firstname,' ',u.lastname),
											concat(u.firstname,' ',u.middlename,' ',u.lastname)
										),
								        esd.section_id");

    	if (count((array)$students) > 0) {
    		return response()->json($students);
    	} else {
    		$students = DB::select("SELECT
										u.id as id,
									    s.id_number as id_number,
									    if(u.middlename is null,
												concat(u.firstname,' ',u.lastname),
									            concat(u.firstname,' ',u.middlename,' ',u.lastname)
									        ) as fullname
									FROM students as s
									JOIN users as u on u.id = s.user_id
									WHERE s.program_id=".$req->prog_id."
									and u.id not in (
										SELECT user_id
										FROM enroll_student_details
									)");
	   		return response()->json($students);
    	}
    }

    public function save(Request $req)
    {
    	$students = [];
    	$data = [
            'msg' => 'Saving failed',
            'status' => 'failed'
        ];

        if (count((array)$req->student_user_id) > 0) {
        	if (is_null($req->es_id) || $req->es_id == 'null') {
	    		$es = EnrollStudent::create([
			    			'program_id' => $req->program,
			    			'section_id' => $req->section,
			    			'school_year' => $req->school_year,
			    			'no_of_students' => $req->no_of_students,
			    			'create_user' => Auth::id(),
			    			'update_user' => Auth::id()
			    		]);
	    		$student_count = 0;
	    		foreach ($req->student_user_id as $key => $user_id) {
	    			array_push($students,[
	    				'es_id' => $es->id,
	    				'program_id' => $req->program,
	    				'section_id' => $req->section,
	    				'user_id' => $user_id,
	    				'id_number' => $this->getIDnumber($user_id),
	    			]);
	    			$student_count++;
	    		}

	    		$params = array_chunk($students, 1000);
		        foreach ($params as $param) {
		            EnrollStudentDetail::insert($param);

		            $data = [
		                'msg' => 'Successfully saved.',
		                'status' => 'success'
		            ];
		        }
		        EnrollStudent::where('id',$es->id)->update([
		        	'no_of_students_enrolled' => $student_count,
		        	'updated_at' => date('Y-m-d H:i:s')
		        ]);
	    	} else {
	    		$es = EnrollStudent::find($req->es_id);

	    		if (count((array)$es) > 0) {
	    			EnrollStudentDetail::where('es_id',$req->es_id)->delete();

		    		$student_count = 0;
		    		foreach ($req->student_user_id as $key => $user_id) {
		    			array_push($students,[
		    				'es_id' => $es->id,
		    				'program_id' => $req->program,
		    				'section_id' => $req->section,
		    				'user_id' => $user_id,
		    				'id_number' => $this->getIDnumber($user_id),
		    			]);
		    			$student_count++;
		    		}

		    		$params = array_chunk($students, 1000);
			        foreach ($params as $param) {
			            EnrollStudentDetail::insert($param);

			            $data = [
			                'msg' => 'Successfully saved.',
			                'status' => 'success'
			            ];
			        }

			        EnrollStudent::where('id',$es->id)->update([
			        	'program_id' => $req->program,
		    			'section_id' => $req->section,
		    			'school_year' => $req->school_year,
		    			'no_of_students' => $req->no_of_students,
			        	'no_of_students_enrolled' => $student_count,
			        	'updated_at' => date('Y-m-d H:i:s')
			        ]);
	    		}
	    	}
        } else {
        	$data = [
	            'msg' => 'Select at least 1 student.',
	            'status' => 'failed'
	        ];
        }

	    	

    	return response()->json($data);
    }

    private function getIDnumber($user_id)
    {
    	$user = User::select('username')->where('id',$user_id)->first();
    	return $user->username;
    }
}
