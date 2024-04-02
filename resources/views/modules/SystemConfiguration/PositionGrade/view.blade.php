@extends('layouts.master')

@section('content')

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">

                {{-- PAGE TITLE --}}
                <div class="border-bottom border-primary mb-4"><h4 class="card-title">{{ getPageTitle(1) }}</h4></div>

                <form >
                    <div class="mb-3 row">
                        <label for="name" class="col-md-2 col-form-label">Gred Jawatan</label>
                        <div class="col-md-10">
                            <input class="form-control @error('grade_no') is-invalid @enderror" type="text" id="grade_no" name="grade_no" value="{{ $positionGrade->grade_no }}" readonly>
                            @error('grade_no')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <div class="col-sm-12 mt-3">
                            {{-- <a href="{{ route('positionGrade.edit', ['id' => $positionGrade->id]) }}" class="btn btn-primary float-end ">{{ __('button.kemaskini') }}</a>
                            <a class="btn btn-danger float-end me-2 swal-delete">Hapus</a> --}}
                            <a href="{{ route('positionGrade.index') }}" class="btn btn-secondary float-end me-2">{{ __('button.kembali') }}</a>
                        </div>
                    </div>
                </form>

                <form method="POST" action="{{ route('positionGrade.delete') }}" id="delete-form">
                    {{ csrf_field() }}
                    {{ method_field('DELETE') }}
                    <input class="form-control" type="hidden" id="id-delete" name="id" value="{{ $positionGrade->id }}">
                </form>
            </div>
        </div>
    </div> <!-- end col -->
</div>
<!-- end row -->

@endsection
