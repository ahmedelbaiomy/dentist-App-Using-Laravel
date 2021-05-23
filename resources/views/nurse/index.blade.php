@extends('layouts/layoutMaster')

@section('title', 'Dashboard')

@section('vendor-style')
<!-- vendor css files -->
<link rel="stylesheet" href="{{ asset('assets/plugins/calendar/css/fullcalendar.min.css') }}" />
<link rel="stylesheet" href="{{ asset('assets/plugins/calendar/css/custom-calendar.css') }}" />
<link rel="stylesheet" href="{{ asset('assets/css/color.css') }}">
@endsection

@section('page-style')
{{-- Page Css files --}}
<style>
.fc-event.fc-draggable,
.fc-event span {
    color: #fff !important;
}
</style>
@endsection

@section('content')

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <!-- <h4 class="card-title">Dashboard</h4> -->
                <div class="row gutters">
                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="card">
                            <div class="card-body">
                                <div id="calendar" class="fc-calendar"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('vendor-script')
<script src="{{ asset('assets/js/moment.js') }}"></script>
<script src="{{ asset('assets/plugins/calendar/js/fullcalendar.min.js') }}"></script>
@endsection
@section('page-script')
<script src="{{ asset('new-assets/js/main.js') }}"></script>

<script>
var date = new Date();
var d = date.getDate();
var m = date.getMonth();
var y = date.getFullYear();
$('#calendar').fullCalendar({
    header: {
        left: 'prev, next',
        center: 'title',
        right: 'today, month, agendaWeek, agendaDay'
    },
    eventClick: function(info) {
        //console.log(info);
        window.location.href = '/profile/patient/' + info.p_id;
    },
    //Add Events
    events: {!!$events!!},

    editable: true,
    eventLimit: true,
    droppable: true, // this allows things to be dropped onto the calendar
    drop: function(date, allDay) { // this function is called when something is dropped

        // retrieve the dropped element's stored Event Object
        var originalEventObject = $(this).data('eventObject');

        // we need to copy it, so that multiple events don't have a reference to the same object
        var copiedEventObject = $.extend({}, originalEventObject);

        // assign it the date that was reported
        copiedEventObject.start = date;
        copiedEventObject.allDay = allDay;

        // render the event on the calendar
        // the last `true` argument determines if the event "sticks" (http://arshaw.com/fullcalendar/docs/event_rendering/renderEvent/)
        $('#calendar').fullCalendar('renderEvent', copiedEventObject, true);

        // is the "remove after drop" checkbox checked?
        if ($('#drop-remove').is(':checked')) {
            // if so, remove the element from the "Draggable Events" list
            $(this).remove();
        }
    }
});
</script>
@endsection


















