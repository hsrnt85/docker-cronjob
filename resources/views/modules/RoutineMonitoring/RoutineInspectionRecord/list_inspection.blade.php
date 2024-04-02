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
                            <a type="button" href="{{ route('routineInspectionRecord.create', $category) }}" class="btn btn-success float-end waves-effect waves-light">{{ __('button.rekod_baru') }}</a>
                        </div>
                    </div>
                @endif
                <!-- TABS -->
                <ul class="nav nav-tabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" data-bs-toggle="tab" href="#semasa" role="tab">
                            <span class="d-none d-sm-block">Untuk Tindakan</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#selesai" role="tab">
                            <span class="d-none d-sm-block">Senarai Terdahulu : Selesai</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#belumSelesai" role="tab">
                            <span class="d-none d-sm-block">Senarai Terdahulu : Belum Selesai</span>
                        </a>
                    </li>
                </ul>

                <!-- TABS CONTENT-->
                <div class="tab-content pt-3">
                    <div class="tab-pane active" id="semasa" role="tabpanel">
                        <div id="datatable_wrapper">
                            <div class="row">
                                <div class="col-sm-12">
                                    <table id="datatable" class="table table-striped table-bordered dt-responsive w-100 dataTable" role="grid" >
                                        <thead class="bg-primary bg-gradient text-white">
                                            <tr role="row">
                                                <th class="text-center" width="2%" style="">Bil</th>
                                                <th class="text-center">No. Rujukan</th>
                                                <th class="text-center">Kategori Kuarters (Lokasi)</th>
                                                <th class="text-center">Alamat</th>
                                                <th class="text-center">Tugasan</th>
                                                <th class="text-center">Pegawai Pemantau</th>
                                                <th class="text-center">Tarikh</th>
                                                <th class="text-center">Tindakan</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($inspectionAll as $inspection)
                                                <tr class="odd">
                                                    <td class="text-center" tabindex="0">{{ $loop->iteration }}</td>
                                                    <td class="text-center" >{{ $inspection->ref_no }} </td>
                                                    <td class="text-center" >{{ $inspection->quarters_category->name }} </td>
                                                    <td class="text-center" >{{ $inspection->address }} </td>
                                                    <td class="text-center" >{{ $inspection->remarks }}</td>
                                                    <td class="text-center" >{{ $inspection->monitoring_officer->user->name }} </td>
                                                    <td class="text-center" >{{ convertDateSys($inspection->inspection_date)  }} </td>
                                                    <td class="text-center">
                                                        <div class="btn-group" role="group">
                                                            @if(checkPolicy("V"))
                                                                <a href="{{ route('routineInspectionRecord.view', $inspection) }}" class="btn btn-outline-primary px-2 py-1 tooltip-icon">
                                                                    <span class="tooltip-text">{{ __('button.papar') }}</span><i class="{{ __('icon.view_folder') }}"></i>
                                                                </a>
                                                            @endif
                                                            @if(checkPolicy("U"))
                                                                <a href="{{ route('routineInspectionRecord.edit', $inspection) }}" class="btn btn-outline-primary px-2 py-1 tooltip-icon">
                                                                    <span class="tooltip-text">{{ __('button.kemaskini') }}</span><i class="{{ __('icon.edit') }}"></i>
                                                                </a>
                                                            @endif
                                                            @if(checkPolicy("D"))
                                                                <a class="btn btn-outline-primary px-2 py-1 tooltip-icon swal-delete-list">
                                                                    <span class="tooltip-text">{{ __('button.hapus') }}</span><i class="{{ __('icon.delete') }}"></i>
                                                                </a>
                                                            @endif
                                                        </div>
                                                        <form method="POST" action="{{ route('routineInspectionRecord.delete') }}" class="delete-form-list">
                                                            {{ csrf_field() }}
                                                            {{ method_field('DELETE') }}
                                                            <input class="form-control" type="hidden" name="id" value="{{ $inspection->id }}">
                                                            <input class="form-control" type="hidden" name="quarters_category_id" value="{{ $inspection->quarters_category->id }}">
                                                        </form>
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
                                    <table id="datatable2" class="table table-striped table-bordered dt-responsive w-100 dataTable" role="grid" >
                                        <thead class="bg-primary bg-gradient text-white">
                                            <tr role="row">
                                                <th class="text-center" width="2%" style="">Bil</th>
                                                <th class="text-center">No. Rujukan</th>
                                                <th class="text-center">Kategori Kuarters (Lokasi)</th>
                                                <th class="text-center">Alamat</th>
                                                <th class="text-center">Tugasan</th>
                                                <th class="text-center">Pegawai Pemantau</th>
                                                <th class="text-center">Tarikh</th>
                                                <th class="text-center">Tindakan</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($inspectionArchivedDone as $inspectionArchived)
                                                <tr class="odd">
                                                    <td class="text-center" tabindex="0">{{ $loop->iteration }}</td>
                                                    <td class="text-center" >{{ $inspectionArchived->ref_no }} </td>
                                                    <td class="text-center" >{{ $inspectionArchived->quarters_category->name }} </td>
                                                    <td class="text-center" >{{ $inspectionArchived->address }} </td>
                                                    <td class="text-center" >{{ $inspectionArchived->remarks }}</td>
                                                    <td class="text-center" >{{ $inspectionArchived->monitoring_officer->user->name }} </td>
                                                    <td class="text-center" >{{ convertDateSys($inspectionArchived->inspection_date)  }} </td>
                                                    <td class="text-center">
                                                        <div class="btn-group" role="group">
                                                            @if(checkPolicy("V"))
                                                                <a href="{{ route('routineInspectionRecord.view', $inspectionArchived) }}" class="btn btn-outline-primary px-2 py-1 tooltip-icon">
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

                    <div class="tab-pane" id="belumSelesai" role="tabpanel">
                        <div id="datatable_wrapper">
                            <div class="row">
                                <div class="col-sm-12">
                                    <table id="datatable3" class="table table-striped table-bordered dt-responsive w-100 dataTable" role="grid" >
                                        <thead class="bg-primary bg-gradient text-white">
                                            <tr role="row">
                                                <th class="text-center" width="2%" style="">Bil</th>
                                                <th class="text-center">No. Rujukan</th>
                                                <th class="text-center">Kategori Kuarters (Lokasi)</th>
                                                <th class="text-center">Alamat</th>
                                                <th class="text-center">Tugasan</th>
                                                <th class="text-center">Pegawai Pemantau</th>
                                                <th class="text-center">Tarikh</th>
                                                <th class="text-center">Tindakan</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($inspectionArchivedNonDone as $inspectionArchivedNon)
                                                <tr class="odd">
                                                    <td class="text-center" tabindex="0">{{ $loop->iteration }}</td>
                                                    <td class="text-center" >{{ $inspectionArchivedNon->ref_no }} </td>
                                                    <td class="text-center" >{{ $inspectionArchivedNon->quarters_category->name }} </td>
                                                    <td class="text-center" >{{ $inspectionArchivedNon->address }} </td>
                                                    <td class="text-center" >{{ $inspectionArchivedNon->remarks }}</td>
                                                    <td class="text-center" >{{ $inspectionArchivedNon->monitoring_officer->user->name }} </td>
                                                    <td class="text-center" >{{ convertDateSys($inspectionArchivedNon->inspection_date)  }} </td>
                                                    <td class="text-center">
                                                        <div class="btn-group" role="group">
                                                            @if(checkPolicy("V"))
                                                                <a href="{{ route('routineInspectionRecord.view', $inspectionArchivedNon) }}" class="btn btn-outline-primary px-2 py-1 tooltip-icon">
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
<script src="{{ URL::asset('assets/js/pages/RoutineInspection/routine-inspection.js')}}"></script>
@endsection
