<?php

namespace App\Http\Controllers\Settings;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\GlobalController;
use App\Http\Controllers\Monitoring\LogsController;
use App\ActivityLog;
use App\User;

class ChangePasswordController extends Controller
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
    	return view('settings.change_password');
    }

    public function save(Request $req)
    {
    	$data = [
    		'msg' => 'Changing password failed.',
    		'status' => 'warning'
    	];

        $user = User::find(Auth::id());

        $user->password = bcrypt($req->new_password);
        $user->actual_password = $req->new_password;

        if ($user->save()) {
            $this->_log->log([
                'user_id' => Auth::id(),
                'module' => 'Settings - Change Password',
                'activity' => 'Changed password.'
            ]);

        	$data = [
	    		'msg' => 'Password was successfuly changed.',
	    		'status' => 'success'
	    	];
        }

        return response()->json($data);
    }

    public function checkOldPassword(Request $req)
    {
    	$check = User::where('id',Auth::id())
    				->where('actual_password',$req->password)
    				->get();

    	if (count((array)$check) > 0) {
            return response()->json(['status' => 'success'], 200);
    	} else {
            return response()->json([
             'errors' => [
                 'old_password' => 'This password is not match with your current password.'
             ]
            ], 422);
        }
    }
}
