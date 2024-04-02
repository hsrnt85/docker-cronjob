@extends('layouts.master')

@section('content')

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">

                {{-- PAGE TITLE --}}
                <div class="border-bottom border-primary mb-4"><h4 class="card-title">{{ getPageTitle(1) }}</h4></div>

                <form method="post" action="{{ route('userApproval.update') }}" >
                    {{ csrf_field() }}
                    <input type="hidden" name="id" value="{{ $user->id }}">
                    <div class="mb-2 row">
                        <label class="col-md-2 col-form-label">Nama Pengguna</label>
                        <div class="col-md-10"><p class="col-form-label">{{ $user->name }}</p></div>
                    </div>
                    <div class="mb-2 row">
                        <label for="new_ic" class="col-md-2 col-form-label ">No. Kad Pengenalan (Baru)</label>
                        <div class="col-md-10"><p class="col-form-label">{{ $user->new_ic }}</p></div>
                    </div>

                    <div class="mb-2 row">
                        <label for="name" class="col-md-2 col-form-label ">Nama</label>
                        <div class="col-md-10"><p class="col-form-label">{{ $user->name }}</p></div>
                    </div>

                    <div class="mb-2 row">
                        <label for="email" class="col-md-2 col-form-label ">Emel</label>
                        <div class="col-md-10"><p class="col-form-label">{{ $user->email }}</p></div>
                    </div>

                    <div class="mb-2 row">
                        <label for="position" class="col-md-2 col-form-label ">Jawatan</label>
                        <div class="col-md-10"><p class="col-form-label">{{ $user->position->position_name ?? '' }}</p></div>
                    </div>

                    <div class="mb-2 row">
                        <label for="position_type" class="col-md-2 col-form-label ">Kod/ Gred Jawatan</label>
                        <div class="col-md-10"><p class="col-form-label">{{ $user->position_type->position_type ?? '' }} {{ $user->position_grade->grade_no ?? '' }}</p></div>
                    </div>

                    <div class="mb-2 row">
                        <label for="organization" class="col-md-2 col-form-label ">Jabatan/Agensi Bertugas</label>
                        <div class="col-md-10"><p class="col-form-label">{{ $userOffice->organization->name ?? '' }}</p></div>
                    </div>

                    <div class="mb-2 row">
                        <label for="services_type" class="col-md-2 col-form-label ">Jenis Perkhidmatan</label>
                        <div class="col-md-10"><p class="col-form-label">{{ $user->services_type->services_type }}</p></div>
                    </div>

                    <div class="mb-2 row">
                        <label for="district" class="col-md-2 col-form-label ">Daerah</label>
                        <div class="col-md-10"><p class="col-form-label">{{ $userOffice->district->district_name ?? '' }}</p></div>
                    </div>

                    <div class="mb-2 row">
                        <label for="roles" class="col-md-2 col-form-label ">Tetapan Peranan</label>
                        <div class="col-md-10">
                            <select class="form-control select2 @error('roles') is-invalid @enderror" id="roles" name="roles" value="{{ $user->roles }}">
                                <option value="">-- Pilih Tetapan Peranan --</option>
                                @foreach($rolesAll as $roles)
                                    @if($roles->id == $user->roles_id)
                                        <option value="{{ $roles->id }}" selected >{{ $roles->name }}</option>
                                    @else
                                        <option value="{{ $roles->id }}" >{{ $roles->name }}</option>
                                    @endif
                                @endforeach
                            </select>
                            @error('roles')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <hr>

                    <div class="mb-2 row">
                        <div class="col-sm-12">
                           <button type="submit" class="btn btn-primary float-end swal-approve">{{ __('button.pengesahan_pengguna') }}</button>
                           <a href="{{ route('userApproval.index') }}" class="btn btn-secondary float-end me-2">{{ __('button.kembali') }}</a>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div> <!-- end col -->
</div>
<!-- end row -->

@endsection
