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
            <form class="search_form custom-validation" action="{{ route('cashBookReport.index') }}" method="post">
                {{csrf_field()}}

                <div class="card-body p-3">
                    <div class="border-bottom border-primary mb-4"><h4 class="card-title">Carian Rekod</h4></div>

                    <div class="row">

                        <div class="col-md-3 p-1 mb-3" >
                            <label class="col-form-label ">Tarikh Dari<span class="text-danger"> *</span></label>
                            <div class="input-group" id="datepicker2">
                                <input class="form-control"  type="text" placeholder="dd/mm/yyyy" name="search_date_from" id="search_date_from"
                                data-date-format="dd/mm/yyyy" data-date-container='#datepicker2' data-provide="datepicker" autocomplete="off"
                                data-date-autoclose="true" value="{{  old('search_date_from', $search_date_from) }}" required data-parsley-required-message="{{ setMessage('search_date_from.required') }}"
                                data-parsley-errors-container="#search_date_from_error" >
                                <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
                            </div>
                            <div id="search_date_from_error" ></div>
                        </div>

                        <div class="col-md-3 p-1 mb-3">
                            <label class="col-form-label">Tarikh Hingga<span class="text-danger"> *</span></label>
                            <div class="input-group" id="datepicker2">
                                <input class="form-control"  type="text" placeholder="dd/mm/yyyy" name="search_date_to" id="search_date_to"
                                data-date-format="dd/mm/yyyy" data-date-container='#datepicker2' data-provide="datepicker" autocomplete="off"
                                data-date-autoclose="true" value="{{  old('search_date_to', $search_date_to) }}" required data-parsley-required-message="{{ setMessage('search_date_to.required') }}"
                                data-parsley-errors-container="#search_date_to_error" >
                                <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
                            </div>
                            <div id="search_date_to_error" ></div>
                        </div>

                        <div class="col-md-3 p-1 mb-3">
                            <label for="search_payment_method" class="col-form-label ">Kaedah Bayaran</label>
                            <select class="form-select select2" name="search_payment_method" value="{{ old('search_payment_method', $search_payment_method) }}">
                                <option value="">-- Sila Pilih --</option>
                                @foreach($paymentMethodAll as $method)
                                    <option value="{{ $method->id }}" @if ($method->id  == $search_payment_method) selected @endif>{{ $method->payment_method }}</option>
                                @endforeach
                            </select>
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

        <!-- section - list report -->
        <div class="card">
            <div class="card-body">
                {{-- PAGE TITLE --}}
                <div class="border-bottom border-primary mb-4"><h4 class="card-title">{{ getPageTitle(1) }}</h4></div>

                <div class="row">
                    <div class="col-sm-12">
                        <div data-simplebar style="max-height: 1000px;">
                            <table class="table table-striped table-bordered wrap w-100" >
                                <thead class="bg-primary bg-gradient text-white">
                                    <tr role="row">
                                        <th class="text-center" rowspan="2">Bil.</th>
                                        <th class="text-center" rowspan="2">Tarikh</th>
                                        <th class="text-center" rowspan="2">Kaedah Bayaran</th>
                                        <th class="text-center" rowspan="2">No. Resit</th>
                                        <th class="text-center" rowspan="2">Amaun (RM)</th>
                                        <th class="text-center" colspan="5">Pembayaran Kepada Perbendaharaan</th>
                                    </tr>
                                    <tr>
                                        <th class="text-center" >No. Penyata Pemungut <br> Tarikh Slip Bank</th>
                                        <th class="text-center" >Amaun (RM)</th>
                                        <th class="text-center" >No. Resit Perbendaharaan <br> Tarikh Resit</th>
                                        <th class="text-center" >Perbezaan Hari di Bank</th>
                                        <th class="text-center" >Status</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @if($dataReport->count()==0)
                                        <tr>
                                            <td class="text-center" colspan="10">Tiada Rekod</td>
                                        </tr>
                                    @else
                                        @foreach($dataReport as $bil => $data)
                                            <tr>
                                                <th class="text-center" scope="row">{{ $loop->iteration }}</th>
                                                <td class="text-center" >{{ convertDateSys($data->payment_date) }}</td>
                                                <td class="text-center" >{{ $data->payment_method }}</td>
                                                <td class="text-center" >{{ $data->payment_receipt_no }}</td>
                                                <td style="text-align:right;" >{{ numberFormatComma($data->amount) }}</td>
                                                <td class="text-center" >{{ $data->collector_statement_no }}<br/>{{ convertDateSys($data->bank_slip_date) }}</td>
                                                <td style="text-align:right;" >{{ numberFormatComma($data->amount) }}</td>
                                                <td class="text-center" >{{ $data->receipt_no ?? ""}} <br/> {{ convertDateSys($data->receipt_date ?? "") }}</td>
                                                <td class="text-center" >{{ 0 }}</td>
                                                <td class="text-center" >{{ $data->status }}</td>
                                            </tr>
                                        @endforeach
                                    @endif
                                    @php
                                    $total_amount = numberFormatComma($dataReport->sum('amount'));
                                @endphp
                                    @if($dataReport->count()>0)
                                        <tr class="fw-bolder" style="text-align:right;">
                                            <td colspan="4" ><b>JUMLAH KESELURUHAN</b></td>
                                            <td colspan="1" >{{ $total_amount }}</td>
                                            <td></td>
                                            <td colspan="1" >{{ $total_amount }}</td>
                                            <td colspan="3"></td>
                                        </tr>
                                    @endif
                                </tbody>
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
