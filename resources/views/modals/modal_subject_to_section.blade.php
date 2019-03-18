<div class="modal fade" id="assign_subject_modal" tabindex="-1" role="dialog" aria-labelledby="assign_subject_modalLabel" aria-hidden="true" data-backdrop="static">
	<div class="modal-dialog modal-xl" role="document">
		<div class="modal-content">
			<form action="{{ url('/transaction/save-assign-subjects') }}" class="form-horizontal" method="post" id="frm_subtosec">
				<div class="modal-header">
					<h5 class="modal-title" id="assign_subject_modalLabel">
						Assign Subject to Section
					</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<div class="loading"></div>

                    <div class="form-group row">
                        <div class="col-sm-6">
                            <div class="input-group input-group-sm">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Year Level:</span>
                                </div>
                                <select class="form-control form-control-sm validate clear" name="program" id="program" required></select>
                                <div id="program_feedback"></div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                    	<div class="col-md-6">
                    		<table class="table table-sm table-bordered table-striped" width="100%" id="tbl_section">
								<thead>
									<tr>
										<td width="5%">
											<input type="checkbox" class="check_all_sections">
										</td>
										<td>Year Level</td>
										<td>Section Name</td>
									</tr>
								</thead>
								<tbody id="tbl_section_body"></tbody>
							</table>
                    	</div>
                    	<div class="col-md-6">
                    		<table class="table table-sm table-bordered table-striped" width="100%" id="tbl_subject">
								<thead>
									<tr>
										<td width="5%">
											<input type="checkbox" class="check_all_subjects">
										</td>
										<td>Subject Code</td>
										<td>Description</td>
									</tr>
								</thead>
								<tbody id="tbl_subject_body"></tbody>
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