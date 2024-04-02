@extends('layouts.master')

@section('content')

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                
                {{-- PAGE TITLE --}}
                <div class="border-bottom border-primary mb-4"><h4 class="card-title">{{ getPageTitle(1) }}</h4></div>

                <form  class="custom-validation" id="form" method="post" action="{{ route('radius.store') }}" >
                    {{ csrf_field() }}

                    <div class="mb-3 row">
                        <label for="radius" class="col-md-2 col-form-label">Radius (km)</label>
                        <div class="col-md-10">
                            <input class="form-control @error('radius') is-invalid @enderror" type="text" id="radius" name="radius" oninput="checkDecimal(this)" onfocus="focusInput(this);"
                                    value="{{ old('radius','') }}" required data-parsley-required-message="{{ setMessage('radius.required') }}">
                            @error('radius')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="date" class="col-md-2 col-form-label">Tarikh Kuatkuasa</label>
                        <div class="col-md-10">
                            <input class="form-control @error('date') is-invalid @enderror" type="date" id="date" name="date" value="{{ old('date','') }}" required data-parsley-type="dateIso", data-parsley-required-message="{{ setMessage('date.required') }}">
                            @error('date')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <div class="col-sm-12">
                            <button type="submit" class="btn btn-primary float-end swal-tambah">{{ __('button.simpan') }}</button>
                            <a href="{{ route('radius.index') }}" class="btn btn-secondary float-end me-2">{{ __('button.kembali') }}</a>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div> <!-- end col -->
</div>
<!-- end row -->

@endsection
