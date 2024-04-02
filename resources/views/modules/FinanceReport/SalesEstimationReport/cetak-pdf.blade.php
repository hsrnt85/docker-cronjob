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
                    <td>LAPORAN ANGGARAN TERIMAAN HASIL SEHINGGA {{ upperText($till_date_name) }}</td>
                </tr>
            </table>

            <div class="pb-4"></div>

            <table width="100%" cellpadding="3" cellspacing="0" class="bold uppercase"  >
                <!-- Department Information -->
                <tr>
                    <td width="10%">JABATAN</td>
                    <td width="1%">:</td>
                    <td class="left">{{ $fd->department_name ?? '' }}</td>

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

        <table width="100%" cellpadding="5" cellspacing="0">
            <thead>
                <!-- Table Header -->
                <tr role="row" class="info_content_border bold uppercase" >
                    <th width="5%"> Bil. </th>
                    <th width="30%"> Perihal Hasil </th>
                    <th width="15%"> Anggaran Hasil Tahun {{ $search_year }} <br/>(RM) </th>
                    <th width="15%"> Kutipan Hasil Sehingga <br/>{{ capitalizeText($till_date_name) }} <br/>(RM) </th>
                    <th width="20%"> Anggaran Hasil <br/>Dari {{ $date_from_estimation_name }} <br/>Hingga {{ $date_to_estimation_name }} <br/>(RM)</th>
                    <th width="15%"> Anggaran Hasil Keseluruhan Sehingga {{ $date_to_estimation_name }} <br/>(RM) </th>
                </tr>
            </thead>

            @php
                $sum_estimation_year = 0;
                $sum_till_date = 0;
                $sum_estimation_balance = 0;
                $sum_estimation_year_all = 0;
            @endphp
            <tbody>
                @if ($dataReport)
                    @foreach($dataReport as $account_type => $data)
                        @php
                            $total_estimation_year = $data['total_estimation_year'];
                            $total_till_date = $data['total_till_date'];
                            $total_estimation_balance = $data['total_estimation_balance'];
                            $total_estimation_year_all = $total_till_date + $total_estimation_balance;

                            $sum_estimation_year += $total_estimation_year;
                            $sum_till_date += $total_till_date;
                            $sum_estimation_balance += $total_estimation_balance;
                            $sum_estimation_year_all = $sum_till_date + $sum_estimation_balance;
                        @endphp
                        <tr class="info_content_border">
                            <td class="text-center" scope="row">{{ $loop->iteration }}</td>
                            <td>{{ $account_type }}</td>
                            <td style="text-align: right;">{{ numberFormatComma($total_estimation_year) }}</td>
                            <td style="text-align: right;">{{ numberFormatComma($total_till_date) }}</td>
                            <td style="text-align: right;">{{ numberFormatComma($total_estimation_balance) }}</td>
                            <td style="text-align: right;">{{ numberFormatComma($total_estimation_year_all) }}</td>
                        </tr>
                    @endforeach
                @endif
            </tbody>
            <tfoot>
                <tr class="info_content_border">
                    <td class="right" colspan="2" ><b>JUMLAH KESELURUHAN </b></td>
                    <td style="text-align: right;"><b>{{ numberFormatComma($sum_estimation_year) }}</b></td>
                    <td style="text-align: right;"><b>{{ numberFormatComma($sum_till_date) }}</b></td>
                    <td style="text-align: right;"><b>{{ numberFormatComma($sum_estimation_balance) }}</b></td>
                    <td style="text-align: right;"><b>{{ numberFormatComma($sum_estimation_year_all) }}</b></td>
                </tr>

            </tfoot>


        </table>

    </body>

</html>

