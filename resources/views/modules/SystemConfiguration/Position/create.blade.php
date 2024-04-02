@extends('layouts.master')

@section('content')

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">

                {{-- PAGE TITLE --}}
                <div class="border-bottom border-primary mb-4"><h4 class="card-title">{{ getPageTitle(1) }}</h4></div>

                <form class="custom-validation" id="form" method="post" action="{{ route('position.store') }}" >
                    {{ csrf_field() }}

                    <div class="mb-3 row">
                        <label for="position_name" class="col-md-2 col-form-label">Jawatan</label>
                        <div class="col-md-10">
                            <input class="form-control @error('position_name') is-invalid @enderror" type="text" id="position_name" name="position_name"  required data-parsley-required-message="{{ setMessage('position_name.required') }}">
                            @error('position_name')
                            <span class="invalid-feedback"> {{$message}} </span>
                            @enderror
                        </div>

                    </div>

                    <div class="mb-3 row">
                        <div class="col-sm-12">
                            <button type="submit" class="btn btn-primary float-end swal-tambah">{{ __('button.simpan') }}</button>
                            <a href="{{ route('position.index') }}" class="btn btn-secondary float-end me-2">{{ __('button.kembali') }}</a>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div> <!-- end col -->
</div>
<!-- end row -->

@endsection
