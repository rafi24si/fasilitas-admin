@extends('layouts.admin.app')
@section('title', 'Edit User')

@push('styles')
    <style>
        /* =====================
           ANIMATION
        ===================== */
        .fade-in {
            animation: fadeIn .4s ease;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(8px);
            }

            to {
                opacity: 1;
                transform: none;
            }
        }

        /* =====================
           CARD CONTAINER
        ===================== */
        .edit-card {
            border-radius: 20px;
            border: 1px solid #e5e7eb;
            box-shadow: 0 14px 34px rgba(0, 0, 0, .12);
            overflow: hidden;
            background: #ffffff;
        }

        /* =====================
           LEFT AVATAR PANEL
        ===================== */
        .avatar-wrapper {
            text-align: center;
            padding: 32px 24px;
            background: linear-gradient(135deg, #16a34a, #22c55e);
            color: #ffffff;
        }

        /* =====================
           AVATAR
        ===================== */
        .avatar-box {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            overflow: hidden;
            border: 5px solid rgba(255, 255, 255, .9);
            margin: 0 auto 14px;
            background: #ffffff;
            box-shadow: 0 8px 22px rgba(0, 0, 0, .25);
        }

        .avatar-box img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        /* =====================
           ROLE BADGE
        ===================== */
        .role-badge {
            display: inline-block;
            background: rgba(255, 255, 255, .18);
            border: 1px solid rgba(255, 255, 255, .35);
            color: #ffffff;
            font-size: 12px;
            padding: 6px 16px;
            border-radius: 50px;
            margin-top: 6px;
            backdrop-filter: blur(6px);
            font-weight: 600;
        }

        /* =====================
           FORM AREA
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
           BUTTON
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
           SMALL TEXT
        ===================== */
        .hint {
            font-size: 12px;
            color: #6b7280;
        }
    </style>
@endpush


@section('content')
    <div class="container-fluid py-4 fade-in">

        <div class="mb-4">
            <h4 class="fw-bold text-primary mb-1">✏️ Edit User</h4>
            <small class="text-muted">Perbarui data akun pengguna</small>
        </div>

        <div class="card edit-card">
            <form action="{{ route('user.update', $user->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row g-0">

                    {{-- ================= LEFT : FOTO ================= --}}
                    <div class="col-md-4 avatar-wrapper">

                        <div class="avatar-box">
                            <img id="avatarPreview"
                                src="{{ $user->fotoProfil
                                    ? asset('storage/' . $user->fotoProfil->file_url)
                                    : 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&background=2563eb&color=fff' }}">
                        </div>

                        <strong>{{ $user->name }}</strong><br>
                        <span class="role-badge">{{ ucfirst($user->role) }}</span>

                        <div class="mt-3">
                            {{-- 🔥 NAME HARUS "foto" --}}
                            <input type="file" name="foto" accept="image/*" class="form-control form-control-sm"
                                onchange="previewAvatar(this)">
                            <div class="hint mt-1">
                                JPG / PNG • Maks 2MB
                            </div>
                        </div>
                    </div>

                    {{-- ================= RIGHT : FORM ================= --}}
                    <div class="col-md-8 p-4">

                        <div class="row g-3">

                            <div class="col-md-6">
                                <label class="form-label">Nama Lengkap</label>
                                <input type="text" name="name" class="form-control"
                                    value="{{ old('name', $user->name) }}" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Role</label>
                                <select name="role" class="form-select" required>
                                    <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>
                                        Admin
                                    </option>
                                    <option value="petugas" {{ $user->role == 'petugas' ? 'selected' : '' }}>
                                        Petugas
                                    </option>
                                </select>
                            </div>

                            <div class="col-12">
                                <label class="form-label">Email</label>
                                <input type="email" name="email" id="emailField" class="form-control"
                                    value="{{ old('email', $user->email) }}" required>
                                <div id="email-status" class="hint mt-1"></div>
                            </div>

                            <div class="col-12">
                                <label class="form-label">
                                    Password Baru
                                    <span class="hint">(opsional)</span>
                                </label>

                                <div class="input-group">
                                    <input type="password" name="password" id="passwordField" class="form-control"
                                        placeholder="Kosongkan jika tidak diganti">
                                    <button type="button" class="btn btn-outline-secondary" onclick="togglePassword()">
                                        👁
                                    </button>
                                </div>
                            </div>

                        </div>

                        <hr class="my-4">

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('user.index') }}" class="btn btn-light px-4">
                                Batal
                            </a>
                            <button class="btn btn-primary px-4">
                                💾 Simpan Perubahan
                            </button>
                        </div>

                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- ================= JS ================= --}}
    <script>
        function previewAvatar(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = e => {
                    document.getElementById('avatarPreview').src = e.target.result;
                };
                reader.readAsDataURL(input.files[0]);
            }
        }

        function togglePassword() {
            const field = document.getElementById('passwordField');
            field.type = field.type === 'password' ? 'text' : 'password';
        }

        const emailField = document.getElementById('emailField');
        const statusBox = document.getElementById('email-status');
        const originalEmail = "{{ $user->email }}";

        emailField.addEventListener('input', () => {
            const email = emailField.value;

            if (!email || email === originalEmail) {
                statusBox.innerHTML = '';
                return;
            }

            fetch("{{ route('user.checkEmail') }}?email=" + encodeURIComponent(email))
                .then(res => res.json())
                .then(data => {
                    statusBox.innerHTML = data.exists ?
                        "<span class='text-danger'>Email sudah digunakan</span>" :
                        "<span class='text-success'>Email tersedia</span>";
                });
        });
    </script>
@endsection
