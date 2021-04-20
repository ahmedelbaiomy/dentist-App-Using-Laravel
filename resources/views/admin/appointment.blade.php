@extends('layouts/layoutMaster')

@section('title', 'Appointments Lists')

@section('vendor-style')
<!-- vendor css files -->
<link rel="stylesheet" href="{{ asset('new-assets/app-assets/vendors/css/tables/datatable/datatables.min.css') }}">
@endsection

@section('page-style')
{{-- Page Css files --}}

@endsection

@section('content')

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Appointments Lists</h4>
                <h5 class='text-success'>You have total {{ count($appointments) }} Appointments.</h5>
                <div class="table-responsive">
                    <table class="datatable table table-striped dataex-html5-selectors">
                        <thead>
                            <tr>
                                <th>Patient</th>
                                <th>Doctor</th>
                                <th>Star Time</th>
                                <th>Finish Time</th>
                                <th>Comments</th>
                                <th>State</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($appointments as $appointment)
                            <tr>
                                <td><span>{{ $appointment->p_email }}</span></td>
                                <td><span>{{ $appointment->d_email }}</span></td>
                                <td><span>{{ $appointment->start_time }}</span></td>
                                <td><span>{{ $appointment->duration }}</span></td>
                                <td><span>{{ $appointment->comments }}</span></td>
                                <td>
                                    @if($appointment->status == 1)
                                    <span class="tb-status text-success">Booked</span>
                                    @elseif($appointment->status == 2)
                                    <span class="tb-status text-warning">Confirmed</span>
                                    @elseif($appointment->status == 3)
                                    <span class="tb-status text-danger">Canceled</span>
                                    @elseif($appointment->status == 4)
                                    <span class="tb-status text-info">Attended</span>
                                    @else
                                    <span class="tb-status">None</span>
                                    @endif
                                </td>

                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('vendor-script')
<script src="{{ asset('new-assets/app-assets/vendors/js/tables/datatable/datatables.min.js') }}"></script>
<script src="{{ asset('new-assets/app-assets/vendors/js/tables/datatable/datatables.buttons.min.js') }}"></script>
<script src="{{ asset('new-assets/app-assets/vendors/js/tables/datatable/buttons.html5.min.js') }}"></script>
<script src="{{ asset('new-assets/app-assets/vendors/js/tables/datatable/buttons.print.min.js') }}"></script>
<script src="{{ asset('new-assets/app-assets/vendors/js/tables/datatable/buttons.bootstrap.min.js') }}"></script>
<script src="{{ asset('new-assets/app-assets/vendors/js/tables/datatable/datatables.bootstrap4.min.js') }}"></script>
@endsection
@section('page-script')
<script>
$(document).ready(function() {
    var table = $('.datatable').DataTable();
});
</script>
@endsection












