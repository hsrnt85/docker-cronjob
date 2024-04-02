@extends('layouts.master')

@section('content')

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">

                {{-- PAGE TITLE --}}
                <div class="border-bottom border-primary mb-4"><h4 class="card-title">{{ getPageTitle(2) }}</h4></div>

                <form method="post" action="{{ route('placement.update') }}" >
                    {{ csrf_field() }}

                    <input type="hidden" name="id" value="{{ $application->id }}">

                    <div class="mb-3 row">
                        <label for="district" class="col-md-2 col-form-label">Kuarters Kategori</label>
                        <div class="col-md-9">
                            <select class="form-select @error('quarters_category') is-invalid @enderror" id="quarters_category" name="quarters_category" data-route="{{ route('placement.ajaxGetUnitNo') }}">
                                <option value="">-- Pilih Kuarters --</option>
                                @foreach($quartersCategoryAll as $quartersCategory)
                                    <option value="{{ $quartersCategory->id }}" {{ ($application->selected_category()->id == $quartersCategory->id )? "selected" : "" }}>
                                        {{ $quartersCategory->name }}
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
                        <label for="name" class="col-md-2 col-form-label">No unit</label>
                        <div class="col-md-9">
                            <select class="form-select @error('quarters') is-invalid @enderror" id="quarters" name="quarters" required >
                                <option value="">-- Pilih Unit --</option>
                                @foreach($quartersAll as $data)
                                    <option value="{{ $data->id }}" {{ (old('unit_no') == $data->id ) ? "selected" : "" }}>
                                        {{ $data->unit_no }}
                                    </option>
                                @endforeach
                            </select>

                            @error('quarters')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="col-md-1 spinner-wrapper"></div>
                    </div>

                    <div class="mb-3 row">
                        <label for="name" class="col-md-2 col-form-label">Alamat</label>
                        <div class="col-md-9">
                            <input class="form-control" type="text" id="address1" value="{{ $quarters?->address_1 }}" readonly>
                        </div>
                        <div class="col-md-1 spinner-wrapper"></div>
                    </div>

                    <div class="mb-3 row">
                        <div class=" offset-md-2 col-md-9">
                            <input class="form-control" type="text" id="address2" value="{{ $quarters?->address_2 }}" readonly>
                        </div>
                        <div class="col-md-1 spinner-wrapper"></div>
                    </div>

                    <div class="mb-3 row">
                        <div class=" offset-md-2 col-md-9">
                            <input class="form-control" type="text" id="address3" value="{{ $quarters?->address_3 }}" readonly>
                        </div>
                        <div class="col-md-1 spinner-wrapper"></div>
                    </div>

                    <div class="mb-3 row">
                        <div class="col-sm-10 offset-sm-1">
                            <button type="submit" class="btn btn-primary float-end swal-kemaskini">{{ __('button.kemaskini') }}</button>
                            <a href="{{ route('placement.listPlacement', ['category' => $application->selected_category()->id]) }}" class="btn btn-secondary float-end me-2">{{ __('button.kembali') }}</a>
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
<script src="{{ URL::asset('assets/js/pages/Placement/placement.js')}}"></script>
@endsection
