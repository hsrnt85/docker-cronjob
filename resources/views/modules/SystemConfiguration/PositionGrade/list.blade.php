@extends('layouts.master')

@section('content')

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">

                {{-- PAGE TITLE --}}
                <div class="border-bottom border-primary mb-4"><h4 class="card-title">{{ getPageTitle(1) }}</h4></div>

                <div class="row mb-2">
                    {{-- <div class="col-sm-12">
                        <a type="button" href="{{ route('positionGrade.create') }}" class="btn btn-primary float-end waves-effect waves-light">{{ __('button.rekod_baru') }}</a>
                    </div> --}}
                </div>
                <div id="datatable_wrapper">
                    <div class="row">
                        <div class="col-sm-12">
                            <table id="datatable" class="table table-striped table-sm table-bordered dt-responsive nowrap w-100 dataTable" role="grid" >
                            <thead class="bg-primary bg-gradient text-white">
                                    <tr role="row">
                                        <th class="text-center" width="4%">Bil</th>
                                        <th class="text-center">Gred Jawatan</th>
                                        <th class="text-center" width="10%">Tindakan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($positionGradeAll as $data)
                                        <tr class="odd">
                                            <td class="text-center" tabindex="0">{{ $loop->iteration }}</td>
                                            <td class="text-center">{{$data->grade_no}} </a></td>
                                            <td class="text-center">
                                                <div class="btn-group" role="group">
                                                    @if(checkPolicy("V"))
                                                    <a href="{{ route('positionGrade.view', ['id' => $data->id]) }}" class="btn btn-outline-primary px-2 py-1 tooltip-icon">
                                                        <span class="tooltip-text">{{ __('button.papar') }}</span> <i class="{{ __('icon.view_folder') }}"></i>
                                                    </a>
                                                    @endif
                                                    {{-- <a href="{{ route('positionGrade.edit', ['id' => $data->id]) }}" class="btn btn-outline-primary px-2 py-1 tooltip-icon">
                                                        <span class="tooltip-text">{{ __('button.kemaskini') }}</span> <i class="{{ __('icon.edit') }}"></i>
                                                    </a>
                                                    <a class="btn btn-outline-primary px-2 py-1 tooltip-icon swal-delete-list">
                                                        <span class="tooltip-text">{{ __('button.hapus') }}</span> <i class="{{ __('icon.delete') }}"></i>
                                                    </a> --}}
                                                </div>
                                                <form method="POST" action="{{ route('positionGrade.delete') }}" class="delete-form-list">
                                                    {{ csrf_field() }}
                                                    {{ method_field('DELETE') }}
                                                    <input class="form-control" type="hidden" name="id" value="{{ $data->id }}">
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
