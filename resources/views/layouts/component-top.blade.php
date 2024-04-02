
@php
    $menu_id = Session::get('menu_id');
    $submenu_id = Session::get('submenu_id');
    $lbl_menu = Session::get('lbl_menu');
    $lbl_submenu = Session::get('lbl_submenu');
    $route_name = Session::get('route_name');

@endphp
@if ($menu_id!="")
    @component('components.breadcrumb')

        @slot('title') <a href="{{ route('submenu', ['mid' => $menu_id ]) }}" > {{ $lbl_menu }} </a> @endslot
        @slot('li_1')
        @if ($route_name!=""&&Route::has($route_name))<a href="{{ route($route_name, ['smid' => $submenu_id]) }}" > {{ $lbl_submenu }} </a>@endif
        @endslot

    @endcomponent
@endif

@component('components.alert')@endcomponent

@component('components.view-attachment')@endcomponent
