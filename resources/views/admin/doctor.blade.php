@extends('layouts/layoutMaster')

@section('title', 'Doctor List')

@section('vendor-style')
<!-- vendor css files -->
<link rel="stylesheet" href="{{ asset('new-assets/app-assets/vendors/css/tables/datatable/datatables.min.css') }}">
<link rel="stylesheet"
    href="{{ asset('new-assets/app-assets/vendors/css/tables/datatable/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet"
    href="{{ asset('new-assets/app-assets/vendors/css/tables/datatable/responsive.bootstrap4.min.css') }}">
@endsection

@section('page-style')
{{-- Page Css files --}}

@endsection

@section('content')

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Doctor List</h4>
                <h5 class='text-success'>You have total {{ count($doctors) }} doctors.</h5>
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
                                <th>Target</th>
                                <th>
                                    Action
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($doctors as $doctor)
                            <tr>
                                <td><span>{{ $doctor->name }}</span></td>
                                <td><span>{{ $doctor->email }}</span></td>
                                <td><span>{{ $doctor->birthday }}</span></td>
                                <td><span>{{ $doctor->address }}</span></td>
                                <td><span>{{ $doctor->phone }}</span></td>
                                <td>
                                    @if($doctor->state == 1)
                                    <span class="tb-status text-success">Verified</span>
                                    @elseif($doctor->state == 0)
                                    <span class="tb-status text-warning">Pending</span>
                                    @elseif($doctor->state == 2)
                                    <span class="tb-status text-danger">Suspend</span>
                                    @endif
                                </td>
                                <td><span>{{ $doctor->target }}</span></td>
                                <td class="text-center">
                                    <a href="" data-toggle="modal" data-target="#set_target_modal" data-id="{{ json_encode($doctor) }}" title="Set Target" class="btn btn-icon btn-sm btn-outline-primary mr-1">{!!\App\Library\Helpers\Helper::getSvgIconeByAction('CREDIT-CARD')!!}</a>
                                    <a href="/report/pdf/doctor/daily/{{ $doctor->id }}/1" target="_blank" title="Download daily report" class="btn btn-icon btn-sm btn-outline-warning mr-1 mt-1">{!!\App\Library\Helpers\Helper::getSvgIconeByAction('DOWNLOAD')!!}</a>
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


<div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" id="set_target_modal">
    <div class="modal-dialog modal-lg" role="document">

        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="basicModalLabel">Set Target</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="s_doctor_id" name="s_doctor_id">
                <input type="hidden" id="s_user_id" name="s_user_id">
                <div class="row gy-4">

                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label" for="display-name">Email*</label>
                            <input type="text" id="s_email" name="s_email" class="form-control form-control-lg"
                                placeholder="Enter Email" readonly>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label" for="default-05">Target(USD)</label>
                            <div class="form-control-wrap">
                                <input type="number" min="1" max="9999999999" id="s_target" name="s_target"
                                    class="form-control form-control-lg">
                            </div>
                        </div>
                    </div>
                </div>
            </div><!-- .modal-body -->
            <div class="modal-footer">
                <button href="#" class="btn btn-primary" id="target_set_btn"><i data-feather="save"></i>&nbsp;Set</button>
                <button href="#" data-dismiss="modal" class="btn btn-danger"><i data-feather="x"></i>&nbsp;Cancel</button>
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
<!-- responsive -->
<script src="{{ asset('new-assets/app-assets/vendors/js/tables/datatable/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('new-assets/app-assets/vendors/js/tables/datatable/responsive.bootstrap4.min.js') }}"></script>
<script src="{{ asset('new-assets/app-assets/vendors/js/extensions/sweetalert2.all.min.js') }}"></script>
@endsection
@section('page-script')
<script src="{{ asset('new-assets/js/main.js') }}"></script>
<script>
function delete_func(val) {
    document.getElementById(val).submit();
}
$(document).ready(function() {
    var table = $('.datatable').DataTable({
        responsive: true,
    });
    /* $(".form_datetime").datetimepicker({
        format: "yyyy-mm-dd hh:ii",
        autoclose: true,
        todayBtn: true,
        startDate: "2020-01-01 10:00",
        minuteStep: 10
    }); */



    $('#set_target_modal').on('show.bs.modal', function(e) {
        var doctor_data = $(e.relatedTarget).data('id');
        $("#s_doctor_id").val(doctor_data['d_id']); //id
        $("#s_user_id").val(doctor_data['id']); //user_id
        $("#s_email").val(doctor_data['email']);
        $("#s_target").val(doctor_data['target']);
    });


    $("#target_set_btn").click(function(e) {
        e.preventDefault();
        var id = $("#s_doctor_id").val();
        var user_id = $("#s_user_id").val();
        var email = $("#s_email").val();
        var target = $("#s_target").val();

        if (target != "") {
            $.ajax({
                url: '{{ route("admin.doctor.settarget") }}',
                type: "POST",
                data: {
                    id: id,
                    user_id: user_id,
                    target: target,
                    _token: "{{ csrf_token() }}",
                },
                success: function(response) {
                    $("#set_target_modal").modal('hide');
                    _showResponseMessage("success", 'Success.');
                    setTimeout(function(){ window.location.href = '{{route("admin.doctor")}}'; }, 1500);
                },
            });
        }
    });


    $("#search_btn").click(function(e) {
        e.preventDefault();

        var start_time = $("#s_start_time").val();
        var finish_time = $("#s_finish_time").val();

        if (start_time != "" && finish_time != "") {
            $.ajax({
                url: '{{ route("admin.doctor.search") }}',
                type: "POST",
                data: {
                    start_time: start_time,
                    finish_time: finish_time,
                    _token: "{{ csrf_token() }}",
                },
                success: function(response) {

                },
            });
        }
    });



});
</script>
@endsection


