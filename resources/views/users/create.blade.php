@extends('layouts.admin.app')
@section('title', 'Tambah User')

@push('styles')
    <style>
        /* =====================
           ANIMATION
        ===================== */
        .fade-in {
            animation: fadeIn .4s ease-in-out;
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
           FORM CONTAINER
        ===================== */
        .form-shell {
            border-radius: 20px;
            overflow: hidden;
            background: #ffffff;
            box-shadow: 0 12px 30px rgba(0, 0, 0, .12);
            border: 1px solid #e5e7eb;
        }

        /* =====================
           SIDE INFO (HIJAU)
        ===================== */
        .side-info {
            background: linear-gradient(135deg, #16a34a, #22c55e);
            color: #ffffff;
            padding: 32px;
        }

        .side-info h5 {
            font-weight: 700;
            letter-spacing: .4px;
        }

        .side-info p {
            opacity: .9;
            font-size: 14px;
        }

        /* =====================
           FORM AREA
        ===================== */
        .form-area {
            padding: 32px;
            background: #ffffff;
        }

        /* =====================
           LABEL & INPUT
        ===================== */
        .form-label {
            font-weight: 600;
            color: #065f46;
        }

        .form-control,
        .form-select {
            border-radius: 12px;
            border: 1px solid #d1fae5;
            transition: .2s ease;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #22c55e;
            box-shadow: 0 0 0 3px rgba(34, 197, 94, .25);
        }

        /* =====================
           BUTTON PRIMARY
        ===================== */
        .btn-primary {
            background: linear-gradient(135deg, #16a34a, #22c55e);
            border: none;
            border-radius: 50px;
            font-weight: 600;
            padding: 10px 28px;
            box-shadow: 0 8px 22px rgba(34, 197, 94, .35);
            transition: .25s ease;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 14px 32px rgba(34, 197, 94, .5);
        }

        /* =====================
           HINT / SMALL TEXT
        ===================== */
        .hint {
            font-size: 13px;
            color: #6b7280;
        }
    </style>
@endpush


@section('content')
    <div class="container-fluid fade-in">

        {{-- HEADER --}}
        <div class="mb-4">
            <h3 class="fw-bold text-primary mb-1">➕ Tambah User Baru</h3>
            <small class="text-muted">Buat akun admin atau petugas</small>
        </div>

        <div class="card shadow-sm border-0 form-shell">

            <div class="row g-0">

                {{-- LEFT INFO --}}
                <div class="col-md-4 side-info">
                    <h5>Informasi</h5>
                    <p class="small opacity-75 mb-3">
                        User yang dibuat dapat langsung login sesuai role yang dipilih.
                    </p>

                    <ul class="small">
                        <li>✔ Email harus unik</li>
                        <li>✔ Password minimal 6 karakter</li>
                        <li>✔ Role menentukan akses</li>
                    </ul>
                </div>

                {{-- FORM --}}
                <div class="col-md-8 form-area">

                    <form action="{{ route('user.store') }}" method="POST">
                        @csrf

                        <div class="row g-3">

                            <div class="col-md-6">
                                <label class="form-label">Nama Lengkap</label>
                                <input type="text" name="name" class="form-control" placeholder="Nama user" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Role</label>
                                <select name="role" class="form-select" required>
                                    <option value="admin">Admin</option>
                                    <option value="petugas">Petugas</option>
                                </select>
                            </div>

                            <div class="col-12">
                                <label class="form-label">Email</label>
                                <input type="email" name="email" class="form-control" placeholder="email@domain.com"
                                    required>
                                <small id="email-status" class="d-block mt-1"></small>
                            </div>

                            <div class="col-12">
                                <label class="form-label">Password</label>
                                <input type="password" name="password" class="form-control" placeholder="Minimal 6 karakter"
                                    required>
                            </div>

                        </div>

                        <hr class="my-4">

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('user.index') }}" class="btn btn-light px-4">
                                Batal
                            </a>
                            <button class="btn btn-primary px-4">
                                Simpan User
                            </button>
                        </div>

                    </form>

                </div>
            </div>
        </div>
    </div>

    {{-- EMAIL CHECK --}}
    <script>
        document.querySelector('input[name="email"]').addEventListener('input', function() {
            const email = this.value;
            const box = document.getElementById('email-status');

            if (!email) {
                box.innerHTML = '';
                return;
            }

            fetch("{{ route('user.checkEmail') }}?email=" + encodeURIComponent(email))
                .then(res => res.json())
                .then(data => {
                    box.innerHTML = data.exists ?
                        "<span class='text-danger'>Email sudah digunakan</span>" :
                        "<span class='text-success'>Email tersedia</span>";
                });
        });
    </script>
@endsection
