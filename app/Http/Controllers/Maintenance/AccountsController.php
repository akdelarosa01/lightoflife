<?php

namespace App\Http\Controllers\Maintenance;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\GlobalController;
use App\Http\Controllers\Monitoring\LogsController;
use App\ActivityLog;
use App\User;
use App\Teacher;
use App\Student;
use App\ParentUser;
use App\Program;
use App\Department;
use Excel;
use File;
use DB;

class AccountsController extends Controller
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
    	return view('maintenance.accounts');
    }

    public function save(Request $req)
    {
    	switch ($req->user_type) {
            case 2:
                return $this->save_teacher($req);
                break;

            case 3:
                return $this->save_student($req);
                break;
            
            case 1:
                return $this->save_admin($req);
                break;
        }
    }

    public function save_teacher($req)
    {
        $data = [
            'msg' => 'Saving failed.',
            'status' => 'failed'
        ];

        if (!empty($req->id)) {
            $check = Teacher::where('user_id',$req->id)->count();

            if ($check > 0) {
                $user = User::find($req->id);
                $user->firstname = $req->firstname;
                $user->middlename = $req->middlename;
                $user->lastname = $req->lastname;
                $user->user_type = $req->user_type;

                if ($user->save()) {
                    $update = Teacher::where('user_id',$req->id)
                                    ->update([
                                        'program_id' => $req->program,
                                        'dept_id' => $req->department,
                                        'updated_at' => date('Y-m-d H:i:s')
                                    ]);
                    if ($update) {
                        $this->_log->log([
                            'user_id' => Auth::id(),
                            'module' => 'Maintenance - Accounts',
                            'activity' => 'Updated account of '.$user->username.'.'
                        ]);

                        $data = [
                            'msg' => 'Teacher was successfully updated.',
                            'status' => 'success'
                        ];
                    } else {
                        $data = [
                            'msg' => 'Cannot update as teacher.',
                            'status' => 'failed'
                        ];
                    }
                } else {
                    $data = [
                        'msg' => 'Cannot update as teacher.',
                        'status' => 'failed'
                    ];
                }
            } else {
                $data = [
                        'msg' => 'Cannot update as teacher.',
                        'status' => 'failed'
                    ];
            }
                
        } else {
            $check = User::where([
                ['firstname',$req->firstname],
                ['lastname',$req->lastname],
                ['user_type',$req->user_type]
            ])->count();

            if ($check > 0) {
                $data = [
                    'msg' => 'Name is already registered.',
                    'status' => 'failed'
                ];
            } else {
                $user = new User;

                $username = $this->_global->IDnumber('TCHR');
                $password = $this->randomPassword();

                $user->username = $username;
                $user->firstname = $req->firstname;
                $user->middlename = $req->middlename;
                $user->lastname = $req->lastname;
                $user->password = bcrypt($password);
                $user->actual_password = $password;
                $user->user_type = $req->user_type;

                if ($user->save()) {
                    $insert = Teacher::create([
                                        'user_id' => $user->id,
                                        'id_number' => $username,
                                        'program_id' => $req->program,
                                        'dept_id' => $req->department,
                                        'created_at' => date('Y-m-d H:i:s'),
                                        'updated_at' => date('Y-m-d H:i:s')
                                    ]);
                    if ($insert) {
                        $this->_log->log([
                            'user_id' => Auth::id(),
                            'module' => 'Maintenance - Accounts',
                            'activity' => 'Added account of '.$user->username.'.'
                        ]);

                        $data = [
                            'msg' => 'Teacher was successfully saved.',
                            'status' => 'success'
                        ];
                    }
                }
            }
        }

        return response()->json($data);
    }

    public function save_student($req)
    {
    	$data = [
            'msg' => 'Saving failed.',
            'status' => 'failed'
        ];

        if (!empty($req->id)) {
            $check = Student::where('user_id',$req->id)->count();

            if ($check > 0) {
                $user = User::find($req->id);
                $user->firstname = $req->firstname;
                $user->middlename = $req->middlename;
                $user->lastname = $req->lastname;
                $user->user_type = $req->user_type;

                if ($user->save()) {
                    $update = Student::where('user_id',$req->id)
                                    ->update([
                                        'program_id' => $req->program,
                                        'updated_at' => date('Y-m-d H:i:s')
                                    ]);

                    if ($update) {
                        ParentUser::where('student_user_id',$req->id)
                                ->update([
                                    'program_id' => $req->program,
                                    'updated_at' => date('Y-m-d H:i:s')
                                ]);

                        $this->_log->log([
                            'user_id' => Auth::id(),
                            'module' => 'Maintenance - Accounts',
                            'activity' => 'Updated account of '.$user->username.'.'
                        ]);

                        $data = [
                            'msg' => 'Student was successfully updated.',
                            'status' => 'success'
                        ];
                    }
                }
            } else {
                $data = [
                        'msg' => 'Cannot update as student.',
                        'status' => 'failed'
                    ];
            }
                
        } else {
            $check = User::where([
                ['firstname',$req->firstname],
                ['lastname',$req->lastname],
                ['user_type',$req->user_type]
            ])->count();

            if ($check > 0) {
                $data = [
                    'msg' => 'Name is already registered.',
                    'status' => 'failed'
                ];
            } else {
                $user = new User;

                $username = $this->_global->IDnumber('STDNT');
                $password = $this->randomPassword();

                $user->username = $username;
                $user->firstname = $req->firstname;
                $user->middlename = $req->middlename;
                $user->lastname = $req->lastname;
                $user->password = bcrypt($password);
                $user->actual_password = $password;
                $user->user_type = $req->user_type;

                if ($user->save()) {
                    $insert = Student::create([
                                        'user_id' => $user->id,
                                        'id_number' => $username,
                                        'program_id' => $req->program,
                                        'created_at' => date('Y-m-d H:i:s'),
                                        'updated_at' => date('Y-m-d H:i:s')
                                    ]);
                    if ($insert) {

                        $parent = new User;

                        $parent_username = str_replace('S', 'P', $username);

                        $parent->username = $parent_username;
                        $parent->firstname = 'Mr./Mrs.';
                        $parent->middlename = '';
                        $parent->lastname = $req->lastname;
                        $parent->password = bcrypt($password);
                        $parent->actual_password = $password;
                        $parent->user_type = 4;

                        if ($parent->save()) {
                            ParentUser::create([
                                        'user_id' => $parent->id,
                                        'id_number' => $parent_username,
                                        'program_id' => $req->program,
                                        'student_user_id' => $user->id,
                                        'created_at' => date('Y-m-d H:i:s'),
                                        'updated_at' => date('Y-m-d H:i:s')
                                    ]);
                        }

                        $this->_log->log([
                            'user_id' => Auth::id(),
                            'module' => 'Maintenance - Accounts',
                            'activity' => 'Added account of '.$user->username.'.'
                        ]);

                        $data = [
                            'msg' => 'Student was successfully saved.',
                            'status' => 'success'
                        ];
                    }
                }
            }
        }

        return response()->json($data);
    }

    public function save_admin($req)
    {
        $data = [
            'msg' => 'Saving failed.',
            'status' => 'failed'
        ];

        if (!empty($req->id)) {
            $user = User::find($req->id);
            $user->firstname = $req->firstname;
            $user->middlename = $req->middlename;
            $user->lastname = $req->lastname;
            $user->user_type = $req->user_type;
            $user->is_admin = 1;

            if ($user->save()) {
                $this->_log->log([
                    'user_id' => Auth::id(),
                    'module' => 'Maintenance - Accounts',
                    'activity' => 'Updated account of '.$user->username.'.'
                ]);

                $data = [
                    'msg' => 'Admin was successfully updated.',
                    'status' => 'success'
                ];
            }
        } else {
            $check = User::where([
                ['firstname',$req->firstname],
                ['lastname',$req->lastname],
                ['user_type',$req->user_type]
            ])->count();

            if ($check > 0) {
                $data = [
                    'msg' => 'Name is already registered.',
                    'status' => 'failed'
                ];
            } else {
                $user = new User;

                $username = $this->_global->IDnumber('ADMN');
                $password = $this->randomPassword();

                $user->username = $username;
                $user->firstname = $req->firstname;
                $user->middlename = $req->middlename;
                $user->lastname = $req->lastname;
                $user->password = bcrypt($password);
                $user->actual_password = $password;
                $user->user_type = $req->user_type;
                $user->is_admin = 1;

                if ($user->save()) {
                    $this->_log->log([
                        'user_id' => Auth::id(),
                        'module' => 'Maintenance - Accounts',
                        'activity' => 'Added account of '.$user->username.'.'
                    ]);

                    $data = [
                        'msg' => 'Admin was successfully saved.',
                        'status' => 'success'
                    ];
                }
            }
        }

        return response()->json($data);
    }

    public function getAccounts()
    {
    	$accounts = DB::select(
    						"SELECT u.id as id,
                                u.username as username,
                                u.firstname as firstname,
                                ifnull(u.middlename,'') as middlename,
                                u.lastname as lastname,
                                u.user_type as user_type,
                                u.is_deleted as is_deleted,
                                u.actual_password as actual_password,
                                ifnull(if(
                                    t.program_id is null, 
                                    (select ifnull(id,'')
                                    from programs
                                    where id = s.program_id), 
                                    (select ifnull(id,'')
                                    from programs
                                    where id = t.program_id)
                                ),'') as program,
                                ifnull(if(t.dept_id is null, 
                                    '', 
                                    (select id
                                    from departments
                                    where id = t.dept_id)
                                ),'') as department
                            FROM users as u
                            left join teachers as t on u.id = t.user_id
                            left join students as s on u.id = s.user_id
                            where u.user_type <> 4"
    					);
    	return response()->json($accounts);
    }

    public function getInfo(Request $req)
    {
    	$info = [];
    	if ($req->user_type == 2) {
    		$info = Teacher::select(
    							'user_id',
    							'id_number',
    							'program_id',
    							'dept_id'
    						)
    						->where('user_id',$req->id)->first();
    	}

    	if ($req->user_type == 3) {
    		$info = Student::select(
    							'user_id',
    							'id_number',
    							'program_id'
    						)
    						->where('user_id',$req->id)->first();
    	}

    	if (count((array)$info) > 0) {
    		return response()->json($info);
    	}
    }

    private function randomPassword()
    {
        $alphabet = "abcdefghijklmnopqrstuwxyzABCDEFGHIJKLMNOPQRSTUWXYZ0123456789";
        $pass = array(); //remember to declare $pass as an array
        $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
        for ($i = 0; $i < 10; $i++) {
            $n = rand(0, $alphaLength);
            $pass[] = $alphabet[$n];
        }
        return implode($pass); //turn the array into a string
    }

    public function delete(Request $req)
    {
        $data = [
            'msg' => 'Deleting failed.',
            'status' => 'failed'
        ];

        if (is_array($req->ids)) {
            foreach ($req->ids as $key => $id) {
                $users = User::find($id);
                $users->is_deleted = 1;

                if ($users->save()) {
                    $data = [
                        'msg' => 'Successfully deleted.',
                        'status' => 'success'
                    ];
                }
            }

            $this->_log->log([
                'user_id' => Auth::id(),
                'module' => 'Maintenance - Account',
                'activity' => 'Deleted accounts.'
            ]);

        } else {
            $users = User::find($req->id);
            $users->is_deleted = 1;

            if ($users->save()) {

                $this->_log->log([
                    'user_id' => Auth::id(),
                    'module' => 'Maintenance - Account',
                    'activity' => 'Deleted account.'
                ]);

                $data = [
                    'msg' => 'Successfully deleted.',
                    'status' => 'success'
                ];
            }
        }

        return response()->json($data);
    }

    public function enable(Request $req)
    {
        $data = [
            'msg' => 'Enabling failed.',
            'status' => 'failed'
        ];

        if (is_array($req->ids)) {
            foreach ($req->ids as $key => $id) {
                $users = User::find($id);
                $users->is_deleted = 0;

                if ($users->save()) {
                    $data = [
                        'msg' => 'Successfully enabled.',
                        'status' => 'success'
                    ];
                }
            }

            $this->_log->log([
                'user_id' => Auth::id(),
                'module' => 'Maintenance - Account',
                'activity' => 'Enabled accounts.'
            ]);

        } else {
            $users = User::find($req->id);
            $users->is_deleted = 0;

            if ($users->save()) {

                $this->_log->log([
                    'user_id' => Auth::id(),
                    'module' => 'Maintenance - Account',
                    'activity' => 'Enabled account.'
                ]);

                $data = [
                    'msg' => 'Successfully enabled.',
                    'status' => 'success'
                ];
            }
        }

        return response()->json($data);
    }

    public function upload(Request $req)
    {
        $data = [
            'msg' => 'Uploading failed.',
            'status' => 'failed',
            'not_saved' => []
        ];

        $file = $req->file('account_file');
        $fields;
        
        Excel::load($file, function($reader) use(&$fields){
            $fields = $reader->toArray();
        });

        // return dd($fields);

        $not_saved = [];

        // if (isset($fields[0]['firstname']) and isset($fields[0]['middlename']) and isset($fields[0]['lastname']) and isset($fields[0]['user_type']) and isset($fields[0]['year_level']) and isset($fields[0]['department'])) {
            
            foreach ($fields as $key => $field) {
                if ($field['firstname'] == '' and $field['middlename'] == '' and $field['lastname'] == '' and $field['user_type'] == '') {
                    # code...
                } else {
                    if (intval($field['user_type']) == 1 || intval($field['user_type']) == '1') {
                        $check = DB::select("SELECT firstname,
                                                    lastname,
                                                    user_type
                                            FROM users
                                            WHERE REPLACE(UPPER(firstname),' ','') = REPLACE(UPPER(".$field['firstname']."),' ','')
                                            AND REPLACE(UPPER(lastname),' ','') = REPLACE(UPPER(".$field['lastname']."),' ','')
                                            AND user_type = ".intval($field['user_type']));

                        if (count((array)$check) > 0) {
                            array_push($not_saved, [
                                'firstname' => $field['firstname'],
                                'middlename' => $field['middlename'],
                                'lastname' => $field['lastname']
                            ]);
                        } else {
                            $user = new User;

                            $username = $this->_global->IDnumber('ADMN');
                            $password = $this->randomPassword();

                            $user->username = $username;
                            $user->firstname = $field['firstname'];
                            $user->middlename = $field['middlename'];
                            $user->lastname = $field['lastname'];
                            $user->password = bcrypt($password);
                            $user->actual_password = $password;
                            $user->user_type = intval($field['user_type']);
                            $user->is_admin = 1;

                            if ($user->save()) {
                                $this->_log->log([
                                    'user_id' => Auth::id(),
                                    'module' => 'Maintenance - Accounts',
                                    'activity' => 'Added account of '.$user->username.'.'
                                ]);

                                $data = [
                                    'msg' => 'Admin was successfully saved.',
                                    'status' => 'success'
                                ];
                            }
                        }
                    }

                    if (intval($field['user_type']) == 2 || intval($field['user_type']) == '2') {
                        $check = DB::select("SELECT firstname,
                                                    lastname,
                                                    user_type
                                            FROM users
                                            WHERE REPLACE(UPPER(firstname),' ','') = REPLACE(UPPER('".$field['firstname']."'),' ','')
                                            AND REPLACE(UPPER(lastname),' ','') = REPLACE(UPPER('".$field['lastname']."'),' ','')
                                            AND user_type = ".intval($field['user_type']));

                        if (count((array)$check) > 0) {
                            array_push($not_saved, [
                                'firstname' => $field['firstname'],
                                'middlename' => $field['middlename'],
                                'lastname' => $field['lastname']
                            ]);
                        } else {
                            $user = new User;

                            $username = $this->_global->IDnumber('TCHR');
                            $password = $this->randomPassword();

                            $user->username = $username;
                            $user->firstname = $field['firstname'];
                            $user->middlename = $field['middlename'];
                            $user->lastname = $field['lastname'];
                            $user->password = bcrypt($password);
                            $user->actual_password = $password;
                            $user->user_type = intval($field['user_type']);

                            if ($user->save()) {
                                $insert = Teacher::create([
                                                    'user_id' => $user->id,
                                                    'id_number' => $username,
                                                    'program_id' => $this->checkYearLevel($field['year_level']),
                                                    'dept_id' => $this->checkDepartment($field['department'])
                                                ]);
                                if ($insert) {
                                    $this->_log->log([
                                        'user_id' => Auth::id(),
                                        'module' => 'Maintenance - Accounts',
                                        'activity' => 'Added account of '.$user->username.'.'
                                    ]);

                                    $data = [
                                        'msg' => 'Teacher was successfully saved.',
                                        'status' => 'success'
                                    ];
                                }
                            }
                        }
                    }

                    if (intval($field['user_type']) == 3 || intval($field['user_type']) == '3') {
                        $check = DB::select("SELECT firstname,
                                                    lastname,
                                                    user_type
                                            FROM users
                                            WHERE REPLACE(UPPER(firstname),' ','') = REPLACE(UPPER('".$field['firstname']."'),' ','')
                                            AND REPLACE(UPPER(lastname),' ','') = REPLACE(UPPER('".$field['lastname']."'),' ','')
                                            AND user_type = ".intval($field['user_type']));

                        if (count((array)$check) > 0) {
                            array_push($not_saved, [
                                'firstname' => $field['firstname'],
                                'middlename' => $field['middlename'],
                                'lastname' => $field['lastname']
                            ]);
                        } else {
                            $user = new User;

                            $username = $this->_global->IDnumber('STDNT');
                            $password = $this->randomPassword();

                            $user->username = $username;
                            $user->firstname = $field['firstname'];
                            $user->middlename = $field['middlename'];
                            $user->lastname = $field['lastname'];
                            $user->password = bcrypt($password);
                            $user->actual_password = $password;
                            $user->user_type = intval($field['user_type']);

                            if ($user->save()) {
                                $insert = Student::create([
                                                    'user_id' => $user->id,
                                                    'id_number' => $username,
                                                    'program_id' => $this->checkYearLevel($field['year_level'])
                                                ]);

                                $parent = new User;

                                $parent_username = str_replace('S', 'P', $username);

                                $parent->username = $parent_username;
                                $parent->firstname = 'Mr./Mrs.';
                                $parent->middlename = '';
                                $parent->lastname = $field['lastname'];
                                $parent->password = bcrypt($password);
                                $parent->actual_password = $password;
                                $parent->user_type = 4;

                                if ($parent->save()) {
                                    ParentUser::create([
                                                'user_id' => $parent->id,
                                                'id_number' => $parent_username,
                                                'program_id' => $this->checkYearLevel($field['year_level']),
                                                'student_user_id' => $user->id
                                            ]);
                                }

                                if ($insert) {
                                    $this->_log->log([
                                        'user_id' => Auth::id(),
                                        'module' => 'Maintenance - Accounts',
                                        'activity' => 'Added account of '.$user->username.'.'
                                    ]);

                                    $data = [
                                        'msg' => 'Student was successfully saved.',
                                        'status' => 'success'
                                    ];
                                }
                            }
                        }
                    }
                }   
            }

            $data = [
                'msg' => 'Accounts were successfully uploaded.',
                'status' => 'success',
                'not_saved' => $not_saved
            ];
        // } else {
        //     $data = [
        //         'msg' => 'Please use a correct template file.',
        //         'status' => 'failed',
        //         'not_saved' => $not_saved
        //     ];
        // }

        return response()->json($data);
    }

    private function checkYearLevel($yearlevel)
    {
        if (is_numeric($yearlevel)) {
            $progs = Program::select('id','program')->where('id',intval($yearlevel))->first();
            if ($progs->id == intval($yearlevel)) {
                return $progs->id;
            } else {
                return 0;
            }
        } else {
            $progs = Program::select('id',
                                db::raw("REPLACE(UPPER(program), ' ', '') as program")
                            )->get();

            foreach ($progs as $key => $prog) {
                $yr = strtoupper(str_replace(' ', '', $yearlevel));
                // $program = strtoupper(str_replace(' ', '', $prog->program));

                if ($yr == $prog->program) {
                    return $prog->id;
                } else {
                    $yrlvl = new Program;

                    $yrlvl->program = $yearlevel;
                    $yrlvl->create_user = Auth::id();
                    $yrlvl->update_user = Auth::id();

                    if ($yrlvl->save()) {
                        return $yrlvl->id;
                    } else {
                        return 0;
                    }
                }
            }
        }
    }

    private function checkDepartment($dept)
    {
        if (is_numeric($dept)) {
            $depts = Department::select('id','department')->where('id',intval($dept))->first();
            if (count((array)$depts) > 0) {
                return $depts->id;
            } else {
                return 0;
            }
        } else {
            $depts = Department::select('id','department')
                            ->where('department','like','%'.$dept.'%')->first();
            if (count((array)$depts) > 0) {
                return $depts->id;
            } else {

                $new_dept = new Department;

                $new_dept = $dept;
                $new_dept = Auth::id();
                $new_dept = Auth::id();

                if ($new_dept->save()) {
                    return $new_dept->id;
                } else {
                    return 0;
                }
            }
        }
    }
}
