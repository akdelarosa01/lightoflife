<div class="modal fade" id="subject_handle_modal" tabindex="-1" role="dialog" aria-labelledby="assign_subject_modalLabel" aria-hidden="true" data-backdrop="static">
	<div class="modal-dialog modal-xl" role="document">
		<div class="modal-content">
			<form action="{{ url('/transaction/save-subject-handle') }}" class="form-horizontal" method="post" id="frm_subhandle">
				<div class="modal-header">
					<h5 class="modal-title" id="assign_subject_modalLabel">
						Assign subjects
					</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<div class="loading"></div>

                    <div class="row">
                    	<div class="col-md-6">
                    		<div class="form-group row">
		                        <div class="col-md-12">
		                            <div class="input-group input-group-sm">
		                                <div class="input-group-prepend">
		                                    <span class="input-group-text">Teacher Name:</span>
		                                </div>
		                                <input type="text" class="form-control form-control-sm validate clear" id="fullname" name="fullname" readonly>
		                                <div id="fullname_feedback"></div>
		                            </div>
		                        </div>

		                        <input type="hidden" id="user_id" name="user_id">
		                        <input type="hidden" id="id_number" name="id_number">
		                        <input type="hidden" id="dept_id" name="dept_id">
		                        <input type="hidden" id="subj_id" name="subj_id">
		                        <input type="hidden" id="program_id" name="program_id">
		                    </div>

		                    <div class="form-group row">
		                        <div class="col-md-12">
		                            <div class="input-group input-group-sm">
		                                <div class="input-group-prepend">
		                                    <span class="input-group-text">Subject:</span>
		                                </div>
		                                <select type="text" class="form-control form-control-sm validate clear" id="subject" name="subject" required></select>
		                                <div id="subject_feedback"></div>
		                            </div>
		                        </div>
		                    </div>

		                    <div class="form-group row">
		                        <div class="col-md-12">
		                            <div class="input-group input-group-sm">
		                                <div class="input-group-prepend">
		                                    <span class="input-group-text">Year Level:</span>
		                                </div>
		                                <input type="text" class="form-control form-control-sm validate clear" id="program" name="program" readonly>
		                                <div id="program_feedback"></div>
		                            </div>
		                        </div>
		                    </div>

		                    <table class="table table-sm table-bordered table-striped" width="100%" id="tbl_sections">
								<thead>
									<tr>
										<td width="5%"></td>
										<td>Year Level</td>
										<td>Section Name</td>
									</tr>
								</thead>
								<tbody id="tbl_sections_body"></tbody>
							</table>
                    	</div>

                    	<div class="col-md-6">
                    		<table class="table table-sm table-bordered table-striped" width="100%" id="tbl_handles">
								<thead>
									<tr>
										<td>Year Level</td>
										<td>Section</td>
										<td>Subject</td>
									</tr>
								</thead>
								<tbody id="tbl_handles_body"></tbody>
							</table>
                    	</div>
                    </div>

	                
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">
						<i class="fa fa-times"></i> Close
					</button>
					<button type="submit" class="btn btn-primary btn-sm">
						<i class="fa fa-floppy-o"></i> Save
					</button>
				</div>
			</form>
		</div>
	</div>
</div>