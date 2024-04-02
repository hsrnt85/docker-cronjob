@extends('layouts.master')

@section('content')

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">

                {{-- PAGE TITLE --}}
                <div class="border-bottom border-primary mb-4"><h4 class="card-title">{{ getPageTitle(1) }}</h4></div>

                <form  class="custom-validation" id="form" method="post" action="{{ route('positionGrade.store') }}" >
                    {{ csrf_field() }}

                    <div class="mb-3 row">
                        <label for="name" class="col-md-2 col-form-label">Gred</label>
                        <div class="col-md-10">
                            <input class="form-control @error('grade_no') is-invalid @enderror" type="text" id="grade_no" name="grade_no" required data-parsley-required-message="{{ setMessage('grade_no.required') }}">
                            @error('grade_no')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <div class="col-sm-10 offset-sm-1">
                            <button type="submit" class="btn btn-primary float-end swal-tambah">{{ __('button.simpan') }}</button>
                            <a href="{{ route('positionGrade.index') }}" class="btn btn-secondary float-end me-2">{{ __('button.kembali') }}</a>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div> <!-- end col -->
</div>
<!-- end row -->

@endsection
