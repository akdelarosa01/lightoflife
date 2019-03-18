@extends('layouts.app')

@section('content')
<div class="container-fluid mt-2">
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow-lg">
                <div class="card-header bg-red text-white">Create Account</div>

                <div class="card-body">
                    <div class="loading"></div>

                    {{-- <div class="row">
                        <div class="col-md-5">
                            
                        </div>
                    </div> --}}
                    
                    <div class="row">
                        <div class="col-md-5">
                            <form id="frm_upload_accounts" role="form" method="POST" action="{{ url('maintenance/upload-accounts') }}" enctype="multipart/form-data">
                                {{ csrf_field() }}
                                <div class="form-group row">
                                    <div class="col-md-8">
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input custom-file-input-sm" name="account_file" id="account_file">
                                            <label class="custom-file-label" for="account_file" id="file_label">Choose file</label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <button class="btn btn-primary btn-block">
                                            <i class="fa fa-upload"></i> Upload
                                        </button>
                                    </div>
                                </div>
                            </form>
                            
                            <form id="frm_accounts" role="form" method="POST" action="{{ url('maintenance/save-accounts') }}">
                                <div class="loadingOverlay"></div>

                                <input type="hidden" class="clear" id="id" name="id">
                                {{ csrf_field() }}

                                <div class="form-group row">
                                    <div class="col-sm-12">
                                        <div class="input-group input-group-sm">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">First Name:</span>
                                            </div>
                                            <input type="text" class="form-control form-control-sm validate clear" name="firstname" id="firstname" required>
                                            <div id="firstname_feedback"></div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-sm-12">
                                        <div class="input-group input-group-sm">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">Middle Name:</span>
                                            </div>
                                            <input type="text" class="form-control form-control-sm validate clear" name="middlename" id="middlename">
                                            <div id="middlename_feedback"></div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-sm-12">
                                        <div class="input-group input-group-sm">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">Last Name:</span>
                                            </div>
                                            <input type="text" class="form-control form-control-sm validate clear" name="lastname" id="lastname" required>
                                            <div id="lastname_feedback"></div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-sm-12">
                                        <div class="input-group input-group-sm">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">Account Type:</span>
                                            </div>
                                            <select class="form-control form-control-sm select-validate clear" name="user_type" id="user_type" required>
                                                <option value=""></option>
                                                <option value="1">Administrator</option>
                                                <option value="2">Teacher</option>
                                                <option value="3">Student</option>
                                            </select>
                                            <div id="user_type_feedback"></div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group row teacher student">
                                    <div class="col-sm-12">
                                        <div class="input-group input-group-sm">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">Year Level:</span>
                                            </div>
                                            <select class="form-control form-control-sm select-validate clear" name="program" id="program"></select>
                                            <div id="program_feedback"></div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group row teacher">
                                    <div class="col-sm-12">
                                        <div class="input-group input-group-sm">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">Department:</span>
                                            </div>
                                            <select class="form-control form-control-sm select-validate clear" name="department" id="department"></select>
                                            <div id="department_feedback"></div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-md-3">
                                        <button type="button" class="btn bg-red btn-sm btn-block" id="btn_delete">
                                            <i class="fa fa-times"></i> Disable
                                        </button>
                                    </div>
                                    <div class="col-md-3">
                                        <button type="button" class="btn btn-success btn-sm btn-block" id="btn_enable">
                                            <i class="fa fa-check"></i> Enable
                                        </button>
                                    </div>

                                    <div class="col-md-3">
                                        <button type="button" class="btn btn-secondary btn-sm btn-block" id="btn_clear">
                                            <i class="fa fa-refresh"></i> Clear
                                        </button>
                                    </div>
                                    <div class="col-md-3">
                                        <button type="submit" class="btn btn-primary btn-sm btn-block">
                                            <i class="fa fa-floppy-o"></i> Save
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <div class="col-md-7">
                            <table class="table table-bordered table-striped table-sm dt-responsive nowrap" id="tbl_account" style="width:100%">
                                <thead>
                                    <tr>
                                        <th width="5%">
                                            <input type="checkbox" class="check_all_accounts">
                                        </th>
                                        <th width="5%"></th>
                                        <th>ID Number</th>
                                        <th>First Name</th>
                                        <th>Middle Name</th>
                                        <th>Last Name</th>
                                        <th>Account Type</th>
                                        <th>Password</th>
                                    </tr>
                                </thead>
                                <tbody id="tbl_account_body"></tbody>
                            </table>
                        </div>

                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

@include('modals.modal_users')

@endsection

@push('scripts')
<script type="text/javascript" src="/js/pages/accounts.js"></script>
@endpush