@extends('layouts.master')

@section('content')

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                
            {{-- PAGE TITLE --}}
            <div class="border-bottom border-primary mb-4"><h4 class="card-title">{{ getPageTitle(1) }}</h4></div>

            <form  class="custom-validation"  id="form" method="post" autocomplete="off" action="{{ route('specialPermission.update') }}" enctype="multipart/form-data">
                {{ csrf_field() }}

                <input type="hidden" id="id" name="id" value="{{ $specialPermission->id }}">
                <input type="hidden" id="user_id" name="user_id" value="{{ $specialPermission->user_id }}">
                    <div class="mb-3 row">
                        <label class="col-md-2 col-form-label">Nama</label>
                        <div class="col-md-10">
                            <p class="col-form-label">{{ $specialPermission->name }}</p>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label class="col-md-2 col-form-label">No Kad Pengenalan</label>
                        <div class="col-md-10">
                            <p class="col-form-label">{{ $specialPermission->new_ic }}</p>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label class="col-md-2 col-form-label">Email</label>
                        <div class="col-md-10">
                            <p class="col-form-label">{{ $specialPermission->email }}</p>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label class="col-md-2 col-form-label">Jawatan</label>
                        <div class="col-md-10">
                            <p class="col-form-label">{{ ($specialPermission->ui_position_name) ? $specialPermission->ui_position_name : $specialPermission->position_name }}</p>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label class="col-md-2 col-form-label">Kod Jawatan</label>
                        <div class="col-md-10">
                            <p class="col-form-label">{{ ($specialPermission->ui_grade_type) ? $specialPermission->ui_grade_type : $specialPermission->grade_type }}</p>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label class="col-md-2 col-form-label">Gred Jawatan</label>
                        <div class="col-md-10">
                            <p class="col-form-label">{{ ($specialPermission->ui_grade_no) ? $specialPermission->ui_grade_no : $specialPermission->grade_no }}</p>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label class="col-md-2 col-form-label">Jabatan/Agensi Bertugas</label>
                        <div class="col-md-10">
                            <p class="col-form-label">{{ $specialPermission->organization_name }}</p>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label class="col-md-2 col-form-label">Jenis Perkhidmatan</label>
                        <div class="col-md-10">
                            <p class="col-form-label">{{ ($specialPermission->ui_services_type) ? $specialPermission->ui_services_type : $specialPermission->services_type }}</p>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label class="col-md-2 col-form-label">Daerah</label>
                        <div class="col-md-10">
                            <p class="col-form-label">{{ $specialPermission->district_name }}</p>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="remarks" class="col-md-2 col-form-label">Ulasan</label>
                        <div class="col-md-10">
                            <textarea class="form-control @error('remarks') is-invalid @enderror" rows="3" id="remarks" name="remarks" required data-parsley-required-message="{{ setMessage('remarks.required') }}">{{ $specialPermission->remarks }}</textarea>
                            @error('remarks')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3 row">
                    <label for="suppporting_document" class="col-md-2 col-form-label">Dokumen Sokongan</label>
                    <div class="col-md-10">
                        <table class="table  table-sm table-bordered border-secondary" id="table-supporting-document" data-list="{{$special_permission_attachment->count()}}">
                            <thead class="bg-primary bg-gradient text-white">
                                <tr>
                                    <th class="text-center">Dokumen Sokongan</th>
                                    <th class="text-center">Nama Dokumen</th>
                                    <th class="text-center">Papar</th>
                                    <th class="text-center"><a onclick="duplicateRow();" class="btn btn-success btn-sm" ><i class="mdi mdi-plus mdi-18px"></i></a></th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(old('supporting_document') != null)
                                    @foreach(old('supporting_document') as $i => $document)
                                        <tr id="tr-supporting-document{{$i}}">
                                            <td class="text-center">
                                                <input type="file" class="form-control @error('supporting_document.'.$i) is-invalid @enderror"  id="supporting_document{{$i}}" name="supporting_document[{{$i}}][]"  value="{{old('supporting_document','')}}"   required data-parsley-required-message="{{ setMessage('supporting_document.required') }}"  multiple>
                                            </td>
                                            <td class="text-center">
                                                <input type="text" id="supporting_document_id{{$i}}" name="supporting_document_id[]" value="{{ old('supporting_document.'.$i) }}">
                                                <a id="btnRemove{{$i}}" class="btnRemove btn btn-warning btn-sm"><i class="mdi mdi-minus mdi-18px"></i></a>
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    @if($special_permission_attachment->count()==0)
                                        <tr id="tr-supporting-document0" >
                                            <td><input class="form-control" type="file" name="supporting_document[]" id="supporting_document0" required data-parsley-required-message="{{ setMessage('supporting_document.required') }}" multiple></td>
                                            <td class="text-center">
                                                <input type="hidden" id="supporting_document_id0" name="supporting_document_id[]" value="0">
                                                <a id="btnRemove0" class="btnRemove btn btn-warning btn-sm" data-row-index="0"><i class="mdi mdi-minus mdi-18px"></i></a>
                                            </td>
                                        </tr>
                                    @else
                                        @foreach($special_permission_attachment as $i => $special_permission_attachment)
                                                <tr id="tr-supporting-document{{$i}}">
                                                    <td>
                                                        <input type="file" class="form-control @error('supporting_document.'.$i) is-invalid @enderror" id="supporting_document{{$i}}" name="supporting_document[{{$i}}][]"  value="{{$special_permission_attachment->path_document}}" multiple>
                                                    </td>
                                                    @php
                                                        $path = $special_permission_attachment->path_document;
                                                        $document = pathinfo($path);
                                                        $document_name = $document['basename']."\n";
                                                    @endphp
                                                    <td class="align-left">
                                                        <label for="doc_name">{{ $document_name }}</label>
                                                    </td>
                                                    <td class="text-center">
                                                        <div>
                                                        <a download="{{ $special_permission_attachment->path_document }}" target="_blank" href="{{ getCdn() . $special_permission_attachment->path_document }}"
                                                            class="btn btn-outline-primary tooltip-icon" title="{{ $special_permission_attachment->path_document }}"><i class="{{ __('icon.view_file') }}"></i></a></div>
                                                    </td>
                                                    <td class="text-center">
                                                        <input type="hidden" id="supporting_document_id{{$i}}" name="supporting_document_id[{{$i}}]" value="{{$special_permission_attachment->id }}">
                                                        <a id="btnRemove{{$i}}" class="btnRemove btn btn-warning btn-sm" data-row-index="{{$i}}"><i class="mdi mdi-minus mdi-18px"></i></a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                    @endif
                                @endif
                            </tbody>
                         </table>
                    </div>
                </div>

                    <div class="mb-3 row">
                        <div class="col-sm-12 mt-3">
                            <button type="submit" class="btn btn-primary float-end swal-kemaskini">{{ __('button.kemaskini') }}</button>
                            <a href="{{ route('specialPermission.index') }}" class="btn btn-secondary float-end me-2">{{ __('button.kembali') }}</a>
                        </div>
                    </div>
            </form>

            <form method="POST" action="{{ route('specialPermission.deleteByRow') }}" id="delete-form-by-row">
                {{ csrf_field() }}
                {{ method_field('DELETE') }}
                <input type="hidden" name="id" value="{{ $specialPermission->id }}">
                <input type="hidden" id="row_supporting_document_id" name="row_supporting_document_id">
            </form>

            </div>
        </div>
    </div> <!-- end col -->
</div>
<!-- end row -->

@endsection

@section('script')
<script src="{{ URL::asset('assets/js/pages/SpecialPermission/specialPermission.js')}}"></script>
@endsection
