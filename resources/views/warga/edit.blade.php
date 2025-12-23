@extends('layouts.admin.app')
@section('title', 'Edit Warga')

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
                <h3 class="fw-bold text-primary mb-0">✏️ Edit Data Warga</h3>
                <small class="text-muted">Perbarui informasi kependudukan</small>
            </div>

            <a href="{{ route('warga.index') }}" class="btn btn-light rounded-pill px-4">
                ← Kembali
            </a>
        </div>

        <div class="card shadow-sm border-0 form-wrapper">

            <div class="row g-0">

                {{-- SIDE INFO --}}
                <div class="col-md-4 side-info">
                    <h5>Ringkasan Warga</h5>

                    <p class="mt-3 mb-1 fw-semibold">{{ $warga->nama }}</p>
                    <p class="small opacity-75 mb-2">
                        {{ $warga->no_ktp }}
                    </p>

                    <ul class="small mt-3">
                        <li>Jenis Kelamin : {{ $warga->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</li>
                        <li>Agama : {{ $warga->agama ?? '-' }}</li>
                        <li>Pekerjaan : {{ $warga->pekerjaan ?? '-' }}</li>
                    </ul>

                    <hr class="border-light opacity-50">

                    <p class="small opacity-75">
                        Pastikan perubahan data sesuai dokumen resmi.
                    </p>
                </div>

                {{-- FORM --}}
                <div class="col-md-8 form-area">

                    <form method="POST" action="{{ route('warga.update', $warga->warga_id) }}">
                        @csrf
                        @method('PUT')

                        {{-- STEP 1 --}}
                        <div class="mb-4">
                            <div class="step-title">🆔 Data Identitas</div>

                            <div class="row g-3">

                                <div class="col-md-6">
                                    <label>No KTP</label>
                                    <input type="text" name="no_ktp"
                                        class="form-control @error('no_ktp') is-invalid @enderror"
                                        value="{{ old('no_ktp', $warga->no_ktp) }}">
                                    @error('no_ktp')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label>Nama Lengkap</label>
                                    <input type="text" name="nama"
                                        class="form-control @error('nama') is-invalid @enderror"
                                        value="{{ old('nama', $warga->nama) }}">
                                    @error('nama')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-4">
                                    <label>Jenis Kelamin</label>
                                    <select name="jenis_kelamin" class="form-select">
                                        <option value="L"
                                            {{ old('jenis_kelamin', $warga->jenis_kelamin) == 'L' ? 'selected' : '' }}>
                                            Laki-laki
                                        </option>
                                        <option value="P"
                                            {{ old('jenis_kelamin', $warga->jenis_kelamin) == 'P' ? 'selected' : '' }}>
                                            Perempuan
                                        </option>
                                    </select>
                                </div>

                                <div class="col-md-4">
                                    <label>Agama</label>
                                    <input type="text" name="agama" class="form-control"
                                        value="{{ old('agama', $warga->agama) }}">
                                </div>

                                <div class="col-md-4">
                                    <label>Pekerjaan</label>
                                    <input type="text" name="pekerjaan" class="form-control"
                                        value="{{ old('pekerjaan', $warga->pekerjaan) }}">
                                </div>

                            </div>
                        </div>

                        {{-- STEP 2 --}}
                        <div class="mb-4">
                            <div class="step-title">📞 Kontak Warga</div>

                            <div class="row g-3">

                                <div class="col-md-6">
                                    <label>No Telepon</label>
                                    <input type="text" name="telp" class="form-control"
                                        value="{{ old('telp', $warga->telp) }}">
                                </div>

                                <div class="col-md-6">
                                    <label>Email</label>
                                    <input type="email" name="email" class="form-control"
                                        value="{{ old('email', $warga->email) }}">
                                </div>

                            </div>
                        </div>

                        <hr>

                        <div class="d-flex justify-content-end">
                            <button class="btn btn-primary rounded-pill px-5">
                                Simpan Perubahan
                            </button>
                        </div>

                    </form>

                </div>
            </div>
        </div>
    </div>
@endsection
