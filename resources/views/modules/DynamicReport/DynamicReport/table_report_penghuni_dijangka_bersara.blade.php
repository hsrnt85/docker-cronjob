<div class="row">
    @if ($selectedDistrict)
        <p class="col-sm-12 fw-bold" id="daerah-pdf">Daerah : <span>{{ ucwords(strtolower($selectedDistrict?->district_name)) }}</span></p>
    @endif
    @if ($from && $to)
        <p class="col-sm-12 fw-bold" id="tarikh-pdf">Tarikh : <span>{{ convertDateSys($from) }} - {{ convertDateSys($to) }} </span></p>
    @endif
</div>
<table id="my-table" class="table table-bordered text-nowrap" role="grid" cellspacing="0" cellpadding="4" width="100%">
    <thead class="bg-primary bg-gradient text-white">
        <tr role="row" class="info_content_border">
            <th class="text-center">Bil</th>
            <th class="text-center">Tarikh Dijangka Bersara</th>
            <th class="text-center">Nama / <br> Kad Pengenalan</th>
            <th class="text-center">Perkhidmatan / <br>Taraf Jawatan</th>
            <th class="text-center">Jabatan /Jawatan</th>
        </tr>
    </thead>
    <tbody>
        @if (count($reportData))
            @foreach ($reportData as $tenant)
                <tr class="info_content_border">
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td class="text-center">{{ convertDateSys($tenant->user->expected_date_of_retirement) }}</td>
                    <td class="text-center">{{ $tenant->name }} <br> {{ $tenant->new_ic }}</td>
                    <td class="text-center">{{ $tenant->services_type }} <br> {{ $tenant->position_type }}</td>
                    <td class="text-center">{{ $tenant->position }} {{ $tenant->position_grade_type }}
                        {{ $tenant->position_grade }} <br> {{ $tenant->organization_name }} </td>
                </tr>
            @endforeach
        @else
                <tr class="info_content_border">
                    <td class="text-center" colspan="5"> Tiada Rekod</td>
                </tr>
        @endif
    </tbody>
</table>
