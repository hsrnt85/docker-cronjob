@extends('layouts.master')

@section('title') @lang('translation.Dashboards') @endsection

@section('content')

@component('components.breadcrumb')
@slot('li_1') Dashboards @endslot
@slot('title') Kategori Pegawai @endslot
@endcomponent

@component('components.alert')@endcomponent

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">

                <h4 class="card-title">{{ __('button.simpan') }} Kategori Pegawai</h4>
                <p class="card-title-desc">{{ __('button.simpan') }} kategori pegawai</p>

                <form method="post" action="{{ route('officerCategory.update') }}" >
                    {{ csrf_field() }}

                    <input type="hidden" name="id" value="{{ $officerCategory->id }}">

                    <div class="mb-3 row">
                        <label for="name" class="col-md-2 col-form-label">Nama Kategori</label>
                        <div class="col-md-10">
                            <input class="form-control @error('category_name') is-invalid @enderror" type="text" id="category_name" name="category_name" value="{{ $officerCategory->category_name }}">
                            @error('category_name')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <div class="col-sm-10 offset-sm-1">
                            <button type="submit" class="btn btn-primary float-end swal-kemaskini">{{ __('button.kemaskini') }}</button>
                            <a class="btn btn-danger float-end me-2 swal-delete">Hapus</a>
                            <a href="{{ route('officerCategory.index') }}" class="btn btn-secondary float-end me-2">{{ __('button.kembali') }}</a>
                        </div>
                    </div>
                </form>

                <form method="POST" action="{{ route('officerCategory.delete') }}" id="delete-form">
                    {{ csrf_field() }}
                    {{ method_field('DELETE') }}
                    <input class="form-control" type="hidden" id="id-delete" name="id" value="{{ $officerCategory->id }}">
                </form>
            </div>
        </div>
    </div> <!-- end col -->
</div>
<!-- end row -->

@endsection
@section('script')

@endsection
