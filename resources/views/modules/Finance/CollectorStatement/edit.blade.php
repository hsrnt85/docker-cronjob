@extends('layouts.master')
@section('content')

<div >
    <form class="custom-validation" id="form" method="post" action="{{ route('collectorStatement.update') }}" >
    {{ csrf_field() }}

    <div class="col-12">
        <div class="card">
            <div class="card-body">

                <div id="section_alert">
                    <div class="alert alert-warning">
                        <div id="alert_data_more_than_200"></div>
                    </div>
                </div>

                {{-- PAGE TITLE --}}
                <div class="border-bottom border-primary mb-4"><h4 class="card-title">{{ getPageTitle(1) }}</h4></div>

                <input class="form-control" type="hidden" id="id" name="id" value="{{ $collectorStatement->id}}">
                <input type="hidden" id="page" name="page" value="edit">
                <input type="hidden" id="current_status" name="current_status" value="{{ $collectorStatement->transaction_status_id}}">
                <input type="hidden" id="btn_type_input" name="btn_type" ><br>

                <input type="hidden" id="current_officer" value="{{ $login_officer}}">
                <input type="hidden" id="preparer_id"  value="{{ $collectorStatement->preparer_id}}">
                <input type="hidden" id="checker_id"   value="{{ $collectorStatement->checker_id}}">
                <input type="hidden" id="approver_id"  value="{{$collectorStatement->approver_id}}">

                <input type="hidden" name="proses_sedia" id="proses_sedia" value="{{ $proses_sedia}}">
                <input type="hidden" name="proses_semak" id="proses_semak"value="{{ $proses_semak}}">
                <input type="hidden" name="proses_lulus" id="proses_lulus"value="{{$proses_lulus}}">

                <input type="hidden" name="tab_id" value="{{$tab_id}}">

                <!-- tab 1-->
                <ul class="nav nav-tabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="nav-penyata-pemungut" data-bs-toggle="tab" href="#penyataPemungut" role="tab" aria-controls="penyataPemungutTab" >Penyata Pemungut</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="nav-senarai-kutipan-hasil" data-bs-toggle="tab" href="#senaraiKutipanHasil" role="tab" aria-controls="senaraiKutipanHasilTab">Senarai Terimaan Hasil</a>
                    </li>
                </ul>
                @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
                <div class="tab-content p-4">

                    {{-- tab 1 --}}
                    <div class="tab-pane fade show active" id="penyataPemungut" role="tabpanel">

                        @php
                            $disabled_sedia = (!$proses_sedia) ? "disabled" : ""; //section penyedia
                            $disabled_semak = (!$proses_semak) ? "disabled" : ""; //section penyemak
                            $disabled_lulus = (!$proses_lulus) ? "disabled" : ""; //section pelulus
                        @endphp

                        @php
                            $batal = (($collectorStatement->transaction_status_id == 1) && ($collectorStatement->preparer_id == $login_officer)) ? true : false;
                            $batal_selepas_lulus = (($collectorStatement->transaction_status_id == 4) && ($collectorStatement->ispeks_integration_id == 0) && ($collectorStatement->approver_id == $login_officer) ) ? true : false;

                            //--------------------------------------------------------------------------------------------------------
                            // GET LATEST LOG BEFORE KUIRI = CURRENT : KUIRI & BEFORE KUIRI : SAH SIMPAN
                            // Penyedia batalkan penyata pemungut selepas pegawai penyemak kuiri
                            //--------------------------------------------------------------------------------------------------------
                            $remove_log  = $collectorStatementLog->pop();
                            $last_log    = $collectorStatementLog->last();

                            $batal_selepas_sah_simpan = (($collectorStatement->transaction_status_id == 5) && ($last_log->transaction_status_id == 2) && ($collectorStatement->preparer_id == $login_officer) ) ? true : false;
                        @endphp

                        <h4 class="card-title text-primary mb-3">Maklumat Induk</h4>

                        <div class="mb-3 row">
                            <label class="col-md-2 col-form-label">Tahun Kewangan</label>
                            <div class="col-md-7">
                                <input class="form-control" value="{{currentYear()}}" disabled>
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label class="col-md-2 col-form-label">Jabatan Penyedia</label>
                            <div class="col-md-7">
                                <input class="form-control" value="{{$finance_department->department_name}}" disabled>
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label class="col-md-2 col-form-label">PTJ Penyedia</label>
                            <div class="col-md-7">
                                <input class="form-control" value="{{$finance_department->ptj_name }}" disabled>
                            </div>
                        </div>

                        <hr><h4 class="card-title text-primary mt-2 mb-3">Maklumat Penyata Pemungut</h4>

                        <div class="mb-3 row">
                            <label for="no" class="col-md-2 col-form-label">No. Penyata Pemungut</label>
                            <div class="col-md-7">
                                <input class="form-control" value="{{$collectorStatement->collector_statement_no}}" readonly>
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label for="date" class="col-md-2 col-form-label">Tarikh Penyata Pemungut</label>
                            <div class="col-md-7">
                                <div class="input-group" id="datepicker2">
                                    <input class="form-control "  type="text" value="{{convertDateSys($collectorStatement->collector_statement_date)}}" disabled>
                                    <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label for="date_from" class="col-md-2 col-form-label">Tempoh Pungutan Dari</label>
                            <div class="col-md-7">
                                <div class="input-group" id="datepicker2">
                                    <input class="form-control  @error('date_from') is-invalid @enderror" name="date_from" id="date_from" value="{{convertDateSys($collectorStatement->collector_statement_date_from)}}"
                                  {{--{{ $disabled_sedia --}}  disabled>
                                    <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label for="date_to" class="col-md-2 col-form-label">Tempoh Pungutan Hingga</label>
                            <div class="col-md-7">
                                <div class="input-group" id="datepicker2">
                                    <input class="form-control  @error('date_to') is-invalid @enderror" name="date_to" id="date_to"  value="{{convertDateSys($collectorStatement->collector_statement_date_to)}}"
                                    {{--{{ $disabled_sedia --}}  disabled>
                                    <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label for="bank_slip_date" class="col-md-2 col-form-label">Tarikh Dibankkan</label>
                            <div class="col-md-7">
                                <div class="input-group" id="datepicker2">
                                    <input class="form-control" type="text" name="bank_slip_date" value="{{convertDateSys($collectorStatement->bank_slip_date)}}" disabled>
                                    <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label for="payment_method" class="col-md-2 col-form-label">Kaedah Bayaran</label>
                            <div class="col-md-7">
                                <input class="form-control" type="text" name="payment_method" value="{{ $collectorStatement->payment_method?->payment_method }}" disabled>
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label for="bank_name" class="col-md-2 col-form-label">Nama Bank </label>
                            <div class="col-md-7">
                                <input class="form-control" type="text" id="selected-bank-name" value="{{ $collectorStatement->transit_bank->bank->bank_name }}" readonly>
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label for="bank_slip" class="col-md-2 col-form-label">No. Slip Bank</label>
                            <div class="col-md-7">
                                <input class="form-control" type="text" id="bank_slip" name="bank_slip"value="{{ $collectorStatement->bank_slip_no }}" readonly>
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label for="purpose" class="col-md-2 col-form-label">Butiran</label>
                            <div class="col-md-7">
                                <input class="form-control  @error('purpose') is-invalid @enderror" id="purpose" name="purpose" value="{{ $collectorStatement->description }}"
                                required data-parsley-required-message="{{ setMessage('purpose.required') }}"  data-parsley-errors-container="#errorPurpose"  {{ $disabled_sedia }}>
                                <div id="errorPurpose"></div>
                            </div>
                        </div>

                        <hr><h4 class="card-title text-primary mt-2 mb-3">Maklumat Vot Hasil</h4>

                        @include('modules.Finance.CollectorStatement.maklumat-vot-hasil-edit')

                        <hr><h4 class="card-title text-primary mt-2 mb-3">Maklumat Pegawai</h4>

                        <div class="mb-3 row">
                            <label for="preparer" class="col-md-2 col-form-label">Disediakan Oleh</label>
                            <div class="col-md-7">
                                <select class="form-select select2" value="" disabled><option>{{$collectorStatement->preparer->user->name}}</option></select>
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label for="checker" class="col-md-2 col-form-label ">Disemak Oleh</label>
                            <div class="col-md-7">
                                <select class="form-select select2  @error('checker') is-invalid @enderror" id="checker" name="checker" required data-parsley-required-message="{{ setMessage('checker.required') }}" data-parsley-errors-container="#error-checker"  {{ $disabled_sedia }}>
                                    <option value="">-- Pilih Pegawai --</option>
                                    @foreach($pegawaiPenyemakList as $officer)
                                        <option value="{{ $officer->fin_officer_id }}" @if($officer->fin_officer_id == $collectorStatement->checker_id) selected @endif>{{ $officer->name }}</option>
                                    @endforeach
                                </select>
                                <div id="error-checker"></div>
                            </div>
                        </div>

                        <div id="hide-for-preparer">
                            <div class="mb-3 row">
                                <label for="checking_status" class="col-md-2 col-form-label ">Status Semakan</label>
                                <div class="col-md-7">
                                    @foreach( $checkingList as $status )  {{--radio: kuiri / semak --}}
                                        <div class="form-check form-check-inline" >
                                            <input class=" form-check-input me-2  checking_status @error('checking_status') is-invalid @enderror" type="radio" name="checking_status"  value="{{ $status->id }}" @if((in_array($current_status , [3,4,7]) ) && ($status->id == 3)) checked  @endif
                                            required data-parsley-required-message="{{ setMessage('checking_status.required') }}" data-parsley-errors-container="#error-checking-status" {{$disabled_semak }}>
                                            <label class="form-check-label me-4" for="checking_status">
                                                {{ $status->status }}
                                            </label>
                                        </div>
                                    @endforeach
                                        <div id="error-checking-status"></div>
                                </div>
                            </div>

                            <div class="mb-3 row">
                                <label for="approver" class="col-md-2 col-form-label ">Diluluskan Oleh</label>
                                <div class="col-md-7">
                                    <select class="form-select select2 @error('approver') is-invalid @enderror" id="approver" name="approver"
                                        required data-parsley-required-message="{{ setMessage('approver.required') }}"
                                        data-parsley-errors-container="#error-approver"  {{ $disabled_semak }}>
                                        <option value="">-- Pilih Pegawai --</option>
                                        @foreach($pegawaiPelulusList as $officer)
                                            <option value="{{ $officer->fin_officer_id }}"  @if($officer->fin_officer_id == $collectorStatement->approver_id) selected @endif>{{ $officer->name }}</option>
                                        @endforeach
                                    </select>
                                    <div id="error-approver"></div>
                                    @error('approver')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div id="hide-for-checker">
                                <div class="mb-3 row">
                                    <label for="approval_status" class="col-md-2 col-form-label ">Status Kelulusan</label>
                                    <div class="col-md-7">
                                        @foreach( $approvalList as $status )  {{--radio: kuiri / lulus --}}
                                            <div class="form-check form-check-inline" >
                                                <input class="form-check-input me-2  approval_status @error('approval_status') is-invalid @enderror" type="radio" name="approval_status" value="{{ $status->id }}" @if((in_array($current_status , [4,7]) && ($status->id == 4))) checked  @endif
                                                required data-parsley-required-message="{{ setMessage('approval_status.required') }}" data-parsley-errors-container="#error-approval-status" {{$disabled_lulus }}>
                                                <label class="form-check-label me-4" for="approval_status">
                                                    {{ $status->status }}
                                                </label>
                                            </div>
                                        @endforeach
                                        <div id="error-approval-status"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3 row" id="section-kuiri">
                            <hr><label for="kuiri_remarks" class="col-md-2 col-form-label ">Sebab Kuiri</label>
                            <div class="col-md-7">
                                <textarea class="form-control  @error('kuiri_remarks') is-invalid @enderror" rows="5" id="kuiri_remarks" name="kuiri_remarks" value="{{ old ('kuiri_remarks') }}" required data-parsley-required-message="{{ setMessage('kuiri_remarks.required') }}"
                                    data-parsley-errors-container="#error-remarks"></textarea>
                                <div id="error-remarks"></div>
                                @error('kuiri_remarks')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        @if(!$log_kuiri->isEmpty())
                            <hr><h4 class="card-title text-primary mt-2 mb-3">Maklumat Kuiri</h4>
                            <div class="mb-3 row">
                                <label class="col-md-2">Log Kuiri</label>
                                <div class="col-md-7">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-striped">
                                            <thead class="bg-primary bg-gradient text-white">
                                                <tr class="text-center">
                                                    <th width="5%;">Bil.</th>
                                                    <th width="13%">Status</th>
                                                    <th width="14%">Tarikh</th>
                                                    <th width="25%">Nama Pegawai</th>
                                                    <th width="30%">Sebab Kuiri/Batal</th>
                                                    <th width="13%">Status Kuiri</th>
                                                </tr>
                                            </thead>
                                            <tbody >
                                                @foreach ($log_kuiri as $i => $log)
                                                    @php
                                                        $isLast = ($currentKey == $totalItems - 1);
                                                        $currentKey++;
                                                    @endphp
                                                    <tr class="odd">
                                                        <td class="text-center" tabindex="0">{{ $loop->iteration }}</td>
                                                        <td class="text-center">{{ $log->transaction_status->status }}</td>
                                                        <td class="text-center">{{ convertDateSys($log->date)}}</td>
                                                        <td class="text-center">{{ $log->finance_officer_name }}</td>
                                                        <td class="text-center">{{ $log->remarks }}</td>
                                                        <td class="text-center"> @if($isLast && (in_array($current_status , [5,1]))) {{''}} @else {{$log->log_status}} @endif</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        @endif

                        {{-- BATAL --}}
                        @if($current_log->transaction_status_id > 5)
                            <hr><h4 class="card-title text-primary mt-2 mb-3">Maklumat Pembatalan</h4>
                            <div class="mb-3 row">
                                <label for="approval_status" class="col-md-2 col-form-label ">Status</label>
                                <div class="col-md-7">
                                    <span class="badge bg-danger p-2 fs-7 text-black">{{$current_log->transaction_status->status}}</span>
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label for="approval_status" class="col-md-2 col-form-label ">Sebab Batal</label>
                                <div class="col-md-7">
                                    <input class="form-control" value="{{strToUpper($current_log->remarks)}}" readonly>
                                </div>
                            </div>
                        @endif

                    <div class="mb-3 row">
                        <div class="col-sm-12 pr-5">
                            <a href="{{ route('collectorStatement.index') . '#' . $tab }}"  class="btn btn-secondary float-end me-2">{{ __('button.kembali') }}</a>
                            @if($proses_sedia)
                                <button type="submit" id="btn-hantar" class="btn btn-primary float-end me-2 swal-validate-hantar">{{ __('button.hantar') }}</button>
                            @endif
                            @if($proses_sedia || $proses_lulus)
                                <button type="submit" id="btn-submit" class="btn btn-primary float-end me-2 swal-simpan-penyata">{{ __('button.simpan') }}</button>
                            @endif
                            @if($proses_semak)
                                <button type="submit" id="btn-submit" class="btn btn-primary float-end me-2 swal-simpan-penyata">{{ __('button.simpan') }} & {{ __('button.hantar') }}</button>
                            @endif
                            @if($current_status == 2 && $proses_sedia )
                                    <button type="submit" class="btn btn-primary float-end me-2 swal-kemaskini-penyata">{{ __('button.kemaskini') }}</button>
                            @endif
                            @if(checkPolicy("D") && ($batal == true || $batal_selepas_lulus == true || $batal_selepas_sah_simpan == true))
                                <button type="submit" class="btn btn-danger float-end me-2 swal-batal-penyata" data-index="{{ $collectorStatement->id }}" data-page="edit">{{ __('button.batal') }}</button>
                            @endif
                        </div>
                    </div>
                    </div>
                    {{-- end tab 1 --}}

                    {{-- tab 2 --}}
                    <div class="tab-pane fade mt-4" id="senaraiKutipanHasil" role="tabpanel" aria-labelledby="nav-senarai-kutipan-hasil">
                        @include('modules.Finance.CollectorStatement.kutipan-hasil-list-edit')
                    </div>
                    {{-- end tab 2 --}}

                </div>{{-- end tab content --}}

            </div> {{-- end card body --}}

        </div>{{-- end card --}}

    </div>{{-- end col1-12 --}}

    </form>

    <form method="POST" id="form-cancel" action="{{ route('collectorStatement.cancel') }}" class="delete-form-list">
        {{ csrf_field() }}
        {{ method_field('DELETE') }}
        <input type="hidden" name="id" id="id" value="{{ $collectorStatement->id }}">
        <input type="hidden" name="cancel_remarks" id="cancel_remarks_{{ $collectorStatement->id }}" >
    </form>

</div>{{-- end row  --}}

@endsection

@section('script')
<script src="{{ URL::asset('assets/js/pages/CollectorStatement/collectorStatement.js')}}"></script>
@endsection

