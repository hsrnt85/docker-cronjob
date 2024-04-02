@extends('layouts.master')

@section('file-css')
<link rel="stylesheet" href="{{ asset('assets/css/pages/UserPolicy/userPolicy.css') }}">
@endsection

@section('content')

<form class="custom-validation" id="form" method="post" action="{{ route('userPolicy.store') }}" >
{{ csrf_field() }}

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">

                    {{-- PAGE TITLE --}}
                    <div class="border-bottom border-primary mb-4"><h4 class="card-title">{{ getPageTitle(1) }}</h4></div>

                    <input type="hidden" id="configSubmenu" value="{{ $configSubmenu }}">

                    <div class="mb-3 row">
                        <label for="name" class="col-md-2 col-form-label ">Nama Peranan</label>
                        <div class="col-md-6">
                            <input class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required data-parsley-required-message="{{ setMessage('name.required') }}">
                            @error('name')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <label for="is_district" class="col-md-2 col-form-label ">Semua Daerah?</label>
                        <div class="col-md-6">
                            <div class="form-check is-district">
                                <input type="checkbox" class="form-check-input" id="is_district" name="is_district" {{ old('is_district') ? 'checked' : ''}}>
                                <label class="form-check-label" for="is_district"> Ya</label>
                            </div>
                            @error('is_district')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                </div>
            </div>

            <div class="card">
                <div class="card-body">

                    <div class="row">
                        <div class="mb-3" id="msg-errors-tab-all"></div>
                    </div>

                    <!-- Nav tabs -->
                    <ul class="nav nav-tabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" data-bs-toggle="tab" href="#module" role="tab">Peranan </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#user-list" role="tab">Senarai Pengguna Sistem</a>
                        </li>
                    </ul>

                    <!-- Tab panes -->
                    <!-- TAB -CONTENT - MODULE/ SUBMODULE-->
                    <div class="tab-content mb-1 p-0 text-muted">

                        <div class="mt-3 tab-pane active" id="module" role="tabpanel">
                            <div class="row">
                                <div class="col-lg-3">
                                    @foreach ($configMenu as $data)
                                        <a href="#" onclick="showSection(this, 'sub_view', {{ $data->id }})" id="btn-sidemenu{{$data->id}}" class="bold btn btn-sidemenu {{ $loop->first ? 'active' : '' }}" >{{ capitalizeText($data->menu) }}</a>
                                    @endforeach
                                </div> <!-- end col-->
                                <div class="col-lg-1"></div>
                                <div class="col-lg-8">

                                    <div class="card card-body shadow rounded bg-white">
                                        <div class="table-responsive">

                                            <table id="tbl_submenu" class="table" >
                                                <thead class="">
                                                    <tr>
                                                        <th width="40%"></th>
                                                        <th width="11%">Rekod Baru</th>
                                                        <th width="11%">Kemaskini</th>
                                                        <th width="10%">Papar</th>
                                                        <th width="10%">Hapus</th>
                                                    </tr>
                                                </thead>

                                                <tbody id="tbody_data"></tbody>

                                            </table>
                                            <div id="section_hidden">
                                                <input type="text" id="menus" name="menus[]" min="1" data-parsley-min-message="{{ setMessage('roles.required') }}" data-parsley-errors-container="#msg-errors-tab-all">
                                            </div>

                                        </div>

                                    </div>

                                </div> <!-- end col -->

                            </div>

                        </div>

                        <!-- TAB -CONTENT - USER LIST-->
                        <div class="tab-pane" id="user-list" role="tabpanel">
                            <div class="row">
                                <div class="mt-3 col-lg-10 table-responsive"data-simplebar style="max-height: 500px;">

                                    <table class="table" >
                                        <thead class="table-light">
                                            <tr>
                                                <th width="5%">Bil</th>
                                                <th width="40%" colspan="2">Nama Pengguna</th>
                                                <th width="20%">Peranan Semasa</th>
                                                <th width="20%">Platform Sistem</th>
                                            </tr>
                                        </thead>

                                        <tbody class="table-striped">
                                            @foreach($user as $bil => $data)
                                                <tr>
                                                    <th scope="row">{{ ++$bil }}</th>
                                                    <td width="1%">
                                                        <div class="form-check">
                                                            <input type="checkbox" name="users_ids[]" class="form-check-input" value="{{ $data->id }}">
                                                                {{-- required data-parsley-required-message="{{ setMessage('users.required') }}"
                                                                data-parsley-mincheck="1" data-parsley-mincheck-message="{{ setMessage('users.required') }}"
                                                                data-parsley-errors-container="#msg-errors-tab-all"> --}}
                                                        </div>
                                                    </td>
                                                    <td>{{ $data->name }}</td>
                                                    <td>{{ $data->roles?->name ? $data->roles?->name : 'Tiada Peranan'}}</td>
                                                    <td>{{ $data->system->name ?? '' }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>

                                    </table>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="row mt-4">
                        <hr/>
                        <div class="col-sm-11 offset-sm-1 mt-1">
                            <button type="submit" id="btn-submit" class="btn btn-primary float-end swal-tambah">{{ __('button.simpan') }}</button>
                            <a href="{{ route('userPolicy.index') }}" class="btn btn-secondary float-end me-2">{{ __('button.kembali') }}</a>
                        </div>
                    </div>


                </div>
            </div>
        </div>
    </div>

</form>

@endsection

@section('file-js')
<script src="{{ URL::asset('assets/js/pages/UserPolicy/userPolicyNew.js')}}"> </script>
<script src="{{ URL::asset('assets/js/pages/UserPolicy/userPolicy.js')}}"> </script>
@endsection
