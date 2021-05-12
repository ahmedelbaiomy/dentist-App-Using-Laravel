@extends('layouts/layoutMaster')

@section('title', 'Patient Lists')

@section('vendor-style')
<!-- vendor css files -->
<link rel="stylesheet" href="{{ asset('new-assets/app-assets/vendors/css/tables/datatable/datatables.min.css') }}">
<link rel="stylesheet" href="{{ asset('new-assets/app-assets/vendors/css/tables/datatable/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('new-assets/app-assets/vendors/css/tables/datatable/responsive.bootstrap4.min.css') }}">
@endsection

@section('page-style')
{{-- Page Css files --}}

@endsection

@section('content')

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Patients List</h4>
                <div class="table-responsive">
                    <table class="datatable table table-bordered">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Birthday</th>
                                <th>Address</th>
                                <th>Phone</th>
                                <th>Status</th>
                                <th class="tb-tnx-action">
                                    Action
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($patients as $patient)
                            <tr>
                                
                                <td onClick="window.location.href = '/profile/patient/{{$patient->id}}'"><span>{{ $patient->name }}</span></td>
                                <td><span>{{ $patient->email }}</span></td>
                                <td><span>{{ $patient->birthday }}</span></td>
                                <td><span>{{ $patient->address }}</span></td>
                                <td><span>{{ $patient->phone }}</span></td>
                                <td>
                                    @if($patient->state == 0)
                                    <span class="tb-status text-success">Open</span>
                                    @elseif($patient->state == 1)
                                    <span class="tb-status text-warning">Complete</span>
                                    @endif
                                </td>

                                <td class="text-center">
                                    <div class="btn-group btn-group-sm">
                                        <button type="button" class="btn btn-icon btn-sm btn-outline-info mr-1" onClick="window.location.href = '/profile/patient/{{$patient->id}}'">
                                            {!!\App\Library\Helpers\Helper::getSvgIconeByAction('VIEW')!!}
                                        </button>
                                        <button type="button" data-toggle="modal"
                                            data-id="{{ $patient }}" class="btn btn-icon btn-sm btn-outline-primary mr-1">
                                            {!!\App\Library\Helpers\Helper::getSvgIconeByAction('EDIT')!!}
                                        </button>
                                        <button onclick="delete_func('delete_frm_{{ $patient->id }}')" type="button"
                                            class="btn btn-icon btn-sm btn-outline-danger mr-1">
                                            <form action="{{ route('reception.patient.destroy', $patient->id)}}"
                                                name="delete_frm_{{ $patient->id }}" id="delete_frm_{{ $patient->id }}"
                                                method="post">
                                                @csrf
                                                @method('DELETE')
                                                {!!\App\Library\Helpers\Helper::getSvgIconeByAction('DELETE')!!}
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


<div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" id="add_patient_modal">
    <div class="modal-dialog modal-lg" role="document">

        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="basicModalLabel">Add Patient</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row gy-4">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label" for="first-name">Full Name*</label>
                            <input type="text" id="a_name" name="a_name" class="form-control form-control-lg"
                                placeholder="Enter Full name" require>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label" for="display-name">Email*</label>
                            <input type="text" id="a_email" name="a_email" class="form-control form-control-lg"
                                placeholder="Enter Email">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label" for="birth-day">Date of Birth</label>
                            <input type="text" id="a_birthday" name="a_birthday" data-date-format="yyyy-mm-dd"
                                class="form-control datepicker" placeholder="Enter your birthday">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label" for="phone-no">Phone Number</label>
                            <input type="text" id="a_phone" name="a_phone" class="form-control form-control-lg"
                                placeholder="Phone Number">
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="form-label" for="address">Address</label>
                            <input type="text" id="a_address" name="a_address" class="form-control form-control-lg"
                                placeholder="Enter address">
                        </div>
                    </div>
                </div>
            </div><!-- .modal-body -->
            <div class="modal-footer">
                <button href="#" class="btn btn-primary" id="patient_save_btn"><span
                        class="icon-save2"></span>&nbsp;Save</button>
                <button href="#" data-dismiss="modal" class="btn btn-danger"><span
                        class="icon-close"></span>&nbsp;Cancel</button>
            </div>
        </div><!-- .modal-content -->
    </div><!-- .modal-dialog -->
</div><!-- .modal -->




<div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" id="edit_patient_modal">
    <div class="modal-dialog modal-lg" role="document">

        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="basicModalLabel">Edit Patient</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="e_patient_id" name="e_patient_id">
                <div class="row gy-4">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label" for="first-name">Full Name*</label>
                            <input type="text" id="e_name" name="e_name" class="form-control form-control-lg"
                                placeholder="Enter Full name">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label" for="display-name">Email*</label>
                            <input type="text" id="e_email" name="e_email" class="form-control form-control-lg"
                                placeholder="Enter Email">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label" for="birth-day">Date of Birth</label>
                            <input type="text" id="e_birthday" name="e_birthday" data-date-format="yyyy-mm-dd"
                                class="form-control form-control-lg datepicker" placeholder="Enter your birthday">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label" for="phone-no">Phone Number</label>
                            <input type="text" id="e_phone" name="e_phone" class="form-control form-control-lg"
                                placeholder="Phone Number">
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="form-label" for="address">Address</label>
                            <input type="text" id="e_address" name="e_address" class="form-control form-control-lg"
                                placeholder="Enter address">
                        </div>
                    </div>
                </div>
            </div><!-- .modal-body -->
            <div class="modal-footer">
                <button href="#" class="btn btn-primary" id="patient_update_btn"><span
                        class="icon-save2"></span>&nbsp;Update</button>
                <button href="#" data-dismiss="modal" class="btn btn-danger"><span
                        class="icon-close"></span>&nbsp;Cancel</button>
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
@endsection
@section('page-script')
<script>
function delete_func(val) {
    document.getElementById(val).submit();
}
$(document).ready(function() {
    var table = $('.datatable').DataTable();
    $(".datepicker").da

    $('.datepicker').pickadate({
        format: 'yyyy-mm-dd',
        hiddenName: true
    })
    $("#patient_save_btn").click(function(e) {
        e.preventDefault();
        var name = $("#a_name").val();
        var email = $("#a_email").val();
        var birthday = $("#a_birthday").val();
        var phone = $("#a_phone").val();
        var address = $("#a_address").val();
        if (name != "" && email != "") {
            $.ajax({
                url: '{{route("admin.patient.store")}}',
                type: "POST",
                data: {
                    name: name,
                    email: email,
                    birthday: birthday,
                    phone: phone,
                    address: address,
                    _token: "{{ csrf_token() }}",
                },
                success: function(response) {
                    $("#add_patient_modal").modal('hide');
                    NioApp.Toast('Success.', 'success');
                    toastr.clear();
                    window.location.href = '{{route("admin.patient")}}';
                },
            });
        }
    });



    $('#edit_patient_modal').on('show.bs.modal', function(e) {
        var patient_data = $(e.relatedTarget).data('id');
        $("#e_patient_id").val(patient_data['id']);
        $("#e_name").val(patient_data['name']);
        $("#e_email").val(patient_data['email']);
        $("#e_phone").val(patient_data['phone']);
        $("#e_birthday").val(patient_data['birthday']);
        $("#e_address").val(patient_data['address']);
    });


    $("#patient_update_btn").click(function(e) {
        e.preventDefault();
        var id = $("#e_patient_id").val();
        var name = $("#e_name").val();
        var email = $("#e_email").val();
        var birthday = $("#e_birthday").val();
        var phone = $("#e_phone").val();
        var address = $("#e_address").val();
        if (name != "" && email != "") {
            $.ajax({
                url: '{{route("admin.patient.store")}}',
                type: "PUT",
                data: {
                    id: id,
                    name: name,
                    email: email,
                    birthday: birthday,
                    phone: phone,
                    address: address,
                    _token: "{{ csrf_token() }}",
                },
                success: function(response) {
                    $("#edit_patient_modal").modal('hide');
                    NioApp.Toast('Success.', 'success');
                    toastr.clear();
                    window.location.href = '{{route("admin.patient")}}';
                },
            });
        }
    });


});
</script>
@endsection



