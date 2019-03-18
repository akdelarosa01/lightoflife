<?php

namespace App\Http\Controllers\Maintenance;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\GlobalController;
use App\Http\Controllers\Monitoring\LogsController;
use App\Subject;
use DB;

class SubjectsController extends Controller
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
    	return view('maintenance.subjects');
    }

    public function getSubjects()
    {
        $sub = DB::table('subjects as s')->select(
                        DB::raw("s.id as id"),
                        DB::raw("s.code as code"),
                        DB::raw("s.description as description"),
                        DB::raw("s.program_id as program_id"),
                        DB::raw("s.dept_id as dept_id"),
                        DB::raw("p.program as program"),
                        DB::raw("d.department as department"),
                        DB::raw("DATE_FORMAT(s.created_at, '%m/%d/%Y %h:%i %p') as created_at")
                    )
                    ->join('programs as p','p.id','=','s.program_id')
                    ->join('departments as d','d.id','=','s.dept_id')
                    ->get();
        return response()->json($sub);
    }

    public function save(Request $req)
    {
        $this->validate($req, [
            'code' => 'required',
            'description' => 'required'
        ]);

        $data = [
            'msg' => 'Saving failed.',
            'status' => 'failed'
        ];

        if (!empty($req->id)) {
            if ($this->checkSubject($req) > 0) {
                $data = [
                    'msg' => 'Subject is already a duplicate.',
                    'status' => 'warning'
                ];
            } else {
                $sub = Subject::find($req->id);

                $sub->code = $req->code;
                $sub->description = $req->description;
                $sub->program_id = $req->program;
                $sub->dept_id = $req->department;
                $sub->update_user = Auth::id();

                if ($sub->save()) {
                    $this->_log->log([
                        'user_id' => Auth::id(),
                        'module' => 'Maintenance - Subject',
                        'activity' => 'Updated Subject '.$req->code.'.'
                    ]);

                    $data = [
                        'msg' => 'Subject successfully updated.',
                        'status' => 'success'
                    ];
                }
            }
                
        } else {
            if ($this->checkSubject($req) > 0) {
                $data = [
                    'msg' => 'Subject is already a duplicate.',
                    'status' => 'warning'
                ];
            } else {
                $sub = new Subject;

                $sub->code = $req->code;
                $sub->description = $req->description;
                $sub->program_id = $req->program;
                $sub->dept_id = $req->department;
                $sub->create_user = Auth::id();
                $sub->update_user = Auth::id();

                if ($sub->save()) {
                    $this->_log->log([
                        'user_id' => Auth::id(),
                        'module' => 'Maintenance - Subject',
                        'activity' => 'Added a new Subject '.$req->code.'.'
                    ]);
                    $data = [
                        'msg' => 'Subject successfully saved.',
                        'status' => 'success'
                    ];
                }
            }
                
        }

        return response()->json($data);
    }

    public function checkSubject($req)
    {
        $check = Subject::where('code',$req->code)
                        ->where('program_id',$req->program)
                        ->where('dept_id',$req->department)
                        ->count();
        return $check;
    }

    public function delete(Request $req)
    {
        $data = [
            'msg' => 'Deleting failed.',
            'status' => 'failed'
        ];

        if (is_array($req->ids)) {
            foreach ($req->ids as $key => $id) {
                $sub = Subject::find($id);

                if ($sub->delete()) {
                    $data = [
                        'msg' => 'Successfully deleted.',
                        'status' => 'success'
                    ];
                }
            }

            $this->_log->log([
                'user_id' => Auth::id(),
                'module' => 'Maintenance - Subject',
                'activity' => 'Deleted Subjects.'
            ]);

        } else {
            $sub = Subject::find($req->id);

            if ($sub->delete()) {

                $this->_log->log([
                    'user_id' => Auth::id(),
                    'module' => 'Maintenance - Subject',
                    'activity' => 'Deleted Subject.'
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
