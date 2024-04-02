@extends('layouts.master')

@section('content')

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">

                {{-- PAGE TITLE --}}
                <div class="border-bottom border-primary mb-4"><h4 class="card-title">{{ getPageTitle(1) }}</h4></div>

                <!-- TABS -->
                <ul class="nav nav-tabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" data-bs-toggle="tab" href="#application-for-action" role="tab">
                            <span class="d-none d-sm-block">Untuk Tindakan</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#application-history" role="tab">
                            <span class="d-none d-sm-block">Senarai Terdahulu</span>
                        </a>
                    </li>
                </ul>
                <!-- TABS CONTENT-->
                <div class="tab-content pt-3">

                    <div class="tab-pane active" id="application-for-action" role="tabpanel">

                        <div id="datatable_wrapper">
                            <div class="row">
                                <div class="col-sm-12">
                                    <table id="datatable" class="table table-striped table-bordered dt-responsive nowrap w-100 dataTable" role="grid" >
                                        <thead class="bg-primary bg-gradient text-white">
                                            <tr role="row">
                                                <th class="text-center" width="4%">Bil</th>
                                                <th width="30%" class="text-center" >Pemohon</th>
                                                <th width="30%" class="text-center" >Kategori Kuarters (Lokasi)</th>
                                                <th class="text-center" >Tarikh</th>
                                                <th class="text-center" width="10%">Tindakan</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($applicationAll as $application)
                                                <tr class="odd">
                                                    <td class="text-center" tabindex="0">{{ $loop->iteration }}</td>
                                                    <td >{{ $application->user->name }}</td>
                                                    <td > {!! ArrayToStringList($application->quarters_category->pluck('name')->toArray()) !!}</td>
                                                    <td class="text-center">{{ convertDateSys($application->application_date_time) }}</td>
                                                    <td class="text-center">
                                                        <div class="btn-group" role="group">
                                                            @if(checkPolicy("U"))
                                                            <a href="{{ route('applicationScoring.score', ['id' => $application->id, 'qcid' => $application->quarters_category_id]) }}" class="btn btn-outline-primary px-2 py-1 tooltip-icon">
                                                                <span class="tooltip-text">{{ __('button.kemaskini') }}</span> <i class="{{ __('icon.edit') }}"></i>
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

                    <div class="tab-pane mb-3" id="application-history" role="tabpanel">

                        <div id="datatable_wrapper">
                            <div class="row">
                                <div class="col-sm-12">
                                    <table id="datatable2" class="table table-striped table-bordered dt-responsive nowrap w-100 dataTable" role="grid" >
                                        <thead class="bg-primary bg-gradient text-white">
                                            <tr role="row">
                                                <th class="text-center" width="4%">Bil</th>
                                                <th width="30%" class="text-center" >Pemohon</th>
                                                <th width="30%" class="text-center" >Kategori Kuarters (Lokasi)</th>
                                                <th class="text-center" >Tarikh</th>
                                                <th class="text-center" width="10%">Tindakan</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($applicationHistoryAll as $application)
                                                <tr class="odd">
                                                    <td class="text-center" width="4%" tabindex="0">{{ $loop->iteration }}</td>
                                                    <td >{{ $application->user->name }}</td>
                                                    <td > {!! ArrayToStringList($application->quarters_category->pluck('name')->toArray()) !!}</td>
                                                    <td class="text-center">{{ convertDateSys($application->application_date_time) }}</td>
                                                    <td class="text-center" width="10%">
                                                        <div class="btn-group" role="group">
                                                            @if(checkPolicy("V"))
                                                            <a href="{{ route('applicationScoring.view', ['id' => $application->id]) }}" class="btn btn-outline-primary px-2 py-1 tooltip-icon">
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
        </div>
    </div> <!-- end col -->
</div>
<!-- end row -->

@endsection

@section('script')
<script src="{{ URL::asset('assets/js/pages/ApplicationScoring/ApplicationScoring.js')}}"></script>
@endsection
