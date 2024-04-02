@extends('layouts.master')

@section('content')

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">

                {{-- PAGE TITLE --}}
                <div class="border-bottom border-primary mb-4"><h4 class="card-title">{{ getPageTitle(2) }}</h4></div>

                <form class="custom-validation" id="form" method="post" action="{{ route('inventory.update') }}" >
                    {{ csrf_field() }}

                    <input class="form-control" type="hidden" id="id" name="id" value="{{ $inventory->id }}">
                    <input class="form-control" type="hidden" id="quarters_cat_id" name="quarters_cat_id" value="{{ $quarters_cat_id }}">
                    <div class="mb-3 row">
                        <label for="name" class="col-md-2 col-form-label">Inventori</label>
                        <div class="col-md-10">
                            <input class="form-control @error('name') is-invalid @enderror" type="text" id="name" name="name" value="{{ $inventory->name }}" required data-parsley-required-message="{{ setMessage('name.required') }}">
                            @error('name')
                            <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="name" class="col-md-2 col-form-label">Harga (RM)</label>
                        <div class="col-md-10">
                            <input class="form-control @error('price') is-invalid @enderror numeric" type="text" id="price" name="price" value="{{ $inventory->price }}" oninput="validateHargaInventory(this)" required data-parsley-required-message="{{ setMessage('price.required') }}">
                            @error('price')
                            <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <div class="col-sm-12">
                            <button type="submit" class="btn btn-primary float-end swal-kemaskini">{{ __('button.kemaskini') }}</button>
                            {{-- <a class="btn btn-danger float-end me-2 swal-delete">{{ __('button.hapus') }}</a> --}}
                            <a href="{{ route('inventory.index', ['quarters_cat_id' => $quarters_cat_id]) }}" class="btn btn-secondary float-end me-2">{{ __('button.kembali') }}</a>
                        </div>
                    </div>
                </form>

                {{-- <form method="POST" action="{{ route('inventory.delete') }}" id="delete-form">
                    {{ csrf_field() }}
                    {{ method_field('DELETE') }}
                    <input class="form-control" type="hidden" id="id-delete" name="id" value="{{ $inventory->id }}">
                    <input class="form-control" type="hidden" name="quarters_cat_id" value="{{ $quarters_cat_id }}">
                </form> --}}

            </div>
        </div>
    </div> <!-- end col -->
</div>
<!-- end row -->

@endsection

@section('script')
<script src="{{ URL::asset('assets/js/pages/Inventory/inventory.js')}}"></script>
@endsection
