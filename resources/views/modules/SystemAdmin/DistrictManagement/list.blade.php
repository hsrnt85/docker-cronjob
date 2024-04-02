@extends('layouts.master')

@section('content')

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">

                {{-- PAGE TITLE --}}
                <div class="border-bottom border-primary mb-4"><h4 class="card-title">{{ getPageTitle(1) }}</h4></div>

                <div class="row mb-2">
                    <div class="col-sm-12">
                        <a type="button" href="{{ route('districtManagement.create') }}" class="btn btn-success float-end waves-effect waves-light">{{ __('button.rekod_baru') }}</a>
                    </div>
                </div>
                <div id="datatable_wrapper">
                    <div class="row">
                        <div class="col-sm-12">
                            <table id="datatable" class="table table-bordered dt-responsive nowrap w-100 dataTable" role="grid" >
                                <thead class="bg-primary bg-gradient text-white">
                                    <tr role="row">
                                        <th class="text-center" >Bil</th>
                                        <th class="text-center" >Daerah</th>
                                        <th class="text-center" >Pegawai</th>
                                        <th class="text-center" >Jabatan/Unit</th>
                                        <th class="text-center" >Tindakan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($districtManagementAll as $districtManagement)
                                        <tr class="odd">
                                            <td class="text-center" tabindex="0">{{ $loop->iteration }}</td>
                                            <td class="text-center">{{$districtManagement->district->district_name}}</td>
                                            <td class="text-center">{{$districtManagement->user->name}}</td>
                                            <td class="text-center">{{$districtManagement->user->office->organization->name ?? ''}}</td>
                                            <td class="text-center">
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('districtManagement.view', ['id' => $districtManagement->id]) }}" class="btn btn-outline-primary px-2 py-1"><i class="mdi mdi-folder-search mdi-18px"></i></a>
                                                    <a href="{{ route('districtManagement.edit', ['id' => $districtManagement->id]) }}" class="btn btn-outline-primary px-2 py-1"><i class="mdi mdi-pencil mdi-18px"></i></a>
                                                    <a class="btn btn-outline-primary px-2 py-1 swal-delete-list"><i class="mdi mdi-delete mdi-18px"></i></a>
                                                </div>
                                                <form method="POST" action="{{ route('districtManagement.delete') }}" class="delete-form-list">
                                                    {{ csrf_field() }}
                                                    {{ method_field('DELETE') }}
                                                    <input class="form-control" type="hidden" name="id" value="{{ $districtManagement->id }}">
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
