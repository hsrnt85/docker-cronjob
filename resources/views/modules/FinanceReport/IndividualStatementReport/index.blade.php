@extends('layouts.master')
@section('css')
    <link href="{{ URL::asset('/assets/libs/bootstrap-datepicker/bootstrap-datepicker.min.css') }}" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="{{ URL::asset('/assets/libs/datepicker/datepicker.min.css') }}">
    <style>
        .align-right{
            text-align: right;
        }
    </style>
@endsection
@section('content')
<div class="row">
    <div class="col-12">
        <!-- section - search  -->
        <div class="card ">
            <form class="search_form custom-validation" action="{{ route('individualStatementReport.index') }}" method="post">
                {{csrf_field()}}

                <div class="card-body p-3">
                    <div class="border-bottom border-primary mb-4"><h4 class="card-title">Carian Rekod</h4></div>

                    <div class="row">
                        <div class="col-md-3 p-1 mb-3" >
                            <label class="col-form-label ">Tarikh Dari</label>
                            <div class="input-group" id="datepicker2">
                                <input class="form-control"  type="text" placeholder="dd/mm/yyyy" name="search_date_from" id="search_date_from"
                                data-date-format="dd/mm/yyyy" data-date-container='#datepicker2' data-provide="datepicker" autocomplete="off"
                                data-date-autoclose="true" value="{{  old('search_date_from', $search_date_from) }}"  required data-parsley-required-message="{{ setMessage('date_from.required') }}"  data-parsley-errors-container="#date_from_error">
                                <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
                            </div>
                            <div id="date_from_error" ></div>
                        </div>

                        <div class="col-md-3 p-1 mb-3">
                            <label class="col-form-label">Tarikh Hingga</label>
                            <div class="input-group" id="datepicker2">
                                <input class="form-control"  type="text" placeholder="dd/mm/yyyy" name="search_date_to" id="search_date_to"
                                data-date-format="dd/mm/yyyy" data-date-container='#datepicker2' data-provide="datepicker" autocomplete="off"
                                data-date-autoclose="true" value="{{  old('search_date_to', $search_date_to) }}"  required data-parsley-required-message="{{ setMessage('date_to.required') }}"  data-parsley-errors-container="#date_to_error">
                                <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
                            </div>
                            <div id="date_to_error" ></div>
                        </div>

                        <div class="col-md-3 p-1 mb-3">
                            <label class="col-form-label">No. Kad Pengenalan</label>
                            <input class="form-control"  type="text" name="search_ic_no" id="search_ic_no" minlength="12" maxlength="12" oninput="checkNumber(this)" onfocus="focusInput(this);" value="{{  old('search_ic_no', $search_ic_no) }}" required data-parsley-required-message="{{ setMessage('ic_no.required') }}" data-parsley-length-message="{{ setMessage('ic_no.digits') }}">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12 p-1 mb-3">
                            <button class="btn btn-primary" type="submit" onClick="setFormTarget()">{{ __('button.cari') }}</button>
                            <button name="reset" class="btn btn-primary" value="reset" onClick="clearSearchInput()">{{ __('button.reset') }}</button>
                            <button name="muat_turun_pdf" class="btn btn-success" type="submit" value="pdf" onClick="setFormTarget(this)">{{ __('button.muat_turun_pdf') }}</button>
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

                <div class="mb-2 row">
                    <div class="col-sm-12">

                        Nama Pembayar : {{ $dataTenant?->name }}

                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-12">
                        <div data-simplebar style="max-height: 1000px;">
                            <table class="table table-striped table-bordered wrap w-100" >
                                <thead class="bg-primary bg-gradient text-white">
                                    <tr role="row">
                                        <th class="text-center" width="5%"> Bil. </th>
                                        <th class="text-center" width="10%"> Tarikh </th>
                                        <th class="text-center" width="15%"> No. Rujukan </th>
                                        <th class="text-center"> Keterangan </th>
                                        <th class="text-center" width="13%"> Debit (RM) </th>
                                        <th class="text-center" width="13%"> Kredit (RM) </th>
                                        <th class="text-center" width="13%"> Baki (RM) </th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @if (!$dataReport)
                                        <tr>
                                            <td class="text-center" colspan="7">Tiada Rekod</td>
                                        </tr>
                                    @else
                                        @php
                                            $bb_amount_unpaid = ($dataBbUnpaid?->bb_amount) ? $dataBbUnpaid?->bb_amount : 0;
                                            $bb_amount_paid = ($dataBbPaid?->bb_amount) ? $dataBbPaid?->bb_amount : 0;
                                            $bb_amount = $bb_amount_unpaid + $bb_amount_paid;
                                            $balance_amount = $bb_amount;
                                        @endphp
                                        <tr class="bg-light fw-bolder">
                                            <td class="bold align-right" colspan="4">BAKI BAWA HADAPAN</td>
                                            <td class="text-center" ></td>
                                            <td class="text-center" ></td>
                                            <td class="align-right" >{{ numberFormatComma($bb_amount) }}</td>
                                        </tr>
                                        @foreach($dataReport as $bil => $data)
                                            @php
                                                $debit_amount = $data->debit_amount ? $data->debit_amount : 0;
                                                $credit_amount = $data->credit_amount ? $data->credit_amount : 0;
                                                $balance_amount += $debit_amount - $credit_amount;
                                            @endphp
                                            <tr>
                                                <td class="text-center" width="2%">{{ ++$bil }}</td>
                                                <td class="text-center" width="5%">{{ convertDateSys($data->transaction_date) }}</td>
                                                <td class="text-center" >{{ $data->transaction_no }}</td>
                                                <td width="35%">{{ $data->description}}</td>
                                                <td class="align-right" width="8%">{{ numberFormatComma($debit_amount) }}</td>
                                                <td class="align-right" width="8%">{{ numberFormatComma($credit_amount) }}</td>
                                                <td class="align-right" width="8%">{{ numberFormatComma($balance_amount) }}</td>
                                            </tr>
                                        @endforeach
                                        <tr class="bg-light fw-bolder">
                                            <td class="bold align-right"colspan="4">BAKI BAYARAN</td>
                                            <td class="text-center" ></td>
                                            <td class="text-center" ></td>
                                            <td class="align-right" >{{ numberFormatComma($balance_amount) }}</td>
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
<script src="{{ URL::asset('assets/libs/bootstrap-datepicker/bootstrap-datepicker.min.js')}}"></script>
<script src="{{ URL::asset('assets/js/pages/FinanceReport/financeReport.js')}}"></script>

@endsection
