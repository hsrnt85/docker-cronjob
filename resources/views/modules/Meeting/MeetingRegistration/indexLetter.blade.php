@extends('layouts.master')

@section('content')

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">

                {{-- PAGE TITLE --}}
                <div class="border-bottom border-primary mb-4"><h4 class="card-title">{{ getPageTitle(1) }}</h4></div>

                <form  class="custom-validation" id="form" method="post" action="{{ route('meetingRegistration.processLetter', ['id' => $meeting->id, 'flag' => 3]) }}" >
                    {{ csrf_field() }}

                    <div class="mb-3 row">
                        <label for="letter_ref_no" class="col-md-2 col-form-label">No. Rujukan Surat</label>
                        <div class="col-md-9">
                            <input class="form-control @error('letter_ref_no') is-invalid @enderror" type="text" id="letter_ref_no" name="letter_ref_no" value="{{ old('letter_ref_no', $meeting->letter_ref_no) }}"
                                    required data-parsley-required-message="{{ setMessage('letter_ref_no.required') }}">
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="letter_date" class="col-md-2 col-form-label">Tarikh Surat</label>
                        <div class="col-md-9">
                            <input class="form-control @error('letter_date') is-invalid @enderror" type="date" id="letter_date" name="letter_date" value="{{ old('letter_ref_no', $meeting->letter_date) }}"
                                    required data-parsley-required-message="{{ setMessage('letter_date.required') }}">
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="officer" class="col-md-2 col-form-label">Pegawai Pengesahan</label>
                        <div class="col-md-9">
                            <select class="form-select select2 @error('officer') is-invalid @enderror" id="officer" name="officer" required  data-parsley-required-message="{{ setMessage('officer.required') }}">
                                <option value="">-- Pilih Pegawai Pengesahan --</option>
                                @foreach($officerAll as $officer)
                                    <option value="{{ $officer->id }}" @if($officer->id == $meeting->officer_id) selected @endif>{{ $officer->name }}</option>
                                @endforeach
                            </select>

                        </div>
                    </div>

                    <h4 class="card-title">Ahli Jawatankuasa</h4>

                    <ul class="nav nav-tabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" data-bs-toggle="tab" role="tab" href="#internal-panel">Panel Dalaman</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" role="tab" href="#invitation-panel">Panel Luar</a>
                        </li>
                    </ul>
                    <div class="tab-content pt-2">
                        {{-- panel - internal tab --}}
                        <div class="tab-pane active" id="internal-panel" role="tabpanel">
                            <div class="table-responsive">
                                <table class="table align-middle table-nowrap" >
                                    <thead class="table-light">
                                        <tr>
                                            <th width="10%">Bil</th>
                                            <th width="25%">Nama Panel</th>
                                            <th width="20%">Jawatan</th>
                                            <th width="20%">Emel</th>
                                            <th width="10%">Pengerusi</th>
                                            <th width="10%">Panel</th>
                                        </tr>
                                    </thead>
                                    <tbody class="table-striped">
                                        @foreach($internalPanelAll as $data)
                                        <tr>
                                            <td>{{ $loop->iteration }}</th>
                                            <td>{{ $data->name ?? '' }}</td>
                                            <td>{{ $data->users->position->position_name }}</td>
                                            <td>{{ $data->email }}</td>
                                            <td >
                                                <div class="form-check">
                                                    <input type="checkbox" class="form-check-input" name="meeting_chairmain_ids[]" value="{{ $data }}" @if($data->is_chairmain == 1) checked @endif disabled>
                                                </div>
                                            </td>
                                            <td >
                                                <div class="form-check">
                                                    <input type="checkbox" class="form-check-input" name="meeting_panel_ids[]" checked disabled>
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
                                <table class="table align-middle table-nowrap">
                                    <thead class="table-light">
                                        <tr>
                                            <th width="8%">Bil</th>
                                            <th width="25%">Nama Panel</th>
                                            <th width="20%">Gelaran Jawatan</th>
                                            <th width="10%">Jabatan</th>
                                            <th width="20%">Emel</th>
                                            <th width="10%">Hadir</th>
                                        </tr>
                                    </thead>
                                    <tbody class="table-striped">
                                        @foreach($invitationPanelAll as $data)
                                        <tr>
                                            <td>{{ $loop->iteration }}</th>
                                            <td>{{ $data->name ?? '' }}</td>
                                            <td>{{ $data->position ?? '' }}</td>
                                            <td>{{ $data->department ?? '' }}</td>
                                            <td>{{ $data->email ?? '' }}</td>
                                            <td>
                                                <div class="form-check">
                                                    <input type="checkbox" class="form-check-input" id="meeting_invitation_panel_ids_{{ $data->invitation_panel_id }}" name="meeting_invitation_panel_ids[]" checked disabled>
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

                    <div class="mb-3 row">
                        <div class="col-sm-12">
                            <button type="submit" class="btn btn-primary float-end swal-update-email-surat">{{ __('button.simpan_emel_surat') }}</button>
                            @if($meeting->is_done == 0)
                                <a href="{{ route('meetingRegistration.processLetter', ['id' => $meeting->id, 'flag' => 1]) }}" class="btn btn-primary float-end swal-email-surat me-2">
                                    {{ __('button.emel_surat') }}
                                </a>
                                <a href="{{ route('meetingRegistration.processLetter', ['id' => $meeting->id, 'flag' => 2]) }}" class="btn btn-primary float-end me-2" target="_blank">
                                    {{ __('button.cetak_surat') }}
                                </a>
                            @endif
                            <a href="{{ route('meetingRegistration.index') }}" class="btn btn-secondary float-end me-2">{{ __('button.kembali') }}</a>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div> <!-- end col -->
</div>
<!-- end row -->

@endsection
