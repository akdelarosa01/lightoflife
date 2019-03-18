<div class="modal fade" id="homework_modal" tabindex="-1" role="dialog" aria-labelledby="homework_modalLabel" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form id="frm_homework" role="form" method="POST" action="{{ url('student-activities/save-homeworks') }}" enctype="multipart/form-data">
                <div class="modal-header">
                    <h5 class="modal-title" id="homework_modalLabel">
                        Answer Homework
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="loading"></div>

                    {{ csrf_field() }}

                    <input type="hidden" class="clear" name="hw_id" id="hw_id">
                    <input type="hidden" class="clear" name="id" id="id">
                    <input type="hidden" class="clear" name="subject_id" id="subject_id">

                    <div class="row mb-2">
                        <div class="col-md-12">

                            <div class="form-group row">
                                <div class="col-sm-12">
                                    <div class="input-group input-group-sm">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">Subject:</span>
                                        </div>
                                        <input type="text" class="form-control form-control-sm clear" id="subject" name="subject" readonly>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-sm-12">
                                    <div class="input-group input-group-sm">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">Homework Title:</span>
                                        </div>
                                        <input type="text" class="form-control form-control-sm clear" id="title" name="title" readonly>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-sm-12">
                                    <div class="input-group input-group-sm">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">Question:</span>
                                        </div>
                                        <textarea class="form-control form-control-sm clear" name="question" id="question" cols="30" rows="5" readonly></textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-sm-12" id="attachments">
                                </div>
                            </div>

                            <hr>

                            <div class="form-group row">
                                <div class="col-sm-12">
                                    <div class="input-group input-group-sm">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">Answer:</span>
                                        </div>
                                        <textarea class="form-control form-control-sm clear" name="answer" id="answer" cols="30" rows="5"></textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-sm-12">
                                    <div class="input-group input-group-sm">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">Files:</span>
                                        </div>
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input custom-file-input-sm clear" name="homework_file[]" id="homework_file" multiple>
                                            <label class="custom-file-label" for="homework_file" id="file_label">Choose file</label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-sm-12" id="student_attachments">
                                </div>
                            </div>

                        </div>
                    </div>

                    
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">
                        <i class="fa fa-times"></i> Close
                    </button>
                    <button type="submit" class="btn btn-primary btn-sm hide">
                        <i class="fa fa-floppy-o"></i> Save
                    </button>
                </div>
            </form>
                
        </div>
    </div>
</div>