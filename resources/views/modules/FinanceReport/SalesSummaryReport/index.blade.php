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
            <form class="search_form custom-validation" action="{{ route('salesSummaryReport.index') }}" method="post" id="form_search_record">
                {{csrf_field()}}

                <div class="card-body p-3">
                    <div class="border-bottom border-primary mb-4"><h4 class="card-title">Carian Rekod</h4></div>

                    <div class="row">
                        <div class="col-md-3 p-1 mb-3" >
                            <label class="col-form-label ">Tarikh Dari<span class="text-danger"> *</span></label>
                            <div class="input-group" id="datepicker2">
                                <input class="form-control"  type="text" placeholder="dd/mm/yyyy" name="date_from" id="date_from"
                                data-date-format="dd/mm/yyyy" data-date-container='#datepicker2' data-provide="datepicker" autocomplete="off"
                                data-date-autoclose="true" value="{{  old('date_from', $search_date_from) }}" required data-parsley-required-message="{{ setMessage('date_from.required') }}" data-parsley-errors-container="#date_from_error">
                                <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
                            </div>
                            <div id="date_from_error" ></div>
                        </div>

                        <div class="col-md-3 p-1 mb-3">
                            <label class="col-form-label">Tarikh Hingga<span class="text-danger"> *</span></label>
                            <div class="input-group" id="datepicker2">
                                <input class="form-control"  type="text" placeholder="dd/mm/yyyy" name="date_to" id="date_to"
                                data-date-format="dd/mm/yyyy" data-date-container='#datepicker2' data-provide="datepicker" autocomplete="off"
                                data-date-autoclose="true" value="{{  old('date_to', $search_date_to) }}" required data-parsley-required-message="{{ setMessage('date_to.required') }}" data-parsley-errors-container="#date_to_error">
                                <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
                            </div>
                            <div id="date_to_error" ></div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12 p-1 mb-3">
                            <button class="btn btn-primary" type="submit" onClick="setFormTarget()">{{ __('button.cari') }}</button>
                            <button name="reset" class="btn btn-primary"  onClick ="clearSearchInput()">{{ __('button.reset') }}</button>
                            <button name="muat_turun_pdf" class="btn btn-success" type="submit" value="pdf" onClick="setFormTarget(this)">{{ __('button.muat_turun_pdf') }}</button>
                            {{-- <button name="muat_turun_excel" class="btn btn-success" type="submit" value="excel" >{{ __('button.muat_turun_excel') }}</button> --}}
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <!-- end section - search  -->

        <!-- section - list search -->
        <div class="card">
            <div class="card-body">
                {{-- PAGE TITLE --}}
                <div class="border-bottom border-primary mb-4"><h4 class="card-title">{{ getPageTitle(1) }}</h4></div>

                <div class="row">
                    <div class="col-sm-12">
                        <div data-simplebar style="max-height: 1000px;">
                            <table class="table table-striped table-bordered wrap w-100" >
                                <thead class="bg-primary bg-gradient text-white">
                                    <tr role="row" class="text-center">
                                        <th width="8%"> Bil. </th>
                                        <th> Kod Hasil </th>
                                        <th> Kod Hasil Terimaan </th>
                                        <th width="48%"> Perihal Hasil </th>
                                        <th width="15%"> Jumlah Kutipan (RM) </th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @if ($summaryData -> count() == 0)
                                        <tr>
                                            <td class="text-center" colspan="10">Tiada Rekod</td>
                                        </tr>
                                    @else
                                        @foreach($summaryData as $bil => $data)
                                            <tr>
                                                <th class="text-center" scope="row">{{ $loop->iteration }}</th>
                                                <td class="text-center" >{{ $data->ispeks_account_code??'' }}</td>
                                                <td class="text-center" >{{ $data->income_code ??'' }}</td>
                                                <td >{{ $data->income_code_description ??'' }}</td>
                                                <td style="text-align: right;">{{ numberFormatComma($data->total_amount ??'0') }}</td>
                                            </tr>
                                        @endforeach
                                    @endif
                                </tbody>
                                <tfoot>
                                    <tr class="info_content_border" style="text-align: right;">
                                        <td colspan="4" ><b>JUMLAH KESELURUHAN</b></td>
                                        <td class="fw-bolder">{{ numberFormatComma($summaryData->sum('total_amount')) }}</td>
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
