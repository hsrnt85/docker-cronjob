<html>
    <head>
        <link href="{{ getPathDocumentCss() .'finance-report.css' }}" type="text/css" />
    </head>
    <body>

        <!-- Define header and footer blocks before your content -->
        @include('report.header')
        {{-- @include('report.footer') --}}
        <!-- Define header and footer blocks before your content -->
        <header >
            <table width="100%" cellspacing="0" cellpadding="3" class="bold">
                <tr class="center">
                    <td>KERAJAAN NEGERI JOHOR DARUL TAKZIM</td>
                </tr>
                <tr class="center title_padding">
                    <td>BUKU TUNAI PUNGUTAN/TERIMAAN PADA {{ $search_date_from }} HINGGA {{ $search_date_to }} </td>
                </tr>
            </table>

            <div class="pb-4"></div>

            <table width="100%" cellpadding="3" cellspacing="0" class="bold border-solid uppercase" >
                <tr >
                    <td width="11%" colspan="2"></td>
                    <td class="left" width="10%">KOD</td>
                    <td class="left">PERIHAL</td>
                </tr>
                <tr >
                    <td width="10%">JABATAN</td>
                    <td width="1%">:</td>
                    <td class="left">{{$fd->department_code ?? ''}}</td>
                    <td class="left">{{upperText($fd->department_name) ??''}}</td>
                </tr>
                <tr >
                    <td width="10%">PTJ/PK</td>
                    <td width="1%">:</td>
                    <td class="left">{{$fd->ptj_code ?? ''}}</td>
                    <td class="left">{{upperText($fd->ptj_name) ??''}}</td>
                </tr>
            </table>
        </header>
        <div class="pb-2"></div>

        <table width="100%" cellpadding="4" cellspacing="0">
            <thead>
                <tr role="row"  class="info_content_border bold">
                    <th class="text-center" rowspan="2" width="4%">BIL.</th>
                    <th class="text-center" rowspan="2" width="8%">TARIKH</th>
                    <th class="text-center" rowspan="2" width="15%">KAEDAH BAYARAN</th>
                    <th class="text-center" rowspan="2" width="9%">NO. RESIT</th>
                    <th class="center" rowspan="2" width="9%">AMAUN (RM)</th>
                    <th class="text-center" colspan="5">PEMBAYARAN KEPADA PERBENDAHARAAN</th>
                </tr>
                <tr class="info_content_border bold">
                    <th class="text-center" >NO. PENYATA PEMUNGUT <br> TARIKH SLIP BANK</th>
                    <th class="center" >AMAUN (RM)</th>
                    <th class="text-center" >NO. RESIT PERBENDAHARAAN <br> TARIKH RESIT</th>
                    <th class="text-center" >PERBEZAAN HARI DI BANK</th>
                    <th class="text-center" >STATUS</th>
                </tr>
            </thead>

            <tbody>

                @if($dataReport->count()>0)
                    @foreach($dataReport as $data)
                        <tr class="info_content_border">
                            <th class="text-center" scope="row">{{ $loop->iteration }}</th>
                            <td class="text-center" >{{ convertDateSys($data->payment_date) }}</td>
                            <td class="text-center" >{{ $data->payment_method }}</td>
                            <td class="text-center" >{{ $data->payment_receipt_no }}</td>
                            <td class="right" >{{ numberFormatComma($data->amount) }}</td>
                            <td class="text-center" >{{ $data->collector_statement_no }}<br/>{{ convertDateSys($data->bank_slip_date) }}</td>
                            <td class="right" >{{ numberFormatComma($data->amount) }}</td>
                            <td class="text-center" >{{ $data->receipt_no ?? ""}} <br/> {{ convertDateSys($data->receipt_date ?? "") }}</td>
                            <td class="text-center" >{{ 0 }}</td>
                            <td class="text-center" >{{ $data->status }}</td>
                        </tr>
                    @endforeach
                @endif
                @php
                    $total_amount = numberFormatComma($dataReport->sum('amount'));
                @endphp
                <tr class="info_content_border">
                    <td class="right bold" colspan="4" >JUMLAH KESELURUHAN</td>
                    <td class="right bold" >{{ $total_amount }}</td>
                    <td class="right" ></td>
                    <td class="right bold" >{{ $total_amount }}</td>
                    <td class="right" colspan="3"></td>
                </tr>

            </tbody>

        </table>

        <div class="pb-4"></div>

        <table width="40%" cellpadding="4" cellspacing="0" class="bold">
            <thead>
                <tr role="row"  class="info_content_border bold">
                    <th class="text-center" width="4%">BIL.</th>
                    <th class="center" width="8%">PENGKELASAN</th>
                    <th class="center" width="8%">JUMLAH (RM)</th>
                </tr>
            </thead>

            <tbody>
                @if(count($dataSummary)>0)
                    @php $bil=1; @endphp
                    @foreach($dataSummary as $payment_method => $amount)
                        <tr class="info_content_border">
                            <td class="text-center" width="4%">{{ $bil++ }}</td>
                            <td class="left" width="15%">{{ $payment_method }}</td>
                            <td class="right" width="15%">{{ numberFormatcomma($amount) }}</td>
                        </tr>
                    @endforeach
                @else
                    <tr class="info_content_border">
                        <td class="text-center" colspan="3">Tiada Rekod</td>
                    </tr>
                @endif
            </tbody>
        </table>

    </body>

</html>

