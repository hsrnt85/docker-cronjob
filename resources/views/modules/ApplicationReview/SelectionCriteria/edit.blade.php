@extends('layouts.master')

@section('content')

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">

                <h4 class="card-title">{{ __('button.kemaskini') }} Pengurusan Daerah</h4>
                <p class="card-title-desc">{{ __('button.kemaskini') }} Pengurusan Daerah</p>

                <form method="post" action="{{ route('selectionCriteria.update') }}" >
                    {{ csrf_field() }}

                    <input type="hidden" name="id" value="{{ $selectionCriteria->id }}">

                    <div class="mb-3 row">
                        <label for="statement" class="col-md-1 col-form-label">Kenyataan Pemarkahan</label>
                        <div class="col-md-10">
                            <input class="form-control" type="text" id="category" name="category" value="{{ $selectionCriteria -> criteria -> category -> criteria_category }}" readonly>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="statement" class="col-md-1 col-form-label">Kenyataan Pemarkahan</label>
                        <div class="col-md-10">
                            <input class="form-control" type="text" id="criteria" name="criteria" value="{{ $selectionCriteria -> criteria -> criteria }}" readonly>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="statement" class="col-md-1 col-form-label">Kenyataan Pemarkahan</label>
                        <div class="col-md-10">
                            <input class="form-control @error('statement') is-invalid @enderror" type="text" id="statement" name="statement" value="{{ $selectionCriteria -> sub_criteria }}" style="text-transform:uppercase" required>
                            @error('statement')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="statement" class="col-md-1 col-form-label">Markah</label>
                        <div class="col-md-10">
                            <input class="form-control @error('marks') is-invalid @enderror" type="text" id="marks" name="marks" value="{{ $selectionCriteria -> mark}}" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" maxlength="2" required>
                            @error('marks')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <div class="col-sm-10 offset-sm-1">
                            <button type="submit" class="btn btn-primary float-end swal-kemaskini">{{ __('button.kemaskini') }}</button>
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

@section('script')
<script src="{{ URL::asset('assets/js/pages/DistrictManagement/districtManagement.js')}}"></script>
@endsection
