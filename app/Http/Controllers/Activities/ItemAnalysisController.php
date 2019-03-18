<?php

namespace App\Http\Controllers\Activities;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\GlobalController;
use App\Http\Controllers\Monitoring\LogsController;
use App\ActivityLog;
use DB;
use PDF;

class ItemAnalysisController extends Controller
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
    	return view('activities.item_analysis');
    }

    public function GivenQuiz(Request $req)
    {
        $qg = DB::select("SELECT qg.quiz_id as quiz_id,
                                qg.quiz_title as quiz_title,
                                qg.quiz_type as quiz_type,
                                concat(sb.code,' - ',sb.description) as `subject`,
                                sc.section as section,
                                qg.section_id as section_id,
                                qg.subject_id as subject_id
                        FROM quiz_givens as qg
                        join subjects as sb on sb.id = qg.subject_id
                        join sections as sc on sc.id = qg.section_id
                        where qg.teacher_id = ".Auth::id()."
                        group by qg.quiz_id,
                                qg.quiz_title,
                                qg.quiz_type,
                                concat(sb.code,' - ',sb.description),
                                sb.code,
                                sb.description,
                                sc.section,
                                qg.section_id,
                                qg.subject_id");
        return response()->json($qg);
    }

    public function AnalysisDetails(Request $req)
    {
        $qg_results = DB::table('quiz_given_results as qgr')
                        ->select(
                            DB::raw("concat(u.firstname,' ',u.lastname) as teacher"),
                            DB::raw("concat(sb.code, ' - ',sb.description) as `subject`"),
                            DB::raw("sc.section as section"),
                            DB::raw("qgr.quiz_title"),
                            DB::raw("qgr.quiz_type"),
                            DB::raw("concat(qg.start_date,' ',qg.start_time) as start_date"),
                            DB::raw("concat(qg.due_date,' ',qg.due_time) as due_date"),
                            DB::raw("qg.max_attempt"),
                            DB::raw("concat(qg.timer,' min.') as timer"),
                            DB::raw("if(qg.late_submission = 0, 'NO','YES') as late_submission"),
                            DB::raw("qg.instruction")
                        )
                        ->join('subjects as sb','sb.id','=','qgr.subject_id')
                        ->join('sections as sc','sc.id','=','qgr.section_id')
                        ->join('users as u','u.id','=','qgr.teacher_id')
                        ->join('quiz_givens as qg','qg.id','=','qgr.quiz_given_id')
                        ->where('qgr.quiz_id',$req->quiz_id)->first();

        $total_num_of_studs = DB::table('quiz_givens')->where('quiz_id',$req->quiz_id)->count();
        $students_took = DB::table('quiz_givens')
                            ->where('quiz_id',$req->quiz_id)
                            ->where('status','FINISHED')
                            ->count();
        $students_not_take = DB::table('quiz_givens')
                                ->where('quiz_id',$req->quiz_id)
                                ->where('status','PENDING')
                                ->count();
        $no_of_question = DB::table('quiz_items')
                            ->where('quiz_id',$req->quiz_id)
                            ->count();
        $no_of_choices = DB::table('quiz_item_choices')
                            ->select('choice')
                            ->where('quiz_id',$req->quiz_id)
                            ->groupBy('choice')
                            ->count();
        $max = DB::table('quiz_givens')
                            ->select('max_score')
                            ->where('quiz_id',$req->quiz_id)
                            ->first();

        $highest = DB::table('quiz_given_results')
                        ->select(DB::raw("max(total_points) as total_points"))
                        ->where('quiz_id',$req->quiz_id)
                        ->first();
        $lowest = DB::table('quiz_given_results')
                        ->select(DB::raw("min(total_points) as total_points"))
                        ->where('quiz_id',$req->quiz_id)
                        ->first();

        $avg = DB::table('quiz_given_results')
                        ->select(DB::raw("avg(total_points) as total_points"))
                        ->where('quiz_id',$req->quiz_id)
                        ->first();

        $qg_item = DB::table('quiz_given_results as qgr')
                        ->select(
                            DB::raw("concat(u.firstname,' ',u.lastname) as student"),
                            DB::raw("qgr.attempt_no as attempt_no"),
                            DB::raw("qgr.total_points as total_points"),
                            DB::raw("qgr.max_score as max_score"),
                            DB::raw("qgr.grade_percent as grade_percent"),
                            DB::raw("qgr.remarks as remarks")
                        )
                        ->join('users as u','u.id','=','qgr.student_id')
                        ->where('qgr.quiz_id',$req->quiz_id)->get();                     

        $data = [
            'qg_results' => $qg_results,
            'qg_item' => $qg_item,
            'total_num_of_studs' => $total_num_of_studs,
            'students_took' => $students_took,
            'students_not_take' => $students_not_take,
            'no_of_question' => $no_of_question,
            'no_of_choices' => $no_of_choices,
            'max_points' => $max->max_score,
            'highest' => $highest->total_points,
            'lowest' => $lowest->total_points,
            'avg' => $avg->total_points,
        ];

        return response()->json($data);
    }

    public function print(Request $req)
    {
        $qg_results = DB::table('quiz_given_results as qgr')
                        ->select(
                            DB::raw("concat(u.firstname,' ',u.lastname) as teacher"),
                            DB::raw("concat(sb.code, ' - ',sb.description) as `subject`"),
                            DB::raw("sc.section as section"),
                            DB::raw("qgr.quiz_title"),
                            DB::raw("qgr.quiz_type"),
                            DB::raw("concat(qg.start_date,' ',qg.start_time) as start_date"),
                            DB::raw("concat(qg.due_date,' ',qg.due_time) as due_date"),
                            DB::raw("qg.max_attempt"),
                            DB::raw("concat(qg.timer,' min.') as timer"),
                            DB::raw("if(qg.late_submission = 0, 'NO','YES') as late_submission"),
                            DB::raw("qg.instruction")
                        )
                        ->join('subjects as sb','sb.id','=','qgr.subject_id')
                        ->join('sections as sc','sc.id','=','qgr.section_id')
                        ->join('users as u','u.id','=','qgr.teacher_id')
                        ->join('quiz_givens as qg','qg.id','=','qgr.quiz_given_id')
                        ->where('qgr.quiz_id',$req->quiz_id)->first();

        $total_num_of_studs = DB::table('quiz_givens')->where('quiz_id',$req->quiz_id)->count();
        $students_took = DB::table('quiz_givens')
                            ->where('quiz_id',$req->quiz_id)
                            ->where('status','FINISHED')
                            ->count();
        $students_not_take = DB::table('quiz_givens')
                                ->where('quiz_id',$req->quiz_id)
                                ->where('status','PENDING')
                                ->count();
        $no_of_question = DB::table('quiz_items')
                            ->where('quiz_id',$req->quiz_id)
                            ->count();
        $no_of_choices = DB::table('quiz_item_choices')
                            ->select('choice')
                            ->where('quiz_id',$req->quiz_id)
                            ->groupBy('choice')
                            ->count();
        $max = DB::table('quiz_givens')
                            ->select('max_score')
                            ->where('quiz_id',$req->quiz_id)
                            ->first();

        $highest = DB::table('quiz_given_results')
                        ->select(DB::raw("max(total_points) as total_points"))
                        ->where('quiz_id',$req->quiz_id)
                        ->first();
        $lowest = DB::table('quiz_given_results')
                        ->select(DB::raw("min(total_points) as total_points"))
                        ->where('quiz_id',$req->quiz_id)
                        ->first();

        $avg = DB::table('quiz_given_results')
                        ->select(DB::raw("avg(total_points) as total_points"))
                        ->where('quiz_id',$req->quiz_id)
                        ->first();

        $qg_item = DB::table('quiz_given_results as qgr')
                        ->select(
                            DB::raw("concat(u.firstname,' ',u.lastname) as student"),
                            DB::raw("qgr.attempt_no as attempt_no"),
                            DB::raw("qgr.total_points as total_points"),
                            DB::raw("qgr.max_score as max_score"),
                            DB::raw("qgr.grade_percent as grade_percent"),
                            DB::raw("qgr.remarks as remarks")
                        )
                        ->join('users as u','u.id','=','qgr.student_id')
                        ->where('qgr.quiz_id',$req->quiz_id)->get();                     

        $data = [
            'qg_results' => $qg_results,
            'qg_item' => $qg_item,
            'total_num_of_studs' => $total_num_of_studs,
            'students_took' => $students_took,
            'students_not_take' => $students_not_take,
            'no_of_question' => $no_of_question,
            'no_of_choices' => $no_of_choices,
            'max_points' => $max->max_score,
            'highest' => $highest->total_points,
            'lowest' => $lowest->total_points,
            'avg' => $avg->total_points,
        ];

        $pdf = PDF::loadView('pdf.item_analysis', $data);
        return $pdf->stream();
    }
}
