@extends('layouts/layoutMaster')

@section('title', 'Reports')

@section('vendor-style')
<!-- vendor css files -->
<link rel="stylesheet" href="{{ asset('new-assets/app-assets/vendors/css/charts/apexcharts.css') }}">
<link rel="stylesheet" href="{{ asset('new-assets/app-assets/vendors/css/pickers/flatpickr/flatpickr.min.css') }}">
<link href="{{ asset('new-assets/js/select2/select2.min.css') }}" rel="stylesheet" />
@endsection

@section('page-style')
{{-- Page Css files --}}
<link rel="stylesheet" href="{{ asset('new-assets/app-assets/css/plugins/charts/chart-apex.css') }}">
@endsection

@section('content')

@php
$dtNow = Carbon\Carbon::now();
@endphp

<!-- FILTER FORM -->
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">{{ __('locale.reports') }}</h4>
                <!-- begin custom range -->
                <div class="row">
                    <div class="col-md-4">
                        <form id="formFilterStats">
                            <input type="hidden" value="" name="quick_type" id="INPUT_QUICK">
                            <div class="input-group mb-1">
                                <input type="text" class="form-control  flatpickr-range" id="custom-range"
                                    name="filter_range" placeholder="" aria-describedby="button-filter" />
                                <div class="input-group-append" id="button-filter">
                                    <button class="btn btn-outline-primary " type="button" onclick="range_filters()"><i
                                            data-feather="search"></i> {{ __('locale.search') }}</button>
                                </div>
                            </div>
                        </form>
                        <div class="spinner-border text-primary d-none" id="SPINNER">
                            <span class="sr-only">Loading...</span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <select class="form-control form-control-sm js-select2" id="select_doctors"
                                name="doctor_id">
                            </select>
                            <small>{{ __('locale.select_doctor') }}</small>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="btn-group float-right" role="group" aria-label="Basic example">
                            <button type="button" class="btn btn-outline-primary "
                                onclick="quick_filters('reset')">{{ __('locale.reset') }}</button>
                            <button class="btn btn-outline-primary  dropdown-toggle" type="button"
                                id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true"
                                aria-expanded="false">
                                Today : {{$dtNow->format('F d')}}
                            </button>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                <a class="dropdown-item" href="javascript:void(0);"
                                    onclick="quick_filters('today')">{{ __('locale.today') }}</a>
                                <a class="dropdown-item" href="javascript:void(0);"
                                    onclick="quick_filters('yesterday')">{{ __('locale.yesterday') }}</a>
                                <a class="dropdown-item" href="javascript:void(0);"
                                    onclick="quick_filters('last_7_days')">{{ __('locale.last_7_days') }}</a>
                                <a class="dropdown-item" href="javascript:void(0);"
                                    onclick="quick_filters('last_30_days')">{{ __('locale.last_30_days') }}</a>
                                <a class="dropdown-item" href="javascript:void(0);"
                                    onclick="quick_filters('this_month')">{{ __('locale.this_month') }}</a>
                                <a class="dropdown-item" href="javascript:void(0);"
                                    onclick="quick_filters('last_month')">{{ __('locale.last_month') }}</a>
                                <!-- <a class="dropdown-item" href="javascript:void(0);"
                                    onclick="quick_filters('this_year')">This year</a> -->
                            </div>

                        </div>
                    </div>

                </div>
                <!-- endcustom range -->

                <div class="row">
                    <div class="col-md-6">
                        <p class="font-weight-bolder">{{ __('locale.selected_filter_date') }} : <span class="badge badge-light-primary" id="current_filter_date"></span></p>
                    </div>

                    <div class="col-md-8">
{{-- Download report pdf --}}
                        <a style="float:left;" target="_blank" href="{{route('admin.report.doctor',['doctor_id'=>0,'mode'=>1])}}"  id="report_service" title="daily report all doctors" class="btn btn-icon btn-outline-warning ml-1"><i data-feather="download"></i> {{ __('locale.Download_Report_PDF') }}</a>
                    </div>
                </div>




            </div>
        </div>
    </div>

</div>
<!-- FILTER FORM -->


<!-- Stats Vertical Card -->
<div class="row">
    <div class="col-xl-2 col-md-4 col-sm-6">
        <div class="card text-center">
            <div class="card-body">
                <h4 class="font-weight-bolder" id="payments">0</h4>
                <p class="card-text">{{ __('locale.payments') }}</p>
            </div>
        </div>
    </div>
    <div class="col-xl-2 col-md-4 col-sm-6">
        <div class="card text-center">
            <div class="card-body">
                <h4 class="font-weight-bolder" id="appointments">0</h4>
                <p class="card-text">{{ __('locale.appointments') }}</p>
            </div>
        </div>
    </div>
    <div class="col-xl-2 col-md-4 col-sm-6">
        <div class="card text-center">
            <div class="card-body">
                <h4 class="font-weight-bolder" id="procedures">0</h4>
                <p class="card-text">{{ __('locale.procedures') }}</p>
            </div>
        </div>
    </div>
    <div class="col-xl-2 col-md-4 col-sm-6">
        <div class="card text-center">
            <div class="card-body">
                <h4 class="font-weight-bolder" id="invoices">0</h4>
                <p class="card-text">{{ __('locale.invoices') }}</p>
            </div>
        </div>
    </div>
    <div class="col-xl-2 col-md-4 col-sm-6">
        <div class="card text-center">
            <div class="card-body">
                <h4 class="font-weight-bolder" id="bookings">0</h4>
                <p class="card-text">{{ __('locale.bookings') }}</p>
            </div>
        </div>
    </div>
    <div class="col-xl-2 col-md-4 col-sm-6">
        <div class="card text-center">
            <div class="card-body">
                <h4 class="font-weight-bolder" id="patients">0</h4>
                <p class="card-text">{{ __('locale.patients') }}</p>
            </div>
        </div>
    </div>
</div>
<!--/ Stats Vertical Card -->


<!-- Row finances Chart Starts -->
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div
                class="card-header d-flex flex-md-row flex-column justify-content-md-between justify-content-start align-items-md-center align-items-start">
                <div>
                    <h4 class="card-title mb-1">{{ __('locale.finances') }}</h4>
                    <span class="cursor-pointer mr-1">
                        <!-- <span class="bullet bullet-primary align-middle bullet-sm">&nbsp;</span> -->
                        <span class="align-middle cursor-pointer" onclick="_loadFinancialsDataChart(0);">{{ __('locale.all') }}</span>
                    </span>
                    <span class="cursor-pointer mr-1">
                        <span class="bullet bullet-primary align-middle bullet-sm">&nbsp;</span>
                        <span class="align-middle cursor-pointer"
                            onclick="_loadFinancialsDataChart(1);">{{ __('locale.production') }}</span>
                    </span>
                    <span class="cursor-pointer mr-1">
                        <span class="bullet bullet-sm align-middle" style="background-color: #28c76f">&nbsp;</span>
                        <span class="align-middle cursor-pointer"
                            onclick="_loadFinancialsDataChart(2);">{{ __('locale.collection') }}</span>
                    </span>
                </div>
            </div>
            <div class="card-body">
                <div id="finances-column-chart"></div>
                <!-- finance stats -->
                <div class="row">

                    <div class="col-md-2">
                        <div class="card text-center">
                            <div class="card-body">
                                <h4 class="font-weight-bolder" id="chart_stats_production">0</h4>
                                <p class="card-text">{{ __('locale.production') }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="card text-center">
                            <div class="card-body">
                                <h4 class="font-weight-bolder" id="chart_stats_collection">0</h4>
                                <p class="card-text">{{ __('locale.collection') }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="card text-center">
                            <div class="card-body">
                                <h4 class="font-weight-bolder" id="chart_stats_remaining">0</h4>
                                <p class="card-text">{{ __('locale.remaining') }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="card text-center">
                            <div class="card-body">
                                <h4 class="font-weight-bolder" id="chart_stats_cash">0</h4>
                                <p class="card-text">{{ __('locale.cash') }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="card text-center">
                            <div class="card-body">
                                <h4 class="font-weight-bolder" id="chart_stats_mada">0</h4>
                                <p class="card-text">{{ __('locale.mada') }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="card text-center">
                            <div class="card-body">
                                <h4 class="font-weight-bolder" id="chart_stats_credit">0</h4>
                                <p class="card-text">{{ __('locale.credit') }}</p>
                            </div>
                        </div>
                    </div>

                    {{-- <div class="col-md-3">
                        <div class="card text-center">
                            <div class="card-body">
                                <h4 class="font-weight-bolder" id="chart_stats_discounts">0</h4>
                                <p class="card-text">{{ __('locale.discounts') }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="card text-center">
                            <div class="card-body">
                                <h4 class="font-weight-bolder" id="chart_stats_taxes">0</h4>
                                <p class="card-text">{{ __('locale.taxes') }}</p>
                            </div>
                        </div>
                    </div> --}}

                </div>

            </div>
        </div>
    </div>
</div>
<!-- Row finances Chart Starts -->

{{-- #####################################################################################################################3 --}}
<div class="table-responsive">
    <table class="datatable table table-bordered">
        <thead>
            <tr>
                <th>{{ __('locale.name') }}</th>
                <th>{{ __('locale.birthday') }}</th>
                <th>{{ __('locale.address') }}</th>
                <th>{{ __('locale.phone') }}</th>
                <th>{{ __('locale.status') }}</th>
                <th>{{ __('locale.target') }} ({{__('locale.'.env('CURRENCY_SYMBOL')) }})</th>
                <th>
                {{ __('locale.actions') }}
                </th>
            </tr>
        </thead>
        <tbody>
            {{-- @foreach($doctors as $doctor)
            <tr>
                <td><span>{{ $doctor->name }}</span></td>
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
                    <div class="btn-group" role="group">
                        <a href="" data-toggle="modal" data-target="#set_target_modal" data-id="{{ json_encode($doctor) }}" title="Set Target" class="btn btn-icon btn-sm btn-outline-primary">{!!\App\Library\Helpers\Helper::getSvgIconeByAction('CREDIT-CARD')!!}</a>
                        <a href="/report/pdf/doctor/daily/{{ $doctor->id }}/1" target="_blank" title="Download daily report" class="btn btn-icon btn-sm btn-outline-warning">{!!\App\Library\Helpers\Helper::getSvgIconeByAction('DOWNLOAD')!!}</a>
                        <button class="btn btn-icon btn-sm btn-outline-primary" onclick="_formDoctor({{ $doctor->id }})" title="Edit">{!!\App\Library\Helpers\Helper::getSvgIconeByAction('EDIT')!!}</button>
                    </div>
                </td>
            </tr>
            @endforeach --}}
        </tbody>
    </table>
</div>



{{--
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
                            <label class="form-label" for="default-05">Target({{env('CURRENCY_SYMBOL')}})</label>
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


<x-modal-form id="modal_form_doctor" formName="DOCTOR" content="modal_form_doctor_content" /> --}}

{{-- ##################################################################################################################### --}}
<!-- Row appointments Chart Starts -->
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div
                class="card-header d-flex flex-md-row flex-column justify-content-md-between justify-content-start align-items-md-center align-items-start">
                <div>
                    <h4 class="card-title mb-1">{{ __('locale.appointments') }}</h4>
                    <span class="cursor-pointer mr-1">
                        <!-- <span class="bullet bullet-primary align-middle bullet-sm">&nbsp;</span> -->
                        <span class="align-middle cursor-pointer" onclick="_loadAppointmentsDataChart(0);">{{ __('locale.all') }}</span>
                    </span>
                    <span class="cursor-pointer mr-1">
                        <span class="bullet align-middle bullet-sm" style="background-color: #7367f0">&nbsp;</span>
                        <span class="align-middle cursor-pointer"
                            onclick="_loadAppointmentsDataChart(1);">{{ __('locale.booked') }}</span>
                    </span>
                    <span class="cursor-pointer mr-1">
                        <span class="bullet bullet-sm align-middle" style="background-color: #00cfe8">&nbsp;</span>
                        <span class="align-middle cursor-pointer"
                            onclick="_loadAppointmentsDataChart(2);">{{ __('locale.confirmed') }}</span>
                    </span>
                    <span class="cursor-pointer mr-1">
                        <span class="bullet bullet-sm align-middle" style="background-color: #ea5455">&nbsp;</span>
                        <span class="align-middle cursor-pointer"
                            onclick="_loadAppointmentsDataChart(3);">{{ __('locale.canceled') }}</span>
                    </span>
                    <span class="cursor-pointer mr-1">
                        <span class="bullet bullet-sm align-middle" style="background-color: #ff9f43">&nbsp;</span>
                        <span class="align-middle cursor-pointer"
                            onclick="_loadAppointmentsDataChart(4);">{{ __('locale.attended') }}</span>
                    </span>
                </div>
            </div>
            <div class="card-body">
                <div id="appointments-column-chart"></div>
                <!-- finance stats -->
                <div class="row">
                    <div class="col-md-3">
                        <div class="card text-center">
                            <div class="card-body">
                                <h4 class="font-weight-bolder" id="chart_stats_booked">0</h4>
                                <p class="card-text">{{ __('locale.booked') }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card text-center">
                            <div class="card-body">
                                <h4 class="font-weight-bolder" id="chart_stats_confirmed">0</h4>
                                <p class="card-text">{{ __('locale.confirmed') }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card text-center">
                            <div class="card-body">
                                <h4 class="font-weight-bolder" style="color:#ea5455;" id="chart_stats_canceled">0</h4>
                                <p class="card-text" style="color:#ea5455;">{{ __('locale.canceled') }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card text-center">
                            <div class="card-body">
                                <h4 class="font-weight-bolder" id="chart_stats_attended">0</h4>
                                <p class="card-text">{{ __('locale.attended') }}</p>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
<!-- Row appointments Chart Starts -->

@endsection

@section('vendor-script')
<script src="{{ asset('new-assets/app-assets/vendors/js/charts/apexcharts.min.js') }}"></script>
<script src="{{ asset('new-assets/app-assets/vendors/js/pickers/flatpickr/flatpickr.min.js') }}"></script>
<script src="{{ asset('new-assets/js/select2/select2.min.js') }}"></script>


{{-- ############################################## --}}
<script src="{{ asset('new-assets/app-assets/vendors/js/forms/validation/jquery.validate.min.js') }}"></script>
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


<script src="{{ asset('assets/plugins/datepicker/js/picker.js') }}"></script>
<script src="{{ asset('assets/plugins/datepicker/js/picker.date.js') }}"></script>
<script src="{{ asset('assets/plugins/datepicker/js/custom-picker.js') }}"></script>
<script src="{{ asset('new-assets/app-assets/vendors/js/pickers/flatpickr/flatpickr.min.js') }}"></script>


{{-- ############################################## --}}
@endsection
@section('page-script')
@php
$date = Carbon\Carbon::today()->subDays(7);
$start_custom_range = $date->format('Y-m-d');
$dtNow = Carbon\Carbon::now();
$end_custom_range = $dtNow->format('Y-m-d');
@endphp
<script>
$.ajaxSetup({
    headers: {
        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
    },
});
$(document).ready(function() {
    $('.js-select2').select2();
    $('#custom-range').flatpickr({
        mode: 'range',
        defaultDate: ['{{$start_custom_range}}', '{{$end_custom_range}}']
    });


});




function quick_filters(type) {
    $('#INPUT_QUICK').val(type);
    var selected_filter=type;
    if (type != 'reset') {
        $('#custom-range').val('');
    } else {
        $('#INPUT_QUICK').val('');
        var filter_range = '{{$start_custom_range}} to {{$end_custom_range}}';
        $('#custom-range').val(filter_range);
    }
    _update_current_selected_filter_date();
    $("#formFilterStats").submit();
}



$("#report_service").submit(function(event) {
    event.preventDefault();
    $("#select_doctors").removeClass('d-none');
    var doctor_id = $('#select_doctors').val();
    var url = '{{route('admin.report.doctor',['doctor_id'=>0,'mode'=>1])}}';
    $.ajax({
        type: "POST",
        dataType: 'json',
        data: {
            url : url,
            doctor_id : doctor_id
        },
        success: function(response) {

            $('#payments').html(response.payments);
            $('#appointments').html(response.appointments);
            $('#procedures').html(response.procedures);
            $('#invoices').html(response.invoices);
            $('#bookings').html(response.bookings);
            $('#patients').html(response.patients);
        },
    })
    return false;
});






$('#select_doctors').on('change', function() {
    _update_current_selected_filter_date();
    $("#formFilterStats").submit();
});

function quick_filters(type) {
    $('#INPUT_QUICK').val(type);
    var selected_filter=type;
    if (type != 'reset') {
        $('#custom-range').val('');
    } else {
        $('#INPUT_QUICK').val('');
        var filter_range = '{{$start_custom_range}} to {{$end_custom_range}}';
        $('#custom-range').val(filter_range);
    }
    _update_current_selected_filter_date();
    $("#formFilterStats").submit();
}

function _update_current_selected_filter_date(){
    $('#current_filter_date').html('<span class="spinner-border spinner-border-sm text-primary" role="status" aria-hidden="true"></span>');
    var current=$('#custom-range').val();
    var type=$('#INPUT_QUICK').val();
    if(type && type!="reset"){
        current=type;
        if(type=='today'){
            current='Today';
        }
        if(type=='yesterday'){
            current='Yesterday';
        }
        if(type=='last_7_days'){
            current='Last 7 days';
        }
        if(type=='last_30_days'){
            current='Last 30 days';
        }
        if(type=='this_month'){
            current='This month';
        }
        if(type=='last_month'){
            current='Last month';
        }
    }
    $('#current_filter_date').html(current);
}

function range_filters() {
    $('#INPUT_QUICK').val('');
    _update_current_selected_filter_date();
    $("#formFilterStats").submit();
}
//submit form
$("#formFilterStats").submit(function(event) {
    event.preventDefault();
    $("#SPINNER").removeClass('d-none');
    var doctor_id = $('#select_doctors').val();
    var formData = $(this).serializeArray();
    formData.push({
        name: "doctor_id",
        value: doctor_id
    });
    //ajax dashboard stats
    $('#payments, #appointments, #procedures, #invoices, #bookings, #patients').html(
        '<span class="spinner-border spinner-border-sm text-primary" role="status" aria-hidden="true"></span>'
    );
    $.ajax({
        type: "POST",
        dataType: 'json',
        data: formData,
        url: '/admin/reports/stats',
        success: function(response) {
            $('#payments').html(response.payments);
            $('#appointments').html(response.appointments);
            $('#procedures').html(response.procedures);
            $('#invoices').html(response.invoices);
            $('#bookings').html(response.bookings);
            $('#patients').html(response.patients);
        },
    }).done(function(data) {
        $("#SPINNER").addClass('d-none');
    });
    _loadFinancialsDataChart(0);
    _loadAppointmentsDataChart(0);
    return false;
});


function _loadDashboardStats() {
    $('#payments, #appointments, #procedures, #invoices, #bookings, #patients').html(
        '<span class="spinner-border spinner-border-sm text-primary" role="status" aria-hidden="true"></span>');
        var doctor_id = $('#select_doctors').val();
        var filter_range = $('#custom-range').val();
        var quick_type = $('#INPUT_QUICK').val();
        if (filter_range == '') {
            filter_range = '{{$start_custom_range}} to {{$end_custom_range}}';
        }
    $.ajax({
        type: "POST",
        dataType: 'json',
        data: {
            quick_type: quick_type,
            filter_range: filter_range,
            doctor_id: doctor_id
        },
        url: '/admin/reports/stats',
        success: function(response) {
            $('#payments').html(response.payments);
            $('#appointments').html(response.appointments);
            $('#procedures').html(response.procedures);
            $('#invoices').html(response.invoices);
            $('#bookings').html(response.bookings);
            $('#patients').html(response.patients);
        },
    });
}
//chart
var chartColors = {
    column: {
        series1: '#7367f0',
        series2: '#28c76f',
        bg: '#f1effd'
    },
};
//Finances chart
var columnChartEl = document.querySelector('#finances-column-chart'),
    columnChartConfig = {
        chart: {
            height: 400,
            type: 'bar',
            stacked: true,
            toolbar: {
                show: false
            }
        },
        plotOptions: {
            bar: {
                columnWidth: '15%',
                colors: {
                    backgroundBarColors: [],
                    backgroundBarRadius: 0
                }
            }
        },
        dataLabels: {
            enabled: false
        },
        legend: {
            show: false
        },
        colors: [chartColors.column.series1, chartColors.column.series2],
        stroke: {
            show: true,
            colors: ['transparent']
        },
        grid: {
            xaxis: {
                lines: {
                    show: true
                }
            }
        },
        series: [],
        noData: {
            text: 'Loading...'
        },
        fill: {
            opacity: 1
        },
    };
if (typeof columnChartEl !== undefined && columnChartEl !== null) {
    var columnChart = new ApexCharts(columnChartEl, columnChartConfig);
    columnChart.render();
}

_loadDatasDoctorsForSelectOptions('select_doctors', 0, 0);

function _loadDatasDoctorsForSelectOptions(select_id, doctor_id, selected_value = 0) {
    $('#' + select_id).empty().append('<option value="0">All</option>');
    $.ajax({
        url: '/admin/select/json/doctors/' + doctor_id,
        dataType: 'json',
        success: function(response) {
            var array = response;
            if (array != '') {
                for (i in array) {
                    $('#' + select_id).append("<option value='" + array[i].id + "'>" + array[i].name +
                        "</option>");
                }
            }
        },
    }).done(function() {
        if (selected_value != 0 && selected_value != '') {
            $('#' + select_id + ' option[value="' + selected_value + '"]').attr('selected', 'selected');
        }
        _update_current_selected_filter_date();
        _loadDashboardStats();
        _loadFinancialsDataChart(0);
        _loadAppointmentsDataChart(0);
    });
}

function _loadFinancialsDataChart(type_data) {

    $('#chart_stats_production, #chart_stats_collection, #chart_stats_discounts, #chart_stats_taxes').html('<span class="spinner-border spinner-border-sm text-primary" role="status" aria-hidden="true"></span>');
    var doctor_id = $('#select_doctors').val();
    var filter_range = $('#custom-range').val();
    var quick_type = $('#INPUT_QUICK').val();
    //console.log(filter_range+doctor_id);return false;
    if (filter_range == '') {
        filter_range = '{{$start_custom_range}} to {{$end_custom_range}}';
    }
    $.ajax({
        type: "POST",
        dataType: 'json',
        data: {
            quick_type: quick_type,
            filter_range: filter_range,
            doctor_id: doctor_id
        },
        url: '/admin/reports/json/finances/stats/' + type_data,
        success: function(response) {
            columnChart.updateSeries([{
                    name: 'Production',
                    data: response.production
                },
                {
                    name: 'Collection',
                    data: response.collection
                }
            ]);
            //chart stats

            $('#chart_stats_production').html(response.stats.production);
            $('#chart_stats_collection').html(response.stats.collection);
            $('#chart_stats_remaining').html(response.stats.remaining)
            $('#chart_stats_cash').html(response.stats.cash);
            $('#chart_stats_mada').html(response.stats.mada);
            $('#chart_stats_credit').html(response.stats.credit);
            // $('#chart_stats_discounts').html(response.stats.discounts);
            // $('#chart_stats_taxes').html(response.stats.taxes);
        },
    });
}
function _loadAppointmentsDataChart(type_data) {
    $('#chart_stats_booked, #chart_stats_confirmed, #chart_stats_canceled, #chart_stats_attended').html('<span class="spinner-border spinner-border-sm text-primary" role="status" aria-hidden="true"></span>');
    var doctor_id = $('#select_doctors').val();
    var filter_range = $('#custom-range').val();
    var quick_type = $('#INPUT_QUICK').val();
    if (filter_range == '') {
        filter_range = '{{$start_custom_range}} to {{$end_custom_range}}';
    }
    $.ajax({
        type: "POST",
        dataType: 'json',
        data: {
            quick_type: quick_type,
            filter_range: filter_range,
            doctor_id: doctor_id
        },
        url: '/admin/reports/json/appointments/stats/' + type_data,
        success: function(response) {
            columnChartAppointment.updateSeries([
                {
                    name: 'Booked',
                    data: response.booked
                },
                {
                    name: 'Confirmed',
                    data: response.confirmed
                },
                {
                    name: 'Canceled',
                    data: response.canceled
                },
                {
                    name: 'Attended',
                    data: response.attended
                }
            ]);
            //chart stats
            $('#chart_stats_booked').html(response.stats.nb_booked);
            $('#chart_stats_confirmed').html(response.stats.nb_confirmed);
            $('#chart_stats_canceled').html(response.stats.nb_canceled+' ('+response.stats.percent_canceled+'%)');
            $('#chart_stats_attended').html(response.stats.nb_attended);
        },
    });
}


//Appointments chart
var appointmentChartColors = {
    column: {
        booked: '#7367f0',
        confirmed: '#00cfe8',
        canceled: '#ea5455',
        attended: '#ff9f43',
        bg: '#e7fafc'
    },
};
var columnChartAppointment = document.querySelector('#appointments-column-chart'),
    columnChartConfig = {
        chart: {
            height: 400,
            type: 'bar',
            stacked: true,
            toolbar: {
                show: false
            }
        },
        plotOptions: {
            bar: {
                columnWidth: '15%',
                colors: {
                    /* backgroundBarColors: [
                        appointmentChartColors.column.bg,
                        appointmentChartColors.column.bg,
                        appointmentChartColors.column.bg,
                        appointmentChartColors.column.bg,
                        appointmentChartColors.column.bg
                    ], */
                    backgroundBarColors: [],
                    backgroundBarRadius: 0
                }
            }
        },
        dataLabels: {
            enabled: false
        },
        legend: {
            show: false
        },
        colors: [appointmentChartColors.column.booked, appointmentChartColors.column.confirmed, appointmentChartColors.column.canceled, appointmentChartColors.column.attended],
        stroke: {
            show: true,
            colors: ['transparent']
        },
        grid: {
            xaxis: {
                lines: {
                    show: true
                }
            }
        },
        series: [],
        noData: {
            text: 'Loading...'
        },
        fill: {
            opacity: 1
        },
    };
if (typeof columnChartAppointment !== undefined && columnChartAppointment !== null) {
    var columnChartAppointment = new ApexCharts(columnChartAppointment, columnChartConfig);
    columnChartAppointment.render();
}
</script>
@endsection
