@extends('layouts.master')

@section('title') @lang('translation.Dashboards') @endsection

@section('content')

@component('components.breadcrumb')
@slot('li_1') Dashboards @endslot
@slot('title') Pegawai @endslot
@endcomponent

@component('components.alert')@endcomponent

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">

                <h4 class="card-title">Senarai Kategori Pegawai</h4>

                <div class="row mb-2">
                    <div class="col-sm-12">
                        <a type="button" href="{{ route('officerCategory.create') }}" class="btn btn-primary float-end waves-effect waves-light">{{ __('button.simpan') }} Kategori Pegawai</a>
                    </div>
                </div>
                <div id="datatable_wrapper">
                    <div class="row">
                        <div class="col-sm-12">
                            <table id="datatable" class="table table-striped table-bordered dt-responsive nowrap w-100 dataTable" role="grid" >
                                <thead>
                                    <tr role="row">
                                        <th class="text-center" >Bil</th>
                                        <th class="text-center" >Nama Kategori</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($officerCategoryAll as $officerCategory)
                                        <tr class="odd">
                                            <td class="text-center" tabindex="0">{{ $loop->iteration }}</td>
                                            <td class="text-center"><a href="{{ route('officerCategory.edit', ['id' => $officerCategory->id]) }}"> {{$officerCategory->category_name}}</a></td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
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
<script src="{{ URL::asset('/assets/libs/datatables/datatables.min.js') }}"></script>
@endsection
