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
    <div class="row mb-2">    
        <div class="col-md-10">
            <h5 class='text-success'>You have total {{ count(json_decode($appointments)) }} Appointments.</h5>
        </div>
        <div class="col-md-2 text-right">
            <!-- <a href="#" id="new_service_btn" class="btn btn-icon btn-primary" data-toggle="modal" data-target="#add_appointment_modal"><span class="icon-plus1"></span></a> -->
            <button onclick="_formAppointment(0)" class="btn btn-icon btn-primary"><span class="icon-plus1"></span></button>
        </div>  
    </div> 
    <div class="row">
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
                                    <th>Duration</th>
                                    <th>Comments</th>
                                    <th>Status</th>
                                    <th class="tb-tnx-action">
                                        Action
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach(json_decode($appointments) as $appointment)
                                <tr>
                                    <td onClick = "window.location.href = 'patientprofile/{{$appointment->patient_id}}/{{$appointment->doctor_id}}'" style="cursor: url(hand.cur), pointer">
                                        <span>{{ $appointment->p_email }}</span>
                                    </td>
                                    <td onClick = "window.location.href = 'patientprofile/{{$appointment->patient_id}}/{{$appointment->doctor_id}}'" style="cursor: url(hand.cur), pointer">
                                        <span>{{ $appointment->d_email }}</span>
                                    </td>
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
                                    <td class="text-center">
                                        <div class="btn-group btn-group-sm">
                                            <button type="button" onclick="_formAppointment({{$appointment->id}})" class="btn btn-info">
                                                <i class="icon-edit1"></i>
                                            </button>
                                            <button onclick="delete_func('delete_frm_{{ $appointment->id }}')"  type="button" class="btn btn-danger">
                                                <form action="{{ route('reception.appointment.destroy', $appointment->id)}}" name="delete_frm_{{ $appointment->id }}" id="delete_frm_{{ $appointment->id }}" method="post">
                                                    @csrf
                                                    @method('DELETE')
                                                    <i class="icon-cancel"></i>
                                                </form>    
                                            
                                            </button>                                            
                                        </div>                                    
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




<input type="hidden" id="INPUT_HIDDEN_EDIT_APPONTMENT" value="Edit Appointment">
<input type="hidden" id="INPUT_HIDDEN_NEW_APPONTMENT" value="Add Appointment">
<!-- Modal -->
<div class="modal fade bd-example-modal-lg" role="dialog" id="modal_form_appointment">
   <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="APPONTMENT_MODAL_TITLE">Modal title</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      </div>


      <div class="modal-body">
        <form id="FORM_APPOINTMENT">
            <div id='modal_form_appointment_body'>
            </div>
        </form>
      </div>
      

      </div>
      <div class="modal-footer">
        <button onclick="" class="btn btn-primary"><span class="icon-save"></span>&nbsp;Update</button>                                        
        <button data-dismiss="modal" class="btn btn-danger"><span class="icon-close"></span>&nbsp;Cancel</button> 
      </div>
    </div>
  </div>
</div>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css" integrity="sha512-mSYUmp1HYZDFaVKK//63EcZq4iFWFjxSL+Z3T/aCt4IO9Cejm03q3NKKYN6pFQzY0SBOr8h+eCIAZHPXcpZaNw==" crossorigin="anonymous" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js" integrity="sha512-T/tUfKSV1bihCnd+MxKD0Hm1uBBroVYBOYSk1knyvQ9VyZJpc/ALb4P0r6ubwVPSGB2GvjeoMAJJImBG12TiaQ==" crossorigin="anonymous"></script>
<script>
$(document).ready(function(){
    var table = $('.datatable').DataTable();
    get_time_range_for_doctor();


    function get_time_range_for_doctor() {
        let current_doctor_id = $("#a_doctor").val();
        let doctors = {!! json_encode($doctors) !!};
       
        let date = new Date();      
        let year = date.getUTCFullYear();
        let month = date.getUTCMonth() + 1;
        let day = date.getUTCDate();
        
        if(month < 10) month = "0" + month;
        if(day < 10) day = "0" + day;

        doctors.forEach((element) => {
            if (current_doctor_id == element.user_id){
                $("#a_starttime").val( year + "-" + month + "-" + day + " " + element.from + ":00:00");
                $("#a_endtime").val( year + "-" + month + "-" + day + " " + element.to + ":00:00");
            }
        });
    }
    

    function time_convert(time) {
        let date = new Date(time);
                    
        let year = date.getUTCFullYear();
        let month = date.getUTCMonth() + 1; //months from 1-12
        //var day = date.getDay();
        let day = date.getUTCDate();
        let hour = date.getHours();
        let minutes = date.getMinutes();

        let temp_min = 0;
        
        // if (minutes >=0 && minutes <=15 )
        //     temp_min = 15;
        // else if (minutes >=15 && minutes <=30 )
        //     temp_min = 30;
        // else if (minutes >=30 && minutes <=45 )
        //     temp_min = 45;
        // else if (minutes >=45 && minutes <=59 ) {
        //     temp_min = 0;
        //     hour = hour + 1;
        // }
        if (minutes == 0)
            temp_min = 00;
        else if (minutes >0 && minutes <=15 )
            temp_min = 15;
        else if (minutes >=15 && minutes <=30 )
            temp_min = 30;
        else if (minutes >=30 && minutes <=45 )
            temp_min = 45;
        else if (minutes >=45 && minutes <=59 ) {
            temp_min = 0;
            hour = hour + 1;
        }

        if (hour == 24) {
            hour = 0;
            day = day + 1; 
        }
        if(month < 10) month = "0" + month;
        if(day < 10) day = "0" + day;
        if(hour < 10) hour = "0" + hour;
        if(temp_min < 10) temp_min = "0" + temp_min;
        //2021-02-21 22:52:29
        let new_date = year + "-" + month + "-" + day + " " + hour + ":" + temp_min + ":00";
        return new_date;
    }


    $('#a_doctor').on('change', function (e) {
        var optionSelected = $("option:selected", this);
        get_time_range_for_doctor();
    });



    function checkingState(e) {

        var url = "/reception/appointment/" + $('#a_duration').val() + "/" + $("#a_doctor").val() + "/" +  $("#a_starttime").val() + "/" + $("#a_endtime").val();
        $.ajax({
            url: url,
            type:"GET",
            dataType: 'json',
            success:function(response){
             
                if (response['state'] == true && response['start_time'] != '0')
                {
                    swal({
                        title: "Good!",
                        text: "This doctor is available!",
                        icon: "success",
                    });
                    e.preventDefault();
                   
                    $("#a_start_time").val(time_convert(response['start_time']));
                   // $("#start_time_div").addClass( 'form_datetime' );
                    $(".form_datetime").datetimepicker({
                        format: "yyyy-mm-dd hh:ii",
                        autoclose: true,
                        todayBtn: true,
                        startDate: "{{$current_time}}",
                        minuteStep: 10
                    });

                } else if (response['state'] == false && response['start_time'] == '0') {
                    swal({
                        title: "Error!",
                        text: "This doctor is not available!",
                        icon: "error",
                    });
                    e.preventDefault();
                    $("#a_start_time").val("");
                    $("#start_time_div").removeClass( 'form_datetime' );
                   
                }
                
            },
        });
    }



    $("#a_doctor").change(function(e){
        var doctor = $("#a_doctor").val();
        var duration = $('#a_duration').val();

        if (doctor != null && duration != "") {
            checkingState(e);
        }
    });

    $("#a_duration").change(function(e){
        var doctor = $("#a_doctor").val();
        var duration = $('#a_duration').val();
      
        if (doctor != null && duration != "") {
            checkingState(e);
        }
    });


    
    $("#appointment_save_btn").click(function(e){
        e.preventDefault();
        var patient_id = $("#a_patient").val();
        var doctor_id = $("#a_doctor").val();
        var start_time = $("#a_start_time").val();
        var duration = $("#a_duration").val();
        var status = $("#a_status").val();
        var comments = $("#a_comments").val();
        
        //var services = $("#a_services").val();

        if( patient_id != "" && start_time != "" && duration != "" ) {
            $.ajax({
                url: '{{route("reception.appointment.store")}}',
                type:"POST",
                data:{
                    patient_id: patient_id,
                    doctor_id: doctor_id,
                    start_time: start_time,
                    duration: duration,
                    status: status,
                    comments: comments,
                    _token: "{{ csrf_token() }}",
                },
                success:function(response){
                    $("#add_appointment_modal").modal('hide');
                    swal({
                            title: "Success!",
                            text: 'New note created successfully.',
                            icon: "success",
                        });

                    window.location.href = '{{route("reception.appointment")}}';
                },
            });
        }
    });


   


    $('#edit_appointment_modal').on('show.bs.modal', function(e) {
        var appointment_data = $(e.relatedTarget).data('id');
        $("#e_appointment_id").val(appointment_data['id']);

        $("#e_doctor").val(appointment_data['doctor_id']);
        $('#select2-e_doctor-container').text($( "#e_doctor option:selected").text());
        
        $("#e_patient").val(appointment_data['patient_id']);
        $('#select2-e_patient-container').text($( "#e_patient option:selected").text());

        $("#e_start_time").val(appointment_data['start_time']);
        $("#e_duration").val(appointment_data['duration']);
        $("#e_temp_duration").val(appointment_data['duration']);
        $("#e_status").val(appointment_data['status']);
        $("#e_comments").val(appointment_data['comments']);


        let current_doctor_id = $("#e_doctor").val();
        let doctors = {!! json_encode($doctors) !!};
       
        let date = new Date();      
        let year = date.getUTCFullYear();
        let month = date.getUTCMonth() + 1;
        let day = date.getUTCDate();
        
        if(month < 10) month = "0" + month;
        if(day < 10) day = "0" + day;

        doctors.forEach((element) => {
            if (current_doctor_id == element.user_id){
                $("#e_starttime").val( year + "-" + month + "-" + day + " " + element.from + ":00:00");
                $("#e_endtime").val( year + "-" + month + "-" + day + " " + element.to + ":00:00");
            }
        });

        

    });



    function editCheckingState(e) {
        e.preventDefault();
        let temp = $("#e_temp_duration").val();
        let duration = $('#e_duration').val();
        if ( duration <= temp ){
            swal({
                title: "Good!",
                text: "This doctor is available!",
                icon: "success",
            });
        } else {
            var url = "/reception/appointment/" + $('#e_duration').val() + "/" + $("#e_doctor").val() + "/" +  $("#e_starttime").val() + "/" + $("#e_endtime").val();
            $.ajax({
                url: url,
                type:"GET",
                dataType: 'json',
                success:function(response){
                
                    if (response['state'] == true && response['start_time'] != '0')
                    {
                        swal({
                            title: "Good!",
                            text: "This doctor is available!",
                            icon: "success",
                        });
                        e.preventDefault();
                    
                        $("#e_start_time").val(time_convert(response['start_time']));
                    // $("#start_time_div").addClass( 'form_datetime' );
                        $(".form_datetime").datetimepicker({
                            format: "yyyy-mm-dd hh:ii",
                            autoclose: true,
                            todayBtn: true,
                            startDate: "{{$current_time}}",
                            minuteStep: 10
                        });

                    } else if (response['state'] == false && response['start_time'] == '0') {
                        swal({
                            title: "Error!",
                            text: "This doctor is not available!",
                            icon: "error",
                        });
                        e.preventDefault();
                        $("#e_start_time").val("");
                        $("#start_time_div").removeClass( 'form_datetime' );
                    
                    }
                    
                },
            });
        }
    }


    $("#e_doctor").change(function(e){
        var doctor = $("#e_doctor").val();
        var duration = $('#e_duration').val();

        if (doctor != null && duration != "") {
            editCheckingState(e);
        }
    });

    $("#e_duration").change(function(e){
        var doctor = $("#e_doctor").val();
        var duration = $('#e_duration').val();

        if (doctor != null && duration != "") {
            editCheckingState(e);
        }
    });



    $("#appointment_update_btn").click(function(e){
        e.preventDefault();
        var id = $("#e_appointment_id").val();
        var patient_id = $("#e_patient").val();
        var doctor_id = $("#e_doctor").val();
        var start_time = $("#e_start_time").val();
        var duration = $("#e_duration").val();
        var status = $("#e_status").val();
        var comments = $("#e_comments").val();

        if( patient_id != "" && start_time != "" && duration != "" ) {
            $.ajax({
                url: '{{route("reception.appointment.store")}}',
                type:"PUT",
                data:{
                    id: id,
                    patient_id: patient_id,
                    doctor_id: doctor_id,
                    start_time: start_time,
                    duration: duration,
                    status: status,
                    comments: comments,
                    _token: "{{ csrf_token() }}",
                },
                success:function(response){
                    $("#edit_service_modal").modal('hide');
                    NioApp.Toast('Success.', 'success');
                    toastr.clear();
                    window.location.href = '{{route("reception.appointment")}}';
                },
            });
        }
    });

});

function _formAppointment(id) {
  var modal_id = "modal_form_appointment";
  var modal_content_id = "modal_form_appointment_body";
  var spinner ='<div class="modal-body"><center><div class="spinner-border text-primary text-center" role="status"><span class="sr-only">Loading...</span></div></center></div>';
  $("#" + modal_id).modal("show");
  $("#" + modal_content_id).html(spinner);
  var modalTitle =id > 0? $("#INPUT_HIDDEN_EDIT_APPONTMENT").val(): $("#INPUT_HIDDEN_NEW_APPONTMENT").val();
  $("#APPONTMENT_MODAL_TITLE").html('<i class="feather icon-edit"></i> ' + modalTitle);
  $.ajax({
    url: "/reception/form/appointment/" + id,
    type: "GET",
    dataType: "html",
    success: function(html, status) {
      $("#" + modal_content_id).html(html);
    },
  });
};



$("#FORM_APPOINTMENT").submit(function(event) {
        event.preventDefault();
        var formData = $(this).serializeArray();
        //console.log(formData);
        //return false;
        $.ajax({
            type: "POST",
            dataType: 'json',
            data: formData,
            url: '/reception/form/appointment',
            success: function(response) {
                if(response.success){
                $("#modal_form_appointment").modal('hide');
                swal({
                            title: "Success!",
                            text: response.msg,
                            icon: "success",
                        });
                window.location.href = '{{route("reception.appointment")}}';
                }else{
                    swal({
                            title: "Error!",
                            text: response.msg,
                            icon: "error",
                    });
                }
            },
            error: function() {
                
            }
        }).done(function(data) {
            
        });
        return false;
    });




</script>

@endsection
