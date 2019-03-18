<?php

namespace App\Http\Controllers\Monitoring;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\GlobalController;
use App\ActivityLog;
use DB;

class LogsController extends Controller
{
    protected $_global;

    public function __construct()
    {
        $this->_global = new GlobalController;
    }

    public function activity_log_view()
    {
    	return view('monitoring.acitvity_logs');
    }

    public function activity_logs()
    {
    	$logs = DB::table('activity_logs as al')
    				->join('users as u','al.user_id','=','u.id')
    				->orderBy('created_at','desc')
    				->select(
    					DB::raw("u.username as username"),
    					DB::raw("concat(u.firstname,' ',u.lastname) as fullname"),
    					DB::raw("al.module as module"),
    					DB::raw("al.activity as activity"),
    					DB::raw("DATE_FORMAT(al.created_at, '%m/%d/%Y %h:%i %p') as created_at")
    				)
    				->get();
    	return response()->json($logs);
    }

    public function log(array $activity)
    {
    	ActivityLog::create($activity);
    }

    public function user_log_view()
    {
    	return view('monitoring.user_logs');
    }

    public function user_logs()
    {
    	$logs = DB::table('user_logs as ul')
    				->join('users as u','ul.user_id','=','u.id')
    				->orderBy('ul.logged_at','desc')
    				->select(
    					DB::raw("u.username as username"),
    					DB::raw("concat(u.firstname,' ',u.lastname) as fullname"),
    					DB::raw("ul.log_type as log_type"),
    					DB::raw("DATE_FORMAT(ul.logged_at, '%m/%d/%Y %h:%i %p') as logged_at")
    				)
    				->get();
    	return response()->json($logs);
    }
}
