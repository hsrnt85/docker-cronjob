@extends('layouts.master-without-nav')

@section('body')

<body>
@endsection

@section('content')
    <div class="account-pages my-5 pt-sm-5">
        <div class="container">

            <!-- end row -->
            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-6 col-xl-6">
                    <div class="card shadow-lg">

                        <div class="card-body">

                            <div class="p-2">
                                <div class="text-center">

                                    <div class="avatar-md mx-auto">
                                        <div class="avatar-title rounded-circle bg-light">
                                            <i class="bx bx-info-circle h1 mb-0 text-primary"></i>
                                        </div>
                                    </div>
                                    <div class="p-2 mt-4">
                                        @if($flagPageError === '1')
                                            <p class="text-14">
                                                Untuk makluman, pautan ini telah tamat tempoh. <br/>
                                            </p>
                                        @elseif($flagPageError === '2')
                                            <p class="text-16"><u><b>KATA LALUAN TELAH DIKEMASKINI</b></u></p>
                                            <p class="text-14">
                                                Untuk makluman, kata laluan anda telah dikemaskini. <br/>
                                                Sila log masuk ke dalam menggunakan No. Kad Pengenalan dan Kata laluan yang sah.
                                            </p>
                                        @endif


                                        <div class="mt-4 d-grid">
                                            <a href="{{ route('login') }}" class="btn btn-primary waves-effect waves-light">Log Masuk</a>
                                        </div>

                                    </div>


                                </div>
                            </div>

                        </div>
                    </div>


                </div>
            </div>
        </div>
    </div>

@endsection
