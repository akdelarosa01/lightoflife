<div class="modal fade" id="enroll_students_modal" tabindex="-1" role="dialog" aria-labelledby="enroll_students_modalLabel" aria-hidden="true" data-backdrop="static">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<form action="{{ url('/transaction/save-enroll-students') }}" class="form-horizontal" method="post" id="frm_enroll">
				<div class="modal-header">
					<h5 class="modal-title" id="enroll_students_modalLabel">
						Enroll Students
					</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<div class="loading"></div>
					<div class="row">

						<div class="col-md-5">
							<div class="form-group row">
		                        <div class="col-sm-12">
		                            <div class="input-group input-group-sm">
		                                <div class="input-group-prepend">
		                                    <span class="input-group-text">Year Level:</span>
		                                </div>
		                                <select class="form-control form-control-sm validate clear" id="program" name="program" required></select>
		                                <div id="program_feedback"></div>

		                                <input type="hidden" name="es_id" id="es_id" class="clear">
		                            </div>
		                        </div>
		                    </div>

		                    <div class="form-group row">
		                        <div class="col-sm-12">
		                            <div class="input-group input-group-sm">
		                                <div class="input-group-prepend">
		                                    <span class="input-group-text">Section:</span>
		                                </div>
		                                <select class="form-control form-control-sm validate clear" id="section" name="section" required></select>
		                                <div id="section_feedback"></div>
		                            </div>
		                        </div>
		                    </div>

		                    <div class="form-group row">
		                        <div class="col-sm-12">
		                            <div class="input-group input-group-sm">
		                                <div class="input-group-prepend">
		                                    <span class="input-group-text">School Year:</span>
		                                </div>
		                                <select class="form-control form-control-sm validate clear" id="school_year" name="school_year" required></select>
		                                <div id="school_year_feedback"></div>
		                            </div>
		                        </div>
		                    </div>

		                    <div class="form-group row">
		                        <div class="col-sm-12">
		                            <div class="input-group input-group-sm">
		                                <div class="input-group-prepend">
		                                    <span class="input-group-text">No. of Students:</span>
		                                </div>
		                                <select class="form-control form-control-sm validate clear" id="no_of_students" name="no_of_students" required>
		                                	<option value=""></option>
		                                	<?php
		                                		for($xi=1;$xi<=45;$xi++)
												{
													echo "<option>".$xi."</option>";
												}
											?>
		                                </select>
		                                <div id="no_of_students_feedback"></div>
		                            </div>
		                        </div>
		                    </div>
						</div>

						<div class="col-md-7">
                    		<table class="table table-sm table-bordered table-striped" width="100%" id="tbl_subjects">
								<thead>
									<tr>
										<td>Code</td>
										<td>Description</td>
									</tr>
								</thead>
								<tbody id="tbl_subjects_body"></tbody>
							</table>
                    	</div>

					</div>

                    <div class="row">

                    	<div class="col-md-12">
                    		<table class="table table-sm table-bordered table-striped" width="100%" id="tbl_students">
								<thead>
									<tr>
										<td width="5%">
											<input type="checkbox" class="check_all_students">
										</td>
										<td>ID Number</td>
										<td>Student's Name</td>
									</tr>
								</thead>
								<tbody id="tbl_students_body"></tbody>
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