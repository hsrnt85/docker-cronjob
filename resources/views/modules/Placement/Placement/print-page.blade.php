@extends('layouts.master')

@section('content')

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">

                {{-- PAGE TITLE --}}
                <div class="border-bottom border-primary mb-4"><h4 class="card-title">{{ getPageTitle(2) }}</h4></div>

                <form method="post" action="{{ route('placement.print',['application' => $application]) }}" >
                    {{ csrf_field() }}

                    <div class="mb-3 row">
                        <label for="district" class="col-md-2 col-form-label">No. Rujukan Kami</label>
                        <div class="col-md-9">
                            <input class="form-control  @error('rujukan_kami') is-invalid @enderror" type="text" id="rujukan_kami" name="rujukan_kami" value="{{ old('rujukan_kami', '') }}">
                            @error('rujukan_kami')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="district" class="col-md-2 col-form-label">Tarikh Surat</label>
                        <div class="col-md-9">
                            <input class="form-control  @error('tarikh_surat') is-invalid @enderror" type="date" id="tarikh_surat" name="tarikh_surat" value="{{ old('tarikh_surat', '') }}">
                            @error('tarikh_surat')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="district" class="col-md-2 col-form-label">Kadar Sewa</label>
                        <div class="col-md-9">
                            <input class="form-control @error('sewa') is-invalid @enderror" type="number" id="sewa" name="sewa" value="{{ $rental->rental_fee }}" readonly>
                            @error('sewa')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>

                    <!-- <div class="mb-3 row">
                        <label for="district" class="col-md-2 col-form-label">Deposit</label>
                        <div class="col-md-9">
                            <input class="form-control @error('deposit') is-invalid @enderror" type="number" id="deposit" name="deposit" value="{{ old('deposit', '') }}">
                            @error('deposit')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div> -->

                    <div class="mb-3 row">
                        <label for="district" class="col-md-2 col-form-label">Tarikh Akhir Maklumbalas</label>
                        <div class="col-md-9">
                            <input class="form-control @error('tarikh_maklumbalas') is-invalid @enderror" type="date" id="tarikh_maklumbalas" name="tarikh_maklumbalas" value="{{ old('tarikh_maklumbalas', '') }}">
                            @error('tarikh_maklumbalas')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="district" class="col-md-2 col-form-label">Nama Pengarah</label>
                        <div class="col-md-9">
                            <input class="form-control @error('pengarah') is-invalid @enderror" type="text" id="pengarah" name="pengarah" value="{{ old('pengarah', '') }}">
                            @error('pengarah')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>

                    <!-- <div class="mb-3 row">
                        <label for="district" class="col-md-2 col-form-label">Jawatan Pengarah</label>
                        <div class="col-md-9">
                            <input class="form-control @error('jawatan') is-invalid @enderror" type="text" id="jawatan" name="jawatan" value="{{ old('jawatan', '') }}">
                            @error('jawatan')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div> -->

                    <div class="mb-3 row">
                        <div class="col-sm-10 offset-sm-1">
                            <button type="submit" class="btn btn-primary float-end swal-kemaskini">{{ __('button.cetak') }}</button>
                            <a id="swal-notification" class="btn btn-primary float-end me-2">{{ __('button.notifikasi') }}</a>
                            <a href="{{ route('placement.index') }}" class="btn btn-secondary float-end me-2">{{ __('button.kembali') }}</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div> <!-- end col -->
</div>
<!-- end row -->

<form method="POST" action="{{ route('placement.notification', ['application' => $application]) }}" id="trigger-noti">
    {{ csrf_field() }}
</form>

@endsection

@section('script')
<script src="{{ URL::asset('assets/js/pages/Placement/placement.js')}}"></script>
@endsection
