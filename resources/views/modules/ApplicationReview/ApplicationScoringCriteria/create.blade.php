@extends('layouts.master')

@section('content')

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">

                {{-- PAGE TITLE --}}
                <div class="border-bottom border-primary mb-4"><h4 class="card-title">{{ getPageTitle(1) }}</h4></div>

                <form class="custom-validation" id="form" method="post" action="{{ route('applicationScoringCriteria.store') }}" >
                    {{ csrf_field() }}

                    <div class="mb-3 row">
                        <div class="mb-2 row">
                            <label for="description" class="col-md-2 col-form-label">Keterangan</label>
                            <div class="col-md-10">
                                <input class="form-control @error('description') is-invalid @enderror" type="text" id="description" name="description" value="{{ old('description') }}"
                                    required data-parsley-required-message="{{ setMessage('description.required') }}">
                                @error('description') <div class="invalid-feedback"> {{ $message }} </div> @enderror
                            </div>
                        </div>

                        <div class="mb-2 row">
                            <label for="execution_date" class="col-md-2 col-form-label">Tarikh Kuatkuasa</label>
                            <div class="col-md-4">
                                <input class="form-control @error('execution_date') is-invalid @enderror" type="date" id="execution_date" name="execution_date" value="{{ old('execution_date') }}"
                                    required data-parsley-required-message="{{ setMessage('execution_date.required') }}">
                                @error('execution_date') <div class="invalid-feedback"> {{ $message }} </div> @enderror
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <table class="table table-sm table-bordered"  id="table-criteria" data-list="{{ (old('criteria_name', null) == null) ? 0 : count(old('criteria_name')) }}" >
                            @php
                                $width_remarks = "90%";
                                $width_mark = "60px";
                            @endphp
                            <thead class="bg-primary bg-gradient text-white">
                                <tr role="row">
                                    <th width="3%" class="text-center">Bil</th>
                                    <th width="40%">Kriteria</th>
                                    <th >
                                        <div class="d-flex">
                                            <div class="flex-fill" style="width:{{ $width_remarks }}">Kenyataan</div>
                                            <div class="flex-fill">Markah</div>
                                        </div>
                                    </th>
                                    <th width="5%" class='row-odd text-center'>
                                        <a class="btn btn-primary btn-sm" onclick="duplicateRowCriteria();"><i class="mdi mdi-plus mdi-18px"></i></a>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $i_criteria = 0;
                                    $i_subcriteria = 0;
                                @endphp
                                @if(old('criteria_name', null) == null)
                                    @include('modules.ApplicationReview.ApplicationScoringCriteria.create-row-data-criteria')
                                @else
                                    @foreach (old('criteria_name') as $i_criteria => $criteria_name)
                                        @include('modules.ApplicationReview.ApplicationScoringCriteria.old-row-data-criteria')
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                        <table class="table table-sm table-bordered">
                            <tr id="row_total_mark" >
                                <td width="43%" class="text-center" colspan="2"> <b>Jumlah Markah</b> </td>
                                <td>
                                    <div class="row">
                                        <div class="col-md-12 input-group">
                                            <div class="col-md-10"></div>
                                            <div class="col-md-1">
                                                <input type='text' id="total_mark" class='form-control' style="width:{{$width_mark}}" value="">
                                            </div>
                                        </div>{{-- end input-group --}}
                                    </div>{{-- end row --}}

                                </td>
                                <td width="5%"> </td>
                            </tr>
                        </table>

                    </div>

                    <div class="row">
                        <div class="col-sm-12">
                            <button type="submit" class="btn btn-primary float-end swal-tambah">{{ __('button.simpan') }}</button>
                            <a href="{{ route('applicationScoringCriteria.index') }}" class="btn btn-secondary float-end me-2">{{ __('button.kembali') }}</a>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div> <!-- end col -->
</div>
<!-- end row -->

@endsection

@section('script')
<script src="{{ URL::asset('assets/js/pages/ApplicationScoringCriteria/ApplicationScoringCriteria.js')}}"></script>
@endsection
