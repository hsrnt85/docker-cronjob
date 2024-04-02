@extends('layouts.master')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">

                {{-- PAGE TITLE --}}
                <div class="border-bottom border-primary mb-4"><h4 class="card-title">{{ getPageTitle(2) }}</h4></div>

                <form>
                    <div class="row">
                        <label for="name" class="col-md-2 col-form-label ">Kuarters</label>
                        <p class="col-md-9 col-form-label">{{ $tenant->quarters_category->name }}</p>
                    </div>

                    <div class="row">
                        <label for="name" class="col-md-2 col-form-label ">No. Unit</label>
                        <p class="col-md-9 col-form-label">{{ $tenant->quarters->unit_no }}</p>
                    </div>

                    <div class="row">
                        <label for="name" class="col-md-2 col-form-label ">Alamat</label>
                        <p class="col-md-9 col-form-label">{{ $tenant->quarters->address_1 }}, {{ $tenant->quarters->address_2 }}, {{ $tenant->quarters->address_3 }}</p>
                    </div>

                    <div class="row">
                        <label for="name" class="col-md-2 col-form-label ">Tarikh Tawaran</label>
                        <p class="col-md-9 col-form-label">{{ $tenant->quarters_offer_date->format('d/m/Y') }}</p>
                    </div>

                    <div class="row">
                        <label for="name" class="col-md-2 col-form-label ">Tarikh Terima</label>
                        <p class="col-md-9 col-form-label">{{ $tenant->quarters_acceptance_date->format('d/m/Y') }}</p>
                    </div>

                    <hr>

                    <h4 class="card-title text-primary mb-2">Maklumat Penghuni</h4>

                    <div class="row mt-2">
                        <label for="name" class="col-md-2 col-form-label ">Nama Penghuni</label>
                        <p class="col-md-9 col-form-label">{{ $tenant->name }}</p>
                    </div>

                    <div class="row">
                        <label for="name" class="col-md-2 col-form-label ">No Kad Pengenalan</label>
                        <p class="col-md-9 col-form-label">{{ $tenant->new_ic }}</p>
                    </div>

                    <div class="row">
                        <label for="name" class="col-md-2 col-form-label ">Status Perkahwinan</label>
                        <p class="col-md-9 col-form-label">{{ $tenant->marital_status }}</p>
                    </div>

                    <div class="row">
                        <label for="name" class="col-md-2 col-form-label ">No Telefon (Rumah)</label>
                        <p class="col-md-9 col-form-label">{{ $tenant->phone_no_home }}</p>
                    </div>

                    <div class="row">
                        <label for="name" class="col-md-2 col-form-label ">No Telefon (Bimbit)</label>
                        <p class="col-md-9 col-form-label">{{ $tenant->phone_no_hp }}</p>
                    </div>

                    <!-- <div class="row">
                        <label for="name" class="col-md-2 col-form-label ">Emel Peribadi</label>
                        <p class="col-md-9 col-form-label">{{ $tenant->email }}</p>
                    </div> -->

                    <div class="row">
                        <label for="name" class="col-md-2 col-form-label ">Nama Agensi Bertugas</label>
                        <p class="col-md-9 col-form-label">{{ $tenant->organization_name }}</p>
                    </div>

                    <div class="row">
                        <label for="name" class="col-md-2 col-form-label ">Jawatan</label>
                        <p class="col-md-9 col-form-label">{{ $tenant->position }}</p>
                    </div>

                    <!-- <div class="row">
                        <label for="name" class="col-md-2 col-form-label ">Alamat Jabatan/Agensi Pembayar Gaji</label>
                        <p class="col-md-9 col-form-label">{{ $tenant->position }}</p>
                    </div> -->

                    <div class="row">
                        <label for="name" class="col-md-2 col-form-label ">Gred Jawatan</label>
                        <p class="col-md-9 col-form-label">{{ $tenant->position_grade }}</p>
                    </div>

                    <div class="row">
                        <label for="name" class="col-md-2 col-form-label ">Taraf Jawatan</label>
                        <p class="col-md-9 col-form-label">{{ $tenant->position_type }}</p>
                    </div>

                    <!-- <div class="row">
                        <label for="name" class="col-md-2 col-form-label ">No Gaji</label>
                        <p class="col-md-9 col-form-label">{{ $tenant->position_type }}</p>
                    </div> -->

                    <hr>

                    <h4 class="card-title text-primary">Dokumen</h4>

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

                    @if(in_array($tenant->leave_status_id , [2,3]) ) 

                        <hr><h4 class="card-title text-primary mb-2">Maklumat Permohonan Keluar Kuarters</h4>

                        <div class="row mt-2">
                            <label for="name" class="col-md-2 col-form-label ">Tarikh mengosongkan kuarters</label>
                            <div class="col-md-9">
                                <p class="col-form-label">{{convertDateSys($tenant->leave_date ?? '')}}</p>
                            </div>
                        </div>

                        <div class="row">
                            <label for="name" class="col-md-2 col-form-label ">Alamat Selepas Keluar Dari Kuarters Kerajaan</label>
                            <div class="col-md-9">
                                <p class="col-form-label">{{upperText($tenant->other_address_1 ?? '')}} {{upperText($tenant->other_address_2 ?? '')}} {{upperText($tenant->other_address_3 ?? '')}}</p>
                            </div>
                        </div>

                        <div class="row">
                            <label for="name" class="col-md-2 col-form-label ">Daerah</label>
                            <div class="col-md-9">
                                <p class="col-form-label">{{$tenant->district->district_name}}</p>
                            </div>
                        </div>

                        <div class="row">
                            <label for="name" class="col-md-2 col-form-label ">Poskod</label>
                            <div class="col-md-9">
                                <p class="col-form-label">{{$tenant->other_postcode}}</p>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="name" class="col-md-2 col-form-label ">Jenis Kediaman Semasa</label>
                            <div class="col-md-9">
                                <p class="col-form-label">{{$tenant->residence_type->residence_type}}</p>
                            </div>
                        </div>

                        <hr><h4 class="card-title text-primary">Maklumat Sebab Keluar Kuarters</h4>

                        <div class="table-responsive col-sm-9 offset-sm-2">
                            <table class="table table-sm table-bordered">
                                <thead class="text-white bg-primary">
                                    <tr>
                                        <th class="text-center">Bil</th>
                                        <th class="text-center">Sebab Keluar Kuarters</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if($leaveOptionIdAll->count() == 0)
                                        <tr class="text-center"><td colspan="4">Tiada rekod</td></tr>
                                    @else
                                        @foreach($leaveOptionIdAll as $leaveOptionId)
                                            <tr>
                                                <th scope="row" class="text-center">{{$loop->iteration}}</th>
                                                <td>
                                                    @if ($leaveOptionId->id == 5) {{$tenant->other_leave_reason}} <br> @else {{$leaveOptionId->description}}  @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </div>
                        @if($tenantsLeaveAttachment)
                        <div class="row">
                            <div class="col-md-2"></div>
                            <label for="name" class="col-md-3 col-form-label ">Salinan Dokumen</label>
                            <div class="col-md-3">
                                <a download="Dokumen Keluar Kuarters" target="_blank" href="{{ getCdn().'/' . $tenantsLeaveAttachment->document_path  }}"
                                    class="btn btn-outline-primary tooltip-icon" title="Dokumen Keluar Kuarters"><i class="{{ __('icon.view_file') }}"></i>
                                </a>
                                <br><br>
                            </div>
                        </div>
                        @endif

                        <hr><h4 class="card-title text-primary">Pengesahan Inventori</h4>

                        <div class="table-responsive col-sm-9 offset-sm-2">
                            <table class="table table-sm table-bordered">
                                <thead class="text-white bg-primary">
                                    <tr>
                                        <th class="text-center">Bil</th>
                                        <th class="text-center">Inventori</th>
                                        <th class="text-center">Kuantiti Masuk</th>
                                        <th class="text-center">Status Masuk</th>
                                        <th class="text-center">Catatan Masuk</th>
                                        <th class="text-center">Kuantiti Keluar</th>
                                        <th class="text-center">Status Keluar</th>
                                        <th class="text-center">Catatan Keluar</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if ($tenantsQuartersInventoryAll->count() == 0)
                                        <tr class="text-center">
                                            <td colspan="5">Tiada rekod</td>
                                        </tr>
                                    @else
                                        @foreach ($tenantsQuartersInventoryAll as $tenantsQuartersInventory)
                                            <tr>
                                                <td class="text-center">{{ $loop->iteration }}</td>
                                                <td class="text-center">{{ $tenantsQuartersInventory->inventory->name }}</td>
                                                <td class="text-center">{{ $tenantsQuartersInventory->quantity_in ?? '-' }}</td>
                                                <td class="text-center">{{ $tenantsQuartersInventory->inventory_status->inventory_status }}</td>
                                                <td class="text-center">{{ $tenantsQuartersInventory->remarks_in ?? '-' }}</td>
                                                <td class="text-center">{{ $tenantsQuartersInventory->quantity_out ?? '-' }}</td>
                                                <td class="text-center">{{ $tenantsQuartersInventory->inventory_status_out?->inventory_status }}</td>
                                                <td class="text-center">
                                                    {{ $tenantsQuartersInventory->remarks_out ?? '-'}}
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </div><br>

                        <hr><h4 class="card-title text-primary">Dokumen Sokongan</h4>
                        <div class="table-responsive col-sm-9 offset-sm-2">
                            <table class="table table-sm table-bordered" >
                                <thead class="text-white bg-primary">
                                    <tr>
                                        <th class="text-center">Bil</th>
                                        <th class="text-center">Nama Dokumen</th>
                                        <th class="text-center">Muat Turun</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($leaveOptionDocumentAll as $leaveOptionDocument)
                                        <tr>
                                            <td class="text-center">{{ $loop->iteration }}</td>
                                            <td class="align-left">{{ $leaveOptionDocument->description }}</td>
                                            <td class="text-center">
                                                <a download="{{ $leaveOptionDocument->description }}" target="_blank" href="{{ getCdn().'/' .$leaveOptionDocument->document_path }}"
                                                class="btn btn-outline-primary tooltip-icon" title="{{ $leaveOptionDocument->description }}"><i class="{{ __('icon.view_file') }}"></i></a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif

                    @if($tenant->blacklist_date)
                    <hr>
                    <h4 class="card-title text-primary mb-2">Maklumat Penghuni</h4>

                    <div class="row">
                        <label for="name" class="col-md-2 col-form-label ">Tarikh Hilang Kelayakan</label>
                        <p class="col-md-9 col-form-label">{{ convertDateSys($tenant->blacklist_date) }}</p>
                    </div>

                    <div class="row">
                        <label for="name" class="col-md-2 col-form-label ">Sebab Hilang Kelayakan</label>
                        <p class="col-md-9 col-form-label">{{ $tenant->reason?->blacklist_reason ??  upperText($tenant->blacklist_reason_others) }}</p>
                    </div>

                    @endif


                    <div class="row">
                        <div class="col-sm-10 offset-sm-1">
                            <a href="{{ route('tenant.tenantList', $category)  . '#' . $tab }}" class="btn btn-secondary float-end me-2">{{ __('button.kembali') }}</a>
                            <!-- <a href="{{ route('tenant.view', ['category' => $tenant->quarters_category_id, 'tenant' => $tenant->id]) }}" class="btn btn-secondary float-end me-2">{{ __('button.kembali') }}</a> -->
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div> <!-- end col -->
</div>
<!-- end row -->

@endsection
