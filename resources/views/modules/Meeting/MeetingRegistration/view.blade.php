@extends('layouts.master')

@section('content')

<form method="post">
    {{ csrf_field() }}

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">

                    {{-- PAGE TITLE --}}
                    <div class="border-bottom border-primary mb-4"><h4 class="card-title">{{ getPageTitle(1) }}</h4></div>

                    @php
                        $quarters_category_id_arr = $listQuartersCategoryAll->groupby('quarters_category_id');
                    @endphp
                    <input type="hidden" id="quarters_category_id_arr" value="{{ $quarters_category_id_arr }}">
                    <input type="hidden" id="meeting_id" name="meeting_id" value="{{ $meeting->id }}">
                    <input type="hidden" id="application-list" data-route="{{ route('meetingRegistration.ajaxGetApplicationList') }}" data-page="view">

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

                    <div class="tab-content p-4">
                        <div class="tab-pane active" id="meeting-info" role="tabpanel">

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
                            <h4 class="card-title">Kehadiran Ahli Jawatankuasa</h4>

                            <ul class="nav nav-tabs" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" data-bs-toggle="tab" role="tab" href="#internal-panel">Panel Dalaman</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-bs-toggle="tab" role="tab" href="#invitation-panel">Panel Luar</a>
                                </li>
                            </ul>

                            <!-- meeting info tab -->
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
                                                @foreach($internalPanelAll as $bil => $data)
                                                <tr>
                                                    <td>{{ ++$bil }}</th>
                                                    <td>{{ $data->name ?? '' }}</td>
                                                    <td>{{ $data->users->position->position_name ?? '' }}</td>
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
                                                                @if($data->users_id == $data->meeting_panel_id) checked @endif disabled>
                                                        </div>
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <!-- panel - invitation tab  -->
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
                                                @foreach($invitationPanelAll as $bil => $data)
                                                <tr>
                                                    <td>{{ ++$bil }}</th>
                                                    <td>{{ $data->name ?? '' }}</td>
                                                    <td>{{ $data->position ?? '' }}</td>
                                                    <td>{{ $data->department ?? '' }}</td>
                                                    <td>
                                                        <div class="form-check">
                                                            <input type="checkbox" class="form-check-input" id="meeting_invitation_panel_ids_{{ $data->invitation_panel_id }}" name="meeting_invitation_panel_ids[]" value="{{ $data->invitation_panel_id }}"
                                                                @if($data->invitation_panel_id == $data->meeting_panel_id) checked @endif disabled>
                                                            <label class="form-check-label" for="meeting_invitation_panel_ids_{{ $data->invitation_panel_id }}">
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

                        {{-- application info all tab --}}
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
                                                Dibawa ke mesyuarat
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody id="application_data_all" ></tbody>
                                    @foreach($listApplicationAll as $i => $application)
                                        <tr>
                                            <td class="text-center"> {{ $loop->iteration }} </td>
                                            <td >{{ $application->applicant_name }}</td>
                                            <td >{!! ArrayToStringList($application->quarters_category->pluck('name')->toArray()) !!}</td>
                                            <td >{{ $application->services_type }}</td>
                                            <td class="text-center">{{ convertDateSys($application->application_date_time) }}</td>
                                            <td class="text-center">{{ $application->scores->sum('mark') }}</td>
                                            <td class="text-center">
                                                <div class="form-check">
                                                    <input type="checkbox" class="form-check-input application_all_ids" id="application_all_ids_{{ $application->id }}" name="application_ids[]" value="{{ $application->id }}"
                                                        @if($application->id == $application->meeting_application_id) checked @endif disabled>
                                                </div>
                                            </td>
                                        </tr>
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
                            <div class="tab-content p-3 text-muted">
                                @forelse($quarters_category_id_arr as $quarters_category_id => $dataQuartersCategory)
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
                                @empty
                                    <p>Tiada Senarai Permohonan</p>
                                @endforelse
                            </div>

                        </div>
                        {{-- end application info by kategori kuarters (lokasi) tab --}}
                    </div>
                    {{-- end tab --}}

                    <div class="mb-3 row">
                        <div class="col-sm-11 offset-sm-1">
                            {{-- <a class="btn btn-danger float-end me-2 swal-delete">{{ __('button.hapus') }}</a> --}}
                            <a href="{{ route('meetingRegistration.index') }}" class="btn btn-secondary float-end me-2">{{ __('button.kembali') }}</a>
                        </div>
                    </div>

                </div>
            </div>
            <!-- end col -->
        </div>
    </div>
    <!-- end row -->
</form>

<form method="POST" action="{{ route('meetingRegistration.delete') }}" id="delete-form">
    {{ csrf_field() }}
    {{ method_field('DELETE') }}
    <input class="form-control" type="hidden" id="id-delete" name="id" value="{{ $meeting->id }}">
</form>

@endsection
@section('script')
<script src="{{ URL::asset('assets/js/pages/MeetingRegistration/MeetingRegistration.js')}}"></script>
@endsection
