@extends('layouts.master')

@section('content')


    <div class="col-12">
        <div class="card">
            <div class="card-body">

                {{-- PAGE TITLE --}}
                <div class="border-bottom border-primary mb-4"><h4 class="card-title">{{ getPageTitle(1) }}</h4></div>

                <form  class="custom-validation" id="form" method="post" action="{{ route('quartersCategory.update') }}" >
                    {{ csrf_field() }}

                    <input class="form-control" type="hidden" id="id" name="id" value="{{ $quartersCategory->id }}">

                    <div class="mb-3 row">
                        <label class="col-md-2 col-form-label">Daerah</label>
                        <div class="col-md-10">
                            <select class="form-control @error('district') is-invalid @enderror" id="district" name="district" required data-parsley-required-message="{{ setMessage('district.required') }}" disabled>
                                <option value="">-- Pilih Daerah --</option>
                                @foreach($districtAll as $district)
                                    <option value="{{ $district->id }}" @if($quartersCategory->district_id == $district->id) selected @endif>
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
                            <input class="form-control" type="text" id="name" name="name" value="{{ $quartersCategory->name }}" value="{{ old('name') }}"  required data-parsley-required-message="{{ setMessage('name.required') }}">
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label class="col-md-2 col-form-label">Jenis Kuarters  </label>
                        <div class="col-md-10">
                            @foreach($landedTypeAll as $landedType)
                                <div class="form-check me-2">
                                    <div>
                                        <input class="form-check-input me-2" type="radio" name="landedType" id="{{ $landedType->id }}" value="{{ $landedType->id }}" {{ $quartersCategory->landed_type_id == $landedType->id ? "checked" : "" }}
                                            required data-parsley-required-message="{{ setMessage('landedType.required') }}"
                                            data-parsley-errors-container="#parsley-errors-landed-type">
                                        <label class="form-check-label" for="{{ $landedType->id }}">
                                            {{ $landedType->type }}
                                        </label>
                                    </div>
                                </div>
                            @endforeach
                            <div id="parsley-errors-landed-type"></div>
                        </div>
                    </div>

                    <!-- Nav tabs -->
                    <ul class="nav nav-tabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" data-bs-toggle="tab" href="#senarai_kelas" role="tab">
                                <span class="d-block d-sm-none"><i class="fas fa-address-card "></i></span>
                                <span class="d-none d-sm-block">Senarai Kelas</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#maklumat_inventori" role="tab">
                                <span class="d-block d-sm-none"><i class="fas fa-building"></i></span>
                                <span class="d-none d-sm-block">Maklumat Inventori</span>
                            </a>
                        </li>
                    </ul><!-- end Nav tabs -->

                    <!-- Tab panes -->
                    <div class="tab-content p-3 text-muted" >
                        <!-- Tab Senarai Kelas --> 
                        <div class="tab-pane active" id="senarai_kelas" role="tabpanel" data-validate="parsley">   
                            <div class="card-body">              
                                <div class="card-body">
                                    <div class="form-row">
                                        <div class="col-md-12">  
                                            <div class=" col-sm">
                                            <table class="table table-sm table-bordered" id="table-quarters-class">
                                                    <thead class="text-white bg-primary">
                                                        <tr>
                                                            <th class="text-center" width="10%">Bil</th>
                                                            <th class="text-center">Kelas Kuarters</th>
                                                            <th class="text-center">Maklumat Sewa</th>
                                                            <th class="text-center">Pilih</th>
                                                        </tr>
                                                    </thead>

                                                    <tbody>
                                                        @foreach($quartersClassAll as $quartersClass)
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
                                                                        <input class="form-check-input me-2" type="checkbox" id="formCheck_{{ $quartersClassId }}" name="categoryClass[]"
                                                                            value="{{$quartersClassId}}" @if(old('quartersClass.' . $quartersClassId, $quartersClassByCatId->find($quartersClassId) != null)) checked @endif
                                                                            required data-parsley-required-message="{{ setMessage('categoryClass.required') }}"
                                                                            data-parsley-mincheck="1" data-parsley-mincheck-message="{{ setMessage('categoryClass.required') }}"
                                                                            data-parsley-errors-container="#parsley-errors-category-class">
                                                                        <label class="form-check-label" for="formCheck_{{ $quartersClassId }}"></label>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>

                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>                             
                            </div>
                        </div> <!-- end Tab Senarai Kelas -->

                        <!-- Tab Maklumat Inventori --> 
                        <div class="tab-pane" id="maklumat_inventori" role="tabpanel" data-validate="parsley">   
                            <div class="card-body">           
                                <div class="card-body">
                                    <div class="form-row">
                                        <div class="col-md-12">  
                                            <div class="table-responsive col-sm">
                                                <table class="table table-sm table-bordered" id="table-quarters-class">
                                                    <thead class="text-white bg-primary">
                                                        <tr>
                                                            <th class="text-center" width="10%">Bil</th>
                                                            <th class="text-center">Inventori</th>
                                                            <th class="text-center">Harga (RM)</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @if($inventoryAll->count() != 0)
                                                            @foreach($inventoryAll as $quartersInventory)
                                                                <tr>
                                                                    <th scope="row" class="text-center">{{$loop->iteration}}</th>
                                                                    <td class="text-center">{{$quartersInventory->name}}</td>
                                                                    <td class="text-center">{{$quartersInventory->price}}</td>
                                                                </tr>
                                                            @endforeach
                                                        @else
                                                            <tr class="text-center"><td colspan="4">Tiada rekod</td></tr>
                                                        @endif
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div> <!-- end Tab Maklumat Inventori -->
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
                                                <tbody class="field_wrapper_listing_maklumat_sewa" >
                                                    

                                                </tbody>
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
                            <button type="submit" class="btn btn-primary float-end swal-kemaskini">{{ __('button.kemaskini') }}</button>
                            <a href="{{ route('quartersCategory.index') }}" class="btn btn-secondary float-end me-2">{{ __('button.kembali') }}</a>
                        </div>
                    </div>
                </form>

                <form method="POST" action="{{ route('quartersCategory.deleteByRow') }}" id="delete-form-by-row">
                    {{ csrf_field() }}
                    {{ method_field('DELETE') }}
                    <input type="hidden" name="id" value="{{ $quartersCategory->id }}">
                    <input type="hidden" id="id_by_row" name="id_by_row" >
                </form>

                <form method="POST" action="{{ route('quartersCategory.delete') }}" id="delete-form">
                    {{ csrf_field() }}
                    {{ method_field('DELETE') }}
                    <input class="form-control" type="hidden" id="id-delete" name="id" value="{{ $quartersCategory->id }}">
                </form>

            </div>
        </div>
    </div> <!-- end col -->

<!-- end row -->

@endsection
@section('script')
    <script src="{{ URL::asset('assets/js/pages/QuartersCategory/quartersCategory.js')}}"></script>
    <script src="{{ URL::asset('assets/js/pages/QuartersCategory/modalClassGradeinfo.js')}}"></script>
@endsection