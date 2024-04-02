

<link href="{{ getPathDocumentCss() .'document-pdf.css' }}" type="text/css" />
<div class="body_resit ">

    @php
        $payment_receipt_no = $tpt->payment_receipt_no ;
        $payment_date = $tpt->payment_date ;
        $eft_no = $tpt->eft_no ;
        $month = getMonthFromDate($payment_date);
        $year = getYearFromDate($payment_date);
        $payment_description = $tpt->payment_description ;
        $total_payment = $tpt->total_payment ?? 0;
        $tenant_name = $tpt->tenant?->name;
        $tenant_ic = $tpt->tenant?->new_ic;
        $unit_no = $tpt->tenant?->quarters?->unit_no;
        $address_1 = $tpt->tenant?->quarters?->address_1;
        $address_2 = $tpt->tenant?->quarters?->address_2;
        $address_3 = $tpt->tenant?->quarters?->address_3;
    @endphp

    <div>
        <table width="100%" cellpadding="0" cellspacing="0">
            <tr class="grid_content"  >
                <td class="data center mb-4"><img  src="{{ getJataJohor() }}" alt="jata_johor" width="auto" height="70px"></td>
            </tr>
        </table>
    </div>

    <div class="text-center mb-20px" style="font-size: 13px;">
            <b>KERAJAAN NEGERI JOHOR DARUL TAKZIM</b><br> <b>RESIT RASMI</b>
            {{-- <br><b>BAYARAN SEWA/DENDA/PENYELENGGARAAN KUARTERS {{ upperText(getMonthName($month)) }} {{ $year }}</b> --}}
    </div>

    <div class="pb-3"></div>

    <table width="100%" cellpadding="3" cellspacing="0">
        <tr class="info_content_borderless" >
            <td width="62%"></td>
            <td width="13%" class="left"><b>NO. RESIT</b></td>
            <td width="5%">:</td>
            <td width="20%"class="left">{{ $payment_receipt_no }}</td>
        </tr>
        <tr class="info_content_borderless" >
            <td width="62%"></td>
            <td width="13%" class="left"><b>TARIKH RESIT</b></td>
            <td width="5%">:</td>
            <td width="20%" class="left">{{ convertDateTimeSys($payment_date) }}</td>
        </tr>
        <tr class="info_content_borderless" >
            <td width="62%"></td>
            <td width="13%" class="left"><b>NO. EFT</b></td>
            <td width="5%">:</td>
            <td width="20%" class="left">{{ $recon->eft_no }}</td>
        </tr>
    </table>

    <div class="pb-4"></div>

    <table width="100%" cellpadding="3" cellspacing="0">
        <tr class="info_content_borderless" >
            <td width="18%" class="left"><b> NAMA PEMBAYAR</b></td>
            <td width="2%">:</td>
            <td width="80%"class="left">{{ $payer?->payer_name ?? ""}}</td>
        </tr>
        <tr class="info_content_borderless" >
            <td width="18%" class="left"><b> ALAMAT</b></td>
            <td width="2%">:</td>
            <td width="80%" class="left">{{ $payer?->payer_address ?? ""}}</td>
        </tr>
    </table>

    <div class="pb-4"></div>

    <table width="100%" cellpadding="4" cellspacing="0">
        <tr class="info_content_border">
            <th width="5%">BIL</th>
            <th width="70%">PERIHAL TERIMAAN</th>
            {{-- <th >CARA BAYARAN</th>
            <th >NO.RUJUKAN / TARIKH</th> --}}
            <th >AMAUN (RM)</th>
        </tr>
        <tr class="info_content_border">
            <td class="text-center"><b>1</b></td>
            <td >{{ $payment_description }}</td>
            {{-- <td ></td>
            <td ></td> --}}
            <td class="text-center"><b>{{ numberFormatComma($total_payment) }}</b></td>
        </tr>
        <tr class="info_content_border">
            <td colspan="2" class="right"><b>JUMLAH</b></td>
            <td class="text-center"><b>{{ numberFormatComma($total_payment) }}</b></td>
        </tr>
    </table>

    <div class="pb-3"></div>

    <table width="100%" cellpadding="3" cellspacing="0">
        <tr class="info_content_borderless" >
            <td width="18%" class="left"><b> RINGGIT MALAYSIA </b></td>
            <td width="2%">:</td>
            <td width="80%"class="left"> {{ upperText(convertNumbertoStatementMal($total_payment)) }}</td>
        </tr>
        <tr class="info_content_borderless">
            <td colspan="3" style="height:15px"></td>
        </tr>
        <tr class="info_content_borderless" >
            <td width="18%" class="left"><b> JABATAN</b></td>
            <td width="2%">:</td>
            <td width="80%" class="left"> {{ upperText($finance_dept->department_name) }}</td>
        </tr>
        <tr class="info_content_borderless" >
            <td width="18%" class="left"><b> PTJ</b></td>
            <td width="2%">:</td>
            <td width="80%" class="left"> {{ upperText($finance_dept->ptj_name) }}</td>
        </tr>
        <tr class="info_content_borderless" >
            <td width="18%" class="left"><b> PUSAT TERIMAAN</b></td>
            <td width="2%">:</td>
            <td width="80%" class="left"> {{ upperText($finance_dept->ptj_code.' - '.$finance_dept->ptj_name) }}</td>
        </tr>
    </table>

</div>
