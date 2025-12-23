@extends('layouts.admin.app')
@section('title', 'Dashboard')

@push('styles')
    <style>
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(12px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .fade-in {
            animation: fadeIn .6s ease-out;
        }

        .brand-primary {
            color: #1e88e5;
        }

        .bg-soft {
            background: #f4f8ff;
        }

        .card-dashboard {
            border-radius: 18px;
            border: none;
            box-shadow: 0 10px 30px rgba(0, 0, 0, .08);
            transition: .3s;
        }

        .card-dashboard:hover {
            transform: translateY(-4px);
            box-shadow: 0 18px 40px rgba(0, 0, 0, .15);
        }

        .stat-icon {
            width: 56px;
            height: 56px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 26px;
            color: #fff;
        }

        .icon-blue {
            background: #1e88e5;
        }

        .icon-green {
            background: #43a047;
        }

        .icon-orange {
            background: #fb8c00;
        }

        .icon-purple {
            background: #8e24aa;
        }


        .dev-section {
            margin: 40px auto;
            max-width: 1100px;
        }

        .dev-title {
            font-weight: 800;
            color: #14532d;
            margin-bottom: 6px;
        }

        .dev-subtitle {
            color: #4b5563;
            margin-bottom: 28px;
            max-width: 700px;
        }

        .dev-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 24px;
        }

        .dev-card {
            background: rgba(255, 255, 255, .92);
            backdrop-filter: blur(14px);
            border-radius: 20px;
            border: 1px solid #e5e7eb;
            box-shadow: 0 12px 32px rgba(0, 0, 0, .12);
            transition: .35s ease;
        }

        .dev-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 20px 45px rgba(0, 0, 0, .18);
        }

        .dev-card-header {
            display: flex;
            align-items: center;
            gap: 16px;
            padding: 24px;
            border-bottom: 1px solid #e5e7eb;
        }

        .dev-avatar {
            width: 64px;
            height: 64px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            color: #fff;
            font-size: 20px;
        }

        .dev-avatar.green {
            background: linear-gradient(135deg, #16a34a, #22c55e);
        }

        .dev-avatar.blue {
            background: linear-gradient(135deg, #2563eb, #60a5fa);
        }

        .dev-card-header h5 {
            margin: 0;
            font-weight: 700;
            color: #111827;
        }

        .dev-card-header span {
            font-size: 13px;
            color: #16a34a;
            font-weight: 600;
        }

        .dev-card-body {
            padding: 22px 24px;
        }

        .dev-card-body ul {
            list-style: none;
            padding: 0;
            margin: 0 0 18px;
            font-size: 14px;
            color: #374151;
        }

        .dev-card-body li {
            margin-bottom: 6px;
        }

        .dev-social {
            display: flex;
            gap: 10px;
        }

        .dev-social a {
            flex: 1;
            text-align: center;
            padding: 8px 0;
            border-radius: 999px;
            font-size: 13px;
            font-weight: 600;
            text-decoration: none;
            transition: .25s ease;
        }

        .dev-social a.linkedin {
            background: #eff6ff;
            color: #1d4ed8;
        }

        .dev-social a.linkedin:hover {
            background: #1d4ed8;
            color: #fff;
        }

        .dev-social a.github {
            background: #f3f4f6;
            color: #111827;
        }

        .dev-social a.github:hover {
            background: #111827;
            color: #fff;
        }

        .dev-social a.instagram {
            background: #fdf2f8;
            color: #be185d;
        }

        .dev-social a.instagram:hover {
            background: linear-gradient(135deg, #ec4899, #db2777);
            color: #fff;
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid py-4 fade-in">

        {{-- HEADER --}}
        <div class="d-flex align-items-center mb-4">
            <img src="{{ asset('assets/images/logo.jpeg') }}" style="width:150px;" class="me-3">

            <div>
                <h3 class="fw-bold mb-0 brand-primary">Dashboard Sistem Fasilitas Desa</h3>
                <small class="text-muted">Monitoring & Manajemen Fasilitas Desa</small>
            </div>

            <div class="ms-auto text-end">
                <div id="clock" class="fw-bold brand-primary fs-4"></div>
                <small id="date-today" class="text-muted"></small>
            </div>
        </div>

        {{-- STAT CARDS --}}
        <div class="row g-4 mb-4">

            <div class="col-md-3">
                <div class="card card-dashboard p-3">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon icon-blue me-3">
                            <i class="ti ti-users"></i>
                        </div>
                        <div>
                            <small class="text-muted">Total Warga</small>
                            <h4 class="fw-bold mb-0">{{ $totalWarga ?? 0 }}</h4>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card card-dashboard p-3">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon icon-green me-3">
                            <i class="ti ti-building"></i>
                        </div>
                        <div>
                            <small class="text-muted">Fasilitas</small>
                            <h4 class="fw-bold mb-0">{{ $totalFasilitas ?? 0 }}</h4>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card card-dashboard p-3">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon icon-orange me-3">
                            <i class="ti ti-calendar-event"></i>
                        </div>
                        <div>
                            <small class="text-muted">Peminjaman Aktif</small>
                            <h4 class="fw-bold mb-0">{{ $peminjamanAktif ?? 0 }}</h4>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card card-dashboard p-3">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon icon-purple me-3">
                            <i class="ti ti-wallet"></i>
                        </div>
                        <div>
                            <small class="text-muted">Total Pembayaran</small>
                            <h4 class="fw-bold mb-0">Rp {{ number_format($totalPembayaran ?? 0, 0, ',', '.') }}</h4>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        {{-- CHART & INFO --}}
        <div class="row g-4">

            {{-- CHART --}}
            <div class="col-md-8">
                <div class="card card-dashboard p-4">
                    <h6 class="fw-bold mb-3">Statistik Peminjaman Bulanan</h6>
                    <canvas id="chartPeminjaman" height="120"></canvas>
                </div>
            </div>

            {{-- INFO PANEL --}}
            <div class="col-md-4">
                <div class="card card-dashboard p-4 bg-soft">
                    <h6 class="fw-bold mb-3">Info Sistem</h6>
                    <ul class="list-unstyled mb-0 small">
                        <li class="mb-2">👤 User Login: <strong>{{ session('user_name') }}</strong></li>
                        <li class="mb-2">🔐 Role: <strong>{{ ucfirst(session('role')) }}</strong></li>
                        <li class="mb-2">🕒 Login Terakhir: <strong>{{ now()->format('d M Y') }}</strong></li>
                        <li>⚙️ Versi Sistem: <strong>v1.0.0</strong></li>
                    </ul>
                </div>
            </div>

            <div class="row g-4 mb-4">

                <div class="col-md-3">
                    <div class="card card-soft p-4">
                        <div class="d-flex justify-content-between">
                            <div>
                                <small class="text-muted">Menunggu</small>
                                <h3 class="fw-bold text-warning">
                                    {{ $statusPeminjaman['menunggu'] ?? 0 }}
                                </h3>
                            </div>
                            <div class="stat-icon bg-warning">
                                ⏳
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card card-soft p-4">
                        <div class="d-flex justify-content-between">
                            <div>
                                <small class="text-muted">Disetujui</small>
                                <h3 class="fw-bold text-primary">
                                    {{ $statusPeminjaman['disetujui'] ?? 0 }}
                                </h3>
                            </div>
                            <div class="stat-icon bg-primary">
                                ✅
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card card-soft p-4">
                        <div class="d-flex justify-content-between">
                            <div>
                                <small class="text-muted">Dipakai</small>
                                <h3 class="fw-bold text-success">
                                    {{ $statusPeminjaman['dipakai'] ?? 0 }}
                                </h3>
                            </div>
                            <div class="stat-icon bg-success">
                                🏢
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card card-soft p-4">
                        <div class="d-flex justify-content-between">
                            <div>
                                <small class="text-muted">Selesai</small>
                                <h3 class="fw-bold text-secondary">
                                    {{ $statusPeminjaman['selesai'] ?? 0 }}
                                </h3>
                            </div>
                            <div class="stat-icon bg-secondary">
                                📦
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <div class="dev-section fade-in">

                <h3 class="dev-title"> Tim Pengembang</h3>
                <p class="dev-subtitle">
                    Aplikasi ini dikembangkan oleh mahasiswa Program Studi Sistem Informasi
                    dengan fokus pada manajemen fasilitas dan layanan desa.
                </p>

                <div class="dev-grid">

                    <!-- DEVELOPER 1 -->
                    <div class="dev-card">
                        <div class="dev-card-header">
                            <div class="dev-avatar green">RM</div>
                            <div>
                                <h5>Rizky Martin Pasaribu</h5>
                            </div>
                        </div>

                        <div class="dev-card-body">
                            <ul>
                                <li><strong>NIM</strong> : 2457301126</li>
                                <li><strong>Jurusan</strong> : Sistem Informasi</li>
                            </ul>

                            <div class="dev-social">
                                <a href="https://www.linkedin.com/" target="_blank" class="linkedin">
                                    <i class="ti ti-brand-linkedin"></i> LinkedIn
                                </a>
                                <a href="https://github.com/" target="_blank" class="github">
                                    <i class="ti ti-brand-github"></i> GitHub
                                </a>
                                <a href="https://www.instagram.com/" target="_blank" class="instagram">
                                    <i class="ti ti-brand-instagram"></i> Instagram
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- DEVELOPER 2 -->
                    <div class="dev-card">
                        <div class="dev-card-header">
                            <div class="dev-avatar blue">TK</div>
                            <div>
                                <h5>Tiara Kania Noer Riska</h5>
                            </div>
                        </div>

                        <div class="dev-card-body">
                            <ul>
                                <li><strong>NIM</strong> : NGASAL</li>
                                <li><strong>Jurusan</strong> : Sistem Informasi</li>
                            </ul>

                            <div class="dev-social">
                                <a href="https://www.linkedin.com/" target="_blank" class="linkedin">
                                    <i class="ti ti-brand-linkedin"></i> LinkedIn
                                </a>
                                <a href="https://github.com/" target="_blank" class="github">
                                    <i class="ti ti-brand-github"></i> GitHub
                                </a>
                                <a href="https://www.instagram.com/" target="_blank" class="instagram">
                                    <i class="ti ti-brand-instagram"></i> Instagram
                                </a>
                            </div>
                        </div>
                    </div>

                </div>
            </div>



        </div>

    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        // JAM REALTIME
        function updateClock() {
            const now = new Date();
            document.getElementById('clock').innerHTML =
                now.toLocaleTimeString('id-ID', {
                    hour: '2-digit',
                    minute: '2-digit',
                    second: '2-digit'
                });

            document.getElementById('date-today').innerHTML =
                now.toLocaleDateString('id-ID', {
                    weekday: 'long',
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric'
                });
        }
        setInterval(updateClock, 1000);
        updateClock();

        // CHART
        const ctx = document.getElementById('chartPeminjaman').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul'],
                datasets: [{
                    label: 'Jumlah Peminjaman',
                    data: [5, 9, 7, 14, 10, 18, 22],
                    borderColor: '#1e88e5',
                    backgroundColor: 'rgba(30,136,229,.15)',
                    fill: true,
                    tension: .4
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
@endpush
