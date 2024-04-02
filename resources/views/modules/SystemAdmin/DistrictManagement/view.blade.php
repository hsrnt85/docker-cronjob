@extends('layouts.master')

@section('content')

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">

                {{-- PAGE TITLE --}}
                <div class="border-bottom border-primary mb-4"><h4 class="card-title">{{ getPageTitle(1) }}</h4></div>

                <form>
                    <div class="mb-3 row">
                        <label for="name" class="col-md-2 col-form-label">Daerah</label>
                        <p class="col-md-9 col-form-label">{{ $districtManagement->district->district_name }}</p>
                    </div>

                    <div class="mb-3 row">
                        <label for="name" class="col-md-2 col-form-label">Nama Pegawai</label>
                        <p class="col-md-9 col-form-label">{{ $districtManagement->user->name }}</p>
                    </div>

                    <div class="mb-3 row">
                        <label for="name" class="col-md-2 col-form-label">Jawatan</label>
                        <p class="col-md-9 col-form-label">{{ $districtManagement->user->position->position_name }}</p>
                    </div>

                    <div class="mb-3 row">
                        <label for="name" class="col-md-2 col-form-label">Jabatan/Unit</label>
                        <p class="col-md-9 col-form-label">{{ $districtManagement->user->office->organization->name ?? '' }}</p>
                    </div>

                    <div class="mb-3 row">
                        <label for="name" class="col-md-2 col-form-label">Alamat Pejabat</label>
                        <p class="col-md-9 col-form-label">{{ $districtManagement->user->office->address_1 }}</p>
                    </div>

                    <div class="mb-3 row">
                        <label for="name" class="col-md-2 col-form-label"></label>
                        <p class="col-md-9 col-form-label">{{ $districtManagement->user->office->address_2 }}</p>
                    </div>

                    <div class="mb-3 row">
                        <label for="name" class="col-md-2 col-form-label"></label>
                        <p class="col-md-9 col-form-label">{{ $districtManagement->user->office->address_3 }}</p>
                    </div>

                    <div class="mb-3 row">
                        <label for="name" class="col-md-2 col-form-label">No Telefon</label>
                        <p class="col-md-9 col-form-label">{{ $districtManagement->user->office->phone_no_office }}</p>
                    </div>

                    <div class="mb-3 row">
                        <label for="name" class="col-md-2 col-form-label">Emel</label>
                        <p class="col-md-9 col-form-label">{{ $districtManagement->user->email }}</p>
                    </div>

                    <hr>

                    <div class="mb-3 row">
                        <div class="col-sm-12">
                            <a href="{{ route('districtManagement.edit', ['id' => $districtManagement->id]) }}" class="btn btn-primary float-end">{{ __('button.kemaskini') }}</a>
                            <a href="{{ route('districtManagement.index') }}" class="btn btn-secondary float-end me-2">{{ __('button.kembali') }}</a>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div> <!-- end col -->
</div>
<!-- end row -->

@endsection
