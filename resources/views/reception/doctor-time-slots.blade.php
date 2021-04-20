@if(isset($slots) && count($slots)>0)
@foreach($slots as $schedule)
@php
$dt = Carbon\Carbon::createFromFormat('Y-m-d H:i:s',$schedule->slot);
$slot = $dt->format('H:i');
@endphp
<div class="form-check form-check-inline">
  <input class="form-check-input" type="radio" name="SLOT" id="inlineRadio{{ $schedule->id }}" value="{{ $slot }}" @if(in_array($slot,$bookedSlots)) disabled @endif>
  <label class="form-check-label" for="inlineRadio{{ $schedule->id }}">{{ $slot }}</label>
</div>
@endforeach
<script>
var default_slot = $("#INPUT_DEFAULT_SLOT").val();
if(default_slot){
  $("input[name=SLOT][value='" + default_slot + "']").prop('checked', true);
  $("input[name=SLOT][value='" + default_slot + "']").prop("disabled", false);
}
</script>
@else
<div class="alert alert-warning" role="alert">
    <div class="alert-body">Not Available</div>
</div>
@endif