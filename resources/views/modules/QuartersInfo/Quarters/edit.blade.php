@extends('layouts.master')

@section('file-css')
<link rel="stylesheet" href="{{ asset('assets/css/pages/Quarters/quarters.css') }}">
@endsection

@section('content')

<div class="row">
    <div class="col-12">
        <div class="card p-3">
            <div class="card-body">
                <form class="custom-validation" method="post" action="{{ route('quarters.update', ['quarters_cat_id' => $quarters_cat_id]) }}" enctype="multipart/form-data">
                    {{ csrf_field() }}

                    {{-- PAGE TITLE --}}
                    <div class="border-bottom border-primary mb-4"><h4 class="card-title">{{ getPageTitle(2) }}</h4></div>


                    <input type="hidden" name="id" value="{{ $quarters->id }}">
                    <input type="hidden" name="quarters_cat_id" value="{{ $quarters->category->id }}">

                    <h4 class="card-title text-primary mb-4">Maklumat Kuarters</h4>

                    <div class="mb-3 row">
                        <label class="col-md-3 col-form-label">Daerah</label>
                        <div class="col-md-9">

                            <select class="form-select " id="district" name="district" disabled>
                                <option value="">-- Pilih Daerah --</option>
                                @foreach($districtAll as $district)
                                    <option value="{{ $district->id }}" {{ old('district', $quarters->category->district->id) == $district->id ? "selected" : "" }}>
                                        {{ $district->district_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label class="col-md-3 col-form-label">Kategori Kuarters (Lokasi) </label>
                        <div class="col-md-9">
                            <select class="form-select @error('quarters_category') is-invalid @enderror" id="quarters_category" name="quarters_category" data-route="{{ route('quarters.ajaxGetCategoryQuartersData') }}" required>
                                <option value="">-- Pilih Kategori --</option>
                                @foreach($quartersCategoryAll as $quartersCategory)
                                    <option value="{{ $quartersCategory->id }}" {{ $quarters->category->id == $quartersCategory->id ? "selected" : "" }}>
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
                        <label class="col-md-3 col-form-label">Jenis Kuarters</label>
                        <div class="col-md-9">
                            <input class="form-control" type="hidden" id="landed_type_id" value="{{ $quarters->category->landed_type->id }}" readonly>
                            <input class="form-control" type="text" id="landed_type" value="{{ $quarters->category->landed_type->type }}" readonly>
                        </div>
                        <div id="quarters-category-data-loading" class="col-md-1"></div>
                    </div>

                    <div class="mb-3 row">
                        <label for="unitno" class="col-md-3 col-form-label">No unit</label>
                        <div class="col-md-9">
                            <input class="form-control" type="text" id="unit_no" name="unit_no" value="{{ old('unit_no', $quarters->unit_no) }}">
                        </div>
                    </div>

                    <!-- <div class="mb-3 row">
                        <label class="col-md-3 col-form-label">No. PTD</label>
                        <div class="col-md-9">
                            <input class="form-control" type="text" id="no_ptd" name="no_ptd" value="{{ old('no_ptd', $quarters->no_ptd) }}" >
                        </div>
                        <div id="quarters-category-data-loading" class="col-md-1"></div>
                    </div> -->

                    <div class="mb-3 row">
                        <label for="name" class="col-md-3 col-form-label">Alamat 1 <span class="text-danger">*</span></label>
                        <div class="col-md-9">
                            <input class="form-control @error('address_1') is-invalid @enderror" type="text" id="address_1" name="address_1" value="{{ old('address_1', $quarters->address_1) }}"
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
                            <input class="form-control " type="text" id="address_2" name="address_2" value="{{ old('address_2', $quarters->address_2) }}">
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="name" class="col-md-3 col-form-label">Alamat 3</label>
                        <div class="col-md-9">
                            <input class="form-control " type="text" id="address_3" name="address_3" value="{{ old('address_3', $quarters->address_3) }}">
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="name" class="col-md-3 col-form-label">Cukai Tanah (RM)</label>
                        <div class="col-md-9">
                            <input class="form-control " type="text" id="land_tax" name="land_tax" value="{{ old('land_tax', $quarters->land_tax) }}">
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="name" class="col-md-3 col-form-label">Cukai Harta (RM)</label>
                        <div class="col-md-9">
                            <input class="form-control " type="text" id="property_tax" name="property_tax" value="{{ old('property_tax', $quarters->property_tax) }}" onfocus="focusInput(this);"  oninput="checkDecimal(this)">
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="name" class="col-md-3 col-form-label">Penyelenggaraan IWK</label>
                        <div class="col-sm-2">
                            <input class="form-check-input me-2" type="checkbox" id="iwk" name="iwk" value="1" @if(old('iwk', $quarters->m_utility_id) == 1) checked @endif>
                            <label class="form-check-label" for="iwk">
                                Ada
                            </label>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="name" class="col-md-3 col-form-label">Yuran IWK (RM)</label>
                        <div class="col-md-9">
                            <input class="form-control" type="text" id="iwk_fee" name="iwk_fee" value="{{ old('iwk_fee', $quarters->iwk_fee) }}" onfocus="focusInput(this);"  oninput="checkDecimal(this)">
                            <input class="form-control" type="hidden" id="iwk_fee_temp" value="{{ old('iwk_fee', $quarters->iwk_fee) }}" >
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="name" class="col-md-3 col-form-label">Yuran Penyelenggaraan (RM)</label>
                        <div class="col-md-9">
                            <input class="form-control" type="text" id="maintenance_fee" name="maintenance_fee" value="{{ old('maintenance_fee', $quarters->maintenance_fee) }}" onfocus="focusInput(this);"  oninput="checkDecimal(this)">
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label class="col-md-3 col-form-label">Keadaan Kuarters</label>
                        <div class="col-md-9">
                            <select class="form-select" id="quarters_condition" name="quarters_condition" >
                                <option value="">-- Pilih Keadaan Kuarters --</option>
                                @foreach($quartersConditionAll as $quartersCondition)
                                    <option value="{{ $quartersCondition->id }}" {{ old('quarters_condition', $quarters->quarters_condition_id) == $quartersCondition->id ? "selected" : "" }}> {{ $quartersCondition->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label class="col-md-3 col-form-label">Gambar Kuarters</label>
                        <div class="col-md-8">
                            <input class="form-control" type="file" id="quarters_picture" name="quarters_picture[]"  value="{{old('quarters_picture','')}}" multiple>
                        </div>
                        <div class="col-md-1">
                            <a href="#quartersPicture" data-bs-toggle="modal" class="btn btn-outline-primary"><i class="mdi mdi-image-search mdi-16px"></i></a>
                        </div>
                    </div>


                    <div class="modal fade" id="quartersPicture" tabindex="-1" role="dialog" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Gambar Kuarters</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true"></span>
                                    </button>
                                </div>
                                <div class="modal-body" style=" margin: 0;">
                                    <p id ="clickingText">Sila klik gambar di bawah:</p>
                                    <div class="container">
                                        <input name="quarters_id" type="hidden" value="{{$quarters->id}}">
                                        {{-- <span onclick="this.parentElement.style.display='none'" class="closebtn">&times;</span> --}}
                                        <img id="expandedImg" name="expandedImg" class="mb-4">
                                        <button type="submit" class="btn btn-outline-secondary swal-delete" class="center">Hapus <i class="mdi mdi-delete mdi-12px"></i></button>
                                    </div>
                                    <div style="text-align: center;">
                                    @foreach($quartersImageAll as $quartersImage)
                                        <img src="{{$cdn .$quartersImage->path_image}}" width= "50px" height="50px"  onclick="myFunction(this,{{ $quartersImage->id }});" style="border: 1px solid black"  >
                                    @endforeach
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                </div>
                            </div>
                        </div>
                    </div>



                    <hr>

                    <h4 class="card-title text-primary mb-4">Maklumat Kapasiti</h4>

                    <div class="mb-3 row">
                        <label for="room_no" class="col-md-3 col-form-label">Jumlah Bilik</label>
                        <div class="col-md-9">
                            <input class="form-control " type="text" id="room_no" name="room_no" value="{{ old('room_no', $quarters->room_no) }}">
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="bathroom_no" class="col-md-3 col-form-label">Jumlah Bilik Air</label>
                        <div class="col-md-9">
                            <input class="form-control " type="text" id="bathroom_no" name="bathroom_no" value="{{ old('bathroom_no', $quarters->bathroom_no) }}">
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
                                            <td class="">
                                                <div class="form-check d-flex justify-content-center">
                                                    <input class="form-check-input me-2 status_inventory" data-inventory-id="{{ $inventory->id }}" type="checkbox" id="formCheck_{{ $inventory->id }}"
                                                        name="inventory[{{$inventory->id}}]" onclick="allowQuantity({{ $inventory->id }});"
                                                        value="1"
                                                        @if(old('inventory.' . $inventory->id, $quartersInventoryAll->find($inventory->id) != null)) checked @endif>
                                                    <label class="form-check-label" for="formCheck_{{ $inventory->id }}">
                                                        Ada
                                                    </label>
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                <div class="">
                                                    <input class="form-control form-control-sm text-center @if($errors->has('quantity.' . $inventory->id)) is-invalid @endif" type="text"
                                                    id="quantity_{{ $inventory->id }}" oninput="checkNumber(this)" onfocus="focusInput(this);"
                                                    name="quantity[{{$inventory->id}}]"
                                                    value="{{ old('quantity.' . $inventory->id, $quartersInventoryAll->find($inventory->id) != null ? $quartersInventoryAll->find($inventory->id)->pivot->quantity : '') }}">
                                                </div>
                                                @error('quantity.' . $inventory->id)
                                                    <div class="d-block invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </td>
                                            <td class="text-center">{{$inventory->price}}</td>
                                            <td class="text-center">
                                                <div class="text-center">
                                                    <select class="form-select responsibility responsibility_{{ $inventory->id }}" id="responsibility_{{ $inventory->id }}" name="responsibility[{{ $inventory->id }}]"
                                                        required data-parsley-required-message="{{ setMessage('responsibility.required') }}" data-parsley-errors-container="#parsley-errors-responsibility_{{ $inventory->id }}">
                                                        <option value="">-- Pilih Jabatan Bertanggungjawab (Inventori) --</option>
                                                            @foreach($maintenanceInventoryAll as $maintenanceInventory)
                                                                <option value="{{ $maintenanceInventory->id }}"
                                                                    {{ old('responsibility.' . $inventory->id, $quartersInventoryAll->find($inventory->id) != null ? $quartersInventoryAll->find($inventory->id)->pivot->m_inventory_id : '') == $maintenanceInventory->id ? "selected" : "" }}>
                                                                    {{ $maintenanceInventory->name }}
                                                                </option>
                                                            @endforeach

                                                    </select>
                                                     {{-- <div class="d-flex justify-content-center"> --}}
                                                            {{-- @foreach($maintenanceInventoryAll as $maintenanceInventory)
                                                                <div class="form-check me-2">
                                                                    <div>
                                                                        <input class="form-check-input me-2 responsibility responsibility_{{ $inventory->id }}" type="radio"
                                                                        name="responsibility[{{ $inventory->id }}]"
                                                                        id="responsibility_{{ $inventory->id }}"
                                                                        value="{{ $maintenanceInventory->id }}"
                                                                        {{ old('responsibility.' . $inventory->id, $quartersInventoryAll->find($inventory->id) != null ? $quartersInventoryAll->find($inventory->id)->pivot->m_inventory_id : '') == $maintenanceInventory->id ? "checked" : "" }}>
                                                                        <label class="form-check-label" for="responsibility_{{ $maintenanceInventory->id }}_{{ $inventory->id }}">
                                                                            {{ $maintenanceInventory->name }}
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                            @endforeach --}}
                                                        {{-- </div> --}}
                                                    {{-- @error('responsibility.' . $inventory->id)
                                                        <div class="d-block invalid-feedback">
                                                            {{ $message }}
                                                        </div>
                                                    @enderror --}}
                                                </div>
                                                <div id="parsley-errors-responsibility_{{ $inventory->id }}"></div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <hr>
                    <div class="mb-3 row">
                        <div class="col-sm-12">
                            <button type="submit" class="btn btn-primary float-end swal-mengemaskini">{{ __('button.kemaskini') }}</button>
                            <a href="{{ route('quarters.index', ['quarters_cat_id' => $quarters->category->id]) }}" class="btn btn-secondary float-end me-2">{{ __('button.kembali') }}</a>
                        </div>
                    </div>
                </form>
                <form method="POST" action="{{ route('quarters.destroyImage') }}" id="delete-form">
                {{ csrf_field() }}
                {{ method_field('DELETE') }}
                <input type="hidden" name="attachment_id" id="attachment_id" >
                <input class="form-control" type="hidden" id="id" name="id" value="{{ $quarters->id }}">
                <input class="form-control" type="hidden" id="id" name="quarters_cat_id" value="{{ $quarters->quarters_cat_id }}">
               </form>
            </div>
        </div>
    </div> <!-- end col -->
</div>
<!-- end row -->

@endsection

@section('script')
<script src="{{ URL::asset('assets/js/pages/Quarters/quarters.js')}}"></script>
@endsection
