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
                    <td colspan="9"> LAPORAN PEMANTAUAN BERKALA (PENGURUSAN) KUARTERS KERAJAAN <br />{{ $selectedQuartersCatPdf->name }}</td>
                </tr>

                <tr class="title center title_padding bold">
                    <td colspan="9">TARIKH PEMANTAUAN DARI {{ convertDateSys($selectedDateFrom) }} HINGGA {{ convertDateSys($selectedDateTo) }} </td>
                </tr>
            </table>
        </header>

        <table width="100%" cellpadding="5" cellspacing="0">
            <thead class="thead-dark">
                <tr class="bold center header_border">
                    <th class="text-center">Bil.</th>
                    <th width="" class="text-center">No. Rujukan</th>
                    <th width="" class="text-center">Tarikh Pemantauan</th>
                    {{-- <th width="" class="text-center">Nama Penghuni</th> --}}
                    <th width="" class="text-center">Alamat Kuarters</th>
                    <th width="" class="text-center">Catatan/Perihal</th>
                    <th width="" class="text-center">Pegawai Pemantau</th>
                    <th width="" class="text-center">Tindakan/Status</th>
                    <th width="" class="text-center">Gambar Pemantauan</th>
                    <th width="" class="text-center">Status Pemantauan</th>
                </tr>
            </thead>

            <tbody>

                @if ($inspectionTransactionAll->count() == 0)
                    <tr class="info_content_border">
                        <td class="text-center" colspan="9">Tiada Rekod</td>
                    </tr>
                @else
                    @foreach ($inspectionTransactionAll as $bil => $rit)
                        <tr class="info_content_border">
                            <td class="text-center" scope="row">{{ $loop->iteration }}</td>
                            <td class="text-center">{{ $rit->routineInspection->ref_no }}</td>
                            <td class="text-center">{{ convertDateSys($rit->routineInspection->inspection_date) }}</td>
                            {{-- <td class="text-center">{{ $rit->routineInspection->quarters_category->district->district_name }}</td> --}}
                            <td >{{ $rit->routineInspection->address }}</td>
                            <td >{{ upperText($rit->routineInspection->remarks) }}</td>
                            <td >{{ $rit->routineInspection->monitoring_officer->user->name }}</td>
                            <td >{{ upperText($rit->remarks) }}</td>
                            <td class="text-center">
                                @if ($rit->attachments->count() != 0)
                                    @foreach ($rit->attachments as $attachment)
                                        @if(file_exists(pathAttachment().$attachment->path_document))
                                            <img src="{{ pathAttachment() . $attachment->path_document }}" width="100%" @if(!$loop->first) style="padding-top:5px;" @endif>
                                        @endif
                                    @endforeach
                                @endif
                            </td>

                            <td class="text-center">{{ $rit->inspectionStatus?->status }}</td>
                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>

    </body>

</html>
