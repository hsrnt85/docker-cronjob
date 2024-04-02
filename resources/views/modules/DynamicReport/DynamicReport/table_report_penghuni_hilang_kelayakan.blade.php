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
    @if (!$ic && $from && $to)
        <p class="col-sm-12 fw-bold" id="tahun-pdf">Tarikh : <span>{{ convertDateSys($from) }} - {{ convertDateSys($to) }} </span></p>
    @endif
</div>
<table id="my-table" class="table table-bordered text-nowrap" role="grid" cellspacing="0" cellpadding="4" width="100%">
    <thead class="bg-primary bg-gradient text-white">
        <tr role="row" class="info_content_border">
            <th class="text-center">Bil.</th>
            <th class="text-center">No. Rujukan Denda</th>
            <th class="text-center">Nama / <br> Kad Pengenalan</th>
            <th class="text-center">Jabatan /Jawatan</th>
            <th class="text-center">Alamat Kuarters</th>
            <th class="text-center">Tarikh Hilang Kelayakan</th>
            <th class="text-center">Sebab Hilang Kelayakan</th>
            <th class="text-center">Keputusan Mesyuarat</th>
        </tr>
    </thead>
    <tbody>
        @if (count($reportData))
            @foreach ($reportData as $tenant)
            @php $bp = App\Models\BlacklistPenalty::where('tenants_id', $tenant->id)->where('data_status', 1)->first(); @endphp
                <tr class="info_content_border">
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td class="text-center">{{ $bp->penalty_ref_no ?? ''}}</td>
                    <td class="text-center">{{ $tenant->name }} <br> {{ $tenant->new_ic }}</td>
                    <td class="uppercase">{{ $tenant->position }} {{ $tenant->position_grade_type }}
                        {{ $tenant->position_grade }} <br> {{ $tenant->organization_name }}</td>
                    <td> {{ $tenant->quarters->unit_no }} {{ $tenant->quarters->address_1 }} {{ $tenant->quarters->address_2 }} {{ $tenant->quarters->address_3 }}</td>
                    <td class="text-center">{{ convertDateSys($tenant->blacklist_date) }}</td>
                    <td class="text-center">{{ $tenant->reason?->blacklist_reason }}</td>
                    <td class="text-center">{{ $bp->meeting_remarks ?? '' }}</td>
                </tr>
            @endforeach
        @else
            <tr class="text-center info_content_border">
                <td colspan="8">Tiada Rekod</td>
            </tr>
        @endif
    </tbody>
</table>
