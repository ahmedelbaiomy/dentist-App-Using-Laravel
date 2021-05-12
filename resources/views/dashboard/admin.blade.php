@extends('layouts/layoutMaster')

@section('title', 'Admin dashboard')

@section('vendor-style')
<!-- vendor css files -->
<link rel="stylesheet" href="{{ asset('new-assets/app-assets/vendors/css/tables/datatable/datatables.min.css') }}">
<link rel="stylesheet"
    href="{{ asset('new-assets/app-assets/vendors/css/tables/datatable/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet"
    href="{{ asset('new-assets/app-assets/vendors/css/tables/datatable/responsive.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('new-assets/app-assets/vendors/css/pickers/flatpickr/flatpickr.min.css') }}">
@endsection

@section('page-style')
{{-- Page Css files --}}

@endsection

@section('content')

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">

                <!-- begin custom range -->
                <div class="row">
                    <div class="col-md-4">
                        <!-- <label for="custom-range">Custom Range</label> -->
                        <input type="text" id="custom-range" class="form-control form-control-sm flatpickr-range"
                            placeholder="YYYY-MM-DD to YYYY-MM-DD" />
                    </div>
                    <div class="col-md-8">
                        <div class="btn-group float-right" role="group" aria-label="Basic example">
                            <button type="button" class="btn btn-outline-primary btn-sm">Today</button>
                            <button type="button" class="btn btn-outline-primary btn-sm">This month</button>
                            <button type="button" class="btn btn-outline-primary btn-sm">This year</button>
                            <button class="btn btn-outline-primary btn-sm dropdown-toggle" type="button"
                                id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true"
                                aria-expanded="false">
                                Today : May 6
                            </button>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                <a class="dropdown-item" href="javascript:void(0);">Yesterday</a>
                                <a class="dropdown-item" href="javascript:void(0);">Last 7 Days</a>
                                <a class="dropdown-item" href="javascript:void(0);">Last 30 Days</a>
                                <a class="dropdown-item" href="javascript:void(0);">Last Month</a>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- endcustom range -->

            </div>
        </div>
    </div>
</div>


<!-- Stats Vertical Card -->
<div class="row">
    <div class="col-xl-3 col-md-4 col-sm-6">
        <div class="card text-center">
            <div class="card-body">
                <div class="avatar bg-light-info p-50 mb-1">
                    <div class="avatar-content">
                        <i data-feather="calendar" class="font-medium-5"></i>
                    </div>
                </div>
                <h2 class="font-weight-bolder">{{count($appointments)}}</h2>
                <p class="card-text">Appointments</p>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-4 col-sm-6">
        <div class="card text-center">
            <div class="card-body" onClick="window.location.href = 'patient'" style="cursor: url(hand.cur), pointer">
                <div class="avatar bg-light-warning p-50 mb-1">
                    <div class="avatar-content">
                        <i data-feather="user-plus" class="font-medium-5"></i>
                    </div>
                </div>
                <h2 class="font-weight-bolder">{{count($patients)}}</h2>
                <p class="card-text">New Patients</p>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-4 col-sm-6">
        <div class="card text-center">
            <div class="card-body">
                <div class="avatar bg-light-danger p-50 mb-1">
                    <div class="avatar-content">
                        <i data-feather="thermometer" class="font-medium-5"></i>
                    </div>
                </div>
                <h2 class="font-weight-bolder">{{count($doctors)}}</h2>
                <p class="card-text">Doctors</p>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-4 col-sm-6">
        <div class="card text-center">
            <div class="card-body">
                <div class="avatar bg-light-primary p-50 mb-1">
                    <div class="avatar-content">
                        <i data-feather="users" class="font-medium-5"></i>
                    </div>
                </div>
                <h2 class="font-weight-bolder">230k</h2>
                <p class="card-text">Incomes</p>
            </div>
        </div>
    </div>
</div>
<!--/ Stats Vertical Card -->

<div class="row">
    <!-- Company Table Card -->
    <div class="col-lg-12 col-12">
        <div class="card card-company-table">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table" id="doctors_stats_datatable">
                        <thead>
                            <tr>
                                <th>Doctor</th>
                                <th>Income</th>
                                <th>Refund</th>
                                <th>Appointment</th>
                                <th>Patient</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!--/ Company Table Card -->
</div>

<!-- Row start -->
<div class="row gutters">
    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="card-header">
                <div class="card-title">Clinic target</div>
            </div>
            <div class="card-body">
                <div class="line-chart" id="filledLineChart" style="min-height:350px"></div>
            </div>
        </div>
    </div>
</div>


@endsection

@section('vendor-script')
<script src="{{ asset('new-assets/app-assets/vendors/js/tables/datatable/datatables.min.js') }}"></script>
<script src="{{ asset('new-assets/app-assets/vendors/js/tables/datatable/datatables.buttons.min.js') }}"></script>
<script src="{{ asset('new-assets/app-assets/vendors/js/tables/datatable/buttons.html5.min.js') }}"></script>
<script src="{{ asset('new-assets/app-assets/vendors/js/tables/datatable/buttons.print.min.js') }}"></script>
<script src="{{ asset('new-assets/app-assets/vendors/js/tables/datatable/buttons.bootstrap.min.js') }}"></script>
<script src="{{ asset('new-assets/app-assets/vendors/js/tables/datatable/datatables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('new-assets/app-assets/vendors/js/extensions/sweetalert2.all.min.js') }}"></script>
<script src="{{ asset('assets/plugins/apex/apexcharts.min.js') }}"></script>
<script src="{{ asset('new-assets/app-assets/vendors/js/pickers/flatpickr/flatpickr.min.js') }}"></script>


@endsection
@section('page-script')
<script src="{{ asset('new-assets/js/main.js') }}"></script>
<script>
$.ajaxSetup({
    headers: {
        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
    },
});
/* nOTES */
var dtUrl = '/admin/sdt/doctors/stats';
var doctors_stats_datatable = $('#doctors_stats_datatable');
doctors_stats_datatable.DataTable({
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
var _reload_doctors_stats_datatable = function() {
    $('#doctors_stats_datatable').DataTable().ajax.reload();
}

$(document).ready(function() {

    $('#custom-range').flatpickr({
        mode: 'range'
    });

    var series = {
        "monthDataSeries1": {
            "prices": [110, 80, 125, 65, 95, 75, 90, 110, 80, 125, 70, 95],
            "dates": ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"]
        }
    }


    var options = {
        chart: {
            height: 300,
            type: 'area'
        },
        dataLabels: {
            enabled: false
        },
        stroke: {
            curve: 'smooth',
            width: 3,
        },
        series: [{
            name: "",
            data: series.monthDataSeries1.prices
        }],
        grid: {
            row: {
                colors: ['#f4f5fb', '#ffffff'], // takes an array which will be repeated on columns
                opacity: 0.5
            },
        },
        labels: series.monthDataSeries1.dates,
        xaxis: {
            type: 'month',
        },
        yaxis: {
            opposite: true
        },
        theme: {
            monochrome: {
                enabled: true,
                color: '#074b9c',
                shadeIntensity: 0.1
            },
        },
        markers: {
            size: 0,
            opacity: 0.2,
            colors: ["#074b9c"],
            strokeColor: "#fff",
            strokeWidth: 2,
            hover: {
                size: 7,
            }
        },
    }

    var chart = new ApexCharts(
        document.querySelector("#filledLineChart"),
        options
    );

    chart.render();
});
</script>
@endsection