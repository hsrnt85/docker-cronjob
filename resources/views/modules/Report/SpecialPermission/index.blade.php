
@extends('layouts.master')

@section('content')

<div class="row">
    <div class="col-12">

        <!-- section - search  -->
        <div class="card">

            <form class="search_form custom-validation" action="{{ route('specialPermissionReport.index') }}" method="post" id="form-laporan-kebenaran-khas">
                {{ csrf_field() }}


                <div class="card-body p-3">

                    <div class="border-bottom border-primary mb-4"><h4 class="card-title">Carian Rekod</h4></div>

                    <div class="row">
                        <div class="col-md-3 p-1 mb-3">
                            <label class="col-form-label">Daerah</label>
                            <select id="carian_daerah" name="carian_daerah" class="form-control select2">
                                <option value="">-- Pilih Daerah --</option>
                                @foreach($districts as $data)
                                    @if($data->id == $carian_daerah)
                                        <option value="{{ $data->id }}" selected>{{ $data->district_name }}</option>
                                    @else
                                        <option value="{{ $data->id }}">{{ $data->district_name }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-3 p-1 mb-3">
                            <label class="col-form-label">No. Kad Pengenalan</label>
                            <input class="form-control" type="text" name="new_ic" id="new_ic" autocomplete="off" value="{{ old('new_ic', $search_new_ic) }}">
                        </div>

                        <div class="col-md-3 p-1 mb-3">
                            <label class="col-form-label">Nama</label>
                            <input class="form-control" type="text" name="name" id="name" autocomplete="off" value="{{ old('name', $search_name) }}">
                        </div>
                    </div>


                    <div class="row">
                        <div class="col-md-12 p-1 mb-3">
                            <button class="btn btn-primary" type="submit" onClick="setFormTarget()">{{ __('button.cari') }}</button>
                            <button name="reset" class="btn btn-primary" type="submit" value="reset"  onClick ="clearSearchInput()">{{ __('button.reset') }}</button>
                            <button name="muat_turun_pdf" class="btn btn-success" type="submit" value="pdf" onClick="setFormTarget(this)">{{ __('button.muat_turun_pdf') }}</button>
                        </div>
                    </div>
                </div>

            </form>
        </div>

         <!-- section - list report -->
         <div class="card">
            <div class="card-body">

                {{-- PAGE TITLE --}}
                <div class="border-bottom border-primary mb-4"><h4 class="card-title">{{ getPageTitle(1) }}</h4></div>

                <div id="datatable_wrapper">
                    <div class="row">
                        <div class="col-sm-12">
                            <table id="datatable1" class="table table-striped table-bordered dt-responsive wrap w-100 dataTable" role="grid">
                                <thead class="bg-primary bg-gradient text-white">
                                    <tr role="row">
                                        <th class="text-center">Bil.</th>
                                        <th width="15%" class="text-center">Nama</th>
                                        <th width="10%" class="text-center">No. Kad Pengenalan</th>
                                        <th width="20%" class="text-center">Jawatan</th>
                                        <th width="10%" class="text-center">Taraf Jawatan</th>
                                        <th width="15%" class="text-center">Jenis Perkhidmatan</th>
                                        <th width="25%" class="text-center">Agensi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($users as $bil => $user)
                                        @foreach ($user->addressOffice as $address)
                                        <tr>
                                            <th class="text-center" scope="row">{{ strtoupper(++$bil) }}</th>
                                            <td >{{ strtoupper($user->name) }}</td>
                                            <td  class="text-center" >{{ strtoupper($user->new_ic) }}</td>
                                            <td >
                                                @if($user->latest_user_info)
                                                    {{ strtoupper($user->latest_user_info->position->position_name) }} - {{ strtoupper($user->latest_user_info->position_grade_code->grade_type ?? '') }}{{ strtoupper($user->latest_user_info->position_grade->grade_no) }}
                                                @else
                                                    {{ strtoupper($user->position->position_name) }} - {{ strtoupper($user->position_grade_code->grade_type ?? '') }}{{ strtoupper($user->position_grade->grade_no) }}
                                                @endif
                                            </td>
                                            <td  class="text-center">
                                                @if($user->latest_user_info)
                                                    {{ strtoupper($user->latest_user_info->position_type->position_type) }}
                                                @else
                                                    {{ strtoupper($user->position_type->position_type) }}
                                                @endif
                                            </td>
                                            <td >
                                                @if($user->latest_user_info)
                                                    {{ strtoupper($user->latest_user_info->services_type->services_type) ?? '-' }}
                                                @else
                                                    {{ strtoupper($user->services_type->services_type ?? '-') }}
                                                @endif
                                            </td>
                                            <td>
                                                @if ($address->organization)
                                                    {{ strtoupper($address->organization->name) }}
                                                @else
                                                    -
                                                @endif
                                            </td>
                                        </tr>
                                        @endforeach
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- end section - list report -->

    </div> <!-- end col -->

</div>
<!-- end row -->

@endsection

@section('script')
<script src="{{ URL::asset('assets/js/pages/Report/specialPermissionReport/specialPermissionReport.js')}}"></script>
@endsection
