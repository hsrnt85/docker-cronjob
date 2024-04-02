@extends('layouts.master')

@section('content')

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">

                {{-- PAGE TITLE --}}
                <div class="border-bottom border-primary mb-4"><h4 class="card-title">{{ getPageTitle(1) }}</h4></div>

                <form  class="custom-validation" id="form" method="post" action="{{ route('radius.update') }}" >
                    {{ csrf_field() }}

                    <input class="form-control" type="hidden" id="id" name="id" value="{{ $radius->id }}">

                    <div class="mb-3 row">
                        <label for="radius" class="col-md-2 col-form-label">Radius (km)</label>
                        <div class="col-md-10">
                            <input class="form-control @error('radius') is-invalid @enderror" type="text" id="radius" name="radius" value="{{ old('radius',$radius->radius) }}"
                                    oninput="checkDecimal(this)" onfocus="focusInput(this);" required data-parsley-required-message="{{ setMessage('radius.required') }}">
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
                            <input class="form-control @error('date') is-invalid @enderror" type="date" id="date" name="date" value="{{ old('date',$radius->date_start->todatestring()) }}"  required data-parsley-required-message="{{ setMessage('date.required') }}">
                            @error('date')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <div class="col-sm-12 mt-3">
                            <button type="submit" class="btn btn-primary float-end swal-kemaskini">{{ __('button.kemaskini') }}</button>
                            {{-- <a class="btn btn-danger float-end me-2 swal-delete">{{ __('button.hapus') }}</a> --}}
                            <a href="{{ route('radius.index') }}" class="btn btn-secondary float-end me-2">{{ __('button.kembali') }}</a>
                        </div>
                    </div>
                </form>

                <form method="POST" action="{{ route('radius.delete') }}" id="delete-form">
                    {{ csrf_field() }}
                    {{ method_field('DELETE') }}
                    <input class="form-control" type="hidden" id="id-delete" name="id" value="{{ $radius->id }}">
                </form>

            </div>
        </div>
    </div> <!-- end col -->
</div>
<!-- end row -->

@endsection
