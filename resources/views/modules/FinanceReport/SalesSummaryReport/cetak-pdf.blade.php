
<html>
    <head>
        <link href="{{ getPathDocumentCss() .'finance-report.css' }}" type="text/css" />
    </head>
    <body>

        <!-- Define header and footer blocks before your content -->
        @include('report.header')
        {{-- {{-- @include('report.footer') --}}
        <!-- Define header and footer blocks before your content -->
        <header >

            <table width="100%" cellspacing="0" cellpadding="3" class="bold">
                <tr class="center">
                    <td>KERAJAAN NEGERI JOHOR DARUL TAKZIM</td>
                </tr>
                <tr class="center title_padding">
                    <td>LAPORAN RINGKASAN TERIMAAN HASIL {{$date_title}} </td>
                </tr>
            </table>

            <div class="pb-4"></div>

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
            </table>
        </header>

        <div class="pb-4"></div>

        <table class="info_content_border"  cellspacing="0" cellpadding="5">
            <thead >
                <tr role="row" class="info_content_border uppercase">
                    <th class="text-center" width="8%"> Bil. </th>
                    <th class="text-center"> Kod Hasil </th>
                    <th class="text-center"> Kod Hasil Terimaan </th>
                    <th class="text-center"> Perihal Hasil </th>
                    <th class="text-center"> Jumlah Kutipan (RM) </th>
                </tr>
            </thead>

            <tbody>
                @if ($summaryData -> count() == 0)
                    <tr class="info_content_border">
                        <td class="text-center" colspan="5">Tiada Rekod</td>
                    </tr>
                @else
                    @foreach($summaryData as $bil => $data)
                        <tr class="info_content_border">
                            <th width="8%" class="text-center" scope="row">{{ $loop->iteration }}</th>
                            <td class="text-center" >{{ $data->ispeks_account_code ??'' }}</td>
                            <td class="text-center" >{{ $data->income_code ??'' }}</td>
                            <td width="45%">{{ $data->income_code_description ??'' }}</td>
                            <td class="right" >{{ numberFormatComma($data->total_amount ??'') }}</td>
                        </tr>
                    @endforeach
                @endif
                <tr class="info_content_border">
                    <td class="right bold" colspan="4" ><b>JUMLAH KESELURUHAN</b></td>
                    <td class="right bold">{{ numberFormatComma($summaryData->sum('total_amount')) }}</td>
                </tr>
            </tbody>
            {{-- end of list of sales report --}}
        </table>

    </body>

</html>

