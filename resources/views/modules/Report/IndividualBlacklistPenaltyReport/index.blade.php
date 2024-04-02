@extends('layouts.master')

@section('content')

    <div class="row">
        <div class="col-12">

            <!-- section - search  -->
            <div class="card ">

                <form class="search_form custom-validation" action="{{ route('individualBlacklistPenaltyReport.index') }}" method="get" id="form-laporan-aduan-kerosakan">

                    {{ csrf_field() }}

                    <div class="card-body p-3">

                        <div class="border-bottom border-primary mb-4">
                            <h4 class="card-title">Carian Rekod</h4>
                        </div>

                        <div class="row">
                            <div class="col-md-3 p-1 mb-3" >
                                <label class="col-form-label ">Tahun<span class="text-danger"> *</span></label>
                                <select class="form-select" id="year" name="year" required data-parsley-required-message="{{ setMessage('year.required') }}">
                                    <option value="">-- Sila Pilih --</option>
                                    @foreach($yearAll as $y)
                                        <option value="{{$y->year}}" {{old('year', $selectedYear) == $y->year? "selected" : ""}}>{{ $y->year }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-3 p-1 mb-3">
                                <label class="col-form-label">Bulan</label>
                                <select class="form-select" id="month" name="month">
                                    <option value="">-- Sila Pilih --</option>
                                    @foreach($monthAll as $m)
                                        <option value="{{ $m->id }}" {{ old('month', $selectedMonth) == $m->id ? 'selected' : '' }}>
                                            {{ $m->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-3 p-1 mb-3" >
                                <label class="col-form-label ">No. Kad Pengenalan<span class="text-danger"> *</span></label>
                                <input class="form-control" type="text" id="new_ic" name="new_ic" value="{{ old('quarters_cat', $searchNewIc)  }}" oninput="checkNumber(this)"
                                minlength="12" maxlength="12"  data-parsley-length-message="{{ setMessage('new_ic.digits') }}"required data-parsley-required-message="{{ setMessage('new_ic.required') }}" data-route-tenant="{{ route('individualBlacklistPenaltyReport.ajaxCheckTenantIC') }}"
                                data-parsley-errors-container="#errors-ic-numb">
                                <div class="col-md-10 mt-1" id="errors-ic-numb"></div>
                                <div class="col-md-10 mt-1" id="ic-error"></div>
                                <div class="spinner-wrapper"></div>
                            </div>
{{--
                            <div class="col-md-3 p-1 mb-3">
                                <label class="col-form-label ">Kategori Kuarters (lokasi) <span class="text-danger"> *</span></label>
                                <select id="quarters_cat" name="quarters_cat" class="form-select" value="{{ old('quarters_cat', $selectedQuartersCat) }}"
                                    data-parsley-required-message="{{ setMessage('quarters_cat.required') }}" data-parsley-errors-container="#parsley-errors-quarters-cat">
                                    <option value=''> -- Kategori Kuarters (Lokasi) -- </option>
                                    @foreach ($quartersCatsAll as $quarters_cat)
                                        <option value='{{ $quarters_cat->id }}' @if ($quarters_cat->id == $selectedQuartersCat) selected @endif>{{ $quarters_cat->name }}</option>
                                    @endforeach
                                </select>
                                <div id="parsley-errors-quarters-cat"></div>
                                @error('quarters_cat')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div> --}}

                            {{-- <div class="col-md-3 p-1 mb-3">
                                <label class="col-form-label ">Pegawai Pemantau</label>
                                <select id="officer" name="officer" class="form-select" value="{{ old('officer', $selectedOfficer) }}"
                                    data-parsley-required-message="{{ setMessage('officer.required') }}" data-parsley-errors-container="#parsley-errors-officer">
                                    <option value=''> -- Pilih Pegawai -- </option>
                                    @foreach ($officerAll as $officer)
                                        <option value='{{ $officer->id }}' @if ($officer->id == $selectedOfficer) selected @endif>{{ $officer->user->name }}</option>
                                    @endforeach
                                </select>
                                <div id="parsley-errors-officer"></div>
                                @error('officer')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div> --}}
                        </div>

                        <div class="row">
                            <div class="col-md-12 p-1 mb-3">
                                <button class="btn btn-primary" id="cari" type="submit" onClick="setFormTarget()">{{ __('button.cari') }}</button>
                                <button name="reset" class="btn btn-primary" type="submit" value="reset" onClick="clearSearchInput()">{{ __('button.reset') }}</button>
                                <button name="muat_turun_pdf" id="muat_turun_pdf" class="btn btn-success" type="submit" value="pdf"
                                    onClick="setFormTarget(this)">{{ __('button.muat_turun_pdf') }}</button>
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
                    <div class="border-bottom border-primary mb-4">
                        <h4 class="card-title">{{ getPageTitle(1) }}</h4>
                    </div>

                    <div id="datatable_wrapper">
                        <div class="row">
                            <div class="col-sm-12">
                                @if($tenantByIc && $searchNewIc)
                                    <table id="info_tenant" width="100%" cellpadding="3" cellspacing="0" style=" font-size: 12px;font-weight:bold;" ">
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

                                    <table  id="info_blacklist" width="100%" cellpadding="3" cellspacing="0" style=" font-size: 12px;   font-weight:bold;">
                                        <tr>
                                            <td width="15%">Tarikh Hilang Kelayakan</td>
                                            <td width="1%">:</td>
                                            <td class="left"  width="30%">{{convertDateSys($tenantByIc->blacklist_date) ?? '-' }}</td>
                                        </tr>

                                        <tr >
                                            <td width="15%">Kadar Sewa Sebulan</td>
                                            <td width="1%">:</td>
                                            <td class="left">{{ $tenantByIc->rental_fee ?? '' }}</td>
                                            <td width="9%"></td>
                                            <td width="18%">Kadar Denda Hilang Kelayakan</td>
                                            <td width="1%">:</td>
                                            <td class="left">{{ ($blacklistPenaltyFirst?->rate?->rate) ? $blacklistPenaltyFirst?->rate?->rate.'%' : '-' }}</td>
                                        </tr>
                                    </table>
                                    <div class="pb-4"></div>

                                @endif

                               {{-- @if ($selectedQuartersCat) --}}

                                <table id="datatable-report" class="table table-striped table-bordered dt-responsive no-wrap w-100 dataTable" role="grid">
                                    <thead class="bg-primary bg-gradient text-white">
                                        <tr role="row">
                                            <th class="text-center">Bil.</th>
                                            <th width="" class="text-center">No. Rujukan Denda</th>
                                            <th width="" class="text-center">Tempoh (Bulan)</th>
                                            <th width="" class="text-center">Harga Pasaran Semasa Rumah (RM)</th>
                                            <th width="" class="text-center">Kadar Denda (%)</th>
                                            <th width="" class="text-center">Amaun Denda (RM)</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if ($blacklistPenaltyAll)
                                            @foreach ($blacklistPenaltyAll as $ind => $bp)
                                                <tr>
                                                    <td class="text-center" scope="row">{{ $loop->iteration }}</td>
                                                    <td class="text-center">{{ $bp->penalty_ref_no }}</td>
                                                    <td class="text-center">{{ convertDateToMonthYear($bp->penalty_date) ?? ''}}</td>
                                                    <td class="text-center">{{ $bp->market_rental_fee ??'' }}</td>
                                                    <td class="text-center">{{ $bp->rate->rate }}</td>
                                                    <td class="text-center">{{ $bp->penalty_amount }}</td>
                                                </tr>
                                            @endforeach
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
    <script src="{{ URL::asset('assets/js/pages/Report/IndividualBlacklistPenaltyReport/IndividualBlacklistPenaltyReport.js') }}"></script>
@endsection
