<div id="datatable_wrapper">
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
    <div class="row">
        <div class="col-sm-12">
            <table id="my-table" class="table table-bordered" role="grid" cellspacing="0" cellpadding ="4" width="100%">
                <thead class="bg-primary bg-gradient text-white">
                    <tr role="row" class="info_content_border">
                        <th class="text-center" width="4%">Bil.</th>
                        <th class="text-center" width="6%">No. Unit </th>
                        <th class="text-center" width="20%">Alamat Kuarters</th>
                        <th class="text-center">Status Kuarters</th>
                        <th class="text-center">Kekosongan</th>
                        <th class="text-center">Yuran Penyelenggaraan (RM)</th>
                        <th class="text-center">Yuran IWK (RM)</th>
                        <th class="text-center">Cukai Tanah (RM)</th>
                        <th class="text-center">Cukai Harta (RM)</th>
                    </tr>
                </thead>
                <tbody>
                    @if (count($reportData))
                        @foreach ($reportData as $quarters)
                            <tr class="info_content_border">
                                <td class="text-center" width="4%">{{ $loop->iteration }}</td>
                                <td class="text-center" width="6%">{{ $quarters->unit_no }}</td>
                                <td width="20%">{{ $quarters->address_1 }} {{ $quarters->address_2 }} {{ $quarters->address_3 }}</td>
                                <td class="text-center">{{ $quarters->quarters_condition?->name }}</td>
                                <td class="text-center">
                                    @if ($quarters->current_active_tenant && $quarters->quarters_condition?->id == 1)
                                        Berpenghuni
                                    @elseif($quarters->quarters_condition?->id == 1 && $quarters->unit_no && $quarters->unit_no != "-")
                                        Boleh Diduduki
                                    @else
                                        Tidak Boleh Diduduki
                                    @endif
                                </td>
                                <td class="text-center">{{ $quarters->maintenance_fee }}</td>
                                <td class="text-center">{{ $quarters->iwk_fee }}</td>
                                <td class="text-center">{{ $quarters->land_tax }}</td>
                                <td class="text-center">{{ $quarters->property_tax }}</td>
                            </tr>
                        @endforeach
                    @else
                        <tr class="text-center info_content_border">
                            <td colspan="9">Tiada Rekod</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>
