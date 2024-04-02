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
                    <td>LAPORAN PERBANDINGAN YURAN PENYELENGGARAAN BAGI BULAN {{ $month_name }}  {{ $search_year }} </td>
                </tr>
            </table>

            <div class="pb-4"></div>

            <table width="100%" cellpadding="2" cellspacing="0" class="bold" >
                <tr >
                    <td width="10%">JABATAN</td>
                    <td width="1%">:</td>
                    <td class="left">{{upperText($fd->department_name) ??''}}</td>
                </tr>
                <tr >
                    <td >KOD JABATAN</td>
                    <td >:</td>
                    <td >{{upperText($fd->department_code) ?? ''}}</td>
                </tr>
                <tr >
                    <td >PTJ</td>
                    <td >:</td>
                    <td >{{upperText($fd->ptj_code).' - ' ?? ''}} {{upperText($fd->ptj_name)?? ''}}</td>
                </tr>
            </table>
        </header>
        <div class="pb-4"></div>
        {{-- List report --}}
        <table width="100%" cellpadding="5" cellspacing="0">
            <thead>
                <tr role="row" class="info_content_border bold uppercase text-center">
                    <th width="5%"> Bil. </th>
                    <th width="20%"> Nama </th>
                    <th width="10%"> No. Kad Pengenalan </th>
                    <th width="35%"> Alamat Rumah </th>
                    <th width="10%"> Anggaran <br/>(RM) </th>
                    <th width="10%"> Kutipan Sebenar <br/>(RM) </th>
                    <th width="10%"> Varian <br/>(RM) </th>
                </tr>
            </thead>
            @php
                $sum_expectation_amount = 0;
                $sum_amount = 0;
                $sum_varian = 0;
            @endphp
            <tbody>
                @if ($dataReport)
                    @foreach($dataReport as $data)
                        @php
                            $quarters_address = ($data->unit_no ?? '').' '.$data->address_1.' '.$data->address_2.' '.$data->address_3;
                            $expectation_amount = $data->maintenance_fee ?? 0;
                            $amount = $data->amount ?? 0;
                            $varian = $expectation_amount - $amount;

                            $sum_expectation_amount += $expectation_amount;
                            $sum_amount += $amount;
                            $sum_varian += $varian;
                        @endphp
                        <tr class="info_content_border">
                            <th class="text-center" scope="row">{{ $loop->iteration.'.' }}</th>
                            <td >{{ $data->name ?? '' }}</td>
                            <td class="text-center" >{{ $data->new_ic ?? '' }}</td>
                            <td >{{ $quarters_address ?? '' }}</td>
                            <td class="right" >{{ numberFormatComma($expectation_amount) }}</td>
                            <td class="right" >{{ numberFormatComma($amount) }}</td>
                            <td class="right" >{{ numberFormatComma($varian) }}</td>
                        </tr>
                    @endforeach
                @endif
            </tbody>
            <tfoot>
                <tr class="info_content_border bold right">
                    <td colspan="4" ><b>JUMLAH KESELURUHAN </b></td>
                    <td >{{ numberFormatComma($sum_expectation_amount) }}</td>
                    <td >{{ numberFormatComma($sum_amount) }}</td>
                    <td >{{ numberFormatComma($sum_varian) }}</td>
                </tr>
            </tfoot>
            {{-- end of list--}}
        </table>

    </body>

</html>
