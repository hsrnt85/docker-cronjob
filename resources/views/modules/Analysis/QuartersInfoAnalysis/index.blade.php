
@extends('layouts.master')

@section('content')

<div class="row">
    <div class="col-12">

        <!-- section - search  -->
        <div class="card ">

            <form class="custom-validation" action="" id="form-carian-aduan-awam">

                <div class="card-body p-3">

                    <div class="border-bottom border-primary mb-4"><h4 class="card-title">Carian Rekod</h4></div>
                    <div class="row">
                        <div class="col-md-3 p-1 mb-3" >
                            <label class="col-form-label ">Kategori Kuarters (Lokasi)<span class="text-danger"> *</span></label>
                            <select id="kategori-kuarters" name="kategori_kuarters" class="form-control select2" value="{{ old('kategori_kuarters') }}"
                                required
                                data-parsley-required-message="{{ setMessage('category.required') }}"
                                data-parsley-errors-container="#parsley-errors-kategori">
                                    <option value="">-- Pilih Kuarters --</option>
                                    @foreach ($quartersCategoryAll as $quartersCategory)
                                        <option value="{{ $quartersCategory->id }}" @if($quartersCategory->id == old('kategori_kuarters',$carian_kategori)) selected @endif>{{ $quartersCategory->name }}</option>
                                    @endforeach
                            </select>
                            <div id="parsley-errors-kategori"></div>
                        </div>
                        <div class="col-md-3 p-1 mb-3" >
                            <label class="col-form-label ">Status Kuarters</label>
                            <select id="status-kuarters" name="status_kuarters" class="form-control select2" value="{{ old('status_kuarters') }}"
                                data-parsley-required-message="{{ setMessage('tahun.required') }}"
                                data-parsley-errors-container="#parsley-errors-status">
                                    <option value="">-- Pilih Status --</option>
                                    @foreach ($conditionAll as $condition)
                                        <option value="{{ $condition->id }}" @if($condition->id == old('status_kuarters', $carian_status)) selected @endif>{{ $condition->name }}</option>
                                    @endforeach
                            </select>
                            <div id="parsley-errors-status"></div>
                        </div>
                        <div class="col-md-3 p-1 mb-3" >
                            <label class="col-form-label ">Kekosongan</label>
                            <select id="kekosongan" name="kekosongan" class="form-control select2" disabled value="{{ old('kekosongan') }}">
                                    <option value="">-- Pilih Kekosongan--</option>
                                    <option value="1" @if(1 == old('kekosongan', $carian_kekosongan)) selected @endif>Boleh Diduduki</option>
                                    <option value="2" @if(2 == old('kekosongan', $carian_kekosongan)) selected @endif>Berpenghuni</option>
                            </select>
                            <div id="parsley-errors-status"></div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12 p-1 mb-3">
                            <button class="btn btn-primary" type="submit">{{ __('button.cari') }}</button>
                            <a id="reset" class="btn btn-primary" type="submit" value="reset">Set Semula</a>
                            <a name="muat_turun_pdf" class="btn btn-success" type="submit" value="pdf" id="download-pdf">Muat Turun PDF</a>
                        </div>
                    </div>
                </div>

            </form>
        </div>
        <!-- end section - search  -->

        @if ($is_carian)
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
                                            <th class="text-center">Bil</th>
                                            <th class="text-center">No. Unit </th>
                                            <th class="text-center">Alamat Kuarters</th>
                                            <th class="text-center">Status Kuarters</th>
                                            @isset($carian_kategori)
                                                <th class="text-center">Kekosongan</th>
                                            @endisset
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if(isset($statistic) && count($statistic))
                                            @foreach ($statistic as $quarters)
                                                @php
                                                    // dd($quarters);
                                                @endphp
                                                <tr class="text-center">
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $quarters->unit_no }} - {{ $quarters->id }}</td>
                                                    <td>{{ $quarters->address_1 }}</td>
                                                    <td>{{ $quarters->quarters_condition->name }}</td>
                                                    @isset($carian_kategori)
                                                        {{-- <td>{{ $quarters->vacancy }}</td> --}}
                                                        <td>@if($quarters->current_active_tenant && $quarters->quarters_condition->id == 1) Berpenghuni @elseif($quarters->quarters_condition->id == 1) Boleh Diduduki @else Tidak Boleh Diduduki @endif</td>
                                                    @endisset
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr class="text-center">
                                                <td colspan="5">Tiada Rekod</td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="card">
                <div class="card-body">

                    {{-- PAGE TITLE --}}
                    <div class="border-bottom border-primary mb-4"><h4 class="card-title">{{ getPageTitle(1) }}</h4></div>

                    <div id="datatable_wrapper">
                        <div class="row">
                            <div class="col-sm-12">
                                <table id="my-table" class="table table-bordered dt-responsive wrap w-100 dataTable" role="grid">
                                    <thead class="bg-primary bg-gradient text-white">
                                        <tr role="row">
                                            <th class="text-center" >Bil</th>
                                            <th class="text-center" >Kuarters </th>
                                            <th class="text-center" >Jumlah</th>
                                            <th class="text-center" >Boleh Diduduki</th>
                                            <th class="text-center" >Berpenghuni</th>
                                            <th class="text-center" >Rosak</th>
                                            <th class="text-center" >Selenggara</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if(isset($statistic) && count($statistic))
                                        @foreach ($statistic as $quarters)

                                            <tr class="text-center">
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $quarters->name }}</td>
                                                <td>{{ $quarters->total_quarters }}</td>
                                                <td>{{ $quarters->total_empty + $quarters->total_left }}</td>
                                                <td>{{ $quarters->total_has_tenant }}</td>
                                                <td>{{ $quarters->total_rosak }}</td>
                                                <td>{{ $quarters->total_selenggara }}</td>
                                            </tr>

                                        @endforeach
                                        @endif

                                        <tr class="bg-secondary bg-gradient text-white">
                                            <td class="text-end" colspan="2"><strong>Jumlah</strong></td>
                                            <td class="text-center"><strong>{{ $gtTotalQuarters ?? ''}}</strong></td>
                                            <td class="text-center"><strong>{{ $gtTotalAvailable ?? ''}}</strong></td>
                                            <td class="text-center"><strong>{{ $gtTotalHasTenant ?? ''}}</strong></td>
                                            <td class="text-center"><strong>{{ $gtTotalRosak ?? ''}}</strong></td>
                                            <td class="text-center"><strong>{{ $gtTotalSelenggara ?? ''}}</strong></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <div id="jata" data-logo-url="{{ URL::asset('assets/images/jata-johor.png')}}"></div>
        <div id="url-reset" data-url="{{ route('quartersInfoAnalysis.index') }}"></div>
    </div> <!-- end col -->

</div>
<!-- end row -->

@endsection

@section('script')
<script src="{{ URL::asset('assets/js/pages/Analysis/QuartersInfoAnalysis/quartersInfoAnalysis.js')}}"></script>
@endsection
