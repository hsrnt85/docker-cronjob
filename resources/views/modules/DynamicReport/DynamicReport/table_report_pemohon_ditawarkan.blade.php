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
<table id="my-table" class="table table-bordered" role="grid" cellspacing="0" cellpadding="3" width="100%">
    <thead class="bg-primary bg-gradient text-white">
        <tr role="row" class="info_content_border">
            <th class="text-center">Bil.</th>
            <th class="text-center">Rujukan Fail</th>
            <th class="text-center">Nama</th>
            <th class="text-center">Kad Pengenalan</th>
            <th class="text-center">Perkhidmatan / <br>Taraf Jawatan</th>
            <th class="text-center">Jabatan /Jawatan</th>
            <th class="text-center">Alamat Kuarters Yang Ditawarkan</th>
            <th class="text-center">Keputusan Mesyuarat</th>
        </tr>
    </thead>
    <tbody>
        @if (count($reportData))
            @foreach ($reportData as $data)
            @if(in_array($data->current_status?->application_status_id, [11,12]))
                @php
                    $quarters_address = ($data->selected_quarters()) ? $data->selected_quarters()?->unit_no.' '.$data->selected_quarters()?->address_1.' '.$data->selected_quarters()?->address_2.' '.$data->selected_quarters()?->address_3: "";
                @endphp
                <tr class="info_content_border pr-1">
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td class="text-center">{{ $data->quarters_offer_letter?->letter_ref_no }} </td>
                    <td class="text-center">{{ $data->user?->name }}</td>
                    <td class="text-center">{{ $data->user?->new_ic }}</td>
                    <td class="text-center">
                        @if ($data->user_info)
                            {{ $data->user_info?->services_type?->services_type }} <br> {{ $data->user_info?->position_type?->position_type }}
                        @else
                            {{ $data->user?->services_type?->services_type }} <br> {{ $data->user?->position_type?->position_type }}
                        @endif
                    </td>
                    <td class="text-center">
                        @if ($data->user_info)
                            {{ $data->user_info?->position?->position_name }} {{ $data->user_info?->position_grade_code?->grade_type }}
                            {{ $data->user_info?->position_grade?->grade_no }} <br> {{ $data->user->office?->organization?->name }}
                        @else
                            {{ $data->user?->position?->position_name }} {{ $data->user?->position_grade_code?->grade_type }}
                            {{ $data->user?->position_grade?->grade_no }} <br> {{ $data->user->office?->organization?->name }}
                        @endif
                        
                    </td>
                    <td class="text-center">{{ $quarters_address }}</td>
                    <td class="text-center">
                        @if(in_array($data->current_status?->application_status_id, [11]))
                            TERIMA TAWARAN
                        @endif
                        @if(in_array($data->current_status?->application_status_id, [12]))
                            TOLAK TAWARAN
                        @endif
                    </td>
                </tr>
                @endif
            @endforeach
        @else
            <tr>
                <td class="text-center info_content_border " colspan="8">Tiada Rekod</td>
            </tr>
        @endif
    </tbody>
</table>
