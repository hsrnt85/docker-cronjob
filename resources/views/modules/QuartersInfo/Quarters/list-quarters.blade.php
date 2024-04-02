@extends('layouts.master')
@section('file-css')
<link rel="stylesheet" href="{{ asset('assets/css/pages/Quarters/quarters.css') }}">
@endsection

@section('content')

<div class="row">
    {{-- <div class="col-12">
        <div class="card">

             <div class="card-body p-3">

                <div class="border-bottom border-primary mb-4"><h4 class="card-title">Carian Rekod</h4></div>

                <div class="row">
                    <div class="col-sm-3" >
                        <label class="col-form-label ">Status Kuarters</label>
                        <div >
                            <select class="form-select" id="activeStatusFilter">
                                <option value="">-- Semua --</option>
                                @foreach($activeStatusAll as $activeStatus)
                                    <option value="{{ $activeStatus->id }}">{{ $activeStatus->status }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div> --}}

    <div class="col-12">
        <div class="card">

            <div class="card-body">

                {{-- PAGE TITLE --}}

                <div class="border-bottom border-primary mb-4"><h4 class="card-title">{{ getPageTitle(2) }}</h4></div>
                <div class="mb-2"><h4 class="card-title">Kategori Kuarters (Lokasi) : {{$category_name->name}}</h4></div>

                @if(checkPolicy("A"))
                    <div class="row mb-2">
                        <div class="col-sm-12">
                            <a type="button" href="{{ route('quarters.create', ['quarters_cat_id' => $quarters_cat_id]) }}" class="btn btn-success float-end waves-effect waves-light">{{ __('button.rekod_baru') }} - Kuarters</a>
                            <a type="button" href="{{ route('quarters.addUnitNo', ['quarters_cat_id' => $quarters_cat_id]) }}" class="btn btn-success me-2 float-end waves-effect waves-light">{{ __('button.rekod_baru') }} - No Unit</a>
                        </div>
                    </div>
                @endif
                <form id="form_datatable_quarters"class="custom-validation" method="post" action="{{ route('quarters.saveQuartersStatus', ['quarters_cat_id' => $quarters_cat_id])}}" enctype="multipart/form-data">
                    {{ csrf_field() }}

                    {{-- DATATABLE --}}
                    <div id="datatable_wrapper">
                        <div class="row">
                            <div class="col-sm-12">
                                <table id="datatable_quarters" class="table table-striped table-bordered dt-responsive wrap w-100 dataTables_filterS" role="grid" >
                                    <p class="text-danger">** Nota : Pegawai tidak boleh mengemaskini kotak semak Status Aktif sekiranya kuarters tersebut mempunyai penghuni.</p>
                                    <thead class="bg-primary bg-gradient text-white">
                                        <tr role="row">
                                            <th class="text-center" width="4%">Bil</th>
                                            <th class="text-center" width="10%" >No Unit</th>
                                            <th class="text-center" >Alamat</th>
                                            <th class="text-center" >Keadaan Kuarters</th>
                                            <th class="text-center" width="10%">Status Aktif</th>
                                            <th class="text-center" >Catatan</th>
                                            <th class="text-center" width="8%">Tindakan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($quartersAll as $i => $data)
                                            @php
                                                $inhabitedQuarters = App\Models\Tenant::getInhabitedQuarters($data->quarters_cat_id, $data->id);  // Kuarters yang masih berpenghuni
                                                $berpenghuni = ($inhabitedQuarters?->quarters_id == $data->id) ? true : false ; // Disable Kuarters yang berpenghuni
                                            @endphp
                                            <tr class="odd">
                                                <td class="text-center" tabindex="0">{{ $loop->iteration }}</td>
                                                <td class="text-center">{{$data->unit_no}} </td>
                                                <td >{{$data->address_1}} {{$data->address_2}}</td>
                                                <td class="text-center">{{$data->quarters_condition?->name}}</td>
                                                <!-- checkbox for available / unavailable unit -->
                                                <td class="text-center">
                                                    <div class="form-check d-flex justify-content-center">
                                                        <input class="form-control" type="hidden" id="id_quarters_{{ $i }}" name="id_quarters[]" value="{{ $data->id }}">
                                                        <input class="data_status_temp" type="hidden" id="data_status_temp_{{ $i }}" value="{{ $data->data_status }}">
                                                        <input class="form-control" type="hidden" id="inhabitedQuarters_{{$i}}" value="{{ $inhabitedQuarters->quarters_id ?? '' }}">
                                                        <input class="form-check-input me-2 data_status" type="checkbox" id="data_status_{{ $i }}" name="data_status[{{ $i }}]" value="{{ $data->data_status }}"
                                                        onclick="enableCatatan({{ $data->id }}, {{ $i }});"  @if($berpenghuni == true) disabled @endif
                                                        @if( $data->data_status == 1 ) checked @endif > <label>Aktif</label>
                                                    </div>
                                                </td>
                                                <!-- input field for catatan -->
                                                <td>
                                                    <input class="form-control inactive_remarks" type="text" id="inactive_remarks_{{ $i }}" name="inactive_remarks[{{ $i }}]" value="{{ $data->inactive_remarks }}"
                                                    @if( $data->data_status == 1 ) disabled @endif >
                                                </td>

                                                <td class="text-center">
                                                    <div class="btn-group" role="group">
                                                        @if(checkPolicy("V"))
                                                            <a href="{{ route('quarters.view', ['id' => $data->id]) }}" class="btn btn-outline-primary px-2 py-1 tooltip-icon">
                                                                <span class="tooltip-text">{{ __('button.papar') }}</span> <i class="{{ __('icon.view_folder') }}"></i>
                                                            </a>
                                                        @endif
                                                        @if(checkPolicy("U"))
                                                            <a href="{{ route('quarters.edit', ['id' => $data->id, 'quarters_cat_id' => $data->quarters_cat_id]) }}" class="btn btn-outline-primary px-2 py-1 tooltip-icon">
                                                                <span class="tooltip-text">{{ __('button.kemaskini') }}</span> <i class="{{ __('icon.edit') }}"></i>
                                                            </a>
                                                        @endif
                                                    </div>
                                                    <!-- <form method="POST" action="{{ route('quarters.delete') }}" class="delete-form-list">
                                                        {{ csrf_field() }}
                                                        {{ method_field('DELETE') }}
                                                        <input class="form-control" type="hidden" name="id" value="{{ $data->id }}">
                                                    </form> -->
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <div class="mb-3 row">
                        <div class="col-sm-12">
                            @if(checkPolicy("U") && $quartersAll->count()>0)
                                <button type="submit" class="btn btn-primary float-end swal-kemaskini-quarters-list">{{ __('button.kemaskini') }}</button>
                            @endif
                            <a href="{{ route('listQuartersCategory.index') }}" class="btn btn-secondary float-end me-2">{{ __('button.kembali') }}</a>
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
@endsection
