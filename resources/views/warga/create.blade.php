@extends('layouts.admin.app')
@section('title', 'Tambah Warga')

@push('styles')
    <style>
        /* =====================
           ANIMATION
        ===================== */
        .fade-in {
            animation: fadeIn .35s ease-in-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: none;
            }
        }

        /* =====================
           FORM WRAPPER
        ===================== */
        .form-wrapper {
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 18px 40px rgba(0, 0, 0, .15);
            background: #ffffff;
        }

        /* =====================
           LEFT SIDE INFO
        ===================== */
        .side-info {
            background: linear-gradient(135deg, #15803d, #22c55e);
            color: #ffffff;
            padding: 36px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .side-info h5 {
            font-weight: 800;
            letter-spacing: .3px;
            margin-bottom: 10px;
        }

        .side-info p {
            font-size: 14px;
            opacity: .95;
        }

        /* =====================
           FORM AREA
        ===================== */
        .form-area {
            padding: 36px;
            background: #ffffff;
        }

        label {
            font-weight: 600;
            color: #374151;
        }

        .hint {
            font-size: 12px;
            color: #6b7280;
        }

        /* =====================
           STEP TITLE
        ===================== */
        .step-title {
            font-weight: 800;
            color: #16a34a;
            margin-bottom: 14px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .step-title::before {
            content: "";
            width: 6px;
            height: 22px;
            border-radius: 10px;
            background: #16a34a;
        }

        /* =====================
           INPUT FOCUS
        ===================== */
        .form-control:focus,
        .form-select:focus {
            border-color: #22c55e;
            box-shadow: 0 0 0 .2rem rgba(34, 197, 94, .25);
        }

        /* =====================
           PRIMARY BUTTON
        ===================== */
        .btn-green {
            background: linear-gradient(135deg, #16a34a, #22c55e);
            border: none;
            color: #fff;
            font-weight: 600;
            transition: .2s ease;
        }

        .btn-green:hover {
            background: linear-gradient(135deg, #15803d, #16a34a);
            transform: translateY(-1px);
            box-shadow: 0 10px 24px rgba(22, 163, 74, .35);
        }
    </style>
@endpush


@section('content')
    <div class="container-fluid fade-in" style="padding-top:35px;">

        {{-- HEADER --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="fw-bold text-primary mb-0">➕ Tambah Data Warga</h3>
                <small class="text-muted">Input data kependudukan desa</small>
            </div>

            <a href="{{ route('warga.index') }}" class="btn btn-light rounded-pill px-4">
                ← Kembali
            </a>
        </div>

        <div class="card shadow-sm border-0 form-wrapper">

            <div class="row g-0">

                {{-- SIDE INFO --}}
                <div class="col-md-4 side-info">
                    <h5>Petunjuk Pengisian</h5>
                    <ul class="small mt-3">
                        <li>No KTP harus 16 digit</li>
                        <li>Email & KTP wajib unik</li>
                        <li>Data dapat diubah kembali</li>
                    </ul>

                    <hr class="border-light opacity-50">

                    <p class="small opacity-75">
                        Pastikan data sesuai KTP asli warga.
                    </p>
                </div>

                {{-- FORM --}}
                <div class="col-md-8 form-area">

                    <form method="POST" action="{{ route('warga.store') }}">
                        @csrf

                        {{-- STEP 1 --}}
                        <div class="mb-4">
                            <div class="step-title">🆔 Data Identitas</div>

                            <div class="row g-3">

                                <div class="col-md-6">
                                    <label>No KTP</label>
                                    <input type="text" id="no_ktp" name="no_ktp" class="form-control"
                                        placeholder="16 digit angka" maxlength="16">
                                    <small id="ktpWarning" class="text-danger d-none hint">
                                        ❌ KTP sudah terdaftar
                                    </small>
                                </div>

                                <div class="col-md-6">
                                    <label>Nama Lengkap</label>
                                    <input type="text" name="nama" class="form-control" placeholder="Nama sesuai KTP"
                                        value="{{ old('nama') }}">
                                </div>

                                <div class="col-md-4">
                                    <label>Jenis Kelamin</label>
                                    <select name="jenis_kelamin" class="form-select">
                                        <option value="L">Laki-laki</option>
                                        <option value="P">Perempuan</option>
                                    </select>
                                </div>

                                <div class="col-md-4">
                                    <label>Agama</label>
                                    <select name="agama" class="form-select">
                                        <option value="">-- Pilih --</option>
                                        @foreach (['Islam', 'Kristen', 'Katolik', 'Hindu', 'Buddha', 'Konghucu', 'Lainnya'] as $a)
                                            <option value="{{ $a }}">{{ $a }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-4">
                                    <label>Pekerjaan</label>
                                    <input type="text" name="pekerjaan" class="form-control"
                                        placeholder="Pekerjaan utama">
                                </div>

                            </div>
                        </div>

                        {{-- STEP 2 --}}
                        <div class="mb-4">
                            <div class="step-title">📞 Kontak Warga</div>

                            <div class="row g-3">

                                <div class="col-md-6">
                                    <label>No Telepon</label>
                                    <input type="text" name="telp" class="form-control" placeholder="08xxxxxxxxxx">
                                </div>

                                <div class="col-md-6">
                                    <label>Email</label>
                                    <input type="email" id="email" name="email" class="form-control"
                                        placeholder="email@domain.com">
                                    <small id="emailWarning" class="text-danger d-none hint">
                                        ❌ Email sudah digunakan
                                    </small>
                                </div>

                            </div>
                        </div>

                        <hr>

                        <div class="d-flex justify-content-end">
                            <button id="submitBtn" class="btn btn-primary rounded-pill px-5">
                                Simpan Data Warga
                            </button>
                        </div>

                    </form>

                </div>
            </div>
        </div>
    </div>

    {{-- JS NATIVE (NO JQUERY) --}}
    <script>
        const ktpInput = document.getElementById('no_ktp');
        const emailInput = document.getElementById('email');
        const submitBtn = document.getElementById('submitBtn');

        ktpInput.addEventListener('input', () => {
            ktpInput.value = ktpInput.value.replace(/\D/g, '').slice(0, 16);
        });

        ktpInput.addEventListener('keyup', () => {
            if (ktpInput.value.length === 16) {
                fetch("{{ route('warga.checkKTP') }}?no_ktp=" + ktpInput.value)
                    .then(res => res.json())
                    .then(data => {
                        document.getElementById('ktpWarning').classList.toggle('d-none', !data.exists);
                        submitBtn.disabled = data.exists;
                    });
            }
        });

        emailInput.addEventListener('keyup', () => {
            if (emailInput.value.includes('@')) {
                fetch("{{ route('warga.checkEmail') }}?email=" + emailInput.value)
                    .then(res => res.json())
                    .then(data => {
                        document.getElementById('emailWarning').classList.toggle('d-none', !data.exists);
                        submitBtn.disabled = data.exists;
                    });
            }
        });
    </script>
@endsection
