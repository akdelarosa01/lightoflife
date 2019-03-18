@extends('layouts.app')

@section('content')
<div class="container mt-2">
    <div class="row">
        <div class="offset-md-2 col-md-8">
            <div class="card shadow-lg">
                <div class="card-header bg-red text-white">User Logs</div>

                <div class="card-body">
                	<div class="loading"></div>

					<table class="table table-sm table-bordered table-striped" width="100%" id="tbl_user">
						<thead>
							<tr>
								<td>ID Number</td>
								<td>Name</td>
								<td>Activity</td>
								<td>Logged Date</td>
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
<script type="text/javascript" src="{{ asset('/js/pages/user_logs.js') }}"></script>
@endpush