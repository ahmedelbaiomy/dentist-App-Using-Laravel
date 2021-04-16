{{ csrf_field() }}
<input type="text" name="day" value="{{ $day }}">
<input type="text" name="doctor_id" value="{{ $doctor_id}}">

<div class="row">
    <div class="col-md-6 form-group">
        <label for="fp-time">Start time</label>
        <input type="text" id="start-time" class="form-control flatpickr-time text-left" placeholder="HH:MM" />
    </div>
    <div class="col-md-6 form-group">
        <label for="fp-time">End time</label>
        <input type="text" id="start-time" class="form-control flatpickr-time text-left" placeholder="HH:MM" />
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