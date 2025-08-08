<!--begin::Header-->
<nav class="app-header navbar navbar-expand bg-body">
    <!--begin::Container-->
    <div class="container-fluid">
        <!--begin::Start Navbar Links-->
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-lte-toggle="sidebar" href="#" role="button">
                    <i class="bi bi-list"></i>
                </a>
            </li>
        </ul>
        <!--end::Start Navbar Links-->

        <!--begin::End Navbar Links-->
        <ul class="navbar-nav ms-auto">
            <!--begin::Clock Display-->
            <li class="nav-item d-flex align-items-center px-3">
                <div class="text-end small">
                    <div class="fw-bold" id="clock"></div>
                    <div id="date" class="text-muted"></div>
                </div>
            </li>
            <!--end::Clock Display-->

            <!--begin::Fullscreen Toggle-->
            <li class="nav-item">
                <a class="nav-link" href="#" data-lte-toggle="fullscreen">
                    <i data-lte-icon="maximize" class="bi bi-arrows-fullscreen"></i>
                    <i data-lte-icon="minimize" class="bi bi-fullscreen-exit" style="display: none;"></i>
                </a>
            </li>
            <!--begin::User Info and Logout-->
            <span class="nav-link d-flex align-items-center">
                <i class="fas fa-user me-1 text-muted"></i>
                {{ Auth::user()->pegawai->nama ?? Auth::user()->username }}
            </span>

            <!-- Logout -->
            <form action="{{ route('logout') }}" method="POST" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-sm btn-outline-danger">
                    <i class="fas fa-sign-out-alt me-1"></i> Logout
                </button>
            </form>
            <!--end::User Info and Logout-->

            <!--end::User Menu Dropdown-->
        </ul>
    </div>

    <!--end::Container-->
</nav>
<!--end::Header-->

@push('scripts')
<script>
    function updateClock() {
    const now = new Date();
    const clock = now.toLocaleTimeString();
    const date = now.toLocaleDateString('id-ID', {
        weekday: 'long',
        day: 'numeric',
        month: 'long',
        year: 'numeric'
    });
    document.getElementById('clock').textContent = clock;
    document.getElementById('date').textContent = date;
}
setInterval(updateClock, 1000);
updateClock();
</script>
@endpush