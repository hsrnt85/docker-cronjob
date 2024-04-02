<body data-sidebar="colored">
    <!-- ========== Left Sidebar Start ========== -->
    <div class="vertical-menu" topbar="colored">
        <div data-simplebar class="h-100">
            <!--- Sidemenu -->
            <div id="sidebar-menu">
                <!-- Left Menu Start -->
                <ul class="metismenu list-unstyled" id="side-menu">

                    @if(!empty(Session::get('menu')))

                        @foreach(Session::get('menu') as $data)

                        <li>
                            <a href="{{ route('submenu', ['mid' => $data['menu_id'] ]) }}" class="btn-menu">
                                <i class="mdi mdi-play-box-outline"></i>
                                <span key="t-ecommerce"> {{  $data['menu']  }}</span>
                            </a>
                        </li>

                        @endforeach

                    @endif

                </ul>

            <!-- Sidebar -->
            </div>
        </div>
    </div>
</body>
<!-- Left Sidebar End -->
