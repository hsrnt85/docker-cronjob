@extends('layouts.master')

@section('content')

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">

                    {{-- PAGE TITLE --}}
                    <div class="border-bottom border-primary mb-4"><h4 class="card-title">{{ getPageTitle(2) }}</h4></div>

                    {{ csrf_field() }}
                    <input type="hidden" name="id" value="{{ $quarters->id }}">
                    <input type="hidden" name="quarters_cat_id" value="{{ $quarters->category->id }}">

                    <h4 class="card-title text-primary mb-4">Maklumat Kuarters</h4>

                    <div class="mb-3 row">
                        <label class="col-md-3 col-form-label">Kategori Kuarters (Lokasi)</label>
                        <div class="col-md-9">
                            <p class="col-form-label">{{ $quarters->category->name ??''}}</p>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label class="col-md-3 col-form-label">Daerah</label>
                        <div class="col-md-9">
                            <p class="col-form-label">{{ $quarters->category->district->district_name ??''}}</p>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label class="col-md-3 col-form-label">Jenis Kuarters</label>
                        <div class="col-md-9">
                            <p class="col-form-label">{{ $quarters->category->landed_type->type ??''}}</p>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="name" class="col-md-3 col-form-label">No unit</label>
                        <div class="col-md-9">
                            <p class="col-form-label">{{ $quarters->unit_no ??''}}</p>
                        </div>
                    </div>

                    <!-- <div class="mb-3 row">
                        <label class="col-md-3 col-form-label">No. PTD</label>
                        <div class="col-md-9">
                            <p class="col-form-label">{{ $quarters->no_ptd ??''}}</p>
                        </div>
                    </div> -->

                    <div class="mb-3 row">
                        <label for="name" class="col-md-3 col-form-label">Alamat 1</label>
                        <div class="col-md-9">
                            <p class="col-form-label">{{ $quarters->address_1 ??''}}</p>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="name" class="col-md-3 col-form-label">Alamat 2</label>
                        <div class="col-md-9">
                            <p class="col-form-label">{{ $quarters->address_2 ??''}}</p>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="name" class="col-md-3 col-form-label">Alamat 3</label>
                        <div class="col-md-9">
                            <p class="col-form-label">{{ $quarters->address_3 ??''}}</p>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="name" class="col-md-3 col-form-label">Cukai Harta (RM)</label>
                        <div class="col-md-9">
                            <p class="col-form-label">RM {{ $quarters->property_tax ??''}}</p>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="name" class="col-md-3 col-form-label">Cukai Tanah (RM)</label>
                        <div class="col-md-9">
                            <p class="col-form-label">RM {{ $quarters->land_tax ??'' }}</p>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="name" class="col-md-3 col-form-label">Penyelenggaraan IWK</label>
                        <div class="col-sm-3">
                            <p class="col-form-label">{{ ($quarters->m_utility_id == 1) ? "ADA" : "" }}</p>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="name" class="col-md-3 col-form-label">Yuran IWK (RM)</label>
                        <div class="col-md-9">
                            <p class="col-form-label">RM {{ $quarters->iwk_fee ??'' }}</p>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="name" class="col-md-3 col-form-label">Yuran Penyelenggaraan (RM)</label>
                        <div class="col-md-9">
                            <p class="col-form-label">RM {{ $quarters->maintenance_fee ??'' }}</p>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label class="col-md-3 col-form-label">Keadaan Kuarters</label>
                        <div class="col-md-9">
                            <p class="col-form-label">{{ $quarters->quarters_condition->name ??'' }}</p>
                        </div>
                    </div>


                    <div class="mb-3 row">
                        <label class="col-md-3 col-form-label">Gambar Kuarters</label>
                        <div class="col-md-9">
                            <a href="#quartersPicture" data-bs-toggle="modal" class="btn btn-outline-primary"> Lihat Gambar Kuarters <i class="mdi mdi-image-search mdi-16px"></i></a>
                        </div>
                    </div>

                    <div class="modal fade" id="quartersPicture" tabindex="-1" role="dialog" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <h5 class="modal-title modal-header">Gambar Kuarters</h5>
                                <div class="modal-body">
                                <p id = "clickingText">Sila klik gambar di bawah:</p>
                                    <div class="container" >
                                        <img id="expandedImg" class="mb-4" >
                                        <div id="imgtext"></div>
                                    </div>
                                    <div  style="text-align: center;">
                                    @foreach($quartersImageAll as $quartersImage)
                                        <img src="{{$cdn .$quartersImage->path_image}}" width= "50px" height="50px"  onclick="myFunction(this);" style="border: 1px solid black"  >
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
                            <p class="col-form-label">{{ $quarters->room_no ??''}} Bilik</p>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="bathroom_no" class="col-md-3 col-form-label">Jumlah Bilik Air</label>
                        <div class="col-md-9">
                            <p class="col-form-label">{{ $quarters->bathroom_no ??''}} Bilik</p>
                        </div>
                    </div>

                    <hr>

                    <h4 class="card-title text-primary mb-4">Maklumat Inventori</h4>

                    <div class="row">
                        <div class="table-responsive col-sm-10 offset-sm-1">
                            <table class="table table-sm table-bordered" id="table-inventory">
                                <thead class="text-white bg-primary">
                                    <tr>
                                        <th class="text-center">Bil</th>
                                        <th class="text-center">Inventori</th>
                                        <th class="text-center">Kuantiti</th>
                                        <th class="text-center">Jabatan Bertanggungjawab (Inventori)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if($quartersInventoryAll->count() != 0)
                                        @foreach($quartersInventoryAll as $quartersInventory)
                                            <tr>
                                                <th scope="row" class="text-center">{{$loop->iteration}}</th>
                                                <td>{{$quartersInventory->inventory->name}}</td>
                                                <td class="text-center">{{$quartersInventory->quantity}}</td>
                                                <td class="text-center">{{$quartersInventory->maintenance?->name}}</td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr class="text-center"><td colspan="4">Tiada rekod</td></tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <hr>

                    <div class="mb-3 row">
                        <div class="col-sm-12">
                            <!-- <a href="{{ route('quarters.edit', ['id' => $quarters->id, 'quarters_cat_id' => $quarters->quarters_cat_id]) }}" class="btn btn-primary float-end me-2">{{ __('button.kemaskini') }}</a> -->
                            @if(checkPolicy("D"))<a class="btn btn-danger float-end me-2 swal-delete">Hapus</a>@endif
                            <a href="{{ route('quarters.index', ['quarters_cat_id' => $quarters->category->id]) }}" class="btn btn-secondary float-end me-2">{{ __('button.kembali') }}</a>
                        </div>
                    </div>


                <form method="POST" action="{{ route('quarters.delete') }}" id="delete-form">
                    {{ csrf_field() }}
                    {{ method_field('DELETE') }}
                    <input class="form-control" type="hidden" id="id-delete" name="id" value="{{ $quarters->id }}">
                    <input type="hidden" name="quarters_cat_id" value="{{ $quarters->category->id }}">
                </form>

            </div>
        </div>
    </div> <!-- end col -->
</div>
<!-- end row -->

@endsection

@section('script')
<script src="{{ URL::asset('/assets/js/pages/Quarters/quarters.js')}}"></script>
@endsection
