@extends('layouts.master')

@section('content')

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">

                {{-- PAGE TITLE --}}
                <div class="border-bottom border-primary mb-4"><h4 class="card-title">{{ getPageTitle(1) }}</h4></div>

                <form method="post" action="{{ route('officer.update') }}" >
                    {{ csrf_field() }}
                    <input type="hidden" name="id" value="{{ $officer->id }}">
                    <div class="mb-3 row">
                        <label class="col-md-2 col-form-label">Pegawai</label>
                        <div class="col-md-10">
                            <p class="col-form-label">{{ strtoupper($officer->user->name)}}</p>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label class="col-md-2 col-form-label">Daerah</label>
                        <div class="col-md-10">
                            <p class="col-form-label">{{ strtoupper($officer->district->district_name) }}</p>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label class="col-md-2 col-form-label">Kumpulan Pegawai</label>
                        <div class="col-md-10">
                            <p class="col-form-label">{{ strtoupper($officer->officer_group->officer_group ) }}</p>
                        </div>
                    </div>

                    @if($officerCategoryAll)
                        <hr>
                        @php
                            $checked_monitoring_district= ($officer->monitoring_district==1) ? 'checked' : '' ;
                        @endphp
                        <p class="card-title-desc">Senarai Kategori Pegawai</p>

                        <div class="row">
                            <div class="table-responsive col-sm-10 offset-sm-1">
                                <table class="table table-sm table-bordered" id="table-quarters-class">
                                    <thead>
                                        <tr>
                                            <th class="text-center">Bil</th>
                                            <th class="text-center">Kategori Pegawai</th>
                                            <th class="text-center" width="20%">Semua Daerah?</br>(Hanya Untuk Pegawai Pemantau)</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($officerCategoryAll as $officerCategory)
                                        <tr>
                                            <th scope="row" class="text-center">{{$loop->iteration}}</th>
                                            <td>{{$officerCategory->category_name}}</td>
                                            <td >
                                                @if($officerCategory->id==4)
                                                    <div class="form-check d-flex justify-content-center monitoring-district">
                                                        <input type="checkbox" class="form-check-input" id="monitoring_district" {{$checked_monitoring_district}} disabled>
                                                        <label class="form-check-label" for="monitoring_district"> Ya</label>
                                                    </div>
                                                @endif
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endif

                    <hr>
                    <div class="mb-3 row">
                        <div class="col-sm-12">
                            <!-- <a href="{{ route('officer.edit', ['id' => $officer->id]) }}" class="btn btn-primary float-end">{{ __('button.kemaskini') }}</a> -->
                            <a class="btn btn-danger float-end me-2 swal-delete">{{ __('button.hapus') }}</a>
                            <a href="{{ route('officer.index') }}" class="btn btn-secondary float-end me-2">{{ __('button.kembali') }}</a>
                        </div>
                    </div>
                </form>

                <form method="POST" action="{{ route('officer.delete') }}" id="delete-form">
                    {{ csrf_field() }}
                    {{ method_field('DELETE') }}
                    <input class="form-control" type="hidden" id="id-delete" name="id" value="{{ $officer->id }}">
                </form>
            </div>
        </div>
    </div> <!-- end col -->
</div>
<!-- end row -->

@endsection
