@extends('layouts.master')

@section('content')

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">

                {{-- PAGE TITLE --}}
                <div class="border-bottom border-primary mb-4"><h4 class="card-title">{{ getPageTitle(1) }}</h4></div>

                <form class="custom-validation" id="form" method="post" action="{{ route('quartersOption.store') }}" >
                    {{ csrf_field() }}

                    <div class="mb-3 row">
                        <label for="option_no" class="col-md-4 col-form-label"> Bilangan Pilihan Kategori Kuarters (Lokasi)</label>
                        <div class="col-md-8">
                            <input class="form-control @error('option_no') is-invalid @enderror" type="number"  name="option_no" id="option_no" value="{{ old('option_no','') }}" oninput="checkInteger(this)" onfocus="focusInput(this);"
                                    required data-parsley-required-message="{{ setMessage('option_no.required') }}" data-parsley-min="1" data-parsley-min-message="{{ setMessage('option_no.required') }}">
                            @error('option_no')
                            <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="execution_date" class="col-md-4 col-form-label">Tarikh Kuatkuasa</label>
                        <div class="col-md-8">
                            <input class="form-control @error('execution_date') is-invalid @enderror" type="date" id="execution_date" name="execution_date"  required  data-parsley-type="dateIso", data-parsley-required-message="{{ setMessage('execution_date.required') }}">
                            @error('execution_date')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <div class="col-sm-12">
                            <button type="submit" class="btn btn-primary float-end swal-tambah">{{ __('button.simpan') }}</button>
                            <a href="{{ route('quartersOption.index') }}" class="btn btn-secondary float-end me-2">{{ __('button.kembali') }}</a>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div> <!-- end col -->
</div>
<!-- end row -->

@endsection

