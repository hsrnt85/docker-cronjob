@extends('layouts.master')

@section('content')

<div class="row">
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

                    </div>

                    <div class="tab-pane" id="application_info" role="tabpanel">

                        {{-- SECTION - APPLICATION INFO --}}
                        @include('modules.ApplicationReview.ApplicationReviewInfo.view-application-info')

                    </div>

                    <hr/>

                    <div class="mt-3 mb-3 ms-2 row">
                        <label for="name" class="col-md-2">Ulasan</label>
                        <div class="col-md-6">
                            {{($applicationReview->remarks ?? '')}}
                        </div>
                    </div>
                    <div class="mt-3 mb-3 ms-2 row">
                        <label for="name" class="col-md-2">Pegawai Penyemak</label>
                        <div class="col-md-6">
                            {{$application->reviewed_by() ?? ''}}
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <div class="col-sm-11 offset-sm-1">
                            <a href="{{ route('applicationScoring.index'). '#' . $tab  }}" class="btn btn-secondary float-end">{{ __('button.kembali') }}</a>
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
<script src="{{ URL::asset('assets/js/pages/ApplicationReview/ApplicationReviewInfo.js')}}"></script>
@endsection
