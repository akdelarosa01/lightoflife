@extends('layouts.app')

@section('content')
<div class="container mt-2">
    <div class="row">
        <div class="col-md-5">
            <div class="card shadow-lg">
                <div class="card-header bg-red text-white">
                    <i class="fa fa-user"></i> 
                    <?php
                        if (Auth::user()->middlename == 'null' || Auth::user()->middlename == null || Auth::user()->middlename == '') {
                            echo Auth::user()->firstname.' '.Auth::user()->lastname;
                        } else {
                            echo Auth::user()->firstname.' '.Auth::user()->middlename.' '.Auth::user()->lastname;
                        }
                    ?>
                </div>

                <div class="card-body">
                    <div class="row justify-content-center">
                        <div class="col-md-4 text-center">
                            <img src="{{ asset('/images/Teachers.png') }}" alt="">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <table class="table">
                                <tr>
                                    <td>ID Number</td>
                                    <td>{{ Auth::user()->username }}</td>
                                </tr>
                                <tr>
                                    <td>Account Type</td>
                                    <td>
                                        <?php
                                            switch (Auth::user()->user_type) {
                                                case 2:
                                                    echo 'Teacher';
                                                    break;

                                                case 3:
                                                    echo 'Student';
                                                    break;

                                                case 4:
                                                    echo 'Parent';
                                                    break;
                                                
                                                default:
                                                    echo 'Administrator';
                                                    break;
                                            }


                                        ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Campus</td>
                                    <td>Light of Life Christian School</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <h6>Subjects Handled</h6>
                            <table class="table table-bordered table-striped table-sm" id="tbl_handled" width="100%">
                                <thead>
                                    <tr>
                                        <th width="5%"></th>
                                        <th>Subject</th>
                                        <th>Section</th>
                                    </tr>
                                </thead>
                                <tbody id="tbl_handled_body"></tbody>
                            </table>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <div class="col-md-7">
            <div class="card shadow-lg">
                <div class="card-header bg-red text-white" id="title">
                    <i class="fa fa-exclamation-triangle"></i> Announcement
                </div>

                <div class="card-body">
                    <p id="announcement" style="font-size: 16px"></p>
                </div>
            </div>
        </div>
    </div>
</div>
@include('modals.modal_home_teacher')

@endsection

@push('scripts')
<script type="text/javascript" src="{{ asset('/js/pages/home_teacher.js') }}"></script>
@endpush