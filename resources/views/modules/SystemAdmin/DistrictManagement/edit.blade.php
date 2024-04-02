@extends('layouts.master')

@section('content')

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">

                {{-- PAGE TITLE --}}
                <div class="border-bottom border-primary mb-4"><h4 class="card-title">{{ getPageTitle(1) }}</h4></div>

                <form method="post" action="{{ route('districtManagement.update') }}" >
                    {{ csrf_field() }}

                    <input type="hidden" name="id" value="{{ $districtManagement->id }}">

                    <div class="mb-3 row">
                        <label for="name" class="col-md-2 col-form-label">Daerah</label>
                        <div class="col-md-10">
                            <select class="form-select @error('district') is-invalid @enderror" id="district" name="district" required data-route="{{ route('districtManagement.ajaxGetUser') }}">
                                <option value="">-- Pilih Daerah --</option>
                                @foreach($districtAll as $district)
                                    <option value="{{ $district->id }}" {{ $districtManagement->district_id == $district->id ? "selected" : "" }}>
                                        {{ $district->district_name }}
                                    </option>
                                @endforeach
                            </select>

                            @error('district')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="name" class="col-md-2 col-form-label">Nama Pegawai</label>
                        <div class="col-md-10">
                            <select class="form-select @error('user') is-invalid @enderror" id="user" name="user" required data-route="{{ route('districtManagement.ajaxGetUserData') }}">
                                <option value="">-- Pilih Pegawai --</option>
                                @foreach($userAll as $user)
                                    <option value="{{ $user->id }}" {{ $districtManagement->users_id == $user->id ? "selected" : "" }}>
                                        {{ $user->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('user')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                            <div id="user-feedback" class="invalid-feedback"></div>
                        </div>
                        <div id="user-loading" class="col-md-1"></div>
                    </div>

                    <div class="mb-3 row">
                        <label for="name" class="col-md-2 col-form-label">Jawatan</label>
                        <div class="col-md-10">
                            <input class="form-control @error('document') is-invalid @enderror" type="text" id="jawatan" value="{{ $districtManagement->user->position->position_name }}" readonly>
                            @error('document')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="col-md-1 spinner-wrapper"></div>
                    </div>

                    <div class="mb-3 row">
                        <label for="name" class="col-md-2 col-form-label">Jabatan/Unit</label>
                        <div class="col-md-10">
                            <input class="form-control @error('document') is-invalid @enderror" type="text" id="jabatan" value="{{ $districtManagement->user->office->organization->name ?? '' }}" readonly>
                            @error('document')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="col-md-1 spinner-wrapper"></div>
                    </div>

                    <div class="mb-3 row">
                        <label for="name" class="col-md-2 col-form-label">Alamat Pejabat</label>
                        <div class="col-md-10">
                            <input class="form-control @error('document') is-invalid @enderror" type="text" id="alamat1" value="{{ $districtManagement->user->office->address_1 }}" readonly>
                            @error('document')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="col-md-1 spinner-wrapper"></div>
                    </div>

                    <div class="mb-3 row">
                        <label for="name" class="col-md-2 col-form-label"></label>
                        <div class="col-md-10">
                            <input class="form-control @error('document') is-invalid @enderror" type="text" id="alamat2" value="{{ $districtManagement->user->office->address_2 }}" readonly>
                            @error('document')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="col-md-1 spinner-wrapper"></div>
                    </div>

                    <div class="mb-3 row">
                        <label for="name" class="col-md-2 col-form-label"></label>
                        <div class="col-md-10">
                            <input class="form-control @error('document') is-invalid @enderror" type="text" id="alamat3" value="{{ $districtManagement->user->office->address_3 }}" readonly>
                            @error('document')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="col-md-1 spinner-wrapper"></div>
                    </div>

                    <div class="mb-3 row">
                        <label for="name" class="col-md-2 col-form-label">No Telefon</label>
                        <div class="col-md-10">
                            <input class="form-control @error('document') is-invalid @enderror" type="text" id="no_tel" value="{{ $districtManagement->user->office->phone_no_office }}" readonly>
                            @error('document')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="col-md-1 spinner-wrapper"></div>
                    </div>

                    <div class="mb-3 row">
                        <label for="name" class="col-md-2 col-form-label">Emel</label>
                        <div class="col-md-10">
                            <input class="form-control @error('document') is-invalid @enderror" type="text" id="email" value="{{ $districtManagement->user->email }}" readonly>
                            @error('document')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="col-md-1 spinner-wrapper"></div>
                    </div>

                    <div class="mb-3 row">
                        <div class="col-sm-12">
                            <button type="submit" class="btn btn-primary float-end swal-kemaskini">{{ __('button.kemaskini') }}</button>
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

@section('script')
<script src="{{ URL::asset('assets/js/pages/DistrictManagement/districtManagement.js')}}"></script>
@endsection
