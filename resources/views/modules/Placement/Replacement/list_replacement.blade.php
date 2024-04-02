@extends('layouts.master')

@section('content')

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">

                <h4 class="card-title mb-3">Pertukaran Penempatan</h4>
                <h4 class="card-title text-primary mb-3">{{ $category->name }}</h4>

                <!-- TABS -->
                <ul class="nav nav-tabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" data-bs-toggle="tab" href="#application-placement" role="tab">
                            <span class="d-none d-sm-block">Pertukaran</span>
                        </a>
                    </li>
                </ul>

                <!-- TABS CONTENT-->
                <div class="tab-content p-0">

                    <div class="tab-pane active" id="application-placement" role="tabpanel">

                        {{-- SECTION - APPLICATION PLACEMENT --}}
                        <div id="datatable_wrapper">
                            <div class="row">
                                <div class="mt-4 col-sm-12">
                                    <table id="datatable" class="table table-bordered dt-responsive nowrap w-100 dataTable" role="grid" >
                                        <thead class="bg-primary bg-gradient text-white">
                                            <tr role="row">
                                                <th class="text-center" width="4%">Bil</th>
                                                <th class="text-center" >Nama Pemohon</th>
                                                <th class="text-center" >No Unit</th>
                                                <th class="text-center" >Alamat</th>
                                                <th class="text-center" width="10%">Tindakan</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($tenantEligibleForReplacementAll as $tenantEligibleForReplacement)
                                                <tr class="odd">
                                                    <td class="text-center" tabindex="0">{{ $loop->iteration }}</td>
                                                    <td class="text-center">{{ $tenantEligibleForReplacement->user->name }} </td>
                                                    <td class="text-center">{{ $tenantEligibleForReplacement->quarters->unit_no  }} </td>
                                                    <td class="text-center">{{ $tenantEligibleForReplacement->quarters->address_1}} </td>
                                                    <td class="text-center">
                                                        <div class="btn-group" role="group">
                                                            <a href="{{ route('replacement.replacepage', ['tenant' => $tenantEligibleForReplacement->id]) }}" class="btn btn-outline-primary px-2 py-1 tooltip-icon">
                                                                <span class="tooltip-text">{{ __('button.tukar') }}</span><i class="{{ __('icon.edit') }}"></i>
                                                            </a>
                                                        </div>
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

            </div>
        </div>
    </div> <!-- end col -->
</div>
<!-- end row -->

@endsection
