@extends('layouts.master')

@section('content')

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">

                {{-- PAGE TITLE --}}
                <div class="border-bottom border-primary mb-4"><h4 class="card-title">{{ getPageTitle(1) }}</h4></div>

                <form  class="custom-validation" id="form" method="post" action="{{ route('financeOfficer.update') }}" >
                    {{ csrf_field() }}

                    <input class="form-control" type="hidden" id="id" name="id" value="{{ $financeOfficer->id}}">

                    <div class="mb-3 row">
                        <label class="col-md-2 col-form-label">Daerah  </label>
                        <div class="col-md-10">
                            <input class="form-control" value="{{$financeOfficer->district->district_name}}" readonly>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="name" class="col-md-2 col-form-label">Nama Pegawai</label>
                        <div class="col-md-10">
                            <input class="form-control" value="{{$financeOfficer->user->name}}" readonly>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label class="col-md-2 col-form-label">Jawatan Pegawai</label>
                        <div class="col-md-10">
                            <input class="form-control" value="{{$financeOfficer->user->position->position_name}}" readonly>
                        </div>
                    </div>

                    <hr/>

                    <div class="row section-officer-category">
                        <p class="card-title-desc">Senarai Kategori Pegawai <label class="col-form-label"></label></p>

                        <div class="row">
                            <div class="mb-1" id="parsley-errors-officer-category"></div>
                        </div>

                        <div class="table-responsive col-sm-10 offset-sm-1">
                            <table class="table table-sm table-bordered" id="table-quarters-class">
                                <thead>
                                    <tr>
                                        <th class="text-center">#</th>
                                        <th class="text-center">Kategori Pegawai</th>
                                        <th class="text-center" width="15%">Pilih</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($financeCategoryAll as $i => $fin_category)
                                        @php
                                            $checked=(inArray($financeOfficer->finance_officer_category_id, $fin_category->id)) ? 'checked' : '' ;
                                        @endphp
                                        <tr>
                                            <th scope="row" class="text-center">{{$loop->iteration}}</th>
                                            <td>{{$fin_category->category_name}}</td>
                                            <td class="">
                                                <div class="form-check d-flex justify-content-center">
                                                    <input class="officer_category form-check-input me-2  @error('officer_category') is-invalid @enderror" type="checkbox" id="formCheck_{{ $fin_category->id }}"
                                                        name="officer_category[]" value="{{ old('officer_category.'.$i, $fin_category->id, $fin_category->id) }}" {{$checked}}
                                                        required data-parsley-mincheck="1"
                                                        data-parsley-required-message="{{ setMessage('officer_category.required') }}"
                                                        data-parsley-mincheck-message="{{ setMessage('officer_category.required') }}"
                                                        data-parsley-errors-container="#parsley-errors-officer-category">
                                                    <label class="form-check-label" for="formCheck_{{ $fin_category->id }}"> </label>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <div class="col-sm-12">
                            <button type="submit" class="btn btn-primary float-end swal-kemaskini">{{ __('button.kemaskini') }}</button>
                            <a href="{{ route('financeOfficer.index') }}" class="btn btn-secondary float-end me-2">{{ __('button.kembali') }}</a>
                        </div>
                    </div>
                </form>

                <form method="POST" action="{{ route('financeOfficer.delete') }}" id="delete-form">
                    {{ csrf_field() }}
                    {{ method_field('DELETE') }}
                    <input class="form-control" type="hidden" id="id-delete" name="id" value="{{ $financeOfficer->id }}">
                </form>

            </div>
        </div>
    </div> <!-- end col -->
</div>
<!-- end row -->

@endsection


