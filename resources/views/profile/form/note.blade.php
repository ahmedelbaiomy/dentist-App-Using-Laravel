{{ csrf_field() }}
<input type="hidden" name="id" value="{{ ($note)?$note->id:0 }}" />
<input type="hidden" name="patient_id" id="patient_id" value="{{$patient_id}}">

<div class="row">
    <div class="col-md-12">
        <div class="form-group">
            <label class="form-label" for="cf-default-textarea">Description</label>
            <div class="form-control-wrap">
                <textarea class="form-control form-control-sm" cols="30" rows="5" id="note" name="note"
                    placeholder="Type Your Note Here...." required>@if($note!=null){{$note->note}}@endif</textarea>
            </div>
        </div>
    </div>
</div>

<div class="card shadow-none bg-transparent border-primary mb-0">
    <div class="card-body">
        @include('profile/patient/recorder-form')
    </div>
</div>


<input class="d-none" type="submit" value="SUBMIT" id="SUBMIT_NOTE_FORM">