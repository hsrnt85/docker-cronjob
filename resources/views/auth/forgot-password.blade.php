@extends('layouts.master-auth')

@section('content')

<div class="my-auto">

    <div>
        <h5 class="text-primary">Lupa Kata Laluan >></h5>
        <p class="text-primary">Sila masukkan No. Kad Pengenalan (Baru) dan Emel untuk set semula kata laluan.</p>
    </div>

    <div class="mt-4">
        <form class="custom-validation" id="form" method="POST" action="{{ route('forgotPassword.sendLink') }}">
            @csrf

            <div class="row-auth mb-3">
                <label for="username" class="form-label">No. Kad Pengenalan (Baru)</label>
                <input id="new_ic" name="new_ic" type="text" class="form-control input-not-uppercase @error('new_ic') is-invalid @enderror" value="{{ old('email') }}"
                        oninput="checkNumber(this)" onfocus="focusInput(this);" placeholder="{{ setSessionMessage('new_ic.required') }}"
                        {{-- onkeyup="check_ic_not_exist()" data-route="{{ route('ajaxCheckIc') }}" --}}
                        minlength="12" maxlength="12" data-parsley-length-message="{{ setSessionMessage('new_ic.digits') }}"
                        required data-parsley-required-message="{{ setSessionMessage('new_ic.required') }}">
                <div id="new_ic_error"></div>
                @error('new_ic')
                <span class="invalid-feedback" role="alert">{{ $message }}</span>
                @enderror
            </div>

            <div class="row-auth mb-3">
                <label for="email" class="form-label">Emel</label>
                <input name="email" type="email" class="form-control input-not-uppercase @error('email') is-invalid @enderror" id="email"
                        onfocus="focusInput(this);" placeholder="{{ setSessionMessage('email.required') }}"
                        value="{{ old('email') }}" data-inputmask="'alias': 'email'"
                        required data-parsley-required-message="{{ setSessionMessage('email.required') }}"
                        data-parsley-type-message="{{ setSessionMessage('email.email') }}">
                @error('new_ic')
                <span class="invalid-feedback" role="alert">{{ $message }}</span>
                @enderror
            </div>

            <div class="mt-3 d-grid">
                <button id="btn-submit" class="btn btn-primary waves-effect waves-light mb-2" type="submit">Hantar</button>
                <a href="{{ route('login') }}" class="btn btn-primary waves-effect waves-light">Log Masuk</a>
            </div>

        </form>


    </div>

</div>

@endsection

@section('script')
<script src="{{ URL::asset('assets/js/pages/User/user.js')}}"></script>
@endsection
