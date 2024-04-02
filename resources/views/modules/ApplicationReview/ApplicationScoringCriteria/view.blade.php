@extends('layouts.master')

@section('content')

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">

                {{-- PAGE TITLE --}}
                <div class="border-bottom border-primary mb-4"><h4 class="card-title">{{ getPageTitle(1) }}</h4></div>

                <form method="post" action="{{ route('applicationScoringCriteria.store') }}" >
                    {{ csrf_field() }}

                    <div class="mb-3 row section-input-disabled">
                        <div class="mb-2 row">
                            <label for="description" class="col-md-2 col-form-label">Keterangan</label>
                            <div class="col-md-4">
                                <input class="form-control" type="text" id="description" name="description" value="{{ $scoringScheme->description }}">
                            </div>
                        </div>


                        <div class="mb-2 row">
                            <label for="execution_date" class="col-md-2 col-form-label">Tarikh Kuatkuasa</label>
                            <div class="col-md-4">
                                <input class="form-control" type="date" id="execution_date" name="execution_date" value="{{ $scoringScheme->execution_date ->format('Y-m-d') }}" >
                            </div>
                        </div>
                    </div>

                    <div class="col-12 section-input-disabled">
                        <table id="table-criteria" class="table table-sm table-bordered table-criteria-list" >
                            @php
                                $width_remarks = "90%";
                                $width_operator = "130px";
                                $width_no = "90px";
                                $width_mark = "50px";
                            @endphp
                            <thead class="bg-primary bg-gradient text-white">
                                <tr role="row">
                                    <th class="text-center">Bil</th>
                                    <th width="40%">Kriteria</th>
                                    <th colspan="2">
                                        <div class="d-flex">
                                            <div class="flex-fill" style="width:{{ $width_remarks }}">Kenyataan</div>
                                            <div class="flex-fill">Markah</div>
                                        </div>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>

                                @foreach($scoringCriteriaAll as $i_criteria => $scoringCriteria)

                                    @php $scoring_criteria_id = $scoringCriteria->id; @endphp

                                    <tr data-criteria-index="{{ $i_criteria }}" @class(['row-odd' => $loop->odd, 'row-even' => $loop->even]) >
                                        <td class="text-center align-top" >{{ $loop->iteration }}</td>
                                        <td class="align-top" >{{ $scoringCriteria->criteria_name }}
                                            @if($scoringCriteria->calculation_method==1)
                                                <div class="form-check mb-2" >
                                                    <input class="form-check-input calculation_method" type="checkbox" @if($scoringCriteria->calculation_method==1) checked @endif >
                                                    </input>
                                                    <label class="form-check-label" for="calculation_method" > Pengiraan Auto - {{ $scoringCriteria->scoringMappingHrmis->mapping_name ?? ''}}</label>
                                                </div>
                                            @endif
                                        </td>
                                        <td>
                                            @php $flag_table = $scoringCriteria->scoring_mapping_hrmis_id; @endphp
                                            @if(isset($scoringCriteria->scoringMappingHrmis->is_dropdown) && $scoringCriteria->scoringMappingHrmis->is_dropdown==1)

                                                @if($flag_table == 1)
                                                    <div class="services_type">
                                                        @foreach($servicesTypeAll as $i => $servicesType)
                                                            @php
                                                                $mark = $subCriteriaArr[$scoring_criteria_id][$i]['mark'] ?? '0' ;
                                                            @endphp
                                                            <div class="d-flex services_type_row {{ $loop->last=='' ? 'border-bottom' : '' }} ">
                                                                <div class="flex-fill" style="width:{{ $width_remarks }}"> {{ $servicesType -> services_type }}</div>
                                                                <div class="flex-fill"> <input type='text' class='form-control mark' id='mark_{{ $i_criteria }}_{{ $i }}' style="width:{{$width_mark}}" value="{{ $mark }}" data-row-index="{{ $i }}"> </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                @elseif($flag_table == 2)
                                                    <div class="position_type">
                                                        @foreach($positionTypeAll as $i => $positionType)
                                                            @php
                                                                $mark = $subCriteriaArr[$scoring_criteria_id][$i]['mark'] ?? '0' ;
                                                            @endphp
                                                            <div class="d-flex position_type_row {{ $loop->last=='' ? 'border-bottom' : '' }} ">
                                                                <div class="flex-fill" style="width:{{ $width_remarks }}">
                                                                    {{ $positionType -> position_type }}
                                                                </div>
                                                                <div class="flex-fill" > <input type='text' class='form-control mark' id='mark_{{ $i_criteria }}_{{ $i }}' style="width:{{$width_mark}}" value="{{ $mark }}" data-row-index="{{ $i }}"> </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                @elseif($flag_table == 3)
                                                    <div class="marital_status">
                                                        @foreach($maritalStatusAll as $i => $maritalStatus)
                                                            @php
                                                                $mark = $subCriteriaArr[$scoring_criteria_id][$i]['mark'] ?? '0' ;
                                                            @endphp
                                                            <div class="d-flex marital_status_row {{ $loop->last=='' ? 'border-bottom' : '' }} ">
                                                                <div class="flex-fill" style="width:{{ $width_remarks }}">
                                                                    {{ $maritalStatus -> marital_status }}
                                                                </div>
                                                                <div class="flex-fill" > <input type='text' class='form-control mark' id='mark_{{ $i_criteria }}_{{ $i }}' style="width:{{$width_mark}}" value="{{ $mark }}" data-row-index="{{ $i }}"> </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                @endif

                                            @elseif(isset($scoringCriteria->scoringMappingHrmis->is_range) && $scoringCriteria->scoringMappingHrmis->is_range==1)

                                                @if(isset($subCriteriaArr[$scoring_criteria_id]))

                                                    @foreach($subCriteriaArr[$scoring_criteria_id] as $i => $subCriteria)
                                                        @php
                                                            $collective_noun = $scoringCriteria->scoringMappingHrmis->collective_noun ?? '';
                                                            $collective_noun_position = $scoringCriteria->scoringMappingHrmis->collective_noun_position ?? '';
                                                            $range_from = isset($subCriteria['range_from']) ? $subCriteria['range_from'] : '0';
                                                            $operator_id = isset($subCriteria['operator_id']) ? $subCriteria['operator_id'] : '0';
                                                            $range_to = isset($subCriteria['range_to']) ? $subCriteria['range_to'] : '0';
                                                            $mark = isset($subCriteria['mark']) ? $subCriteria['mark'] : '0';
                                                        @endphp

                                                        <div class="d-flex {{ $loop->last=='' ? 'border-bottom' : '' }}" >
                                                            <div class="flex-fill" style="width:{{ $width_remarks }}">

                                                                <table class="dt-responsive" >
                                                                    <tr >
                                                                        <td>
                                                                            @if($collective_noun_position == 'PRE') {{ $collective_noun }} @endif
                                                                            @if($collective_noun == 'RM') {{ numberFormatComma($range_from) }} @else {{ $range_from }} @endif
                                                                            @if($collective_noun_position == 'POST') {{ $collective_noun }} @endif
                                                                        </td>
                                                                        <td>
                                                                            @foreach($operatorAll as $operator)
                                                                                @if($operator->id == $operator_id && $operator->id <> 2) {{ $operator -> operator_name }}
                                                                                @elseif($operator->id == $operator_id && $operator->id == 2) {{ $operator -> operator_sign }}
                                                                                @endif
                                                                            @endforeach
                                                                        </td>
                                                                        <td>
                                                                            @if($range_to>0)
                                                                                @if($collective_noun_position == 'PRE') {{ $collective_noun }} @endif
                                                                                @if($collective_noun == 'RM') {{ numberFormatComma($range_to) }} @else {{ $range_to }} @endif
                                                                                @if($collective_noun_position == 'POST') {{ $collective_noun }} @endif
                                                                            @endif
                                                                        </td>
                                                                    </tr>
                                                                </table>

                                                            </div>
                                                            <div class="flex-fill" ><input type='text' class='form-control mark' id='mark_{{ $i_criteria }}_{{ $i }}' style="width:{{$width_mark}}" value="{{ $mark }}" data-row-index="{{ $i }}"> </div>
                                                        </div>
                                                    @endforeach
                                                @endif

                                            @else

                                                @if(isset($subCriteriaArr[$scoring_criteria_id]))

                                                    @foreach($subCriteriaArr[$scoring_criteria_id] as $i => $subCriteria)
                                                        @php
                                                            $remarks = isset($subCriteria['remarks']) ? $subCriteria['remarks'] : '';
                                                            $mark = isset($subCriteria['mark']) ? $subCriteria['mark'] : '0';
                                                        @endphp
                                                        <div class="d-flex {{ $loop->last=='' ? 'border-bottom' : '' }}"" >
                                                            <div class="flex-fill" style="width:{{ $width_remarks }}">{{ $remarks }}</div>
                                                            <div class="flex-fill" ><input type='text' class='form-control mark' id='mark_{{ $i_criteria }}_{{ $i }}' style="width:{{$width_mark}}" value="{{ $mark }}" data-row-index="{{ $i }}"> </div>
                                                        </div>
                                                    @endforeach
                                                @endif

                                            @endif

                                        </td>
                                    </tr>

                                @endforeach
                                <tr id="row_total_mark" >
                                    <td class="text-center" colspan="2"> <b>Jumlah Markah</b> </td>
                                    <td>
                                        <div class="d-flex" >
                                            <div class="flex-fill" style="width:{{ $width_remarks }}"></div>
                                            <div class="flex-fill">
                                                <input type='text' id="total_mark" class='form-control' style="width:{{$width_mark}}" value="">
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="row">
                        <div class="col-sm-12">
                            <!-- <a href="{{ route('applicationScoringCriteria.edit', ['id' => $scoringScheme->id]) }}" class="btn btn-primary float-end">{{ __('button.kemaskini') }}</button> -->
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
