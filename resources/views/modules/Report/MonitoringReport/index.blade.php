@extends('layouts.master')
@section('css')
    <link href="{{ URL::asset('/assets/libs/bootstrap-datepicker/bootstrap-datepicker.min.css') }}" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="{{ URL::asset('/assets/libs/datepicker/datepicker.min.css') }}">
@endsection
@section('content')
<div class="row">
    <div class="col-12">
        <!-- section - search  -->
        <div class="card ">
            <form class="search_form custom-validation" action="{{ route('monitoringReport.index') }}" method="post">
                {{csrf_field()}}

                <div class="card-body p-3">
                    <div class="border-bottom border-primary mb-4"><h4 class="card-title">Carian Rekod</h4></div>

                    <div class="row">
                        <div class="col-md-3 p-1 mb-3" >
                            <label class="col-form-label ">Tarikh Dari<span class="text-danger"> *</span></label>
                            <div class="input-group" id="datepicker2">
                                <input class="form-control"  type="text" placeholder="dd/mm/yyyy" name="date_from" id="date_from"
                                data-date-format="dd/mm/yyyy" data-date-container='#datepicker2' data-provide="datepicker" autocomplete="off"
                                data-date-autoclose="true" value="{{  old('date_from', $search_date_from) }}" required data-parsley-required-message="{{ setMessage('date_from.required') }}"
                                data-parsley-errors-container="#date_from_error" >
                                <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
                            </div>
                            <div id="date_from_error" ></div>
                        </div>

                        <div class="col-md-3 p-1 mb-3">
                            <label class="col-form-label">Tarikh Hingga<span class="text-danger"> *</span></label>
                            <div class="input-group" id="datepicker2">
                                <input class="form-control"  type="text" placeholder="dd/mm/yyyy" name="date_to" id="date_to"
                                data-date-format="dd/mm/yyyy" data-date-container='#datepicker2' data-provide="datepicker" autocomplete="off"
                                data-date-autoclose="true" value="{{  old('date_to', $search_date_to) }}" required data-parsley-required-message="{{ setMessage('date_to.required') }}"
                                data-parsley-errors-container="#date_to_error" >
                                <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
                            </div>
                            <div id="date_to_error" ></div>
                        </div>

                        <div class="col-md-3 p-1 mb-3" >
                            <label class="col-form-label">Kategori Kuarters (lokasi) <span class="text-danger"> *</span></label>
                            <select id="quarters_category" name="quarters_category" class="form-control select2" value="{{ old('quarters_category') }}"
                            required data-parsley-required-message="{{ setMessage('quarters_category.required') }}"
                            data-parsley-errors-container="#parsley-errors-category-quarters">
                                <option value="">-- Pilih Kategori Kuarters --</option>
                                @foreach($quartersCatAll as $data)
                                    @if($data -> id == $search_quarters_cat)
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
                            <label class="col-form-label ">Jenis Aduan <span class="text-danger"> *</span></label>
                            <input type="hidden" id="selected-complaint-type" value="{{ $search_type }}">
                            <select id="complaint_type" name="complaint_type" class="form-select select-complaint-type" data-route="{{ route('monitoringReport.ajaxGetComplaintStatus') }}"
                            required data-parsley-required-message="{{ setMessage('complaint_type.required') }}"
                            data-parsley-errors-container="#parsley-errors-complaint-type">
                                <option value="default">-- Pilih Jenis Aduan --</option>
                                @foreach($complaintTypeAll as $complaintType)
                                    <option value="{{ $complaintType->id }}" @if($complaintType->id == old('complaint_type', $search_type)) selected @endif>{{ $complaintType->complaint_name }}</option>
                                @endforeach
                            </select>
                            <div id="parsley-errors-complaint-type"></div>
                            @error('complaint_type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-3 p-1 mb-3" >
                            <label class="col-form-label ">Status Aduan <span class="text-danger"> *</span></label>
                            <input type="hidden" id="selected-complaint-status" value="{{ $search_status }}">
                            <select class="form-select select_status" id="complaint_status" name="complaint_status" value="{{ old('complaint_status') }}"
                            required data-parsley-required-message="{{ setMessage('complaint_status.required') }}"
                            data-parsley-errors-container="#parsley-errors-status">
                                <option value="">-- Pilih Status Aduan--</option>
                            </select>
                            <div class="spinner-wrapper"></div>
                            <div id="parsley-errors-status"></div>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12 p-1 mb-3">
                            <button class="btn btn-primary" type="submit" onClick="setFormTarget()">{{ __('button.cari') }}</button>
                            <button name="reset" id="reset" class="btn btn-primary"  onClick ="clearSearchInput()">{{ __('button.reset') }}</button>
                            <button name="muat_turun_pdf" class="btn btn-success" type="submit" value="pdf" onClick="setFormTarget(this)">{{ __('button.muat_turun_pdf') }}</button>
                            {{-- <button name="muat_turun_excel" class="btn btn-success" type="submit" value="excel" >{{ __('button.muat_turun_excel') }}</button> --}}
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

                {{-- <div class="row">

                </div> --}}

                <div class="row">
                    <div class="col-sm-12">
                        <div data-simplebar style="max-height: 1000px;">
                            <table id="datatable-report" class="table table-bordered wrap w-100 dataTable table-striped" >
                                <thead class="bg-primary bg-gradient text-white">
                                    <tr role="row">
                                        <th class="text-center" width="4%" >Bil.</th>
                                        <th class="text-center" >No. Aduan</th>
                                        <th class="text-center" >Nama Pengadu</th>
                                        <th class="text-center" >Tarikh Temujanji</th>
                                        <th class="text-center" width="10%">Lokasi Kuarters</th>
                                        <th class="text-center" width="15%">Aduan</th>
                                        <th class="text-center" >Penolong Jurutera</th>
                                        <th class="text-center" width="16%">Butiran Pemantauan Aduan</th>
                                        <th class="text-center" width="15%" >Gambar Pemantauan Aduan</th>
                                        <th class="text-center" width="10%" >Status Pemantauan Aduan</th>
                                    </tr>
                                </thead>

                                <tbody>
                                @if($search_quarters_cat)
                                    @foreach($getMonitoringList as $bil => $list)

                                    @php
                                        $address = $list->quarters?->unit_no.', '.$list->quarters?->address_1.' '.$list->quarters?->address_2.' '.$list->quarters?->address_3;
                                        $monitor_awam_remarks_1 = (isset($list->monitoring_remarks)) ? $list->monitoring_remarks : '-';
                                        $monitor_awam_remarks_2 = (isset($list->monitoring_remarks_repeat)) ? $list->monitoring_remarks_repeat : '';
                                        $monitor_awam_remarks_3 = (isset($list->monitoring_remarks_final)) ? $list->monitoring_remarks_final : '';
                                    @endphp
                                            <tr style="text-transform: uppercase;">
                                                <th class="text-center" scope="row">{{ ++$bil }}</th>
                                                <td class="text-center" >{{ $list->ref_no ??'' }}</td>
                                                <td class="align-left" >{{ $list->user?->name ??'' }}</td>
                                                <td class="text-center" >{{ ($list->appointment_date) ? convertDateSys($list->appointment_date) : '-'}}</td>
                                                <td class="align-left" >{{ $address }}</td>
                                                <td class="align-left" >
                                                    {{-- Aduan Kerosakan --}}
                                                    @if($search_type == 1 && !$list->complaint_inventory->isEmpty())
                                                        <u>Aduan Inventori</u> <ul>
                                                        @foreach($list->complaint_inventory as $damageinv)
                                                            <li><p class="mb-2 align-left">{{$damageinv->inventory->name}} : {{upperText($damageinv->description)}}</p></li>
                                                        @endforeach
                                                        </ul>
                                                    @endif
                                                    @if($search_type == 1 && !$list->complaint_others->isEmpty())
                                                        <u>Aduan Lain-lain</u> <ul>
                                                        <p class="mb-2 align-left">{!! upperText(ArrayToDottedStringList($list->complaint_others?->pluck('description')->toArray())) !!}</p>
                                                        <u>
                                                    @endif

                                                     {{-- Aduan Awam --}}
                                                    @if($search_type == 2)
                                                        {{ $list->complaint_description }}
                                                    @endif
                                                </td>
                                                <td class="align-left" >{{ $list->officer_Api?->name ?? '-' }}</td>
                                                <td style="text-align:justify; text-align:left; ">

                                                    @if (!in_array($search_status, [1, 2]))  <!-- diterima & ditolak , tiada pemantauan -->
                                                        @if ($search_type == 1)
                                                            {{ upperText($list->monitor_remarks_for_damage) }}
                                                        @else
                                                            @if ($list->monitoring_remarks)
                                                                @foreach ([$monitor_awam_remarks_1, $monitor_awam_remarks_2, $monitor_awam_remarks_3] as $index => $remark)
                                                                    @if ($remark)
                                                                        <u>Pemantauan
                                                                            @if ($index == 0) Pertama
                                                                            @elseif ($index == 1) Kedua
                                                                            @elseif ($index == 2) Ketiga
                                                                            @endif
                                                                        </u>
                                                                        <li class="ms-2">{{ $remark }}</li><div class="pb-2"></div>
                                                                    @endif
                                                                @endforeach
                                                            @endif
                                                        @endif
                                                    @endif
                                                </td>
                                                <td class="text-center" >
                                                    @if(!in_array($search_status, [0,1,2]))   <!-- 0:baru 1:diterima & 2:ditolak = tiada pemantauan -->
                                                        @php
                                                            $hasAttachment = null;
                                                            $counter1_attachment = $counter2_attachment = $counter3_attachment = null;

                                                            if($search_type == 1 ) { $hasAttachment = $list->current_complaint_appointment->appointment_attachment; }
                                                            if($search_type == 2)  { $counter1_attachment = $list->complaint_monitoring->monitoring_attachment_counter_1;
                                                                                     $counter2_attachment = $list->complaint_monitoring->monitoring_attachment_counter_2;
                                                                                     $counter3_attachment = $list->complaint_monitoring->monitoring_attachment_counter_3;
                                                            }
                                                        @endphp

                                                        <!-- SERVER ICT & DEV RN-->
                                                        @if ($search_type == 1 && (!$hasAttachment->isEmpty()))
                                                            @foreach ($hasAttachment as $gambar)
                                                                <img src="{{ getCdn() . $gambar->path_document }}"  class="img-thumbnail" width="100%" @if(!$loop->first) style="padding-top:5px;" @endif>
                                                            @endforeach
                                                        @endif

                                                        @if ($search_type == 2)
                                                            @foreach([$counter1_attachment, $counter2_attachment, $counter3_attachment] as $index => $attachments)
                                                                @if (!$attachments->isEmpty())
                                                                    <u>Pemantauan
                                                                        @if ($index == 0) Pertama
                                                                        @elseif ($index == 1) Kedua
                                                                        @elseif ($index == 2) Ketiga
                                                                        @endif
                                                                    </u>
                                                                    @foreach ($attachments as $gambar)
                                                                        <img src="{{ getCdn().$gambar->path_document }}" class="img-thumbnail" width="100%">
                                                                    @endforeach
                                                                @endif
                                                            @endforeach
                                                        @endif
                                                    @endif   <!-- End search_status -->
                                                </td>
                                                <td class="text-center" >{{ $list->status?->complaint_status}}</td>
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
<script src="{{ URL::asset('assets/js/pages/Report/ComplaintMonitoringReport/complaintMonitoringReport.js')}}"></script>
<script src="{{ URL::asset('assets/libs/bootstrap-datepicker/bootstrap-datepicker.min.js')}}"></script>
@endsection
