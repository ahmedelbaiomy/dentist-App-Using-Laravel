@extends('layouts.app')

@section('content')

<script>

    function delete_func(val) {
        document.getElementById(val).submit();
    }

</script>
<div class="page-header">
    <ol class="breadcrumb">
        <li class="breadcrumb-item active">Patient Lists - {{\Session::has('success_add_note') ? '1111' : '0'}}</li>
    </ol>
</div>
<div class="content-wrapper">  
    
    <div class="row">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="card-body">
                    <ul class="nav nav-tabs">
                        <li class="nav-item">
                            <a class="nav-link 
                            @if(!\Session::has('success_add_invoice') 
                            && !\Session::has('success_add_note') 
                            && !\Session::has('success_paid_invoice') 
                            && !\Session::has('success_add_storage')) active @endif" data-toggle="tab" href="#tabOverview"><i class="icon-event_note nav-icon"></i><span>Overview</span></a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#tabProcedures"><i class="icon-event_note nav-user"></i><span>Procedures</span></a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link @if(\Session::has('success_add_note')) active @endif" data-toggle="tab" href="#tabNotes"><i class="icon-event_note nav-user"></i><span>Notes</span></a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link @if (\Session::has('success_paid_invoice')) active @endif" data-toggle="tab" href="#tabBilling"><i class="icon-event_note nav-user"></i><span>Billing</span></a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link @if (\Session::has('success_add_storage')) active @endif" data-toggle="tab" href="#tabStorage"><i class="icon-event_note nav-user"></i><span>Storage</span></a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane 
                        @if(!\Session::has('success_add_invoice') 
                            && !\Session::has('success_add_note') 
                            && !\Session::has('success_paid_invoice') 
                            && !\Session::has('success_add_storage')) active @endif" id="tabOverview"> 
                            <div class="card">
                                <div class="card-aside-wrap">
                                    <div class="card-inner card-inner-lg">
                                        <div class="nk-block-head nk-block-head-lg">
                                            <div class="nk-block-between">
                                                <div class="nk-block-head-content">
                                                    <h4 class="nk-block-title">Personal Information</h4>
                                                    <div class="nk-block-des">
                                                        <p>Basic info, like your name and address, that you Platform.</p>
                                                    </div>
                                                </div>
                                                
                                            </div>
                                        </div><!-- .nk-block-head -->
                                        <div class="nk-block">
                                        </div><!-- .nk-block -->
                                    </div>

                                    <div class="card-aside card-aside-left user-aside toggle-slide toggle-slide-left toggle-break-lg toggle-screen-lg" data-content="userAside" data-toggle-screen="lg" data-toggle-overlay="true">
                                        <div class="card-inner-group" data-simplebar="init"><div class="simplebar-wrapper" style="margin: 0px;"><div class="simplebar-height-auto-observer-wrapper"><div class="simplebar-height-auto-observer"></div></div><div class="simplebar-mask"><div class="simplebar-offset" style="right: 0px; bottom: 0px;"><div class="simplebar-content-wrapper" style="height: auto; overflow: hidden;"><div class="simplebar-content" style="padding: 0px;">
                                            <div class="card-inner">
                                                <div class="user-card">
                                                    <div class="user-avatar bg-primary">
                                                        <span>{{ $short_name }}</span>
                                                    </div>
                                                    <div class="user-info">
                                                        <span class="lead-text">{{ $patient_data[0]->name }}</span>
                                                        <span class="sub-text">{{ $patient_data[0]->email }}</span>
                                                    </div>
                                                    
                                                </div><!-- .user-card -->
                                            </div><!-- .card-inner -->
                                            <div class="card-inner">
                                                <div class="user-account-info py-0">
                                                    <h6 class="overline-title-alt">Birthday: <small>{{ $patient_data[0]->email }}</small></h6>
                                                    <h6 class="overline-title-alt">Phone: <small>{{ $patient_data[0]->phone }}</small></h6>
                                                    <h6 class="overline-title-alt">Address: <small>{{ $patient_data[0]->address }}</small></h6>
                                                </div>
                                            </div><!-- .card-inner -->
                                            
                                        </div></div></div></div><div class="simplebar-placeholder" style="width: auto; height: 446px;"></div></div><div class="simplebar-track simplebar-horizontal" style="visibility: hidden;"><div class="simplebar-scrollbar" style="width: 0px; display: none;"></div></div><div class="simplebar-track simplebar-vertical" style="visibility: hidden;"><div class="simplebar-scrollbar" style="height: 0px; display: none; transform: translate3d(0px, 0px, 0px);"></div></div></div><!-- .card-inner-group -->
                                    </div><!-- card-aside -->
                                </div>
                            </div>
                        </div>

                        <div class="tab-pane" id="tabProcedures">
                            <div class="row">    
                                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-xs-12 mb-2">
                                    <div class="links flex-center">
                                        @foreach($services as $service)
                                            <div class="item" class="img-fluid">
                                                <a href="#" class="btn btn-outline-warning" style="border-radius: 15px; border-color: #ffc107 !important;">{{ $service->name }}</a>
                                            </div>
                                        @endforeach 
                                    </div>
                                    @if(count($services) == 0 )
                                        <div class="links flex-center">
                                            <h5 class='text-success'>No services.</h5>
                                        </div>
                                    @endif
                                </div>    
                                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="flex-center position-ref">
                                                <div class="">
                                                    <div class="links">
                                                        <div class="item">
                                                            <img src="{{ asset('assets/tooth_images/18.svg') }}" alt="" class="img-fluid">
                                                            <p>18</p>
                                                        </div>
                                                        <div class="item">
                                                            <img src="{{ asset('assets/tooth_images/17.svg') }}" alt="" class="img-fluid">
                                                            <p>17</p>
                                                        </div>
                                                        <div class="item">
                                                            <img src="{{ asset('assets/tooth_images/16.svg') }}" alt="" class="img-fluid">
                                                            <p>16</p>
                                                        </div>
                                                        <div class="item">
                                                            <img src="{{ asset('assets/tooth_images/15.svg') }}" alt="" class="img-fluid">
                                                            <p>15</p>
                                                        </div>
                                                        <div class="item">
                                                            <img src="{{ asset('assets/tooth_images/14.svg') }}" alt="" class="img-fluid">
                                                            <p>14</p>
                                                        </div>
                                                        <div class="item">
                                                            <img src="{{ asset('assets/tooth_images/13.svg') }}" alt="" class="img-fluid">
                                                            <p>13</p>
                                                        </div>
                                                        <div class="item">
                                                            <img src="{{ asset('assets/tooth_images/12.svg') }}" alt="" class="img-fluid">
                                                            <p>12</p>
                                                        </div>
                                                        <div class="item">
                                                            <img src="{{ asset('assets/tooth_images/11.svg') }}" alt="" class="img-fluid">
                                                            <p>11</p>
                                                        </div>
                                                        <div class="item">
                                                            <img src="{{ asset('assets/tooth_images/11.svg') }}" alt="" class="img-fluid">
                                                            <p>21</p>
                                                        </div>
                                                        <div class="item">
                                                            <img src="{{ asset('assets/tooth_images/12.svg') }}" alt="" class="img-fluid">
                                                            <p>22</p>
                                                        </div>
                                                        <div class="item">
                                                            <img src="{{ asset('assets/tooth_images/13.svg') }}" alt="" class="img-fluid">
                                                            <p>23</p>
                                                        </div>
                                                        <div class="item">
                                                            <img src="{{ asset('assets/tooth_images/14.svg') }}" alt="" class="img-fluid">
                                                            <p>24</p>
                                                        </div>
                                                        <div class="item">
                                                            <img src="{{ asset('assets/tooth_images/15.svg') }}" alt="" class="img-fluid">
                                                            <p>25</p>
                                                        </div>
                                                        <div class="item">
                                                            <img src="{{ asset('assets/tooth_images/16.svg') }}" alt="" class="img-fluid">
                                                            <p>26</p>
                                                        </div>
                                                        <div class="item">
                                                            <img src="{{ asset('assets/tooth_images/17.svg') }}" alt="" class="img-fluid">
                                                            <p>27</p>
                                                        </div>
                                                        <div class="item">
                                                            <img src="{{ asset('assets/tooth_images/18.svg') }}" alt="" class="img-fluid">
                                                            <p>28</p>
                                                        </div>
                                                    </div>
                                                    <div class="links" style="margin-top:20px;">
                                                        <div class="item">
                                                            <p>48</p>
                                                            <img src="{{ asset('assets/tooth_images/48.svg') }}" alt="" class="img-fluid">
                                                        </div>
                                                        <div class="item">
                                                            <p>47</p>
                                                            <img src="{{ asset('assets/tooth_images/47.svg') }}" alt="" class="img-fluid">
                                                        </div>
                                                        <div class="item">
                                                            <p>46</p>
                                                            <img src="{{ asset('assets/tooth_images/46.svg') }}" alt="" class="img-fluid">
                                                        </div>
                                                        <div class="item">
                                                            <p>45</p>
                                                            <img src="{{ asset('assets/tooth_images/45.svg') }}" alt="" class="img-fluid">
                                                        </div>
                                                        <div class="item">
                                                            <p>44</p>
                                                            <img src="{{ asset('assets/tooth_images/44.svg') }}" alt="" class="img-fluid">
                                                        </div>
                                                        <div class="item">
                                                            <p>43</p>
                                                            <img src="{{ asset('assets/tooth_images/43.svg') }}" alt="" class="img-fluid">
                                                        </div>
                                                        <div class="item">
                                                            <p>42</p>
                                                            <img src="{{ asset('assets/tooth_images/42.svg') }}" alt="" class="img-fluid">
                                                        </div>
                                                        <div class="item">
                                                            <p>41</p>
                                                            <img src="{{ asset('assets/tooth_images/41.svg') }}" alt="" class="img-fluid">
                                                        </div>
                                                        <div class="item flip">
                                                            <p>31</p>
                                                            <img src="{{ asset('assets/tooth_images/41.svg') }}" alt="" class="img-fluid">
                                                        </div>
                                                        <div class="item">
                                                            <p>32</p>
                                                            <img src="{{ asset('assets/tooth_images/42.svg') }}" alt="" class="img-fluid">
                                                        </div>
                                                        <div class="item">
                                                            <p>33</p>
                                                            <img src="{{ asset('assets/tooth_images/43.svg') }}" alt="" class="img-fluid">
                                                        </div>
                                                        <div class="item">
                                                            <p>34</p>
                                                            <img src="{{ asset('assets/tooth_images/44.svg') }}" alt="" class="img-fluid">
                                                        </div>
                                                        <div class="item">
                                                            <p>35</p>
                                                            <img src="{{ asset('assets/tooth_images/45.svg') }}" alt="" class="img-fluid">
                                                        </div>
                                                        <div class="item">
                                                            <p>36</p>
                                                            <img src="{{ asset('assets/tooth_images/46.svg') }}" alt="" class="img-fluid">
                                                        </div>
                                                        <div class="item">
                                                            <p>37</p>
                                                            <img src="{{ asset('assets/tooth_images/47.svg') }}" alt="" class="img-fluid">
                                                        </div>
                                                        <div class="item">
                                                            <p>38</p>
                                                            <img src="{{ asset('assets/tooth_images/48.svg') }}" alt="" class="img-fluid">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="table-responsive">
                                                <table class="datatable table">
                                                    <thead>
                                                        <tr>
                                                            <th>Teeth ID</th>
                                                            <th>Service Name</th>
                                                            <th>Note</th>
                                                            <th>Price</th>
                                                           
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                    @foreach($services as $service)
                                                        <tr>
                                                            <td><span>{{ $service->teeth_id }}</span></td>
                                                            <td><span>{{ $service->name }}</span></td>
                                                            <td><span>{{ $service->note }}</span></td>
                                                            <td><span>{{ $service->price }}</span></td>
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

                        <div class="tab-pane @if (\Session::has('success_add_storage')) active @endif" id="tabStorage">

                            <div class="row mb-2">    
                                <div class="col-md-10">
                                </div>
                                <div class="col-md-2 text-right">
                                    <a href="#" id="new_service_btn" class="btn btn-icon btn-primary" data-toggle="modal" data-target="#add_storage_modal"><span class="icon-plus1"></span></a>
                                </div>  
                            </div> 

                            <div class="row">        
                                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="table-responsive">
                                                <table class="datatable table">
                                                <thead>
                                                        <tr>
                                                            <th>Title</th>
                                                            <th>Description</th>
                                                            <th>
                                                                Action
                                                            </th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                    @foreach($storages as $storage)
                                                        <tr>
                                                            <td><span>{{ $storage->title }}</span></td>
                                                            <td><span>{{ $storage->description }}</span></td>
                                                            <td class="text-center">
                                                                <div class="btn-group btn-group-sm">
                                                                    <a href="{{ route('reception.storage.download', $storage->id) }}"  class="btn btn-primary"><i class="icon-cloud"></i></a>
                                                                    <button onclick="delete_func('delete_frm_{{ $storage->id }}')" type="button" class="btn btn-danger">
                                                                        <form action="{{ route('reception.storage.destroy', ['storage_id' => $storage->id, 'patient_id' => $patient_id, 'doctor_id' => $doctor_id ])}}" name="delete_frm_{{ $storage->id }}" id="delete_frm_{{ $storage->id }}" method="post">
                                                                            @csrf
                                                                            @method('DELETE')
                                                                            <i class="icon-cancel"></i>
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

                        <div class="tab-pane @if (\Session::has('success_add_note')) active @endif" id="tabNotes">
                            <p>Notes</p>
                            <div class="row mb-2">    
                                <div class="col-md-10"></div>
                                <div class="col-md-2 text-right">
                                    <a href="#" id="new_modal_btn" class="btn btn-icon btn-primary" data-toggle="modal" data-target="#add_invoice_modal"><span class="icon-plus1">Add New Note</span></a>
                                </div>  
                            </div> 
                            
                            <div class="row">
                                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="table-responsive">
                                                <table class="datatable table"> 
                                                <thead>
                                                        <tr>
                                                            <th>Note</th>
                                                            <th>Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                    @foreach($notes as $note)
                                                        <tr>
                                                            <td><span>{{ $note->note }}</span></td>  
                                                            <td>
                                                                <button onclick="delete_func('delete_note_{{ $note->id }}')" type="button" class="btn btn-danger">
                                                                    <form action="{{ route('reception.note.destroy', ['id' => $note->id])}}" name="delete_note_{{ $note->id }}" id="delete_note_{{ $note->id }}" method="post">
                                                                        @csrf
                                                                        @method('DELETE')
                                                                        <i class="icon-cancel"></i>
                                                                    </form>    
                                                                </button>
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

                        <div class="tab-pane @if (\Session::has('success_paid_invoice')) active @endif" id="tabBilling">
                            <div class="row">
                                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="card-body">

                                                <div class="table-responsive">
                                                   <a style="float: right;color: #fff;" onclick="document.getElementById('addbillingform1').submit();" class="btn btn-icon btn-primary">
                                                        <span class="icon-plus1"></span>
                                                    </a>
                                                    <table id="mytable1" class="datatable table">
                                                        <thead>
                                                            <tr>
                                                                <th>Teeth ID</th>
                                                                <th>Doctor</th>
                                                                <th>Service Name</th>
                                                                <th>Note</th>
                                                                <th>Price</th>
                                                                <th>Type</th>
                                                                <th class="tb-tnx-action">
                                                                    Action
                                                                </th>
                                                                <th></th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                        <form id="addbillingform1" action="{{ route('reception.patient.BillingStore') }}" method="POST" enctype="multipart/form-data">
                                                            @csrf
                                                            <input type="hidden" name="patient_id" value="{{ $patient_id }}">
                                                            @foreach($services as $service)
                                                               <?php $doctorinfo = DB::table('users')->where('id',$service->doctor_id)->first(); ?>
                                                                <tr>
                                                                    <td><input type="hidden" name="teeth_id[]" value="{{ $service->teeth_id }}"><span>{{ $service->teeth_id }}</span></td>
                                                                    <td><span>{{$doctorinfo ? $doctorinfo->name : 'Doctor'  }}</span></td>
                                                                    <td><input type="hidden" name="service[]" value="{{ $service->name }}"><span>{{ $service->name }}</span></td>
                                                                    <td><span>{{ $service->note }}</span></td>
                                                                    <td><input type="hidden" name="amount[]" value="{{ $service->price }}"><span>{{ $service->price }}</span></td>
                                                                    <td><span>{{ $service->type }}</span></td>
                                                                    <td class="text-center">
                                                                        @if($service->type != "completed")
                                                                            <div class="btn-group btn-group-sm">
                                                                                <button type="button" data-toggle="modal" data-target="#view_notemodal"  data-id="{{ json_encode($service) }}"  class="btn btn-info">
                                                                                    <i class="icon-edit1"></i>
                                                                                </button>
                                                                            </div> 
                                                                        @endif
                                                                    </td>
                                                                    <td>
                                                                        @if($service->invoiced == 0)
                                                                            <input type="checkbox" name="servic_ids[]" value="{{$service->id}}" >
                                                                        @else 
                                                                            <p>invoiced</p>
                                                                        @endif
                                                                    </td>
                                                                </tr>
                                                            @endforeach 
                                                        </form>
                                                        </tbody>
                                                    </table>
                                                </div>

                                                <div class="table-responsive">
                                                    <table class="datatable table"> 
                                                        <thead>
                                                            <tr>
                                                                <th>Code</th>
                                                                <th>From</th>
                                                                <th>To</th>
                                                                <th>Amount</th>
                                                                <th>Paid</th>
                                                                <th>Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                        @foreach($invoices as $invoice)
                                                            <?php 
                                                                $invoicedetails = DB::table('invoice_lists')->where('invoice_id',$invoice->id)->get(); 
                                                                $total += $invoice->amount;
                                                            ?>
                                                            <tr>
                                                                <td><span>{{ $invoice->code }}</span></td>
                                                                <td><span>{{ $invoice->d_email }}</span></td>
                                                                <td><span>{{ $invoice->p_email }}</span></td>
                                                                <td><span>{{ $invoice->amount }}</span></td> 
                                                                <td>
                                                                    @if($invoice->paid == 0)
                                                                        <?php $dept += $invoice->amount; ?>
                                                                        <form action="{{route('reception.patient.BillingPaid')}}" method="post">
                                                                            @csrf
                                                                            <input type="hidden" name="invoiceid" value="{{$invoice->id}}">
                                                                            <button  type="submit" class="btn btn-primary">Paid</button> 
                                                                        </form>    
                                                                    @else 
                                                                        <?php $paid += $invoice->amount; ?>
                                                                        <p>Paid</p>      
                                                                    @endif
                                                                </td>
                                                                <td>
                                                                    <div class="btn-group btn-group-sm">
                                                                        <a href="{{route('reception.invoice.generatepdf',$invoice->id)}}" class="btn btn-info">
                                                                            PDF
                                                                        </a>
                                                                    </div> 
                                                                </td>
                                                                {{--  <td>
                                                                    <a href="#" class="btn btn-icon btn-primary" data-toggle="modal" data-target="#show_invoice_modal{{$invoice->id}}"><span class="icon-eye"></span></a> 
                                                                </td>  --}}
                                                            </tr>
                                                            <tr>
                                                                 <div class="modal" id="show_invoice_modal{{$invoice->id}}">
                                                                    <div class="modal-dialog">
                                                                        <div class="modal-content">

                                                                            <!-- Modal Header -->
                                                                            <div class="modal-header">
                                                                                <h4 class="modal-title">Invoice Details</h4>
                                                                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                                            </div>

                                                                            <!-- Modal body -->
                                                                            <div class="modal-body">
                                                                                 
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </tr>
                                                        @endforeach
                                                        </tbody> 
                                                        <div class="row">
                                                        <div class="col-md-4"><h4> Total : {{$total}}</h4></div>
                                                        <div class="col-md-4"><h4> Paid : {{$paid}}</h4></div>
                                                        <div class="col-md-4"><h4> Residual : {{$dept}}</h4></div>                        
                                                        </div>
                                                    </table>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" id="add_storage_modal">
    <div class="modal-dialog modal-lg" role="document">
        
        <div class="modal-content">
            <div  class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            
            <div class="modal-body">
           
                    
                    <form action="{{ route('file.upload.post') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" id="p_id" name="p_id" value="{{$patient_id}}">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="form-label" for="birth-day">Title</label>
                                    <input class="form-control form-control-sm" id="a_title" name="a_title" type="text" required >                           
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="form-label" for="cf-default-textarea">Description</label>
                                    <div class="form-control-wrap">
                                        <textarea class="form-control form-control-sm" cols="30" rows="5" id="a_description" name="a_description" placeholder="Write your description" required></textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <input type="file" name="file" class="form-control" required>
                            </div>

                            
                            <div class="col-md-12">
                                <ul class="align-center flex-wrap flex-sm-nowrap gx-4 gy-2">
                                    <li class="text-right">
                                        <button type="submit" class="btn btn-lg btn-primary">Save</button>
                                    </li>
                                </ul>
                            </div>
                            
                        </div>
                    </form>
                    
                
            </div><!-- .modal-body -->
            
        </div><!-- .modal-content -->
    </div><!-- .modal-dialog -->
</div><!-- .modal -->

<div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" id="add_invoice_modal">
    <div class="modal-dialog modal-lg" role="document">
        
        <div class="modal-content">
            <div  class="modal-header">
                <h5 class="modal-title" id="basicModalLabel">Add New Note</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('reception.note.store')}}" method="post">
            @csrf
            <div class="modal-body">
                
                <div class="row gy-4">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="form-label">Note</label>
                            <div class="form-control-wrap">
                                <input type="hidden" name="user_id" id="user_id" value="{{$doctor_id}}">
                                <input type="hidden" name="patient_id" id="patient_id" value="{{$patient_id}}">
                                <textarea rows="5" class="form-control" name="note" id="note" placeholder="Type Your Note Here...."></textarea>
                            </div>
                        </div>
                    </div>
                   
                </div>
            </div><!-- .modal-body -->
            <div class="modal-footer">
                <button  class="btn btn-primary" id="note_save_btn"><span class="icon-save"></span>&nbsp;Save</button>                                        
                <button  data-dismiss="modal" class="btn btn-danger"><span class="icon-close"></span>&nbsp;Cancel</button>                                            
            </div>
            </form>
        </div><!-- .modal-content -->
    </div><!-- .modal-dialog -->
</div>

<script>
$(document).ready(function(){
   

});

// $("#note_save_btn").on('click', function(e) {
//         $.ajax({
//             url: '{{route("reception.note.store")}}',
//             type:"POST",
//             data:{
//                 user_id    : $("#user_id").val(),
//                 patient_id : $("#patient_id").val(),
//                 note       : $('#note').val(),
//                 _token: "{{ csrf_token() }}",
//             },
//             success:function(response){
//                 $("#add_invoice_modal").modal("hide");


//                 //location.reload();
//             },
//         });
//     });
</script>

@endsection
