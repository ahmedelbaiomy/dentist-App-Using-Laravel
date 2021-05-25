<input type="hidden" id="VIEW_INPUT_PATIENT_ID" value="{{ $patient_id }}">
@if($viewtype=='overview')
@php
$birthday = '';
if($patient){
$dt = Carbon\Carbon::createFromFormat('Y-m-d',$patient->birthday);
$birthday = $dt->format('d/m/Y');
}

$patient_status='Open';
$patient_status_css_class='warning';
if($patient->state == 0){
$patient_status='Open';
$patient_status_css_class='success';
}elseif($patient->state == 1){
$patient_status='Complete';
$patient_status_css_class='warning';
}

@endphp

<!-- statistics billing-->
<div class="card card-statistics shadow-none bg-transparent border-primary">
    <div class="card-body statistics-body">
        <h4 class="card-title">Statistics</h4>
        <div class="row mb-2">
            <div class="col-xl-3 col-sm-6 col-12 mb-2 mb-xl-0">
                <div class="media">
                    <div class="avatar bg-light-primary mr-2">
                        <div class="avatar-content">
                            {!!\App\Library\Helpers\Helper::getSvgIconeByAction('CALENDAR')!!}
                        </div>
                    </div>
                    <div class="media-body my-auto">
                        <h4 class="font-weight-bolder mb-0" id="nb_appointments">0</h4>
                        <p class="card-text font-small-3 mb-0">Appointments</p>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6 col-12 mb-2 mb-xl-0">
                <div class="media">
                    <div class="avatar bg-light-success mr-2">
                        <div class="avatar-content">
                            {!!\App\Library\Helpers\Helper::getSvgIconeByAction('MIC')!!}
                        </div>
                    </div>
                    <div class="media-body my-auto">
                        <h4 class="font-weight-bolder mb-0" id="nb_notes">0</h4>
                        <p class="card-text font-small-3 mb-0">Notes</p>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6 col-12 mb-2 mb-xl-0">
                <div class="media">
                    <div class="avatar bg-light-warning mr-2">
                        <div class="avatar-content">
                            {!!\App\Library\Helpers\Helper::getSvgIconeByAction('ARCHIVE')!!}
                        </div>
                    </div>
                    <div class="media-body my-auto">
                        <h4 class="font-weight-bolder mb-0" id="nb_storages">0</h4>
                        <p class="card-text font-small-3 mb-0">Storage</p>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6 col-12 mb-2 mb-xl-0">
                <div class="media">
                    <div class="avatar bg-light-info mr-2">
                        <div class="avatar-content">
                            {!!\App\Library\Helpers\Helper::getSvgIconeByAction('TRENDING')!!}
                        </div>
                    </div>
                    <div class="media-body my-auto">
                        <h4 class="font-weight-bolder mb-0" id="nb_invoices">0</h4>
                        <p class="card-text font-small-3 mb-0">Invoices</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">

            <div class="col-xl-3 col-sm-6 col-12 mb-2 mb-xl-0">
                <div class="media">
                    <div class="avatar bg-light-info mr-2">
                        <div class="avatar-content">
                            {!!\App\Library\Helpers\Helper::getSvgIconeByAction('DOLLAR')!!}
                        </div>
                    </div>
                    <div class="media-body my-auto">
                        <h4 class="font-weight-bolder mb-0" id="total_invoices">$0</h4>
                        <p class="card-text font-small-3 mb-0">Total</p>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6 col-12 mb-2 mb-sm-0">
                <div class="media">
                    <div class="avatar bg-light-danger mr-2">
                        <div class="avatar-content">
                            {!!\App\Library\Helpers\Helper::getSvgIconeByAction('DOLLAR')!!}
                        </div>
                    </div>
                    <div class="media-body my-auto">
                        <h4 class="font-weight-bolder mb-0" id="total_paid_invoices">$0</h4>
                        <p class="card-text font-small-3 mb-0">Paid</p>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6 col-12">
                <div class="media">
                    <div class="avatar bg-light-success mr-2">
                        <div class="avatar-content">
                            {!!\App\Library\Helpers\Helper::getSvgIconeByAction('DOLLAR')!!}
                        </div>
                    </div>
                    <div class="media-body my-auto">
                        <h4 class="font-weight-bolder mb-0" id="total_discount">$0</h4>
                        <p class="card-text font-small-3 mb-0">Discount</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- statistics -->
<div class="card card-statistics shadow-none bg-transparent border-primary">
    <div class="card-body statistics-body">
        <h4 class="card-title">About</h4>
        <div class="row">
            <div class="col-lg-6">
                <div class="mt-2">
                    <h6 class="mb-75">Birthday:</h5>
                        <p class="card-text">{{$birthday}}</p>
                </div>
                <div class="mt-2">
                    <h6 class="mb-75">Phone:</h5>
                        <p class="card-text">{{ $patient->phone }}</p>
                </div>
                <div class="mt-2">
                    <h6 class="mb-75">Email:</h5>
                        <p class="card-text">{{ $patient->email }}</p>
                </div>
                <div class="mt-2">
                    <h6 class="mb-50">Address:</h5>
                        <p class="card-text mb-0">{{ $patient->address }}</p>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="mt-2">
                    <h6 class="mb-75">Status:</h5>
                        <p class="card-text"><span
                                class="badge badge-light-{{$patient_status_css_class}}">{{$patient_status}}</span></p>
                </div>
                <div class="mt-2">
                    <h6 class="mb-50">Created at:</h5>
                        <p class="card-text mb-0"><span
                                class="badge badge-light-primary">{{ $patient->created_at->format('Y/m/d H:i') }}</span>
                        </p>
                </div>
                <div class="mt-2">
                    <h6 class="mb-50">Updated at:</h5>
                        <p class="card-text mb-0"><span
                                class="badge badge-light-info">{{ $patient->updated_at->format('Y/m/d H:i')}}</span></p>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
_loadPatientStats();

function _loadPatientStats() {
    var spinner = '<span class="spinner-border spinner-border-sm"></span>';
    $("#nb_invoices,#total_invoices,#total_paid_invoices,#total_discount").html(spinner);
    var patient_id = $('#VIEW_INPUT_PATIENT_ID').val();
    $.ajax({
        url: "/profile/stats/invoice/" + patient_id,
        type: "GET",
        dataType: "json",
        success: function(res, status) {
            $("#nb_invoices").html(res.nb_invoices);
            $("#total_invoices").html('$' + res.total_invoices);
            $("#total_paid_invoices").html('$' + res.total_paid_invoices);
            $("#total_discount").html('$' + res.total_discount);
            //stats
            $("#nb_appointments").html(res.nb_appointments);
            $("#nb_notes").html(res.nb_notes);
            $("#nb_storages").html(res.nb_storages);
        },
    });
};
</script>
@endif

@if($viewtype=='procedures')
<div class="row">
    <div class="col-lg-12">
        <div class="card shadow-none bg-transparent border-primary">
            <div class="card-body">
                <!-- carousel -->
                <div class="jcarousel-wrapper p-1">
                    <div id="jcarousel-categories" class="jcarousel">
                        <ul>
                            @if(count($categories) > 0 )
                            @foreach($categories as $k=>$category)
                            <li class="text-center">
                                <!-- <div class="btn-group btn-group-toggle" data-toggle="buttons"> -->
                                    <label style="border:0 !important;" class="btn btn-outline-primary btn-sm mb-1">
                                        @if($category->path_icon)
                                        <p class="mb-0 text-center"><img style="height:30px"
                                                src="{{asset(base64_decode($category->path_icon))}}" class="" /></p>
                                        @endif
                                        <input type="radio" name="radio_categories" value="{{$category->id}}"
                                            id="radio_category{{$category->id}}" {{ ($k==0)?'checked':'' }} />
                                        {{$category->name}}
                                    </label>
                                <!-- </div> -->
                            </li>
                            @endforeach
                            @else
                            <li>No categories</li>
                            @endif
                        </ul>
                    </div>

                    <a href="#" class="jcarousel-control-prev">&lsaquo;</a>
                    <a href="#" class="jcarousel-control-next">&rsaquo;</a>

                    <p class="jcarousel-pagination d-none"></p>
                </div>
                <!-- carousel -->

            </div>
        </div>
    </div>
    <div class="col-md-12">
        <!-- Procedures-->
        <div class="card shadow-none bg-transparent border-primary">
            <div class="card-body" id="BLOCK_TOOTHS_PROCEDURES"></div>
        </div>
        <!-- Procedures -->
    </div>
    <div class="col-md-12">
        <!-- services-->
        <div class="card shadow-none bg-transparent border-primary">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="procedures_datatable" class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Teeth</th>
                                <th>Service</th>
                                <th>Qty</th>
                                <th>Cost</th>
                                <th>Total</th>
                                <th>Note</th>
                                <th>Action</th>
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
<script>
(function($) {
    $(function() {
        var jcarousel = $('#jcarousel-categories');

        jcarousel
            .on('jcarousel:reload jcarousel:create', function() {
                var carousel = $(this),
                    width = carousel.innerWidth();

                if (width >= 600) {
                    width = width / 6;
                } else if (width >= 350) {
                    width = width / 2;
                }

                carousel.jcarousel('items').css('width', Math.ceil(width) + 'px');
            })
            .jcarousel({
                wrap: 'circular'
            });

        $('.jcarousel-control-prev')
            .jcarouselControl({
                target: '-=1'
            });

        $('.jcarousel-control-next')
            .jcarouselControl({
                target: '+=1'
            });

        $('.jcarousel-pagination')
            .on('jcarouselpagination:active', 'a', function() {
                $(this).addClass('active');
            })
            .on('jcarouselpagination:inactive', 'a', function() {
                $(this).removeClass('active');
            })
            .on('click', function(e) {
                e.preventDefault();
            })
            .jcarouselPagination({
                perPage: 1,
                item: function(page) {
                    return '<a href="#' + page + '">' + page + '</a>';
                }
            });
    });
})(jQuery);

var patient_id = $('#VIEW_INPUT_PATIENT_ID').val();
//procedures_datatable
var dtProceduresUrl = '/admin/sdt/procedures/' + patient_id;
var procedures_datatable = $('#procedures_datatable');
procedures_datatable.DataTable({
    responsive: true,
    processing: true,
    paging: true,
    ordering: true,
    ajax: {
        url: dtProceduresUrl,
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
var _reload_procedures_datatable = function() {
    $('#procedures_datatable').DataTable().ajax.reload();
}

function _formProcedureServiceItem(procedure_service_item_id, teeth_id) {

    var patient_id = $("#INPUT_HIDDEN_PATIENT_ID").val();
    var doctor_id = 0;

    var modal_id = "modal_form_procedure_service_item";
    var modal_content_id = "modal_form_procedure_service_item_content";
    var spinner =
        '<div class="modal-body"><center><div class="spinner-border text-primary text-center" role="status"><span class="sr-only">Loading...</span></div></center></div>';
    $("#" + modal_id).modal("show");
    $("#" + modal_content_id).html(spinner);
    var modalTitle = ((procedure_service_item_id > 0) ? 'Edit item' : 'New item') + ' : Selected teeth - ' + teeth_id;
    $("#ITEM_MODAL_TITLE").html('{!!\App\Library\Helpers\Helper::getSvgIconeByAction('
        EDIT ')!!} ' + modalTitle);
    $.ajax({
        url: "/admin/form/procedure/service/item/" + procedure_service_item_id + "/" + teeth_id + "/" +
            patient_id + "/" + doctor_id,
        type: "GET",
        dataType: "html",
        success: function(html, status) {
            $("#" + modal_content_id).html(html);
            category_id = $('input[name=radio_categories]:checked').val();
            doctor_id = 0;
            _loadDatasServicesForSelectOptions('select_services', category_id, 0);
            _loadDatasDoctorsForSelectOptions('select_doctors', doctor_id, 0);
        },
    });
};
$("#FORM_ITEM").validate({
    rules: {},
    messages: {},
    submitHandler: function(form) {
        $("#SPAN_SAVE_ITEM").addClass("spinner-border spinner-border-sm");
        var formData = $(form).serializeArray(); // convert form to array
        $.ajax({
            type: "POST",
            url: "/admin/form/procedure/service/item",
            data: formData,
            dataType: "JSON",
            success: function(result) {
                if (result.success) {
                    _showResponseMessage("success", result.msg);
                    $("#modal_form_procedure_service_item").modal("hide");
                } else {
                    _showResponseMessage("error", result.msg);
                }
            },
            error: function(error) {
                _showResponseMessage(
                    "error",
                    "Oooops..."
                );
            },
            complete: function(resultat, statut) {
                $("#SPAN_SAVE_ITEM").removeClass("spinner-border spinner-border-sm");
                _reload_procedures_datatable();
            },
        });
        return false;
    },
});

function calculateTotalPriceService(service_id) {
    if (service_id > 0) {
        $.ajax({
            url: '/admin/get/price/service/' + service_id,
            dataType: 'json',
            success: function(response) {
                quantity = $('#quantity').val();
                var rate = response.price;
                $('#rate').val(rate);
                var total = quantity * rate;
                $('#total').val(total);
            },
        }).done(function() {});
    }
}

function onChangeQuantity() {
    quantity = $('#quantity').val();
    var rate = $('#rate').val();
    var total = quantity * rate;
    $('#total').val(total);
}

function _loadDatasServicesForSelectOptions(select_id, category_id, selected_value = 0) {
    $.ajax({
        url: '/admin/select/json/services/' + category_id,
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
        var service_id = $('#select_services').val();
        calculateTotalPriceService(service_id);
    });
}

function _loadDatasDoctorsForSelectOptions(select_id, doctor_id, selected_value = 0) {
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
    });
}

_loadProceduresTooths();

function _loadProceduresTooths() {
    var spinner =
        '<center><div class="spinner-border text-primary text-center" role="status"><span class="sr-only">Loading...</span></div></center>';
    $('#BLOCK_TOOTHS_PROCEDURES').html(spinner);
    $.ajax({
        url: "/profile/get/procedures/tooths",
        type: "GET",
        dataType: "html",
        success: function(html, status) {
            $('#BLOCK_TOOTHS_PROCEDURES').html(html);
        },
    });
}
</script>
@endif
@if($viewtype=='notes')
<div class="card shadow-none bg-transparent border-primary">
    <div class="card-body">
        <div class="row mb-2">
            <div class="col-md-10"></div>
            <div class="col-md-2 text-right">
                <button onclick="_formNote({{ $patient_id }},0)"
                    class="btn btn-icon btn-outline-primary">{!!\App\Library\Helpers\Helper::getSvgIconeByAction('NEW')!!}</button>
            </div>
        </div>
        <!-- Notes -->
        <div class="table-responsive">
            <table id="notes_datatable" class="table table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>By</th>
                        <th>Description</th>
                        <th>Attachment</th>
                        <th>Created</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
        <!-- Notes -->
    </div>
</div>
<script>
var patient_id = $('#VIEW_INPUT_PATIENT_ID').val();
/* nOTES */
var dtUrl = '/profile/sdt/notes/' + patient_id;
var notes_datatable = $('#notes_datatable');
notes_datatable.DataTable({
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
var _reload_notes_datatable = function() {
    $('#notes_datatable').DataTable().ajax.reload();
}

function _formNote(patient_id, note_id) {
    var modal_id = "modal_form_note";
    var modal_content_id = "modal_form_note_body";
    var spinner =
        '<div class="modal-body"><center><div class="spinner-border text-primary text-center" role="status"><span class="sr-only">Loading...</span></div></center></div>';
    $("#" + modal_id).modal("show");
    $("#" + modal_content_id).html(spinner);
    var modalTitle = (note_id > 0) ? $("#INPUT_HIDDEN_EDIT_NOTE").val() : $("#INPUT_HIDDEN_NEW_NOTE").val();
    $("#NOTE_MODAL_TITLE").html('{!!\App\Library\Helpers\Helper::getSvgIconeByAction('
        EDIT ')!!} ' + modalTitle);
    $.ajax({
        url: "/profile/form/note/" + patient_id + '/' + note_id,
        type: "GET",
        dataType: "html",
        success: function(html, status) {
            $("#" + modal_content_id).html(html);
        },
    });
};

function _reset_note_form() {
    var modal_content_id = "modal_form_note_body";
    stopRecording();
    $("#" + modal_content_id).html('<p></p>');
}

$("#FORM_NOTE").validate({
    rules: {},
    messages: {},
    submitHandler: function(form) {
        var formDataToUpload = new FormData(form);
        $("#SPAN_SAVE").addClass("spinner-border spinner-border-sm");
        var fileUrl = $('#BLOB_FILE').val();
        if (fileUrl != '') {
            var block = fileUrl.split(";");
            // Get the content type of the image
            var contentType = block[0].split(":")[1]; // In this case "image/gif"
            // get the real base64 content of the file
            var realData = block[1].split(",")[1]; // In this case "R0lGODlhPQBEAPeoAJosM...."
            // Convert it to a blob to upload
            var blob = b64toBlob(realData, contentType);
            var filename = Math.floor(Date.now() / 1000);
            formDataToUpload.append("audio_data", blob, filename + ".wav");
        }
        $.ajax({
            type: "POST",
            dataType: 'json',
            data: formDataToUpload,
            url: '/profile/form/note',
            cache: false,
            contentType: false,
            processData: false,
            success: function(response) {
                if (response.success) {
                    $("#modal_form_note").modal('hide');
                    _showResponseMessage("success", response.msg);
                    _reload_notes_datatable();
                    $("#SPAN_SAVE").removeClass("spinner-border spinner-border-sm");
                } else {
                    _showResponseMessage("error", response.msg);
                }
            },
            error: function() {}
        }).done(function(data) {});
        return false;
    },
});

function _submit_note_form() {
    var fileUrl = $('#BLOB_FILE').val();
    if (fileUrl != '') {
        $("#SUBMIT_NOTE_FORM").click();
    } else {
        $.ajax({
            url: stopRecording(),
            success: function() {
                $("#SUBMIT_NOTE_FORM").click();
            }
        });
    }
}

function _deleteNote(id) {
    var successMsg = "Your note has been deleted.";
    var errorMsg = "Your note has not been deleted.";
    var swalConfirmTitle = "Are you sure you want to delete?";
    var swalConfirmText = "You can't go back!";
    Swal.fire({
        title: swalConfirmTitle,
        text: swalConfirmText,
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Yes, delete it!",
        customClass: {
            confirmButton: "btn btn-primary",
            cancelButton: "btn btn-outline-danger ml-1",
        },
        buttonsStyling: false,
    }).then(function(result) {
        if (result.value) {
            $.ajax({
                url: "/profile/delete/note/" + id,
                type: "DELETE",
                cache: false,
                data: {
                    _token: $('meta[name="csrf-token"]').attr("content")
                },
                dataType: "JSON",
                success: function(result, status) {
                    if (result.success) {
                        _showResponseMessage("success", successMsg);
                    } else {
                        _showResponseMessage("error", errorMsg);
                    }
                },
                error: function(result, status, error) {
                    _showResponseMessage("error", errorMsg);
                },
                complete: function(result, status) {
                    _reload_notes_datatable();
                },
            });
        }
    });
}

function b64toBlob(b64Data, contentType, sliceSize) {
    contentType = contentType || '';
    sliceSize = sliceSize || 512;

    var byteCharacters = atob(b64Data);
    var byteArrays = [];

    for (var offset = 0; offset < byteCharacters.length; offset += sliceSize) {
        var slice = byteCharacters.slice(offset, offset + sliceSize);

        var byteNumbers = new Array(slice.length);
        for (var i = 0; i < slice.length; i++) {
            byteNumbers[i] = slice.charCodeAt(i);
        }

        var byteArray = new Uint8Array(byteNumbers);

        byteArrays.push(byteArray);
    }

    var blob = new Blob(byteArrays, {
        type: contentType
    });
    return blob;
}
</script>
@endif
@if($viewtype=='billings')

<!-- statistics -->
<div class="card card-statistics shadow-none bg-transparent border-primary">
    <div class="card-body statistics-body">
        <div class="row">
            <div class="col-xl-3 col-sm-6 col-12 mb-2 mb-xl-0">
                <div class="media">
                    <div class="avatar bg-light-primary mr-2">
                        <div class="avatar-content">
                            {!!\App\Library\Helpers\Helper::getSvgIconeByAction('TRENDING')!!}
                        </div>
                    </div>
                    <div class="media-body my-auto">
                        <h4 class="font-weight-bolder mb-0" id="nb_invoices">0</h4>
                        <p class="card-text font-small-3 mb-0">invoices</p>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6 col-12 mb-2 mb-xl-0">
                <div class="media">
                    <div class="avatar bg-light-info mr-2">
                        <div class="avatar-content">
                            {!!\App\Library\Helpers\Helper::getSvgIconeByAction('DOLLAR')!!}
                        </div>
                    </div>
                    <div class="media-body my-auto">
                        <h4 class="font-weight-bolder mb-0" id="total_invoices">$0</h4>
                        <p class="card-text font-small-3 mb-0">Total</p>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6 col-12 mb-2 mb-sm-0">
                <div class="media">
                    <div class="avatar bg-light-danger mr-2">
                        <div class="avatar-content">
                            {!!\App\Library\Helpers\Helper::getSvgIconeByAction('DOLLAR')!!}
                        </div>
                    </div>
                    <div class="media-body my-auto">
                        <h4 class="font-weight-bolder mb-0" id="total_paid_invoices">$0</h4>
                        <p class="card-text font-small-3 mb-0">Paid</p>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6 col-12">
                <div class="media">
                    <div class="avatar bg-light-success mr-2">
                        <div class="avatar-content">
                            {!!\App\Library\Helpers\Helper::getSvgIconeByAction('DOLLAR')!!}
                        </div>
                    </div>
                    <div class="media-body my-auto">
                        <h4 class="font-weight-bolder mb-0" id="total_discount">$0</h4>
                        <p class="card-text font-small-3 mb-0">Discount</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- statistics -->
<!-- invoices -->
<div class="card shadow-none bg-transparent border-primary">
    <div class="card-body">
        <div class="row mb-2">
            <div class="col-md-10">
                <h4>Patient invoices</h4>
            </div>
            <div class="col-md-2 text-right">
                <button onclick="_formInvoice({{ $patient_id }},0)"
                    class="btn btn-icon btn-outline-primary">{!!\App\Library\Helpers\Helper::getSvgIconeByAction('NEW')!!}</button>
            </div>
        </div>
        <div class="table-responsive">
            <table id="invoices_datatable" class="table table-bordered">
                <thead>
                    <tr>
                        <th>Code</th>
                        <th>Infos</th>
                        <!-- <th>Doctor</th>
                        <th>Patient</th> -->
                        <th>Total</th>
                        <th>Paid</th>
                        <!-- <th>Dates</th> -->
                        <!-- <th>Bill date</th>
                        <th>Issue Date</th> -->
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>
<!-- invoices -->
<script>
var patient_id = $('#VIEW_INPUT_PATIENT_ID').val();
/* nOTES */
var dtUrl = '/profile/sdt/invoices/' + patient_id;
var invoices_datatable = $('#invoices_datatable');
invoices_datatable.DataTable({
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
var _reload_invoices_datatable = function() {
    $('#invoices_datatable').DataTable().ajax.reload();
}

function _formInvoice(patient_id, invoice_id) {
    var modal_id = "modal_form_invoice";
    var modal_content_id = "modal_form_invoice_content";
    var spinner =
        '<div class="modal-body"><center><div class="spinner-border text-primary text-center" role="status"><span class="sr-only">Loading...</span></div></center></div>';
    $("#" + modal_id).modal("show");
    $("#" + modal_content_id).html(spinner);
    var modalTitle = (invoice_id > 0) ? 'Edit invoice' : 'Add invoice';
    $("#INVOICE_MODAL_TITLE").html(modalTitle);
    $.ajax({
        url: "/profile/form/invoice/" + patient_id + '/' + invoice_id,
        type: "GET",
        dataType: "html",
        success: function(html, status) {
            $("#" + modal_content_id).html(html);
            doctor_id = 0;
            _loadDatasDoctorsForSelectOptions('select_doctors_invoice', doctor_id, 0);
        },
    });
};

$("#FORM_INVOICE").validate({
    rules: {},
    messages: {},
    submitHandler: function(form) {
        $("#SPAN_SAVE_INVOICE").addClass("spinner-border spinner-border-sm");
        var formData = $(form).serializeArray(); // convert form to array
        $.ajax({
            type: "POST",
            url: "/profile/form/invoice",
            data: formData,
            dataType: "JSON",
            success: function(result) {
                if (result.success) {
                    _showResponseMessage("success", result.msg);
                    //$("#modal_form_invoice").modal("hide");
                    var patient_id = $('#VIEW_INPUT_PATIENT_ID').val();
                    _formInvoice(patient_id, result.invoice_id);
                } else {
                    _showResponseMessage("error", result.msg);
                }
            },
            error: function(error) {
                _showResponseMessage(
                    "error",
                    "Oooops..."
                );
            },
            complete: function(resultat, statut) {
                $("#SPAN_SAVE_INVOICE").removeClass("spinner-border spinner-border-sm");
                _reload_invoices_datatable();
                _loadBillingStats();
            },
        });
        return false;
    },
});

function _loadDatasDoctorsForSelectOptions(select_id, doctor_id, selected_value = 0) {
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
    });
}

function _formPayment(payment_id, invoice_id) {
    var modal_id = "modal_form_payment";
    var modal_content_id = "modal_form_payment_content";
    var spinner =
        '<div class="modal-body"><center><div class="spinner-border text-primary text-center" role="status"><span class="sr-only">Loading...</span></div></center></div>';
    $("#" + modal_id).modal("show");
    $("#" + modal_content_id).html(spinner);
    var modalTitle = (payment_id > 0) ? 'Edit payment' : 'Add payment';
    $("#PAYMENT_MODAL_TITLE").html(modalTitle);
    $.ajax({
        url: "/profile/form/payment/" + payment_id + '/' + invoice_id,
        type: "GET",
        dataType: "html",
        success: function(html, status) {
            $("#" + modal_content_id).html(html);
        },
    });
};
$("#FORM_PAYMENT").validate({
    rules: {},
    messages: {},
    submitHandler: function(form) {
        $("#SPAN_SAVE_PAYMENT").addClass("spinner-border spinner-border-sm");
        var formData = $(form).serializeArray(); // convert form to array
        $.ajax({
            type: "POST",
            url: "/profile/form/payment",
            data: formData,
            dataType: "JSON",
            success: function(result) {
                if (result.success) {
                    _showResponseMessage("success", result.msg);
                    $("#modal_form_payment").modal("hide");
                } else {
                    _showResponseMessage("error", result.msg);
                }
            },
            error: function(error) {
                _showResponseMessage(
                    "error",
                    "Oooops..."
                );
            },
            complete: function(resultat, statut) {
                $("#SPAN_SAVE_PAYMENT").removeClass("spinner-border spinner-border-sm");
                _reload_invoices_datatable();
            },
        });
        return false;
    },
});

_loadBillingStats();
function _loadBillingStats() {
    var spinner = '<span class="spinner-border spinner-border-sm"></span>';
    $("#nb_invoices,#total_invoices,#total_paid_invoices,#total_discount").html(spinner);
    var patient_id = $('#VIEW_INPUT_PATIENT_ID').val();
    $.ajax({
        url: "/profile/stats/invoice/" + patient_id,
        type: "GET",
        dataType: "json",
        success: function(res, status) {
            $("#nb_invoices").html(res.nb_invoices);
            $("#total_invoices").html('$' + res.total_invoices);
            $("#total_paid_invoices").html('$' + res.total_paid_invoices);
            $("#total_discount").html('$' + res.total_discount);
        },
    });
};


function _formRefund(refund_id, invoice_id) {
    var modal_id = "modal_form_refund";
    var modal_content_id = "modal_form_refund_content";
    var spinner =
        '<div class="modal-body"><center><div class="spinner-border text-primary text-center" role="status"><span class="sr-only">Loading...</span></div></center></div>';
    $("#" + modal_id).modal("show");
    $("#" + modal_content_id).html(spinner);
    var modalTitle = (refund_id > 0) ? 'Edit refund' : 'Add refund';
    $("#REFUND_MODAL_TITLE").html(modalTitle);
    $.ajax({
        url: "/profile/form/refund/" + refund_id + '/' + invoice_id,
        type: "GET",
        dataType: "html",
        success: function(html, status) {
            $("#" + modal_content_id).html(html);
        },
    });
};
$("#FORM_REFUND").validate({
    rules: {},
    messages: {},
    submitHandler: function(form) {
        $("#SPAN_SAVE_REFUND").addClass("spinner-border spinner-border-sm");
        var formData = $(form).serializeArray(); // convert form to array
        $.ajax({
            type: "POST",
            url: "/profile/form/refund",
            data: formData,
            dataType: "JSON",
            success: function(result) {
                if (result.success) {
                    _showResponseMessage("success", result.msg);
                    $("#modal_form_refund").modal("hide");
                } else {
                    _showResponseMessage("error", result.msg);
                }
            },
            error: function(error) {
                _showResponseMessage(
                    "error",
                    "Oooops..."
                );
            },
            complete: function(resultat, statut) {
                $("#SPAN_SAVE_REFUND").removeClass("spinner-border spinner-border-sm");
                _reload_invoices_datatable();
            },
        });
        return false;
    },
});
</script>
@endif
@if($viewtype=='storages')
<div class="card shadow-none bg-transparent border-primary">
    <div class="card-body">
        <!-- <p>Storage</p> -->
        <div class="row mb-2">
            <div class="col-md-10">
            </div>
            <div class="col-md-2 text-right">
                <button class="btn btn-icon btn-outline-primary" data-toggle="modal"
                    data-target="#modal_form_storage">{!!\App\Library\Helpers\Helper::getSvgIconeByAction('NEW')!!}</button>
            </div>
        </div>
        <div class="table-responsive">
            <table id="storage_datatable" class="table table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>By</th>
                        <th>Title</th>
                        <th>Description</th>
                        <th>Attachment</th>
                        <th>Created</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>
<script>
function setup_camera() {
    $('#data_uri_hidden').val('');
    Webcam.reset();
    Webcam.attach('#BLOCK_CAMERA');
    hide_upload_file();
}

function take_photo() {
    // take snapshot and get image data
    Webcam.snap(function(data_uri) {
        // display results in page
        $('#results').html('<img class="img-fluid" src="' + data_uri + '"/>');
        $('#data_uri_hidden').val(data_uri);
    });
}

function show_upload_file() {
    Webcam.reset('#BLOCK_CAMERA');
    $("#BLOCK_CAMERA").removeAttr("style");
    $('#data_uri_hidden').val('');
    $('#BLOCK_UPLOAD_FILE').html('<input type="file" class="form-control-file" name="file" id="file" required />');
}

function hide_upload_file() {
    $('#BLOCK_UPLOAD_FILE').html('');
}
/* storageS */
var dtStorageUrl = '/profile/sdt/storages/{{ $patient_id }}';
var storage_datatable = $('#storage_datatable');
storage_datatable.DataTable({
    responsive: true,
    processing: true,
    paging: true,
    ordering: false,
    ajax: {
        url: dtStorageUrl,
        type: 'POST',
        data: {
            pagination: {
                perpage: 50,
            },
        },
    },
    lengthMenu: [5, 10, 25, 50],
    pageLength: 25,
    drawCallback: function(settings) {
        $("a.fancybox-file").fancybox();
    },
});
var _reload_storages_datatable = function() {
    $('#storage_datatable').DataTable().ajax.reload();
}
/* Storage form */
$("#FORM_STORAGE").validate({
    rules: {},
    messages: {},
    submitHandler: function(form) {

        $("#SPAN_SAVE_STORAGE").addClass("spinner-border spinner-border-sm");
        var fileUrl = $('#data_uri_hidden').val();
        if (fileUrl != '') {
            var formData = new FormData(form);
            var block = fileUrl.split(";");
            // Get the content type of the image
            var contentType = block[0].split(":")[1]; // In this case "image/gif"
            // get the real base64 content of the file
            var realData = block[1].split(",")[1]; // In this case "R0lGODlhPQBEAPeoAJosM...."
            // Convert it to a blob to upload
            var blob = b64toBlob(realData, contentType);
            var filename = Math.floor(Date.now() / 1000);
            formData.append("file", blob, filename + ".jpeg");
        } else {
            var formData = new FormData($(form)[0]);
        }
        $.ajax({
            type: "POST",
            dataType: 'json',
            data: formData,
            url: '/profile/form/storage',
            cache: false,
            contentType: false,
            processData: false,
            success: function(response) {
                if (response.success) {
                    $("#modal_form_storage").modal('hide');
                    _showResponseMessage("success", response.msg);
                    _reload_storages_datatable();
                    $("#SPAN_SAVE_STORAGE").removeClass("spinner-border spinner-border-sm");
                } else {
                    _showResponseMessage("error", response.msg);
                }
            },
            error: function() {}
        }).done(function(data) {});
        return false;
    },
});

function _deletePatientStorage(id) {
    var successMsg = "Your file has been deleted.";
    var errorMsg = "Your file has not been deleted.";
    var swalConfirmTitle = "Are you sure you want to delete?";
    var swalConfirmText = "You can't go back!";
    Swal.fire({
        title: swalConfirmTitle,
        text: swalConfirmText,
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Yes, delete it!",
        customClass: {
            confirmButton: "btn btn-primary",
            cancelButton: "btn btn-outline-danger ml-1",
        },
        buttonsStyling: false,
    }).then(function(result) {
        if (result.value) {
            $.ajax({
                url: "/profile/delete/storage/" + id,
                type: "DELETE",
                cache: false,
                data: {
                    _token: $('meta[name="csrf-token"]').attr("content")
                },
                dataType: "JSON",
                success: function(result, status) {
                    if (result.success) {
                        _showResponseMessage("success", successMsg);
                    } else {
                        _showResponseMessage("error", errorMsg);
                    }
                },
                error: function(result, status, error) {
                    _showResponseMessage("error", errorMsg);
                },
                complete: function(result, status) {
                    _reload_storages_datatable();
                },
            });
        }
    });
}
navigator.getMedia = (navigator.getUserMedia || // use the proper vendor prefix
    navigator.webkitGetUserMedia ||
    navigator.mozGetUserMedia ||
    navigator.msGetUserMedia);

navigator.getMedia({
    video: true
}, function() {
    // webcam is available
    console.log('webcam is available');
}, function() {
    // webcam is not available
    console.log('webcam is not available');
});
Webcam.set({
    width: 768,
    height: 576,
    image_format: 'jpeg',
    //jpeg_quality: 90
});
</script>
@endif