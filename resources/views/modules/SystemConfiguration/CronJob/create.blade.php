@extends('layouts.master')

@section('content')

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">

                {{-- PAGE TITLE --}}
                <div class="border-bottom border-primary mb-4"><h4 class="card-title">{{ getPageTitle(1) }}</h4></div>

                <form class="custom-validation" id="form" method="post" action="{{ route('cronJob.store') }}" >
                    {{ csrf_field() }}
                    <div class="mb-3 row">
                        <label for="cronjob_type" class="col-md-2 col-form-label">Jenis CronJob</label>
                        <div class="col-md-10">
                            @foreach ($cronJobType as $data)
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="cronjob_type" id="cronjob_type{{ $loop->iteration }}" value="{{ $data->id }}" required
                                        @if (old('cronjob_type') == $data->id) checked="" @endif data-parsley-required-message="{{ setMessage('cronjob_type.required') }}">
                                    <label class="form-check-label" for="cronjob_type{{ $loop->iteration }}">
                                        {{ $data->cronjob_type }}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <div class="col-sm-12">
                            <button type="submit" class="btn btn-primary float-end swal-tambah">{{ __('button.proses') }}</button>
                            <a href="{{ route('cronJob.index') }}" class="btn btn-secondary float-end me-2">{{ __('button.kembali') }}</a>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div> <!-- end col -->
</div>
<!-- end row -->

@endsection

{{-- @section('script')
<script src="{{ URL::asset('assets/js/pages/Officer/cronJob.js')}}"></script>
@endsection --}}
