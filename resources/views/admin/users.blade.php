@extends('layouts.app')

@section('content')

<script>

    function delete_func(val) {
        document.getElementById(val).submit();
    }

    function set_state_func(val) {
        document.getElementById(val).submit();
    }

    function reset_pass_func(val) {

        swal({
            title: "Hello!",
            text: "The password will be reset with 12345.",
            icon: "success",
        });
        document.getElementById(val).submit();
    }


</script>
<div class="page-header">
    <ol class="breadcrumb">
        <li class="breadcrumb-item active">Users List</li>
    </ol>
</div>
<div class="content-wrapper">
    <div class="row">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-xs-12 mb-2">
            <h5 class='text-success'>You have total {{ count($users) }} users.</h5>
        </div>
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="datatable table">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>username</th>
                                    <th>Type</th>
                                    <th>State</th>
                                    <th class="tb-tnx-action">
                                        Action
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                            
                            @foreach($users as $user)
                                <tr>
                                    <td><span>{{ $user->name }}</span></td>
                                    <td><span>{{ $user->username }}</span></td>
                                    <td><span>{{ $user->user_type }}</span></td>
                                    <td>
                                        <span>
                                            @if($user->state == 1)
                                                <span class="tb-status text-success">Verified</span>
                                            @elseif($user->state == 0)
                                                <span class="tb-status text-warning">Pending</span>
                                            @elseif($user->state == 2)
                                                <span class="tb-status text-danger">Suspend</span>
                                            @endif
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            Action
                                        </button>
                                        <div class="dropdown-menu">
                                            @if($user->state != 1)
                                                <div onclick="set_state_func('verify_frm_{{ $user->id }}')" >
                                                    <form action="{{ route('admin.users.setstate', ['user_id'=>$user->id, 'key'=>1] )}}" name="verify_frm_{{ $user->id }}" id="verify_frm_{{ $user->id }}" method="post">
                                                        @csrf
                                                        @method('PUT')
                                                        <a class="dropdown-item" href="#"><span class="icon-check"></span><span>&nbsp;Verify</span></a>
                                                    </form>
                                                </div>
                                            @endif
                                            @if($user->state != 2)
                                                <div onclick="set_state_func('suspend_frm_{{ $user->id }}')" >
                                                    <form action="{{ route('admin.users.setstate', ['user_id'=>$user->id, 'key'=>2] )}}" name="suspend_frm_{{ $user->id }}" id="suspend_frm_{{ $user->id }}" method="post">
                                                        @csrf
                                                        @method('PUT')
                                                        <a  class="dropdown-item" href="#"><span class="icon-slash"></span><span>&nbsp;Suspend User</span></a>
                                                    </form>
                                                </div>
                                            @endif
                                            <div onclick="reset_pass_func('resetpass_frm_{{ $user->id }}')" >
                                                <form action="{{ route('admin.users.resetpass', $user->id)}}" name="resetpass_frm_{{ $user->id }}" id="resetpass_frm_{{ $user->id }}" method="post">
                                                    @csrf
                                                    @method('PUT')
                                                    <a class="dropdown-item" href="#"><span class="icon-shield"></span><span>&nbsp;Reset Pass</span></a>
                                                </form>
                                            </div>
                                            <div data-toggle="modal" data-target="#set_type_modal"  data-id="{{ $user }}"><a class="dropdown-item" href="#"><span class="icon-settings1"></span><span>&nbsp;Set Type</span></a></div>
                                            <div onclick="delete_func('delete_frm_{{ $user->id }}')" >
                                                <form action="{{ route('admin.users.destroy', $user->id)}}" name="delete_frm_{{ $user->id }}" id="delete_frm_{{ $user->id }}" method="post">
                                                    @csrf
                                                    @method('DELETE')
                                                    <a  class="dropdown-item" href="#" style="color:#e85347" ><span class="icon-trash-2"></span><span>&nbsp;Remove</span></a>
                                                </form>
                                            </div>                                                                               
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
</div>

<div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" id="set_type_modal">
    <div class="modal-dialog modal-lg" role="document">
        
        <div class="modal-content">
            <div  class="modal-header">
                <h5 class="modal-title" id="basicModalLabel">Set Type</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="user_id" name="user_id">
                <div class="row gy-4">
                            
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label" for="first-name">Email</label>
                            <input type="text" id="email" name="email" class="form-control form-control-lg" readonly>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">Types</label>
                            <div class="form-control-wrap">
                                <select class="form-control form-control-lg selectpicker" data-live-search="true" id="user_type" name="user_type">
                                    <option value="admin">Admin</option>             
                                    <option value="doctor">Doctor</option>          
                                    <option value="reception">Reception</option>          
                                    <option value="accountant">Accountant</option> 
                                    <option value="none">None</option>                                   
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div><!-- .modal-body -->
            <div class="modal-footer">
                <button href="#" class="btn btn-primary" id="set_type_btn"><span class="icon-settings1"></span>&nbsp;Set</button>                                        
                <button href="#" data-dismiss="modal" class="btn btn-danger"><span class="icon-close"></span>&nbsp;Cancel</button>                                            
            </div>
        </div><!-- .modal-content -->
    </div><!-- .modal-dialog -->
</div><!-- .modal -->
<!-- @@ Set Type Modal @e -->



<script>
$(document).ready(function(){
    var table = $('.datatable').DataTable();
    $('#set_type_modal').on('show.bs.modal', function(e) {
        var user_data = $(e.relatedTarget).data('id');
        $("#user_id").val(user_data['id']);
        $("#email").val(user_data['email']);
    });


    $("#set_type_btn").click(function(e){
        e.preventDefault();
        var id = $("#user_id").val();
        var user_type = $("#user_type").val();
        $.ajax({
            url: '{{ route("admin.users.settype") }}',
            type:"PUT",
            data:{
                id: id,
                user_type: user_type,
                _token: "{{ csrf_token() }}",
            },
            success:function(response){
                $("#set_type_modal").modal('hide');
                NioApp.Toast('Success.', 'success');
                toastr.clear();
                window.location.href = '{{route("admin.users")}}';
            },
        });
     
    });



});
</script>

@endsection
