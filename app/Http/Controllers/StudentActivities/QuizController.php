<?php

namespace App\Http\Controllers\StudentActivities;

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
        $this->checkExaminer();
    	return view('student_activities.quizzes');
    }

    public function takeQuiz(Request $req)
    {
        $details = QuizGiven::select('id',
                                    'quiz_title',
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
                                    'section_id',
                                    'teacher_id',
                                    DB::raw("if(late_submission = 0,'NO','YES') as late_submission"))
                            ->where('quiz_id',$req->quiz_id)
                            ->where('student_id',Auth::id())
                            ->first();

        $items = QuizItem::where('quiz_id',$req->quiz_id)->get();
        $choices = QuizItemChoice::where('quiz_id',$req->quiz_id)->get();

        $quiz_details = DB::select("SELECT q.id as quiz_id,
                                    concat(s.code,' - ',s.description) as `subject`,
                                    q.quiz_title as quiz_title,
                                    q.subject_id as subject_id,
                                    q.quiz_type as quiz_type
                            FROM quizzes as q
                            join subjects as s on s.id = q.subject_id
                            where q.id = ".$req->quiz_id);

        $data = [
            'details' => $details,
            'items' => $items,
            'choices' => $choices,
            'quiz_details' => $quiz_details[0]
        ];

        return view('student_activities.take_quiz',$data);
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
        $quiz = DB::select("SELECT qg.subject_id as subject_id,
                                    concat(s.code,' - ',s.description) as `subject`,
                                    qg.quiz_title as quiz_title,
                                    qg.status as `status`,
                                    qg.student_id,
                                    qg.quiz_id,
                                    DATE_FORMAT(qg.created_at, '%m/%d/%Y %h:%i %p') as date_given
                            FROM quiz_givens as qg
                            join subjects as s on s.id = qg.subject_id
                            where qg.max_attempt > qg.user_attempt AND qg.student_id = 65
                            and not (qg.user_attempt = 0 and qg.status = 'FINISHED')
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
        $quiz = DB::select("SELECT qg.id as id,
                                    qg.subject_id as subject_id,
                                    qg.section_id as section_id,
                                    concat(s.code,' - ',s.description) as `subject`,
                                    qg.quiz_title as quiz_title,
                                    qg.status as `status`,
                                    qg.student_id as student_id,
                                    qg.quiz_id as quiz_id,
                                    qg.teacher_id as teacher_id,
                                    IF(qgr.date_submitted is null,'DID NOT TAKE QUIZ',DATE_FORMAT(qgr.date_submitted, '%m/%d/%Y %h:%i %p')) as date_submitted
                            FROM quiz_givens as qg
                            join subjects as s on s.id = qg.subject_id
                            join quiz_given_results as qgr on qgr.quiz_given_id = qg.id
                            where qg.status = 'FINISHED' AND qg.student_id = ".Auth::id()."
                            and qg.max_attempt <= qg.user_attempt OR (qg.user_attempt = 0 and qg.status = 'FINISHED' and qg.student_id = ".Auth::id().")
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

    public function getQuizCount()
    {
        $quiz = DB::select("SELECT count(*) as quiz_count
                            FROM quiz_givens as qg
                            join subjects as s on s.id = qg.subject_id
                            where qg.status = 'PENDING' AND qg.student_id = ".Auth::id());

        $data = [
            'quiz_count' => $quiz[0]->quiz_count
        ];

        return response()->json($data);
    }

    public function submitQuiz(Request $req)
    {
        QuizGivenResults::where('student_id', Auth::id())
                        ->where('section_id', $req->section_id)
                        ->where('subject_id', $req->subject_id)
                        ->where('teacher_id', $req->teacher_id)
                        ->where('quiz_id', $req->quiz_id)
                        ->where('quiz_given_id',$req->qg_id)->delete();

        QuizGivenResultItem::where('student_id', Auth::id())
                        ->where('section_id', $req->section_id)
                        ->where('subject_id', $req->subject_id)
                        ->where('teacher_id', $req->teacher_id)
                        ->where('quiz_id', $req->quiz_id)->delete();

        $student_score = 0;
        $max_score = 0;
        foreach ($req->question_num as $key => $qn) {
            if ($req->correct_answer[$key] == $req->student_answer[$key]) {
                $student_score += intval($req->points[$key]);
            }

            $max_score += intval($req->points[$key]);
        }

        $grade_percent = (($student_score / $max_score) * 50)+50;
        $remarks = 'FAILED';

        if ($grade_percent >= 75) {
            $remarks = 'PASSED';
        }

        $quiz_given = QuizGiven::where('id',$req->qg_id)->first();

        $attempt = intval($quiz_given->user_attempt)+1;

        $qgr = QuizGivenResults::create([
            'student_id' => Auth::id(),
            'section_id' => $req->section_id,
            'subject_id' => $req->subject_id,
            'teacher_id' => $req->teacher_id,
            'quiz_id' => $req->quiz_id,
            'quiz_given_id' => $req->qg_id,
            'quiz_title' => $req->quiz_title,
            'quiz_type' => $req->quiz_type,
            'date_taken' => date('Y-m-d'),
            'time_taken' => date('H:i:s'),
            'date_submitted' => date('Y-m-d'),
            'time_submitted' => date('H:i:s'),
            'total_points' => $student_score,
            'max_score' => $max_score,
            'grade_percent' => $grade_percent,
            'attempt_no' => $attempt,
            'remarks' => $remarks
        ]);

        if ($qgr->save()) {
            foreach ($req->question_num as $key => $num) {
                $score = 0;
                if ($req->correct_answer[$key] == $req->student_answer[$key]) {
                    $score = intval($req->points[$key]);
                }

                QuizGivenResultItem::insert([
                    'qgr_id' => $qgr->id,
                    'student_id' => Auth::id(),
                    'section_id' => $req->section_id,
                    'subject_id' => $req->subject_id,
                    'teacher_id' => $req->teacher_id,
                    'quiz_id' => $req->quiz_id,
                    'question_num' => $num,
                    'question' => $req->question[$key],
                    'correct_answer' => $req->correct_answer[$key],
                    'student_answer' => $req->student_answer[$key],
                    'score' => $score,
                    'max_score' => $req->points[$key],
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ]);
            }

            QuizGiven::where('id',$req->qg_id)
                        ->increment('user_attempt', 1, [
                            'status' => 'FINISHED'
                        ]);
        }

        $quiz_given = QuizGiven::where('id',$req->qg_id)->first();
        $qg_results = QuizGivenResults::where('id',$qgr->id)->first();
        $qg_item = QuizGivenResultItem::where('qgr_id', $qgr->id)
                                        ->where('student_id', Auth::id())
                                        ->where('section_id', $req->section_id)
                                        ->where('subject_id', $req->subject_id)
                                        ->where('teacher_id', $req->teacher_id)
                                        ->where('quiz_id', $req->quiz_id)->get();

        $data = [
            'quiz_given' => $quiz_given,
            'qg_results' => $qg_results,
            'qg_item' => $qg_item,
            'subject' => $req->subject
        ];

        return view('student_activities.take_quiz_results',$data);
    }

    public function viewResults(Request $req)
    {
        $qg_item = [];
        $quiz_given = QuizGiven::where('id',$req->qg_id)->first();
        $qg_results = QuizGivenResults::where('id',$req->qg_id)->first();

        if (isset($qg_results)) {
            $qg_item = QuizGivenResultItem::where('qgr_id', $qg_results->id)
                                        ->where('student_id', Auth::id())
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

    private function checkExaminer()
    {
        $nontakers = DB::select("SELECT qg.student_id,
                                        qg.section_id,
                                        qg.subject_id,
                                        qg.teacher_id,
                                        qg.quiz_id,
                                        qg.id,
                                        qg.quiz_title,
                                        qg.quiz_type,
                                        qg.max_score
                            FROM quiz_givens AS qg
                            LEFT JOIN quiz_given_results AS qgr ON qg.id = qgr.quiz_given_id
                            WHERE concat(qg.due_date,' ',qg.due_time) <= curdate()
                            AND qgr.date_submitted is NULL AND qgr.remarks is NULL
                            AND qg.user_attempt = 0 AND qg.status = 'PENDING'");

        if (count((array)$nontakers) > 0) {
            foreach ($nontakers as $key => $nontaker) {
                QuizGivenResults::insert([
                    'student_id' => $nontaker->student_id,
                    'section_id' => $nontaker->section_id,
                    'subject_id' => $nontaker->subject_id,
                    'teacher_id' => $nontaker->teacher_id,
                    'quiz_id' => $nontaker->quiz_id,
                    'quiz_given_id' => $nontaker->id,
                    'quiz_title' => $nontaker->quiz_title,
                    'quiz_type' => $nontaker->quiz_type,
                    'date_taken' => null,
                    'time_taken' => null,
                    'date_submitted' => null,
                    'time_submitted' => null,
                    'total_points' => 0,
                    'max_score' => $nontaker->max_score,
                    'grade_percent' => 50,
                    'attempt_no' => 0,
                    'remarks' => 'FAILED: DID NOT TAKE THE QUIZ.',
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ]);

                $items = QuizItem::where('quiz_id',$nontaker->quiz_id)->get();
                $lastQGR = QuizGivenResults::select('id')->orderBy('id','desc')->first();

                foreach ($items as $key => $item) {
                    QuizGivenResultItem::insert( [
                        'qgr_id' => $lastQGR->id,
                        'student_id' => $nontaker->student_id,
                        'section_id' => $nontaker->section_id,
                        'subject_id' => $nontaker->subject_id,
                        'teacher_id' => $nontaker->teacher_id,
                        'quiz_id' => $nontaker->quiz_id,
                        'question_num' => $item->question_num,
                        'question' => $item->question,
                        'correct_answer' => $item->answer,
                        'student_answer' => 'NO ANSWER',
                        'score' => 0,
                        'max_score' => $item->points,
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s')
                    ]);
                }

                QuizGiven::where('id',$nontaker->id)->update([
                    'status' => 'FINISHED'
                ]);
            }
        }
    }
}
