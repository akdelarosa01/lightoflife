@extends('layouts.app')

@section('content')
<div class="container mt-2">
    <div class="row">
        <div class="offset-md-2 col-md-8">
            <div class="card shadow-lg">
                <div class="card-header bg-red text-white">Given Quiz</div>

                <div class="card-body">

                    <div class="loading"></div>

                    <div class="row mb-2">
                        <div class="col-md-4">
                            <button type="button" class="btn btn-sm btn-danger btn-block" id="btn_delete">
                                <i class="fa fa-trash"></i> Delete
                            </button>
                        </div>
                        <div class="offset-md-4 col-md-4">
                            <button class="btn btn-sm btn-primary btn-block" id="btn_create">
                                <i class="fa fa-plus"></i> Assign Quiz
                            </button>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <table class="table table-sm table-bordered table-striped" width="100%" id="tbl_quizzes">
                                <thead>
                                    <tr>
                                        <td width="5%">
                                            <input type="checkbox" class="check_all_quizzes">
                                        </td>
                                        <td width="5%"></td>
                                        <td>Subject</td>
                                        <td>Quiz Title</td>
                                        <td>Quiz type</td>
                                        <td>Date Given</td>
                                    </tr>
                                </thead>
                                <tbody id="tbl_quizzes_body"></tbody>
                            </table>
                        </div>
                    </div>
                    

                </div>
            </div>
        </div>
    </div>
</div>
@include('modals.modal_give_quiz')

@endsection

@push('scripts')
    <script type="text/javascript" src="{{ asset('/js/pages/give-quiz.js') }}"></script>
@endpush