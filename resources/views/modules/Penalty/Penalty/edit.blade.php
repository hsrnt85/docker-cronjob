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

                <form  class="custom-validation" id="form" method="post" action="{{ route('penalty.update') }}" >
                    {{ csrf_field() }}

                    <input class="form-control" type="hidden" name="id" value="{{ $tenantsPenalty->id}}">
                    <input type="hidden" name="q_category_id" value="{{$category->id}}">

                    <div class="mb-3 row">
                        <label for="ic_numb" class="col-md-2 col-form-label">No. Rujukan Denda</label>
                        <div class="col-md-10">
                            <input class="form-control" value="{{ $tenantsPenalty->penalty_ref_no }}" readonly>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="ic_numb" class="col-md-2 col-form-label">No. Kad Pengenalan</label>
                        <div class="col-md-10">
                            <input class="form-control" value="{{ $tenantsPenalty->tenants->new_ic }}" readonly>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="tenant_name" class="col-md-2 col-form-label">Nama Penghuni</label>
                        <div class="col-md-10">
                            <input class="form-control" value="{{ $tenantsPenalty->tenants->name }}" readonly>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="phone_numb" class="col-md-2 col-form-label">No. Telefon</label>
                        <div class="col-md-10">
                            <input class="form-control" value="{{ $tenantsPenalty->tenants->phone_no_hp }}" readonly>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="quarters_cat" class="col-md-2 col-form-label">Kategori Kuarters (Lokasi)</label>
                        <div class="col-md-10">
                            <input class="form-control" value="{{$tenantsPenalty->tenants->quarters_category->name}}" readonly>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="penalty_date" class="col-md-2 col-form-label">Tarikh Dikenakan Denda</label>
                        <div class="col-md-10">
                            <div class="input-group" id="datepicker2">
                                <input class="form-control @error('penalty_date') is-invalid @enderror" type="text" name="penalty_date"  data-date-format="dd/mm/yyyy" data-date-container='#datepicker2' data-provide="datepicker" autocomplete="off"
                                data-date-autoclose="true" value="{{ convertDateSys($tenantsPenalty->penalty_date) }}" required data-parsley-required-message="{{ setMessage('penalty_date.required') }}"  data-parsley-errors-container="#errorDate" >
                                <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
                                @error('penalty_date')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                            <div id="errorDate"></div>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="remarks" class="col-md-2 col-form-label">Keterangan Denda</label>
                        <div class="col-md-10">
                            <input class="form-control @error('remarks') is-invalid @enderror" type="text" name="remarks" value="{{ old('remarks', $tenantsPenalty->remarks) }}" required data-parsley-required-message="{{ setMessage('remarks.required') }}">
                            @error('remarks')
                            <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="penalty_amount" class="col-md-2 col-form-label">Amaun Denda (RM)</label>
                        <div class="col-md-10">
                            <input class="form-control @error('penalty_amount') is-invalid @enderror numeric" placeholder="RM00.00" type="text" name="penalty_amount" value="{{ old('penalty_amount', $tenantsPenalty->penalty_amount) }}" oninput="validateAmount(this)" required data-parsley-required-message="{{ setMessage('penalty_amount.required') }}">
                            @error('penalty_amount')
                            <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <div class="col-sm-12">
                            <button type="submit" class="btn btn-primary float-end swal-kemaskini">{{ __('button.kemaskini') }}</button>
                            <a href="{{ route('penalty.penaltyList', $category->id) }}" class="btn btn-secondary float-end me-2">{{ __('button.kembali') }}</a>
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

