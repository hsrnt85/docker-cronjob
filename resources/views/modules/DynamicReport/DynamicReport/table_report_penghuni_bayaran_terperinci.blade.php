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
    @if (!$ic && $selectedMonth)
        <p class="col-sm-12 fw-bold" id="tahun-pdf">Bulan : <span>{{ getMonthName($selectedMonth) }}</span></p>
    @endif
</div>
<table id="my-table" class="table table-bordered text-nowrap" role="grid" cellspacing="0" cellpadding="4" width="100%">
    <thead class="bg-primary bg-gradient text-white">
        <tr role="row" class="center header_border bold info_content_border">
            <th width="3%" class="text-center"  rowspan="2">Bil.</th>
            <th width="10%" class="text-center"  rowspan="2">Nama Penghuni / <br> No. Kad Pengenalan</th>
            <th width="3%" class="text-center"  rowspan="2">Agensi</th>
            <th width="15%" class="text-center"  rowspan="2">Alamat </th>
            <th width="3%" class="text-center"  rowspan="2">No. Notis </th>
            <th width="6%" class="text-center" colspan="2">Sewa (RM)</th>
            <th width="6%" class="text-center" colspan="2">Denda (RM)</th>
            <th width="6%" class="text-center" colspan="2">Yuran Penyelenggaraan (RM)</th>
            <th width="5%" class="text-center"  rowspan="2">Pelarasan (RM)</th>
            <th width="5%" class="text-center"  rowspan="2">Jumlah Notis<br>(RM)</th>
            <th width="5%" class="text-center"  rowspan="2">Jumlah Bayaran<br>(RM)</th>
            <th width="5%" class="text-center"  rowspan="2">Baki Bayaran<br>(RM)<br></th>
        </tr>
        <tr class="info_content_border bold">
            <th class="text-center" >Tunggakan</th>
            <th class="text-center" >Semasa</th>
            <th class="text-center" >Tunggakan</th>
            <th class="text-center" >Semasa</th>
            <th class="text-center" >Tunggakan</th>
            <th class="text-center" >Semasa</th>
        </tr>
    </thead>
    <tbody>
        @if (count($reportData))
            @php
                $sum_rental_amount = 0;
                $sum_outstanding_rental_amount = 0;
                $sum_penalty_amount = 0;
                $sum_outstanding_penalty_amount = 0;
                $sum_maintenance_fee_amount = 0;
                $sum_outstanding_maintenance_fee_amount = 0;
                $sum_total_rental = 0;
                $sum_total_penalty = 0;
                $sum_total_maintenance_fee = 0;
                $sum_adjustment_amount = 0;
                $sum_total_amount_after_adjustment = 0;
                $sum_total_payment_amount = 0;
                $sum_total_payment_balance = 0;
            @endphp
            @foreach ($reportData as $data)
                @php
                    $rental_amount = $data->rental_amount;
                    $outstanding_rental_amount = $data->outstanding_rental_amount;
                    $total_rental = $data->total_rental;
                    $penalty_amount = $data->penalty_amount;
                    $outstanding_penalty_amount = $data->outstanding_penalty_amount;
                    $total_penalty = $data->total_penalty;
                    $maintenance_fee_amount = $data->maintenance_fee_amount;
                    $outstanding_maintenance_fee_amount = $data->outstanding_maintenance_fee_amount;
                    $total_maintenance_fee = $data->total_maintenance_fee;
                    $adjustment_amount = $data->adjustment_amount;
                    $total_amount_after_adjustment = $data->total_amount_after_adjustment;
                    $total_payment_amount = $data->total_payment_amount;
                    $total_payment_balance = $data->total_payment_balance;

                @endphp
                <tr class="border info_content_border">
                    <td class="text-center" tabindex="0">{{ $loop->iteration }}</td>
                    <td >{{ $data->name }} <br/> {{ $data->ic }} </td>
                    <td >{{ $data->organization_name ?? "" }}</td>
                    <td >{{ $data->quarters_address }}</td>
                    <td >{{ $data->payment_notice_no }}</td>
                    <td class="text-center">{{ numberFormatComma($outstanding_rental_amount)}}</td>
                    <td class="text-center">{{ numberFormatComma($rental_amount)}}</td>
                    <td class="text-center">{{ numberFormatComma($outstanding_penalty_amount)}}</td>
                    <td class="text-center">{{ numberFormatComma($penalty_amount)}}</td>
                    <td class="text-center">{{ numberFormatComma($outstanding_maintenance_fee_amount)}}</td>
                    <td class="text-center">{{ numberFormatComma($maintenance_fee_amount)}}</td>
                    <td class="text-center">{{ numberFormatComma($adjustment_amount)}}</td>
                    <td class="text-center">{{ numberFormatComma($total_amount_after_adjustment)}}</td>
                    <td class="text-center">{{ $data->total_payment_amount }} </td>
                    <td class="text-center"><b>{{ numberFormatComma($total_payment_balance) }}</b></td>
                </tr>
                @php
                    $sum_rental_amount += $rental_amount;
                    $sum_outstanding_rental_amount += $outstanding_rental_amount;
                    $sum_penalty_amount += $penalty_amount;
                    $sum_outstanding_penalty_amount += $outstanding_penalty_amount;
                    $sum_maintenance_fee_amount += $maintenance_fee_amount;
                    $sum_outstanding_maintenance_fee_amount += $outstanding_maintenance_fee_amount;
                    $sum_total_rental += $total_rental; //
                    $sum_total_penalty += $total_penalty; //
                    $sum_total_maintenance_fee += $total_maintenance_fee; //
                    $sum_adjustment_amount += $adjustment_amount; //
                    $sum_total_amount_after_adjustment += $total_amount_after_adjustment; //
                    $sum_total_payment_amount += $total_payment_amount;
                    $sum_total_payment_balance += $total_payment_balance;
                @endphp
            @endforeach
            <tr class="border bold info_content_border">
                <td class="text-center" colspan="5"><b>Jumlah (RM)</b></td>
                <td class="text-center"><b>{{ numberFormatComma($sum_outstanding_rental_amount)}}</b></td>
                <td class="text-center"><b>{{ numberFormatComma($sum_total_rental)}}</b></td>
                <td class="text-center"><b>{{ numberFormatComma($sum_outstanding_penalty_amount)}}</b></td>
                <td class="text-center"><b>{{ numberFormatComma($sum_total_penalty)}}</td>
                <td class="text-center"><b>{{ numberFormatComma($sum_outstanding_maintenance_fee_amount)}}</b></td>
                <td class="text-center"><b>{{ numberFormatComma($sum_total_maintenance_fee)}}</b></td>
                <td class="text-center"><b>{{ numberFormatComma($sum_adjustment_amount)}}</b></td>
                <td class="text-center"><b>{{ numberFormatComma($sum_total_amount_after_adjustment)}}</b></td>
                <td class="text-center"><b>{{ numberFormatComma($sum_total_payment_amount)}}</b></td> <!-- Jumlah Bayaran -->
                <td class="text-center"><b>{{ numberFormatComma($sum_total_payment_balance)}}</b></td> <!-- Jumlah Baki Bayaran -->
            </tr>
        @else
            <tr><td class="text-center" colspan="12">Tiada Rekod</td></tr>
        @endif
    </tbody>

</table>
