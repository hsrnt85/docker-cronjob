@extends('layouts.master')

@section('content')

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">

                {{-- PAGE TITLE --}}
                <div class="border-bottom border-primary mb-4"><h4 class="card-title">{{ getPageTitle(1) }}</h4></div>

                <form>
                    {{ csrf_field() }}

                    <input type="hidden" name="id" value="{{ $invitationPanel->id }}">

                    <div class="mb-3 row">
                        <label class="col-md-2 col-form-label">Nama Panel</label>
                        <div class="col-md-9"><p class="col-form-label">{{ $invitationPanel->name }}</p></div>
                    </div>
                    <div class="mb-3 row">
                        <label class="col-md-2 col-form-label">Gelaran Jawatan</label>
                        <div class="col-md-9"><p class="col-form-label">{{ $invitationPanel->position }}</p></div>
                    </div>
                    <div class="mb-3 row">
                        <label class="col-md-2 col-form-label">Nama Panel</label>
                        <div class="col-md-9"><p class="col-form-label">{{ $invitationPanel->department }}</p></div>
                    </div>
                    <div class="mb-3 row">
                        <label class="col-md-2 col-form-label">Nama Panel</label>
                        <div class="col-md-9"><p class="col-form-label">{{ $invitationPanel->email }}</p></div>
                    </div>

                    <div class="mb-3 row">
                        <div class="col-sm-11">
                            <!-- <a href="{{ route('invitationPanel.edit', ['id'=>$invitationPanel->id]) }}" class="btn btn-primary float-end ">{{ __('button.kemaskini') }}</button> -->
                            {{-- <a class="btn btn-danger float-end me-2 swal-delete">Hapus</a> --}}
                            <a href="{{ route('invitationPanel.index') }}" class="btn btn-secondary float-end me-2">{{ __('button.kembali') }}</a>
                        </div>
                    </div>
                </form>

                <form method="POST" action="{{ route('invitationPanel.delete') }}" id="delete-form">
                    {{ csrf_field() }}
                    {{ method_field('DELETE') }}
                    <input class="form-control" type="hidden" id="id-delete" name="id" value="{{ $invitationPanel->id }}">
                </form>
            </div>
        </div>
    </div> <!-- end col -->
</div>
<!-- end row -->

@endsection
