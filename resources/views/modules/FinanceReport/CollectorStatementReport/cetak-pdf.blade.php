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
            <table width="100%" cellspacing="0">
                <tr class="title_header center title_header_padding">
                    <td colspan="18">KERAJAAN NEGERI JOHOR DARUL TAKZIM</td>
                </tr>

                <tr class="title_header center title_header_padding">
                    <td colspan="18">TAHUN KEWANGAN {{ getYearFromDate($search_date_from_db) }} </td>
                </tr>

                <tr class="title_header center title_padding">
                    <td colspan="18">LAPORAN PENYATA PEMUNGUT BAGI TEMPOH {{ convertDateSys($search_date_from_db) }} HINGGA {{ convertDateSys($search_date_to_db) }} </td>
                </tr>
            </table>

            <table width="100%" cellpadding="3" cellspacing="0" class="bold uppercase">
                <!-- Department Information -->
                <tr>
                    <td width="10%">JABATAN</td>
                    <td width="1%">:</td>
                    <td class="left" >{{ $fd->department_name ?? '' }}</td>

                </tr>
                <tr>
                    <td>KOD JABATAN</td>
                    <td>:</td>
                    <td class="left" >{{ $fd->department_code ?? '' }}</td>

                </tr>
                <tr>
                    <td>PTJ</td>
                    <td>:</td>
                    <td class="left" >{{ $fd->ptj_code ?? '' }} - {{ $fd->ptj_name ?? '' }}</td>

                </tr>
            </table>
        </header>

        <div class="pb-4"></div>

            {{-- List of penyata Pemungut --}}
        <table width="100%" cellspacing ="0" cellpadding="4">
            <thead>
                <tr role="row" class="info_content_border">
                    <th class="text-center" rowspan="2">BIL.</th>
                    <th class="text-center" colspan="2">PENYATA PEMUNGUT</th>
                    <th class="text-center" colspan="3">TEMPOH PUNGUTAN</th>
                    <th class="text-center" colspan="2">MEMBAYAR</th>
                    <th class="text-center" colspan="8">DIMASUKIRA</th>
                    <th class="text-center" rowspan="2">NO. RESIT BN</th>
                    <th class="text-center" rowspan="2">TARIKH RESIT BN</th>
                    <th class="text-center" rowspan="2">KOD STATUS</th>
                    <th class="text-center" rowspan="2">AMAUN (RM)</th>
                </tr>
                <tr role="row" class="info_content_border">
                    <th class="text-center">NO. RUJ</th>
                    <th class="text-center">TARIKH</th>
                    <th class="text-center">DARI</th>
                    <th class="text-center">HINGGA</th>
                    <th class="right">JUMLAH</th>
                    <th class="text-center">JAB</th>
                    <th class="text-center">PTJ/PK</th>
                    <th class="text-center">VOT</th>
                    <th class="text-center">JAB</th>
                    <th class="text-center">PTJ/PK</th>
                    <th class="text-center">PROG/AKT</th>
                    <th class="text-center">PROJEK</th>
                    <th class="text-center">SETIA</th>
                    <th class="text-center">CP</th>
                    <th class="text-center">KOD AKAUN</th>
                </tr>
            </thead>

            <tbody style="font-size: 10px;">
                @if ($collectorStatementListAll->count() == 0)
                    <tr class="info_content_border">
                        <td class="text-center" colspan="20">Tiada Rekod</td>
                    </tr>
                @else
                    @foreach($collectorStatementListAll as $bil => $data)
                        <tr class="info_content_border">
                            <th class="text-center" scope="row">{{ ++$bil }}</th>
                            <td class="text-center" >{{ $data->collector_statement_no ??'' }}</td>
                            <td class="text-center" >{{ convertDateSys($data->collector_statement_date)}}</td>
                            <td class="text-center" >{{ convertDateSys($data->collector_statement_date_from)}}</td>
                            <td class="text-center" >{{ convertDateSys($data->collector_statement_date_to)}}</td>
                            <td class="right" >{{ numberFormatComma($data->total_amount) }}</td>
                            <td class="text-center" >{{ $data->department_code ??'' }}</td>
                            <td class="text-center" >{{ $data->ptj_code ??'' }}</td>
                            <td class="text-center" >{{ $data->general_income_code ??'' }}</td>
                            <td class="text-center" >{{ $data->department_code ??'' }}</td>
                            <td class="text-center" >{{ $data->ptj_code ??'' }}</td>
                            <td class="text-center" ></td>
                            <td class="text-center" ></td>
                            <td class="text-center" ></td>
                            <td class="text-center" ></td>
                            <td class="text-center" >{{ $data->income_code ??'' }}</td>
                            <td class="text-center" ></td>
                            <td class="text-center" ></td>
                            <td class="text-center" >{{ $data->status_code ??'' }}</td>
                            <td class="right" >{{ numberFormatComma($data->total_amount) }}</td>
                        </tr>
                    @endforeach
                        <tr class="info_content_border">
                            <td class="right bold" colspan="19" >JUMLAH KESELURUHAN</td>
                            <td class="right bold" colspan="1" >{{ numberFormatComma($collectorStatementListAll ->sum('total_amount')) }}</td>
                        </tr>
                @endif
            </tbody>
            {{-- end of list of penyata Pemungut --}}
        </table> {{-- end of table pp --}}

        {{-- Status Code  --}}
        <div class="pb-4"></div>
        <table width="50%" cellspacing="0" cellpadding="4">
            <thead class="thead-dark">
                <tr role="row" class="info_content_border">
                    <th class="text-center" colspan="4">RINGKASAN STATUS PENYATA PEMUNGUT</th>
                </tr>
                <tr role="row" class="info_content_border">
                    <th class="text-center">BIL.</th>
                    <th class="text-center">KOD STATUS</th>
                    <th class="text-center">PERIHAL</th>
                    <th class="text-center">JUMLAH (RM)</th>
                </tr>
            </thead>

            <tbody>
                @if (count($summaryStatusPenyataPemungutArr) == 0)
                    <tr class="info_content_border">
                        <td class="text-center" colspan="4">Tiada Rekod</td>
                    </tr>
                @else
                    @foreach($summaryStatusPenyataPemungutArr as $i => $data)
                        <tr class="info_content_border">
                            <th class="text-center" scope="row">{{ $data['bil'] }}</th>
                            <td class="text-center" >{{ $data['transaction_status_id'] }}</td>
                            <td class="text-center" >{{ $data['status'] }}</td>
                            <td class="right" >{{ numberFormatComma($data['total']) }}</td>
                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>

    </body>

</html>

