@extends('layouts.master')
@section('content')

<div >
    <form class="custom-validation" id="form" method="post" action="{{ route('journalAdjustment.update') }}" >
        {{ csrf_field() }}

        <div class="col-12">
            <div class="card">
                <div class="card-body">

                    {{-- PAGE TITLE --}}
                    <div class="border-bottom border-primary mb-4"><h4 class="card-title">{{ getPageTitle(1) }}</h4></div>

                    <input class="form-control" type="hidden" id="id" name="id" value="{{ $journal->id}}">
                    <input type="hidden" id="page" name="page" value="edit">
                    <input type="hidden" id="current_status" name="current_status" value="{{ $current_status}}">
                    <input type="hidden" id="btn_type_input" name="btn_type" ><br>

                    <input type="hidden" id="current_officer" value="{{ $login_officer}}">
                    <input type="hidden" id="preparer_id" value="{{ $journal->preparer_id}}">
                    <input type="hidden" id="checker_id"  value="{{ $journal->checker_id}}">
                    <input type="hidden" id="approver_id" value="{{$journal->approver_id}}">

                    <input type="hidden" name="proses_sedia" id="proses_sedia" value="{{ $proses_sedia}}">
                    <input type="hidden" name="proses_semak" id="proses_semak"value="{{ $proses_semak}}">
                    <input type="hidden" name="proses_lulus" id="proses_lulus"value="{{$proses_lulus}}">

                    <input type="hidden" name="tab_id" value="{{$tab_id}}">

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @php
                        $disabled_sedia = (!$proses_sedia) ? "disabled" : ""; //section penyedia
                        $disabled_semak = (!$proses_semak) ? "disabled" : ""; //section penyemak
                        $disabled_lulus = (!$proses_lulus) ? "disabled" : ""; //section pelulus
                    @endphp

                    @php
                        $batal = (($current_status == 1) && ($journal->preparer_id == $login_officer)) ? true : false;
                        $batal_selepas_lulus = (($current_status == 4) && ($journal->ispeks_integration_id == 0) && ($journal->approver_id == $login_officer) ) ? true : false;

                        //--------------------------------------------------------------------------------------------------------
                        // GET LATEST LOG BEFORE KUIRI = CURRENT : KUIRI & BEFORE KUIRI : SAH SIMPAN
                        // Penyedia batalkan penyata pemungut selepas pegawai penyemak kuiri
                        //--------------------------------------------------------------------------------------------------------
                        $remove_log  = $journal_log->pop();
                        $last_log    = $journal_log->last();

                        $batal_selepas_sah_simpan = (($current_status == 5) && ($last_log->transaction_status_id == 2) && ($journal->preparer_id == $login_officer) ) ? true : false;
                    @endphp

                    <h4 class="card-title text-primary mb-3">Maklumat Induk</h4>

                    <div class="mb-3 row">
                        <label class="col-md-2 col-form-label">Tahun Kewangan</label>
                        <div class="col-md-9">
                            <input class="form-control" value="{{currentYear()}}" disabled>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label class="col-md-2 col-form-label">Jabatan Penyedia</label>
                        <div class="col-md-9">
                            <input class="form-control" value="{{$finance_department->department_name}}" disabled>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label class="col-md-2 col-form-label">PTJ Penyedia</label>
                        <div class="col-md-9">
                            <input class="form-control" value="{{$finance_department->ptj_name }}" disabled>
                        </div>
                    </div>

                    <hr><h4 class="card-title text-primary mt-2 mb-3">Maklumat Jurnal</h4>

                    <div class="mb-3 row">
                        <label class="col-md-2 col-form-label">No. Jurnal Pelarasan</label>
                        <div class="col-md-9">
                            <input class="form-control"  value="{{$journal->journal_no}}" disabled>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label class="col-md-2 col-form-label">Jenis Jurnal Pelarasan</label>
                        <div class="col-md-9">
                            <select class="form-select select2" disabled>
                                <option value="{{ $journal->id }}" >{{ $journal->journal_type->journal_type}}</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label class="col-md-2 col-form-label">Tarikh</label>
                        <div class="col-md-9">
                            <div class="input-group" id="datepicker2">
                                <input class="form-control"  value="{{convertDateSys($journal->journal_date)}}" disabled>
                                <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label class="col-md-2 col-form-label">No. Penyata Pemungut</label>
                        <div class="col-md-9">
                            <select class="form-select select2" disabled>
                                <option >{{ $journal->collector_statement->collector_statement_no }}</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="description" class="col-md-2 col-form-label">Keterangan</label>
                        <div class="col-md-9">
                            <textarea class="form-control  @error('description') is-invalid @enderror"  name="description"  rows="4"
                            required data-parsley-required-message="{{ setMessage('description.required') }}"  data-parsley-errors-container="#error-desc" {{ $disabled_sedia }}>{{upperText($journal->description)}}</textarea>
                            <div id="error-desc"></div>
                        </div>
                    </div>

                    <hr><h4 class="card-title text-primary mt-2 mb-3">Maklumat Terperinci</h4>
                    @if(!$proses_sedia && $current_status != 5)
                        <div class="mb-3 row">
                            <label class="col-md-2" >Vot / Kod Akaun</label>
                            <div class="col-md-9">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped" id="table-vot-akaun" data-list="{{$journal_vot_list->count()}}">
                                        <thead class="bg-primary bg-gradient text-white">
                                            <tr class="text-center">
                                                <th class="text-center" width="5%">Bil.</th>
                                                <th class="text-center" width="50%">Kod Akaun</th>
                                                <th class="text-center" width="20%">Amaun Debit (RM)</th>
                                                <th class="text-center" width="20%">Amaun Kredit (RM)</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php $total_debit = 0.00; $total_credit = 0.00; @endphp
                                            @foreach($journal_vot_list as $i => $votlist)
                                                <tr>
                                                    <td class="text-center" tabindex="0" width="5%">{{$loop->iteration.'.'}}</td>
                                                    <td class="text-left justify-between">{{$votlist->income_account?->income_code.' - '.$votlist->income_account?->income_code_description }}</td>
                                                    <td class="text-center">{{$votlist->debit_amount }}</td>
                                                    <td class="text-center">{{$votlist->credit_amount }}</td>
                                                </tr>
                                                @php 
                                                    $total_debit += $votlist->debit_amount;
                                                    $total_credit += $votlist->credit_amount;
                                                @endphp
                                            @endforeach
                                            <tr>
                                                <td colspan="2" class="text-center"><b>Jumlah Keseluruhan (RM)</b></td>
                                                <td class="text-center">{{numberFormatNoComma($total_debit)}}</td>
                                                <td class="text-center">{{numberFormatNoComma($total_credit)}}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="mb-3 row">
                            <label class="col-md-2" >Vot / Kod Akaun</label>
                            <div class="col-md-9">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped" id="table-vot-akaun" data-list="{{$journal_vot_list->count()}}">
                                        <thead class="bg-primary bg-gradient text-white">
                                            <tr class="text-center">
                                                <th class="text-center" width="50%">Kod Akaun</th>
                                                <th class="text-center" width="20%">Amaun Debit (RM)</th>
                                                <th class="text-center" width="20%">Amaun Kredit (RM)</th>
                                                @if($proses_sedia)<th class="text-center" width="10%"><a onclick="duplicateRow();" class="btn btn-success btn-sm" ><i class="mdi mdi-plus mdi-18px"></i></a></th>@endif
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if($journal_vot_list->count()==0)
                                                <tr id="tbl-row-0" >
                                                    <td>
                                                        <select class="form-select select2 select2-account account-code" width="100%" id="income_code0" name="income_code[]" value="{{ old('income_code') }}"
                                                        required data-parsley-required-message="{{ setMessage('income_code.required') }}" >
                                                            <option value="">-- Sila Pilih --</option>
                                                            @foreach($get_income_code as $code)
                                                                <option value="{{ $code->id }}" >{{ $code->income_code.' - '.$code->income_code_description }}</option>
                                                            @endforeach
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <input class="form-control text-start debit debit0" type="text" id="debit0" name="debit[]" value="{{ old('debit','') }}"
                                                        oninput="checkDecimal(this)" onfocus="focusInput(this);" placeholder="0.00"
                                                        required data-parsley-required-message="{{ setMessage('debit.required') }}" >
                                                    </td>
                                                    <td>
                                                        <input class="form-control text-start credit credit0" type="text" id="credit0" name="credit[]" value="{{ old('credit', '') }}"
                                                        oninput="checkDecimal(this)" onfocus="focusInput(this);" placeholder="0.00"
                                                        required data-parsley-required-message="{{ setMessage('credit.required') }}" >
                                                    </td>
                                                    <td>
                                                        <input type="hidden" id="votlist_id0" name="votlist_id[]" value="0">
                                                        <a id="btnRemove0" class='btnRemove btn btn-warning btn-sm' data-row-index="0"><i class='mdi mdi-minus mdi-16px'></i></a>
                                                    </td>
                                                </tr>
                                            @else
                                                @foreach($journal_vot_list as $i => $votlist)
                                                    <tr id="tbl-row-{{$i}}">
                                                        <td>
                                                            <select class="form-select select2 select2-account account-code @error('income_code.'.$i) is-invalid @enderror" id="income_code{{$i}}" name="income_code[]" value="{{ old('income_code.'.$i,'') }}"
                                                                required data-parsley-required-message="{{ setMessage('income_code.required') }}">
                                                                <option value="">-- Sila Pilih --</option>
                                                                @foreach($get_income_code as $code)
                                                                    @if($code->id == $votlist->income_account_code_id)
                                                                        <option value="{{ $code->id }}" selected>{{ $code->income_code.' - '.$code->income_code_description }}</option>
                                                                    @else
                                                                        <option value="{{ $code->id }}">{{ $code->income_code.' - '.$code->income_code_description }}</option>
                                                                    @endif
                                                                @endforeach
                                                            </select>
                                                        </td>
                                                        <td>
                                                            <input class="form-control @error('debit.'.$i) is-invalid @enderror text-start debit debit{{$i}}" type="text" id="debit{{$i}}" name="debit[]" value="{{ $votlist->debit_amount }}" placeholder="0.00" data-inputmask="'alias': 'numeric', 'groupSeparator': ',', 'digits': 2, 'digitsOptional': false "
                                                            required data-parsley-required-message="{{ setMessage('debit.required.'.$i,'') }}">
                                                            @error('debit.'.$i,'')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </td>
                                                        <td>
                                                            <input class="form-control @error('credit') is-invalid @enderror text-start credit credit{{$i}}" type="text" id="credit{{$i}}" name="credit[]" value="{{ $votlist->credit_amount }}" placeholder="0.00"  data-inputmask="'alias': 'numeric', 'groupSeparator': ',', 'digits': 2, 'digitsOptional': false "
                                                            required data-parsley-required-message="{{ setMessage('credit.required.'.$i,'') }}">
                                                            @error('credit')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </td>
                                                        <td class="text-center">
                                                            {{-- <input id="row_index"> {{$i}} --}}
                                                            <input type="hidden" id="votlist_id{{$i}}" name="votlist_id[]" value="{{ $votlist->id }}">
                                                            <a id="btnRemove{{$i}}" class="btnRemove btn btn-warning btn-sm" data-row-index="{{$i}}"><i class="mdi mdi-minus mdi-18px"></i></a>
                                                        </td>
                                                    </tr> 
                                                @endforeach 
                                            @endif
                                              
                                        </tbody> 
                             
                                        <tfoot >
                                            <tr>
                                                <td>Jumlah Keseluruhan (RM)</td>
                                                <td><input class="form-control" id="total_debit" placeholder="0.00" readonly></td>
                                                <td><input class="form-control" id="total_credit" placeholder="0.00" readonly></td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td colspan="4">
                                                    <label class="col-lg-12 mt-3"><span id="msg_error" class="error"></span> </label>
                                                </td>
                                            </tr>
                                        </tfoot>
                                   
                                    </table>
                                </div>
                            </div>
                        </div>
                    @endif

                    <hr><h4 class="card-title text-primary mt-2 mb-3">Maklumat Pegawai</h4>

                    <div class="mb-3 row">
                        <label class="col-md-2 col-form-label">Disediakan Oleh</label>
                        <div class="col-md-9">
                            <select class="form-select select2" disabled><option>{{$journal->preparer->user->name}}</option></select>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="checker" class="col-md-2 col-form-label ">Disemak Oleh</label>
                        <div class="col-md-9">
                            <select class="form-select select2  @error('checker') is-invalid @enderror" id="checker" name="checker" required data-parsley-required-message="{{ setMessage('checker.required') }}" data-parsley-errors-container="#error-checker"  {{ $disabled_sedia }}>
                                <option value="">-- Pilih Pegawai --</option>
                                @foreach($checker_list as $checker)
                                    <option value="{{ $checker->fin_officer_id }}" @if($checker->fin_officer_id == $journal->checker_id) selected @endif>{{ $checker->name }}</option>
                                @endforeach
                            </select>
                            <div id="error-checker"></div>
                        </div>
                    </div>

                    <div id="hide-for-preparer">
                        <div class="mb-3 row">
                            <label for="checking_status" class="col-md-2 col-form-label ">Status Semakan</label>
                            <div class="col-md-9">
                                @foreach( $checking_list as $status )  {{--radio: kuiri / semak --}}
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

                        {{-- <hr> --}}
                        <div class="mb-3 row">
                            <label for="approver" class="col-md-2 col-form-label ">Diluluskan Oleh</label>
                            <div class="col-md-9">
                                <select class="form-select select2 @error('approver') is-invalid @enderror" id="approver" name="approver"
                                    required data-parsley-required-message="{{ setMessage('approver.required') }}"
                                    data-parsley-errors-container="#error-approver"  {{ $disabled_semak }}>
                                    <option value="">-- Pilih Pegawai --</option> 
                                    @foreach($approver_list as $approver)
                                        <option value="{{ $approver->fin_officer_id }}"  @if($approver->fin_officer_id == $journal->approver_id) selected @endif>{{ $approver->name }}</option>
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
                                <div class="col-md-9">
                                    @foreach( $approval_list as $status )  {{--radio: kuiri / lulus --}}
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
                        <label for="kuiri_remarks" class="col-md-2 col-form-label ">Sebab Kuiri</label>
                        <div class="col-md-9">
                            <textarea class="form-control  @error('kuiri_remarks') is-invalid @enderror" rows="5" id="kuiri_remarks" name="kuiri_remarks" value="{{ old ('kuiri_remarks') }}" required data-parsley-required-message="{{ setMessage('kuiri_remarks.required') }}"
                                data-parsley-errors-container="#error-remarks"></textarea>
                            <div id="error-remarks"></div>
                            @error('kuiri_remarks')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    @if(!$kuiri_list->isEmpty())
                        <hr><h4 class="card-title text-primary mt-2 mb-3">Maklumat Log Kuiri</h4>

                        <div class="mb-3 row">
                            <label class="col-md-2">Log Kuiri</label>
                            <div class="col-md-9">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped">
                                        <thead class="bg-primary bg-gradient text-white">
                                            <tr class="text-center">
                                                <th width="5%;">Bil.</th>
                                                <th width="30%">Sebab Kuiri</th>
                                                <th width="15%">Tarikh Kuiri</th>
                                                <th width="25%">Dikuiri Oleh</th>
                                                <th width="25%">Nama Pegawai Kuiri</th>
                                            </tr>
                                        </thead>
                                        <tbody >
                                            @foreach ($kuiri_list as $i => $kuiri)
                                                <tr class="odd">
                                                    <td class="text-center" tabindex="0">{{ $loop->iteration }}</td>
                                                    <td class="text-center">{{ upperText($kuiri->remarks) }}</td>
                                                    <td class="text-center">{{ convertDateSys($kuiri->date)}}</td>
                                                    <td class="text-center">{{ $kuiri->finance_officer_category_name }}</td>
                                                    <td class="text-center">{{ $kuiri->finance_officer_name }}</td>
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
                        <hr>
                        <div class="mb-3 row">
                            <label for="approval_status" class="col-md-2 col-form-label ">Status</label>
                            <div class="col-md-9">
                                <span class="badge bg-danger p-2 fs-7 text-black">{{$current_log->transaction_status->status}}</span>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="approval_status" class="col-md-2 col-form-label ">Sebab Batal</label>
                            <div class="col-md-9">
                                <input class="form-control" value="{{strToUpper($current_log->remarks)}}" readonly>
                            </div>
                        </div>
                    @endif

                    <div class="mb-3 row">
                        <div class="col-sm-12">
                            <a href="{{ route('journalAdjustment.index') . '#' . $tab }}"  class="btn btn-secondary float-end me-2">{{ __('button.kembali') }}</a>
                            @if($proses_sedia)
                                <button type="submit" id="btn_hantar2" class="btn btn-primary float-end me-2 swal-validate-hantar">{{ __('button.hantar') }}</button>
                            @endif
                            @if($proses_sedia || $proses_semak || $proses_lulus)
                                <button type="submit" id="btn_simpan2" class="btn btn-primary float-end me-2 swal-simpan-jurnal">{{ __('button.simpan') }}</button>
                            @endif
                            @if($current_status == 2 && $proses_sedia )
                                    <button type="submit" class="btn btn-primary float-end me-2 swal-kemaskini-jurnal">{{ __('button.kemaskini') }}</button>
                            @endif
                            @if(checkPolicy("D") && ($batal == true || $batal_selepas_lulus == true || $batal_selepas_sah_simpan == true))
                                <button type="submit" class="btn btn-danger float-end me-2 swal-batal-jurnal" data-index="{{ $journal->id }}" data-page="edit">{{ __('button.batal') }}</button>
                            @endif
                        </div>
                    </div>
                </div> {{-- end card body --}}
            </div>{{-- end card --}}
        </div>{{-- end col1-12 --}}
    </form>

    <form method="POST" id="form-cancel" action="{{ route('journalAdjustment.cancel') }}" class="delete-form-list">
        {{ csrf_field() }}
        {{ method_field('DELETE') }}
        <input type="hidden" name="id" id="id" value="{{ $journal->id }}">
        <input type="hidden" name="cancel_remarks" id="cancel_remarks_{{ $journal->id }}" >
    </form>

    <form method="POST" action="{{ route('journalAdjustment.deleteByRow') }}" id="delete-form-by-row">
        {{ csrf_field() }}
        {{ method_field('DELETE') }}
        <input type="hidden" name="id" value="{{ $journal->id }}">
        <input type="hidden" name="tab_id" value="{{$tab_id}}">
        <input type="hidden" id="id_by_row" name="id_by_row">
    </form>

</div>{{-- end row  --}}

@endsection

@section('script')
<script src="{{ URL::asset('assets/js/pages/JournalAdjustment/journalAdjustment.js')}}"></script>
@endsection

