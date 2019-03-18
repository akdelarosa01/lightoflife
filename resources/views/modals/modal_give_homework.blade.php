<div class="modal fade" id="give_homework_modal" tabindex="-1" role="dialog" aria-labelledby="give_homework_modalLabel" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="frm_homework" role="form" method="POST" action="{{ url('activities/save-give-homeworks') }}" enctype="multipart/form-data">
                <div class="modal-header">
                    <h5 class="modal-title" id="give_homework_modalLabel">
                        Assign Homework
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="loading"></div>

                    {{ csrf_field() }}

                    <input type="hidden" name="post_status" id="post_status">
                    <input type="hidden" name="hw_given_id" id="hw_given_id">

                    <div class="row mb-2">
                        <div class="col-md-12">

                            <div class="form-group row mb-2">
                                <div class="col-sm-12">
                                    <div class="input-group input-group-sm">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">Subject:</span>
                                        </div>
                                        <select class="form-control form-control-sm clear" id="subject" name="subject" required></select>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row mb-2">
                                <div class="col-sm-12">
                                    <div class="input-group input-group-sm">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">Section:</span>
                                        </div>
                                        <select class="form-control form-control-sm clear" id="section" name="section" required></select>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row mb-2">
                                <div class="col-sm-12">
                                    <div class="input-group input-group-sm">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">Homework:</span>
                                        </div>
                                        <select class="form-control form-control-sm clear" id="hw_id" name="hw_id" required></select>
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