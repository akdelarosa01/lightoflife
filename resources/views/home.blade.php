@extends('layouts.app')

@section('content')
<div class="container mt-2">
    <div class="row">
        <div class="offset-md-2 col-md-8">
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
@endsection

@push('scripts')
<script type="text/javascript" src="{{ asset('/js/pages/home.js') }}"></script>
@endpush