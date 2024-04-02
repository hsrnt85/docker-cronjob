<table class="info_content_border" style="border-block-color: black" cellpadding="3" cellspacing="0" >
    <tr class="info_content_border" >
        <td colspan="5"> SENARAI RESIT YANG DIKELUARKAN </td>
    </tr>
    <tr class="info_content_border" >
        <td width="5%"> Bil.</td>
        <td width="25%"> No. Resit</td>
        <td width="20%"> Tarikh </td>
        <td width="20%"> Amaun (RM) </td>
        <td width="30%"> Perihal </td>
    </tr>
    @foreach($chunk_per_page as $i=>  $tpn)

        <tr class="info_content_border" >
            <td > {{$i+1 .'.'}}</td>
            <td > {{$tpn->payment_receipt_no}}</td>
            <td > {{convertDateSys($tpn->payment_date)}}</td>
            <td style="text-align:right;"> {{$tpn->total_amount}}</td>
            <td > {{$tpn->payment_description}}</td>
        </tr>

    @endforeach
</table>
