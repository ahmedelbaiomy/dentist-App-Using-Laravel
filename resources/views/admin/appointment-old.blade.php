@extends('layouts.app')

@section('content')
<div class="page-header">
    <ol class="breadcrumb">
        <li class="breadcrumb-item active">Appointments Lists</li>
    </ol>
</div>
<div class="content-wrapper">    
    <div class="row">    
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-xs-12 mb-2">
            <h5 class='text-success'>You have total {{ count($appointments) }} Appointments.</h5>
        </div>    
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="datatable table"> 
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
</div>
<script>
$(document).ready(function(){
    var table = $('.datatable').DataTable();
   
});
</script>

@endsection
