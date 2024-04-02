
@extends('layouts.master')

@section('content')

<div class="row">
    <div class="col-12">

        <!-- section - search  -->
        <div class="card ">

            <form class="search_form custom-validation" action="" id="form-carian-aduan-awam">

                <div class="card-body p-3">

                    <div class="border-bottom border-primary mb-4"><h4 class="card-title">Carian Rekod</h4></div>
                    <div class="row">
                        <div class="col-md-3 p-1 mb-3" >
                            <label class="col-form-label ">Tahun</label>
                            <select id="tahun" name="tahun" class="form-control form-select" value="{{ old('tahun') }}"
                            required
                            data-parsley-required-message="{{ setMessage('tahun.required') }}"
                            data-parsley-errors-container="#parsley-errors-tahun">
                                <option value="">-- Pilih Tahun --</option>
                                @foreach ($tahunDBAll as $tahunDB)
                                    <option value="{{ $tahunDB->tahun }}" @if($tahunDB->tahun == $tahun) selected @endif>{{ $tahunDB->tahun }}</option>
                                @endforeach
                            </select>
                            <div id="parsley-errors-tahun"></div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12 p-1 mb-3">
                            <button class="btn btn-primary" type="submit">{{ __('button.cari') }}</button>
                            <button name="reset" id="reset" class="btn btn-primary" type="button">{{ __('button.reset') }}</button>
                            <button name="muat_turun_pdf" class="btn btn-success" type="submit" value="pdf" id="download-pdf">{{ __('button.muat_turun_pdf') }}</button>
                        </div>
                    </div>
                </div>

            </form>
            <div id="url-reset" data-url="{{ route('quartersApplicationAnalysis.index') }}"></div>

        </div>
        <!-- end section - search  -->


        <!-- section - carta -->
        <div class="card">
            <div class="card-body">
                {{-- PAGE TITLE --}}
                <div class="border-bottom border-primary mb-4"><h4 class="card-title">Carta Bar Permohonan</h4></div>

                <div class="row">
                    <div class="col-sm-12 col-md-8 offset-md-2">
                        <canvas id="myChart" class="chart"></canvas>
                        <div id="chartData" data-chart-data="{{ json_encode($mergedResults) ?? '' }}"></div>
                    </div>
                </div>

            </div>
        </div>
        <!-- end section - carta -->

        <!-- section - table statistik -->
        <div class="card">
            <div class="card-body">

                {{-- PAGE TITLE --}}
                <div class="border-bottom border-primary mb-4"><h4 class="card-title">{{ getPageTitle(1) }}</h4></div>

                <div id="datatable_wrapper">
                    <div class="row">
                        <div class="col-sm-12">
                            <table id="my-table" class="table table-striped table-bordered dt-responsive wrap w-100 dataTable" role="grid">
                                <thead class="bg-primary bg-gradient text-white">
                                    <tr role="row">
                                        <th class="text-center" >Bil</th>
                                        <th class="text-center" >Bulan </th>
                                        <th class="text-center" >Jumlah Permohonan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(isset($mergedResults) && count($mergedResults))
                                    @foreach ($mergedResults as $result)

                                        <tr class="text-center">
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $result->bm }}</td>
                                            <td>{{ $result->applications_count }}</td>
                                        </tr>

                                    @endforeach
                                    @endif

                                    <tr class="bg-secondary bg-gradient text-white">
                                        <td class="text-end" colspan="2"><strong>Jumlah</strong></td>
                                        <td class="text-center"><strong>{{ $gtJumlah ?? ''}}</strong></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- end section - list report -->

        <div id="jata" data-logo-url="{{ URL::asset('assets/images/jata-johor.png')}}"></div>

    </div> <!-- end col -->

</div>
<!-- end row -->

@endsection

@section('script')
<script src="{{ URL::asset('assets/js/pages/Analysis/QuartersApplicationAnalysis/quartersApplicationAnalysis.js')}}"></script>
@endsection
