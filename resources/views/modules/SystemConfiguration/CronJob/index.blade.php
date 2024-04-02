@extends('layouts.master')

@section('content')

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">

                {{-- PAGE TITLE --}}
                <div class="border-bottom border-primary mb-4"><h4 class="card-title">{{ getPageTitle(1) }}</h4></div>

                <div class="row mb-2">
                    <div class="col-sm-12">
                        @if(checkPolicy("A"))
                            <a href="{{ route('cronJob.create') }}" class="btn btn-success float-end waves-effect waves-light me-2">{{ __('button.rekod_baru') }}</a>
                        @endif
                    </div>
                </div>

                <div id="datatable_wrapper">
                    <div class="row">
                        <div class="col-sm-12">
                            <table id="datatable" class="table table-striped table-bordered dt-responsive wrap w-100 dataTable" role="grid">
                                <thead class="bg-primary bg-gradient text-white">
                                    <tr role="row">
                                        <th class="text-center" width="4%">Bil</th>
                                        <th class="text-center">Proses</th>
                                        <th class="text-center">Kaedah Proses</th>
                                        <th class="text-center">Status Proses</th>
                                        <th class="text-center">Di Proses pada</th>
                                        <th class="text-center" width="10%">Tindakan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($list as $bil => $data)
                                        <tr>
                                            <th class="text-center" scope="row">{{ ++$bil }}</th>
                                            <td>{{ $data->cronjobType?->cronjob_type }}</td>
                                            <td class="text-center">{{ ($data->process_method==1) ? "SERVER" : "MANUAL" }}</td>
                                            <td class="text-center">{{ ($data->status == 0) ? "TIADA DATA DIKEMASKINI" : "" }}</td>
                                            <td class="text-center">{{ convertDateSys($data->action_on) }}, {{ convertTime($data->action_on) }}</td>
                                            <td class="text-center">
                                                <div class="btn-group" role="group">
                                                    @if(checkPolicy("D"))
                                                        <a class="btn btn-outline-primary px-2 py-1 tooltip-icon swal-delete-list">
                                                            <span class="tooltip-text">{{ __('button.hapus') }}</span>  <i class="{{ __('icon.delete') }}"></i>
                                                        </a>
                                                    @endif
                                                </div>
                                                <form method="POST" action="{{ route('cronJob.delete') }}" class="delete-form-list">
                                                    {{ csrf_field() }}
                                                    {{ method_field('DELETE') }}
                                                    <input type="hidden" name="id" value="{{ $data->id }}">
                                                </form>
                                            </td>
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
