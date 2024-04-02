@extends('layouts.master')

@section('content')

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">

                {{-- PAGE TITLE --}}
                <div class="border-bottom border-primary mb-4"><h4 class="card-title">{{ getPageTitle(1) }}</h4></div>

                <form class="custom-validation" id="form" method="post" action="{{ route('user.update') }}" >
                    {{ csrf_field() }}

                    <input class="form-control" type="hidden" id="id" name="id" value="{{ $user->id }}">

                    <div class="mb-3 row">
                        <label for="new_ic" class="col-md-3 col-form-label ">No. Kad Pengenalan (Baru)</label>
                        <div class="col-md-9">
                            <p class="col-form-label">{{ $user->new_ic }}</p>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="name" class="col-md-3 col-form-label ">Nama</label>
                        <div class="col-md-9">
                            <p class="col-form-label">{{ $user->name }}</p>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="email" class="col-md-3 col-form-label ">Emel</label>
                        <div class="col-md-9">{{ $user->email }}</div>
                    </div>

                    <div class="mb-3 row">
                        <label for="position" class="col-md-3 col-form-label ">Jawatan</label>
                        <div class="col-md-9">
                            <p class="col-form-label">{{ $user->position->position_name }} - {{ $user->position_grade_code?->grade_type }} {{ $user->position_grade->grade_no }}</p>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="position_type" class="col-md-3 col-form-label ">Jenis Lantikan</label>
                        <div class="col-md-9">
                            <p class="col-form-label">{{ $user->position_type->position_type }}</p>
                        </div>
                    </div>

                    <div class="mb-2 row">
                        <label for="services_type" class="col-md-3 col-form-label ">Jenis Perkhidmatan</label>
                        <div class="col-md-9">
                            <p class="col-form-label">{{ $user->services_type->services_type ?? '' }}</p>
                        </div>
                    </div>

                    <div class="mb-4 row">
                        <label for="organization" class="col-md-3 col-form-label ">Jabatan/Agensi Bertugas</label>
                        <div class="col-md-9">
                            <p class="col-form-label">{{ $user->office->organization->name ?? '' }}</p>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="district" class="col-md-3 col-form-label ">Daerah</label>
                        <div class="col-md-9">
                            <p class="col-form-label">{{ $user->office->district->district_name ?? '' }}</p>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="roles" class="col-md-3 col-form-label ">Peranan</label>
                        <div class="col-md-9">
                            <select class="form-control select2 @error('roles') is-invalid @enderror" id="roles" name="roles" value="{{ old('roles',$user->roles) }}"
                                required data-parsley-required-message="{{ setMessage('roles.required') }}"
                                data-parsley-errors-container="#parsley-errors-roles">
                                <option value="">-- Pilih Peranan --</option>
                                @foreach($rolesAll as $roles)
                                    @if($roles->id == $user->roles_id)
                                        <option value="{{ $roles->id }}" selected >{{ $roles->name }}</option>
                                    @else
                                        <option value="{{ $roles->id }}" >{{ $roles->name }}</option>
                                    @endif
                                @endforeach
                            </select>
                            <div id="parsley-errors-roles"></div>
                            @error('roles')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="system_platform" class="col-md-3 col-form-label ">Platform Sistem</label>
                        <div class="col-md-9">
                            <select class="form-control select2 @error('system_platform') is-invalid @enderror" id="system_platform" name="system_platform" value="{{ old('system_platform',$user->flag) }}"
                                required data-parsley-required-message="{{ setMessage('system_platform.required') }}"
                                data-parsley-errors-container="#parsley-errors-system-platform">
                                <option value="">-- Pilih Platform Sistem --</option>
                                @foreach($systemPlatformAll as $systemPlatform)
                                    @if($systemPlatform->id == $user->flag)
                                        <option value="{{ $systemPlatform->id }}" selected >{{ $systemPlatform->name }}</option>
                                    @else
                                    <option value="{{ $systemPlatform->id }}" >{{ $systemPlatform->name }}</option>
                                    @endif
                                @endforeach
                            </select>
                            <div id="parsley-errors-system-platform"></div>
                            @error('roles')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="status" class="col-md-3 col-form-label ">Status Pengguna</label>
                        <div class="col-md-9">
                            <select class="form-select @error('status') is-invalid @enderror"  name="status" value="{{ old('status' ) }}"
                                required data-parsley-required-message="{{ setMessage('status.required') }}"
                                data-parsley-errors-container="#parsley-errors-status">
                                <option value="">-- Pilih Status Pengguna --</option>
                                @foreach($activeStatusAll as $activeStatus)
                                     <option value="{{ $activeStatus->id }}" @if($user->data_status==$activeStatus->id) selected @endif>{{ $activeStatus->status }}</option>
                                @endforeach
                            </select>
                            <div id="parsley-errors-status"></div>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <div class="col-sm-12">
                            <button type="submit" class="btn btn-primary float-end swal-kemaskini">{{ __('button.kemaskini') }}</button>
                            <a href="{{ route('user.index') }}" class="btn btn-secondary float-end me-2">{{ __('button.kembali') }}</a>
                        </div>
                    </div>

                </form>

                <form method="POST" action="{{ route('user.delete') }}" id="delete-form">
                    {{ csrf_field() }}
                    {{ method_field('DELETE') }}
                    <input class="form-control" type="hidden" id="id-delete" name="id" value="{{ $user->id }}">
                </form>

            </div>
        </div>
    </div> <!-- end col -->
</div>
<!-- end row -->

@endsection
