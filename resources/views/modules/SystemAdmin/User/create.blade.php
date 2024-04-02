@extends('layouts.master')

@section('content')

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">

                {{-- PAGE TITLE --}}
                <div class="border-bottom border-primary mb-4"><h4 class="card-title">{{ getPageTitle(1) }}</h4></div>

                <form class="custom-validation" id="form" method="post" action="{{ route('user.store') }}" >
                    {{ csrf_field() }}

                    <div class="mb-3 row">
                        <label for="new_ic" class="col-md-3 col-form-label ">No. Kad Pengenalan (Baru)</label>
                        <div class="col-md-9">
                            <input class="form-control numeric" id="new_ic" name="new_ic" value="{{ old('new_ic') }}" onkeyup="check_ic()"
                                    data-route="{{ route('ajaxCheckIcAdmin') }}" data-inputmask="'mask': '9', 'repeat': 12, 'greedy' : false" type="text"
                                    data-route-hrmis="{{ route('ajaxProcessDataHrmisAdmin') }}" data-route-user="{{ route('ajaxGetDataUsersAdmin') }}" 
                                    data-route-position-type="{{ route('ajaxGetDataPositionTypeAdmin') }}" data-route-services-type="{{ route('ajaxGetDataServiceTypeAdmin') }}"
                                    minlength="12" maxlength="12" data-parsley-length-message="{{ setMessage('new_ic.digits') }}"
                                    required data-parsley-required-message="{{ setMessage('new_ic.required') }}">
                            <div id="new_ic_error"></div>
                            @error('new_ic')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="name" class="col-md-3 col-form-label ">Nama</label>
                        <div class="col-md-9">
                            <input class="form-control @error('name') is-invalid @enderror user_info" id="name" name="name" value="{{ old('name') }}"
                                    data-parsley-required-message="{{ setMessage('name.required') }}" readonly>
                            <div class="spinner-wrapper"></div>
                            @error('name')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="email" class="col-md-3 col-form-label ">Emel</label>
                        <div class="col-md-9">
                            <input class="form-control input-not-uppercase input-mask @error('email') is-invalid @enderror user_info" id="email" name="email" value="{{ old('email') }}" data-inputmask="'alias': 'email'"
                                readonly data-parsley-required-message="{{ setMessage('email.required') }}"
                                type="email" data-parsley-required-message="{{ setMessage('email.email') }}">
                            <div class="spinner-wrapper"></div>
                            @error('email')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="position" class="col-md-3 col-form-label ">Jawatan</label>
                        <div class="col-md-9">
                            <input class="form-control nput-not-uppercase input-mask" id="position" name="position" value="{{ old('position') }}" readonly>
                            <div class="spinner-wrapper"></div>
                            <div id="parsley-errors-position"></div>
                            @error('position')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="position_type" class="col-md-3 col-form-label ">Jenis Lantikan</label>
                        <div class="col-md-9">
                            <input class="form-control select2 @error('position_type') is-invalid @enderror user_info" id="position_type" name="position_type" value="{{ old('position_type') }}"
                                readonly data-parsley-required-message="{{ setMessage('position_type.required') }}"
                                data-parsley-errors-container="#parsley-errors-position-type">
                            <div class="spinner-wrapper"></div>
                            <div id="parsley-errors-position-type"></div>
                            @error('position_type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-2 row">
                        <label for="services_type" class="col-md-3 col-form-label ">Jenis Perkhidmatan</label>
                        <div class="col-md-9">
                            <input class="form-control @error('services_type') is-invalid @enderror user_info" id="services_type" name="services_type" value="{{ old('services_type') }}"
                                readonly data-parsley-required-message="{{ setMessage('services_type.required') }}"
                                data-parsley-errors-container="#parsley-errors-services-type">
                            <div class="spinner-wrapper"></div>
                            <div id="parsley-errors-services-type"></div>
                            @error('services_type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="organization" class="col-md-3 col-form-label ">Jabatan/Agensi Bertugas</label>
                        <div class="col-md-9">
                            <input class="form-control input-not-uppercase input-mask" id="organization" name="organization" value="{{ old('organization') }}" readonly>
                            <div class="spinner-wrapper"></div>
                            <div id="parsley-errors-organization"></div>
                            @error('organization')
                                <span class="invalid-feedback" >{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="district" class="col-md-3 col-form-label ">Daerah</label>
                        <div class="col-md-9">
                            <select class="form-control @error('district') is-invalid @enderror user_info" id="district" name="district" value="{{ old('district') }}"
                                disabled data-parsley-required-message="{{ setSessionMessage('district.required') }}"
                                data-parsley-errors-container="#parsley-errors-district">
                                <option value=""></option>
                                @foreach($postcodeAll as $postcode)
                                    <option value="{{ $postcode->postcode }}" >{{ $postcode->district->district_name }}</option>
                                @endforeach
                            </select>
                            <div class="spinner-wrapper"></div>
                            <div id="parsley-errors-district"></div>
                            @error('district')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="roles" class="col-md-3 col-form-label ">Peranan</label>
                        <div class="col-md-9">
                            <select class="form-control select2 @error('roles') is-invalid @enderror" id="roles" name="roles" value="{{ old('roles') }}"
                                required data-parsley-required-message="{{ setMessage('roles.required') }}"
                                data-parsley-errors-container="#parsley-errors-roles">
                                <option value="">-- Pilih Peranan --</option>
                                @foreach($rolesAll as $roles)
                                    <option value="{{ $roles->id }}" >{{ $roles->name }}</option>
                                @endforeach
                            </select>
                            <div id="parsley-errors-roles"></div>
                            @error('roles')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="border-top row">
                        <div class="col-sm-12 mt-3">
                            <button type="submit" id="btn-submit" class="btn btn-primary float-end swal-tambah">{{ __('button.simpan') }}</button>
                            <a href="{{ route('user.index') }}" class="btn btn-secondary float-end me-2">{{ __('button.kembali') }}</a>
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
<script src="{{ URL::asset('assets/js/pages/User/user.js')}}"></script>
@endsection
