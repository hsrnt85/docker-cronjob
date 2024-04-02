
<div class="row mt-3">

    <h4 class="card-title text-primary">Maklumat Terperinci Pemohon</h4>
    {{ csrf_field() }}
    {{-- <input type="hidden" name="id" value="{{ $application->id }}"> --}}

    <div class="row">
        <label class="col-md-2 col-form-label">Daerah</label>
        <div class="col-md-9">
            <p class="col-form-label">{{ $application->category?->district?->district_name }}</p>
        </div>
    </div>

    <div class="row">
        <label for="name" class="col-md-2 col-form-label ">No Kad Pengenalan</label>
        <div class="col-md-9">
            <p class="col-form-label">{{ $application->user->new_ic }}</p>
        </div>
    </div>

    <div class="row">
        <label for="name" class="col-md-2 col-form-label ">Status Perkahwinan</label>
        <div class="col-sm-3">
            <p class="col-form-label">{{ ($userInfo) ? $userInfo?->marital_status?->marital_status : $user?->marital_status?->marital_status }}</p>
        </div>
    </div>

    <div class="row">
        <label for="name" class="col-md-2 col-form-label ">No Telefon (Rumah)</label>
        <div class="col-sm-3">
            <p class="col-form-label">{{ $user?->phone_no_home }}</p>
        </div>
    </div>

    <div class="row">
        <label for="name" class="col-md-2 col-form-label ">No Telefon (Bimbit)</label>
        <div class="col-sm-3">
            <p class="col-form-label">{{ $user?->phone_no_hp }}</p>
        </div>
    </div>

    <div class="row">
        <label for="name" class="col-md-2 col-form-label ">Emel Peribadi</label>
        <div class="col-sm-3">
            <p class="col-form-label">{{ $user?->email }}</p>
        </div>
    </div>

    <div class="row">
        <label for="name" class="col-md-2 col-form-label ">Nama Agensi Bertugas</label>
        <div class="col-md-9">
            <p class="col-form-label">{{ $userOffice?->organization?->name}}</p>
        </div>
    </div>

    <div class="row">
        <label for="name" class="col-md-2 col-form-label ">Jawatan</label>
        <div class="col-md-9">
            <p class="col-form-label">{{ ($userInfo) ? $userInfo?->position?->position_name : $user?->position?->position_name }}</p>
        </div>
    </div>

    <div class="row">
        <label for="name" class="col-md-2 col-form-label ">Alamat Jabatan/Agensi Pembayar Gaji</label>
        <div class="col-sm-3">
            <p class="col-form-label">{{ $userOffice?->address_1 }}</p>
        </div>
    </div>

    <div class="row">
        <label for="name" class="col-md-2 col-form-label ">Gred Jawatan</label>
        <div class="col-sm-3">
            <p class="col-form-label">{{ ($userInfo) ? $userInfo?->position_grade?->grade_no : $user?->position_grade?->grade_no }}</p>
        </div>
    </div>

    <div class="row">
        <label for="name" class="col-md-2 col-form-label ">Taraf Jawatan</label>
        <div class="col-sm-3">
            <p class="col-form-label">{{ ($userInfo) ? $userInfo?->position_type?->position_type : $user?->position_type?->position_type }}</p>
        </div>
    </div>

    <div class="row">
        <label for="name" class="col-md-2 col-form-label ">No Gaji</label>
        <div class="col-sm-3">
            <p class="col-form-label">{{ $userSalary?->no_gaji }}</p>
        </div>
    </div>

    <div class="row">
        <label for="name" class="col-md-2 col-form-label ">Gaji Pokok</label>
        <div class="col-sm-3">
            <p class="col-form-label">RM {{ $userSalary?->basic_salary }}</p>
        </div>
    </div>

    <div class="row">
        <label for="name" class="col-md-2 col-form-label ">Imbuhan Tetap Perumahan</label>
        <div class="col-sm-3">
            <p class="col-form-label">RM {{ $userSalary?->itp }}</p>
        </div>
    </div>

    <div class="row">
        <label for="name" class="col-md-2 col-form-label ">Bantuan Sara Hidup</label>
        <div class="col-sm-3">
            <p class="col-form-label">RM {{ $userSalary?->bsh }}</p>
        </div>
    </div>

    <div class="row">
        <label for="name" class="col-md-2 col-form-label ">Tarikh Mula Berkhidmat</label>
        <div class="col-sm-3">
            <p class="col-form-label">{{ $user?->date_of_service?->format('d/m/Y') }}</p>
        </div>
    </div>

    <div class="row">
        <label for="name" class="col-md-2 col-form-label ">No Telefon (Pejabat)</label>
        <div class="col-sm-3">
            <p class="col-form-label">{{ $userOffice?->phone_no_office }}</p>
        </div>
    </div>

    <div class="row">
        <label for="name" class="col-md-2 col-form-label ">No Fax (Pejabat)</label>
        <div class="col-sm-3">
            <p class="col-form-label">{{ $userOffice?->fax_no_office }}</p>
        </div>
    </div>

    <div class="row">
        <label for="name" class="col-md-2 col-form-label ">Emel Jabatan/Syarikat</label>
        <div class="col-sm-3">
            <p class="col-form-label">{{ $userOffice?->email_office }}</p>
        </div>
    </div>


    <hr>

    <h4 class="card-title text-primary">Maklumat Pasangan</h4>

    <div class="row">
        <label for="name" class="col-md-2 col-form-label ">Nama Pasangan</label>
        <div class="col-sm-3">
            <p class="col-form-label">{{ $userSpouse?->spouse_name ?? '-'}}</p>
        </div>
    </div>

    <div class="row">
        <label for="name" class="col-md-2 col-form-label ">No Kad Pengenalan</label>
        <div class="col-sm-3">
            <p class="col-form-label">{{ $userSpouse?->new_ic ?? '-' }}</p>
        </div>
    </div>

    <div class="row">
        <label for="name" class="col-md-2 col-form-label ">Bekerja(Ya/Tidak)</label>
        <div class="col-sm-3">
            <p class="col-form-label">{{ ($userSpouse) ? ($userSpouse?->is_work == 1) ? 'Ya' : 'Tidak' : '-'  }}</p>
        </div>
    </div>

    <div class="row">
        <label for="name" class="col-md-2 col-form-label ">Alamat Tempat Bekerja</label>
        <div class="col-sm-3">
            <p class="col-form-label">
                @if ($userSpouse?->is_work == 1)
                    {{ $userSpouse?->office_address_1 . ', ' . $userSpouse?->office_address_2 . ', ' . $userSpouse?->office_address_3 }}
                @else
                    -
                @endif
            </p>
        </div>
    </div>

    <div class="row">
        <label for="name" class="col-md-2 col-form-label ">Jawatan</label>
        <div class="col-sm-3">
            <p class="col-form-label">{{ ($userSpouse?->is_work == 1) ? $userSpouse?->position_name : '-' }}</p>
        </div>
    </div>

    <div class="row">
        <label for="name" class="col-md-2 col-form-label ">Gaji Pokok</label>
        <div class="col-sm-3">
            <p class="col-form-label">RM {{ ($userSpouse?->is_work == 1) ? $userSpouse?->salary : '-' }}</p>
        </div>
    </div>

    <div class="row">
        <label for="name" class="col-md-2 col-form-label ">No Telefon Bimbit</label>
        <div class="col-sm-3">
            <p class="col-form-label">{{ ($userSpouse) ? $userSpouse?->phone_no_hp : '-'}}</p>
        </div>
    </div>

    @if ($userSpouse)
        <div class="row">
            <label for="name" class="col-md-2 col-form-label ">Kad Pengenalan</label>
            <div class="col-sm-3 py-2">
                <a download="Kad Pengenalan Pasangan" target="_blank" href="{{ $cdn . $userSpouse?->ic_attachment }}"
                class="btn btn-outline-primary tooltip-icon" title="Kad Pengenalan Pasangan"><i class="{{ __('icon.view_file') }}"></i></a>
            </div>
        </div>
        <div class="row">
            <label for="name" class="col-md-2 col-form-label ">Sijil Nikah</label>
            <div class="col-sm-3 py-2">
                <a download="Sijil Nikah" target="_blank" href="{{ $cdn . $userSpouse?->marriage_cert_attachment }}"
                class="btn btn-outline-primary tooltip-icon" title="Sijil Nikah"><i class="{{ __('icon.view_file') }}"></i></a>
            </div>
        </div>
    @endif

    @if ($userSpouse?->is_work == 1)
        <div class="row">
            <label for="name" class="col-md-2 col-form-label ">Slip Gaji</label>
            <div class="col-sm-3 py-2">
                <a download="Slip Gaji" target="_blank" href="{{ $cdn . $userSpouse?->salary_slip_attachment }}"
                class="btn btn-outline-primary tooltip-icon" title="Slip Gaji"><i class="{{ __('icon.view_file') }}"></i></a>
            </div>
        </div>
    @endif

    <hr>

    <h4 class="card-title text-primary">Maklumat Anak</h4>

    <div class="table-responsive col-sm-9 offset-sm-2">
        <table class="table table-sm table-bordered" id="table-anak-list">
            <thead class="text-white bg-primary">
                <tr>
                    <th class="text-center">Bil</th>
                    <th class="text-center">Nama Anak</th>
                    <th class="text-center">No Kad Pengenalan/Surat Beranak</th>
                    <th class="text-center">Salinan Kad Pengenalan/Surat Beranak</th>
                    <th class="text-center">OKU</th>
                </tr>
            </thead>
            <tbody>
                @if ($userChildAll->count() > 0)
                    @foreach($userChildAll as $userChild)
                        <tr>
                            <td class="text-center">{{ $loop->iteration }}</td>
                            <td class="text-center">{{ $userChild->child_name }} </td>
                            <td class="text-center">{{ $userChild->new_ic }}</td>
                            <td class="text-center">
                                @if ($userChildAttachmentAll->where('users_child_id', $userChild->id)->first())
                                    <a download="{{ $userChild->child_name }}" target="_blank" href="{{ $cdn . $userChildAttachmentAll->where('users_child_id', $userChild->id)->first()->path_document }}"
                                    class="btn btn-outline-primary tooltip-icon" title="{{ $userChild->child_name }}"><i class="{{ __('icon.view_file') }}"></i></a>
                                @else
                                    -
                                @endif
                            </td>
                            <td class="text-center">{{ ($userChild->is_cacat) ? 'YA' : 'TIDAK' }}</td>
                        </tr>
                    @endforeach
                @else
                    <tr>
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

    <hr>

    <h4 class="card-title text-primary">Maklumat Kediaman Terkini</h4>

    <div class="row">
        <label for="name" class="col-md-2 col-form-label ">Alamat Kediaman Sekarang</label>
        <div class="col-sm-3">
            <p class="col-form-label">{{ $userHouse?->last()?->address_1 ?? ''}} {{ $userHouse?->last()?->address_2 ?? '' }} {{ $userHouse?->last()?->address_3 ?? '' }}</p>
        </div>
    </div>

    <div class="row">
        <label for="name" class="col-md-2 col-form-label ">Sewa</label>
        <div class="col-sm-3">
            <p class="col-form-label">RM {{ $application->rental_fee }}</p>
        </div>
    </div>

    <div class="row">
        <label for="name" class="col-md-2 col-form-label ">Jarak dari pejabat</label>
        <div class="col-sm-3">
            <p class="col-form-label">{{ $userHouse?->last()?->distance }} km</p>
        </div>
    </div>

    <hr>

    <h4 class="card-title text-primary">Maklumat Pinjaman</h4>

    <div class="row">
        <label for="name" class="col-md-2 col-form-label ">Pinjaman Pemohon</label>
        <div class="col-sm-3">
            <p class="col-form-label">@if($userEpnj) Ada @else Tiada @endif</p>
        </div>
    </div>

    @if($userEpnj)
        <div class="row">
            <label for="name" class="col-md-2 col-form-label ">Alamat Perumahan Dimiliki Pemohon</label>
            <div class="col-sm-3">
                <p class="col-form-label">{{ $userEpnj->address_1 }} {{ $userEpnj->address_2 }} {{ $userEpnj->address_3 }}</p>
            </div>
        </div>

        <div class="row">
            <label for="name" class="col-md-2 col-form-label ">Poskod</label>
            <div class="col-sm-3">
                <p class="col-form-label">{{ $userEpnj->postcode }}</p>
            </div>
        </div>

        <div class="row mb-3">
            <label for="name" class="col-md-2 col-form-label ">Mukim</label>
            <div class="col-sm-3">
                <p class="col-form-label">{{ $userEpnj->mukim }} </p>
            </div>
        </div>

        <div class="row mb-3">
            <label for="name" class="col-md-2 col-form-label ">Jarak dari pejabat</label>
            <div class="col-sm-3">
                <p class="col-form-label">{{ number_format($userEpnj->distance, 2) }} km</p>
            </div>
        </div>
    @endif

    <div class="row">
        <label for="name" class="col-md-2 col-form-label ">Pinjaman Pasangan</label>
        <div class="col-sm-3">
            <p class="col-form-label">@if($userSpouseEpnj) Ada @else Tiada @endif</p>
        </div>
    </div>

    @if($userSpouseEpnj)
        <div class="row">
            <label for="name" class="col-md-2 col-form-label ">Alamat Perumahan Dimiliki Pemohon</label>
            <div class="col-sm-3">
                <p class="col-form-label">{{ $userSpouseEpnj->address_1 }} {{ $userSpouseEpnj->address_2 }} {{ $userSpouseEpnj->address_3 }}</p>
            </div>
        </div>

        <div class="row">
            <label for="name" class="col-md-2 col-form-label ">Poskod</label>
            <div class="col-sm-3">
                <p class="col-form-label">{{ $userSpouseEpnj->postcode }}</p>
            </div>
        </div>

        <div class="row mb-3">
            <label for="name" class="col-md-2 col-form-label ">Mukim</label>
            <div class="col-sm-3">
                <p class="col-form-label">{{ $userSpouseEpnj->mukim }} </p>
            </div>
        </div>

        <div class="row mb-3">
            <label for="name" class="col-md-2 col-form-label ">Jarak dari pejabat</label>
            <div class="col-sm-3">
                <p class="col-form-label">{{ number_format($userSpouseEpnj->distance, 2) }} km</p>
            </div>
        </div>
    @endif

    <hr>

    <h4 class="card-title text-primary">Ulasan</h4>

    <div class="row">
        <label for="name" class="col-md-2 col-form-label ">Ulasan Pemohon</label>
        <div class="col-sm-3">
            <p class="col-form-label">{{ $application->review }}</p>
        </div>
    </div>

    <div class="row">
        <label for="name" class="col-md-2 col-form-label ">Dokumen Sokongan</label>
        <div class="col-sm-3 py-2">
            @if($application->review_attachment != null)
                <a download="Dokumen Sokongan" target="_blank" href="{{ $cdn . $application->review_attachment }}"
                class="btn btn-outline-primary tooltip-icon" title="Dokumen Sokongan "><i class="{{ __('icon.view_file') }}"></i></a>
            @else
                -
            @endif
        </div>
    </div>
    <hr>

    <h4 class="card-title text-primary">Dokumen Sokongan</h4>

    <div class="table-responsive col-sm-9 offset-sm-2">
        <table class="table table-sm table-bordered" id="table-anak-list">
            <thead class="text-white bg-primary">
                <tr>
                    <th class="text-center">Bil</th>
                    <th class="text-center">Dokumen</th>
                    <th class="text-center">Muat Turun</th>
                </tr>
            </thead>
            <tbody>
                @foreach($applicationAttachmentAll as $applicationAttachment)
                    <tr>
                        <td class="text-center">{{ $loop->iteration }}</td>
                        <td class="text-center">{{ $applicationAttachment->document->document_name }}</td>
                        <td class="text-center">
                            <a download="{{ $applicationAttachment->document->document_name }}" target="_blank" href="{{ $cdn . $applicationAttachment->path_document }}"
                            class="btn btn-outline-primary tooltip-icon" title="{{ $applicationAttachment->document->document_name }}"><i class="{{ __('icon.view_file') }}"></i></a></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>


</div>
<!-- end row -->


