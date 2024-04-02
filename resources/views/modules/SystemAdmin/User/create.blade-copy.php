@extends('layouts.master')

@section('content')

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">

                <form class="custom-validation" id="form" method="post" action="{{ route('user.store') }}" >
                    {{ csrf_field() }}

                    <div class="mb-3 row">
                        <label for="name" class="col-md-3 col-form-label ">Nama</label>
                        <div class="col-md-9">
                            <input class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}"
                                    required data-parsley-required-message="{{ setMessage('name.required') }}">
                            @error('name')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="new_ic" class="col-md-3 col-form-label ">No. Kad Pengenalan (Baru)</label>
                        <div class="col-md-9">
                            <input class="form-control @error('new_ic') is-invalid @enderror input-mask numeric" id="new_ic" name="new_ic" value="{{ old('new_ic') }}" onkeyup="check_ic_admin()" 
                                    data-route="{{ route('user.ajaxCheckIcAdmin') }}" data-inputmask="'mask': '9', 'repeat': 12, 'greedy' : false" type="text"
                                    minlength="12" maxlength="12" data-parsley-length-message="{{ setMessage('new_ic.digits') }}"
                                    required data-parsley-required-message="{{ setMessage('new_ic.required') }}">
                            <div id="new_ic_error"></div>
                            @error('new_ic')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="email" class="col-md-3 col-form-label ">Emel</label>
                        <div class="col-md-9">
                            <input class="form-control input-not-uppercase input-mask @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" data-inputmask="'alias': 'email'"
                                    required data-parsley-required-message="{{ setMessage('email.required') }}"
                                    required data-parsley-required-message="{{ setMessage('email.email') }}">
                            @error('email')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="position" class="col-md-3 col-form-label ">Jawatan</label>
                        <div class="col-md-9">
                            <select class="form-control select2 @error('position') is-invalid @enderror" id="position" name="position" value="{{ old('position') }}"
                                required data-parsley-required-message="{{ setMessage('position.required') }}"
                                data-parsley-errors-container="#parsley-errors-position">
                                <option value="">-- Pilih Jawatan --</option>
                                @foreach($positionAll as $position)
                                    <option value="{{ $position->id }}" >{{ $position->position_name }}</option>
                                @endforeach
                            </select>
                            <div id="parsley-errors-position"></div>
                            @error('position')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="position_grade_code" class="col-md-3 col-form-label ">Kod Jawatan</label>
                        <div class="col-md-9">
                            <select class="form-control select2 @error('position_grade_code') is-invalid @enderror" id="position_grade_code" name="position_grade_code" value="{{ old('position_type') }}"
                                required data-parsley-required-message="{{ setMessage('position_grade_code.required') }}"
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
                    </div>

                    <div class="mb-3 row">
                        <label for="position_grade" class="col-md-3 col-form-label ">Gred Jawatan</label>
                        <div class="col-md-9">
                            <select class="form-control select2 @error('position_grade') is-invalid @enderror" id="position_grade" name="position_grade" value="{{ old('position_grade') }}"
                                required data-parsley-required-message="{{ setMessage('position_grade.required') }}"
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
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="position_type" class="col-md-3 col-form-label ">Jenis Lantikan</label>
                        <div class="col-md-9">
                            <select class="form-control select2 @error('position_type') is-invalid @enderror" id="position_type" name="position_type" value="{{ old('position_type') }}"
                                required data-parsley-required-message="{{ setMessage('position_type.required') }}"
                                data-parsley-errors-container="#parsley-errors-position-type">
                                <option value="">-- Pilih Jenis Lantikan --</option>
                                @foreach($positionTypeAll as $positionType)
                                    <option value="{{ $positionType->id }}" >{{ $positionType->position_type }}</option>
                                @endforeach
                            </select>
                            <div id="parsley-errors-position-type"></div>
                            @error('position_type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-2 row">
                        <label for="services_type" class="col-md-3 col-form-label ">Jenis Perkhidmatan</label>
                        <div class="col-md-9">
                            <select class="form-control select2 @error('services_type') is-invalid @enderror" id="services_type" name="services_type" value="{{ old('services_type') }}"
                                required data-parsley-required-message="{{ setMessage('services_type.required') }}"
                                data-parsley-errors-container="#parsley-errors-services-type">
                                <option value="">-- Pilih Jenis Perkhidmatan --</option>
                                @foreach($servicesTypeAll as $servicesType)
                                    <option value="{{ $servicesType->id }}" >{{ $servicesType->services_type }}</option>
                                @endforeach
                            </select>
                            <div id="parsley-errors-services-type"></div>
                            @error('services_type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="organization" class="col-md-3 col-form-label ">Jabatan/Agensi Bertugas</label>
                        <div class="col-md-9">
                            <select class="form-control select2  @error('organization') is-invalid @enderror" id="organization" name="organization" value="{{ old('organization') }}"
                                required data-parsley-required-message="{{ setMessage('organization.required') }}"
                                data-parsley-errors-container="#parsley-errors-organization">
                                <option value="">-- Pilih Jabatan/Agensi Bertugas --</option>
                                @foreach($organizationAll as $organization)
                                    <option value="{{ $organization->id }}" >{{ $organization->name }}</option>
                                @endforeach
                            </select>
                            <div id="parsley-errors-organization"></div>
                            @error('organization')
                                <span class="invalid-feedback" >{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="district" class="col-md-3 col-form-label ">Daerah</label>
                        <div class="col-md-9">
                            <select class="form-control select2 @error('district') is-invalid @enderror" id="district" name="district" value="{{ old('district') }}"
                                required data-parsley-required-message="{{ setMessage('district.required') }}"
                                data-parsley-errors-container="#parsley-errors-district">
                                <option value="">-- Pilih Daerah --</option>
                                @foreach($districtAll as $district)
                                    <option value="{{ $district->id }}" >{{ $district->district_name }}</option>
                                @endforeach
                            </select>
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
