@extends('layouts.app')

@section('content')
<div class="container mt-2">
    <div class="row">
        <div class="offset-md-2 col-md-8">
            <div class="card shadow-lg">
                <div class="card-header bg-red text-white">Inbox</div>

                <div class="card-body">
                    <div class="loading"></div>

                    <div class="row mb-2">
                        <div class="offset-md-8 col-md-4">
                            <a href="{{ url('messages/inbox') }}" class="btn btn-secondary btn-sm btn-block" id="btn_back">Back to inbox</a>
                        </div>
                    </div>

                    <div class="row mb-2">
                        <div class="col-md-12" id="convo_msg" style="overflow-y:scroll;height: 200px">
                            
                        </div>
                    </div>

                    <div class="row mb-2">
                        <div class="col-sm-12" id="attachments">
                        </div>
                    </div>


                    <form id="frm_message" role="form" method="POST" action="{{ url('messages/send-reply') }}" enctype="multipart/form-data">

                        {{ csrf_field() }}
                        <div class="form-group row">
                            <div class="col-md-12">
                                <div class="input-group input-group-sm">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Reply:</span>
                                    </div>
                                    <input type="text" class="form-control form-control-sm clear" name="reply" id="reply" required>
                                    <div class="input-group-sppend">
                                        <button type="submit" class="btn btn-success">
                                            <i class="fa fa-send"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <input type="hidden" id="subject_id" name="subject_id" value="{{$id}}">
                        <input type="hidden" id="user_to" name="user_to">

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
                                <div class="btn-group" id="reply_attachments"></div>
                            </div>
                        </div>

                    </form>

                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
    <script type="text/javascript" src="{{ asset('/js/pages/conversation.js') }}"></script>
@endpush