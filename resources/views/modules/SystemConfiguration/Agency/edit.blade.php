@extends('layouts.master')

@section('content')

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">

                {{-- PAGE TITLE --}}
                <div class="border-bottom border-primary mb-4"><h4 class="card-title">{{ getPageTitle(1) }}</h4></div>

                <form class="custom-validation" id="form" method="post" action="{{ route('agency.update') }}" >
                    {{ csrf_field() }}

                    <input type="hidden" name="id" value="{{ $agensi->id }}">

                    <div class="mb-3 row">
                        <label class="col-md-2 col-form-label">Agensi</label>
                        <div class="col-md-10">
                            <p class="col-form-label">{{ $agensi->name }}</p>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="name" class="col-md-2 col-form-label">Kod Agensi (JohorPay)</label>
                        <div class="col-md-10">
                            <input class="form-control @error('code') is-invalid @enderror" type="text" id="code" name="code" value="{{ old('code', $agensi->code) }}" data-parsley-required-message="{{ setMessage('code.required') }}">
                            @error('code')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <div class="col-sm-12">
                            <button type="submit" class="btn btn-primary float-end swal-kemaskini">{{ __('button.kemaskini') }}</button>
                            <a href="{{ route('agency.index') }}" class="btn btn-secondary float-end me-2">{{ __('button.kembali') }}</a>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div> <!-- end col -->
</div>
<!-- end row -->

@endsection
