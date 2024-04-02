@extends('layouts.master')

@section('content')

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">

                {{-- PAGE TITLE --}}
                <div class="border-bottom border-primary mb-4"><h4 class="card-title">{{ getPageTitle(1) }}</h4></div>

                <form method="get" action="{{ route('radius.update') }}" >
                    {{ csrf_field() }}

                    <input class="form-control" type="hidden" id="id" name="id" value="{{ $radius->id }}">

                    <div class="mb-3 row">
                        <label class="col-md-2 col-form-label">Radius</label>
                        <div class="col-md-10">
                            <p class="col-form-label">{{ $radius->radius }}</p>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label class="col-md-2 col-form-label">Tarikh Kuatkuasa</label>
                        <div class="col-md-10">
                            <p class="col-form-label">{{ $radius->date_start->format("d/m/Y") }}</p>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <div class="col-sm-10 offset-sm-1">
                            <!-- <a href="{{ route('radius.edit', ['id' => $radius->id]) }}" class="btn btn-primary float-end" >{{ __('button.kemaskini') }}</a> -->
                            <a class="btn btn-danger float-end me-2 swal-delete">{{ __('button.hapus') }}</a>
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
