@extends('layouts.app')

@section('content')
<div class="container mt-2">
    <div class="row">
        <div class="offset-md-2 col-md-8">
            <div class="card shadow-lg">
                <div class="card-header bg-red text-white">Handouts</div>

                <div class="card-body">

                    <div class="loading"></div>

                    <div class="row">
                        <div class="col-md-12">
                            <table class="table table-sm table-bordered table-striped" width="100%" id="tbl_class">
                                <thead>
                                    <tr>
                                        <td>Year Level</td>
                                        <td>Section</td>
                                        <td>Subject</td>
                                        <td width="5%"></td>
                                    </tr>
                                </thead>
                                <tbody id="tbl_class_body"></tbody>
                            </table>
                        </div>
                    </div>
                	

                </div>
            </div>
        </div>
    </div>
</div>
@include('modals.modal_handouts')

@endsection

@push('scripts')
	<script type="text/javascript" src="{{ asset('/js/pages/handouts.js') }}"></script>
@endpush