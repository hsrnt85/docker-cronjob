@extends('layouts.master')

@section('content')

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">

                {{-- PAGE TITLE --}}
                <div class="border-bottom border-primary mb-4"><h4 class="card-title">{{ getPageTitle(1) }}</h4></div>

                <form  class="custom-validation" id="form" method="post" action="{{ route('paymentNoticeSchedule.update') }}" >
                    {{ csrf_field() }}

                    <div class="mb-1 row">
                        <h5 class="card-title">Tahun Notis Bayaran : {{ $year }}</h5>
                        <input type="hidden" id="year" name="year" value="{{ $year }}" >
                    </div>

                    <div class="mb-1 row">
                        <label class="col-md-1 col-form-label bg-primary text-white text-center">Bil</label>
                        <label class="col-md-2 col-form-label bg-primary text-white ps-1">Bulan</label>
                        <label class="col-md-3 col-form-label bg-primary text-white ps-1">Tarikh Notis Bayaran</label>
                    </div>
                    @foreach ($paymentNoticeSchedule as $data)
                        @php
                            $disabled = (!empty($data->month) && $data->month < currentMonth()) ? 'disabled':'';
                        @endphp
                        <div class="mb-1 row">
                            <label class="col-md-1 p-2 text-center">{{ $loop->iteration }}</label>
                            <label for="payment_notice_date_{{$data->month_id}}" class="col-md-2 col-form-label">{{ capitalizeText($data->month_name) }}</label>
                            <div class="col-md-3">
                                <div class="input-group" id="datepicker2">
                                    <input class="form-control @error('payment_notice_date_{{$data->month_id}}') is-invalid @enderror"  type="text"  placeholder="dd/mm/yyyy" 
                                        name="payment_notice_date_{{$data->month_id}}" id="payment_notice_date_{{$data->month_id}}" value="{{ old('payment_notice_date_'.$data->month_id, $data->payment_notice_date) }}"
                                        autocomplete="off" required data-parsley-required-message="{{ setMessage('payment_notice_date_'.$data->month_id.'.required') }}" 
                                        data-parsley-errors-container="#payment_notice_date_error{{$data->month_id}}" >
                                        {{-- {{ $disabled }} --}}
                                    <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
                                </div>
                            </div>
                            <div class="col-md-5 ps-2" id="payment_notice_date_error{{$data->month_id}}">
                                @error('payment_notice_date_{{$data->month_id}}')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>                        
                        </div>
                        
                    @endforeach
                   
                    <div class="mb-3 row">
                        <div class="col-sm-12">
                            <button type="submit" class="btn btn-primary float-end swal-kemaskini">{{ __('button.kemaskini') }}</button>
                            <a href="{{ route('paymentNoticeSchedule.listYear') }}" class="btn btn-secondary float-end me-2">{{ __('button.kembali') }}</a>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div> <!-- end col -->
</div>
<!-- end row -->

@endsection

@section('script-bottom')
<script src="{{ URL::asset('assets/js/pages/PaymentNoticeSchedule/payment-notice-schedule.js')}}"></script>
@endsection
