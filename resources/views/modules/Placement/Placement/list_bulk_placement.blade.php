@extends('layouts.master')

@section('content')

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">

                {{-- PAGE TITLE --}}
                <div class="border-bottom border-primary mb-4"><h4 class="card-title">{{ getPageTitle(2) }}</h4></div>
                <h4 class="card-title mb-3 text-primary">{{ $category->name }}</h4>

                <form id="form" method="post" action="{{ route('placement.updateBulk') }}" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <input type="hidden" name="category_id" value="{{ $category->id }}">
                    <div id="datatable_wrapper">
                        <div class="row">
                            <div class="mt-4 col-sm-12">
                                <table id="" class="table table-sm table-striped table-bordered dt-responsive wrap w-100 dataTable" role="grid" >
                                    <thead class="bg-primary bg-gradient text-white">
                                        <tr role="row">
                                            <th class="text-center" width="4%">Bil</th>
                                            <th class="text-center" >Nama Pemohon</th>
                                            <th class="text-center" >Kad Pengenalan</th>
                                            <th class="text-center" >Alamat Kuarters</th>
                                            <th class="text-center" >No. Unit</th>
                                            <th class="text-center" >No. Rujukan Surat Tawaran</th>
                                            <th class="text-center" >Muat Naik Surat Tawaran</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if ($appNeededPlacementAll->count() > 0)
                                        @foreach ($appNeededPlacementAll as $i => $appNeededPlacement)
                                            <tr class="odd">
                                                <input type="hidden" name="application_id[{{$appNeededPlacement->id}}]" value="{{$appNeededPlacement->id}}">
                                                <td class="text-center" tabindex="0">{{ $loop->iteration }}</td>
                                                <td class="text-center">{{ $appNeededPlacement->user->name }} </td>
                                                <td class="text-center">{{ $appNeededPlacement->user->new_ic }} </td>
                                                <td class="text-center">
                                                    <select class="form-select" id="address" name="address[{{$appNeededPlacement->id}}]" data-category-id="{{ $category->id }}" data-route="{{ route('placement.ajaxGetAvailableUnitByAddr') }}">
                                                        <option class="text-center" value="">-- Pilih Alamat --</option>
                                                        @foreach($addressAll as $address)
                                                            <option class="text-center" value="{{ $address->address_1 }}" >{{ $address->address_1 }}</option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                                <td class="text-center">
                                                    <select class="form-select select_unit" id="unit_no" name="unit_no[{{ $appNeededPlacement->id }}]" data-index="{{$i}}" disabled>
                                                        <option class="text-center" value="">-- Pilih Unit --</option>
                                                    </select>
                                                    <div class="spinner-wrapper"></div>
                                                </td>
                                                <td>
                                                    <input class="form-control  @error('letter_ref_no') is-invalid @enderror" type="text" id="letter_ref_no" name="letter_ref_no[{{$appNeededPlacement->id}}]"  value="{{old('letter_ref_no','')}}" >
                                                </td>
                                                <td>
                                                    <input class="form-control  @error('offer_letter') is-invalid @enderror" type="file" id="offer_letter" name="offer_letter[{{$appNeededPlacement->id}}]"  value="{{old('offer_letter','')}}"  multiple
                                                    data-parsley-fileextension='PDF'
                                                    data-parsley-fileextension-message="Sila Gunakan Format PDF sahaja"
                                                    data-parsley-max-file-size="2000"
                                                    data-parsley-max-file-size-message="Saiz Fail Mestilah Bawah 2MB">
                                                </td>
                                            </tr>
                                        @endforeach
                                        @else
                                            <tr>
                                                <td class="text-center" colspan="6">Tiada Rekod</td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <div class="col-sm-12">
                            @if ($appNeededPlacementAll->count() > 0)
                                <button type="submit" class="btn btn-primary float-end swal-kemaskini">{{ __('button.kemaskini') }}</button>
                            @endif
                            <a href="{{ route('placement.listPlacement', $category) }}" class="btn btn-secondary float-end me-2">{{ __('button.kembali') }}</a>
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
