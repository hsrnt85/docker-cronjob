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
                    <td>LAPORAN JURNAL PELARASAN DALAMAN PADA {{$search_date_from}} HINGGA {{$search_date_to}}  </td>
                </tr>
            </table>

            <div class="pb-4"></div>

            <table width="100%" cellpadding="3" cellspacing="0" class="bold uppercase">
                <tr >
                    <td width="10%">JABATAN</td>
                    <td width="1%">:</td>
                    <td class="left">{{ $fd->department_name ?? '' }}</td>
                </tr>
                <tr >
                    <td >KOD JABATAN</td>
                    <td >:</td>
                    <td class="left">{{ $fd->department_code ?? '' }}</td>
                </tr>
                <tr >
                    <td >PTJ</td>
                    <td >:</td>
                    <td class="left">{{ $fd->ptj_code ?? '' }} - {{ $fd->ptj_name ?? '' }}</td>
                </tr>
            </table>
        </header>

        <div class="pb-4"></div>
            {{-- List report --}}
            <table width="100%" cellpadding="4" cellspacing="0">
            <thead>
                <tr role="row" class="info_content_border bold" >
                    <td class="text-center" >BIL.</td>
                    <th class="text-center" >TARIKH JURNAL</th>
                    <th class="text-center" >NO. JURNAL</th>
                    <th class="text-center" >NO. NOTIS BAYARAN</th>
                    <th class="text-center" >NAMA PENYEWA</th>
                    <th class="text-center" >PERIHAL</th>
                    <th class="text-center" >JUMLAH NOTIS (RM)</th>
                    <th class="text-center" >JUMLAH PELARASAN (RM)</th>
                    <th class="text-center" >JUMLAH AKHIR (RM)</th>
                </tr>
            </thead>

            @php  $bil = 0; @endphp
            <tbody>
                @if ($getInternalJournal->count() == 0)
                    <tr class="info_content_border">
                        <td class="text-center" colspan="9">Tiada Rekod</td>
                    </tr>
                @else
                    @foreach ($getInternalJournal as $j)
                        <tr class="info_content_border">
                            <th class="text-center" scope="row">{{ ++$bil }}</th>
                            <td class="text-center" >{{ convertDateSys($j->journal_date) ??'' }}</td>
                            <td class="text-center" >{{ $j->journal_no ??'' }}</td>
                            <td class="text-center" >{{ $j->payment_notice->payment_notice_no ?? ''   }}</td>
                            <td class="text-center" >{{ $j->tenants_name ??'' }}</td>
                            <td class="text-center" >{{ $j->description ??'' }}</td>
                            <td class="right" >{{ numberFormatComma($j->payment_notice_amount ??'') }}</td>
                            <td class="right" >{{ numberFormatComma($j->adjustment_amount ??'') }}</td>
                            <td class="right" >{{ numberFormatComma($j->total_amount ??'') }}</td>
                        </tr>
                        @php @endphp
                    @endforeach
                    <tr class="info_content_border bold">
                        <td class="right" colspan="6">JUMLAH KESELURUHAN</td>
                        <td class="right" >{{ numberFormatComma($j->sum('payment_notice_amount')) }}</td>
                        <td class="right" >{{ numberFormatComma($j->sum('adjustment_amount')) }}</td>
                        <td class="right" >{{ numberFormatComma($j->sum('total_amount')) }}</td>
                    </tr>
                @endif
            </tbody>
            {{-- end of list--}}
        </table>

    </body>

</html>

