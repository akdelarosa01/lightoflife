@extends('layouts.app')

@section('content')
<div class="container mt-2">
    <div class="row">
        <div class="offset-md-2 col-md-8">
            <div class="card shadow-lg">
                <div class="card-header bg-red text-white">
                	<i class="fa fa-pencil"></i> Take Quiz
                </div>

                <div class="card-body">
                    <form class="form-horizontal" method="post" id="frm_quiz" action="{{ url('student-activities/submit-quiz') }}">
                        {{ csrf_field() }}

                        <div class="form-group row">
                            <div class="col-md-12">
                                <div class="input-group input-group-sm">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Subject:</span>
                                    </div>
                                    <input type="text" class="form-control form-control-sm clear" name="subject" id="subject" value="{{ $quiz_details->subject }}" readonly>
                                    <input type="hidden" class="clear" name="subject_id" id="subject_id" value="{{ $quiz_details->subject_id }}" >
                                    <input type="hidden" class="clear" name="section_id" id="section_id" value="{{ $details->section_id }}" >
                                    <input type="hidden" class="clear" name="teacher_id" id="teacher_id" value="{{ $details->teacher_id }}" >
                                    <input type="hidden" class="clear" name="qg_id" id="qg_id" value="{{ $details->id }}" >
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-12">
                                <div class="input-group input-group-sm">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Quiz Title:</span>
                                    </div>
                                    <input type="text" class="form-control form-control-sm clear" name="quiz_title" id="quiz_title" value="{{ $details->quiz_title }}" readonly>
                                    <input type="hidden" name="quiz_id" value="{{ $quiz_details->quiz_id }}">
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-12">
                                <div class="input-group input-group-sm">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Quiz Type:</span>
                                    </div>
                                    <input type="text" class="form-control form-control-sm clear" name="quiz_type" id="quiz_type" value="{{ $details->quiz_type }}" readonly>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-12">
                                <div class="input-group input-group-sm">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Timer:</span>
                                    </div>
                                    <input type="text" class="form-control form-control-sm clear" name="timer" id="timer" value="{{ $details->timer.':00' }}" readonly>
                                </div>
                            </div>
                        </div>

                        <hr>

                        <div class="row mb-2">
                            <div class="col-md-12" id="quiz_items">
                                <table class="table table-striped">


                                    <?php
                                        $count = 0;
                                        foreach ($items as $key => $item) {
                                    ?>
                                            <tr style="background-color: #379b2f !important">
                                                <td id="question_num'+x.question_num+'" width="20%" style="color: #fff">
                                                    Question # {{ $item->question_num }}:
                                                </td>
                                                <td id="question_item{{ $item->question_num }}" style="color: #fff">
                                                    {{ $item->question }}
                                                    <input type="hidden" name="question_num[]" value="{{ $item->question_num }}">
                                                    <input type="hidden" name="correct_answer[]" value="{{ $item->answer }}">
                                                    <input type="hidden" name="points[]" value="{{ $item->points }}">
                                                    <input type="hidden" name="question[]" value="{{ $item->question }}">

                                                </td>
                                            </tr>

                                            <tr style="background-color:#2e2623">
                                                <td id="answer_num" width="20%" style="color: #fff">Answer:</td>
                                                <td id="answer_item">
                                    <?php
                                                if ($item->quiz_type == 'TRUE OR FALSE') {
                                                ?>
                                                    <select type="text" class="form-control form-control-sm" name="student_answer[]">
                                                        <option value="TRUE">TRUE</option>
                                                        <option value="FALSE">FALSE</option>
                                                    </select>
                                                <?php
                                                        
                                                } else if ($item->quiz_type == 'MULTIPLE CHOICE') {
                                                    if (count((array)$choices) > 0) {

                                                ?>
                                                        <select type="text" class="form-control form-control-sm" name="student_answer[]">
                                                            <?php
                                                            foreach ($choices as $key => $ch) {
                                                                if ($ch->quiz_item_id == $item->id) {
                                                            ?>
                                                                    <option value="{{ $ch->choice }}">{{ $ch->choice.' - '.$ch->choice_desc }}</option>
                                                            <?php
                                                                }
                                                            }
                                                            ?>
                                                            
                                                        </select>
                                                
                                                <?php
                                                    }
                                                } else {
                                                ?>
                                                    <input type="text" class="form-control form-control-sm" name="student_answer[]" value="">
                                                <?php
                                                }
                                                ?>

                                                </td>
                                            </tr>
                                    <?php
                                        $count++;
                                        }


                                    ?>
                                    

                                </table>
                            </div>
                        </div>

                        <div class="row">
                            <div class="offset-md-8 col-md-4">
                                <button type="submit" class="btn btn-sm btn-primary btn-block">
                                    <i class="fa fa-send"></i> Submit
                                </button>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script type="text/javascript" src="{{ asset('/js/pages/take_quiz.js') }}"></script>
@endpush