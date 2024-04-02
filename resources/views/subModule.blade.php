@extends('layouts.master')

@section('content')

<div class="row">

    @if(!empty(Session::get('submenu')))

        @foreach(Session::get('submenu') as $data )
            <div class="col-lg-4 p-1">
                <div class="card card-body card-dashboard">
                    <h4 class="card-title mt-0">{{ $data['submenu'] }}</h4>
                    <p class="card-text"></p>

                    @php
                        $route_name = $data['route_name'];
                    @endphp

                    @if($route_name!="" && Route::has($route_name))
                        <a href="{{ route(''. $route_name .'') }}" class="btn btn-primary waves-effect waves-light btn-submenu ">Teruskan</a>
                    @else
                        <a href="#" class="btn btn-primary waves-effect waves-light">Teruskan</a>
                    @endif

                </div>
            </div>
        @endforeach

    @endif

</div>
<!-- end row -->

@endsection
