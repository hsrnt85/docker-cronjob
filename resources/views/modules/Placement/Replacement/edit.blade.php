@extends('layouts.master')

@section('content')

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">

                <h4 class="card-title">{{ __('button.kemaskini') }} Pertukaran Penempatan</h4>

                <form method="post" action="{{ route('replacement.update') }}" enctype="multipart/form-data" id="app-form">
                    {{ csrf_field() }}

                    <input type="hidden" name="id" value="{{ $application->id }}">

                    <div class="mb-3 row">
                        <label for="district" class="col-md-2 col-form-label">Kuarters Kategori</label>
                        <div class="col-md-9">
                            <select class="form-select @error('quarters_category') is-invalid @enderror" id="quarters_category" name="quarters_category" data-route="{{ route('replacement.ajaxGetAddressByCategory') }}"
                                required>
                                    <option value="">-- Pilih Kuarters --</option>
                                    @foreach($categoryAll as $category)
                                        <option value="{{ $category->id }}" {{ ($application->selected_category()->id == $category->id )? "selected" : "" }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                            </select>

                            @error('quarters_category')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="name" class="col-md-2 col-form-label">Alamat</label>
                        <div class="col-md-9">
                            <select class="form-select select_address @error('quarters') is-invalid @enderror" id="address" name="address" data-route="{{ route('placement.ajaxGetAvailableUnitByAddr') }}"
                                required
                                data-parsley-required-message="Sila pilih alamat">
                                    <option value="">-- Pilih Alamat --</option>
                                    @foreach($addressAll as $address)
                                        <option value="{{ $address->address_1 }}" {{ ($application->selected_quarters()->address_1 == $address->address_1)? "selected" : "" }}>
                                            {{ $address->address_1 }}
                                        </option>
                                    @endforeach
                            </select>
                        </div>
                        <div class="col-md-1 spinner-wrapper"></div>
                    </div>

                    <div class="mb-3 row">
                        <label for="name" class="col-md-2 col-form-label">No Unit</label>
                        <div class="col-md-9">
                            <select class="form-select select_unit" name="quarters"
                                required
                                data-parsley-required-message="Sila pilih unit">
                                    <option value="">-- Pilih Unit --</option>
                                    <option value="{{ $application->selected_quarters()->id }}" selected>{{ $application->selected_quarters()->unit_no }}</option>
                                    @foreach($availableUnitAll as $availableUnit)
                                        <option value="{{ $availableUnit->id }}">{{ $availableUnit->unit_no }}</option>
                                    @endforeach
                            </select>

                            @error('unit_no')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="col-md-1 spinner-wrapper"></div>
                    </div>

                    <div class="mb-3 row">
                        <label for="name" class="col-md-2 col-form-label">Sebab Pertukaran</label>
                        <div class="col-md-9">
                            <textarea class="form-control" type="text" name="reason"
                                required
                                data-parsley-required-message="Sila isi sebab pertukaran"></textarea>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="name" class="col-md-2 col-form-label">Surat Pertukaran</label>
                        <div class="col-md-9">
                            <input class="form-control" type="file" name="reason_attachment"
                                required
                                data-parsley-fileextension='pdf'
                                data-parsley-required-message="Sila muat naik pertukaran"
                                data-parsley-fileextension-message="Sila muat naik fail lain"
                                data-parsley-max-file-size="2000"
                                data-parsley-max-file-size-message="Fail mestilah kurang daripada 2000 kB"/>
                                <p class="card-title-desc">* PDF sahaja | Bawah 2 MB</p>
                        </div>
                    </div>


                    <div class="mb-3 row">
                        <div class="col-sm-10 offset-sm-1">
                            <button type="submit" class="btn btn-primary float-end swal-kemaskini">{{ __('button.kemaskini') }}</button>
                            <a href="{{ route('replacement.index') }}" class="btn btn-secondary float-end me-2">{{ __('button.kembali') }}</a>
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
<script src="{{ URL::asset('assets/js/pages/Placement/replacement.js')}}"></script>
@endsection
