<div class="modal fade" id="handouts_modal" tabindex="-1" role="dialog" aria-labelledby="handouts_modalLabel" aria-hidden="true" data-backdrop="static">
	<div class="modal-dialog modal-xl" role="document">
		<div class="modal-content">
			<form id="frm_handouts" role="form" method="POST" action="{{ url('handouts/save-handouts') }}" enctype="multipart/form-data">
				<div class="modal-header">
					<h5 class="modal-title" id="handouts_modalLabel">
						Handouts
					</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<div class="loading"></div>

					{{ csrf_field() }}

					<div class="row">
						<div class="col-md-5">
							<div class="form-group row">
		                        <div class="col-sm-12">
		                            <div class="input-group input-group-sm">
		                                <div class="input-group-prepend">
		                                    <span class="input-group-text">Year Level:</span>
		                                </div>
		                                <input type="text" class="form-control form-control-sm validate" id="program" name="program" readonly>
		                                <input type="hidden" class="clear" id="program_id" name="program_id">
		                            </div>
		                        </div>
		                    </div>

		                    <div class="form-group row">
		                        <div class="col-sm-12">
		                            <div class="input-group input-group-sm">
		                                <div class="input-group-prepend">
		                                    <span class="input-group-text">Subject:</span>
		                                </div>
		                                <input type="text" class="form-control form-control-sm validate" id="subject" name="subject" readonly>
		                                <input type="hidden" class="clear" id="subject_id" name="subject_id">
		                            </div>
		                        </div>
		                    </div>

		                    <div class="form-group row">
		                        <div class="col-sm-12">
		                            <div class="input-group input-group-sm">
		                                <div class="input-group-prepend">
		                                    <span class="input-group-text">Section:</span>
		                                </div>
		                                <input type="text" class="form-control form-control-sm validate" id="section" name="section" readonly>
		                                <input type="hidden" class="clear" id="section_id" name="section_id">
		                            </div>
		                        </div>
		                    </div>

		                    <div class="form-group row">
		                        <div class="col-sm-12">
		                            <div class="input-group input-group-sm">
		                                <div class="input-group-prepend">
		                                    <span class="input-group-text">Title:</span>
		                                </div>
		                                <input type="text" class="form-control form-control-sm validate clear" id="title" name="title" required>
		                            </div>
		                        </div>
		                    </div>

		                    <div class="form-group row">
		                        <div class="col-sm-12">
		                            <div class="input-group input-group-sm">
		                                <div class="input-group-prepend">
		                                    <span class="input-group-text">Description:</span>
		                                </div>
		                                <textarea class="form-control form-control-sm validate clear" id="description" name="description"></textarea>
		                            </div>
		                        </div>
		                    </div>

		                    <div class="form-group row">
		                        <div class="col-sm-12">
		                            <div class="input-group input-group-sm">
		                                <div class="input-group-prepend">
		                                    <span class="input-group-text">File Upload:</span>
		                                </div>
		                                <div class="custom-file">
		                                    <input type="file" class="custom-file-input custom-file-input-sm" name="handouts_file" id="handouts_file">
		                                    <label class="custom-file-label" for="handouts_file" id="file_label">Choose file</label>
		                                </div>
		                            </div>
		                        </div>
		                    </div>
						</div>

						<div class="col-md-7">
							<div class="row">
			                	<div class="col-md-12">
			                		<table class="table table-sm table-bordered table-striped" width="100%" id="tbl_handouts">
										<thead>
											<tr>
												<td width="5%">
													<input type="checkbox" class="check_all_handouts">
												</td>
												<td></td>
												<td>Title</td>
												<td>Description</td>
												<td>Date Uploaded</td>
											</tr>
										</thead>
										<tbody id="tbl_handouts_body"></tbody>
									</table>
			                	</div>
			                </div>

			                <div class="row">
			                	<div class="offset-md-8 col-md-4">
			                		<button type="button" class="btn btn-sm btn-danger btn-block" id="btn_delete">
			                			<i class="fa fa-trash"></i> Delete
			                		</button>
			                	</div>
			                </div>
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