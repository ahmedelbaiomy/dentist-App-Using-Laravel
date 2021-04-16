<!--begin::Modal Content-->
<div class="modal fade text-left" id="{{ $id }}" role="dialog" aria-labelledby="{{$formName}}_MODAL_TITLE"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="FORM_{{ $formName }}">
                <div class="modal-header">
                    <h4 class="modal-title">{!!\App\Library\Helpers\Helper::getSvgIconeByAction('EDIT')!!} <span
                            id="{{$formName}}_MODAL_TITLE"></span></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="{{ $content }}">

                </div>
                <div class="modal-footer">
                    <button type="submit"
                        class="btn btn-sm btn-outline-primary">
                        {!!\App\Library\Helpers\Helper::getSvgIconeByAction('SAVE')!!}
                        <span class="align-middle">Save</span>
                        <span id="SPAN_SAVE" class="" role="status" aria-hidden="true"></span>
                    </button>
                    <button type="button" data-dismiss="modal" class="btn btn-sm btn-outline-danger">
                        {!!\App\Library\Helpers\Helper::getSvgIconeByAction('CANCEL')!!}
                        <span class="align-middle">Cancel</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
</div>
<!--end::Modal Content-->