@extends('layouts.master')

@section('content')

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">

                {{-- PAGE TITLE --}}
                <div class="border-bottom border-primary mb-4"><h4 class="card-title">{{ getPageTitle(3) }}</h4></div>

                <form  class="custom-validation" id="form" method="post" action="#" >
                    {{ csrf_field() }}

                    <p>Daerah : {{ district()->district_name; }}</p>
                    <p>Tahun/Bulan Notis Bayaran : {{ $year }}/{{ $month }}</p>

                    <table class="table table-striped table-bordered dt-responsive wrap w-100 dataTable" role="grid" >
                        <thead class="bg-primary bg-gradient text-white">
                            <tr role="row">
                                <th class="text-center" width="4%">Bil</th>
                                <th>Agensi</th>
                                <th class="text-center">Jumlah Penyewa</th>
                                <th class="text-center" width="10%">Tindakan</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach($agencyAll as $bil => $data)
                                <tr>
                                    <th class="text-center" scope="row">{{ ++$bil }}</th>
                                    <td>{{ $data->name }}</td>
                                    <td class="text-center">{{ $data->total_tenants ?? 0; }}</td>
                                    <td class="text-center">
                                        <div class="btn-group" role="group">
                                            @if(checkPolicy("V"))
                                                <a href="{{ route('agencyPaymentNotice.listTenant', ['year' => $year, 'month' => $month, 'oid'=> $data->id ]) }}" class="btn btn-outline-primary px-2 py-1 tooltip-icon">
                                                    <span class="tooltip-text">{{ __('button.papar_penyewa') }}</span> <i class="{{ __('icon.view_folder') }}"></i>
                                                </a>
                                                <a href="{{route('agencyPaymentNotice.listTenantPdf', ['year' => $year, 'month' => $month, 'oid'=> $data->id ]) }}" target="_blank" class="btn btn-outline-primary px-2 py-1 tooltip-icon" >
                                                    <span class="tooltip-text">{{ __('button.muat_turun_pdf') }}</span> <i class="{{ __('icon.file_pdf') }}"></i>
                                                </a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <div class="mb-3 row">
                        <div class="col-sm-12">
                            <a href="{{ route('agencyPaymentNotice.listPaymentNotice', ['year' => $year]) }}" class="btn btn-secondary float-end me-2">{{ __('button.kembali') }}</a>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div> <!-- end col -->
</div>
<!-- end row -->

@endsection

