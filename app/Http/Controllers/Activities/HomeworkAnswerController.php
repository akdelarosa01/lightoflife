<?php

namespace App\Http\Controllers\Activities;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\GlobalController;
use App\Http\Controllers\Monitoring\LogsController;
use App\ActivityLog;
use File;
use App\Homework;
use App\HomeworkAttachment;
use DB;

class HomeworkAnswerController extends Controller
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
    	return view('activities.homework_answers');
    }

    public function getAnswers()
    {
    	$ans = DB::select("SELECT hsa.hw_id as hw_id,
    							h.title as title,
    							hsa.student_id as student_id,
						        s.section as section,
						        h.question as question,
						        concat(sb.code,' - ',sb.description) as `subject`,
						        concat(u.firstname,' ',u.lastname) as student_name,
						        hsa.answer as answer,
						        DATE_FORMAT(hsa.date_submitted, '%m/%d/%Y %h:%i %p') as date_submitted
						FROM homework_student_answers as hsa
						join homeworks as h on h.id = hsa.hw_id
						join sections as s on s.id = hsa.section_id
						join subjects as sb on sb.id = h.subject_id
						join users as u on u.id = hsa.student_id
						where hsa.answer <> '' and h.user_id=".Auth::id());
    	return response()->json($ans);
    }

    public function getAttachments(Request $req)
    {
    	$attachments = DB::select("SELECT filename,
										fullpath
								FROM homework_attachments
								where hw_id = ".$req->hw_id);

    	$student_att = DB::select("SELECT filename,
										fullpath
								FROM homework_student_attachments
								where hw_id = ".$req->hw_id."
								and student_id = ".$req->student_id);

    	$data = [
    		'attachments' => $attachments,
    		'student_att' => $student_att
    	];

    	return response()->json($data);
    }
}
