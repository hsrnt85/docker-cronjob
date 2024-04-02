@extends('layouts.master')

@section('content')

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">

                {{-- PAGE TITLE --}}
                <div class="border-bottom border-primary mb-4"><h4 class="card-title">{{ getPageTitle(1) }}</h4></div>

                <!-- Nav tabs -->
                <ul class="nav nav-tabs" role="tablist" >
                    <li class="nav-item " >
                        <a class="nav-link active" data-bs-toggle="tab" href="#baru" role="tab" >Aduan Baru</a>
                    </li>
                    <li class="nav-item" >
                        <a class="nav-link" data-bs-toggle="tab" href="#pengesahan" role="tab" >Pengesahan Aduan</a>
                    </li>
                    <li class="nav-item" >
                        <a class="nav-link" data-bs-toggle="tab" href="#terdahulu" role="tab" >Senarai Aduan Terdahulu</a>
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
                                                <th class="text-center" width="5%">Bil</th>
                                                <th class="text-center" width="15%">No. Aduan</th>
                                                <th class="text-center" width="15%">Nama Pengadu</th>
                                                <th class="text-center" width="15%">Tarikh Aduan</th>
                                                <th class="text-center" width="25%">Kategori Kuarters (Lokasi)</th>
                                                <th class="text-center" width="15%">Status Aduan</th>
                                                <th class="text-center" width="10%">Tindakan</th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            @foreach($senaraiAduanAwam as $bil => $senaraiAduan)
                                                <tr>
                                                    <th class="text-center" width="5%" scope="row">{{ ++$bil }}</th>
                                                    <td class="text-center" width="15%">{{ $senaraiAduan->ref_no ?? '' }}</td>
                                                    <td class="text-center" width="15%">{{ $senaraiAduan -> user -> name ??'' }}</a></td>
                                                    <td class="text-center" width="15%">{{ $senaraiAduan->complaint_date?->format("d/m/Y") ?? '' }}</a></td>
                                                    <td class="text-center" width="25%">{{ $senaraiAduan-> quarters_name ??'' }}</a></td>
                                                    <td class="text-center" width="15%">{{ $senaraiAduan -> status -> complaint_status ??'' }}</a></td>
                                                    <td class="text-center" width="10%">
                                                        <div class="btn-group" role="group">
                                                            @if(checkPolicy("U"))
                                                                <a href="{{ route('rulesViolationComplaintApproval.create', ['id' => $senaraiAduan->id]) }}" class="btn btn-outline-primary px-2 py-1 tooltip-icon">
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
                                    <table id="datatable2" class="table table-striped table-bordered dt-responsive wrap w-100 dataTable" role="grid">
                                        <thead class="bg-primary bg-gradient text-white">
                                            <tr role="row">
                                                <th class="text-center" width="5%">Bil</th>
                                                <th class="text-center" width="15%">No. Aduan</th>
                                                <th class="text-center" width="15%">Nama Pengadu</th>
                                                <th class="text-center" width="15%">Tarikh Aduan</th>
                                                <th class="text-center" width="25%">Kategori Kuarters (Lokasi)</th>
                                                <th class="text-center" width="15%">Status Aduan</th>
                                                <th class="text-center" width="10%">Tindakan</th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            @foreach($pengesahanAduanAwam as $bil => $pengesahanAduan)
                                                <tr>
                                                    <th class="text-center" width="5%" scope="row">{{ ++$bil }}</th>
                                                    <td class="text-center" width="15%">{{ $pengesahanAduan->ref_no ?? null }}</td>
                                                    <td class="text-center" width="15%">{{ $pengesahanAduan->user->name ??'' }}</a></td>
                                                    <td class="text-center" width="15%">{{ $pengesahanAduan->complaint_date->format('d/m/Y')}}</a></td>
                                                    <td class="text-center" width="25%">{{ $pengesahanAduan->quarters_name ??'' }}</a></td>
                                                    <td class="text-center" width="15%">{{ $pengesahanAduan->status ->complaint_status ??'' }}</a></td>
                                                    <td class="text-center" width="10%" >
                                                        <div class="btn-group" role="group">
                                                            @if(checkPolicy("V"))
                                                                <a href="{{ route('rulesViolationComplaintApproval.view', ['id' => $pengesahanAduan->id]) }}" class="btn btn-outline-primary px-2 py-1 tooltip-icon">
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
                    <div class="tab-pane" id="terdahulu" role="tabpanel">
                        <div id="datatable_wrapper">
                            <div class="row">
                                <div class="col-sm-12">
                                    <table id="datatable3" class="table table-striped table-bordered dt-responsive wrap w-100 dataTable" role="grid">
                                        <thead class="bg-primary bg-gradient text-white">
                                            <tr role="row">
                                                <th class="text-center" width="5%">Bil</th>
                                                <th class="text-center" width="15%">No. Aduan</th>
                                                <th class="text-center" width="15%">Nama Pengadu</th>
                                                <th class="text-center" width="15%">Tarikh Aduan</th>
                                                <th class="text-center" width="25%">Kategori Kuarters (Lokasi)</th>
                                                <th class="text-center" width="15%">Status Aduan</th>
                                                <th class="text-center" width="10%">Tindakan</th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            @foreach($senaraiAduanTerdahulu as $bil => $aduanTerdahulu)
                                                <tr>
                                                    <th class="text-center" width="5%" scope="row">{{ ++$bil }}</th>
                                                    <td class="text-center" width="15%">{{ $aduanTerdahulu->ref_no ?? null }}</td>
                                                    <td class="text-center" width="15%">{{ $aduanTerdahulu->user->name ??'' }}</td>
                                                    <td class="text-center" width="15%">{{ $aduanTerdahulu->complaint_date?-> format("d/m/Y") ??'' }}</td>
                                                    <td class="text-center" width="25%">{{ $aduanTerdahulu->quarters_name ??'' }}</td>
                                                    <td class="text-center" width="15%">{{ $aduanTerdahulu->status->complaint_status ??'' }}</a></td>
                                                    <td class="text-center" width="10%">
                                                        <div class="btn-group" role="group">
                                                            @if(checkPolicy("V"))
                                                                <a href="{{ route('rulesViolationComplaintApproval.view', ['id' => $aduanTerdahulu->id]) }}" class="btn btn-outline-primary px-2 py-1 tooltip-icon">
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
<script src="{{ URL::asset('assets/js/pages/RulesViolationComplaintApproval/rulesViolationComplaintApproval.js')}}"></script>
@endsection
