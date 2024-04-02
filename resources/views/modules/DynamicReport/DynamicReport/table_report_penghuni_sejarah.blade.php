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
    @if (!$ic && $selectedYear)
        <p class="col-sm-12 fw-bold" id="tahun-pdf">Tahun : <span>{{ $selectedYear }}</span></p>
    @endif
</div>
<table id="my-table" class="table table-bordered text-nowrap" role="grid" cellspacing="0" cellpadding="4" width="100%">
    <thead class="bg-primary bg-gradient text-white">
        <tr role="row"  class="info_content_border">
            <th class="text-center">Bil.</th>
            <th class="text-center">Tahun Permohonan</th>
            <th class="text-center">Nama / <br> Kad Pengenalan</th>
            <th class="text-center">Perkhidmatan / <br>Taraf Jawatan</th>
            <th class="text-center">Jabatan /Jawatan</th>
            <th class="text-center">Jenis Kuarters</th>
            <th class="text-center">Alamat Kuarters</th>
        </tr>
    </thead>
    <tbody>
        @if (count($reportData))
            @foreach ($reportData as $tenant)
                <tr class="info_content_border uppercase">
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td class="text-center">{{ date('Y', strtotime($tenant->quarters_acceptance_date)) }}</td>
                    <td class="text-center">{{ $tenant->name }} <br> {{ $tenant->new_ic }}</td>
                    <td class="text-center">{{ $tenant->services_type }} <br> {{ $tenant->position_type }}</td>
                    <td class="text-center">{{ $tenant->position }} {{ $tenant->position_grade_type }}
                        {{ $tenant->position_grade }} <br> {{ $tenant->organization_name }}</td>
                    <td class="text-center">
                        {{ $tenant->quarters_category->landed_type->type }}
                    </td>
                    <td>
                        {{ $tenant->quarters->unit_no }} {{ $tenant->quarters->address_1 }} {{ $tenant->quarters->address_2 }} {{ $tenant->quarters->address_3 }}
                    </td>
                </tr>
            @endforeach
        @else
            <tr class="text-center info_content_border">
                <td colspan="7">Tiada Rekod</td>
            </tr>
        @endif
    </tbody>
</table>
