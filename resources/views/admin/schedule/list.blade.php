@if(count($schedules)>0)
@foreach($schedules as $schedule)
@php
$dt = Carbon\Carbon::createFromFormat('Y-m-d H:i:s',$schedule->slot);
$slot = $dt->format('H:i');
@endphp

<button type="button" class="btn btn-light">{{ $slot }}</button>
@endforeach
@endif