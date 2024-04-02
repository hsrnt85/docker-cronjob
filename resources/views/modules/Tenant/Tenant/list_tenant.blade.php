@extends('layouts.master')

@section('content')

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">

                {{-- PAGE TITLE --}}
                <div class="border-bottom border-primary mb-4"><h4 class="card-title">{{ getPageTitle(2) }}</h4></div>

                <h4 class="card-title text-primary mb-4">{{ $category->name }}</h4>

                <ul class="nav nav-tabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a class="nav-link active" data-bs-toggle="tab" href="#semasa" role="tab" aria-selected="false" tabindex="-1">
                            <span class="d-block d-sm-none"><i class="fas fa-home"></i></span>
                            <span class="d-none d-sm-block">Penghuni Semasa</span>
                        </a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" data-bs-toggle="tab" href="#blacklist" role="tab" aria-selected="true">
                            <span class="d-block d-sm-none"><i class="far fa-user"></i></span>
                            <span class="d-none d-sm-block">Penghuni Hilang Kelayakan</span>
                        </a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" data-bs-toggle="tab" href="#keluar" role="tab" aria-selected="true">
                            <span class="d-block d-sm-none"><i class="far fa-user"></i></span>
                            <span class="d-none d-sm-block">Penghuni Keluar</span>
                        </a>
                    </li>
                </ul>

                <div class="tab-content p-3 text-muted">
                    <div class="tab-pane active show" id="semasa" role="tabpanel">
                        <div id="datatable_wrapper">
                            <div class="row">
                                <div class="col-sm-12">
                                    <table id="datatable" class="table table-striped table-bordered dt-responsive wrap w-100 dataTable" role="grid" >
                                        <thead class="bg-primary bg-gradient text-white">
                                            <tr role="row">
                                                <th class="text-center" >Bil.</th>
                                                <th class="text-center" >Nama</th>
                                                <th class="text-center" >Kad Pengenalan</th>
                                                <th class="text-center" >No. Unit</th>
                                                <th class="text-center" >Alamat </th>
                                                <th class="text-center" >Tarikh Masuk </th>
                                                <th class="text-center" >Tindakan</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($tenantAll as $tenant)
                                                <tr class="odd">
                                                    <td class="text-center" tabindex="0">{{ $loop->iteration }}</td>
                                                    <td >{{ $tenant->name }} </td>
                                                    <td class="text-center">{{ $tenant->new_ic }} </td>
                                                    <td class="text-center">{{ $tenant->application->selected_quarters()?->unit_no ?? '' }} </td>
                                                    <td >{{ $tenant->quarters?->address_1 }} {{ $tenant->quarters?->address_2 }} {{ $tenant->quarters?->address_3 }}</td>
                                                    <td class="text-center">{{ $tenant->quarters_acceptance_date?->format('d/m/Y') }} </td>
                                                    <td class="text-center">
                                                        <div class="btn-group" role="group">
                                                            @if(checkPolicy("V"))
                                                                <a href="{{ route('tenant.view', ['category' => $tenant->quarters_category_id, 'tenant' => $tenant->id]) }}" class="btn btn-outline-primary px-2 py-1 tooltip-icon">
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
                    <div class="tab-pane" id="blacklist" role="tabpanel">
                        <div id="datatable_wrapper">
                            <div class="row">
                                <div class="col-sm-12">
                                    <table id="datatable3" class="table table-striped table-bordered dt-responsive w-100 dataTable" role="grid" >
                                        <thead class="bg-primary bg-gradient text-white">
                                            <tr role="row">
                                                <th class="text-center" >Bil.</th>
                                                <th class="text-center" >Nama</th>
                                                <th class="text-center" >No. Kad Pengenalan</th>
                                                <th class="text-center" >No. Unit</th>
                                                <th class="text-center" >Alamat </th>
                                                <th class="text-center" >Tarikh Hilang Kelayakan </th>
                                                <th class="text-center" >Tindakan</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($tenantBlacklistAll as $tenantBlacklist)
                                                <tr class="odd">
                                                    <td class="text-center" tabindex="0">{{ $loop->iteration }}</td>
                                                    <td >{{ $tenantBlacklist->name }} </td>
                                                    <td class="text-center">{{ $tenantBlacklist->new_ic }} </td>
                                                    <td class="text-center">{{ $tenantBlacklist->application->selected_quarters()?->unit_no }} </td>
                                                    <td >{{ $tenantBlacklist->quarters?->address_1 }} {{ $tenantBlacklist->quarters?->address_2 }} {{ $tenantBlacklist->quarters?->address_3 }}</td>
                                                    <td class="text-center">{{ $tenantBlacklist->blacklist_date->format('d/m/Y') }} </td>
                                                    <td class="text-center">
                                                        <div class="btn-group" role="group">
                                                            @if(checkPolicy("V"))
                                                                <a href="{{ route('tenant.view', ['category' => $tenantBlacklist->quarters_category_id, 'tenant' => $tenantBlacklist->id]) }}" class="btn btn-outline-primary px-2 py-1 tooltip-icon">
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
                                    <table id="datatable2" class="table table-striped table-bordered dt-responsive wrap w-100 dataTable" role="grid" >
                                        <thead class="bg-primary bg-gradient text-white">
                                            <tr role="row">
                                                <th class="text-center" width="5%">Bil.</th>
                                                <th class="text-center" width="20%">Nama</th>
                                                <th class="text-center" width="13%">No. Kad Pengenalan</th>
                                                <th class="text-center" width="10%">No. Unit</th>
                                                <th class="text-center" >Alamat </th>
                                                <th class="text-center" width="13%">Tarikh Permohonan </th>
                                                <th class="text-center" width="13%">Tarikh Keluar Kuarters</th>
                                                <th class="text-center" width="10%" >Tindakan</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($tenantLeftAll as $tenantLeft)

                                                @php
                                                    $leave_application_date = convertDateSys($tenantLeft->action_on ?? '');
                                                    //$leave_date = $tenantLeft->leave_date?->format('d/m/Y');
                                                    $leave_date = convertDateSys($tenantLeft->leave_date ?? '');
                                                    $leave_status_id = $tenantLeft->leave_status_id;
                                                @endphp
                                                <tr class="odd">
                                                    <td width="5%" class="text-center" tabindex="0">{{ $loop->iteration }}</td>
                                                    <td width="20%">{{ $tenantLeft->name }} </td>
                                                    <td width="13%" class="text-center">{{ $tenantLeft->new_ic }} </td>
                                                    <td class="text-center" width="10%">{{ $tenantLeft->application->selected_quarters()?->unit_no }} </td>
                                                    <td >{{ $tenantLeft->quarters?->address_1 }} {{ $tenantLeft->quarters?->address_2 }} {{ $tenantLeft->quarters?->address_3 }}</td>
                                                    <td class="text-center" width="13%">{{ $leave_application_date }} </td>
                                                    <td class="text-center" width="13%">{{ $leave_date }} </td>
                                                    <td class="text-center" width="10%">
                                                        <div class="btn-group" role="group">
                                                            @if(checkPolicy("V"))
                                                                <a href="{{ route('tenant.view', ['category' => $tenantLeft->quarters_category_id, 'tenant' => $tenantLeft->id]) }}" class="btn btn-outline-primary px-2 py-1 tooltip-icon">
                                                                    <span class="tooltip-text">{{ __('button.papar') }}</span><i class="{{ __('icon.view_folder') }}"></i>
                                                                </a>
                                                            @endif
                                                            @if(checkPolicy("V") && $leave_status_id==2)
                                                                <a href="{{ route('tenant.leaveApproval', ['category' => $tenantLeft->quarters_category_id, 'tenant' => $tenantLeft->id]) }}" class="btn btn-outline-primary px-2 py-1 tooltip-icon">
                                                                    <span class="tooltip-text">{{ __('button.pengesahan') }}</span><i class="{{ __('icon.approve_user') }}"></i>
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

@section('script')
<script src="{{ URL::asset('assets/js/pages/Tenant/tenant.js')}}"></script>
@endsection
