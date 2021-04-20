@extends('layouts/layoutMaster')

@section('title', 'Dashboard')

@section('vendor-style')
<!-- vendor css files -->
<link rel="stylesheet" href="{{ asset('new-assets/app-assets/vendors/css/calendars/fullcalendar.min.css') }}">
<!-- <link rel="stylesheet" href="{{ asset('assets/plugins/calendar/css/fullcalendar.min.css') }}" /> -->

@endsection

@section('page-style')
{{-- Page Css files --}}
<link rel="stylesheet" href="{{ asset('new-assets/app-assets/css/pages/app-calendar.css') }}"> 
<!-- <link rel="stylesheet" href="{{ asset('assets/plugins/calendar/css/custom-calendar.css') }}" /> -->
@endsection

@section('content')

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-0">Dashboard</h4>

            </div>
        </div>
    </div>
</div>

<!-- Full calendar start -->
<section>
    <div class="app-calendar overflow-hidden border">
        <div class="row no-gutters">
            <!-- Sidebar -->
            <div class="col app-calendar-sidebar flex-grow-0 overflow-hidden d-flex flex-column"
                id="app-calendar-sidebar">
                <div class="sidebar-wrapper">
                    <div class="card-body pb-0">
                        <div class="calendar-events-filter">
							@foreach(json_decode($events) as $event)
                            <div class="custom-control custom-control-danger custom-checkbox mb-1">
                                <input type="checkbox" id="{{$event->d_id}}" name="doctor_id[]" onchange="getDoctorappointmentCalender()" class="custom-control-input input-filter" value="{{$event->d_id}}" />
                                <label class="custom-control-label" for="{{$event->d_id}}">{{$event->title}}</label>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            <!-- /Sidebar -->

            <!-- Calendar -->
            <div class="col position-relative">
                <div class="card shadow-none border-0 mb-0 rounded-0">
                    <div class="card-body pb-0">
                        <div id="calendar"></div>
                    </div>
                </div>
            </div>
            <!-- /Calendar -->
            <div class="body-content-overlay"></div>
        </div>
    </div>
    
</section>
<!-- Full calendar end -->


@endsection

@section('vendor-script')
<!-- <script src="{{ asset('assets/js/moment.js') }}"></script>
<script src="{{ asset('assets/plugins/calendar/js/fullcalendar.min.js') }}"></script> -->
<script src="{{ asset('new-assets/app-assets/vendors/js/calendar/fullcalendar.min.js') }}"></script>
<script src="{{ asset('new-assets/app-assets/vendors/js/extensions/moment.min.js') }}"></script>
@endsection
@section('page-script')
<script>
var date = new Date();
var d = date.getDate();
var m = date.getMonth();
var y = date.getFullYear();
var previewEventPopup = $('#previewEventPopup');



// Calendar plugins
var calendarEl = document.getElementById('calendar');
var calendar = new FullCalendar.Calendar(calendarEl, {
    initialView: 'dayGridMonth',
    //events: {!!$events!!},
	events: [
		{
        "id": 1,
        "title": " Patient 222",
        "start": "2021-04-11 09:00:00",
        "end": "2021-04-11 10:00:00",
        "className": "new_color_8",
        "description": "nnn",
        "p_id": 2,
        "d_id": 8
    }
  	], 
    editable: true,
    //dragScroll: true,
    dayMaxEvents: 2,
    eventResizableFromStart: true,
    customButtons: {
      sidebarToggle: {
        text: 'Sidebar'
      }
    },
    headerToolbar: {
      start: 'sidebarToggle, prev,next, title',
      end: 'dayGridMonth,timeGridWeek,timeGridDay,listMonth'
    },
    //direction: direction,
    initialDate: new Date(),
    navLinks: true, // can click day/week names to navigate views
    eventClassNames: function ({ event: calendarEvent }) {
      //const colorName = calendarsColor[calendarEvent._def.extendedProps.calendar];
      const colorName = 'info';

      return [
        // Background Color
        'bg-light-' + colorName
      ];
    },
    eventClick: function(info) {
        window.location.href = 'patientprofile/' + info.p_id + "/" + info.d_id;
    },
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

  // Render calendar
  calendar.render();


/* $('#calendar').fullCalendar({
    header: {
        left: 'prev, next',
        center: 'title',
        right: 'today, month, agendaWeek, agendaDay'
    },
    eventClick: function(info) {
        window.location.href = 'patientprofile/' + info.p_id + "/" + info.d_id;
    },

    events: {!!$events!!},

    editable: true,
    eventLimit: false,
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
}); */
function getDoctorappointmentCalender() {
    var doctor_id = [];
    $('input[name="doctor_id[]"]:checked').each(function() {
        doctor_id.push(this.value);
    });

    $.ajax({
        type: "post",
        url: '{{ route("reception.getDoctorappointmentCalender") }}',
        data: {
            "_token": "{{ csrf_token() }}",
            "doctor_id": doctor_id
        },
        success: function(data) {
            //$('#calendar').fullCalendar('removeEvents');
            //$('#calendar').fullCalendar('addEventSource', data);
			calendar.addEventSource( data );
        },
    });
}
</script>
@endsection