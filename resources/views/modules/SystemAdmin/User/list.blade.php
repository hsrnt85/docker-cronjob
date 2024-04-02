@extends('layouts.master')

@section('content')

<div class="row">
    <div class="col-12">
        <div class="card">

            <div class="card-body">

                {{-- PAGE TITLE --}}
                <div class="border-bottom border-primary mb-4"><h4 class="card-title">{{ getPageTitle(1) }}</h4></div>
                @if(checkPolicy("A"))
                <div class="row mb-2">
                    <div class="col-sm-12">
                        <a type="button" href="{{ route('user.create') }}" class="btn btn-success float-end waves-effect waves-light">{{ __('button.rekod_baru') }}</a>
                    </div>
                </div>
                @endif
                <div id="datatable_wrapper">
                    <div class="row">
                        <div class="col-sm-12">
                            <table id="datatable" class="table table-striped table-bordered dt-responsive nowrap w-100 dataTable" role="grid">
                                <thead class="bg-primary bg-gradient text-white">
                                    <tr role="row">
                                        <th class="text-center" width="4%">Bil</th>
                                        <th width="19%">Nama Pengguna</th>
                                        <th width="13%">No. Kad Pengenalan (Baru)</th>
                                        <th>Tetapan Peranan</th>
                                        <th>Platform Sistem</th>
                                        <th class="text-center">Status Pengguna</th>
                                        <th class="text-center" width="12%">Tindakan</th>
                                    </tr>
                                </thead>

                                <tbody class="table-striped">

                                    @foreach($senaraiUser as $bil => $data)
                                        @php
                                            $approval_status = ($data->data_status==2) ? 'Tidak Aktif' : 'Aktif';
                                            $badge = ($data->data_status==2) ? 'bg-danger' : 'bg-success';
                                        @endphp

                                        <tr>
                                            <th class="text-center" scope="row">{{ ++$bil }}</th>
                                            <td>{{ $data->name }}</td>
                                            <td>{{ $data->new_ic }}</td>
                                            <td>{{ $data->roles->name ?? '' }}</td>
                                            <td>{{ $data->system->name ?? '' }}</td>
                                            <td class="text-center"><span class="p-1 badge {{ $badge }}">{{ Str::upper($approval_status)  }}</span></td>
                                            <td class="text-center">
                                                <div class="btn-group" role="group">
                                                    @if(checkPolicy("V"))
                                                    <a href="{{ route('user.view', ['id' => $data->id]) }}" class="btn btn-outline-primary px-2 py-1 tooltip-icon">
                                                        <span class="tooltip-text">{{ __('button.papar') }}</span><i class="{{ __('icon.view_folder') }}"></i>
                                                    </a>
                                                    @endif
                                                    @if(checkPolicy("U"))
                                                    <a href="{{ route('user.edit', ['id' => $data->id]) }}" class="btn btn-outline-primary px-2 py-1 tooltip-icon">
                                                        <span class="tooltip-text">{{ __('button.kemaskini') }}</span><i class="{{ __('icon.edit') }}"></i>
                                                    </a>
                                                    <a href="{{ route('user.resetPassword.sendLink', ['id' => $data->id]) }}" class="btn btn-outline-primary px-2 py-1 swal-reset-email-list-user tooltip-icon">
                                                        <span class="tooltip-text">{{ __('button.tukar_katalaluan') }}</span><i class="{{ __('icon.password') }}"></i>
                                                    </a>
                                                    @endif
                                                    @if(checkPolicy("D"))
                                                    <a class="btn btn-outline-primary px-2 py-1 swal-delete-list tooltip-icon" data-route="{{ route('user.validateDelete') }}" data-id="{{ $data->id }}">
                                                        <span class="tooltip-text">{{ __('button.hapus') }}</span><i class="{{ __('icon.delete') }}"></i>
                                                    </a>
                                                    @endif
                                                </div>
                                                <form method="POST" action="{{ route('user.delete') }}" class="delete-form-list">
                                                    {{ csrf_field() }}
                                                    {{ method_field('DELETE') }}
                                                    <input class="form-control" type="hidden" name="id" value="{{ $data->id }}">
                                                </form>
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
