@extends('layouts.master')

@section('content')

<div class="row">
    <div class="col-12">

    <div class="card">
        <div class="card-body">

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="row">
                <div class="offset-lg-3" id="parsley-errors"></div>
            </div>

            <form action="{{ route('applicationGreenlane.update') }}" method="POST" enctype="multipart/form-data" id="app-form">
                {{ csrf_field() }}
                <input type="hidden" name="id" value="{{$application->id}}">

                <div id="vertical-permohonan" class="vertical-wizard">
                    <!-- Kategori Kuarters -->
                    <h3>Maklumat Kategori Kuarters</h3>
                    <section>

                            <div class="row">
                                <div class="col-lg-6">
                                    <label class="form-label">Kategori</label>
                                    @foreach($QuartersCategoryAll as $QuartersCategory)
                                        <div class="form-check mb-3 @error('quarter_category') is-invalid @enderror">
                                            <input class="form-check-input" type="radio" name="quarter_category" value="{{ $QuartersCategory->id }}" id="quarter_category{{ $QuartersCategory->id }}" @if(old('quarter_category', $application->q_category_id) == $QuartersCategory->id) checked @endif>
                                            <label class="form-check-label" for="quarter_category{{ $QuartersCategory->id }}">
                                                {{ $QuartersCategory->name}}
                                            </label>
                                        </div>
                                    @endforeach
                                    @error('quarter_category')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                    </section>

                    <!-- Maklumat Pemohon -->
                    <h3>Maklumat Pemohon</h3>
                    <section>

                            <div class="row mb-2">
                                <label for="name" class="col-md-2 col-form-label">Nama Pemohon</label>
                                <div class="col-md-10">
                                    <input class="form-control @error('application_name') is-invalid @enderror" type="text" id="application_name" name="application_name" value="{{ $user->name ?? '' }}" readonly>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <label for="name" class="col-md-2 col-form-label">No Kad Pengenalan</label>
                                <div class="col-md-10">
                                    <input class="form-control @error('ic_no') is-invalid @enderror" type="text" id="ic_no" name="ic_no" value="{{ $user->new_ic ?? '' }}" readonly>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <label for="name" class="col-md-2 col-form-label">Status Perkahwinan</label>
                                <div class="col-md-10">
                                    <input class="form-control" type="text" value="{{ $user->marital_status->marital_status ?? '' }}" readonly>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <label for="name" class="col-md-2 col-form-label">No Telefon (Bimbit)</label>
                                <div class="col-md-10">
                                    <input class="form-control" type="text" value="{{ $user->phone_no_hp ?? ''}}" readonly>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <label for="name" class="col-md-2 col-form-label">Emel Peribadi</label>
                                <div class="col-md-10">
                                    <input class="form-control" type="text" value="{{ $user->email ?? '' }}" readonly>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <label for="name" class="col-md-2 col-form-label">Alamat Kediaman Pemohon</label>
                                <div class="col-md-10">
                                    <input class="form-control" type="text" value="{{ $userHouse->last()->address_1 }} {{ $userHouse->last()->address_2 }} {{ $userHouse->last()->address_3 }}" readonly>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <label for="name" class="col-md-2 col-form-label">No Telefon (Rumah)</label>
                                <div class="col-md-10">
                                    <input class="form-control" type="text" value="{{ $user->phone_no_home ?? '' }}" readonly>
                                </div>
                            </div>

                    </section>

                    <!-- Maklumat Pekerjaan -->
                    <h3>Maklumat Pekerjaan</h3>
                    <section>
                        <div class="row mb-2">
                            <label for="name" class="col-md-3 col-form-label">Taraf Jawatan</label>
                            <div class="col-md-9">
                                <input class="form-control" type="text" value="{{ $user->position_type->position_type }}" readonly>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <label for="name" class="col-md-3 col-form-label">Jenis Lantikan</label>
                            <div class="col-md-9">
                                <input class="form-control" type="text" value="{{ $user->services_type->services_type }}" readonly>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <label for="name" class="col-md-3 col-form-label">Jawatan</label>
                            <div class="col-md-9">
                                <input class="form-control" type="text" value="{{ $user->position->position_name ?? '' }}" readonly>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <label for="name" class="col-md-3 col-form-label">Gred Jawatan</label>
                            <div class="col-md-9">
                                <input class="form-control" type="text" value="{{ $user->position_grade->grade_no ?? '' }}" readonly>
                            </div>
                        </div>

                        <div class="row mb-2">
                            <label for="name" class="col-md-3 col-form-label">Tarikh Mula Berkhidmat dengan Kerajaan</label>
                            <div class="col-md-9">
                                <input class="form-control" type="text" value="{{ $user->date_of_service }}" readonly>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <label for="name" class="col-md-3 col-form-label">Alamat Pejabat/Jabatan</label>
                            <div class="col-md-9">
                                <input class="form-control" type="text" value="{{ $userOffice->address_1 ?? '' }} {{ $userOffice?->address_2 }} {{ $userOffice?->address_3 }}" readonly>
                            </div>
                        </div>

                        <div class="row mb-2">
                            <label for="name" class="col-md-3 col-form-label">Gaji Sebulan</label>
                            <div class="col-md-9">
                                <input class="form-control input-mask text-start" type="text" name="user_salary" value="{{ $userSalary?->basic_salary }}"
                                data-inputmask="'alias': 'numeric', 'groupSeparator': ',', 'digits': 2, 'digitsOptional': false, 'prefix': 'RM '"
                                required=""
                                data-parsley-errors-container="#parsley-errors"
                                data-parsley-gt="0"
                                data-parsley-gt-message="Sila isi gaji sebulan gt"
                                data-parsley-required-message="Sila isi gaji sebulan">
                            </div>
                        </div>
                        <div class="row mb-2">
                            <label for="name" class="col-md-3 col-form-label">Imbuhan Tetap Perumahan</label>
                            <div class="col-md-9">
                                <input class="form-control input-mask text-start" type="text" name="user_itp" value="{{ $userSalary?->itp }}"
                                data-inputmask="'alias': 'numeric', 'groupSeparator': ',', 'digits': 2, 'digitsOptional': false, 'prefix': 'RM '"
                                required=""
                                data-parsley-errors-container="#parsley-errors"
                                data-parsley-required-message="Sila isi imbuhan tetap perumahan">
                            </div>
                        </div>
                        <div class="row mb-2">
                            <label for="name" class="col-md-3 col-form-label">Bantuan Sara Hidup</label>
                            <div class="col-md-9">
                                <input class="form-control input-mask text-start" type="text" name="user_bsh" value="{{ $userSalary?->bsh }}"
                                data-inputmask="'alias': 'numeric', 'groupSeparator': ',', 'digits': 2, 'digitsOptional': false, 'prefix': 'RM '"
                                required=""
                                data-parsley-errors-container="#parsley-errors"
                                data-parsley-required-message="Sila isi bantuan sara hidup">
                            </div>
                        </div>
                        <div class="row mb-2">
                            <label for="name" class="col-md-3 col-form-label">No. Gaji</label>
                            <div class="col-md-9">
                                <input class="form-control" type="text" name="user_no_gaji" value="{{ $userSalary?->no_gaji }}"
                                required=""
                                data-parsley-errors-container="#parsley-errors"
                                data-parsley-required-message="Sila isi no gaji">
                            </div>
                        </div>

                        <div class="row mb-2">
                            <label for="name" class="col-md-3 col-form-label">No. Tel Pejabat</label>
                            <div class="col-md-9">
                                <input class="form-control input-mask" type="text" value="{{ old('phone_no_office', $userOffice?->phone_no_office) }}" name="phone_no_office"
                                 data-inputmask="'mask':'99-9999 9999'"
                                 required=""
                                 data-parsley-errors-container="#parsley-errors"
                                 data-parsley-required-message="Sila isi no telefon pejabat">
                            </div>
                        </div>
                        <div class="row mb-2">
                            <label for="name" class="col-md-3 col-form-label">No. Faks Pejabat</label>
                            <div class="col-md-9">
                                <input class="form-control input-mask" type="text" value="{{ old('fax_no_office', $userOffice?->fax_no_office) }}" name="fax_no_office"
                                data-inputmask="'mask':'99-9999 9999'"
                                required=""
                                data-parsley-errors-container="#parsley-errors"
                                data-parsley-required-message="Sila isi no faks pejabat">
                            </div>
                        </div>
                        <div class="row mb-2">
                            <label for="name" class="col-md-3 col-form-label">Emel Jabatan/Syarikat</label>
                            <div class="col-md-9">
                                <input class="form-control input-mask" type="text" value="{{ old('email_office', $userOffice?->email_office) }}" name="email_office"
                                data-inputmask="'alias': 'email'">
                            </div>
                        </div>
                    </section>
                    <!-- Maklumat Pasangan -->
                    <h3>Maklumat Pasangan (Suami/Isteri)</h3>
                    <section>
                        <div>
                                <div class="row mb-2">
                                    <label for="name" class="col-md-2 col-form-label">Nama Pasangan</label>
                                    <div class="col-md-10">
                                        <input class="form-control" type="text" value="{{ $userSpouse?->spouse_name ?? '' }}" readonly>
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <label for="name" class="col-md-2 col-form-label">No Kad Pengenalan</label>
                                    <div class="col-md-10">
                                        <input class="form-control" type="text" value="{{ $userSpouse?->new_ic ?? '' }}" readonly>
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <label for="name" class="col-md-2 col-form-label">No. Tel Bimbit <span class="text-danger @if(!$userSpouse) d-none @endif">*</span></label>
                                    <div class="col-md-10">
                                        <input class="form-control input-mask" type="text" name="spouse_phone_no" value="{{ old('spouse_phone_no', $userSpouse?->phone_no_hp) }}"
                                        data-inputmask="'mask':'999-999999999'"
                                        @if(!$userSpouse) disabled @endif
                                        @if($userSpouse) required @endif
                                        data-parsley-errors-container="#parsley-errors"
                                        data-parsley-required-message="Sila isi no telefon bimbit pasangan">
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <label for="name" class="col-md-2 col-form-label">Bekerja(Ya/Tidak)</label>
                                    <div class="col-md-10">
                                        <div class="form-check mb-3">
                                            <input class="form-check-input" type="radio" name="is_spouse_work" id="yaBekerja" value="1" @if($userSpouse?->is_work == 1) checked @endif>
                                            <label class="form-check-label" for="yaBekerja">
                                                Ya
                                            </label>
                                        </div>
                                        <div class="form-check mb-3">
                                            <input class="form-check-input" type="radio" name="is_spouse_work" id="tidakBekerja" value="0" @if($userSpouse?->is_work == 0) checked @endif>
                                            <label class="form-check-label" for="tidakBekerja">
                                                Tidak
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <label for="name" class="col-md-2 col-form-label">Alamat Tempat Bekerja</label>
                                    <div class="col-md-10">
                                        <input class="form-control" type="text" name="spouse_office_address_1" value="{{ old('spouse_office_address_1', $userSpouse?->office_address_1) }}" disabled
                                        data-parsley-errors-container="#parsley-errors"
                                        data-parsley-required-message="Sila isi alamat tempat bekerja pasangan">
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-md-10 offset-md-2">
                                        <input class="form-control" type="text" name="spouse_office_address_2" value="{{ old('spouse_office_address_2', $userSpouse?->office_address_2) }}" disabled
                                        data-parsley-errors-container="#parsley-errors"
                                        data-parsley-required-message="Sila isi alamat tempat bekerja pasangan">
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-md-10 offset-md-2">
                                        <input class="form-control" type="text" name="spouse_office_address_3" value="{{ old('spouse_office_address_3', $userSpouse?->office_address_3) }}" disabled
                                        data-parsley-errors-container="#parsley-errors"
                                        data-parsley-required-message="Sila isi alamat tempat bekerja pasangan">
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <label for="name" class="col-md-2 col-form-label">Jawatan<span class="d-none spouse-work text-danger">*</span></label>
                                    <div class="col-md-10">
                                        <input class="form-control" type="text" name="spouse_position" value="{{ old('spouse_position', $userSpouse?->position_name) }}" disabled
                                        data-parsley-errors-container="#parsley-errors"
                                        data-parsley-required-message="Sila isi jawatan pasangan" >
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <label for="name" class="col-md-2 col-form-label">Gaji (RM)</label>
                                    <div class="col-md-10">
                                        <input class="form-control input-mask text-start" type="text" name="spouse_salary" value="{{ old('spouse_salary', $userSpouse?->salary) }}" disabled
                                        data-inputmask="'alias': 'numeric', 'groupSeparator': ',', 'digits': 2, 'digitsOptional': false, 'prefix': 'RM '"
                                        data-parsley-errors-container="#parsley-errors"
                                        data-parsley-required-message="Sila isi gaji pasangan">
                                    </div>
                                </div>
                        </div>
                    </section>

                    <!-- Maklumat Anak -->
                    <h3>Maklumat Anak</h3>
                    <section>
                        <div>
                            <div>
                                <div class="table-responsive col-sm-10 offset-sm-1">
                                    <table class="table table-sm table-bordered" id="table-anak-list">
                                        <thead>
                                            <tr>
                                                <th class="text-center">#</th>
                                                <th class="text-center">Nama Anak</th>
                                                <th class="text-center">No Kad Pengenalan/Surat Beranak</th>
                                                <th class="text-center">Salinan Kad Pengenalan/Surat Beranak</th>
                                                <th class="text-center">Muatnaik Kad Pengenalan/Surat Beranak</th>
                                                <th class="text-center">OKU</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if($userChildAll->count() != 0)
                                            @foreach($userChildAll as $userChild)
                                                <tr>
                                                    <td class="text-center align-middle">{{ $loop->iteration }}</td>
                                                    <td class="text-center align-middle">{{ $userChild->child_name ?? '' }} </td>
                                                    <td class="text-center align-middle">{{ $userChild->new_ic ?? '' }}</td>
                                                    <td class="text-center">
                                                        @if ($userChildAttachmentAll->where('users_child_id', $userChild->id)->first())
                                                            <a download="{{ $userChild->child_name }}" target="_blank" href="{{ $cdn . $userChildAttachmentAll->where('users_child_id', $userChild->id)->first()->path_document }}" title="{{ $userChild->child_name }}">Kad Pengenalan/Surat Beranak</a>
                                                        @else
                                                            -
                                                        @endif
                                                    </td>
                                                    <td class="text-center align-middle">
                                                        <input type="hidden" name="child[{{ $userChild->id }}]" value="{{ $userChild->id }}">
                                                        <input type="file" name="child_ic_document[{{ $userChild->id }}]" id="ic_{{ $userChild->id }}" class="form-control">
                                                    </td>
                                                    <td class="text-center align-middle">
                                                        <input type="hidden" name="cacat[{{ $userChild->id }}]" value="0">
                                                        <input type="checkbox" name="cacat[{{ $userChild->id }}]" id="cacat_{{ $userChild->id }}" value="1" @if($userChild->is_cacat == 1) checked @endif >
                                                    </td>
                                                </tr>
                                            @endforeach
                                            @else
                                                <tr>
                                                    <td class="text-center">-</td>
                                                    <td class="text-center">-</td>
                                                    <td class="text-center">-</td>
                                                    <td class="text-center">-</td>
                                                    <td class="text-center">-</td>
                                                    <td class="text-center">-</td>
                                                </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </section>

                    <!-- Maklumat Kediaman  -->
                    <h3>Maklumat Kediaman </h3>
                    <section>
                        <div>

                                <div class="row mb-2">
                                    <label for="name" class="col-md-2 col-form-label">Alamat Kediaman Semasa</label>
                                    <div class="col-md-10">
                                        <input class="form-control" type="text" value="{{ $userHouse->last()->address_1 }}" name="current_address1" data-url="{{ route('applicationGreenlane.ajaxGetDistance') }}" readonly>
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-md-10 offset-md-2">
                                        <input class="form-control" type="text" value="{{ $userHouse->last()->address_2 }}" name="current_address2" readonly>
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-md-10 offset-md-2">
                                        <input class="form-control" type="text" value="{{ $userHouse->last()->address_3 }}" name="current_address3" readonly>
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <label for="name" class="col-md-2 col-form-label">Sewa Sebulan</label>
                                    <div class="col-md-10">
                                        <input class="form-control input-mask text-start" type="text" name="current_house_rent" value="{{ old('current_house_rent', $application->rental_fee ?? '0.00') }}"
                                        data-inputmask="'alias': 'numeric', 'groupSeparator': ',', 'digits': 2, 'digitsOptional': false, 'prefix': 'RM '">
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <label for="name" class="col-md-2 col-form-label">Jarak Daripada Pejabat (KM)</label>
                                    <div class="col-md-10">
                                        <input class="form-control" type="text" value="" id="distance" name="current_house_distance" readonly>
                                    </div>
                                </div>

                        </div>
                    </section>

                    <!-- Maklumat Pinjaman Perumahan -->
                    <h3>Maklumat Pinjaman Perumahan</h3>
                    <section>
                        <div>

                            <div class="row mb-2">
                                <label for="name" class="col-md-2 col-form-label">Adakah pemohon membeli rumah dengan kemudahan pinjaman perumahan kerajaan</label>
                                <div class="col-md-10">
                                    <div class="form-check mb-3">
                                        <input class="form-check-input" type="radio" name="is_epnj_user" value="1" id="yaEpnj" @if(old('is_epnj_user', '') == 1 || (bool) $userEpnj == true) checked @endif>
                                        <label class="form-check-label" for="yaEpnj">
                                            Ya
                                        </label>
                                    </div>
                                    <div class="form-check mb-3">
                                        <input class="form-check-input" type="radio" name="is_epnj_user" value="0" id="tidakEpnj" @if(old('is_epnj_user', '') == 0 || (bool) $userEpnj == false) checked @endif>
                                        <label class="form-check-label" for="tidakEpnj">
                                            Tidak
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <label for="name" class="col-md-2 col-form-label">Alamat Perumahan Dimiliki</label>
                                <div class="col-md-10">
                                    <input class="form-control" type="text"  name="user_epnj_address_1" value="{{ old('user_epnj_address_1', $userEpnj?->address_1) }}" data-url="{{ route('applicationGreenlane.ajaxGetDistance') }}"
                                    data-parsley-errors-container="#parsley-errors"
                                    data-parsley-required-message="Sila isi alamat rumah pinjaman kerajaan">
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-md-10 offset-md-2">
                                    <input class="form-control" type="text"  name="user_epnj_address_2" value="{{ old('user_epnj_address_2', $userEpnj?->address_2) }}"
                                    data-parsley-errors-container="#parsley-errors"
                                    data-parsley-required-message="Sila isi alamat rumah pinjaman kerajaan">
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-md-10 offset-md-2">
                                    <input class="form-control" type="text"  name="user_epnj_address_3" value="{{ old('user_epnj_address_3', $userEpnj?->address_3) }}"
                                    data-parsley-errors-container="#parsley-errors"
                                    data-parsley-required-message="Sila isi alamat rumah pinjaman kerajaan">
                                </div>
                            </div>
                            <div class="row mb-2">
                                <label for="name" class="col-md-2 col-form-label">Poskod</label>
                                <div class="col-md-10">
                                    <input class="form-control" type="text"  name="user_epnj_postcode" value="{{ old('user_epnj_postcode', $userEpnj?->postcode) }}"
                                    data-parsley-errors-container="#parsley-errors"
                                    data-parsley-required-message="Sila isi poskod rumah pinjaman kerajaan">
                                </div>
                            </div>
                            <div class="row mb-2">
                                <label for="name" class="col-md-2 col-form-label">Mukim</label>
                                <div class="col-md-10">
                                    <input class="form-control" type="text"  name="user_epnj_mukim" value="{{ old('user_epnj_mukim', $userEpnj?->mukim) }}"
                                    data-parsley-errors-container="#parsley-errors"
                                    data-parsley-required-message="Sila isi mukim rumah pinjaman kerajaan">
                                </div>
                            </div>
                            <div class="row mb-2">
                                <label for="name" class="col-md-2 col-form-label">Jarak Daripada Pejabat (KM)</label>
                                <div class="col-md-10">
                                    <input class="form-control" type="text"  id="epnj_distance" name="user_epnj_distance" value="{{ round($userEpnj?->distance, 2)  }}" readonly>
                                </div>
                            </div>


                            <div class="row mb-2">
                                <label for="name" class="col-md-2 col-form-label">Adakah pasangan membeli rumah dengan kemudahan pinjaman perumahan kerajaan</label>
                                <div class="col-md-10">
                                    <div class="form-check mb-3">
                                        <input class="form-check-input" type="radio" name="is_epnj_spouse" value="1" id="yaEpnj" @if(old('is_epnj_spouse', '') == 1 || (bool) $userSpouseEpnj == true) checked @endif >
                                        <label class="form-check-label" for="yaEpnj">
                                            Ya
                                        </label>
                                    </div>
                                    <div class="form-check mb-3">
                                        <input class="form-check-input" type="radio" name="is_epnj_spouse" value="0" id="tidakEpnj" @if(old('is_epnj_spouse', '') == 0 || (bool) $userSpouseEpnj == false) checked @endif >
                                        <label class="form-check-label" for="tidakEpnj">
                                            Tidak
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <label for="name" class="col-md-2 col-form-label">Alamat Perumahan Dimiliki</label>
                                <div class="col-md-10">
                                    <input class="form-control" type="text"  name="spouse_epnj_address_1" value="{{ old('spouse_epnj_address_1', $userSpouseEpnj?->address_1) }}"
                                    data-parsley-errors-container="#parsley-errors"
                                    data-parsley-required-message="Sila isi alamat rumah pinjaman kerajaan pasangan">
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-md-10 offset-md-2">
                                    <input class="form-control" type="text"  name="spouse_epnj_address_2" value="{{ old('spouse_epnj_address_2', $userSpouseEpnj?->address_2) }}"
                                    data-parsley-errors-container="#parsley-errors"
                                    data-parsley-required-message="Sila isi alamat rumah pinjaman kerajaan pasangan">
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-md-10 offset-md-2">
                                    <input class="form-control" type="text"  name="spouse_epnj_address_3" value="{{ old('spouse_epnj_address_3', $userSpouseEpnj?->address_3) }}">
                                </div>
                            </div>
                            <div class="row mb-2">
                                <label for="name" class="col-md-2 col-form-label">Poskod</label>
                                <div class="col-md-10">
                                    <input class="form-control" type="text" name="spouse_epnj_postcode" value="{{ old('spouse_epnj_postcode', $userSpouseEpnj?->postcode) }}"
                                    data-parsley-errors-container="#parsley-errors"
                                    data-parsley-required-message="Sila isi poskod rumah pinjaman kerajaan pasangan">
                                </div>
                            </div>
                            <div class="row mb-2">
                                <label for="name" class="col-md-2 col-form-label">Mukim</label>
                                <div class="col-md-10">
                                    <input class="form-control" type="text"  name="spouse_epnj_mukim" value="{{ old('spouse_epnj_mukim', $userSpouseEpnj?->mukim) }}"
                                    data-parsley-errors-container="#parsley-errors"
                                    data-parsley-required-message="Sila isi mukim rumah pinjaman kerajaan pasangan">
                                </div>
                            </div>
                            <div class="row mb-2">
                                <label for="name" class="col-md-2 col-form-label">Jarak Daripada Pejabat (KM)</label>
                                <div class="col-md-10">
                                    <input class="form-control" type="text"  id="epnj_distance" name="spouse_epnj_distance" readonly>
                                </div>
                            </div>

                        </div>
                    </section>

                    <!-- Ulasan -->
                    <h3>Ulasan</h3>
                    <section>
                        <div>

                                <div class="row mb-2">
                                    <label for="name" class="col-md-2 col-form-label">Ulasan Tambahan Permohonan</label>
                                    <div class="col-md-10">
                                        <textarea id="message" class="form-control" placeholder="Ulasan disini" rows="4" name="review"
                                        required
                                        data-parsley-errors-container="#parsley-errors"
                                        data-parsley-required-message="Sila isi ulasan tambahan permohonan">{{ old('review', $application->review) }}</textarea>
                                    </div>
                                </div>

                                @if ($application->review_attachment)
                                    <div class="row mb-2">
                                        <label for="name" class="col-md-2 col-form-label">Surat Sokongan Ulasan</label>
                                        <div class="col-md-10">
                                            <a download="Surat Sokongan Ulasan" target="_blank" href="{{ $cdn . $application->review_attachment }}" title="Surat Sokongan Ulasan">Surat Sokongan Ulasan</a>
                                        </div>
                                    </div>
                                @endif

                                <div class="row mb-2">
                                    <label for="name" class="col-md-2 col-form-label">Surat Sokongan Ulasan</label>
                                    <div class="col-md-10">
                                        <input type="file" class="form-control" name="review_document">
                                    </div>
                                </div>

                        </div>
                    </section>

                    <!-- Dokumen Sokongan -->
                    <h3>Dokumen Sokongan</h3>
                    <section>
                        <div>
                            <div class="table-responsive col-sm-10 offset-sm-1">
                                <table class="table table-sm table-bordered" id="table-anak-list">
                                    <thead class="bg-primary text-white">
                                        <tr>
                                            <th class="text-center">#</th>
                                            <th class="text-center">Dokumen</th>
                                            <th class="text-center">Kemaskini</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($documentAll as $document)
                                            <tr>
                                                <td class="text-center">{{ $loop->iteration }}</td>
                                                <td class="text-center">
                                                    @if ($applicationAttachmentAll->where('d_id', $document->id)->where('a_id', $application->id)->first())
                                                        <a download="{{ $document->document_name }}" target="_blank" href="{{ $cdn . $applicationAttachmentAll->where('d_id', $document->id)->where('a_id', $application->id)->first()->path_document }}" title="{{ $document->document_name }}">{{ $document->document_name }}</a>
                                                    @else
                                                        -
                                                    @endif
                                                </td>
                                                <td>
                                                    <input type="file" class="form-control" name="document[{{$document->id}}]">
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>


                            <div class="row mb-2">
                                <div class="col-md-10 offset-md-1">
                                    <input type="hidden" name="disclaimer" value="0">
                                    <input type="checkbox" name="disclaimer" id="disclaimer" class="me-2"
                                        required=""
                                        data-parsley-errors-container="#parsley-errors"
                                        data-parsley-required-message="Sila klik pengakuan">
                                    <p class="" for="disclaimer">Bahawasanya saya mengaku maklumat yang diberikan di atas adalah sah dan benar. Sekiranya terdapat maklumat yang diberikan adalah palsu dan tidak benar, maka permohonan saya ini <strong>*DITOLAK/DIBATALKAN</strong> oleh Jawatankuasa Kuarters.</p>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>
            </form>
        </div>
    </div>



    </div> <!-- end col -->
</div>
<!-- end row -->

@endsection

@section('script')
<script src="{{ URL::asset('/assets/libs/parsleyjs/parsleyjs.min.js')}}"></script>
<script src="{{ URL::asset('assets/js/pages/Application/application.js')}}"></script>
<script src="{{ URL::asset('/assets/libs/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
@endsection
