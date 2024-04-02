
@extends('layouts.master')
@section('css')
    <link href="{{ URL::asset('/assets/libs/bootstrap-datepicker/bootstrap-datepicker.min.css') }}" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="{{ URL::asset('/assets/libs/datepicker/datepicker.min.css') }}">
@endsection
@section('content')

<div class="row">
    <div class="col-12">

        <!-- section - search  -->
        <div class="card">

            <form class="search_form custom-validation" action="{{ route('rulesViolationComplaintReport.index') }}" method="post">
                {{ csrf_field() }}
                <div class="card-body p-3">

                    <div class="border-bottom border-primary mb-4"><h4 class="card-title">Carian Rekod</h4></div>

                    <div class="row">
                        <div class="col-md-3 p-1 mb-3" >
                            <label for="carian_tarikh_aduan_dari" class="col-form-label ">Tarikh Dari <span class="text-danger"> *</span></label>
                            <div class="input-group" id="datepicker2">
                                <input class="form-control"  type="text" placeholder="dd/mm/yyyy" name="carian_tarikh_aduan_dari" id="carian_tarikh_aduan_dari"
                                data-date-format="dd/mm/yyyy" data-date-container='#datepicker2' data-provide="datepicker" autocomplete="off"
                                data-date-autoclose="true" value="{{  old('carian_tarikh_aduan_dari', $carian_tarikh_aduan_dari) }}" required data-parsley-required-message="{{ setMessage('carian_tarikh_aduan_dari.required') }}"
                                data-parsley-errors-container="#date_from_error" >
                                <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
                            </div>
                            <div id="date_from_error" ></div>
                        </div>
                        <div class="col-md-3 p-1 mb-3" >
                            <label class="col-form-label ">Tarikh Hingga <span class="text-danger"> *</span></label>
                            <div class="input-group" id="datepicker2">
                                <input class="form-control"  type="text" placeholder="dd/mm/yyyy" name="carian_tarikh_aduan_hingga" id="carian_tarikh_aduan_hingga"
                                data-date-format="dd/mm/yyyy" data-date-container='#datepicker2' data-provide="datepicker" autocomplete="off"
                                data-date-autoclose="true" value="{{  old('carian_tarikh_aduan_hingga', $carian_tarikh_aduan_hingga) }}" required data-parsley-required-message="{{ setMessage('carian_tarikh_aduan_hingga.required') }}"
                                data-parsley-errors-container="#date_to_error" >
                                <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
                            </div>
                            <div id="date_to_error" ></div>
                        </div>

                        <div class="col-md-3 p-1 mb-3" >
                            <label class="col-form-label ">Kategori Kuarters (lokasi) <span class="text-danger"> *</span></label>
                            <select id="carian_kategori" name="carian_kategori" class="form-control select2"
                            required data-parsley-required-message="{{ setMessage('carian_id_kategori.required') }}"
                            data-parsley-errors-container="#parsley-quarters">
                                <option value="">-- Pilih Kategori Kuarters --</option>
                                @foreach($quarters_category as $data)
                                    @if($data -> id == $carian_kategori)
                                        <option value="{{ $data -> id }}" selected>{{ $data -> name }}</option>
                                    @else
                                        <option value="{{ $data -> id }}">{{ $data -> name }}</option>
                                    @endif
                                @endforeach
                            </select>
                            <div id="parsley-quarters"></div>
                        </div>

                        <div class="col-md-3 p-1 mb-3" >
                            <label class="col-form-label ">Status Aduan</label>
                            <select id="status_aduan" name="status_aduan" class="form-control select2">
                                <option value="">-- Pilih Status --</option>
                                @foreach($complaintStatusAll as $status)
                                    @if($status -> id == $carian_status_aduan)
                                        <option value="{{ $status -> id }}" selected>{{ $status->complaint_status }}</option>
                                    @else
                                        <option value="{{ $status -> id }}">{{ $status->complaint_status }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12 p-1 mb-3">
                            <button class="btn btn-primary" type="submit" onClick="setFormTarget()">{{ __('button.cari') }}</button>
                            <button name="reset" class="btn btn-primary" type="submit" value="reset"  onClick ="clearSearchInput()">{{ __('button.reset') }}</button>
                            <button name="muat_turun_pdf" class="btn btn-success" type="submit" value="pdf" onClick="setFormTarget(this)">{{ __('button.muat_turun_pdf') }}</button>
                            {{-- <button name="muat_turun_excel" class="btn btn-success" type="submit" value="excel" onClick="setFormTarget()">{{ __('button.muat_turun_excel') }}</button> --}}
                        </div>
                    </div>
                </div>

            </form>
        </div>

         <!-- section - list report -->
         <div class="card">
            <div class="card-body">

                {{-- PAGE TITLE --}}
                <div class="border-bottom border-primary mb-4"><h4 class="card-title">{{ getPageTitle(1) }}</h4></div>

                <div id="datatable_wrapper">
                    <div class="row">
                        <div class="col-sm-12">
                            <div data-simplebar style="max-height: 1000px;">
                                <table id="datatable-report" class="table wrap w-100 dataTable table-striped table-bordered">
                                    <thead class="bg-primary bg-gradient text-white">
                                        <tr role="row">
                                            <th class="text-center" width="5%">Bil.</th>
                                            <th width="10%" class="text-center">No. Aduan</th>
                                            <th width="15%" class="text-center">Nama Pengadu</th>
                                            <th width="10%" class="text-center">Tarikh Aduan</th>
                                            <th width="20%" class="text-center">Alamat Kuarters</th>
                                            <th width="20%" class="text-center">Butiran Aduan</th>
                                            <th width="10%" class="text-center">Gambar Aduan</th>
                                            <th width="10%" class="text-center">Status Aduan</th>

                                            {{-- <th width="8%" class="text-center">Tarikh Temujanji</th> --}}
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($complaintListAll as $bil => $complaintList)
                                            @php
                                                $unitno = $complaintList -> unit_no . ", " ??'';
                                                $add1 = $complaintList -> address_1 ??'';
                                                $add2 = ($complaintList -> address_2) ? ", ". $complaintList -> address_2 : '';
                                                $add3 = ($complaintList -> address_3) ? ", ". $complaintList -> address_3 : '';
                                                $full_address = $unitno.$add1.$add2.$add3;
                                            @endphp
                                            <tr>
                                                <th class="text-center" scope="row">{{ ++$bil }}</th>
                                                <td class="text-center">{{ $complaintList->ref_no ??'' }}</a></td>
                                                <td >{{ $complaintList->name ??'' }}</a></td>
                                                <td class="text-center" >{{ convertDateSys($complaintList->complaint_date) ??'' }}</a></td>
                                                <td class="align-left justify" >{{ $full_address }}</a></td>
                                                <td class="align-left justify">{{ $complaintList->complaint_description ?? '' }}</a></td>
                                                <td class="text-center" >
                                                    @if ($complaintList->attachment->count() != 0)
                                                        @foreach ($complaintList->attachment as $attachment)
                                                            <img src="{{ getCdn() . $attachment->path_document }}" class="img-thumbnail">
                                                        @endforeach
                                                    @else
                                                    -
                                                    @endif
                                                </td>
                                                <td class="text-center" >{{ $complaintList->status->complaint_status??'' }}</a></td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
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
@endsection
