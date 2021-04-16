{{ csrf_field() }}
<input type="text" name="day" value="{{ $day }}">
<input type="text" name="doctor_id" value="{{ $doctor_id}}">

<div class="row">
    <div class="col-md-6 form-group">
        <label for="fp-time">Start time</label>
        <input type="text" id="started_at" name="started_at" class="form-control flatpickr-time text-left"
            placeholder="HH:MM" />
    </div>
    <div class="col-md-6 form-group">
        <label for="fp-time">End time</label>
        <input type="text" id="ended_at" name="ended_at" class="form-control flatpickr-time text-left"
            placeholder="HH:MM" />
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="form-group">
            <label for="select-doctos">Timing Slot Duration</label>
            <select class="form-control" id="select_slot_duration" name="slot_duration">
                <option value="15">15 mins</option>
                <option value="30">30 mins</option>
                <option value="40">40 mins</option>
                <option value="45">45 mins</option>
                <option value="60">1 Hour</option>
            </select>
        </div>
    </div>
</div>

<script>
var timePickr = $('.flatpickr-time');
// Time
if (timePickr.length) {
    timePickr.flatpickr({
        enableTime: true,
        noCalendar: true
    });
}
</script>