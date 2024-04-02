@extends('layouts.master')

@section('content')

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">

                {{-- PAGE TITLE --}}
                <div class="border-bottom border-primary mb-4"><h4 class="card-title">{{ getPageTitle(1) }}</h4></div>

                <form  class="custom-validation" id="form" method="post" action="{{ route('paymentMethod.update') }}" >
                    {{ csrf_field() }}

                    <input class="form-control" type="hidden" id="id" name="id" value="{{ $paymentMethod->id}}">

                    <div class="mb-3 row">
                        <label for="payment_method_code" class="col-md-2 col-form-label">Kod Kaedah Bayaran</label>
                        <div class="col-md-10">
                            <input class="form-control @error('payment_method_code') is-invalid @enderror" type="text" name="payment_method_code" value="{{ old('payment_method_code', $paymentMethod->payment_method_code) }}" required data-parsley-required-message="{{ setMessage('payment_method_code.required') }}">
                            @error('payment_method_code')
                            <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="payment_method" class="col-md-2 col-form-label">Kaedah Bayaran</label>
                        <div class="col-md-10">
                            <input class="form-control @error('payment_method') is-invalid @enderror" type="text" name="payment_method" value="{{ old('payment_method', $paymentMethod->payment_method) }}" required data-parsley-required-message="{{ setMessage('payment_method.required') }}">
                            @error('payment_method')
                            <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="payment_category_id" class="col-md-2 col-form-label">Kategori Bayaran</label>
                        <div class="col-md-10">
                            <select class="form-select @error('payment_category_id') is-invalid @enderror"
                                    name="payment_category_id" required>
                                <option value="">-- Sila Pilih --</option>
                                @foreach($paymentCategoryCode as $category)
                                    <option value="{{ $category->id }}"
                                            {{ old('payment_category_id', $paymentMethod->payment_category_id) == $category->id ? 'selected' : '' }}>
                                        {{ $category->payment_category }}
                                    </option>
                                @endforeach
                            </select>
                            @error('payment_category_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="mb-3 row">
                        <label for="ispeks_payment_code_id" class="col-md-2 col-form-label">Kaedah Bayaran iSPEKS</label>
                        <div class="col-md-10">
                            <select class="form-select @error('ispeks_payment_code_id') is-invalid @enderror"
                                    name="ispeks_payment_code_id" required>
                                <option value="">-- Sila Pilih --</option>
                                @foreach($ispeksPaymentCodes as $code)
                                    <option value="{{ $code->id }}"
                                            {{ old('ispeks_payment_code_id', $paymentMethod->ispeks_payment_code_id) == $code->id ? 'selected' : '' }}>
                                        {{ $code->description }}
                                    </option>
                                @endforeach
                            </select>
                            @error('ispeks_payment_code_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>                    
                    
                    <div class="mb-3 row">
                        <div class="col-sm-12">
                            <button type="submit" class="btn btn-primary float-end swal-kemaskini">{{ __('button.kemaskini') }}</button>
                            <a href="{{ route('paymentMethod.index') }}" class="btn btn-secondary float-end me-2">{{ __('button.kembali') }}</a>
                        </div>
                    </div>
                </form>

                <form method="POST" action="{{ route('paymentMethod.delete') }}" id="delete-form">
                    {{ csrf_field() }}
                    {{ method_field('DELETE') }}
                    <input class="form-control" type="hidden" id="id-delete" name="id" value="{{ $paymentMethod->id }}">
                </form>

            </div>
        </div>
    </div> <!-- end col -->
</div>
<!-- end row -->

@endsection


