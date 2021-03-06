<div class="modal fade" id="item_analysis_modal" tabindex="-1" role="dialog" aria-labelledby="item_analysis_modalLabel" aria-hidden="true" data-backdrop="static">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="item_analysis_modalLabel">
					Quiz Item Result Analysis
				</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="loading"></div>

                <input type="hidden" id="quiz_id" name="quiz_id"/>
                <input type="hidden" id="quiz_title" name="quiz_title"/>
                <input type="hidden" id="quiz_type" name="quiz_type"/>
                <input type="hidden" id="subject" name="subject"/>
                <input type="hidden" id="section" name="section"/>
                <input type="hidden" id="section_id" name="section_id"/>
                <input type="hidden" id="subject_id" name="subject_id"/>

                <div class="row">
                    <div class="col-md-6">
                        <p><b>Teacher:</b> <span id="td_teacher"></span></p>
                        <p><b>Subject:</b> <span id="td_subject"></span></p>
                        <p><b>Section:</b> <span id="td_section"></span></p>

                        <br>

                        <p><b>Quiz Title:</b> <span id="td_quiz_title"></span></p>
                        <p><b>Quiz Type:</b> <span id="td_quiz_type"></span></p>
                        <p><b>Start Date:</b> <span id="td_start_date"></span></p>
                        <p><b>Due Date:</b> <span id="td_due_date"></span></p>
                        <p><b>Max Attempt:</b> <span id="td_max_attempt"></span></p>
                        <p><b>Timer:</b> <span id="td_timer"></span></p>
                        <p><b>Allow Late Submission:</b> <span id="td_late_submission"></span></p>
                        <p><b>Instruction:</b> <span id="td_instruction"></span></p>
                    </div>
                    <div class="col-md-6">
                        <p><b>Total # of Students:</b> <span id="td_total_num_of_studs"></span></p>
                        <br>

                        <p><b># of Students who took the quiz:</b> <span id="td_students_took"></span></p>
                        <p><b># of Students who did not take the quiz:</b> <span id="td_students_not_take"></span></p>

                        <p><b># of Questions:</b> <span id="td_no_of_question"></span></p>
                        <p><b># of Choices:</b> <span id="td_no_of_choices"></span></p>
                        <p><b>Max Points:</b> <span id="td_max_points"></span></p>

                        <p><b>Highest Score:</b> <span id="td_highest"></span></p>
                        <p><b>Lowest Score:</b> <span id="td_lowest"></span></p>
                        <p><b>Average Score:</b> <span id="td_avg"></span></p>
                    </div>
                        
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-bordered table-sm table-striped" width="100%">
                            <thead class="thead-dark">
                                <tr>
                                    <th>Fullname</th>
                                    <th>User Attempt</th>
                                    <th>Score</th>
                                    <th>Final Grade</th>
                                    <th>Remarks</th>
                                </tr>
                            </thead>
                            <tbody id="tbl_details_body"></tbody>
                        </table>
                    </div>
                </div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">
					<i class="fa fa-times"></i> Close
				</button>
                <button type="button" class="btn btn-info btn-sm" id="btn_print">
                    <i class="fa fa-print"></i> Print
                </button>
			</div>
				
		</div>
	</div>
</div>