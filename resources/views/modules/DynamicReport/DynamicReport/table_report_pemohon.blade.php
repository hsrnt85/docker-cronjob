<table id="my-table" class="table table-bordered table-striped text-nowrap" role="grid"  cellspacing="0" cellpadding="4" width="100%">
    <thead class="bg-primary bg-gradient text-white">
        <tr role="row" class="info_content_border">
            <th class="text-center">Bil.</th>
            <th class="text-center">Tarikh Draf Permohonan</th>
            <th class="text-center">Tarikh Permohonan</th>
            <th class="text-center">Nama / <br> Kad Pengenalan</th>
            <th class="text-center">Perkhidmatan / <br>Taraf Jawatan</th>
            <th class="text-center">Jabatan /Jawatan</th>
            <th class="text-center">Status Perkahwinan / <br>Pendapatan</th>
            <th class="text-center">Pekerjaan Pasangan / <br>Pendapatan</th>
            <th class="text-center">Alamat Kediaman Sekarang / <br> Jarak ke Pejabat</th>
            <th class="text-center">Catatan</th>
            <th class="text-center">Pilihan Kuarters</th>
        </tr>
    </thead>
    <tbody>
        @if (count($reportData))
            @foreach ($reportData as $application)
                <tr class="info_content_border uppercase">
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td class="text-center">{{ convertDateSys($application->application_draft_date_time) }}</td>
                    <td class="text-center">
                        @if ($application->is_draft==0) {{ convertDateSys($application->application_date_time) }} @endif
                    </td>
                    <td class="text-center">{{ $application->user->name }} <br> {{ $application->user->new_ic }}</td>
                    <td class="text-center">
                        @if ($application->user_info)
                            {{ $application->user_info?->services_type?->services_type }} <br> {{ $application->user_info?->position_type?->position_type }}
                        @else
                            {{ $application->user->services_type->services_type }} <br> {{ $application->user->position_type->position_type }}
                        @endif
                    </td>
                    <td >
                        @if ($application->user_info)
                            {{ $application->user_info?->position?->position_name }} {{ $application->user_info?->position_grade_code?->grade_type }}
                            {{ $application->user_info?->position_grade?->grade_no }} <br> {{ $application->user->office?->organization?->name }}
                        @else
                            {{ $application->user?->position?->position_name }} {{ $application->user?->position_grade_code?->grade_type }}
                            {{ $application->user?->position_grade?->grade_no }} <br> {{ $application->user->office?->organization?->name }}
                        @endif
                    </td>
                    <td class="text-center">
                        @if ($application->user_info)
                            {{ $application->user_info?->marital_status?->marital_status }} <br> RM{{ $application->applicant_salary->basic_salary }}
                        @else
                            {{ $application->user->marital_status?->marital_status }} <br> RM{{ $application->applicant_salary->basic_salary }}
                        @endif

                    </td>
                    <td class="text-center">
                        @if ($application->user->spouse)
                            {{ $application->user->spouse?->spouse_name }}
                            <br> {{ $application->user->spouse?->salary ? 'RM' . $application->user->spouse->salary : 'Tidak Bekerja' }}
                        @else
                            -
                        @endif
                    </td>
                    <td>{{ $application->user->current_address()?->address_1 }} {{ $application->user->current_address()?->address_2 }}
                        {{ $application->user->current_address()?->address_3 }} <br> {{ $application->distance_from_office }}</td>
                    <td class="pr-3">
                        <ul style="margin: 0; padding: 0; margin-left:5">
                            @if ($application->review)
                                <li class="">{{ removeWhiteSpace($application->review) }}</li> <br>
                            @endif
                            @if ($application->user->children->count() > 0)
                                <li class="">Anak: {{ $application->user->children->count() }} orang</li>
                            @endif
                        </ul>
                    </td>
                    <td>
                        <ul  style="margin: 0; padding: 0; margin-left:5">
                            @foreach ($application->application_quarters_categories as $aqc)
                                <li>{{ $aqc->quarters_category->name }}</li> <br>
                            @endforeach
                        </ul>
                    </td>
                </tr>
            @endforeach
        @else
            <tr class="text-center" class="info_content_border ">
                <td colspan="10" class="text-center">Tiada Rekod</td>
            </tr>
        @endif
    </tbody>
</table>
