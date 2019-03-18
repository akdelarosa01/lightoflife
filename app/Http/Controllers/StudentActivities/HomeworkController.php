<?php

namespace App\Http\Controllers\StudentActivities;

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
    	return view('student_activities.homeworks');
    }

    public function getPending(Request $req)
    {
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
                                where ha.student_id = ".Auth::id()." and ha.status = 'PENDING'
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
                                where ha.student_id = ".Auth::id()." and ha.status = 'FINISHED'
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

    public function getDetails(Request $req)
    {
        $details = DB::select("SELECT h.subject_id as subject_id,
                                        h.title as title,
                                        h.question as question,
                                        h.points as points,
                                        concat(s.code,' - ',s.description) as `subject`
                                FROM homeworks as h
                                join subjects as s on s.id = h.subject_id
                                where h.id = ".$req->hw_id."
                                group by h.subject_id,
                                        h.title,
                                        h.question,
                                        h.points,
                                        s.code,
                                        s.description,
                                        concat(s.code,' - ',s.description)");

        $attachments = HomeworkAttachment::select('filename',
                                                'fullpath')
                                        ->where('hw_id',$req->hw_id)->get();


        $data = [
            'details' => (isset($details[0]))? $details[0] : [],
            'attachments' => $attachments
        ];

        return response()->json($data);
    }

    public function getAnswer(Request $req)
    {
        $answers = HomeworkStudentAnswer::select("answer")
                                        ->where('hw_id',$req->hw_id)
                                        ->where('student_id',Auth::id())
                                        ->first();

        $attachments = HomeworkStudentAttachment::select('filename',
                                                'fullpath')
                                        ->where('hw_id',$req->hw_id)
                                        ->where('student_id',Auth::id())->get();
        $data = [
            'answers' => $answers,
            'attachments' => $attachments
        ];

        return response()->json($data);
    }

    public function save(Request $req)
    {
        $files = $req->file('homework_file');

        $data = [
            'msg' => 'Answering failed',
            'status' => 'failed'
        ];

        if (isset($req->hw_id)) {
            $update = HomeworkStudentAnswer::where('hw_id',$req->hw_id)
                                        ->where('student_id',Auth::id())
                                        ->update([
                                            'answer' => $req->answer,
                                            'date_submitted' => date('Y-m-d H:i:s'),
                                            'status' => 'FINISHED',
                                            'updated_at' =>date('Y-m-d H:i:s')
                                        ]);

            if ($update) {
                if (isset($files)) {
                    foreach ($files as $key => $file) {
                        $this->uploadfile($req->hw_id,$file,$req->subject,$req->title);
                    }
                }

                $this->_log->log([
                    'user_id' => Auth::id(),
                    'module' => 'Homework',
                    'activity' => Auth::user()->firstname.' '.Auth::user()->lastname.' answered a homework.'
                ]);

                $data = [
                    'msg' => 'Homework was successfully answered',
                    'status' => 'success'
                ];
            }
        }

        return response()->json($data);
    }

    private function uploadfile($hw_id,$file,$subject,$title)
    {
        if (isset($file)) {
            $dbPath = 'uploads/homeworks/student-answers-attachment/'.Auth::user()->username.'/'.$subject.'/'.$title;
            $destinationPath = public_path($dbPath);
            $fileName = $file->getClientOriginalName();

            if (!File::exists($destinationPath)) {
                File::makeDirectory($destinationPath, 0777, true, true);
            }

            if (File::exists($destinationPath.'/'.$fileName)) {
                File::delete($destinationPath.'/'.$fileName);
            }

            $file->move($destinationPath, $fileName);

            HomeworkStudentAttachment::create([
                'hw_id' => $hw_id,
                'student_id' => Auth::id(),
                'filename' => $fileName,
                'fullpath' => $dbPath.$fileName,
            ]);
        } else {
        }
    }

    public function getHomeworkCount()
    {
        $pending = DB::select("SELECT count(*) as homework_count
                                FROM homework_student_answers as ha
                                join homework_givens as hg on hg.id = ha.hw_given_id
                                join subjects as s on s.id = hg.subject_id
                                where ha.student_id = ".Auth::id()." and ha.status = 'PENDING'");

        $data = [
            'homework_count' => $pending[0]->homework_count
        ];

        return response()->json($data);
    }
}
