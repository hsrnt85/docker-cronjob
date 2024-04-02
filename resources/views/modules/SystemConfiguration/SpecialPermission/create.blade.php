@extends('layouts.master')

@section('content')

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">

                {{-- PAGE TITLE --}}
                <div class="border-bottom border-primary mb-4"><h4 class="card-title">{{ getPageTitle(1) }}</h4></div>

                <form class="custom-validation" method="post" action="{{ route('specialPermission.store') }}" enctype="multipart/form-data" id="form">
                    {{ csrf_field() }}

                    <div class="mb-3 row" hidden>
                        <label for="user_id" class="col-md-3 col-form-label ">Id Pengguna</label>
                        <div class="col-md-9">
                            <input class="form-control" id="user_id" name="user_id" value="{{ old('user_id') }}" readonly>

                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="new_ic" class="col-md-3 col-form-label ">No. Kad Pengenalan (Baru)</label>
                        <div class="col-md-9">
                            <input class="form-control @error('new_ic') is-invalid @enderror" type="text" id="new_ic" name="new_ic" value="{{ old('new_ic') }}"
                                oninput="checkNumber(this)" onkeyup="autofill_user();" onblur="autofill_user();"
                                minlength="12" maxlength="12" data-parsley-length-message="{{ setMessage('new_ic.digits') }}"
                                required data-parsley-required-message="{{ setMessage('new_ic.required') }}">
                            @error('new_ic')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                            <div id="new_ic_error"></div>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="name" class="col-md-3 col-form-label ">Nama</label>
                        <div class="col-md-9">
                            <input class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" readonly>
                            @error('name')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="email" class="col-md-3 col-form-label ">Emel</label>
                        <div class="col-md-9">
                            <input class="form-control input-mask @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" readonly>
                            @error('email')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="position" class="col-md-3 col-form-label ">Jawatan</label>
                        <div class="col-md-9">
                            <input class="form-control @error('position') is-invalid @enderror" id="position" name="position" value="{{ old('position') }}" readonly>
                            @error('position')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="position_type" class="col-md-3 col-form-label ">Kod Jawatan</label>
                        <div class="col-md-9">
                            <input class="form-control @error('position_type') is-invalid @enderror" id="position_type" name="position_type" value="{{ old('position_type') }}" readonly>
                            @error('position_type')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="position_grade" class="col-md-3 col-form-label ">Gred Jawatan</label>
                        <div class="col-md-9">
                            <input class="form-control @error('position_type') is-invalid @enderror" id="position_grade" name="position_grade" value="{{ old('position_grade') }}" readonly>
                            @error('position_grade')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="organization" class="col-md-3 col-form-label ">Jabatan/Agensi Bertugas</label>
                        <div class="col-md-9">
                            <input class="form-control @error('organization') is-invalid @enderror" id="organization" name="organization" value="{{ old('organization') }}" readonly>
                            @error('organization')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="services_type" class="col-md-3 col-form-label ">Jenis Perkhidmatan</label>
                        <div class="col-md-9">
                            <input class="form-control @error('services_type') is-invalid @enderror" id="services_type" name="services_type" value="{{ old('services_type') }}" readonly>
                            @error('services_type')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="district" class="col-md-3 col-form-label ">Daerah</label>
                        <div class="col-md-9">
                            <input class="form-control @error('services_type') is-invalid @enderror" id="district" name="district" value="{{ old('district') }}" readonly>
                            @error('district')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="remarks" class="col-md-3 col-form-label ">Ulasan</label>
                        <div class="col-md-9">
                            <textarea class="form-control @error('remarks') is-invalid @enderror" rows="3" id="remarks" name="remarks" required data-parsley-required-message="{{ setMessage('remarks.required') }}"></textarea>
                            @error('remarks')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="supporting_document" class="col-md-3 col-form-label ">Dokumen Sokongan</label>
                    <div class="col-md-9">
                        <table class="table  table-sm table-bordered border-secondary" id="table-supporting-document" data-list="0">
                            <thead class="bg-primary bg-gradient text-white">
                                <tr>
                                    <th class="text-center">Dokumen Sokongan</th>
                                    <th class="text-center"><a onclick="duplicateRow();" class="btn btn-success btn-sm" ><i class="mdi mdi-plus mdi-18px"></i></a></th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(old('supporting_document', null) != null)
                                        @foreach(old('supporting_document') as $i => $document)
                                            <tr id="tr-supporting-document{{$i}}">
                                                <td>
                                                    <input type="file" class="form-control supporting_document @error('supporting_document.'.$i) is-invalid @enderror"  id="supporting_document{{$i}}" name="supporting_document[{{$i}}][]" value="{{old('supporting_document','')}}" multiple
                                                    required data-parsley-required-message="{{ setMessage('supporting_document.required') }}">
                                                </td>
                                                <td class="text-center"><a id="btnRemove{{$i}}" class='btnRemove btn btn-warning btn-sm'><i class='mdi mdi-minus mdi-16px'></i></a></td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr id="tr-supporting-document0" >
                                            <td><input class="form-control" type="file" name='supporting_document[]' id='supporting_document0' multiple required data-parsley-required-message="{{ setMessage('supporting_document.required') }}" multiple></td>
                                            <td class='text-center'>
                                                <input type="hidden" id="supporting_document_id0" name="supporting_document_id[]" value="0">
                                                <a id="btnRemove0" class='btnRemove btn btn-warning btn-sm' data-row-index="0"><i class='mdi mdi-minus mdi-18px'></i></a>
                                            </td>
                                        </tr>
                                    @endif
                            </tbody>
                        </table>
                        <p class="card-title-desc">* PDF sahaja | Bawah 2 MB</p>
                    </div>
                </div>

                    <div class="border-top row">
                        <div class="col-sm-12 mt-3">
                             <button type="submit" id="btn-submit" class="btn btn-primary float-end swal-tambah">{{ __('button.simpan') }}</button>
                            <a href="{{ route('specialPermission.index') }}" class="btn btn-secondary float-end me-2">{{ __('button.kembali') }}</a>
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
<script src="{{ URL::asset('assets/js/pages/SpecialPermission/specialPermission.js')}}"></script>
@endsection
