<style>
td:hover {background-color:rgba(115,103,240,.12);}
</style>
<table>
    <tbody>
        <tr>
            <!-- row one -->
            @if(count($procedures_row_one)>0)
            @foreach($procedures_row_one as $p)
            <td>
                <a style="cursor:pointer;max-height:196px;" onClick="_formProcedureServiceItem(0,{{$p->number}})">
                    <img src="{{ asset($p->image) }}" alt="" class="img-fluid">
                    <p class="text-center">{{$p->number}}</p>
                </a>
            </td>
            @endforeach
            @endif
        </tr>
        <tr>
            <!-- row two -->
            @if(count($procedures_row_two)>0)
            @foreach($procedures_row_two as $p)
            <td>
                <a style="cursor:pointer;max-height:196px;" onClick="_formProcedureServiceItem(0,{{$p->number}})">
                    <p class="text-center">{{$p->number}}</p>
                    <img src="{{ asset($p->image) }}" alt="" class="img-fluid">
                </a>
            </td>
            @endforeach
            @endif
        </tr>
    </tbody>
</table>