@extends('layouts.master')
@section('css')
    <link href="{{ URL::asset('/assets/libs/bootstrap-datepicker/bootstrap-datepicker.min.css') }}" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="{{ URL::asset('/assets/libs/datepicker/datepicker.min.css') }}">
@endsection
@section('content')

<div >
    <form class="custom-validation" id="form" method="post" action="{{ route('meetingRegistration.store') }}" >
    {{ csrf_field() }}

    <div class="col-12">
        <div class="card">
            <div class="card-body">

                {{-- PAGE TITLE --}}
                <div class="border-bottom border-primary mb-4"><h4 class="card-title">{{ getPageTitle(1) }}</h4></div>

                <div class="row">
                    <div class="mb-3" id="parsley-errors-all"></div>
                </div>

                <!-- tabs- daftar mesyuarat -->
                <ul class="nav nav-tabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" data-bs-toggle="tab" role="tab" href="#meeting-info">Maklumat Mesyuarat</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" role="tab" href="#application-info-all">Senarai Permohonan</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" role="tab" href="#application-info-kategori">Senarai Permohonan Mengikut Kategori Kuarters (Lokasi)</a>
                    </li>
                </ul>
                @php
                    $quarters_category_id_arr = $listQuartersCategoryAll->groupby('quarters_category_id');
                @endphp

                <input type="hidden" id="district" name="district" value="{{ $district_id }}" >
                <input type="hidden" id="quarters_category_id_arr" value="{{ $quarters_category_id_arr }}">
                <input type="hidden" id="application-list" data-route="{{ route('meetingRegistration.ajaxGetApplicationList') }}" data-page="new" data-application-msg="{{ setMessage('application_ids.required') }}">

                <div class="tab-content p-4">
                    <div class="tab-pane active" id="meeting-info" role="tabpanel">

                        <div class="mb-3 row">
                            <label for="bil_no" class="col-md-2 col-form-label">Bil. Mesyuarat</label>
                            <div class="col-md-10">
                                <input class="form-control @error('bil_no') is-invalid @enderror" type="text" id="bil_no" name="bil_no" value="{{ old('bil_no','') }}"
                                        required data-parsley-required-message="{{ setMessage('bil_no.required') }}"
                                        data-parsley-errors-container="#parsley-errors-all">
                                @error('bil_no')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label for="purpose" class="col-md-2 col-form-label">Tajuk Mesyuarat</label>
                            <div class="col-md-10">
                                <input class="form-control @error('purpose') is-invalid @enderror" type="text" id="purpose" name="purpose" value="{{ old('purpose','') }}"
                                        required data-parsley-required-message="{{ setMessage('purpose.required') }}"
                                        data-parsley-errors-container="#parsley-errors-all">
                                @error('purpose')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label for="venue" class="col-md-2 col-form-label">Tempat</label>
                            <div class="col-md-10">
                                <input class="form-control @error('venue') is-invalid @enderror" type="text" id="venue" name="venue" value="{{ old('venue','') }}"
                                        required data-parsley-required-message="{{ setMessage('venue.required') }}"
                                        data-parsley-errors-container="#parsley-errors-all">
                                @error('venue')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label for="date" class="col-md-2 col-form-label">Tarikh</label>
                            <div class="col-md-3">
                                <div class="input-group" id="datepicker2">
                                    <input class="form-control @error('date') is-invalid @enderror"  type="text"  placeholder="dd/mm/yyyy" name="date" id="date"
                                    data-date-format="dd/mm/yyyy" data-date-container='#datepicker2' data-provide="datepicker" autocomplete="off"
                                    data-date-autoclose="true" required data-parsley-required-message="{{ setMessage('date.required') }}" data-parsley-errors-container="#parsley-errors-all">
                                    <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
                                    @error('date')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div id="errorContainer"></div>
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label for="time" class="col-md-2 col-form-label">Masa</label>
                            <div class="col-md-3">
                                <input class="form-control form-control @error('time') is-invalid @enderror" type="time" id="time" name="time" value="{{ old('time','') }}"
                                        required data-parsley-required-message="{{ setMessage('time.required') }}"
                                        data-parsley-errors-container="#parsley-errors-all">
                                @error('time')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <hr>
                        <!-- Nav tabs -->
                        <h4 class="card-title">Kehadiran Ahli Jawatankuasa</h4>

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
                                                <th width="10%">Panel</th>
                                            </tr>
                                        </thead>
                                        <tbody class="table-striped">
                                            @foreach($internalPanelAll as $data)
                                            <tr>
                                                <td>{{ $loop->iteration }}</th>
                                                <td>{{ $data->name ?? '' }}</td>
                                                <td>{{ $data->position?->position_name }}</td>
                                                <td >
                                                    <div class="form-check">
                                                        <input type="checkbox" class="form-check-input meeting_chairmain_ids" id="meeting_chairmain_ids_{{ $data->users_id }}" name="meeting_chairmain_ids[]" value="{{ $data->users_id }}"
                                                            required data-parsley-required-message="{{ setMessage('chairmain.required') }}"
                                                            data-parsley-mincheck="1" data-parsley-mincheck-message="{{ setMessage('chairmain.required') }}"
                                                            data-parsley-maxcheck="1" data-parsley-maxcheck-message="{{ setMessage('chairmain.required') }}"
                                                            data-parsley-errors-container="#parsley-errors-all">
                                                        <label class="form-check-label" for="meeting_chairmain_ids_{{ $data->users_id }}">
                                                    </div>
                                                </td>
                                                <td >
                                                    <div class="form-check">
                                                        <input type="checkbox" class="form-check-input meeting_internal_panel_ids" id="meeting_internal_panel_ids_{{ $data->users_id }}" name="meeting_internal_panel_ids[]" value="{{ $data->users_id }}"
                                                            required data-parsley-required-message="{{ setMessage('internal_panel.required') }}"
                                                            data-parsley-mincheck="1" data-parsley-mincheck-message="{{ setMessage('internal_panel.required') }}"
                                                            data-parsley-errors-container="#parsley-errors-all">
                                                        <label class="form-check-label" for="meeting_internal_panel_ids_{{ $data->users_id }}">
                                                    </div>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>

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
                                                <th width="10%">Pilih</th>
                                            </tr>
                                        </thead>
                                        <tbody class="table-striped">
                                            @foreach($invitationPanelAll as $data)
                                            <tr>
                                                <td>{{ $loop->iteration }}</th>
                                                <td>{{ $data->name ?? '' }}</td>
                                                <td>{{ $data->position ?? '' }}</td>
                                                <td>{{ $data->department ?? '' }}</td>
                                                <td>
                                                    <div class="form-check">
                                                        <input type="checkbox" class="form-check-input" id="meeting_invitation_panel_ids_{{ $data->id }}" name="meeting_invitation_panel_ids[]" value="{{ $data->id }}"
                                                                required data-parsley-required-message="{{ setMessage('invitation_panel.required') }}"
                                                                data-parsley-mincheck="1" data-parsley-mincheck-message="{{ setMessage('invitation_panel.required') }}"
                                                                data-parsley-errors-container="#parsley-errors-all">
                                                        <label class="form-check-label" for="meeting_invitation_panel_ids_{{ $data->id }}">
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
                    </div>
                    {{-- end meeting info tab --}}

                    {{-- application info tab --}}
                    <div class="tab-pane" id="application-info-all" role="tabpanel">

                        <div class="table-responsive">
                            <table class="table align-middle dt-responsive w-100 mb-4" id="table-quarters-class" >
                                <thead class="table-light">
                                    <tr>
                                        <th class="text-center" width="5%">Bil</th>
                                        <th width="25%">Nama Pemohon</th>
                                        <th width="25%" >Kategori Kuarters (Lokasi)</th>
                                        <th >Jenis Lantikan</th>
                                        <th width="10%" >Tarikh Permohonan</th>
                                        <th width="10%" class="text-center">Markah</th>
                                        <th >
                                            Dibawa ke mesyuarat<br/>
                                            <div class="form-check"><input type="checkbox" class="form-check-input" id="select_all_application">
                                                <label class="form-check-label" > Pilih Semua</label>
                                            </div>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody id="application_data_all" ></tbody>
                                @foreach($listApplicationAll as $i => $application)
                                    @php $bil=0 @endphp
                                    {{-- @if($application->meeting_application_id == 0) --}}
                                        @php 
                                            $quarters_category_names = $application->quarters_category->pluck('name')->toArray();
                                            $quarters_category_ids = $application->quarters_category->pluck('id')->toArray();
                                        @endphp
                                        <tr>
                                            <td class="text-center">{{ $loop->iteration }}</td>
                                            <td >{{ $application->applicant_name }}</td>
                                            <td >
                                                {!! ArrayToStringList($quarters_category_names) !!}
                                                <input type="hidden" name="quarters_category_ids[{{ $application->id }}]" value="{{ implode(",",$quarters_category_ids) }}">
                                            </td>
                                            <td >{{ $application->services_type }}</td>
                                            <td class="text-center">{{ convertDateSys($application->application_date_time) }}</td>
                                            <td class="text-center">{{ $application->scores->sum('mark') }}</td>
                                            <td class="text-center">
                                                <div class="form-check">
                                                    <input type="checkbox" class="form-check-input application_all_ids" id="application_all_ids_{{ $application->id }}" name="application_ids[]" value="{{ $application->id }}"
                                                        required data-parsley-required-message="{{ setMessage('application_ids.required') }}"
                                                        data-parsley-mincheck="1" data-parsley-mincheck-message="{{ setMessage('application_ids.required') }}"
                                                        data-parsley-errors-container="#parsley-errors-all" >
                                                </div>
                                            </td>
                                        </tr>
                                    {{-- @endif --}}
                                @endforeach
                            </table>
                        </div>

                    </div>
                    {{-- end application info all tab --}}

                    {{-- application info by kategori kuarters (lokasi) tab --}}
                    <div class="tab-pane" id="application-info-kategori" role="tabpanel">

                        <!-- tabs- application list by kategori kuarters (lokasi) -->
                        <ul class="nav nav-tabs" role="tablist">
                            @foreach($quarters_category_id_arr as $quarters_category_id => $dataQuartersCategory)
                                @foreach($dataQuartersCategory as $dataQuartersCategoryItem)
                                    @php $quarters_category = $dataQuartersCategoryItem->category_name; @endphp
                                @endforeach
                                <li class="nav-item">
                                    <a class="nav-link @if($loop->iteration==1) active @endif" data-bs-toggle="tab" role="tab" href="#quarters_category_id{{ $quarters_category_id }}">
                                        {{ capitalizeText($quarters_category) }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>

                        <!-- Tab panes -->
                        <!-- TAB -CONTENT -->
                        <div class="tab-content p-3 text-muted" >
                            @foreach($quarters_category_id_arr as $quarters_category_id => $dataQuartersCategory)
                                <div class="tab-pane @if($loop->iteration==1) active @endif" id="quarters_category_id{{ $quarters_category_id }}" role="tabpanel" data-simplebar style="max-height: 600px;">
                                    <div class="table-responsive">
                                        <table class="table align-middle dt-responsive w-100 mb-4" id="table-quarters-class" >
                                            <thead class="table-light">
                                                <tr>
                                                    <th class="text-center" width="5%">Bil</th>
                                                    <th width="30%">Nama Pemohon</th>
                                                    <th >Jenis Lantikan</th>
                                                    <th width="10%" >Tarikh Permohonan</th>
                                                    <th width="15%" class="text-center">Markah</th>
                                                    <th >Dibawa ke mesyuarat</th>
                                                </tr>
                                            </thead>
                                            <tbody id="application_data_category{{ $quarters_category_id }}" ></tbody>

                                        </table>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                    </div>
                    {{-- end application info by kategori kuarters (lokasi) tab --}}
                </div>
                {{-- end tab --}}

                <div class="mb-3 row">
                    <div class="col-sm-12 pr-5">
                        <button type="submit" class="btn btn-primary float-end swal-tambah">{{ __('button.simpan') }}</button>
                        <a href="{{ route('meetingRegistration.index') }}" class="btn btn-secondary float-end me-2">{{ __('button.kembali') }}</a>
                    </div>
                </div>

            </div>{{-- end card body --}}
        </div>{{-- end card --}}
    </div>{{-- end col1-12 --}}

    </form>

</div>{{-- end row  --}}

@endsection

@section('script')
<script src="{{ URL::asset('assets/js/pages/MeetingRegistration/MeetingRegistration.js')}}"></script>
<script src="{{ URL::asset('assets/libs/bootstrap-datepicker/bootstrap-datepicker.min.js')}}"></script>
@endsection

