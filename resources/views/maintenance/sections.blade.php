@extends('layouts.app')

@section('content')
<div class="container-fluid mt-2">
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow-lg">
                <div class="card-header bg-red text-white">Create Sections</div>

                <div class="card-body">
                	<div class="loading"></div>

                	<div class="row">
                		<div class="col-md-5">
                			<form action="{{ url('/maintenance/save-sections') }}" method="post" class="forn-horizontal" id="frm_section">

				            	<input type="hidden" id="id" name="id">

				            	<div class="form-group row">
			                        <div class="col-sm-12">
			                            <div class="input-group input-group-sm">

			                                <div class="input-group-prepend">
			                                    <span class="input-group-text">Section Name:</span>
			                                </div>
			                                <input type="text" class="form-control form-control-sm validate clear" name="section" id="section" required>
			                                <div id="section_feedback"></div>

			                            </div>
			                        </div>
			                    </div>

				            	<div class="form-group row">
			                        <div class="col-sm-12">
			                            <div class="input-group input-group-sm">

			                                <div class="input-group-prepend">
			                                    <span class="input-group-text">Year Level:</span>
			                                </div>
			                                <select class="form-control form-control-sm validate clear" name="program" id="program" required>
			                                </select>
			                                <div id="program_feedback"></div>

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
                		</div>

		            	<div class="col-md-7">
		            		<table class="table table-sm table-bordered table-striped dt-responsive nowrap" width="100%" id="tbl_section">
								<thead>
									<tr>
										<td width="5%">
											<input type="checkbox" class="check_all">
										</td>
										<td width="5%"></td>
										<td>Section</td>
										<td>Year Level</td>
										<td>Date Created</td>
									</tr>
								</thead>
								<tbody id="tbl_section_body"></tbody>
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
<script type="text/javascript" src="{{ asset('/js/pages/sections.js') }}"></script>
@endpush