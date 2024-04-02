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

            <form action="{{ route('applicationGreenlane.store') }}" method="POST" enctype="multipart/form-data" id="app-form">
                {{ csrf_field() }}

            <div id="vertical-permohonan" class="vertical-wizard">


                <!-- Kategori Kuarters -->
                <h3>Kategori Kuarters (Lokasi)</h3>
                <section>
                    <form>
                        <div class="row">
                            <div class="col-lg-6">
                                <label class="form-label">Kategori Kuarters (Lokasi)<span class="text-danger">*</span></label>
                                    @foreach($QuartersCategoryAll as $QuartersCategory)
                                        <div class="form-check mb-3 @error('quarter_category') is-invalid @enderror">
                                            <input class="form-check-input" type="radio" name="quarter_category" value="{{ $QuartersCategory->id }}" id="quarter_category{{ $QuartersCategory->id }}"
                                            required="" data-parsley-errors-container="#parsley-errors"
                                            data-parsley-required-message="Sila pilih satu(1) pilihan Kategori Kuarters (Lokasi)">
                                            <label class="form-check-label" for="quarter_category{{ $QuartersCategory->id }}"   >
                                                {{ $QuartersCategory->name}}
                                            </label>
                                        </div>
                                    @endforeach
                                    @error('quarter_category')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                    <div id="quarter-category-errors"></div>
                            </div>
                        </div>
                    </form>
                </section>

                <!-- Maklumat Pemohon -->
                <h3>Maklumat Pemohon</h3>
                    <section>
                           <div class="row mb-2">
                                <label for="new_ic" class="col-md-2 col-form-label">No Kad Pengenalan</label>
                                <div class="col-md-10">
                                    <input class="form-control @error('ic_no') is-invalid @enderror" type="text" id="new_ic" name="new_ic" onkeyup="autofill_ic(); autofill_Epnj(); autofill_TableAnak(); autofill_spouse();" value="{{ old('new_ic', '') }}">
                                </div>
                            </div>
                            <div class="row mb-2" hidden>
                                <label for="user_id" class="col-md-2 col-form-label">Id</label>
                                <div class="col-md-10">
                                    <input class="form-control" type="text" id="user_id" name="user_id" value="{{ old('user_id', '') }}" >
                                </div>
                            </div>
                            <div class="row mb-2">
                                <label for="name" class="col-md-2 col-form-label">Nama Pemohon</label>
                                <div class="col-md-10">
                                    <input class="form-control @error('application_name') is-invalid @enderror" type="text" id="name" name="name" value="{{ old('name', '') }}" readonly>
                                    <input type="hidden" id="id" name="id">
                                </div>
                            </div>
                            <div class="row mb-2">
                                <label for="marital_status" class="col-md-2 col-form-label">Status Perkahwinan</label>
                                <div class="col-md-10">
                                    <input class="form-control" type="text" id="marital_status" name="marital_status" value="{{ old('marital_status', '') }}" readonly>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <label for="no_phone" class="col-md-2 col-form-label">No Telefon (Bimbit)</label>
                                <div class="col-md-10">
                                    <input class="form-control" type="text" id="phone_no_hp" name="phone_no_hp" value="{{ old('phone_no_hp', '') }}" readonly>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <label for="email" class="col-md-2 col-form-label">Emel</label>
                                <div class="col-md-10">
                                    <input class="form-control" type="text" id="email" name="email" value="{{ old('email', '') }}" readonly>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <label for="address" class="col-md-2 col-form-label">Alamat Kediaman Pemohon</label>
                                <div class="col-md-10">
                                    <input class="form-control" type="text" id="address" name="address" value="{{ old('address', '') }}" readonly>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <label for="house_phone" class="col-md-2 col-form-label">No Telefon (Rumah)</label>
                                <div class="col-md-10">
                                    <input class="form-control" type="text" id="phone_no_home" name="phone_no_home" value="{{ old('phone_no_home', '') }}" readonly>
                                </div>
                            </div>

                    </section>

                    <!-- Maklumat Pekerjaan -->
                    <h3>Maklumat Pekerjaan</h3>
                    <section>
                        <div class="row mb-2">
                            <label for="position_type" class="col-md-3 col-form-label">Taraf Jawatan</label>
                            <div class="col-md-9">
                                <input class="form-control" type="text" name="position_type" id="position_type" value="{{ old('position_type', '') }}" readonly>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <label for="services_type" class="col-md-3 col-form-label">Jenis Lantikan</label>
                            <div class="col-md-9">
                                <input class="form-control" type="text" name="services_type" id="services_type" value="{{ old('services_type', '') }}" readonly>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <label for="position" class="col-md-3 col-form-label">Jawatan</label>
                            <div class="col-md-9">
                                <input class="form-control" type="text" name="position" id="position" value="{{ old('position', '') }}" readonly>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <label for="position_grade" class="col-md-3 col-form-label">Gred Jawatan</label>
                            <div class="col-md-9">
                                <input class="form-control" type="text" name="position_grade" id="position_grade" value="{{ old('position_grade', '') }}" readonly>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <label for="date_of_service" class="col-md-3 col-form-label">Tarikh Mula Berkhidmat dengan Kerajaan</label>
                            <div class="col-md-9">
                                <input class="form-control" type="text" name="date_of_service" id="date_of_service" value="{{ old('date_of_service', '') }}" readonly>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <label for="office_address" class="col-md-3 col-form-label">Alamat Pejabat/Jabatan</label>
                            <div class="col-md-9">
                                <input class="form-control" type="text" name="office_address" id="office_address" value="{{ old('office_address', '') }}" readonly>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <label for="name" class="col-md-3 col-form-label">Gaji Sebulan <span class="text-danger">*</span></label>
                            <div class="col-md-9">
                                <input class="form-control input-mask text-start" type="text" value="" name="user_salary" value="{{ old('user_salary', '0.00') }}"
                                data-inputmask="'alias': 'numeric', 'groupSeparator': ',', 'digits': 2, 'digitsOptional': false, 'prefix': 'RM '"
                                required=""
                                data-parsley-errors-container="#parsley-errors"
                                data-parsley-gt="0"
                                data-parsley-gt-message="Sila isi gaji sebulan gt"
                                data-parsley-required-message="Sila isi gaji sebulan">
                                <div id="user-salary-errors"></div>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <label for="user_itp" class="col-md-3 col-form-label">Imbuhan Tetap Perumahan <span class="text-danger">*</span></label>
                            <div class="col-md-9">
                                <input class="form-control input-mask text-start" type="text" value="" name="user_itp" value="{{ old('user_itp', '0.00') }}"
                                data-inputmask="'alias': 'numeric', 'groupSeparator': ',', 'digits': 2, 'digitsOptional': false, 'prefix': 'RM '"
                                required=""
                                data-parsley-errors-container="#parsley-errors"
                                data-parsley-required-message="Sila isi gaji sebulan">
                                <div id="user-itp-errors"></div>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <label for="user_bsh" class="col-md-3 col-form-label">Bantuan Sara Hidup <span class="text-danger">*</span></label>
                            <div class="col-md-9">
                                <input class="form-control input-mask text-start" type="text" value="" name="user_bsh" value="{{ old('user_bsh', '0.00') }}"
                                data-inputmask="'alias': 'numeric', 'groupSeparator': ',', 'digits': 2, 'digitsOptional': false, 'prefix': 'RM '"
                                required=""
                                data-parsley-errors-container="#parsley-errors"
                                data-parsley-required-message="Sila isi gaji sebulan">
                                <div id="user-salary-errors"></div>
                            </div>
                        </div>
                        <div class="row mb-2">
                        <label for="user_no_salary" class="col-md-3 col-form-label">No. Gaji <span class="text-danger">*</span></label>
                            <div class="col-md-9">
                            <input class="form-control" type="text" name="user_no_salary" id="user_no_salary" value="{{ old('user_no_salary', '') }}" readonly>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <label for="phone_no_office" class="col-md-3 col-form-label">No. Tel Pejabat <span class="text-danger">*</span></label>
                            <div class="col-md-9">
                                <input class="phone form-control input-mask" type="text" value="{{ old('phone_no_office', '') }}" name="phone_no_office"
                                data-inputmask="'mask':'99-9999 9999'"
                                required=""
                                data-parsley-errors-container="#parsley-errors"
                                data-parsley-required-message="Sila isi no telefon pejabat">
                            </div>
                        </div>
                        <div class="row mb-2">
                            <label for="fax_no_office" class="col-md-3 col-form-label">No. Faks Pejabat</label>
                            <div class="col-md-9">
                                <input class="form-control input-mask" type="text" value="{{ old('fax_no_office', '') }}" name="fax_no_office"
                                data-inputmask="'mask':'99-9999 9999'">
                            </div>
                        </div>
                        <div class="row mb-2">
                            <label for="email_office" class="col-md-3 col-form-label">Emel Jabatan/Syarikat </label>
                            <div class="col-md-9">
                                <input class="form-control input-mask" type="text" value="{{ old('email_office', '') }}" name="email_office" data-inputmask="'alias': 'email'">
                            </div>
                        </div>
                    </section>

                    <!-- Maklumat Pasangan -->
                    <h3>Maklumat Pasangan (Suami/Isteri)</h3>
                    <section>
                        <div>
                            <div class="row mb-2">
                                <label for="spouse_name" class="col-md-2 col-form-label">Nama Pasangan</label>
                                <div class="col-md-10">
                                    <input class="form-control" type="text" name="spouse_name" id="spouse_name" value="{{ old('spouse_name', '') }}" readonly>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <label for="spouse_new_ic" class="col-md-2 col-form-label">No Kad Pengenalan</label>
                                <div class="col-md-10">
                                    <input class="form-control" type="text" name="spouse_new_ic" id="spouse_new_ic" value="{{ old('spouse_new_ic', '') }}" readonly>
                                </div>
                            </div>
                            <div class="row mb-2">
                            <label for="spouse_phone_no" class="col-md-2 col-form-label">No. Tel Bimbit <span class="text-danger">*</span></label>
                                <div class="col-md-10">
                                    <input class="form-control input-mask" type="text" value="{{ old('spouse_phone_no', '') }}" name="spouse_phone_no"
                                    data-inputmask="'mask':'999-999999999'"
                                    required=""
                                    data-parsley-errors-container="#parsley-errors"
                                    data-parsley-required-message="Sila isi no telefon bimbit pasangan">
                                </div>
                            </div>
                            <div class="row mb-2">
                                <label for="spouse_work" class="col-md-2 col-form-label">Bekerja(Ya/Tidak)</label>
                                <div class="col-md-10">
                                    <div class="form-check mb-3">
                                        <input class="form-check-input" type="radio" name="is_spouse_work" id="yaBekerja" value="1" {{ old('is_spouse_work') == '1' ? 'checked' : '' }} onclick="enableSpouseWork()">
                                        <label class="form-check-label" for="yaBekerja">
                                            Ya
                                        </label>
                                    </div>
                                    <div class="form-check mb-3">
                                        <input class="form-check-input" type="radio" name="is_spouse_work" id="tidakBekerja"  value="0" {{ old('is_spouse_work') == '0' ? 'checked' : '' }} onclick="disableSpouseWork()">
                                        <label class="form-check-label" for="tidakBekerja">
                                            Tidak
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <label for="name" class="col-md-2 col-form-label">Alamat Tempat Bekerja <span class="text-danger">*</span></label>
                                <div class="col-md-10">
                                    <input class="form-control" type="text" name="spouse_office_address_1" id ="spouse_office_address_1" value="{{ old('spouse_office_address_1', '') }}" disabled
                                    data-parsley-errors-container="#parsley-errors"
                                    data-parsley-required-message="Sila isi alamat tempat bekerja pasangan">
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-md-10 offset-md-2">
                                    <input class="form-control" type="text" name="spouse_office_address_2" id ="spouse_office_address_2" value="{{ old('spouse_office_address_2', '') }}" disabled
                                    data-parsley-errors-container="#parsley-errors"
                                    data-parsley-required-message="Sila isi alamat tempat bekerja pasangan">
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-md-10 offset-md-2">
                                    <input class="form-control" type="text" name="spouse_office_address_3" id ="spouse_office_address_3" value="{{ old('spouse_office_address_3', '') }}" disabled
                                    data-parsley-errors-container="#parsley-errors"
                                    data-parsley-required-message="Sila isi alamat tempat bekerja pasangan">
                                </div>
                            </div>
                            <div class="row mb-2">
                                <label for="name" class="col-md-2 col-form-label">Jawatan <span class="text-danger">*</span></label>
                                <div class="col-md-10">
                                    <input class="form-control" type="text" name="spouse_position" id ="spouse_position" value="{{ old('spouse_position', '') }}" disabled
                                    data-parsley-errors-container="#parsley-errors"
                                    data-parsley-required-message="Sila isi jawatan pasangan">
                                </div>
                            </div>
                            <div class="row mb-2">
                                <label for="spouse_salary" class="col-md-2 col-form-label">Gaji (RM) <span class="text-danger">*</span></label>
                                <div class="col-md-10">
                                    <input class="form-control input-mask text-start" type="text" value="" name="spouse_salary" id="spouse_salary" value="{{ old('spouse_salary', '0.00') }}"
                                    data-inputmask="'alias': 'numeric', 'groupSeparator': ',', 'digits': 2, 'digitsOptional': false, 'prefix': 'RM '"
                                    data-parsley-errors-container="#parsley-errors"
                                    data-parsley-required-message="Sila isi gaji pasangan" disabled>
                                    <div id="user-salary-errors"></div>
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
                                                <th class="text-center">Salinan Kad Pengenalan/Surat Beranak<span class="text-danger">*</span></th>
                                                <th class="text-center">OKU</th>
                                            </tr>
                                        </thead>
                                        <tbody id ="child_info" name="child_info" >

	                                        @if(old('child') != null)

			                                    @foreach (old('child') as $i => $child_id)
			                                        @php
				                                        $child_name = old('child_name.'.$child_id) ?? "";
				                                        $child_ic = old('child_ic.'.$child_id) ?? "";
				                                        $is_cacat = old('cacat.'.$child_id) ?? "";
				                                        $checkbox_cacat= ($is_cacat == 1 ) ? 'checked' : "";
			                                        @endphp

                                                    <tr>
				                                        <td class="text-center">{{ $loop->iteration }} {{ $is_cacat.$checkbox_cacat }}</td>
				                                        <td class="text-center">{{ $child_name }}</td>
				                                        <td class="text-center">{{ $child_ic }}</td>
				                                        <td class="text-center">
					                                        <div class="form-check">
						                                        <input type="hidden" name="child[{{ $child_id }}]" value="{{ $child_id }}" ></input>
						                                        <input type="file" class="form-control @error('child_ic_document.'.$child_id) is-invalid @enderror" name="child_ic_document[{{ $child_id }}]" ></input>
					                                        </div>
				                                        </td>
				                                        <td class="text-center width=1%">
					                                        <div class="form-check">
						                                        <input type="checkbox" class="center" name="cacat[{{ $child_id }}]" value="{{ $is_cacat }}" {{ $checkbox_cacat }}></input>
					                                       </div>
				                                        </td>
			                                        </tr>
		                                        @endforeach
	                                        @endif
                                        </tbody>
                                    </table>
                                    <p class="card-title-desc">* PDF, JPG, JPEG & PNG sahaja</p>
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
                                        <input class="form-control" type="text" name="current_address_1" id="current_address_1" value="{{ old('current_address_1', '') }}" data-url="{{ route('applicationGreenlane.ajaxGetDistance') }}" readonly>
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-md-10 offset-md-2">
                                        <input class="form-control" type="text" name="current_address_2" id="current_address_2" value="{{ old('current_address_2', '') }}" readonly>
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-md-10 offset-md-2">
                                        <input class="form-control" type="text" name="current_address_3" id="current_address_3" value="{{ old('current_address_3', '') }}" readonly>
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <label for="name" class="col-md-2 col-form-label">Sewa Sebulan</label>
                                    <div class="col-md-10">
                                    <input class="form-control input-mask text-start" type="text" value="" name="current_house_rent" value="{{ old('current_house_rent', '0.00') }}"
                                        data-inputmask="'alias': 'numeric', 'groupSeparator': ',', 'digits': 2, 'digitsOptional': false, 'prefix': 'RM '">
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <label for="name" class="col-md-2 col-form-label">Jarak Daripada Pejabat (KM)</label>
                                    <div class="col-md-10">
                                        <input class="form-control" type="text" name="current_house_distance" id="current_house_distance" readonly>
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
                                        <input class="form-check-input" type="radio" name="is_epnj_user" value="1" id="yaEpnj" {{ old('is_epnj_user') == '1' ? 'checked' : '' }} >
                                        <label class="form-check-label" for="yaEpnj">
                                            Ya
                                        </label>
                                    </div>
                                    <div class="form-check mb-3">
                                        <input class="form-check-input" type="radio" name="is_epnj_user" value="0" id="tidakEpnj" {{ old('is_epnj_user') == '0' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="tidakEpnj">
                                            Tidak
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <label for="name" class="col-md-2 col-form-label">Alamat Perumahan Dimiliki</label>
                                <div class="col-md-10">
                                    <input class="form-control" type="text" value="" name="user_epnj_address_1" id="user_epnj_address_1" value="{{ old('user_epnj_address_1', '') }}" data-url="{{ route('applicationGreenlane.ajaxGetDistance') }}"
                                    data-parsley-errors-container="#parsley-errors"
                                    data-parsley-required-message="Sila isi alamat rumah">
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-md-10 offset-md-2">
                                    <input class="form-control" type="text" value="" name="user_epnj_address_2" id="user_epnj_address_2" value="{{ old('user_epnj_address_2', '') }}"
                                    data-parsley-errors-container="#parsley-errors"
                                    data-parsley-required-message="Sila isi alamat rumah">
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-md-10 offset-md-2">
                                    <input class="form-control" type="text" value="" name="user_epnj_address_3" id="user_epnj_address_3" value="{{ old('user_epnj_address_3', '') }}"
                                    data-parsley-errors-container="#parsley-errors"
                                    data-parsley-required-message="Sila isi alamat rumah">
                                </div>
                            </div>
                            <div class="row mb-2">
                                <label for="name" class="col-md-2 col-form-label">Poskod</label>
                                <div class="col-md-10">
                                    <input class="form-control" type="text" value="" name="user_epnj_postcode" id="user_epnj_postcode" value="{{ old('user_epnj_postcode', '') }}"
                                    data-parsley-errors-container="#parsley-errors"
                                    data-parsley-required-message="Sila isi poskod rumah pinjaman kerajaan">
                                </div>
                            </div>
                            <div class="row mb-2">
                                <label for="name" class="col-md-2 col-form-label">Mukim</label>
                                <div class="col-md-10">
                                    <input class="form-control" type="text" value="" name="user_epnj_mukim" value="{{ old('user_epnj_mukim', '') }}"
                                    data-parsley-errors-container="#parsley-errors"
                                    data-parsley-required-message="Sila isi mukim rumah pinjaman kerajaan">
                                </div>
                            </div>
                            <div class="row mb-2">
                                <label for="name" class="col-md-2 col-form-label">Jarak Daripada Pejabat (KM)</label>
                                <div class="col-md-10">
                                    <input class="form-control" type="text" value="" id="epnj_distance" name="user_epnj_distance" readonly>
                                </div>
                            </div>

                            <div class="row mb-2">
                                <label for="name" class="col-md-2 col-form-label">Adakah pasangan membeli rumah dengan kemudahan pinjaman perumahan kerajaan</label>
                                <div class="col-md-10">
                                    <div class="form-check mb-3">
                                        <input class="form-check-input" type="radio" name="is_epnj_spouse" value="1" id="yaEpnj" {{ old('is_epnj_spouse') == '1' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="yaEpnj">
                                            Ya
                                        </label>
                                    </div>
                                    <div class="form-check mb-3">
                                        <input class="form-check-input" type="radio" name="is_epnj_spouse" value="0" id="tidakEpnj" {{ old('is_epnj_spouse') == '0' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="tidakEpnj">
                                            Tidak
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-2">
                                <label for="name" class="col-md-2 col-form-label">Alamat Perumahan Dimiliki</label>
                                <div class="col-md-10">
                                    <input class="form-control" type="text" value="" name="spouse_epnj_address_1" value="{{ old('spouse_epnj_address_1', '') }}"
                                    data-parsley-errors-container="#parsley-errors"
                                    data-parsley-required-message="Sila isi alamat rumah">
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-md-10 offset-md-2">
                                    <input class="form-control" type="text" value="" name="spouse_epnj_address_2" value="{{ old('spouse_epnj_address_1', '') }}"
                                    data-parsley-errors-container="#parsley-errors"
                                    data-parsley-required-message="Sila isi alamat rumah">
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-md-10 offset-md-2">
                                    <input class="form-control" type="text" value="" name="spouse_epnj_address_3" value="{{ old('spouse_epnj_address_1', '') }}"
                                    data-parsley-errors-container="#parsley-errors"
                                    data-parsley-required-message="Sila isi alamat rumah">
                                </div>
                            </div>
                            <div class="row mb-2">
                                <label for="name" class="col-md-2 col-form-label">Poskod</label>
                                <div class="col-md-10">
                                    <input class="form-control" type="text" value="" name="spouse_epnj_postcode" value="{{ old('spouse_epnj_postcode', '') }}"
                                    data-parsley-errors-container="#parsley-errors"
                                    data-parsley-required-message="Sila isi poskod rumah pinjaman kerajaan">
                                </div>
                            </div>
                            <div class="row mb-2">
                                <label for="name" class="col-md-2 col-form-label">Mukim</label>
                                <div class="col-md-10">
                                    <input class="form-control" type="text" value="" name="spouse_epnj_mukim" value="{{ old('spouse_epnj_mukim', '') }}"
                                    data-parsley-errors-container="#parsley-errors"
                                    data-parsley-required-message="Sila isi mukim rumah pinjaman kerajaan">
                                </div>
                            </div>
                            <div class="row mb-2">
                                <label for="name" class="col-md-2 col-form-label">Jarak Daripada Pejabat (KM)</label>
                                <div class="col-md-10">
                                    <input class="form-control" type="text" value="" id="epnj_distance" name="spouse_epnj_distance" readonly>
                                </div>
                            </div>

                        </div>
                    </section>

                <!-- Ulasan -->
                <h3>Ulasan</h3>
                    <section>
                        <div>

                                <div class="row mb-2">
                                    <label for="name" class="col-md-2 col-form-label">Ulasan Tambahan<span class="text-danger">*</span></label>
                                    <div class="col-md-10">
                                        <textarea id="message" class="form-control" placeholder="Ulasan disini" rows="4" name="review"
                                        required
                                        data-parsley-errors-container="#parsley-errors"
                                        data-parsley-required-message="Sila isi ulasan tambahan permohonan">{{ old('review', '') }}</textarea>
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <label for="name" class="col-md-2 col-form-label">Surat Sokongan Ulasan<span class="text-danger">*</span></label>
                                    <div class="col-md-10">
                                    <input type="file" class="form-control" name="review_document"
                                    required
                                    data-parsley-errors-container="#parsley-errors"
                                    data-parsley-required-message="Sila muatnaik surat sokongan ulasan">
                                        <p class="card-title-desc">* PDF, JPG, JPEG & PNG sahaja</p>
                                    </div>
                                </div>

                        </div>
                    </section>

                 <!-- Dokumen Sokongan -->
                 <h3>Dokumen Sokongan</h3>
                    <section>
                        <div>
                            @foreach($documentAll as $document)
                                <div class="row mb-2">
                                    <label for="name" class="col-md-3 col-form-label">{{ $document->document_name }}</label>
                                    <div class="col-md-9">
                                        <input type="file" class="form-control" name="document[{{$document->id}}]">
                                    </div>
                                </div>
                            @endforeach


                            <div class="row mb-2">
                                <div class="col-md-9 offset-md-3">
                                    <p class="card-title-desc">* PDF, JPG, JPEG & PNG sahaja</p>
                                </div>
                            </div>

                            <div class="row mb-2">
                                <div class="col-md-3">
                                </div>

                                <div class="col-md-9">
                                        <input type="hidden" name="disclaimer" value="0">
                                        <input type="checkbox" name="disclaimer" id="disclaimer" class="me-2"
                                            required=""
                                            data-parsley-errors-container="#parsley-errors"
                                            data-parsley-required-message="Sila klik pengakuan" >

                                        <p for="disclaimer">Bahawasanya saya mengaku maklumat yang diberikan di atas adalah sah dan benar. Sekiranya terdapat maklumat yang diberikan adalah palsu dan tidak benar, maka permohonan saya ini <strong>*DITOLAK/DIBATALKAN</strong> oleh Jawatankuasa Kuarters.</p>
                                        <div id="disclaimer-errors"></div>
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
<script src="{{ URL::asset('assets/js/pages/Greenlane/greenlane.js')}}"></script>
<script src="{{ URL::asset('assets/js/pages/Greenlane/autofillgreenlane.js')}}"></script>
<script src="{{ URL::asset('/assets/libs/parsleyjs/parsleyjs.min.js')}}"></script>
@endsection
