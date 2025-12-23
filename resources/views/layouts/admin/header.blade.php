<div class="app-topstrip py-3 px-4 d-lg-flex align-items-center justify-content-between"
    style="
        background:#0bba45;
        box-shadow:0 3px 12px rgba(0,0,0,0.2);
        margin-left:260px;
        width:calc(100% - 260px);
     ">



    {{-- BAGIAN KIRI --}}
    <div class="d-flex align-items-center gap-4">

        {{-- LOGO & TITLE --}}
        <a href="{{ route('dashboard') }}" class="d-flex align-items-center gap-3 text-white text-decoration-none">

            <img src="{{ asset('assets/images/logo.jpeg') }}" style="width:100px;" class="drop-shadow-lg">

            <div class="lh-sm">
                <div class="fw-bold fs-5" style="letter-spacing:.5px;">
                    SISTEM FASILITAS DESA
                </div>
                <small class="opacity-75">
                    Peminjaman Ruang & Sarana Umum
                </small>
            </div>
        </a>

        {{-- QUICK MENU --}}
        <div class="d-none d-xl-flex align-items-center gap-2">

            <a href="javascript:void(0)" class="btn btn-sm px-3 py-1 text-white disabled-menu" aria-disabled="true">
                <i class="ti ti-building-community fs-5"></i>
                Fasilitas
            </a>

            <a href="javascript:void(0)" class="btn btn-sm px-3 py-1 text-white disabled-menu" aria-disabled="true">
                <i class="ti ti-calendar-event fs-5"></i>
                Peminjaman
            </a>

            <a href="javascript:void(0)" class="btn btn-sm px-3 py-1 text-white disabled-menu" aria-disabled="true">
                <i class="ti ti-clock fs-5"></i>
                Jadwal
            </a>

            <a href="javascript:void(0)" class="btn btn-sm px-3 py-1 text-white disabled-menu" aria-disabled="true">
                <i class="ti ti-file-text fs-5"></i>
                Laporan
            </a>

        </div>

    </div>

    {{-- BAGIAN KANAN (USER) --}}
    <div class="d-flex align-items-center gap-3">

        @php
            $user = null;
            if (session()->has('user_id')) {
                $user = \App\Models\User::with('fotoProfil')->find(session('user_id'));
            }
        @endphp

        @if ($user)
            <div class="dropdown">

                {{-- TOGGLE --}}
                <a href="#" class="d-flex align-items-center gap-3 text-decoration-none dropdown-toggle"
                    id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false"
                    style="
                    background:#ffffff;
                    padding:8px 14px;
                    border-radius:14px;
                    border:1px solid #e5e7eb;
                    box-shadow:0 4px 12px rgba(0,0,0,.12);
               ">

                    {{-- AVATAR --}}
                    <img src="{{ $user->fotoProfil ? asset('storage/' . $user->fotoProfil->file_url) : asset('images/default-user.png') }}"
                        class="rounded-circle" width="40" height="40" style="object-fit:cover;">

                    {{-- INFO --}}
                    <div class="d-none d-md-block lh-sm">
                        <div class="fw-semibold text-dark">
                            {{ $user->name }}
                        </div>
                        <small class="text-muted">
                            {{ ucfirst($user->role) }}
                        </small>
                    </div>
                </a>

                {{-- DROPDOWN --}}
                <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-2"
                    style="min-width:220px; border-radius:14px;">

                    <li class="px-3 py-2">
                        <a href="{{ route('user.profil') }}" class="dropdown-item d-flex align-items-center gap-2">
                            <i class="ti ti-user"></i>
                            Profil Saya
                        </a>
                    </li>

                    <li>
                        <hr class="dropdown-divider">
                    </li>

                    <li class="px-3 py-2">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="dropdown-item d-flex align-items-center gap-2 text-danger"
                                onclick="return confirm('Keluar dari aplikasi?')">
                                <i class="ti ti-logout"></i>
                                Logout
                            </button>
                        </form>
                    </li>
                </ul>

            </div>
        @else
            <a href="{{ route('login.form') }}" class="btn btn-warning d-flex align-items-center gap-2 px-4"
                style="border-radius:10px;">
                <i class="ti ti-login fs-5"></i> Login
            </a>
        @endif

    </div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
document.querySelectorAll('.dropdown-toggle').forEach(el => {
    new bootstrap.Dropdown(el)
})
</script>


</div>
