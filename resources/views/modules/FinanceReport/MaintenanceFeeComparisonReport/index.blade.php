@extends('layouts.master')
@section('content')

<style>
    .align-right{
        text-align: right;
    }
    .fw-bolder {
    font-weight: bolder !important;
    }
</style>

<div class="row">
    <div class="col-12">
        <!-- section - search  -->
        <div class="card ">
            <form class="search_form custom-validation" action="{{ route('maintenanceFeeComparisonReport.index') }}" method="post">
                {{csrf_field()}}

                <div class="card-body p-3">
                    <div class="border-bottom border-primary mb-4"><h4 class="card-title">Carian Rekod</h4></div>

                    <div class="row">
                        <div class="col-md-3 p-1 mb-3" >
                            <label class="col-form-label ">Tahun<span class="text-danger"> *</span></label>
                            <select class="form-select" id="search_year" name="search_year" required data-parsley-required-message="{{ setMessage('search_year.required') }}">
                                <option value="">-- Sila Pilih --</option>
                                @foreach($year as $y)
                                    <option value="{{$y->year}}" {{old('search_year', $search_year) == $y->year? "selected" : ""}}>{{ $y->year }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-3 p-1 mb-3">
                            <label class="col-form-label">Bulan<span class="text-danger"> *</span></label>
                            <select class="form-select" id="search_month" name="search_month" required data-parsley-required-message="{{ setMessage('search_month.required') }}">
                                <option value="">-- Sila Pilih --</option>
                                @foreach($month as $m)
                                    <option value="{{ $m->id }}" {{ old('search_month', $search_month) == $m->id ? 'selected' : '' }}>  {{ $m->name }}  </option>
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
                            <table class="table table-bordered table-striped wrap w-100" >
                                <thead class="bg-primary bg-gradient text-white">
                                    <tr role="row">
                                        <th class="text-center" width="5%"> Bil. </th>
                                        <th class="text-center" width="20%"> Nama </th>
                                        <th class="text-center" width="10%"> No. Kad Pengenalan </th>
                                        <th class="text-center" width="35%"> Alamat Rumah </th>
                                        <th class="text-center" width="10%"> Anggaran <br/>(RM) </th>
                                        <th class="text-center" width="10%"> Kutipan Sebenar <br/>(RM) </th>
                                        <th class="text-center" width="10%"> Varian <br/>(RM) </th>
                                    </tr>
                                </thead>
                                @php
                                    $sum_expectation_amount = 0;
                                    $sum_amount = 0;
                                    $sum_varian = 0;
                                @endphp
                                <tbody>
                                    @if ($dataReport)
                                        @foreach($dataReport as $data)
                                            @php
                                                $quarters_address = ($data->unit_no ?? '').' '.$data->address_1.' '.$data->address_2.' '.$data->address_3;
                                                $expectation_amount = $data->maintenance_fee ?? 0;
                                                $amount = $data->amount ?? 0;
                                                $varian = $expectation_amount - $amount;

                                                $sum_expectation_amount += $expectation_amount;
                                                $sum_amount += $amount;
                                                $sum_varian += $varian;
                                            @endphp
                                            <tr>
                                                <th class="text-center" scope="row">{{ $loop->iteration.'.' }}</th>
                                                <td >{{ $data->name ?? '' }}</td>
                                                <td class="text-center" >{{ $data->new_ic ?? '' }}</td>
                                                <td >{{ $quarters_address ?? '' }}</td>
                                                <td class="align-right" >{{ numberFormatComma($expectation_amount) }}</td>
                                                <td class="align-right" >{{ numberFormatComma($amount) }}</td>
                                                <td class="align-right" >{{ numberFormatComma($varian) }}</td>
                                            </tr>
                                        @endforeach
                                    @endif
                                </tbody>
                                <tfoot>
                                    <tr class="bg-light fw-bolder align-right">
                                        <td colspan="4" ><b>JUMLAH KESELURUHAN </b></td>
                                        <td >{{ numberFormatComma($sum_expectation_amount) }}</td>
                                        <td >{{ numberFormatComma($sum_amount) }}</td>
                                        <td >{{ numberFormatComma($sum_varian) }}</td>
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

