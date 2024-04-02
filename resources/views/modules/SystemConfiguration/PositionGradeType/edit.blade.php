@extends('layouts.master')

@section('content')

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">

                {{-- PAGE TITLE --}}
                <div class="border-bottom border-primary mb-4"><h4 class="card-title">{{ getPageTitle(1) }}</h4></div>

                <form class="custom-validation" id="form" method="post" action="{{ route('positionGradeType.update') }}" >
                    {{ csrf_field() }}

                    <input type="hidden" name="id" value="{{ $positionGradeType->id }}">

                    <div class="mb-3 row">
                        <label for="name" class="col-md-2 col-form-label">Kod Jawatan</label>
                        <div class="col-md-10">
                            <input class="form-control @error('grade_type') is-invalid @enderror" type="text" id="grade_type" name="grade_type" value="{{ $positionGradeType->grade_type }}"  required data-parsley-required-message="{{ setMessage('grade_type.required') }}">
                            @error('grade_type')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <div class="col-sm-10 offset-sm-1">
                            <button type="submit" class="btn btn-primary float-end swal-kemaskini">{{ __('button.kemaskini') }}</button>
                            <a href="{{ route('positionGradeType.index') }}" class="btn btn-secondary float-end me-2">{{ __('button.kembali') }}</a>
                        </div>
                    </div>
                </form>

                <form method="POST" action="{{ route('positionGradeType.delete') }}" id="delete-form">
                    {{ csrf_field() }}
                    {{ method_field('DELETE') }}
                    <input class="form-control" type="hidden" id="id-delete" name="id" value="{{ $positionGradeType->id }}">
                </form>
            </div>
        </div>
    </div> <!-- end col -->
</div>
<!-- end row -->

@endsection
