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
                        <a class="nav-link active" data-bs-toggle="tab" href="#baru" role="tab">Temujanji Baru</a>
                    </li>
                    <li class="nav-item" >
                        <a class="nav-link" data-bs-toggle="tab" href="#pengesahan" role="tab">Pengesahan Temujanji</a>
                    </li>
                    <li class="nav-item" >
                        <a class="nav-link" data-bs-toggle="tab" href="#senarai" role="tab">Senarai Temujanji</a>
                    </li>
                    <li class="nav-item" >
                        <a class="nav-link" data-bs-toggle="tab" href="#batal" role="tab">Temujanji Dibatalkan</a>
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
                                            <tr role="row" >
                                                <th class="text-center" width="5%">Bil.</th>
                                                <th class="text-center" width="10%">No. Aduan</th>
                                                <th class="text-center" width="20%">Nama Pengadu</th>
                                                <th class="text-center" width="10%">Tarikh Aduan</th>
                                                <th class="text-center" width="25%">Kategori Kuarters (Lokasi)</th>
                                                <th class="text-center" width="15%">Status Aduan</th>
                                                <th class="text-center" width="15%">Tindakan</th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            @foreach($senaraiAduanAll as $bil => $senaraiAduan)
                                                <tr >
                                                    <th scope="row" class="text-center">{{ ++$bil }}</th>
                                                    <td class="text-center">{{ $senaraiAduan->ref_no ?? '' }}</td>
                                                    <td class="align-left">{{ $senaraiAduan -> user -> name ??'' }}</a></td>
                                                    <td class="text-center">{{ $senaraiAduan->complaint_date?->format("d/m/Y") ?? '' }}</a></td>
                                                    <td class="align-left">{{ $senaraiAduan-> quarters_name ??'' }}</a></td>
                                                    <td class="text-center">{{ $senaraiAduan -> status -> complaint_status ??'' }}</a></td>
                                                    <td class="text-center">
                                                        <div class="btn-group" role="group">
                                                            @if(checkPolicy("U"))
                                                                <a href="{{ route('complaintAppointment.create', ['id' => $senaraiAduan->id]) }}" class="btn btn-outline-primary px-2 py-1 tooltip-icon">
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
                    <div class="tab-pane" id="pengesahan" role="tabpanel">
                        <div id="datatable_wrapper">
                            <div class="row">
                                <div class="col-sm-12">
                                    <table id="datatable2" class="table table-striped  table-bordered dt-responsive wrap w-100 dataTable" role="grid">
                                        <thead class="bg-primary bg-gradient text-white">
                                            <tr role="row">
                                                <th class="text-center">Bil.</th>
                                                <th class="text-center">No. Aduan</th>
                                                <th class="text-center">Nama Pengadu</th>
                                                <th class="text-center">Tarikh Aduan</th>
                                                <th class="text-center" >Kategori Kuarters (Lokasi)</th>
                                                <th class="text-center">Status Aduan</th>
                                                <th class="text-center">Tindakan</th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            @foreach($pengesahanTemujanjiAll as $bil => $pengesahanTemujanji)
                                                <tr class="text-center">
                                                    <th width="5%" scope="row" class="text-center">{{ ++$bil }}</th>
                                                    <td width="10%" class="text-center">{{ $pengesahanTemujanji->complaint->ref_no ?? null }}</td>
                                                    <td width="20%" class="align-left">{{ $pengesahanTemujanji->complaint->user->name ??'' }}</a></td>
                                                    <td width="10%" class="text-center">{{ $pengesahanTemujanji->complaint->complaint_date->format('d/m/Y')}}</a></td>
                                                    <td width="25%" class="align-left">{{ $pengesahanTemujanji->complaint->quarters->category->name ??'' }}</a></td>
                                                    <td width="15%" class="text-center">{{ $pengesahanTemujanji -> complaint -> status -> complaint_status ??'' }}</a></td>
                                                    <td width="15%" class="text-center">
                                                        <div class="btn-group" role="group">
                                                            @if(checkPolicy("U"))
                                                                <a href="{{ route('complaintAppointment.edit', ['id' => $pengesahanTemujanji->complaint_id]) }}" class="btn btn-outline-primary px-2 py-1 tooltip-icon">
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
                    <div class="tab-pane" id="senarai" role="tabpanel">
                        <div id="datatable_wrapper">
                            <div class="row">
                                <div class="col-sm-12">
                                    <table id="datatable3" class="table table-striped table-bordered dt-responsive wrap w-100 dataTable" role="grid">
                                        <thead class="bg-primary bg-gradient text-white">
                                            <tr role="row">
                                                <th class="text-center" width="5%">Bil.</th>
                                                <th class="text-center" width="10%">No. Aduan</th>
                                                <th class="text-center" width="15%">Nama Pengadu</th>
                                                <th class="text-center" width="10%">Tarikh Aduan</th>
                                                <th class="text-center" width="10%">Tarikh Temujanji</th>
                                                <th class="text-center" width="20%">Kategori Kuarters (Lokasi)</th>
                                                <th class="text-center" width="10%">Status Temujanji</th>
                                                <th class="text-center" width="10%">Status Aduan</th>
                                                <th class="text-center" width="10%">Tindakan</th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            @foreach($senaraiTemujanjiAll as $bil => $senaraiTemujanji)
                                                <tr >
                                                    <th scope="row" width="5%" class="text-center">{{ ++$bil }}</th>
                                                    <td width="10%" class="text-center">{{ $senaraiTemujanji->complaint->ref_no ?? null }}</td>
                                                    <td width="15%">{{ $senaraiTemujanji -> complaint -> user -> name ??'' }}</td>
                                                    <td width="10%" class="text-center">{{ $senaraiTemujanji -> complaint -> complaint_date?-> format("d/m/Y") ??'' }}</td>
                                                    <td width="10%" class="text-center">{{ $senaraiTemujanji -> appointment_date?-> format("d/m/Y") }}</td>
                                                    <td width="20%">{{ $senaraiTemujanji -> complaint ->quarters->category?-> name ??'' }}</td>
                                                    <td width="10%" class="text-center">{{ $senaraiTemujanji->status_appointment->appointment_status ?? '' }}</td>
                                                    <td width="10%" class="text-center">{{ $senaraiTemujanji -> complaint -> status -> complaint_status ??'' }}</a></td>
                                                    <td width="10%" class="text-center">
                                                        <div class="btn-group" role="group">
                                                            @if(checkPolicy("V"))
                                                                <a href="{{ route('complaintAppointment.view', ['id' => $senaraiTemujanji->complaint_id]) }}" class="btn btn-outline-primary px-2 py-1 tooltip-icon">
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
                    <div class="tab-pane" id="batal" role="tabpanel">
                        <div id="datatable_wrapper">
                            <div class="row">
                                <div class="col-sm-12">
                                    <table id="datatable4" class="table table-striped table-bordered dt-responsive wrap w-100 dataTable" role="grid">
                                        <thead class="bg-primary bg-gradient text-white">
                                            <tr role="row">
                                                <th class="text-center" width="5%">Bil.</th>
                                                <th class="text-center" width="10%">No. Aduan</th>
                                                <th class="text-center" width="15%">Nama Pengadu</th>
                                                <th class="text-center" width="8%">Tarikh Aduan</th>
                                                <th class="text-center" width="10%">Tarikh Temujanji</th>
                                                <th class="text-center" width="18%">Kategori Kuarters (Lokasi)</th>
                                                <th class="text-center" width="8%">Jenis Aduan</th>
                                                <th class="text-center" width="10%">Status Temujanji</th>
                                                <th class="text-center" width="8%">Status Aduan</th>
                                                <th class="text-center" width="8%">Tindakan</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($temujanjiDibatalkan as $bil => $senaraiTemujanji)
                                                <tr>
                                                    <th width="5%" scope="row" class="text-center">{{ ++$bil }}</th>
                                                    <td width="10%" class="text-center">{{ $senaraiTemujanji->complaint->ref_no ?? null }}</td>
                                                    <td width="15%">{{ $senaraiTemujanji -> complaint -> user -> name ??'' }}</td>
                                                    <td width="8%" class="text-center">{{ $senaraiTemujanji -> complaint -> complaint_date?-> format("d/m/Y") ??'' }}</td>
                                                    <td width="10%" class="text-center">{{ $senaraiTemujanji -> appointment_date?-> format("d/m/Y") }}</td>
                                                    <td width="18%">{{ $senaraiTemujanji -> complaint ->quarters->category?-> name ??'' }}</td>
                                                    <td width="8%" class="text-center">{{ $senaraiTemujanji -> complaint -> complaint_category -> complaint_name ??'' }}</td>
                                                    <td width="10%"class="text-center">{{ $senaraiTemujanji-> status_appointment->appointment_status ?? 'BATAL' }}</td>
                                                    <td width="8%" class="text-center">{{ $senaraiTemujanji ->status ->complaint_status ?? '' }}</a></td>
                                                    <td width="8%" class="text-center">
                                                        <div class="btn-group" role="group">
                                                            @if(checkPolicy("V"))
                                                                <a href="{{ route('complaintAppointment.view', ['id' => $senaraiTemujanji->complaint_id]) }}" class="btn btn-outline-primary px-2 py-1 tooltip-icon">
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
                </div>
            </div>
        </div>
    </div> <!-- end col -->
</div>
<!-- end row -->

@endsection

@section('script-bottom')
<script src="{{ URL::asset('assets/js/pages/ComplaintAppointment/complaintAppointment.js')}}"></script>
@endsection
