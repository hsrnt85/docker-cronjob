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
                <tr class="title_header center title_header_padding">
                    <td colspan="8">KERAJAAN NEGERI JOHOR DARUL TAKZIM</td>
                </tr>
                <tr class="title_header center title_padding">
                    <td colspan="8">PENYATA INDIVIDU DARI {{ $search_date_from }} HINGGA {{ $search_date_to }} </td>
                </tr>
            </table>


            <div class="pb-4"></div>

            <table width="100%" cellpadding="3" cellspacing="0" class="bold uppercase" >
                <tr >
                    <td width="15%">NAMA PEMBAYAR</td>
                    <td width="1%">:</td>
                    <td class="left">{{ $dataTenant?->name }}</td>
                </tr>
                <tr >
                    <td >NO K/P</td>
                    <td >:</td>
                    <td >{{$search_ic_no ?? ''}}</td>
                </tr>
                <tr >
                    <td >ALAMAT</td>
                    <td >:</td>
                    <td class="left">{{ $dataTenant?->quarters_address }}</td>
                </tr>
            </table>
        </header>

        <div class="pb-4"></div>
        {{-- List report --}}
        <table width="100%" cellpadding="5" cellspacing="0" style="font-size: 10px">
            <thead>
                <tr role="row" class="info_content_border bold uppercase ">
                    <th class="text-center" width="5%"> Bil. </th>
                    <th class="text-center" width="10%"> Tarikh </th>
                    <th class="text-center" width="15%"> No. Rujukan </th>
                    <th class="text-center"> Perihal </th>
                    <th class="text-center" width="13%"> Debit (RM) </th>
                    <th class="text-center" width="13%"> Kredit (RM) </th>
                    <th class="text-center" width="13%"> Baki (RM) </th>
                </tr>
            </thead>

            <tbody>
                @if (!$dataReport)
                    <tr>
                        <td class="text-center" colspan="7">Tiada Rekod</td>
                    </tr>
                @else
                    @php
                        // $bb_amount = ($dataBb?->bb_amount) ? $dataBb?->bb_amount : 0;
                        // $balance_amount = $bb_amount;
                        $bb_amount_unpaid = ($dataBbUnpaid?->bb_amount) ? $dataBbUnpaid?->bb_amount : 0;
                        $bb_amount_paid = ($dataBbPaid?->bb_amount) ? $dataBbPaid?->bb_amount : 0;
                        $bb_amount = $bb_amount_unpaid + $bb_amount_paid;
                        $balance_amount = $bb_amount;
                    @endphp
                    <tr class="info_content_border">
                        <td class="right bold uppercase" colspan="4">Baki Bawa Hadapan</td>
                        <td class="right" ></td>
                        <td class="right" ></td>
                        <td class="right bold" >{{ numberFormatComma($bb_amount) }}</td>
                    </tr>
                    @foreach($dataReport as $bil => $data)
                        @php
                            $debit_amount = $data->debit_amount ? $data->debit_amount : 0;
                            $credit_amount = $data->credit_amount ? $data->credit_amount : 0;
                            $balance_amount += $debit_amount - $credit_amount;
                        @endphp
                        <tr class="info_content_border">
                            <td class="text-center" width="2%">{{ ++$bil }}</td>
                            <td class="text-center" width="5%">{{ convertDateSys($data->transaction_date) }}</td>
                            <td class="text-center" >{{ $data->transaction_no }}</td>
                            <td width="35%">{{ $data->description}}</td>
                            <td class="right" width="8%">{{ numberFormatComma($debit_amount) }}</td>
                            <td class="right" width="8%">{{ numberFormatComma($credit_amount) }}</td>
                            <td class="right bold" width="8%">{{ numberFormatComma($balance_amount) }}</td>
                        </tr>
                    @endforeach
                    <tr class="info_content_border">
                        <td class="right bold uppercase" colspan="4">Baki Bayaran</td>
                        <td class="text-center" ></td>
                        <td class="text-center" ></td>
                        <td class="right bold" >{{ numberFormatComma($balance_amount) }}</td>
                    </tr>

                @endif
            </tbody>

        </table>

    </body>

</html>

