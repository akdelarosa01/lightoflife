<div class="modal fade" id="quiz_modal" tabindex="-1" role="dialog" aria-labelledby="quiz_modalLabel" aria-hidden="true" data-backdrop="static">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<form id="frm_quiz" role="form" method="POST" action="{{ url('activities/save-quizzes') }}">
				<div class="modal-header">
					<h5 class="modal-title" id="quiz_modalLabel">
						Create Quiz
					</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<div class="loading"></div>

					{{ csrf_field() }}

					<input type="hidden" class="clear" name="quiz_id" id="quiz_id">

					<div class="row mb-2">
						<div class="col-md-12">

							<div class="form-group row">
		                        <div class="col-sm-12">
		                            <div class="input-group input-group-sm">
		                                <div class="input-group-prepend">
		                                    <span class="input-group-text">Subject:</span>
		                                </div>
		                                <select class="form-control form-control-sm clear" id="subject" name="subject" required></select>
		                            </div>
		                        </div>
		                    </div>

							<div class="form-group row">
		                        <div class="col-sm-12">
		                            <div class="input-group input-group-sm">
		                                <div class="input-group-prepend">
		                                    <span class="input-group-text">Quiz Title:</span>
		                                </div>
		                                <input type="text" class="form-control form-control-sm clear" id="quiz_title" name="quiz_title" required>
		                            </div>
		                        </div>
		                    </div>

							<div class="form-group row">
		                        <div class="col-sm-12">
		                            <div class="input-group input-group-sm">
		                                <div class="input-group-prepend">
		                                    <span class="input-group-text">Quiz Type:</span>
		                                </div>
		                                <select class="form-control form-control-sm clear" id="quiz_type" name="quiz_type" required>
		                                	<option value=""></option>
		                                	<option value="MULTIPLE CHOICE">MULTIPLE CHOICE</option>
		                                	<option value="IDENTIFICATION">IDENTIFICATION</option>
		                                	<option value="FILL IN THE BLANKS">FILL IN THE BLANKS</option>
		                                	<option value="TRUE OR FALSE">TRUE OR FALSE</option>
		                                </select>
		                            </div>
		                        </div>
		                    </div>

		                    <div class="form-group row">
		                        <div class="col-sm-12">
		                            <div class="input-group input-group-sm">
		                                <div class="input-group-prepend">
		                                    <span class="input-group-text">Number of Items:</span>
		                                </div>
		                                <input type="number" class="form-control form-control-sm clear" id="no_of_items" name="no_of_items" min="1" max="50" value="0" required>
		                            </div>
		                        </div>
		                    </div>

		                    <div class="form-group row MULTIPLE_CHOICE">
		                        <div class="col-sm-12">
		                            <div class="input-group input-group-sm">
		                                <div class="input-group-prepend">
		                                    <span class="input-group-text">Number of Choices:</span>
		                                </div>
		                                <input type="number" class="form-control form-control-sm clear" id="no_of_choices" name="no_of_choices" min="0" max="26" value="0">
		                            </div>
		                        </div>
		                    </div>

		                    <div class="row">
		                    	<div class="offset-md-8 col-md-4">
		                    		<button type="button" class="btn btn-sm btn-success btn-block" id="btn_generate">
		                    			<i class="fa fa-refresh"></i> Generate
		                    		</button>
		                    	</div>
		                    </div>
						</div>
					</div>

					<div class="row">
						<div class="col-md-12" id="quiz_items"></div>
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