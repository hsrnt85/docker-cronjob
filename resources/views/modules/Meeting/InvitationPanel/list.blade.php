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
                            <a type="button" href="{{ route('invitationPanel.create') }}" class="btn btn-success float-end waves-effect waves-light">{{ __('button.tambah_panel') }}</a>
                        </div>
                    </div>
                @endif
                <div id="datatable_wrapper">
                    <div class="row">
                        <div class="col-sm-12">
                            <table id="datatable" class="table table-sm table-striped table-bordered dt-responsive wrap w-100 dataTable" role="grid" >
                                <thead class="bg-primary bg-gradient text-white">
                                    <tr role="row">
                                        <th class="text-center" width="4%">Bil</th>
                                        <th class="text-center" width="25%">Nama Panel</th>
                                        <th class="text-center" width="20%">Gelaran Jawatan</th>
                                        <th class="text-center" width="20%">Jabatan</th>
                                        <th class="text-center" >Email</th>
                                        <th class="text-center" width="10%">Tindakan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($invitationPanelAll as $data)
                                        <tr class="odd">
                                            <td class="text-center" tabindex="0">{{ $loop->iteration }}</td>
                                            <td class="">{{ $data->name }} </a></td>
                                            <td class="">{{ $data->position }} </a></td>
                                            <td class="">{{ $data->department }} </a></td>
                                            <td class="">{{ $data->email }} </a></td>
                                            <td class="text-center">
                                                <div class="btn-group" role="group">
                                                    @if(checkPolicy("V"))
                                                        <a href="{{ route('invitationPanel.view', ['id' => $data->id]) }}" class="btn btn-outline-primary px-2 py-1 tooltip-icon">
                                                            <span class="tooltip-text">{{ __('button.papar') }}</span><i class="{{ __('icon.view_folder') }}"></i>
                                                        </a>
                                                    @endif
                                                    @if(checkPolicy("U"))
                                                        <a href="{{ route('invitationPanel.edit', ['id' => $data->id]) }}" class="btn btn-outline-primary px-2 py-1 tooltip-icon">
                                                            <span class="tooltip-text">{{ __('button.kemaskini') }}</span> <i class="{{ __('icon.edit') }}"></i>
                                                        </a>
                                                    @endif
                                                    @if(checkPolicy("D"))
                                                        <a class="btn btn-outline-primary px-2 py-1 tooltip-icon swal-delete-list" data-route="{{ route('invitationPanel.validateDelete') }}" data-id="{{ $data->id }}">
                                                            <span class="tooltip-text">{{ __('button.hapus') }}</span> <i class="{{ __('icon.delete') }}"></i>
                                                        </a>
                                                    @endif
                                                </div>
                                                <form method="POST" action="{{ route('invitationPanel.delete') }}" class="delete-form-list">
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
