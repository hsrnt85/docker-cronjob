@extends('layouts.master')

@section('content')

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">

                {{-- PAGE TITLE --}}
                <div class="border-bottom border-primary mb-4"><h4 class="card-title">{{ getPageTitle(1) }}</h4></div>

                <div id="datatable_wrapper">
                    <div class="row">
                        <div class="col-sm-12">
                            <table id="datatable" class="table table-striped table-bordered dt-responsive nowrap w-100 dataTable" role="grid" >
                                <thead class="bg-primary bg-gradient text-white">
                                    <tr role="row">
                                        <th class="text-center" width="4%">Bil</th>
                                        <th class="text-center">Tahun/Bulan Notis Bayaran</th>
                                        <th class="text-center" width="10%">Tindakan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($yearMonthAll as $i => $data)
                                        @php
                                            $disabled = (!empty($data->month) && $data->month < currentMonth()) ? 'disabled':'';
                                            $process_status = ($data->data_status == 1) ? "TELAH DIPROSES" : "BELUM PROSES";
                                            $flag_process = ($data->flag_process == 1) ? true : false;
                                            $flag_process_previous = ($i > 0) ? $yearMonthAll[$i-1]->flag_process : 0;
                                        @endphp
                                        <tr class="odd">
                                            <td class="text-center" tabindex="0">{{ $loop->iteration }}</td>
                                            <td class="align-left">{{ $data->year }} / {{ $data->month }}</td>
                                            <td class="text-center">
                                                <div class="btn-group" role="group">
                                                    @if(checkPolicy("U") && ($flag_process_previous || $loop->first)  && !$flag_process )
                                                        <a href="{{ route('accountReconciliationIgfmas.listTransaction', ['year' => $data->year, 'month' => $data->month]) }}" class="btn btn-outline-primary px-2 py-1 tooltip-icon">
                                                            <span class="tooltip-text">{{ __('button.kemaskini') }}</span> <i class="{{ __('icon.edit') }}"></i>
                                                        </a>
                                                    @endif
                                                    @if(checkPolicy("V") && $flag_process )
                                                        <a href="{{ route('accountReconciliationIgfmas.listTransaction', ['year' => $data->year, 'month' => $data->month]) }}" class="btn btn-outline-primary px-2 py-1 tooltip-icon">
                                                            <span class="tooltip-text">{{ __('button.papar') }}</span> <i class="{{ __('icon.view_folder') }}"></i>
                                                        </a>
                                                    @endif
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
    </div> <!-- end col -->
</div>
<!-- end row -->

@endsection
