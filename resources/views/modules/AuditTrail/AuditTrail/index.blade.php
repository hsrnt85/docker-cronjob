@extends('layouts.master')
@section('css')
    <link href="{{ URL::asset('/assets/libs/bootstrap-datepicker/bootstrap-datepicker.min.css') }}" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="{{ URL::asset('/assets/libs/datepicker/datepicker.min.css') }}">
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <!-- section - search  -->
        <div class="card ">
            <form class="search_form custom-validation" action="{{ route('auditTrail.index') }}" method="post">
                {{csrf_field()}}

                <div class="card-body p-3">
                    <div class="border-bottom border-primary mb-4"><h4 class="card-title">Carian Rekod</h4></div>

                    <div class="row">
                        <div class="col-md-3 p-1 mb-3">
                            <label class="col-form-label">Tarikh Dari<span class="text-danger"> *</span></label>
                            <div class="input-group" id="datepicker_from">
                                <input class="form-control" type="text" placeholder="dd/mm/yyyy" name="date_from" id="date_from"
                                    data-date-format="dd/mm/yyyy" data-date-container="#datepicker_from" data-provide="datepicker"
                                    autocomplete="off" data-date-autoclose="true" value="{{ old('date_from', $search_date_from) }}"
                                    required data-parsley-required-message="{{ setMessage('date_from.required') }}"
                                    data-parsley-errors-container="#date_from_error">
                                <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
                            </div>
                            <div id="date_from_error"></div>
                        </div>

                        <div class="col-md-3 p-1 mb-3">
                            <label class="col-form-label">Tarikh Hingga<span class="text-danger"> *</span></label>
                            <div class="input-group" id="datepicker_to">
                                <input class="form-control" type="text" placeholder="dd/mm/yyyy" name="date_to" id="date_to"
                                    data-date-format="dd/mm/yyyy" data-date-container="#datepicker_to" data-provide="datepicker"
                                    autocomplete="off" data-date-autoclose="true" value="{{ old('date_to', $search_date_to) }}"
                                    required data-parsley-required-message="{{ setMessage('date_to.required') }}"
                                    data-parsley-errors-container="#date_to_error">
                                <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
                            </div>
                            <div id="date_to_error"></div>
                        </div>

                        <div class="col-md-3 p-1 mb-3">
                            <label class="col-form-label">Nama Pengguna</label>
                            <select class="form-select select2" id="name" name="name">
                                <option value="">-- Sila Pilih --</option>
                                @foreach($user->groupBy('name') as $name => $usersWithSameName)
                                    @if($name !== null && count($usersWithSameName) === 1)
                                        <option value="{{ $usersWithSameName[0]->id }}" {{ $selectedName == $name ? 'selected' : '' }}>
                                            {{ $name }}
                                        </option>
                                    @endif
                                @endforeach
                            </select>
                            @error('name')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                            <div id="parsley-errors-name"></div>
                        </div>

                    </div>


                    <div class="row">

                        <div class="col-md-3 p-1 mb-3">
                            <label class="col-form-label">Modul</label>
                            <select class="form-select select2 @error('module_id') is-invalid @enderror" id="module_id" name="module_id" data-route="{{ route('auditTrail.ajaxGetSubmodule') }}" data-parsley-errors-container="#parsley-errors-module_name">
                                <option value="">-- Sila Pilih --</option>
                                @foreach($menu->sortBy('order') as $code)
                                    @if ($code->data_status === 1)
                                        <option value="{{ $code->id }}" {{ $selectedModuleName == $code->menu ? 'selected' : '' }}>
                                            {{ $code->menu }}
                                        </option>
                                    @endif
                                @endforeach
                            </select>
                            @error('module_id')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                            <div id="parsley-errors-module_name"></div>
                        </div>

                        <div class="col-md-3 p-1 mb-3">
                            <label class="col-form-label">Sub Modul</label>
                            <select class="form-select select2 @error('submodule_id') is-invalid @enderror" id="submodule_id" name="submodule_id" data-parsley-errors-container="#parsley-errors-submodule_name">
                                <option value="">-- Sila Pilih --</option>
                                @foreach($submenu as $sub)
                                    <option value="{{ $sub->id }}" {{ $selectedSubmoduleName == $sub->submenu ? 'selected' : '' }}>
                                        {{ $sub->submenu }}
                                    </option>
                                @endforeach
                            </select>
                            @error('submodule_id')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                            <div id="parsley-errors-submodule_name"></div>
                        </div>

                    </div>

                    <div class="row">

                        <div class="col-md-12 p-1 mb-3">
                            <div id="loading-spinner" class="text-center" style="display: none;">
                                <i class="fa fa-spinner fa-spin fa-2x"></i> Loading...
                            </div>
                            <button class="btn btn-primary" type="submit" onClick="setFormTarget()">{{ __('button.cari') }}</button>
                            <button name="reset" class="btn btn-primary" value="reset" onClick="clearSearchInput()">{{ __('button.reset') }}</button>
                            {{-- <button name="muat_turun_pdf" class="btn btn-success" type="submit" value="pdf" onClick="setFormTarget(this)">{{ __('button.muat_turun_pdf') }}</button> --}}

                        </div>
                    </div>

                </div>
            </form>
        </div>
        <!-- end section - search  -->


        <div class="card">
            <div class="card-body">

                {{-- PAGE TITLE --}}
                <div class="border-bottom border-primary mb-4"><h4 class="card-title">{{ getPageTitle(1) }}</h4></div>


                <div id="datatable_wrapper">
                    <div class="row">
                        <div class="col-sm-12">
                            <table id="datatable" class="table table-striped table-bordered dt-responsive w-100 dataTable" role="grid" >
                                <thead class="bg-primary bg-gradient text-white">
                                    <tr role="row">
                                        <th class="text-center" width="2%"> Bil. </th>
                                        <th class="text-center" width="5%"> Tarikh & Masa Log </th>
                                        <th class="text-center" width="12%"> Nama Pengguna </th>
                                        <th class="text-center" width="10%"> Modul </th>
                                        <th class="text-center" width="10%"> Submodul </th>
                                        <th class="text-center" width="20%"> Aktiviti </th>
                                        {{-- <th class="text-center" width="10%"> Tindakan </th> --}}
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($userActivityLog as $bil => $activity)
                                    <tr>
                                        <th class="text-center" scope="row">{{ ++$bil }}</th>
                                        <td class="text-center">{{ convertDateSys($activity->action_on) }}<br/>{{ convertTime($activity->action_on) }}</td>
                                        <td class="text-center">{{ $activity->name ? ucwords(strtolower($activity->name)) : '' }}</td>
                                        <td class="text-center">{{ $activity->module_name ? ucwords(strtolower($activity->module_name)) : '' }}</td>
                                        <td class="text-center">{{ $activity->submodule_name }}</td>
                                        <td >{{ $activity->activity }}</td>
                                        {{-- <td class="text-center">
                                            <div class="btn-group" role="group">
                                                @if(checkPolicy("V"))
                                                    <a href="{{ route('auditTrail.view', ['id' => $activity->id]) }}" class="btn btn-outline-primary px-2 py-1 tooltip-icon">
                                                        <span class="tooltip-text">{{ __('button.papar') }}</span> <i class="{{ __('icon.open_folder') }}"></i>
                                                    </a>
                                                @endif

                                            </div>
                                        </td> --}}
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div> <!-- end col -->

</div>
<!-- end row -->

@endsection
@section('script')
<script src="{{ URL::asset('assets/libs/bootstrap-datepicker/bootstrap-datepicker.min.js')}}"></script>
<script src="{{ URL::asset('assets/js/pages/AuditTrail/audit-trail.js') }}"></script>

@endsection
