@extends('layouts.master')

@section('content')

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">

                {{-- PAGE TITLE --}}
                <div class="border-bottom border-primary mb-4"><h4 class="card-title">{{ getPageTitle(1) }}</h4></div>

                <form  class="custom-validation" id="form" method="post" action="{{ route('incomeAccountCode.update') }}" >
                    {{ csrf_field() }}

                    <input class="form-control" type="hidden" id="id" name="id" value="{{ $incomeAccountCode->id}}">

                    <div class="mb-3 row">
                        <label for="salary_deduction_code" class="col-md-2 col-form-label">Kod Potongan Gaji</label>
                        <div class="col-md-10">
                            <input class="form-control @error('salary_deduction_code') is-invalid @enderror" type="text" name="salary_deduction_code" value="{{ old('salary_deduction_code', $incomeAccountCode->salary_deduction_code) }}" required data-parsley-required-message="{{ setMessage('salary_deduction_code.required') }}">
                            @error('salary_deduction_code')
                            <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="general_income_code" class="col-md-2 col-form-label">Kod Am Hasil</label>
                        <div class="col-md-10">
                            <input class="form-control @error('general_income_code') is-invalid @enderror" type="text" name="general_income_code" value="{{ old('general_income_code', $incomeAccountCode->general_income_code) }}" required data-parsley-required-message="{{ setMessage('general_income_code.required') }}" >
                            @error('general_income_code')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="ispeks_account_code" class="col-md-2 col-form-label">Kod Akaun iSPEKS</label>
                        <div class="col-md-10">
                            <input class="form-control @error('ispeks_account_code') is-invalid @enderror" type="text" name="ispeks_account_code" value="{{ old('ispeks_account_code', $incomeAccountCode->ispeks_account_code) }}" required data-parsley-required-message="{{ setMessage('ispeks_account_code.required') }}">
                            @error('ispeks_account_code')
                            <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="ispeks_account_description" class="col-md-2 col-form-label">Butiran Kod Akaun iSPEKS</label>
                        <div class="col-md-10">
                            <input class="form-control @error('ispeks_account_description') is-invalid @enderror" type="text" name="ispeks_account_description" value="{{ old('ispeks_account_description', $incomeAccountCode->ispeks_account_description) }}" required data-parsley-required-message="{{ setMessage('ispeks_account_description.required') }}">
                            @error('ispeks_account_description')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="income_code" class="col-md-2 col-form-label">Kod Hasil</label>
                        <div class="col-md-10">
                            <input class="form-control @error('income_code') is-invalid @enderror" type="text" name="income_code" value="{{ old('income_code', $incomeAccountCode->income_code) }}" required data-parsley-required-message="{{ setMessage('income_code.required') }}">
                            @error('income_code')
                            <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="income_code_description" class="col-md-2 col-form-label">Butiran Kod Hasil</label>
                        <div class="col-md-10">
                            <input class="form-control @error('income_code_description') is-invalid @enderror" type="text" name="income_code_description" value="{{ old('income_code_description', $incomeAccountCode->income_code_description) }}" required data-parsley-required-message="{{ setMessage('income_code_description.required') }}">
                            @error('income_code_description')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="account_type" class="col-md-2 col-form-label">Jenis Akaun</label>
                        <div class="col-md-10">
                            <select class="form-select @error('account_type') is-invalid @enderror" name="account_type" required data-parsley-required-message="{{ setMessage('account_type.required') }}">
                                <option value="">-- Pilih Jenis Akaun --</option>
                                @foreach($accountTypeAll as $accountType)
                                    <option value="{{$accountType->id }}" {{ old('account_type', $incomeAccountCode->account_type_id) == $accountType->id ? "selected" : "" }} >{{$accountType->account_type}}</option>
                                @endforeach
                            </select>
                            @error('account_type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="payment_category" class="col-md-2 col-form-label">Kategori Bayaran</label>
                        <div class="col-md-10">
                            <select class="form-select @error('payment_category') is-invalid @enderror" name="payment_category"
                                required data-parsley-required-message="{{ setMessage('payment_category.required') }}"
                                data-parsley-errors-container="#parsley-payment-category">
                                <option value="">-- Pilih Kategori Bayaran --</option>
                                @foreach($paymentCategoryAll as $category)
                                    <option value="{{$category->id }} " {{ old('payment_category', $incomeAccountCode->payment_category_id) == $category->id ? "selected" : "" }} >{{$category->payment_category}}</option>
                                @endforeach
                            </select>
                            <div class="row">
                                <div class="col-md-10 mt-1" id="parsley-payment-category"></div>
                            </div>
                            @error('officer')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="services_status" class="col-md-2 col-form-label">Status Perkhimatan</label>
                        <div class="col-md-10">
                            @foreach($servicesStatusAll as $i => $servicesStatus)
                                <span class="form-check d-flex  @error('services_status') is-invalid @enderror">
                                    <input class="services_status form-check-input me-2" type="checkbox"
                                        id="formCheck_{{ $servicesStatus->id }}" name="services_status[]" value="{{ old('services_status.'.$i, $servicesStatus->id) }}"
                                        {{ inArray(old('flag_pensioner', $incomeAccountCode->services_status_id), $servicesStatus->id) ? "checked" : "" }}
                                        required data-parsley-mincheck="1"
                                        data-parsley-required-message="{{ setMessage('services_status.required') }}"
                                        data-parsley-mincheck-message="{{ setMessage('services_status.required') }}"
                                        data-parsley-errors-container="#parsley-errors-services-status">
                                    <label class="form-check-label" for="formCheck_{{ $servicesStatus->id }}"></label>
                                    {{$servicesStatus->status_name}}
                                </span>
                            @endforeach
                            <div class="col-md-10 mt-1" id="parsley-errors-services-status"></div>
                        </div>
                    </div>
                    {{-- <div class="mb-3 row">
                        <label for="flag_pensioner" class="col-md-2 col-form-label">Termasuk Pesara ?</label>
                        <div class="col-md-1">
                            <div class="form-check @error('flag_pensioner') is-invalid @enderror">
                                <input class="flag_pensioner form-check-input me-2" type="checkbox" id="formCheck" name="flag_pensioner"
                                value="1" {{ old('flag_pensioner', $incomeAccountCode->flag_pensioner) == 1 ? "checked" : "" }} >
                                <label class="form-check-label" for="formCheck"></label>
                            </div>
                        </div>
                    </div> --}}
                    <div class="mb-3 row">
                        <label for="flag_outstanding" class="col-md-2 col-form-label">Akaun Tunggakan ?</label>
                        <div class="col-md-1">
                            <div class="form-check @error('flag_outstanding') is-invalid @enderror">
                                <input class="flag_outstanding form-check-input me-2" type="checkbox" id="formCheck" name="flag_outstanding" value="2" {{ old('flag_outstanding', $incomeAccountCode->flag_outstanding) == 2 ? "checked" : "" }}>
                                <label class="form-check-label" for="formCheck">YA</label>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <div class="col-sm-12">
                            <button type="submit" class="btn btn-primary float-end swal-kemaskini">{{ __('button.kemaskini') }}</button>
                            <a href="{{ route('incomeAccountCode.index') }}" class="btn btn-secondary float-end me-2">{{ __('button.kembali') }}</a>
                        </div>
                    </div>
                </form>

                <form method="POST" action="{{ route('incomeAccountCode.delete') }}" id="delete-form">
                    {{ csrf_field() }}
                    {{ method_field('DELETE') }}
                    <input class="form-control" type="hidden" id="id-delete" name="id" value="{{ $incomeAccountCode->id }}">
                </form>

            </div>
        </div>
    </div> <!-- end col -->
</div>
<!-- end row -->

@endsection


