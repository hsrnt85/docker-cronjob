@extends('layouts.master')
@section('css')
    <link href="{{ URL::asset('/assets/libs/bootstrap-datepicker/bootstrap-datepicker.min.css') }}" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="{{ URL::asset('/assets/libs/datepicker/datepicker.min.css') }}">
@endsection
@section('content')
<div class="row">
    <div class="col-12">
        <!-- section - search  -->
        <div class="card ">
            <form class="search_form custom-validation" action="{{ route('collectorStatementReport.index') }}" method="post" id="form_search_record">
                {{ csrf_field()}}

                <div class="card-body p-3">

                    <div class="border-bottom border-primary mb-4">
                        <h4 class="card-title">Carian Rekod</h4>
                    </div>

                    <div class="row">

                        <div class="col-md-3 p-1 mb-3" >
                            <label class="col-form-label ">No. Penyata Pemungut</label>
                            <input class="form-control" type="text" id="search_collector_statement_no" name="search_collector_statement_no" value="{{ $search_collector_statement_no }}">
                        </div>

                        <div class="col-md-3 p-1 mb-3" >
                            <label class="col-form-label ">Tarikh Dari<span class="text-danger"> *</span></label>
                            <div class="input-group" id="datepicker2">
                                <input class="form-control"  type="text" placeholder="dd/mm/yyyy" name="search_date_from" id="search_date_from"
                                data-date-format="dd/mm/yyyy" data-date-container='#datepicker2' data-provide="datepicker" autocomplete="off"
                                data-date-autoclose="true" value="{{  old('search_date_from', $search_date_from) }}"  required data-parsley-required-message="{{ setMessage('date_from.required') }}"  data-parsley-errors-container="#date_from_error">
                                <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
                            </div>
                            <div id="date_from_error" ></div>
                        </div>

                        <div class="col-md-3 p-1 mb-3">
                            <label class="col-form-label">Tarikh Hingga<span class="text-danger"> *</span></label>
                            <div class="input-group" id="datepicker2">
                                <input class="form-control"  type="text" placeholder="dd/mm/yyyy" name="search_date_to" id="search_date_to"
                                data-date-format="dd/mm/yyyy" data-date-container='#datepicker2' data-provide="datepicker" autocomplete="off"
                                data-date-autoclose="true" value="{{  old('search_date_to', $search_date_to) }}"  required data-parsley-required-message="{{ setMessage('date_to.required') }}"  data-parsley-errors-container="#date_to_error">
                                <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
                            </div>
                            <div id="date_to_error" ></div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12 p-1 mb-3">
                            <button class="btn btn-primary" type="submit" onClick="setFormTarget()">{{ __('button.cari') }}</button>
                            <button name="reset" class="btn btn-primary" type="submit" value="reset"  onClick ="clearSearchInput()">{{ __('button.reset') }}</button>
                            <button name="muat_turun_pdf" class="btn btn-success" type="submit" value="pdf" onClick="setFormTarget(this)">{{ __('button.muat_turun_pdf') }}</button>
                            {{-- <button name="muat_turun_excel" class="btn btn-success" type="submit" value="excel" >{{ __('button.muat_turun_excel') }}</button> --}}
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <!-- end section - search  -->

        <!-- section - list report -->
        <div class="card">
            <div class="card-body">
                {{-- PAGE TITLE --}}
                <div class="border-bottom border-primary mb-4">
                    <h4 class="card-title">{{ getPageTitle(1) }}</h4>
                </div>

                <div class="row">
                    <div class="col-sm-12">
                        <div data-simplebar style="max-height: 1000px;">
                            <table class="table table-striped table-bordered wrap w-100 mb-4" width="100%" >
                                <thead class="bg-primary bg-gradient text-white">
                                    <tr role="row">
                                        <th class="text-center" rowspan="2">Bil.</th>
                                        <th class="text-center" colspan="2">Penyata Pemungut</th>
                                        <th class="text-center" colspan="3">Tempoh Pungutan</th>
                                        <th class="text-center" colspan="2">Membayar</th>
                                        <th class="text-center" colspan="8">Dimasukira</th>
                                        <th class="text-center" rowspan="2">No. Resit BN</th>
                                        <th class="text-center" rowspan="2">Tarikh Resit BN</th>
                                        <th class="text-center" rowspan="2">Kod Status</th>
                                        <th class="text-center" rowspan="2">Amaun(RM)</th>
                                    </tr>
                                    <tr role="row" >
                                        <th class="text-center">No. Ruj</th>
                                        <th class="text-center">Tarikh</th>
                                        <th class="text-center">Dari</th>
                                        <th class="text-center">Hingga</th>
                                        <th class="right">Jumlah</th>
                                        <th class="text-center">JAB</th>
                                        <th class="text-center">PTJ/PK</th>
                                        <th class="text-center">VOT</th>
                                        <th class="text-center">JAB</th>
                                        <th class="text-center">PTJ/PK</th>
                                        <th class="text-center">PROG/AKT</th>
                                        <th class="text-center">PROJEK</th>
                                        <th class="text-center">SETIA</th>
                                        <th class="text-center">CP</th>
                                        <th class="text-center">Kod Akaun</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @if ($collectorStatementListAll -> count() == 0)
                                        <tr>
                                            <td class="text-center" colspan="20">Tiada Rekod</td>
                                        </tr>
                                    @else
                                        @foreach($collectorStatementListAll as $bil => $csr)
                                            <tr>
                                                <th class="text-center" scope="row">{{ ++$bil }}</th>
                                                <td class="text-center" >{{ $csr -> collector_statement_no ??'' }}</td>
                                                <td class="text-center" >{{ convertDateSys($csr ->collector_statement_date)}}</td>
                                                <td class="text-center" >{{ convertDateSys($csr ->collector_statement_date_from)}}</td>
                                                <td class="text-center" >{{ convertDateSys($csr ->collector_statement_date_to)}}</td>
                                                <td style="text-align:right" >{{ numberFormatComma($csr->total_amount) }}</td>
                                                <td class="text-center" >{{ $csr -> department_code ??'' }}</td>
                                                <td class="text-center" >{{ $csr -> ptj_code ??'' }}</td>
                                                <td class="text-center" >{{ $csr -> general_income_code ??'' }}</td>
                                                <td class="text-center" >{{ $csr -> department_code ??'' }}</td>
                                                <td class="text-center" >{{ $csr -> ptj_code ??'' }}</td>
                                                <td class="text-center" ></td>
                                                <td class="text-center" ></td>
                                                <td class="text-center" ></td>
                                                <td class="text-center" ></td>
                                                <td class="text-center" >{{ $csr -> income_code ??'' }}</td>
                                                <td class="text-center" ></td>
                                                <td class="text-center" ></td>
                                                <td class="text-center" >{{ $csr -> status_code ??'' }}</td>
                                                <td style="text-align:right">{{ $csr -> total_amount ??'' }}</td>
                                            </tr>
                                        @endforeach
                                    @endif
                                </tbody>
                                <tfoot>
                                    <tr class="bold" style="text-align:right">
                                        <td colspan="19"><b>JUMLAH KESELURUHAN</b></td>
                                        <td ><b>{{ numberFormatComma($collectorStatementListAll ->sum('total_amount')) }}</b></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- end section - list report -->
    </div> <!-- end col -->

</div>
<!-- end row -->

@endsection

@section('script')
<script src="{{ URL::asset('assets/js/pages/FinanceReport/financeReport.js')}}"></script>
<script src="{{ URL::asset('assets/libs/bootstrap-datepicker/bootstrap-datepicker.min.js')}}"></script>
@endsection
