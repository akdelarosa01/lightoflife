@extends('layouts.app')

@section('content')
<div class="container mt-2">
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow-lg">
                <div class="card-header bg-red text-white">Set School Year</div>

                <div class="card-body">
                	<div class="loading"></div>

                	<div class="row">
                		<div class="col-md-5">
                			
                			<form action="{{ url('/maintenance/save-school-year') }}" method="post" class="forn-horizontal" id="frm_schoolyear">

				            	<input type="hidden" id="id" name="id" class="clear">

				            	<div class="form-group row">
			                        <div class="col-sm-12">
			                            <div class="input-group input-group-sm">

			                                <div class="input-group-prepend">
			                                    <span class="input-group-text">From:</span>
			                                </div>
			                                <select class="form-control form-control-sm validate clear" name="from" id="from" required>
			                                	<option value=""></option>
			                                	<?php

			                                		for ($z=date('Y')-1; $z<=2050; $z++)
													{
														echo '<option value="'.$z.'">'.$z.'</option>';
													}

			                                	?>
			                                	
			                                </select>
			                                <div id="from_feedback"></div>

			                                <div class="input-group-prepend">
			                                    <span class="input-group-text">To:</span>
			                                </div>
			                                <input type="text" class="form-control form-control-sm validate clear" name="to" id="to" required readonly>
			                                <div id="to_feedback"></div>

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
			            	<table class="table table-sm table-bordered table-striped dt-responsive nowrap" width="100%" id="tbl_schoolyear">
								<thead>
									<tr>
										<td width="5%">
											<input type="checkbox" class="check_all">
										</td>
										<td width="5%"></td>
										<td>From</td>
										<td>To</td>
										<td>Date Created</td>
									</tr>
								</thead>
								<tbody id="tbl_schoolyear_body"></tbody>
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
<script type="text/javascript" src="{{ asset('/js/pages/school_year.js') }}"></script>
@endpush