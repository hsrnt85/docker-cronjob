@extends('layouts.master')

@section('content')

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">

                {{-- PAGE TITLE --}}
                <div class="border-bottom border-primary mb-4"><h4 class="card-title">{{ getPageTitle(1) }}</h4></div>

                <form  class="custom-validation" id="form" method="post" action="{{ route('bankAccount.update') }}" >
                    {{ csrf_field() }}

                    <input class="form-control" type="hidden" id="id" name="id" value="{{ $bankAccount->id}}">      
                    
                    <div class="mb-3 row">
                        <label for="bank_id" class="col-md-2 col-form-label">Nama Bank</label>
                        <div class="col-md-10">
                            <select id="bank_id" class="form-control select2 @error('bank_id') is-invalid @enderror" name="bank_id" required data-parsley-required-message="Sila pilih nama bank.">
                                <option value="">-- Sila Pilih --</option>
                                @foreach($bankName as $bank)
                                    <option value="{{ $bank->id }}" {{ $bankAccount->bank_id == $bank->id ? 'selected' : '' }}>
                                        {{ $bank->bank_name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('bank_id')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="account_no" class="col-md-2 col-form-label">No. Akaun</label>
                        <div class="col-md-10">
                            <input class="form-control @error('account_no') is-invalid @enderror" type="text" name="account_no" value="{{ old('account_no', $bankAccount->account_no) }}" required data-parsley-required-message="{{ setMessage('account_no.required') }}">
                            @error('account_no')
                            <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="account_name" class="col-md-2 col-form-label">Nama Akaun</label>
                        <div class="col-md-10">
                            <input class="form-control @error('account_name') is-invalid @enderror" type="text" name="account_name" value="{{ old('account_name', $bankAccount->account_name) }}" required data-parsley-required-message="{{ setMessage('account_name.required') }}">
                            @error('account_name')
                            <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    
                    {{-- <div class="mb-3 row">
                        <label for="payment_method_id" class="col-md-2 col-form-label">Kaedah Bayaran</label>
                        <div class="col-md-10">
                            <select class="form-select @error('payment_method_id') is-invalid @enderror" name="payment_method_id" data-parsley-required-message="Sila pilih kaedah bayaran.">
                                <option value="">-- Sila Pilih --</option>
                                @foreach($paymentMethod as $code)
                                    <option value="{{ $code->id }}" {{ $bankAccount->payment_method_id == $code->id ? 'selected' : '' }}>
                                        {{ $code->payment_method }}
                                    </option>
                                @endforeach
                            </select>
                            @error('payment_method_id')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="payment_category" class="col-md-2 col-form-label">Kategori Bayaran</label>
                        <div class="col-md-10">
                            <select class="form-select @error('payment_category') is-invalid @enderror" name="payment_category" data-parsley-required-message="Sila pilih kategori bayaran.">
                                <option value="">-- Sila Pilih --</option>
                                @foreach($paymentCategory as $code)
                                    <option value="{{ $code->id }}" {{ $bankAccount->payment_category_id == $code->id ? 'selected' : '' }}>
                                        {{ $code->payment_category }}
                                    </option>
                                @endforeach
                            </select>
                            @error('payment_category')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>     --}}

                    <div class="mb-3 row">
                        <label for="payment_method_id" class="col-md-2 col-form-label">Kaedah Bayaran</label>
                        <div class="col-md-10">
                            <select class="form-select @error('payment_method_id') is-invalid @enderror" name="payment_method_id" id="payment_method_id" data-parsley-required-message="{{ setMessage('payment_method_id.required') }}">
                                <option value="">-- Sila Pilih --</option>
                                @foreach($paymentMethod as $code)
                                    <option value="{{ $code->id }}" data-payment-category-id="{{ $code->payment_category_id }}"
                                        {{ old('payment_method_id', $bankAccount->payment_method_id) == $code->id ? 'selected' : '' }}>
                                        {{ $code->payment_method }}
                                    </option>
                                @endforeach
                            </select>
                            @error('payment_method_id')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Loading spinner element -->
                    <div id="loading-spinner" class="spinner-border text-primary" role="status" style="display: none;">
                        <span class="visually-hidden">Loading...</span>
                    </div>

                    <input type="hidden" id="payment-category" data-route="{{ route('bankAccount.getPaymentCategories') }}" />

                    <div class="mb-3 row">
                        <label for="payment_category_id" class="col-md-2 col-form-label">Kategori Bayaran</label>
                        <div class="col-md-10">
                            <input class="form-control @error('payment_category_id') is-invalid @enderror" type="text" name="payment_category_id" id="payment_category_id" value="{{ old('payment_category_id', $paymentCategoryValue) }}" required data-parsley-required-message="Sila masukkan kaedah bayaran." readonly>
                            @error('payment_category_id')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="mb-3 row">
                        <label class="col-md-2 col-form-label">Jenis Akaun Bank</label>
                        <div class="col-md-10">
                            <div class="form-check">
                                <input class="form-check-input @error('bank_account_type') is-invalid @enderror" type="radio" name="bank_account_type" id="bank_account_type_main" value="1" {{ $bankAccount->bank_account_type == '1' ? 'checked' : '' }} required data-parsley-required-message="Sila pilih jenis akaun bank.">
                                <label class="form-check-label" for="bank_account_type_main">Akaun Bank Utama</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input @error('bank_account_type') is-invalid @enderror" type="radio" name="bank_account_type" id="bank_account_type_transit" value="2" {{ $bankAccount->bank_account_type == '2' ? 'checked' : '' }}>
                                <label class="form-check-label" for="bank_account_type_transit">Akaun Bank Transit</label>
                            </div>
                            @error('bank_account_type')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>                    
                    
                    <div class="mb-3 row">
                        <div class="col-sm-12">
                            <button type="submit" class="btn btn-primary float-end swal-kemaskini">{{ __('button.kemaskini') }}</button>
                            <a href="{{ route('bankAccount.index') }}" class="btn btn-secondary float-end me-2">{{ __('button.kembali') }}</a>
                        </div>
                    </div>
                </form>

                <form method="POST" action="{{ route('bankAccount.delete') }}" id="delete-form">
                    {{ csrf_field() }}
                    {{ method_field('DELETE') }}
                    <input class="form-control" type="hidden" id="id-delete" name="id" value="{{ $bankAccount->id }}">
                </form>

            </div>
        </div>
    </div> <!-- end col -->
</div>
<!-- end row -->

@endsection

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="{{ URL::asset('assets/js/pages/BankAccount/bankAccount.js') }}"></script>
