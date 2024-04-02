@extends('layouts.master')

@section('content')

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">

                {{-- PAGE TITLE --}}
                <div class="border-bottom border-primary mb-4"><h4 class="card-title">{{ getPageTitle(1) }}</h4></div>

                <form method="post" action="{{ route('districtManagement.store') }}" >
                    {{ csrf_field() }}

                    <div class="mb-3 row">
                        <label for="name" class="col-md-2 col-form-label">Daerah</label>
                        <div class="col-md-10">
                            <select class="form-select @error('district') is-invalid @enderror" id="district" name="district" required data-route="{{ route('districtManagement.ajaxGetUser') }}">
                                <option value="">-- Pilih Daerah --</option>
                                @foreach($districtAll as $district)
                                    <option value="{{ $district->id }}" {{ old('district') == $district->id ? "selected" : "" }}>
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
                            <select class="form-select @error('user') is-invalid @enderror" id="user" name="user" required data-route="{{ route('districtManagement.ajaxGetUserData') }}" disabled></select>
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
                            <input class="form-control @error('document') is-invalid @enderror" type="text" id="jawatan" readonly>
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
                            <input class="form-control @error('document') is-invalid @enderror" type="text" id="jabatan" readonly>
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
                            <input class="form-control @error('document') is-invalid @enderror" type="text" id="alamat1" readonly>
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
                            <input class="form-control @error('document') is-invalid @enderror" type="text" id="alamat2" readonly>
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
                            <input class="form-control @error('document') is-invalid @enderror" type="text" id="alamat3" readonly>
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
                            <input class="form-control @error('document') is-invalid @enderror" type="text" id="no_tel" readonly>
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
                            <input class="form-control @error('document') is-invalid @enderror" type="text" id="email" readonly>
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
                            <button type="submit" class="btn btn-primary float-end swal-tambah">{{ __('button.simpan') }}</button>
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
