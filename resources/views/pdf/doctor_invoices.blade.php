@php
$app_title=config('global.app_title');
@endphp
<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Invoices - {{$app_title}}</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,300;0,400;0,500;0,600;1,400;1,500;1,600"
        rel="stylesheet">

    <style type="text/css">
    body {
        color: #6e6b7b !important;
        font-size: 14px !important;
        font-family: 'Montserrat' !important;
    }

    .mb-2 {
        margin-bottom: 20px !important;
    }

    table {
        font-size: 14px !important;
    }

    .gray {
        background-color: #ebebeb;
    }

    hr {
        border-top: 1px solid #ebe9f1 !important;
        overflow: visible !important;
    }

    table tr td {
        border: 1px solid black !important;
        border-collapse: collapse !important;
        padding: 4px !important;
    }
    </style>

</head>

<body>
    <table style="width:100%;border: 1px solid black;border-collapse: collapse;">
        <thead>
            <tr class="gray">
                <td colspan="9" style="text-align:center;font-size: 15px;" class="gray">Invoices</td>
            </tr>
            <tr class="gray">
                <td colspan="3">Doctor : <strong>{{$doctor->name ?? "All"}}</strong></td>
                <td colspan="3">Date : {{$today->format('Y-m-d')}}</td>
                <td colspan="3">Filtered:</td>
            </tr>
            <tr class="gray">
                <th>Number</th>
                <th>Patient</th>
                <th>Services</th>
                <th>Bill date</th>
                <th>Due date</th>
                <th>Amount</th>
                <th>Paid</th>
                <th>Remain</th>
                <th>Refund</th>
                <th>Doctor</th>
            </tr>
        </thead>
        <tbody>
            @if(count($bills))
            @foreach($bills as $d)

            @php
            $dtBillDate = Carbon\Carbon::createFromFormat('Y-m-d',$d->bill_date);
            $dtIssueDate = Carbon\Carbon::createFromFormat('Y-m-d',$d->due_date);
            @endphp
            <tr>
                <td style="height:40px;" align="center">#{{$d->Bill_Number}}</td>
                <td align="center">{{$d->Patient}}</td>
                <td align="center">
                {{-- @foreach($d->services as $s) --}}
                {{ $d->service_name ??'service name'}}
                {{-- @endforeach --}}
                </td>
                <td align="center">{{$dtBillDate->format('Y-m-d')}}</td>
                <td align="right">{{$dtIssueDate->format('Y-m-d')}}</td>
                <td align="right">{{$d->Amount ??'0.00'}} {{env('CURRENCY_SYMBOL')}}</td>
                <td align="right">{{$d->Paid ??'0.00'}} {{env('CURRENCY_SYMBOL')}}</td>
                <td align="right">{{($d->Paid - $d->Amount) >= 0 ? ($d->Paid - $d->Amount) : ($d->Paid - $d->Amount) * -1}} {{env('CURRENCY_SYMBOL')}}</td>
                <td align="right">{{$d->Refund ??'0.00' }} {{env('CURRENCY_SYMBOL')}}</td>
                <td align="center">{{$d->doctor ??'Doctor name'}}</td>
            </tr>
            @endforeach
            @endif

            @if($nb_empty_rows>0)
                @for ($i = 1; $i <= $nb_empty_rows; $i++)
                <tr>
                    <td style="height:40px;"></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                @endfor
            @endif

        </tbody>
    </table>

    <table style="border: 1px solid black;border-collapse: collapse; margin-top:20px">
      <tr>
      <td>TOTAL AMOUNT</td>
      <td>{{$Total[0]->TOTAL_AMOUNT}} {{env('CURRENCY_SYMBOL')}}</td>
      </tr>
      <tr>
      <td>TOTAL PAID</td>
      <td>{{$Total[0]->TOTAL_PAID }} {{env('CURRENCY_SYMBOL')}}</td>
      </tr>
      <tr>
      <td>TOTAL REMAINING</td>
      <td>{{($Total[0]->TOTAL_AMOUNT - $Total[0]->TOTAL_PAID)>0 ? ($Total[0]->TOTAL_AMOUNT - $Total[0]->TOTAL_PAID) : ($Total[0]->TOTAL_AMOUNT - $Total[0]->TOTAL_PAID) *-1}} {{env('CURRENCY_SYMBOL')}}</td>
      </tr>
      <tr>
      <td>TOTAL REFUND</td>
      <td>{{$Total[0]->TOTAL_REFUND ??'0.00'}} {{env('CURRENCY_SYMBOL')}}</td>
      </tr>
    </table>

    <!-- <footer>
        <table width="100%">
            <tbody>
                <tr>
                    <td align="left" style="border: 0px;">
                        <p>Patient signature</p>
                    </td>
                    <td align="left" style="border: 0px;">
                        <p></p>
                    </td>
                </tr>
            </tbody>
        </table>
    </footer> -->
</body>

</html>




{{-- @php
$app_title=config('global.app_title');
@endphp
<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Invoices - {{$app_title}}</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,300;0,400;0,500;0,600;1,400;1,500;1,600"
        rel="stylesheet">

    <style type="text/css">
    body {
        color: #6e6b7b !important;
        font-size: 14px !important;
        font-family: 'Montserrat' !important;
    }

    .mb-2 {
        margin-bottom: 20px !important;
    }

    table {
        font-size: 14px !important;
    }

    .gray {
        background-color: #ebebeb;
    }

    hr {
        border-top: 1px solid #ebe9f1 !important;
        overflow: visible !important;
    }

    table tr td {
        border: 1px solid black !important;
        border-collapse: collapse !important;
        padding: 4px !important;
    }
    </style>

</head>

<body>
    <table style="width:100%;border: 1px solid black;border-collapse: collapse;">
        <thead>
            <tr class="gray">
                <td colspan="8" style="text-align:center;font-size: 15px;" class="gray">Invoices</td>
            </tr>
            <tr class="gray">
                <td colspan="3">Doctor : <strong>{{$doctor->name}}</strong></td>
                <td colspan="3">Date : {{$today->format('Y-m-d')}}</td>
                <td colspan="2">Filtered: {{$filter=="day"?"Last 1 day":($filter=="week"?"Last 7 days":"Last 30 days")}}</td>
            </tr>
            <tr class="gray">
                <th>Number</th>
                <th>Patient</th>
                <th>Bill date</th>
                <th>Due date</th>
                <th>Total</th>
                <th>Paid</th>
                <th>Refund</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @if(count($invoices))
            @foreach($invoices as $d)

            @php
            $dtBillDate = Carbon\Carbon::createFromFormat('Y-m-d',$d->bill_date);
            $dtIssueDate = Carbon\Carbon::createFromFormat('Y-m-d',$d->due_date);
            @endphp
            <tr>
                <td style="height:40px;" align="center">#{{$d->number}}</td>
                <td align="center">{{$d->patient->ar_name?$d->patient->ar_name:$d->patient->name}}</td>
                <td align="center">{{$dtBillDate->format('Y-m-d')}}</td>
                <td align="right">{{$dtIssueDate->format('Y-m-d')}}</td>
                <td align="right">{{$arrayCalculate[$d->id]['total']}} {{env('CURRENCY_SYMBOL')}}</td>
                <td align="right">{{$arrayCalculate[$d->id]['total_paid']}} {{env('CURRENCY_SYMBOL')}}</td>
                <td align="right">{{$arrayCalculate[$d->id]['total_refund']}} {{env('CURRENCY_SYMBOL')}}</td>
                <td align="center">{{$d->status}}</td>
            </tr>
            @endforeach
            @endif

            @if($nb_empty_rows>0)
                @for ($i = 1; $i <= $nb_empty_rows; $i++)
                <tr>
                    <td style="height:40px;"></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                @endfor
            @endif

        </tbody>
    </table>


    <!-- <footer>
        <table width="100%">
            <tbody>
                <tr>
                    <td align="left" style="border: 0px;">
                        <p>Patient signature</p>
                    </td>
                    <td align="left" style="border: 0px;">
                        <p></p>
                    </td>
                </tr>
            </tbody>
        </table>
    </footer> -->
</body>

</html> --}}
