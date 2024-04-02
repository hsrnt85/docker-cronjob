@extends('layouts.master')

@section('content')

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">

                {{-- PAGE TITLE --}}
                <div class="border-bottom border-primary mb-4"><h4 class="card-title">{{ getPageTitle(2) }}</h4></div>

                @if(checkPolicy("A"))
                    <div class="row mb-2">
                        <div class="col-sm-12">
                            <a type="button" href="{{ route('penalty.create', $category) }}" class="btn btn-success float-end waves-effect waves-light">{{ __('button.rekod_baru') }}</a>
                        </div>
                    </div>
                @endif

                <div id="datatable_wrapper">
                    <div class="row">
                        <div class="col-sm-12">
                            <table id="datatable" class="table table-striped table-bordered dt-responsive nowrap w-100 dataTable" role="grid" >
                                <thead class="bg-primary bg-gradient text-white">
                                    <tr role="row">
                                        <th class="text-center" width="4%">Bil</th>
                                        <th class="text-center">No. Rujukan Denda</th>
                                        <th class="text-center">Nama Penghuni</th>
                                        <th class="text-center">Keterangan Denda</th>
                                        <th class="text-center">Amaun Denda (RM)</th>
                                        <th class="text-center" width="10%">Tindakan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($tenantsPenalty as $penalty)
                                        <tr class="odd">
                                            <td class="text-center" tabindex="0">{{ $loop->iteration }}</td>
                                            <td class="text-center">{{ $penalty->penalty_ref_no}}</td>
                                            <td class="text-center">{{ $penalty->tenants->user->name}}</td>
                                            <td class="text-center">{{ $penalty->remarks}}</td>
                                            <td class="text-center">{{ $penalty->penalty_amount}}</td>

                                            <td class="text-center">
                                                <div class="btn-group" role="group">
                                                    @if(checkPolicy("V"))
                                                        <a href="{{ route('penalty.view', ['category' => $category->id, 'id' => $penalty->id]) }}" class="btn btn-outline-primary px-2 py-1 tooltip-icon">
                                                            <span class="tooltip-text">{{ __('button.papar') }}</span> <i class="{{ __('icon.view_folder') }}"></i>
                                                        </a>
                                                    @endif
                                                    @if(checkPolicy("U"))
                                                        <a href="{{ route('penalty.edit', ['category' => $category->id, 'id' => $penalty->id]) }}" class="btn btn-outline-primary px-2 py-1 tooltip-icon">
                                                            <span class="tooltip-text">{{ __('button.kemaskini') }}</span> <i class="{{ __('icon.edit') }}"></i>
                                                        </a>
                                                    @endif
                                                    @if(checkPolicy("D"))
                                                        <a class="btn btn-outline-primary px-2 py-1 tooltip-icon swal-delete-list">
                                                            <span class="tooltip-text">{{ __('button.hapus') }}</span> <i class="{{ __('icon.delete') }}"></i>
                                                        </a>
                                                    @endif
                                                </div>
                                                <form method="POST" action="{{ route('penalty.delete') }}" class="delete-form-list">
                                                    {{ csrf_field() }}
                                                    {{ method_field('DELETE') }}
                                                    <input type="hidden" name="id" value="{{ $penalty->id }}">
                                                    <input type="hidden" name="quarters_cat_id" value="{{ $category->id }}">
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
@section('script')

@endsection
