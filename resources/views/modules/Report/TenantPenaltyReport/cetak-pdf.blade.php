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
                <tr class="header center">
                    <td colspan="6"></td>
                </tr>

                <tr class="title_header center title_header_padding">
                    <td colspan="8">LAPORAN DENDA KEROSAKAN KUARTERS KERAJAAN<br/>{{ $selectedQuartersCatPdf->name }}</td>
                </tr>

                <tr class="title_header  center title_padding bold">
                    <td colspan="8">TARIKH DARI {{ convertDateSys($selectedDateFrom) }} HINGGA {{ convertDateSys($selectedDateTo) }} </td>
                </tr>
            </table>
        </header>

        <table width="100%" cellspacing="0" cellpadding="3" >
            <thead class="thead-dark">
                <tr class="center header_border bold">
                    <th >Bil.</th>
                    <th width="10%">No. Rujukan Denda</th>
                    <th width="15%">Nama</th>
                    <th width="12%">Jawatan</th>
                    <th width="12%">Jabatan</th>
                    <th width="15%">Alamat</th>
                    <th width="15%">Keterangan Denda</th>
                    <th width="9%">Tarikh Dikenakan Denda</th>
                    <th width="8%">Amaun Denda</th>
                </tr>
            </thead>

            <tbody>
                @if ($tenantPenalties->count() > 0)
                    @foreach($tenantPenalties as $bil => $tp)
                        <tr class="grid_content border">
                            <td class="center" scope="row">{{ $loop->iteration }}</td>
                            <td class="center">{{ $tp->penalty_ref_no }}</td>
                            <td>{{ $tp->tenants->name }}</td>
                            <td >
                                {{ $tp->tenants->user->position->position_name }} {{ $tp->tenants->user->position_grade_code?->grade_type }} {{ $tp->tenants->user->position_grade->grade_no }}
                            </td>
                            <td>{{ $tp->tenants->user->office->organization->name }}</td>
                            <td>{{ $tp->tenants->quarters->address_1 . $tp->tenants->quarters->address_2}}</td>
                            <td>{{ $tp->remarks }}</td>
                            <td class="center">{{ convertDateSys($tp->penalty_date) }}</td>
                            <td class="center">{{ $tp->penalty_amount }}</td>
                        </tr>
                    @endforeach
                @else
                    <tr class="grid_content border">
                        <td class="text-center" colspan="9">Tiada Rekod</td>
                    </tr>
                @endif
            </tbody>
        </table>

    </body>

</html>
