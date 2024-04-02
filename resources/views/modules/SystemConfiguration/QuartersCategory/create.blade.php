@extends('layouts.master')

@section('content')

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">

                {{-- PAGE TITLE --}}
                <div class="border-bottom border-primary mb-4"><h4 class="card-title">{{ getPageTitle(1) }}</h4></div>

                <form class="custom-validation" id="form" method="post" autocomplete="off" action="{{ route('quartersCategory.store') }}" >
                    {{ csrf_field() }}

                    <div class="mb-3 row">
                        <label class="col-md-2 col-form-label">Daerah</label>
                        <div class="col-md-10">
                            <select class="form-control  @error('district') is-invalid @enderror" id="district" name="district" required data-parsley-required-message="{{ setMessage('district.required') }}" disabled>
                                <option value="" >-- Pilih Daerah --</option>
                                @foreach($districtAll as $district)
                                    <option value="{{ $district->id }}" {{ districtId() == $district->id ? "selected" : "" }}>
                                        {{ $district->district_name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('district')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="name" class="col-md-2 col-form-label">Kategori Kuarters (Lokasi)</label>
                        <div class="col-md-10">
                            <input class="form-control @error('name') is-invalid @enderror" type="text" id="name" name="name" value="{{ old('name') }}"  required data-parsley-required-message="{{ setMessage('name.required') }}">
                            <div id="name-errors"></div>
                            @error('name')
                            <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label class="col-md-2 col-form-label">Jenis Kuarters </label>
                        <div class="col-md-10">
                            @foreach($landedTypeAll as $landedType)
                                <div class="form-check me-2" required>
                                    <div>
                                        <input class="form-check-input me-2 @error('landedType') is-invalid @enderror" type="radio" name="landedType" id="{{ $landedType->id }}" value="{{ $landedType->id }}" {{ old('landedType') == $landedType->id ? "checked" : "" }}
                                            required data-parsley-required-message="{{ setMessage('landedType.required') }}"
                                            data-parsley-errors-container="#parsley-errors-landed-type">
                                        <label class="form-check-label" for="{{ $landedType->id }}">{{ $landedType->type }}</label>

                                        @error('landedType')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                    </div>
                                </div>
                            @endforeach
                            <div id="parsley-errors-landed-type"></div>
                        </div>
                    </div>
                    
                    <p class="card-title-desc">Senarai Kelas Kuarters </p>

                    <div class="row">
                        <div class="col-sm-11 offset-sm-1">
                            <div class="row">
                                <div class="mb-1" id="parsley-errors-category-class"></div>
                            </div>
                            <table class="table table-sm table-bordered">
                                <thead class="text-white bg-primary">
                                    <tr>
                                        <th class="text-center">Bil</th>
                                        <th class="text-center">Kelas Kuarters</th>
                                        <th class="text-center">Maklumat Sewa</th>
                                        <th class="text-center">Pilih</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($quartersClassAll as $i => $quartersClass)
                                        @php $quartersClassId = $quartersClass->id; @endphp
                                        <tr>
                                            <th scope="row" class="text-center">{{$loop->iteration}}</th>
                                            <td>{{$quartersClass->class_name}}</td>
                                            <td class="text-center">
                                                <a onclick="showClassGrade({{ $quartersClass->id }});" data-route="{{ route('quartersCategory.classGradeList') }}"  id="btn-show-complaint-others" data-bs-toggle="modal" class="btn btn-outline-primary tooltip-icon">
                                                    <span class="tooltip-text">{{ __('button.papar') }}</span> <i class="{{ __('icon.view_folder') }}"></i>
                                                </a>
                                            </td>
                                            <td class="">
                                                <div class="form-check d-flex justify-content-center">

                                                    <input class="form-check-input me-2 @error('categoryClass') is-invalid @enderror" type="checkbox" id="formCheck_{{ $quartersClassId }}" name="categoryClass[]" value="{{ $quartersClassId }}" @if(old('className.' .$quartersClassId)) checked @endif
                                                        required data-parsley-required-message="{{ setMessage('categoryClass.required') }}"
                                                        data-parsley-mincheck="1" data-parsley-mincheck-message="{{ setMessage('categoryClass.required') }}"
                                                        data-parsley-errors-container="#parsley-errors-category-class">
                                                    <label class="form-check-label" for="formCheck_{{ $quartersClassId }}"> </label>
                                                    @error('categoryClass')
                                                        <div class="invalid-feedback">
                                                            {{ $message }}
                                                        </div>
                                                    @enderror
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Modal Maklumat Sewa-->
                    <div class="modal fade" id="view-maklumat-sewa" tabindex="-1" role="dialog" aria-hidden="true">
                        <div class="modal-dialog modal-xl" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Maklumat Sewa</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true"></span>
                                    </button>
                                </div>
                                <div class="modal-body" style=" margin: 0;">
                                    

                                    <div class="row">
                                        <div class="table-responsive col-sm-10 offset-sm-1">
                                            <table class="table table-sm table-bordered" id="maklumat-sewa-modal">
                                                <thead>
                                                    <tr role="row">
                                                        <th class="text-center">Bil</th>
                                                        <th class="text-center">Gred Jawatan</th>
                                                        <th class="text-center">Kategori</th>
                                                        <th class="text-center">Sewa (RM)</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="field_wrapper_listing_maklumat_sewa" ></tbody>
                                            </table>
                                        </div>
                                    </div>

                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <div class="col-sm-12">
                            <button type="submit" class="btn btn-primary float-end swal-tambah">{{ __('button.simpan') }}</button>
                            <a href="{{ route('quartersCategory.index') }}" class="btn btn-secondary float-end me-2">{{ __('button.kembali') }}</a>
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
    <script src="{{ URL::asset('assets/js/pages/QuartersCategory/quartersCategory.js')}}"></script>
    <script src="{{ URL::asset('assets/js/pages/QuartersCategory/modalClassGradeinfo.js')}}"></script>
@endsection