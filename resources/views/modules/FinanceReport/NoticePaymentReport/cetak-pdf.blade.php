<html>
    <head>
        <link href="{{ getPathDocumentCss() .'finance-report.css' }}" type="text/css" />
    </head>

    <body class="payment-notice-report">
        <!-- Define header and footer blocks before your content -->
        @include('report.header')
        {{-- @include('report.footer') --}}
        <!-- Define header and footer blocks before your content -->
        <header >   {{--class="subheader-payment-notice" --}}
            <table width="100%" cellspacing="0" cellpadding="3" class="bold fixed-header" >
                <tr class="center">
                    <td>KERAJAAN NEGERI JOHOR DARUL TAKZIM</td>
                </tr>
                <tr class="center title_padding">
                    <td>LAPORAN NOTIS BAYARAN BAGI BULAN {{ $month.' '.$selectedYear }} </td>
                </tr>
            </table>

            <table class="bold" width="100%" cellspacing="0" cellpadding="2">
                <tr >
                    <td width="19%">JABATAN</td>
                    <td width="1%">:</td>
                    <td class="left">{{upperText($finance_department->department_name) ??''}}</td>
                </tr>
                <tr >
                    <td >KOD JABATAN</td>
                    <td >:</td>
                    <td >{{upperText($finance_department->department_code) ?? ''}}</td>
                </tr>
                <tr >
                    <td >PTJ</td>
                    <td >:</td>
                    <td >{{upperText($finance_department->ptj_code).' - ' ?? ''}} {{upperText($finance_department->ptj_name)?? ''}}</td>
                </tr>
                <tr>
                    <td >TAHUN / BULAN NOTIS BAYARAN</td>
                    <td >:</td>
                    <td >{{ $selectedYear }}/{{ $selectedMonth }}</td>
                </tr>
                @if($selectedDistrict)
                    <tr>
                        <td>DAERAH</td>
                        <td>:</td>
                        <td>{{ $district->district_name}}</td>
                    </tr>
                @endif
                @if($quartersCategory)
                    <tr>
                        <td>KATEGORI KUARTERS</td>
                        <td>:</td>
                        <td>{{ $quartersCategory}}</td>
                    </tr>
                @endif
                @if($servicesType)
                    <tr>
                        <td>JENIS PERKHIDMATAN</td>
                        <td>:</td>
                        <td>{{ upperText($servicesType) }}</td>
                    </tr>
                @endif
                @if($searchNoticeNo)
                    <tr>
                        <td>NO. NOTIS BAYARAN</td>
                        <td>:</td>
                        <td>{{ $searchNoticeNo}}</td>
                    </tr>
                @endif
                @if($searchIcNo)
                    <tr>
                        <td>NO. KAD PENGENALAN</td>
                        <td>:</td>
                        <td>{{ $searchIcNo}}</td>
                    </tr>
                @endif
            </table>
        </header>
        {{-- <div class="pb-4"></div> --}}

        <div class="content page-break ">

        <table width="100%" cellspacing="0" cellpadding="5" class="table-content">
            <thead class="bg-primary bg-gradient text-white">
                <tr role="row" class="center header_border bold info_content_border uppercase">
                    <th width="3%" rowspan="2">Bil.</th>
                    <th width="10%"rowspan="2">Nama Penghuni</th>
                    <th width="7%" rowspan="2">No. Kad Pengenalan</th>
                    <th width="9%" rowspan="2">Agensi</th>
                    <th width="9%" rowspan="2">Alamat </th>
                    <th width="3%" rowspan="2">No. Notis </th>
                    <th width="6%" colspan="2">Sewa (RM)</th>
                    <th width="6%" colspan="2">Denda (RM)</th>
                    <th width="6%" colspan="2">Yuran Penyelenggaraan (RM)</th>
                    <th width="5%" rowspan="2">Pelarasan (RM)</th>
                    <th width="5%" rowspan="2">Jumlah Notis<br>(RM)</th>
                    <th width="5%" rowspan="2">Jumlah Bayaran<br>(RM)</th>
                    <th width="5%" rowspan="2">Baki Bayaran<br>(RM)<br></th>
                </tr>
                <tr class="info_content_border bold center uppercase">
                    <th>Tunggakan</th>
                    <th>Semasa</th>
                    <th>Tunggakan</th>
                    <th>Semasa</th>
                    <th>Tunggakan</th>
                    <th>Semasa</th>
                </tr>
            </thead>
            <tbody>
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
                @foreach ($tenantPaymentNoticeAll as $data)
                @php
                    $rental_amount = $data->rental_amount;
                    $outstanding_rental_amount = $data->outstanding_rental_amount;
                    $total_rental = $data->total_rental;
                    $penalty_amount = $data->damage_penalty_amount + $data->blacklist_penalty_amount;
                    $outstanding_penalty_amount = $data->outstanding_damage_penalty_amount + $data->outstanding_blacklist_penalty_amount;
                    $total_penalty = $penalty_amount + $outstanding_penalty_amount ;
                    $maintenance_fee_amount = $data->maintenance_fee_amount;
                    $outstanding_maintenance_fee_amount = $data->outstanding_maintenance_fee_amount;
                    $total_maintenance_fee = $data->total_maintenance_fee;
                    $adjustment_amount = $data->adjustment_amount;
                    $total_amount_after_adjustment = $data->total_amount_after_adjustment;
                    $total_payment_amount = ($data->payment_status ==2) ?  $data->total_amount : '0.00'; //  Jumlah Bayaran
                    $total_payment_balance =  $total_amount_after_adjustment - $total_payment_amount; //  Baki Bayaran

                @endphp
                    <tr class="border info_content_border" >
                        <td class="text-center" tabindex="0">{{ $loop->iteration }}</td>
                        <td >{{ $data->name }}</td>
                        <td class="text-center">{{ $data->no_ic }}</td>
                        <td >{{ $data->organization->name }}</td>
                        <td >{{ $data->quarters_address }}</td>
                        <td >{{ $data->payment_notice_no }}</td>
                        <td class="right">{{ numberFormatComma($outstanding_rental_amount)}}</td>
                        <td class="right">{{ numberFormatComma($rental_amount)}}</td>
                        <td class="right">{{ numberFormatComma($outstanding_penalty_amount)}}</td>
                        <td class="right">{{ numberFormatComma($penalty_amount)}}</td>
                        <td class="right">{{ numberFormatComma($outstanding_maintenance_fee_amount)}}</td>
                        <td class="right">{{ numberFormatComma($maintenance_fee_amount)}}</td>
                        <td class="right">{{ numberFormatComma($adjustment_amount)}}</td>
                        <td class="right">{{ numberFormatComma($total_amount_after_adjustment)}}</td>
                        <td class="right">{{ numberFormatComma($total_payment_amount) }} </td>
                        <td class="right">{{ numberFormatComma($total_payment_balance) }}</td>   <!-- Baki Bayaran -->
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
                    <td class="right" colspan="6">JUMLAH KESELURUHAN</td>
                    <td class="right">{{ numberFormatComma($sum_outstanding_rental_amount)}}</td>
                    <td class="right">{{ numberFormatComma($sum_rental_amount)}}</td>
                    <td class="right">{{ numberFormatComma($sum_outstanding_penalty_amount)}}</td>
                    <td class="right">{{ numberFormatComma($sum_penalty_amount)}}</td>
                    <td class="right">{{ numberFormatComma($sum_outstanding_maintenance_fee_amount)}}</td>
                    <td class="right">{{ numberFormatComma($sum_maintenance_fee_amount)}}</td>
                    <td class="right">{{ numberFormatComma($sum_adjustment_amount)}}</td>
                    <td class="right">{{ numberFormatComma($sum_total_amount_after_adjustment)}}</td>
                    <td class="right">{{ numberFormatComma($sum_total_payment_amount)}}</td> <!-- Jumlah Bayaran -->
                    <td class="right">{{ numberFormatComma($sum_total_payment_balance)}}</td> <!-- Jumlah Baki Bayaran -->
                </tr>


            </tbody>
        </table>
        <div class="pb-4"></div>
        <script >
            document.addEventListener("DOMContentLoaded", function () {
            // Get the height of the header
            var headerHeight = document.querySelector("header").offsetHeight;
            // Set the margin-top of the content based on the header height
            document.querySelector(".content").style.marginTop = headerHeight + "px";
            });
        </script>
    </body>

</html>
