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
                <div class="border-bottom border-primary mb-4"><h4 class="card-title">{{ getPageTitle(2) }}</h4></div>

                <form class="custom-validation" id="form" method="post" action="{{ route('penalty.store') }}" >
                    {{ csrf_field() }}
                    <input type="hidden" name="q_category_id" id="q_category_id" value="{{$category->id}}">

                    <div class="mb-3 row">
                        <label for="ic_numb" class="col-md-2 col-form-label">No. Kad Pengenalan</label>
                        <div class="col-md-10">
                            <input class="form-control input-not-uppercase @error('ic_numb') is-invalid @enderror" name="ic_numb" id="ic_numb" value="{{ old('ic_numb') }}" oninput="checkNumber(this)" onfocus="focusInput(this);" onkeyup="check_ic()"
                            minlength="12" maxlength="12" data-parsley-length-message="{{ setMessage('ic_numb.digits') }}"
                            required   data-parsley-required-message="{{ setMessage('ic_numb.required') }}"  data-route-tenant="{{ route('penalty.ajaxCheckTenantIC') }}"
                            data-parsley-errors-container="#errors-ic-numb">
                            <div class="col-md-10 mt-1" id="tenant-leave-error"></div>
                            {{-- <div class="col-md-10 mt-1" id="errors-ic-numb"></div> --}}
                        </div>
                        @error('ic_numb')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="mb-3 row">
                        <label for="tenant_name" class="col-md-2 col-form-label">Nama Penghuni</label>
                        <div class="col-md-10">
                            <input class="form-control" type="hidden" name="tenant_id" id="tenant_id" readonly>
                            <input class="form-control" name="tenant_name" id="tenant_name" readonly>
                            <div class="spinner-wrapper"></div>
                            @error('tenant_name')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="phone_numb" class="col-md-2 col-form-label">No. Telefon</label>
                        <div class="col-md-10">
                            <input class="form-control" name="phone_numb" id="phone_numb" readonly>
                            <div class="spinner-wrapper"></div>
                            @error('phone_numb')
                                <span class="invalid-feedback">{{ $message }}</span>
                             @enderror
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="quarters_cat" class="col-md-2 col-form-label">Kategori Kuarters (Lokasi)</label>
                        <div class="col-md-10">
                            <input class="form-control" name="quarters_cat" id="quarters_cat" readonly>
                            <div class="spinner-wrapper"></div>
                            @error('quarters_cat')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="penalty_date" class="col-md-2 col-form-label">Tarikh Dikenakan Denda</label>
                        <div class="col-md-10">
                            <div class="input-group" id="datepicker2">
                                {{-- <input class="form-control @error('penalty_date') is-invalid @enderror" type="date" name="penalty_date" value="{{ old('penalty_date', '') }}" > --}}
                                <input class="form-control @error('penalty_date') is-invalid @enderror"  type="text"  placeholder="dd/mm/yyyy" name="penalty_date" id="penalty_date"  value="{{ old('penalty_date') }}"
                                data-date-format="dd/mm/yyyy" data-date-container='#datepicker2' data-provide="datepicker" autocomplete="off"
                                data-date-autoclose="true" required data-parsley-required-message="{{ setMessage('penalty_date.required') }}" data-parsley-errors-container="#errorDate" >
                                <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
                            </div>
                            <div id="errorDate"></div>
                            @error('penalty_date')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="remarks" class="col-md-2 col-form-label">Keterangan Denda</label>
                        <div class="col-md-10">
                            <input class="form-control @error('remarks') is-invalid @enderror" type="text" name="remarks" value="{{ old('remarks', '') }}" required data-parsley-required-message="{{ setMessage('remarks.required') }}">
                            @error('remarks')
                            <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="payment_amount" class="col-md-2 col-form-label">Amaun Denda (RM)</label>
                        <div class="col-md-10">
                            <input class="form-control @error('penalty_amount') is-invalid @enderror" type="text" name="penalty_amount" oninput="checkNumber(this)" onfocus="focusInput(this);" value="{{ old('penalty_amount', '') }}" required data-parsley-required-message="{{ setMessage('penalty_amount.required') }}">
                            @error('penalty_amount')
                            <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <div class="col-sm-12">
                            <button type="submit" id="btn-submit" class="btn btn-primary float-end swal-tambah">{{ __('button.simpan') }}</button>
                            <a href="{{ route('penalty.index') }}" class="btn btn-secondary float-end me-2">{{ __('button.kembali') }}</a>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div> <!-- end col -->
</div>
<!-- end row -->

@endsection

@section('script')
<script src="{{ URL::asset('assets/js/pages/Penalty/penalty.js')}}"></script>
<script src="{{ URL::asset('assets/libs/bootstrap-datepicker/bootstrap-datepicker.min.js')}}"></script>
@endsection

