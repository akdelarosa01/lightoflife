<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\GlobalController;
use App\Http\Controllers\Monitoring\LogsController;
use App\Message;
use App\Conversation;
use App\MessageAttachment;
use App\User;
use File;
use DB;

class MessageController extends Controller
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
        return view('message.inbox');
    }

    public function getMessages()
    {
    	$msg = DB::select("SELECT m.subject_id,
								m.subject_msg,
								DATE_FORMAT(c.date_sent, '%m/%d/%Y') as date_sent,
								(select concat(u.firstname,' ',u.lastname) 
									from users as u where u.id = c.user_id_from) as user_from
							FROM messages as m
							join conversations as c on m.subject_id = c.subject_id
							where m.user_id_from = ".Auth::id()."
							or c.user_id_to = ".Auth::id()."
							group by m.subject_id,
								m.subject_msg,
								c.user_id_from,
								DATE_FORMAT(c.date_sent, '%m/%d/%Y'),
							    (select concat(u.firstname,' ',u.lastname) 
									from users as u where u.id = c.user_id_from)");

    	return response()->json($msg);
    }

    public function getRecipients()
    {
    	$users = User::select(
    					'id',
    					DB::raw("concat(firstname,' ',lastname) as fullname")
    				)->orderBy('lastname','asc')
    				->get();
    	return response()->json($users);
    }

    public function conversation($id)
    {
    	return view('message.conversation',['id' => $id]);
    }

    public function getConversations(Request $req)
    {
    	$msg = DB::select("SELECT c.subject_id,
								c.message,
						        (select concat(u.firstname,' ',u.lastname) 
						        from users as u where u.id = c.user_id_from) as user_from,
						        (select concat(u.firstname,' ',u.lastname) 
						        from users as u where u.id = c.user_id_to) as user_to,
								c.user_id_from,
								c.user_id_to,
						        DATE_FORMAT(date_sent, '%m/%d/%Y %h:%i %p') as date_sent
						FROM conversations as c
						where c.subject_id = '".$req->subject_id."'
						AND user_id_from = ".Auth::id()." or user_id_to = ".Auth::id()."
						order by date_sent asc");

    	$att = DB::select("SELECT *
    					FROM message_attachments
    					where subject_id = '".$req->subject_id."'");

    	$data = [
    		'msg' => $msg,
    		'attachments' => $att,
    		'current_user' => Auth::id(),
    		'recipient_id' => (isset($msg))? $msg[0]->user_id_from : 0
    	];

    	return response()->json($data);
    }

    public function SendMessage(Request $req)
    {
    	$data = [
    		'msg' => 'Sending failed.',
    		'status' => 'failed'
    	];

    	$files = $req->file('msg_file');

    	$subject_id = $this->randomID();

    	$msg = new Message;

		$msg->subject_id = $subject_id;
		$msg->subject_msg = $req->subject_msg;
    	$msg->user_id_from = Auth::id();
    	$msg->to_read = 0;
    	$msg->date_sent = date('Y-m-d H:i:s');
    	$msg->deleted_from = 0;
    	$msg->deleted_to = 0;
    	$msg->create_user = Auth::id();
    	$msg->update_user = Auth::id();

    	if ($msg->save()) {
    		foreach ($req->recipients as $key => $recipient) {
	    		$convo = Conversation::insert([
	    					'subject_id' => $subject_id,
					    	'user_id_from' => Auth::id(),
					    	'user_id_to' => $recipient,
					    	'to_read' => 0,
					    	'message' => $req->message,
					    	'date_sent' => date('Y-m-d H:i:s'),
					    	'create_user' => Auth::id(),
					    	'update_user' => Auth::id(),
					    	'created_at' => date('Y-m-d H:i:s'),
					    	'updated_at' => date('Y-m-d H:i:s')
			    		]);
	    		

	    		if ($convo) {
	    			$data = [
			    		'msg' => 'Message sent!',
			    		'status' => 'success'
			    	];
	    		}
	    	}

	    	if (isset($files)) {
				foreach ($files as $key => $file) {
					$this->uploadfile($subject_id,$file);
				}
			}
    	}

	    	

    	return response()->json($data);
    }

    public function SendReply(Request $req)
    {
    	$data = [
    		'msg' => 'Sending failed.',
    		'status' => 'failed'
    	];

    	$files = $req->file('msg_file');
		$convo = Conversation::insert([
					'subject_id' => $req->subject_id,
			    	'user_id_from' => Auth::id(),
			    	'user_id_to' => $req->user_to,
			    	'to_read' => 0,
			    	'message' => $req->reply,
			    	'date_sent' => date('Y-m-d H:i:s'),
			    	'create_user' => Auth::id(),
			    	'update_user' => Auth::id(),
			    	'created_at' => date('Y-m-d H:i:s'),
			    	'updated_at' => date('Y-m-d H:i:s')
	    		]);
		

		if ($convo) {
			$data = [
	    		'msg' => 'Message sent!',
	    		'status' => 'success'
	    	];
		}

    	if (isset($files)) {
			foreach ($files as $key => $file) {
				$this->uploadfile($req->subject_id,$file);
			}
		}

    	return response()->json($data);
    }

    private function randomID()
    {
        $alphabet = "0123456789";
        $pass = array(); //remember to declare $pass as an array
        $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
        for ($i = 0; $i < 10; $i++) {
            $n = rand(0, $alphaLength);
            $pass[] = $alphabet[$n];
        }
        return implode($pass); //turn the array into a string
    }

    private function uploadfile($subject_id,$file)
    {
        if (isset($file)) {
            $dbPath = 'uploads/messages/'.Auth::user()->username.'/'.$subject_id.'/';
            $destinationPath = public_path($dbPath);
            $fileName = $file->getClientOriginalName();

            if (!File::exists($destinationPath)) {
                File::makeDirectory($destinationPath, 0777, true, true);
            }

            if (File::exists($destinationPath.'/'.$fileName)) {
                File::delete($destinationPath.'/'.$fileName);
            }

            $file->move($destinationPath, $fileName);

            MessageAttachment::create([
                'subject_id' => $subject_id,
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
                $dept = Message::where('subject_id',$id)->delete();
            	Conversation::where('subject_id',$id)->delete();
                $data = [
                    'msg' => 'Successfully deleted.',
                    'status' => 'success'
                ];
            }

            $this->_log->log([
                'user_id' => Auth::id(),
                'module' => 'Messages',
                'activity' => 'Deleted messages.'
            ]);

        } else {
            $dept = Message::where('subject_id',$req->ids)->delete();

            if ($dept) {
            	Conversation::where('subject_id',$req->ids)->delete();
                $this->_log->log([
                    'user_id' => Auth::id(),
                    'module' => 'Messages',
                    'activity' => 'Deleted message.'
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
