<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\QuizGivenResults;
use App\QuizGivenResultItem;
use App\QuizGiven;
use App\QuizItem;
use DB;

class CheckWhoTookQuiz extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check:examiner';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $nontakers = DB::select("SELECT qg.student_id,
                                        qg.section_id,
                                        qg.subject_id,
                                        qg.teacher_id,
                                        qg.quiz_id,
                                        qg.id,
                                        qg.quiz_title,
                                        qg.quiz_type,
                                        qg.max_score
                            FROM quiz_givens AS qg
                            LEFT JOIN quiz_given_results AS qgr ON qg.id = qgr.quiz_given_id
                            WHERE concat(qg.due_date,' ',qg.due_time) <= curdate()
                            AND qgr.date_submitted is NULL AND qgr.remarks is NULL
                            AND qg.user_attempt = 0 AND qg.status = 'PENDING'");

        if (count((array)$nontakers) > 0) {
            foreach ($nontakers as $key => $nontaker) {
                QuizGivenResults::insert([
                    'student_id' => $nontaker->student_id,
                    'section_id' => $nontaker->section_id,
                    'subject_id' => $nontaker->subject_id,
                    'teacher_id' => $nontaker->teacher_id,
                    'quiz_id' => $nontaker->quiz_id,
                    'quiz_given_id' => $nontaker->id,
                    'quiz_title' => $nontaker->quiz_title,
                    'quiz_type' => $nontaker->quiz_type,
                    'date_taken' => null,
                    'time_taken' => null,
                    'date_submitted' => null,
                    'time_submitted' => null,
                    'total_points' => 0,
                    'max_score' => $nontaker->max_score,
                    'grade_percent' => 50,
                    'attempt_no' => 0,
                    'remarks' => 'FAILED: DID NOT TAKE THE QUIZ.',
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ]);

                $items = QuizItem::where('quiz_id',$nontaker->quiz_id)->get();
                $lastQGR = QuizGivenResults::select('id')->orderBy('id','desc')->first();

                foreach ($items as $key => $item) {
                    QuizGivenResultItem::insert( [
                        'qgr_id' => $lastQGR->id,
                        'student_id' => $nontaker->student_id,
                        'section_id' => $nontaker->section_id,
                        'subject_id' => $nontaker->subject_id,
                        'teacher_id' => $nontaker->teacher_id,
                        'quiz_id' => $nontaker->quiz_id,
                        'question_num' => $item->question_num,
                        'question' => $item->question,
                        'correct_answer' => $item->answer,
                        'student_answer' => 'NO ANSWER',
                        'score' => 0,
                        'max_score' => $item->points,
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s')
                    ]);
                }

                QuizGiven::where('id',$nontaker->id)->update([
                    'status' => 'FINISHED'
                ]);
            }
        }
    }
}
