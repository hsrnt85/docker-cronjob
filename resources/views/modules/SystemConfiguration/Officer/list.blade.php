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
                            <a type="button" href="{{ route('officer.create') }}" class="btn btn-success float-end waves-effect waves-light">{{ __('button.rekod_baru') }}</a>
                        </div>
                    </div>
                @endif

                <div id="datatable_wrapper">
                    <div class="row">
                        <div class="col-sm-12">
                            <table id="datatable" class="table table-striped table-bordered dt-responsive nowrap w-100 dataTable" role="grid" >
                                <thead class="bg-primary bg-gradient text-white">
                                    <tr role="row">
                                        <th class="text-center" width="4%">Bil</th>
                                        <th class="align-left" >Nama</th>
                                        <th class="text-center" >Kad Pengenalan</th>
                                        <th class="align-left" >Daerah</th>
                                        <th class="align-left" >Kumpulan Pegawai</th>
                                        <th class="align-left" >Kategori Pegawai</th>
                                        <th class="text-center" width="10%">Tindakan</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    @foreach ($officerAll as $data)

                                        <tr class="odd">
                                            <td class="text-center" tabindex="0">{{ $loop->iteration }}</td>
                                            <td class="align-left">{{strtoupper($data->user->name ?? '')}}</td>
                                            <td class="align-left">{{$data->user?->new_ic }}</td>
                                            <td class="align-left">{{$data->district->district_name ?? ''}}</td>
                                            <td class="align-left">{{ $data->officer_group?->officer_group }}</td>
                                            <td class="align-left">
                                                @foreach($officerCategoryAll as $officerCategory)
                                                        @php
                                                            $category_name = (inArray($data->officer_category_id, $officerCategory->id)) ? $officerCategory->category_name : '';
                                                        @endphp
                                                       @if ($category_name != null) {{'- '.$category_name}} <br> @endif
                                                @endforeach
                                            </td>
                                            <td class="text-center">
                                                <div class="btn-group" role="group">
                                                    @if(checkPolicy("V"))
                                                        <a href="{{ route('officer.view', ['id' => $data->id]) }}" class="btn btn-outline-primary px-2 py-1 tooltip-icon">
                                                            <span class="tooltip-text">{{ __('button.papar') }}</span> <i class="{{ __('icon.view_folder') }}"></i>
                                                        </a>
                                                    @endif
                                                    @if(checkPolicy("U"))
                                                        <a href="{{ route('officer.edit', ['id' => $data->id]) }}" class="btn btn-outline-primary px-2 py-1 tooltip-icon">
                                                            <span class="tooltip-text">{{ __('button.kemaskini') }}</span> <i class="{{ __('icon.edit') }}"></i>
                                                        </a>
                                                    @endif
                                                    @if(checkPolicy("D"))
                                                        <a class="btn btn-outline-primary px-2 py-1 tooltip-icon swal-delete-list" data-route="{{ route('officer.validateDelete') }}" data-id="{{ $data->id }}">
                                                            <span class="tooltip-text">{{ __('button.hapus') }}</span> <i class="{{ __('icon.delete') }}"></i>
                                                        </a>
                                                    @endif
                                                </div>
                                                <form method="POST" action="{{ route('officer.delete') }}" class="delete-form-list">
                                                    {{ csrf_field() }}
                                                    {{ method_field('DELETE') }}
                                                    <input type="hidden" name="id" value="{{ $data->id }}">
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
@section('script')

@endsection
