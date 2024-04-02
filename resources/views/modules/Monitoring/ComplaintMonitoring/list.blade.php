@extends('layouts.master')

@section('content')

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">

                {{-- PAGE TITLE --}}
                <div class="border-bottom border-primary mb-4"><h4 class="card-title">{{ getPageTitle(1) }}</h4></div>

                <!-- Nav tabs -->
                <ul class="nav nav-tabs" role="tablist">
                    <li class="nav-item" >
                        <a class="nav-link active" data-bs-toggle="tab" href="#baru" role="tab" >Pemantauan Aduan Baru</a>
                    </li>
                    <li class="nav-item" >
                        <a class="nav-link" data-bs-toggle="tab" href="#ditolak" role="tab" >Pemantauan Aduan Ditolak</a>
                    </li>
                    <li class="nav-item" >
                        <a class="nav-link" data-bs-toggle="tab" href="#berulang" role="tab" >Pemantauan Aduan Berulang</a>
                    </li>
                    <li class="nav-item" >
                        <a class="nav-link" data-bs-toggle="tab" href="#selenggara" role="tab" >Penyelenggaraan Aduan</a>
                    </li>
                    <li class="nav-item" >
                        <a class="nav-link" data-bs-toggle="tab" href="#selesai" role="tab" >Pemantauan Aduan Selesai</a>
                    </li>
                    <li class="nav-item" >
                        <a class="nav-link" data-bs-toggle="tab" href="#keluar" role="tab" >Pemantauan Keluar</a>
                    </li>
                </ul>
               <br>

                <div class="tab-content">
                    <div class="tab-pane active" id="baru" role="tabpanel">
                        <div id="datatable_wrapper">

                            <div class="row">
                                <div class="col-sm-12">
                                    <table id="datatable1" class="table table-striped table-bordered dt-responsive wrap w-100 dataTable" role="grid">

                                        <thead class="bg-primary bg-gradient text-white">
                                            <tr role="row">
                                                <th class="text-center" width="4%">Bil</th>
                                                <th class="text-center" width="15%">No. Aduan</th>
                                                <th class="text-center" width="18%">Nama Pengadu</th>
                                                <th class="text-center" width="10%">Tarikh Aduan</th>
                                                <th class="text-center" width="10%">Tarikh Temujanji</th>
                                                <th class="text-center" width="15%">Kategori Kuarters (Lokasi)</th>
                                                <th class="text-center" >Nama Pemantau</th>
                                                <th class="text-center" width="10%">Tindakan</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($senaraiPemantauanBaruAll as $bil => $pemantauanBaru)
                                                <tr>
                                                    <th class="text-center" width="4%" scope="row">{{ ++$bil }}</th>
                                                    <td class="text-center" width="15%">{{ upperText($pemantauanBaru ->ref_no) ??'' }}</td>
                                                    <td class="align-left" width="18%">{{ upperText($pemantauanBaru -> name) ??'' }}</a></td>
                                                    <td class="text-center" width="10%">{{ convertDateSys($pemantauanBaru -> complaint_date) ??'' }}</a></td>
                                                    <td class="text-center" width="10%">{{ convertDateSys($pemantauanBaru->appointment_date) ?? '-' }}</td>
                                                    <td class="align-left" width="15%">{{ $pemantauanBaru -> quarters_name ??'' }}</a></td>
                                                    <td class="align-left" >{{ upperText($pemantauanBaru->officer_Api->name)??'' }}</a></td>
                                                    <td class="text-center" width="10%">
                                                        <div class="btn-group" role="group">
                                                            @if(checkPolicy("U"))
                                                                <a href="{{ route('complaintMonitoring.edit', ['id' => $pemantauanBaru->complaint_ids]) }}" class="btn btn-outline-primary px-2 py-1 tooltip-icon">
                                                                    <span class="tooltip-text">{{ __('button.kemaskini') }}</span><i class="{{ __('icon.edit') }}"></i>
                                                                </a>
                                                            @endif
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane" id="ditolak" role="tabpanel">
                        <div id="datatable_wrapper">
                            <div class="row">
                                <div class="col-sm-12">
                                <table id="datatable2" class="table table-striped table-bordered dt-responsive wrap w-100 dataTable" role="grid">
                                        <thead class="bg-primary bg-gradient text-white">
                                            <tr role="row">
                                                <th class="text-center" width="4%">Bil</th>
                                                <th class="text-center" width="16%">No. Aduan</th>
                                                <th class="text-center" width="20%">Nama Pengadu</th>
                                                <th class="text-center" width="10%">Tarikh Aduan</th>
                                                <th class="text-center" width="20%">Kategori Kuarters (Lokasi)</th>
                                                <th class="text-center" width="20%">Nama Pemantau</th>
                                                <th class="text-center" width="10%">Tindakan</th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                        @foreach($senaraiPemantauanDitolakAll as $bil => $pemantauanDitolak)
                                            <tr>
                                                <th class="text-center" width="4%" scope="row">{{ ++$bil }}</th>
                                                <td class="text-center" width="16%">{{ upperText($pemantauanDitolak->ref_no) ??'' }}</td>
                                                <td class="align-left" width="20%">{{ $pemantauanDitolak ->name ??'' }}</a></td>
                                                <td class="text-center" width="10%">{{ convertDateSys($pemantauanDitolak ->complaint_date) ??'' }}</a></td>
                                                <td class="align-left" width="20%">{{ $pemantauanDitolak ->quarters_name ??'' }}</a></td>
                                                <td class="align-left" width="20%">{{ upperText($pemantauanDitolak->officer_Api->name)??'' }}</a></td>
                                                <td class="text-center" width="10%">
                                                    <div class="btn-group" role="group">
                                                        @if(checkPolicy("V"))
                                                            <a href="{{ route('complaintMonitoring.view_aduan_ditolak', ['id' => $pemantauanDitolak->complaint_ids]) }}" class="btn btn-outline-primary px-2 py-1 tooltip-icon">
                                                                <span class="tooltip-text">{{ __('button.papar') }}</span><i class="{{ __('icon.view_folder') }}"></i>
                                                            </a>
                                                        @endif
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane" id="selesai" role="tabpanel">
                        <div id="datatable_wrapper">
                            <div class="row">
                                <div class="col-sm-12">
                                    <table id="datatable3" class="table table-striped table-bordered dt-responsive wrap w-100 dataTable" role="grid">
                                        <thead class="bg-primary bg-gradient text-white">
                                            <tr role="row">
                                                <th class="text-center" width="4%">Bil</th>
                                                <th class="text-center" width="16%">No. Aduan</th>
                                                <th class="text-center" width="20%">Nama Pengadu</th>
                                                <th class="text-center" width="10%">Tarikh Aduan</th>
                                                <th class="text-center" width="20%">Kategori Kuarters (Lokasi)</th>
                                                <th class="text-center" width="20%">Nama Pemantau</th>
                                                <th class="text-center" width="10%">Tindakan</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($senaraiPemantauanSelesaiAll as $bil => $pemantauanSelesai)
                                            <tr>
                                                <th class="text-center" width="4%" scope="row">{{ ++$bil }}</th>
                                                <td class="text-center" width="16%">{{ upperText($pemantauanSelesai ->ref_no) ??'' }}</td>
                                                <td class="align-left" width="20%">{{ $pemantauanSelesai-> name ??'' }}</a></td>
                                                <td class="text-center" width="10%">{{ convertDateSys($pemantauanSelesai -> complaint_date) ??'' }}</a></td>
                                                <td class="align-left" width="20%">{{ $pemantauanSelesai -> quarters_name ??'' }}</a></td>
                                                <td class="align-left" width="20%">{{ upperText($pemantauanSelesai->officer_Api->name)??'' }}</a></td>
                                                <td class="text-center" width="10%">
                                                    <div class="btn-group" role="group">
                                                        @if(checkPolicy("V"))
                                                            <a href="{{ route('complaintMonitoring.view_aduan_selesai', ['id' => $pemantauanSelesai->complaint_ids]) }}" class="btn btn-outline-primary px-2 py-1 tooltip-icon">
                                                                <span class="tooltip-text">{{ __('button.papar') }}</span><i class="{{ __('icon.view_folder') }}"></i>
                                                            </a>
                                                        @endif
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane" id="berulang" role="tabpanel">
                        <div id="datatable_wrapper">
                            <div class="row">
                                <div class="col-sm-12">
                                    <table id="datatable4" class="table table-striped table-bordered dt-responsive wrap w-100 dataTable" role="grid">
                                        <thead class="bg-primary bg-gradient text-white">
                                            <tr role="row">
                                                <th class="text-center" width="5%">Bil</th>
                                                <th class="text-center" width="10">No. Aduan</th>
                                                <th class="text-center" width="15%">Nama Pengadu</th>
                                                <th class="text-center" width="10%">Tarikh Aduan</th>
                                                <th class="text-center" width="20%">Kategori Kuarters (Lokasi)</th>
                                                <th class="text-center" width="15%">Status Pemantauan</th>
                                                <th class="text-center" width="15%">Nama Pemantau</th>
                                                <th class="text-center" width="10%">Tindakan</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($senaraiPemantauanBerulangAll as $bil => $pemantauanBerulang)
                                            <tr>
                                                <th class="text-center" width="5%" scope="row">{{ ++$bil }}</th>
                                                <td class="text-center" width="10%">{{ upperText($pemantauanBerulang ->ref_no) ??'' }}</td>
                                                <td class="align-left" width="15%">{{ $pemantauanBerulang -> name ??'' }}</a></td>
                                                <td class="text-center" width="10%">{{ convertDateSys($pemantauanBerulang -> complaint_date) ??'' }}</a></td>
                                                <td class="align-left" width="20%">{{ $pemantauanBerulang -> quarters_name ??'' }}</a></td>
                                                <td class="text-center" width="15%">{{ $pemantauanBerulang->monitoring_status }}</a></td>
                                                <td class="align-left" width="15%">{{ upperText($pemantauanBerulang->officer_Api->name)??'' }}</a></td>
                                                <td class="text-center" width="10%">
                                                    <div class="btn-group" role="group">
                                                        @if(checkPolicy("U"))
                                                            <a href="{{ route('complaintMonitoring.view_aduan_berulang', ['id' => $pemantauanBerulang->complaint_ids]) }}" class="btn btn-outline-primary px-2 py-1 tooltip-icon">
                                                                <span class="tooltip-text">{{ __('button.kemaskini') }}</span><i class="{{ __('icon.edit') }}"></i>
                                                            </a>
                                                        @endif
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane" id="selenggara" role="tabpanel">
                        <div id="datatable_wrapper">
                            <div class="row">
                                <div class="col-sm-12">
                                    <table id="datatable5" class="table table-striped table-bordered dt-responsive wrap w-100 dataTable" role="grid">
                                        <thead class="bg-primary bg-gradient text-white">
                                            <tr role="row">
                                                <th class="text-center" width="4%">Bil</th>
                                                <th class="text-center" width="16%">No. Aduan</th>
                                                <th class="text-center" width="20%">Nama Pengadu</th>
                                                <th class="text-center" width="10%">Tarikh Aduan</th>
                                                <th class="text-center" width="20%">Kategori Kuarters (Lokasi)</th>
                                                <th class="text-center" width="20%">Nama Pemantau</th>
                                                <th class="text-center" width="10%">Tindakan</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($senaraiPemantauanSelenggaraAll as $bil => $pemantauanSelenggara)
                                            <tr>
                                                <th class="text-center" width="4%" scope="row">{{ ++$bil }}</th>
                                                <td class="text-center" width="16%">{{ upperText($pemantauanSelenggara ->ref_no) ??'' }}</td>
                                                <td class="align-left" width="20%">{{ $pemantauanSelenggara -> name ??'' }}</a></td>
                                                <td class="text-center" width="10%">{{ convertDateSys($pemantauanSelenggara -> complaint_date) ??'' }}</a></td>
                                                <td class="align-left" width="20%">{{ $pemantauanSelenggara -> quarters_name ??'' }}</a></td>
                                                <td class="align-left" width="20%">{{ upperText($pemantauanSelenggara->officer_Api->name)??'' }}</a></td>
                                                <td class="text-center" width="10%">
                                                    <div class="btn-group" role="group">
                                                        @if(checkPolicy("V"))
                                                        <a href="{{ route('complaintMonitoring.view_aduan_selenggara', ['id' => $pemantauanSelenggara->complaint_ids]) }}" class="btn btn-outline-primary px-2 py-1 tooltip-icon">
                                                            <span class="tooltip-text">{{ __('button.papar') }}</span><i class="{{ __('icon.view_folder') }}"></i>
                                                        </a>
                                                        @endif
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane" id="keluar" role="tabpanel">
                        <div id="datatable_wrapper">
                            <div class="row">
                                <div class="col-sm-12">
                                    <table id="datatable" class="table table-striped table-bordered dt-responsive wrap w-100 dataTable" role="grid">
                                        <thead class="bg-primary bg-gradient text-white">
                                            <tr role="row">
                                                <th class="text-center" width="4%">Bil</th>
                                                <th class="text-center" width="30%">Nama Penghuni</th>
                                                <th class="text-center" width="16%">No. Kad Pengenalan</th>
                                                <th class="text-center" width="20%">Kategori Kuarters (Lokasi)</th>
                                                <th class="text-center" width="10%">Tarikh Permohonan</th>
                                                <th class="text-center" width="10%">Tarikh Keluar</th>
                                                <th class="text-center" width="10%">Tindakan</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($senaraiPemantauanKeluarAll as $bil => $tenant)
                                            <tr>
                                                <th class="text-center" width="4%" scope="row">{{ ++$bil }}</th>
                                                <td class="align-left" width="20%">{{ $tenant->name }}</td>
                                                <td class="text-center" width="16%">{{ $tenant->new_ic }}</td>
                                                <td class="align-left" width="20%">{{ $tenant->quarters_category->name }}</td>
                                                <td class="text-center" width="10%">{{ convertDateSys($tenant->action_on)  }}</td>
                                                <td class="text-center" width="10%">{{ convertDateSys($tenant->leave_date)  }}</td>
                                                {{-- <td class="text-center" width="20%">{{(upperText($tenant->monitor_leave?->monitoring_officer?->user?->name)) ?? '-'}}</a></td> --}}
                                                <td class="text-center" width="10%">
                                                    <div class="btn-group" role="group">
                                                        @if($tenant->leave_status_id == 1 && checkPolicy("U"))
                                                            <a href="{{ route('complaintMonitoring.view_penghuni_keluar', ['tenant' => $tenant->id]) }}" class="btn btn-outline-primary px-2 py-1 tooltip-icon">
                                                            <span class="tooltip-text">{{ __('button.kemaskini') }}</span><i class="{{ __('icon.edit') }}"></i>
                                                            </a>
                                                        @elseif($tenant->leave_status_id == 2 && checkPolicy("V"))
                                                            <a href="{{ route('complaintMonitoring.view_penghuni_keluar', ['tenant' => $tenant->id]) }}" class="btn btn-outline-primary px-2 py-1 tooltip-icon">
                                                            <span class="tooltip-text">{{ __('button.papar') }}</span><i class="{{ __('icon.view_folder') }}"></i></a>
                                                        @endif
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> <!-- end col -->
</div>
<!-- end row -->

@endsection

@section('script-bottom')
<script src="{{ URL::asset('assets/js/pages/ComplaintMonitoring/complaintMonitoring.js')}}"></script>
@endsection
