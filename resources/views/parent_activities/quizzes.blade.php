@extends('layouts.app')

@section('content')
<div class="container mt-2">
    <div class="row">
        <div class="offset-md-2 col-md-8">
            <div class="card shadow-lg">
                <div class="card-header bg-red text-white">Quizzes</div>

                <div class="card-body">
                    <div class="loading"></div>

                    <div class="row">
                        <div class="col-md-12">
                            <table class="table table-sm table-bordered table-striped dt-responsive nowrap" width="100%" id="tbl_pending">
                                <thead>
                                    <tr>
                                        <td></td>
                                        <td>Subject</td>
                                        <td>Quiz Title</td>
                                        <td>Status</td>
                                        <td>Date Given</td>
                                        
                                    </tr>
                                </thead>
                                <tbody id="tbl_pending_body"></tbody>
                            </table>
                        </div>
                    </div>

                    <hr>

                    <div class="row">
                        <div class="col-md-12">
                            <table class="table table-sm table-bordered table-striped dt-responsive nowrap" width="100%" id="tbl_finished">
                                <thead>
                                    <tr>
                                        <td></td>
                                        <td>Subject</td>
                                        <td>Quiz Title</td>
                                        <td>Status</td>
                                        <td>Date Submitted</td>
                                    </tr>
                                </thead>
                                <tbody id="tbl_finished_body"></tbody>
                            </table>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@include('modals.modal_parent_quiz')
@endsection

@push('scripts')
<script type="text/javascript" src="{{ asset('/js/pages/parent-quizlist.js') }}"></script>
@endpush