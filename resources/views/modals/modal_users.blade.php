<div class="modal fade" id="not_saved_modal" tabindex="-1" role="dialog" aria-labelledby="not_saved_modalLabel" aria-hidden="true" data-backdrop="static">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="not_saved_modalLabel">
					Names that already exists
				</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="loading"></div>

                <div class="row">
                	<div class="col-md-12">
                		<table class="table table-sm table-bordered table-striped" width="100%" id="tbl_not_saved">
							<thead>
								<tr>
									<td>First Name</td>
									<td>Middle Name</td>
									<td>Last Name</td>
								</tr>
							</thead>
							<tbody id="tbl_not_saved_body"></tbody>
						</table>
                	</div>
                </div>
                
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">
					<i class="fa fa-times"></i> Close
				</button>
			</div>
		</div>
	</div>
</div>