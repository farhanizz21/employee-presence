<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ route('dashboard') }}">
        <div class="sidebar-brand-text mx-3">Employee-Presence</div>
    </a>

    <hr class="sidebar-divider my-0">

    <!-- Nav Item - Absensi -->
    <li class="nav-item {{ request()->is('absensi*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('absensi.index') }}">
            <i class="fas fa-fw fa-calendar-check"></i>
            <span>Absensi</span>
        </a>
    </li>

    <!-- Nav Item - Payroll -->
    <li class="nav-item {{ request()->is('payroll*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('payroll.index') }}">
            <i class="fas fa-fw fa-money-bill-wave"></i>
            <span>Payroll</span>
        </a>
    </li>

    <hr class="sidebar-divider">

    <!-- Heading -->
    <div class="sidebar-heading">
        Master Data
    </div>

    <!-- Collapsible Master Data -->
    <li class="nav-item">
        <a class="nav-link d-flex align-items-center justify-content-between collapsed" href="#" data-toggle="collapse"
            data-target="#collapseMaster" aria-expanded="true" aria-controls="collapseMaster">
            <div>
                <i class="fas fa-fw fa-database me-2"></i>
                <span>Master Data</span>
            </div>
            <i class="fas fa-chevron-right small"></i>
        </a>

        <div id="collapseMaster"
            class="collapse {{ request()->is('user*') || request()->is('pegawai*') || request()->is('jobdesk*') || request()->is('jabatan*') || request()->is('bonus-potongan*') ? 'show' : '' }}"
            aria-labelledby="headingMaster" data-parent="#accordionSidebar">

            <div class="bg-white py-2 collapse-inner rounded shadow-sm" style="border-left: 3px solid #4e73df;">
                <h6 class="collapse-header text-primary fw-bold">Kelola Data:</h6>

                <a class="collapse-item {{ request()->is('user*') ? 'active' : '' }}" href="{{ route('user.index') }}">
                    <i class="fas fa-user-cog me-2 text-secondary"></i> User
                </a>

                <a class="collapse-item {{ request()->is('pegawai*') ? 'active' : '' }}"
                    href="{{ route('pegawai.index') }}">
                    <i class="fas fa-users me-2 text-secondary"></i> Pegawai
                </a>

                <a class="collapse-item {{ request()->is('jobdesk*') ? 'active' : '' }}"
                    href="{{ route('jobdesk.index') }}">
                    <i class="fas fa-tasks me-2 text-secondary"></i> Jobdesk
                </a>

                <a class="collapse-item {{ request()->is('jabatan*') ? 'active' : '' }}"
                    href="{{ route('jabatan.index') }}">
                    <i class="fas fa-briefcase me-2 text-secondary"></i> Jabatan
                </a>

                <a class="collapse-item {{ request()->is('bonus-potongan*') ? 'active' : '' }}"
                    href="{{ route('bonus-potongan.index') }}">
                    <i class="fas fa-coins me-2 text-secondary"></i> Bonus &amp; Potongan
                </a>
            </div>
        </div>
    </li>


    <hr class="sidebar-divider d-none d-md-block">

</ul>