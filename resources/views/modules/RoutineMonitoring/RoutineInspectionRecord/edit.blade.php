@extends('layouts.master')
@section('content')

<div class="row">
    <div class="col-12">
        <div class="card p-3">
            <div class="card-body">

                {{-- PAGE TITLE --}}
                <div class="border-bottom border-primary mb-4"><h4 class="card-title">{{ getPageTitle(2) }}</h4></div>

                <form class="custom-validation" id="form" method="post" action="{{ route('routineInspectionRecord.update') }}" enctype="multipart/form-data">
                    {{ csrf_field() }}

                    <input type="hidden" name="quarters_category_id" value="{{ $category->id }}">
                    <input type="hidden" name="inspection_id" value="{{ $inspection->id }}">

                    <div class="mb-3 row">
                        <label for="name" class="col-md-3 col-form-label">Kategori Kuarters (Lokasi)</label>
                        <div class="col-md-9">
                            <input class="form-control" type="text" id="quarters_category" name="quarters_category" value="{{ $inspection->quarters_category->name }}" readonly>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label class="col-md-3 col-form-label">Alamat Kuarters <span class="text-danger">*</span></label>
                        <div class="col-md-9">
                            <select class="form-select" id="address" name="address" required data-parsley-required-message="{{ setMessage('address_1.required') }}">
                                <option value=""> --  Pilih Alamat -- </option>
                                @foreach($addressAll as $address)
                                    <option value="{{ $address->address_1 }}" {{ ($inspection->address == $address->address_1) ? 'selected' : '' }} > {{ $address->address_1 }} </option>
                                @endforeach
                            </select>
                            @error('address')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label class="col-md-3 col-form-label">Pegawai Pemantau <span class="text-danger">*</span></label>
                        <div class="col-md-9">
                            <select class="form-select" id="pemantau" name="pemantau" required data-parsley-required-message="{{ setMessage('pemantau.required') }}">
                                <option value=""> --  Pilih Pegawai Pemantau -- </option>
                                @foreach($pemantauAll as $pemantau)
                                    <option value="{{ $pemantau->id }}"  {{ ($inspection->monitoring_officer_id == $pemantau->id) ? 'selected' : '' }}> {{ $pemantau->user->name }} </option>
                                @endforeach
                            </select>
                            @error('pemantau')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="name" class="col-md-3 col-form-label">Tarikh Pemantauan</label>
                        <div class="col-md-9">
                            <div class="input-group">
                            {{-- <input class="form-control" type="date" id="tarikh_pemantauan" name="tarikh_pemantauan" value="{{ convertDateDb($inspection->inspection_date) }}" required data-parsley-required-message="{{ setMessage('tarikh_pemantauan.required') }}"> --}}
                                <input class="form-control datepicker" data-provide="datepicker" placeholder="dd/mm/yyyy" type="text" id="tarikh_pemantauan" name="tarikh_pemantauan" value="{{ convertDateSys($inspection->inspection_date) }}" required data-parsley-required-message="{{ setMessage('tarikh_pemantauan.required') }}">
                                <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="name" class="col-md-3 col-form-label">Tugasan</label>
                        <div class="col-md-9">
                            <textarea class="form-control" name="catatan" required data-parsley-required-message="{{ setMessage('catatan.required') }}">{{ $inspection->remarks }}</textarea>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <div class="col-sm-12">
                            <button type="submit" class="btn btn-primary float-end swal-tambahkuarters">{{ __('button.simpan') }}</button>
                            <a href="{{ route('routineInspectionRecord.listInspection', $category) }}" class="btn btn-secondary float-end me-2">{{ __('button.kembali') }}</a>
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
    <script src="{{ URL::asset('/assets/libs/parsleyjs/parsleyjs.min.js')}}"></script>
@endsection
