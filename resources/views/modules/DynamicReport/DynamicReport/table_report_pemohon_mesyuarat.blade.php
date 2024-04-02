<div class="row">
    @if (!$ic && $selectedDistrict)
        <p class="col-sm-12 fw-bold" id="daerah-pdf">Daerah : <span>{{ ucwords(strtolower($selectedDistrict?->district_name)) }}</span></p>
    @endif
    @if (!$ic && $selectedQuartersCategory)
        <p class="col-sm-12 fw-bold" id="kategori-pdf">Kategori Kuarters (Lokasi) : <span>{{ ucwords(strtolower($selectedQuartersCategory?->name)) }}</span></p>
    @endif
    @if ($from && $to)
        <p class="col-sm-12 fw-bold" id="tahun-pdf">Tarikh : <span>{{ convertDateSys($from) }} - {{ convertDateSys($to) }} </span></p>
    @endif
</div>
<table id="my-table" class="table table-bordered" role="grid" cellspacing="0" cellpadding="4" width="100%">
    <thead class="bg-primary bg-gradient text-white">
        <tr role="row" class="info_content_border">
            <th class="text-center">Bil.</th>
            <th class="text-center">Bil. Mesyuarat</th>
            <th class="text-center">Nama / <br> Kad Pengenalan</th>
            <th class="text-center">Perkhidmatan / <br>Taraf Jawatan</th>
            <th class="text-center">Jabatan /Jawatan</th>
            <th class="text-center">Kategori Kuarters (Lokasi)</th>
            <th class="text-center">Keputusan Mesyuarat</th>
        </tr>
    </thead>
    <tbody>
        @if (count($reportData))
            @foreach ($reportData as $meeting_app)
                <tr class="info_content_border">
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td class="text-center">{{ $meeting_app->meeting->bil_no }} </td>
                    <td class="text-center">{{ $meeting_app->application?->user?->name }} <br> {{ $meeting_app->application?->user?->new_ic }}</td>
                    <td class="text-center uppercase">
                        @if ($meeting_app->application?->user_info)
                            {{ $meeting_app->application?->user_info?->services_type?->services_type }} <br> {{ $meeting_app->application?->user_info?->position_type?->position_type }}
                        @else
                            {{ $meeting_app->application?->user?->services_type?->services_type }} <br> {{ $meeting_app->application?->user?->position_type?->position_type }}
                        @endif
                    </td>
                    <td class="text-center uppercase">
                        @if ($meeting_app->application?->user_info)
                            {{ $meeting_app->application?->user_info?->position?->position_name }} {{ $meeting_app->application?->user_info?->position_grade_code?->grade_type }}
                            {{ $meeting_app->application?->user_info?->position_grade?->grade_no }} <br> {{ $meeting_app->application?->user->office?->organization?->name }}
                        @else
                            {{ $meeting_app->application?->user?->position?->position_name }} {{ $meeting_app->application?->user?->position_grade_code?->grade_type }}
                            {{ $meeting_app->application?->user?->position_grade?->grade_no }} <br> {{ $meeting_app->application?->user->office?->organization?->name }}
                        @endif
                    </td>
                    <td class="text-center">{{ $meeting_app->quarters_category?->name }}</td>
                    <td class="text-center">
                        @if ($meeting_app->is_delay != 1)
                            {{ $meeting_app?->application_status?->status }}
                        @else
                            TANGGUH
                        @endif
                    </td>
                </tr>
            @endforeach
        @else
            <tr class="info_content_border">
                <td class="text-center" colspan="7">Tiada Rekod</td>
            </tr>
        @endif
    </tbody>
</table>
