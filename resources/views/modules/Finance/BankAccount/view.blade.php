@extends('layouts.master')

@section('content')

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">

                {{-- PAGE TITLE --}}
                <div class="border-bottom border-primary mb-4"><h4 class="card-title">{{ getPageTitle(1) }}</h4></div>

                <input class="form-control" type="hidden" id="id" name="id" value="{{ $bankAccount->id}}">

                <div class="mb-3 row">
                    <label for="bank_id" class="col-md-2 col-form-label">Nama Bank</label>
                    <div class="col-md-10">
                        <p class="col-form-label">{{$bankAccount->bank->bank_name}}</p>
                    </div>
                </div>

                <div class="mb-3 row">
                    <label for="account_no" class="col-md-2 col-form-label">No. Akaun</label>
                    <div class="col-md-10">
                        <p class="col-form-label">{{$bankAccount->account_no}}</p>
                    </div>
                </div>

                <div class="mb-3 row">
                    <label for="account_name" class="col-md-2 col-form-label">Nama Akaun</label>
                    <div class="col-md-10">
                        <p class="col-form-label">{{$bankAccount->account_name}}</p>
                    </div>
                </div>

                <div class="mb-3 row">
                    <label for="payment_method_id" class="col-md-2 col-form-label">Kaedah Bayaran</label>
                    <div class="col-md-10">
                        <p class="col-form-label">{{$bankAccount->paymentMethod?->payment_method}}</p>
                    </div>
                </div>
                
                <div class="mb-3 row">
                    <label for="payment_category" class="col-md-2 col-form-label">Kategori Bayaran</label>
                    <div class="col-md-10">
                        <p class="col-form-label">{{$bankAccount->paymentCategory?->payment_category}}</p>
                    </div>
                </div>

                

                <div class="mb-3 row">
                    <label for="bank_account_type" class="col-md-2 col-form-label">Jenis Akaun</label>
                    <div class="col-md-10">
                        <p class="col-form-label">{{ $bankAccount->bank_account_type == 1 ? 'Akaun Bank Utama' : 'Akaun Bank Transit' }}</p>
                    </div>
                </div>

                <div class="mb-3 row">
                    <div class="col-sm-12">
                        <a href="{{ route('bankAccount.index') }}" class="btn btn-secondary float-end me-2">{{ __('button.kembali') }}</a>
                    </div>
                </div>
            </div>
        </div>
    </div> <!-- end col -->
</div>
<!-- end row -->

@endsection

