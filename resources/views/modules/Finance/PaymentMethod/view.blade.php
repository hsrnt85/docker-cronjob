@extends('layouts.master')

@section('content')

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">

                {{-- PAGE TITLE --}}
                <div class="border-bottom border-primary mb-4"><h4 class="card-title">{{ getPageTitle(1) }}</h4></div>

                <input class="form-control" type="hidden" id="id" name="id" value="{{ $paymentMethod->id}}">

                <div class="mb-3 row">
                    <label for="payment_method_code" class="col-md-2 col-form-label">Kod Kaedah Bayaran</label>
                    <div class="col-md-10">
                        <p class="col-form-label">{{$paymentMethod->payment_method_code}}</p>
                    </div>
                </div>

                <div class="mb-3 row">
                    <label for="payment_method" class="col-md-2 col-form-label">Kaedah Bayaran</label>
                    <div class="col-md-10">
                        <p class="col-form-label">{{$paymentMethod->payment_method}}</p>
                    </div>
                </div>

                <div class="mb-3 row">
                    <label for="payment_category_id" class="col-md-2 col-form-label">Kategori Bayaran</label>
                    <div class="col-md-10">
                        <p class="col-form-label">{{$paymentMethod->paymentCategoryCode->payment_category}}</p>
                    </div>
                </div>

                <div class="mb-3 row">
                    <label for="ispeks_payment_code_id" class="col-md-2 col-form-label">Kaedah Bayaran iSPEKS</label>
                    <div class="col-md-10">
                        <p class="col-form-label">{{$paymentMethod->ispeksPaymentCode->description}}</p>
                    </div>
                </div>
                

                <div class="mb-3 row">
                    <div class="col-sm-12">
                        <a href="{{ route('paymentMethod.index') }}" class="btn btn-secondary float-end me-2">{{ __('button.kembali') }}</a>
                    </div>
                </div>
            </div>
        </div>
    </div> <!-- end col -->
</div>
<!-- end row -->

@endsection

