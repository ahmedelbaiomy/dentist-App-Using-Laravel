
    {{ csrf_field() }}
    <input type="hidden" name="id" value="@if($appointment!=null) {{ $appointment->id }} @else 0 @endif">

    <div class="row gy-4">
        <div class="col-md-6">
            <div class="form-group">
                <label class="form-label">Patient*</label>
                <div class="form-control-wrap">
                    <select class="form-control form-control-lg" data-search="on" id="patient_id" name="patient_id" required>
                        @foreach($patients as $patient)
                            @php
                                $selected = ($appointment && $appointment->patient_id ===$patient->id)?'selected':'';
                            @endphp
                        <option value="{{ $patient->id }}">{{ $patient->email }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <label class="form-label">Doctor</label>
                <div class="form-control-wrap">
                    <select class="form-control form-control-lg" data-search="on" id="select_doctor" name="doctor_id" required>
                        @foreach($doctors as $doctor)
                            @php
                                $selected = ($appointment && $appointment->doctor_id ===$doctor->user_id)?'selected':'';
                            @endphp
                        <option value="{{ $doctor->user_id }}">{{ $doctor->email }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <label class="form-label" for="start_time">Choose date *</label>
            @php
                $start_time ='';
                if($appointment){
                    $dt = Carbon\Carbon::createFromFormat('Y-m-d H:i:s',$appointment->start_time);
                    $start_time = $dt->format('Y-m-d');
                }
            @endphp
            <input class="form-control form-control-lg" id="start_time" name="start_time" onchange="_loadSlots()"
                size="16" type="text" value="{{$start_time}}" required>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <p>Choose time slot :</p>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="alert alert-info" id="DIV_TIME_SLOT" role="alert"></div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                <label class="form-label" for="default-05">Duration(Minutes*)</label>
                <div class="form-control-wrap">
                    <input type="number" min="1" max="480" id="duration" name="duration" value="@if($appointment!=null){{$appointment->duration}}@endif"
                        class="form-control form-control-lg" required>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                <label class="form-label" for="cf-default-textarea">Comment</label>
                <div class="form-control-wrap">
                    <textarea class="form-control form-control-sm" cols="30" rows="5" id="comments" name="comments"
                        placeholder="Write your comment">@if($appointment!=null){{$appointment->comments}}@endif</textarea>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                <label class="form-label">Status</label>
                <div class="form-control-wrap">
                    <select class="form-control" id="status" name="status" data-placeholder="Select Multiple services" required>
                        <option {{($appointment && $appointment->status ===1)?'selected':''}} value="1">Booked</option>
                        <option {{($appointment && $appointment->status ===2)?'selected':''}} value="2">Confirmed</option>
                        <option {{($appointment && $appointment->status ===3)?'selected':''}} value="3">Canceled</option>
                        <option {{($appointment && $appointment->status ===4)?'selected':''}} value="4">Attended</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <input type="submit" value="SUBMIT" id="SUBMIT_APPOINTMENT_FORM">

<script>
_loadSlots();
function _loadSlots() {
    //alert('_loadSlots');
    var loaderHtml ='<i class="fa fa-spinner spin"></i>';
    $("#DIV_TIME_SLOT").html(loaderHtml);
    var doctor_id = $("#select_doctor").val();
    var start_date = $("#start_time").val();
    //console.log(doctor_id+start_date);
    if(doctor_id>0 && start_date!=''){
        $.ajax({
            type: "GET",
            url: "/reception/get/time/slots/" + doctor_id+'/'+start_date,
            dataType: "html",
            success: function(html) {
                $("#DIV_TIME_SLOT").html(html);
            },
            error: function(err) {
                $("#DIV_TIME_SLOT").html('<i class="fa fa-times></i> Oops! Something went wrong. Please try again later.');
            },
        }).done(function(data) {});
    }   
}
$('#select_doctor').on('change', function() {
    _loadSlots();
});
$(document).ready(function(){
    $("#start_time").datepicker({
            format: "yyyy-mm-dd",
            autoclose: true,
            todayBtn: true,
            startDate: "{{$current_time}}",
            //minuteStep: 10
    }).on('show.bs.modal', function(event) {
        event.stopPropagation();
    });
});
</script>