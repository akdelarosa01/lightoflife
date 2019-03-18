<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\IdNumber;
use App\Program;
use App\Department;
use App\Section;
use App\SchoolYear;
use App\Subject;
use DB;

class GlobalController extends Controller
{

	public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function NextIDnumber($code)
    {
        $result = '';
        $new_code = 'ERROR';

        try
        {
            $result = IdNumber::select(
                                        DB::raw("CONCAT(prefix, LPAD(IFNULL(next_no, 0), next_no_length, '0')) AS new_code"),
                                        'next_no',
                                        'month'
                                    )
                                    ->where('code', '=', $code)
                                    ->first();

            if(count((array)$result) <= 0)
            {
                $result->new_code = 'ERROR';
                $result->next_no = 0;
            }

            if ($code == 'INVOICE_NUM') {
                if ($result->month == date('m')) {
                    IdNumber::where('code', '=', $code) ->update(['next_no' => $result->next_no + 1]);
                } else {
                    IdNumber::where('code', '=', $code)->update(['next_no' => 1, 'month' => date('m')]);

                    $result = IdNumber::select(
                                            DB::raw("CONCAT(prefix, LPAD(IFNULL(next_no, 0), next_no_length, '0')) AS new_code"),
                                            'next_no'
                                        )
                                        ->where('code', '=', $code)
                                        ->first();
                }
            } else {
                $result = IdNumber::select(
                                            DB::raw("CONCAT(prefix, LPAD(IFNULL(next_no, 0), next_no_length, '0')) AS new_code"),
                                            'next_no'
                                        )
                                        ->where('code', '=', $code)
                                        ->first();
            }
            

            if(count((array)$result) <= 0)
            {
                $result->new_code = 'ERROR';
                $result->next_no = 0;
            }
            IdNumber::where('code', '=', $code)->update(['next_no' => $result->next_no + 1]);
        }
        catch (Exception $e)
        {
            Log::error($e->getMessage());
        }

        return $result->new_code;
    }

    public function IDnumber($code)
    {
        $id_no;
        $check = IdNumber::where('code',$code)->count();
        if ($check > 0) {
            $id_no = $this->NextIDnumber($code);
            return str_replace('YY',date("y"),$id_no);
        }
    }

    public function getPrograms()
    {
        $prog = Program::select('id','program')->get();
        return response()->json($prog);
    }

    public function getDepartments()
    {
        $dept = Department::select('id','department')->get();
        return response()->json($dept);
    }

    public function getSections(Request $req)
    {
        $sec = Section::select('id','section')
                        ->where('program_id',$req->prog_id)->get();
        return response()->json($sec);
    }

    public function getSchoolYear()
    {
        $sy = SchoolYear::select(
                            DB::raw("concat(`from`,' - ',`to`) as school_year")
                        )->get();
        return response()->json($sy);
    }

    public function convertDate($date,$format)
    {
        $time = strtotime($date);
        $newdate = date($format,$time);
        return $newdate;
    }

    
}
