@extends('layouts.master')

@section('content')

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">

                {{-- PAGE TITLE --}}
                <div class="border-bottom border-primary mb-4"><h4 class="card-title">{{ getPageTitle(1) }}</h4></div>

                <form class="custom-validation" id="form" method="post" action="{{ route('invitationPanel.update') }}" >
                    {{ csrf_field() }}

                    <input type="hidden" name="id" value="{{ $invitationPanel->id }}">

                    <div class="mb-3 row">
                        <label for="name" class="col-md-2 col-form-label">Nama Panel</label>
                        <div class="col-md-9">
                            <input class="form-control @error('name') is-invalid @enderror" type="text" id="name" name="name" value="{{ old('name', $invitationPanel->name) }}">
                            @error('name')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="position" class="col-md-2 col-form-label">Gelaran Jawatan</label>
                        <div class="col-md-9">
                            <input class="form-control @error('position') is-invalid @enderror" type="text" id="name" name="position" value="{{ old('name', $invitationPanel->position) }}"
                                    required data-parsley-required-message="{{ setMessage('position.required') }}">
                            @error('position')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="name" class="col-md-2 col-form-label">Jabatan</label>
                        <div class="col-md-9">
                            <input class="form-control @error('department') is-invalid @enderror" type="text" id="department" name="department" value="{{ old('name', $invitationPanel->department) }}"
                                    required data-parsley-required-message="{{ setMessage('department.required') }}">
                            @error('department')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="email" class="col-md-2 col-form-label ">Emel</label>
                        <div class="col-md-9">
                            <input class="form-control input-not-uppercase input-mask @error('email') is-invalid @enderror" type="email" id="email" name="email" value="{{ old('email', $invitationPanel->email) }}" data-inputmask="'alias': 'email'"
                                    required data-parsley-required-message="{{ setMessage('email.required') }} " data-parsley-type-message="{{ setMessage('email_type.required') }}">
                            @error('email')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <div class="col-sm-11">
                            <button type="submit" class="btn btn-primary float-end swal-kemaskini">{{ __('button.kemaskini') }}</button>
                            <a href="{{ route('invitationPanel.index') }}" class="btn btn-secondary float-end me-2">{{ __('button.kembali') }}</a>
                        </div>
                    </div>
                </form>

                <form method="POST" action="{{ route('invitationPanel.delete') }}" id="delete-form">
                    {{ csrf_field() }}
                    {{ method_field('DELETE') }}
                    <input class="form-control" type="hidden" id="id-delete" name="id" value="{{ $invitationPanel->id }}">
                </form>
            </div>
        </div>
    </div> <!-- end col -->
</div>
<!-- end row -->

@endsection
@section('script')
<script src="{{ URL::asset('assets/js/pages/InvitationPanel/InvitationPanel.js')}}"></script>
@endsection
