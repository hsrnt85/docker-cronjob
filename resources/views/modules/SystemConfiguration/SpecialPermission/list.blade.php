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
                            <a type="button" href="{{ route('specialPermission.create') }}" class="btn btn-success float-end waves-effect waves-light">{{ __('button.rekod_baru') }}</a>
                        </div>
                    </div>
                @endif
                <div id="datatable_wrapper">
                    <div class="row">
                        <div class="col-sm-12">
                            <table id="datatable" class="table table-striped table-bordered dt-responsive wrap w-100 dataTable" role="grid">
                                <thead class="bg-primary bg-gradient text-white">
                                    <tr role="row">
                                        <th class="text-center" width="4%">Bil</th>
                                        <th class="text-center">Nama</th>
                                        <th class="text-center" width="15%">No. Kad Pengenalan (Baru)</th>
                                        <th class="text-center">Jawatan</th>
                                        <th class="text-center">Jabatan/Agensi Bertugas</th>
                                        <th class="text-center" width="10%">Tindakan</th>
                                    </tr>
                                </thead>
                                <tbody class="table-striped">
                                    @if ($listSpecialPermission -> count() == 0)
                                        <tr>
                                            <td class="text-center" colspan="3">Tiada Rekod</td>
                                        </tr>
                                    @endif
                                    @foreach($listSpecialPermission as $bil => $specialPermission)
                                        <tr>
                                            <th class="text-center" scope="row">{{ ++$bil }}</th>
                                            <td style="height:40px">{{ strtoupper($specialPermission -> name ??'') }}</a></td>
                                            <td>{{ $specialPermission -> new_ic ??'' }}</a></td>
                                            <td>
                                                @if ($specialPermission->ui_position_name)
                                                    {{ $specialPermission->ui_position_name ?? '' }} {{ $specialPermission->ui_grade_type ?? '' }}{{ $specialPermission->ui_grade_no ?? '' }}
                                                @else
                                                    {{ $specialPermission -> position_name ??'' }} {{ $specialPermission -> grade_type ??'' }}{{ $specialPermission -> grade_no ??'' }}
                                                @endif
                                            </td>
                                            <td>{{ Str::upper($specialPermission -> organization_name ??'') }}</a></td>
                                            <td class="text-center">
                                                <div class="btn-group" role="group">
                                                    @if(checkPolicy("V"))
                                                        <a href="{{ route('specialPermission.view', ['id' => $specialPermission->id]) }}" class="btn btn-outline-primary px-2 py-1 tooltip-icon">
                                                            <span class="tooltip-text">{{ __('button.papar') }}</span> <i class="{{ __('icon.view_folder') }}"></i>
                                                        </a>
                                                    @endif
                                                    @if(checkPolicy("U"))
                                                        <a href="{{ route('specialPermission.edit', ['id' => $specialPermission->id]) }}" class="btn btn-outline-primary px-2 py-1 tooltip-icon">
                                                            <span class="tooltip-text">{{ __('button.kemaskini') }}</span> <i class="{{ __('icon.edit') }}"></i>
                                                        </a>
                                                    @endif
                                                    @if(checkPolicy("D"))
                                                        <a class="btn btn-outline-primary px-2 py-1 tooltip-icon swal-delete-list">
                                                            <span class="tooltip-text">{{ __('button.hapus') }}</span> <i class="{{ __('icon.delete') }}"></i>
                                                        </a>
                                                    @endif
                                                </div>
                                                <form method="POST" action="{{ route('specialPermission.delete') }}" class="delete-form-list">
                                                    {{ csrf_field() }}
                                                    {{ method_field('DELETE') }}
                                                    <input class="form-control" type="hidden" name="id" value="{{ $specialPermission->id }}">
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
