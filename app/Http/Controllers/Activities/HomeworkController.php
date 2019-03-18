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
    	return view('activities.homeworks');
    }

    public function getHomeworks()
    {
        $hw = DB::select("SELECT h.id as id,
                            h.subject_id as subject_id,
                            concat(s.code,' - ', s.description) as `subject`,
                            h.title as title,
                            h.question as question,
                            h.points as points,
                            DATE_FORMAT(h.created_at, '%m/%d/%Y %h:%i %p') as created_at
                        FROM homeworks as h
                        join subjects as s on s.id = h.subject_id
                        where user_id = ".Auth::id()."
                        group by h.id,
                                h.subject_id,
                                s.code,
                                s.description,
                                concat(s.code,' - ', s.description),
                                h.title,
                                h.created_at,
                                h.question,
                                h.points,
                                DATE_FORMAT(h.created_at, '%m/%d/%Y %h:%i %p')");

        return response()->json($hw);
    }

    public function getSubjectHandle()
    {
        $subj = DB::select("SELECT sh.subject_id as id,
                                    concat(s.code,' - ',s.description) as `subject`
                            FROM subject_handles as sh
                            join subjects as s on s.id = sh.subject_id
                            where sh.user_id=".Auth::id());

        return response()->json($subj);
    }

    public function save(Request $req)
    {
        $files = $req->file('homework_file');

        $data = [
            'msg' => 'Saving failed',
            'status' => 'failed'
        ];

        if (is_null($req->hw_id) || $req->hw_id == '') {
            $hw = Homework::create([
                    'user_id' => Auth::id(),
                    'id_number' => Auth::user()->username,
                    'subject_id' => $req->subject,
                    'title' => $req->title,
                    'question' => $req->question,
                    'points' => $req->points,
                    'create_user' => Auth::id(),
                    'update_user' => Auth::id()
                ]);

            if ($hw->save()) {
                if (isset($files)) {
                    foreach ($files as $key => $file) {
                        $this->uploadfile($hw->id,$file,$this->getSubjectName($req->subject));
                    }
                }

                $this->_log->log([
                    'user_id' => Auth::id(),
                    'module' => 'Homework',
                    'activity' => 'Created a homework.'
                ]);

                $data = [
                    'msg' => 'Homework was successfully saved',
                    'status' => 'success'
                ];
            }
        } else {
            $hw = Homework::find($req->hw_id);
            $hw->subject_id = $req->subject;
            $hw->title = $req->title;
            $hw->question = $req->question;
            $hw->points = $req->points;
            $hw->update_user = Auth::id();

            if ($hw->save()) {
                if (isset($files)) {

                    $atts = HomeworkAttachment::where('hw_id',$req->hw_id)->get();

                    foreach ($atts as $key => $att) {
                        if (File::exists(public_path().'/'.$att->fullpath)) {
                            File::delete(public_path().'/'.$att->fullpath);
                        }
                    }

                    foreach ($files as $key => $file) {
                        $this->uploadfile($hw->id,$file,$this->getSubjectName($req->subject));
                    }
                }

                $this->_log->log([
                    'user_id' => Auth::id(),
                    'module' => 'Homework',
                    'activity' => 'Edited a homework.'
                ]);

                $data = [
                    'msg' => 'Homework was successfully saved',
                    'status' => 'success'
                ];
            }
        }

        return response()->json($data);
    }

    private function getSubjectName($subject_id)
    {
        $sub = DB::table('subjects')->where('id',$subject_id)->first();
        return $sub->code;
    }

    private function uploadfile($hw_id,$file,$subject)
    {
        if (isset($file)) {
            $dbPath = 'uploads/homeworks/'.Auth::user()->username.'/'.$subject.'/';
            $destinationPath = public_path($dbPath);
            $fileName = $file->getClientOriginalName();

            if (!File::exists($destinationPath)) {
                File::makeDirectory($destinationPath, 0777, true, true);
            }

            if (File::exists($destinationPath.'/'.$fileName)) {
                File::delete($destinationPath.'/'.$fileName);
            }

            $file->move($destinationPath, $fileName);

            HomeworkAttachment::create([
                'hw_id' => $hw_id,
                'filename' => $fileName,
                'fullpath' => $dbPath.$fileName,
            ]);
        } else {
        }
    }

    public function delete(Request $req)
    {
        $data = [
            'msg' => 'Deleting failed.',
            'status' => 'failed'
        ];

        if (is_array($req->ids)) {
            foreach ($req->ids as $key => $id) {
                $hw = Homework::find($id);

                if ($hw->delete()) {
                    $atts = HomeworkAttachment::where('hw_id',$id)->get();

                    foreach ($atts as $key => $att) {
                        if (File::exists(public_path().'/'.$att->fullpath)) {
                            File::delete(public_path().'/'.$att->fullpath);
                        }
                    }

                    HomeworkAttachment::where('hw_id',$id)->delete();

                    $data = [
                        'msg' => 'Successfully deleted.',
                        'status' => 'success'
                    ];
                }
            }

            $this->_log->log([
                'user_id' => Auth::id(),
                'module' => 'Activities - Homework',
                'activity' => 'Deleted Homeworks.'
            ]);

        } else {
            $hw = Homework::find($req->ids);

            if ($hw->delete()) {

                $atts = HomeworkAttachment::where('hw_id',$req->ids)->get();

                foreach ($atts as $key => $att) {
                    if (File::exists(public_path().$att->fullpath)) {
                        File::delete(public_path().$att->fullpath);
                    }
                }

                HomeworkAttachment::where('hw_id',$id)->delete();

                $this->_log->log([
                    'user_id' => Auth::id(),
                    'module' => 'Activities - Homework',
                    'activity' => 'Deleted Homework.'
                ]);

                $data = [
                    'msg' => 'Successfully deleted.',
                    'status' => 'success'
                ];
            }
        }

        return response()->json($data);
    }
}
