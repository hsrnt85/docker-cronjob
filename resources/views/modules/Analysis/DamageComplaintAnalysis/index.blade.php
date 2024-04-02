
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
                            <label for="carian_tarikh_aduan_dari" class="col-form-label ">Tarikh Dari <span class="text-danger"> *</span></label>
                            <div class="input-right-icon">
                                <input class="form-control" type="date" id="carian_tarikh_aduan_dari" name="carian_tarikh_aduan_dari" value="{{ old('carian_id_kategori', $from) }}">
                                <span class="span-right-input-icon">
                                    <i class="icon-regular i-Calendar-4"></i>
                                </span>
                            </div>
                        </div>
                        <div class="col-md-3 p-1 mb-3" >
                            <label class="col-form-label ">Tarikh Hingga <span class="text-danger"> *</span></label>
                            <div class="input-right-icon">
                                <input class="form-control" type="date" id="carian_tarikh_aduan_hingga" name="carian_tarikh_aduan_hingga" value="{{ old('carian_id_kategori', $to) }}">
                                <span class="span-right-input-icon">
                                    <i class="icon-regular i-Calendar-4"></i>
                                </span>
                            </div>
                        </div>
                        {{-- required data-parsley-required-message="{{ setMessage('carian_id_kategori.required') }}" --}}
                        <div class="col-md-3 p-1 mb-3" >
                            <label class="col-form-label ">Kategori Kuarters (Lokasi)</label>
                            <select id="carian_id_kategori" name="carian_id_kategori" class="form-control select2" value="{{ old('carian_id_kategori') }}"
                            data-parsley-errors-container="#parsley-errors-category-quarters">
                                <option value="">-- Pilih Kategori Kuarters --</option>
                                @foreach ($quartersCategoryAll as $category)
                                    <option value="{{ $category->id }}" @if($category->id == $categoryId) selected @endif>{{ $category->name }}</option>
                                @endforeach
                            </select>
                            <div id="parsley-errors-category-quarters"></div>
                            @error('position_type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12 p-1 mb-3">
                            <button class="btn btn-primary" type="submit">{{ __('button.cari') }}</button>
                            <button name="reset" class="btn btn-primary" type="submit" value="reset"  onClick ="clearSearchInput()">{{ __('button.reset') }}</button>
                            <button name="muat_turun_pdf" class="btn btn-success" type="submit" value="pdf" id="download-pdf">Muat Turun PDF</button>
                        </div>
                    </div>
                </div>

            </form>
        </div>
        <!-- end section - search  -->


        <!-- section - carta -->
        <div class="card">
            <div class="card-body">
                {{-- PAGE TITLE --}}
                <div class="border-bottom border-primary mb-4"><h4 class="card-title">Carta Aduan Kerosakan</h4></div>

                <div class="row">
                    <div class="col-sm-12 col-md-8 offset-md-2">
                        <canvas id="myChart" class="chart"></canvas>
                        <div id="chartData" data-chart-data="{{ $chartData ?? '' }}"></div>
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
                                        <th class="text-center" >Kategori Kuarters <br/> (Lokasi)</th>
                                        <th class="text-center" >Jumlah Aduan</th>
                                        <th class="text-center" >Dalam Pemantauan</th>
                                        <th class="text-center" >Dalam Penyelenggaraan</th>
                                        <th class="text-center" >Aduan <br/> Selesai</th>
                                        <th class="text-center" >Aduan <br/> Ditolak</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(isset($damageStatisticByQuartersCategory) && count($damageStatisticByQuartersCategory))
                                    @foreach ($damageStatisticByQuartersCategory as $category)
                                        <tr class="text-center">
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $category->name }}</td>
                                            <td>{{ $category->quarters->sum('jumlah') }}</td>
                                            <td>{{ $category->quarters->sum('dalam_pemantauan') }}</td>
                                            <td>{{ $category->quarters->sum('dalam_penyelenggaraan') }}</td>
                                            <td>{{ $category->quarters->sum('ditolak') }}</td>
                                            <td>{{ $category->quarters->sum('selesai') }}</td>
                                        </tr>
                                    @endforeach
                                    <tr class="bg-secondary bg-gradient text-white">
                                        <td class="text-end" colspan="2"><strong>Jumlah</strong></td>
                                        <td class="text-center"><strong>{{ $gtJumlah ?? '0'}}</strong></td>
                                        <td class="text-center"><strong>{{ $gtDalamPemantauan ?? '0'}}</strong></td>
                                        <td class="text-center"><strong>{{ $gtDalamPenyelenggaraan ?? '0'}}</strong></td>
                                        <td style="text-align: center"><strong>{{ $gtDitolak ?? '0'}}</strong></td>
                                        <td class="text-center"><strong>{{ $gtSelesai ?? '0'}}</strong></td>
                                    </tr>
                                    @else
                                    <tr>
                                        <td class="text-center" colspan="7">
                                            Tiada Rekod
                                        </td>
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

        <div id="jata" data-logo-url="{{ URL::asset('assets/images/jata-johor.png')}}"></div>

    </div> <!-- end col -->

</div>
<!-- end row -->

@endsection

@section('script')
<script src="{{ URL::asset('assets/js/pages/Analysis/DamageComplaintAnalysis/damageComplaintAnalysis.js')}}"></script>
@endsection
