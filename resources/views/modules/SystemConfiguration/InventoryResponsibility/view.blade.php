@extends('layouts.master')

@section('content')

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">

                {{-- PAGE TITLE --}}
                <div class="border-bottom border-primary mb-4"><h4 class="card-title">{{ getPageTitle(1) }}</h4></div>

                <form method="post" action="{{ route('inventoryResponsibility.update') }}" >
                    {{ csrf_field() }}
                    <input type="hidden" name="id" value="{{ $inventoryResponsibility->id }}">

                    <div class="mb-3 row">
                        <label class="col-md-4 col-form-label">Jabatan Bertanggungjawab (Inventori)</label>
                        <div class="col-md-8">
                            <p class="col-form-label">{{ $inventoryResponsibility->name }}</p>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <div class="col-sm-12">
                            <!-- <a href="{{ route('inventoryResponsibility.edit', ['id' => $inventoryResponsibility->id]) }}" class="btn btn-primary float-end">{{ __('button.kemaskini') }}</a> -->
                            {{-- <a class="btn btn-danger float-end me-2 swal-delete">{{ __('button.hapus') }}</a> --}}
                            <a href="{{ route('inventoryResponsibility.index') }}" class="btn btn-secondary float-end me-2">{{ __('button.kembali') }}</a>
                        </div>
                    </div>
                </form>

                {{-- <form method="POST" action="{{ route('inventoryResponsibility.delete') }}" id="delete-form">
                    {{ csrf_field() }}
                    {{ method_field('DELETE') }}
                    <input class="form-control" type="hidden" id="id-delete" name="id" value="{{ $inventoryResponsibility->id }}">
                </form> --}}
            </div>
        </div>
    </div> <!-- end col -->
</div>
<!-- end row -->

@endsection
