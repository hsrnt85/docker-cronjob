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
                    <td>LAPORAN JURNAL PELARASAN PADA {{ $search_from_convert }} HINGGA {{ $search_to_convert }} </td>
                </tr>
            </table>

            <div class="pb-4"></div>

            <table width="100%" cellpadding="3" cellspacing="0" class="bold uppercase">
                <tr >
                    <td width="10%">JABATAN</td>
                    <td width="1%">:</td>
                    <td class="left">{{$fd->department_name ??''}}</td>
                </tr>
                <tr >
                    <td >KOD JABATAN</td>
                    <td >:</td>
                    <td >{{$fd->department_code ?? ''}}</td>
                </tr>
                <tr >
                    <td >PTJ</td>
                    <td >:</td>
                    <td >{{$fd->ptj_code ?? ''}} - {{$fd->ptj_name?? ''}}</td>
                </tr>
            </table>
        </header>
        <div class="pb-4"></div>
            {{-- List report --}}
            <table width="100%" cellpadding="4" cellspacing="0" >
            <thead>
                <tr role="row" class="info_content_border bold uppercase"  style="font-size:11px">
                    <th class="text-center" >Bil. </th>
                    <th class="text-center" >No. Jurnal</th>
                    <th class="text-center" >No. Rujukan</th>
                    <th class="text-center" >Tarikh Jurnal</th>
                    <th class="text-center" >Perihal</th>
                    <th class="text-center" >Kod Akaun</th>
                    <th class="text-center" >Debit (RM)</th>
                    <th class="text-center" >Kredit (RM)</th>
                </tr>
            </thead>

            @php  $bil = 0; @endphp
            <tbody >
                @if ($recordListAll->count() == 0)
                    <tr class="info_content_border">
                        <td class="text-center" colspan="8">Tiada Rekod</td>
                    </tr>
                @else
                    @foreach ($groupedRecords as $key => $group)
                        @foreach ($group as $j)
                            <tr class="info_content_border">
                                @if ($loop->first)
                                    <td class="text-center" rowspan="{{ $j->rowspan }}">{{ ++$bil }}</th>
                                    <td class="text-center" rowspan="{{ $j->rowspan }}">{{ $j->journal_no ?? '' }}</td>
                                    <td class="text-center" rowspan="{{ $j->rowspan }}">{{ $j->collector_statement_no ?? '' }}</td>
                                    <td class="text-center" rowspan="{{ $j->rowspan }}">{{ $j->journal_date ?? '' }}</td>
                                    <td class="text-center" rowspan="{{ $j->rowspan }}">{{ upperText($j->description)  ?? ''}} </td>
                                @endif

                                <td class="text-center">{{ $j->ispeks_account_code }}</td>

                                <td class="right">{{ $j->debit_amount_formatted }}</td>
                                <td class="right">{{ $j->credit_amount_formatted }}</td>
                            </tr>
                        @endforeach

                        <tr class="info_content_border">
                            <td class="right" colspan="6"><strong>JUMLAH (RM)</strong></td>
                            <td class="right" colspan="1"><strong>{{ $journalSums[$key]['debit'] }}<strong></td>
                            <td class="right" colspan="1"><strong>{{ $journalSums[$key]['credit'] }}<strong></td>
                        </tr>
                    @endforeach

                    <tr class="info_content_border bold">
                        <td class="right" colspan="6">JUMLAH KESELURUHAN</td>
                        <td class="right" colspan="1">{{ $totalDebit }}</td>
                        <td class="right" colspan="1">{{ $totalCredit }}</td>
                    </tr>
                @endif
            </tbody>
            {{-- end of list--}}
        </table>

    </body>

</html>

