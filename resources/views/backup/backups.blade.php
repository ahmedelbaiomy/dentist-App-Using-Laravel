@extends('layouts/layoutMaster')

@section('title', 'Backups')

@section('vendor-style')
<!-- vendor css files -->
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
                <h4 class="card-title">Administer Database Backups</h4>
                <div class="row">
                    <!-- <div class="col-md-12 clearfix">
                        <a id="create-new-backup-button" href="{{ url('/admin/backup/create') }}"
                            class="btn btn-outline-primary btn-sm" style="margin-bottom:2em;"><i
                                data-feather="plus"></i>
                            Create New Backup
                        </a>
                    </div> -->

                    <div class="col-md-12">
                        <p>Download a zipped copy of your entire site or a part of your site that you can save to your
                            computer. When you backup your website, you have an extra copy of your information in case
                            something happens to your host.</p>
                        <p>You have an automatically generated backups that are currently available. This feature is
                            enabled in your server.</p>
                    </div>

                    <div class="col-md-12">
                        @if ( Session::has('success') )
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <div class="alert-body">
                                {{ Session::get('success') }}
                            </div>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        @endif

                        @if ( Session::has('update') )
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <div class="alert-body">
                                {{ Session::get('update') }}
                            </div>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        @endif

                        @if ( Session::has('delete') )
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <div class="alert-body">
                                {{ Session::get('delete') }}
                            </div>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        @endif
                    </div>

                    <div class="col-md-12">
                        @if (count($backups))

                        <div class="table-responsive">
                            <table id="backup_datatable" class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>File Name</th>
                                        <th>File Size</th>
                                        <th>Created Date</th>
                                        <th>Created Age</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($backups as $backup)
                                    <tr>
                                        <td>{{ $backup['file_name'] }}</td>
                                        <td>{{ \App\Library\Helpers\Helper::humanFilesize($backup['file_size']) }}</td>
                                        <td>
                                            {{ date('F jS, Y, g:ia (T)',$backup['last_modified']) }}
                                        </td>
                                        <td>
                                            {{ \Carbon\Carbon::parse($backup['last_modified'])->diffForHumans() }}
                                        </td>
                                        <td class="text-right">
                                            <a class="btn btn-outline-primary btn-sm"
                                                href="{{ url('admin/backup/download/'.$backup['file_name']) }}"><i
                                                    data-feather="download"></i> Download</a>
                                            <a class="btn btn-outline-danger btn-sm" data-button-type="delete"
                                                href="{{ url('admin/backup/delete/'.$backup['file_name']) }}"><i
                                                    data-feather="trash"></i>
                                                Delete</a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>


                        @else
                        <div class="well">
                            <h4>There are no backups</h4>
                        </div>
                        @endif
                    </div>
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
<script src="{{ asset('new-assets/app-assets/vendors/js/extensions/sweetalert2.all.min.js') }}"></script>
@endsection
@section('page-script')
<script src="{{ asset('new-assets/js/main.js') }}"></script>
<script>
@if (count($backups))
var table = $('#backup_datatable');
table.DataTable({
    responsive: true,
    lengthMenu: [5, 10, 25, 50],
    pageLength: 25,
});
@endif
</script>
@endsection