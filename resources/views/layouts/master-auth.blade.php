
<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

    <head>
        <meta charset="utf-8" />
        <title> @lang('lang.nama_sistem') </title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <!-- App favicon -->
        <link rel="shortcut icon" href="{{ URL::asset('assets/images/jata-johor.ico')}}">
        @include('layouts.head-css')

        {{-- page specific css --}}
        @yield('file-css')

  </head>
  <body class="auth-body-bg">

    @include('layouts.content-auth')

    @include('layouts.vendor-scripts')

    @yield('file-js')

    </body>
</html>
