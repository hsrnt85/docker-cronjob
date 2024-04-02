@extends('layouts.master')

@section('content')

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">

                {{-- PAGE TITLE --}}
                <div class="border-bottom border-primary mb-4"><h4 class="card-title">{{ getPageTitle(1) }}</h4></div>

                {{-- <div class="row mb-2">
                    <div class="col-sm-12">
                        <a type="button" href="{{ route('quarters.create', ['quarters_cat_id' => 0]) }}" class="btn btn-success float-end waves-effect waves-light">{{ __('button.rekod_baru') }} - Kuarters</a>
                        <a type="button" href="{{ route('quarters.addUnitNo', ['quarters_cat_id' => 0]) }}" class="btn btn-success me-2 float-end waves-effect waves-light">{{ __('button.rekod_baru') }} - No Unit</a>
                    </div>
                </div> --}}
                <div id="datatable_wrapper">
                    <div class="row">
                        <div class="col-sm-12">
                            <table id="datatable" class="table table-striped table-bordered dt-responsive nowrap w-100 dataTable" role="grid" >
                                <thead class="bg-primary bg-gradient text-white">
                                    <tr role="row">
                                        <th class="text-center" width="4%">Bil</th>
                                        <th>Kategori Kuarters (Lokasi)</th>
                                        <th>Daerah</th>
                                        <th>Jenis Kuarters</th>
                                        <th>Bil Kuarters</th>
                                        <th class="text-center" width="10%">Tindakan</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach($quartersCategoryAll as $bil => $data)
                                        <tr>
                                            <th class="text-center" scope="row">{{ ++$bil }}</th>
                                            <td>{{ $data->name }}</td>
                                            <td>{{ $data->district?->district_name ?? '' }}</td>
                                            <td>{{ $data->landed_type?->type }}</td>
                                            <td>{{ $data->quarters?->count('id') }}</td>
                                            <td class="text-center">
                                                <div class="btn-group" role="group">
                                                    @if(checkPolicy("V"))
                                                        <a href="{{ route('quarters.index', ['quarters_cat_id' => $data->id]) }}" class="btn btn-outline-primary px-2 py-1 tooltip-icon">
                                                            <span class="tooltip-text">{{ __('button.papar') }}</span> <i class="{{ __('icon.view_folder') }}"></i>
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
