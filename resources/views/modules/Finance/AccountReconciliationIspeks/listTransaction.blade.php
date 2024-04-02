@extends('layouts.master')

@section('content')

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">

            <form  class="custom-validation" id="form" method="post" action="{{ route('accountReconciliationIspeks.processPayment', ['year' => $year, 'month' => $month]) }}" >
                {{ csrf_field() }}
                {{-- PAGE TITLE --}}
                <div class="border-bottom border-primary mb-4"><h4 class="card-title">{{ getPageTitle(2) }}</h4></div>

                <p>Tahun/Bulan Notis Bayaran : {{ $year }}/{{ $month }}</p>

                <div class="row mb-2">
                    <div class="col-sm-12">
                        @if(checkPolicy("U") && $is_approver_officer)
                            @if($accountReconciliationAll->count()>0)
                                <button type="submit" class="btn btn-success float-end waves-effect waves-light me-2 swal-approve">{{ __('button.pengesahan') }}</button>
                            @endif
                        @endif
                        @if(checkPolicy("A"))
                            <a href="{{ route('accountReconciliationIspeks.create', ['year' => $year, 'month' => $month]) }}" class="btn btn-success float-end waves-effect waves-light me-2">{{ __('button.rekod_baru') }}</a>
                        @endif

                    </div>
                </div>

                <div id="datatable_wrapper">
                    <div class="row">
                        <div class="col-sm-12">
                            <table id="datatable" class="table-list-ispeks table table-striped table-bordered dt-responsive nowrap w-100 dataTable" role="grid" >
                                <thead class="bg-primary bg-gradient text-white">
                                    <tr role="row">
                                        <th class="text-center" width="4%">Bil</th>
                                        <th class="text-center">Tahun/Bulan Notis</th>
                                        <th class="text-center">Kod Potongan</th>
                                        <th class="text-center">Kaedah Bayaran</th>
                                        <th class="text-center">Nama Fail</th>
                                        <th class="text-center">Status Proses</th>
                                        <th class="text-center">Tarikh Bayaran</th>
                                        <th class="text-center">No. Resit Bayaran</th>
                                        <th class="text-center" width="10%">Tindakan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $salary_deduction_code_arr = [];
                                    @endphp
                                    @foreach ($accountReconciliationAll as $i => $data)
                                        @php
                                            $process_status = ($data->data_status==2) ? 'Belum Disahkan' : 'Telah Disahkan';
                                            $badge = ($data->data_status==2) ? 'bg-danger' : 'bg-success';
                                        @endphp
                                        <tr class="odd">
                                            <td class="text-center" tabindex="0">{{ $loop->iteration }}</td>
                                            <td class="text-center">{{ $data->year }} / {{ $data->month }}</td>
                                            <td class="text-center">{{ $data->salary_deduction_code }}
                                                <input type="hidden" name="salary_deduction_code[]" value="{{ $data->salary_deduction_code }}" >
                                                <input type="hidden" name="reconciliation_transaction_id[]" value="{{ $data->id }}" >
                                            </td>
                                            <td class="text-center">{{ $data->payment_method?->payment_method }}
                                                <input type="hidden" name="payment_method_id[]" value="{{ $data->payment_method_id }}" >
                                            </td>
                                            <td class="align-left">{{ $data->file_name }}</td>
                                            <td class="text-center"><span class="p-1 badge {{ $badge }}">{{ Str::upper($process_status)  }}</span></td>
                                            <td class="text-center">{{ convertDateSys($data->payment_date) }}</td>
                                            <td class="text-center">{{ $data->payment_receipt_no }}</td>
                                            <td class="text-center">
                                                <div class="btn-group" role="group">
                                                    @if(checkPolicy("V"))
                                                        <a href="{{ route('accountReconciliationIspeks.view', ['id' => $data->id, 'year' => $year, 'month' => $month]) }}" class="btn btn-outline-primary px-2 py-1 tooltip-icon">
                                                            <span class="tooltip-text">{{ __('button.papar') }}</span> <i class="{{ __('icon.view_folder') }}"></i>
                                                        </a>
                                                    @endif
                                                    @if(checkPolicy("D") && $data->data_status==2)
                                                        <input type="hidden" id="item_id{{$i}}" value="{{ $data->id }}">
                                                        <input type="hidden" id="kod_potongan{{$i}}" value="{{ $data->salary_deduction_code }}">
                                                        <a id="btnRemove{{$i}}" class='btnRemove btn btn-outline-primary btn-sm' data-row-index="{{$i}}"><i class="{{ __('icon.delete') }}"></i></a>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>

                            <div class="mt-3 row">
                                <div class="col-sm-12">
                                    <a href="{{ route('accountReconciliationIspeks.listYearMonth', ['year' => $year, 'month' => $month]) }}" class="btn btn-secondary float-end me-2">{{ __('button.kembali') }}</a>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

            </form>

            <form method="POST" action="{{ route('accountReconciliationIspeks.deleteByRow') }}" id="delete-form-by-row">
                {{ csrf_field() }}
                {{ method_field('DELETE') }}
                <input type="hidden" id="id_by_row" name="id_by_row" >
                <input type="hidden" id="kod_potongan" name="kod_potongan" >
                <input type="hidden" name="year" value="{{ $year }}">
                <input type="hidden" name="month" value="{{ $month }}">
            </form>

            </div>
        </div>
    </div> <!-- end col -->
</div>
<!-- end row -->

@endsection

@section('script')
<script src="{{ URL::asset('assets/js/pages/AccountReconciliationIspeks/accountReconciliationIspeks.js')}}"></script>
@endsection
