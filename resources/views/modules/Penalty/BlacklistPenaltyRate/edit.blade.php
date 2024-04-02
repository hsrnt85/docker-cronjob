@extends('layouts.master')
@section('css')
    <link href="{{ URL::asset('/assets/libs/bootstrap-datepicker/bootstrap-datepicker.min.css') }}" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="{{ URL::asset('/assets/libs/datepicker/datepicker.min.css') }}">
@endsection
@section('content')

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body" style="width:100%">

                    {{-- PAGE TITLE --}}
                    <div class="border-bottom border-primary mb-4">
                        <h4 class="card-title">{{ getPageTitle(1) }}</h4>
                    </div>

                    <form class="custom-validation" id="form" method="post" action="{{ route('blacklistPenaltyRate.update') }}" >
                        {{ csrf_field() }}

                        

                        <input type="hidden" name="bpr_id" value="{{ $bpr->id }}">
                        <div class="mb-3 row">
                            <label for="year" class="col-md-2 col-form-label">Tarikh Kuatkuasa</label>
                            <div class="col-md-6">
                                <div class="input-group " id="datepicker2">
                                    <input class="form-control @error('eff_date') is-invalid @enderror" type="text" placeholder="dd/mm/yyyy" name="eff_date" id="eff_date"
                                        data-date-format="dd/mm/yyyy" data-date-container='#datepicker2' data-provide="datepicker" autocomplete="off" data-date-autoclose="true"
                                        required data-parsley-required-message="{{ setMessage('date_search.required') }}" data-parsley-errors-container="#errorDate"
                                        value="{{ old('eff_date', convertDateSys($bpr->effective_date)) }}">
                                    <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>

                                    @error('eff_date')
                                        <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div id="errorDate"></div>
                            </div>
                        </div>

                        <div class="mb-3 row">
                            
                            <label for="year" class="col-md-2 col-form-label">Keterangan</label>
                            <div class="col-md-6">
                                <input type="text" class="form-control @error('description') is-invalid @enderror" id="description" name="description" required
                                    data-parsley-required-message="{{ setMessage('description.required') }}" value="{{ old('description', $bpr->description) }}">

                                @error('description')
                                    <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-12">
                            <table class="table table-sm table-bordered" id="table-rate">
                                <thead class="text-center bg-primary bg-gradient text-white">
                                    <th>Bil</th>
                                    <th>Tempoh Kiraan Denda Hilang Kelayakan (Bulan)</th>
                                    <th>Kadar Denda (%)</th>
                                    <th width="5%" class="text-center">
                                        <a class="btn btn-success btn-sm" id="duplicateRowEdit"><i class="mdi mdi-plus mdi-18px"></i></a>
                                    </th>
                                </thead>
                                <tbody>

                                    @if (old('range_from', null) != null)
                                        @foreach (old('range_from') as $ind => $old_range_from)
                                            <tr class="text-center">
                                                <input type="hidden" name="rate_id[]" value="{{ old('rate_id.' . $ind, null) }}">
                                                <input type="hidden" name="is_edit[]" value="{{ old('is_edit.' . $ind, 0) }}">
                                                <td>{{ $loop->iteration }}</td>
                                                <td>
                                                    <div class="row justify-content-md-center">
                                                        <div class="col-md-2 p-1">
                                                            <input type="text" class="form-control text-center @error('range_from.' . $ind) is-invalid @enderror" name="range_from[]"
                                                                oninput="checkNumber(this)" onfocus="focusInput(this);" required
                                                                data-parsley-required-message="{{ setMessage('month.required') }}" value="{{ $old_range_from }}">

                                                            @error('range_from.' . $ind)
                                                                <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                                            @enderror
                                                        </div>
                                                        <div class="col-md-4 p-1">
                                                            <select class="form-select text-center operator_id @error('operator_id.' . $ind) is-invalid @enderror" name="operator_id[]"
                                                                required data-parsley-required-message="{{ setMessage('operator.required') }}">
                                                                <option value="">-- SILA PILIH --</option>
                                                                @foreach ($operatorAll as $operator)
                                                                    <option value="{{ $operator->id }}" @if ($operator->id == old('operator_id.' . $ind)) selected @endif>
                                                                        {{ $operator->operator_name }}</option>
                                                                @endforeach
                                                            </select>

                                                            @error('operator_id.' . $ind)
                                                                <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                                            @enderror
                                                        </div>
                                                        <div class="col-md-2 p-1">
                                                            <input class="form-control text-center range_to @error('range_to.' . $ind) is-invalid @enderror" type="text"
                                                                name="range_to[]" oninput="checkNumber(this)" onfocus="focusInput(this);" required
                                                                data-parsley-required-message="{{ setMessage('month.required') }}" value="{{ old('range_to.' . $ind, null) }}">

                                                            @error('range_to.' . $ind)
                                                                <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                                            @enderror
                                                        </div>
                                                        <div>
                                                            <span class="col-md-8 d-none range-error-msg" style="color:#f46a6a">{{ setMessage('range-error-msg') }}</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="row justify-content-md-center">
                                                        <div class="col-md-3">
                                                            <input type="text" class="form-control text-center @error('rate.' . $ind) is-invalid @enderror" name="rate[]"
                                                                id="rate" oninput="checkNumber(this)" required
                                                                data-parsley-required-message="{{ setMessage('rate.required') }}" value="{{ old('rate.' . $ind) }}">

                                                            @error('rate.' . $ind)
                                                                <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="align-top text-center">
                                                    @if (old('rate_id.' . $ind))
                                                        <a class="btnDeleteRate btn btn-danger btn-sm swal-delete-list"><i class="mdi mdi-minus mdi-18px"></i></a>
                                                        <form method="POST" action="{{ route('blacklistPenaltyRate.destroyRate') }}" class="delete-form-list">
                                                            {{ csrf_field() }}
                                                            <input class="form-control" type="hidden" name="id" value="{{ old('rate_id.' . $ind) }}">
                                                        </form>
                                                    @else
                                                        <a id="btnRemoveCriteria-1" class="btnRemoveCriteria btn btn-warning btn-sm"><i class="mdi mdi-minus mdi-18px"></i></a>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        @foreach ($rates as $rate)
                                            <tr class="text-center">
                                                <input type="hidden" name="rate_id[]" value="{{ $rate->id }}">
                                                <input type="hidden" name="is_edit[]" value="1">
                                                <td>{{ $loop->iteration }}</td>
                                                <td>
                                                    <div class="row justify-content-md-center">
                                                        <div class="col-md-2 p-1">
                                                            <input type="text" class="form-control text-center" name="range_from[]" oninput="checkNumber(this)"
                                                                onfocus="focusInput(this);" required data-parsley-required-message="{{ setMessage('month.required') }}"
                                                                value="{{ $rate->range_from }}">
                                                        </div>
                                                        <div class="col-md-4 p-1">
                                                            <select class="form-select text-center operator_id" name="operator_id[]" required
                                                                data-parsley-required-message="{{ setMessage('operator.required') }}">
                                                                <option value="">-- SILA PILIH --</option>
                                                                @foreach ($operatorAll as $operator)
                                                                    <option value="{{ $operator->id }}" @if ($operator->id == $rate->operator_id) selected @endif>
                                                                        {{ $operator->operator_name }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="col-md-2 p-1">
                                                            <input class="form-control text-center range_to" type="text" name="range_to[]" oninput="checkNumber(this)"
                                                                onfocus="focusInput(this);" required data-parsley-required-message="{{ setMessage('month.required') }}"
                                                                value="{{ $rate->range_to }}">
                                                        </div>
                                                        <div>
                                                            <span class="col-md-8 d-none range-error-msg" style="color:#f46a6a">{{ setMessage('range-error-msg') }}</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="row justify-content-md-center">
                                                        <div class="col-md-3">
                                                            <input type="text" class="form-control text-center" name="rate[]" id="rate" oninput="checkNumber(this)"
                                                                required data-parsley-required-message="{{ setMessage('rate.required') }}" value="{{ $rate->rate }}">
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="align-top text-center">
                                                    <a class="btnDeleteRate btn btn-danger btn-sm swal-delete-list"><i class="mdi mdi-minus mdi-18px"></i></a>
                                                    <form method="POST" action="{{ route('blacklistPenaltyRate.destroyRate') }}" class="delete-form-list">
                                                        {{ csrf_field() }}
                                                        <input class="form-control" type="hidden" name="id" value="{{ $rate->id }}">
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </div>

                        <div class="mb-3 row">
                            <div class="col-sm-12">
                                <button formnovalidate type="submit" id="btn-submit" class="btn btn-primary float-end swal-kemaskini">{{ __('button.kemaskini') }}</button>
                                <a href="{{ route('blacklistPenaltyRate.index') }}" class="btn btn-secondary float-end me-2">{{ __('button.kembali') }}</a>
                            </div>
                        </div>

                    </form>

                </div>
            </div>
        </div> <!-- end col -->
    </div>
    <!-- end row -->

    <div id="newRow" class="d-none">
        <table>
            <tbody>
                <tr class="text-center">
                    <input type="hidden" name="is_edit[]" value="0">
                    <td>1</td>
                    <td>
                        <div class="row justify-content-md-center">
                            <div class="col-md-2 p-1">
                                <input type="text" class="form-control text-center" name="range_from[]" oninput="checkNumber(this)" onfocus="focusInput(this);" required
                                    data-parsley-required-message="{{ setMessage('month.required') }}">

                            </div>
                            <div class="col-md-4 p-1">
                                <select class="form-select text-center operator_id" name="operator_id[]" required
                                    data-parsley-required-message="{{ setMessage('operator.required') }}">
                                    <option value="">-- SILA PILIH --</option>
                                    @foreach ($operatorAll as $operator)
                                        <option value="{{ $operator->id }}">{{ $operator->operator_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2 p-1">
                                <input class="form-control text-center range_to" type="text" name="range_to[]" oninput="checkNumber(this)" onfocus="focusInput(this);" required
                                    data-parsley-required-message="{{ setMessage('month.required') }}">
                            </div>
                            <div>
                                <span class="col-md-8 d-none range-error-msg" style="color:#f46a6a">{{ setMessage('range-error-msg') }}</span>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="row justify-content-md-center">
                            <div class="col-md-3">
                                <input type="text" class="form-control text-center" name="rate[]" id="rate" oninput="checkNumber(this)" required
                                    data-parsley-required-message="{{ setMessage('rate.required') }}">
                            </div>
                        </div>
                    </td>
                    <td class="align-top text-center">
                        <a id="btnRemoveCriteria-1" class="btnRemoveCriteria btn btn-warning btn-sm"><i class="mdi mdi-minus mdi-18px"></i></a>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

@endsection

@section('script')
    <script src="{{ URL::asset('assets/libs/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ URL::asset('assets/js/pages/BlacklistPenalty/blacklist-penalty-rate.js') }}"></script>
    <script>
        $(function() {

            // checkOperatorSelection();
            recheckMonthRange();

            function recheckMonthRange() {
                $("input[name='range_from[]").first().trigger('change');
            }


            // Function to clone the row
            function cloneRowEdit(row) {
                const clonedRow = row.clone();

                // Increment the numbering
                const newRowNumber = parseInt(row.find("td:first").text()) + 1;
                clonedRow.find("td:first").text(newRowNumber);

                // Increment the names and ids of the input fields
                clonedRow.find("input, select").each(function() {
                    $(this).val("");
                });

                return clonedRow;
            }

            // Add row button click event
            $("#duplicateRowEdit").on("click", function() {
                const row = $("#newRow table tbody tr:first");
                const newRow = cloneRowEdit(row);
                newRow.appendTo("table tbody");

                // Recalculate numbering
                $("table tbody tr").each(function(index) {
                    $(this).find("td:first").text(index + 1);
                });
            });


        });
    </script>
@endsection
