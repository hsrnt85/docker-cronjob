@extends('layouts.master')

@section('content')

    <div class="row">
        <div class="col-12">

            <!-- section - search  -->
            <div class="card ">

                <form class="search_form custom-validation" action="{{ route('blacklistPenaltyReport.index') }}" method="get" id="form-laporan-aduan-kerosakan">

                    {{ csrf_field() }}

                    <div class="card-body p-3">

                        <div class="border-bottom border-primary mb-4">
                            <h4 class="card-title">Carian Rekod</h4>
                        </div>

                        <div class="row">
                            <div class="col-md-3 p-1 mb-3">
                                <label for="dateFrom" class="col-form-label ">Tarikh Dari <span class="text-danger"> *</span></label>
                                <div class="input-group" id="datepicker2">
                                    <input class="form-control @error('date_from') is-invalid @enderror" type="text" placeholder="dd/mm/yyyy" name="date_from" id="date-from"
                                        data-date-format="dd/mm/yyyy" data-date-container='#datepicker2' data-provide="datepicker" autocomplete="off" data-date-autoclose="true"
                                        required data-parsley-required-message="{{ setMessage('date_from.required') }}" data-parsley-errors-container="#parsley-errors-date-from"
                                        value="{{ convertDateSys($selectedDateFrom) ?? '' }}">
                                    <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
                                </div>
                                <div id="parsley-errors-date-from"></div>
                                @error('date_from')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="col-md-3 p-1 mb-3">
                                <label class="col-form-label ">Tarikh Hingga <span class="text-danger"> *</span></label>
                                <div class="input-group" id="datepicker2">
                                    <input class="form-control @error('date_to') is-invalid @enderror" type="text" placeholder="dd/mm/yyyy" name="date_to" id="date-to"
                                        data-date-format="dd/mm/yyyy" data-date-container='#datepicker2' data-provide="datepicker" autocomplete="off" data-date-autoclose="true"
                                        required data-parsley-required-message="{{ setMessage('date_to.required') }}" data-parsley-errors-container="#parsley-errors-date-to"
                                        value="{{ convertDateSys($selectedDateTo) ?? '' }}">
                                    <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
                                </div>
                                <div id="parsley-errors-date-to"></div>
                                @error('date_to')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="col-md-3 p-1 mb-3">
                                <label class="col-form-label">Kategori Kuarters (lokasi)</label>
                                <select id="quarters_cat" name="quarters_cat" class="form-select" data-parsley-errors-container="#parsley-errors-quarters-cat">
                                    <option value=''> -- Kategori Kuarters (Lokasi) -- </option>
                                    @foreach ($quartersCatsAll as $quarters_cat)
                                        <option value='{{ $quarters_cat->id }}' @if ($quarters_cat->id == $selectedQuartersCat) selected @endif>{{ $quarters_cat->name }}</option>
                                    @endforeach
                                </select>
                                <div id="parsley-errors-quarters-cat"></div>
                                @error('quarters_cat')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>


                            {{-- <div class="col-md-3 p-1 mb-3">
                                <label class="col-form-label ">Pegawai Pemantau</label>
                                <select id="officer" name="officer" class="form-select" value="{{ old('officer', $selectedOfficer) }}"
                                    data-parsley-required-message="{{ setMessage('officer.required') }}" data-parsley-errors-container="#parsley-errors-officer">
                                    <option value=''> -- Pilih Pegawai -- </option>
                                    @foreach ($officerAll as $officer)
                                        <option value='{{ $officer->id }}' @if ($officer->id == $selectedOfficer) selected @endif>{{ $officer->user->name }}</option>
                                    @endforeach
                                </select>
                                <div id="parsley-errors-officer"></div>
                                @error('officer')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div> --}}
                        </div>

                        <div class="row">
                            <div class="col-md-12 p-1 mb-3">
                                <button class="btn btn-primary" type="submit" onClick="setFormTarget()">{{ __('button.cari') }}</button>
                                <button name="reset" class="btn btn-primary" type="submit" value="reset" onClick="clearSearchInput()">{{ __('button.reset') }}</button>
                                <button name="muat_turun_pdf" class="btn btn-success" type="submit" value="pdf"
                                    onClick="setFormTarget(this)">{{ __('button.muat_turun_pdf') }}</button>
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
                    <div class="border-bottom border-primary mb-4">
                        <h4 class="card-title">{{ getPageTitle(1) }}</h4>
                    </div>

                    <div id="datatable_wrapper">
                        <div class="row">
                            <div class="col-sm-12">
                                <table id="datatable-report" class="table table-striped table-bordered dt-responsive no-wrap w-100 dataTable" role="grid">
                                    <thead class="bg-primary bg-gradient text-white">
                                        <tr role="row">
                                            <th class="text-center">Bil.</th>
                                            <th width="" class="text-center">No. Rujukan Denda</th>
                                            <th width="" class="text-center">Nama Penghuni</th>
                                            <th width="" class="text-center">No. Kad Pengenalan</th>
                                            <th width="" class="text-center">Alamat Kuarters</th>
                                            <th width="" class="text-center">Jawatan</th>
                                            <th width="" class="text-center">Jabatan</th>
                                            <th width="" class="text-center">Tarikh Hilang Kelayakan</th>
                                            <th width="" class="text-center">Tarikh Kuatkuasa</th>
                                            <th width="" class="text-center">Sebab Hilang Kelayakan</th>
                                            <th width="" class="text-center">Jumlah Denda (RM)</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if ($blacklistPenaltyAll)
                                            @foreach ($blacklistPenaltyAll as $ind => $bp)
                                                <tr>
                                                    <td class="text-center" scope="row">{{ $loop->iteration }}</td>
                                                    <td class="text-center">{{ $bp->penalty_ref_no }}</td>
                                                    <td >{{ $bp->tenant->user->name }}</td>
                                                    <td class="text-center">{{ $bp->tenant->user->new_ic }}</td>
                                                    <td >{{ $bp->tenant->quarters->address_1 . ' ' . $bp->tenant->quarters->address_2 }}</td>
                                                    <td >{{ upperText($bp->tenant->user->position->position_name) }}</td>
                                                    <td >{{ $bp->tenant->user->office->organization->name }}</td>
                                                    <td class="text-center">{{ convertDateSys($bp->penalty_date) }}</td>
                                                    <td class="text-center">{{ convertDateSys($bp->execution_date) }}</td>
                                                    <td >{{ $bp->tenant?->reason?->blacklist_reason ?? $bp->tenant?->blacklist_reason_others }}</td>
                                                    <td class="text-center">{{ $bp->penalty_amount }}</td>
                                                </tr>
                                            @endforeach
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
    <script>
        function clearSearchInput() {
            document.getElementById("form-laporan-aduan-langgar-peraturan").reset();
        }
    </script>
@endsection
