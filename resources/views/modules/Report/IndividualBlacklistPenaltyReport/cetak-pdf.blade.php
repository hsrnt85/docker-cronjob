<html>
    <head>
        <link href="{{ getPathDocumentCss() .'report.css' }}" type="text/css" />
    </head>
    <body>

        <!-- Define header and footer blocks before your content -->
        @include('report.header')
        @include('report.footer')
        <!-- Define header and footer blocks before your content -->
        <header>
            <table width="100%" cellspacing="0">

                <tr class="title_header center title_header_padding">
                    @php
                        $sentence = "";
                        if($selectedMonth){
                            $sentence .= "PADA BULAN " . $monthName .' '.$selectedYear;
                        }else{
                            $sentence .= "SEPANJANG TAHUN " . $selectedYear;
                        }
                    @endphp
                    <td colspan="6">LAPORAN DENDA HILANG KELAYAKAN (INDIVIDU) {{ $sentence }}</td>
                </tr>
            </table>

            <div class="pb-4"></div><br>

            @if($tenantByIc && $searchNewIc)
                <table width="100%" cellpadding="2" cellspacing="0" class="bold" >
                    <tr>
                        <td width="15%">Nama</td>
                        <td width="1%">:</td>
                        <td class="left">{{ $tenantByIc->name??'' }}</td>
                        <td width="9%"></td>
                        <td width="18%">No. Kad Pengenalan</td>
                        <td width="1%">:</td>
                        <td class="left">{{ $tenantByIc->new_ic??'' }}</td>
                    </tr>

                    <tr >
                        <td width="15%">Jawatan</td>
                        <td width="1%">:</td>
                        <td class="left">{{ $tenantByIc->position ?? '' }}</td>
                        <td width="9%"></td>
                        <td width="18%">Jabatan</td>
                        <td width="1%">:</td>
                        <td class="left">{{ $tenantByIc->organization_name??'' }}</td>
                    </tr>
                    <tr >
                        <td width="15%">Alamat Kuarters</td>
                        <td >:</td>
                        <td class="left" width="30%">{{($tenantByIc->quarters?->unit_no.', '.$tenantByIc->quarters?->address_1.' '.$tenantByIc->quarters?->address_2.' '.$tenantByIc->quarters?->address_3) ?? ''}}</td>
                    <td width="26%" colspan="3"></td>
                    <td class="left"></td>
                    </tr>
                </table>

                <div class="pb-4"></div>

                <table width="100%" cellpadding="2" cellspacing="0" class="bold"  >
                    <tr>
                        <td width="15%">Tarikh Hilang Kelayakan</td>
                        <td width="1%">:</td>
                        <td class="left">{{convertDateSys($tenantByIc->blacklist_date) ?? '-' }}</td>
                    </tr>

                    <tr >
                        <td width="15%">Kadar Sewa Sebulan</td>
                        <td width="1%">:</td>
                        <td class="left">{{ $tenantByIc->rental_fee ?? '' }}</td>
                        <td width="9%"></td>
                        <td width="18%">Kadar Denda Hilang Kelayakan</td>
                        <td width="1%">:</td>
                        <td class="left">{{ ($blacklistPenaltyFirst != "") ? $blacklistPenaltyFirst->rate?->rate.'%' : '-' }}</td>
                    </tr>
                </table>
                <div class="pb-4"></div>
            @endif
        </header>

        <div class="pb-4"></div>  <div class="pb-4"></div>   <div class="pb-4"></div>

        <table width="100%" cellspacing="0" cellpadding="3" >
            <thead>
                <tr role="row" class="grid_header center header_border">
                    <th class="text-center" width="8%">Bil.</th>
                    <th  class="text-center">No. Rujukan Denda</th>
                    <th width="" class="text-center">Tempoh (Bulan)</th>
                    <th width="25%" class="text-center">Harga Pasaran Semasa Rumah (RM)</th>
                    <th width="" class="text-center">Kadar Denda (%)</th>
                    <th width="" class="text-center">Amaun Denda (RM)</th>
                </tr>
            </thead>

            <tbody>
                @if ($blacklistPenaltyAll->count()> 0)
                    @foreach ($blacklistPenaltyAll as $bil => $bp)
                        <tr class="grid_content center border ">
                            <td class="text-center" scope="row">{{ $loop->iteration }}</td>
                            <td class="text-center">{{ $bp->penalty_ref_no }}</td>
                            <td class="text-center">{{ convertDateToMonthYear($bp->penalty_date)}}</td>
                            <td class="text-center">{{ $bp->market_rental_fee ??'' }}</td>
                            <td class="text-center">{{ ($bp) ? $bp->rate?->rate.'%' : ''  }}</td>
                            <td class="text-center">{{ $bp->penalty_amount }}</td>
                        </tr>
                    @endforeach
                @else
                    <tr class="center grid_content ">
                        <td class="text-center " colspan="6">Tiada Rekod</td>
                    </tr>
                @endif
            </tbody>
        </table>

    </body>

</html>
