
@extends('layouts.master')

@section('content')

<div class="row">
    <div class="col-12">

         <!-- section - list report -->
         <div class="card">
            <div class="card-body">

                {{-- PAGE TITLE --}}
                <div class="border-bottom border-primary mb-4"><h4 class="card-title">{{ getPageTitle(1) }}</h4></div>

                <div id="datatable_wrapper">
                    <div class="row">
                        <div class="col-sm-12">

                            @foreach($landedTypeAll as $i_LandedType => $dataLandedType)
                                <table class="table table-bordered dt-responsive wrap w-100 mb-4" >
                                    <thead class="bg-primary bg-gradient text-white">
                                        <tr role="row">
                                            <th class="text-center">Jenis Rumah</th>
                                            <th class="text-center" colspan="4">{{ $dataLandedType->type }}</th>
                                        </tr>
                                        <tr role="row">
                                            <th class="text-center">Kategori Kuarters (Lokasi)</th>
                                            <th width="12%" class="text-center">Jumlah Kuarters</th>
                                            <th width="12%" class="text-center">Jumlah Kuarters Dihuni</th>
                                            <th width="12%" class="text-center">Jumlah Kuarters Kosong</th>
                                            <th width="20%" class="text-center">Jumlah Yuran Penyelenggaraan (RM)</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $total_sum_quarters = 0;
                                            $total_sum_quarters_with_tenant = 0;
                                            $total_sum_available_quarters = 0;
                                            $total_sum_maintenance_fee = 0;
                                        @endphp
                                        @if(isset($maintenanceFeebyQuartersCategoryArr[$i_LandedType]))
                                            @foreach($maintenanceFeebyQuartersCategoryArr[$i_LandedType] as $i => $data)
                                                @php
                                                    $quarters_category_name = $data['quarters_category_name'];
                                                    $sum_quarters = $data['sum_quarters'];
                                                    $sum_quarters_with_tenant = $data['sum_quarters_with_tenant'];
                                                    $sum_available_quarters = $data['sum_available_quarters'];
                                                    $sum_maintenance_fee = $data['sum_maintenance_fee'];

                                                    $total_sum_quarters += $sum_quarters;
                                                    $total_sum_quarters_with_tenant += $sum_quarters_with_tenant;
                                                    $total_sum_available_quarters += $sum_available_quarters;
                                                    $total_sum_maintenance_fee += $sum_maintenance_fee;
                                                @endphp
                                                <tr>
                                                    <td >{{ $quarters_category_name }}</a></td>
                                                    <td class="text-center" >{{ $sum_quarters }}</a></td>
                                                    <td class="text-center" >{{ $sum_quarters_with_tenant }}</a></td>
                                                    <td class="text-center" >{{ $sum_available_quarters }}</a></td>
                                                    <td class="text-center" >{{ numberFormatComma($sum_maintenance_fee) }}</a></td>
                                                </tr>

                                            @endforeach
                                        @endif
                                        <tr class="bg-light fw-bolder">
                                            <td class="text-center" >Jumlah Keseluruhan</a></td>
                                            <td class="text-center" >{{ $total_sum_quarters }}</a></td>
                                            <td class="text-center" >{{ $total_sum_quarters_with_tenant }}</a></td>
                                            <td class="text-center" >{{ $total_sum_available_quarters }}</a></td>
                                            <td class="text-center" >{{ numberFormatComma($total_sum_maintenance_fee) }}</a></td>
                                        </tr>

                                    </tbody>
                                </table>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- end section - list report -->

    </div> <!-- end col -->

</div>
<!-- end row -->

@endsection

