@extends('layouts.app')

@section('content')

<script>

    function delete_func(val) {
        document.getElementById(val).submit();
    }

</script>
<div class="page-header">
    <ol class="breadcrumb">
        <li class="breadcrumb-item active">Officetime List</li>
    </ol>
</div>

<div class="content-wrapper"> 
    <div class="row">
        <div class="offset-md-7 col-md-2">
           
        </div>
        <div class="col-md-2">
           
        </div>
        <div class="col-md-1 text-right">
            <a href="#" id="new_service_btn" class="btn btn-icon btn-primary" data-toggle="modal" data-target="#add_officetime_modal"><span class="icon-plus1"></span></a>
        </div>
    </div>   
    <div class="row">        
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="datatable table">
                        <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Day of week</th>
                                    <th>From</th>
                                    <th>To</th>
                                    <th>
                                        Action
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach($officetimes as $officetime)
                                <tr>
                                    <td><span>{{ $officetime->name }}</span></td>
                                    <td><span>{{ $officetime->email }}</span></td>
                                    <td>
                                        @if($officetime->day == 0)
                                            <span>Sunday</span>
                                        @elseif($officetime->day == 1)
                                            <span>Monday</span>
                                        @elseif($officetime->day == 2)
                                            <span>Tuesday</span>
                                        @elseif($officetime->day == 3)
                                            <span>Wednesday</span>
                                        @elseif($officetime->day == 4)
                                            <span>Thursday</span>
                                        @elseif($officetime->day == 5)
                                            <span>Friday</span>
                                        @elseif($officetime->day == 6)
                                            <span>Saturday</span>
                                        @endif

                                    </td>
                                    <td><span>{{ $officetime->from }}</span></td>
                                    <td><span>{{ $officetime->to }}</span></td>
                                    <td class="text-center">
                                        <div class="btn-group btn-group-sm">
                                            <button type="button"  data-toggle="modal" data-target="#edit_officetime_modal"  data-id="{{ json_encode($officetime) }}" class="btn btn-info">
                                                <i class="icon-edit1"></i>
                                            </button>
                                            <button  onclick="delete_func('delete_frm_{{ $officetime->id }}')"  type="button" class="btn btn-danger">
                                                <form action="{{ route('admin.officetime.destroy', $officetime->id)}}" name="delete_frm_{{ $officetime->id }}" id="delete_frm_{{ $officetime->id }}" method="post">
                                                    @csrf
                                                    @method('DELETE')
                                                    <i class="icon-cancel"></i>
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
</div>

<div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" id="add_officetime_modal">
    <div class="modal-dialog modal-lg" role="document">
        
        <div class="modal-content">
            <div  class="modal-header">
                <h5 class="modal-title" id="basicModalLabel">Add Office time</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row gy-4">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">Doctor</label>
                            <div class="form-control-wrap">
                                <select class="form-control form-control-lg" id="a_doctor" name="a_doctor">
                                    @foreach($doctors as $doctor)
                                        <option value="{{ $doctor->id }}">{{ $doctor->email }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">Day of week</label>
                            <div class="form-control-wrap">
                                <select class="form-control" id="a_day" name="a_day"> 
                                    <option value="0">Sunday </option>
                                    <option value="1">Monday </option>
                                    <option value="2">Tuesday </option>
                                    <option value="3">Wednesday </option>
                                    <option value="4">Thursday </option>
                                    <option value="5">Friday </option>
                                    <option value="6">Saturday </option>
                                </select>
                            </div>
                        </div>
                    </div>

                 
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label" for="default-05">From</label>
                            <div class="form-control-wrap">                                        
                                <input type="number" value = "1" min="1" max="24" id="a_from" name="a_from" class="form-control form-control-lg">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label" for="default-05">To</label>
                            <div class="form-control-wrap">                                        
                                <input type="number" value="1" min="1" max="24" id="a_to" name="a_to" class="form-control form-control-lg">
                            </div>
                        </div>
                    </div>
                </div>
            </div><!-- .modal-body -->
            <div class="modal-footer">
                <button href="#" class="btn btn-primary" id="officetime_save_btn"><span class="icon-save2"></span>&nbsp;Save</button>                                        
                <button href="#" data-dismiss="modal" class="btn btn-danger"><span class="icon-close"></span>&nbsp;Cancel</button>                                            
            </div>
        </div><!-- .modal-content -->
    </div><!-- .modal-dialog -->
</div><!-- .modal -->


<div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" id="edit_officetime_modal">
    <div class="modal-dialog modal-lg" role="document">
        
        <div class="modal-content">
            <div  class="modal-header">
                <h5 class="modal-title" id="basicModalLabel">Edit Office time</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row gy-4">
                    <input type="hidden" id="e_officetime_id" name="e_officetime_id" >
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">Doctor</label>
                            <div class="form-control-wrap">
                                <select class="form-control form-control-lg" id="e_doctor" name="e_doctor">
                                    @foreach($doctors as $doctor)
                                        <option value="{{ $doctor->id }}">{{ $doctor->email }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">Day of week</label>
                            <div class="form-control-wrap">
                                <select class="form-control" id="e_day" name="e_day"> 
                                    <option value="0">Sunday </option>
                                    <option value="1">Monday </option>
                                    <option value="2">Tuesday </option>
                                    <option value="3">Wednesday </option>
                                    <option value="4">Thursday </option>
                                    <option value="5">Friday </option>
                                    <option value="6">Saturday </option>
                                </select>
                            </div>
                        </div>
                    </div>

                 
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label" for="default-05">From</label>
                            <div class="form-control-wrap">                                        
                                <input type="number" min="1" max="24" id="e_from" name="e_from" class="form-control form-control-lg">
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label" for="default-05">To</label>
                            <div class="form-control-wrap">                                        
                                <input type="number" min="1" max="24" id="e_to" name="e_to" class="form-control form-control-lg">
                            </div>
                        </div>
                    </div>
                </div>
            </div><!-- .modal-body -->
            <div class="modal-footer">
                <button href="#" class="btn btn-primary" id="officetime_update_btn"><span class="icon-save2"></span>&nbsp;Update</button>                                        
                <button href="#" data-dismiss="modal" class="btn btn-danger"><span class="icon-close"></span>&nbsp;Cancel</button>                                            
            </div>
        </div><!-- .modal-content -->
    </div><!-- .modal-dialog -->
</div><!-- .modal -->
<script>
$(document).ready(function(){
    var table = $('.datatable').DataTable();

    $("#officetime_save_btn").click(function(e){
        e.preventDefault();
 
        $.ajax({
            url: '{{ route("admin.officetime.store") }}',
            type:"POST",
            data:{
                user_id: $("#a_doctor").val(),
                day: $("#a_day").val(),
                from: $("#a_from").val(),
                to: $("#a_to").val(),
                _token: "{{ csrf_token() }}",
            },
            success:function(response){
                $("#add_officetime_modal").modal('hide');
                window.location.href = '{{route("admin.officetime")}}';
            },
        });
        
    });


    $('#edit_officetime_modal').on('show.bs.modal', function(e) {
        var officetime_data = $(e.relatedTarget).data('id');
        $("#e_officetime_id").val(officetime_data['id']);
        $("#e_doctor").val(officetime_data['user_id']);
        $("#e_day").val(officetime_data['day']);
        $("#e_from").val(officetime_data['from']);
        $("#e_to").val(officetime_data['to']);
    });



    $("#officetime_update_btn").click(function(e){
        e.preventDefault();
        $.ajax({
            url: '{{ route("admin.officetime.store") }}',
            type:"PUT",
            data:{
                id: $("#e_officetime_id").val(),
                user_id: $("#e_doctor").val(),
                day: $("#e_day").val(),
                from: $("#e_from").val(),
                to: $("#e_to").val(),
                _token: "{{ csrf_token() }}",
            },
            success:function(response){
                $("#edit_officetime_modal").modal('hide');
                window.location.href = '{{route("admin.officetime")}}';
            },
        });
    });




});
</script>

@endsection
