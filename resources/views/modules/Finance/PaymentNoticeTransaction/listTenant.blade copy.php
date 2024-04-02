@extends('layouts.master')

{{-- @section('file-css')
    <link href="{{ URL::asset('/assets/libs/rwd-table/rwd-table.min.css') }}" rel="stylesheet" type="text/css" />
@endsection --}}

@section('content')

    <div class="row">
        <div class="col-12">
            <!-- section - search  -->
            <div class="card ">
                <form class="search_form" method="post" action="{{ route('paymentNoticeTransaction.listTenant', ['year' => $year, 'month' => $month, 'qcid'=> $quarters_cat_id ]) }}" >
                    {{ csrf_field() }}

                    <div class="card-body p-3">
                        <div class="border-bottom border-primary mb-4"><h4 class="card-title">Carian Rekod</h4></div>

                        <div class="row mb-4">
                            <label for="carian_nama_penghuni" class="col-md-2 col-form-label">Nama Penghuni</label>
                            <div class="col-md-8">
                                <input class="form-control" type="text" id="carian_nama_penghuni" name="carian_nama_penghuni" value="{{ old('carian_nama_penghuni', $carian_nama_penghuni) }}">
                            </div>
                        </div>
                        <div class="row mb-4">
                            <label for="carian_nama_penghuni" class="col-md-2 col-form-label">No Notis</label>
                            <div class="col-md-8">
                                <input class="form-control" type="text" id="carian_no_notis" name="carian_no_notis" value="{{ old('carian_no_notis', $carian_no_notis) }}">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 mb-1">
                                <button class="btn btn-primary" type="submit">{{ __('button.cari') }}</button>
                                <button name="reset" class="btn btn-primary" value="reset" onClick ="clearSearchInput()">{{ __('button.reset') }}</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <!-- end section - search  -->

            <!-- section - list tenant -->
            <div class="card">
                <div class="card-body">
                                    
                    {{-- PAGE TITLE --}}
                    <div class="border-bottom border-primary mb-4"><h4 class="card-title">{{ getPageTitle(4) }}</h4></div>

                    <p>Daerah : {{ district()->district_name; }}</p>
                    <p>Kategori Kuarters (Lokasi) : {{ $category_quarters->name; }}</p>
                    <p>Tahun/Bulan Notis Bayaran : {{ $year }}/{{ $month }}</p>
                   
                    <div class="table-rep-plugin">
                    <div class="table-responsive mb-0 border-0" >
                    <div data-simplebar style="max-height: 1000px;">

                        <table class="table table-bordered dt-responsive wrap w-100 mb-4" >
                            <thead class="bg-primary bg-gradient text-white">
                                <tr role="row">
                                    <th width="4%" class="text-center" rowspan="2">Bil</th>
                                    <th width="18%" class="text-center" rowspan="2">Nama Penghuni</th>
                                    <th class="text-center" rowspan="2">No. Kad Pengenalan</th>
                                    <th class="text-center" rowspan="2">Jenis Perkhidmatan</th>
                                    <th width="25%" class="text-center" rowspan="2">Alamat </th>
                                    <th width="10%" class="text-center" rowspan="2">No. Notis </th>
                                    {{-- <th class="text-center" colspan="3">Sewa (RM)</th>
                                    <th class="text-center" colspan="3">Denda (RM)</th>
                                    <th class="text-center" colspan="3">Yuran Penyelenggaraan (RM)</th>
                                    <th class="text-center" rowspan="2">Pelarasan (RM)</th>
                                    <th class="text-center" rowspan="2">Jumlah Notis(RM)</th> --}}
                                    <th class="text-center" >Sewa (RM)</th>
                                    <th class="text-center" >Denda (RM)</th>
                                    <th class="text-center" >Yuran Penyelenggaraan (RM)</th>
                                    <th class="text-center" >Pelarasan (RM)</th>
                                    <th class="text-center" >Jumlah Notis(RM)</th>
                                </tr>
                                {{-- <tr role="row">
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
                                @if($tenantPaymentNoticeAll->count() > 0)
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
                                            $dataQuarters = $data->quarters;
                                            $address = $dataQuarters?->unit_no.' '. $dataQuarters?->address_1.' '.$dataQuarters?->address_2.' '.$dataQuarters?->address_3;
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
                                        <tr class="odd">
                                            <td class="text-center" tabindex="0">{{ $loop->iteration }}</td>
                                            <td >{{ $data->name }} </td>
                                            <td >{{ $data->no_ic }} </td>
                                            <td >{{ $data->services_type?->services_type }} </td>
                                            <td >{{ $address }}</td>
                                            <td class="text-center">{{ $data->payment_notice_no }}</td>
                                            {{-- <td class="text-center">{{ numberFormatComma($rental_amount)}}</td>
                                            <td class="text-center">{{ numberFormatComma($outstanding_rental_amount)}}</td> --}}
                                            <td class="text-center">{{ numberFormatComma($total_rental)}}</td>
                                            {{-- <td class="text-center">{{ numberFormatComma($penalty_amount)}}</td>
                                            <td class="text-center">{{ numberFormatComma($outstanding_penalty_amount)}}</td> --}}
                                            <td class="text-center">{{ numberFormatComma($total_penalty)}}</td>
                                            {{-- <td class="text-center">{{ numberFormatComma($maintenance_fee_amount)}}</td>
                                            <td class="text-center">{{ numberFormatComma($outstanding_maintenance_fee_amount)}}</td> --}}
                                            <td class="text-center">{{ numberFormatComma($total_maintenance_fee)}}</td>
                                            <td class="text-center">{{ numberFormatComma($adjustment_amount)}}</td>
                                            <td class="text-center">{{ numberFormatComma($total_amount_after_adjustment)}}</td>
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
                                    <tr class="bg-light fw-bolder">
                                        <td class="text-center" colspan="6">Jumlah Keseluruhan</a></td>
                                        {{-- <td class="text-center">{{ numberFormatComma($sum_rental_amount)}}</td>
                                        <td class="text-center">{{ numberFormatComma($sum_outstanding_rental_amount)}}</td> --}}
                                        <td class="text-center">{{ numberFormatComma($sum_total_rental)}}</td>
                                        {{-- <td class="text-center">{{ numberFormatComma($sum_penalty_amount)}}</td>
                                        <td class="text-center">{{ numberFormatComma($sum_outstanding_penalty_amount)}}</td> --}}
                                        <td class="text-center">{{ numberFormatComma($sum_total_penalty)}}</td>
                                        {{-- <td class="text-center">{{ numberFormatComma($sum_maintenance_fee_amount)}}</td>
                                        <td class="text-center">{{ numberFormatComma($sum_outstanding_maintenance_fee_amount)}}</td> --}}
                                        <td class="text-center">{{ numberFormatComma($sum_total_maintenance_fee)}}</td>
                                        <td class="text-center">{{ numberFormatComma($sum_adjustment_amount)}}</td>
                                        <td class="text-center">{{ numberFormatComma($sum_total_amount_after_adjustment)}}</td>
                                    </tr>
                                @else
                                    <tr>
                                        <td class="text-center" colspan="11">Tiada Rekod</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                        
                        <div class="mb-3 row">
                            <div class="col-sm-12">
                                @if($paymentNoticeTransaction->flag_process==0)
                                    <button type="submit" class="btn btn-primary float-end swal-process-notice-list">{{ __('button.proses_notis_bayaran') }}</button>
                                @endif
                                <a href="{{ route('paymentNoticeTransaction.listQuartersCategoryWithTenant', ['year' => $year, 'month' => $month]) }}" class="btn btn-secondary float-end me-2">{{ __('button.kembali') }}</a>
                            </div>
                        </div> 

                    </div>    
                    </div>    
                    </div><!--data-simplebar-->

                </div>
            </div>
            <!-- end section - list tenant -->

        </div> <!-- end col -->
    </div>
    <!-- end row -->

@endsection

{{-- @section('script')
    <!-- Responsive Table js -->
    <script src="{{ URL::asset('/assets/libs/rwd-table/rwd-table.min.js') }}"></script>
    <!-- Init js -->
    <script src="{{ URL::asset('/assets/js/libs/table-responsive.init.js') }}"></script>
@endsection --}}