
<form id="form-edit-application-scoring" action="">

    <input type="hidden" name="application_id" value="{{ $application->id }}">
    @if(isset($quarters_category_id))  <input type="hidden" name="qcid" value="{{ $quarters_category_id }}">  @endif

    <div class="table-responsive col-sm-12 mt-3">
        <table class="table dt-responsive " >
            @php
                $width_remarks = "40%";
                $height_mark = "40px";
                $width_mark = "50px";
            @endphp
            <thead class="bg-primary bg-gradient text-white">
                <tr role="row">
                    <th class="text-center">Bil</th>
                    <th width="38%">Kriteria</th>
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

                @php
                    $total_mark = $total_full_mark = 0;
                    $i_criteria = 0;
                @endphp
                @foreach($applicationScoringInfo->groupby('criteria_name')->toArray() as $criteria)

                    @php
                        $criteria_name = $criteria[0]['criteria_name'] ?? '';
                        $criteria_id = $criteria[0]['id'] ?? '0';
                        $flag_edit = $criteria[0]['flag_edit'];
                        $flag_disabled = ($flag_edit==1) ? '' : 'disabled';
                        $flag_auto_dropdown = $criteria[0]['flag_auto_dropdown'];//mark reorder
                    @endphp

                    <tr data-criteria-index='{{ $i_criteria }}' @class(['row-odd' => $loop->odd, 'row-even' => $loop->even]) >
                        <td class="text-center align-top" > {{ $loop->iteration }} </td>
                        <td class="align-top" > {{ $criteria_name }} </td>
                        <td>
                            @for($i=0; $i<count($criteria); $i++)
                                @php
                                    $subcriteria_name = $criteria[$i]['subcriteria_name'] ?? '';
                                    $mark = $criteria[$i]['mark'] ?? 0;
                                    $full_mark = $criteria[$i]['full_mark'] ?? 0;
                                    if($i==0) $total_full_mark += $full_mark;
                                    $total_mark += $mark;
                                @endphp
                                <div class="subcriteria_name" data-criteria-index='{{ $i_criteria }}' data-flag-auto-dropdown='{{ $flag_auto_dropdown }}' >
                                    <div class="d-flex subcriteria_name_row_{{ $i_criteria }} {{ $loop->last!='' ? 'border-bottom' : '' }} ">
                                        <div class="flex-fill" style="width:{{ $width_remarks }}"> {{ $subcriteria_name }}</div>
                                        <div class="flex-fill" style="width:{{ $width_mark }}">
                                            <input class="form-control full_mark" style="width:{{$width_mark}}" value="{{ $full_mark }}" id='full_mark_{{ $i_criteria }}_{{ $i }}' data-row-index="{{ $i }}"  readonly>
                                        </div>
                                        <div class="flex-fill" style="line-height:{{ $height_mark }};width:{{ $width_mark }}">
                                            @if($mark > 0 || $flag_edit==1)
                                                <input class="form-control mark" style="width:{{$width_mark}}" name="mark[]" oninput="checkNumber(this)" onfocus="focusInput(this);" value="{{ $mark }}"  maxlength="2"
                                                    data-criteria-index='{{ $i_criteria }}' data-subcriteria-index='{{ $i }}' {{ $flag_disabled }}>
                                                <input type="hidden" name="scoring_criteria_id[]" value="{{ $criteria_id }}" {{ $flag_disabled }}>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endfor
                        </td>
                    </tr>
                    @php  $i_criteria++ @endphp
                @endforeach

                <tr id="total_mark">
                    <td class="text-center" colspan="2"> <b>Jumlah Markah</b> </td>
                    <td>
                        <div class="d-flex" >
                            <div class="flex-fill" style="width:{{ $width_remarks }}"></div>
                            <div class="flex-fill"><input class="form-control" id="final_full_mark" style="width:{{$width_mark}}" value="{{ $total_full_mark }}" readonly> </div>
                            <div class="flex-fill"><input class="form-control" id="final_total_mark" style="width:{{$width_mark}}" value="{{ $total_mark }}" readonly> </div>
                        </div>
                    </td>
                </tr>

            </tbody>
        </table>
    </div>

    <div class="mb-3 row">
        <div class="col-sm-12">
            <button type="submit" class="btn btn-primary float-end swal-kemaskini-markah">{{ __('button.kemaskini') }}</button>
        </div>
    </div>

</form>

@section('script')
<script type="text/javascript" src="{{ URL::asset('assets/js/pages/EvaluationMeeting/meetingApplicationScoring.js')}}"> </script>
@endsection
