@extends('layouts.master')

@section('title') @lang('translation.Dashboards') @endsection

@section('content')

@component('components.breadcrumb')
@slot('li_1') Dashboards @endslot
@slot('title') Kuarters @endslot
@endcomponent

@component('components.alert')@endcomponent

<div class="row">
    <div class="col-12">

    <div class="card">
        <div class="card-body">
            <h4 class="card-title mb-4">Vertical Wizard</h4>

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('application.store') }}" method="POST" >
                {{ csrf_field() }}

            <div id="vertical-permohonan" class="vertical-wizard">
                <!-- Kategori Kuarters -->
                <h3>Kategori Kuarters</h3>
                <section>
                    <form>
                        <div class="row">
                            <div class="col-lg-6">
                                <label class="form-label">Kategori</label>
                                @foreach($quarterCategoryAll as $quarterCategory)
                                    <div class="form-check mb-3 @error('quarter_category') is-invalid @enderror">
                                        <input class="form-check-input" type="radio" name="quarter_category" value="{{ $quarterCategory->id }}">
                                        <label class="form-check-label">
                                            {{ $quarterCategory->name}}
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
                    </form>
                </section>

                <!-- Maklumat Pemohon -->
                <h3>Maklumat Pemohon</h3>
                <section>
                    <form>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label>Nama Pemohon</label>
                                    <input type="text" class="form-control @error('application_name') is-invalid @enderror" name="application_name" value="{{ $user->name }}" disabled>
                                    @error('application_name')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label>No Kad Pengenalan</label>
                                    <input type="text" class="form-control @error('ic_no') is-invalid @enderror" name="ic_no" value="{{ $user->new_ic }}" disabled>
                                    @error('ic_no')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label >Jabatan/Agensi Bertugas</label>
                                    <input type="text" class="form-control @error('agency') is-invalid @enderror" name="agency" value="{{ $userOffice->organization }}" disabled>
                                    @error('agency')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label >Jawatan</label>
                                    <input type="text" class="form-control @error('position') is-invalid @enderror" name="position" value="{{ $user->position->position_name }}" disabled>
                                    @error('position')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label>Alamat Jabatan/Agensi Pembayar Gaji</label>
                                    <input type="text" class="form-control @error('agency_address') is-invalid @enderror" name="agency_address" value="{{ $userOffice->address_1 }} {{ $userOffice->address_2 }} {{ $userOffice->address_3 }}" disabled>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label>Gred Jawatan</label>
                                    <input type="text" class="form-control" name="agency_address" value="{{ $user->position_grade->grade_no }}" disabled>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="verticalnav-declaration-input">Taraf Jawatan</label>
                                    <input type="text" class="form-control"  value="{{ $user->position_type->grade_type }}" disabled>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <label for="verticalnav-declaration-input">Taraf Perkhidmatan</label>

                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="radio" name="level">
                                    <label class="form-check-label" for="formRadios1">
                                        Negeri
                                    </label>
                                </div>
                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="radio" name="level">
                                    <label class="form-check-label" for="formRadios1">
                                        Badan Berkanun
                                    </label>
                                </div>
                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="radio" name="level">
                                    <label class="form-check-label" for="formRadios1">
                                        Persekutuan
                                    </label>
                                </div>
                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="radio" name="level">
                                    <label class="form-check-label" for="formRadios1">
                                        Pihak Berkuasa Tempatan
                                    </label>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="verticalnav-declaration-input">Taraf Perkahwinan</label>
                                    <input type="text" class="form-control"  value="{{ $user->marital_status->marital_status }}" disabled>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="verticalnav-declaration-input">Tempoh Lantikan</label>
                                    <input type="text" class="form-control"  >
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="verticalnav-declaration-input">Tarikh Bersara</label>
                                    <input type="date" class="form-control"  >
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="verticalnav-declaration-input">Pendapatan Bulanan</label>
                                    <input type="date" class="form-control" >
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="verticalnav-declaration-input">Tarikh Dilantik Perkhidmatan</label>
                                    <input type="date" class="form-control">
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="verticalnav-declaration-input">No Telefon (Rumah)</label>
                                    <input type="number" class="form-control"  >
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="verticalnav-declaration-input">No Telefon (Bimbit)</label>
                                    <input type="number" class="form-control" value="{{ $user->phone_no_hp }}" disabled >
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="verticalnav-declaration-input">No Telefon (Pejabat)</label>
                                    <input type="number" class="form-control" value="{{ $userOffice->phone_no_office }}" disabled>
                                </div>
                            </div>
                        </div>
                    </form>
                </section>

                <!-- Maklumat Pasangan -->
                <h3>Maklumat Pasangan (Suami/Isteri)</h3>
                <section>
                    <div>
                        <form>
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label for="verticalnav-namecard-input">Nama Pasangan</label>
                                        <input type="text" class="form-control" value="{{ $userSpouse->spouse_name }}" disabled>
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label for="verticalnav-namecard-input">No Kad Pengenalan</label>
                                        <input type="text" class="form-control" value="{{ $userSpouse->new_ic }}" disabled >
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label>Bekerja(Ya/Tidak)</label>
                                        <div class="form-check mb-3">
                                            <input class="form-check-input" type="radio" name="bekerja" @if($userSpouse->department_name != null) checked @endif>
                                            <label class="form-check-label" for="formRadios1">
                                                Ya
                                            </label>
                                        </div>
                                        <div class="form-check mb-3">
                                            <input class="form-check-input" type="radio" name="bekerja" @if($userSpouse->department_name == null) checked @endif>
                                            <label class="form-check-label" for="formRadios1">
                                                Tidak
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label for="verticalnav-cardno-input">Jabatan/Tempat Bekerja</label>
                                        <input type="text" class="form-control" value="{{ $userSpouse->department_name }}" disabled>
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label for="verticalnav-card-verification-input">Jawatan</label>
                                        <input type="text" class="form-control" value="{{ $userSpouse->position_name }}" disabled>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label for="verticalnav-expiration-input">Gaji (RM)</label>
                                        <input type="text" class="form-control">
                                    </div>
                                </div>

                            </div>
                        </form>
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
                                            <th class="text-center"><a class="btn btn-primary btn-sm" id="tambah-anak" @if($user->is_hrmis) style="pointer-events: none; cursor: default;" @endif><i class="mdi mdi-plus mdi-18px"></i></a></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($userChildAll as $userChild)
                                            <tr>
                                                <td class="text-center">{{ $loop->iteration }}</td>
                                                <td class="text-center">{{ $userChild->child_name }} </td>
                                                <td class="text-center">{{ $userChild->new_ic }}</td>
                                                <td></td>
                                                <td class="text-center">-</td>
                                            </tr>
                                        @endforeach
                                        <tr>
                                            <td class="text-center">-</td>
                                            <td> <input type="text" class="form-control" ></td>
                                            <td> <input type="text" class="form-control"  ></td>
                                            <td> <input type="file" class="form-control"  ></td>
                                            <td class="text-center"></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Maklumat Kediaman Terkini -->
                <h3>Maklumat Kediaman Terkini</h3>
                <section>
                    <div>
                        <form>
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label for="verticalnav-namecard-input">Alamat Kediaman Sekarang</label>
                                        <input type="text" class="form-control" value="{{ $userHouse->last()->address_1 }} {{ $userHouse->last()->address_2 }} {{ $userHouse->last()->address_3 }}" disabled>
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label for="verticalnav-namecard-input">Sewa Sebulan</label>
                                        <input type="text" class="form-control" >
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label for="verticalnav-namecard-input">Latitude</label>
                                        <input type="text" class="form-control" >
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label for="verticalnav-cardno-input">Longitude</label>
                                        <input type="text" class="form-control" >
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label for="verticalnav-card-verification-input">Jarak Dari Pejabat (KM)</label>
                                        <input type="text" class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label for="verticalnav-expiration-input">Nama Tuan Rumah</label>
                                        <input type="text" class="form-control" >
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label for="verticalnav-expiration-input">Alamat Tuan Rumah</label>
                                        <input type="text" class="form-control" >
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label for="verticalnav-expiration-input">No. Tel Tuan Rumah</label>
                                        <input type="text" class="form-control">
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </section>

                <!-- Maklumat Pinjaman Perumahan -->
                <h3>Maklumat Pinjaman Perumahan</h3>
                <section>
                    <div>
                        <form>
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label for="verticalnav-namecard-input">Adakah kamu atau suami/isteri membeli rumah dengan kemudahan pinjaman perumahan kerajaan</label>
                                        <div class="form-check mb-3">
                                            <input class="form-check-input" type="radio" name="pinjaman">
                                            <label class="form-check-label" for="formRadios1">
                                                Ya
                                            </label>
                                        </div>
                                        <div class="form-check mb-3">
                                            <input class="form-check-input" type="radio" name="pinjaman">
                                            <label class="form-check-label" for="formRadios1">
                                                Tidak
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label for="verticalnav-namecard-input">Alamat perumahan dimiliki</label>
                                        <input type="text" class="form-control" id="verticalnav-namecard-input" >
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label for="verticalnav-namecard-input">Latitude</label>
                                        <input type="text" class="form-control" id="verticalnav-namecard-input" >
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label for="verticalnav-cardno-input">Longitude</label>
                                        <input type="text" class="form-control" id="verticalnav-cardno-input" >
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label for="verticalnav-card-verification-input">Jarak Dari Pejabat (KM)</label>
                                        <input type="text" class="form-control" id="verticalnav-card-verification-input">
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </section>

                <!-- Maklumat Gaji -->
                <h3>Maklumat Gaji</h3>
                <section>
                    <div>
                        <form>
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label for="verticalnav-namecard-input">Potongan gaji bayaran sewa</label>
                                        <div class="form-check mb-3">
                                            <input class="form-check-input" type="radio" name="pinjaman">
                                            <label class="form-check-label" for="formRadios1">
                                                Ya
                                            </label>
                                        </div>
                                        <div class="form-check mb-3">
                                            <input class="form-check-input" type="radio" name="pinjaman">
                                            <label class="form-check-label" for="formRadios1">
                                                Tidak
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </section>

                <!-- Ulasan -->
                <h3>Ulasan</h3>
                <section>
                    <div>
                        <form>
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label for="verticalnav-namecard-input">Ulasan</label>
                                        <div class="form-check mb-3">
                                            <textarea id="message" class="form-control" placeholder="Ulasan disini"></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label for="verticalnav-namecard-input">Surat Sokongan Ulasan</label>
                                        <input type="file" class="form-control">
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </section>

                <!-- Dokumen Sokongan -->
                <h3>Dokumen Sokongan</h3>
                <section>
                    <div>
                        <form>
                            <div class="row">
                                @foreach($documentAll as $document)
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="verticalnav-namecard-input">{{ $document->document_name }}</label>
                                            <input type="file" class="form-control">
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </form>
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
<script src="{{ URL::asset('assets/js/pages/Application/application.js')}}"></script>
@endsection