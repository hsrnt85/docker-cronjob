@extends('layouts.master')

{{-- @section('file-css')
    <link href="{{ URL::asset('/assets/libs/rwd-table/rwd-table.min.css') }}" rel="stylesheet" type="text/css" />
@endsection --}}

@section('content')

    <div class="row">
        <div class="col-12">
            <!-- section - search  -->
            <div class="card ">
                <form class="search_form" method="post" action="{{ route('paymentNoticeTransaction.listTenant', ['year' => $year, 'month' => $month ]) }}" >
                    {{ csrf_field() }}

                    <div class="card-body p-3">
                        <div class="border-bottom border-primary mb-3"><h4 class="card-title">Carian Rekod</h4></div>

                        <div class="row mb-2">
                            <label for="search_tenant_name" class="col-md-2 col-form-label">Nama Penghuni</label>
                            <div class="col-md-4">
                                <input class="form-control" type="text" id="search_tenant_name" name="search_tenant_name" value="{{ old('search_tenant_name', $search_tenant_name) }}">
                            </div>
                            <div class="col-md-1"></div>
                            <label for="search_tenant_name" class="col-md-1 col-form-label">No. Notis</label>
                            <div class="col-md-3">
                                <input class="form-control" type="text" id="search_notice_no" name="search_notice_no" value="{{ old('search_notice_no', $search_notice_no) }}">
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label for="payment_category" class="col-md-2 col-form-label">Kategori Bayaran</label>
                            <div class="col-md-4">
                                <select class="form-select" id="search_payment_category" name="search_payment_category" >
                                    <option value="">-- Sila Pilih --</option>
                                    @foreach($paymentCategoryAll as $code)
                                        <option value="{{ $code->id }}" {{ old('search_payment_category', $search_payment_category) == $code->id ? 'selected' : '' }}> {{ $code->payment_category }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
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
                    <div class="border-bottom border-primary mb-4"><h4 class="card-title">{{ getPageTitle(3) }}</h4></div>
                    <form  class="custom-validation" id="form" method="post" action="{{ route('paymentNoticeTransaction.process', ['year' => $year, 'month' => $month ]) }}" >
                        {{ csrf_field() }}
                        <div class="mb-2 row">
                            <div class="col-sm-12">
                                <div class="float-start">
                                    <p>Daerah : {{ district()->district_name; }}</p>
                                    <p>Tahun/Bulan Notis Bayaran : {{ $year }}/{{ $month }}</p>
                                </div>
                                <div class="float-end">
                                @if($paymentNoticeTransaction->flag_process==0 && $tenantPaymentNoticeAll->count() > 0 )
                                {{-- && $tenantPaymentNoticeAll->count() > 0 --}}
                                    <button type="submit" class="btn btn-success swal-process-notice-list">{{ __('button.proses_notis_bayaran') }}</button>
                                @endif
                                <a href="{{ route('paymentNoticeTransaction.listPaymentNoticeSchedule', ['year' => $year, 'month' => $month]) }}" class="btn btn-secondary me-2">{{ __('button.kembali') }}</a>
                                </div>
                            </div>
                        </div>

                        {{-- <div class="table-rep-plugin"> --}}
                        {{-- <div class="table-responsive mb-0 border-0" > --}}
                        {{-- <div data-simplebar style="max-height: 1000px;"> --}}
                            <div id="datatable_wrapper">
                            <table class="table table-striped table-bordered dt-responsive wrap w-100 mb-4" >
                                <thead class="bg-primary bg-gradient text-white">
                                    <tr role="row">
                                        <th width="4%" class="text-center">Bil</th>
                                        <th width="18%" class="text-center">Nama Penghuni</th>
                                        <th class="text-center">No. Kad Pengenalan</th>
                                        {{-- <th class="text-center">Jenis Perkhidmatan</th> --}}
                                        <th width="25%" class="text-center">Alamat </th>
                                        <th width="10%" class="text-center">No. Notis </th>
                                        <th class="text-center" >Sewa (RM)</th>
                                        <th class="text-center" >Denda (RM)</th>
                                        <th class="text-center" >Yuran Penyelenggaraan (RM)</th>
                                        <th class="text-center" >Pelarasan (RM)</th>
                                        <th class="text-center" >Jumlah Notis(RM)</th>
                                    </tr>

                                </thead>
                                <tbody>
                                    @if($tenantPaymentNoticeAll->count() > 0)
                                        @php

                                            $sum_total_rental = 0;
                                            $sum_total_penalty = 0;
                                            $sum_total_maintenance_fee = 0;
                                            $sum_adjustment_amount = 0;
                                            $sum_total_amount_after_adjustment = 0;
                                        @endphp
                                        @foreach ($tenantPaymentNoticeAll as $data)
                                            @php
                                                $dataQuarters = $data->quarters;
                                                $address = $dataQuarters?->unit_no.' '. $dataQuarters?->address_1.' '.$dataQuarters?->address_2.' '.$dataQuarters?->address_3;

                                                $total_rental = $data->total_rental;
                                                $total_penalty = $data->total_damage_penalty + $data->total_blacklist_penalty;
                                                $total_maintenance_fee = $data->total_maintenance_fee;
                                                $adjustment_amount = $data->adjustment_amount;
                                                $total_amount_after_adjustment = $data->total_amount_after_adjustment;
                                            @endphp
                                            <tr class="odd">
                                                <td class="text-center" tabindex="0">{{ $loop->iteration }}</td>
                                                <td >{{ $data->name }} <input type="hidden" name="tenants_id_arr[]" value="{{ $data->tenants_id }}" ></td>
                                                <td >{{ $data->new_ic }} </td>
                                                {{-- <td >{{ $data->services_type }} </td> --}}
                                                <td >{{ $address }}</td>
                                                <td class="text-center">{{ $data->payment_notice_no }}</td>
                                                <td style="text-align:right">{{ numberFormatComma($total_rental)}}</td>
                                                <td style="text-align:right">{{ numberFormatComma($total_penalty)}}</td>
                                                <td style="text-align:right">{{ numberFormatComma($total_maintenance_fee)}}</td>
                                                <td style="text-align:right">{{ numberFormatComma($adjustment_amount)}}</td>
                                                <td style="text-align:right">{{ numberFormatComma($total_amount_after_adjustment)}}</td>
                                            </tr>
                                            @php

                                                $sum_total_rental += $total_rental;
                                                $sum_total_penalty += $total_penalty;
                                                $sum_total_maintenance_fee += $total_maintenance_fee;
                                                $sum_adjustment_amount += $adjustment_amount;
                                                $sum_total_amount_after_adjustment += $total_amount_after_adjustment;
                                            @endphp
                                        @endforeach
                                        <tr class="bg-light fw-bolder">
                                            <td class="text-center" colspan="5">JUMLAH KESELURUHAN (RM)</a></td>
                                            <td style="text-align:right">{{ numberFormatComma($sum_total_rental)}}</td>
                                            <td style="text-align:right">{{ numberFormatComma($sum_total_penalty)}}</td>
                                            <td style="text-align:right">{{ numberFormatComma($sum_total_maintenance_fee)}}</td>
                                            <td style="text-align:right">{{ numberFormatComma($sum_adjustment_amount)}}</td>
                                            <td style="text-align:right">{{ numberFormatComma($sum_total_amount_after_adjustment)}}</td>
                                        </tr>
                                    @else
                                        <tr>
                                            <td class="text-center" colspan="10">Tiada Rekod</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>

                        </div> <!--data-simplebar-->
                        {{-- </div>     --}}
                        {{-- </div> --}}

                        <div class="mb-2 row">
                            <div class="col-sm-12">
                                <div class="float-end">
                                @if($paymentNoticeTransaction->flag_process==0 && $tenantPaymentNoticeAll->count() > 0)
                                    <button type="submit" class="btn btn-success swal-process-notice-list">{{ __('button.proses_notis_bayaran') }}</button>
                                @endif
                                <a href="{{ route('paymentNoticeTransaction.listPaymentNoticeSchedule', ['year' => $year, 'month' => $month]) }}" class="btn btn-secondary me-2">{{ __('button.kembali') }}</a>
                                </div>
                            </div>
                        </div>
                    </form>
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
