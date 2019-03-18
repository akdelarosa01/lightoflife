@extends('layouts.app')

@section('content')
<div class="container mt-2">
    <div class="row">
        <div class="offset-md-2 col-md-8">
            <div class="card shadow-lg">
                <div class="card-header bg-red text-white">Inbox</div>

                <div class="card-body">

                    <div class="row">
                        <div class="col-md-4">
                            <button class="btn btn-danger btn-sm btn-block" id="btn_delete">
                                <i class="fa fa-trash"></i> Delete
                            </button>
                        </div>
                        <div class="offset-md-4 col-md-4">
                            <button class="btn btn-primary btn-sm btn-block" id="btn_new">
                                <i class="fa fa-comment"></i> New Message
                            </button>
                        </div>
                    </div>

                    <table class="table table-bordered table-sm table-striped" width="100%" id="tbl_messages">
                        <thead>
                            <tr>
                                <td width="5%">
                                    <input type="checkbox" class="check_all">
                                </td>
                                <td width="5%"></td>
                                <td width="25%">From</td>
                                <td>Subject</td>
                                <td width="25%">Date Sent</td>
                            </tr>
                        </thead>
                        <tbody id="tbl_messages_body"></tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>
</div>

@include('modals.modal_message')

@endsection

@push('scripts')
    <script type="text/javascript" src="{{ asset('/js/pages/inbox.js') }}"></script>
@endpush