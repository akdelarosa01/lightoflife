<?php

namespace App\Http\Controllers\Maintenance;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\GlobalController;
use App\Http\Controllers\Monitoring\LogsController;
use App\Program;
use DB;

class ProgramsController extends Controller
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
    	return view('maintenance.programs');
    }

    public function getPrograms()
    {
        $prog = DB::table('programs as p')->select(
                    DB::raw("p.id as id"),
                    DB::raw("p.program as program"),
                    DB::raw("concat(u.firstname,' ',u.firstname) as create_user"),
                    DB::raw("DATE_FORMAT(p.created_at, '%m/%d/%Y %h:%i %p') as created_at")
                )
                ->join('users as u','u.id','=','p.create_user')
                ->get();
        return response()->json($prog);
    }

    public function save(Request $req)
    {
        $this->validate($req, [
            'program' => 'required'
        ]);

        $data = [
            'msg' => 'Saving failed.',
            'status' => 'failed'
        ];

        if (!empty($req->id)) {
            $check = Program::where('id','<>',$req->id)->where('program',$req->program)->count();

            if ($check < 1) {
                $prog = Program::find($req->id);

                $prog->program = $req->program;
                $prog->update_user = Auth::id();

                if ($prog->save()) {
                    $this->_log->log([
                        'user_id' => Auth::id(),
                        'module' => 'Maintenance - Program',
                        'activity' => 'Updated Program '.$req->title.' to '.$prog->title.'.'
                    ]);

                    $data = [
                        'msg' => 'Program successfully updated.',
                        'status' => 'success'
                    ];
                }
            } else {
                $data = [
                    'msg' => 'Program is already existing.',
                    'status' => 'failed'
                ];
            }
                
        } else {
            $check = Program::where('program',$req->program)->count();

            if ($check < 1) {
                $prog = new Program;

                $prog->program = $req->program;
                $prog->create_user = Auth::id();
                $prog->update_user = Auth::id();

                if ($prog->save()) {
                    $this->_log->log([
                        'user_id' => Auth::id(),
                        'module' => 'Maintenance - Program',
                        'activity' => 'Added a new program '.$req->title.'.'
                    ]);
                    $data = [
                        'msg' => 'Program successfully saved.',
                        'status' => 'success'
                    ];
                }
            } else {
                $data = [
                    'msg' => 'Program is already existing.',
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
                $prog = Program::find($id);

                if ($prog->delete()) {
                    $data = [
                        'msg' => 'Successfully deleted.',
                        'status' => 'success'
                    ];
                }
            }

            $this->_log->log([
                'user_id' => Auth::id(),
                'module' => 'Maintenance - Program',
                'activity' => 'Deleted programs.'
            ]);

        } else {
            $prog = Program::find($req->id);

            if ($prog->delete()) {

                $this->_log->log([
                    'user_id' => Auth::id(),
                    'module' => 'Maintenance - Program',
                    'activity' => 'Deleted program.'
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
