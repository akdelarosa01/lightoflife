<?php

namespace App\Http\Controllers\Maintenance;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\GlobalController;
use App\Http\Controllers\Monitoring\LogsController;
use App\Section;
use DB;

class SectionsController extends Controller
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
    	return view('maintenance.sections');
    }

    public function getSections()
    {
        $sec = DB::table('sections as s')->select(
                        DB::raw("s.id as id"),
                        DB::raw("s.program_id as program_id"),
                        DB::raw("p.program as program"),
                        DB::raw("s.section as section"),
                        DB::raw("DATE_FORMAT(s.created_at, '%m/%d/%Y %h:%i %p') as created_at")
                    )
                    ->join('programs as p','p.id','=','s.program_id')
                    ->get();
        return response()->json($sec);
    }

    public function save(Request $req)
    {
        $this->validate($req, [
            'section' => 'required',
            'program' => 'required'
        ]);

        $data = [
            'msg' => 'Saving failed.',
            'status' => 'failed'
        ];

        if (!empty($req->id)) {
            if ($this->checkSection($req) > 0) {
                $data = [
                    'msg' => 'Section is already a duplicate.',
                    'status' => 'warning'
                ];
            } else {
                $sec = Section::find($req->id);

                $sec->program_id = $req->program;
                $sec->section = $req->section;
                $sec->update_user = Auth::id();

                if ($sec->save()) {
                    $this->_log->log([
                        'user_id' => Auth::id(),
                        'module' => 'Maintenance - Section',
                        'activity' => 'Updated Section '.$req->section.' to '.$sec->section.'.'
                    ]);

                    $data = [
                        'msg' => 'Section successfully updated.',
                        'status' => 'success'
                    ];
                }
            }
                
        } else {
            if ($this->checkSection($req) > 0) {
                $data = [
                    'msg' => 'Section is already a duplicate.',
                    'status' => 'warning'
                ];
            } else {
                $sec = new Section;

                $sec->program_id = $req->program;
                $sec->section = $req->section;
                $sec->create_user = Auth::id();
                $sec->update_user = Auth::id();

                if ($sec->save()) {
                    $this->_log->log([
                        'user_id' => Auth::id(),
                        'module' => 'Maintenance - Section',
                        'activity' => 'Added a new Section '.$req->section.'.'
                    ]);
                    $data = [
                        'msg' => 'Section successfully saved.',
                        'status' => 'success'
                    ];
                }
            }
                
        }

        return response()->json($data);
    }

    public function checkSection($req)
    {
        $check = Section::where('section',$req->section)
                        ->where('program_id',$req->program)
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
                $sec = Section::find($id);

                if ($sec->delete()) {
                    $data = [
                        'msg' => 'Successfully deleted.',
                        'status' => 'success'
                    ];
                }
            }

            $this->_log->log([
                'user_id' => Auth::id(),
                'module' => 'Maintenance - Section',
                'activity' => 'Deleted Sections.'
            ]);

        } else {
            $sec = Section::find($req->id);

            if ($sec->delete()) {

                $this->_log->log([
                    'user_id' => Auth::id(),
                    'module' => 'Maintenance - Section',
                    'activity' => 'Deleted Section.'
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
