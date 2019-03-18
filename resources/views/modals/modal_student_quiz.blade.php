<div class="modal fade" id="quiz_modal" tabindex="-1" role="dialog" aria-labelledby="quiz_modalLabel" aria-hidden="true" data-backdrop="static">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="quiz_modalLabel">
					Quiz Details
				</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="loading"></div>

				<input type="hidden" id="quiz_id" name="quiz_id">

				<table class="table table-striped table-bordered table-sm" width="100%" id="tbl_details">
					<tbody>
						<tr>
							<td width="30%">Quiz Title</td>
							<td id="quiz_title" width="70%"></td>
						</tr>
						<tr>
							<td>Quiz Type</td>
							<td id="quiz_type"></td>
						</tr>
						<tr>
							<td>Max Score</td>
							<td id="max_score"></td>
						</tr>
						<tr>
							<td>Start</td>
							<td id="start"></td>
						</tr>
						<tr>
							<td>Due</td>
							<td id="due"></td>
						</tr>
						<tr>
							<td>Time Limit</td>
							<td id="time_timit"></td>
						</tr>
						<tr>
							<td>Allowed Attempts</td>
							<td id="allowed_attempts"></td>
						</tr>
						<tr>
							<td>Committed Attempts</td>
							<td id="committed_attempts"></td>
						</tr>
						<tr>
							<td>Late Submission Allowed</td>
							<td id="late_submission"></td>
						</tr>
						<tr>
							<td>Instruction</td>
							<td id="instruction"></td>
						</tr>
					</tbody>
				</table>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">
					<i class="fa fa-times"></i> Close
				</button>
				<button type="button" class="btn btn-primary btn-sm" id="take_quiz">
					<i class="fa fa-pencil"></i> Take Quiz
				</button>
			</div>
				
		</div>
	</div>
</div>

<div class="modal fade" id="results_modal" tabindex="-1" role="dialog" aria-labelledby="results_modalLabel" aria-hidden="true" data-backdrop="static">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="results_modalLabel">
					Quiz Results
				</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="loading"></div>

				<div class="row mb-2">
                    <div class="col-md-12">
                        <table class="table table-bordered table-sm table-striped" width="100%">
                            <tr>
                                <td>Subject</td>
                                <td id="td_subject"></td>
                                <td>Date Taken</td>
                                <td id="td_date_taken"></td>
                            </tr>

                            <tr>
                                <td>Quiz Type</td>
                                <td id="td_quiz_type"></td>
                                <td>Date Submitted</td>
                                <td id="td_date_submitted"></td>
                            </tr>

                            <tr>
                                <td>Max Score</td>
                                <td id="td_max_score"></td>
                                <td>Score</td>
                                <td id="td_student_score"></td>
                            </tr>

                            <tr>
                                <td>Start</td>
                                <td id="td_start_date"></td>
                                <td>Final Grade</td>
                                <td id="td_final_grade"></td>
                            </tr>

                            <tr>
                                <td>Due</td>
                                <td id="td_due_date"></td>
                                <td>Remarks</td>
                                <td id="td_remarks"></td>
                            </tr>

                            <tr>
                                <td>Timer</td>
                                <td id="td_timer"></td>
                                <td colspan="2"></td>
                            </tr>

                            <tr>
                                <td>Max Attempts</td>
                                <td id="td_max_attempt"></td>
                                <td colspan="2"></td>
                            </tr>

                            <tr>
                                <td>User Attempts</td>
                                <td id="td_user_attempt"></td>
                                <td colspan="2"></td>
                            </tr>

                            <tr>
                                <td>Allow Late Submission</td>
                                <td id="td_late_submission"></td>
                                <td colspan="2"></td>
                            </tr>

                            <tr>
                                <td>Status</td>
                                <td id="td_status"></td>
                                <td colspan="2"></td>
                            </tr>

                            <tr>
                                <td colspan="2">Instruction:</td>
                                <td colspan="2" id="td_instruction"></td>
                            </tr>
                        </table>
                    </div>
                </div>

                <div class="row mb-2">
                    <div class="col-md-12">
                        <table class="table table-bordered table-sm table-striped" width="100%">
                            <thead>
                            	<tr>
	                                <td colspan="4">Questions:</td>
	                            </tr>
                            </thead>
                            <tbody id="question_body"></tbody>
                        </table>
                    </div>
                </div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">
					<i class="fa fa-times"></i> Close
				</button>
				<button type="button" class="btn btn-primary btn-sm" id="take_quiz">
					<i class="fa fa-pencil"></i> Take Quiz
				</button>
			</div>
				
		</div>
	</div>
</div>