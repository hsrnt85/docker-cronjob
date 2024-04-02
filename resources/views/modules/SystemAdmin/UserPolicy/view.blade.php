@extends('layouts.master')

@section('file-css')
<link rel="stylesheet" href="{{ asset('assets/css/pages/UserPolicy/userPolicy.css') }}">
@endsection

@section('content')

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">

                <input type="hidden" name="id" value="{{ $roles->id }}">
                <input type="hidden" id="configSubmenu" value="{{ $configSubmenu }}">
                <input type="hidden" id="rolesAbilities" value="{{ $rolesAbilities }}">

                {{-- PAGE TITLE --}}
                <div class="border-bottom border-primary mb-4"><h4 class="card-title">{{ getPageTitle(1) }}</h4></div>

                <div class="mb-3 row">
                    <label class="col-md-2 col-form-label ">Nama Peranan</label>
                    <div class="col-md-6"><input class="form-control " id="name" name="name" value="{{ $roles->name }}" disabled>  </div>
                </div>

                <div class="row">
                    <label class="col-md-2 col-form-label ">Semua Daerah?</label>
                    <div class="col-md-6">

                        <div class="form-check is-district">
                            <input type="checkbox" class="form-check-input" name="is_district" {{ $roles->is_district ? 'checked' : ''}} disabled>
                            <label class="form-check-label" for="is_district" checked > Ya </label>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <div class="card">
            <div class="card-body">

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
                <div class="tab-content p-3 text-muted">

                    <div class="tab-pane active" id="module" role="tabpanel">
                        <div class="row">
                            <div class="col-lg-3">
                                @foreach ($configMenu as $data)
                                    <a href="#" onclick="showSection(this, 'sub_view', {{ $data->id }})" id="btn-sidemenu{{$data->id}}" class="bold btn btn-sidemenu {{ $loop->first ? 'active' : '' }}" >{{ capitalizeText($data->menu) }}</a>
                                @endforeach
                            </div> <!-- end col-->
                            <div class="col-lg-1"></div>

                            <div class="col-lg-8">
                                <div class="mb-3 row">
                                    <div class="table-responsive">

                                        <table id="tbl_submenu" class="table" >
                                            <thead>
                                                <tr>
                                                    <th width="40%"></th>
                                                    <th width="11%">Rekod Baru</th>
                                                    <th width="11%">Kemaskini</th>
                                                    <th width="10%">Papar</th>
                                                    <th width="10%">Hapus</th>
                                                </tr>
                                            </thead>

                                            <tbody id="tbody_data_view"></tbody>

                                        </table>
                                    </div>

                                </div>
                            </div> <!-- end col -->

                        </div>

                    </div>

                    <!-- TAB -CONTENT - USER LIST-->
                    <div class="tab-pane" id="user-list" role="tabpanel">

                        <div class="col-lg-8 table-responsive">

                            <table class="table" >
                                <thead>
                                    <tr>
                                        <th width="5%">Bil</th>
                                        <th width="40%" >Nama Pengguna</th>
                                        <th width="20%">Platform Sistem</th>
                                    </tr>
                                </thead>

                                <tbody class="table-striped">
                                    @foreach($user as $bil => $data)
                                        <tr>
                                            <th scope="row">{{ ++$bil }}</th>
                                            <td>{{ $data->name }}</td>
                                            <td>{{ $data->system->name ?? '' }}</td>
                                        </tr>
                                    @endforeach

                                </tbody>

                            </table>
                        </div>

                    </div>

                </div>

                <div class="row mt-4">
                    <hr/>
                    <div class="col-sm-11 offset-sm-1 mt-1">
                        {{-- <a href="{{ route('userPolicy.edit', ['id' => $roles->id]) }}" class="btn btn-primary float-end">{{ __('button.kemaskini') }}</a> --}}
                        {{-- <a class="btn btn-danger float-end me-2 swal-delete">{{ __('button.hapus') }}</a> --}}
                        <a href="{{ route('userPolicy.index') }}" class="btn btn-secondary float-end me-2">{{ __('button.kembali') }}</a>
                    </div>
                </div>

                <form method="POST" action="{{ route('userPolicy.delete') }}" id="delete-form">
                    {{ csrf_field() }}
                    {{ method_field('DELETE') }}
                    <input class="form-control" type="hidden" id="id-delete" name="id" value="{{ $roles->id }}">
                </form>

            </div>

        </div>

    </div>

</div>

@endsection

@section('file-js')
<script src="{{ URL::asset('assets/js/pages/UserPolicy/userPolicyEditView.js')}}"> </script>
<script src="{{ URL::asset('assets/js/pages/UserPolicy/userPolicy.js')}}"> </script>
@endsection
