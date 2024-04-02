@extends('layouts.master')

@section('content')

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">

                {{-- PAGE TITLE --}}
                <div class="border-bottom border-primary mb-4"><h4 class="card-title">{{ getPageTitle(1) }}</h4></div>

                <form method="post" action="{{ route('complaintType.update') }}" >
                    {{ csrf_field() }}

                    <input class="form-control" type="hidden" id="id" name="id" value="{{ $complaintType->id }}">

                    <div class="mb-3 row">
                        <label for="complaint_name" class="col-md-2 col-form-label">Jenis Aduan</label>
                        <div class="col-md-10">
                            <input class="form-control" type="text" id="complaint_name" name="complaint_name" value="{{ $complaintType->complaint_name }}">
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <div class="col-sm-10 offset-sm-1">
                            <button type="submit" class="btn btn-primary float-end swal-kemaskini">{{ __('button.kemaskini') }}</button>
                            <a class="btn btn-danger float-end me-2 swal-delete">{{ __('button.hapus') }}</a>
                            <a href="{{ route('complaintType.index') }}" class="btn btn-secondary float-end me-2">{{ __('button.kembali') }}</a>
                        </div>
                    </div>
                </form>

                <form method="POST" action="{{ route('complaintType.delete') }}" id="delete-form">
                    {{ csrf_field() }}
                    {{ method_field('DELETE') }}
                    <input class="form-control" type="hidden" id="id-delete" name="id" value="{{ $complaintType->id }}">
                </form>
            
            </div>
        </div>
    </div> <!-- end col -->
</div>
<!-- end row -->

@endsection
