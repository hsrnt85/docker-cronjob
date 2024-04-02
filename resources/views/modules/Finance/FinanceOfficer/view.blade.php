@extends('layouts.master')

@section('content')

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">

                {{-- PAGE TITLE --}}
                <div class="border-bottom border-primary mb-4"><h4 class="card-title">{{ getPageTitle(1) }}</h4></div>

                    <input type="hidden" name="id" value="{{ $financeOfficer->id }}">

                    <div class="mb-3 row">
                        <label class="col-md-2 col-form-label">Daerah</label>
                        <div class="col-md-10">
                            <p class="col-form-label">{{ strtoupper($financeOfficer->district->district_name) }}</p>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label class="col-md-2 col-form-label">Nama Pegawai</label>
                        <div class="col-md-10">
                            <p class="col-form-label">{{ strtoupper($financeOfficer->user->name)}}</p>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label class="col-md-2 col-form-label">Jawatan Pegawai</label>
                        <div class="col-md-10">
                            <p class="col-form-label">{{ strtoupper($financeOfficer->user->position->position_name ) }}</p>
                        </div>
                    </div>

                    <hr>
                    <p class="card-title-desc">Senarai Kategori Pegawai</p>

                    <div class="row">
                        <div class="table-responsive col-sm-10 offset-sm-1">
                            <table class="table table-sm table-bordered" id="table-quarters-class">
                                <thead>
                                    <tr>
                                        <th class="text-center">Bil</th>
                                        <th class="text-center">Kategori Pegawai</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($financeOfficerCategory as $category)
                                    <tr>
                                        <th scope="row" class="text-center">{{$loop->iteration}}</th>
                                        <td>{{$category->category_name}}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <hr>
                    <div class="mb-3 row">
                        <div class="col-sm-12">
                            <a href="{{ route('financeOfficer.index') }}" class="btn btn-secondary float-end me-2">{{ __('button.kembali') }}</a>
                        </div>
                    </div>
            </div>
        </div>
    </div> <!-- end col -->
</div>
<!-- end row -->

@endsection
