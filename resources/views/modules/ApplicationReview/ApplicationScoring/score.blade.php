@extends('layouts.master')

@section('content')

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">

                {{-- PAGE TITLE --}}
                <div class="border-bottom border-primary mb-4"><h4 class="card-title">{{ getPageTitle(1) }}</h4></div>

                <form class="custom-validation" id="form" method="post" action="{{ route('applicationScoring.store') }}" >
                    {{ csrf_field() }}

                    <input type="hidden" name="id" value="{{ $application->id }}">
                    <input type="hidden" name="qcid" value="{{ $application->quarters_category_id }}">

                    <div class="row">
                        <label for="name" class="col-md-2 col-form-label">Pemohon</label>
                        <p class="col-md-9 col-form-label">{{ $application->user->name }}</p>
                    </div>

                    <div class="row">
                        <label for="name" class="col-md-2 col-form-label">Kategori Kuarters (Lokasi)</label>
                        <p class="col-md-9 col-form-label">{!! ArrayToStringList($application->quarters_category->pluck('name')->toArray()) !!}</p>
                    </div>

                    <div class="row mb-4">
                        <label for="name" class="col-md-2 col-form-label">Tarikh Permohonan</label>
                        <p class="col-md-9 col-form-label">{{ convertDateSys($application->application_date_time)  }}</p>
                    </div>

                    <hr>

                    <!-- TABS -->
                    <ul class="nav nav-tabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" data-bs-toggle="tab" href="#application_scoring" role="tab">
                                <span class="d-none d-sm-block">Penilaian Permohonan</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#application_info" role="tab">
                                <span class="d-none d-sm-block">Maklumat Permohonan</span>
                            </a>
                        </li>
                    </ul>

                    <!-- TABS CONTENT-->
                    <div class="tab-content p-3 text-muted">
                        <div class="tab-pane active" id="application_scoring" role="tabpanel">

                            <div class="row">

                                <div class="table-responsive col-sm-12">
                                    <table class="table dt-responsive " >
                                        @php
                                            $width_remarks = "40%";
                                            $width_mark = "50px";
                                        @endphp
                                        <thead class="bg-primary bg-gradient text-white">
                                            <tr role="row">
                                                <th class="text-center">Bil</th>
                                                <th width="40%">Kriteria</th>
                                                <th colspan="2">
                                                    <div class="d-flex">
                                                        <div class="flex-fill" style="width:{{ $width_remarks }}">Kenyataan</div>
                                                        <div class="flex-fill">Markah Penuh</div>
                                                        <div class="flex-fill">Markah Penilaian</div>
                                                    </div>
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                        @php  $total_mark = 0; @endphp
                                        @if($criteriaArr)

                                            @foreach($criteriaArr as $i_criteria => $criteria)
                                                @php
                                                    $criteria_id = $criteria['criteria_id'] ?? 0;
                                                    $criteria_name = $criteria['criteria_name'] ?? '';
                                                    $calculation_method = $criteria['calculation_method'] ?? 0;
                                                    $mapping_name = $criteria['mapping_name'] ?? '';
                                                    $collective_noun = $criteria['collective_noun'] ?? '';
                                                    $collective_noun_position = $criteria['collective_noun_position'] ?? '';
                                                    $flag_table = $criteria['flag_table'] ?? '';
                                                    $is_dropdown = $criteria['is_dropdown'] ?? 0;
                                                    $is_range = $criteria['is_range'] ?? 0;
                                                @endphp
                                                @if(!$loop->last)

                                                <tr data-criteria-index='{{ $i_criteria }}' @class(['row-odd' => $loop->odd, 'row-even' => $loop->even]) >
                                                    <td class="text-center align-top" > @if(!$loop->last) {{ $loop->iteration }} @endif</td>
                                                    <td class="align-top" >
                                                        <input type="hidden" name="criteria_name[{{$i_criteria}}]" value="{{ $criteria_name }}"></input> {{ $criteria_name  }}
                                                        @if($calculation_method==1)
                                                            <div class="form-check mb-2" >
                                                                <input class="form-check-input calculation_method" name="calculation_method[{{$i_criteria}}]" type="checkbox" @if($calculation_method==1) checked disabled @endif></input>
                                                                <label class="form-check-label" for="calculation_method" > Pengiraan Auto - {{ $mapping_name ?? ''}}</label>
                                                            </div>
                                                        @endif
                                                    </td>
                                                    <td>

                                                        @if($is_dropdown == 1)

                                                            @if($flag_table == 'services_type')
                                                                <div class="services_type">
                                                                    <input type="hidden" name="flag_section_auto_dropdown[{{$i_criteria}}]" value="1">
                                                                    @foreach($servicesTypeAll as $i => $servicesType)
                                                                        @php
                                                                            $full_mark = $subCriteriaArr[$criteria_id][$i]['full_mark'] ?? '0' ;
                                                                            $mark = $subCriteriaArr[$criteria_id][$i]['mark'] ?? '0' ;
                                                                            $total_mark += $mark;
                                                                        @endphp
                                                                        <div class="d-flex services_type_row {{ $loop->last=='' ? 'border-bottom' : '' }} ">
                                                                            <div class="flex-fill mt-2" style="width:{{ $width_remarks }}">
                                                                                <input type="hidden" name="subcriteria_name[{{$i_criteria}}][{{$i}}]" value="{{ $servicesType -> services_type }}"></input> {{ $servicesType -> services_type }}
                                                                            </div>
                                                                            <div class="flex-fill">
                                                                                <input type='text' class='form-control full_mark' style="width:{{$width_mark}}" name='full_mark[{{$i_criteria}}][{{$i}}]' value="{{ $full_mark }}"
                                                                                    id='full_mark_{{ $i_criteria }}_{{ $i }}' data-subcriteria-index='{{ $i }}' >
                                                                            </div>
                                                                            <div class="flex-fill" style="width:{{$width_mark}}">
                                                                                @if($mark > 0)<input type='text' class='form-control mark' style="width:{{$width_mark}}" name='mark[{{$i_criteria}}][{{$i}}]' value="{{ $full_mark }}" value="{{ $mark }}"> @endif
                                                                            </div>
                                                                        </div>
                                                                    @endforeach
                                                                </div>

                                                            @elseif($flag_table == 'position_type')
                                                                <div class="position_type">
                                                                    <input type="hidden" name="flag_section_auto_dropdown[{{$i_criteria}}]" value="1">
                                                                    @foreach($positionTypeAll as $i => $positionType)
                                                                        @php
                                                                            $full_mark = $subCriteriaArr[$criteria_id][$i]['full_mark'] ?? '0' ;
                                                                            $mark = $subCriteriaArr[$criteria_id][$i]['mark'] ?? '0' ;
                                                                            $total_mark += $mark;
                                                                        @endphp
                                                                        <div class="d-flex position_type_row {{ $loop->last=='' ? 'border-bottom' : '' }} ">
                                                                            <div class="flex-fill mt-2" style="width:{{ $width_remarks }}">
                                                                                <input type="hidden" name="subcriteria_name[{{$i_criteria}}][{{$i}}]" value="{{ $positionType -> position_type }}">{{ $positionType -> position_type }}
                                                                            </div>
                                                                            <div class="flex-fill">
                                                                                <input type='text' class='form-control full_mark' style="width:{{$width_mark}}" name='full_mark[{{$i_criteria}}][{{$i}}]' value="{{ $full_mark }}"
                                                                                        id='full_mark_{{ $i_criteria }}_{{ $i }}' data-subcriteria-index='{{ $i }}' > </div>
                                                                            <div class="flex-fill" style="width:{{$width_mark}}">
                                                                                @if($mark > 0)<input type='text' class='form-control mark' style="width:{{$width_mark}}" name='mark[{{$i_criteria}}][{{$i}}]' value="{{ $mark }}"> @endif
                                                                            </div>
                                                                        </div>
                                                                    @endforeach
                                                                </div>
                                                            @elseif($flag_table == 'marital_status')
                                                                <div class="marital_status">
                                                                    <input type="hidden" name="flag_section_auto_dropdown[{{$i_criteria}}]" value="1">
                                                                    @foreach($maritalStatusAll as $i => $maritalStatus)
                                                                        @php
                                                                            $full_mark = $subCriteriaArr[$criteria_id][$i]['full_mark'] ?? '0' ;
                                                                            $mark = $subCriteriaArr[$criteria_id][$i]['mark'] ?? '0' ;
                                                                            $total_mark += $mark;
                                                                        @endphp
                                                                        <div class="d-flex marital_status_row {{ $loop->last=='' ? 'border-bottom' : '' }} ">
                                                                            <div class="flex-fill mt-2" style="width:{{ $width_remarks }}">
                                                                                <input type="hidden" name="subcriteria_name[{{$i_criteria}}][{{$i}}]" value="{{ $maritalStatus -> marital_status }}">{{ $maritalStatus -> marital_status }}
                                                                            </div>
                                                                            <div class="flex-fill">
                                                                                <input type='text' class='form-control full_mark' style="width:{{$width_mark}}" name='full_mark[{{$i_criteria}}][{{$i}}]' value="{{ $full_mark }}"
                                                                                    id='full_mark_{{ $i_criteria }}_{{ $i }}' data-subcriteria-index='{{ $i }}'>
                                                                            </div>
                                                                            <div class="flex-fill" style="width:{{$width_mark}}">
                                                                                @if($mark > 0)<input type='text' class='form-control mark' style="width:{{$width_mark}}" name='mark[{{$i_criteria}}][{{$i}}]' value="{{ $mark }}"> @endif
                                                                            </div>
                                                                        </div>
                                                                    @endforeach
                                                                </div>
                                                            @endif

                                                        @elseif($is_range==1)

                                                            @if(isset($subCriteriaArr[$criteria_id]))

                                                                @foreach($subCriteriaArr[$criteria_id] as $i => $subCriteria)
                                                                    @php
                                                                        $range_from = isset($subCriteria['range_from']) ? $subCriteria['range_from'] : '0';
                                                                        $operator_id = isset($subCriteria['operator_id']) ? $subCriteria['operator_id'] : '0';
                                                                        $range_to = isset($subCriteria['range_to']) ? $subCriteria['range_to'] : '0';
                                                                        $full_mark = isset($subCriteria['full_mark']) ? $subCriteria['full_mark'] : '0' ;
                                                                        $mark = isset($subCriteria['mark']) ? $subCriteria['mark'] : '0';
                                                                        $subcriteria_name = '';
                                                                        $total_mark += $mark;

                                                                    @endphp

                                                                    <div class="d-flex {{ $loop->last=='' ? 'border-bottom' : '' }}" >
                                                                        <div class="flex-fill mt-2" style="width:{{ $width_remarks }}">
                                                                        @php
                                                                            $subcriteria_name = ($collective_noun_position == 'PRE') ? $collective_noun : '';
                                                                            $subcriteria_name .= ($collective_noun == 'RM') ? ' '.numberFormatComma($range_from) : $range_from ;
                                                                            $subcriteria_name .= ($collective_noun_position == 'POST') ? ' '.$collective_noun : '';

                                                                            foreach($operatorAll as $operator){
                                                                                if($operator->id == $operator_id && $operator->id <> 2){
                                                                                    $subcriteria_name .= ' '.$operator -> operator_name;
                                                                                }elseif($operator->id == $operator_id && $operator->id == 2){
                                                                                    $subcriteria_name .= ' '.$operator -> operator_sign;
                                                                                }
                                                                            }

                                                                            if($range_to>0){
                                                                                $subcriteria_name .= ($collective_noun_position == 'PRE') ? ' '.$collective_noun : '';
                                                                                $subcriteria_name .= ($collective_noun == 'RM') ? ' '.numberFormatComma($range_to) : $range_to ;
                                                                                $subcriteria_name .= ($collective_noun_position == 'POST') ? ' '.$collective_noun : '';
                                                                            }
                                                                            @endphp
                                                                            {{ $subcriteria_name }}
                                                                            <input type="hidden" name="subcriteria_name[{{$i_criteria}}][{{$i}}]" value="{{ $subcriteria_name }}">

                                                                        </div>
                                                                        <div class="flex-fill">
                                                                            <input type='text' class='form-control full_mark' style="width:{{$width_mark}}" name='full_mark[{{$i_criteria}}][{{$i}}]' value="{{ $full_mark }}"
                                                                                   id='full_mark_{{ $i_criteria }}_{{ $i }}' data-subcriteria-index='{{ $i }}'>
                                                                        </div>
                                                                        <div class="flex-fill" style="width:{{$width_mark}}">
                                                                            @if($mark > 0)
                                                                            <input type='text' class='form-control mark' style="width:{{$width_mark}}" name='mark[{{$i_criteria}}][{{$i}}]' value="{{ $mark }}">
                                                                            @endif
                                                                        </div>
                                                                    </div>
                                                                @endforeach
                                                            @endif
                                                        @else

                                                            @if(isset($subCriteriaArr[$criteria_id]))
                                                                @php
                                                                    $flag_edit = (count($subCriteriaArr[$criteria_id]) == 1) ? 1 : 0;
                                                                @endphp
                                                                <input type="hidden" name="flag_edit[{{$i_criteria}}]" value="{{ $flag_edit }}">

                                                                @foreach($subCriteriaArr[$criteria_id] as $i => $subCriteria)
                                                                    @php
                                                                        $remarks = isset($subCriteria['remarks']) ? $subCriteria['remarks'] : '';
                                                                        $full_mark = isset($subCriteria['full_mark']) ? $subCriteria['full_mark'] : '0' ;
                                                                        $mark = isset($subCriteria['mark']) ? $subCriteria['mark'] : '0';
                                                                        $total_mark += $mark;
                                                                    @endphp
                                                                    <div class="d-flex {{ $loop->last=='' ? 'border-bottom' : '' }}" >
                                                                        <div class="flex-fill mt-1" style="width:{{ $width_remarks }}">

                                                                            @if(count($subCriteriaArr[$criteria_id])>1)

                                                                                <div class="form-check">
                                                                                    <input class="form-check-input cb_remarks cb_remarks_{{ $i_criteria }}" type="checkbox" id='cb_remarks_{{ $i_criteria }}_{{ $i }}' data-subcriteria-index='{{ $i }}'>
                                                                                    <label >{{ $remarks }}</label>
                                                                                    <input type="hidden" name="subcriteria_name[{{$i_criteria}}][{{$i}}]" value="{{ $remarks }}">
                                                                                </div>
                                                                            @else
                                                                                <input type="hidden" name="subcriteria_name[{{$i_criteria}}][{{$i}}]" value="{{ $remarks }}">
                                                                                {{ $remarks }}
                                                                            @endif
                                                                        </div>
                                                                        <div class="flex-fill text-center">
                                                                            <input type='text' class='form-control full_mark' style='width:{{$width_mark}}' name='full_mark[{{$i_criteria}}][{{$i}}]' value="{{ $full_mark }}"
                                                                                id='full_mark_{{ $i_criteria }}_{{ $i }}' data-subcriteria-index='{{ $i }}'>
                                                                                @if(count($subCriteriaArr[$criteria_id])>1)
                                                                            @endif
                                                                        </div>
                                                                        <div class="flex-fill">
                                                                            <input type='text' class='form-control @if(count($subCriteriaArr[$criteria_id])>1) selected_mark selected_mark_{{$i_criteria}} @else manual_mark @endif'
                                                                                style='width:{{$width_mark}}' oninput="checkNumber(this)" onfocus="focusInput(this);"
                                                                                id='selected_mark_{{ $i_criteria }}_{{ $i }}' name='mark[{{$i_criteria}}][{{$i}}]' value="0"  maxlength="2"
                                                                                data-criteria-index='{{ $i_criteria }}' data-subcriteria-index='{{ $i }}'>
                                                                        </div>
                                                                    </div>
                                                                @endforeach
                                                            @endif
                                                        @endif

                                                    </td>
                                                </tr>
                                                @endif
                                            @endforeach

                                            <tr id="total_mark" >
                                                <td class="text-center" colspan="2"> <b>Jumlah Markah</b> </td>
                                                <td>
                                                    <div class="d-flex" >
                                                        <div class="flex-fill" style="width:{{ $width_remarks }}"></div>
                                                        <div class="flex-fill">
                                                            <input type='text' id="final_full_mark" class='form-control full_mark' style="width:{{$width_mark}}" value="{{ $criteriaArr['total']['total_full_mark'] }}" >
                                                        </div>
                                                        <div class="flex-fill">
                                                            <input type='hidden' id="current_total_mark" value="{{ $total_mark }}" >
                                                            <input type='text' id="final_total_mark" class='form-control mark' style="width:{{$width_mark}}" value="{{ $total_mark }}">
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="mt-3 mb-3 row">
                                <label for="name" class="col-md-2 col-form-label">Ulasan</label>
                                <div class="col-md-6">
                                    <textarea class="form-control @error('remarks') is-invalid @enderror" rows="3" name="remarks" data-parsley-required-message="{{ setMessage('remarks.required') }}"></textarea>
                                    @error('remarks') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label for="officer_approval" class="col-md-2 col-form-label ">Pegawai Penyemak</label>
                                <div class="col-md-6">
                                    <select class="form-control select2 @error('officer_approval') is-invalid @enderror" id="officer_approval" name="officer_approval" value="{{ old('officer_approval') }}"
                                        required data-parsley-required-message="{{ setMessage('officer_approval.required') }}"
                                        data-parsley-errors-container="#parsley-errors-officer-approval">
                                        <option value="">-- Pilih Pegawai --</option>
                                        @foreach($officerApproval as $officer)
                                            <option value="{{ $officer->id }}" >{{ $officer->user->name }}</option>
                                        @endforeach
                                    </select>
                                    <div id="parsley-errors-officer-approval"></div>
                                    @error('officer_approval')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-3 row">
                                <div class="col-sm-12">
                                    <button type="submit" class="btn btn-primary float-end swal-hantar">{{ __('button.hantar') }}</button>
                                    <a href="{{ route('applicationScoring.index'). '#' . $tab  }}" class="btn btn-secondary float-end me-2">{{ __('button.kembali') }}</a>
                                </div>
                            </div>

                        </div>

                        <div class="tab-pane" id="application_info" role="tabpanel">

                            @include('modules.ApplicationReview.ApplicationReviewInfo.view-application-info')

                            <div class="mb-3 row">
                                <div class="col-sm-12">
                                    <a href="{{ route('applicationScoring.index') }}" class="btn btn-secondary float-end me-2">{{ __('button.kembali') }}</a>
                                </div>
                            </div>

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
<script src="{{ URL::asset('assets/js/pages/ApplicationScoring/ApplicationScoring.js')}}"></script>
@endsection
