@extends('layouts.master')

@section('content')

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">

                {{-- PAGE TITLE --}}
                <div class="border-bottom border-primary mb-4"><h4 class="card-title">{{ getPageTitle(1) }}</h4></div>

                <form class="custom-validation" id="form" method="post" action="{{ route('paymentMethod.store') }}" >
                    {{ csrf_field() }}

                    <div class="mb-3 row">
                        <label for="payment_method_code" class="col-md-2 col-form-label">Kod Kaedah Bayaran</label>
                        <div class="col-md-10">
                            <input class="form-control @error('payment_method_code') is-invalid @enderror" type="text" name="payment_method_code" value="{{ old('payment_method_code', '') }}" required data-parsley-required-message="Sila masukkan kod kaedah bayaran.">
                            @error('payment_method_code')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="payment_method" class="col-md-2 col-form-label">Kaedah Bayaran</label>
                        <div class="col-md-10">
                            <input class="form-control @error('payment_method') is-invalid @enderror" type="text" name="payment_method" value="{{ old('payment_method', '') }}" required data-parsley-required-message="Sila masukkan kaedah bayaran.">
                            @error('payment_method')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="payment_category_id" class="col-md-2 col-form-label">Kategori Bayaran</label>
                        <div class="col-md-10">
                            <select class="form-select @error('payment_category_id') is-invalid @enderror" name="payment_category_id" required data-parsley-required-message="Sila buat pilihan Kategori bayaran.">
                                <option value="">-- Sila Pilih --</option>
                                @foreach($paymentCategoryCode as $code1)
                                    <option value="{{ $code1->id }}"
                                            {{ old('payment_category_id') == $code1->id ? 'selected' : '' }}>
                                        {{ $code1->payment_category }}
                                    </option>
                                @endforeach
                            </select>
                            @error('payment_category_id')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="mb-3 row">
                        <label for="ispeks_payment_code_id" class="col-md-2 col-form-label">Kaedah Bayaran iSPEKS</label>
                        <div class="col-md-10">
                            <select class="form-select @error('ispeks_payment_code_id') is-invalid @enderror" name="ispeks_payment_code_id" required data-parsley-required-message="Sila buat pilihan kaedah bayaran.">
                                <option value="">-- Sila Pilih --</option>
                                @foreach($ispeksPaymentCodes as $code)
                                    <option value="{{ $code->id }}"
                                            {{ old('ispeks_payment_code_id') == $code->id ? 'selected' : '' }}>
                                        {{ $code->description }}
                                    </option>
                                @endforeach
                            </select>
                            @error('ispeks_payment_code_id')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
  
                    <div class="mb-3 row">
                        <div class="col-sm-12">
                            <button type="submit" class="btn btn-primary float-end swal-tambah">{{ __('button.simpan') }}</button>
                            <a href="{{ route('paymentMethod.index') }}" class="btn btn-secondary float-end me-2">{{ __('button.kembali') }}</a>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div> <!-- end col -->
</div>
<!-- end row -->

@endsection
