<table class="info_content_border" style="border-block-color: black;" cellpadding="4" cellspacing="0"  >
    <tr class="text-center" style="border: 1px solid black; border-bottom:none;" >
        <td colspan="13"> PUNGUTAN DIMASUKIRA KE DALAM AKAUN-AKAUN DI BAWAH</td>
    </tr>
    <tr style="border: 1px solid black; border-top:none;">
        <td colspan="13"></td>
    </tr>
    <tr class="info_content_border text-center" style="font-size: 9px;">
        <td width="5%"> Bil</td>
        <td width="5%"> Vot</td>
        <td width="9%"> Jab</td>
        <td width="8%"> PTJ/PK</td>
        <td width="8%"> Prog/Akt</td>
        <td width="8%"> Projek</td>
        <td width="8%"> Setia</td>
        <td width="8%"> Sub Setia</td>
        <td width="6%"> CP</td>
        <td width="10%"> Objek</td>
        <td width="10%"> Amaun (RM)</td>
        <td colspan="2" width="15%"> Kod Kegunaan Jabatan</td>
    </tr>
    <tr style="border: 1px solid black; border-bottom:none;" >
        <td colspan="13" style="text-align: top" class="text-center">Perihal Am </td>
    </tr>

    @foreach($collectorStatementVotList as $vot)
        <tr class="info_content_border" style="font-size: 9px;">
            <td width="5%" class="text-center"> {{$loop->iteration}}</td>
            <td width="5%" class="text-center"> {{$vot->income_account?->general_income_code}}</td>
            <td width="9%" class="text-center"> {{$finance_department->department_code}}</td>
            <td width="8%" class="text-center"> {{$finance_department->ptj_code}}</td>
            <td width="8%"> </td>
            <td width="8%"> </td>
            <td width="8%"> </td>
            <td width="8%"> </td>
            <td width="6%"> </td>
            <td width="10%">  {{$vot->income_account?->income_code}}</td>
            <td width="10%" class="text-right"> {{$vot->total_amount}}</td>
            <td colspan="2" width="15%"></td>
        </tr>
        <tr style="border: 1px solid black; border-bottom:none;" >
            <td colspan="13" style="text-align: top" class="text-left">{{$vot->income_account?->income_code_description}}</td>
        </tr>
    @endforeach

    <tr style="border: 1px solid black; border-top:none; border-bottom:none; min-height: 150px; ">
        <td colspan="13" style="padding: 10px;"> </td>

    </tr>
    <tr style="border: 1px solid black; border-top:none;  border-bottom:none; font-size: 9px;" >
        <td colspan="8" style="border-right:none;"></td>

        <td class="text-center">JUMLAH</td>
        <td class="text-right" style="border-top:1px solid black;">
            {{$collector_statement->collection_amount}}
        </td>

        <td class="text-center">Jumlah Bil. Subsidiari</td>
        <td style="border-top:1px solid black;" class="text-right" colspan="2">{{$collector_statement->collection_amount}}
        </td>
    </tr>
    <tr width="2px" style="border: 1px solid black; border-top:none;" >
        <td colspan="9" style="border-right:none;"></td>
        <td style="border-top:1px solid black;"></td>
        <td class="text-center"></td>
        <td style="border-top:1px solid black;" colspan="2"></td>
    </tr>
</table>
