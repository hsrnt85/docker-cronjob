@extends('layouts.master')

{{-- @section('file-css')
    <link href="{{ URL::asset('/assets/libs/rwd-table/rwd-table.min.css') }}" rel="stylesheet" type="text/css" />
@endsection --}}

@section('content')

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                
                {{-- PAGE TITLE --}}
                <div class="border-bottom border-primary mb-4"><h4 class="card-title">{{ getPageTitle(1) }}</h4></div>
                
                <div class="card ">
                    <form id="form" method="post" >
                        {{ csrf_field() }}

                        <div class="mb-3 row">
                            <label class="col-md-2 mt-2">Tahun / Bulan Proses Gaji</label>
                            <div class="col-md-10">
                                <p class="col-form-label">{{ $year }} / {{ $month }}</p>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label class="col-md-2 col-form-label">Kod Potongan Gaji</label>
                            <div class="col-md-10">
                                <div class="col-md-3 mt-2"> {{ $accountReconciliation->salary_deduction_code }}</div>
                            </div>
                        </div>
                        
                        <div class="mb-3 row">
                            <label class="col-md-2 col-form-label">Nama Fail </label>
                            <div class="col-md-10">
                                <div class="col-md-10 mt-2">{{ $accountReconciliation->file_name }}</div>
                            </div>
                        </div>
                        
                        <div class="mb-3 row">
                            <div class="col-sm-12">
                                {{-- <button type="submit" class="btn btn-primary float-end swal-approve">{{ __('button.pengesahan') }}</button> --}}
                                <a href="{{ route('accountReconciliationIspeks.listTransaction', ['year' => $year, 'month' => $month]) }}" class="btn btn-secondary float-end me-2">{{ __('button.kembali') }}</a>
                            </div>
                        </div>
                    
                    </form>
                    
                    <div class="border-bottom border-primary mt-1"><h4 class="card-title">Senarai Penghuni</h4></div>

                    <div class="table-rep-plugin">
                    <div class="table-responsive mb-0 border-0" >
                    <div data-simplebar style="max-height: 1000px;">  
                    <div id="datatable_wrapper">
                        <div class="row">
                            <div class="col-sm-12">
                                <table class="table table-bordered dt-responsive nowrap w-100 dataTable" role="grid" >
                                    <thead class="bg-primary bg-gradient text-white">
                                        <tr role="row">
                                            <th class="text-center" width="4%">Bil</th>
                                            <th class="text-center">No. Kad Pengenalan</th>
                                            <th class="text-center">Nama Penghuni</th>
                                            <th class="text-center">Amaun Bayaran (RM)</th>
                                            <th class="text-center">No. Rujukan</th>
                                            <th class="text-center" width="10%">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if($accountReconciliationItem)
                                            @foreach ($accountReconciliationItem as $i => $data)
                                                @php
                                                    $process_status = ($data->data_status==2) ? 'Belum Disahkan' : 'Telah Disahkan';
                                                    $badge = ($data->data_status==2) ? 'bg-danger' : 'bg-success';
                                                @endphp
                                        
                                                <tr class="odd">
                                                    <td class="text-center">{{ $loop->iteration }}</td>
                                                    <td class="text-center">{{ $data->ic_no }}</td>
                                                    <td class="text-center">{{ $data->name }}</td>
                                                    <td class="text-center">{{ numberFormatComma($data->amount) }}</td>        
                                                    <td class="text-center">{{ $data->ref_no }}</td>
                                                    <td class="text-center"><span class="p-1 badge {{ $badge }}">{{ Str::upper($process_status)  }}</span></td>  
                                                </tr>
                                            @endforeach
                                            <tr>
                                                <td class="right bold" colspan="3" >Jumlah Keseluruhan</td>
                                                <td class="text-center" colspan="1" >{{ numberFormatComma($accountReconciliationItem->sum('amount')) }}</td>
                                            </tr>

                                        @endif
                                    </tbody>
                                </table>
                                
                            </div>
                        </div>
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

{{-- @section('script')
    <!-- Responsive Table js -->
    <script src="{{ URL::asset('/assets/libs/rwd-table/rwd-table.min.js') }}"></script>
    <!-- Init js -->
    <script src="{{ URL::asset('/assets/js/libs/table-responsive.init.js') }}"></script>
@endsection --}}
