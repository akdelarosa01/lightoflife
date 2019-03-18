<?php

namespace App\Http\Controllers\Activities;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\GlobalController;
use App\Http\Controllers\Monitoring\LogsController;
use App\ActivityLog;
use App\Quiz;
use App\QuizItem;
use App\QuizGiven;
use App\EnrollStudentDetail;
use File;
use DB;

class GiveQuizController extends Controller
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
    	return view('activities.give-quizzes');
    }

    public function getGivenQuiz()
    {
        $gq = DB::select("SELECT gq.quiz_id as quiz_id,
                                gq.quiz_title as quiz_title,
                                gq.quiz_type as quiz_type,
                                concat(s.code,' - ',s.description) as `subject`,
                                gq.section_id as section_id,
                                gq.subject_id as subject_id,
                                gq.start_date as start_date,
                                gq.start_time as start_time,
                                gq.due_date as due_date,
                                gq.due_time as due_time,
                                gq.timer as timer,
                                gq.max_attempt as max_attempt,
                                gq.instruction as instruction,
                                gq.late_submission as late_submission,
                                DATE_FORMAT(gq.created_at, '%m/%d/%Y %h:%i %p') as created_at
                        FROM quiz_givens as gq
                        join subjects as s on s.id = gq.subject_id
                        where gq.teacher_id = ".Auth::id()."
                        group by gq.quiz_id,
                                gq.quiz_title,
                                gq.quiz_type,
                                s.code,
                                s.description,
                                concat(s.code,' - ',s.description),
                                gq.created_at,
                                gq.section_id,
                                gq.subject_id,
                                gq.start_date,
                                gq.start_time,
                                gq.due_date,
                                gq.due_time,
                                gq.timer,
                                gq.max_attempt,
                                gq.instruction,
                                gq.late_submission,
                                DATE_FORMAT(gq.created_at, '%m/%d/%Y %h:%i %p')");

        return response()->json($gq);
    }

    public function getSubjectHandle()
    {
        $subj = DB::select("SELECT sh.subject_id as id,
                                    sh.section_id as section_id,
                                    concat(s.code,' - ',s.description) as `subject`
                            FROM subject_handles as sh
                            join subjects as s on s.id = sh.subject_id
                            where sh.user_id=".Auth::id());

        return response()->json($subj);
    }

    public function getSectionHandle(Request $req)
    {
        $sec = DB::select("SELECT sh.section_id as id,
                                    s.section as section
                            FROM subject_handles as sh
                            join sections as s on s.id = sh.section_id
                            where sh.user_id=".Auth::id()."
                            and sh.subject_id = ".$req->subject_id);

        return response()->json($sec);
    }

    public function getQuiz(Request $req)
    {
        $quiz = DB::select("SELECT id, quiz_title
							FROM quizzes
							where user_id = ".Auth::id()."
							and subject_id = ".$req->subject_id);

        return response()->json($quiz);
    }

    public function save(Request $req)
    {
    	$data = [
    		'msg' => 'Saving failed.',
    		'status' => 'failed'
    	];

    	if ($req->post_status == 'ADD') {
    		$students = EnrollStudentDetail::where('section_id',$req->section)->get();
    		$quiz = Quiz::where('id',$req->quiz_id)->first();
    		$total = QuizItem::where('quiz_id',$req->quiz_id)->select(DB::raw("sum(points) as points"))->first();

    		if (count((array)$students) > 0) {
    			foreach ($students as $key => $student) {
    				$gq = QuizGiven::insert([
		    				'student_id' => $student->user_id,
							'section_id' => $req->section,
							'subject_id' => $req->subject,
							'quiz_id' => $req->quiz_id,
							'quiz_title' => $quiz->quiz_title,
                            'quiz_type' => $quiz->quiz_type,
							'teacher_id' => Auth::id(),
							'start_date' => $req->start_date,
							'start_time' => $req->start_time,
							'due_date' => $req->due_date,
							'due_time' => $req->due_time,
							'timer' => $req->timer,
							'max_attempt' => $req->max_attempt,
							'user_attempt' => 0,
							'instruction' => $req->instruction,
							'late_submission' => $req->late_submission,
							'max_score' => $total->points,
							'status' => 'PENDING',
							'create_user' => Auth::id(),
							'update_user' => Auth::id(),
                            'created_at' => date('Y-m-d H:i:s'),
                            'updated_at' => date('Y-m-d H:i:s')
		    			]);
    			}

                $data = [
                    'msg' => 'Successfully saved.',
                    'status' => 'success'
                ];
    		}
    	} else {
    		$students = EnrollStudentDetail::where('section_id',$req->section)->get();
    		$quiz = Quiz::where('id',$req->quiz_id)->first();
    		$total = QuizItem::where('quiz_id',$req->quiz_id)->select(DB::raw("sum(points) as points"))->first();

            if (count((array)$students) > 0) {
                foreach ($students as $key => $student) {
                    QuizGiven::where('quiz_id',$req->quiz_id)->update([
                        'student_id' => $student->user_id,
                        'section_id' => $req->section,
                        'subject_id' => $req->subject,
                        'quiz_id' => $req->quiz_id,
                        'quiz_title' => $quiz->quiz_title,
                        'quiz_type' => $quiz->quiz_type,
                        'teacher_id' => Auth::id(),
                        'start_date' => $req->start_date,
                        'start_time' => $req->start_time,
                        'due_date' => $req->due_date,
                        'due_time' => $req->due_time,
                        'timer' => $req->timer,
                        'max_attempt' => $req->max_attempt,
                        'user_attempt' => 0,
                        'instruction' => $req->instruction,
                        'late_submission' => $req->late_submission,
                        'max_score' => $total->points,
                        'status' => 'PENDING',
                        'update_user' => Auth::id(),
                        'updated_at' => date('Y-m-d H:i:s')
                    ]);
                }

                $data = [
                    'msg' => 'Successfully saved.',
                    'status' => 'success'
                ];
            }
    	}

        return response()->json($data);
    }

    public function delete(Request $req)
    {
        $data = [
            'msg' => 'Deleting failed.',
            'status' => 'failed'
        ];

        if (is_array($req->ids)) {
            foreach ($req->ids as $key => $id) {
                QuizGiven::where('quiz_id',$id)->delete();

                $data = [
                    'msg' => 'Successfully deleted.',
                    'status' => 'success'
                ];
            }

            $this->_log->log([
                'user_id' => Auth::id(),
                'module' => 'Activities - Give Quiz',
                'activity' => 'Deleted given quizzes.'
            ]);

        } else {
        	QuizGiven::where('quiz_id',$req->ids)->delete();

            $this->_log->log([
                'user_id' => Auth::id(),
                'module' => 'Activities - Give Quiz',
                'activity' => 'Deleted given quiz.'
            ]);

            $data = [
                'msg' => 'Successfully deleted.',
                'status' => 'success'
            ];
        }

        return response()->json($data);
    }
}
