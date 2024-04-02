@extends('layouts.master')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">

                    {{-- PAGE TITLE --}}
                    <div class="border-bottom border-primary mb-4">
                        <h4 class="card-title">{{ getPageTitle(2) }} </h4>
                    </div>

                    @if (checkPolicy('A'))
                        <div class="row mb-2">
                            <div class="col-sm-12">
                                <p class="fw-bold float-start">{{ $category->name }}</p>
                                <a type="button" href="{{ route('blacklistPenalty.create', $category) }}"
                                    class="btn btn-success float-end waves-effect waves-light">{{ __('button.rekod_baru') }}</a>
                            </div>
                        </div>
                    @endif

                    <div id="datatable_wrapper">
                        <div class="row">
                            <div class="col-sm-12">
                                <table id="datatable" class="table table-striped table-bordered dt-responsive w-100 dataTable" role="grid">
                                    <thead class="bg-primary bg-gradient text-white">
                                        <tr role="row">
                                            <th class="text-center" width="4%">Bil</th>
                                            <th class="text-center">No. Rujukan Denda</th>
                                            <th class="text-center">Nama Penghuni</th>
                                            <th class="text-center">Tarikh Hilang Kelayakan</th>
                                            <th class="text-center">Tarikh Kuatkuasa</th>
                                            <th class="text-center">Sebab Hilang Kelayakan</th>
                                            <th class="text-center">Amaun Denda (RM)</th>
                                            <th class="text-center" width="10%">Tindakan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($tenantsPenalty as $penalty)
                                            <tr class="odd">
                                                <td class="text-center" tabindex="0">{{ $loop->iteration }}</td>
                                                <td class="text-center">{{ $penalty->penalty_ref_no }}</td>
                                                <td class="text-center">{{ $penalty->tenant->user->name }}</td>
                                                <td class="text-center">{{ convertDateSys($penalty->tenant->blacklist_date) }}</td>
                                                <td class="text-center">{{ convertDateSys($penalty->execution_date) }}</td>
                                                <td class="text-center">{{ ($penalty->tenant?->reason?->blacklist_reason) ??  upperText($penalty->tenant?->blacklist_reason_others)}}</td>
                                                <td class="text-center">{{ $penalty->penalty_amount }}</td>

                                                <td class="text-center">
                                                    <div class="btn-group" role="group">
                                                        @if (checkPolicy('V'))
                                                            <a href="{{ route('blacklistPenalty.view', ['category' => $category->id, 'bp' => $penalty->id]) }}"
                                                                class="btn btn-outline-primary px-2 py-1 tooltip-icon">
                                                                <span class="tooltip-text">{{ __('button.papar') }}</span> <i class="{{ __('icon.view_folder') }}"></i>
                                                            </a>
                                                        @endif
                                                        {{-- @if (checkPolicy('U'))
                                                            <a href="{{ route('blacklistPenalty.edit', ['category' => $category->id, 'bp' => $penalty->id]) }}"
                                                                class="btn btn-outline-primary px-2 py-1 tooltip-icon">
                                                                <span class="tooltip-text">{{ __('button.kemaskini') }}</span> <i class="{{ __('icon.edit') }}"></i>
                                                            </a>
                                                        @endif
                                                        @if (checkPolicy('D'))
                                                            <a class="btn btn-outline-primary px-2 py-1 tooltip-icon swal-delete-list">
                                                                <span class="tooltip-text">{{ __('button.hapus') }}</span> <i class="{{ __('icon.delete') }}"></i>
                                                            </a>
                                                        @endif --}}
                                                    </div>
                                                    <form method="POST" action="{{ route('blacklistPenalty.destroy') }}" class="delete-form-list">
                                                        {{ csrf_field() }}
                                                        {{ method_field('DELETE') }}
                                                        <input type="hidden" name="id" value="{{ $penalty->id }}">
                                                        <input type="hidden" name="quarters_category_id" value="{{ $category->id }}">
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
