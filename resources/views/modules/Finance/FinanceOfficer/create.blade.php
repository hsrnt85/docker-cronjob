@extends('layouts.master')

@section('content')

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">

                {{-- PAGE TITLE --}}
                <div class="border-bottom border-primary mb-4"><h4 class="card-title">{{ getPageTitle(1) }}</h4></div>

                <form class="custom-validation" id="form" method="post" action="{{ route('financeOfficer.store') }}" >
                    {{ csrf_field() }}

                    <div class="mb-3 row">
                        <label class="col-md-2 col-form-label">Daerah</label>
                        <div class="col-md-10">
                            <input class="form-control" type="hidden" id="district" name="district" value="{{$userOffice->district->id}}" readonly>
                            <input class="form-control" type="text" id="" name="" value="{{$userOffice->district->district_name}}" readonly>
                        </div>
                    </div>

                   <div class="mb-3 row">
                        <label for="name" class="col-md-2 col-form-label">Nama Pegawai</label>
                        <div class="col-md-10">
                            <select class="form-select select2 @error('officer') is-invalid @enderror" name="officer" id="officer"
                                required data-parsley-required-message="{{ setMessage('officer.required') }}" data-route="{{ route('financeOfficer.ajaxGetPosition') }}"
                                data-parsley-errors-container="#parsley-errors-officer">
                                <option value="">-- Pilih Pegawai --</option>
                                @foreach($userAll as $user)
                                    <option value="{{$user->id }}">{{$user->name}}</option>
                                @endforeach
                            </select>
                            <div class="row">
                                <div class="col-md-10 mt-1" id="parsley-errors-officer"></div>
                            </div>
                            @error('officer')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label class="col-md-2 col-form-label">Jawatan Pegawai</label>
                        <div class="col-md-10">
                            <input class="form-control" id="position" name="position" readonly>
                            <div class="spinner-wrapper"></div>
                            <div id="parsley-errors-position"></div>
                            @error('position')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div> <hr/>

                    <div class="row section-officer-category">
                        <p class="card-title-desc">Senarai Kategori Pegawai <label class="col-form-label"></label></p>

                        <div class="table-responsive col-sm-10 offset-sm-1">
                            <div class="row">
                                <div class="mb-1" id="parsley-errors-officer-category"></div>
                            </div>
                            <table class="table table-sm table-bordered" >
                                <thead>
                                    <tr>
                                        <th class="text-center">Bil</th>
                                        <th class="text-center">Kategori Pegawai</th>
                                        <th class="text-center" width="15%">Pilih</th>
                                    </tr>
                                </thead>
                                <tbody>
                                     @foreach($financeOfficerCategory as $i => $officerCategory)
                                        <tr>
                                            <th scope="row" class="text-center">{{$loop->iteration}}</th>
                                            <td>{{$officerCategory->category_name}}</td>
                                            <td >
                                                <div class="form-check d-flex justify-content-center @error('officer_category') is-invalid @enderror">
                                                    <input class="officer_category form-check-input me-2" type="checkbox"
                                                        id="formCheck_{{ $officerCategory->id }}" name="officer_category[]" value="{{ old('officer_category.'.$i, $officerCategory->id) }}"
                                                        required data-parsley-mincheck="1"
                                                        data-parsley-required-message="{{ setMessage('officer_category.required') }}"
                                                        data-parsley-mincheck-message="{{ setMessage('officer_category.required') }}"
                                                        data-parsley-errors-container="#parsley-errors-officer-category">
                                                    <label class="form-check-label" for="formCheck_{{ $officerCategory->id }}"></label>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                               </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <div class="col-sm-12">
                            <button type="submit" class="btn btn-primary float-end swal-tambah">{{ __('button.simpan') }}</button>
                            <a href="{{ route('financeOfficer.index') }}" class="btn btn-secondary float-end me-2">{{ __('button.kembali') }}</a>
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
<script src="{{ URL::asset('assets/js/pages/FinanceOfficer/financeOfficer.js')}}"></script>
@endsection


