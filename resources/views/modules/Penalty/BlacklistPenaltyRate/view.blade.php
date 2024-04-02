@extends('layouts.master')
@section('css')
    <link href="{{ URL::asset('/assets/libs/bootstrap-datepicker/bootstrap-datepicker.min.css') }}" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="{{ URL::asset('/assets/libs/datepicker/datepicker.min.css') }}">
@endsection
@section('content')

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body" style="width:100%">

                {{-- PAGE TITLE --}}
                <div class="border-bottom border-primary mb-4"><h4 class="card-title">{{ getPageTitle(1) }}</h4></div>

                    <div class="mb-3 row">
                        <label for="year" class="col-sm-1">Tarikh Kuatkuasa</label>
                        <div class="col-md-6">
                            {{ convertDateSys($bpr->effective_date) }}
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="year" class="col-sm-1">Keterangan</label>
                        <div class="col-md-6">
                            {{ $bpr->description }}
                        </div>
                    </div>

                    <div class="col-6">
                        <table class="table table-sm table-bordered" id="table-rate">
                            <thead class="text-center bg-primary bg-gradient text-white">
                                <th>Bil</th>
                                <th>Tempoh Kiraan Denda Hilang Kelayakan (Bulan)</th>
                                <th>Kadar Denda (%)</th>
                            </thead>
                            <tbody>
                                @foreach ($bpr->rates as $rate)
                                    <tr class="text-center">
                                        <td>{{ $loop->iteration }}</td>
                                        <td>
                                            {{ $rate->range_from }}  {{$rate->operator->operator_name}}  {{$rate->range_to}}
                                        </td>
                                        <td>
                                            {{ $rate->rate }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mb-3 row">
                        <div class="col-sm-12">
                            <a href="{{ route('blacklistPenaltyRate.index') }}" class="btn btn-secondary float-end me-2">{{ __('button.kembali') }}</a>
                        </div>
                    </div>
            </div>
        </div>
    </div> <!-- end col -->
</div>
<!-- end row -->

@endsection

@section('script')
<script src="{{ URL::asset('assets/libs/bootstrap-datepicker/bootstrap-datepicker.min.js')}}"></script>
<script src="{{ URL::asset('assets/js/pages/BlacklistPenalty/blacklist-penalty-rate.js')}}"></script>

@endsection

