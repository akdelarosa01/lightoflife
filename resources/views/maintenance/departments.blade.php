@extends('layouts.app')

@section('content')

<div class="container mt-2">
    <div class="row">
        <div class="offset-md-2 col-md-8">
            <div class="card shadow-lg">
                <div class="card-header bg-red text-white">Create Department</div>

                <div class="card-body">
                	<div class="loading"></div>
                	<form action="{{ url('/maintenance/save-departments') }}" method="post" class="forn-horizontal" id="frm_department">

		            	<input type="hidden" id="id" name="id">

		            	<div class="form-group row">
	                        <div class="col-sm-12">
	                            <div class="input-group input-group-sm">

	                                <div class="input-group-prepend">
	                                    <span class="input-group-text">Department:</span>
	                                </div>
	                                <input type="text" class="form-control form-control-sm validate clear" name="department" id="department" required>
	                                <div id="department_feedback"></div>

	                            </div>
	                        </div>
	                    </div>

	                    <div class="form-group row">
	                    	<div class="col-sm-4">
	                            <button type="button" class="btn btn-sm bg-red btn-block" id="btn_delete">
	                            	<i class="fa fa-trash"></i> Delete
	                            </button>
	                        </div>
	                        <div class="offset-md-4 col-sm-4">
	                            <button type="submit" class="btn btn-sm btn-primary btn-block">
	                            	<i class="fa fa-floppy-o"></i> Save
	                            </button>
	                        </div>
	                    </div>
		            </form>

		            <div class="row">
		            	<div class="col-md-12">
		            		<table class="table table-sm table-bordered table-striped dt-responsive nowrap" width="100%" id="tbl_dept">
								<thead>
									<tr>
										<td width="5%">
											<input type="checkbox" class="check_all">
										</td>
										<td width="5%"></td>
										<td>Department</td>
										<td>Created by</td>
										<td>Date Created</td>
									</tr>
								</thead>
								<tbody id="tbl_dept_body"></tbody>
							</table>
		            	</div>
			            	
		            </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script type="text/javascript" src="{{ asset('/js/pages/departments.js') }}"></script>
@endpush