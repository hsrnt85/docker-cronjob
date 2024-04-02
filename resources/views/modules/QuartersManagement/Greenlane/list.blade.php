@extends('layouts.master')

@section('content')

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">

                <h4 class="card-title">Senarai Permohonan Greenlane</h4>

                <div class="row mb-2">
                    <div class="col-sm-12">
                        <a type="button" href="{{ route('applicationGreenlane.create') }}" class="btn btn-primary float-end waves-effect waves-light">Permohonan Baru</a>
                    </div>
                </div>
                <div id="datatable_wrapper">
                    <div class="row">
                        <div class="col-sm-12">
                            <table id="datatable" class="table table-bordered dt-responsive nowrap w-100 dataTable" role="grid" >
                                <thead class="bg-primary bg-gradient text-white">
                                    <tr role="row">
                                        <th class="text-center" >Bil</th>
                                        <th class="text-center" >Nama Pemohon</th>
                                        <th class="text-center" >No. Kad Pengenalan</th>
                                        <th class="text-center" >Kategori Kuarters (Lokasi)</th>
                                        <th class="text-center" >Tarikh Permohonan</th>
                                        <th class="text-center" >Status</th>
                                        <th class="text-center" >Tindakan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($applicationAll as $application)
                                        <tr class="odd">
                                            <td class="text-center align-middle" tabindex="0">{{ $loop->iteration }}</td>
                                            <td class="text-center align-middle">{{$application->user->name ??''}}</td>
                                            <td class="text-center align-middle">{{$application->user->new_ic ??''}}</td>
                                            <td class="text-center align-middle">{{$application->category->name ??''}}</td>
                                            <td class="text-center align-middle" >{{ $application ->action_on ->format("d/m/Y") ??'' }}</td>
                                            <td class="text-center align-middle" ><span class="badge bg-warning p-2 font-size-12">{{($application->is_draft) ? 'Draf' : $application->current_status->status->status }}</span></td>
                                            <td class="text-center ">
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('applicationGreenlane.view', ['id' => $application->id]) }}" class="btn btn-outline-primary px-2 py-1"><i class="mdi mdi-folder-search mdi-18px"></i></a>
                                                    @if($application->is_draft)
                                                        <a href="{{ route('applicationGreenlane.edit', ['id' => $application->id]) }}" class="btn btn-outline-primary px-2 py-1"><i class="mdi mdi-pencil mdi-18px"></i></a>
                                                    @endif
                                                    <a class="btn btn-outline-primary px-2 py-1 swal-delete-list"><i class="mdi mdi-delete mdi-18px"></i></a>
                                                </div>
                                                <form method="POST" action="{{ route('applicationGreenlane.delete') }}" class="delete-form-list">
                                                    {{ csrf_field() }}
                                                    {{ method_field('DELETE') }}
                                                    <input class="form-control" type="hidden" name="id" value="{{ $application->id }}">
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
