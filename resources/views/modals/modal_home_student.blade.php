<div class="modal fade" id="handouts_modal" tabindex="-1" role="dialog" aria-labelledby="handouts_modalLabel" aria-hidden="true" data-backdrop="static">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="handouts_modalLabel">
					Subject Handouts
				</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="loading"></div>

                <div class="row">
                	<div class="col-md-12">
                		<table class="table table-sm table-bordered table-striped" width="100%" id="tbl_handouts">
							<thead>
								<tr>
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
                
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">
					<i class="fa fa-times"></i> Close
				</button>
			</div>
		</div>
	</div>
</div>