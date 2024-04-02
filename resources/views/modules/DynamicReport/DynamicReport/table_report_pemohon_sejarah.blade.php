<div class="row">
    @if (!$ic && $selectedDistrict)
        <p class="col-sm-12 fw-bold" id="daerah-pdf">Daerah : <span>{{ ucwords(strtolower($selectedDistrict?->district_name)) }}</span></p>
    @endif
    @if (!$ic && $selectedYear)
        <p class="col-sm-12 fw-bold" id="tahun-pdf">Tahun : <span>{{ $selectedYear }}</span></p>
    @endif
</div>
<table id="my-table" class="table table-bordered text-nowrap" role="grid" cellspacing="0" cellpadding="4" width="100%">
    <thead class="bg-primary bg-gradient text-white">
        <tr role="row" class="info_content_border">
            <th class="text-center">Bil.</th>
            <th class="text-center">Tahun Permohonan</th>
            <th class="text-center">Nama / <br> Kad Pengenalan</th>
            <th class="text-center">Perkhidmatan / <br>Taraf Jawatan</th>
            <th class="text-center">Jabatan /Jawatan</th>
            <th class="text-center">Jenis Kuarters</th>
            <th class="text-center">Pilihan Kuarters</th>
            <th class="text-center">Status Pemohon</th>
        </tr>
    </thead>
    <tbody>
        @if (count($reportData))
            @foreach ($reportData as $application)
                <tr class="info_content_border">
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td class="text-center">{{ date('Y', strtotime($application->application_date_time)) }}</td>
                    <td class="text-center">{{ $application->user->name }} <br> {{ $application->user->new_ic }}</td>
                    <td class="text-center">
                        @if ($application->user_info)
                            {{ $application->user_info->services_type->services_type }} <br> {{ $application->user_info->position_type->position_type }}
                        @else
                            {{ $application->user->services_type->services_type }} <br> {{ $application->user->position_type->position_type }}
                        @endif
                    </td>
                    <td class="text-center">
                        @if ($application->user_info)
                            {{ $application->user_info->position->position_name }} {{ $application->user_info->position_grade_code?->grade_type }}
                            {{ $application->user_info->position_grade->grade_no }} <br> {{ $application->user->office->organization->name }}
                        @else
                            {{ $application->user->position->position_name }} {{ $application->user->position_grade_code?->grade_type }}
                            {{ $application->user->position_grade->grade_no }} <br> {{ $application->user->office->organization->name }}
                        @endif
                    </td>
                    <td>
                        <ul class="ps-3">
                            @foreach ($application->application_quarters_categories as $aqc)
                                <li>{{ $aqc->quarters_category->landed_type->type }}</li> <br>
                            @endforeach
                        </ul>
                    </td>
                    <td>
                        <ul class="ps-3">
                            @foreach ($application->application_quarters_categories as $aqc)
                                <li>{{ $aqc->quarters_category->name }}</li> <br>
                            @endforeach
                        </ul>
                    </td>
                    <td>
                        @if(in_array($application->current_status->application_status_id, [7, 11, 12]))
                            Berjaya
                        @endif

                        @if(in_array($application->current_status->application_status_id, [4, 6, 8, 9]))
                            Tidak Berjaya
                        @endif
                    </td>
                </tr>
            @endforeach
        @else
            <tr class="info_content_border">
                <td colspan="8" class="text-center">Tiada Rekod</td>
            </tr>
        @endif
    </tbody>
</table>
