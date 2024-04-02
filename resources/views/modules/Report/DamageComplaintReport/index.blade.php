
@extends('layouts.master')

@section('content')

<div class="row">
    <div class="col-12">

        <!-- section - search  -->
        <div class="card ">

            <form class="search_form custom-validation" action="{{ route('damageComplaintReport.index') }}" method="post">

                {{ csrf_field() }}

                <div class="card-body p-3">

                    <div class="border-bottom border-primary mb-4"><h4 class="card-title">Carian Rekod</h4></div>

                    <div class="row">
                        <div class="col-md-3 p-1 mb-3" >
                            <label class="col-form-label ">Tarikh Dari <span class="text-danger"> *</span></label>
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
                            <select id="carian_id_kategori" name="carian_id_kategori" class="form-control select2" value="{{ old('carian_id_kategori') }}"
                            required data-parsley-required-message="{{ setMessage('carian_id_kategori.required') }}"
                            data-parsley-errors-container="#parsley-errors-category-quarters">
                                <option value="">-- Pilih Kategori Kuarters --</option>
                                @foreach($quarters_category as $data)
                                    @if($data -> id == $carian_id_kategori)
                                        <option value="{{ $data -> id }}" selected>{{ $data -> name }}</option>
                                    @else
                                        <option value="{{ $data -> id }}">{{ $data -> name }}</option>
                                    @endif
                                @endforeach
                            </select>
                            <div id="parsley-errors-category-quarters"></div>
                            @error('position_type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-3 p-1 mb-3" >
                            <label class="col-form-label ">Status Aduan </label>
                            <select id="status_aduan" name="status_aduan" class="form-control select2">
                                <option value="">-- Pilih Status Aduan --</option>
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
                            {{-- <button name="reset" class="btn btn-primary" type="submit" value="reset"  onClick ="clearSearchInput()">{{ __('button.reset') }}</button> --}}
                            <button name="reset" id="reset" class="btn btn-primary"  onClick ="clearSearchInput()">{{ __('button.reset') }}</button>
                            <button name="muat_turun_pdf" class="btn btn-success" type="submit" value="pdf" onClick="setFormTarget(this)">{{ __('button.muat_turun_pdf') }}</button>
                            {{-- <button name="muat_turun_excel" class="btn btn-success" type="submit" value="excel" onClick="setFormTarget()">{{ __('button.muat_turun_excel') }}</button> --}}
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
                <div class="border-bottom border-primary mb-4"><h4 class="card-title">{{ getPageTitle(1) }}</h4></div>

                <div id="datatable_wrapper">
                    <div class="row">
                        <div class="col-sm-12">
                            <div data-simplebar style="max-height: 1000px;">
                                <table id="datatable-report" class="table table-bordered table-striped dt-responsive wrap w-100" role="grid">
                                    <thead class="bg-primary bg-gradient text-white">
                                        <tr role="row">
                                            <th class="text-center">Bil.</th>
                                            <th width="" class="text-center">No. Aduan</th>
                                            <th width="" class="text-center">Nama Pengadu</th>
                                            <th width="" class="text-center">Tarikh Aduan</th>
                                            <th width="" class="text-center">Tarikh Temujanji</th>
                                            <th width="" class="text-center">Alamat</th>
                                            <th width="" class="text-center">Butiran <br> Kerosakan</th>
                                            <th width="15%" class="text-center">Gambar Aduan</th>
                                            <th width="" class="text-center">Butiran <br>(lain-lain)</th>
                                            <th width="15%" class="text-center">Gambar Aduan <br>(lain-lain)</th>
                                            <th width="" class="text-center">Status Aduan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($complaintListAll as $bil => $complaintList)
                                            @php
                                                $unitno = $complaintList->quarters?->unit_no . ", " ??'';
                                                $add1 = $complaintList->quarters?->address_1 . ", " ??'';
                                                $add2 = $complaintList->quarters?->address_2 . ", " ??'';
                                                $add3 = $complaintList->quarters?->address_3 ??'';
                                                $full_address = $unitno.$add1.$add2.$add3;
                                            @endphp
                                            <tr>
                                                <th class="text-center" scope="row">{{ ++$bil }}</th>
                                                <td class="" >{{ $complaintList->ref_no ??'' }}</td>
                                                <td class="" >{{ $complaintList->user->name ??'' }}</a></td>
                                                <td class="text-center" >@if(isset($complaintList?->complaint_date)) {{ convertDateSys($complaintList->complaint_date) }} @else '' @endif</a></td>
                                                <td class="text-center" >@if(isset($complaintList?->appointment_date)) {{ convertDateSys($complaintList->appointment_date) }} @else '' @endif</a></td>
                                                <td class="" >{{ $full_address }}</a></td>
                                                <td class="" style="text-align:justify; text-align:left;">
                                                    @if($complaintList->complaint_inventory)
                                                        <ul>
                                                            @foreach($complaintList->complaint_inventory as $damageinv)
                                                                <li>{{$damageinv->inventory->name}} - {{$damageinv->description}}</li>
                                                            @endforeach
                                                        </ul>
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    @if ($complaintList->complaint_inventory->count() != 0)
                                                        @foreach ($complaintList->complaint_inventory as $inventory)
                                                            <img src="{{ getCdn() . $inventory->attachment?->path_document }}" class="img-thumbnail">
                                                        @endforeach
                                                    @endif
                                                </td>
                                                <td class="" style="text-align:justify; text-align:left;">
                                                    @if($complaintList->complaint_others)
                                                    <ul>
                                                        @foreach($complaintList->complaint_others as $others)
                                                            <li> {{$others->description}}</li>
                                                        @endforeach
                                                    </ul>
                                                    @endif
                                                </td>
                                                <td class="" style="text-align:justify; text-align:left; vertical-align:baseline;">
                                                    @if ($complaintList->complaint_others->count() != 0)
                                                        @foreach ($complaintList->complaint_others as $complaint_other)
                                                            @if ($complaint_other->attachments->count() != 0)
                                                                @foreach ($complaint_other->attachments as $attachment)
                                                                    <img src="{{ getCdn() . $attachment->path_document }}" class="img-thumbnail">
                                                                @endforeach
                                                            @endif
                                                        @endforeach
                                                    @endif
                                                </td>
                                                <td>{{ $complaintList->status->complaint_status }}</td>
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
{{-- <script>
 function clearSearchInput(){
   document.getElementById("form-laporan-aduan-langgar-peraturan").reset();
 }
</script> --}}
@endsection
