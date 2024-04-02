@extends('layouts.master')

@section('content')

<div class="row">

    <div class="col-xl-12">

        <div class="row">
            <div class="col-sm-12 p-1">
                <h5 class="text-primary">Statistik Bilangan Kuarters</h5>
            </div>
        </div>
        <div class="row">
            <div class="col-md-3 p-1" id="kuarters-boleh-diduduki" data-route="{{ route('dashboard.ajaxGetQuartersNoByCondition') }}" flag="1">
                <div class="card mini-stats-wid">
                    <div class="card-body">
                        <div class="d-flex">
                            <div class="flex-grow-1">
                                <p class="text-muted fw-medium">Boleh Diduduki</p>
                                <h4 class="mb-0" id="val-boleh-diduduki">0</h4>
                            </div>

                            <div class="flex-shrink-0 align-self-center">
                                <div class="mini-stat-icon avatar-sm rounded-circle ">
                                    <span class="avatar-title bg-success">
                                        <i class="fa fa-home font-size-24"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div> 

            <div class="col-md-3 p-1" id="kuarters-tidak-boleh-diduduki" data-route="{{ route('dashboard.ajaxGetQuartersNoByCondition') }}" flag="2" >
                <div class="card mini-stats-wid">
                    <div class="card-body">
                        <div class="d-flex">
                            <div class="flex-grow-1">
                                <p class="text-muted fw-medium">Tidak Boleh Diduduki</p>
                                <h4 class="mb-0" id="val-tidak-boleh-diduduki">0</h4>
                            </div>

                            <div class="flex-shrink-0 align-self-center">
                                <div class="avatar-sm rounded-circle  mini-stat-icon">
                                    <span class="avatar-title rounded-circle bg-warning">
                                        <i class="fa fa-home font-size-24"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div> 

            <div class="col-md-3 p-1" id="bil-kuarters-jumlah">
                <div class="card mini-stats-wid">
                    <div class="card-body">
                        <div class="d-flex">
                            <div class="flex-grow-1">
                                <p class="text-muted fw-medium">Jumlah Kuarters</p>
                                <h4 class="mb-0">0</h4>
                            </div>

                            <div class="flex-shrink-0 align-self-center">
                                <div class="avatar-sm rounded-circle  mini-stat-icon">
                                    <span class="avatar-title rounded-circle bg-primary">
                                        <i class="fa fa-home font-size-24"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div> 
        </div>

        <div class="row">
            <div class="col-sm-12 p-1">
                <h5 class="text-primary">Statistik Penghuni Kuarters</h5>
            </div>
        </div>
        <div class="row">
            <div class="col-md-3 p-1" id="berpenghuni" data-route="{{ route('dashboard.ajaxGetTenantAll') }}">
                <div class="card mini-stats-wid">
                    <div class="card-body">
                        <div class="d-flex">
                            <div class="flex-grow-1">
                                <p class="text-muted fw-medium">Berpenghuni</p>
                                <h4 class="mb-0" id="val-berpenghuni">0</h4>
                            </div>

                            <div class="flex-shrink-0 align-self-center">
                                <div class="mini-stat-icon avatar-sm rounded-circle ">
                                    <span class="avatar-title bg-success">
                                        <i class="bx bx-user-check font-size-24"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3 p-1" id="kosong" data-route="{{ route('dashboard.ajaxGetQuartersNoByCondition') }}" flag="1">
                <div class="card mini-stats-wid">
                    <div class="card-body">
                        <div class="d-flex">
                            <div class="flex-grow-1">
                                <p class="text-muted fw-medium">Tidak Berpenghuni</p>
                                <h4 class="mb-0" id="val-tidak-berpenghuni">0</h4>
                            </div>

                            <div class="flex-shrink-0 align-self-center">
                                <div class="avatar-sm rounded-circle  mini-stat-icon">
                                    <span class="avatar-title rounded-circle bg-warning">
                                        <i class="bx bx-user-x font-size-24"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <div class="row">
            <div class="col-sm-12 p-1" id="kuarters-selenggara" data-route="{{ route('dashboard.ajaxGetQuartersByCondition') }}">
                <h5 class="text-primary">Statistik Penyelenggaraan</h5>
            </div>
        </div>
        <div class="row">
            @for ($i=1;$i<=2;$i++)
                @php
                    if($i==1){ $label = "Sedang Diselenggara"; $bg_color = "bg-warning"; }
                    else if($i==2){ $label = "Rosak"; $bg_color = "bg-danger"; }
                @endphp
                <div class="col-md-3 p-1" id="kuarters-selenggara-{{$i}}" >
                    <div class="card mini-stats-wid">
                        <div class="card-body">
                            <div class="d-flex">
                                <div class="flex-grow-1">
                                    <p class="text-muted fw-medium label">{{$label}}</p>
                                    <h4 class="mb-0">0</h4>
                                </div>
    
                                <div class="flex-shrink-0 align-self-center">
                                    <div class="avatar-sm rounded-circle  mini-stat-icon">
                                        <span class="avatar-title rounded-circle {{$bg_color}}">
                                            <i class="fa fa-home font-size-24"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endfor
        </div>

        {{--------------------------------------------------------------------------------------------------------------------}}
        {{-- STATISTIK ADUAN AWAM --}}
        {{--------------------------------------------------------------------------------------------------------------------}}
        <div class="row">
            <div class="col-sm-12 p-1" id="aduan-awam" data-complaint-type="1" data-route="{{ route('dashboard.ajaxGetComplaint') }}">
                <h5 class="text-primary">Statistik Aduan Awam</h5>
            </div>
        </div>
        <div class="row">
            @for ($i=1;$i<=4;$i++)
                @php
                    if($i==1){ $label = "Aduan  Baru "; $bg_color = "bg-danger"; }
                    else if($i==2){ $label = "Dalam Tindakan"; $bg_color = "bg-warning"; }
                    else if($i==3){ $label = "Aduan Ditolak"; $bg_color = "bg-secondary"; }
                    else if($i==4){ $label = "Aduan Selesai"; $bg_color = "bg-success"; }
                @endphp
                <div class="col-md-2 p-1" id="aduan-awam-{{$i}}" >
                    <div class="card mini-stats-wid">
                        <div class="card-body">
                            <div class="d-flex">
                                <div class="flex-grow-1">
                                    <p class="text-muted fw-small">{{$label}}</p>
                                    <h4 class="mb-0">0</h4>
                                </div>
    
                                <div class="flex-shrink-0 align-self-center">
                                    <div class="avatar-sm rounded-circle  mini-stat-icon">
                                        <span class="avatar-title rounded-circle {{$bg_color}}">
                                            <i class="fa fa-file font-size-24"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endfor
            <div class="col-md-2 p-1" id="aduan-awam-jumlah" >
                <div class="card mini-stats-wid">
                    <div class="card-body">
                        <div class="d-flex">
                            <div class="flex-grow-1">
                                <p class="text-muted fw-small">Jumlah Aduan</p>
                                <h4 class="mb-0">0</h4>
                            </div>

                            <div class="flex-shrink-0 align-self-center">
                                <div class="avatar-sm rounded-circle  mini-stat-icon">
                                    <span class="avatar-title rounded-circle bg-primary">
                                        <i class="fa fa-file font-size-24"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{--------------------------------------------------------------------------------------------------------------------}}
        {{-- END STATISTIK ADUAN AWAM --}}
        {{--------------------------------------------------------------------------------------------------------------------}}

        {{--------------------------------------------------------------------------------------------------------------------}}
        {{-- STATISTIK ADUAN KEROSAKAN --}}
        {{--------------------------------------------------------------------------------------------------------------------}}
        <div class="row">
            <div class="col-sm-12 p-1" id="aduan-kerosakan" data-complaint-type="2" data-route="{{ route('dashboard.ajaxGetComplaint') }}">
                <h5 class="text-primary">Statistik Aduan Kerosakan</h5>
            </div>
        </div>
        <div class="row">
            @for ($i=1;$i<=4;$i++)
                @php
                    if($i==1){ $label = "Aduan  Baru "; $bg_color = "bg-danger"; }
                    else if($i==2){ $label = "Dalam Tindakan"; $bg_color = "bg-warning"; }
                    else if($i==3){ $label = "Aduan Ditolak"; $bg_color = "bg-secondary"; }
                    else if($i==4){ $label = "Aduan Selesai"; $bg_color = "bg-success"; }
                @endphp
                <div class="col-md-2 p-1" id="aduan-kerosakan-{{$i}}" >
                    <div class="card mini-stats-wid">
                        <div class="card-body">
                            <div class="d-flex">
                                <div class="flex-grow-1">
                                    <p class="text-muted fw-small">{{$label}}</p>
                                    <h4 class="mb-0">0</h4>
                                </div>
    
                                <div class="flex-shrink-0 align-self-center">
                                    <div class="avatar-sm rounded-circle  mini-stat-icon">
                                        <span class="avatar-title rounded-circle {{$bg_color}}">
                                            <i class="fa fa-file font-size-24"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endfor
            <div class="col-md-2 p-1" id="aduan-kerosakan-jumlah" >
                <div class="card mini-stats-wid">
                    <div class="card-body">
                        <div class="d-flex">
                            <div class="flex-grow-1">
                                <p class="text-muted fw-small">Jumlah Aduan</p>
                                <h4 class="mb-0">0</h4>
                            </div>

                            <div class="flex-shrink-0 align-self-center">
                                <div class="avatar-sm rounded-circle  mini-stat-icon">
                                    <span class="avatar-title rounded-circle bg-primary">
                                        <i class="fa fa-file font-size-24"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{--------------------------------------------------------------------------------------------------------------------}}
        {{-- END STATISTIK ADUAN KEROSAKAN --}}
        {{--------------------------------------------------------------------------------------------------------------------}}
       
    </div>
</div>
<!-- end row -->

@endsection
@section('script')
<!-- apexcharts -->
<script src="{{ URL::asset('/assets/libs/apexcharts/apexcharts.min.js') }}"></script>

<!-- dashboard init -->
<script src="{{ URL::asset('/assets/js/libs/dashboard.init.js') }}"></script>
@endsection
