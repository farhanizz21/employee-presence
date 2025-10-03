<!DOCTYPE html>
<html lang="en">
<!--begin::Head-->

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>Employee Presence</title>
    <!--begin::Accessibility Meta Tags-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
    <meta name="color-scheme" content="light dark">
    <meta name="theme-color" content="#007bff" media="(prefers-color-scheme: light)">
    <meta name="theme-color" content="#1a1a1a" media="(prefers-color-scheme: dark)">
    <!--end::Accessibility Meta Tags-->
    <!--begin::Primary Meta Tags-->
    <meta name="title" content="AdminLTE v4 | Dashboard">
    <meta name="author" content="ColorlibHQ">
    <meta name="description"
        content="AdminLTE is a Free Bootstrap 5 Admin Dashboard, 30 example pages using Vanilla JS. Fully accessible with WCAG 2.1 AA compliance.">
    <meta name="keywords"
        content="bootstrap 5, bootstrap, bootstrap 5 admin dashboard, bootstrap 5 dashboard, bootstrap 5 charts, bootstrap 5 calendar, bootstrap 5 datepicker, bootstrap 5 tables, bootstrap 5 datatable, vanilla js datatable, colorlibhq, colorlibhq dashboard, colorlibhq admin dashboard, accessible admin panel, WCAG compliant">
    <!--end::Primary Meta Tags-->
    <!--begin::Accessibility Features-->
    <!-- Skip links will be dynamically added by accessibility.js -->
    <meta name="supported-color-schemes" content="light dark">
    
    <!--end::Accessibility Features-->


    
    <!--begin::Required Plugin(AdminLTE)-->
    <link rel="stylesheet" href="{{ asset('adminlte/dist/css/adminlte.min.css') }}">
    <!--end::Required Plugin(AdminLTE)-->
    
    <!-- Custom fonts for this template-->
    
   
<!-- Font Awesome -->
<link rel="stylesheet" href="{{ asset('adminlte/plugins/fontawesome-free/css/all.min.css') }}">

<!-- Select2 -->
<link rel="stylesheet" href="{{ asset('adminlte/plugins/select2/css/select2.min.css') }}">

<!-- FullCalendar -->
<link rel="stylesheet" href="{{ asset('adminlte/plugins/fullcalendar/main.min.css') }}">



</head>
<!--end::Head-->
<!--begin::Body-->
<script src="{{ asset('adminlte/plugins/jquery/jquery.min.js') }}"></script>

<!-- Bootstrap 4 bundle (sudah ada Popper.js di dalamnya) -->
<script src="{{ asset('adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<body class="layout-fixed sidebar-expand-lg sidebar-open bg-body-tertiary">
    <!--begin::App Wrapper-->
    <div class="app-wrapper">

        @include('partials.navbar')
        @include('partials.sidebar')
        <!--begin::App Main-->
        <main class="app-main">
            <div class="container mt-3">

                {{-- Flash Message --}}
                @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                @endif

                @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                @endif

                {{-- Validation Errors --}}
                @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Terjadi kesalahan:</strong>
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                @endif

            </div>

            {{-- Konten utama halaman --}}
            @yield('content')
        </main>

        <!--end::App Main-->
        <!--begin::Footer-->
        <footer class="app-footer">
            <!--begin::To the end-->
            <!-- <div class="float-end d-none d-sm-inline">Anything you want</div> -->
            <!--end::To the end-->
            <!--begin::Copyright--> <strong>
                Copyright &copy;
            </strong>
            Employee Absence Management System
            <!--end::Copyright-->
        </footer>
        <!--end::Footer-->
    </div>
    <!--end::App Wrapper-->

    <!--begin::Script-->

    <!-- CSS -->
<link rel="stylesheet" href="{{ asset('adminlte/dist/css/daterangepicker.css') }}">

<!-- JS -->
<script src="{{ asset('adminlte/dist/js/moment.min.js') }}"></script>
<script src="{{ asset('adminlte/dist/js/daterangepicker.min.js') }}"></script>

    
    
    <!--begin::Required Plugin(AdminLTE)-->
    <!-- <script src="{{ asset('adminlte/dist/js/adminlte.js') }}"></script> -->
    
    <!--end::Required Plugin(AdminLTE)-->
<!-- jQuery -->
<!-- <script src="{{ asset('adminlte/plugins/jquery/jquery.min.js') }}"></script> -->
<!-- Bootstrap Bundle -->
<!-- <script src="{{ asset('adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script> -->
<!-- OverlayScrollbars JS -->
<script src="{{ asset('adminlte/plugins/overlayScrollbars/js/OverlayScrollbars.min.js') }}"></script>
<!-- AdminLTE App -->
<script src="{{ asset('adminlte/dist/js/adminlte.min.js') }}"></script>

<!-- OverlayScrollbars & Tooltip Init -->
<script>
document.addEventListener("DOMContentLoaded", function () {
    // OverlayScrollbars init
    const sidebarWrapper = document.querySelector(".sidebar-wrapper");
    if (sidebarWrapper && typeof OverlayScrollbarsGlobal !== "undefined" && OverlayScrollbarsGlobal.OverlayScrollbars) {
        OverlayScrollbarsGlobal.OverlayScrollbars(sidebarWrapper, {
            scrollbars: {
                theme: "os-theme-light",
                autoHide: "leave",
                clickScroll: true,
            },
        });
    }

    // Bootstrap tooltip init
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});
</script>


<!-- OverlayScrollbars CSS -->
<link rel="stylesheet" href="{{ asset('adminlte/plugins/overlayScrollbars/css/OverlayScrollbars.min.css') }}">


<script src="{{ asset('adminlte/plugins/select2/js/select2.full.min.js') }}"></script>
<script src="{{ asset('adminlte/plugins/fullcalendar/main.min.js') }}"></script>




    @stack('scripts')

    <!--end::Script-->
</body>
<!--end::Body-->

</html>