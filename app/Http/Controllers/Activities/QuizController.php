<?php

namespace App\Http\Controllers\Activities;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\GlobalController;
use App\Http\Controllers\Monitoring\LogsController;
use App\ActivityLog;
use App\Quiz;
use App\QuizItem;
use App\QuizItemChoice;
use File;
use DB;

class QuizController extends Controller
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
    	return view('activities.quizzes');
    }

    public function getQuizzes()
    {
        $quiz = DB::select("SELECT q.id as id,
                                    concat(s.code,' - ',s.description) as `subject`,
                                    q.quiz_title as quiz_title,
                                    q.subject_id as subject_id,
                                    q.quiz_type as quiz_type,
                                    q.no_of_items as no_of_items,
                                    q.no_of_choices as no_of_choices,
                                    DATE_FORMAT(q.created_at, '%m/%d/%Y %h:%i %p') as created_at
                            FROM quizzes as q
                            join subjects as s on s.id = q.subject_id
                            where q.user_id = ".Auth::id());
        return response()->json($quiz);
    }

    public function getQuizDetails(Request $req)
    {
        $items = QuizItem::where('quiz_id',$req->quiz_id)->get();
        $choices = QuizItemChoice::where('quiz_id',$req->quiz_id)->get();

        $data = [
            'items' => $items,
            'choices' => $choices
        ];

        return response()->json($data);
    }

    public function getSubjectHandle()
    {
        $subj = DB::select("SELECT sh.subject_id as id,
                                    concat(s.code,' - ',s.description) as `subject`
                            FROM subject_handles as sh
                            join subjects as s on s.id = sh.subject_id
                            where sh.user_id=".Auth::id());

        return response()->json($subj);
    }

    public function save(Request $req)
    {
        $data = [
            'msg' => 'Saving failed',
            'status' => 'failed'
        ];

        $choices = ['A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z'];

        if (is_null($req->quiz_id) || $req->quiz_id == '') {
            $quiz = new Quiz;
            $quiz->subject_id = $req->subject;
            $quiz->user_id = Auth::id();
            $quiz->id_number = Auth::user()->username;
            $quiz->quiz_title = $req->quiz_title;
            $quiz->quiz_type = $req->quiz_type;
            $quiz->no_of_items = $req->no_of_items;
            $quiz->no_of_choices = ($req->no_of_choices == '' || is_null($req->no_of_choices))? 0 : $req->no_of_choices;
            $quiz->create_user = Auth::id();
            $quiz->update_user = Auth::id();

            if ($quiz->save()) {
                $num = 1;
                foreach ($req->question as $key => $question) {
                    $item = QuizItem::create([
                                'quiz_id' => $quiz->id,
                                'question_num' => $num,
                                'question' => $question,
                                'answer' => $req->answer_item[$key],
                                'points' => $req->points_item[$key],
                                'quiz_type' => $req->quiz_type,
                            ]);

                    if ($req->no_of_choices > 0) {
                        if (count($req->choice_item) > 0) {
                            foreach ($req->choice_item[$key] as $key_choice => $choice_desc) {
                                QuizItemChoice::create([
                                    'quiz_id' => $quiz->id,
                                    'quiz_item_id' => $item->id,
                                    'choice' => $choices[$key_choice],
                                    'choice_desc' => $choice_desc,
                                ]);
                            }
                        }
                    }
                        

                    $num++;
                }

                $data = [
                    'msg' => 'Successfully saved.',
                    'status' => 'success'
                ];

            }
        } else {
            $quiz = Quiz::find($req->quiz_id);

            $quiz->subject_id = $req->subject;
            $quiz->user_id = Auth::id();
            $quiz->id_number = Auth::user()->username;
            $quiz->quiz_title = $req->quiz_title;
            $quiz->quiz_type = $req->quiz_type;
            $quiz->no_of_items = $req->no_of_items;
            $quiz->no_of_choices = ($req->no_of_choices == '' || is_null($req->no_of_choices))? 0 : $req->no_of_choices;
            $quiz->update_user = Auth::id();

            if ($quiz->save()) {
                QuizItem::where('quiz_id',$req->quiz_id)->delete();
                QuizItemChoice::where('quiz_id',$req->quiz_id)->delete();

                $num = 1;
                foreach ($req->question as $key => $question) {
                    $item = QuizItem::create([
                                'quiz_id' => $quiz->id,
                                'question_num' => $num,
                                'question' => $question,
                                'answer' => $req->answer_item[$key],
                                'points' => $req->points_item[$key],
                                'quiz_type' => $req->quiz_type,
                            ]);

                    if ($req->no_of_choices > 0) {
                        if (count($req->choice_item) > 0) {
                            foreach ($req->choice_item[$key] as $key_choice => $choice_desc) {
                                QuizItemChoice::create([
                                    'quiz_id' => $quiz->id,
                                    'quiz_item_id' => $item->id,
                                    'choice' => $choices[$key_choice],
                                    'choice_desc' => $choice_desc,
                                ]);
                            }
                        }
                    }

                    $num++;
                }

                $data = [
                    'msg' => 'Successfully saved.',
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
                $ann = Quiz::find($id);

                if ($ann->delete()) {
                    QuizItem::where('quiz_id',$id)->delete();
                    QuizItemChoice::where('quiz_id',$id)->delete();

                    $data = [
                        'msg' => 'Successfully deleted.',
                        'status' => 'success'
                    ];
                }
            }

            $this->_log->log([
                'user_id' => Auth::id(),
                'module' => 'Activities - Quiz',
                'activity' => 'Deleted quizzes.'
            ]);

        } else {
            $ann = Quiz::find($req->ids);

            if ($ann->delete()) {

                QuizItem::where('quiz_id',$req->ids)->delete();
                QuizItemChoice::where('quiz_id',$req->ids)->delete();

                $this->_log->log([
                    'user_id' => Auth::id(),
                    'module' => 'Activities - Quiz',
                    'activity' => 'Deleted quiz.'
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
