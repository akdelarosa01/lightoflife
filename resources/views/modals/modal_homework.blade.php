<div class="modal fade" id="homework_modal" tabindex="-1" role="dialog" aria-labelledby="homework_modalLabel" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form id="frm_homework" role="form" method="POST" action="{{ url('activities/save-homeworks') }}" enctype="multipart/form-data">
                <div class="modal-header">
                    <h5 class="modal-title" id="homework_modalLabel">
                        Create Homework
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="loading"></div>

                    {{ csrf_field() }}

                    <input type="hidden" class="clear" name="hw_id" id="hw_id">

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
                                            <span class="input-group-text">Homework Title:</span>
                                        </div>
                                        <input type="text" class="form-control form-control-sm clear" id="title" name="title" required>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-sm-12">
                                    <div class="input-group input-group-sm">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">Question:</span>
                                        </div>
                                        <textarea class="form-control form-control-sm clear" name="question" id="question" cols="30" rows="5" required></textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-sm-12">
                                    <div class="input-group input-group-sm">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">Points:</span>
                                        </div>
                                        <input type="number" step=".01" class="form-control form-control-sm clear" id="points" name="points" required>
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

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="btn-group" id="attachements"></div>
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