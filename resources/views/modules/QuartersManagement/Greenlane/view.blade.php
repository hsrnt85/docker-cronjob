@extends('layouts.master')

@section('content')

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">

                <h4 class="card-title text-primary">Paparan Permohonan</h4>
                <p class="card-title-desc">Maklumat Permohonan</p>
                {{ csrf_field() }}
                <input type="hidden" name="id" value="{{ $application->id }}">
                <div class="mb-3 row">
                    <label class="col-md-2 col-form-label">Kategori Kuarters</label>
                    <div class="col-md-9">
                        <p class="col-form-label">{{ $application->category->name ??''}}</p>
                    </div>
                </div>

                <div class="mb-3 row">
                    <label class="col-md-2 col-form-label">Daerah</label>
                    <div class="col-md-9">
                        <p class="col-form-label">{{ $application->category->district->district_name ??''}}</p>
                    </div>
                </div>

                <div class="mb-3 row">
                    <label for="name" class="col-md-2 col-form-label">Nama Pemohon</label>
                    <div class="col-md-9">
                        <p class="col-form-label">{{ $application->user->name ??''}}</p>
                    </div>
                </div>

                <div class="mb-3 row">
                    <label for="name" class="col-md-2 col-form-label">No Kad Pengenalan</label>
                    <div class="col-md-9">
                        <p class="col-form-label">{{ $application->user->new_ic ??'' }}</p>
                    </div>
                </div>

                <div class="mb-3 row">
                    <label for="name" class="col-md-2 col-form-label">Jabatan/Agensi Bertugas</label>
                    <div class="col-md-9">
                        <p class="col-form-label">{{ $userOffice->organization->name ??'' }}</p>
                    </div>
                </div>

                <div class="mb-3 row">
                    <label for="name" class="col-md-2 col-form-label">Jawatan</label>
                    <div class="col-md-9">
                        <p class="col-form-label">{{ $user->position->position_name ??'' }}</p>
                    </div>
                </div>

                <div class="mb-3 row">
                    <label for="name" class="col-md-2 col-form-label">Alamat Jabatan/Agensi Pembayar Gaji</label>
                    <div class="col-sm-3">
                        <p class="col-form-label">{{ $userOffice->address_1 ??''}}</p>
                    </div>
                </div>

                <div class="mb-3 row">
                    <label for="name" class="col-md-2 col-form-label">Gred Jawatan</label>
                    <div class="col-sm-3">
                        <p class="col-form-label">{{ $user->position_grade->grade_no ??''}}</p>
                    </div>
                </div>

                <div class="mb-3 row">
                    <label for="name" class="col-md-2 col-form-label">Taraf Jawatan</label>
                    <div class="col-sm-3">
                        <p class="col-form-label">{{ $user->position_type->grade_type ??''}}</p>
                    </div>
                </div>

                <div class="mb-3 row">
                    <label for="name" class="col-md-2 col-form-label">Taraf Perkahwinan</label>
                    <div class="col-sm-3">
                        <p class="col-form-label">{{ $user->marital_status->marital_status ??''}}</p>
                    </div>
                </div>

                <div class="mb-3 row">
                    <label for="name" class="col-md-2 col-form-label">No Telefon (Bimbit)</label>
                    <div class="col-sm-3">
                        <p class="col-form-label">{{ $user->phone_no_hp ??''}}</p>
                    </div>
                </div>

                <div class="mb-3 row">
                    <label for="name" class="col-md-2 col-form-label">No Telefon (Pejabat)</label>
                    <div class="col-sm-3">
                        <p class="col-form-label">{{ $userOffice->phone_no_office ??''}}</p>
                    </div>
                </div>

                <hr>

                <h4 class="card-title text-primary">Maklumat Pasangan</h4>
                <p class="card-title-desc">Maklumat Pasangan</p>

                <div class="mb-3 row">
                    <label for="name" class="col-md-2 col-form-label">Nama Pasangan</label>
                    <div class="col-sm-3">
                        <p class="col-form-label">{{ $userSpouse->spouse_name ??''}}</p>
                    </div>
                </div>

                <div class="mb-3 row">
                    <label for="name" class="col-md-2 col-form-label">No Kad Pengenalan</label>
                    <div class="col-sm-3">
                        <p class="col-form-label">{{ $userSpouse->new_ic ??''}}</p>
                    </div>
                </div>

                <div class="mb-3 row">
                    <label for="name" class="col-md-2 col-form-label">Bekerja(Ya/Tidak)</label>
                    <div class="col-sm-3">
                        <p class="col-form-label">{{ ($userSpouse->department_name != null) ? 'Ya' : 'Tidak' ??'' }}</p>
                    </div>
                </div>

                <div class="mb-3 row">
                    <label for="name" class="col-md-2 col-form-label">Jabatan/Tempat Bekerja</label>
                    <div class="col-sm-3">
                        <p class="col-form-label">{{ $userSpouse->department_name ??''}}</p>
                    </div>
                </div>

                <div class="mb-3 row">
                    <label for="name" class="col-md-2 col-form-label">Jawatan</label>
                    <div class="col-sm-3">
                        <p class="col-form-label">{{ $userSpouse->position_name ??''}}</p>
                    </div>
                </div>

                <hr>

                <h4 class="card-title text-primary">Maklumat Anak</h4>
                <p class="card-title-desc">Maklumat Anak</p>

                <div class="table-responsive col-sm-9 offset-sm-2">
                    <table class="table table-sm table-bordered" id="table-anak-list">
                        <thead class="text-white bg-primary">
                            <tr>
                                <th class="text-center">#</th>
                                <th class="text-center">Nama Anak</th>
                                <th class="text-center">No Kad Pengenalan/Surat Beranak</th>
                                <th class="text-center">Salinan Kad Pengenalan/Surat Beranak</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($userChildAll->count() > 0)
                                @foreach($userChildAll as $userChild)
                                    <tr>
                                        <td class="text-center">{{ $loop->iteration }}</td>
                                        <td class="text-center">{{ $userChild->child_name ??''}} </td>
                                        <td class="text-center">{{ $userChild->new_ic ??''}}</td>
                                        <td class="text-center">
                                            @if ($userChildAttachmentAll->where('users_child_id', $userChild->id)->first())
                                                <a download="{{ $userChild->child_name }}" target="_blank" href="{{ $cdn . $userChildAttachmentAll->where('users_child_id', $userChild->id)->first()->path_document }}"
                                                title="{{ $userChild->child_name }}">Kad Pengenalan/Surat Beranak</a>
                                            @else
                                                -
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td class="text-center">-</td>
                                    <td class="text-center">-</td>
                                    <td class="text-center">-</td>
                                </tr>
                            @endif

                        </tbody>
                    </table>
                </div>

                <hr>

                <h4 class="card-title text-primary">Maklumat Kediaman Terkini</h4>
                <p class="card-title-desc">Maklumat Kediaman Terkini</p>

                <div class="mb-3 row">
                    <label for="name" class="col-md-2 col-form-label">Alamat Kediaman Sekarang</label>
                    <div class="col-sm-3">
                        <p class="col-form-label">{{ $userHouse->last()->address_1 ??''}} {{ $userHouse->last()->address_2 ??''}} {{ $userHouse->last()->address_3 ??''}}</p>
                    </div>
                </div>

                <hr>

                <h4 class="card-title text-primary">Dokumen Sokongan</h4>
                <p class="card-title-desc">Dokumen Sokongan</p>

                <div class="table-responsive col-sm-9 offset-sm-2">
                    <table class="table table-sm table-bordered" id="table-anak-list">
                        <thead class="text-white bg-primary">
                            <tr>
                                <th class="text-center">#</th>
                                <th class="text-center">Dokumen</th>
                                <th class="text-center"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($applicationAttachmentAll as $applicationAttachment)
                                <tr>
                                    <td class="text-center">{{ $loop->iteration }}</td>
                                    <td class="text-center"><a download="{{ $applicationAttachment->document->document_name }}" target="_blank" href="{{ $cdn . $applicationAttachment->path_document }}" title="{{ $applicationAttachment->document->document_name }}">{{ $applicationAttachment->document->document_name }}</a></td>
                                    <td></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mb-3 row">
                    <label for="name" class="col-md-2 col-form-label align-self-center">Status Permohonan</label>
                    <div class="col-sm-3">
                        <p class="col-form-label">{!! ($application->is_draft) ? '<span class="badge bg-warning p-2 font-size-16">Draf</span>' : $application->application_status !!}</p>
                    </div>
                </div>

                <div class="mb-3 row">
                    <div class="col-sm-10 offset-sm-1">
                        @if($application->is_draft == 1)
                            <a class="btn btn-primary float-end swal-hantar">Hantar</a>
                        @endif
                        <a href="{{ route('applicationGreenlane.edit', ['id' => $application->id]) }}" class="btn btn-primary float-end me-2">Kemaskini</button>
                        <a class="btn btn-danger float-end me-2 swal-delete">Hapus</a>
                        <a href="{{ route('applicationGreenlane.index') }}" class="btn btn-secondary float-end me-2">Kembali</a>
                    </div>
                </div>



                <form method="POST" action="{{ route('applicationGreenlane.delete') }}" id="delete-form">
                    {{ csrf_field() }}
                    {{ method_field('DELETE') }}
                    <input class="form-control" type="hidden" id="id-delete" name="id" value="{{ $application->id }}">
                </form>

                <form method="POST" action="{{ route('applicationGreenlane.send') }}" id="send-form">
                    {{ csrf_field() }}
                    <input class="form-control" type="hidden" id="id-send" name="id" value="{{ $application->id }}">
                </form>

            </div>
        </div>
    </div> <!-- end col -->
</div>
<!-- end row -->

@endsection
