@extends('layouts.app')

@section('content')
<div class="container mt-2">
    <div class="row">
        <div class="offset-md-2 col-md-8">
            <div class="card shadow-lg">
                <div class="card-header bg-red text-white">
                	<i class="fa fa-pencil"></i> Quiz Results
                </div>

                <div class="card-body">
                    <div class="row mb-2">
                        <div class="col-md-12">
                            <table class="table table-bordered table-sm table-striped" width="100%">
                                <tr>
                                    <td>Subject</td>
                                    <td>{{ $subject }}</td>
                                    <td>Date Taken</td>
                                    <td>{{ $qg_results->date_taken.' '.$qg_results->time_taken }}</td>
                                </tr>

                                <tr>
                                    <td>Quiz Type</td>
                                    <td>{{ $quiz_given->quiz_type }}</td>
                                    <td>Date Submitted</td>
                                    <td>{{ $qg_results->date_submitted }}</td>
                                </tr>

                                <tr>
                                    <td>Max Score</td>
                                    <td>{{ $qg_results->max_score }}</td>
                                    <td>Score</td>
                                    <td>{{ $qg_results->total_points.' out of '.$qg_results->max_score }}</td>
                                </tr>

                                <tr>
                                    <td>Start</td>
                                    <td>{{ $quiz_given->start_date.' '.$quiz_given->start_time }}</td>
                                    <td>Final Grade</td>
                                    <td>{{ $qg_results->grade_percent.'%' }}</td>
                                </tr>

                                <tr>
                                    <td>Due</td>
                                    <td>{{ $quiz_given->due_date.' '.$quiz_given->due_time }}</td>
                                    <td>Remarks</td>
                                    <td>{{ $qg_results->remarks }}</td>
                                </tr>

                                <tr>
                                    <td>Timer</td>
                                    <td>{{ $quiz_given->timer }}</td>
                                    <td colspan="2"></td>
                                </tr>

                                <tr>
                                    <td>Max Attempts</td>
                                    <td>{{ $quiz_given->max_attempt }}</td>
                                    <td colspan="2"></td>
                                </tr>

                                <tr>
                                    <td>User Attempts</td>
                                    <td>{{ $quiz_given->user_attempt }}</td>
                                    <td colspan="2"></td>
                                </tr>

                                <tr>
                                    <td>Allow Late Submission</td>
                                    <td>
                                        @if ($quiz_given->late_submission == 0)
                                            {{ 'NO' }}
                                        @else
                                            {{ 'YES' }}
                                        @endif
                                    </td>
                                    <td colspan="2"></td>
                                </tr>

                                <tr>
                                    <td>Status</td>
                                    <td>
                                        @if ($quiz_given->status == 'FINISHED')
                                            <b style="color: #0b8e0d">{{ $quiz_given->status }}</b>
                                        @else
                                            <b style="color: #bd0707">{{ $quiz_given->status }}</b>
                                        @endif
                                        
                                    </td>
                                    <td colspan="2"></td>
                                </tr>

                                <tr>
                                    <td colspan="2">Instruction:</td>
                                    <td colspan="2">{{ $quiz_given->instruction }}</td>
                                </tr>

                                <tr>
                                    <td colspan="4">Questions:</td>
                                </tr>

                                <?php
                                    foreach ($qg_item as $key => $qgi) {
                                ?>
                                        <tr>
                                            <td colspan="2">{{ $qgi->question_num.'. '.$qgi->question }}</td>
                                            <td>{{ 'Your Answer is: '.$qgi->student_answer }}</td>
                                            <td>
                                                {{ 'Score: '.$qgi->score.' out of '.$qgi->max_score }}
                                                <?php
                                                    if ($qgi->student_answer == $qgi->correct_answer) {
                                                ?>
                                                    <i class="fa fa-check" style="color: #0b8e0d"></i>
                                                <?php
                                                    } else {
                                                ?>
                                                    <i class="fa fa-times" style="color: #bd0707"></i>
                                                <?php

                                                    }
                                                ?>
                                            </td>
                                        </tr>

                                <?php
                                    }
                                ?>
                            </table>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <a href="{{ url('student-activities/quizzes') }}" class="btn btn-sm btn-secondary btn-block">Back to Quiz List</a>
                        </div>
                    </div>
                            
                </div>

            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script type="text/javascript" src="{{ asset('/js/pages/take_quiz.js') }}"></script>
@endpush