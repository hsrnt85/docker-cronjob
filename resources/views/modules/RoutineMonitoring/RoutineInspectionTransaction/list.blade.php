@extends('layouts.master')

@section('content')

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">

                {{-- PAGE TITLE --}}
                <div class="border-bottom border-primary mb-4"><h4 class="card-title">{{ getPageTitle(1) }}</h4></div>

                <div id="datatable_wrapper">
                    <div class="row">
                        <div class="col-sm-12">
                            <table id="datatable" class="table table-striped table-bordered dt-responsive w-100 dataTable" role="grid" >
                                <thead class="bg-primary bg-gradient text-white">
                                    <tr role="row">
                                        <th class="text-center" width="2%" style="">Bil</th>
                                        <th class="text-center">No. Rujukan</th>
                                        <th class="text-center">Kategori Kuarters <br> (Lokasi)</th>
                                        <th class="text-center">Alamat</th>
                                        <th class="text-center">Tugasan</th>
                                        <th class="text-center">Pegawai Pemantau</th>
                                        <th class="text-center">Tarikh</th>
                                        <th class="text-center">Status</th>
                                        <th class="text-center">Tindakan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($routineInspectionAll as $inspection)
                                        <tr class="odd">
                                            <td class="text-center" tabindex="0">{{ $loop->iteration }}</td>
                                            <td class="text-center" >{{ $inspection->ref_no }} </td>
                                            <td class="text-center" >{{ $inspection->quarters_category->name }} </td>
                                            <td class="text-center" >{{ $inspection->address }} </td>
                                            <td class="text-center" >{{ upperText($inspection->remarks) }}</td>
                                            <td class="text-center" >{{ $inspection->monitoring_officer->user->name }} </td>
                                            <td class="text-center" >{{ convertDateSys($inspection->inspection_date)  }} </td>
                                            <td class="text-center" >
                                                <span class="badge {{($inspection->inspection_transaction) ? 'bg-danger' : 'bg-warning'}} p-2">
                                                    {{ $inspection->inspection_transaction->inspectionStatus->status ?? '-'}}
                                                </span>

                                            </td>
                                            <td class="text-center">
                                                <div class="btn-group" role="group">
                                                    @if(checkPolicy("V"))
                                                        <a href="{{ route('routineInspectionTransaction.view', $inspection) }}" class="btn btn-outline-primary px-2 py-1 tooltip-icon">
                                                            <span class="tooltip-text">{{ __('button.papar') }}</span><i class="{{ __('icon.view_folder') }}"></i>
                                                        </a>
                                                    @endif
                                                    @if(checkPolicy("U"))
                                                        <a href="{{ route('routineInspectionTransaction.edit', $inspection) }}" class="btn btn-outline-primary px-2 py-1 tooltip-icon">
                                                            <span class="tooltip-text">{{ __('button.kemaskini') }}</span><i class="{{ __('icon.edit') }}"></i>
                                                        </a>
                                                    @endif
                                                </div>
                                                <form method="POST" action="{{ route('routineInspectionRecord.delete') }}" class="delete-form-list">
                                                    {{ csrf_field() }}
                                                    {{ method_field('DELETE') }}
                                                    <input class="form-control" type="hidden" name="id" value="{{ $inspection->id }}">
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
        </div>
    </div> <!-- end col -->
</div>
<!-- end row -->

@endsection
