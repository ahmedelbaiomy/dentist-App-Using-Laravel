@extends('layouts/layoutMaster')

@section('title', 'Patient profile')

@section('vendor-style')
<link rel="stylesheet" href="{{ asset('new-assets/app-assets/vendors/css/tables/datatable/datatables.min.css') }}">
@endsection

@section('page-style')
{{-- Page Css files --}}

@endsection

@section('content')

@php
$birthday = '';
if($patient_data[0]){
$dt = Carbon\Carbon::createFromFormat('Y-m-d',$patient_data[0]->birthday);
$birthday = $dt->format('d/m/Y');
}
@endphp


<div class="row">


    <div class="col-md-12">
        <!-- Overview -->
        <div class="card">
            <div class="card-body">


                <div class="row">
                    <div class="col-xl-6 col-lg-12 d-flex flex-column justify-content-between border-container-lg">
                        <div class="user-avatar-section">
                            <div class="d-flex justify-content-start">
                                <!-- avatar -->
                                <div class="avatar mr-1">
                                    <div class="avatar bg-light-primary avatar-lg">
                                        <span class="avatar-content">{{ $short_name }}</span>
                                    </div>
                                </div>
                                <!--/ avatar -->
                                <div class="d-flex flex-column ml-1">
                                    <div class="user-info mb-1">
                                        <h4 class="mb-0">{{ $patient_data[0]->name }}</h4>
                                        <span class="card-text">{{ $patient_data[0]->email }}</span>
                                    </div>
                                    <!-- <div class="d-flex flex-wrap">
                                        <a href="{{url('app/user/edit')}}" class="btn btn-primary">Edit</a>
                                        <button class="btn btn-outline-danger ml-1">Delete</button>
                                    </div> -->
                                </div>

                            </div>
                        </div>

                    </div>
                    <div class="col-xl-6 col-lg-12 mt-2 mt-xl-0">
                        <div class="user-info-wrapper">
                            <div class="d-flex flex-wrap">
                                <div class="user-info-title">
                                    <i data-feather="user" class="mr-1"></i>
                                    <span class="card-text user-info-title font-weight-bold mb-0">Birthday</span>
                                </div>
                                <p class="card-text mb-0">{{$birthday}}</p>
                            </div>
                            <div class="d-flex flex-wrap my-50">
                                <div class="user-info-title">
                                    <i data-feather="check" class="mr-1"></i>
                                    <span class="card-text user-info-title font-weight-bold mb-0">Phone</span>
                                </div>
                                <p class="card-text mb-0">{{ $patient_data[0]->phone }}</p>
                            </div>
                            <div class="d-flex flex-wrap my-50">
                                <div class="user-info-title">
                                    <i data-feather="star" class="mr-1"></i>
                                    <span class="card-text user-info-title font-weight-bold mb-0">Address</span>
                                </div>
                                <p class="card-text mb-0">{{ $patient_data[0]->address }}</p>
                            </div>
                        </div>
                    </div>
                </div>



                <!-- <div class="d-flex justify-content-start align-items-center mb-1">
                 
                    <div class="avatar mr-1">
                        <div class="avatar bg-light-primary avatar-lg">
                            <span class="avatar-content">{{ $short_name }}</span>
                        </div>
                    </div>
               
                    <div class="profile-user-info">
                        <h5 class="mb-0">{{ $patient_data[0]->name }}</h5>
                        <small class="text-muted"><b>Patient ID</b> : {{ $patient_data[0]->id }}</small>
                    </div>
                </div>
 -->
<!-- 
                <div class="mt-2">
                    <h6 class="mb-75">Birthday:</h5>
                        <p class="card-text">{{$birthday}}</p>
                </div>
                <div class="mt-2">
                    <h6 class="mb-75">Phone:</h5>
                        <p class="card-text">{{ $patient_data[0]->phone }}</p>
                </div>
                <div class="mt-2">
                    <h6 class="mb-75">Email:</h5>
                        <p class="card-text">{{ $patient_data[0]->email }}</p>
                </div>
                <div class="mt-2">
                    <h6 class="mb-50">Address:</h5>
                        <p class="card-text mb-0">{{ $patient_data[0]->address }}</p>
                </div> -->




            </div>
        </div>
        <!-- Overview -->
        <!-- suggestion pages -->
        <!-- <div class="card">
            <div class="card-body profile-suggestion">
                <h5 class="mb-2">Last booking</h5>
             
                <div class="d-flex justify-content-start align-items-center mb-1">
                    <div class="avatar mr-1">
                        <div class="avatar bg-light-primary avatar-lg">
                            <span class="avatar-content">DR</span>
                        </div>
                    </div>
                    <div class="profile-user-info">
                        <h6 class="mb-0">Peter Reed</h6>
                        <small class="text-muted">Company</small>
                    </div>
                </div>
            </div>
        </div> -->
        <!--/ suggestion pages -->

    </div>
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <!-- DETAILS -->
                <ul class="nav nav-tabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="procedures-tab" data-toggle="tab" href="#procedures"
                            aria-controls="home" role="tab" aria-selected="true"><i data-feather="home"></i>
                            Procedures</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="notes-tab" data-toggle="tab" href="#notes" aria-controls="profile"
                            role="tab" aria-selected="false"><i data-feather="tool"></i>
                            Notes</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="billing-tab" data-toggle="tab" href="#billing" aria-controls="about"
                            role="tab" aria-selected="false"><i data-feather="user"></i> Billing</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="storageIcon-tab" data-toggle="tab" href="#storageIcon"
                            aria-controls="storage" role="tab" aria-selected="false"><i data-feather="user"></i>
                            Storage</a>
                    </li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane active" id="procedures" aria-labelledby="procedures-tab" role="tabpanel">
                        <div class="row">
                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-xs-12 mb-2">
                                <div class="d-flex flex-row bd-highlight links flex-center">
                                    @foreach($services as $service)
                                    <div class="p-2 bd-highlight"><a href="#" class="btn btn-outline-warning"
                                            style="border-radius: 15px; border-color: #ffc107 !important;">{{ $service->name }}</a>
                                    </div>
                                    @endforeach
                                </div>
                                @if(count($services) == 0 )
                                <div class="links flex-center">
                                    <h5 class='text-success'>No services.</h5>
                                </div>
                                @endif
                            </div>
                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-xs-12 mb-2">
                                <!-- Procedures-->
                                @include('helpers/procedures')
                                <!-- Procedures -->
                            </div>
                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-xs-12 mb-2">
                                <!-- services-->
                                <div class="table-responsive">
                                    <table class="datatable table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Teeth ID</th>
                                                <th>Service Name</th>
                                                <th>Note</th>
                                                <th>Price</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($services as $service)
                                            <tr>
                                                <td><span>{{ $service->teeth_id }}</span></td>
                                                <td><span>{{ $service->name }}</span></td>
                                                <td><span>{{ $service->note }}</span></td>
                                                <td><span>{{ $service->price }}</span></td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <!-- services -->
                            </div>



                        </div>
                    </div>
                    <div class="tab-pane" id="notes" aria-labelledby="notes-tab" role="tabpanel">

                        <div class="row mb-2">
                            <div class="col-md-10"></div>
                            <div class="col-md-2 text-right">
                                <button onclick="_formNote({{ $patient_data[0]->id }},0)"
                                    class="btn btn-icon btn-primary"><i data-feather="plus"></i></button>
                                <!--  <a href="#" id="new_modal_btn" class="btn btn-icon btn-primary" data-toggle="modal" data-target="#modal_form_note"><i data-feather="plus"></i></a> -->
                            </div>
                        </div>

                        <!-- Notes -->
                        <div class="table-responsive">
                            <table id="notes_datatable" class="table">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Description</th>
                                        <th>Created</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- @foreach($notes as $note)
                                    <tr>
                                        <td><span>#NOTE-{{ $note->id }}</span></td>
                                        <td><span>{{ $note->note }}</span></td>
                                        <td>
                                            <p class='mb-0'><span class="badge badge-light-primary">Created at : {{ $note->created_at->format('Y/m/d h:i:s') }}</span></p>
                                            <p class='mb-0'><span class="badge badge-light-primary">By : {{ $note->user->name }}</span></p>
                                        </td>
                                        <td>
                                            <button type="button" onclick="_deleteNote({{ $note->id }})" class="btn btn-icon btn-outline-danger">{!!\App\Library\Helpers\Helper::getSvgIconeByAction('DELETE')!!}</button>
                                        </td>
                                    </tr>
                                    @endforeach -->
                                </tbody>
                            </table>
                        </div>
                        <!-- Notes -->
                    </div>
                    <div class="tab-pane" id="billing" aria-labelledby="billing-tab" role="tabpanel">
                        <p>Billing</p>
                    </div>
                    <div class="tab-pane" id="storageIcon" aria-labelledby="storageIcon-tab" role="tabpanel">
                        <p>Storage</p>
                    </div>
                </div>
            </div>
            <!-- DETAILS -->
        </div>
    </div>
</div>
</div>

<input type="hidden" id="INPUT_HIDDEN_EDIT_NOTE" value="Edit note">
<input type="hidden" id="INPUT_HIDDEN_NEW_NOTE" value="Add note">

<!-- Modal -->
<div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" id="modal_form_note">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="NOTE_MODAL_TITLE">Modal title</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body modal-body-lg">
                <form id="FORM_NOTE">
                    <div id='modal_form_note_body'>
                    </div>
                </form>
            </div><!-- .modal-body -->
            <div class="modal-footer">
                <button onclick="_submit_note_form()" class="btn btn-primary"><i
                        data-feather="save"></i>&nbsp;Save</button>
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
<script src="{{ asset('new-assets/js/recorder.js') }}"></script>
@endsection
@section('page-script')
<!-- <script src="{{ asset('new-assets/js/recorder-script.js') }}"></script> -->
<script src="{{ asset('new-assets/js/main.js') }}"></script>
<script>
$(document).ready(function() {
    var table = $('.datatable').DataTable();
});
$.ajaxSetup({
    headers: {
        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
    },
});

var dtUrl = '/profile/sdt/notes/{{ $patient_data[0]->id }}';
var notes_datatable = $('#notes_datatable');
notes_datatable.DataTable({
    responsive: true,
    processing: true,
    paging: true,
    ordering: false,
    ajax: {
        url: dtUrl,
        type: 'POST',
        data: {
            pagination: {
                perpage: 50,
            },
        },
    },
    lengthMenu: [5, 10, 25, 50],
    pageLength: 25,
});
var _reload_notes_datatable = function() {
    $('#notes_datatable').DataTable().ajax.reload();
}


function _formNote(patient_id, note_id) {
    var modal_id = "modal_form_note";
    var modal_content_id = "modal_form_note_body";
    var spinner =
        '<div class="modal-body"><center><div class="spinner-border text-primary text-center" role="status"><span class="sr-only">Loading...</span></div></center></div>';
    $("#" + modal_id).modal("show");
    $("#" + modal_content_id).html(spinner);
    var modalTitle = note_id > 0 ? $("#INPUT_HIDDEN_EDIT_NOTE").val() : $("#INPUT_HIDDEN_NEW_NOTE").val();
    $("#NOTE_MODAL_TITLE").html('{!!\App\Library\Helpers\Helper::getSvgIconeByAction('
        EDIT ')!!} ' + modalTitle);
    $.ajax({
        url: "/profile/form/note/" + patient_id + '/' + note_id,
        type: "GET",
        dataType: "html",
        success: function(html, status) {
            $("#" + modal_content_id).html(html);
        },
    });
};
$("#FORM_NOTE").submit(function(event) {
    event.preventDefault();
    var formData = $(this).serializeArray();
    $.ajax({
        type: "POST",
        dataType: 'json',
        data: formData,
        url: '/profile/form/note',
        success: function(response) {
            if (response.success) {
                $("#modal_form_note").modal('hide');
                _showResponseMessage("success", response.msg);
                _reload_notes_datatable();
            } else {
                _showResponseMessage("error", response.msg);
            }
        },
        error: function() {

        }
    }).done(function(data) {

    });
    return false;
});

function _submit_note_form() {
    $("#SUBMIT_NOTE_FORM").click();
}

function _deleteNote(id) {
    var successMsg = "Your note has been deleted.";
    var errorMsg = "Your note has not been deleted.";
    var swalConfirmTitle = "Are you sure you want to delete?";
    var swalConfirmText = "You can't go back!";
    Swal.fire({
        title: swalConfirmTitle,
        text: swalConfirmText,
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Yes, delete it!",
        customClass: {
            confirmButton: "btn btn-primary",
            cancelButton: "btn btn-outline-danger ml-1",
        },
        buttonsStyling: false,
    }).then(function(result) {
        if (result.value) {
            $.ajax({
                url: "/profile/delete/note/" + id,
                type: "DELETE",
                cache: false,
                data: {
                    _token: $('meta[name="csrf-token"]').attr("content")
                },
                dataType: "JSON",
                success: function(result, status) {
                    if (result.success) {
                        _showResponseMessage("success", successMsg);
                    } else {
                        _showResponseMessage("error", errorMsg);
                    }
                },
                error: function(result, status, error) {
                    _showResponseMessage("error", errorMsg);
                },
                complete: function(result, status) {
                    _reload_notes_datatable();
                },
            });
        }
    });
}
</script>
@endsection