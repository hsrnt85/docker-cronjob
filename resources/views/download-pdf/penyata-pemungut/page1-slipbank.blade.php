
<table class="info_content_border" styletr="border-block-color: black" cellpadding="2" cellspacing="0" >
    <tr class="info_content_border " >
        <td class="text-center bold" colspan="6" style="font-size: 12px;"> SLIP BAYAR MASUK BANK</td>
    </tr>
    <tr class="bottom-none border-solid">
        <td colspan="4"> Nama Bank : {{$collector_statement->transit_bank?->bank->bank_name ?? ''}}</td>
        <td > Tarikh : {{ convertDateSys($collector_statement->bank_slip_date) }}</td>
        <td > No. Slip Bank : {{  $collector_statement->bank_slip_no }}</td>
    </tr>
    <tr class="top-none border-solid text-center">
        <td colspan="6" >Pej Perakaunan : {{ $finance_department->department_name }}</td>
    </tr>
    <tr class="info_content_border text-center">
        <td rowspan="5" colspan="3">
                @php $tick = ($collector_statement->payment_method->payment_method) ? '/' : ''; @endphp
                <div><span class="border border-dark w-22"></span> <span class="position-absolute">CEK CAWANGAN INI</span> </div>
                <div><span class="border border-dark w-22"></span> <span class="position-absolute">CEK CAWANGAN LAIN</span> </div>
                <div><span class="border border-dark w-22"></span> <span class="position-absolute">CEK BANK TEMPATAN</span> </div>
                <div><span class="border border-dark w-22"></span> <span class="position-absolute">CEK TEMPAT LAIN</span> </div>
                <div><span class="border border-dark w-22"></span> <span class="position-absolute">WANG TUNAI</span> </div>
                <div><span class="border border-dark w-22"><div class="text-center ">{{$tick ?? '/'}}</div></span> <span class="position-absolute">{{ $collector_statement->payment_method ->payment_method ?? '..........................................' }}</></span> </div>
        </td>
        <td class="text-center" colspan="2" > WANG TUNAI </td>
        <td class="text-center"> AMAUN (RM) </td>
    </tr>
    <tr class="info_content_border ">
        <td class="text-center"> RINGGIT MALAYSIA</td>
        <td class="text-center"> {{ convertNumbertoStatementMal($collector_statement->collection_amount) }}</td>
        <td class="text-right"> {{ $collector_statement->collection_amount }}</td>
    </tr>
    <tr class="info_content_border text-center">
        <td colspan="2"></td>
        <td></td>
    </tr>
    <tr class="info_content_border text-center">
        <td colspan="2">CEK-CEK</td>
        <td>AMAUN (RM)</td>
    </tr>
    <tr class="info_content_border text-center">
        <td colspan="2">(BUTIRAN CEK SEPERTI DISENARAI CEK)</td>
        <td></td>
    </tr>
</table>
