@extends('layouts.master')

@section('content')

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">

                {{-- PAGE TITLE --}}
                <div class="border-bottom border-primary mb-4"><h4 class="card-title">{{ getPageTitle(3) }}</h4></div>

                <form  class="custom-validation" id="form" method="post" action="{{ route('paymentNoticeTransaction.process') }}" >
                    {{ csrf_field() }}

                    <p>Daerah : {{ district()->district_name; }}</p>
                    <p>Tahun/Bulan Notis Bayaran : {{ $year }}/{{ $month }}</p>
                    <input name="year" type="hidden" value="{{ $year }}">
                    <input name="month" type="hidden" value="{{ $month }}">

                    <table class="table table-striped table-bordered dt-responsive nowrap w-100" role="grid" >
                        <thead class="bg-primary bg-gradient text-white">
                            <tr role="row">
                                <th class="text-center" width="4%">Bil</th>
                                <th>Kategori Kuarters (Lokasi)</th>
                                <th>Daerah</th>
                                <th>Jenis Kuarters</th>
                                <th class="text-center">Jumlah Penghuni</th>
                                <th class="text-center" width="10%">Tindakan</th>
                            </tr>
                        </thead>

                        <tbody>
                            @php
                                $tenants_id_arr = [];
                                $bil=0;
                            @endphp
                            @foreach($quartersCategoryAll as $data)
                                @php
                                    $dataTenants = ($data->tenants()) ? $data->tenants() : null;
                                    $bil_tenants = $dataTenants?->count('id');
                                    $tenants_id_arr = ArrayToString($dataTenants->pluck('id')->toArray());
                                @endphp
                                @if($bil_tenants>0)
                                    <input type="hidden" name="tenants_id_arr[]" value="{{ $tenants_id_arr }}" >
                                    <tr>
                                        <th class="text-center" scope="row">{{ ++$bil }}</th>
                                        <td>{{ $data->name }}</td>
                                        <td>{{ $data->district?->district_name ?? '' }}</td>
                                        <td>{{ $data->landed_type?->type }}</td>
                                        <td class="text-center">{{ $bil_tenants }}</td>
                                        <td class="text-center">
                                            <div class="btn-group" role="group">
                                                @if(checkPolicy("V"))
                                                    <a href="{{ route('paymentNoticeTransaction.listTenant', ['year' => $year, 'month' => $month, 'qcid'=> $data->id ]) }}" class="btn btn-outline-primary px-2 py-1 tooltip-icon">
                                                        <span class="tooltip-text">{{ __('button.papar_penyewa') }}</span> <i class="{{ __('icon.view_folder') }}"></i>
                                                    </a>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>

                    <div class="mb-3 row">
                        <div class="col-sm-12">
                            @if($paymentNoticeTransaction->flag_process==0)
                                <button type="submit" class="btn btn-primary float-end swal-process-notice-list">{{ __('button.proses_notis_bayaran') }}</button>
                            @endif
                            <a href="{{ route('paymentNoticeTransaction.listPaymentNoticeSchedule', ['year' => $year]) }}" class="btn btn-secondary float-end me-2">{{ __('button.kembali') }}</a>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div> <!-- end col -->
</div>
<!-- end row -->

@endsection

