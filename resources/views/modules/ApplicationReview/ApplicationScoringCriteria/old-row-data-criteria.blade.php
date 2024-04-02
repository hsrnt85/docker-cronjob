
<tr id="tr-criteria-{{ $i_criteria }}" data-tr-index="{{ $i_criteria }}" @class(['row-odd' => $loop->odd, 'row-even' => $loop->even])>

    <td class="text-center align-top bil_criteria" ><span id='bil-criteria-{{ $i_criteria }}'>{{ $loop->iteration }}</span></td>
    <td class="align-top">
        <input type="hidden" name='scoring_criteria_id[{{ $i_criteria }}]' value="0">
        <input class="form-control criteria_name @error('criteria_name.'.$i_criteria) is-invalid @enderror" type="text" id="criteria_name{{ $i_criteria }}" name='criteria_name[{{ $i_criteria }}]' value="{{ old('criteria_name.'.$i_criteria) }}">
        <div class="form-check mb-2">
            <input class="form-check-input calculation_method" type="checkbox" name='calculation_method[{{ $i_criteria }}]' value="1"
                    @if(old('calculation_method.'.$i_criteria)==1) checked @endif>
            </input>
            <label class="form-check-label" for="calculation_method" > Pengiraan Auto ?</label>
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
                            @if(old('scoring_mapping_hrmis.'.$i_criteria)==$scoringMappingHrmis->id) selected @endif >
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
            @php  $i = 0; @endphp
            @foreach($servicesTypeAll as $i => $servicesType)
                <div class="row">
                    <div class="col-md-12 input-group  {{ $loop->last=='' ? 'border-bottom' : '' }} "">
                        <div class="col-md-10 mt-2">
                            <input type="hidden" name='item_id[{{ $i_criteria }}][{{ $i }}]' value="{{ $servicesType -> id }}"> {{ $servicesType -> services_type }}
                        </div>
                        <div class="col-md-1 mt-1 mb-1">
                            <input class='form-control mark @error('mark.'.$i_criteria.'.'.$i) is-invalid @enderror'
                                    type='text' style="width:{{$width_mark}}" name='mark[{{ $i_criteria }}][{{ $i }}]'
                                    oninput="checkNumber(this)" onfocus="focusInput(this);" maxlength="2" value="{{ old('mark.'.$i_criteria.'.'.$i) }}" >
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        {{-- end services_type --}}
        {{-- position_type --}}
        <div class="section-auto-dropdown position_type">
            @php  $i = 0; @endphp
            @foreach($positionTypeAll as $i => $positionType)
                <div class="row">
                    <div class="col-md-12 input-group  {{ $loop->last=='' ? 'border-bottom' : '' }} ">
                        <div class="col-md-10 mt-2">
                            <input type="hidden" name='item_id[{{ $i_criteria }}][{{ $i }}]' value="{{ $positionType -> id }}"> {{ $positionType -> position_type }}
                        </div>
                        <div class="col-md-1 mt-1 mb-1">
                            <input class='form-control mark @error('mark.'.$i_criteria.'.'.$i) is-invalid @enderror'
                                type='text' style="width:{{$width_mark}}" name='mark[{{ $i_criteria }}][{{ $i }}]' maxlength="2"
                                oninput="checkNumber(this)" onfocus="focusInput(this);" value="{{ old('mark.'.$i_criteria.'.'.$i) }}"
                                data-row-index="{{ $i }}">
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        {{-- end position_type --}}
        {{-- marital_status --}}
        <div class="section-auto-dropdown marital_status">
            @php  $i = 0; @endphp
            @foreach($maritalStatusAll as $i => $maritalStatus)
                <div class="row">
                    <div class="col-md-12 input-group  {{ $loop->last=='' ? 'border-bottom' : '' }} ">
                        <div class="col-md-10 mt-2">
                            <input type="hidden" name='item_id[{{ $i_criteria }}][{{ $i }}]' value="{{ $maritalStatus -> id }}"> {{ $maritalStatus -> marital_status }}
                        </div>
                        <div class="col-md-1 mt-1 mb-1">
                            <input class='form-control mark @error('mark.'.$i_criteria.'.'.$i) is-invalid @enderror'
                                type='text' style="width:{{$width_mark}}" name='mark[{{ $i_criteria }}][{{ $i }}]' maxlength="2"
                                oninput="checkNumber(this)" onfocus="focusInput(this);" value="{{ old('mark.'.$i_criteria.'.'.$i) }}"
                                data-row-index="{{ $i }}">
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        {{-- end marital_status --}}
        {{-- END DATA TYPE DROPDOWN ---------------------------------------------------------------------------------------------------------------------}}
        {{-- DATA TYPE RANGE ----------------------------------------------------------------------------------------------------------------------------}}
        <div class="section-auto-range">
            @if(old('range_from.'.$i_criteria) != null)

                @foreach (old('range_from.'.$i_criteria) as $i => $range_from)
                    <div class="div-row-subcriteria" id="div-row-subcriteria-{{ $i_criteria }}--{{ $i }}" data-criteria-index="{{ $i_criteria }}" data-row-index="{{ $i }}">
                        <div class="row">
                            <div class="col-md-12 input-group">
                                <div class="col-md-2 mt-1 p-1">
                                    <input type="hidden" id="flag-delete-subcriteria-{{ $i_criteria }}" name='flag_delete_subcriteria[{{ $i_criteria }}]' value="{{ old('flag_delete_subcriteria.'.$i_criteria.'.'.$i) }}">
                                    <input type="hidden" name='scoring_subcriteria_id[{{ $i_criteria }}][{{ $i }}]' value="{{ old('scoring_subcriteria_id.'.$i_criteria.'.'.$i) }}">
                                    <a id="btnAdd-{{ $i_criteria }}--{{ $i }}" class="btnDuplicateRowSubCriteria btn btn-success btn-sm" data-criteria-index="{{ $i_criteria }}" data-row-index="{{ $i }}"><i class="mdi mdi-plus mdi-16px"></i></a>
                                    <a id="btnRemoveSubCriteria-{{ $i_criteria }}--{{ $i }}" class='btnRemoveSubCriteria btn btn-danger btn-sm'><i class='mdi mdi-minus mdi-16px'></i></a>
                                </div>
                                <div class="col-md-2 p-1">
                                    <input type="text" class='form-control @error('range_from.'.$i_criteria.'.'.$i) is-invalid @enderror'
                                            name='range_from[{{ $i_criteria }}][{{ $i }}]'
                                            oninput="checkNumber(this)" onfocus="focusInput(this);" value="{{ old('range_from.'.$i_criteria.'.'.$i) }}">
                                </div>
                                <div class="col-md-4 p-1">
                                    <select class="form-select operator_id" name="operator_id[{{ $i_criteria }}][{{ $i }}]" >
                                        <option value="">TIADA</option>
                                        @foreach($operatorAll as $operator)
                                        <option value="{{ $operator -> id }}" @if(old('operator_id.'.$i_criteria.'.'.$i)==$operator->id) selected @endif >
                                            {{ $operator -> operator_name }}
                                        </option>
                                @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2 p-1">
                                    <input class='form-control range_to' type="text" name='range_to[{{ $i_criteria }}][{{ $i }}]' oninput="checkNumber(this)" onfocus="focusInput(this);">
                                </div>
                                <div class="col-md-1 p-1">
                                    <input class='form-control mark @error('mark.'.$i_criteria.'.'.$i) is-invalid @enderror'
                                            type='text' style="width:{{$width_mark}}" name='mark[{{ $i_criteria }}][{{ $i }}]' maxlength="2"
                                            oninput="checkNumber(this)" onfocus="focusInput(this);" value="{{ old('mark.'.$i_criteria.'.'.$i) }}"
                                            data-row-index="{{ $i }}">
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
            @php  $i = 0; @endphp
            @if(old('remarks.'.$i_criteria) != null)
                @foreach (old('remarks.'.$i_criteria) as $i => $remarks)
                    <div class="div-row-subcriteria-manual" id="div-row-subcriteria-manual-{{ $i_criteria }}--{{ $i }}" data-criteria-index="{{ $i_criteria }}" data-row-index="{{ $i }}">
                        <div class="row">
                            <div class="col-md-12 input-group">
                                <div class="col-md-2 mt-1 p-1">
                                    <a id="btnAdd-{{ $i_criteria }}--{{ $i }}" class="btnDuplicateRowSubCriteriaManual btn btn-success btn-sm" data-criteria-index="{{ $i_criteria }}" data-row-index="{{ $i }}"><i class="mdi mdi-plus mdi-16px"></i></a>
                                    <a id="btnRemoveSubCriteria-{{ $i_criteria }}--{{ $i }}" class='btnRemoveSubCriteria btn btn-danger btn-sm'><i class='mdi mdi-minus mdi-16px'></i></a>
                                </div>
                                <div class="col-md-8 p-1">
                                    <input type="text" class='form-control' name='remarks[{{ $i_criteria }}][{{ $i }}]' value="{{ old('remarks.'.$i_criteria.'.'.$i) }}">
                                </div>
                                <div class="col-md-1 p-1">
                                    <input type='text' class='form-control mark @error('mark.'.$i_criteria.'.'.$i) is-invalid @enderror'
                                            style="width:{{$width_mark}}" name='mark[{{ $i_criteria }}][{{ $i }}]' value="{{ old('mark.'.$i_criteria.'.'.$i) }}"
                                            oninput="checkNumber(this)" onfocus="focusInput(this);"  maxlength="2"
                                            data-row-index="{{ $i }}">
                                </div>
                            </div>{{-- end input-group --}}
                        </div>{{-- end row --}}
                    </div>{{-- end div-subcriteria-manual --}}
                @endforeach
            @endif

        </div>
        {{-- ENF SECTION PENGIRAAN MANUAL  ------------------------------------------------------------------------------------------------------------}}
    </td>
    {{-- BUTTON - REMOVE ROW CRITERIA --}}
    <td class="align-top text-center">
        <input type="hidden" id="flag-delete-criteria-{{ $i_criteria }}" name='flag_delete_criteria[{{ $i_criteria }}]' value="{{ old('flag_delete_criteria.'.$i_criteria.'.'.$i) }}">
        <a id="btnRemoveCriteria-{{ $i_criteria }}" class='btnRemoveCriteria btn btn-warning btn-sm' data-criteria-index="{{ $i_criteria }}"><i class='mdi mdi-minus mdi-18px'></i></a>
    </td>
</tr>

