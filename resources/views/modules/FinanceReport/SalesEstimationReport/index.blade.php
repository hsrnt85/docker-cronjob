@extends('layouts.master')

@section('content')
<div class="row">
    <div class="col-12">

        <!-- section - search  -->
        <div class="card ">
            <form class="search_form custom-validation" action="{{ route('salesEstimationReport.index') }}" method="post">
                {{csrf_field()}}

                <div class="card-body p-3">
                    <div class="border-bottom border-primary mb-4"><h4 class="card-title">Carian Rekod</h4></div>

                    <div class="row">
                        <div class="col-md-3 p-1 mb-3" >
                            <label class="col-form-label ">Tahun<span class="text-danger"> *</span></label>
                            <select class="form-select" id="carian_tahun" name="carian_tahun" required data-parsley-required-message="{{ setMessage('carian_tahun.required') }}">
                                <option value="">-- Sila Pilih --</option>
                                @foreach($yearAll as $y)
                                    <option value="{{$y->year}}" {{old('carian_tahun', $search_year) == $y->year? "selected" : ""}}>{{ $y->year }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-3 p-1 mb-3">
                            <label class="col-form-label">Bulan<span class="text-danger"> *</span></label>
                            <select class="form-select" id="carian_bulan" name="carian_bulan" required data-parsley-required-message="{{ setMessage('carian_bulan.required') }}">
                                <option value="">-- Sila Pilih --</option>
                                @foreach($monthAll as $m)
                                    <option value="{{ $m->id }}" {{ old('carian_bulan', $search_month) == $m->id ? 'selected' : '' }}>
                                        {{ $m->name }}
                                    </option>
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
                <div class="border-bottom border-primary mb-4"><h4 class="card-title">{{ getPageTitle(1) }}</h4></div>

                <div class="row">
                    <div class="col-sm-12">
                        <div data-simplebar style="max-height: 1000px;">
                            <table class="table table-striped table-bordered wrap w-100" >
                                <thead class="bg-primary bg-gradient text-white">
                                    <tr role="row">
                                        <th class="text-center" width="5%"> Bil. </th>
                                        <th class="text-center" width="30%"> Perihal Hasil </th>
                                        <th class="text-center" width="15%"> Anggaran Hasil Tahun {{ $search_year }} <br/>(RM) </th>
                                        <th class="text-center" width="15%"> Kutipan Hasil Sehingga <br/>{{ capitalizeText($till_date_name) }} <br/>(RM) </th>
                                        <th class="text-center" width="20%"> Anggaran Hasil <br/>Dari {{ $date_from_estimation_name }} <br/>Hingga {{ $date_to_estimation_name }} <br/>(RM)</th>
                                        <th class="text-center" width="15%"> Anggaran Hasil Keseluruhan Sehingga {{ $date_to_estimation_name }} <br/>(RM) </th>
                                    </tr>
                                </thead>
                                @php
                                    $sum_estimation_year = 0;
                                    $sum_till_date = 0;
                                    $sum_estimation_balance = 0;
                                    $sum_estimation_year_all = 0;
                                @endphp
                                <tbody>
                                    @if ($dataReport)
                                        @foreach($dataReport as $account_type => $data)
                                            @php
                                                $total_estimation_year = $data['total_estimation_year'];
                                                $total_till_date = $data['total_till_date'];
                                                $total_estimation_balance = $data['total_estimation_balance'];
                                                $total_estimation_year_all = $total_till_date + $total_estimation_balance;

                                                $sum_estimation_year += $total_estimation_year;
                                                $sum_till_date += $total_till_date;
                                                $sum_estimation_balance += $total_estimation_balance;
                                                $sum_estimation_year_all = $sum_till_date + $sum_estimation_balance;
                                            @endphp
                                            <tr class="info_content_border">
                                                <td class="text-center" scope="row">{{ $loop->iteration }}</td>
                                                <td>{{ $account_type }}</td>
                                                <td class="text-end">{{ numberFormatComma($total_estimation_year) }}</td>
                                                <td class="text-end">{{ numberFormatComma($total_till_date) }}</td>
                                                <td class="text-end">{{ numberFormatComma($total_estimation_balance) }}</td>
                                                <td class="text-end">{{ numberFormatComma($total_estimation_year_all) }}</td>
                                            </tr>
                                            @endforeach
                                    @endif
                                </tbody>
                                            <tfoot>
                                                <tr class="bg-light fw-bolder">
                                                    <td style="text-align: right;" colspan="2"><b>JUMLAH KESELURUHAN</b></td>
                                                    <td class="text-end">{{ numberFormatComma($sum_estimation_year) }}</td>
                                                    <td class="text-end">{{ numberFormatComma($sum_till_date) }}</td>
                                                    <td class="text-end">{{ numberFormatComma($sum_estimation_balance) }}</td>
                                                    <td class="text-end">{{ numberFormatComma($sum_estimation_year_all) }}</td>
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

