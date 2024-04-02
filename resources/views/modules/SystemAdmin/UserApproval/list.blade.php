@extends('layouts.master')

@section('content')

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">

                {{-- PAGE TITLE --}}
                <div class="border-bottom border-primary mb-4"><h4 class="card-title">{{ getPageTitle(1) }}</h4></div>

                <div id="datatable_wrapper">
                    <div class="row">
                        <div class="col-sm-12">
                            <table id="datatable" class="table table-striped table-bordered dt-responsive nowrap w-100 dataTable" role="grid">
                                <thead class="bg-primary bg-gradient text-white">
                                    <tr role="row">
                                        <th class="text-center" width="4%">Bil</th>
                                        <th>Nama Pengguna</th>
                                        <th>No. Kad Pengenalan (Baru)</th>
                                        <th>Platform Sistem</th>
                                        <th class="text-center">Status Pengguna</th>
                                        <th class="text-center" width="10%">Tindakan</th>
                                    </tr>
                                </thead>

                                <tbody class="table-striped">

                                    @foreach($senaraiUser as $bil => $data)
                                        @php
                                            $approval_status = ($data->data_status==2) ? 'Belum disahkan' : 'Aktif';
                                            $badge = ($data->data_status==2) ? 'bg-danger' : 'bg-success';
                                        @endphp

                                        <tr>
                                            <th class="text-center" scope="row">{{ ++$bil }}</th>
                                            <td>{{ $data->name }}</td>
                                            <td>{{ $data->new_ic }}</td>
                                            <td>{{ $data->system->name ?? '' }}</td>
                                            <td class="text-center"><span class="p-1 badge {{ $badge }}">{{ Str::upper($approval_status)  }}</span></td>
                                            <td class="text-center">
                                                <div class="btn-group" role="group">
                                                    @if(checkPolicy("U"))
                                                    <a href="{{ route('userApproval.approval', ['id' => $data->id]) }}" class="btn btn-outline-primary px-2 py-1 tooltip-icon">
                                                        <span class="tooltip-text">{{ __('button.sah_pengguna') }}</span><i class="{{ __('icon.approve_user') }}"></i>
                                                    </a>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> <!-- end col -->
</div>
<!-- end row -->

@endsection
