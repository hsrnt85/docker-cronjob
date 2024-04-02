@extends('layouts.master')

@section('content')

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">

                {{-- PAGE TITLE --}}
                <div class="border-bottom border-primary mb-4"><h4 class="card-title">{{ getPageTitle(1) }}</h4></div>

                <form method="post" action="" >
                    {{ csrf_field() }}
                    <input type="hidden" name="id" value="{{ $agensi->id }}">

                    <div class="mb-3 row">
                        <label class="col-md-2 col-form-label">Agensi</label>
                        <div class="col-md-10">
                            <p class="col-form-label">{{ $agensi->name }}</p>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label class="col-md-2 col-form-label">Kod Agensi (JohorPay)</label>
                        <div class="col-md-10">
                            <p class="col-form-label">{{ $agensi->code ?? "" }}</p>
                        </div>
                    </div>

                    <div class="mb-3 row"> 
                        <div class="col-sm-12">
                            {{-- <a href="" class="btn btn-primary float-end">{{ __('button.kemaskini') }}</a>
                            <a class="btn btn-danger float-end me-2 swal-delete">{{ __('button.hapus') }}</a> --}}
                            <a href="{{ route('agency.index') }}" class="btn btn-secondary float-end me-2">{{ __('button.kembali') }}</a>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div> <!-- end col -->
</div>
<!-- end row -->

@endsection
