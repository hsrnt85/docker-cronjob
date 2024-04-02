<table class="info_content_border" style="border-block-color: black" cellpadding="4" cellspacing="0" >
    <tr class="info_content_border text-center" >
        <td width="15%"> </td>
        <td > Disediakan</td>
        <td > Semak</td>
        <td > Lulus</td>
    </tr>
    <tr class="info_content_border text-center" style="text-align: left">
        <td > Nama</td>
        <td > {{upperText($preparer->finance_officer_name)}}</td>
        <td > {{upperText($checker ->finance_officer_name)}}</td>
        <td > {{upperText($approver->finance_officer_name)}}</td>
    </tr>
    <tr class="info_content_border text-center" style="text-align: left">
        <td > Jawatan</td>
        <td > {{$preparer->position_name}}</td>
        <td > {{$checker ->position_name}}</td>
        <td > {{$approver->position_name}}</td>
    </tr>
    <tr class="info_content_border text-center" style="text-align: left">
        <td > Tarikh</td>
        <td > {{convertDateSys($preparer->action_on)}}</td>
        <td > {{convertDateSys($checker ->action_on)}}</td>
        <td > {{convertDateSys($approver->action_on)}}</td>
    </tr>
    <tr class="info_content_border text-center" style="text-align: left">
        <td > Tandatangan</td>
        <td > </td>
        <td > </td>
        <td > </td>
    </tr>
    <tr class="info_content_border text-center" style="text-align: left">
        <td > Catatan</td>
        <td colspan="3"> </td>
    </tr>
</table>
<p>No. Kelulusan Perb. BNPK(8.15)248-10(SK.6)JD.33(9)</p>

<div class="pb-5"></div>

<div class="footer"  >
    <p class="text-center">Ini adalah cetakan komputer dan tidak perlu ditandatangani</p>
</div>
{{-- <div class="page-break"> --}}
