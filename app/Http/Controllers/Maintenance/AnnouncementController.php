<?php

namespace App\Http\Controllers\Maintenance;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\GlobalController;
use App\Http\Controllers\Monitoring\LogsController;
use App\Announcement;
use DB;

class AnnouncementController extends Controller
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
    	return view('maintenance.announcement');
    }

    public function getAnnouncement()
    {
        $ac = DB::table('announcements as ac')->select(
                    DB::raw("ac.id as id"),
                    DB::raw("ac.title as title"),
                    DB::raw("ac.announcement as announcement"),
                    DB::raw("concat(u.firstname,' ',u.firstname) as create_user"),
                    DB::raw("DATE_FORMAT(ac.created_at, '%m/%d/%Y %h:%i %p') as created_at")
                )
                ->join('users as u','u.id','=','ac.create_user')
                ->get();
        return response()->json($ac);
    }

    public function save(Request $req)
    {
        $this->validate($req, [
            'title' => 'required',
            'announcement' => 'required'
        ]);

        $data = [
            'msg' => 'Saving failed.',
            'status' => 'failed'
        ];

        if (!empty($req->id)) {
            $ann = Announcement::find($req->id);

            $ann->title = $req->title;
            $ann->announcement = $req->announcement;
            $ann->update_user = Auth::id();

            if ($ann->save()) {
                $this->_log->log([
                    'user_id' => Auth::id(),
                    'module' => 'Maintenance - Announcement',
                    'activity' => 'Updated Announcement '.$req->title.' to '.$ann->title.'.'
                ]);

                $data = [
                    'msg' => 'Announcement successfully updated.',
                    'status' => 'success'
                ];
            }
        } else {
            $ann = new Announcement;

            $ann->title = $req->title;
            $ann->announcement = $req->announcement;
            $ann->create_user = Auth::id();
            $ann->update_user = Auth::id();

            if ($ann->save()) {
                $this->_log->log([
                    'user_id' => Auth::id(),
                    'module' => 'Maintenance - Announcement',
                    'activity' => 'Posted a new announcement '.$req->title.'.'
                ]);
                $data = [
                    'msg' => 'Announcement successfully posted.',
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
                $ann = Announcement::find($id);

                if ($ann->delete()) {
                    $data = [
                        'msg' => 'Successfully deleted.',
                        'status' => 'success'
                    ];
                }
            }

            $this->_log->log([
                'user_id' => Auth::id(),
                'module' => 'Maintenance - Announcement',
                'activity' => 'Deleted announcements.'
            ]);

        } else {
            $ann = Announcement::find($req->id);

            if ($ann->delete()) {

                $this->_log->log([
                    'user_id' => Auth::id(),
                    'module' => 'Maintenance - Announcement',
                    'activity' => 'Deleted announcement.'
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
