@extends('layouts.master')
@section('css')
    <link href="{{ URL::asset('/assets/libs/bootstrap-datepicker/bootstrap-datepicker.min.css') }}" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="{{ URL::asset('/assets/libs/datepicker/datepicker.min.css') }}">
@endsection
@section('content')

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body" style="height:450px; width:100%">

                {{-- PAGE TITLE --}}
                <div class="border-bottom border-primary mb-4"><h4 class="card-title">{{ getPageTitle(1) }}</h4></div>

                <form class="custom-validation" id="form" method="post" action="{{ route('applicationDate.store') }}" >
                    {{ csrf_field() }}

                    <div class="mb-3 row">
                        <label for="year" class="col-md-3 col-form-label">Tahun</label>
                        <div class="col-md-9" id="datepicker5">
                            <input type="text" class="form-control" id="year" name="year" data-provide="datepicker" data-date-container='#datepicker5' data-date-autoclose="true" data-date-format="yyyy" data-date-min-view-mode="2">
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="date_open" class="col-md-3 col-form-label">Tarikh Buka Permohonan</label>
                        <div class="col-md-9">
                            <div class="input-group" id="datepicker2">
                                <input class="form-control @error('date_open') is-invalid @enderror"  type="text"  placeholder="dd/mm/yyyy" name="date_open" id="date_open"
                                data-date-format="dd/mm/yyyy" data-date-container='#datepicker2' data-provide="datepicker" onchange="validateEndDate(this.id, document.getElementById('date_close').id);"
                                data-date-autoclose="true" required data-parsley-required-message="{{ setMessage('date_open.required') }}" data-parsley-errors-container="#errorContainer">
                                <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
                                @error('date_open')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div id="errorContainer"></div>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="date_close" class="col-md-3 col-form-label">Tarikh Tutup Permohonan</label>
                        <div class="col-md-9">
                            <div class="input-group" id="datepicker2">
                                <input class="form-control @error('date_close') is-invalid @enderror"  type="text"  placeholder="dd/mm/yyyy" name="date_close" id="date_close"
                                data-date-format="dd/mm/yyyy" data-date-container='#datepicker2' data-provide="datepicker" onchange="validateStartDate(this.value, '{{ setMessage('date_close.after_or_equal') }}');"
                                data-date-autoclose="true" required data-parsley-required-message="{{ setMessage('date_close.required') }}" data-parsley-errors-container="#errorContainer2">
                                <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
                                @error('date_close')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div id="errorContainer2"></div>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <div class="col-sm-12">
                            <button type="submit" id="btn-submit" class="btn btn-primary float-end swal-tambah">{{ __('button.simpan') }}</button>
                            <a href="{{ route('applicationDate.index') }}" class="btn btn-secondary float-end me-2">{{ __('button.kembali') }}</a>
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
<script src="{{ URL::asset('assets/js/pages/ApplicationDate/applicationDate.js')}}"></script>
 <script src="{{ URL::asset('assets/libs/bootstrap-datepicker/bootstrap-datepicker.min.js')}}"></script>


@endsection

