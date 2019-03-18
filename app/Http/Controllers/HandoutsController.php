<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\GlobalController;
use App\Http\Controllers\Monitoring\LogsController;
use App\Handout;
use File;
use DB;

class HandoutsController extends Controller
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
        return view('handouts');
    }

    public function getClass()
    {
    	$class = DB::select("SELECT sh.section_id as section_id,
                                    sc.section as section,
                                    sh.subject_id as subject_id,
                                    concat(sb.code,' - ',sb.description) as `subject`,
                                    sh.program_id as program_id,
                                    sh.program as program
                            FROM subject_handles as sh
                            join subjects as sb on sb.id = sh.subject_id
                            join sections as sc on sc.id = sh.section_id
                            where sh.user_id = ".Auth::id()."
                            group by sh.section_id,
                                    sc.section,
                                    sh.subject_id,
                                    sb.code,
                                    sb.description,
                                    concat(sb.code,' - ',sb.description),
                                    sh.program_id,
                                    sh.program");
		return response()->json($class);
    }

    public function getHandouts(Request $req)
    {
    	$hnds = DB::select("SELECT id,
    								title,
									ifnull(description,'') as description,
							        file_path,
							        DATE_FORMAT(created_at, '%m/%d/%Y %h:%i %p') as date_uploaded
							FROM handouts
							where section_id = ".$req->section_id."
							and subject_id = ".$req->subject_id."
							and program_id = ".$req->program_id."
							and user_id = ".Auth::id());

    	return response()->json($hnds);
    }

    public function save(Request $req)
    {
    	$data = [
			'msg' => 'Saving failed.',
			'status' => 'failed'
		];

    	$hd = new Handout();

    	$hd->user_id = Auth::id();
    	$hd->id_number = Auth::user()->username;
    	$hd->program_id = $req->program_id;
    	$hd->subject_id = $req->subject_id;
    	$hd->section_id = $req->section_id;
    	$hd->title = $req->title;
    	$hd->description = $req->description;
    	$hd->create_user = Auth::id();
    	$hd->update_user = Auth::id();

    	if ($hd->save()) {
    		$this->uploadfile($hd->id,$req->handouts_file,$req->title,$req->program,$req->section,$req->subject);

    		$this->_log->log([
                'user_id' => Auth::id(),
                'module' => 'Handouts',
                'activity' => 'Uploaded and added handout.'
            ]);

    		$data = [
    			'msg' => 'Handout was successfully saved',
    			'status' => 'success'
    		];
    	}

    	return response()->json($data);
    }

    private function uploadfile($id,$file,$title,$program,$section,$subject)
    {
        if (isset($file)) {
            $dbPath = str_replace(' ', '', 'uploads/handouts/'.$program.' - '.$section.'/'.$subject.'/');
            $destinationPath = str_replace(' ', '', public_path($dbPath));
            $filetitle = str_replace(' ', '-', $title);
            $fileName = $file->getClientOriginalName();

            if (!File::exists($destinationPath)) {
                File::makeDirectory($destinationPath, 0777, true, true);
            }

            if (File::exists($destinationPath.'/'.$fileName)) {
                File::delete($destinationPath.'/'.$fileName);
            }

            $file->move($destinationPath, $fileName);

            $handout = Handout::find($id);
            $handout->file_path = $dbPath.$fileName;
            $handout->update();
        } else {
            // $user = Handout::find($id);
            // $user->photo = 'images/default-profile.png';
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
                $hnd = Handout::find($id);

                $destinationPath = public_path($hnd->file_path);

                if (File::exists($destinationPath)) {
                	File::delete($destinationPath);
                }

                if ($hnd->delete()) {
                    $data = [
                        'msg' => 'Successfully deleted.',
                        'status' => 'success'
                    ];
                }
            }

            $this->_log->log([
                'user_id' => Auth::id(),
                'module' => 'Handouts',
                'activity' => 'Deleted handouts.'
            ]);

        } else {
            $hnd = Handout::find($req->id);

            $destinationPath = public_path($hnd->file_path);

            if (File::exists($destinationPath)) {
            	File::delete($destinationPath);
            }

            if ($hnd->delete()) {

                $this->_log->log([
                    'user_id' => Auth::id(),
                    'module' => 'Handouts',
                    'activity' => 'Deleted handout.'
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
