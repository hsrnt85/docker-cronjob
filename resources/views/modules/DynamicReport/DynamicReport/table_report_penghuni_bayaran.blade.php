<div class="row">
    @if (!$ic && $selectedDistrict)
        <p class="col-sm-12 fw-bold" id="daerah-pdf">Daerah : <span>{{ ucwords(strtolower($selectedDistrict?->district_name)) }}</span></p>
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
        <tr role="row" class="info_content_border">
            <th class="text-center">Bil.</th>
            <th class="text-center">Nama / <br> Kad Pengenalan</th>
            {{-- <th class="text-center">Perkhidmatan / <br>Taraf Jawatan</th>
            <th class="text-center">Jabatan /Jawatan</th> --}}
            <th class="text-center">Alamat Kuarters</th>
            <th class="text-center">Tarikh Masuk</th>
            <th class="text-center">Tarikh Keluar</th>
            <th class="text-center">Kadar Bayaran<br> (Sebulan)</th>
            @foreach ($months as $month)
                <th class="text-center">{{ $month->bm }}</th>
            @endforeach
            <th class="text-center">Jumlah Tahunan</th>
        </tr>
    </thead>
    <tbody>
        @if (count($reportData))
            @php
                $totalPaymentArr = [];
                $payment = 0;
            @endphp
            @foreach ($reportData as $i => $tenantPN)
                <tr class="info_content_border">
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td class="text-center">{{ $tenantPN->name }} <br> {{ $tenantPN->ic }}</td>
                    {{-- <td class="text-center">{{ $tenantPN->services_type }} <br> {{ $tenantPN->position_type }}</td>
                    <td class="text-center">{{ $tenantPN->position_name }} {{ $tenantPN->grade_type }}
                        {{ $tenantPN->grade_no }} <br> {{ $tenantPN->organization_name }}</td> --}}
                    <td class="text-center">{{ $tenantPN->quarters_address }}</td>
                    <td class="text-center">{{ $tenantPN->acceptance_date }}</td>
                    <td class="text-center">{{ $tenantPN->leave_date }}</td>
                    <td class="text-center">{{ $tenantPN->amount }}</td>
                    @foreach ($months as $month)
                        @php
                            $total_payment = 0;
                            $payment = $tenantPN->payments[$month->month] ?? '0.00';
                            $totalPaymentArr[$i][$month->month] = $payment;
                        @endphp
                        <td class="text-center">{{ $payment }}</td>
                    @endforeach
                    <td class="text-center">
                        {{ $tenantPN->total_payments }}
                    </td>

                </tr>
            @endforeach
        @else
            <tr><td class="text-center info_content_border" colspan="19">Tiada Rekod</td></tr>
        @endif
    </tbody>

    @if (count($reportData))
        <tr class="bg-light fw-bolder info_content_border">
            <td class="text-center" colspan="5" ><b>Jumlah (RM)</b></td>
            <td class="text-center">{{ numberFormatComma($reportData->sum('amount')) }}</td>
            @foreach ($months as $month)
                @php
                    $totalPaymentByMonth = array_sum(array_column($totalPaymentArr, $month->month)) ?? "0.00";
                @endphp
                <td class="text-center">{{ numberFormatComma($totalPaymentByMonth) }}</td>
            @endforeach
            <td class="text-center">{{ numberFormatComma($reportData->sum('total_payments')) }}</td>
        </tr>
    @endif
</table>
