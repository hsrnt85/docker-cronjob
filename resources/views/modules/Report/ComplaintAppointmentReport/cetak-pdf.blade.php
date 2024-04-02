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
                    <td colspan="10">LAPORAN TEMUJANJI ADUAN KUARTERS KERAJAAN<br />{{ $selectedQuartersCatPdf->name }}</td>
                </tr>
                <tr class="title_header center title_header_padding">
                    <td colspan="10">TARIKH ADUAN DARI {{ convertDateSys($selectedDateFrom) }} HINGGA {{ convertDateSys($selectedDateTo) }} </td>
                </tr>

            </table>
        </header>

        <table width="100%" cellspacing="0" cellpadding="3" >
            <thead class="thead-dark">
                <tr class="grid_header center header_border">
                    <th >Bil.</th>
                    <th width="">No. Rujukan Denda</th>
                    <th width="" >Nama</th>
                    <th width="" >Jawatan</th>
                    <th width="" >Jabatan</th>
                    <th width="" >Alamat</th>
                    <th width="" >Butiran Kerosakan</th>
                    <th width="" >Tarikh Aduan</th>
                    <th width="" >Tarikh Temujanji</th>
                    <th width="" >Status Temujanji</th>
                </tr>
            </thead>

            <tbody>

                @if ($appointments->count() > 0)
                    @foreach ($appointments as $bil => $appt)
                        <tr class="grid_content border">
                            <td class="text-center" scope="row">{{ $loop->iteration }}</td>
                            <td class="text-center">{{ $appt->complaint->ref_no }}</td>
                            <td >{{ $appt->complaint->user->name }}</td>
                            <td >
                                @php
                                    $latestUserInfo = $appt->complaint->user->latest_user_info_before($appt->complaint->complaint_date);
                                @endphp
                                
                                @if($latestUserInfo)
                                    {{ upperText($latestUserInfo->position->position_name) }} {{ upperText($latestUserInfo->position_grade_code?->grade_type) }}{{ $latestUserInfo->position_grade->grade_no }}
                                @else
                                    {{ upperText($appt->complaint->user->position->position_name) }} {{ upperText($appt->complaint->user->position_grade_code?->grade_type) }}{{ $appt->complaint->user->position_grade->grade_no }}
                                @endif
                                {{-- {{ upperText($appt->complaint->user->position->position_name) }}
                                {{upperText($appt->complaint->user->position_grade_code?->grade_type ) }}{{ $appt->complaint->user->position_grade->grade_no }} --}}
                            </td>
                            <td >{{ $appt->complaint->user->office->organization->name }}</td>
                            <td >{{ $appt->complaint->quarters->address_1 . $appt->complaint->quarters->address_2 }}</td>
                            <td >{{ $appt->complaint->remarks }}</td>
                            <td class="text-center">{{ convertDateSys($appt->complaint->complaint_date) }}</td>
                            <td class="text-center">{{ convertDateSys($appt->appointment_date) }}</td>
                            <td class="text-center">{{ $appt->status_appointment?->appointment_status }}</td>
                        </tr>
                    @endforeach
                @else
                    <tr class="grid_content border">
                        <td class="text-center" colspan="10">Tiada Rekod</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </body>

</html>
