@extends('layouts.master')

@section('content')

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">

                {{-- PAGE TITLE --}}
                <div class="border-bottom border-primary mb-4"><h4 class="card-title">{{ getPageTitle(2) }}</h4></div>

                @if(checkPolicy("A"))
                    <div class="row mb-2">
                        <div class="col-sm-12">
                            <a type="button" href="{{ route('placement.bulkPlacement', $category) }}" class="btn btn-success float-end waves-effect waves-light">{{ __('button.pengesahan_penempatan_unit') }}</a>
                        </div>
                    </div>
                @endif

                <!-- TABS -->
                <ul class="nav nav-tabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" data-bs-toggle="tab" href="#application-placement" role="tab">
                            <span class="d-none d-sm-block">Penempatan</span>
                        </a>
                    </li>
                    <!-- <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#application-print-page" role="tab">
                            <span class="d-none d-sm-block">Cetak Surat</span>
                        </a>
                    </li> -->
                </ul>

                <!-- TABS CONTENT-->
                <div class="tab-content p-0">

                    <div class="tab-pane active" id="application-placement" role="tabpanel">

                        {{-- SECTION - APPLICATION PLACEMENT --}}
                        <div id="datatable_wrapper">
                            <div class="row">
                                <div class="mt-4 col-sm-12">
                                    <table id="datatable" class="table table-striped table-bordered dt-responsive nowrap w-100 dataTable" role="grid" >
                                        <thead class="bg-primary bg-gradient text-white">
                                            <tr role="row">
                                                <th class="text-center" width="4%">Bil</th>
                                                <th class="text-center" >Nama Pemohon</th>
                                                <th class="text-center" >Tarikh Diluluskan</th>
                                                <th class="text-center" >Status</th>
                                                <th class="text-center" width="10%">Tindakan</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($appNeededPlacementAll as $appNeededPlacement)
                                                <tr class="odd">
                                                    <td class="text-center" tabindex="0">{{ $loop->iteration }}</td>
                                                    <td class="text-center">{{ $appNeededPlacement->user->name }} </td>
                                                    <td class="text-center">{{ $appNeededPlacement->current_status->action_on->format('d/m/Y')  }} </td>
                                                    <td class="text-center">{{ $appNeededPlacement->current_status->status->status }} </td>
                                                    <td class="text-center">
                                                        <div class="btn-group" role="group">
                                                            @if(checkPolicy("V"))
                                                                <a href="{{ route('placement.show', ['application' => $appNeededPlacement]) }}" class="btn btn-outline-primary px-2 py-1 tooltip-icon">
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

                    <div class="tab-pane" id="application-print-page" role="tabpanel">

                        {{-- SECTION - APPLICATION PRINT --}}
                        <div id="datatable_wrapper">
                            <div class="row">
                                <div class="mt-4 col-sm-12">
                                    <table id="datatable2" class="table table-striped table-bordered dt-responsive nowrap w-100 dataTable" role="grid" >
                                        <thead class="bg-primary bg-gradient text-white">
                                            <tr role="row">
                                                <th class="text-center" width="4%">Bil</th>
                                                <th class="text-center" >Nama Pemohon</th>
                                                <th class="text-center" >Tarikh Diluluskan</th>
                                                <th class="text-center" >Status</th>
                                                <th class="text-center" width="10%">Tindakan</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($appNeededPrintAll as $appNeededPrint)
                                                <tr class="odd">
                                                    <td class="text-center" width="4%" tabindex="0">{{ $loop->iteration }}</td>
                                                    <td class="text-center">{{ $appNeededPrint->user->name }} </td>
                                                    <td class="text-center">{{ $appNeededPrint->current_status->action_on->format('d/m/Y')  }} </td>
                                                    <td class="text-center">{{ $appNeededPrint->current_status->status->status }} </td>
                                                    <td class="text-center" width="10%">
                                                        <div class="btn-group" role="group">
                                                            <a href="{{ route('placement.show', ['application' => $appNeededPrint]) }}" class="btn btn-outline-primary px-2 py-1 tooltip-icon">
                                                                <span class="tooltip-text">{{ __('button.papar') }}<i class="{{ __('icon.view_folder') }}"></i>
                                                            </a>
                                                            <a href="{{ route('placement.edit', ['application' => $appNeededPrint]) }}" class="btn btn-outline-primary px-2 py-1 tooltip-icon">
                                                                <span class="tooltip-text">{{ __('button.kemaskini') }}<i class="{{ __('icon.edit') }}"></i>
                                                            </a>
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
