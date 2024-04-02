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
                        <a class="nav-link active" data-bs-toggle="tab" href="#tindakan" role="tab" >Untuk Tindakan</a>
                    </li>
                    <li class="nav-item" >
                        <a class="nav-link" data-bs-toggle="tab" href="#terdahulu" role="tab" >Senarai Terdahulu</a>
                    </li>
                </ul>
                <br>
                <div class="tab-content">
                    <div class="tab-pane active" id="tindakan" role="tabpanel">
                        <div id="datatable_wrapper">
                            <div class="row">
                                <div class="col-sm-12">
                                    <table id="datatable1" class="table table-striped table-bordered dt-responsive wrap w-100 dataTable" role="grid">
                                        <thead class="bg-primary bg-gradient text-white">
                                            <tr role="row">
                                                <th class="text-center" width="5%">Bil</th>
                                                <th class="text-center" width="25%">No. Aduan</th>
                                                <th class="text-center" width="15%">Tarikh Aduan</th>
                                                <th class="text-center" width="25%">Pegawai Pemantau</th>
                                                <th class="text-center" width="20%">Status</th>
                                                <th class="text-center" width="10%">Tindakan</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($complaintMaintenance as $bil => $complaint)
                                                <tr>
                                                    <th class="text-center" scope="row"><input type="hidden" value={{$complaint->current_maintenance_transaction->id ?? ''}}>
                                                        {{ ++$bil }}
                                                    </th>
                                                    <td class="text-center">{{ $complaint->ref_no ?? '' }} </td>
                                                    <td class="text-center">{{ convertDateSys($complaint->complaint_date) ??'' }}</a></td>
                                                    <td class="text-center">{{ upperText($complaint->current_maintenance_transaction?->officer?->user?->name) ?? '-' }}</a></td>
                                                    <td  class="text-center">{{$complaint->current_maintenance_transaction->status?->status ?? '-' }}</td>
                                                    <td class="text-center">
                                                        <div class="btn-group" role="group">
                                                            @if(checkPolicy("U"))
                                                                <a href="{{ route('maintenanceTransaction.edit', ['id' => $complaint->id]) }}" class="btn btn-outline-primary px-2 py-1 tooltip-icon">
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
                    <div class="tab-pane" id="terdahulu" role="tabpanel">
                        <div id="datatable_wrapper">
                            <div class="row">
                                <div class="col-sm-12">
                                    <table id="datatable2" class="table table-striped table-bordered dt-responsive wrap w-100 dataTable" role="grid">
                                        <thead class="bg-primary bg-gradient text-white">
                                            <tr role="row">
                                                <th class="text-center" width="5%">Bil</th>
                                                <th class="text-center" width="25%">No. Aduan</th>
                                                <th class="text-center" width="15%">Tarikh Aduan</th>
                                                <th class="text-center" width="25%">Pegawai Pemantau</th>
                                                <th class="text-center" width="20%">Status</th>
                                                <th class="text-center" width="10%">Tindakan</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($maintenanceHistory as $bil => $history)
                                                <tr>
                                                    <th class="text-center" scope="row" width="5%"><input type="hidden" value={{$history->id}}>{{ ++$bil }}</th>
                                                    <td class="text-center" width="25%">{{ $history->ref_no ?? '' }}</td>
                                                    <td class="text-center" width="15%">{{ convertDateSys($history->complaint_date) ??'' }}</a></td>
                                                    <td class="text-center" width="25%">{{ upperText($history->monitoring_officer_2?->user?->name) ??'' }}</a></td>
                                                    <td class="text-center" width="20%">{{ $history->maintenance_status?->status??'' }}</a></td>
                                                    <td class="text-center"  width="10%">
                                                        <div class="btn-group" role="group">
                                                            @if(checkPolicy("V"))
                                                                <a href="{{ route('maintenanceTransaction.view',['id' => $history->id]) }}" class="btn btn-outline-primary px-2 py-1 tooltip-icon">
                                                                    <span class="tooltip-text">{{ __('button.papar') }}</span> <i class="{{ __('icon.view_folder') }}"></i>
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
    </div> <!-- end col -->
</div>
<!-- end row -->

@endsection

@section('script')
<script src="{{ URL::asset('assets/js/pages/MaintenanceTransaction/maintenanceTransaction.js')}}"></script>
@endsection
