@extends('layouts.master')

@section('content')

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">

                    {{-- PAGE TITLE --}}
                    <div class="border-bottom border-primary mb-4"><h4 class="card-title">{{ getPageTitle(1) }}</h4></div>

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
                        <label class="col-md-2 col-form-label">Ulasan</label>
                        <div class="col-md-10">
                            <p class="col-form-label">{{ $specialPermission->remarks }}</p>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label class="col-md-2 col-form-label">Dokumen Sokongan</label>
                    <div class="table-responsive col-md-8">
                        <table class="table table-sm table-striped table-bordered" id="table-supporting-document">
                            <thead class="text-white bg-primary">
                                <tr>
                                    <th class="text-center">Bil</th>
                                    <th class="text-center">Dokumen Sokongan</th>
                                    <th class="text-center">Papar</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if($special_permission_attachment)
                                    @foreach($special_permission_attachment as $attachment)
                                        <tr>
                                            <td class="text-center">{{ $loop->iteration }}</td>
                                            <td>
                                                @php
                                                $path = $attachment->path_document;
                                                $document = pathinfo($path);
                                                $document_name = $document['basename']."\n";
                                                @endphp
                                                {{ $document_name }}
                                            </td>
                                            <td class="text-center">
                                                <a download="{{ $attachment->path_document }}" target="_blank" href="{{ getCdn() . $attachment->path_document }}"
                                                    class="btn btn-outline-primary tooltip-icon" title="{{ $attachment->path_document }}"><i class="{{ __('icon.view_file') }}"></i></a>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                    </div>

                    <div class="mb-3 row">
                        <div class="col-sm-12 mt-3">
                            <a href="{{ route('specialPermission.index') }}" class="btn btn-secondary float-end me-2">{{ __('button.kembali') }}</a>
                        </div>
                    </div>

            </div>
        </div>
    </div> <!-- end col -->
</div>
<!-- end row -->

@endsection
