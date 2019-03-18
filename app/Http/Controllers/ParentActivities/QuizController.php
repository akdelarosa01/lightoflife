<?php

namespace App\Http\Controllers\ParentActivities;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\GlobalController;
use App\Http\Controllers\Monitoring\LogsController;
use App\ActivityLog;
use App\Quiz;
use App\QuizGiven;
use App\QuizItem;
use App\QuizItemChoice;
use App\QuizGivenResults;
use App\QuizGivenResultItem;
use File;
use DB;

class QuizController extends Controller
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
    	return view('parent_activities.quizzes');
    }

    public function getQuizDetails(Request $req)
    {
        $details = QuizGiven::select('quiz_title',
                                    'quiz_type',
                                    'max_score',
                                    'start_date',
                                    'start_time',
                                    'due_date',
                                    'due_time',
                                    'max_attempt',
                                    'user_attempt',
                                    'instruction',
                                    'timer',
                                    DB::raw("if(late_submission = 0,'NO','YES') as late_submission"))
                            ->where('quiz_id',$req->quiz_id)
                            ->where('student_id',Auth::id())
                            ->first();
        $take_quiz = false;

        if (date('Y-m-d H:i:s') > $this->_global->convertDate($details->start_date.' '.$details->start_time,'Y-m-d H:i:s')) {
            $take_quiz = true;
        }

        if (date('Y-m-d H:i:s') > $this->_global->convertDate($details->due_date.' '.$details->due_time,'Y-m-d H:i:s')) {
            $take_quiz = false;
        }

        if ($details->user_attempt >= $details->max_attempt) {
            $take_quiz = false;
        }

        $data = [
            'details' => $details,
            'take_quiz' => $take_quiz
        ];

        return response()->json($data);
    }

    public function getPending()
    {
    	$id = Auth::id() - 1;
        $quiz = DB::select("SELECT qg.subject_id as subject_id,
                                    concat(s.code,' - ',s.description) as `subject`,
                                    qg.quiz_title as quiz_title,
                                    qg.status as `status`,
                                    qg.student_id,
                                    qg.quiz_id,
                                    DATE_FORMAT(qg.created_at, '%m/%d/%Y %h:%i %p') as date_given
                            FROM quiz_givens as qg
                            join subjects as s on s.id = qg.subject_id
                            where qg.max_attempt > qg.user_attempt AND qg.student_id = ".$id."
                            group by subject_id,
                                    s.code,
                                    s.description,
                                    concat(s.code,' - ',s.description),
                                    qg.quiz_title,
                                    qg.status,
                                    qg.student_id,
                                    qg.quiz_id,
                                    qg.created_at,
                                    DATE_FORMAT(qg.created_at, '%m/%d/%Y %h:%i %p')");



        return response()->json($quiz);
    }

    public function getFinished()
    {
    	$id = Auth::id() - 1;
        $quiz = DB::select("SELECT qg.id as id,
                                    qg.subject_id as subject_id,
                                    qg.section_id as section_id,
                                    concat(s.code,' - ',s.description) as `subject`,
                                    qg.quiz_title as quiz_title,
                                    qg.status as `status`,
                                    qg.student_id as student_id,
                                    qg.quiz_id as quiz_id,
                                    qg.teacher_id as teacher_id,
                                    DATE_FORMAT(qgr.date_submitted, '%m/%d/%Y %h:%i %p') as date_submitted
                            FROM quiz_givens as qg
                            join subjects as s on s.id = qg.subject_id
                            join quiz_given_results as qgr on qgr.quiz_given_id = qg.id
                            where qg.status = 'FINISHED' AND qg.student_id = ".$id."
                            and qg.max_attempt <= qg.user_attempt
                            group by qg.id,
                                    qg.subject_id,
                                    qg.section_id,
                                    s.code,
                                    s.description,
                                    concat(s.code,' - ',s.description),
                                    qg.quiz_title,
                                    qg.status,
                                    qg.student_id,
                                    qg.quiz_id,
                                    qg.teacher_id,
                                    qgr.date_submitted,
                                    DATE_FORMAT(qgr.date_submitted, '%m/%d/%Y %h:%i %p')");

        return response()->json($quiz);
    }

    public function viewResults(Request $req)
    {
    	$qg_item = [];
    	$id = Auth::id() - 1;
        $quiz_given = QuizGiven::where('id',$req->qg_id)->first();
        $qg_results = QuizGivenResults::where('id',$req->qg_id)->first();

        if (isset($qg_results->id)) {
        	$qg_item = QuizGivenResultItem::where('qgr_id', $qg_results->id)
	                                        ->where('student_id', $id)
	                                        ->where('section_id', $req->section_id)
	                                        ->where('subject_id', $req->subject_id)
	                                        ->where('teacher_id', $req->teacher_id)
	                                        ->where('quiz_id', $req->quiz_id)->get();
        }
	        

        $subject = DB::table('subjects')
                    ->select(DB::raw("concat(`code`,' - ',`description`) as `subject`"))
                    ->where('id',$req->subject_id)->first();

        $data = [
            'quiz_given' => $quiz_given,
            'qg_results' => $qg_results,
            'qg_item' => $qg_item,
            'subject' => $subject->subject
        ];

        return response()->json($data);
    }
}
