
@php $scoring_criteria_id = $scoringCriteria->id ?? ''; @endphp

<tr id="tr-criteria-{{ $i_criteria }}" data-tr-index="{{ $i_criteria }}" @class(['row-odd' => $loop->odd, 'row-even' => $loop->even]) >

    <td class="text-center align-top bil_criteria" ><span id='bil-criteria-{{ $i_criteria }}'>{{ $loop->iteration }}</span></td>
    <td class="align-top" >
        <input type="hidden" name='scoring_criteria_id[{{ $i_criteria }}]' value="{{ $scoring_criteria_id }}">
        <input class="form-control criteria_name @error('criteria_name.'.$i_criteria) is-invalid @enderror" type="text" id="criteria_name{{ $i_criteria }}" name='criteria_name[{{ $i_criteria }}]' value="{{ $scoringCriteria->criteria_name ?? '' }}">
        <div class="form-check mb-2" >
            <input class="form-check-input calculation_method" type="checkbox" name='calculation_method[{{ $i_criteria }}]' value="1"
                    @if(($scoringCriteria->calculation_method ?? '0')==1) checked @endif >
            </input>
            <label class="form-check-label" for="calculation_method" > Pengiraan Auto ? </label>
        </div>
        <div class="section-data-hrmis col-md-8">
            <select class="form-select scoring_mapping_hrmis" name="scoring_mapping_hrmis[{{ $i_criteria }}]" >
                <option value="">-- Pilih --</option>
                    @foreach($scoringMappingHrmisAll as $scoringMappingHrmis)
                        <option value="{{ $scoringMappingHrmis->id }}"
                            data-is-dropdown="{{$scoringMappingHrmis->is_dropdown}}"
                            data-is-range="{{$scoringMappingHrmis->is_range}}"
                            data-section="{{$scoringMappingHrmis->dropdown_table}}"
                            data-mapping-name="{{ $scoringMappingHrmis->mapping_name }}"
                            @if(($scoringCriteria->scoring_mapping_hrmis_id ?? '0')==$scoringMappingHrmis->id) selected @endif >
                            {{ $scoringMappingHrmis->mapping_name }} @if($scoringMappingHrmis->collective_noun) {{ '( '.$scoringMappingHrmis->collective_noun.' )' }} @endif
                        </option>
                    @endforeach
            </select>
        </div>
    </td>
    <td class="align-top">
        {{-- SECTION PENGIRAAN AUTO --------------------------------------------------------------------------------------------------------------------}}
        {{-- DATA TYPE DROPDOWN ------------------------------------------------------------------------------------------------------------------------}}
        {{-- services_type --}}
        <div class="section-auto-dropdown services_type">
            @foreach($servicesTypeAll as $i_subcriteria => $servicesType)
                @php
                    $scoring_subcriteria_id = isset($subCriteriaArr[$i_criteria][$i_subcriteria]['subcriteria_id']) ? $subCriteriaArr[$i_criteria][$i_subcriteria]['subcriteria_id'] : '0';
                    $mark = $subCriteriaArr[$i_criteria][$i_subcriteria]['mark'] ?? '0' ;
                @endphp
                <div class="row services_type_row">
                    <div class="col-md-12 input-group  {{ $loop->last=='' ? 'border-bottom' : '' }} "">
                        <input type="hidden" name='scoring_subcriteria_id[{{ $i_criteria }}][{{ $i_subcriteria }}]' value="{{ $scoring_subcriteria_id }}">
                        <div class="col-md-10 mt-2">
                            <input type="hidden" name='item_id[{{ $i_criteria }}][{{ $i_subcriteria }}]' value="{{ $servicesType -> id }}"> {{ $servicesType -> services_type }}
                        </div>
                        <div class="col-md-1 mt-1 mb-1">
                            <input type='text' class='form-control mark @error('mark.'.$i_criteria.'.'.$i_subcriteria) is-invalid @enderror'
                                    name='mark[{{ $i_criteria }}][{{ $i_subcriteria }}]' style="width:{{$width_mark}}"
                                    oninput="checkNumber(this)" onfocus="focusInput(this);" maxlength="2" value="{{ $mark }}"
                                    data-row-index="{{ $i_subcriteria  }}">
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        {{-- end services_type --}}
        {{-- position_type --}}
        <div class="section-auto-dropdown position_type">
            @foreach($positionTypeAll as $i_subcriteria => $positionType)
                @php
                    $scoring_subcriteria_id = isset($subCriteriaArr[$i_criteria][$i_subcriteria]['subcriteria_id']) ? $subCriteriaArr[$i_criteria][$i_subcriteria]['subcriteria_id'] : '0';
                    $mark = $subCriteriaArr[$i_criteria][$i_subcriteria]['mark'] ?? '0' ;
                @endphp
                <div class="row position_type_row">
                    <div class="col-md-12 input-group  {{ $loop->last=='' ? 'border-bottom' : '' }} ">
                        <input type="hidden" name='scoring_subcriteria_id[{{ $i_criteria }}][{{ $i_subcriteria }}]' value="{{ $scoring_subcriteria_id }}">
                        <div class="col-md-10 mt-2">
                            <input type="hidden" name='item_id[{{ $i_criteria }}][{{ $i_subcriteria }}]' value="{{ $positionType -> id }}"> {{ $positionType -> position_type }}
                        </div>
                        <div class="col-md-1 mt-1 mb-1">
                            <input type='text' class='form-control mark  @error('mark.'.$i_criteria.'.'.$i_subcriteria) is-invalid @enderror'
                                    name='mark[{{ $i_criteria }}][{{ $i_subcriteria }}]' style="width:{{$width_mark}}"
                                    oninput="checkNumber(this)" onfocus="focusInput(this);" maxlength="2" value="{{ $mark }}"
                                    data-row-index="{{ $i_subcriteria  }}">
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        {{-- end position_type --}}
        {{-- marital_status --}}
        <div class="section-auto-dropdown marital_status">
            @foreach($maritalStatusAll as $i_subcriteria => $maritalStatus)
                @php
                    $scoring_subcriteria_id = isset($subCriteriaArr[$i_criteria][$i_subcriteria]['subcriteria_id']) ? $subCriteriaArr[$i_criteria][$i_subcriteria]['subcriteria_id'] : '0';
                    $mark = $subCriteriaArr[$i_criteria][$i_subcriteria]['mark'] ?? '0' ;
                @endphp
                <div class="row marital_status_row">
                    <div class="col-md-12 input-group  {{ $loop->last=='' ? 'border-bottom' : '' }} ">
                        <input type="hidden" name='scoring_subcriteria_id[{{ $i_criteria }}][{{ $i_subcriteria }}]' value="{{ $scoring_subcriteria_id }}">
                        <div class="col-md-10 mt-2">
                            <input type="hidden" name='item_id[{{ $i_criteria }}][{{ $i_subcriteria }}]' value="{{ $maritalStatus -> id }}"> {{ $maritalStatus -> marital_status }}
                        </div>
                        <div class="col-md-1 mt-1 mb-1">
                            <input type='text' class='form-control mark @error('mark.'.$i_criteria.'.'.$i_subcriteria) is-invalid @enderror'
                                    name='mark[{{ $i_criteria }}][{{ $i_subcriteria }}]' style="width:{{$width_mark}}"
                                    oninput="checkNumber(this)" onfocus="focusInput(this);" maxlength="2" value="{{ $mark }}"
                                    data-row-index="{{ $i_subcriteria  }}">
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        {{-- end marital_status --}}
        {{-- END DATA TYPE DROPDOWN ---------------------------------------------------------------------------------------------------------------------}}
        {{-- DATA TYPE RANGE ----------------------------------------------------------------------------------------------------------------------------}}
        <div class="section-auto-range">
            @if(isset($subCriteriaArr[$i_criteria]))
                @foreach($subCriteriaArr[$i_criteria] as $i_subcriteria => $subCriteria)
                    @php
                        $scoring_subcriteria_id = isset($subCriteria['subcriteria_id']) ? $subCriteria['subcriteria_id'] : '0';
                        $range_from = isset($subCriteria['range_from']) ? $subCriteria['range_from'] : '0';
                        $operator_id = isset($subCriteria['operator_id']) ? $subCriteria['operator_id'] : '0';
                        $range_to = (isset($subCriteria['range_to']) && ($subCriteria['range_to']>0)) ? $subCriteria['range_to'] : '';
                        $mark = isset($subCriteria['mark']) ? $subCriteria['mark'] : '0';
                    @endphp
                    <div class="div-row-subcriteria" id="div-row-subcriteria-{{ $i_criteria }}--{{ $i_subcriteria }}" data-criteria-index="{{ $i_criteria }}" data-row-index="{{ $i_subcriteria }}">
                        <div class="row">
                            <div class="col-md-12 input-group">
                                <div class="col-md-2 mt-1 p-1">
                                    <input type="hidden" name='scoring_subcriteria_id[{{ $i_criteria }}][{{ $i_subcriteria }}]' value="{{ $scoring_subcriteria_id }}">
                                    <input type="hidden" id="flag-delete-subcriteria-{{ $i_criteria }}--{{ $i_subcriteria }}" name="flag_delete_subcriteria[{{ $i_criteria }}]" value="0">
                                    <a id="btnAdd-{{ $i_criteria }}--{{ $i_subcriteria }}" class="btnDuplicateRowSubCriteria btn btn-success btn-sm" data-criteria-index="{{ $i_criteria }}"  data-row-index="{{ $i_subcriteria }}"><i class="mdi mdi-plus mdi-16px"></i></a>
                                    <a id="btnRemoveSubCriteria-{{ $i_criteria }}--{{ $i_subcriteria }}" class='btnRemoveSubCriteria btn btn-danger btn-sm' data-criteria-index="{{ $i_criteria }}" data-subcriteria-index="{{ $i_subcriteria }}" data-subcriteria-id="{{ $scoring_subcriteria_id }}"><i class='mdi mdi-minus mdi-16px'></i></a>
                                </div>
                                <div class="col-md-2 p-1">
                                    <input type="text" class='form-control @error('range_from.'.$i_criteria.'.'.$i_subcriteria) is-invalid @enderror' name='range_from[{{ $i_criteria }}][{{ $i_subcriteria }}]'
                                            oninput="checkNumber(this)" onfocus="focusInput(this);" value="{{ $range_from }}" >
                                </div>
                                <div class="col-md-4 p-1">
                                    <select class="form-select operator_id" name="operator_id[{{ $i_criteria }}][{{ $i_subcriteria }}]" >
                                        <option value="">TIADA</option>
                                        @foreach($operatorAll as $operator)
                                            <option value="{{ $operator -> id }}" @if($operator_id==$operator->id) selected @endif>{{ $operator -> operator_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2 p-1">
                                    <input type="text" class='form-control range_to' name='range_to[{{ $i_criteria }}][{{ $i_subcriteria }}]'
                                            oninput="checkNumber(this)" onfocus="focusInput(this);" value="{{ $range_to }}">
                                </div>
                                <div class="col-md-1 p-1">
                                    <input class='form-control mark @error('mark.'.$i_criteria.'.'.$i_subcriteria) is-invalid @enderror' type='text' style="width:{{$width_mark}}" name='mark[{{ $i_criteria }}][{{ $i_subcriteria }}]'
                                            oninput="checkNumber(this)" onfocus="focusInput(this);" maxlength="2" value="{{ $mark }}"
                                            data-row-index="{{ $i_subcriteria  }}">
                                </div>
                            </div>{{-- end input-group --}}
                        </div>{{-- end row --}}
                    </div>{{-- end div-subcriteria --}}
                @endforeach
            @endif
        </div>
        {{-- ENF DATA TYPE RANGE ----------------------------------------------------------------------------------------------------------------------}}
        {{-- ENF SECTION PENGIRAAN AUTO  --------------------------------------------------------------------------------------------------------------}}

        {{-- SECTION PENGIRAAN MANUAL -----------------------------------------------------------------------------------------------------------------}}
        <div class="section-manual">
            @if(isset($subCriteriaArr[$i_criteria]))
                @foreach($subCriteriaArr[$i_criteria] as $i_subcriteria => $subCriteria)
                    @php
                        $scoring_subcriteria_id = isset($subCriteria['subcriteria_id']) ? $subCriteria['subcriteria_id'] : '0';
                        $remarks = isset($subCriteria['remarks']) ? $subCriteria['remarks'] : '';
                        $mark = isset($subCriteria['mark']) ? $subCriteria['mark'] : '0';
                    @endphp
                    <div class="div-row-subcriteria-manual" id="div-row-subcriteria-manual-{{ $i_criteria }}--{{ $i_subcriteria }}" data-criteria-index="{{ $i_criteria }}" data-row-index="{{ $i_subcriteria }}">
                        <div class="row">
                            <div class="col-md-12 input-group">
                                <div class="col-md-2 mt-1 p-1">
                                    <input type="hidden" name='scoring_subcriteria_id[{{ $i_criteria }}][{{ $i_subcriteria }}]' value="{{ $scoring_subcriteria_id }}">
                                    <a id="btnAdd-{{ $i_criteria }}--{{ $i_subcriteria }}" class="btnDuplicateRowSubCriteriaManual btn btn-success btn-sm" data-criteria-index="{{ $i_criteria }}" data-row-index="{{ $i_subcriteria }}"><i class="mdi mdi-plus mdi-16px"></i></a>
                                    <a id="btnRemoveSubCriteria-{{ $i_criteria }}--{{ $i_subcriteria }}" class='btnRemoveSubCriteria btn btn-danger btn-sm' data-criteria-index="{{ $i_criteria }}" data-subcriteria-index="{{ $i_subcriteria }}" data-subcriteria-id="{{ $scoring_subcriteria_id }}"><i class='mdi mdi-minus mdi-16px'></i></a>
                                </div>
                                <div class="col-md-8 p-1">
                                    <input type="text" class='form-control' name='remarks[{{ $i_criteria }}][{{ $i_subcriteria }}]' value="{{ $remarks }}">
                                </div>
                                <div class="col-md-1 p-1">
                                    <input class='form-control mark @error('mark.'.$i_criteria.'.'.$i_subcriteria) is-invalid @enderror'
                                        type='text' style="width:{{$width_mark}}" name='mark[{{ $i_criteria }}][{{ $i_subcriteria }}]'
                                        oninput="checkNumber(this)" onfocus="focusInput(this);" maxlength="2" value="{{ $mark }}"
                                        data-row-index="{{ $i_subcriteria  }}">
                                </div>
                            </div>{{-- end input-group --}}
                        </div>{{-- end row --}}
                    </div>{{-- end div-subcriteria-manual --}}
                @endforeach
            @else

                @php  $i = 0; @endphp
                <div class="div-row-subcriteria-manual" id="div-row-subcriteria-manual-{{ $i_criteria }}--{{ $i }}" data-criteria-index="{{ $i_criteria }}" data-row-index="{{ $i }}">
                    <div class="row">
                        <div class="col-md-12 input-group">
                            <div class="col-md-2 mt-1 p-1">
                                <a id="btnAdd-{{ $i_criteria }}--{{ $i }}" class="btnDuplicateRowSubCriteriaManual btn btn-primary btn-sm" data-criteria-index="{{ $i_criteria }}" data-row-index="{{ $i }}"><i class="mdi mdi-plus mdi-16px"></i></a>
                                <a id="btnRemoveSubCriteria-{{ $i_criteria }}--{{ $i }}" class='btnRemoveSubCriteria btn btn-danger btn-sm'><i class='mdi mdi-minus mdi-16px'></i></a>
                            </div>
                            <div class="col-md-8 p-1">
                                <input type="text" class='form-control' name='remarks[{{ $i_criteria }}][{{ $i }}]' >
                            </div>
                            <div class="col-md-1 p-1">
                                <input type='text' class='form-control mark' style="width:{{$width_mark}}" name='mark[{{ $i_criteria }}][{{ $i }}]'
                                        oninput="checkNumber(this)" onfocus="focusInput(this);" value="" maxlength="2"
                                        data-row-index="{{ $i }}">
                            </div>
                        </div>{{-- end input-group --}}
                    </div>{{-- end row --}}
                </div>{{-- end div-subcriteria-manual --}}

            @endif
        </div>
        {{-- END SECTION PENGIRAAN MANUAL  ------------------------------------------------------------------------------------------------------------}}
    </td>
     {{-- BUTTON - REMOVE ROW CRITERIA --}}
    <td class="align-top text-center">
        <input type="hidden" id="flag-delete-criteria-{{ $i_criteria }}" name='flag_delete_criteria[{{ $i_criteria }}]' value="0">
        <a id="btnRemoveCriteria-{{ $i_criteria }}" class='btnRemoveCriteria btn btn-warning btn-sm' data-criteria-index="{{ $i_criteria }}" data-criteria-id="{{ $scoring_criteria_id }}"><i class='mdi mdi-minus mdi-18px'></i></a>
    </td>

</tr>


