@extends('layouts.master')

@section('content')

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">

                {{-- PAGE TITLE --}}
                <div class="border-bottom border-primary mb-4"><h4 class="card-title">{{ getPageTitle(1) }}</h4></div>

                <input class="form-control" type="hidden" id="id" name="id" value="{{ $incomeAccountCode->id}}">

                <div class="mb-3 row">
                    <label for="salary_deduction_code" class="col-md-2 col-form-label">Kod Potongan Gaji</label>
                    <div class="col-md-10">
                        <p class="col-form-label">{{$incomeAccountCode->salary_deduction_code}}</p>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label class="col-md-2 col-form-label">Kod Am Hasil</label>
                    <div class="col-md-10">
                        <p class="col-form-label">{{$incomeAccountCode->general_income_code}}</p>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label class="col-md-2 col-form-label">Kod Akaun iSPEKS</label>
                    <div class="col-md-10">
                        <p class="col-form-label">{{$incomeAccountCode->ispeks_account_code}}</p>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label class="col-md-2 col-form-label">Butiran Kod Akaun iSPEKS</label>
                    <div class="col-md-10">
                        <p class="col-form-label">{{$incomeAccountCode->ispeks_account_description}}</p>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label class="col-md-2 col-form-label">Kod Hasil</label>
                    <div class="col-md-10">
                        <p class="col-form-label">{{$incomeAccountCode->income_code}}</p>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label class="col-md-2 col-form-label">Butiran Kod Hasil</label>
                    <div class="col-md-10">
                        <p class="col-form-label">{{$incomeAccountCode->income_code_description}}</p>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label class="col-md-2 col-form-label">Jenis Akaun</label>
                    <div class="col-md-10">
                        <p class="col-form-label">{{$incomeAccountCode->account_type?->account_type}}</p>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label class="col-md-2 col-form-label">Kategori Bayaran</label>
                    <div class="col-md-10">
                        <p class="col-form-label">{{$incomeAccountCode->payment_category?->payment_category}}</p>
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
                                    data-parsley-errors-container="#parsley-errors-services-status" disabled>
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
                            <input class="flag_pensioner form-check-input me-2" type="checkbox" id="formCheck" name="flag_pensioner" value="1" {{ old('flag_pensioner', $incomeAccountCode->flag_pensioner) == 1 ? "checked" : "" }} disabled>
                            <label class="form-check-label" for="formCheck"></label>
                        </div>
                    </div>
                </div> --}}
                <div class="mb-3 row">
                    <label for="flag_outstanding" class="col-md-2 col-form-label">Akaun Tunggakan ?</label>
                    <div class="col-md-1">
                        <div class="form-check @error('flag_outstanding') is-invalid @enderror">
                            <input class="flag_outstanding form-check-input me-2" type="checkbox" id="formCheck" name="flag_outstanding" value="2" {{ old('flag_outstanding', $incomeAccountCode->flag_outstanding) == 2 ? "checked" : "" }} disabled>
                            <label class="form-check-label" for="formCheck">YA</label>
                        </div>
                    </div>
                </div>

                <div class="mb-3 row">
                    <div class="col-sm-12">
                        <a href="{{ route('incomeAccountCode.index') }}" class="btn btn-secondary float-end me-2">{{ __('button.kembali') }}</a>
                    </div>
                </div>
            </div>
        </div>
    </div> <!-- end col -->
</div>
<!-- end row -->

@endsection
