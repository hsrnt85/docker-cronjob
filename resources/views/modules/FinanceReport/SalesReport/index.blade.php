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
            <form class="search_form custom-validation" action="{{ route('salesReport.index') }}" method="post">
                {{csrf_field()}}
                <div class="card-body p-3">
                    <div class="border-bottom border-primary mb-4">
                        <h4 class="card-title">Carian Rekod</h4>
                    </div>

                    <div class="row">
                        <div class="col-md-3 p-1 mb-3" >
                            <label class="col-form-label ">Tarikh Dari<span class="text-danger"> *</span></label>
                            <div class="input-group" id="datepicker2">
                                <input class="form-control"  type="text" placeholder="dd/mm/yyyy" name="date_from" id="date_from"
                                data-date-format="dd/mm/yyyy" data-date-container='#datepicker2' data-provide="datepicker" autocomplete="off"
                                data-date-autoclose="true" value="{{  old('date_from', convertDateSys($search_date_from)) }}"  required data-parsley-required-message="{{ setMessage('date_from.required') }}"  data-parsley-errors-container="#date_from_error">
                                <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
                            </div>
                            <div id="date_from_error" ></div>
                        </div>

                        <div class="col-md-3 p-1 mb-3">
                            <label class="col-form-label">Tarikh Hingga<span class="text-danger"> *</span></label>
                            <div class="input-group" id="datepicker2">
                                <input class="form-control"  type="text" placeholder="dd/mm/yyyy" name="date_to" id="date_to"
                                data-date-format="dd/mm/yyyy" data-date-container='#datepicker2' data-provide="datepicker" autocomplete="off"
                                data-date-autoclose="true" value="{{  old('date_to', convertDateSys($search_date_to)) }}"  required data-parsley-required-message="{{ setMessage('date_to.required') }}"  data-parsley-errors-container="#date_to_error">
                                <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
                            </div>
                            <div id="date_to_error" ></div>
                        </div>

                        <div class="col-md-3 p-1 mb-3" >
                            <label class="col-form-label ">Jenis Terimaan</label>
                            <select class="form-select" id="search_account" name="search_account" >
                                <option value="">-- Sila Pilih --</option>
                                @foreach($receiptType as $data)
                                    <option value="{{ $data->ispeks_account_code }}" {{old('search_account', $search_account)==$data->ispeks_account_code ? 'selected' : ''}}>{{ $data->ispeks_account_code }} - {{ $data->ispeks_account_description }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-3 p-1 mb-3" >
                            <label class="col-form-label ">Jenis Tunggakan</label>
                            <select class="form-select" id="search_outstanding" name="search_outstanding">
                                <option value="">-- Sila Pilih --</option>
                                @foreach($outstandingType as $data)
                                    <option value="{{ $data->flag_outstanding}}" {{old('search_outstanding', $search_outstanding)==$data->flag_outstanding ? 'selected' : ''}}>{{ $data->outstanding_type }}</option>
                                @endforeach
                            </select>
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

                <div class="row">
                    <div class="col-sm-12">
                        <div data-simplebar style="max-height: 1000px;">
                            <table class="table table-striped table-bordered wrap w-100" >
                                <thead class="bg-primary bg-gradient text-white">
                                    <tr role="row">
                                        <th class="text-center" >Bil. </th>
                                        <th class="text-center" >No. Resit</th>
                                        <th class="text-center" >Nama Pembayar</th>
                                        <th class="text-center" >Perihal Bayaran</th>
                                        <th class="text-center" >Tarikh Urusniaga</th>
                                        <th class="text-center" >Masa Urusniaga</th>
                                        <th class="text-center" >Bentuk Bayaran</th>
                                        <th class="text-center" >Kod Akaun</th>
                                        <th class="text-center" >Jumlah Bayaran (RM)</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @if ($recordList->count() == 0)
                                        <tr>
                                            <td class="text-center" colspan="10">Tiada Rekod</td>
                                        </tr>
                                    @else
                                        @foreach($recordList as $bil => $data)
                                            @php $payer_name = ($data->payment_category_id!=3) ? $data->payer_name : $data->tenant?->name; @endphp
                                            <tr>
                                                <th class="text-center" scope="row">{{ $loop->iteration }}</th>
                                                <td class="text-center" >{{ $data->payment_receipt_no ??'' }}</td>
                                                <td >{{ $payer_name ?? '-' }}</td>
                                                <td >{{ $data->payment_description ??'' }}</td>
                                                <td class="text-center" >{{ convertDateSys($data->payment_date) }}</td>
                                                <td class="text-center" >{{ $data->payment_time }}</td>
                                                <td class="text-center" >{{ $data->payment_category ??'' }}</td>
                                                <td class="text-center" >{{ $data->income_code }} </td>
                                                <td style="text-align: right;" >{{ numberFormatComma($data->amount ??'') }}</td>
                                            </tr>
                                        @endforeach
                                    @endif
                                </tbody>
                                <tfoot>
                                    <tr class="bg-light fw-bolder"  style="text-align: right;" >
                                        <td colspan="8" >JUMLAH KESELURUHAN </td>
                                        <td>{{ numberFormatComma($recordList ->sum('amount')) }}</td>
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
