@extends('layouts/layoutMaster')

@section('title', 'Services List')

@section('vendor-style')
<!-- vendor css files -->
<link rel="stylesheet" href="{{ asset('new-assets/app-assets/vendors/css/tables/datatable/datatables.min.css') }}">
<link rel="stylesheet" href="{{ asset('new-assets/app-assets/vendors/css/tables/datatable/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('new-assets/app-assets/vendors/css/tables/datatable/responsive.bootstrap4.min.css') }}">
<link href="{{ asset('assets/css/libs/jstree.css?ver=2.3.0') }}" rel="stylesheet">
@endsection

@section('page-style')
{{-- Page Css files --}}

@endsection

@section('content')

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Services List</h4>
                <div class="row my-3">
                    <div class="col-md-12 text-right">
                        <a href="#" id="new_service_btn" class="btn btn-icon btn-primary" data-toggle="modal"
                            data-target="#add_service_modal"><i data-feather="plus"></i></a>
                    </div>
                </div>

                <div class="row gy-4">
                    <div class="col-md-3">
                        <div class="row">
                            <div class="col-md-8">
                                <input type="hidden" id="created_id">
                                <div class="form-group">
                                    <div class="form-control-wrap">
                                        <input type="text" class="form-control form-control-outlined" id="root_cat"
                                            placeholder="Input Root Category">

                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <div class="form-control-wrap">
                                        <a href="#" class="btn btn-icon btn-primary" id="rootcat_save_btn"><i data-feather="plus"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="context-menu-tree">
                            <ul>
                            </ul>
                        </div>
                    </div>

                    <div class="col-md-9">
                        <div class="table-responsive">
                            <table class="datatable table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Service Name</th>
                                        <th>Price</th>
                                        <th>Note</th>
                                        <th>Category</th>
                                        <th class="tb-tnx-action">
                                            Action
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($services as $service)
                                    <tr>
                                        <td><span>{{ $service->service_name }}</span></td>
                                        <td><span>{{ $service->price }}</span></td>
                                        <td><span>{{ $service->note }}</span></td>
                                        <td><span>{{ $service->s_name }}</span></td>
                                        <td class="text-center">
                                            <div class="btn-group btn-group-sm">
                                                <button type="button" data-toggle="modal"
                                                    data-target="#edit_service_modal"
                                                    data-id="{{ json_encode($service) }}" class="btn btn-info">
                                                    {!!\App\Library\Helpers\Helper::getSvgIconeByAction('EDIT')!!}
                                                </button>
                                                <button onclick="delete_func('delete_frm_{{ $service->id }}')"
                                                    type="button" class="btn btn-danger">
                                                    <form action="{{ route('admin.services.destroy', $service->id)}}"
                                                        name="delete_frm_{{ $service->id }}"
                                                        id="delete_frm_{{ $service->id }}" method="post">
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
    </div>
</div>


<!-- @@ Add Modal @e -->
<div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" id="add_service_modal">
    <div class="modal-dialog modal-lg" role="document">

        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="basicModalLabel">Add Service</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row gy-4">
                    <input type="hidden" id="add_sel_category" value="0">
                    <div class="col-md-4">
                        <div id="add_context-menu-tree">
                            <ul>
                            </ul>
                        </div>
                    </div>

                    <div class="col-md-8">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label" for="first-name">Service Name*</label>
                                    <input type="text" id="a_service_name" name="a_service_name"
                                        class="form-control form-control-lg" placeholder="Enter Service Name" require>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label" for="default-05">Price*(USD)</label>
                                    <div class="form-control-wrap">
                                        <input type="number" min="1" max="9999999999" id="a_price" name="a_price"
                                            class="form-control form-control-lg">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="form-label" for="cf-default-textarea">Note</label>
                                    <div class="form-control-wrap">
                                        <textarea class="form-control form-control-sm" cols="30" rows="5" id="a_note"
                                            name="a_note" placeholder="Enter Note"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div><!-- .tab-pane -->
                </div><!-- .tab-content -->
            </div><!-- .modal-body -->
            <div class="modal-footer">
                <button href="#" class="btn btn-primary" id="service_save_btn"><i data-feather="save"></i>&nbsp;Save</button>
                <button href="#" data-dismiss="modal" class="btn btn-danger"><i data-feather="x"></i>&nbsp;Cancel</button>
            </div>
        </div><!-- .modal-content -->
    </div><!-- .modal-dialog -->
</div><!-- .modal -->







<!-- @@ Edit Modal @e -->
<div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" id="edit_service_modal">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="basicModalLabel">Edit Service</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body modal-body-lg">

                <input type="hidden" id="e_service_id" name="e_service_id">
                <div class="modal-body">
                    <div class="row gy-4">
                        <input type="hidden" id="edit_sel_category" value="0">
                        <div class="col-md-4">
                            <div id="edit_context-menu-tree">
                                <ul>
                                </ul>
                            </div>
                        </div>

                        <div class="col-md-8">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label" for="first-name">Service Name*</label>
                                        <input type="text" id="e_service_name" name="e_service_name"
                                            class="form-control form-control-lg" placeholder="Enter Service Name"
                                            require>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label" for="default-05">Price* (USD)</label>
                                        <div class="form-control-wrap">
                                            <input type="number" min="1" max="9999999999" id="e_price" name="e_price"
                                                class="form-control form-control-lg">
                                        </div>
                                    </div>
                                </div>


                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="form-label" for="cf-default-textarea">Note</label>
                                        <div class="form-control-wrap">
                                            <textarea class="form-control form-control-sm" cols="30" rows="5"
                                                id="e_note" name="e_note" placeholder="Enter Note"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div><!-- .tab-pane -->

                </div><!-- .tab-content -->
            </div><!-- .modal-body -->
            <div class="modal-footer">
                <button href="#" class="btn btn-primary" id="service_update_btn"><i data-feather="save"></i>&nbsp;Update</button>
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
<script src="{{ asset('new-assets/app-assets/vendors/js/extensions/sweetalert2.all.min.js') }}"></script>
<script src="{{ asset('assets/js/libs/jstree.js?ver=2.3.0') }}"></script>
@endsection
@section('page-script')
<script src="{{ asset('new-assets/js/main.js') }}"></script>
<script>
$(document).ready(function() {

/////////////////////////////////////////////////////////////////////////////
////////////////////////@S service Home////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////
var table = $('.datatable').DataTable();
$("#context-menu-tree").jstree({
    "core": {
        "data": {!!$datas!!},
        // 'data' : [
        //     { "id" : "ajson1", "parent" : "#", "text" : "Simple root node" },
        //     { "id" : "ajson2", "parent" : "#", "text" : "Root node 2" },
        //     { "id" : "ajson3", "parent" : "ajson2", "text" : "Child 1" },
        //     { "id" : "ajson4", "parent" : "ajson2", "text" : "Child 2" },
        // ],
        "themes": {
            "responsive": false
        },
        "check_callback": true
    },
    "plugins": ["contextmenu"],
    "contextmenu": {
        "items": function($node) {
            var tree = $("#context-menu-tree").jstree(true);
            return {
                "Create": {
                    "separator_before": false,
                    "separator_after": false,
                    "label": "Create",
                    "action": function(obj) {
                        $node = tree.create_node($node);
                        tree.edit($node);
                    }
                },
                "Rename": {
                    "separator_before": false,
                    "separator_after": false,
                    "label": "Rename",
                    "action": function(obj) {
                        tree.edit($node);
                    }
                },
                "Delete": {
                    "separator_before": false,
                    "separator_after": false,
                    "label": "Delete",
                    "action": function(obj) {
                        tree.delete_node($node);
                    }
                }
            };
        }
    }
}).bind("create_node.jstree", function(e, data) {
    $.ajax({
        url: '{{route("admin.services.category")}}',
        type: "POST",
        data: {
            parent_id: data.node.parent,
            name: data.node.text,
            _token: "{{ csrf_token() }}",
        },
        success: function(response) {
            $("#created_id").val(response['id'])
        },
    });
}).bind("rename_node.jstree", function(e, data) {
    $.ajax({
        url: '{{route("admin.services.category")}}',
        type: "PUT",
        data: {
            id: $("#created_id").val(),
            name: data.node.text,
            _token: "{{ csrf_token() }}",
        },
        success: function(response) {
            console.log(response);
        },
    });

}).bind("delete_node.jstree", function(e, data) {
    $.ajax({
        url: '{{route("admin.services.category.delete")}}',
        type: "PUT",
        data: {
            id: data.node.id,
            _token: "{{ csrf_token() }}",
        },
        success: function(response) {
            console.log(response);
        },
    });
});


$("#context-menu-tree").bind("changed.jstree", function(e, data) {
    // alert("Checked: " + data.node.id);
    // alert("Parent: " + data.node.parent); 
});

$("#rootcat_save_btn").click(function(e) {
    if ($("#root_cat").val() != "") {
        $.ajax({
            url: '{{ route("admin.services.rootcatstore") }}',
            type: "POST",
            data: {
                name: $("#root_cat").val(),
                _token: "{{ csrf_token() }}",
            },
            success: function(response) {
                //console.log(response['id']);
                _showResponseMessage("success", 'Success.');
                setTimeout(function(){ window.location.href = '{{route("admin.services")}}'; }, 1500);
            },
        });
    }

});



/////////////////////////////////////////////////////////////////////////////
////////////////////////@E service Home////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////







/////////////////////////////////////////////////////////////////////////////
////////////////////////@S service add ////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////



$("#add_context-menu-tree").jstree({
    "core": {
        "data": {!!$datas!!},
        "themes": {
            "responsive": false
        },
        "check_callback": true
    },
    "plugins": ["contextmenu"],
    "contextmenu": {
        "items": function($node) {

        }
    }
});


$("#add_context-menu-tree").bind("changed.jstree", function(e, data) {
    $("#add_sel_category").val(data.node.id);

    //alert("Checked: " + data.node.id);
    // alert("Parent: " + data.node.parent); 
});






$("#service_save_btn").click(function(e) {
    e.preventDefault();
    var service_name = $("#a_service_name").val();
    var price = $("#a_price").val();
    var note = $("#a_note").val();
    var category = $("#add_sel_category").val();

    if (service_name != "" && price != "") {
        $.ajax({
            url: '{{route("admin.services")}}',
            type: "POST",
            data: {
                service_name: service_name,
                price: price,
                note: note,
                category: category,
                _token: "{{ csrf_token() }}",
            },
            success: function(response) {
                $("#add_service_modal").modal('hide');
                NioApp.Toast('Success.', 'success');
                toastr.clear();
                window.location.href = '{{route("admin.services")}}';
            },
        });
    }
});


/////////////////////////////////////////////////////////////////////////////
////////////////////////@E service add ////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////


/////////////////////////////////////////////////////////////////////////////
////////////////////////@S service Eidt ////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////




$('#edit_service_modal').on('show.bs.modal', function(e) {

    var tmp = $('#edit_context-menu-tree').jstree(true);
    if (tmp) {
        tmp.destroy();
    }

    var service_data = $(e.relatedTarget).data('id');
    $("#e_service_id").val(service_data['id']);
    $("#e_service_name").val(service_data['service_name']);
    $("#e_price").val(service_data['price']);
    $("#e_note").val(service_data['note']);

    // alert(service_data['category_id']);

    var e_c_datas = {!!$datas!!};

    var state_child = {
        opened: true, // is the node open
        disabled: false, // is the node disabled
        selected: true // is the node selected
    };

    var state_parent = {
        opened: true, // is the node open
        disabled: false, // is the node disabled
        selected: false // is the node selected
    };

    for (var i = 0; i < e_c_datas.length; i++) {

        if (e_c_datas[i].id == service_data['category_id']) {
            e_c_datas[i].state = state_child;
        } else {
            e_c_datas[i].state = state_parent;
        }
    };


    console.log(e_c_datas);

    $("#edit_context-menu-tree").jstree({
        "core": {
            "data": e_c_datas,
            "themes": {
                "responsive": false
            },
            "check_callback": true
        },
        "plugins": ["contextmenu"],
        "contextmenu": {
            "items": function($node) {

            }
        }
    });




});


$("#edit_context-menu-tree").bind("changed.jstree", function(e, data) {
    $("#edit_sel_category").val(data.node.id);
    //alert("Checked: " + data.node.id);
    // alert("Parent: " + data.node.parent); 
});


$("#service_update_btn").click(function(e) {
    e.preventDefault();
    var id = $("#e_service_id").val();
    var service_name = $("#e_service_name").val();
    var price = $("#e_price").val();
    var note = $("#e_note").val();
    var category = $("#edit_sel_category").val();

    if (service_name != "" && price != "") {
        $.ajax({
            url: '{{route("admin.services")}}',
            type: "PUT",
            data: {
                id: id,
                service_name: service_name,
                price: price,
                note: note,
                category: category,
                _token: "{{ csrf_token() }}",
            },
            success: function(response) {
                $("#edit_service_modal").modal('hide');
                NioApp.Toast('Success.', 'success');
                toastr.clear();
                window.location.href = '{{route("admin.services")}}';
            },
        });
    }
});


/////////////////////////////////////////////////////////////////////////////
////////////////////////@Eservice add ////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////


});
function delete_func(val) {
    document.getElementById(val).submit();
}
</script>
@endsection
