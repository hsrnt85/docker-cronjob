@extends('layouts.master')

@section('content')

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">

                {{-- PAGE TITLE --}}
                <div class="border-bottom border-primary mb-4"><h4 class="card-title">{{ getPageTitle(2) }}</h4></div>

                <p>Daerah : {{ district()->district_name; }}</p>
                <p>Tahun Notis Bayaran : {{ $year }}</p>

                <div id="datatable_wrapper">
                    <div class="row">
                        <div class="col-sm-12">
                            <table class="table table-striped table-bordered dt-responsive wrap w-100 dataTable" role="grid" >
                                <thead class="bg-primary bg-gradient text-white">
                                    <tr role="row">
                                        <th class="text-center" width="4%">Bil</th>
                                        <th class="text-center">Tahun/Bulan Notis Bayaran</th>
                                        <th class="text-center" width="10%">Tindakan</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    @foreach ($paymentNoticeTransactionAll as $i => $data)
                                        <tr class="odd">
                                            <td class="text-center" tabindex="0">{{ $loop->iteration }}</td>
                                            <td class="text-center">{{ $data->year }}/{{ $data->month }}</td>
                                            <td class="text-center">
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('agencyPaymentNotice.listAgencyWithTenant', ['year' => $data->year, 'month' => $data->month]) }}" class="btn btn-outline-primary px-2 py-1 tooltip-icon">
                                                        <span class="tooltip-text">{{ __('button.papar') }}</span> <i class="{{ __('icon.view_folder') }}"></i>
                                                    </a>
                                                </div>

                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>

                            <div class="mb-3 row">
                                <div class="col-sm-12">
                                    <a href="{{ route('agencyPaymentNotice.listYear') }}" class="btn btn-secondary float-end me-2">{{ __('button.kembali') }}</a>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div> <!-- end col -->
</div>
<!-- end row -->

@endsection

