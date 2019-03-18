<?php

namespace App\Http\Controllers\Transaction;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\GlobalController;
use App\Http\Controllers\Monitoring\LogsController;
use App\Subject;
use App\Section;
use App\SubjectToSection;
use App\SubjectToSectionDetail;
use DB;

class SubjectToSectionController extends Controller
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
    	return view('transaction.subject_to_section');
    }

    public function getAssigned()
    {
    	$assigned = DB::select("SELECT
									ss.id as id,
								    p.program as program,
								    s.section as section,
								    DATE_FORMAT(ss.created_at, '%m/%d/%Y %h:%i %p') as created_at
								FROM subject_to_sections as ss
								join programs as p on p.id = ss.program_id
								join sections as s on s.id = ss.section_id
								group by ss.id,
								    p.program,
								    s.section,
								    ss.created_at");
    	return response()->json($assigned);
    }

    public function getSectionSubject(Request $req)
    {
    	$data = [
    		'sections' => '',
    		'subjects' => ''
    	];

    	$sec = DB::select("SELECT s.id,
								p.program,
								s.section 
						FROM sections as s
						join programs as p on p.id = s.program_id
						where s.program_id = ".$req->program_id."
						group by s.id,
								p.program,
								s.section");

    	$sub = Subject::select('id','code','description')
    					->where('program_id',$req->program_id)
    					->get();

    	$data = [
    		'sections' => $sec,
    		'subjects' => $sub
    	];

    	return response()->json($data);
    }

    public function save(Request $req)
    {
        $data = [
            'msg' => 'Assigning failed.',
            'status' => 'failed'
        ];

        foreach ($req->section as $key => $sec) {
            $checksec = SubjectToSection::where('program_id',$req->program)
                                        ->where('section_id',$sec)->count();
            if ($checksec > 0) {
                $data = [
                    'msg' => 'Section is already assigned to some subjects.',
                    'status' => 'failed'
                ];
            } else {
                $secs = SubjectToSection::create([
                            'program_id' => $req->program,
                            'section_id' => $sec,
                            'create_user' => Auth::id(),
                            'update_user' => Auth::id()
                        ]);

                $check = SubjectToSectionDetail::where('sts_id',$secs->id)->count();

                if ($check > 0) {
                    
                } else {

                    foreach ($req->subject as $key => $sub) {
                        $code = Subject::select('code')->where('id',$sub)->first();
                        $subs = SubjectToSectionDetail::create([
                                    'sts_id' => $secs->id,
                                    'subj_id' => $sub,
                                    'code' => $code->code
                                ]);
                    }
                }

                $data = [
                    'msg' => 'Subjects were successfully assigned.',
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
                $ann = SubjectToSection::find($id);

                if ($ann->delete()) {
                    SubjectToSectionDetail::where('sts_id',$id)->delete();
                    $data = [
                        'msg' => 'Successfully deleted.',
                        'status' => 'success'
                    ];
                }
            }

            $this->_log->log([
                'user_id' => Auth::id(),
                'module' => 'Transaction - Subjects to section',
                'activity' => 'Deleted subjects.'
            ]);

        } else {
            $ann = SubjectToSection::find($req->id);

            if ($ann->delete()) {
                SubjectToSectionDetail::where('sts_id',$id)->delete();
                $this->_log->log([
                    'user_id' => Auth::id(),
                    'module' => 'Transaction - Subjects to section',
                    'activity' => 'Deleted subjects.'
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
