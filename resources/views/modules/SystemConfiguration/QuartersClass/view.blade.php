@extends('layouts.master')

@section('content')

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">

                {{-- PAGE TITLE --}}
                <div class="border-bottom border-primary mb-4"><h4 class="card-title">{{ getPageTitle(1) }}</h4></div>

                <form method="post" action="{{ route('quartersClass.update') }}" >
                    {{ csrf_field() }}
                    <input type="hidden" name="id" value="{{ $quartersClass->id }}">
                    <div class="mb-3 row">
                        <label class="col-md-2 col-form-label">Kelas Kuarters</label>
                        <div class="col-md-10">
                            <p class="col-form-label">{{ $quartersClass->class_name }}</p>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label class="col-md-2 col-form-label">Daerah</label>
                        <div class="col-md-10">
                            <p class="col-form-label">{{ $quartersClass->district->district_name }}</p>
                        </div>
                    </div>
                    <hr>
                    <h4 class="card-title">Maklumat Sewa</h4>

                    <div id="datatable_wrapper">
                        <div class="row">
                            <div class="col-sm-12">
                                <table id="datatable" class="table table-bordered dt-responsive nowrap w-100 dataTable" role="grid">
                                    <thead class="bg-primary bg-gradient text-white">
                                        <tr role="row">
                                            <th class="text-center">Bil</th>
                                            <th class="text-center">Gred Jawatan</th>
                                            <th class="text-center">Kategori</th>
                                            <th class="text-center">Harga Pasaran (RM)</th>
                                            <th class="text-center">Sewa (RM)</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if($classGradeAll->count() != 0)
                                            @foreach($classGradeAll as $classGrade)
                                                <tr>
                                                    <th scope="row" class="text-center">{{$loop->iteration}}</th>
                                                    <td class="text-center">{{$classGrade->positionGrade->grade_no}}</td>
                                                    <td>{{$classGrade->servicesType?->services_type}}</td>
                                                    <td>{{$classGrade->market_rental_amount}}</td>
                                                    <td>{{$classGrade->rental_fee}}</td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr class="text-center"><td colspan="4">Tiada rekod</td></tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <div class="mb-3 row">
                        <div class="col-sm-12">
                            <!-- <a href="{{ route('quartersClass.edit', ['id' => $quartersClass->id]) }}" class="btn btn-primary float-end">{{ __('button.kemaskini') }}</a> -->
                            {{-- <a class="btn btn-danger float-end me-2 swal-delete">{{ __('button.hapus') }}</a> --}}
                            <a href="{{ route('quartersClass.index') }}" class="btn btn-secondary float-end me-2">{{ __('button.kembali') }}</a>
                        </div>
                    </div>
                </form>

                <form method="POST" action="{{ route('quartersClass.delete') }}" id="delete-form">
                    {{ csrf_field() }}
                    {{ method_field('DELETE') }}
                    <input class="form-control" type="hidden" id="id-delete" name="id" value="{{ $quartersClass->id }}">
                </form>
            </div>
        </div>
    </div> <!-- end col -->
</div>
<!-- end row -->

@endsection
