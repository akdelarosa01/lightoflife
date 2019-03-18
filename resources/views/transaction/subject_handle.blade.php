@extends('layouts.app')

@section('content')
<div class="container mt-2">
    <div class="row">
        <div class="offset-md-2 col-md-8">
            <div class="card shadow-lg">
                <div class="card-header bg-red text-white">Subject handle</div>

                <div class="card-body">

                	<div class="row">
                		<div class="col-md-12">
                			<table class="table table-sm table-bordered table-striped" width="100%" id="tbl_teachers">
								<thead>
									<tr>
										<td width="5%"></td>
										<td>ID Number</td>
										<td>Name</td>
										<td>Department</td>
									</tr>
								</thead>
								<tbody id="tbl_teachers_body"></tbody>
							</table>
                		</div>
                	</div>

                </div>
            </div>
        </div>
    </div>
</div>

@include('modals.modal_subject_handle')

@endsection

@push('scripts')
	<script type="text/javascript" src="{{ asset('/js/pages/subject_handle.js') }}"></script>
@endpush