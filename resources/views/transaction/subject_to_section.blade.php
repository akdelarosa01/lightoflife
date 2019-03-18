@extends('layouts.app')

@section('content')
<div class="container mt-2">
    <div class="row">
        <div class="offset-md-2 col-md-8">
            <div class="card shadow-lg">
                <div class="card-header bg-red text-white">Assigned Subjects to Sections</div>

                <div class="card-body">
                	<div class="row mb-2">
                		<div class="col-md-4">
                			<button type="button" class="btn btn-primary btn-block btn-sm" id="btn_assign">
                				<i class="fa fa-refresh"></i> Assign subjects
                			</button>
                		</div>
                        <div class="offset-md-4 col-md-4">
                            <button type="button" class="btn btn-danger btn-block btn-sm" id="btn_delete">
                                <i class="fa fa-refresh"></i> Delete
                            </button>
                        </div>
                	</div>

                	<div class="row">
                		<div class="col-md-12">
                			<table class="table table-sm table-bordered table-striped" width="100%" id="tbl_assigned">
								<thead>
									<tr>
										<td width="5%">
                                            <input type="checkbox" class="check_all_sec">                              
                                        </td>
										<td>Year Level</td>
										<td>Section Name</td>
										<td>Date Assigned</td>
									</tr>
								</thead>
								<tbody id="tbl_assigned_body"></tbody>
							</table>
                		</div>
                	</div>
                </div>
            </div>
        </div>
    </div>
</div>

@include('modals.modal_subject_to_section')

@endsection

@push('scripts')
	<script type="text/javascript" src="{{ asset('/js/pages/subject_to_section.js') }}"></script>
@endpush