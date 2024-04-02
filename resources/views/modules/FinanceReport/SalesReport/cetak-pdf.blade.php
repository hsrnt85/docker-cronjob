
<html>
    <head>
        <link href="{{ getPathDocumentCss() .'finance-report.css' }}" type="text/css" />
    </head>
    <div class="pt-2"></div>
    <body>
        <!-- Define header and footer blocks before your content -->
        @include('report.header')
        {{-- @include('report.footer') --}}
        <!-- Define header and footer blocks before your content -->
        <header >
            <div class="pb-1"></div>
            <table width="100%" cellspacing="0" cellpadding="3" class="bold">
                <tr class="center">
                    <td>KERAJAAN NEGERI JOHOR DARUL TAKZIM</td>
                </tr>
                <tr class="center title_padding">
                    <td>LAPORAN TERIMAAN HASIL {{ $selectedTunggakan }} {{ $selectedTerimaan }} PADA {{ convertDateSys($search_date_from) }} HINGGA {{ convertDateSys($search_date_to) }} </td>
                </tr>
            </table>

            <div class="pb-2"></div>

            <table width="100%" cellpadding="3" cellspacing="0" class="bold uppercase">
                <tr >
                    <td width="10%">JABATAN</td>
                    <td width="1%">:</td>
                    <td class="left">{{upperText($fd->department_name) ??''}}</td>
                </tr>
                <tr >
                    <td >KOD JABATAN</td>
                    <td >:</td>
                    <td >{{$fd->department_code ?? ''}}</td>
                </tr>
                <tr >
                    <td >PTJ</td>
                    <td >:</td>
                    <td >{{$fd->ptj_code ?? ''}} - {{upperText($fd->ptj_name)?? ''}}</td>
                </tr>
                <tr >
                    <td >KOD AKAUN</td>
                    <td >:</td>
                    <td >{{$search_account ?? ''}} - {{$selectedReceiptType ?? ''}}</td>
                </tr>
            </table>
        </header>
        {{-- <div class="pb-4"></div> --}}

        <table class="info_content_border"  cellspacing="0" cellpadding="5" style="padding-top:10px">
            {{-- List of sales report --}}
            <thead class="info_content_border">
                <tr role="row" class="info_content_border bold text-center uppercase" >
                    <th>Bil. </th>
                    <th>No. Resit</th>
                    <th>Nama Pembayar</th>
                    <th>Perihal Bayaran</th>
                    <th>Tarikh<br> Urusniaga</th>
                    <th>Masa<br> Urusniaga</th>
                    <th>Bentuk Bayaran</th>
                    <th>Kod Akaun</th>
                    <th>Jumlah Bayaran (RM)</th>
                </tr>
            </thead>

            <tbody>
                @if ($recordList -> count() == 0)
                <tr class="info_content_border">
                    <td class="text-center" colspan="9">Tiada Rekod</td>
                </tr>
            @else
                @foreach($recordList as $bil => $sr)
                    @php $payer_name = ($sr->payment_category_id!=3) ? $sr->payer_name : $sr->tenant?->name; @endphp
                    <tr class="info_content_border">
                        <th class="text-center" scope="row">{{ ++$bil }}</th>
                        <td class="text-center" >{{ $sr -> payment_receipt_no ??'' }}</td>
                        <td >{{ $payer_name ?? '-' }}</td>
                        <td >{{ $sr -> payment_description }}</td>
                        <td class="text-center" >{{ convertDateSys($sr -> payment_date) }}</td>
                        <td class="text-center" >{{  $sr->payment_time  }}</td>
                        <td class="text-center" >{{ $sr -> payment_category }}</td>
                        <td class="text-center" >{{ $sr -> income_code}} </td>
                        <td class="right" >{{ numberFormatComma($sr -> amount )}}</td>
                    </tr>
                @endforeach
                <tr class="info_content_border bold right">
                    <td colspan="8" >JUMLAH KESELURUHAN</td>
                    <td >{{ numberFormatComma($recordList ->sum('amount')) }}</td>
                </tr>
            @endif
            </tbody>
            {{-- end of list of sales report --}}
        </table>
    </div>
    </body>

</html>

