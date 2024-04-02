@extends('layouts.master')

@section('content')

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">

                <h4 class="card-title">Papar Kriteria Pemarkahan</h4>

                <form>
                    <<div class="mb-3 row">
                        <label for="name" class="col-md-1 col-form-label">Kategori</label>
                        <p class="col-md-10 col-form-label">{{ $selectionCriteria -> criteria -> category -> criteria_category }}</p>
                    </div>

                    <div class="mb-3 row">
                        <label for="name" class="col-md-1 col-form-label">Kriteria Pemilihan</label>
                        <p class="col-md-10 col-form-label">{{ $selectionCriteria -> criteria -> criteria}}</p>
                    </div>

                    <div class="mb-3 row">
                        <label for="name" class="col-md-1 col-form-label">Kenyataan Pemarkahan</label>
                        <p class="col-md-10 col-form-label">{{ $selectionCriteria -> sub_criteria }}</p>
                    </div>

                    <div class="mb-3 row">
                        <label for="name" class="col-md-1 col-form-label">Markah</label>
                        <p class="col-md-10 col-form-label">{{ $selectionCriteria -> mark }}</p>
                    </div>

                    <hr>

                    <div class="mb-3 row">
                        <div class="col-sm-10 offset-sm-1">
                            <a href="{{ route('selectionCriteria.index') }}" class="btn btn-secondary float-end me-2">{{ __('button.kembali') }}</a>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div> <!-- end col -->
</div>
<!-- end row -->

@endsection
