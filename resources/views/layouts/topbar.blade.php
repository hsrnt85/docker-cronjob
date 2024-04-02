<header id="page-topbar">
    <div class="navbar-header">
        <div class="d-flex">

            <button type="button" class="btn btn-sm px-3 font-size-16 header-item waves-effect" id="vertical-menu-btn">
                <i class="fa fa-fw fa-bars"></i>
            </button>

        </div>

        <div class="d-flex">

            <!-- SECTION - NOTIFICATION -->
            <div class="dropdown d-inline-block">
                <button type="button" class="btn header-item noti-icon waves-effect" id="page-header-notifications-dropdown"
                    data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-url="{{ route('notification.ajaxGetNotification') }}">
                    <i class="bx bx-bell bx-tada"></i>
                    <span class="badge bg-danger rounded-pill" id="unread-notification-total"></span>
                </button>
                <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end p-0"
                    aria-labelledby="page-header-notifications-dropdown">
                    <div class="border-bottom p-3">
                        <div class="row align-items-center">
                            <div class="col">
                                <h6 class="m-0" key="t-notifications"> Notifikasi </h6>
                            </div>
                            <div class="col-auto">
                                <!-- <a href="#!" class="small" key="t-view-all"> Papar</a> -->
                            </div>
                        </div>
                    </div>
                    <div data-simplebar style="max-height: 230px;" id="notification-container" data-mark-url="{{ route('notification.markAsRead') }}">

                    </div>
                    <div class="p-2 border-top d-grid">
                        <a class="btn btn-sm btn-link font-size-14 text-center" href="{{ route('notification.index') }}">
                            <i class="mdi mdi-arrow-right-circle me-1"></i> <span key="t-view-more">Papar Semua</span>
                        </a>
                    </div>
                </div>
            </div>

            <!-- SECTION - USER -->
            <div class="dropdown d-inline-block">
                <button type="button" class="btn header-item waves-effect" id="page-header-user-dropdown"
                    data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <img class="rounded-circle header-profile-user mb-4" src="{{ isset(Auth::user()->image) ? asset(Auth::user()->image) : asset('/assets/images/users/avatar-1.jpg') }}"
                        alt="Header Avatar">
                    <span class="d-none d-xl-inline-block ms-2 mt-2 me-2">{{ capitalizeText(Auth::user()->name)}} <br> {{capitalizeText(Auth::user()->roles?->name)}}</span>
                    <i class="mdi mdi-chevron-down d-none d-xl-inline-block"></i>
                </button>
                <div class="dropdown-menu dropdown-menu-end">
                    <!-- item-->
                    <a class="dropdown-item" href="{{ route('user.viewByUser')}}"><i class="bx bx-user font-size-16 align-middle me-1"></i> <span key="t-profile">Maklumat Pengguna</span></a>
                    <div class="dropdown-divider"></div>

                    <a class="dropdown-item text-danger" href="javascript:void();" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i class="bx bx-power-off font-size-16 align-middle me-1 text-danger"></i> <span key="t-logout">Log Keluar</span></a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </div>
            </div>

        </div>
    </div>
</header>
