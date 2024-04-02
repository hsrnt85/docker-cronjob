@extends('layouts.master')

@section('content')

<div class="row">
    <div class="col-12">

        <div class="card">
            <div class="card-body">
                {{-- PAGE TITLE --}}
                <div class="border-bottom border-primary mb-4"><h4 class="card-title">{{ getPageTitle(1) }}</h4></div>

                @if(checkPolicy("A") )
                    <div class="row mb-2">
                        <div class="col-sm-12">
                            <a type="button" href="{{ route('ispeksIntegrationIncoming.process') }}" class="btn btn-success float-end waves-effect waves-light">{{ __('button.proses_integrasi') }}</a>
                        </div>
                    </div>
                @endif

                <div id="datatable_wrapper">
                    <div class="row">
                        <div class="col-sm-12">
                            <table id="datatable" class="table table-striped table-bordered dt-responsive nowrap w-100 dataTable indextable" role="grid" >
                                <thead class="bg-primary bg-gradient text-white">
                                    <tr role="row">
                                        <th class="text-center" width="5%">Bil</th>
                                        <th class="text-center" width="25%">Nama Fail</th>
                                        <th class="text-center" width="25%">Kategori</th>
                                        <th class="text-center" width="25%">Diproses Pada</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($ispeksIntegrationList as $bil => $data)
                                        <tr>
                                            <td class="text-center">{{ ++$bil }}</td>
                                            <td class="text-center">{{ $data->file_name_gpg }}</td>
                                            <td class="text-center">{{ $data->category }}</td>
                                            <td class="text-center">{{ convertDateTimeSys($data->action_on) }}</td>
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
