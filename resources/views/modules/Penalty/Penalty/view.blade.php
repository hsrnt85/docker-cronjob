@extends('layouts.master')

@section('content')

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">

                {{-- PAGE TITLE --}}
                <div class="border-bottom border-primary mb-4"><h4 class="card-title">{{ getPageTitle(2) }}</h4></div>

                    <input type="hidden" name="id" value="{{ $tenantsPenalty->id }}">

                    <div class="mb-3 row">
                        <label for="ic_numb" class="col-md-2 col-form-label">No. Rujukan Denda</label>
                        <div class="col-md-10">
                            <p class="col-form-label">{{ $tenantsPenalty->penalty_ref_no}}</p>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="ic_numb" class="col-md-2 col-form-label">No. Kad Pengenalan</label>
                        <div class="col-md-10">
                            <p class="col-form-label">{{ $tenantsPenalty->tenants->new_ic }}</p>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="tenant_name" class="col-md-2 col-form-label">Nama Penghuni</label>
                        <div class="col-md-10">
                            <p class="col-form-label">{{ $tenantsPenalty->tenants->name }}</p>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="phone_numb" class="col-md-2 col-form-label">No. Telefon</label>
                        <div class="col-md-10">
                            <p class="col-form-label">{{ $tenantsPenalty->tenants->phone_no_hp }}</p>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="quarters_cat" class="col-md-2 col-form-label">Kategori Kuarters (Lokasi)</label>
                        <div class="col-md-10">
                            <p class="col-form-label">{{ $tenantsPenalty->tenants->quarters_category->name }}</p>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="penalty_date" class="col-md-2 col-form-label">Tarikh Dikenakan Denda</label>
                        <div class="col-md-10">
                            <p class="col-form-label">{{ convertDateSys($tenantsPenalty->penalty_date) }}</p>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="remarks" class="col-md-2 col-form-label">Keterangan Denda</label>
                        <div class="col-md-10">
                            <p class="col-form-label">{{ $tenantsPenalty->remarks }}</p>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="payment_amount" class="col-md-2 col-form-label">Amaun Denda (RM)</label>
                        <div class="col-md-10">
                            <p class="col-form-label">{{ 'RM '.$tenantsPenalty->penalty_amount }}</p>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <div class="col-sm-12">
                            <a href="{{ route('penalty.penaltyList', $category->id) }}" class="btn btn-secondary float-end me-2">{{ __('button.kembali') }}</a>
                        </div>
                    </div>
            </div>
        </div>
    </div> <!-- end col -->
</div>
<!-- end row -->

@endsection
