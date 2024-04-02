@extends('layouts.master')

@section('content')

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">

                {{-- PAGE TITLE --}}
                <div class="border-bottom border-primary mb-4"><h4 class="card-title">{{ getPageTitle(1) }}</h4></div>
                    
                    <div class="mb-1 row">
                        <h5 class="card-title">Tahun Notis Bayaran : {{ $year }}</h5>
                        <input type="hidden" id="year" name="year" value="{{ $year }}" >
                    </div>

                    <table class="table table-sm table-bordered">
                        <thead class="text-white bg-primary">
                            <tr>
                                <th class="text-center">Bil</th>
                                <th class="text-center">Bulan</th>
                                <th class="text-center">Tarikh Notis Bayaran</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($paymentNoticeSchedule as $data)
                                <tr>
                                    <th scope="row" class="text-center">{{$loop->iteration}}</th>
                                    <td>{{ capitalizeText($data->month_name) }}</td>
                                    <td class="text-center">{{ $data->payment_notice_date }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    {{-- <div class="mb-1 row">
                        <label class="col-md-1 col-form-label bg-primary text-white text-center">Bil</label>
                        <label class="col-md-2 col-form-label bg-primary text-white ps-1">Bulan</label>
                        <label class="col-md-3 col-form-label bg-primary text-white">Tarikh Notis Bayaran</label>
                    </div>
                    @foreach ($paymentNoticeSchedule as $data)
                        <div class="mb-1 row">
                            <label class="col-md-1 col-form-label text-center">{{ $loop->iteration }}</label> 
                            <label class="col-md-2 col-form-label">{{ capitalizeText($data->month_name) }}</label> 
                            <label class="col-md-3 col-form-label">{{ $data->payment_notice_date }} </label>                      
                        </div>
                    @endforeach --}}
                   
                    <div class="mb-3 row">
                        <div class="col-sm-12">
                            <a href="{{ route('paymentNoticeSchedule.listYear') }}" class="btn btn-secondary float-end me-2">{{ __('button.kembali') }}</a>
                        </div>
                    </div>
          

            </div>
        </div>
    </div> <!-- end col -->
</div>
<!-- end row -->

@endsection

