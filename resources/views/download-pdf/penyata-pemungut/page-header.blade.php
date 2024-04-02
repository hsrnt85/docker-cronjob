
<table class="info_content_border" style="border-block-color: black" cellpadding="3" cellspacing="0" >
    <tr style="border: 1px solid black; border-right:none;border-bottom:none;">
        <td width="20%"></td>
        <td width="60%" colspan="2" rowspan="2" class="text-center" style="font-size: 12px;">
            <b>KERAJAAN NEGERI JOHOR</b><br>
            <b>PENYATA PEMUNGUT</b>
        </td>
        <td width="20%" style="border: 1px solid black; border-bottom:none; border-left:none; vertical-align:left;">(Kew: 305E-Pind .1/2015)</td>
    </tr>
    <tr  style="border: 1px solid black; border-top:none; vertical-align:left;">
        <td width="20%"></td>
        <td width="20%"> Mukasurat : {{$bil_page .'/'. $total_page}}</td>
    </tr>
</table>

<table class="info_content_border" style="border-block-color: black" cellpadding="3" cellspacing="0" >
    <tr class="info_content_border text-center" >
        <td > Tahun Kewangan : {{currentYear()}}</td>
    </tr>
</table>

<table class="info_content_border" style="border-block-color: black" cellpadding="4" cellspacing="0" >
    <tr class="info_content_border text-center" >
        <td width="28%"> Jenis Urusniaga </td>
        <td > Pej. Perakaunan</td>
        <td > No. Penyata Pemungut </td>
        <td > Tarikh Penyata Pemungut</td>
    </tr>
    <tr class="info_content_border text-center" >
        <td width="28%"> PENYATA PEMUNGUT- AUTO </td>
        <td >  {{$finance_department->department_name}}</td>
        <td > {{$collector_statement->collector_statement_no}} </td>
        <td > {{convertDateSys($collector_statement->collector_statement_date)}}</td>
    </tr>
</table>
<table class="info_content_border" style="border-block-color: black" cellpadding="4" cellspacing="0" >
    <tr class="info_content_border text-center" >
        <td width="12%" style="text-align: left"> Jab.</td>
        <td width="16%"> {{$finance_department->department_code}} </td>
        <td colspan="5"> {{$finance_department->department_name}}</td>
    </tr>
    <tr class="info_content_border text-center" >
        <td style="text-align: left"> PTJ/PK </td>
        <td > {{$finance_department->ptj_code}} </td>
        <td colspan="5"> {{$finance_department->ptj_name}} </td>
    </tr>
    <tr class="info_content_border text-center" >
        <td style="text-align: left"> Kod Pembayar </td>
        <td colspan="6"> </td>
    </tr>
    <tr class="info_content_border text-center" >
        <td style="text-align: left"> Kod Panjar </td>
        <td colspan="6"> </td>
    </tr>
    <tr class="info_content_border text-center" >
        <td colspan="3" style="text-align: left"> Jenis Pungutan D = Pungutan diperakaunkan sahaja. </td>
        <td width="15%"> Perihal Pungutan</td>
        <td colspan="3"> {{$collector_statement->description}} </td>
    </tr>
    <tr class="info_content_border text-center" >
        <td style="text-align: left" > Tempoh Pungutan </td>
        <td > Dari : </td>
        <td > {{convertDateSys($collector_statement->collector_statement_date_from)}} </td>
        <td > Hingga : </td>
        <td > {{convertDateSys($collector_statement->collector_statement_date_to)}} </td>
        <td > Tarikh diterima oleh bank</td>
        <td > {{convertDateSys($collector_statement->bank_slip_date)}}</td>
    </tr>
</table>
