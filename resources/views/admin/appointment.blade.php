@extends('layouts/layoutMaster')

@section('title', 'Appointments Lists')

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

@php
$lang='en';
if(session()->has('locale')){
$lang=session()->get('locale');
}
@endphp

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">{{ __('locale.appointments') }}</h4>
                <div class="row">
                    <div class="col-md-4">
                        <form id="formFilterSearch">
                            <div class="input-group mb-1">
                                <input type="text" class="form-control  flatpickr-range" id="custom-range"
                                    name="filter_range" placeholder="" aria-describedby="button-filter" />
                                <div class="input-group-append" id="button-filter">
                                    <button class="btn btn-outline-primary " type="button" onclick="_submit_search_form()"><i
                                            data-feather="search"></i> {{ __('locale.search') }}</button>
                                </div>
                            </div>
                        </form>
                        <div class="spinner-border text-primary d-none" id="SPINNER">
                            <span class="sr-only">Loading...</span>
                        </div>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="datatable table table-striped dataex-html5-selectors table-bordered" id="appointments_datatable">
                        <thead>
                            <tr>
                                <th>{{ __('locale.patient') }}</th>
                                <th>{{ __('locale.doctor') }}</th>
                                <th>{{ __('locale.start_time') }}</th>
                                <th>{{ __('locale.duration') }}</th>
                                <th>{{ __('locale.comments') }}</th>
                                <th>{{ __('locale.status') }}</th>
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

@endsection

@section('vendor-script')
<script src="{{ asset('new-assets/app-assets/vendors/js/tables/datatable/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('new-assets/app-assets/vendors/js/tables/datatable/datatables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('new-assets/app-assets/vendors/js/tables/datatable/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('new-assets/app-assets/vendors/js/tables/datatable/responsive.bootstrap4.js') }}"></script>
<script src="{{ asset('new-assets/app-assets/vendors/js/pickers/flatpickr/flatpickr.min.js') }}"></script>
@endsection
@section('page-script')
<script>
$.ajaxSetup({
    headers: {
        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
    },
});
$(document).ready(function() {

    $('#custom-range').flatpickr({
        mode: 'range'
    });

    var dtUrl = '/sdt/appointments';
    var table = $('#appointments_datatable').DataTable({
        responsive: true,
        @if($lang == 'ar')
        language: {
            url: '/json/datatable/ar.json'
        },
        @endif
        searching: false,
        processing: true,
        paging: true,
        ordering: true,
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
});

function _submit_search_form(){
    $("#formFilterSearch").submit();
}
//submit form
$("#formFilterSearch").submit(function(event) {
    event.preventDefault();
    $("#SPINNER").removeClass('d-none');
    var formData = $(this).serializeArray();
    var table = 'appointments_datatable';
    var dtUrl = '/sdt/appointments';
    $.ajax({
        type: "POST",
        dataType: 'json',
        data: formData,
        url: dtUrl,
        success: function(response) {
            if (response.data.length == 0) {
                $('#' + table).dataTable().fnClearTable();
                return 0;
            }
            $('#' + table).dataTable().fnClearTable();
            $("#" + table).dataTable().fnAddData(response.data, true);
        },
        error: function() {
            $('#' + table).dataTable().fnClearTable();
        }
    }).done(function(data) {
        $("#SPINNER").addClass('d-none');
    });
    return false;
});
</script>
@endsection