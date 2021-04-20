@extends('layouts/layoutMaster')

@section('title', 'Appointments Lists')

@section('vendor-style')
<!-- vendor css files -->
<link rel="stylesheet" href="{{ asset('new-assets/app-assets/vendors/css/tables/datatable/datatables.min.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/datepicker/1.0.10/datepicker.min.css" integrity="sha512-YdYyWQf8AS4WSB0WWdc3FbQ3Ypdm0QCWD2k4hgfqbQbRCJBEgX0iAegkl2S1Evma5ImaVXLBeUkIlP6hQ1eYKQ==" crossorigin="anonymous" />
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
                <h5 class='text-success'>You have total {{ count(json_decode($appointments)) }} Appointments.</h5>

                <div class="row mb-2">
                    <div class="col-md-10">

                    </div>
                    <div class="col-md-2 text-right">
                        <!-- <a href="#" id="new_service_btn" class="btn btn-icon btn-primary" data-toggle="modal" data-target="#add_appointment_modal"><span class="icon-plus1"></span></a> -->
                        <button onclick="_formAppointment(0)" class="btn btn-icon btn-primary"><i data-feather="plus"></i></button>
                    </div>
                </div>

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
                                <td onClick="window.location.href = 'patientprofile/{{$appointment->patient_id}}/{{$appointment->doctor_id}}'"
                                    style="cursor:pointer">
                                    <span>{{ $appointment->p_email }}</span>
                                </td>
                                <td onClick="window.location.href = 'patientprofile/{{$appointment->patient_id}}/{{$appointment->doctor_id}}'"
                                    style="cursor:pointer">
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
                                        <button type="button" onclick="_formAppointment({{$appointment->id}})"
                                            class="btn btn-info">
                                            {!!\App\Library\Helpers\Helper::getSvgIconeByAction('EDIT')!!}
                                        </button>
                                        <button onclick="delete_func('delete_frm_{{ $appointment->id }}')" type="button"
                                            class="btn btn-danger">
                                            <form action="{{ route('reception.appointment.destroy', $appointment->id)}}"
                                                name="delete_frm_{{ $appointment->id }}"
                                                id="delete_frm_{{ $appointment->id }}" method="post">
                                                @csrf
                                                @method('DELETE')
                                                <i data-feather="trash"></i>
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

<input type="hidden" id="INPUT_HIDDEN_EDIT_APPONTMENT" value="Edit Appointment">
<input type="hidden" id="INPUT_HIDDEN_NEW_APPONTMENT" value="Add Appointment">
<!-- Modal -->
<div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" id="modal_form_appointment">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="APPONTMENT_MODAL_TITLE">Modal title</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body modal-body-lg">
            <form id="FORM_APPOINTMENT">
                    <div id='modal_form_appointment_body'>
                    </div>
                </form>
            </div><!-- .modal-body -->
            <div class="modal-footer">
                <button onclick="_submit_form()" class="btn btn-primary" id="service_update_btn"><i data-feather="save"></i>&nbsp;Update</button>
                <button data-dismiss="modal" class="btn btn-danger"><i data-feather="x"></i>&nbsp;Cancel</button>
            </div>
        </div><!-- .modal-content -->
    </div><!-- .modal-dialog -->
</div><!-- .modal -->

@endsection

@section('vendor-script')
<script src="{{ asset('new-assets/app-assets/vendors/js/tables/datatable/datatables.min.js') }}"></script>
<script src="{{ asset('new-assets/app-assets/vendors/js/tables/datatable/datatables.buttons.min.js') }}"></script>
<script src="{{ asset('new-assets/app-assets/vendors/js/tables/datatable/buttons.html5.min.js') }}"></script>
<script src="{{ asset('new-assets/app-assets/vendors/js/tables/datatable/buttons.print.min.js') }}"></script>
<script src="{{ asset('new-assets/app-assets/vendors/js/tables/datatable/buttons.bootstrap.min.js') }}"></script>
<script src="{{ asset('new-assets/app-assets/vendors/js/tables/datatable/datatables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('new-assets/app-assets/vendors/js/extensions/sweetalert2.all.min.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/datepicker/1.0.10/datepicker.min.js" integrity="sha512-RCgrAvvoLpP7KVgTkTctrUdv7C6t7Un3p1iaoPr1++3pybCyCsCZZN7QEHMZTcJTmcJ7jzexTO+eFpHk4OCFAg==" crossorigin="anonymous"></script>
@endsection
@section('page-script')
<script src="{{ asset('new-assets/js/main.js') }}"></script>
<script>

$(document).ready(function() {
    var table = $('.datatable').DataTable();
});

function _formAppointment(id) {
    var modal_id = "modal_form_appointment";
    var modal_content_id = "modal_form_appointment_body";
    var spinner =
        '<div class="modal-body"><center><div class="spinner-border text-primary text-center" role="status"><span class="sr-only">Loading...</span></div></center></div>';
    $("#" + modal_id).modal("show");
    $("#" + modal_content_id).html(spinner);
    var modalTitle = id > 0 ? $("#INPUT_HIDDEN_EDIT_APPONTMENT").val() : $("#INPUT_HIDDEN_NEW_APPONTMENT").val();
    $("#APPONTMENT_MODAL_TITLE").html('<i class="feather icon-edit"></i> ' + modalTitle);
    $.ajax({
        url: "/reception/form/appointment/" + id,
        type: "GET",
        dataType: "html",
        success: function(html, status) {
            $("#" + modal_content_id).html(html);
            alert('start_time');
            $("#start_time").datepicker({
                format: "yyyy-mm-dd",
                autoclose: true,
                todayBtn: true,
                startDate: "{{$current_time}}",
                //minuteStep: 10
                }).on('show.bs.modal', function(event) {
                    event.stopPropagation();
                });
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
            if (response.success) {
                $("#modal_form_appointment").modal('hide');
                swal({
                    title: "Success!",
                    text: response.msg,
                    icon: "success",
                });
                window.location.href = '{{route("reception.appointment")}}';
            } else {
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
function delete_func(val) {
    document.getElementById(val).submit();
}
function _submit_form(){
    $("#SUBMIT_APPOINTMENT_FORM").click();
}

</script>
@endsection