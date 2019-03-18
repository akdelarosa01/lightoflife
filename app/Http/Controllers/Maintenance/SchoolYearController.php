<?php

namespace App\Http\Controllers\Maintenance;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\GlobalController;
use App\Http\Controllers\Monitoring\LogsController;
use App\SchoolYear;
use DB;

class SchoolYearController extends Controller
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
    	return view('maintenance.school_year');
    }

    public function getSchoolYear()
    {
        $yr = DB::table('school_years')->select(
                            'id',
                            'from',
                            'to',
                            DB::raw("DATE_FORMAT(created_at, '%m/%d/%Y %h:%i %p') as created_at")
                        )->get();
        return response()->json($yr);
    }

    public function save(Request $req)
    {
        $this->validate($req, [
            'from' => 'required',
            'to' => 'required'
        ]);

        $data = [
            'msg' => 'Saving failed.',
            'status' => 'failed'
        ];

        if (!empty($req->id)) {
            $yr = SchoolYear::find($req->id);

            $yr->from = $req->from;
            $yr->to = $req->to;
            $yr->update_user = Auth::id();

            if ($yr->save()) {
                $this->_log->log([
                    'user_id' => Auth::id(),
                    'module' => 'Maintenance - School Year',
                    'activity' => 'Updated school year from '.$req->from.' - '.$req->to.' to '.$yr->from.' - '.$yr->to.'.'
                ]);

                $data = [
                    'msg' => 'School Year successfully updated.',
                    'status' => 'success'
                ];
            }
        } else {
            $yr = new SchoolYear;

            $yr->from = $req->from;
            $yr->to = $req->to;
            $yr->create_user = Auth::id();
            $yr->update_user = Auth::id();

            if ($yr->save()) {
                $this->_log->log([
                    'user_id' => Auth::id(),
                    'module' => 'Maintenance - School Year',
                    'activity' => 'Added new school year '.$req->from.' - '.$req->to.'.'
                ]);
                $data = [
                    'msg' => 'School Year successfully saved.',
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
                $sy = SchoolYear::find($id);

                if ($sy->delete()) {
                    $data = [
                        'msg' => 'Successfully deleted.',
                        'status' => 'success'
                    ];
                }
            }

            $this->_log->log([
                'user_id' => Auth::id(),
                'module' => 'Maintenance - School Year',
                'activity' => 'Deleted school years.'
            ]);

        } else {
            $sy = SchoolYear::find($req->id);

            if ($sy->delete()) {

                $this->_log->log([
                    'user_id' => Auth::id(),
                    'module' => 'Maintenance - School Year',
                    'activity' => 'Deleted school year.'
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
