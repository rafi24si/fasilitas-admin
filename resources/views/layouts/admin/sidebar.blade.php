<aside class="left-sidebar shadow-sm">

    <div class="sidebar-wrapper">

        {{-- USER PROFILE --}}
        @if (session()->has('user_id'))
            @php
                $user = \App\Models\User::with('fotoProfil')->find(session('user_id'));
                $foto = $user?->fotoProfil?->file_url
                    ? asset('storage/' . $user->fotoProfil->file_url)
                    : 'https://ui-avatars.com/api/?name=' .
                        urlencode($user->name) .
                        '&background=16a34a&color=fff&size=100&rounded=true';
            @endphp

            <div class="sidebar-profile text-center py-4 px-3">
                <div class="profile-avatar mb-2">
                    <img src="{{ $foto }}" alt="User">
                </div>

                <h6 class="fw-semibold mb-1 text-white">{{ $user->name }}</h6>

                <span class="badge role-badge">
                    {{ ucfirst($user->role) }}
                </span>
            </div>
        @endif

        {{-- NAVIGATION --}}
        <nav class="sidebar-nav px-2">
            <ul id="sidebarnav">

                <li class="nav-section"><span>MENU UTAMA</span></li>

                <li class="sidebar-item">
                    <a class="sidebar-link {{ request()->routeIs('dashboard') ? 'active' : '' }}"
                        href="{{ route('dashboard') }}">
                        <iconify-icon icon="solar:dashboard-bold-duotone"></iconify-icon>
                        <span>Dashboard</span>
                    </a>
                </li>

                <li class="nav-section"><span>ADMINISTRASI</span></li>

                <li class="sidebar-item">
                    <a class="sidebar-link {{ request()->routeIs('warga.*') ? 'active' : '' }}"
                        href="{{ route('warga.index') }}">
                        <iconify-icon icon="solar:users-group-rounded-bold-duotone"></iconify-icon>
                        <span>Data Warga</span>
                    </a>
                </li>

                @if ($user && $user->role === 'admin')
                    <li class="sidebar-item">
                        <a class="sidebar-link {{ request()->routeIs('user.*') ? 'active' : '' }}"
                            href="{{ route('user.index') }}">
                            <iconify-icon icon="solar:user-id-bold-duotone"></iconify-icon>
                            <span>Manajemen User</span>
                        </a>
                    </li>
                @endif

                <li class="nav-section"><span>FASILITAS DESA</span></li>

                <li class="sidebar-item">
                    <a class="sidebar-link {{ request()->routeIs('fasilitas.*') ? 'active' : '' }}"
                        href="{{ route('fasilitas.index') }}">
                        <iconify-icon icon="solar:buildings-bold-duotone"></iconify-icon>
                        <span>Fasilitas</span>
                    </a>
                </li>

                <li class="sidebar-item">
                    <a class="sidebar-link {{ request()->routeIs('petugas.*') ? 'active' : '' }}"
                        href="{{ route('petugas.index') }}">
                        <iconify-icon icon="solar:shield-user-bold-duotone"></iconify-icon>
                        <span>Petugas</span>
                    </a>
                </li>

                <li class="sidebar-item">
                    <a class="sidebar-link {{ request()->routeIs('syarat.*') ? 'active' : '' }}"
                        href="{{ route('syarat.index') }}">
                        <iconify-icon icon="solar:clipboard-text-bold-duotone"></iconify-icon>
                        <span>Syarat</span>
                    </a>
                </li>

                <li class="nav-section"><span>TRANSAKSI</span></li>

                <li class="sidebar-item">
                    <a class="sidebar-link {{ request()->routeIs('peminjaman.*') ? 'active' : '' }}"
                        href="{{ route('peminjaman.index') }}">
                        <iconify-icon icon="solar:calendar-bold-duotone"></iconify-icon>
                        <span>Peminjaman</span>
                    </a>
                </li>

                <li class="sidebar-item">
                    <a class="sidebar-link {{ request()->routeIs('pembayaran.*') ? 'active' : '' }}"
                        href="{{ route('pembayaran.index') }}">
                        <iconify-icon icon="solar:wallet-money-bold-duotone"></iconify-icon>
                        <span>Pembayaran</span>
                    </a>
                </li>

            </ul>
        </nav>
    </div>
</aside>

<style>
    /* =====================
   SIDEBAR CONTAINER
===================== */
    .left-sidebar {
        position: fixed;
        top: 0;
        left: 0;
        width: 260px;
        height: 100vh;
        background: linear-gradient(180deg, #049c3c, #27dc69);
        border-right: 1px solid rgba(255, 255, 255, .15);
        z-index: 1000;
        overflow: hidden;
    }

    /* =====================
   WRAPPER
===================== */
    .sidebar-wrapper {
        height: 100%;
        display: flex;
        flex-direction: column;
    }

    /* =====================
   PROFILE
===================== */
    .sidebar-profile {
        border-bottom: 1px solid rgba(255, 255, 255, .2);
        flex-shrink: 0;
    }

    .profile-avatar img {
        width: 85px;
        height: 85px;
        border-radius: 50%;
        object-fit: cover;
        border: 3px solid #bbf7d0;
    }

    .role-badge {
        background: rgba(255, 255, 255, .15);
        color: #ecfdf5;
        padding: 5px 14px;
        border-radius: 20px;
        font-size: 12px;
    }

    /* =====================
   NAV
===================== */
    .sidebar-nav {
        flex: 1;
        overflow-y: auto;
        overflow-x: hidden;
        padding-bottom: 40px;
        padding-right: 6px;
    }

    /* =====================
   SECTION
===================== */
    .nav-section {
        font-size: 11px;
        font-weight: 700;
        color: #d1fae5;
        padding: 18px 16px 8px;
        letter-spacing: .08em;
    }

    /* =====================
   LINK
===================== */
    .sidebar-link {
        position: relative;
        display: flex;
        align-items: center;
        gap: 14px;
        padding: 11px 16px;
        margin-bottom: 4px;
        border-radius: 12px;
        color: #ecfdf5;
        text-decoration: none;
        transition: all .2s ease;
    }

    .sidebar-link iconify-icon {
        font-size: 22px;
        color: #bbf7d0;
    }

    /* HOVER */
    .sidebar-link:hover {
        background: rgba(255, 255, 255, .15);
        transform: translateX(6px);
    }

    /* ACTIVE */
    .sidebar-link.active {
        background: #ecfdf5;
        color: #14532d;
        font-weight: 600;
        box-shadow: 0 8px 20px rgba(0, 0, 0, .2);
    }

    .sidebar-link.active iconify-icon {
        color: #16a34a;
    }

    /* =====================
   SCROLLBAR
===================== */
    .sidebar-nav::-webkit-scrollbar {
        width: 6px;
    }

    .sidebar-nav::-webkit-scrollbar-thumb {
        background: rgba(255, 255, 255, .35);
        border-radius: 10px;
    }

    .sidebar-nav::-webkit-scrollbar-thumb:hover {
        background: rgba(255, 255, 255, .6);
    }
</style>
