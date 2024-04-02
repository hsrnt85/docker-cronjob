@extends('layouts.master')

@section('content')

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">

                {{-- PAGE TITLE --}}
                <div class="border-bottom border-primary mb-4"><h4 class="card-title">{{ getPageTitle(2) }}</h4></div>

                <p class="card-title-desc">Senarai-senarai kuarters yang belum mempunyai no unit</p>

                <form class="custom-validation" id="form" method="post" action="{{ route('quarters.storeUnitNo', ['quarters_cat_id' => $quarters_cat_id]) }}" >
                    {{ csrf_field() }}
                <div id="datatable_wrapper">
                    <div class="row">
                        <div class="col-sm-12">
                            <table id="" class="table table-bordered table-sm dt-responsive nowrap w-100 dataTable" role="grid" >
                                <thead>
                                    <tr role="row">
                                        <th class="text-center" >Bil</th>
                                        <th class="text-center" >No Unit</th>
                                        <th class="text-center" >Cukai Tanah (RM)</th>
                                        <th class="text-center" >Cukai Harta (RM)</th>
                                        <th class="text-center" >Kategori Kuarters (Lokasi) </th>
                                        <th class="text-center" >Alamat</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($quartersAll as $quarters)
                                        <tr class="odd">
                                            <td class="text-center" tabindex="0">{{ $loop->iteration }}</td>
                                            <td class="text-center">
                                                <input class="form-control form-control-sm text-center" type="text" id="unit_no_{{ $quarters->id }}" name="unit_no[{{$quarters->id}}]" >
                                            </td>
                                            <td class="text-center">
                                                <input class="form-control form-control-sm text-center" type="text" id="land_tax_{{ $quarters->id }}" name="land_tax[{{$quarters->id}}]" value="{{$quarters->land_tax}}" oninput="checkDecimal(this)" placeholder="0.00">
                                            </td>
                                            <td class="text-center">
                                                <input class="form-control form-control-sm text-center" type="text" id="property_tax_{{ $quarters->id }}" name="property_tax[{{$quarters->id}}]" value="{{$quarters->property_tax}}" oninput="checkDecimal(this)" placeholder="0.00">
                                            </td>
                                            <td class="text-center">{{$quarters->category->name}} </td>
                                            <td class="text-center">{{$quarters->address_1}} {{$quarters->address_2}}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col-sm-12">
                        <button type="submit" class="btn btn-primary float-end swal-tambah">{{ __('button.simpan') }}</button>
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
