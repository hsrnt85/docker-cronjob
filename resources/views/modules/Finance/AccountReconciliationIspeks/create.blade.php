@extends('layouts.master')

@section('content')

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                
                {{-- PAGE TITLE --}}
                <div class="border-bottom border-primary mb-4"><h4 class="card-title">{{ getPageTitle(1) }}</h4></div>
   
                    <form id="form" method="post" action="{{ route('accountReconciliationIspeks.processFile', ['year' => $year, 'month' => $month]) }}" enctype="multipart/form-data">
                        
                        {{ csrf_field() }}
                        <div class="mb-3 row">
                            <label class="col-md-2 mt-2">Tahun / Bulan Proses Gaji</label>
                            <div class="col-md-2 mt-2"> {{ $year }} / {{ $month }}</div>                            
                        </div>

                        <div class="mb-3 row">
                            <label class="col-md-2 col-form-label">Kod Potongan Gaji</label>
                            <div class="col-md-4">
                                <select name="salary_deduction_code" class="form-select" value="{{ old('salary_deduction_code') }}" required data-parsley-required-message="{{ setMessage('salary_deduction_code.required') }}" >
                                    <option value=''> -- Sila Pilih -- </option>
                                    @foreach($salaryDeductionCodeAll as $data)
                                        <option value="{{ $data->salary_deduction_code }}" {{ old('salary_deduction_code', $salary_deduction_code) == $data->salary_deduction_code ? 'selected' : '' }}>
                                            {{ $data->salary_deduction_code }} - {{ $data->ispeks_account_description }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label class="col-md-2 col-form-label">Kaedah Bayaran</label>
                            <div class="col-md-4">
                                <select class="form-select @error('payment_method_id') is-invalid @enderror" name="payment_method_id" required data-parsley-required-message="{{ setMessage('payment_method_id.required') }}">
                                    <option value="">-- Sila Pilih --</option>
                                    @foreach($paymentMethod as $code)
                                        <option value="{{ $code->id }}" {{ old('payment_method_id') == $code->id ? 'selected' : '' }}>
                                            {{ $code->payment_method }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label class="col-md-2 col-form-label">Muat Naik Fail (.xlsx/.xls)</label>
                            <div class="col-md-6">
                                <input class="form-control" type="file" name="file_upload"  value="{{old('file_upload','')}}"  
                                    required data-parsley-required-message="{{ setMessage('file_upload.required') }}"
                                    data-parsley-fileextension='.xlsx/.xls' data-parsley-fileextension-message="{{ setMessage('file_upload.file-extension') }}">
                                    @error('file_upload')<span class="invalid-feedback">{{$message}}</span>@enderror
                            </div>
                        </div>
                    
                        <div class="mb-3 row">
                            <div class="col-sm-12">
                                <button type="submit" class="btn btn-primary float-end swal-tambah">{{ __('button.simpan') }}</button>
                                <a href="{{ route('accountReconciliationIspeks.listTransaction', ['year' => $year, 'month' => $month]) }}" class="btn btn-secondary float-end me-2">{{ __('button.kembali') }}</a>
                            </div>
                        </div>
                    
                    </form>
              
            </div>
        </div>
    </div> <!-- end col -->
</div>
<!-- end row -->

@endsection

