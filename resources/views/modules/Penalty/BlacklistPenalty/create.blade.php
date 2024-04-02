@extends('layouts.master')
@section('css')
    <link href="{{ URL::asset('/assets/libs/bootstrap-datepicker/bootstrap-datepicker.min.css') }}" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="{{ URL::asset('/assets/libs/datepicker/datepicker.min.css') }}">
@endsection
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">

                    {{-- PAGE TITLE --}}
                    <div class="border-bottom border-primary mb-4">
                        <h4 class="card-title">{{ getPageTitle(2) }}</h4>
                    </div>

                    <form class="custom-validation" id="form" method="post" action="{{ route('blacklistPenalty.store') }}">
                        {{ csrf_field() }}
                        <input type="hidden" name="q_category_id" id="q_category_id" value="{{ $category->id }}">

                        <div class="mb-3 row">
                            <label for="ic_numb" class="col-md-2 col-form-label">No. Kad Pengenalan</label>
                            <div class="col-md-10">
                                <input class="form-control input-mask numeric @error('tid') is-invalid @enderror" type="text" name="tid" id="tid" value="{{ old('tid') }}"
                                oninput="checkNumber(this)"
                                minlength="12" maxlength="12"
                                data-parsley-length-message="{{ setMessage('tid.digits') }}"
                                required
                                data-parsley-required-message="{{ setMessage('tid.required') }}"
                                data-route-tenant="{{ route('blacklistPenalty.ajaxCheckTenantIC') }}"
                                data-parsley-errors-container="#errors-ic-numb"
                                >
                                <div class="col-md-10 mt-1" id="ic-error"></div>
                                <div class="col-md-10 mt-1" id="errors-ic-numb"></div>
                                @error('tid')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="tenant_name" class="col-md-2 col-form-label">Nama Penghuni</label>
                            <div class="col-md-10">
                                <input class="form-control" type="hidden" name="tenant_id" id="tenant_id" readonly>
                                <input class="form-control" name="tenant_name" id="tenant_name" readonly>
                                <div class="spinner-wrapper"></div>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="phone_numb" class="col-md-2 col-form-label">No. Telefon</label>
                            <div class="col-md-10">
                                <input class="form-control" name="phone_numb" id="phone_numb" readonly>
                                <div class="spinner-wrapper"></div>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="quarters_cat" class="col-md-2 col-form-label">Kategori Kuarters (Lokasi)</label>
                            <div class="col-md-10">
                                <input class="form-control" name="quarters_cat" id="quarters_cat" readonly value="{{ $category->name }}">
                                <div class="spinner-wrapper"></div>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="blacklist_date" class="col-md-2 col-form-label">Tarikh Hilang Kelayakan</label>
                            <div class="col-md-10">
                                <div class="input-group "id="datepicker2">
                                    <input class="form-control @error('blacklist_date') is-invalid @enderror" type="text" placeholder="dd/mm/yyyy" name="blacklist_date"
                                        id="blacklist_date" data-date-format="dd/mm/yyyy" data-date-container='#datepicker2' data-provide="datepicker" autocomplete="off"
                                        data-date-autoclose="true" required data-parsley-required-message="{{ setMessage('blacklist_date.required') }}"
                                        data-parsley-errors-container="#error-blacklist-date" value="{{ old('blacklist_date') }}">
                                    <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
                                    @error('blacklist_date')
                                        <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div id="error-blacklist-date"></div>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label class="col-md-2 col-form-label">Sebab Hilang Kelayakan</label>
                            <div class="col-md-10">
                                @foreach ($blacklistReasonAll as $reason)
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="reason" id="reason{{ $loop->iteration }}" value="{{ $reason->id }}" required
                                            @if (old('reason') == $reason->id) checked="" @endif data-parsley-required-message="{{ setMessage('reason.required') }}">
                                        <label class="form-check-label" for="reason{{ $loop->iteration }}">
                                            {{ $reason->blacklist_reason }}
                                        </label>
                                    </div>
                                @endforeach
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="reason" id="reason9999" value="9999" required
                                        @if (old('reason') == 9999) checked="" @endif data-parsley-required-message="{{ setMessage('reason.required') }}">
                                    <label class="form-check-label" for="reason9999">
                                        LAIN-LAIN
                                    </label>
                                </div>
                                @error('reason')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-10 offset-md-2 mt-2">
                                <textarea class="form-control" name="other_reason" id="other_reason" rows="3" disabled data-parsley-required-message="{{ setMessage('reason.required') }}"></textarea>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="meeting_remarks" class="col-md-2 col-form-label">Keputusan Mesyuarat</label>
                            <div class="col-md-10">
                                <textarea class="form-control" name="meeting_remarks" id="meeting_remarks" required data-parsley-required-message="{{ setMessage('meeting_remarks.required') }}"></textarea>
                                <div class="spinner-wrapper"></div>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="penalty_date" class="col-md-2 col-form-label">Tarikh Kuatkuasa</label>
                            <div class="col-md-10">
                                <div class="input-group "id="datepicker2">
                                    <input class="form-control @error('penalty_date') is-invalid @enderror" type="text" placeholder="dd/mm/yyyy" name="penalty_date"
                                        id="penalty_date" data-date-format="dd/mm/yyyy" data-date-container='#datepicker2' data-provide="datepicker" autocomplete="off"
                                        data-date-autoclose="true" required data-parsley-required-message="{{ setMessage('penalty_date.required') }}"
                                        data-parsley-errors-container="#error-penalty-date" value="{{ old('penalty_date') }}">
                                    <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
                                    @error('penalty_date')
                                        <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div id="error-penalty-date"></div>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="mv" class="col-md-2 col-form-label">Harga Pasaran Semasa Rumah</label>
                            <div class="col-md-10">
                                <input class="form-control" type="text" id="mv" name="mv" readonly>
                            </div>
                        </div>
                        {{-- <div class="mb-3 row">
                            <label for="ic_numb" class="col-md-2 col-form-label">Kadar Denda (%)</label>
                            <div class="col-md-10">
                                <input class="form-control @error('rate') is-invalid @enderror" type="text" name="rate" id="rate" readonly required
                                    data-parsley-required-message="{{ setMessage('rate.required') }}" data-parsley-errors-container="#error-rate">
                                @error('rate')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="payment_amount" class="col-md-2 col-form-label">Amaun Denda (RM)</label>
                            <div class="col-md-10">
                                <input class="form-control @error('penalty_amount') is-invalid @enderror" type="text" name="penalty_amount" oninput="checkNumber(this)"
                                    onfocus="focusInput(this);" value="{{ old('penalty_amount', '') }}" readonly>
                                @error('penalty_amount')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div> --}}
                        <div class="mb-3 row">
                            <div class="col-sm-12">
                                <button type="submit" id="btn-submit" class="btn btn-primary float-end swal-tambah">{{ __('button.simpan') }}</button>
                                <a href="{{ route('blacklistPenalty.penaltyList', $category) }}" class="btn btn-secondary float-end me-2">{{ __('button.kembali') }}</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div> <!-- end col -->
    </div>
    <!-- end row -->

    <div id="get-rate" data-route="{{ route('blacklistPenalty.ajaxGetRate') }}"></div>
@endsection

@section('script')
    <script src="{{ URL::asset('assets/libs/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ URL::asset('assets/js/pages/BlacklistPenalty/blacklist-penalty.js') }}"></script>
@endsection
