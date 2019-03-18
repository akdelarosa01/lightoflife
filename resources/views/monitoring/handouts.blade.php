@extends('layouts.app')

@section('content')
<div class="container mt-2">
    <div class="row">
        <div class="offset-md-1 col-md-10">
            <div class="card shadow-lg">
                <div class="card-header bg-red text-white">Handouts</div>

                <div class="card-body">
                	<div class="loading"></div>

					<table class="table table-sm table-bordered table-striped" width="100%" id="tbl_handouts">
						<thead>
							<tr>
								<td width="10%"></td>
								<td width="10%">Year Level</td>
								<td width="15%">Subject</td>
								<td width="65%">Description</td>
								
							</tr>
						</thead>
						<tbody id="tbl_handouts_body"></tbody>
					</table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
	<script type="text/javascript" src="{{ asset('/js/pages/handouts-list.js') }}"></script>
@endpush