@extends('layouts.app')

@section('content')
<div class="container mt-2">
    <div class="row">
        <div class="offset-md-2 col-md-8">
            <div class="card shadow-lg">
                <div class="card-header bg-red text-white">Change Password</div>

                <div class="card-body">
                    <div class="loading"></div>

                    <form action="{{ url('/settings/save-password') }}" class="form-horizontal" method="post" id="frm_password">
                        <div class="form-group row">
                            <div class="col-sm-12">
                                <div class="input-group input-group-sm">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Old Password:</span>
                                    </div>
                                    <input type="password" class="form-control form-control-sm validate clear" name="old_password" id="old_password" required>
                                    <div id="old_password_feedback"></div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-sm-12">
                                <div class="input-group input-group-sm">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">New Password:</span>
                                    </div>
                                    <input type="password" class="form-control form-control-sm validate clear" name="new_password" id="new_password" required>
                                    <div id="new_password_feedback"></div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-sm-12">
                                <div class="input-group input-group-sm">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Confirm Password:</span>
                                    </div>
                                    <input type="password" class="form-control form-control-sm validate clear" name="confirm_password" id="confirm_password" required>
                                    <div id="confirm_password_feedback"></div>
                                </div>
                            </div>
                        </div>

                        <hr>

                        <div class="form-group row">
                            <div class="offset-md-8 col-md-4">
                                <button type="submit" class="btn btn-block btn-sm btn-primary" id="btn_save" disabled>
                                    <i class="fa fa-floppy-o"></i> Save
                                </button>
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
<script type="text/javascript" src="{{ asset('/js/pages/change_password.js') }}"></script>
@endpush