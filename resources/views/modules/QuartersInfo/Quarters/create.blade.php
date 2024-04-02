@extends('layouts.master')

@section('content')

<div class="row">
    <div class="col-12">
        <div class="card p-3">
            <div class="card-body">

                {{-- PAGE TITLE --}}
                <div class="border-bottom border-primary mb-4"><h4 class="card-title">{{ getPageTitle(2) }}</h4></div>

                <form class="custom-validation" id="form" method="post" action="{{ route('quarters.store', ['quarters_cat_id' => $quarters_cat_id]) }}" enctype="multipart/form-data">
                    {{ csrf_field() }}

                    <div class="mb-3 row">

                        <h4 class="card-title text-primary mb-4">Maklumat Kuarters</h4>

                        <label class="col-md-3 col-form-label">Daerah <span class="text-danger">*</span></label>
                        <div class="col-md-9">
                            @if($quarters_cat_id>0)
                                <select class="form-control" id="district" name="district" required readonly>
                                    @foreach($districtAll as $district)
                                        <option value="{{$district->id}}" selected>{{$district->district_name}}</option>
                                    @endforeach
                                </select>
                            @else
                                <select class="form-select select2" id="district" name="district"  data-route="{{ route('quarters.ajaxGetCategoryQuarters') }}"  required @if($quarters_cat_id>0) readonly @endif>
                                    <option value="">-- Pilih Daerah --</option>
                                    @foreach($districtAll as $district)
                                        <option value="{{$district->id}}" {{ old('district') == $district->id ? "selected" : "" }}>{{$district->district_name}}</option>
                                    @endforeach
                                </select>
                            @endif
                            @error('district')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label class="col-md-3 col-form-label">Kategori Kuarters (Lokasi) <span class="text-danger">*</span></label>
                        <div class="col-md-9">
                            @if($quarters_cat_id>0)
                                <select class="form-control @error('quarters_category') is-invalid @enderror" id="quarters_category" name="quarters_category" required readonly>
                                    <option value="{{ $quartersCategoryAll->id }}" selected> {{ $quartersCategoryAll->name }}</option>
                                </select>
                            @else
                                <select class="form-select select2 @error('quarters_category') is-invalid @enderror" id="quarters_category" name="quarters_category" data-route="{{ route('quarters.ajaxGetCategoryQuartersData') }}" required ></select>
                            @endif
                            @error('quarters_category')
                                <div id="quarters-category-feedback" class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div id="quarters-category-loading" class="col-md-1"></div>
                    </div>

                    <div class="mb-3 row">
                        <label class="col-md-3 col-form-label">Jenis Kuarters</label>
                        <div class="col-md-9">
                            @if($quarters_cat_id>0)
                                <input class="form-control" type="hidden" id="landed_type_id" value="{{ $quartersCategoryAll->landed_type->id }}" readonly>
                                <input class="form-control" type="text" id="landed_type" value="{{ $quartersCategoryAll->landed_type->type }}" readonly>
                            @else
                                <input class="form-control" type="text" id="landed_type" readonly>
                            @endif
                        </div>
                        <div id="quarters-category-data-loading" class="col-md-1"></div>
                    </div>

                    <!-- <div class="mb-3 row">
                        <label class="col-md-3 col-form-label">No. PTD</label>
                        <div class="col-md-9">
                            <input class="form-control" type="text" id="no_ptd" name="no_ptd" value="{{ old('no_ptd') }}">
                        </div>
                        <div id="quarters-category-data-loading" class="col-md-1"></div>
                    </div> -->

                    <div class="mb-3 row">
                        <label for="name" class="col-md-3 col-form-label">Alamat 1 <span class="text-danger">*</span></label>
                        <div class="col-md-9">
                            <input class="form-control @error('address_1') is-invalid @enderror" type="text" id="address_1" name="address_1" value="{{ old('address_1') }}"
                            required data-parsley-required-message="{{ setMessage('address_1.required') }}">
                            @error('address_1')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="name" class="col-md-3 col-form-label">Alamat 2</label>
                        <div class="col-md-9">
                            <input class="form-control" type="text" id="address_2" name="address_2" value="{{ old('address_2') }}">
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="name" class="col-md-3 col-form-label">Alamat 3</label>
                        <div class="col-md-9">
                            <input class="form-control" type="text" id="address_3" name="address_3" value="{{ old('address_3') }}">
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="name" class="col-md-3 col-form-label">Cukai Harta (RM)</label>
                        <div class="col-md-9">
                            <input class="form-control" type="text" id="property_tax" name="property_tax" value="{{ old('property_tax') }}" oninput="checkDecimal(this)">
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="name" class="col-md-3 col-form-label">Cukai Tanah (RM)</label>
                        <div class="col-md-9">
                            <input class="form-control" type="text" id="land_tax" name="land_tax" value="{{ old('land_tax') }}" onfocus="focusInput(this);"  oninput="checkDecimal(this)">
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="name" class="col-md-3 col-form-label">Penyelenggaraan IWK</label>
                        <div class="col-md-1">
                            <input class="form-check-input mt-2 me-2" type="checkbox" id="iwk" name="iwk" value="1" @if(old('iwk')) checked @endif>
                            <label class="form-check-label mt-2" for="iwk">Ada</label>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="name" class="col-md-3 col-form-label">Yuran IWK (RM)</label>
                        <div class="col-md-9">
                            <input class="form-control" type="text" id="iwk_fee" name="iwk_fee" value="{{ old('iwk_fee') }}" onfocus="focusInput(this);" oninput="checkDecimal(this)">
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="name" class="col-md-3 col-form-label">Yuran Penyelenggaraan (RM)</label>
                        <div class="col-md-9">
                            <input class="form-control" type="text" id="maintenance_fee" name="maintenance_fee" value="{{ old('maintenance_fee') }}" onfocus="focusInput(this);" oninput="checkDecimal(this)">
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label class="col-md-3 col-form-label">Keadaan Kuarters</label>
                        <div class="col-md-9">
                            <select class="form-select" id="quarters_condition" name="quarters_condition" >
                                <option value="">-- Pilih Keadaan Kuarters --</option>
                                @foreach($quartersConditionAll as $quartersCondition)
                                    <option value="{{ $quartersCondition->id }}" {{ old('quarters_condition') == $quartersCondition->id ? "selected" : "" }}> {{ $quartersCondition->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label class="col-md-3 col-form-label">Gambar Kuarters</label>
                        <div class="col-md-9">
                            <input class="form-control" type="file" id="quarters_picture" name="quarters_picture[]"  value="{{old('quarters_picture','')}}" multiple>
                        </div>
                    </div>

                    <hr>
                    <h4 class="card-title text-primary mb-4">Maklumat Kapasiti</h4>

                    <div class="mb-3 row">
                        <label for="name" class="col-md-3 col-form-label">Jumlah Bilik</label>
                        <div class="col-md-9">
                            <input class="form-control" type="number" id="room_no" name="room_no" value="{{ old('room_no') }}">
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="name" class="col-md-3 col-form-label">Jumlah Bilik Air</label>
                        <div class="col-md-9">
                            <input class="form-control" type="number" id="bathroom_no" name="bathroom_no" value="{{ old('bathroom_no') }}">
                        </div>
                    </div>

                    <hr>

                    <h4 class="card-title text-primary mb-4">Maklumat Inventori</h4>
                    <div class="row">

                        <div class="text-danger mb-2">** Ruangan ini adalah pilihan. Sila lengkapkan maklumat Kuantiti, Harga (RM), Jabatan Bertanggungjawab (Inventori), jika Status Inventori telah dipilih.</div>

                        <div class="table-responsive col-sm-12">
                            <table class="table table-sm table-bordered" id="table-inventory">
                                <thead class="text-white bg-primary">
                                    <tr>
                                        <th width="5%" class="text-center">Bil</th>
                                        <th width="20%" class="text-center">Inventori</th>
                                        <th width="10%" class="text-center">Status</th>
                                        <th width="15%" class="text-center">Kuantiti</th>
                                        <th width="10%" class="text-center">Harga (RM)</th>
                                        <th width="30%" class="text-center">Jabatan Bertanggungjawab (Inventori)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($inventoryAll as $inventory)
                                        <tr>
                                            <th scope="row" class="text-center">{{$loop->iteration}}</th>
                                            <td>{{$inventory->name}}</td>
                                            <td >
                                                <div class="form-check d-flex justify-content-center">
                                                    <input class="form-check-input me-2 status_inventory" data-inventory-id="{{ $inventory->id }}" type="checkbox"  id="formCheck_{{ $inventory->id }}" name="inventory[{{$inventory->id}}]" onclick="allowQuantity({{ $inventory->id }});"
                                                    {{-- required data-parsley-required-message="{{ setMessage('status_inventory.required') }}" --}}
                                                    data-parsley-errors-container="#parsley-errors-status-inventory-{{ $inventory->id }}"
                                                    value="1" @if(old('inventory.' . $inventory->id)) checked @endif>
                                                    <label class="form-check-label" for="formCheck_{{ $inventory->id }}"> Ada </label>
                                                </div>
                                                <div id="parsley-errors-status-inventory-{{ $inventory->id }}"></div>
                                            </td>
                                            <td class="text-center">
                                                <div class="">
                                                    <input class="form-control form-control-sm qty text-center quantity_{{ $inventory->id }} @if($errors->has('quantity.' . $inventory->id)) is-invalid @endif" type="text" id="quantity_{{ $inventory->id }}" name="quantity[{{$inventory->id}}]"
                                                    oninput="checkNumber(this)" onfocus="focusInput(this);" required data-parsley-required-message="{{ setMessage('quantity.required') }}" data-parsley-errors-container="#parsley-errors-quantity-{{ $inventory->id }}">
                                                </div>
                                                <div id="parsley-errors-quantity-{{ $inventory->id }}"></div>
                                                @error('quantity.' . $inventory->id)
                                                    <div class="d-block invalid-feedback"> {{ $message }}</div>
                                                @enderror
                                            </td>
                                            <td class="text-center">{{ $inventory->price }}</td>
                                            <td class="text-center">
                                                <div class="text-center">
                                                    <select class="form-select responsibility responsibility_{{ $inventory->id }}" id="responsibility_{{ $inventory->id }}" name="responsibility[{{ $inventory->id }}]"
                                                        required data-parsley-required-message="{{ setMessage('responsibility.required') }}" data-parsley-errors-container="#parsley-errors-responsibility-{{ $inventory->id }}">
                                                        <option value="">-- Pilih Jabatan Bertanggungjawab (Inventori) --</option>
                                                        @foreach($maintenanceInventoryAll as $maintenanceInventory)
                                                            <option value="{{ $maintenanceInventory->id }}" {{ old('responsibility.' . $inventory->id) == $maintenanceInventory->id  ? "selected" : "" }}>{{ $maintenanceInventory->name }}</option>
                                                            {{-- <div class="form-check me-2">
                                                                <div>
                                                                    <input class="form-check-input me-2 responsibility responsibility_{{ $inventory->id }}" type="radio" name="responsibility[{{ $inventory->id }}]"
                                                                    required data-parsley-required-message="{{ setMessage('responsibility.required') }}" data-parsley-errors-container="#parsley-errors-all"
                                                                            value="{{ $maintenanceInventory->id }}" {{ old('responsibility.' . $inventory->id) == $maintenanceInventory->id ? "checked" : "" }}>
                                                                    <label class="form-check-label" for="responsibility_{{ $maintenanceInventory->id }}_{{ $inventory->id }}">
                                                                        {{ $maintenanceInventory->name }}
                                                                    </label>
                                                                </div>
                                                            </div> --}}
                                                        @endforeach
                                                    </select>
                                                    @error('responsibility.' . $inventory->id)
                                                        <div class="d-block invalid-feedback">
                                                            {{ $message }}
                                                        </div>
                                                    @enderror
                                                </div>
                                                <div id="parsley-errors-responsibility-{{ $inventory->id }}"></div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <hr>

                    <h4 class="card-title mb-4">Bilangan Kuarters Yang Sama </h4>

                    <div class="mb-3 row">
                        <label for="name" class="col-md-3 col-form-label">Jumlah <span class="text-danger">*</span></label>
                        <div class="col-sm-2">
                            <input class="form-control @error('total') is-invalid @enderror text-center" type="number" id="total" name="total" value="{{ old('total') }}"
                            required data-parsley-required-message="{{ setMessage('total.required') }}" >
                            @error('total')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <div class="col-sm-12">
                            <button type="submit" class="btn btn-primary float-end swal-tambahkuarters">{{ __('button.simpan') }}</button>
                            @if($quarters_cat_id>0)
                            <a href="{{ route('quarters.index', ['quarters_cat_id' => $quarters_cat_id]) }}" class="btn btn-secondary float-end me-2">{{ __('button.kembali') }}</a>
                            @else
                            <a href="{{ route('listQuartersCategory.index') }}" class="btn btn-secondary float-end me-2">{{ __('button.kembali') }}</a>
                            @endif
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
<script src="{{ URL::asset('assets/js/pages/Quarters/quarters.js')}}"></script>
<script src="{{ URL::asset('/assets/libs/parsleyjs/parsleyjs.min.js')}}"></script>
@endsection
