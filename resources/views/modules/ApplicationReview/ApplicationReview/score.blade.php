@extends('layouts.master')

@section('content')

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">

                <h4 class="card-title">Penilaian Permohonan</h4>
                <p class="card-title-desc">Penilaian permohonan</p>

                <form method="post" action="{{ route('applicationReview.store') }}" >
                    {{ csrf_field() }}

                    <input type="hidden" name="id" value="{{ $application->id }}">

                    <div class=" row">
                        <label for="name" class="col-md-2 col-form-label">Pemohon</label>
                        <p class="col-md-9 col-form-label">{{ $application->user->name }}</p>
                    </div>

                    <div class=" row">
                        <label for="name" class="col-md-2 col-form-label">Kategori</label>
                        <p class="col-md-9 col-form-label">{{ $application->category->name }}</p>
                    </div>

                    <div class=" row">
                        <label for="name" class="col-md-2 col-form-label">Tarikh</label>
                        <p class="col-md-9 col-form-label">{{ convertDateSys($application->application_date_time)  }}</p>
                    </div>

                    <hr>

                    <div class="row">
                        <label for="name" class="col-md-2 col-form-label">Penilaian</label>
                        <div class="table-responsive col-sm-9">
                            <table class="table table-sm table-bordered" id="table-anak-list">
                                <thead class="text-white bg-primary">
                                    <tr>
                                        <th class="text-center">#</th>
                                        <th class="text-center">Kategori</th>
                                        <th class="text-center">Kriteria</th>
                                        <th class="text-center">Kenyataan</th>
                                        <th class="text-center">Markah</th>
                                        <th class="text-center">Tindakan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($categoryArr as $valueCategory)

                                        <tr class="bg-primary bg-soft ">
                                            <td class="text-center" rowspan="{{$valueCategory['rowspan']}}">{{ $loop->iteration }}</td>
                                            <td class="text-center" rowspan="{{$valueCategory['rowspan']}}">{{ $valueCategory['criteria_category'] }}</td>
                                            <td class="text-center" rowspan="{{$subCriteriaArr[$criteriaArr[$valueCategory['id']]['data'][0]->id]['rowspan']}}">{{ $criteriaArr[$valueCategory['id']]['data'][0]->criteria }}</td>
                                            <td class="text-center" >{{ $subCriteriaArr[$criteriaArr[$valueCategory['id']]['data'][0]->id]['data'][0]->sub_criteria }}</td>
                                            <td class="text-center" >{{ $subCriteriaArr[$criteriaArr[$valueCategory['id']]['data'][0]->id]['data'][0]->mark }}</td>
                                            <td class="text-center">
                                                <label class="d-block m-0" for="__{{ $subCriteriaArr[$criteriaArr[$valueCategory['id']]['data'][0]->id]['data'][0]->id }}" >
                                                    <input type="hidden" name="mark[{{$subCriteriaArr[$criteriaArr[$valueCategory['id']]['data'][0]->id]['data'][0]->s_criteria_id}}]" type="radio" value="" />
                                                    <input  class="form-check-input @error('mark.' . $subCriteriaArr[$criteriaArr[$valueCategory['id']]['data'][0]->id]['data'][0]->s_criteria_id) is-invalid @enderror" type="radio" name="mark[{{ $subCriteriaArr[$criteriaArr[$valueCategory['id']]['data'][0]->id]['data'][0]->s_criteria_id }}]" value="{{ $subCriteriaArr[$criteriaArr[$valueCategory['id']]['data'][0]->id]['data'][0]->id }}" id="__{{ $subCriteriaArr[$criteriaArr[$valueCategory['id']]['data'][0]->id]['data'][0]->id }}" />
                                                    @error('mark.' . $subCriteriaArr[$criteriaArr[$valueCategory['id']]['data'][0]->id]['data'][0]->s_criteria_id)
                                                        <div class="invalid-feedback">
                                                            {{ $message }}
                                                        </div>
                                                    @enderror
                                                </label>
                                            </td>
                                        </tr>

                                        @for ($i = 1; $i < $subCriteriaArr[$criteriaArr[$valueCategory['id']]['data'][0]->id]['data']->count(); $i++)
                                            <tr class="bg-primary bg-soft" >
                                                <td class="text-center">{{ $subCriteriaArr[$criteriaArr[$valueCategory['id']]['data'][0]->id]['data'][$i]->sub_criteria }}</td>
                                                <td class="text-center">{{ $subCriteriaArr[$criteriaArr[$valueCategory['id']]['data'][0]->id]['data'][$i]->mark }}</td>
                                                <td class="text-center">
                                                    <label class="d-block m-0" for="__{{ $subCriteriaArr[$criteriaArr[$valueCategory['id']]['data'][0]->id]['data'][$i]->id }}" >
                                                        <input  class="form-check-input @error('mark.' . $subCriteriaArr[$criteriaArr[$valueCategory['id']]['data'][0]->id]['data'][$i]->s_criteria_id) is-invalid @enderror" type="radio" name="mark[{{ $subCriteriaArr[$criteriaArr[$valueCategory['id']]['data'][0]->id]['data'][$i]->s_criteria_id }}]" value="{{ $subCriteriaArr[$criteriaArr[$valueCategory['id']]['data'][0]->id]['data'][$i]->id }}" id="__{{ $subCriteriaArr[$criteriaArr[$valueCategory['id']]['data'][0]->id]['data'][$i]->id }}" />
                                                        @error('mark.' . $subCriteriaArr[$criteriaArr[$valueCategory['id']]['data'][0]->id]['data'][$i]->s_criteria_id)
                                                            <div class="invalid-feedback">
                                                                {{ $message }}
                                                            </div>
                                                        @enderror
                                                    </label>
                                                </td>
                                            </tr>
                                        @endfor

                                        @for ($j = 1; $j < $criteriaArr[$valueCategory['id']]['data']->count(); $j++)
                                            <tr class="bg-primary bg-soft" >
                                                <td class="text-center" rowspan="{{$subCriteriaArr[$criteriaArr[$valueCategory['id']]['data'][$j]->id]['rowspan']}}">{{ $criteriaArr[$valueCategory['id']]['data'][$j]->criteria }}</td>
                                                <td class="text-center">{{ $subCriteriaArr[$criteriaArr[$valueCategory['id']]['data'][$j]->id]['data'][0]->sub_criteria }}</td>
                                                <td class="text-center">{{ $subCriteriaArr[$criteriaArr[$valueCategory['id']]['data'][$j]->id]['data'][0]->mark }}</td>
                                                <td class="text-center">
                                                    <label class="d-block m-0" for="__{{ $subCriteriaArr[$criteriaArr[$valueCategory['id']]['data'][$j]->id]['data'][0]->id }}" >
                                                        <input type="hidden" name="mark[{{ $subCriteriaArr[$criteriaArr[$valueCategory['id']]['data'][$j]->id]['data'][0]->s_criteria_id }}]" type="radio" value="" />
                                                        <input  class="form-check-input @error('mark.' . $subCriteriaArr[$criteriaArr[$valueCategory['id']]['data'][$j]->id]['data'][0]->s_criteria_id) is-invalid @enderror" type="radio" name="mark[{{ $subCriteriaArr[$criteriaArr[$valueCategory['id']]['data'][$j]->id]['data'][0]->s_criteria_id }}]" value="{{ $subCriteriaArr[$criteriaArr[$valueCategory['id']]['data'][$j]->id]['data'][0]->id }}" id="__{{ $subCriteriaArr[$criteriaArr[$valueCategory['id']]['data'][$j]->id]['data'][0]->id }}" />
                                                        @error('mark.' . $subCriteriaArr[$criteriaArr[$valueCategory['id']]['data'][$j]->id]['data'][0]->s_criteria_id)
                                                            <div class="invalid-feedback">
                                                                {{ $message }}
                                                            </div>
                                                        @enderror
                                                    </label>
                                                </td>
                                            </tr>


                                            @for ($k = 1; $k < $subCriteriaArr[$criteriaArr[$valueCategory['id']]['data'][$j]->id]['data']->count(); $k++)
                                                <tr class="bg-primary bg-soft" >
                                                    <td class="text-center">{{ $subCriteriaArr[$criteriaArr[$valueCategory['id']]['data'][$j]->id]['data'][$k]->sub_criteria }}</td>
                                                    <td class="text-center">{{ $subCriteriaArr[$criteriaArr[$valueCategory['id']]['data'][$j]->id]['data'][$k]->mark }}</td>
                                                    <td class="text-center">
                                                        <label class="d-block m-0" for="__{{ $subCriteriaArr[$criteriaArr[$valueCategory['id']]['data'][$j]->id]['data'][$k]->id }}" >
                                                            <input  class="form-check-input @error('mark.' . $subCriteriaArr[$criteriaArr[$valueCategory['id']]['data'][$j]->id]['data'][$k]->s_criteria_id) is-invalid @enderror" type="radio" name="mark[{{ $subCriteriaArr[$criteriaArr[$valueCategory['id']]['data'][$j]->id]['data'][$k]->s_criteria_id }}]" value="{{ $subCriteriaArr[$criteriaArr[$valueCategory['id']]['data'][$j]->id]['data'][$k]->id }}" id="__{{ $subCriteriaArr[$criteriaArr[$valueCategory['id']]['data'][$j]->id]['data'][$k]->id }}" />
                                                            @error('mark.' . $subCriteriaArr[$criteriaArr[$valueCategory['id']]['data'][$j]->id]['data'][$k]->s_criteria_id)
                                                                <div class="invalid-feedback">
                                                                    {{ $message }}
                                                                </div>
                                                            @enderror
                                                        </label>
                                                    </td>
                                                </tr>
                                            @endfor

                                        @endfor

                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <div class="col-sm-10 offset-sm-1">
                            <button type="submit" class="btn btn-primary float-end swal-kemaskini">{{ __('button.kemaskini') }}</button>
                            <a href="{{ route('applicationReview.index') . '#' . $tab}}" class="btn btn-secondary float-end me-2">{{ __('button.kembali') }}</a>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div> <!-- end col -->
</div>
<!-- end row -->

@endsection
