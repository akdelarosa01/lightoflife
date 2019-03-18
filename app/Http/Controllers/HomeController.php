<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\GlobalController;
use App\Http\Controllers\Monitoring\LogsController;
use App\Announcement;
use App\User;
use DB;

class HomeController extends Controller
{
    public function welcome()
    {
        if (Auth::check()) {
            return redirect('/home');
        } else {
            return view('auth.login');
        }
    }

    public function index()
    {
        switch (Auth::user()->user_type) {
            case 2:
                return view('home_teacher');
                break;

            case 3:
                $id_number = str_replace('S', 'P', Auth::user()->username);
                $parent = User::where('username',$id_number)->first();
                return view('home_student',['parent' => $parent]);
                break;

            case 4:
                return view('home_parent');
                break;
            
            default:
                return view('home');
                break;
        }
    }

    public function announcement()
    {
        $announcement = Announcement::select(
                                        'title',
                                        'announcement',
                                        'created_at'
                                    )->orderBy('id','desc')->first();
        return response()->json($announcement);
    }

    public function getEnrolledSubject()
    {
        $id_num = '';
        if (Auth::user()->user_type == 3) {
            $id_num = str_replace('P', 'S', Auth::user()->username);
        } else{
            $id_num = str_replace('P', 'S', Auth::user()->username);
        }

        $subs = DB::select("SELECT esd.id_number as id_number,
                                    sub.code as code,
                                    sub.description as description,
                                    concat(sub.code,' - ',sub.description) as `subject`,
                                    sh.teacher_name as teacher_name,
                                    sh.subject_id as subject_id,
                                    esd.section_id as section_id,
                                    esd.program_id as program_id
                            FROM enroll_student_details esd
                            join subject_handles as sh on sh.section_id = esd.section_id
                            join subjects as sub on sub.id = sh.subject_id
                            where esd.id_number='".$id_num."'
                            group by esd.id_number,
                                    sub.code,
                                    sub.description,
                                    concat(sub.code,' - ',sub.description),
                                    sh.teacher_name,
                                    sh.subject_id,
                                    esd.section_id,
                                    esd.program_id");
        return response()->json($subs);
    }

    public function getSubjectHandled()
    {
        $subs = DB::select("SELECT sh.section_id as section_id,
                                    sc.section as section,
                                    sh.subject_id as subject_id,
                                    sb.code,
                                    sb.description,
                                    concat(sb.code,' - ',sb.description) as `subject`,
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
                                    sh.program");
        return response()->json($subs);
    }

    public function getMyClassBySubjectSection(Request $req)
    {
        $class = DB::select("SELECT sh.id_number as teacher_id,
                                    sh.teacher_name as teacher_name,
                                    s.section as section,
                                    esd.id_number as student_id,
                                    u.middlename,
                                    u.firstname,
                                    u.lastname,
                                    if(u.middlename is null,
                                        concat(u.firstname,' ',u.lastname),
                                        concat(u.firstname,' ',u.middlename,' ',u.lastname)
                                    ) as student_name
                            FROM subject_handles as sh
                            join enroll_student_details as esd on esd.section_id = sh.section_id
                            join users as u on u.id = esd.user_id
                            join sections as s on s.id = sh.section_id
                            join subjects as sub on sub.id = sh.subject_id
                            where sh.section_id = ".$req->section_id." and sh.subject_id = ".$req->subject_id."
                            group by sh.id_number,
                                    sh.teacher_name,
                                    s.section,
                                    esd.id_number,
                                    u.middlename,
                                    u.firstname,
                                    u.lastname,
                                    if(u.middlename is null,
                                        concat(u.firstname,' ',u.lastname),
                                        concat(u.firstname,' ',u.middlename,' ',u.lastname)
                                    )");
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
                            and program_id = ".$req->program_id);

        return response()->json($hnds);
    }
}
