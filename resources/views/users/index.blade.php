@extends('layouts.admin.app')
@section('title', 'Manajemen User')

@push('styles')
    <style>
        /* =====================
           ANIMATION
        ===================== */
        .fade-in {
            animation: fadeIn .5s ease-in-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* =====================
           PAGE
        ===================== */
        .page-container {
            padding-top: 35px;
        }

        /* =====================
           USER CARD
        ===================== */
        .user-card {
            border-radius: 18px;
            transition: .3s ease;
            background: #ffffff;
            border: 1px solid #e5e7eb;
            box-shadow: 0 6px 18px rgba(0, 0, 0, .08);
        }

        .user-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 18px 38px rgba(0, 0, 0, .16);
        }

        /* =====================
           AVATAR
        ===================== */
        .user-avatar {
            width: 90px;
            height: 90px;
            object-fit: cover;
            border-radius: 50%;
            border: 4px solid #dcfce7;
            background: #f0fdf4;
        }

        /* =====================
           ROLE BADGE
        ===================== */
        .badge-role {
            background: linear-gradient(135deg, #16a34a, #22c55e);
            color: #ffffff;
            padding: 6px 16px;
            border-radius: 50px;
            font-size: 12px;
            font-weight: 600;
            letter-spacing: .3px;
            box-shadow: 0 4px 14px rgba(22, 163, 74, .35);
        }

        /* =====================
           ICON BUTTON
        ===================== */
        .btn-icon {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: .2s ease;
        }

        .btn-icon:hover {
            transform: scale(1.08);
        }

        /* =====================
           ACTION COLORS
        ===================== */
        .btn-edit {
            background: #ecfeff;
            color: #0369a1;
            border: 1px solid #bae6fd;
        }

        .btn-edit:hover {
            background: #0ea5e9;
            color: #fff;
        }

        .btn-delete {
            background: #fef2f2;
            color: #b91c1c;
            border: 1px solid #fecaca;
        }

        .btn-delete:hover {
            background: #dc2626;
            color: #fff;
        }
    </style>
@endpush


@section('content')
    <div class="container-fluid fade-in page-container">

        {{-- HEADER --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="fw-bold text-primary mb-0">👤 Manajemen User</h3>
                <small class="text-muted">Kelola akun admin & petugas</small>
            </div>

            <a href="{{ route('user.create') }}" class="btn btn-primary rounded-pill px-4">
                + Tambah User
            </a>
        </div>

        {{-- FILTER --}}
        <form method="GET" class="row g-2 mb-4">
            <div class="col-md-4">
                <input type="text" name="search" class="form-control rounded-pill"
                    placeholder="🔍 Cari nama atau email..." value="{{ request('search') }}">
            </div>

            <div class="col-md-3">
                <select name="role" class="form-select rounded-pill">
                    <option value="all">Semua Role</option>
                    <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                    <option value="petugas" {{ request('role') == 'petugas' ? 'selected' : '' }}>Petugas</option>
                </select>
            </div>

            <div class="col-md-2">
                <button class="btn btn-info rounded-pill w-100">Filter</button>
            </div>
        </form>

        {{-- USER CARD GRID --}}
        <div class="row g-4">
            @foreach ($users as $u)
                <div class="col-xl-3 col-lg-4 col-md-6">
                    <div class="card user-card shadow-sm border-0 h-100">

                        <div class="card-body text-center">
                            <img src="{{ $u->fotoProfil ? asset('storage/' . $u->fotoProfil->file_url) : asset('images/default-user.png') }}"
                                class="rounded-circle user-avatar mb-3">

                            <h6 class="fw-bold mb-1">{{ $u->name }}</h6>
                            <small class="text-muted d-block mb-3">{{ $u->email }}</small>

                            <span class="badge-role">
                                {{ ucfirst($u->role) }}
                            </span>
                        </div>

                        <div class="card-footer bg-white border-0 d-flex justify-content-center gap-2 pb-3">
                            <a href="{{ route('user.edit', $u->id) }}" class="btn btn-outline-warning btn-icon"
                                title="Edit User">
                                ✏️
                            </a>

                            <form method="POST" action="{{ route('user.destroy', $u->id) }}"
                                onsubmit="return confirm('Yakin ingin menghapus user ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger btn-icon" title="Hapus User">
                                    🗑️
                                </button>
                            </form>
                        </div>

                    </div>
                </div>
            @endforeach
        </div>

        {{-- PAGINATION --}}
        <div class="d-flex justify-content-center mt-4">
            {{ $users->onEachSide(1)->links('pagination::bootstrap-5') }}
        </div>

    </div>
@endsection
