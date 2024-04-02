@extends('layouts.master')

@section('content')

<div class="row">
    <div class="col-12">
        <!-- section - search  -->
        <div class="card ">
            <form class="search_form custom-validation" action="{{ route('salesPerformanceReport.index') }}" method="post">
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
                {{-- PAGE TITLE --}}
                <div class="border-bottom border-primary mb-4"><h4 class="card-title">{{ getPageTitle(1) }}</h4></div>

                <div class="row">
                    <div class="col-sm-12">
                        <div data-simplebar style="max-height: 1000px;">
                            <table class="table table-striped table-bordered wrap w-100" >
                                <thead class="bg-primary bg-gradient text-white">
                                    <tr role="row">
                                        <th class="text-center" width="5%"> Bil. </th>
                                        {{-- <th class="text-center" width="10%"> Kod Hasil </th> --}}
                                        <th class="text-center" width="30%"> Perihal Hasil </th>
                                        <th class="text-center" width="15%"> Kutipan Sebenar <br/>Tahun {{ $year_prev }} (RM) </th>
                                        <th class="text-center" width="15%"> Anggaran Hasil <br/>Tahun {{ $search_year }} (RM) </th>
                                        <th class="text-center" width="15%"> Kutipan Hasil Sehingga <br/>{{ capitalizeText($till_date_name) }} (RM) </th>
                                        <th class="text-center"> Peratus (%) </th>
                                    </tr>
                                </thead>
                                @php
                                    $sum_last_year = 0;
                                    $sum_estimation = 0;
                                    $sum_current = 0;
                                    $sum_percentage = 0;
                                @endphp
                                <tbody>
                                    @if ($dataReport)
                                        @foreach($dataReport as $account_type => $data)
                                           @php
                                                $total_last_year = $data['total_last_year'];
                                                $total_estimation = $data['total_estimation'];
                                                $total_current = $data['total_current'];

                                                $percentage = ($total_estimation>0) ? ($total_current/$total_estimation) * 100 : 0;

                                                $sum_last_year += $total_last_year;
                                                $sum_estimation += $total_estimation;
                                                $sum_current += $total_current;
                                                if ($sum_estimation != 0) {
                                                    $sum_percentage = $sum_current / $sum_estimation * 100;
                                                } else {
                                                    $sum_percentage = 0; // or any other value you want to assign when the denominator is zero
                                                }

                                            @endphp
                                            <tr>
                                                <td class="text-center" scope="row">{{ $loop->iteration }}</td>
                                                <td>{{ $account_type }}</td>
                                                <td class="text-end">{{ numberFormatComma($total_last_year) }}</td>
                                                <td class="text-end">{{ numberFormatComma($total_estimation) }}</td>
                                                <td class="text-end">{{ numberFormatComma($total_current) }}</td>
                                                <td class="text-end">{{ numberFormatNoComma($percentage) }}</td>
                                            </tr>

                                        @endforeach
                                    @endif
                                </tbody>
                                <tfoot>
                                    <tr class="bg-light fw-bolder">
                                        <td style="text-align: right;" colspan="2"><b>JUMLAH KESELURUHAN</b></td>
                                        <td class="text-end">{{ numberFormatComma($sum_last_year) }}</td>
                                        <td class="text-end">{{ numberFormatComma($sum_estimation) }}</td>
                                        <td class="text-end">{{ numberFormatComma($sum_current) }}</td>
                                        <td style="text-align: right;"><b>{{ numberFormatComma($sum_percentage) }}</b></td>

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
