@extends('layouts.master')
@section('content')
<div class="row">
    <div class="col-12">
        <!-- section - search  -->
        <div class="card ">
            <form class="search_form custom-validation" id="form" action="{{ route('paymentRecord.index') }}" method="post" >
                {{csrf_field()}}
                <div class="card-body p-3">
                    <div class="border-bottom border-primary mb-4">
                        <h4 class="card-title">Carian Rekod</h4>
                    </div>

                    <div class="row">
                        <div class="col-md-3 p-1 mb-3">
                            <label class="col-form-label">No. Kad Pengenalan</label>
                            <input class="form-control input-mask" type="text" id="carian_no_ic" name="carian_no_ic" value="{{ old('carian_no_ic', $search_no_ic) }}"  data-inputmask="'mask':'999999-99-9999'" minlength="14" maxlength="14" required data-parsley-length-message="{{ setMessage('carian_no_ic.digits') }}"  data-parsley-required-message="{{ setMessage('carian_no_ic.required') }}">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-3 p-1 mb-3" >
                            <label class="col-form-label ">Tahun<span class="text-danger"> *</span></label>
                            <select class="form-select carian_tahun" id="carian_tahun" name="carian_tahun"  data-parsley-required-message="{{ setMessage('carian_tahun.required') }}">
                                <option value="">-- Sila Pilih --</option>
                                @foreach($year as $y)
                                    <option value="{{$y->year}}" {{old('carian_tahun', $carian_tahun) == $y->year? "selected" : ""}}>{{ $y->year }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-3 p-1 mb-3">
                            <label class="col-form-label">Bulan<span class="text-danger"> *</span></label>
                            <select class="form-select" id="carian_bulan" name="carian_bulan"  data-parsley-required-message="{{ setMessage('carian_bulan.required') }}">
                                <option value="">-- Sila Pilih --</option>
                                @foreach($month as $m)
                                    <option value="{{ $m->id }}" {{ old('carian_bulan', $carian_bulan) == $m->id ? 'selected' : '' }}>
                                        {{ $m->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-3 p-1 mb-3" >
                            <label class="col-form-label ">Kategori Bayaran </label>
                            <select class="form-select" id="carian_bayaran" name="carian_bayaran" >
                                <option value="">-- Sila Pilih --</option>
                                @foreach($paymentCategory as $code)
                                    <option value="{{ $code->id }}" {{ old('carian_bayaran', $carian_bayaran) == $code->id ? 'selected' : '' }}> {{ $code->payment_category }} </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12 p-1 mb-3">
                            <button class="btn btn-primary" type="submit" onClick="setFormTarget()">{{ __('button.cari') }}</button>
                            <button name="reset" class="btn btn-primary" value="reset" id="reset" onClick="clearSearchInput()">{{ __('button.reset') }}</button>
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
                            <table class="table table-striped table-bordered table-striped wrap w-100" >
                                <thead class="bg-primary bg-gradient text-white">
                                    <tr role="row">
                                        <th class="text-center" >Bil. </th>
                                        <th class="text-center" width="22%">Nama Pembayar</th>
                                        <th class="text-center" >No. Resit</th>
                                        <th class="text-center" >No. Notis Bayaran</th>
                                        <th class="text-center" width="25%">Perihal Bayaran</th>
                                        <th class="text-center" >Tarikh & Masa Urusniaga</th>
                                        <th class="text-center" >Kaedah Bayaran</th>
                                        <th class="text-center" >Jumlah Bayaran (RM)</th>
                                    </tr>
                                </thead>
                                @php  $sum = 0.00; @endphp
                                <tbody>
                                    @if ($recordList->isEmpty())
                                        <tr>
                                            <td colspan="7" class="text-center">Tiada Rekod</td>
                                        </tr>
                                    @else

                                    @foreach ($recordList as $index => $data)
                                        @php $payer_name = ($data->payment_category_id!=3) ? $data->payer_name : $data->name."\n".$data->no_ic; @endphp
                                        <tr>
                                            <td class="text-center">{{ $loop->iteration }}</td>
                                            <td class="text-center">{{ $payer_name }}</td>
                                            <td class="text-center">{{ $data->payment_receipt_no }}</td>
                                            <td class="text-center">{{ $data->payment_notice_no }}</td>
                                            <td >{{ $data->payment_description }}</td>
                                            <td class="text-center">{{ convertDateTimeSys($data->payment_date) }}</td>
                                            <td class="text-center">{{ $data->payment_category }}</td>
                                            <td style="text-align:right">{{ $data->total_payment }}</td>
                                        </tr>
                                        @php $sum += $data->total_payment @endphp
                                        @endforeach
                                    @endif
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td class="text-center" colspan="7"><b>JUMLAH KESELURUHAN (RM)</b></td>
                                        <td style="text-align:right" ><b>{{numberFormatComma($sum)}}</b></td>
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
<script src="{{ URL::asset('assets/js/pages/PaymentRecord/paymentRecord.js')}}"></script>
@endsection
