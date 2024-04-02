@extends('layouts.master')

@section('content')

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">

                <div class="border-bottom border-primary mb-4"><h4 class="card-title">Notifikasi</h4></div>

                <div id="datatable_wrapper">
                    <div class="row">
                        <div class="col-sm-12">
                            <table id="datatable" class="table table-bordered dt-responsive wrap w-100 dataTable" role="grid" >
                                <thead class="bg-primary bg-gradient text-white">
                                    <tr role="row">
                                        <th class="text-center" width="4%">Bil</th>
                                        <th class="text-center" >Tajuk</th>
                                        <th class="text-center" >Butiran</th>
                                        <th class="text-center" >Dihantar Pada</th>
                                        <th class="text-center" >Status Notifikasi</th>
                                        <th class="text-center" width="10%">Pilih</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($notifications as $i => $notification )
                                        @php $status_notifikasi = ($notification["read_at"]) ? 'Telah Dibaca' : 'Belum Dibaca'; @endphp
                                        <tr class="odd">
                                            <td class="text-center" tabindex="0">{{ $loop->iteration }}</td>
                                            <td class=""> {{ $notification['data']["title"] }}</td>
                                            <td class=""> {{ $notification['data']["body"] }}</td>
                                            <td class=""> {{ convertDateTimeSys($notification["created_at"]) }}</td>
                                            <td class=""> {{ $status_notifikasi }}</td>
                                            <td class="text-center">
                                                <a class="btn btn-outline-primary px-2 py-1 tooltip-icon swal-delete-list">
                                                    <span class="tooltip-text">{{ __('button.hapus') }}</span> <i class="{{ __('icon.delete') }}"></i>
                                                </a>
                                                <form method="POST" action="{{ route('notification.deleteByRow') }}" class="delete-form-list">
                                                    {{ csrf_field() }}
                                                    {{ method_field('DELETE') }}
                                                    <input type="hidden" name="id" value="{{ $notification->id }}">
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>


                {{-- <div id="datatable_wrapper">
                    <div class="row">
                        <div class="col-sm-12">
                            <ul class="list-group list-group-flush">
                                @foreach ($notifications as $notification)
                                    <li class="list-group-item py-3">
                                        <div class="d-flex">
                                            <div class="flex-shrink-0 me-3">
                                                <div class="avatar-xs">
                                                    <div class="avatar-title rounded-circle bg-light text-primary">
                                                        <i class="bx bxs-user"></i>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="flex-grow-1">
                                                <h5 class="font-size-14 mb-1">{{  $notification['data']["title"] }}</h5>
                                                <div>
                                                    @isset($notification['data']["action"] )
                                                        <a href="javascript: void(0);" class="text-success"><i class="mdi mdi-reply me-1"></i>
                                                            {{ $notification['data']["action"] }}
                                                        </a>
                                                    @endisset
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div> --}}

            </div>
        </div>
    </div> <!-- end col -->
</div>
<!-- end row -->

@endsection
