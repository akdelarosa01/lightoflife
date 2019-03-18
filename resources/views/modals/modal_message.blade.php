<div class="modal fade" id="message_modal" tabindex="-1" role="dialog" aria-labelledby="message_modalLabel" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form id="frm_message" role="form" method="POST" action="{{ url('messages/send-message') }}" enctype="multipart/form-data">
                <div class="modal-header">
                    <h5 class="modal-title" id="message_modalLabel">
                        Create New Message
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="loading"></div>

                    {{ csrf_field() }}

                    <input type="hidden" class="clear" name="id" id="id">

                    <div class="row mb-2">
                        <div class="col-md-12">

                            <div class="form-group row">
                                <div class="col-sm-12">
                                    <div class="input-group input-group-sm">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">Subject:</span>
                                        </div>
                                        <input type="text" class="form-control form-control-sm clear" id="subject_msg" name="subject_msg" required>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-sm-12">
                                    <div class="input-group input-group-sm">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">Message:</span>
                                        </div>
                                        <textarea class="form-control form-control-sm clear" name="message" id="message" cols="30" rows="5" required></textarea>
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
                                            <input type="file" class="custom-file-input custom-file-input-sm clear" name="msg_file[]" id="msg_file" multiple>
                                            <label class="custom-file-label" for="msg_file" id="file_label">Choose file</label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="btn-group" id="attachments"></div>
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-sm-12">
                                    <div class="input-group input-group-sm">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">Recipients:</span>
                                        </div>
                                        <select class="form-control form-control-sm clear" id="recipients" name="recipients[]" required multiple style="height: 100px"></select>
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
                        <i class="fa fa-send"></i> Send
                    </button>
                </div>
            </form>
                
        </div>
    </div>
</div>