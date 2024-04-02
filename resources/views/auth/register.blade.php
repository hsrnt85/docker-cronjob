@extends('layouts.master-auth')

@section('content')
        <div>
            <h5 class="text-primary">Daftar Pengguna Baru >></h5>
            <p class="text-primary">Sila Masukkan No. Kad Pengenalan. Sistem akan membuat semakan data HRMIS anda.</p>
        </div>
        <form class="custom-validation" id="form" method="post" action="{{ route('register.store') }}" >
            {{ csrf_field() }}

            <div class="mb-1 row">
                <label for="new_ic" class="col-form-label ">No. Kad Pengenalan (Baru)</label>
                <input class="form-control " id="new_ic" name="new_ic" value="{{ old('new_ic') }}" onkeyup="check_ic()"
                        data-route="{{ route('ajaxCheckIc') }}" data-route-hrmis="{{ route('ajaxProcessDataHrmis') }}" data-route-user="{{ route('ajaxGetDataUsers') }}" 
                        data-route-position-type="{{ route('ajaxGetDataPositionType') }}" data-route-services-type="{{ route('ajaxGetDataServiceType') }}"
                        minlength="12" maxlength="12" data-parsley-length-message="{{ setSessionMessage('new_ic.digits') }}"
                        required data-parsley-required-message="{{ setSessionMessage('new_ic.required') }}">
                <div id="new_ic_error"></div>
                @error('new_ic')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-1 row">
                <label for="name" class="col-form-label ">Nama</label>
                <input class="form-control @error('name') is-invalid @enderror user_info" id="name" name="name" value="{{ old('name') }}"
                        data-parsley-required-message="{{ setSessionMessage('name.required') }}"
                        disabled>
                <div class="spinner-wrapper"></div>
                @error('name')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-1 row">
                <label for="email" class="col-form-label ">Emel</label>
                <input class="form-control input-not-uppercase input-mask @error('email') is-invalid @enderror user_info" id="email" name="email" value="{{ old('email') }}" data-inputmask="'alias': 'email'"
                        disabled data-parsley-required-message="{{ setSessionMessage('email.required') }}"
                        type="email" data-parsley-type-message="{{ setSessionMessage('email.email') }}">
                <div class="spinner-wrapper"></div>

                @error('email')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-1 row">
                <label for="position" class="col-form-label ">Jawatan</label>
                <input class="form-control input-not-uppercase input-mask" id="position" name="position" value="{{ old('position') }}" disabled>
                <div class="spinner-wrapper"></div>

                <!-- <select class="form-control select2 @error('position') is-invalid @enderror user_info" id="position" name="position" value="{{ old('position') }}"
                    required data-parsley-required-message="{{ setSessionMessage('position.required') }}"
                    data-parsley-errors-container="#parsley-errors-position">
                    <option value="">-- Pilih Jawatan --</option>
                    @foreach($positionAll as $position)
                        <option value="{{ $position->id }}" >{{ $position->position_name }}</option>
                    @endforeach
                </select> -->
                <div id="parsley-errors-position"></div>
                @error('position')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror

            </div>

            <!-- <div class="mb-1 row">
                <label for="position_grade_code" class="col-form-label ">Kod Jawatan</label>
                <select class="form-control select2 @error('position_grade_code') is-invalid @enderror user_info" id="position_grade_code" name="position_grade_code" value="{{ old('position_type') }}"
                    required data-parsley-required-message="{{ setSessionMessage('position_grade_code.required') }}"
                    data-parsley-errors-container="#parsley-errors-position-grade-code">
                    <option value="">-- Pilih Kod Jawatan --</option>
                    @foreach($positionGradeCodeAll as $positionGradeCode)
                        <option value="{{ $positionGradeCode->id }}" >{{ $positionGradeCode->grade_type }}</option>
                    @endforeach
                </select>
                <div id="parsley-errors-position-grade-code"></div>
                @error('position_type')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-1 row">
                <label for="position_grade" class="col-form-label ">Gred Jawatan</label>
                <select class="form-control select2 @error('position_grade') is-invalid @enderror user_info" id="position_grade" name="position_grade" value="{{ old('position_grade') }}"
                    required data-parsley-required-message="{{ setSessionMessage('position_grade.required') }}"
                    data-parsley-errors-container="#parsley-errors-position-grade">
                    <option value="">-- Pilih Gred Jawatan --</option>
                    @foreach($positionGradeAll as $positionGrade)
                        <option value="{{ $positionGrade->id }}" > {{ $positionGrade->grade_no }}</option>
                    @endforeach
                </select>
                <div id="parsley-errors-position-grade"></div>
                @error('position_grade')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div> -->

            <div class="mb-1 row">
                <label for="position_type" class="col-form-label ">Jenis Lantikan</label>
                    <input class="form-control input-not-uppercase input-mask" id="position_type" name="position_type" value="{{ old('position_type') }}" disabled>
                    <!-- <select class="form-control @error('position_type') is-invalid @enderror user_info" id="position_type" name="position_type" value="{{ old('position_type') }}"
                        disabled data-parsley-required-message="{{ setSessionMessage('position_type.required') }}"
                        data-parsley-errors-container="#parsley-errors-position-type"
                        >
                        <option value=""></option>
                        @foreach($positionTypeAll as $positionType)
                            <option value="{{ $positionType->position_code }}" >{{ $positionType->position_type }}</option>
                        @endforeach
                    </select> -->
                    <div class="spinner-wrapper"></div>

                    <div id="parsley-errors-position-type"></div>
                    @error('position_type')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
            </div>

            <div class="mb-1 row">
                <label for="services_type" class="col-form-label ">Jenis Perkhidmatan</label>
                <input class="form-control input-not-uppercase input-mask" id="services_type" name="services_type" value="{{ old('services_type') }}" disabled>
                <!-- <select class="form-control  @error('services_type') is-invalid @enderror user_info" id="services_type" name="services_type" value="{{ old('services_type') }}"
                    disabled data-parsley-required-message="{{ setSessionMessage('services_type.required') }}"
                    data-parsley-errors-container="#parsley-errors-services-type"
                    >
                    <option value=""></option>
                    @foreach($servicesTypeAll as $servicesType)
                        <option value="{{ $servicesType->code }}" >{{ $servicesType->services_type }}</option>
                    @endforeach
                </select> -->
                <div class="spinner-wrapper"></div>

                <div id="parsley-errors-services-type"></div>
                @error('services_type')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-1 row">
                <label for="organization" class="col-form-label ">Jabatan/Agensi Bertugas</label>
                <input class="form-control input-not-uppercase input-mask" id="organization" name="organization" value="{{ old('organization') }}" disabled>
                <div class="spinner-wrapper"></div>

                <!-- <select class="form-control select2  @error('organization') is-invalid @enderror user_info" id="organization" name="organization" value="{{ old('organization') }}"
                    required data-parsley-required-message="{{ setSessionMessage('organization.required') }}"
                    data-parsley-errors-container="#parsley-errors-organization">
                    <option value="">-- Pilih Jabatan/Agensi Bertugas --</option>
                    @foreach($organizationAll as $organization)
                        <option value="{{ $organization->id }}" >{{ $organization->name }}</option>
                    @endforeach
                </select> -->
                <div id="parsley-errors-organization"></div>
                @error('organization')
                    <span class="invalid-feedback" >{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-1 row">
                <label for="district" class="col-form-label ">Daerah</label>
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

            <div class="mt-2 d-grid">
                <button class="btn btn-primary waves-effect waves-light mb-2" disabled id="btn-submit" name="submit" type="submit">Daftar</button>
                <a href="{{ route('login') }}" class="btn btn-secondary waves-effect waves-light">Kembali</a>
            </div>

        </form>

@endsection

@section('script')
<script src="{{ URL::asset('assets/js/pages/User/user.js')}}"></script>
@endsection
