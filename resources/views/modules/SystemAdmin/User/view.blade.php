@extends('layouts.master')

@section('content')

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">

                {{-- PAGE TITLE --}}
                <div class="border-bottom border-primary mb-4"><h4 class="card-title">{{ getPageTitle(1) }}</h4></div>

                <form >
                    {{ csrf_field() }}
                    <input type="hidden" name="id" value="{{ $user->id }}">
                    <div class="mb-3 row">
                        <label class="col-md-2 col-form-label">Nama Pengguna</label>
                        <div class="col-md-9">
                            <p class="col-form-label">{{ $user->name }}</p>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label class="col-md-2 col-form-label ">No. Kad Pengenalan (Baru)</label>
                        <div class="col-md-9">
                            <p class="col-form-label">{{ $user->new_ic }}</p>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label class="col-md-2 col-form-label">Emel</label>
                        <div class="col-md-9">
                            <p class="col-form-label">{{ $user->email }}</p>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label class="col-md-2 col-form-label">Jawatan</label>
                        <div class="col-md-9">
                            <p class="col-form-label">{{ $user->position->position_name }} - {{ $user->position_grade_code?->grade_type }} {{ $user->position_grade->grade_no }}</p>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label class="col-md-2 col-form-label">Jenis Lantikan</label>
                        <div class="col-md-9">
                            <p class="col-form-label">{{ $user->position_type->position_type }}</p>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label class="col-md-2 col-form-label">Jabatan/Agensi Bertugas</label>
                        <div class="col-md-9">
                            <p class="col-form-label">{{ $user->office->organization->name ?? '' }}</p>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label class="col-md-2 col-form-label">Daerah</label>
                        <div class="col-md-9">
                            <p class="col-form-label">{{ $user->office->district->district_name ?? '' }}</p>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label class="col-md-2 col-form-label">Jenis Perkhidmatan</label>
                        <div class="col-md-9">
                            <p class="col-form-label">{{ $user->services_type->services_type ?? '' }}</p>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label class="col-md-2 col-form-label ">Peranan</label>
                        <div class="col-md-9">
                            <p class="col-form-label">{{ $user->roles?->name ?? '' }}</p>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label class="col-md-2 col-form-label ">Platform Sistem</label>
                        <div class="col-md-9">
                            <p class="col-form-label">{{ $user->system?->name ?? '' }}</p>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label class="col-md-2 col-form-label ">Status Pengguna</label>
                        <div class="col-md-9">
                            <p class="col-form-label">{{ $user->active_status->status ?? '' }}</p>
                        </div>
                    </div>
                    <hr>

                    <div class="mb-3 row">
                        <div class="col-sm-12">
                            <!-- <a href="{{ route('user.edit', ['id' => $user->id]) }}" class="btn btn-primary float-end">{{ __('button.kemaskini') }}</a> -->
                            {{-- <a class="btn btn-danger float-end me-2 swal-delete">{{ __('button.hapus') }}</a> --}}
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
