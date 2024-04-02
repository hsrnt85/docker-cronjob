@extends('layouts.master')

@section('content')

<div class="col-12">

    <div class="card">
        <div class="card-body">

            {{-- PAGE TITLE --}}
            <div class="border-bottom border-primary mb-4"><h4 class="card-title">{{ getPageTitle(1) }}</h4></div>

            @php
                $quarters_category_id_arr = $listQuartersCategoryAll->groupby('quarters_category_id');
            @endphp
            <input type="hidden" id="meeting_id" value="{{ $meeting->id }}">
            <input type="hidden" id="meeting_is_done" value="{{ $meeting->is_done }}">
            <input type="hidden" id="is_check_attendance" value="{{ $meeting->is_check_attendance }}">
            <input type="hidden" id="quarters_category_id_arr" value="{{ $quarters_category_id_arr }}">
            <input type="hidden" id="application_status_arr" value="{{ $application_status_id_arr }}">


            <div class="row">
                @if($meeting->is_check_attendance==0)
                <div class="alert alert-danger">
                    <span class="text-danger" ><i class="mdi mdi-alert-circle mdi-40px"></i>  Sila kemaskini kehadiran Ahli Jawatankuasa (Panel Dalaman dan Luar) terlebih dahulu sebelum penilaian permohonan dibuat.</span>  
                </div>
                @endif

                <div class="mb-3" id="parsley-errors-meeting-panel"></div>
            </div>

            <!-- tabs- penilaian mesyuarat -->
            <ul class="nav nav-tabs" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" data-bs-toggle="tab" role="tab" href="#meeting-info">Maklumat Mesyuarat</a>
                </li>
                <li class="nav-item">
                    <a id="tab-application-info-kategori" class="nav-link" data-bs-toggle="tab" role="tab" href="#application-info-kategori">Senarai Permohonan Mengikut Kategori Kuarters (Lokasi)</a>
                </li>
            </ul>

            <div class="tab-content p-4">

                <div class="tab-pane active" id="meeting-info" role="tabpanel">
                    <form class="custom-validation" id="form" method="post" action="{{ route('evaluationMeeting.updateAttendance', ['meeting_id'=>$meeting->id]) }}" >
                        {{ csrf_field() }}

                        <div class="mb-3 row">
                            <label class="col-md-2 col-form-label">Bil Mesyuarat</label>
                            <div class="col-md-10">
                                <p class="col-form-label">{{ $meeting -> bil_no }}</p>
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label class="col-md-2 col-form-label">Tajuk Mesyuarat</label>
                            <div class="col-md-10">
                                <p class="col-form-label">{{ $meeting->purpose }}</p>
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label class="col-md-2 col-form-label">Tarikh</label>
                            <div class="col-md-10">
                                <p class="col-form-label">{{ $meeting->date->format("d/m/Y") }}</p>
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label class="col-md-2 col-form-label">Masa</label>
                            <div class="col-md-10">
                                <p class="col-form-label">{{ $meeting->time->format("h:i A") }}</p>
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label class="col-md-2 col-form-label">Tempat</label>
                            <div class="col-md-10">
                                <p class="col-form-label">{{ $meeting->venue }}</p>
                            </div>
                        </div>

                        <hr>
                        <!-- Nav tabs -->
                        <h4 class="card-title mb-3">Kehadiran Ahli Jawatankuasa</h4>

                        <ul class="nav nav-tabs" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" data-bs-toggle="tab" role="tab" href="#internal-panel">Panel Dalaman</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-bs-toggle="tab" role="tab" href="#invitation-panel">Panel Luar</a>
                            </li>
                        </ul>

                        {{-- meeting info tab --}}
                        <div class="tab-content pt-2">

                            {{-- panel - internal tab --}}
                            <div class="tab-pane active" id="internal-panel" role="tabpanel">
                                <div class="table-responsive">
                                    <table class="table align-middle dt-responsive w-100" >
                                        <thead class="table-light">
                                            <tr>
                                                <th width="10%">Bil</th>
                                                <th width="30%">Nama Panel</th>
                                                <th width="25%">Jawatan</th>
                                                <th width="10%">Pengerusi</th>
                                                <th width="10%">Hadir</th>
                                            </tr>
                                        </thead>
                                        <tbody class="table-striped">
                                            @foreach($internalPanelAll as $bil => $data)
                                            <tr>
                                                <td>{{ ++$bil }}</th>
                                                <td>{{ $data->name ?? '' }}</td>
                                                <td>{{ $data->position?->position_name }}</td>
                                                <td >
                                                    @if($data->is_chairmain == 1)
                                                        <div class="form-check">
                                                            <input type="checkbox" class="form-check-input meeting_chairmain_ids" id="meeting_chairmain_ids_{{ $data->users_id }}" name="meeting_chairmain_ids[]" value="{{ $data->users_id }}" checked disabled>
                                                        </div>
                                                    @endif
                                                </td>
                                                <td >
                                                    <div class="form-check">
                                                        <input type="checkbox" class="form-check-input meeting_internal_panel_ids" id="meeting_internal_panel_ids_{{ $data->users_id }}" name="meeting_internal_panel_ids[]" value="{{ $data->users_id }}"
                                                            @if($data->is_attend ==1) checked @endif
                                                            required data-parsley-required-message="{{ setMessage('internal_panel.required') }}"
                                                            data-parsley-mincheck="1" data-parsley-mincheck-message="{{ setMessage('internal_panel.required') }}"
                                                            data-parsley-errors-container="#parsley-errors-meeting-panel">
                                                    </div>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            {{-- end panel - internal tab --}}

                            {{-- panel - invitation tab --}}
                            <div class="tab-pane" id="invitation-panel" role="tabpanel">
                                <div class="table-responsive">
                                    <table class="table align-middle dt-responsive w-100">
                                        <thead class="table-light">
                                            <tr>
                                                <th width="8%">Bil</th>
                                                <th width="30%">Nama Panel</th>
                                                <th width="20%">Gelaran Jawatan</th>
                                                <th width="20%">Jabatan</th>
                                                <th width="10%">Hadir</th>
                                            </tr>
                                        </thead>
                                        <tbody class="table-striped">
                                            @foreach($invitationPanelAll as $bil => $data)
                                            <tr>
                                                <td>{{ ++$bil }}</th>
                                                <td>{{ $data->name ?? '' }}</td>
                                                <td>{{ $data->position ?? '' }}</td>
                                                <td>{{ $data->department ?? '' }}</td>
                                                <td>
                                                    <div class="form-check">
                                                        <input type="checkbox" class="form-check-input" id="meeting_invitation_panel_ids_{{ $data->invitation_panel_id }}" name="meeting_invitation_panel_ids[]" value="{{ $data->invitation_panel_id }}"
                                                        @if($data->is_attend ==1) checked @endif
                                                        required data-parsley-required-message="{{ setMessage('invitation_panel.required') }}"
                                                        data-parsley-mincheck="1" data-parsley-mincheck-message="{{ setMessage('invitation_panel.required') }}"
                                                        data-parsley-errors-container="#parsley-errors-meeting-panel">
                                                    </div>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            {{-- end panel - invitation tab --}}

                        </div>
                        {{-- end meeting panel tab --}}

                        <!-- Button -->
                        <div class="row">
                            <div class="col-sm-11 offset-sm-1 px-4">
                                @if($meeting->is_done==0)
                                    <button type="submit" class="btn btn-primary float-end swal-kemaskini">{{ __('button.kemaskini_kehadiran') }}</button>
                                @endif
                                <a href="{{ route('evaluationMeeting.index') }}" class="btn btn-secondary float-end me-2">{{ __('button.kembali') }}</a>
                            </div>
                        </div>

                    </form>
                </div>
                {{-- end meeting info tab --}}

                {{-- application info by kategori kuarters (lokasi) tab --}}
                <div class="tab-pane" id="application-info-kategori" role="tabpanel" >
                    <form class="custom-validation form-application" id="form" method="post" action="{{ route('evaluationMeeting.updateApplication', ['meeting_id'=>$meeting->id]) }}" >
                        {{ csrf_field() }}

                        
                        <input type="hidden" name="bil_no" value="{{ $meeting->bil_no }}">
                        <!-- Nav tabs -->
                        <ul class="nav nav-tabs" role="tablist">
                            @foreach($quarters_category_id_arr as $quarters_category_id => $dataQuartersCategory)
                                @foreach($dataQuartersCategory as $dataQuartersCategoryItem)
                                    @php $quarters_category_name = $dataQuartersCategoryItem->category_name; @endphp
                                @endforeach
                                <input type="hidden" name="quarters_category_name[]" id="quarters_category_name{{ $quarters_category_id }}" value="{{ $quarters_category_name }}" >

                                <li class="nav-item">
                                    <a class="nav-link @if($loop->iteration==1) active @endif" data-bs-toggle="tab" role="tab" href="#quarters_category_id{{ str_replace(' ', '', $quarters_category_id) }}">
                                        {{ capitalizeText($quarters_category_name) }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                        <!-- Tab panes -->
                        <!-- TAB -CONTENT - MODULE/ SUBMODULE-->
                        <div class="tab-content pt-2 text-muted mb-3">
                            @foreach($quarters_category_id_arr as $quarters_category_id => $dataQuartersCategory)
                                <input type="hidden" name="quarters_category[]" id="quarters_category{{ $quarters_category_id }}" value="{{ $quarters_category_id }}" >

                                <div class="tab-pane @if($loop->iteration==1) active @endif" id="quarters_category_id{{ str_replace(' ', '', $quarters_category_id) }}" role="tabpanel">

                                    <div class="table-responsive" data-simplebar style="max-height: 700px;">

                                        <table class="table align-middle dt-responsive w-100 mb-0">
                                            <thead class="table-light">
                                                <tr>
                                                    <th width="5%">Bil</th>
                                                    <th width="35%" >Nama Pemohon</th>
                                                    <th >Jenis Lantikan</th>
                                                    <th width="15%" class="text-center">Markah</th>
                                                    <th class="text-center">Keputusan Mesyuarat</th>
                                                    <th class="text-center">Papar Permohonan</th>
                                                </tr>
                                            </thead>
                                            <tbody id="application_data{{ $quarters_category_id }}"></tbody>
                                            <!-- load from .js-->
                                        </table>

                                    </div>

                                </div>

                            @endforeach

                        </div>

                        <!-- Button -->
                        <div class="row">
                            <div class="col-sm-11 offset-sm-1 px-4">
                                <input type="hidden" name="btn_submit" id="btn_submit">
                                @if($meeting->is_done==0)
                                    <button type="submit" class="btn btn-success float-end swal-sah-penilaian">{{ __('button.pengesahan_akhir_penilaian') }}</button>
                                    <button type="submit" class="btn btn-primary float-end swal-kemaskini me-2">{{ __('button.kemaskini_penilaian') }}</button>
                                @endif
                                <a href="{{ route('evaluationMeeting.index') }}" class="btn btn-secondary float-end me-2">{{ __('button.kembali') }}</a>
                            </div>
                        </div>

                    </form>

                </div>
                {{-- end application info by kategori kuarters (lokasi) tab --}}
            </div>
            {{-- end tab --}}


            <!-- Popup Modal Show Application Info -->
            <div id="modal-view-application"  class="modal fade" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-fullscreen">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" >Maklumat Permohonan</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body" id="modal-body">

                        </div>
                        <div class="modal-footer px-4">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End Popup Modal Show Application Info -->

        </div>
        <!-- end card-body -->
    </div>
    <!-- end card -->
</div>
<!-- end row col -->

@endsection

@section('script')
<script src="{{ URL::asset('assets/js/pages/EvaluationMeeting/evaluationMeeting.js')}}"> </script>
<script src="{{ URL::asset('assets/js/pages/ApplicationReview/ApplicationReviewInfo.js')}}"></script>
@endsection

