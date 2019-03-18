<?php

namespace App\Http\Controllers\Maintenance;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\GlobalController;
use App\Http\Controllers\Monitoring\LogsController;
use App\Department;
use DB;

class DepartmentsController extends Controller
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
    	return view('maintenance.departments');
    }

    public function getDepartments()
    {
        $dept = DB::table('departments as d')->select(
                    DB::raw("d.id as id"),
                    DB::raw("d.department as department"),
                    DB::raw("concat(u.firstname,' ',u.firstname) as create_user"),
                    DB::raw("DATE_FORMAT(d.created_at, '%m/%d/%Y %h:%i %p') as created_at")
                )
                ->join('users as u','u.id','=','d.create_user')
                ->get();
        return response()->json($dept);
    }

    public function save(Request $req)
    {
        $this->validate($req, [
            'department' => 'required'
        ]);

        $data = [
            'msg' => 'Saving failed.',
            'status' => 'failed'
        ];

        if (!empty($req->id)) {
            $check = Department::where('id','<>',$req->id)->where('department',$req->department)->count();

            if ($check < 1) {
                $dept = Department::find($req->id);

                $dept->department = $req->department;
                $dept->update_user = Auth::id();

                if ($dept->save()) {
                    $this->_log->log([
                        'user_id' => Auth::id(),
                        'module' => 'Maintenance - Department',
                        'activity' => 'Updated Department '.$req->title.' to '.$dept->title.'.'
                    ]);

                    $data = [
                        'msg' => 'Department successfully updated.',
                        'status' => 'success'
                    ];
                }
            } else {
                $data = [
                    'msg' => 'Department is already existing.',
                    'status' => 'failed'
                ];
            }
                
        } else {
            $check = Department::where('department',$req->department)->count();

            if ($check < 1) {
                $dept = new Department;

                $dept->department = $req->department;
                $dept->create_user = Auth::id();
                $dept->update_user = Auth::id();

                if ($dept->save()) {
                    $this->_log->log([
                        'user_id' => Auth::id(),
                        'module' => 'Maintenance - Department',
                        'activity' => 'Added a new department '.$req->title.'.'
                    ]);
                    $data = [
                        'msg' => 'Department successfully saved.',
                        'status' => 'success'
                    ];
                }
            } else {
                $data = [
                    'msg' => 'Department is already existing.',
                    'status' => 'failed'
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
                $dept = Department::find($id);

                if ($dept->delete()) {
                    $data = [
                        'msg' => 'Successfully deleted.',
                        'status' => 'success'
                    ];
                }
            }

            $this->_log->log([
                'user_id' => Auth::id(),
                'module' => 'Maintenance - Department',
                'activity' => 'Deleted departments.'
            ]);

        } else {
            $dept = Department::find($req->id);

            if ($dept->delete()) {

                $this->_log->log([
                    'user_id' => Auth::id(),
                    'module' => 'Maintenance - Department',
                    'activity' => 'Deleted department.'
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
