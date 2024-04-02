@extends('layouts.master')
@section('css')
    <link href="{{ getPathDocumentCss() .'dynamic-report.css' }}" type="text/css" />
@endsection
@section('content')

    <div class="row">
        <div class="col-12">
            <!-- section - search  -->
            <div class="card ">
                <form class="search_form custom-validation">
                    <div class="card-body p-3">
                        <div class="border-bottom border-primary mb-4">
                            <h4 class="card-title">{{ getPageTitle(1) }}</h4>
                        </div>
                        <div class="row">
                            <div class="col-md-3 p-1 mb-3">
                                <label class="col-form-label ">Kategori Laporan</label>
                                <select id="kategori-laporan" name="kategori_laporan" class="form-select" value="{{ old('kategori_laporan') }}" required
                                    data-parsley-required-message="{{ setMessage('category.required') }}" data-parsley-errors-container="#parsley-errors-laporan">
                                    @foreach ($categoryAll as $category)
                                        <option value="{{ $category->report_category }}" @if ($category->report_category == old('kategori_laporan', $selectedCategory)) selected @endif>{{ $category->report_category }}</option>
                                    @endforeach
                                </select>
                                <div id="parsley-errors-laporan"></div>
                            </div>
                        </div>

                        <div class="border-bottom border-primary mb-4">
                            <h4 class="card-title">Jenis Laporan </h4>
                        </div>
                        <div class="row">
                            <div class="col-sm-4 p-1 mb-3">
                                @foreach ($typeAll as $type)
                                    <div class="form-check mb-2 @error('report_type') is-invalid @enderror">
                                        <input class="form-check-input" type="radio" name="report_type" id="{{ $type->report_type }}" value="{{ $type->id }}"
                                            @if ($report?->id == $type->id) checked="" @endif required data-parsley-required-message="{{ setMessage('report_type.required') }}"
                                            data-parsley-errors-container="#parsley-errors-report-type">
                                        <label class="form-check-label" for="{{ $type->report_type }}">
                                            {{ $type->report_type }}
                                        </label>
                                    </div>
                                @endforeach
                                <div id="parsley-errors-report-type"></div>
                            </div>
                        </div>

                        <div class="border-bottom border-primary mb-4">
                            <h4 class="card-title">Carian Laporan</h4>
                        </div>
                        <div class="row">
                            <div class="col-md-3 p-1 mb-3" hidden>
                                <label class="col-form-label ">Daerah</label>
                                <select id="district" name="district" class="form-select" value="{{ old('district') }}" required
                                    data-parsley-required-message="{{ setMessage('district.required') }}" data-parsley-errors-container="#parsley-errors-district">
                                    <option value=''> -- Pilih Daerah -- </option>
                                    @foreach ($districtAll as $district)
                                        <option value="{{ $district->id }}" {{ $district->id == old('district', $selectedDistrict?->id) ? 'selected' : '' }}>
                                            {{-- {{ $loop->first ? 'selected' : '' }}> --}}
                                            {{ $district->district_name }}</option>
                                    @endforeach
                                </select>
                                <div id="parsley-errors-district"></div>
                            </div>

                            <div class="col-md-3 p-1 mb-3" hidden>
                                <label class="col-form-label ">Gred Jawatan</label>
                                <select id="grade" name="gradeOption" class="select form-select" required
                                    data-parsley-required-message="{{ setMessage('grade.required') }}" data-parsley-errors-container="#parsley-errors-grade"
                                    data-placeholder="Sila pilih Gred Jawatan">
                                    <option value=""> -- Sila Pilih Gred Jawatan --</option>
                                    @foreach ($gradeOptions as $gradeOption)
                                        <option value="{{ $gradeOption->id }}" {{ $gradeOption->id == old('gradeOption', $selectedGradeOption) ? 'selected' : '' }}>{{ $gradeOption->position_grade }}
                                        </option>
                                    @endforeach
                                </select>
                                <div id="parsley-errors-grade"></div>
                            </div>

                            <div class="col-md-3 p-1 mb-3" hidden>
                                <label class="col-form-label ">Jenis Kuarters</label>
                                <select id="landed-type" name="landed_type" class="form-select" value="{{ old('landed_type') }}" required
                                    data-parsley-required-message="{{ setMessage('landed_type.required') }}" data-parsley-errors-container="#parsley-errors-landed-type">
                                    <option value=''> -- Pilih Jenis Kuarters -- </option>
                                    @foreach ($landedTypeAll as $landedType)
                                        <option value="{{ $landedType->id }}" {{ $landedType->id == old('landed_type', $selectedLandedType) ? 'selected' : '' }}>{{ $landedType->type }}</option>
                                    @endforeach
                                </select>
                                <div id="parsley-errors-landed-type"></div>
                            </div>

                            <div class="col-md-3 p-1 mb-3" hidden>
                                <label class="col-form-label ">Kategori Kuarters (Lokasi)</label>
                                <input type="hidden" id="selected-quarters-category" value="{{ $selectedQuartersCategory?->id }}">
                                <select id="quarters-category" name="quarters_category" class="form-select" value="{{ old('quarters_category') }}" required
                                    data-parsley-required-message="{{ setMessage('quarters_category.required') }}" data-parsley-errors-container="#parsley-errors-quarters-category">
                                    <option value=''> -- Kategori Kuarters (Lokasi) -- </option>
                                </select>
                                <div id="parsley-errors-quarters-category"></div>
                            </div>

                            <div class="col-md-3 p-1 mb-3" hidden>
                                <label class="col-form-label ">Taraf Perkhidmatan</label>
                                <select id="services_type" name="services_type" class="form-select" value="{{ old('services_type') }}"
                                    data-parsley-required-message="{{ setMessage('services_type.required') }}" data-parsley-errors-container="#parsley-errors-services-type">
                                    <option value=''> -- Pilih Taraf Perkhidmatan -- </option>
                                    @foreach ($servicesTypeAll as $servicesType)
                                        <option value="{{ $servicesType->id }}" {{ $servicesType->id == old('services_type', $selectedServicesType?->id) ? 'selected' : '' }}>
                                            {{ $servicesType->services_type }}
                                        </option>
                                    @endforeach
                                </select>
                                <div id="parsley-errors-services-type"></div>
                            </div>

                            <div class="col-md-3 p-1 mb-3" hidden>
                                <label class="col-form-label ">Jenis Sewaan</label>
                                <select id="account_type" name="account_type" class="form-select" value="{{ old('account_type') }}"
                                    data-parsley-required-message="{{ setMessage('account_type.required') }}" data-parsley-errors-container="#parsley-errors-account-type">
                                    <option value=''> -- Pilih Jenis Sewaan -- </option>
                                    @foreach ($accountTypeAll as $accountType)
                                        <option value="{{ $accountType->id }}" {{ $accountType->id == old('account_type', $selectedAccountType) ? 'selected' : '' }}>
                                            {{ $accountType->account_type }}
                                        </option>
                                    @endforeach
                                </select>
                                <div id="parsley-errors-account-type"></div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12 p-1 mb-3" hidden>
                                <label class="col-md-3 col-form-label ">Agensi</label>
                                <div class="col-md-6">
                                    <select id="agency" name="agency" class="form-select" value="{{ old('agency') }}"
                                        data-parsley-required-message="{{ setMessage('agency.required') }}" data-parsley-errors-container="#parsley-errors-agency">
                                        <option value=''> -- Pilih Agensi -- </option>
                                        @foreach ($agencyAll as $agency)
                                            <option value="{{ $agency->id }}" {{ $agency->id == old('agency', $selectedAgency) ? 'selected' : '' }}>
                                                {{ $agency->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div id="parsley-errors-agency"></div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-3 p-1 mb-3 select-container" hidden>
                                <label class="col-form-label ">Status Kuarters</label>
                                <select id="condition" name="condition" class="form-select" data-parsley-required-message="{{ setMessage('condition.required') }}"
                                    data-parsley-errors-container="#parsley-errors-condition">
                                    <option value=""> -- Pilih Status Kuarters -- </option>
                                    @foreach ($conditionAll as $condition)
                                        <option value="{{ $condition->name }}" {{ $condition->name == old('condition', $selectedCondition) ? 'selected' : '' }}>
                                            {{ $condition->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <div id="parsley-errors-condition"></div>
                            </div>

                            <div class="col-md-3 p-1 mb-3 select-container" hidden>
                                <label class="col-form-label ">Kekosongan</label>
                                <select id="vacancy" name="vacancy" class="form-select" value="{{ old('vacancy') }}" disabled
                                    data-parsley-required-message="{{ setMessage('vacancy.required') }}" data-parsley-errors-container="#parsley-errors-vacancy">
                                    <option value=''> -- Pilih Kekosongan -- </option>
                                    @foreach ($vacancyAll as $vacancy)
                                        <option value="{{ $vacancy->name }}" {{ $vacancy->name == old('vacancy', $selectedVacancy) ? 'selected' : '' }}>
                                            {{ $vacancy->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <div id="parsley-errors-vacancy"></div>
                            </div>

                            <div class="col-md-3 p-1 mb-3 select-container" hidden>
                                <label class="col-form-label ">Tawaran</label>
                                <select id="eligibility" name="eligibility" class="form-select" value="{{ old('eligibility') }}"
                                    data-parsley-required-message="{{ setMessage('eligibility.required') }}" data-parsley-errors-container="#parsley-errors-eligibility">
                                    <option value=''> -- Pilih Tawaran -- </option>
                                    @foreach ($eligibilityAll as $eligibility)
                                        <option value="{{ $eligibility->name }}" {{ $eligibility->name == old('eligibility', $selectedEligibility) ? 'selected' : '' }}>
                                            {{ $eligibility->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <div id="parsley-errors-eligibility"></div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-3 p-1 mb-3" hidden>
                                <label class="col-form-label ">Status Pemohon</label>
                                <select id="applicant-status" name="applicant_status" class="form-select" value="{{ old('applicant_status') }}"
                                    data-parsley-required-message="{{ setMessage('applicant_status.required') }}" data-parsley-errors-container="#parsley-errors-applicant-status">
                                    <option value=''> -- Pilih Status Pemohon -- </option>
                                    @foreach ($applicantStatusAll as $applicantStatus)
                                        <option value="{{ $applicantStatus->id }}" {{ $applicantStatus->id == old('applicant_status', $selectedStatus) ? 'selected' : '' }}>
                                            {{ $applicantStatus->status }}</option>
                                    @endforeach
                                </select>
                                <div id="parsley-errors-applicant-status"></div>
                            </div>
                            <div class="col-md-3 p-1 mb-3" hidden>
                                <label class="col-form-label ">Status Tawaran</label>
                                <select id="offer-status" name="offer_status" class="form-select" value="{{ old('offer_status') }}"
                                    data-parsley-required-message="{{ setMessage('offer_status.required') }}" data-parsley-errors-container="#parsley-errors-offer-status">
                                    <option value=''> -- Pilih Status Tawaran -- </option>
                                    @foreach ($offerStatusAll as $offerStatus)
                                        <option value="{{ $offerStatus->id }}" {{ $offerStatus->id == old('offer_status', $selectedOfferStatus) ? 'selected' : '' }}>
                                            {{ $offerStatus->status }}</option>
                                    @endforeach
                                </select>
                                <div id="parsley-errors-offer-status"></div>
                            </div>
                            <div class="col-md-3 p-1 mb-3" hidden>
                                <label class="col-form-label ">Status Penghuni</label>
                                <select id="tenant-status" name="tenant_status" class="form-select" value="{{ old('tenant_status') }}"
                                    data-parsley-required-message="{{ setMessage('tenant_status.required') }}" data-parsley-errors-container="#parsley-errors-tenant-status">
                                    <option value=''> -- Pilih Status Penghuni -- </option>
                                    @foreach ($tenantStatusAll as $tenantStatus)
                                        <option value="{{ $tenantStatus->id }}" {{ $tenantStatus->id == old('tenant_status', $selectedTenantStatus) ? 'selected' : '' }}>
                                            {{ $tenantStatus->status }}
                                        </option>
                                    @endforeach
                                </select>
                                <div id="parsley-errors-tenant-status"></div>
                            </div>
                            <div class="col-md-3 p-1 mb-3" hidden>
                                <label class="col-form-label ">Keputusan Mesyuarat</label>
                                <select id="meeting-result" name="meeting_result" class="form-select" value="{{ old('meeting_result') }}"
                                    data-parsley-required-message="{{ setMessage('meeting_result.required') }}" data-parsley-errors-container="#parsley-errors-meeting-result">
                                    <option value=''> -- Pilih Keputusan Mesuarat -- </option>
                                    <option value='99' @if(old('meeting_result', $selectedMeetingResult) == 99) selected @endif> Tangguh </option>
                                    <option value='7' @if(old('meeting_result', $selectedMeetingResult) == 7) selected @endif> Lulus </option>
                                    <option value='8' @if(old('meeting_result', $selectedMeetingResult) == 8) selected @endif> Gagal </option>
                                    <option value='9' @if(old('meeting_result', $selectedMeetingResult) == 9) selected @endif> Rayuan Semula </option>
                                </select>
                                <div id="parsley-errors-meeting-result"></div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-3 p-1 mb-3" hidden>
                                <label class="col-form-label ">Tahun</label>
                                <select id="year" name="year" class="form-select" value="{{ old('year') }}" required
                                    data-parsley-required-message="{{ setMessage('year.required') }}" data-parsley-errors-container="#parsley-errors-year">
                                    <option value="">-- Pilih Tahun --</option>
                                    @foreach ($yearDBAll as $yearDB)
                                        <option value="{{ $yearDB->tahun }}" @if ($yearDB->tahun == old('year', $selectedYear)) selected @endif>{{ $yearDB->tahun }}</option>
                                    @endforeach
                                </select>
                                <div id="parsley-errors-year"></div>
                            </div>
                            <div class="col-md-3 p-1 mb-3" hidden>
                                <label class="col-form-label ">Bulan</label>
                                <select id="month" name="month" class="form-select" value="{{ old('month') }}" required
                                    data-parsley-required-message="{{ setMessage('month.required') }}" data-parsley-errors-container="#parsley-errors-month">
                                    <option value=''> -- Pilih Bulan -- </option>
                                    @foreach ($months as $month)
                                        <option value="{{ $month->month }}" {{ $month->month == old('month', $selectedMonth) ? 'selected' : '' }}>{{ $month->bm }}</option>
                                    @endforeach
                                </select>
                                <div id="parsley-errors-month"></div>
                            </div>
                            <div class="col-md-3 p-1 mb-3" hidden>
                                <label for="date_from" class="col-form-label">Tarikh Dari</label>
                                <div>
                                    <div class="input-group" id="datepicker2">
                                        <input class="form-control @error('date_from') is-invalid @enderror" type="text" placeholder="dd/mm/yyyy" name="date_from"
                                            id="date-from" data-date-format="dd/mm/yyyy" data-date-container='#datepicker2' data-provide="datepicker" autocomplete="off"
                                            data-date-orientation="top" data-date-autoclose="true" required data-parsley-required-message="{{ setMessage('date_from.required') }}"
                                            data-parsley-errors-container="#parsley-errors-from" value="{{ convertDateSys($from) ?? '' }}">
                                        <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
                                        @error('date_from')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                                <div id="parsley-errors-from"></div>
                            </div>
                            <div class="col-md-3 p-1 mb-3" hidden>
                                <label for="date_to" class="col-form-label">Tarikh Hingga</label>
                                <div class="input-group" id="datepicker2">
                                    <input class="form-control @error('date_to') is-invalid @enderror" type="text" placeholder="dd/mm/yyyy" name="date_to" id="date-to"
                                        data-date-format="dd/mm/yyyy" data-date-container='#datepicker2' data-provide="datepicker" autocomplete="off" data-date-orientation="top"
                                        data-date-autoclose="true" required data-parsley-required-message="{{ setMessage('date_to.required') }}"
                                        data-parsley-errors-container="#parsley-errors-to" value="{{ convertDateSys($to) ?? '' }}">
                                    <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
                                    @error('date_to')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div id="parsley-errors-to"></div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-3 p-1 mb-3" hidden>
                                <label for="ic" class="col-form-label">No Kad Pengenalan</label>
                                <input class="form-control input-mask" type="text" name="ic" id="ic"
                                    data-parsley-required-message="{{ setMessage('ic.required') }}" value="{{ old('ic', $ic) }}" data-inputmask="'mask':'999999-99-9999'"
                                    data-parsley-report-penama-ditawarkan data-parsley-report-penama-ditawarkan-message="{{ setMessage('ic.required') }}"
                                    data-parsley-report-senarai-permohonan data-parsley-report-senarai-permohonan-message="{{ setMessage('ic.required') }}"
                                    data-parsley-report-penghuni data-parsley-report-penghuni-message="{{ setMessage('ic.required') }}"
                                    data-parsley-report-pemarkahan-ic data-parsley-report-pemarkahan-ic-message="{{ setMessage('ic.required') }}"
                                    data-parsley-report-sejarah-pemohon data-parsley-report-sejarah-pemohon-message="{{ setMessage('ic.required') }}"
                                    data-parsley-report-sejarah-penghuni data-parsley-report-sejarah-penghuni-message="{{ setMessage('ic.required') }}"
                                    data-parsley-report-bayaran-penghuni data-parsley-report-bayaran-penghuni-message="{{ setMessage('ic.required') }}"
                                    data-parsley-report-bayaran-terperinci-penghuni data-parsley-report-bayaran-terperinci-penghuni-message="{{ setMessage('ic.required') }}"
                                    data-parsley-report-hilang-kelayakan-penghuni data-parsley-report-hilang-kelayakan-penghuni-message="{{ setMessage('ic.required') }}"
                                    data-parsley-report-penghuni-dijangka-bersara data-parsley-report-penghuni-dijangka-bersara-message="{{ setMessage('ic.required') }}"
                                    data-parsley-report-mesyuarat data-parsley-report-mesyuarat-message="{{ setMessage('ic.required') }}"
                                    data-parsley-validate-if-empty>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12 p-1 mb-3">
                                <button class="btn btn-primary" type="submit" onClick="setFormTarget()">{{ __('button.cari') }}</button>
                                {{-- <button name="reset" id="reset" class="btn btn-primary"  onClick ="clearSearchInput()">{{ __('button.reset') }}</button> --}}
                                <a id="reset" class="btn btn-primary" href="{{ route('dynamicReport.index') }}" value="reset">Set Semula</a>
                                {{-- <a name="muat_turun_pdf" class="btn btn-success" type="submit" value="pdf" id="download-pdf">Muat Turun PDF</a> --}}
                                {{-- <a name="muat_turun_pdf" class="btn btn-success" type="submit" value="pdf" href="{{ route('dynamicReport.generatePDF') }}">Muat Turun PDF</a> --}}
                                <button name="muat_turun_pdf" id="muat_turun_pdf" class="btn btn-success" type="submit" value="pdf"  onClick="setFormTarget(this)">{{ __('button.muat_turun_pdf') }}</button>
                            </div>
                        </div>
                    </div>

                </form>
            </div>
            <!-- end section - search  -->

            @if ($reportData)
                <div class="card">
                    <div class="card-body overflow-auto">
                        {{-- PAGE TITLE --}}
                        <div class="border-bottom border-primary mb-4">
                            <h4 class="card-title" id="title-laporan">{{ $title ?? '' }}</h4>
                        </div>
                        @if (in_array($report->id, [1]))
                            @include('modules.DynamicReport.DynamicReport.table_report_kuarters')
                        @endif
                        @if (in_array($report->id, [4, 6]))
                            @include('modules.DynamicReport.DynamicReport.table_report_pemohon')
                        @endif
                        @if (in_array($report->id, [5]))
                            @include('modules.DynamicReport.DynamicReport.table_report_pemohon_ditawarkan')
                        @endif
                        @if (in_array($report->id, [10]))
                            @include('modules.DynamicReport.DynamicReport.table_report_pemohon_skor')
                        @endif
                        @if (in_array($report->id, [11]))
                            @include('modules.DynamicReport.DynamicReport.table_report_pemohon_sejarah')
                        @endif
                        @if (in_array($report->id, [8]))
                            @include('modules.DynamicReport.DynamicReport.table_report_penghuni')
                        @endif
                        @if (in_array($report->id, [12]))
                            @include('modules.DynamicReport.DynamicReport.table_report_penghuni_sejarah')
                        @endif
                        @if (in_array($report->id, [13]))
                            @include('modules.DynamicReport.DynamicReport.table_report_penghuni_bayaran')
                        @endif
                        @if (in_array($report->id, [14]))
                            @include('modules.DynamicReport.DynamicReport.table_report_penghuni_hilang_kelayakan')
                        @endif
                        @if (in_array($report->id, [15]))
                            @include('modules.DynamicReport.DynamicReport.table_report_penghuni_dijangka_bersara')
                        @endif
                        @if (in_array($report->id, [16]))
                            @include('modules.DynamicReport.DynamicReport.table_report_pemohon_mesyuarat')
                        @endif
                        @if (in_array($report->id, [17]))
                            @include('modules.DynamicReport.DynamicReport.table_report_penghuni_bayaran_terperinci')
                        @endif
                    </div>
                </div>
            @endif

            <div id="jata" class="d-none" data-logo-url="{{ URL::asset('assets/images/jata-johor.png') }}"></div>
            <div id="url-report" class="d-none" data-url="{{ route('dynamicReport.ajaxGetReport') }}"></div>
            <div id="url-categories" class="d-none" data-url="{{ route('dynamicReport.ajaxGetQuartersCategory') }}"></div>
            <div id="category-data" class="d-none" data-data="{{ $quartersCategoryGrouped }}"></div>

        </div> <!-- end col -->

    </div>
    <!-- end row -->

@endsection

@section('script')
    <script src="{{ URL::asset('assets/js/pages/DynamicReport/dynamicReport.js') }}"></script>
@endsection
