<link href="{{ getPathDocumentCss() .'journal-adjustment.css' }}" type="text/css" />

<div class="body_jurnal ">

    <table class="info_content_border" style="border-block-color: black" cellpadding="3" cellspacing="0" >
        <tr class="border-solid bottom-none">
            <td width="20%"></td>
            <td width="60%" colspan="2" rowspan="2" class="text-center" style="font-size: 12px;">
                <b>KERAJAAN NEGERI JOHOR DARUL TAKZIM</b><br>
                <b>BAUCAR JURNAL</b>
            </td>
            <td width="20%" class="text-left " ></td>
        </tr>
        <tr class="border-solid top-none text-left">
            <td width="20%"></td>
            <td width="20%"><b>(KEW.306E - Pin 1/09)<br> Mukasurat : 1/1</b></td>
        </tr>
    </table>

    <div class="pb-1"></div>

    <table class="info_content_border" style="border-block-color: black" cellpadding="3" cellspacing="0" >
        <tr class="info_content_border text-center" >
            <td > <b>TAHUN KEWANGAN </b> {{getYearFromDate($journal->journal_date)}}</td>
        </tr>
    </table>

    <div class="pb-1"></div>

    <table class="info_content_border" style="border-block-color: black" cellpadding="4" cellspacing="0" >
        <tr class="info_content_border text-center bold" >
            <td width="28%"> Jenis Urusniaga </td>
            <td > Kod Pejabat Perakaunan</td>
            <td > No. Baucar </td>
            <td > Tarikh Baucar</td>
            <td > AP 58(a)</td>
        </tr>
        <tr class="info_content_border text-center" >
            <td width="28%">  </td>
            <td > </td>
            <td > {{$journal->journal_no}} </td>
            <td > {{convertDateSys($journal->journal_date)}}</td>
            <td > </td>
        </tr>
    </table>

    <div class="pb-1"></div>

    <table class="info_content_border text-center" style="border-block-color: black" cellpadding="4" cellspacing="0" >
        <tr class="info_content_border" >
            <td width="16%"><b> Kod Jab. Menyedia </b></td>
            <td width="12%"> {{$finance_department->department_code}} </td>
            <td colspan="5" class="text-left"> {{$finance_department->department_name}}</td>
        </tr>
        <tr class="info_content_border text-center" >
            <td > <b> Kod PTJ Menyedia </b></td>
            <td > {{$finance_department->ptj_code}} </td>
            <td colspan="5" class="text-left"> {{$finance_department->ptj_name}} </td>
        </tr>
        <tr class="info_content_border text-center" >
            <td><b> Perihal Jurnal </b></td>
            <td colspan="6" class="text-left"> {{$journal->description}}</td>
        </tr>
    </table>

    <div class="pb-1"></div>

    <table class="info_content_border" style="border-block-color: black;" cellpadding="4" cellspacing="0"  >
        <tr class="text-center border-solid bold bottom-none">
            <td colspan="15"> PINDAHAN / PELARASAN DIMASUK KIRA KE DALAM AKAUN DI BAWAH</td>
        </tr>
        <tr class="info_content_border text-center bold">
            <td width="5%"> Bil.</td>
            <td width="7%"> Vot</td>
            <td width="7%"> Jab</td>
            <td width="7%"> PTJ/PK</td>
            <td width="7%"> Program/Aktiviti</td>
            <td width="7%"> Projek</td>
            <td width="7%"> Setia</td>
            <td width="7%"> Sub Setia</td>
            <td width="7%"> CP</td>
            <td width="8%"> Kod Akaun</td>
            <td width="8%"> Amaun (DT)</td>
            <td width="8%"> Amaun (KT)</td>
            <td width="8%" colspan="2"> Membayar</td>
            <td width="7%"> Kod Penerima / Pembayar</td>
        </tr>
        <tr class="text-center border-solid bottom-none bold">
            <td colspan="10">Kod Kegunaan Jabatan </td>
            <td width="8%" class="border-solid"> RM  </td>
            <td width="8%" class="border-solid"> RM  </td>
            <td width="4%" class="border-solid"> Jab </td>
            <td width="4%" class="border-solid"> PTJ </td>
            <td width="7%"> </td>
        </tr>

        @foreach($journal_vot_list as $vot)
            <tr class="info_content_border text-center">
                <td width="5%"> {{$loop->iteration}}</td>
                <td width="7%"> {{$vot->income_account->general_income_code}}</td>
                <td width="7%"> {{$finance_department->department_code}}</td>
                <td width="7%"> {{$finance_department->ptj_code}}</td>
                <td width="7%"> </td>
                <td width="7%"> </td>
                <td width="7%"> </td>
                <td width="7%"> </td>
                <td width="7%"> </td>
                <td width="8%"> {{$vot->income_account->income_code}}</td>
                <td width="8%" class="text-right"> {{$vot->debit_amount}}</td>
                <td width="8%" class="text-right"> {{$vot->credit_amount}}</td>
                <td width="4%"> {{$finance_department->department_code}}</td>
                <td width="4%"> {{$finance_department->ptj_code}}</td>
                <td width="7%"></td>
            </tr>
        @endforeach

        <tr class="border-solid top-none bottom-none" style="min-height: 150px; ">
            <td colspan="15" style="padding: 10px;"> </td>
        </tr>

        <tr class="border-solid">
            <td colspan="7"></td>
            <td colspan="3"><b>Jumlah Kawalan (RM) </b></td>
            <td class="text-right border-solid" >{{$total_amount->total_debit}}</td>
            <td class="text-right border-solid" >{{$total_amount->total_credit}}</td>
            <td colspan="2" class="text-left"><b>Jumlah Bil. Akaun </b></td>
            <td ></td>
        </tr>
    </table>

    <div class="pb-1"></div>

    <table class="info_content_border text-left" style="border-block-color: black" cellpadding="4" cellspacing="0" >
        <tr class="info_content_border">
            <td > <b> Penyedia</b> </td>
            <td colspan="3"> {{$preparer->finance_officer_name}}</td>
        </tr>
        <tr class="info_content_border">
            <td > <b>Jawatan </b></td>
            <td > {{$preparer->position_name}}</td>
            <td > Tarikh</td>
            <td > {{convertDateSys($preparer->action_on)}}</td>
        </tr>
        <tr class="info_content_border">
            <td > <b>Semak </b></td>
            <td colspan="3"> {{$checker->finance_officer_name}}</td>
        </tr>
        <tr class="info_content_border">
            <td > <b>Jawatan </b></td>
            <td > {{$checker->position_name}}</td>
            <td > Tarikh</td>
            <td > {{convertDateSys($checker->action_on)}}</td>
        </tr>
        <tr class="info_content_border">
            <td > <b>Lulus </b></td>
            <td colspan="3"> {{$approver->finance_officer_name}}</td>
        </tr>
        <tr class="info_content_border">
            <td > <b>Jawatan</b></td>
            <td > {{$approver->position_name}}</td>
            <td > Tarikh</td>
            <td > {{convertDateSys($approver->action_on)}}</td>
        </tr>
        <tr class="info_content_border">
            <td > <b>Dibatalkan</b></td>
            <td colspan="3"> </td>
        </tr>
        <tr class="info_content_border">
            <td > <b>Jawatan</b></td>
            <td ></td>
            <td > Tarikh</td>
            <td > </td>
        </tr>
    </table>

    <div class="bold">
        <p>No. Rujukan Kelulusan BPKS(8.15)248-10(SK.6)JD.35(60)<br>
        Dokumen ini dicetak dari pangkalan data dan telah ditandatangani secara digital. Tandatangan secara manual tidak diperlukan.</p>
    </div>

</div>
