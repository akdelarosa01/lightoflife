@extends('layouts.app')

@section('content')
<div class="container mt-2">
    <div class="row">
        <div class="offset-md-2 col-md-8">
            <div class="card shadow-lg">
                <div class="card-header bg-red text-white">Enroll Students</div>

                <div class="card-body">

                    <div class="loading"></div>

                    <div class="row mb-2">
                        <div class="offset-md-8 col-md-4">
                            <button class="btn btn-sm btn-primary btn-block" id="btn_enroll_students">
                                <i class="fa fa-user-plus"></i> Enroll Students
                            </button>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <table class="table table-sm table-bordered table-striped" width="100%" id="tbl_enrolled">
                                <thead>
                                    <tr>
                                        <td>Year Level</td>
                                        <td>Section</td>
                                        <td>School Year</td>
                                        <td>No. of Students</td>
                                        <td>No. of Students Enrolled</td>
                                        <td width="5%"></td>
                                    </tr>
                                </thead>
                                <tbody id="tbl_enrolled_body"></tbody>
                            </table>
                        </div>
                    </div>
                	

                </div>
            </div>
        </div>
    </div>
</div>

@include('modals.modal_enroll_students')


@endsection

@push('scripts')
	<script type="text/javascript" src="{{ asset('/js/pages/enroll_students.js') }}"></script>
@endpush