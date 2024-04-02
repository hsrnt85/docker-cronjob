<!DOCTYPE html>
<html>

    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <link href="assets/css/document/finance-report.css" rel="stylesheet" type="text/css">
    </head>

    <body class="report_finance">

        <!-- Define header and footer blocks before your content -->
        @include('report.header')
        {{-- @include('report.footer') --}}
        <!-- Define header and footer blocks before your content -->
        <header >
            <table width="100%" cellspacing="0" cellpadding="3" class="bold">
                <tr class="center">
                    <td>KERAJAAN NEGERI JOHOR DARUL TAKZIM</td>
                </tr>
                <tr class="center title_padding">
                    <td>LAPORAN NOTIS BAYARAN AGENSI BAGI BULAN {{ upperText(getMonthName($month)) }} {{ $year }} </td>
                </tr>
            </table>

            <div class="pb-4"></div>

            <table width="100%" cellspacing="0">
                <tr class="title_header title_header_padding">
                    <td colspan="8">Daerah : {{ district()?->district_name }}</td>
                </tr>
                <tr class="title_header title_header_padding">
                    <td colspan="8">Agensi : {{ $organization->name }}</td>
                </tr>
            </table>
        </header>

        <table width="100%" cellspacing="0" cellpadding="5">
            <thead class="bg-primary bg-gradient text-white">
                <tr role="row" class="grid_header center header_border">
                    <th width="3%" class="text-center" >Bil</th>
                    <th width="20%" class="text-center" >Nama Penghuni</th>
                    <th width="7%" class="text-center" >No. Kad Pengenalan</th>
                    <th width="25%" class="text-center" >Alamat </th>
                    <th width="7%" class="text-center" >No. Notis </th>
                    <th width="6%" class="text-center" >Sewa (RM)</th>
                    <th width="6%" class="text-center" >Denda (RM)</th>
                    <th width="6%" class="text-center" >Yuran Penyelenggaraan (RM)</th>
                    <th width="6%" class="text-center" >Pelarasan (RM)</th>
                    <th width="6%" class="text-center" >Jumlah Notis(RM)</th>
                </tr>
                {{-- <tr role="row" class="grid_header center header_border">
                    <th class="text-center" >Semasa</th>
                    <th class="text-center" >Tunggakan</th>
                    <th class="text-center" >Jumlah</th>
                    <th class="text-center" >Semasa</th>
                    <th class="text-center" >Tunggakan</th>
                    <th class="text-center" >Jumlah</th>
                    <th class="text-center" >Semasa</th>
                    <th class="text-center" >Tunggakan</th>
                    <th class="text-center" >Jumlah</th>
                </tr> --}}
            </thead>
            <tbody>
                @php
                    $sum_rental_amount = 0;
                    $sum_outstanding_rental_amount = 0;
                    $sum_total_rental = 0;
                    $sum_penalty_amount = 0;
                    $sum_outstanding_penalty_amount = 0;
                    $sum_total_penalty = 0;
                    $sum_maintenance_fee_amount = 0;
                    $sum_outstanding_maintenance_fee_amount = 0;
                    $sum_total_maintenance_fee = 0;
                    $sum_adjustment_amount = 0;
                    $sum_total_amount_after_adjustment = 0;
                @endphp
                @foreach ($tenantPaymentNoticeAll as $data)
                    @php
                        $rental_amount = $data->rental_amount;
                        $outstanding_rental_amount = $data->outstanding_rental_amount;
                        $total_rental = $data->total_rental;
                        $penalty_amount = $data->damage_penalty_amount + $data->blacklist_penalty_amount;
                        $outstanding_penalty_amount = $data->outstanding_damage_penalty_amount + $data->outstanding_blacklist_penalty_amount;
                        $total_penalty = $data->total_damage_penalty + $data->total_blacklist_penalty;
                        $maintenance_fee_amount = $data->maintenance_fee_amount;
                        $outstanding_maintenance_fee_amount = $data->outstanding_maintenance_fee_amount;
                        $total_maintenance_fee = $data->total_maintenance_fee;
                        $adjustment_amount = $data->adjustment_amount;
                        $total_amount_after_adjustment = $data->total_amount_after_adjustment;
                    @endphp
                    <tr class="grid_content border">
                        <td class="text-center" tabindex="0">{{ $loop->iteration }}</td>
                        <td >{{ $data->name }} </td>
                        <td class="text-center">{{ $data->no_ic }} </td>
                        <td >{{ $data->quarters_address }}</td>
                        <td >{{ $data->payment_notice_no }}</td>
                        {{-- <td class="text-center">{{ numberFormatComma($rental_amount)}}</td>
                        <td class="text-center">{{ numberFormatComma($outstanding_rental_amount)}}</td> --}}
                        <td class="right">{{ numberFormatComma($total_rental)}}</td>
                        {{-- <td class="text-center">{{ numberFormatComma($penalty_amount)}}</td>
                        <td class="text-center">{{ numberFormatComma($outstanding_penalty_amount)}}</td> --}}
                        <td class="right">{{ numberFormatComma($total_penalty)}}</td>
                        {{-- <td class="text-center">{{ numberFormatComma($maintenance_fee_amount)}}</td>
                        <td class="text-center">{{ numberFormatComma($outstanding_maintenance_fee_amount)}}</td> --}}
                        <td class="right">{{ numberFormatComma($total_maintenance_fee)}}</td>
                        <td class="right">{{ numberFormatComma($adjustment_amount)}}</td>
                        <td class="right">{{ numberFormatComma($total_amount_after_adjustment)}}</td>
                    </tr>
                    @php
                        $sum_rental_amount += $rental_amount;
                        $sum_outstanding_rental_amount += $outstanding_rental_amount;
                        $sum_total_rental += $total_rental;
                        $sum_penalty_amount += $penalty_amount;
                        $sum_outstanding_penalty_amount += $outstanding_penalty_amount;
                        $sum_total_penalty += $total_penalty;
                        $sum_maintenance_fee_amount += $maintenance_fee_amount;
                        $sum_outstanding_maintenance_fee_amount += $outstanding_maintenance_fee_amount;
                        $sum_total_maintenance_fee += $total_maintenance_fee;
                        $sum_adjustment_amount += $adjustment_amount;
                        $sum_total_amount_after_adjustment += $total_amount_after_adjustment;
                    @endphp
                @endforeach
                <tr class="grid_content border bold">
                    <td class="text-center" colspan="5">JUMLAH KESELURUHAN (RM)</a></td>
                    {{-- <td class="text-center">{{ numberFormatComma($sum_rental_amount)}}</td>
                    <td class="text-center">{{ numberFormatComma($sum_outstanding_rental_amount)}}</td> --}}
                    <td class="right">{{ numberFormatComma($sum_total_rental)}}</td>
                    {{-- <td class="text-center">{{ numberFormatComma($sum_penalty_amount)}}</td>
                    <td class="text-center">{{ numberFormatComma($sum_outstanding_penalty_amount)}}</td> --}}
                    <td class="right">{{ numberFormatComma($sum_total_penalty)}}</td>
                    {{-- <td class="text-center">{{ numberFormatComma($sum_maintenance_fee_amount)}}</td>
                    <td class="text-center">{{ numberFormatComma($sum_outstanding_maintenance_fee_amount)}}</td> --}}
                    <td class="right">{{ numberFormatComma($sum_total_maintenance_fee)}}</td>
                    <td class="right">{{ numberFormatComma($sum_adjustment_amount)}}</td>
                    <td class="right">{{ numberFormatComma($sum_total_amount_after_adjustment)}}</td>
                </tr>

            </tbody>
        </table>

    </body>
</html>
