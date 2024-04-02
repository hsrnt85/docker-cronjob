@extends('layouts.master')

@section('content')

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">

                {{-- PAGE TITLE --}}
                <div class="border-bottom border-primary mb-4"><h4 class="card-title">{{ getPageTitle(1) }}</h4></div>

                <form method="post" action="{{ route('quartersCategory.update') }}" >
                    {{ csrf_field() }}
                    <input type="hidden" name="id" value="{{ $quartersCategory->id }}">

                    <div class="mb-3 row">
                        <label class="col-md-2 col-form-label">Daerah</label>
                        <div class="col-md-10">
                            <p class="col-form-label">{{ $quartersCategory->district->district_name }}</p>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label class="col-md-2 col-form-label">Kategori Kuarters (Lokasi)</label>
                        <div class="col-md-10">
                            <p class="col-form-label">{{ $quartersCategory->name }}</p>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="name" class="col-md-2 col-form-label">Jenis Kuarters</label>
                        <div class="col-md-10">
                            <p class="col-form-label">{{ $quartersCategory->landed_type->type }}</p>
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
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @if($quartersCategoryClassAll->count() != 0)
                                                            @foreach($quartersCategoryClassAll as $quartersCategoryClass)
                                                                <tr>
                                                                    <th scope="row" class="text-center">{{$loop->iteration}}</th>
                                                                    <td>{{$quartersCategoryClass->quartersClass->class_name}}</td>
                                                                    <td class="text-center">
                                                                        <a onclick="showClassGrade({{ $quartersCategoryClass->q_class_id }});" data-route="{{ route('quartersCategory.classGradeList') }}"  id="btn-show-complaint-others" data-bs-toggle="modal" class="btn btn-outline-primary tooltip-icon">
                                                                            <span class="tooltip-text">{{ __('button.papar') }}</span> <i class="{{ __('icon.view_folder') }}"></i>
                                                                        </a>
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        @else
                                                            <tr class="text-center"><td colspan="2">Tiada rekod</td></tr>
                                                        @endif
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
                                                        @if($quartersInventoryAll->count() != 0)
                                                            @foreach($quartersInventoryAll as $quartersInventory)
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
                            <!-- <a href="{{ route('quartersCategory.edit', ['id' => $quartersCategory->id]) }}" class="btn btn-primary float-end">{{ __('button.kemaskini') }}</a> -->
                            <a class="btn btn-danger float-end me-2 swal-delete">{{ __('button.hapus') }}</a>
                            <a href="{{ route('quartersCategory.index') }}" class="btn btn-secondary float-end me-2">{{ __('button.kembali') }}</a>
                        </div>
                    </div>
                </form>

                <form method="POST" action="{{ route('quartersCategory.delete') }}" id="delete-form">
                    {{ csrf_field() }}
                    {{ method_field('DELETE') }}
                    <input class="form-control" type="hidden" id="id-delete" name="id" value="{{ $quartersCategory->id }}">
                </form>

                
            </div>
        </div>
    </div> <!-- end col -->
</div>
<!-- end row -->

@endsection
@section('script')
    <script src="{{ URL::asset('assets/js/pages/QuartersCategory/modalClassGradeinfo.js')}}"></script>
@endsection
