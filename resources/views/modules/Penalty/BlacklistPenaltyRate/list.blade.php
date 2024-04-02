@extends('layouts.master')

@section('content')

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                {{-- PAGE TITLE --}}
                <div class="border-bottom border-primary mb-4"><h4 class="card-title">{{ getPageTitle(1) }}</h4></div>
                @if(checkPolicy("A"))
                    <div class="row mb-2">
                        <div class="col-sm-12">
                            <a type="button" href="{{ route('blacklistPenaltyRate.create') }}" class="btn btn-success float-end waves-effect waves-light">{{ __('button.rekod_baru') }}</a>
                        </div>
                    </div>
                @endif

                {{-- DATATABLE --}}
                <div id="datatable_wrapper">
                    <div class="row">
                        <div class="col-sm-12">
                            <table id="datatable" class="table table-striped table-bordered dt-responsive w-100 dataTable" role="grid" >
                                <thead class="bg-primary bg-gradient text-white">
                                    <tr role="row">
                                        <th class="text-center" width="2%" style="">Bil</th>
                                        <th class="text-center">Keterangan</th>
                                        <th class="text-center">Tarikh Kuatkuasa</th>
                                        <th class="text-center">Tindakan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(isset($bprAll) && count($bprAll) > 0)
                                        @foreach ($bprAll as $bpr)
                                            <tr class="odd">
                                                <td class="text-center" tabindex="0">{{ $loop->iteration }}</td>
                                                <td class="text-center" >{{ $bpr->description }} </td>
                                                <td class="text-center" >{{ convertDateSys($bpr->effective_date) }} </td>
                                                <td class="text-center">
                                                    <div class="btn-group" role="group">
                                                        @if(checkPolicy("V"))
                                                            <a href="{{ route('blacklistPenaltyRate.view', $bpr) }}" class="btn btn-outline-primary px-2 py-1 tooltip-icon">
                                                                <span class="tooltip-text">{{ __('button.papar') }}</span><i class="{{ __('icon.view_folder') }}"></i>
                                                            </a>
                                                        @endif
                                                        @if(checkPolicy("U"))
                                                            <a href="{{ route('blacklistPenaltyRate.edit', $bpr) }}" class="btn btn-outline-primary px-2 py-1 tooltip-icon">
                                                                <span class="tooltip-text">{{ __('button.kemaskini') }}</span><i class="{{ __('icon.edit') }}"></i>
                                                            </a>
                                                        @endif
                                                        @if(checkPolicy("D"))
                                                            <a class="btn btn-outline-primary px-2 py-1 tooltip-icon swal-delete-list">
                                                                <span class="tooltip-text">{{ __('button.hapus') }}</span> <i class="{{ __('icon.delete') }}"></i>
                                                            </a>
                                                        @endif
                                                    </div>
                                                    <form method="POST" action="{{ route('blacklistPenaltyRate.destroy') }}" class="delete-form-list">
                                                        {{ csrf_field() }}
                                                        {{ method_field('DELETE') }}
                                                        <input class="form-control" type="hidden" name="id" value="{{ $bpr->id }}">
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr class="odd">
                                            <td colspan="4" class="text-center">Tiada Rekod</td>
                                        </tr>
                                    @endif
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
