@extends('layouts/layoutMaster')

@section('title', 'Patient profile')

@section('vendor-style')
<link rel="stylesheet" href="{{ asset('new-assets/app-assets/css/plugins/forms/form-validation.css') }}">
<!-- <link rel="stylesheet" href="{{ asset('new-assets/app-assets/vendors/css/tables/datatable/datatables.min.css') }}"> -->
<link rel="stylesheet"
    href="{{ asset('new-assets/app-assets/vendors/css/tables/datatable/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet"
    href="{{ asset('new-assets/app-assets/vendors/css/tables/datatable/responsive.bootstrap4.min.css') }}">

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.css"
    integrity="sha512-H9jrZiiopUdsLpg94A333EfumgUBpO9MdbxStdeITo+KEIMaNfHNvwyjjDJb+ERPaRS6DpyRlKbvPUasNItRyw=="
    crossorigin="anonymous" />
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
                    <div class="col-xl-12 col-lg-12 d-flex flex-column justify-content-between border-container-lg">
                        <!-- <div class="user-avatar-section"> -->
                        <div class="d-flex justify-content-start">
                            <!-- avatar -->
                            <div class="demo-inline-spacing">
                                <div class="avatar mr-1 mt-0">
                                    <div class="avatar bg-light-primary avatar-lg">
                                        <span class="avatar-content">{{ $short_name }}</span>
                                    </div>
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
                        <!-- </div> -->


                        <ul class="list-inline mt-2">
                            <li class="list-inline-item"><i data-feather="calendar" class=""></i> Birthday :
                                <strong>{{$birthday}}</strong>
                            </li>
                            <li class="list-inline-item"><i data-feather="phone" class=""></i> Phone :
                                <strong>{{ $patient_data[0]->phone }}</strong>
                            </li>
                            <li class="list-inline-item"><i data-feather="map" class=""></i> Address :
                                <strong>{{ $patient_data[0]->address }}</strong>
                            </li>
                        </ul>

                    </div>
                    <!-- <div class="col-xl-6 col-lg-12 mt-2 mt-xl-0">
                        <p><i data-feather="calendar" class="mr-1"></i> Birthday : {{$birthday}}</p>
                        <p><i data-feather="phone" class="mr-1"></i> Phone : {{ $patient_data[0]->phone }}</p>
                        <p><i data-feather="map" class="mr-1"></i> Address : {{ $patient_data[0]->address }}</p>
                    </div> -->
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
                            aria-controls="home" role="tab" aria-selected="true"><i data-feather="grid"></i>
                            Procedures</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="notes-tab" data-toggle="tab" href="#notes" aria-controls="profile"
                            role="tab" aria-selected="false"><i data-feather="mic"></i>
                            Notes</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="billing-tab" data-toggle="tab" href="#billing" aria-controls="about"
                            role="tab" aria-selected="false"><i data-feather="file-text"></i> Billing</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="storageIcon-tab" data-toggle="tab" href="#storageIcon"
                            aria-controls="storage" role="tab" aria-selected="false"><i data-feather="archive"></i>
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
                            <table id="notes_datatable" class="datatables-ajax table">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Description</th>
                                        <th>Attachment</th>
                                        <th>Created</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                        <!-- Notes -->
                    </div>
                    <div class="tab-pane" id="billing" aria-labelledby="billing-tab" role="tabpanel">
                        <!-- <p>Billing</p> -->


                        <div class="table-responsive">
                            <a style="float: right;color: #fff;"
                                onclick="document.getElementById('addbillingform1').submit();"
                                class="btn btn-icon btn-primary mb-1">
                                <i data-feather="plus"></i>
                            </a>
                            <table id="mytable1" class="datatable table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Teeth ID</th>
                                        <th>Doctor</th>
                                        <th>Service Name</th>
                                        <th>Note</th>
                                        <th>Price</th>
                                        <th>Type</th>
                                        <th class="tb-tnx-action">
                                            Action
                                        </th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <form id="addbillingform1" action="{{ route('reception.patient.BillingStore') }}"
                                        method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <input type="hidden" name="patient_id" value="{{ $patient_id }}">
                                        @foreach($services as $service)
                                        <?php $doctorinfo = DB::table('users')->where('id',$service->doctor_id)->first(); ?>
                                        <tr>
                                            <td><input type="hidden" name="teeth_id[]"
                                                    value="{{ $service->teeth_id }}"><span>{{ $service->teeth_id }}</span>
                                            </td>
                                            <td><span>{{$doctorinfo ? $doctorinfo->name : 'Doctor'  }}</span></td>
                                            <td><input type="hidden" name="service[]"
                                                    value="{{ $service->name }}"><span>{{ $service->name }}</span></td>
                                            <td><span>{{ $service->note }}</span></td>
                                            <td><input type="hidden" name="amount[]"
                                                    value="{{ $service->price }}"><span>{{ $service->price }}</span>
                                            </td>
                                            <td><span>{{ $service->type }}</span></td>
                                            <td class="text-center">
                                                @if($service->type != "completed")
                                                <div class="btn-group btn-group-sm">
                                                    <button type="button" data-toggle="modal"
                                                        data-target="#view_notemodal"
                                                        data-id="{{ json_encode($service) }}" class="btn btn-info">
                                                        <i class="icon-edit1"></i>
                                                    </button>
                                                </div>
                                                @endif
                                            </td>
                                            <td>
                                                @if($service->invoiced == 0)
                                                <input type="checkbox" name="servic_ids[]" value="{{$service->id}}">
                                                @else
                                                <p>invoiced</p>
                                                @endif
                                            </td>
                                        </tr>
                                        @endforeach
                                    </form>
                                </tbody>
                            </table>
                        </div>

                        <div class="table-responsive">
                            <table class="datatable table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Code</th>
                                        <th>From</th>
                                        <th>To</th>
                                        <th>Amount</th>
                                        <th>Paid</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($invoices as $invoice)
                                    <?php 
                                                                $invoicedetails = DB::table('invoice_lists')->where('invoice_id',$invoice->id)->get(); 
                                                                $total += $invoice->amount;
                                                            ?>
                                    <tr>
                                        <td><span>{{ $invoice->code }}</span></td>
                                        <td><span>{{ $invoice->d_email }}</span></td>
                                        <td><span>{{ $invoice->p_email }}</span></td>
                                        <td><span>{{ $invoice->amount }}</span></td>
                                        <td>
                                            @if($invoice->paid == 0)
                                            <?php $dept += $invoice->amount; ?>
                                            <form action="{{route('reception.patient.BillingPaid')}}" method="post">
                                                @csrf
                                                <input type="hidden" name="invoiceid" value="{{$invoice->id}}">
                                                <button type="submit" class="btn btn-primary">Paid</button>
                                            </form>
                                            @else
                                            <?php $paid += $invoice->amount; ?>
                                            <p>Paid</p>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                <a href="{{route('reception.invoice.generatepdf',$invoice->id)}}"
                                                    class="btn btn-info">
                                                    PDF
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <div class="modal" id="show_invoice_modal{{$invoice->id}}">
                                            <div class="modal-dialog">
                                                <div class="modal-content">

                                                    <!-- Modal Header -->
                                                    <div class="modal-header">
                                                        <h4 class="modal-title">Invoice Details</h4>
                                                        <button type="button" class="close"
                                                            data-dismiss="modal">&times;</button>
                                                    </div>

                                                    <!-- Modal body -->
                                                    <div class="modal-body">

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <div class="row mt-2 mb-2">
                                    <div class="col-md-4">
                                        <h4> Total : {{$total}}</h4>
                                    </div>
                                    <div class="col-md-4">
                                        <h4> Paid : {{$paid}}</h4>
                                    </div>
                                    <div class="col-md-4">
                                        <h4> Residual : {{$dept}}</h4>
                                    </div>
                                </div>
                            </table>
                        </div>
                    </div>
                    <div class="tab-pane" id="storageIcon" aria-labelledby="storageIcon-tab" role="tabpanel">
                        <!-- <p>Storage</p> -->
                        <div class="row mb-2">
                            <div class="col-md-10">
                            </div>
                            <div class="col-md-2 text-right">
                                <button class="btn btn-icon btn-primary" data-toggle="modal"
                                    data-target="#modal_form_storage"><i data-feather="plus"></i></button>

                                <!-- <button class="btn btn-icon btn-primary"><i data-feather="plus"></i></button> -->

                            </div>
                        </div>

                        <div class="row">
                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table id="storage_datatable" class="table">
                                                <thead>
                                                    <tr>
                                                        <th>ID</th>
                                                        <th>Title</th>
                                                        <th>Description</th>
                                                        <th>Attachment</th>
                                                        <th>Created</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>


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
                <button type="button" onclick="_reset_note_form()" class="close" data-dismiss="modal"
                    aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body modal-body-lg">
                <form id="FORM_NOTE" enctype="multipart/form-data">
                    <div id='modal_form_note_body'>
                    </div>
                </form>
            </div><!-- .modal-body -->
            <div class="modal-footer">
                <button onclick="_submit_note_form()" class="btn btn-primary"><i data-feather="save"></i>&nbsp;Save
                    <span id="SPAN_SAVE"></span> </button>
                <button onclick="_reset_note_form()" data-dismiss="modal" class="btn btn-danger"><i
                        data-feather="x"></i>&nbsp;Cancel</button>
            </div>
        </div><!-- .modal-content -->
    </div><!-- .modal-dialog -->
</div><!-- .modal -->


<div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" id="modal_form_storage">
    <div class="modal-dialog modal-lg" role="document">

        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="STORAGE_MODAL_TITLE">Add file</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">


                <!-- <form id="FORM_STORAGE" action="{{ route('file.upload.post') }}" method="POST" enctype="multipart/form-data"> -->
                <form id="FORM_STORAGE" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" id="patient_id" name="patient_id" value="{{$patient_id}}">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="form-label" for="birth-day">Title</label>
                                <input class="form-control form-control-sm" id="title" name="title" type="text"
                                    required>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="form-label" for="cf-default-textarea">Description</label>
                                <div class="form-control-wrap">
                                    <textarea class="form-control form-control-sm" cols="30" rows="5" id="description"
                                        name="description" placeholder="Write your description" required></textarea>
                                </div>
                            </div>
                        </div>
                        <!-- WEBCAM JS -->
                        <div class="col-md-12">

                            <button type="button" onclick="show_upload_file();" class="btn btn-outline-primary"><i data-feather="upload"></i>&nbsp;Upload file</button>
                            <button type="button" onclick="setup_camera(); $(this).hide().next().show();" class="btn btn-outline-primary"><i data-feather="camera"></i>&nbsp;Access Camera</button>
                            <button type="button" onclick="take_photo()" class="btn btn-outline-primary" style="display:none"><i data-feather="camera"></i> Take photos</button>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <input type="hidden" id="data_uri_hidden">
                                <div id="BLOCK_CAMERA"></div>
                            </div>
                            <div class="col-md-6">
                                <div class="card" id="results"></div>
                            </div>
                        </div>
                        <!-- WEBCAM JS -->
                        <!-- INPUT FILE -->
                        <div class="col-md-12">
                            <div class="form-group" id="BLOCK_UPLOAD_FILE">
                                <!-- <input type="file" class="form-control-file" name="file" id="file" required /> -->
                            </div>
                        </div>
                        <!-- INPUT FILE -->

                    </div>
                </form>


            </div><!-- .modal-body -->

            <div class="modal-footer">
                <button onclick="$('#FORM_STORAGE').submit()" class="btn btn-primary"><i
                        data-feather="save"></i>&nbsp;Save
                    <span id="SPAN_SAVE_STORAGE"></span> </button>
                <button data-dismiss="modal" class="btn btn-danger"><i data-feather="x"></i>&nbsp;Cancel</button>
            </div>

        </div><!-- .modal-content -->
    </div><!-- .modal-dialog -->
</div><!-- .modal -->

@endsection

@section('vendor-script')
<script src="{{ asset('new-assets/app-assets/vendors/js/forms/validation/jquery.validate.min.js') }}"></script>
<script src="{{ asset('new-assets/app-assets/vendors/js/tables/datatable/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('new-assets/app-assets/vendors/js/tables/datatable/datatables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('new-assets/app-assets/vendors/js/tables/datatable/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('new-assets/app-assets/vendors/js/tables/datatable/responsive.bootstrap4.js') }}"></script>


<script src="{{ asset('new-assets/app-assets/vendors/js/extensions/sweetalert2.all.min.js') }}"></script>
<script src="{{ asset('new-assets/js/recorder.js') }}"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.js"
    integrity="sha512-uURl+ZXMBrF4AwGaWmEetzrd+J5/8NRkWAvJx5sbPSSuOb0bZLqf+tOzniObO00BjHa/dD7gub9oCGMLPQHtQA=="
    crossorigin="anonymous"></script>
<script type="text/javascript" src="{{ asset('new-assets/js/webcam/webcam.min.js') }}"></script>
@endsection
@section('page-script')
<!-- <script src="{{ asset('new-assets/js/recorder-script.js') }}"></script> -->
<script src="{{ asset('new-assets/js/main.js') }}"></script>
<script>

let constraints = {video: {width: 9999}};
    video.srcObject = await navigator.mediaDevices.getUserMedia(constraints);
    let track = video.srcObject.getTracks()[0];
    if (track.getSettings) {
      let {width, height} = track.getSettings();
      console.log(`${width}x${height}`);
    }

/* l(async () => {
  try {
    let constraints = {video: {width: 9999}};
    video.srcObject = await navigator.mediaDevices.getUserMedia(constraints);
    let track = video.srcObject.getTracks()[0];
    if (track.getSettings) {
      let {width, height} = track.getSettings();
      console.log(`${width}x${height}`);
    }
    await new Promise(resolve => video.onloadedmetadata = resolve);
    console.log(`${video.videoWidth}x${video.videoHeight}`);
  } catch(e) {
    console.log(e);
  }
})(); */

Webcam.set({
    width: 230,
    height: 173,
    image_format: 'jpeg',
    jpeg_quality: 90
});
function setup_camera() {
    $('#data_uri_hidden').val('');
	Webcam.reset();
	Webcam.attach( '#BLOCK_CAMERA' );
    hide_upload_file();
}

function take_photo() {
    // take snapshot and get image data
    Webcam.snap(function(data_uri) {
        // display results in page
        $('#results').html('<img class="img-fluid" src="' + data_uri + '"/>');
        $('#data_uri_hidden').val(data_uri);
    });
}
function show_upload_file(){
    Webcam.reset( '#BLOCK_CAMERA' );
    $("#BLOCK_CAMERA").removeAttr("style");
    $('#data_uri_hidden').val('');
    $('#BLOCK_UPLOAD_FILE').html('<input type="file" class="form-control-file" name="file" id="file" required />');
}
function hide_upload_file(){
    $('#BLOCK_UPLOAD_FILE').html('');
}

$(document).ready(function() {
    //var table = $('.datatable').DataTable();
    //$("a.fancybox-file").fancybox();
});
$.ajaxSetup({
    headers: {
        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
    },
});
/* nOTES */
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

/* storageS */
var dtStorageUrl = '/profile/sdt/storages/{{ $patient_data[0]->id }}';
var storage_datatable = $('#storage_datatable');
storage_datatable.DataTable({
    responsive: true,
    processing: true,
    paging: true,
    ordering: false,
    ajax: {
        url: dtStorageUrl,
        type: 'POST',
        data: {
            pagination: {
                perpage: 50,
            },
        },
    },
    lengthMenu: [5, 10, 25, 50],
    pageLength: 25,
    drawCallback: function(settings) {
        $("a.fancybox-file").fancybox();
    },
});
var _reload_storages_datatable = function() {
    $('#storage_datatable').DataTable().ajax.reload();
}


function _formNote(patient_id, note_id) {
    var modal_id = "modal_form_note";
    var modal_content_id = "modal_form_note_body";
    var spinner =
        '<div class="modal-body"><center><div class="spinner-border text-primary text-center" role="status"><span class="sr-only">Loading...</span></div></center></div>';
    $("#" + modal_id).modal("show");
    $("#" + modal_content_id).html(spinner);
    var modalTitle = note_id > 0 ? $("#INPUT_HIDDEN_EDIT_NOTE").val() : $("#INPUT_HIDDEN_NEW_NOTE").val();
    $("#NOTE_MODAL_TITLE").html('{!!\App\Library\Helpers\Helper::getSvgIconeByAction('EDIT ')!!} ' + modalTitle);
    $.ajax({
        url: "/profile/form/note/" + patient_id + '/' + note_id,
        type: "GET",
        dataType: "html",
        success: function(html, status) {
            $("#" + modal_content_id).html(html);
        },
    });
};

function _reset_note_form() {
    console.log('_reset_note_form');
    var modal_content_id = "modal_form_note_body";
    stopRecording();
    $("#" + modal_content_id).html('<p></p>');
}

$("#FORM_NOTE").validate({
    rules: {},
    messages: {},
    submitHandler: function(form) {
        var formDataToUpload = new FormData(form);
        $("#SPAN_SAVE").addClass("spinner-border spinner-border-sm");
        var fileUrl = $('#BLOB_FILE').val();
        if (fileUrl != '') {
            var block = fileUrl.split(";");
            // Get the content type of the image
            var contentType = block[0].split(":")[1]; // In this case "image/gif"
            // get the real base64 content of the file
            var realData = block[1].split(",")[1]; // In this case "R0lGODlhPQBEAPeoAJosM...."
            // Convert it to a blob to upload
            var blob = b64toBlob(realData, contentType);
            var filename = Math.floor(Date.now() / 1000);
            formDataToUpload.append("audio_data", blob, filename + ".wav");
        }
        $.ajax({
            type: "POST",
            dataType: 'json',
            data: formDataToUpload,
            url: '/profile/form/note',
            cache: false,
            contentType: false,
            processData: false,
            success: function(response) {
                if (response.success) {
                    $("#modal_form_note").modal('hide');
                    _showResponseMessage("success", response.msg);
                    _reload_notes_datatable();
                    $("#SPAN_SAVE").removeClass("spinner-border spinner-border-sm");
                } else {
                    _showResponseMessage("error", response.msg);
                }
            },
            error: function() {}
        }).done(function(data) {});
        return false;
    },
});

function _submit_note_form() {
    var fileUrl = $('#BLOB_FILE').val();
    if (fileUrl != '') {
        $("#SUBMIT_NOTE_FORM").click();
    } else {
        $.ajax({
            url: stopRecording(),
            success: function() {
                $("#SUBMIT_NOTE_FORM").click();
            }
        });
    }
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

function b64toBlob(b64Data, contentType, sliceSize) {
    contentType = contentType || '';
    sliceSize = sliceSize || 512;

    var byteCharacters = atob(b64Data);
    var byteArrays = [];

    for (var offset = 0; offset < byteCharacters.length; offset += sliceSize) {
        var slice = byteCharacters.slice(offset, offset + sliceSize);

        var byteNumbers = new Array(slice.length);
        for (var i = 0; i < slice.length; i++) {
            byteNumbers[i] = slice.charCodeAt(i);
        }

        var byteArray = new Uint8Array(byteNumbers);

        byteArrays.push(byteArray);
    }

    var blob = new Blob(byteArrays, {
        type: contentType
    });
    return blob;
}

/* Storage form */
$("#FORM_STORAGE").validate({
    rules: {},
    messages: {},
    submitHandler: function(form) {

        $("#SPAN_SAVE_STORAGE").addClass("spinner-border spinner-border-sm");
        var fileUrl = $('#data_uri_hidden').val();
        if (fileUrl != '') {
            var formData = new FormData(form);
            var block = fileUrl.split(";");
            // Get the content type of the image
            var contentType = block[0].split(":")[1]; // In this case "image/gif"
            // get the real base64 content of the file
            var realData = block[1].split(",")[1]; // In this case "R0lGODlhPQBEAPeoAJosM...."
            // Convert it to a blob to upload
            var blob = b64toBlob(realData, contentType);
            var filename = Math.floor(Date.now() / 1000);
            formData.append("file", blob, filename + ".jpeg");
        }else{
            var formData = new FormData($(form)[0]);
        }
        $.ajax({
            type: "POST",
            dataType: 'json',
            data: formData,
            url: '/profile/form/storage',
            cache: false,
            contentType: false,
            processData: false,
            success: function(response) {
                if (response.success) {
                    $("#modal_form_storage").modal('hide');
                    _showResponseMessage("success", response.msg);
                    _reload_storages_datatable();
                    $("#SPAN_SAVE_STORAGE").removeClass("spinner-border spinner-border-sm");
                } else {
                    _showResponseMessage("error", response.msg);
                }
            },
            error: function() {}
        }).done(function(data) {});
        return false;
    },
});

function _deletePatientStorage(id) {
    var successMsg = "Your file has been deleted.";
    var errorMsg = "Your file has not been deleted.";
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
                url: "/profile/delete/storage/" + id,
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
                    _reload_storages_datatable();
                },
            });
        }
    });
}
</script>
@endsection