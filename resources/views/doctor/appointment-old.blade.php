@extends('layouts.app')
@section('content')

<script>

    function delete_func(val) {
        document.getElementById(val).submit();
    }

</script>
<div class="page-header">
    <ol class="breadcrumb">
        <li class="breadcrumb-item active">Appointments Lists</li>
    </ol>
</div>
<div class="content-wrapper">  
    
    <div class="row">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="card-body">
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
</div>



<script>

</script>

@endsection
