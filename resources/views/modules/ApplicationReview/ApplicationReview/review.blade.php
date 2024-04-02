@extends('layouts.master')

@section('content')

<div >
    <div class="col-12">
        <div class="card">
            <div class="card-body">

                {{-- PAGE TITLE --}}
                <div class="border-bottom border-primary mb-4"><h4 class="card-title">{{ getPageTitle(1) }}</h4></div>

                <div class="row">
                    <label for="name" class="col-md-2 col-form-label">Pemohon</label>
                    <p class="col-md-9 col-form-label">{{ $application->user->name }}</p>
                </div>

                <div class="row">
                    <label for="name" class="col-md-2 col-form-label">Kategori Kuarters (Lokasi)</label>
                    <p class="col-md-9 col-form-label">{!! ArrayToStringList($application->quarters_category->pluck('name')->toArray()) !!}</p>
                </div>

                <div class="row mb-4">
                    <label for="name" class="col-md-2 col-form-label">Tarikh Permohonan</label>
                    <p class="col-md-9 col-form-label">{{ convertDateSys($application->application_date_time)  }}</p>
                </div>

                <hr>

                <!-- TABS -->
                <ul class="nav nav-tabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" data-bs-toggle="tab" href="#application_scoring" role="tab">
                            <span class="d-none d-sm-block">Markah Permohonan</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#application_info" role="tab">
                            <span class="d-none d-sm-block">Maklumat Permohonan</span>
                        </a>
                    </li>
                </ul>

                <!-- TABS CONTENT-->
                <div class="tab-content p-0">

                    <div class="tab-pane active" id="application_scoring" role="tabpanel">

                        {{-- SECTION - APPLICATION SCORING INFO --}}
                        @include('modules.ApplicationReview.ApplicationReviewInfo.view-application-scoring')

                        <form class="custom-validation" id="form-review" method="post" action="{{ route('applicationReview.store') }}" >
                            {{ csrf_field() }}

                            <input type="hidden" name="id" value="{{ $application->id }}">
                            <input type="hidden" name="qcid" value="{{ $application->quarters_category_id }}">

                            {{-- SECTION - APPLICATION REVIEW PROCESS --}}
                            <div class="row mt-3 mb-3">
                                <label for="application_status" class="col-md-2 col-form-label">Status Semakan</label>
                                <div class="col-md-9">
                                    @foreach($application_statusAll as $i => $application_status)
                                        <div class="form-check me-2">
                                            <input class="form-check-input me-2 application_status" type="radio" id="application_status_{{ $i}}" name="application_status[]"
                                                    value="{{ $application_status->id }}" {{ old('application_status.' . $i) == $application_status->id ? "checked" : "" }}
                                                    required data-parsley-required-message="{{ setMessage('application_status.required') }}"
                                                    data-parsley-errors-container="#parsley-errors-application-status">
                                            <label class="form-check-label" for="application_status_{{ $i}}"> {{ capitalizeText($application_status->status) }} </label>
                                        </div>
                                    @endforeach
                                    @error('application_status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div id="parsley-errors-application-status"></div>
                                </div>
                            </div>
                            <div class="mt-3 mb-3 row">
                                <label for="name" class="col-md-2 col-form-label">Ulasan</label>
                                <div class="col-md-6">
                                    <textarea class="form-control @error('remarks')is-invalid @enderror" rows="3" id="remarks" name="remarks" data-parsley-required-message="{{ setMessage('remarks.required') }}"></textarea>
                                    @error('remarks') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>

                            <div class="mb-3 row">
                                <label for="position" class="col-md-2 col-form-label ">Pegawai Pelulus</label>
                                <div class="col-md-6">
                                    <select class="form-control select2 @error('officer_approval') is-invalid @enderror" id="officer_approval" name="officer_approval" value="{{ old('officer_approval') }}"
                                        required data-parsley-required-message="{{ setMessage('officer_approval.required') }}"
                                        data-parsley-errors-container="#parsley-errors-officer-approval">
                                        <option value="">-- Pilih Pegawai --</option>
                                        @foreach($officerApproval as $officer)
                                            <option value="{{ $officer->id }}" >{{ $officer->user->name }}</option>
                                        @endforeach
                                    </select>
                                    <div id="parsley-errors-officer-approval"></div>
                                    @error('position')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <hr/>
                            <div class="mt-2 row">
                                <div class="col-sm-11 offset-sm-1">
                                    <button type="submit" class="btn btn-primary float-end swal-review-hantar">{{ __('button.hantar') }}</button>
                                    <a href="{{ route('applicationReview.index') }}" class="btn btn-secondary float-end me-2">{{ __('button.kembali') }}</a>
                                </div>
                            </div>

                        </form>
                    </div>

                    <div class="tab-pane" id="application_info" role="tabpanel">

                        {{-- SECTION - APPLICATION INFO --}}
                        @include('modules.ApplicationReview.ApplicationReviewInfo.view-application-info')

                        <div class="mb-3 row">
                            <div class="col-sm-11 offset-sm-1">
                                <a href="{{ route('applicationReview.index') . '#' . $tab }}" class="btn btn-secondary float-end">{{ __('button.kembali') }}</a>
                            </div>
                        </div>

                    </div>

                </div>

            </div>
        </div>
    </div> <!-- end col -->
</div>
<!-- end row -->
@endsection

@section('script')
<script src="{{ URL::asset('assets/js/pages/ApplicationReview/ApplicationReview.js')}}"></script>
<script src="{{ URL::asset('assets/js/pages/ApplicationReview/ApplicationReviewInfo.js')}}"></script>

@endsection
