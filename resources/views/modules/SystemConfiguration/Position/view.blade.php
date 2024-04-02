@extends('layouts.master')

@section('content')

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">

                {{-- PAGE TITLE --}}
                <div class="border-bottom border-primary mb-4"><h4 class="card-title">{{ getPageTitle(1) }}</h4></div>

                <form method="post" action="{{ route('position.update') }}" >
                    {{ csrf_field() }}
                    <input type="hidden" name="id" value="{{ $position->id }}">

                    <div class="mb-3 row">
                        <label class="col-md-2 col-form-label">Jawatan</label>
                        <div class="col-md-10">
                            <p class="col-form-label">{{ $position->position_name }}</p>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <div class="col-sm-12">
                            {{-- <a href="{{ route('position.edit', ['id' => $position->id]) }}" class="btn btn-primary float-end">{{ __('button.kemaskini') }}</a>
                            <a class="btn btn-danger float-end me-2 swal-delete">{{ __('button.hapus') }}</a> --}}
                            <a href="{{ route('position.index') }}" class="btn btn-secondary float-end me-2">{{ __('button.kembali') }}</a>
                        </div>
                    </div>
                </form>

                <form method="POST" action="{{ route('position.delete') }}" id="delete-form">
                    {{ csrf_field() }}
                    {{ method_field('DELETE') }}
                    <input class="form-control" type="hidden" id="id-delete" name="id" value="{{ $position->id }}">
                </form>
            </div>
        </div>
    </div> <!-- end col -->
</div>
<!-- end row -->

@endsection
