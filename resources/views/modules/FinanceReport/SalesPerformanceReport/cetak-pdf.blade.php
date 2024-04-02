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
                    <td>LAPORAN PRESTASI TERIMAAN HASIL SEHINGGA {{ upperText($till_date_name) }}</td>
                </tr>
            </table>

            <div class="pb-4"></div>

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
                    <td class="left">{{ $fd->ptj_code ?? '' }} - {{ $fd->ptj_name ?? '' }}</td>

                </tr>
            </table>
        </header>

        <div class="pb-4"></div>
        {{-- List report --}}
        <table width="100%" cellpadding="4" cellspacing="0">
            <thead>
                <!-- Table Header -->
                <tr role="row" class="info_content_border bold uppercase">
                    <th class="text-center" width="5%"> Bil. </th>
                    {{-- <th class="text-center" width="10%"> Kod Hasil </th> --}}
                    <th class="text-center" width="30%"> Perihal Hasil </th>
                    <th class="text-center" width="15%"> Kutipan Sebenar <br/>Tahun {{ $year_prev }} (RM) </th>
                    <th class="text-center" width="15%"> Anggaran Hasil <br/>Tahun {{ $search_year }} (RM) </th>
                    <th class="text-center" width="15%"> Kutipan Hasil Sehingga <br/>{{ capitalizeText($till_date_name) }} (RM) </th>
                    <th class="text-center"> Peratus (%) </th>
                </tr>
            </thead>
            @php
                $sum_last_year = 0;
                $sum_estimation = 0;
                $sum_current = 0;
            @endphp
            <tbody>
                @if ($dataReport)
                    @foreach($dataReport as $account_type => $data)
                        @php
                            $total_last_year = $data['total_last_year'];
                            $total_estimation = $data['total_estimation'];
                            $total_current = $data['total_current'];

                            $percentage = ($total_estimation>0) ? ($total_current/$total_estimation) * 100 : 0;

                            $sum_last_year += $total_last_year;
                            $sum_estimation += $total_estimation;
                            $sum_current += $total_current;
                            if ($sum_estimation != 0) {
                                $sum_percentage = $sum_current / $sum_estimation * 100;
                            } else {
                                $sum_percentage = 0; // or any other value you want to assign when the denominator is zero
                            }
                        @endphp
                        <tr class="info_content_border">
                            <td class="text-center" scope="row">{{ $loop->iteration }}</td>
                            <td >{{ $account_type }}</td>
                            <td style="text-align: right;">{{  numberFormatComma($total_last_year) }}</td>
                            <td style="text-align: right;">{{  numberFormatComma($total_estimation) }}</td>
                            <td style="text-align: right;">{{  numberFormatComma($total_current) }}</td>
                            <td style="text-align: right;">{{  numberFormatNoComma($percentage) }}</td>
                        </tr>
                    @endforeach
                @endif
            </tbody>
            <tfoot>
                <tr class="info_content_border">
                    <td class="right" colspan="2" ><b>JUMLAH KESELURUHAN</b></td>
                    <td class="right"><b>{{  numberFormatComma($sum_last_year) }}</b></td>
                    <td class="right"><b>{{  numberFormatComma($sum_estimation) }}</b></td>
                    <td class="right"><b>{{  numberFormatComma($sum_current) }}</b></td>
                    <td class="right"><b>{{ numberFormatComma($sum_percentage) }}</b></td>
                </tr>
            </tfoot>

        </table>

    </body>

</html>
