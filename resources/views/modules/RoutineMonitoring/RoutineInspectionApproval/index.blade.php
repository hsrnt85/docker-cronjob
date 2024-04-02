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
                        <a class="nav-link active" data-bs-toggle="tab" href="#semasa" role="tab">
                            <span class="d-none d-sm-block">Untuk Tindakan</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#terdahulu" role="tab">
                            <span class="d-none d-sm-block">Senarai Terdahulu</span>
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
                                                <th class="text-center" width="4%">Bil</th>
                                                <th class="text-center">No. Rujukan</th>
                                                <th class="text-center">Tarikh </th>
                                                <th class="text-center">Kategori Kuarters (Lokasi)</th>
                                                <th class="text-center">Alamat</th>
                                                <th class="text-center">Tugasan</th>
                                                <th class="text-center">Pegawai Pemantau</th>
                                                <th class="text-center" width="10%">Tindakan</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($inspectionTransactionAll as $inspectionTransaction)
                                                <tr class="odd">
                                                    <td class="text-center" tabindex="0">{{ $loop->iteration }}</td>
                                                    <td class="text-center">{{ $inspectionTransaction->routineInspection->ref_no }} </td>
                                                    <td class="text-center">{{ convertDateSys($inspectionTransaction->routineInspection->inspection_date)  }} </td>
                                                    <td class="text-center">{{ $inspectionTransaction->routineInspection->quarters_category->name }} </td>
                                                    <td class="text-center">{{ $inspectionTransaction->routineInspection->address }} </td>
                                                    <td class="text-center">{{ $inspectionTransaction->routineInspection->remarks }}</td>
                                                    <td class="text-center">{{ $inspectionTransaction->routineInspection->monitoring_officer->user->name }} </td>
                                                    <td class="text-center">
                                                        <div class="btn-group" role="group">
                                                            @if(checkPolicy("U"))
                                                            <a href="{{ route('routineInspectionApproval.edit', $inspectionTransaction) }}" class="btn btn-outline-primary px-2 py-1 tooltip-icon">
                                                                <span class="tooltip-text">{{ __('button.kemaskini') }}</span><i class="{{ __('icon.edit') }}"></i>
                                                            </a>
                                                            @endif
                                                        </div>
                                                        <form method="POST" action="{{ route('routineInspectionRecord.delete') }}" class="delete-form-list">
                                                            {{ csrf_field() }}
                                                            {{ method_field('DELETE') }}
                                                            <input class="form-control" type="hidden" name="id" value="{{ $inspectionTransaction->id }}">
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

                    <div class="tab-pane" id="terdahulu" role="tabpanel">
                        <div id="datatable_wrapper">
                            <div class="row">
                                <div class="col-sm-12">
                                    <table id="datatable2" class="table table-striped table-bordered dt-responsive w-100 dataTable" role="grid" >
                                        <thead class="bg-primary bg-gradient text-white">
                                            <tr role="row">
                                                <th class="text-center" width="4%">Bil</th>
                                                <th class="text-center">No. Rujukan</th>
                                                <th class="text-center">Tarikh </th>
                                                <th class="text-center">Kategori Kuarters (Lokasi)</th>
                                                <th class="text-center">Alamat</th>
                                                <th class="text-center">Tugasan</th>
                                                <th class="text-center">Pemantau</th>
                                                <th class="text-center">Status</th>
                                                <th class="text-center">Pengesah</th>
                                                <th class="text-center" width="10%">Tindakan</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($inspectionTransactionWithStatusAll as $inspectionTransactionWithStatus)
                                                <tr class="odd">
                                                    <td class="text-center" tabindex="0">{{ $loop->iteration }}</td>
                                                    <td class="text-center">{{ $inspectionTransactionWithStatus->routineInspection->ref_no }} </td>
                                                    <td class="text-center">{{ convertDateSys($inspectionTransactionWithStatus->routineInspection->inspection_date)  }} </td>
                                                    <td class="text-center">{{ $inspectionTransactionWithStatus->routineInspection->quarters_category->name }} </td>
                                                    <td class="text-center">{{ $inspectionTransactionWithStatus->routineInspection->address }} </td>
                                                    <td class="text-center">{{ $inspectionTransactionWithStatus->routineInspection->remarks }}</td>
                                                    <td class="text-center">{{ $inspectionTransactionWithStatus->routineInspection->monitoring_officer->user->name }}</td>
                                                    <td class="text-center">
                                                        <span class="badge {{($inspectionTransactionWithStatus->approvalStatus->id == 1) ? 'bg-success' : 'bg-danger'}} p-2">
                                                            {{ $inspectionTransactionWithStatus->approvalStatus->status }}
                                                        </span>
                                                    </td>
                                                    <td class="text-center">{{ $inspectionTransactionWithStatus->officer->user->name }}</td>
                                                    <td class="text-center">
                                                        <div class="btn-group" role="group">
                                                            @if(checkPolicy("V"))
                                                                <a href="{{ route('routineInspectionApproval.view', $inspectionTransactionWithStatus) }}" class="btn btn-outline-primary px-2 py-1 tooltip-icon">
                                                                    <span class="tooltip-text">{{ __('button.papar') }}</span><i class="{{ __('icon.view_file') }}"></i>
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
