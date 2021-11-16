<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Invoice {{$invoice->number}} - Dentinizer</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,300;0,400;0,500;0,600;1,400;1,500;1,600"
        rel="stylesheet">

    <style type="text/css">
    body {
        color: #6e6b7b !important;
        font-size: 20px !important;
        font-family: 'Montserrat' !important;
    }

    .h1,
    .h2,
    .h3,
    .h4,
    .h5,
    .h6,
    h1,
    h2,
    h3,
    h4,
    h5,
    h6 {
        font-family: inherit;
        font-weight: 500;
        line-height: 1.2;
        color: #5e5873;
    }

    .mb-2 {
        margin-bottom: !important;
    }


    table {
        /* font-size: x-small; */
        font-size: 20px !important;
    }

    thead tr th {
        font-weight: bold;
        /* font-size: x-small; */
        font-size: 20px !important;
        border: 1px solid white;
    }

    tbody tr td {
        /* font-size: x-small; */
        font-size: 20px !important;
	font-weight: bold;
        border: 1px solid white;
	 }

    tfoot tr td {
        font-weight: bold;
        /* font-size: x-small; */
        font-size: 20px !important;
        border: 1px solid white;
         }

    .gray { }

    .bg {
        background-color: #3f596a;
    }

    .box-bill {
        font-size: 20px !important;
        color: #fff !important;
        text-align: center;
    }

    .input {
        width: 280px;
        padding-top: 10px;
        padding-bottom: 10px;
        direction: rtl;
        height: 20px !important;
	font-size: 20px !important;
	font-weight:bold;
    }
   .output{
	font-size: 20px; !important;
	font-weight: bold;
    }

    hr {
        border-top: 1px solid white /* #ebe9f1 */ !important;
        overflow: visible !important;
    }

    footer {
        position: fixed;
        bottom: 0cm;
        left: 1cm;
        right: 1cm;
        height: 1.5cm;
    }
    </style>

</head>

<body>
    @php
    $dtBillDate = Carbon\Carbon::createFromFormat('Y-m-d',$invoice->bill_date);
    $dtIssueDate = Carbon\Carbon::createFromFormat('Y-m-d',$invoice->due_date);
    $percent = '';
    if($invoice->discount_amount_type=='percentage'){
    $percent = '('.$invoice->discount_amount.'%)';
    }
    @endphp
   	<table style="border: 1px solid white; font-size:20px;" width="100%">
		<tr>
		 <td height="70px" align="center" style="padding-bottom: 1px; border:1px solid white;">
			<p align="center"><strong>الرقم الضريبي 301008778100003</strong></p>
		 </td>
		</tr>
	</table>
    <table width="100%">
        <tr>
           <td height="30px" style="font-size:20px; padding-left:100px;" align="left">
		<p><strong>#{{$invoice->number}}</strong></p>
            </td>
            <td align="right">
 
            </td>
             <td height="50px" style="font-size:20px; padding-right:220px;" align="right">
              <p><strong> {{($invoice->patient->ar_name)?$invoice->patient->ar_name:$invoice->patient->name}}</strong><p>
            </td>
        </tr>
        <tr>
            <td height="50px" align="right" style="font-size:20px; padding-right:220px; padding-bottom;0px;">
	   	<p><strong>{{$dtBillDate->format('Y-m-d')}}</strong></p>
	     </td>
            <td>
            </td>
            </td>
        </tr>
	<tr>
		<td></td>
		<td>
		</td>
		<td height="50" align="left" style="padding-left:; font-size:20px;">
			<p><strong>{{$invoice->user->name}}</strong></p>
		</td>
	</tr>
	<tr><td></td>
	</tr>
    </table><br>
	<table style="border: 1px solid white;border-collapse: collapse;" width="100%">
        <thead style="background-color: #edf9fd;">
            <tr>
                <th width="120px" height="30px" align="center">
                </th>
                <th width="120px" align="left">
                    <p color="white">ITEM NO.</p>
                </th>
                <th width="" align="left">
                    <p color="white">الوصف</p>
                </th>
                <th align="" width="120px">
                    <p color="white">الكمية</p>
               </th>
                <th width="" align="center">
                    <p color="white">الإجمالي</p>
                </th>
                <th width="500" align="center">
                    <p color="white">نسبة الخصم</p>
                </th>
                <th width="60" align="center">
                    <p color="white">قيمة الخصم</p>

                </th>
                <th width="20" align="center">
                    <p color="white">الصافي</p>
                </th>
            </tr>
        </thead>
        <tbody>
            @if(count($items)>0)
            @foreach($items as $k=>$item)
            @php
            $k++;
            @endphp
 	    	 <tr>
                <td height="25px" class="" align="left">{{number_format($item->total,2)}} {{env('')}}</td>
                <td class="" align="center">
		<p>@if($calcul['discount_amount']>0){{$calcul['discount_amount']}} {{env('')}}@endif</p>
		</td>
                <td width="50px" align="right">{{number_format($item->rate,2)}} {{env('')}}</td>
                <td class="" align="right"> <p>@if($calcul['discount_amount']>0){{$percent}}@endif</p></td>
                <td class="" align="right">{{$item->quantity}}</td>
		<td style="font-size:25px;" class="" align="right" style="padding-right: 100px;">
	       	    <p><strong>{{$item->service->service_name}}</strong></p>
                    <p>   {{$item->note}}   </p>
	        </td>
                <td class="" align="center">{{$item->teeth_id}}</td>
		<td class="" align="center">{{$k}}</td>
        	 </tr>
            @endforeach
            @endif

	   @if(count($items)<5)
           @for ($i = 0; $i < 5-count($items); $i++)
            <tr>
               <td height="30px" align="left"></td>
               <td align="center"></td>
               <td align="left"></td>
	       <td align="left"></td>
               <td align="left"></td>
               <td align="center"></td>
	       <td align="right"></td>
               <td align="right"></td>
            </tr>
           @endfor
           @endif
       </tbody>
	</table><br>
      <table style="border:1px solid white;border-collapse: collapse;" width="100%">
            <tr>
                <td style="font-size:17px;" align="left" padding-bottom="0px">
			<p><strong>{{$calcul['total']}} {{env('CURRENCY_SYMBOL')}}</strong></p>
		 </td>
            </tr>
            <tr>
                <td style="font-size:17px; border:1px solid white;" align="left">
		   <p><strong>@if($calcul['tax_amount']>0) {{$calcul['tax_amount']}} {{env('CURRENCY_SYMBOL')}} ({{$invoice->tax_percentage}}%)<strong></p>@endif
		</td>
            </tr>
	     <tr>
		<td style="font-size:17px; border:1px solid white;">
		     @php
                        $arrayPm=array(
                            "Cash"=>"نقدي",
                            "Credit card"=>"بطاقة الائتمان",
                            "Mada"=>"مادا",
			);
                    @endphp
                   <p><strong>{{$calcul['total_paid']}} {{env('CURRENCY_SYMBOL')}}</strong></p>
              </td>
              </tr>
	       <tr>
	          <td align="left" style="font-size:17px; border:1px solid white;">
		 @php
                    $due_amount=$calcul['nnf_total']-$calcul['nnf_total_paid'];
                    //dd($due_amount);
                    @endphp
                    <p><strong>{{number_format($due_amount,2)}} {{env('CURRENCY_SYMBOL')}}</strong></p>
		</td>
 	      </tr>
    </table>
    <footer>

    </footer>

</body>

</html>
