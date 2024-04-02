@extends('layouts.master')

@section('content')

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">

                {{-- PAGE TITLE --}}
                <div class="border-bottom border-primary mb-4"><h4 class="card-title">{{ getPageTitle(1) }}</h4></div>

                <form class="custom-validation" id="form"  method="post" action="{{ route('document.store') }}" >
                    {{ csrf_field() }}

                    <div class="mb-3 row">
                        <label for="document" class="col-md-2 col-form-label">Nama Dokumen</label>
                        <div class="col-md-10">
                            <input class="form-control @error('document') is-invalid @enderror" type="text" id="document" name="document" value="{{ old('document', '') }}" required data-parsley-required-message="{{ setMessage('document.required') }}">
                            @error('document')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <div class="col-sm-12">
                            <button type="submit" class="btn btn-primary float-end swal-tambah">{{ __('button.simpan') }}</button>
                            <a href="{{ route('document.index') }}" class="btn btn-secondary float-end me-2">{{ __('button.kembali') }}</a>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div> <!-- end col -->
</div>
<!-- end row -->

@endsection
