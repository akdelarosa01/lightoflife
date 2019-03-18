<div class="modal fade" id="give_quiz_modal" tabindex="-1" role="dialog" aria-labelledby="give_quiz_modalLabel" aria-hidden="true" data-backdrop="static">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<form id="frm_quiz" role="form" method="POST" action="{{ url('activities/save-give-quizzes') }}">
				<div class="modal-header">
					<h5 class="modal-title" id="give_quiz_modalLabel">
						Assign Quiz
					</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<div class="loading"></div>

					{{ csrf_field() }}

					<input type="hidden" name="post_status" id="post_status">

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
		                                    <span class="input-group-text">Section:</span>
		                                </div>
		                                <select class="form-control form-control-sm clear" id="section" name="section" required></select>
		                            </div>
		                        </div>
		                    </div>

							<div class="form-group row">
		                        <div class="col-sm-12">
		                            <div class="input-group input-group-sm">
		                                <div class="input-group-prepend">
		                                    <span class="input-group-text">Quiz Title:</span>
		                                </div>
		                                <select class="form-control form-control-sm clear" id="quiz_id" name="quiz_id" required></select>
		                            </div>
		                        </div>
		                    </div>

							<div class="form-group row">
		                        <div class="col-sm-12">
		                            <div class="input-group input-group-sm">
		                                <div class="input-group-prepend">
		                                    <span class="input-group-text">Start Date:</span>
		                                </div>
		                                <input type="date" class="form-control form-control-sm clear" id="start_date" name="start_date" required>
		                            </div>
		                        </div>
		                    </div>

		                    <div class="form-group row">
		                        <div class="col-sm-12">
		                            <div class="input-group input-group-sm">
		                                <div class="input-group-prepend">
		                                    <span class="input-group-text">Start time:</span>
		                                </div>
		                                <input type="time" class="form-control form-control-sm clear" id="start_time" name="start_time" required>
		                            </div>
		                        </div>
		                    </div>

		                    <div class="form-group row">
		                        <div class="col-sm-12">
		                            <div class="input-group input-group-sm">
		                                <div class="input-group-prepend">
		                                    <span class="input-group-text">Due Date:</span>
		                                </div>
		                                <input type="date" class="form-control form-control-sm clear" id="due_date" name="due_date" required>
		                            </div>
		                        </div>
		                    </div>

		                    <div class="form-group row">
		                        <div class="col-sm-12">
		                            <div class="input-group input-group-sm">
		                                <div class="input-group-prepend">
		                                    <span class="input-group-text">Due time:</span>
		                                </div>
		                                <input type="time" class="form-control form-control-sm clear" id="due_time" name="due_time" required>
		                            </div>
		                        </div>
		                    </div>

		                    <div class="form-group row">
		                        <div class="col-sm-12">
		                            <div class="input-group input-group-sm">
		                                <div class="input-group-prepend">
		                                    <span class="input-group-text">Quiz Timer:</span>
		                                </div>
		                                <input type="number" class="form-control form-control-sm clear" id="timer" name="timer" min="1" max="59" value="1" required>
		                                <div class="input-group-append">
		                                    <span class="input-group-text">in minutes:</span>
		                                </div>
		                            </div>
		                        </div>
		                    </div>

		                    <div class="form-group row">
		                        <div class="col-sm-12">
		                            <div class="input-group input-group-sm">
		                                <div class="input-group-prepend">
		                                    <span class="input-group-text">Quiz Attempt:</span>
		                                </div>
		                                <input type="number" class="form-control form-control-sm clear" id="max_attempt" name="max_attempt" min="1" max="10" value="1" required>
		                            </div>
		                        </div>
		                    </div>

		                    <div class="form-group row">
		                        <div class="col-sm-12">
		                            <div class="input-group input-group-sm">
		                                <div class="input-group-prepend">
		                                    <span class="input-group-text">Instruction:</span>
		                                </div>
		                                <textarea class="form-control form-control-sm clear" name="instruction" id="instruction" cols="30" rows="5" required></textarea>
		                            </div>
		                        </div>
		                    </div>

		                    <div class="form-group row">
		                        <div class="col-sm-12">
		                            <div class="input-group input-group-sm">
		                                <div class="input-group-prepend">
		                                    <span class="input-group-text">Allow Late submission:</span>
		                                </div>
		                                <select class="form-control form-control-sm clear" id="late_submission" name="late_submission" required>
		                                	<option value=""></option>
		                                	<option value="0">NO</option>
		                                	<option value="1">YES</option>
		                                </select>
		                            </div>
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