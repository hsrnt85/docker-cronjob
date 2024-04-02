@extends('layouts.master-auth')

@section('content')

<div class="my-auto">
    @if(Session::has('error'))
        <input type="hidden" name="error" value="{{ Session::get('error') }}">
    @endif
    @if(Session::has('success'))
        <input type="hidden" name="success" value="{{ Session::get('success') }}">
    @endif

    <div>
        <h5 class="text-primary">Log Masuk >></h5>
        <p class="text-primary">Sila masukkan No. Kad Pengenalan (Baru) dan Kata Laluan untuk log masuk.</p>
    </div>

    <div class="mt-4">
        <form class="custom-validation" id="form" method="POST" action="{{ route('login.check') }}">
            @csrf
            <div class="row-auth mb-3">
                <label for="new_ic" class="form-label">No. Kad Pengenalan (Baru)</label>
                <input name="new_ic" type="new_ic" class="form-control input-not-uppercase @error('new_ic') is-invalid @enderror" id="new_ic"
                        oninput="checkNumber(this)" onfocus="focusInput(this);" placeholder="{{ setSessionMessage('new_ic.required') }}"
                        onkeyup="check_ic_not_exist()" data-route="{{ route('ajaxCheckIc') }}"
                        required data-parsley-required-message=" "
                        minlength="12" maxlength="12" >
                <div id="new_ic_error"></div>
                @error('new_ic')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>

            <div class="row-auth mb-3">
                <div class="float-end">
                    @if (Route::has('forgotPassword'))
                    <a href="{{ route('forgotPassword') }}" class="text-muted">Lupa Kata Laluan ?</a>
                    @endif
                </div>
                <label class="form-label">Kata Laluan</label>
                <div class="input-group auth-pass-inputgroup @error('password') is-invalid @enderror">
                    <input type="password" name="password" class="form-control input-not-uppercase @error('password') is-invalid @enderror" id="userpassword" placeholder="{{ setSessionMessage('password.required') }}"
                            required data-parsley-required-message=" "
                            minlength="12" data-parsley-minlength-message="{{ setSessionMessage('password.length') }}"
                            data-parsley-errors-container="#parsley-errors-password">
                    <button class="btn btn-light " type="button" id="password-addon"><i class="mdi mdi-eye-outline"></i></button>
                    @error('password')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
                <div id="parsley-errors-password"></div>
            </div>

            <div class="mt-3 d-grid">
                <button id="btn-submit" class="btn btn-primary waves-effect waves-light mb-2" type="submit">Log Masuk</button>
                {{-- <a href="{{ route('register') }}" class="btn btn-primary waves-effect waves-light">Pengguna Baru</a> --}}
            </div>

        </form>


    </div>

</div>

@endsection

@section('script')
<script src="{{ URL::asset('assets/js/pages/User/user.js')}}"></script>
<script src="{{ URL::asset('assets/js/pages/User/auth.js')}}"></script>
@endsection
