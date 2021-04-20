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
                <div class="table-responsive">
                    <table class="datatable table">
                        <thead>
                            <tr>
                                <th>Star Time</th>
                                <th>Duration</th>
                                <th>Patient</th>
                                <th>Comments</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($events as $event)
                            <tr>
                                <td><span>{{ $event->start_time }}</span></td>
                                <td><span>{{ $event->end }}</span></td>
                                <td><span>{{ $event->p_email }}</span></td>
                                <td><span>{{ $event->comments }}</span></td>
                                <td>
                                    @if($event->status == 1)
                                    <span class="tb-status text-success">Booked</span>
                                    @elseif($event->status == 2)
                                    <span class="tb-status text-warning">Confirmed</span>
                                    @elseif($event->status == 3)
                                    <span class="tb-status text-danger">Canceled</span>
                                    @elseif($event->status == 4)
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
<script src="{{ asset('new-assets/js/main.js') }}"></script>
<script>
var table = $('.datatable').DataTable();
</script>
@endsection