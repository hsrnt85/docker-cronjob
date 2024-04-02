@extends('layouts.master')

@section('content')

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">

                {{-- PAGE TITLE --}}
                <div class="border-bottom border-primary mb-4"><h4 class="card-title">{{ getPageTitle(1) }}</h4></div>

                <h4 class="card-title">{{ __('button.kemaskini') }} Tempoh Senarai Hitam</h4>
                <p class="card-title-desc">{{ __('button.kemaskini') }} tempoh untuk disenarai hitamkan dalam bulan.</p>

                <form method="post" action="{{ route('blacklist.update') }}" >
                    {{ csrf_field() }}

                    <input type="hidden" name="id" value="{{ $blacklist->id }}">

                    <div class="mb-3 row">
                        <label for="name" class="col-md-2 col-form-label">Tempoh (bulan)</label>
                        <div class="col-md-10">
                            <input class="form-control @error('range') is-invalid @enderror" type="text" id="range" name="range" value="{{ $blacklist->range }}">
                            @error('range')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
            
                    <div class="mb-3 row">
                        <div class="col-sm-10 offset-sm-1">
                            <button type="submit" class="btn btn-primary float-end swal-kemaskini">{{ __('button.kemaskini') }}</button>
                            <a href="{{ route('blacklist.index') }}" class="btn btn-secondary float-end me-2">{{ __('button.kembali') }}</a>
                        </div>
                    </div>
                </form>
               
                <form method="POST" action="{{ route('blacklist.delete') }}" id="delete-form">
                    {{ csrf_field() }}
                    {{ method_field('DELETE') }}
                    <input class="form-control" type="hidden" id="id-delete" name="id" value="{{ $blacklist->id }}">
                </form>
            </div>
        </div>
    </div> <!-- end col -->
</div>
<!-- end row -->

@endsection