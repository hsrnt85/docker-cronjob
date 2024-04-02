@extends('layouts.master')

@section('content')

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">

                {{-- PAGE TITLE --}}
                <div class="border-bottom border-primary mb-4"><h4 class="card-title">{{ getPageTitle(1) }}</h4></div>

                <form class="custom-validation" id="form" method="post" action="{{ route('quartersClass.update') }}" >
                    {{ csrf_field() }}

                    <input class="form-control" type="hidden" id="id" name="id" value="{{ $quartersClass->id }}">

                    <div class="mb-3 row">
                        <label for="class_name" class="col-md-2 col-form-label">Kelas Kuarters</label>
                        <div class="col-md-10">
                            <input class="form-control  @error('class_name') is-invalid @enderror" type="text" id="class_name" name="class_name" value="{{ $quartersClass->class_name }}">
                            @error('class_name')
                            <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="district" class="col-md-2 col-form-label">Daerah</label>
                        <div class="col-md-10">
                            <!-- <input class="form-control @error('district') is-invalid @enderror" type="text" id="district" name="district" value="{{ old('district') }}"
                            required data-parsley-required-message="{{ setMessage('class_name.required') }}"> -->
                            <select class="form-select @error('quarters_category') is-invalid @enderror" id="district" name="district"
                                required 
                                data-parsley-required-message="{{ setMessage('district.required') }}">
                                    <option value="">-- Pilih Daerah --</option>
                                    @foreach($districtAll as $district)
                                        <option value="{{ $district->id }}" {{ ($district->id == $quartersClass->district_id )? "selected" : "" }}>
                                            {{ $district->district_name }}
                                        </option>
                                    @endforeach
                            </select>

                            @error('district')
                            <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <hr>

                    <h4 class="card-title">Maklumat Sewa</h4>

                    <section>
                        <div class="row">
                            <div>
                                <div class="table-responsive col-sm-10 offset-sm-1">
                                    <table class="table table-sm table-bordered" id="table-gred-list" data-list="{{$senaraiSewaAll->count()}}">
                                        <thead class="text-white bg-primary">
                                            <tr>
                                                <th class="text-center">Gred Jawatan</th>
                                                <th class="text-center">Kategori</th>
                                                <th class="text-center">Harga Pasaran (RM)</th>
                                                <th class="text-center">Sewa (RM)</th>
                                                <th class="text-center"><a onclick="duplicateRow();" class="btn btn-primary btn-sm"><i class="mdi mdi-plus mdi-18px"></i></a></th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                            @if(old('p_grade_id') != null)
                                                @foreach (old('p_grade_id') as $i => $p_grade_id)
                                                    <tr id="tr-gred-list{{$i}}">
                                                        <td>
                                                            <select class="form-control @error('p_grade_id.'.$i) is-invalid @enderror" id="p_grade_id{{$i}}" name="p_grade_id[]" required="required" data-parsley-required-message="{{ setMessage('grade_jawatan.required') }}">
                                                                <option value="">Sila Pilih</option>
                                                                @foreach($gredJawatanAll as $gredJawatan)
                                                                    @if($p_grade_id == $gredJawatan -> id)
                                                                        <option value="{{ $gredJawatan -> id }}" selected>{{ $gredJawatan -> grade_no }}</option>
                                                                    @else
                                                                        <option value="{{ $gredJawatan -> id }}">{{ $gredJawatan -> grade_no }}</option>
                                                                    @endif
                                                                @endforeach
                                                            </select>
                                                        </td>
                                                        <td>
                                                            <select  class="form-control @error('services_type_id.'.$i) is-invalid @enderror" id="services_type_id{{$i}}" name="services_type_id[]" required="required" data-parsley-required-message="{{ setMessage('kategori.required') }}">
                                                                <option value="">Sila Pilih</option>
                                                                @foreach($servicesTypeAll as $servicesType)
                                                                    @if(old('services_type_id.'.$i) == $servicesType -> id)
                                                                        <option value="{{ $servicesType -> id }}" selected >{{ $servicesType -> services_type }}</option>
                                                                    @else
                                                                        <option value="{{ $servicesType -> id }}" >{{ $servicesType -> services_type }}</option>
                                                                    @endif
                                                                @endforeach
                                                            </select>
                                                        </td>
                                                        <td>
                                                            <input type='number' class='form-control @error('market_rental_amount.'.$i) is-invalid @enderror' name='market_rental_amount[]' id='market_rental_amount{{$i}}' value="{{ old('market_rental_amount.'.$i) }}" oninput="checkDecimal(this)" placeholder="0.00"
                                                            required="required" data-parsley-required-message="{{ setMessage('market_rental_amount.required') }}">
                                                        </td>
                                                        <td>
                                                            <input type='number' class='form-control @error('rental_fee.'.$i) is-invalid @enderror' name='rental_fee[]' id='rental_fee{{$i}}' value="{{ old('rental_fee.'.$i) }}" oninput="checkDecimal(this)" placeholder="0.00"
                                                            required="required" data-parsley-required-message="{{ setMessage('rental_fee.required') }}">
                                                        </td>
                                                        <td class='text-center'>
                                                            <input type="hidden" id="id_quarters_class_grade{{$i}}" name="id_quarters_class_grade[]" value="{{ old('id_quarters_class_grade.'.$i) }}">
                                                            <input type="hidden" id="flag_proses{{$i}}" name="flag_proses[]" value="{{ old('flag_proses.'.$i) }}">
                                                            <a id="btnRemove{{$i}}" class='btnRemove btn btn-warning btn-sm'><i class='mdi mdi-minus mdi-18px'></i></a>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @else
                                                @if($senaraiSewaAll->count()==0)
                                                    <tr id="tr-gred-list0" >
                                                        <td><select class='form-control' name='p_grade_id[]' id='p_grade_id0' required="required" data-parsley-required-message="{{ setMessage('p_grade_id.required') }}"><option value=''>Sila Pilih</option></select></td>
                                                        <td><select class='form-control' name='services_type_id[]' id='services_type_id0' required="required" data-parsley-required-message="{{ setMessage('services_type_id.required') }}"><option value=''>Sila Pilih</option></select></td>
                                                        <td><input type='text' class='form-control' name='market_rental_amount[]' id='market_rental_amount0' oninput="checkDecimal(this)" placeholder="0.00" required="required" data-parsley-required-message="{{ setMessage('market_rental_amount.required') }}"></td>
                                                        <td><input type='text' class='form-control' name='rental_fee[]' id='rental_fee0' oninput="checkDecimal(this)" placeholder="0.00" required="required" data-parsley-required-message="{{ setMessage('rental_fee.required') }}"></td>
                                                        <td class='text-center'>
                                                            <input type="hidden" id="id_quarters_class_grade0" name="id_quarters_class_grade[]" value="0">
                                                            <a id="btnRemove0" class='btnRemove btn btn-warning btn-sm' data-row-index="0"><i class='mdi mdi-minus mdi-18px'></i></a>
                                                        </td>
                                                    </tr>
                                                @else
                                                    @foreach($senaraiSewaAll as $i => $senaraiSewa)
                                                        <tr id="tr-gred-list{{$i}}">

                                                            <td>
                                                                <select  class="form-control @error('p_grade_id.'.$i) is-invalid @enderror" id="p_grade_id{{$i}}" name="p_grade_id[]" required="required" data-parsley-required-message="{{ setMessage('p_grade_id.required') }}">
                                                                    <option value="">Sila Pilih</option>
                                                                    @foreach($gredJawatanAll as $gredJawatan)
                                                                        @if($senaraiSewa -> p_grade_id == $gredJawatan -> id)
                                                                            <option value="{{ $gredJawatan -> id }}" selected>{{ $gredJawatan -> grade_no }}</option>
                                                                        @else
                                                                            <option value="{{ $gredJawatan -> id }}">{{ $gredJawatan -> grade_no }}</option>
                                                                        @endif
                                                                    @endforeach
                                                                </select>
                                                            </td>
                                                            <td>
                                                                <select  class="form-control @error('services_type_id.'.$i) is-invalid @enderror" id="services_type_id{{$i}}" name="services_type_id[]" required="required" data-parsley-required-message="{{ setMessage('services_type_id.required') }}">
                                                                    <option value="">Sila Pilih</option>
                                                                    @foreach($servicesTypeAll as $servicesType)
                                                                        @if($senaraiSewa -> services_type_id == $servicesType -> id)
                                                                            <option value="{{ $servicesType -> id }}" selected >{{ $servicesType -> services_type }}</option>
                                                                        @else
                                                                            <option value="{{ $servicesType -> id }}" >{{ $servicesType -> services_type }}</option>
                                                                        @endif
                                                                    @endforeach
                                                                </select>
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control @error('market_rental_amount.'.$i) is-invalid @enderror" name="market_rental_amount[]" id="market_rental_amount{{$i}}" value="{{ $senaraiSewa -> market_rental_amount }}" oninput="checkDecimal(this)" placeholder="0.00" required data-parsley-required-message="{{ setMessage('market_rental_amount.required') }}">
                                                            </td>
                                                            <td >
                                                                <input type="text" class="form-control @error('rental_fee.'.$i) is-invalid @enderror" id="rental_fee{{$i}}" name="rental_fee[]" value="{{ $senaraiSewa -> rental_fee }}" oninput="checkDecimal(this)" required="required" data-parsley-required-message="{{ setMessage('rental_fee.required') }}">
                                                            </td>

                                                            <td class="text-center">
                                                                <input type="hidden" id="id_quarters_class_grade{{$i}}" name="id_quarters_class_grade[]" value="{{ $senaraiSewa -> id }}">
                                                                <input type="hidden" id="flag_proses{{$i}}" name="flag_proses[]" value="0">
                                                                <a id="btnRemove{{$i}}" class='btnRemove btn btn-warning btn-sm' data-row-index="{{$i}}"><i class='mdi mdi-minus mdi-18px'></i></a>

                                                            </td>

                                                        </tr>
                                                        @php
                                                            $i++;
                                                        @endphp

                                                    @endforeach
                                                @endif
                                            @endif
                                        </tbody>
                                    </table>

                                </div>
                            </div>
                        </div>
                    </section>

                    <div class="mt-3 row">
                        <div class="col-sm-12 mt-3">
                            <button type="submit" class="btn btn-primary float-end swal-kemaskini">{{ __('button.kemaskini') }}</button>
                            {{-- <a class="btn btn-danger float-end me-2 swal-delete">{{ __('button.hapus') }}</a> --}}
                            <a href="{{ route('quartersClass.index') }}" class="btn btn-secondary float-end me-2">{{ __('button.kembali') }}</a>
                        </div>
                    </div>
                </form>

                <form method="POST" action="{{ route('quartersClass.deleteByRow') }}" id="delete-form-by-row">
                    {{ csrf_field() }}
                    {{ method_field('DELETE') }}
                    <input type="hidden" name="id" value="{{ $quartersClass->id }}">
                    <input type="hidden" id="id_by_row" name="id_by_row" >
                </form>

                <form method="POST" action="{{ route('quartersClass.delete') }}" id="delete-form">
                    {{ csrf_field() }}
                    {{ method_field('DELETE') }}
                    <input type="hidden" id="id-delete" name="id" value="{{ $quartersClass->id }}">
                </form>

            </div>
        </div>
    </div> <!-- end col -->
</div>
<!-- end row -->

@endsection
@section('script')
<script src="{{ URL::asset('assets/js/pages/QuartersClass/quartersClass.js')}}"></script>
@endsection

