@extends('layouts.master')
@section('content')

<div >
    <form class="custom-validation" id="form" method="post" action="{{ route('journalAdjustment.store') }}" >
    {{ csrf_field() }}

    <div class="col-12">
        <div class="card">
            <div class="card-body">
                {{-- PAGE TITLE --}}
                <div class="border-bottom border-primary mb-4"><h4 class="card-title">{{ getPageTitle(1) }}</h4></div>

                <input type="hidden" id="page" name="page" value="new">

                <h4 class="card-title text-primary mt-2 mb-3">Maklumat Induk</h4>

                <div class="mb-3 row">
                    <label for="date" class="col-md-2 col-form-label">Tahun Kewangan</label>
                    <div class="col-md-9">
                        <input class="form-control" value="{{currentYear()}}" readonly>
                    </div>
                </div>

                <div class="mb-3 row">
                    <label for="date_from" class="col-md-2 col-form-label">Jabatan Penyedia</label>
                    <div class="col-md-9">
                        <input class="form-control" value="{{$finance_department->department_name}}" readonly>
                    </div>
                </div>

                <div class="mb-3 row">
                    <label for="date_to" class="col-md-2 col-form-label">PTJ Penyedia</label>
                    <div class="col-md-9">
                        <input class="form-control" value="{{$finance_department->ptj_name }}" readonly>
                    </div>
                </div>

                <hr><h4 class="card-title text-primary mt-2 mb-3">Maklumat Jurnal</h4>

                <div class="mb-3 row">
                    <label for="journal_type" class="col-md-2 col-form-label">Jenis Jurnal Pelarasan</label>
                    <div class="col-md-9">
                        <select class="form-select select2" name="journal_type" required data-parsley-required-message="{{ setMessage('journal_type.required') }}" data-parsley-errors-container="#journal_type_error">
                            <option value="">-- Sila Pilih --</option>
                            @foreach($journal_type_list as $type)
                                <option value="{{ $type->id }}" @if($type->id == old('journal_type')) selected @endif>{{ $type->journal_type}}</option>
                            @endforeach
                        </select>
                        <div id="journal_type_error"></div>
                    </div>
                </div>

                <div class="mb-3 row">
                    <label class="col-md-2 col-form-label">Tarikh</label>
                    <div class="col-md-9">
                        <div class="input-group" id="datepicker2">
                            <input class="form-control"  value="{{currentDateSys()}}" disabled>
                            <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
                        </div>
                    </div>
                </div>

                <div class="mb-3 row">
                    <label for="collector_statement_no" class="col-md-2 col-form-label ">No. Penyata Pemungut</label>
                    <div class="col-md-9">
                        <select class="form-select select2  @error('collector_statement_no') is-invalid @enderror" name="collector_statement_no"
                            required data-parsley-required-message="{{ setMessage('collector_statement_no.required') }}"
                            data-parsley-errors-container="#collector_statement_no_error">
                            <option value="">-- Sila Pilih --</option>
                            @foreach($get_collector_statement as $cs)
                                <option value="{{ $cs->id }}" @if($cs->id == old('collector_statement_no')) selected @endif>{{ $cs->collector_statement_no }}</option>
                            @endforeach
                        </select>
                        <div id="collector_statement_no_error"></div>
                        @error('collector_statement_no')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-3 row">
                    <label class="col-md-2 col-form-label ">Keterangan</label>
                    <div class="col-md-9">
                        <textarea class="form-control  @error('description') is-invalid @enderror" rows="4" id="description" name="description" required data-parsley-required-message="{{ setMessage('description.required') }}"
                            data-parsley-errors-container="#error-description">{{ old ('description') }}</textarea>
                        <div id="error-description"></div>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <hr><h4 class="card-title text-primary mt-2 mb-3">Maklumat Terperinci</h4>

                <div class="mb-3 row">

                    <label  class="col-md-2 col-form-label ">Vot / Kod Akaun</label>
                    <div class="col-md-9">
                        <table class="table table-sm table-bordered table-striped senarai-vot-akaun" id="table-vot-akaun" data-list="0">
                            <thead class="bg-primary bg-gradient text-white">
                                <tr>
                                    <th class="text-center" width="50%">Kod Akaun</th>
                                    <th class="text-center" width="20%">Amaun Debit (RM)</th>
                                    <th class="text-center" width="20%">Amaun Kredit (RM)</th>
                                    <th class="text-center" width="10%"><a onclick="duplicateRow();" class="btn btn-success btn-sm" ><i class="mdi mdi-plus mdi-18px"></i></a></th>
                                </tr>
                            </thead>
                            <tbody >
                                @if(old('income_code', null) != null)
                                    @foreach(old('income_code') as $i => $income_code)
                                        <tr id="tbl-row-{{$i}}">
                                            <td>
                                                <select  class="form-select select2 select2-account account-code @error('income_code.'.$i) is-invalid @enderror" id="income_code{{$i}}" name="income_code[]" value="{{ old('income_code.'.$i,'') }}"
                                                    required data-parsley-required-message="{{ setMessage('income_code.required') }}">
                                                    <option value="">-- Sila Pilih --</option>
                                                    @foreach($get_income_code as $code)
                                                        <option value="{{ $code->id }}" >{{ $code->income_code.' - '.$code->income_code_description }}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td>
                                                <input class="form-control @error('debit.'.$i) is-invalid @enderror text-start" type="text" id="debit{{$i}}" name="debit[]" value="{{ old('debit.'.$i) }}" placeholder="0.00" data-inputmask="'alias': 'numeric', 'groupSeparator': ',', 'digits': 2, 'digitsOptional': false "
                                                {{-- required data-parsley-required-message="{{ setMessage('debit.required.'.$i,'') }}" --}}
                                                >
                                                {{-- @error('debit.'.$i,'')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror --}}
                                            </td>
                                            <td>
                                                <input class="form-control @error('credit.'.$i) is-invalid @enderror  text-start" type="text" id="credit{{$i}}" name="credit[]" value="{{ old('credit.'.$i,'') }}" placeholder="0.00"  data-inputmask="'alias': 'numeric', 'groupSeparator': ',', 'digits': 2, 'digitsOptional': false "
                                                {{-- required data-parsley-required-message="{{ setMessage('credit.required.'.$i,'') }}" --}}
                                                >
                                                {{-- @error('credit')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror --}}
                                            </td>
                                            <td class="text-center">
                                                <input type="hidden" id="votlist_id{{$i}}" name="votlist_id[]" value="{{ old('votlist_id.'.$i) }}">
                                                <a id="btnRemove{{$i}}" class="btnRemove btn btn-warning btn-sm"><i class="mdi mdi-minus mdi-16px"></i></a>
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr id="tbl-row-0" data-row-index="0">
                                        <td>
                                            <select class="form-select select2 select2-account account-code account-code0  @error('income_code.'.'0') is-invalid @enderror" width="40%" id="income_code0" name="income_code[]" value="{{ old('income_code') }}"
                                            required data-parsley-required-message="{{ setMessage('income_code.required') }}"  data-parsley-errors-container="#error_code" >
                                                <option value="">-- Sila Pilih --</option>
                                                @foreach($get_income_code as $code)
                                                    <option value="{{ $code->id }}" >{{ $code->income_code.' - '. $code->income_code_description}}</option>
                                                @endforeach
                                            </select>
                                            <div id="error_code"></div>
                                        </td>
                                        <td>
                                            <input class="form-control text-start debit debit0" type="text" id="debit0" name="debit[]" value="{{ old('debit','') }}"
                                            oninput="checkDecimal(this);" onfocus="focusInput(this);" placeholder="0.00"
                                            {{-- required data-parsley-required-message="{{ setMessage('debit.required') }}"  --}}
                                            >
                                        </td>
                                        <td>
                                            <input class="form-control text-start credit credit0" type="text" id="credit0" name="credit[]" value="{{ old('credit', '') }}"
                                            oninput="checkDecimal(this)" onfocus="focusInput(this);" placeholder="0.00"
                                            {{-- required data-parsley-required-message="{{ setMessage('credit.required') }}" --}}
                                             >
                                        </td>
                                        <td class="text-center">
                                            <input type="hidden" id="votlist_id0" name="votlist_id[]" value="">
                                            <a id="btnRemove0" class='btnRemove btn btn-warning btn-sm' data-row-index="0"><i class='mdi mdi-minus mdi-18px'></i></a>
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                            <tfoot >
                                <tr>
                                    <td>Jumlah Keseluruhan (RM)</td>
                                    <td><input class="form-control" id="total_debit" name="total_debit" value="{{old('total_debit')}}" readonly></td>
                                    <td><input class="form-control" id="total_credit" name="total_credit" value="{{old('total_credit')}}" readonly></td>
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

                <hr><h4 class="card-title text-primary mt-2 mb-3">Maklumat Pegawai</h4>
                <div class="mb-3 row">
                    <label for="preparer" class="col-md-2 col-form-label">Disediakan Oleh</label>
                    <div class="col-md-9">
                        <select class="form-select select2" name="preparer" id="preparer"
                            required data-parsley-required-message="{{ setMessage('preparer.required') }}">
                            @foreach($get_pegawai_penyedia as $officer)
                                @if($officer->users_id == $login_officer->users_id)
                                    <option value="{{ $officer->fin_officer_id}}" selected>{{ $officer->name }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="mb-3 row">
                    <label for="checker" class="col-md-2 col-form-label ">Disemak Oleh</label>
                    <div class="col-md-9">
                        <select class="form-select select2  @error('checker') is-invalid @enderror" id="checker" name="checker" value="{{ old('checker') }}"
                            required data-parsley-required-message="{{ setMessage('checker.required') }}"
                            data-parsley-errors-container="#errorChecker">
                            <option value="">-- Pilih Pegawai --</option>
                            @foreach($get_pegawai_penyemak as $officer)
                                <option value="{{ $officer->fin_officer_id }}" @if($officer->fin_officer_id == old('checker')) selected @endif>{{ $officer->name }}</option>
                            @endforeach
                        </select>
                        <div id="errorChecker"></div>
                        @error('checker')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-3 row">
                    <div class="col-sm-12 pr-5">
                        <button type="submit" id="btn_simpan" class="btn btn-primary float-end swal-tambah" disabled>{{ __('button.simpan') }}</button>
                        <a href="{{ route('journalAdjustment.index') }}" class="btn btn-secondary float-end me-2">{{ __('button.kembali') }}</a>
                    </div>
                </div>
            </div> {{-- end card body --}}

        </div>{{-- end card --}}

    </div>{{-- end col1-12 --}}

    </form>

</div>{{-- end row  --}}

@endsection

@section('script')
<script src="{{ URL::asset('assets/js/pages/JournalAdjustment/journalAdjustment.js')}}"></script>
@endsection

