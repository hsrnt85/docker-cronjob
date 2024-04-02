<html>
    <head>
        <link href="{{ getPathDocumentCss() .'report.css' }}" type="text/css" />
    </head>
    <body>

        <!-- Define header and footer blocks before your content -->
        @include('report.header')
        @include('report.footer')
        <!-- Define header and footer blocks before your content -->
        <header >
            <table width="100%" cellspacing="0">
                <tr class="header center">
                    <td colspan="4"></td>
                </tr>

                <tr class="title_header center title_header_padding">
                    <td colspan="11">LAPORAN DENDA HILANG KELAYAKAN<br />{{ $selectedQuartersCatPdf?->name }}</td>
                </tr>

                <tr class="title_header  center title_padding bold">
                    <td colspan="11">TARIKH DENDA DARI {{ convertDateSys($selectedDateFrom) }} HINGGA {{ convertDateSys($selectedDateTo) }} </td>
                </tr>
            </table>
        </header>
        <table width="100%" cellpadding="5" cellspacing="0">
            <thead class="thead-dark">
                <tr class="grid_header center header_border">
                    <th class="text-center">Bil.</th>
                    <th width="" class="text-center">No. Rujukan Denda</th>
                    <th width="" class="text-center">Nama Penghuni</th>
                    <th width="" class="text-center">Kad Pengenalan</th>
                    <th width="" class="text-center">Alamat Kuarters</th>
                    <th width="" class="text-center">Jawatan</th>
                    <th width="" class="text-center">Jabatan</th>
                    <th width="" class="text-center">Tarikh Hilang Kelayakan</th>
                    <th width="" class="text-center">Tarikh Kuatkuasa</th>
                    <th width="" class="text-center">Sebab Hilang Kelayakan</th>
                    <th width="" class="text-center">Jumlah Denda (RM)</th>
                </tr>
            </thead>

            <tbody>

                @if ($blacklistPenaltyAll->count()  > 0)
                    @foreach ($blacklistPenaltyAll as $bil => $bp)
                        <tr class="grid_content border">
                            <td class="text-center" scope="row">{{ $loop->iteration }}</td>
                            <td class="text-center">{{ $bp->penalty_ref_no }}</td>
                            <td>{{ $bp->tenant->user->name }}</td>
                            <td class="text-center">{{ $bp->tenant->user->new_ic }}</td>
                            <td>{{ $bp->tenant->quarters->address_1 . ' ' . $bp->tenant->quarters->address_2 }}</td>
                            <td>{{ upperText($bp->tenant->user->position->position_name) }}</td>
                            <td>{{ $bp->tenant->user->office->organization->name }}</td>
                            <td class="text-center">{{ convertDateSys($bp->penalty_date) }}</td>
                            <td class="text-center">{{ convertDateSys($bp->execution_date) }}</td>
                            <td>{{ $bp->tenant?->reason?->blacklist_reason ?? $bp->tenant?->blacklist_reason_others }}</td>
                            <td class="text-center">{{ $bp->penalty_amount }}</td>
                        </tr>
                    @endforeach
                @else
                    <tr class="border grid_content">
                        <td class="text-center" colspan="11">Tiada Rekod</td>
                    </tr>
                @endif
            </tbody>
        </table>

    </body>

</html>
