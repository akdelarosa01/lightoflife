<?php

namespace App\Http\Controllers\ParentActivities;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\GlobalController;
use App\Http\Controllers\Monitoring\LogsController;
use App\ActivityLog;
use App\Homework;
use App\HomeworkAttachment;
use App\HomeworkStudentAnswer;
use App\HomeworkStudentAttachment;
use App\User;
use File;
use DB;

class HomeworkController extends Controller
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
    	return view('parent_activities.homeworks');
    }

    public function getPending(Request $req)
    {
    	$student_id_num = str_replace('P', 'S', Auth::user()->username);
    	$student = User::where('username',$student_id_num)->first();

        $pending = DB::select("SELECT ha.id as id,
                                        ha.hw_id as hw_id,
                                        hg.title as title,
                                        hg.subject_id as subject_id,
                                        concat(s.code,' - ',s.description) as `subject`,
                                        ha.status as status,
                                        DATE_FORMAT(ha.date_given, '%m/%d/%Y %h:%i %p') as date_given
                                FROM homework_student_answers as ha
                                join homework_givens as hg on hg.id = ha.hw_given_id
                                join subjects as s on s.id = hg.subject_id
                                where ha.student_id = ".$student->id." and ha.status = 'PENDING'
                                group by ha.id,
                                        ha.hw_id,
                                        hg.title,
                                        hg.subject_id,
                                        concat(s.code,' - ',s.description),
                                        s.code,
                                        s.description,
                                        ha.status,
                                        DATE_FORMAT(ha.date_given, '%m/%d/%Y %h:%i %p'),
                                        ha.date_given");

        return response()->json($pending);
    }

    public function getFinished(Request $req)
    {
    	$student_id_num = str_replace('P', 'S', Auth::user()->username);
    	$student = User::where('username',$student_id_num)->first();

        $finished = DB::select("SELECT ha.id as id,
                                        ha.hw_id as hw_id,
                                        hg.title,
                                        hg.subject_id,
                                        concat(s.code,' - ',s.description) as `subject`,
                                        ha.status,
                                        ha.date_given,
                                        ha.date_submitted,
                                        DATE_FORMAT(ha.date_given, '%m/%d/%Y %h:%i %p') as date_given,
                                        DATE_FORMAT(ha.date_submitted, '%m/%d/%Y %h:%i %p') as date_submitted
                                FROM homework_student_answers as ha
                                join homework_givens as hg on hg.id = ha.hw_given_id
                                join subjects as s on s.id = hg.subject_id
                                where ha.student_id = ".$student->id." and ha.status = 'FINISHED'
                                group by ha.id,
                                        ha.hw_id,
                                        hg.title,
                                        hg.subject_id,
                                        concat(s.code,' - ',s.description),
                                        s.code,
                                        s.description,
                                        ha.status,
                                        DATE_FORMAT(ha.date_given, '%m/%d/%Y %h:%i %p'),
                                        DATE_FORMAT(ha.date_submitted, '%m/%d/%Y %h:%i %p'),
                                        ha.date_given,
                                        ha.date_submitted");

        return response()->json($finished);
    }
}
