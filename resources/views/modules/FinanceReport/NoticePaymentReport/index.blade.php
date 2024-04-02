@extends('layouts.master')
@section('content')

<style>
    .align-right{
        text-align: right;
    }
    .fw-bolder {
    font-weight: bolder !important;
    }

</style>
<div class="row">
    <div class="col-12">
        <!-- section - search  -->
        <div class="card ">
            <form class="search_form custom-validation" action="{{ route('noticePaymentReport.index') }}" method="post">
                {{csrf_field()}}

                <div class="card-body p-3">
                    <div class="border-bottom border-primary mb-4"><h4 class="card-title">Carian Rekod</h4></div>

                    <div class="row">
                        <div class="col-md-3 p-1 mb-3">
                            <label class="col-form-label ">Tahun<span class="text-danger"> *</span></label>
                            <select class="form-select"  id="year" name="year" required
                                data-parsley-required-message="{{ setMessage('year.required') }}" data-parsley-errors-container="#parsley-year">
                                <option value="">-- Pilih Tahun --</option>
                                @foreach ($yearAll as $year)
                                    @if($year -> year == $selectedYear)
                                        <option value="{{ $year -> year }}" selected>{{ $year -> year }}</option>
                                    @else
                                        <option value="{{ $year -> year }}">{{ $year -> year }}</option>
                                    @endif
                                @endforeach


                            </select>
                            <div id="parsley-year"></div>
                        </div>

                        <div class="col-md-3 p-1 mb-3">
                            <label class="col-form-label ">Bulan<span class="text-danger"> *</span></label>
                            <select id="month" name="month" class="form-select" value="{{ old('month') }}" required
                                data-parsley-required-message="{{ setMessage('month.required') }}" data-parsley-errors-container="#parsley-month">
                                <option value="">-- Pilih Bulan --</option>
                                @foreach ($monthAll as $month)
                                    <option value="{{ $month->id }}" @if ($month->id == old('month', $selectedMonth)) selected @endif>{{ $month->name }}</option>
                                @endforeach
                            </select>
                            <div id="parsley-month"></div>
                        </div>
                        <div class="col-md-3 p-1 mb-3">
                            <label class="col-form-label ">Daerah</label>
                            <select id="district" name="district" class="form-select" value="{{ old('district') }}" >
                                <option value=''> -- Pilih Daerah -- </option>
                                @foreach ($districtAll as $district)
                                    <option value="{{ $district->id }}" {{ $district->id == old('district', $selectedDistrict) ? 'selected' : '' }}>
                                        {{-- {{ $loop->first ? 'selected' : '' }}> --}}
                                        {{ $district->district_name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-3 p-1 mb-3" >
                            <label class="col-form-label ">Kategori Kuarters (lokasi)</label>
                            <select id="quarters_category" name="quarters_category" class="form-control select2" value="{{ old('quarters_category') }}">
                                <option value="">-- Pilih Kategori Kuarters --</option>
                                @foreach($quartersCategoryAll as $quarters)
                                    @if($quarters -> id == $selectedQuartersCategory)
                                        <option value="{{ $quarters -> id }}" selected>{{ $quarters -> name }}</option>
                                    @else
                                        <option value="{{ $quarters -> id }}">{{ $quarters -> name }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-3 p-1 mb-3">
                            <label class="col-form-label ">Jenis Perkhidmatan</label>
                            <select id="services_type" name="services_type" class="form-select"  >
                                <option value="">-- Pilih Jenis Perkhidmatan --</option>
                                @foreach ($servicesTypeAll as $services)
                                    <option value="{{ $services->id }}" @if ($services->id ==  $selectedServicesType) selected @endif>{{ $services->services_type }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-3 p-1 mb-3">
                            <label class="col-form-label ">No Notis Bayaran</label>
                            <input id="notice_no" name="notice_no" class="form-control" value="{{ old('notice_no', $searchNoticeNo) }}">
                        </div>

                        <div class="col-md-3 p-1 mb-3">
                            <label class="col-form-label ">Agensi</label>
                            <select id="organization" name="organization"class="form-control select2" value="{{ old('organization') }}">
                                <option value="">-- Pilih Agensi --</option>
                                @foreach ($organizationAll as $organization)
                                    <option value="{{ $organization->id }}" @if ($organization->id ==  $selectedOrganization) selected @endif>{{ $organization->name }}</option>
                                @endforeach
                            </select>
                        </div>

                    </div>

                    <div class="col-md-3 p-1 mb-3">
                        <label class="col-form-label">No. Kad Pengenalan</label>
                        <input class="form-control input-mask"  type="text" name="ic_no" id="ic_no" data-inputmask="'mask':'999999-99-9999'"  value="{{  old('ic_no', $searchIcNo) }}" >
                    </div>

                    <div class="row">
                        <div class="col-md-12 p-1 mb-3">
                            <button class="btn btn-primary" type="submit" onClick="setFormTarget()">{{ __('button.cari') }}</button>
                            <button name="reset" class="btn btn-primary" id="reset" onClick ="clearSearchInput()">{{ __('button.reset') }}</button>
                            <button name="muat_turun_pdf" class="btn btn-success" type="submit" value="pdf" onClick="setFormTarget(this)">{{ __('button.muat_turun_pdf') }}</button>
                            {{-- <button name="muat_turun_excel" class="btn btn-success" type="submit" value="excel" >{{ __('button.muat_turun_excel') }}</button> --}}
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <!-- end section - search  -->

        <!-- section - list report -->
        <div class="card">
            <div class="card-body">
                {{-- PAGE TITLE --}}
                <div class="border-bottom border-primary mb-4"><h4 class="card-title">{{ getPageTitle(1) }}</h4></div>

                <div class="row">
                    <div class="col-sm-12">
                        <div data-simplebar style="max-height: 1000px;">
                            <table class="table table-striped table-bordered wrap w-100" >
                                <thead class="bg-primary bg-gradient text-white">
                                    <tr role="row">
                                        <th width="3%" class="text-center"  rowspan="2">Bil.</th>
                                        <th width="10%" class="text-center"  rowspan="2">Nama Penghuni</th>
                                        <th width="7%" class="text-center"  rowspan="2">No. Kad Pengenalan</th>
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
                                    <tr role="row">
                                        <th class="text-center" >Tunggakan</th>
                                        <th class="text-center" >Semasa</th>
                                        <th class="text-center" >Tunggakan</th>
                                        <th class="text-center" >Semasa</th>
                                        <th class="text-center" >Tunggakan</th>
                                        <th class="text-center" >Semasa</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @if($tenantPaymentNoticeAll->count() > 0)
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
                                        @foreach($tenantPaymentNoticeAll as $bil => $tpn)
                                            @php
                                                $rental_amount = $tpn->rental_amount;
                                                $outstanding_rental_amount = $tpn->outstanding_rental_amount;
                                                $total_rental = $tpn->total_rental;
                                                $penalty_amount = $tpn->damage_penalty_amount + $tpn->blacklist_penalty_amount;
                                                $outstanding_penalty_amount = $tpn->outstanding_damage_penalty_amount + $tpn->outstanding_blacklist_penalty_amount;
                                                $total_penalty = $penalty_amount + $outstanding_penalty_amount ;
                                                $maintenance_fee_amount = $tpn->maintenance_fee_amount;
                                                $outstanding_maintenance_fee_amount = $tpn->outstanding_maintenance_fee_amount;
                                                $total_maintenance_fee = $tpn->total_maintenance_fee;
                                                $adjustment_amount = $tpn->adjustment_amount;
                                                $total_amount_after_adjustment = $tpn->total_amount_after_adjustment;
                                                $total_payment_amount =  ($tpn->payment_status ==2) ?  $tpn->total_amount : '0.00'; //  Jumlah Bayaran
                                                $total_payment_balance = $total_amount_after_adjustment - $total_payment_amount; //  Baki Bayaran
                                            @endphp
                                            <tr class="odd">
                                                <td class="text-center" tabindex="0">{{ $loop->iteration }}</td>
                                                <td >{{ $tpn->name}}</td>
                                                <td class="text-center" >{{ $tpn->no_ic }}</td>
                                                <td >{{ $tpn->organization->name }}</td>
                                                <td >{{ $tpn->quarters_address }}</td>
                                                <td >{{ $tpn->payment_notice_no }}</td>
                                                <td style="text-align: right;">{{ numberFormatComma($outstanding_rental_amount) }}</td>
                                                <td style="text-align: right;">{{ numberFormatComma($rental_amount) }}</td>
                                                <td style="text-align: right;">{{ numberFormatComma($outstanding_penalty_amount) }}</td>
                                                <td style="text-align: right;">{{ numberFormatComma($penalty_amount) }}</td>
                                                <td style="text-align: right;">{{ numberFormatComma($outstanding_maintenance_fee_amount) }}</td>
                                                <td style="text-align: right;">{{ numberFormatComma($maintenance_fee_amount) }}</td>
                                                <td style="text-align: right;">{{ numberFormatComma($adjustment_amount) }}</td>
                                                <td style="text-align: right;">{{ numberFormatComma($total_amount_after_adjustment) }}</td>
                                                <td style="text-align: right;">{{ numberFormatComma($total_payment_amount) }}</td>
                                                <td style="text-align: right;">{{ numberFormatComma($total_payment_balance) }}</td>
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
                                        <tr class="bg-light fw-bolder">
                                            <td class="align-right" colspan="6">JUMLAH KESELURUHAN</a></td>
                                            <td class="align-right">{{ numberFormatComma($sum_outstanding_rental_amount)}}</td>
                                            <td class="align-right">{{ numberFormatComma($sum_rental_amount)}}</td>
                                            <td class="align-right">{{ numberFormatComma($sum_outstanding_penalty_amount)}}</td>
                                            <td class="align-right">{{ numberFormatComma($sum_penalty_amount)}}</td>
                                            <td class="align-right">{{ numberFormatComma($sum_outstanding_maintenance_fee_amount)}}</td>
                                            <td class="align-right">{{ numberFormatComma($sum_maintenance_fee_amount)}}</td>
                                            <td class="align-right">{{ numberFormatComma($sum_adjustment_amount)}}</td>
                                            <td class="align-right">{{ numberFormatComma($sum_total_amount_after_adjustment)}}</td>
                                            <td class="align-right">{{ numberFormatComma($sum_total_payment_amount)}}</td>
                                            <td class="align-right">{{ numberFormatComma($sum_total_payment_balance)}}</td>
                                        </tr>
                                        @else
                                        <tr>
                                            <td class="text-center" colspan="10">Tiada Rekod</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- end section - list report -->
    </div> <!-- end col -->

</div>
<!-- end row -->

@endsection
@section('script')
<script src="{{ URL::asset('assets/js/pages/FinanceReport/financeReport.js')}}"></script>
@endsection
