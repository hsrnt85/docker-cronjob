@extends('layouts.master')
@section('css')
    <link href="{{ URL::asset('/assets/libs/bootstrap-datepicker/bootstrap-datepicker.min.css') }}" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="{{ URL::asset('/assets/libs/datepicker/datepicker.min.css') }}">
@endsection
@section('content')

<div class="row">
    <div class="col-12">

        <!-- section - search  -->
        <div class="card">
            <form class="search_form custom-validation" action="{{ route('collectorStatement.index') }}" method="post" id="form_search_record">
                {{ csrf_field() }}
                <div class="card-body p-3">
                    <div class="border-bottom border-primary mb-4"><h4 class="card-title">Carian Rekod</h4></div>
                    <div class="row">
                        <div class="col-md-3 p-1 mb-3" >
                            <label for="statement_code" class="col-form-label ">No. Penyata Pemungut</label>
                            <div class="input-right-icon">
                                <input class="form-control" type="text" id="ref_no" name="ref_no" value="{{  old('ref_no', $search_ref_no) }}">
                            </div>
                        </div>
                        <div class="col-md-3 p-1 mb-3" >
                            <label class="col-form-label ">Tarikh Penyata Pemungut</label>
                            <div class="input-group" id="datepicker2">
                                <input class="form-control"  type="text" placeholder="dd/mm/yyyy" name="search_date" id="search_date"
                                data-date-format="dd/mm/yyyy" data-date-container='#datepicker2' data-provide="datepicker" autocomplete="off"
                                data-date-autoclose="true" value="{{  old('search_date', $search_date) }}">
                                <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 p-1 mb-3">
                            <button class="btn btn-primary" type="submit">{{ __('button.cari') }}</button>
                            <button name="reset" class="btn btn-primary" type="submit" onClick ="clearSearchInput()" >{{ __('button.reset') }}</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <!-- end section - search  -->

        <div class="card">
            <div class="card-body">
                {{-- PAGE TITLE --}}
                <div class="border-bottom border-primary mb-4"><h4 class="card-title">{{ getPageTitle(1) }}</h4></div>

                @if(checkPolicy("A") && $is_preparer_officer)
                    <div class="row mb-2">
                        <div class="col-sm-12">
                            <a type="button" href="{{ route('collectorStatement.create') }}" class="btn btn-success float-end waves-effect waves-light">{{ __('button.rekod_baru') }}</a>
                        </div>
                    </div>
                @endif
                <!-- Nav tabs -->
                <ul class="nav nav-tabs" role="tablist">
                    <li class="nav-item" >
                        <a class="nav-link active" data-bs-toggle="tab" href="#tindakan" role="tab" >Untuk Tindakan</a>
                    </li>
                    <li class="nav-item" >
                        <a class="nav-link" data-bs-toggle="tab" href="#terdahulu" role="tab" >Senarai Terdahulu</a>
                    </li>
                </ul>    <br>

                <div class="tab-content">
                    <div class="tab-pane active" id="tindakan" role="tabpanel">
                        <div id="datatable_wrapper">
                            <div class="row">
                                <div class="col-sm-12">
                                    <table class="table table-striped table-bordered dt-responsive nowrap w-100 dataTable indextable" role="grid" >
                                        <thead class="bg-primary bg-gradient text-white">
                                            <tr role="row">
                                                <th class="text-center" width="5%">Bil</th>
                                                <th class="text-center" width="20%">No. Penyata Pemungut</th>
                                                <th class="text-center" width="15%">Tarikh Penyata Pemungut</th>
                                                <th class="text-center" width="20%">Tempoh Pungutan</th>
                                                <th class="text-center" width="15%">Jumlah Terimaan (RM)</th>
                                                <th class="text-center" width="10%">Status</th>
                                                <th class="text-center" width="15%">Tindakan</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $collector_statement_log = []; // Initialize an empty array
                                            @endphp
                                            @foreach ($collector_statement_list as $bil => $cs)
                                                @php
                                                        $badge_transaction_status = (($cs->transaction_status_id == 4) ? "bg-success" : "bg-warning");
                                                        $badge_transaction_status = ((in_array($cs->transaction_status_id, [6, 7, 8])) ? "bg-danger" : $badge_transaction_status);

                                                        $batal = (($cs->transaction_status_id == 1) && ($cs->preparer_id == $current_fin_officer->id)) ? true : false;
                                                        $batal_selepas_lulus = (($cs->transaction_status_id == 4) && ($cs->ispeks_integration_id == 0) && ($cs->approver_id == $current_fin_officer->id) ) ? true : false;
                                                @endphp

                                                @php
                                                    //--------------------------------------------------------------------------------------------------------
                                                    // GET LATEST LOG BEFORE KUIRI = CURRENT : KUIRI & BEFORE KUIRI : SAH SIMPAN
                                                    // Penyedia batalkan penyata pemungut selepas pegawai penyemak kuiri
                                                    //--------------------------------------------------------------------------------------------------------
                                                    $collector_statement_log = \App\Models\CollectorStatementLog::get_collector_statement_log($cs->id);
                                                    $remove_log  = $collector_statement_log->pop();
                                                    $last_log    = $collector_statement_log->last();

                                                    $batal_selepas_sah_simpan = (($cs->transaction_status_id == 5) && ($last_log->transaction_status_id == 2) && ($cs->preparer_id == $current_fin_officer->id) ) ? true : false;
                                                @endphp

                                                <tr>
                                                    <td class="text-center" tabindex="0" width="5%">{{$loop->iteration}}</td>
                                                    <td class="text-center" width="20%">{{ $cs->collector_statement_no}}</td>
                                                    <td class="text-center" width="15%">{{ convertDateSys($cs->collector_statement_date)}}</td>
                                                    <td class="text-center" width="20%">{{ convertDateSys($cs->collector_statement_date_from).' - '.convertDateSys($cs->collector_statement_date_to)}}</td>
                                                    <td class="text-center" width="15%">{{ $cs->collection_amount}}</td>
                                                    <td class="text-center" width="10%" ><span class="badge {{ $badge_transaction_status }} p-2 text-black">{{ $cs->transaction_status->status ?? ''}}</span></td>
                                                    <td class="text-center" width="15%">
                                                    <div class="btn-group" role="group">
                                                            @if(checkPolicy("U") || checkPolicy("V"))
                                                                <a href="{{ route('collectorStatement.edit', ['id' => $cs->id, 'tab' => 1]) }}" class="btn btn-outline-primary px-2 py-1 tooltip-icon">
                                                                    <span class="tooltip-text">{{ __('button.buka') }}</span> <i class="{{ __('icon.open_folder') }}"></i>
                                                                </a>
                                                            @endif
                                                            @if(checkPolicy("D") && ($batal == true || $batal_selepas_lulus == true || $batal_selepas_sah_simpan == true))
                                                                <a class="btn btn-outline-primary px-2 py-1 tooltip-icon swal-batal-penyata" data-index="{{ $cs->id }}" data-page="index" >
                                                                    <span class="tooltip-text">{{ __('button.batal') }}</span> <i class="{{ __('icon.delete') }}"></i>
                                                                </a>
                                                            @endif
                                                        </div>
                                                        <form method="POST" action="{{ route('collectorStatement.cancel') }}" class="delete-form-list">
                                                            {{ csrf_field() }}
                                                            {{ method_field('DELETE') }}
                                                            <input type="hidden" name="id" id="id" value="{{ $cs->id }}">
                                                            <input type="hidden" name="cancel_remarks" id="cancel_remarks_{{ $cs->id }}" >
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
                                    <table  class="table table-striped table-bordered dt-responsive wrap w-100 dataTable indextable" role="grid">
                                        <thead class="bg-primary bg-gradient text-white">
                                            <tr role="row">
                                                <th class="text-center" width="5%">Bil</th>
                                                <th class="text-center" width="20%">No. Penyata Pemungut</th>
                                                <th class="text-center" width="15%">Tarikh Penyata Pemungut</th>
                                                <th class="text-center" width="20%">Tempoh Pungutan</th>
                                                <th class="text-center" width="15%">Jumlah Terimaan (RM)</th>
                                                <th class="text-center" width="10%">Status</th>
                                                <th class="text-center" width="15%">Tindakan</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($collector_statement_history as $cs)
                                                @php
                                                    $lulus = ($cs->transaction_status_id == 4);
                                                    $badge_transaction_status = (($lulus) ? "bg-success" : "bg-warning");
                                                    $badge_transaction_status = ((in_array($cs->transaction_status_id, [6, 7, 8])) ? "bg-danger" : $badge_transaction_status);

                                                    $batal = (($cs->transaction_status_id == 1) && ($cs->preparer_id == $current_fin_officer->id)) ? true : false;
                                                    $batal_selepas_lulus = (($cs->transaction_status_id == 4) && ($cs->ispeks_integration_id == 0) && ($cs->approver_id == $current_fin_officer->id) ) ? true : false;
                                                @endphp

                                                @php
                                                    //--------------------------------------------------------------------------------------------------------
                                                    // GET LATEST LOG BEFORE KUIRI = CURRENT : KUIRI & BEFORE KUIRI : SAH SIMPAN
                                                    // Penyedia batalkan penyata pemungut selepas pegawai penyemak kuiri
                                                    //--------------------------------------------------------------------------------------------------------
                                                    $collector_statement_log = \App\Models\CollectorStatementLog::get_collector_statement_log($cs->id);
                                                    $remove_log  = $collector_statement_log->pop();
                                                    $last_log    = $collector_statement_log->last();

                                                    $batal_selepas_sah_simpan = (($cs->transaction_status_id == 5) && ($last_log->transaction_status_id == 2) && ($cs->preparer_id == $current_fin_officer->id) ) ? true : false;
                                                @endphp
                                                <tr>
                                                    <td class="text-center" tabindex="0"  width="5%">{{ $loop->iteration }}</td>
                                                    <td class="text-center" width="20%">{{ $cs->collector_statement_no}}</td>
                                                    <td class="text-center" width="15%">{{ convertDateSys($cs->collector_statement_date)}}</td>
                                                    <td class="text-center" width="20%">{{ convertDateSys($cs->collector_statement_date_from).' - '.convertDateSys($cs->collector_statement_date_to)}}</td>
                                                    <td class="text-center" width="15%">{{ $cs->collection_amount}}</td>
                                                    <td class="text-center" width="10%"><span class="badge {{ $badge_transaction_status }} p-2 text-black">{{ $cs->transaction_status->status ?? ''}}</span></td>
                                                    <td class="text-center" width="15%">
                                                        <div class="btn-group" role="group">
                                                            @if(checkPolicy("U") || checkPolicy("V"))
                                                                <a href="{{ route('collectorStatement.edit', ['id' => $cs->id, 'tab' => 2]) }}" class="btn btn-outline-primary px-2 py-1 tooltip-icon">
                                                                    <span class="tooltip-text">{{ __('button.buka') }}</span> <i class="{{ __('icon.open_folder') }}"></i>
                                                                </a>
                                                            @endif
                                                            @if(checkPolicy("D") && ($batal == true || $batal_selepas_lulus == true || $batal_selepas_sah_simpan == true))
                                                                <a class="btn btn-outline-primary px-2 py-1 tooltip-icon swal-batal-penyata" data-index="{{ $cs->id }}" data-page="index" >
                                                                    <span class="tooltip-text">{{ __('button.batal') }}</span> <i class="{{ __('icon.delete') }}"></i>
                                                                </a>
                                                            @endif
                                                            @if($lulus)
                                                                <a href="{{route('collectorStatement.generate_pdf', [ 'id' => $cs->id ]) }}" target="_blank" class="btn btn-outline-primary px-2 py-1 tooltip-icon" >
                                                                    <span class="tooltip-text">{{ __('button.muat_turun_pdf') }}</span> <i class="{{ __('icon.file_pdf') }}"></i>
                                                                </a>
                                                                {{-- <form method="POST" action="{{ route('collectorStatement.index') }}" >
                                                                    {{ csrf_field() }}

                                                                    <a href="#"  onClick="setFormTarget(this)"  class="btn btn-outline-primary px-2 py-1 tooltip-icon" >
                                                                        <span class="tooltip-text">{{ __('button.muat_turun_pdf') }}</span> <i class="{{ __('icon.file_pdf') }}"></i>
                                                                    </a>
                                                                </form> --}}

                                                            @endif
                                                        </div>
                                                        <form method="POST" action="{{ route('collectorStatement.cancel') }}" class="delete-form-list">
                                                            {{ csrf_field() }}
                                                            {{ method_field('DELETE') }}
                                                            <input type="hidden" name="id" id="id" value="{{ $cs->id }}">
                                                            <input type="hidden" name="cancel_remarks" id="cancel_remarks_{{ $cs->id }}" >
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
        </div>
    </div> <!-- end col -->
</div>
<!-- end row -->

@endsection
@section('script')
    <script src="{{ URL::asset('assets/js/pages/CollectorStatement/collectorStatement.js')}}"></script>
    <script src="{{ URL::asset('assets/libs/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
@endsection
