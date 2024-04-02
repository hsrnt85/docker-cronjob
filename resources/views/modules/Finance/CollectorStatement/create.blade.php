@extends('layouts.master')
@section('css')
    <link href="{{ URL::asset('/assets/libs/bootstrap-datepicker/bootstrap-datepicker.min.css') }}" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="{{ URL::asset('/assets/libs/datepicker/datepicker.min.css') }}">
@endsection
@section('content')

<div >
    <form class="custom-validation" id="form" method="post" action="{{ route('collectorStatement.store') }}" >
    {{ csrf_field() }}

    <div class="col-12">
        <div class="card">
            <div class="card-body">

                <div id="section_alert">
                    <div class="alert alert-warning">
                        <div id="alert_data_more_than_200"></div>
                    </div>
                </div>

                {{-- PAGE TITLE --}}
                <div class="border-bottom border-primary mb-4"><h4 class="card-title">{{ getPageTitle(1) }}</h4></div>
                <input type="hidden" id="page" name="page" value="new">
                {{-- <input type="text" id="tenants_payment_notice_id"> --}}
                <!-- tab 1-->
                <ul class="nav nav-tabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="nav-penyata-pemungut" data-bs-toggle="tab" href="#penyataPemungut" role="tab" aria-controls="penyataPemungutTab" >Penyata Pemungut</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="nav-senarai-kutipan-hasil" data-bs-toggle="tab" href="#senaraiKutipanHasil" role="tab" aria-controls="senaraiKutipanHasilTab">Senarai Terimaan Hasil</a>
                    </li>
                </ul>

                <div class="tab-content p-4">

                    {{-- tab 1 --}}
                    <div class="tab-pane fade show active" id="penyataPemungut" role="tabpanel">

                        <h4 class="card-title text-primary mb-3">Maklumat Induk</h4>

                        <div class="mb-3 row">
                            <label class="col-md-2 col-form-label">Tahun Kewangan</label>
                            <div class="col-md-7">
                                <input class="form-control" value="{{currentYear()}}" disabled>
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label class="col-md-2 col-form-label">Jabatan Penyedia</label>
                            <div class="col-md-7">
                                <input class="form-control" value="{{$finance_department->department_name}}" disabled>
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label class="col-md-2 col-form-label">PTJ Penyedia</label>
                            <div class="col-md-7">
                                <input class="form-control" value="{{$finance_department->ptj_name }}" disabled>
                            </div>
                        </div>

                        <hr><h4 class="card-title text-primary mt-2 mb-3">Maklumat Penyata Pemungut</h4>

                        <div class="mb-3 row">
                            <label for="date" class="col-md-2 col-form-label">Tarikh Penyata Pemungut</label>
                            <div class="col-md-7">
                                <div class="input-group" id="datepicker2">
                                    <input class="form-control"  value="{{currentDateSys()}}" name="date" id="date" disabled>
                                    <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label for="date_from" class="col-md-2 col-form-label">Tempoh Pungutan Dari</label>
                            <div class="col-md-7">
                                <div class="input-group" id="datepicker2">
                                    <input class="form-control @error('date_from') is-invalid @enderror"  type="text" onkeydown="return false" placeholder="dd/mm/yyyy"
                                        name="date_from" id="date_from" autocomplete="off" required data-parsley-required-message="{{ setMessage('date_from.required') }}"
                                        data-parsley-errors-container="#date_from_error" >
                                    <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
                                </div>
                                <div id="date_from_error"></div>
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label for="date_to" class="col-md-2 col-form-label">Tempoh Pungutan Hingga</label>
                            <div class="col-md-7">
                                <div class="input-group" id="datepicker2">
                                    <input class="form-control @error('date_to') is-invalid @enderror" type="text" onkeydown="return false"  placeholder="dd/mm/yyyy"
                                        name="date_to" id="date_to" autocomplete="off" required data-parsley-required-message="{{ setMessage('date_to.required') }}"
                                        data-parsley-errors-container="#date_to_error" >
                                    <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
                                </div>
                                <div id="date_to_error"></div>
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label for="bank_slip_date" class="col-md-2 col-form-label">Tarikh Dibankkan</label>
                            <div class="col-md-7">
                                <div class="input-group" id="datepicker2">
                                    <input class="form-control @error('bank_slip_date') is-invalid @enderror"  type="text" onkeydown="return false" placeholder="dd/mm/yyyy"
                                        name="bank_slip_date" id="bank_slip_date"  required data-parsley-required-message="{{ setMessage('bank_slip_date.required') }}"
                                        data-parsley-errors-container="#bank_slip_date_error" >
                                    <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
                                </div>
                                <div id="bank_slip_date_error"></div>
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label for="payment_method" class="col-md-2 col-form-label ">Kaedah Bayaran</label>
                            <div class="col-md-7">
                                <select class="form-select select2 payment_method @error('payment_method') is-invalid @enderror" id="payment_method" name="payment_method" value="{{ old('payment_method') }}"
                                    required data-parsley-required-message="{{ setMessage('payment_method.required') }}"
                                    data-parsley-errors-container="#errorMethod">
                                    <option value="">-- Sila Pilih --</option>
                                    @foreach($getPaymentMethod as $method)
                                        <option value="{{ $method->id }}" >{{ $method->payment_method }}</option>
                                    @endforeach
                                </select>
                                <div id="errorMethod"></div>
                                @error('payment_method')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label for="bank_name" class="col-md-2 col-form-label">Nama Bank  <span class="text-danger"> *</span></label>
                            <div class="col-md-7">
                                <input type="hidden" id="selected-bank-name" value="">
                                <select class="form-select select_bank select2" id="bank_name" name="bank_name" value=""
                                required data-parsley-required-message="{{ setMessage('bank_name.required') }}"
                                data-parsley-errors-container="#parsley-bank">
                                <option value="">-- Sila Pilih --</option>
                                <div class="spinner-wrapper-bank"></div>
                                </select>
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label for="bank_slip" class="col-md-2 col-form-label">No. Slip Bank</label>
                            <div class="col-md-7">
                                <input class="form-control" type="text" id="bank_slip" name="bank_slip" value="" readonly>
                                <div class="spinner-wrapper-bank"></div>
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label for="purpose" class="col-md-2 col-form-label">Butiran</label>
                            <div class="col-md-7">
                                <input class="form-control @error('purpose') is-invalid @enderror" type="text" id="purpose" name="purpose" value="{{ old('purpose','') }}"
                                        required data-parsley-required-message="{{ setMessage('purpose.required') }}"
                                        data-parsley-errors-container="#errorPurpose">
                                @error('purpose')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div id="errorPurpose"></div>
                            </div>
                        </div>

                        <hr><h4 class="card-title text-primary mt-2 mb-3">Maklumat Vot Hasil</h4>
                        
                        @include('modules.Finance.CollectorStatement.maklumat-vot-hasil')

                        <hr><h4 class="card-title text-primary mt-2 mb-3">Maklumat Pegawai</h4>

                        <div class="mb-3 row">
                            <label for="preparer" class="col-md-2 col-form-label">Disediakan Oleh</label>
                            <div class="col-md-7">
                                <select class="form-select select2" name="preparer" id="preparer"
                                    required data-parsley-required-message="{{ setMessage('preparer.required') }}">
                                    @foreach($pegawaiPenyediaList as $officer)
                                        @if($officer->users_id == $currentOfficer->users_id)
                                            <option value="{{ $officer->fin_officer_id}}" selected>{{ $officer->name }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label for="checker" class="col-md-2 col-form-label ">Disemak Oleh</label>
                            <div class="col-md-7">
                                <select class="form-select select2  @error('checker') is-invalid @enderror" id="checker" name="checker" value="{{ old('checker') }}"
                                    required data-parsley-required-message="{{ setMessage('checker.required') }}"
                                    data-parsley-errors-container="#errorChecker">
                                    <option value="">-- Pilih Pegawai --</option>
                                    @foreach($pegawaiPenyemakList as $officer)
                                        <option value="{{ $officer->fin_officer_id }}" >{{ $officer->name }}</option>
                                    @endforeach
                                </select>
                                <div id="errorChecker"></div>
                                @error('checker')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <div class="col-sm-12 pr-5">
                                <button type="submit" id="btn_simpan" class="btn btn-primary float-end swal-tambah">{{ __('button.simpan') }}</button>
                                <a href="{{ route('collectorStatement.index') }}" class="btn btn-secondary float-end me-2">{{ __('button.kembali') }}</a>
                            </div>
                        </div>
                    </div>
                    {{-- end tab 1 --}}

                    {{-- tab 2 --}}
                    <div class="tab-pane fade mt-4" id="senaraiKutipanHasil" role="tabpanel" aria-labelledby="nav-senarai-kutipan-hasil">
                        @include('modules.Finance.CollectorStatement.kutipan-hasil-list')
                    </div>
                    {{-- end tab 2 --}}

                </div>{{-- end tab content --}}

            </div> {{-- end card body --}}

        </div>{{-- end card --}}

    </div>{{-- end col1-12 --}}

    </form>

</div>{{-- end row  --}}

@endsection

@section('script')
<script src="{{ URL::asset('assets/js/pages/CollectorStatement/collectorStatement.js')}}"></script>
{{-- <script src="{{ URL::asset('assets/libs/bootstrap-datepicker/bootstrap-datepicker.min.js')}}"></script> --}}
@endsection

