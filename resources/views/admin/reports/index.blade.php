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
                <h4 class="card-title">Reports</h4>
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
                                            data-feather="search"></i> Search</button>
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
                            <small>Select doctor</small>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="btn-group float-right" role="group" aria-label="Basic example">
                            <button type="button" class="btn btn-outline-primary "
                                onclick="quick_filters('reset')">Reset</button>
                            <button class="btn btn-outline-primary  dropdown-toggle" type="button"
                                id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true"
                                aria-expanded="false">
                                Today : {{$dtNow->format('F d')}}
                            </button>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                <a class="dropdown-item" href="javascript:void(0);"
                                    onclick="quick_filters('today')">Today</a>
                                <a class="dropdown-item" href="javascript:void(0);"
                                    onclick="quick_filters('yesterday')">Yesterday</a>
                                <a class="dropdown-item" href="javascript:void(0);"
                                    onclick="quick_filters('last_7_days')">Last 7 Days</a>
                                <a class="dropdown-item" href="javascript:void(0);"
                                    onclick="quick_filters('last_30_days')">Last 30 Days</a>
                                <a class="dropdown-item" href="javascript:void(0);"
                                    onclick="quick_filters('this_month')">This month</a>
                                <a class="dropdown-item" href="javascript:void(0);"
                                    onclick="quick_filters('last_month')">Last Month</a>
                                <a class="dropdown-item" href="javascript:void(0);"
                                    onclick="quick_filters('this_year')">This year</a>
                            </div>

                        </div>
                    </div>

                </div>
                <!-- endcustom range -->

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
                <!-- <div class="avatar bg-light-info p-50 mb-1">
            <div class="avatar-content">
              <i data-feather="eye" class="font-medium-5"></i>
            </div>
          </div> -->
                <h2 class="font-weight-bolder" id="payments">0</h2>
                <p class="card-text">Payments</p>
            </div>
        </div>
    </div>
    <div class="col-xl-2 col-md-4 col-sm-6">
        <div class="card text-center">
            <div class="card-body">
                <!-- <div class="avatar bg-light-warning p-50 mb-1">
            <div class="avatar-content">
              <i data-feather="message-square" class="font-medium-5"></i>
            </div>
          </div> -->
                <h2 class="font-weight-bolder" id="appointments">0</h2>
                <p class="card-text">Appointments</p>
            </div>
        </div>
    </div>
    <div class="col-xl-2 col-md-4 col-sm-6">
        <div class="card text-center">
            <div class="card-body">
                <!-- <div class="avatar bg-light-danger p-50 mb-1">
            <div class="avatar-content">
              <i data-feather="shopping-bag" class="font-medium-5"></i>
            </div>
          </div> -->
                <h2 class="font-weight-bolder" id="procedures">0</h2>
                <p class="card-text">Procedures</p>
            </div>
        </div>
    </div>
    <div class="col-xl-2 col-md-4 col-sm-6">
        <div class="card text-center">
            <div class="card-body">
                <!-- <div class="avatar bg-light-primary p-50 mb-1">
            <div class="avatar-content">
              <i data-feather="heart" class="font-medium-5"></i>
            </div>
          </div> -->
                <h2 class="font-weight-bolder" id="invoices">0</h2>
                <p class="card-text">Invoices</p>
            </div>
        </div>
    </div>
    <div class="col-xl-2 col-md-4 col-sm-6">
        <div class="card text-center">
            <div class="card-body">
                <!-- <div class="avatar bg-light-success p-50 mb-1">
            <div class="avatar-content">
              <i data-feather="award" class="font-medium-5"></i>
            </div>
          </div> -->
                <h2 class="font-weight-bolder" id="bookings">0</h2>
                <p class="card-text">Bookings</p>
            </div>
        </div>
    </div>
    <div class="col-xl-2 col-md-4 col-sm-6">
        <div class="card text-center">
            <div class="card-body">
                <!-- <div class="avatar bg-light-danger p-50 mb-1">
            <div class="avatar-content">
              <i data-feather="truck" class="font-medium-5"></i>
            </div>
          </div> -->
                <h2 class="font-weight-bolder" id="patients">0</h2>
                <p class="card-text">Patients</p>
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
                    <h4 class="card-title mb-1">Finances</h4>
                    <span class="cursor-pointer mr-1">
                        <!-- <span class="bullet bullet-primary align-middle bullet-sm">&nbsp;</span> -->
                        <span class="align-middle cursor-pointer" onclick="_loadFinancialsDataChart(0);">All</span>
                    </span>
                    <span class="cursor-pointer mr-1">
                        <span class="bullet bullet-primary align-middle bullet-sm">&nbsp;</span>
                        <span class="align-middle cursor-pointer"
                            onclick="_loadFinancialsDataChart(1);">Production</span>
                    </span>
                    <span class="cursor-pointer mr-1">
                        <span class="bullet bullet-sm align-middle" style="background-color: #28c76f">&nbsp;</span>
                        <span class="align-middle cursor-pointer"
                            onclick="_loadFinancialsDataChart(2);">Collection</span>
                    </span>
                </div>
            </div>
            <div class="card-body">
                <div id="finances-column-chart"></div>
                <!-- finance stats -->
                <div class="row">
                    <div class="col-md-3">
                        <div class="card text-center">
                            <div class="card-body">
                                <h2 class="font-weight-bolder" id="chart_stats_production">0</h2>
                                <p class="card-text">Production</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card text-center">
                            <div class="card-body">
                                <h2 class="font-weight-bolder" id="chart_stats_collection">0</h2>
                                <p class="card-text">Collection</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card text-center">
                            <div class="card-body">
                                <h2 class="font-weight-bolder" id="chart_stats_discounts">0</h2>
                                <p class="card-text">Discounts</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card text-center">
                            <div class="card-body">
                                <h2 class="font-weight-bolder" id="chart_stats_taxes">0</h2>
                                <p class="card-text">Taxes</p>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
<!-- Row finances Chart Starts -->

@endsection

@section('vendor-script')
<script src="{{ asset('new-assets/app-assets/vendors/js/charts/apexcharts.min.js') }}"></script>
<script src="{{ asset('new-assets/app-assets/vendors/js/pickers/flatpickr/flatpickr.min.js') }}"></script>
<script src="{{ asset('new-assets/js/select2/select2.min.js') }}"></script>
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

$('#select_doctors').on('change', function() {
    $("#formFilterStats").submit();
});

function quick_filters(type) {
    $('#INPUT_QUICK').val(type);
    if (type != 'reset') {
        $('#custom-range').val('');
    } else {
        $('#INPUT_QUICK').val('');
        var filter_range = '{{$start_custom_range}} to {{$end_custom_range}}';
        $('#custom-range').val(filter_range);
    }
    $("#formFilterStats").submit();
}

function range_filters() {
    $('#INPUT_QUICK').val('');
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
    //ajax apex
    /* $.ajax({
        type: "POST",
        dataType: 'json',
        data: formData,
        url: '/admin/reports/json/finances/stats/0', //type_data==0 all
        success: function(response) {
            columnChart.updateSeries([{
                    name: 'Production',
                    data: response.production
                },
                {
                    name: 'Collection',
                    data: response.collection
                }
            ])
        },
    }); */
    //
    _loadFinancialsDataChart(0);
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
        bg: '#f8d3ff'
    },
};
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
                    backgroundBarColors: [
                        chartColors.column.bg,
                        chartColors.column.bg,
                        chartColors.column.bg,
                        chartColors.column.bg,
                        chartColors.column.bg
                    ],
                    backgroundBarRadius: 10
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
        _loadDashboardStats();
        _loadFinancialsDataChart(0);
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
            $('#chart_stats_discounts').html(response.stats.discounts);
            $('#chart_stats_taxes').html(response.stats.taxes);
        },
    });
}
</script>
@endsection