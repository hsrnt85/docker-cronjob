<table class="info_content_border"  cellpadding="4" cellspacing="0" >
    <tr class="info_content_border" >
        <td > NO AKAUN : </td>
        <td > NAMA : </td>
    </tr>
</table>

<table class="info_content_border" cellpadding="4" cellspacing="0" >
    <tr class="border-black right-none bottom-none">
        <td width="20%"></td>
        <td width="60%" colspan="2" rowspan="2" class="text-center" style="font-size: 12px;">
            <b>KERAJAAN NEGERI JOHOR</b><br>
            <b>PENYATA PEMUNGUT</b>
        </td>
        <td width="20%" class="border-solid bottom-none left-none vertical-left">(Kew: 305E-Pind .1/2015)</td>
    </tr>
    <tr class="border-solid top-none vertical-left">
        <td width="20%"></td>
        <td width="20%"> Mukasurat :</td>
    </tr>
</table>

<table class="info_content_border" style="border-block-color: black" cellpadding="4" cellspacing="0" >
    <tr class="info_content_border text-center" >
        <td > Tahun Kewangan : </td>
    </tr>
</table>

<table class="info_content_border" style="border-block-color: black" cellpadding="8" cellspacing="0" >
    <tr class="info_content_border text-center" >
        <td width="28%"> Jenis Urusniaga </td>
        <td > Pej. Perakaunan</td>
        <td > No. Penyata Pemungut </td>
        <td > Tarikh Penyata Pemungut</td>
    </tr>
    <tr class="info_content_border text-center" >
        <td width="28%"> PENYATA PEMUNGUT- AUTO </td>
        <td > </td>
        <td > {{$collector_statement->collector_statement_no}} </td>
        <td > {{convertDateSys($collector_statement->collector_statement_date)}}</td>
    </tr>
</table>
<table class="info_content_border" style="border-block-color: black" cellpadding="8" cellspacing="0" >
    <tr class="info_content_border" >
        <td width="12%" class="text-left"> Jab.</td>
        <td width="16%"> {{$finance_department->department_code}} </td>
        <td colspan="5"> {{$finance_department->department_name}}</td>
    </tr>
    <tr class="info_content_border" >
        <td > PTJ/PK </td>
        <td class="text-center"> {{$finance_department->ptj_code}} </td>
        <td colspan="5"> {{$finance_department->ptj_name}} </td>
    </tr>
    <tr class="info_content_border" >
        <td class="text-left"> Kod Pembayar </td>
        <td colspan="6"> </td>
    </tr>
    <tr class="info_content_border" >
        <td class="text-left"> Kod Panjar </td>
        <td colspan="6"> </td>
    </tr>
    <tr class="info_content_border" >
        <td colspan="3"> Jenis Pungutan D = Pungutan diperakaunkan sahaja. </td>
        <td width="15%" class="text-center"> Perihal Pungutan</td>
        <td colspan="3"> {{$collector_statement->description}} </td>
    </tr>
    <tr class="info_content_border text-center" >
        <td > Tempoh Pungutan </td>
        <td > Dari : </td>
        <td > {{convertDateSys($collector_statement->collector_statement_date_from)}} </td>
        <td > Hingga : </td>
        <td > {{convertDateSys($collector_statement->collector_statement_date_to)}} </td>
        <td > Tarikh diterima oleh bank</td>
        <td > </td>
    </tr>
</table>

<div class="pb-3"></div>
<table class="info_content_border" style="border-block-color: black" cellpadding="8" cellspacing="0" >
    <tr class="info_content_border text-center" >
        <td width="15%"> </td>
        <td > Disediakan</td>
        <td > Semak</td>
        <td > Lulus</td>
    </tr>
    <tr class="info_content_border text-left">
        <td > Nama</td>
        <td > {{$preparer->finance_officer_name}}</td>
        <td > {{$checker ->finance_officer_name}}</td>
        <td > {{$approver->finance_officer_name}}</td>
    </tr>
    <tr class="info_content_border text-left">
        <td > Jawatan</td>
        <td > {{$preparer->position_name}}</td>
        <td > {{$checker ->position_name}}</td>
        <td > {{$approver->position_name}}</td>
    </tr>
    <tr class="info_content_border text-left">
        <td > Tarikh</td>
        <td > {{convertDateSys($preparer->action_on)}}</td>
        <td > {{convertDateSys($checker ->action_on)}}</td>
        <td > {{convertDateSys($approver->action_on)}}</td>
    </tr>
    <tr class="info_content_border text-left">
        <td > Tandatangan</td>
        <td > </td>
        <td > </td>
        <td > </td>
    </tr>
    <tr class="info_content_border text-left">
        <td > Catatan</td>
        <td colspan="3"> </td>
    </tr>
</table>
<p>No. Kelulusan Perb. BNPK(8.15)248-10(SK.6)JD.33(9)</p>
<div class="pb-5"></div>
<p class="text-center">Ini adalah cetakan komputer dan tidak perlu ditandatangani</p>
