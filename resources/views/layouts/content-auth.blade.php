
<div>
    <div class="container-fluid p-0">
        <div class="row g-0">

            <div class="col-xl-8">
                <div class="auth-full-bg ">
                    <div class="w-100">
                        <div class="bg-overlay"></div>
                    </div>
                </div>
            </div>
            <!-- end col -->

            <div class="col-xl-4">
                <div class="auth-full-page-content p-md-5 p-4">
                    <div class="w-100">

                        <div class="d-flex flex-column h-100">

                            @include('layouts.component-top')
                            @yield('content')

                            <div class="auth-footer p-3 text-center">
                                <p class="mb-0">Hak Cipta © <script>document.write(new Date().getFullYear())</script> Kerajaan Negeri Johor.
                            </div>

                        </div>

                    </div>
                </div>
            </div>

            <!-- end col -->
        </div>
        <!-- end row -->
    </div>
    <!-- end container-fluid -->
</div>
