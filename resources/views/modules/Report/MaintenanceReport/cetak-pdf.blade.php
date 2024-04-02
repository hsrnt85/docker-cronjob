<html>
    <head>
        <link href="{{ getPathDocumentCss() .'report.css' }}" type="text/css" />
    </head>
    <body>

        <!-- Define header and footer blocks before your content -->
        @include('report.header')
        @include('report.footer')
        <!-- Define header and footer blocks before your content -->
        <header>
            <table width="100%" cellspacing="0">
                <tr class="title_header center title_header_padding">
                    <td colspan="10">LAPORAN PENYELENGGARAAN KUARTERS KERAJAAN<br />{{ $selectedQuartersCatPdf->name }}</td>
                </tr>
                <tr class="title_header center title_header_padding">
                    <td colspan="10">TARIKH ADUAN DARI {{ convertDateSys($selectedDateFrom) }} HINGGA {{ convertDateSys($selectedDateTo) }} </td>
                </tr>
            </table>
        </header>

        <table width="100%" cellspacing="0" cellpadding="3" >

            <thead class="thead-dark">
                <tr class="grid_header center header_border">
                    <th width="4%" class="text-center">Bil.</th>
                    <th width="8%" class="text-center">No. Aduan</th>
                    <th width="8%" class="text-center">Tarikh Aduan</th>
                    <th width="10%" class="text-center">Nama Pengadu</th>
                    <th width="17%" class="text-center">Alamat Kuarters</th>
                    <th width="8%" class="text-center">Tarikh Penyelenggaraan</th>
                    <th width="10%" class="text-center">Butiran Penyelenggaraan</th>
                    <th width="15%" class="text-center">Gambar</th>
                    <th width="10%" class="text-center">Pegawai Pemantau</th>
                    <th width="10%" class="text-center">Status Penyelenggaraan</th>
                </tr>
            </thead>

            <tbody >

                @if (!$maintenanceAll->isEmpty())
                    @foreach ($maintenanceAll as $bil => $maint)
                        <tr class="grid_content border">
                            <td class="text-center" scope="row">{{ $loop->iteration }}</td>
                            <td class="text-center">{{ $maint->complaint->ref_no }}</td>
                            <td class="text-center">{{ convertDateSys($maint->complaint->complaint_date) }}</td>
                            <td>{{ $maint->complaint->user->name }}</td>
                            <td>{{ $maint->complaint->quarters->address_1 . ' ' . $maint->complaint->quarters->address_2 }}</td>
                            <td class="text-center">{{ convertDateSys($maint->maintenance_date) }}</td>
                            <td>{{ upperText($maint->remarks) }}</td>
                            <td class="text-center">
                                @if(file_exists(pathAttachment().$maint->attachment?->path_document))
                                    <img src="{{ pathAttachment().$maint->attachment?->path_document }}" width="100%" @if(!$loop->first) style="padding-top:5px;" @endif>
                                @endif
                            </td>
                            <td >{{ $maint->officer->user->name }}</td>
                            <td class="text-center">{{ $maint->status?->status }}</td>
                        </tr>
                    @endforeach
                @else
                    <tr class="border">
                        <td class="text-center" colspan="10">Tiada Rekod</td>
                    </tr>
                @endif
            </tbody>
        </table>

    </body>

</html>
