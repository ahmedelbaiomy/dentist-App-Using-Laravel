@if($total>0)
    @if($notifications)
    @foreach($notifications as $notification)
    <a class="d-flex" href="{{url($url_view.$notification->id)}}">
        <div class="media d-flex align-items-start">
            <div class="media-left">
                <div class="avatar bg-light-success">
                    <div class="avatar-content"><i class="avatar-icon" data-feather="check"></i>
                    </div>
                </div>
            </div>
            <div class="media-body">
                @php
                if(Auth::user()->user_type == "reception"){
                $header_text=$notification->message_type==10?'Appointment Booked':'Appointment Requested';
                }
                @endphp
                <p class="media-heading">{{$header_text}}</p><small class="notification-text">
                    {{$notification->notification}}</small>
            </div>
        </div>
    </a>
    @endforeach
    @endif

    @if($pat_notifications)
        @foreach($pat_notifications as $notific)
        <a class="d-flex" href="javascript:show_confirm('{{$notific->name}}', {{$notific->id}})">
            <div class="media d-flex align-items-start">
                <div class="media-left">
                    <div class="avatar"></div>
                </div>
                <div class="media-body">
                    <p class="media-heading"><span class="font-weight-bolder">Low rated patients detected.</span></p>
                    <small class="notification-text"> Our patient {{$notific->name}} has no appointment in last 6 months.</small>
                </div>
            </div>
        </a>
        @endforeach
    @endif
@else
<a class="d-flex" href="javascript:void(0)">
    <div class="media d-flex align-items-start">There is no notifications</div>
</a>
@endif