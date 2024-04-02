@extends('layouts.master')

@section('content')

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                
                {{-- PAGE TITLE --}}
                <div class="border-bottom border-primary mb-4"><h4 class="card-title">{{ getPageTitle(1) }}</h4></div>
   
                    <form id="form" method="post" action="{{ route('accountReconciliationIgfmas.processPayment', ['year' => $year, 'month' => $month]) }}" enctype="multipart/form-data">
                        
                        {{ csrf_field() }}
                        <div class="mb-3 row">
                            <label class="col-md-2 mt-2">Tahun / Bulan Proses Gaji</label>
                            <div class="col-md-10">
                                <p class="col-form-label">{{ $year }} / {{ $month }}</p>
                            </div>
                        </div>

                        <div id="datatable_wrapper">
                            <div class="row">
                                <div class="col-sm-12">
                                    <table class="table table-bordered dt-responsive nowrap w-100 dataTable" role="grid" >
                                        <thead class="bg-primary bg-gradient text-white">
                                            <tr role="row">
                                                <th class="text-center" width="4%">Bil</th>
                                                <th class="text-center">Kod Potongan</th>
                                                <th class="text-center">Jumlah Bayaran (RM)</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if($salaryDeductionCodeSummary)
                                                @foreach ($salaryDeductionCodeSummary as $i => $data)
                                                    <tr >
                                                        <td class="text-center">{{ $loop->iteration }}</td>
                                                        <td class="text-center">{{ $data->salary_deduction_code }}</td>
                                                        <td class="text-center">{{ numberFormatComma($data->amount) }}</td>
                                                        {{-- <td class="text-center"></td> --}}
                                                    </tr>
                                                @endforeach
                                                <tr>
                                                    <td class="right bold" colspan="2" >Jumlah Keseluruhan</td>
                                                    <td class="text-center" colspan="1" >{{ numberFormatComma($salaryDeductionCodeSummary->sum('amount')) }}</td>
                                                </tr>
    
                                            @endif
                                        </tbody>
                                    </table>
                                    
                                </div>
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <div class="col-sm-12">
                                <button type="submit" class="btn btn-primary float-end swal-approve">{{ __('button.pengesahan') }}</button>
                                <a href="{{ route('accountReconciliationIgfmas.listTransaction', ['year' => $year, 'month' => $month]) }}" class="btn btn-secondary float-end me-2">{{ __('button.kembali') }}</a>
                            </div>
                        </div>
                    
                    </form>
              
            </div>
        </div>
    </div> <!-- end col -->
</div>
<!-- end row -->

@endsection

