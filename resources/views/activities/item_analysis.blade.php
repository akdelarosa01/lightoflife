@extends('layouts.app')

@section('content')
<div class="container mt-2">
    <div class="row">
        <div class="offset-md-2 col-md-8">
            <div class="card shadow-lg">
                <div class="card-header bg-red text-white">Quiz Item Analysis</div>

                <div class="card-body">

                    <div class="loading"></div>

                    <div class="row mb-2">
                        <div class="col-md-12">
                            <table class="table table-sm table-bordered table-striped" width="100%" id="tbl_quiz">
                                <thead>
                                    <tr>
                                        <td width="5%"></td>
                                        <td>Subject</td>
                                        <td>Section</td>
                                        <td>Title</td>
                                        <td>Quiz Type</td>
                                    </tr>
                                </thead>
                                <tbody id="tbl_quiz_body"></tbody>
                            </table>
                        </div>
                    </div>
                    

                </div>
            </div>
        </div>
    </div>
</div>
@include('modals.modal_item_analysis')

@endsection

@push('scripts')
    <script type="text/javascript" src="{{ asset('/js/pages/item_analysis.js') }}"></script>
@endpush