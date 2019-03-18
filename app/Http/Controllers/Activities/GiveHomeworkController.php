<?php

namespace App\Http\Controllers\Activities;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\GlobalController;
use App\Http\Controllers\Monitoring\LogsController;
use App\ActivityLog;
use App\Homework;
use App\HomeworkGiven;
use App\HomeworkStudentAnswer;
use App\EnrollStudentDetail;
use DB;

class GiveHomeworkController extends Controller
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
    	return view('activities.give-homeworks');
    }

    public function getGivenHomework()
    {
        $hg = DB::select("SELECT hg.id as id,
                                concat(s.code,' - ',s.description) as `subject`,
                                hg.title as title,
                                hg.due_date as due_date,
                                hg.due_time as due_time,
                                hg.section_id as section_id,
                                hg.subject_id as subject_id,
                                hg.hw_id as hw_id,
                                DATE_FORMAT(hg.date_given, '%m/%d/%Y %h:%i %p') as date_given
                        FROM homework_givens as hg
                        join subjects as s on s.id = hg.subject_id
                        where hg.teacher_id = ".Auth::id()."
                        group by hg.id,
                                concat(s.code,' - ',s.description),
                                s.code,
                                s.description,
                                hg.title,
                                hg.date_given,
                                hg.due_date,
                                hg.due_time,
                                hg.section_id,
                                hg.subject_id,
                                hg.hw_id,
                                DATE_FORMAT(hg.date_given, '%m/%d/%Y %h:%i %p')");

        return response()->json($hg);
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

    public function getHomework(Request $req)
    {
        $hw = DB::select("SELECT id, title
                            FROM homeworks
                            where user_id = ".Auth::id()."
                            and subject_id = ".$req->subject_id);

        return response()->json($hw);
    }

    public function save(Request $req)
    {
        $data = [
            'msg' => 'Saving failed.',
            'status' => 'failed'
        ];

        if ($req->post_status == 'ADD') {
            $students = EnrollStudentDetail::where('section_id',$req->section)->get();
            $homework = Homework::where('id',$req->hw_id)->first();
            $hg = HomeworkGiven::create([
                    'hw_id' => $req->hw_id,
                    'section_id' => $req->section,
                    'subject_id' => $req->subject,
                    'teacher_id' => Auth::id(),
                    'title' => $homework->title,
                    'due_date' => $req->due_date,
                    'due_time' => $req->due_time,
                    'date_given' => date('Y-m-d H:i:s'),
                    'create_user' => Auth::id(),
                    'update_user' => Auth::id()
                ]);

            if ($hg->save()) {
                if (count((array)$students) > 0) {
                    foreach ($students as $key => $student) {
                        HomeworkStudentAnswer::insert([
                            'hw_given_id' => $hg->id,
                            'hw_id' => $req->hw_id,
                            'student_id' => $student->user_id,
                            'section_id' => $student->section_id,
                            'answer' => '',
                            'status' => 'PENDING',
                            'date_given' => date('Y-m-d H:i:s'),
                            'date_submitted' => null,
                            'created_at' => date('Y-m-d H:i:s'),
                            'updated_at' => date('Y-m-d H:i:s')
                        ]);
                    }
                        
                }

                $data = [
                    'msg' => 'Successfully saved.',
                    'status' => 'success'
                ];
            }
        } else {
            $students = EnrollStudentDetail::where('section_id',$req->section)->get();
            $homework = Homework::where('id',$req->hw_id)->first();
            $hg = HomeworkGiven::find($req->hw_given_id);
            $hg->hw_id = $req->hw_id;
            $hg->section_id = $req->section;
            $hg->subject_id = $req->subject;
            $hg->teacher_id = Auth::id();
            $hg->title = $homework->title;
            $hg->due_date = $req->due_date;
            $hg->due_time = $req->due_time;
            $hg->date_given = date('Y-m-d H:i:s');
            $hg->create_user = Auth::id();
            $hg->update_user = Auth::id();

            if ($hg->save()) {
                if (count((array)$students) > 0) {
                    HomeworkStudentAnswer::where('hw_given_id',$req->hw_given_id)->delete();
                    foreach ($students as $key => $student) {
                        HomeworkStudentAnswer::insert([
                            'hw_given_id' => $hg->id,
                            'hw_id' => $req->hw_id,
                            'student_id' => $student->user_id,
                            'section_id' => $student->section_id,
                            'answer' => '',
                            'status' => 'PENDING',
                            'date_given' => date('Y-m-d H:i:s'),
                            'date_submitted' => null
                        ]);
                    }
                        
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
                HomeworkGiven::where('id',$id)->delete();
                HomeworkStudentAnswer::where('hw_given_id',$id)->delete();
                $data = [
                    'msg' => 'Successfully deleted.',
                    'status' => 'success'
                ];
            }

            $this->_log->log([
                'user_id' => Auth::id(),
                'module' => 'Activities - Give Homework',
                'activity' => 'Deleted given homeworks.'
            ]);

        } else {
            HomeworkGiven::where('id',$req->ids)->delete();
            HomeworkStudentAnswer::where('hw_given_id',$req->ids)->delete();

            $this->_log->log([
                'user_id' => Auth::id(),
                'module' => 'Activities - Give Homework',
                'activity' => 'Deleted given homework.'
            ]);

            $data = [
                'msg' => 'Successfully deleted.',
                'status' => 'success'
            ];
        }

        return response()->json($data);
    }
}
