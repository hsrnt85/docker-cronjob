@extends('layouts.master-without-nav')

@section('body')

<body>
@endsection

@section('content')
    <div class="account-pages my-5 pt-sm-5">
        <div class="container">

            <!-- end row -->
            <div class="row justify-content-center ">
                <div class="col-md-8 col-lg-6 col-xl-6">
                    <div class="card shadow-lg">

                        <div class="row">

                            <img src="{{ URL::asset('/assets/images/top-header-reset-pwd.png') }}" alt="" class="img-fluid rounded-top">

                        </div>

                        <div class="card-body">

                            <div class="p-2">

                                {{-- CONTENT --}}

                                <div>
                                    <h5 class="text-primary">Set Kata Laluan Pengguna >></h5>
                                    <p class="text-primary">Sila masukkan Kata Laluan dan Pengesahan Kata Laluan.</p>
                                </div>

                                <div class="mt-1 mb-4">
                                    {{-- <span class="text-success">
                                        {{ setSessionMessage('msg_katalaluan') }}<br/>
                                        {{ setSessionMessage('msg_katalaluan1') }}<br/>
                                        {{ setSessionMessage('msg_katalaluan2') }}<br/>
                                        &nbsp;{{ setSessionMessage('msg_katalaluan3') }}
                                    </span> --}}
                                </div>

                                <div >
                                    <form class="custom-validation" id="form" method="POST" action="{{ route('setPassword.store', $token) }}">
                                        @csrf

                                        <div class="row-auth mb-3">
                                            <label for="password">Kata Laluan</label>
                                            <div class="input-group auth-pass-inputgroup @error('password') is-invalid @enderror">
                                                <input type="password" name="password" id="password" class="form-control input-not-uppercase @error('password') is-invalid @enderror"
                                                    onblur="validatePassword(this.id,'msg-katalaluan-baru')" onkeyup="validatePassword(this.id,'msg-katalaluan-baru')"
                                                    placeholder="{{ setSessionMessage('password.required') }}" minlength="12" data-parsley-length-message="{{ setSessionMessage('password.length') }}"
                                                    required data-parsley-required-message="{{ setSessionMessage('password.required') }}"
                                                    data-parsley-errors-container="#parsley-errors-password">
                                                <button class="btn btn-light " type="button" id="password-addon"><i class="mdi mdi-eye-outline"></i></button>
                                                @error('password')
                                                <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                                @enderror
                                            </div>
                                            <div id="parsley-errors-password"></div>
                                            <span id="msg-katalaluan-baru" class="mt-1" >
                                                <span id="minlength" class="error">{{ setSessionMessage('msg_katalaluan_jumlah_aksara') }}</span><br/>
                                                <span id="uppercase" class="error">{{ setSessionMessage('msg_katalaluan_huruf_besar') }}</span><br/>
                                                <span id="lowercase" class="error">{{ setSessionMessage('msg_katalaluan_huruf_kecil') }}</span><br/>
                                                <span id="number" class="error">{{ setSessionMessage('msg_katalaluan_nombor') }}</span><br/>
                                                <span id="symbol" class="error">{{ setSessionMessage('msg_katalaluan_simbol') }}</span>
                                            </span>

                                        </div>

                                        <div class="mb-3">
                                            <label for="confirm_password">Pengesahan Kata Laluan</label>
                                            <div class="input-group auth-pass-inputgroup @error('confirm_password') is-invalid @enderror">
                                                <input type="password" name="confirm_password" id="confirm_password" class="form-control input-not-uppercase @error('confirm_password') is-invalid @enderror"
                                                placeholder="{{ setSessionMessage('confirm_password.required') }}" minlength="12" data-parsley-length-message="{{ setSessionMessage('password.length') }}"
                                                required data-parsley-required-message="{{ setSessionMessage('confirm_password.required') }}"
                                                data-parsley-equalto="#password" data-parsley-equalto-message="{{ setSessionMessage('confirm_password.equalto') }}"
                                                data-parsley-errors-container="#parsley-errors-confirm-password">
                                                <button class="btn btn-light " type="button" id="confirm-password-addon"><i class="mdi mdi-eye-outline"></i></button>
                                                @error('confirm_password')
                                                <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                                @enderror
                                            </div>
                                            <div id="parsley-errors-confirm-password"></div>

                                        </div>

                                        <div class="mt-4 d-grid">
                                            <button class="btn btn-primary waves-effect waves-light mb-2" type="submit">{{ __('button.simpan') }}</button>
                                            <a href="{{ route('login') }}" class="btn btn-primary waves-effect waves-light">Log Masuk</a>
                                        </div>

                                    </form>

                                </div>

                                {{-- END CONTENT --}}


                            </div>

                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

@endsection

@section('script')
<script src="{{ URL::asset('assets/js/pages/User/auth.js')}}"></script>
@endsection
