@extends('layouts.app')

@section('content')
<div class="container mt-2">
    <div class="row">
        <div class="offset-md-1 col-md-10">
            <div class="card shadow-lg">
                <div class="card-header bg-red text-white">Activity Logs</div>

                <div class="card-body">
                	<div class="loading"></div>

					<table class="table table-sm table-bordered table-striped" width="100%" id="tbl_activity">
						<thead>
							<tr>
								<td>ID Number</td>
								<td>Name</td>
								<td>Module</td>
								<td>Activity</td>
								<td>Date</td>
							</tr>
						</thead>
						<tbody></tbody>
					</table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
	<script type="text/javascript" src="{{ asset('/js/pages/activity_logs.js') }}"></script>
@endpush