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

                    <input type="hidden" name="id" value="{{ $document->id }}">

                    <div class="mb-3 row">
                        <label for="name" class="col-md-2 col-form-label">Nama Dokumen</label>
                        <div class="col-md-10">
                            <input class="form-control" type="text" id="document" name="document" value="{{ $document->document_name }}" readonly>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <div class="col-sm-12">
                            <!-- <a href="{{ route('document.edit', ['id'=>$document->id]) }}" class="btn btn-primary float-end ">{{ __('button.kemaskini') }}</button> -->
                            <a class="btn btn-danger float-end me-2 swal-delete">Hapus</a>
                            <a href="{{ route('document.index') }}" class="btn btn-secondary float-end me-2">{{ __('button.kembali') }}</a>
                        </div>
                    </div>
                </form>

                <form method="POST" action="{{ route('document.delete') }}" id="delete-form">
                    {{ csrf_field() }}
                    {{ method_field('DELETE') }}
                    <input class="form-control" type="hidden" id="id-delete" name="id" value="{{ $document->id }}">
                </form>
            </div>
        </div>
    </div> <!-- end col -->
</div>
<!-- end row -->

@endsection
