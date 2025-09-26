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
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseMaster"
            aria-expanded="true" aria-controls="collapseMaster">
            <i class="fas fa-fw fa-database"></i>
            <span>Master Data</span>
        </a>
        <div id="collapseMaster" class="collapse {{ request()->is('user*') || request()->is('pegawai*') || request()->is('jobdesk*') || request()->is('jabatan*') || request()->is('bonus-potongan*') ? 'show' : '' }}"
             aria-labelledby="headingMaster" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item {{ request()->is('user*') ? 'active' : '' }}" href="{{ route('user.index') }}">User</a>
                <a class="collapse-item {{ request()->is('pegawai*') ? 'active' : '' }}" href="{{ route('pegawai.index') }}">Pegawai</a>
                <a class="collapse-item {{ request()->is('jobdesk*') ? 'active' : '' }}" href="{{ route('jobdesk.index') }}">Jobdesk</a>
                <a class="collapse-item {{ request()->is('jabatan*') ? 'active' : '' }}" href="{{ route('jabatan.index') }}">Jabatan</a>
                <a class="collapse-item {{ request()->is('bonus-potongan*') ? 'active' : '' }}" href="{{ route('bonus-potongan.index') }}">Bonus &amp; Potongan</a>
            </div>
        </div>
    </li>

    <hr class="sidebar-divider d-none d-md-block">

</ul>
