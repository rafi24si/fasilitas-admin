@extends('layouts.admin.app')
@section('title', 'Profil Akun')

@push('styles')
    <style>
        .profile-card {
            border-radius: 18px;
            border: 1px solid #e5e7eb;
            box-shadow: 0 8px 24px rgba(0, 0, 0, .08);
        }

        .avatar-box {
            width: 110px;
            height: 110px;
            border-radius: 50%;
            overflow: hidden;
            border: 4px solid #e5e7eb;
            background: #f1f5f9;
        }

        .avatar-box img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .profile-title {
            font-weight: 700;
            font-size: 20px;
        }

        .subtle {
            font-size: 13px;
            color: #6b7280;
        }

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
    </style>
@endpush

@section('content')
    <div class="container-fluid py-4 fade-in">

        {{-- HEADER --}}
        <div class="mb-4">
            <h4 class="profile-title mb-1">Profil Akun</h4>
            <div class="subtle">Kelola informasi akun dan keamanan</div>
        </div>

        <div class="card profile-card p-4">

            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <form action="{{ route('user.updateProfil') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="row g-4 align-items-center">

                    {{-- AVATAR --}}
                    <div class="col-md-3 text-center">
                        <div class="avatar-box mx-auto mb-3">
                            <img id="avatarPreview"
                                src="{{ $user->fotoProfil
                                    ? asset('storage/' . $user->fotoProfil->file_url)
                                    : 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&background=2563eb&color=fff' }}">
                        </div>

                        <input type="file" name="foto" accept="image/*" class="form-control form-control-sm"
                            onchange="previewAvatar(this)">
                        <div class="subtle mt-2">JPG / PNG • Max 2MB</div>
                    </div>

                    {{-- FORM --}}
                    <div class="col-md-9">

                        <div class="row g-3">

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Nama Lengkap</label>
                                <input type="text" name="name" class="form-control"
                                    value="{{ old('name', $user->name) }}" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Email</label>
                                <input type="email" name="email" class="form-control"
                                    value="{{ old('email', $user->email) }}" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">
                                    Password Baru
                                    <span class="subtle">(opsional)</span>
                                </label>

                                <div class="input-group">
                                    <input type="password" name="password" id="passwordField" class="form-control"
                                        placeholder="Kosongkan jika tidak diganti">

                                    <button type="button" class="btn btn-outline-secondary" onclick="togglePassword()">
                                        <i class="ti ti-eye"></i>
                                    </button>
                                </div>
                            </div>

                        </div>

                    </div>
                </div>

                <hr class="my-4">

                <div class="d-flex justify-content-end">
                    <button class="btn btn-primary px-5 rounded-pill">
                        💾 Simpan Perubahan
                    </button>
                </div>

            </form>
        </div>
    </div>

    {{-- JS --}}
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
    </script>
@endsection
