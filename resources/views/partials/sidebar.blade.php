<!--begin::Sidebar-->
<aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
    <!--begin::Sidebar Brand-->
    <div class="sidebar-brand">
        <!--begin::Brand Link--> <a href="./index.html" class="brand-link">
            <!--begin::Brand Image-->
            <!-- <img src="/assets/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image opacity-75 shadow"> -->
            <!--end::Brand Image-->
            <!--begin::Brand Text--> <span class="brand-text fw-light">Employee-Presence</span>
            <!--end::Brand Text-->
        </a>
        <!--end::Brand Link-->
    </div>
    <!--end::Sidebar Brand-->
    <!--begin::Sidebar Wrapper-->
    <div class="sidebar-wrapper">
        <nav class="mt-2">
            <!--begin::Sidebar Menu-->
            <ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview" role="navigation"
                aria-label="Main navigation" data-accordion="false" id="navigation">

                {{-- Absensi --}}
                <li class="nav-item">
                    <a href="{{ route('absensi.index') }}"
                        class="nav-link {{ request()->routeIs('absensi.index') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-clipboard-check"></i>
                        <p>Absensi</p>
                    </a>
                </li>

                {{-- Gajian --}}
                <li class="nav-item">
                    <a href="{{ route('gajian.index') }}"
                        class="nav-link {{ request()->routeIs('gajian.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-money-bill-wave"></i>
                        <p>Gajian</p>
                    </a>
                </li>

                {{-- Master Data --}}
                <li class="nav-header">MASTER DATA</li>

                <li
                    class="nav-item has-treeview {{ request()->is('user*','pegawai*', 'grup*', 'jabatan*','bonuspotongan*') ? 'menu-open' : '' }}">
                    <a href="#"
                        class="nav-link {{ request()->is('user*','pegawai*','jabatan*','grup*', 'bonuspotongan*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-database"></i>
                        <p>
                            Master Data
                            <i class="nav-arrow bi bi-chevron-right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">

                        <li class="nav-item">
                            <a href="{{ route('user.index') }}"
                                class="nav-link {{ request()->routeIs('user.*') ? 'active' : '' }}">
                                <i class="fas fa-user me-2"></i>
                                <p>User</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('pegawai.index') }}"
                                class="nav-link {{ request()->routeIs('pegawai.*') ? 'active' : '' }}">
                                <i class="fas fa-users me-2"></i>
                                <p>Pegawai</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('jabatan.index') }}"
                                class="nav-link {{ request()->routeIs('jabatan.*') ? 'active' : '' }}">
                                <i class="fas fa-briefcase me-2"></i>
                                <p>Jabatan</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('grup.index') }}"
                                class="nav-link {{ request()->routeIs('grup.*') ? 'active' : '' }}">
                                <i class="fas fa-briefcase me-2"></i>
                                <p>Group</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('bonuspotongan.index') }}"
                                class="nav-link {{ request()->routeIs('bonuspotongan.*') ? 'active' : '' }}">
                                <i class="fas fa-gift me-2"></i>
                                <p>Bonus & Potongan</p>
                            </a>
                        </li>

                    </ul>
                </li>
            </ul>
            <!--end::Sidebar Menu-->
        </nav>
    </div>

    <!--end::Sidebar Wrapper-->
</aside>
<!--end::Sidebar-->