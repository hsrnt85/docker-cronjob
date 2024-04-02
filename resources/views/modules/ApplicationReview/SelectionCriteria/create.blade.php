@extends('layouts.master')

@section('content')

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">

                <h4 class="card-title">Daftar Kriteria Pemarkahan</h4>

                <form method="post" action="{{ route('selectionCriteria.store') }}" >
                    {{ csrf_field() }}

                    <div class="mb-3 row">
                        <label for="name" class="col-md-2 col-form-label">Kategori</label>
                        <div class="col-md-10">
                            <select class="form-select @error('category') is-invalid @enderror" id="category" name="category" required data-route="{{ route('selectionCriteria.ajaxGetUser') }}">
                                <option value="">-- Pilih Kategori --</option>
                                @foreach($selectionCriteriaAll as $selectionCriteria)
                                    <option value="{{ $selectionCriteria->id }}" {{ old('selectionCriteria') == $selectionCriteria->id ? "selected" : "" }}>
                                        {{ $selectionCriteria->criteria_category }}
                                    </option>
                                @endforeach
                            </select>
                            
                            @error('category')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="name" class="col-md-2 col-form-label">Kriteria Pemilihan</label>
                        <div class="col-md-10">
                            <select class="form-select @error('criteria') is-invalid @enderror " id="criteria" name="criteria"></select>
                            @error('criteria')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div id="user-loading" class="col-md-1"></div>
                    </div>

                    <div class="mb-3 row">
                        <label for="statement" class="col-md-2 col-form-label">Kenyataan Pemarkahan</label>
                        <div class="col-md-10">
                            <input class="form-control @error('statement') is-invalid @enderror" type="text" id="statement" name="statement" style="text-transform:uppercase" required>
                            @error('statement')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="marks" class="col-md-2 col-form-label">Markah</label>
                        <div class="col-md-10">
                            <input class="form-control @error('marks') is-invalid @enderror" type="text" id="marks" name="marks" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" maxlength="2" required>
                            @error('marks')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="mb-3 row">
                        <div class="col-sm-12">
                            <button type="submit" class="btn btn-primary float-end swal-tambah">{{ __('button.simpan') }}</button>
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
<script src="{{ URL::asset('assets/js/pages/SelectionCriteria/selectionCriteria.js')}}"></script>
@endsection