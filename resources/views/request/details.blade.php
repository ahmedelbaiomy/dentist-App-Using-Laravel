@php
$cssArray=array('draft'=>'warning','sent'=>'success');
$dtSentDate='';
if($request->sent_at){
$dtSentDate = Carbon\Carbon::createFromFormat('Y-m-d H:i:s',$request->sent_at);
}
@endphp
<p>
    Status : <span class="badge badge-light-{{$cssArray[$request->status]}}">{{$request->status}}</span>
    @if($dtSentDate)
    at <span class="badge badge-light-success">{{$dtSentDate->format('Y-m-d H:i:s')}}</span>
    @endif
</p>
<p>Requested By: <strong>{{$request->user->name}}</strong> to <strong>{{$request->to}}</strong></p>
<p>Subject: <strong>{{$request->subject}}</strong></p>
<p>Message : </p>
<p>{{$request->message}}</p>

<div class="card shadow-none bg-transparent border-primary">
    <div class="card-body">
    <h4 class="card-title">1 - Requested products : </h4>
        <div class="table-responsive">
            <table id="requests_datatable" class="table table-bordered">
                <thead>
                    <tr>
                        <th>Item</th>
                        <th>Description</th>
                        <th>Quantity</th>
                        <th style="text-align: right;">Rate</th>
                        <th style="text-align: right;">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @if(count($items))
                    @foreach($items as $item)
                    <tr>
                        <td>
                            {{$item->product_name}}
                        </td>
                        <td>
                            {{$item->description}}
                        </td>
                        <td>
                            {{$item->quantity}}
                        </td>
                        <td style="text-align: right;">
                            ${{number_format($item->rate,2)}}</td>
                        <td style="text-align: right;">
                            ${{number_format($item->total,2)}}</td>
                    </tr>
                    @endforeach
                    @endif
                    <tr>
                        <td colspan="4" style="text-align: right;"><strong>Total</strong></td>
                        <td style="text-align: right;"><strong>${{number_format($total,2)}}</strong></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>