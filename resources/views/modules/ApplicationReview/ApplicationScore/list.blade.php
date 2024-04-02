@extends('layouts.master')

@section('content')

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">

                <h4 class="card-title">Senarai Semakan Permohonan</h4>

                <div id="datatable_wrapper">
                    <div class="row">
                        <div class="col-sm-12">
                            <table id="datatable" class="table table-striped table-bordered dt-responsive nowrap w-100 dataTable" role="grid" >
                                <thead class="bg-primary bg-gradient text-white">
                                    <tr role="row">
                                        <th class="text-center" >Bil</th>
                                        <th class="text-center" >Pemohon</th>
                                        <th class="text-center" >Kategori Kuarters (Lokasi)</th>
                                        <th class="text-center" >Tarikh</th>
                                        <th class="text-center" >Tindakan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($applicationAll as $application)
                                        <tr class="odd">
                                            <td class="text-center" tabindex="0">{{ $loop->iteration }}</td>
                                            <td class="text-center">{{ $application->user->name }}</td>
                                            <td class="text-center">{!! ArrayToStringList($application->quarters_category->pluck('name')->toArray()) !!}</td>
                                            <td class="text-center">{{ convertDateSys($application->application_date_time) }}</td>
                                            <td class="text-center">
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('applicationScoring.score', ['id' => $application->id, 'qcid' => $application->quarters_category_id]) }}" class="btn btn-outline-primary px-2 py-1"><i class="mdi mdi-pencil mdi-18px"></i></a>
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
