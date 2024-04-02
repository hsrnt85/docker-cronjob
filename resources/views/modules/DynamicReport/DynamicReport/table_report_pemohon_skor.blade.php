<div class="row">
    @if (!$ic && $selectedDistrict)
        <p class="col-sm-12 fw-bold" id="daerah-pdf">Daerah : <span>{{ ucwords(strtolower($selectedDistrict?->district_name)) }}</span></p>
    @endif

    @if (!$ic && $selectedQuartersCategory)
        <p class="col-sm-12 fw-bold" id="kategori-pdf">Kategori Kuarters (Lokasi) : <span>{{ ucwords(strtolower($selectedQuartersCategory?->name)) }}</span></p>
    @endif

    @if (!$ic && $selectedServicesType)
        <p class="col-sm-12 fw-bold" id="taraf-pdf">Taraf Perkhidmatan : <span>{{ ucwords($selectedServicesType?->services_type) }}</span></p>
    @endif
</div>
<table id="my-table" class="table table-bordered text-nowrap" role="grid" cellspacing="0" cellpadding="4" width="100%">
    <thead class="bg-primary bg-gradient text-white ">
        <tr role="row" class="info_content_border">
            <th class="text-center">Bil</th>
            <th class="text-center">Nama / <br> Kad Pengenalan</th>
            <th class="text-center">Perkhidmatan / <br>Taraf Jawatan</th>
            <th class="text-center">Jabatan /Jawatan</th>
            <th class="text-center">Markah</th>
        </tr>
    </thead>
    <tbody>
        @if (count($reportData))
            @foreach ($reportData as $score)
                <tr class="info_content_border">
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td class="text-center">{{ $score->application->user->name }} <br> {{ $score->application->user->new_ic }}</td>
                    <td class="text-center">
                        @if($score->application->user_info)
                            {{ $score->application->user_info->services_type->services_type }} <br> {{ $score->application->user_info->position_type->position_type }}
                        @else
                            {{ $score->application->user->services_type->services_type }} <br> {{ $score->application->user->position_type->position_type }}
                        @endif
                    </td>
                    <td class="text-center">
                        @if ($score->application->user_info)
                            {{ $score->application->user_info->position->position_name }} {{ $score->application->user_info->position_grade_code?->grade_type }}
                            {{ $score->application->user_info->position_grade->grade_no }} <br> {{ $score->application->user->office->organization->name }}
                        @else
                            {{ $score->application->user->position->position_name }} {{ $score->application->user->position_grade_code?->grade_type }}
                            {{ $score->application->user->position_grade->grade_no }} <br> {{ $score->application->user->office->organization->name }}
                        @endif
                    </td>
                    <td class="text-center">{{ $score->total_marks }}</td>
                </tr>
            @endforeach
        @else
            <tr class="text-center info_content_border">
                <td colspan="9" class="info_content_border">Tiada Rekod</td>
            </tr>
        @endif
    </tbody>
</table>
