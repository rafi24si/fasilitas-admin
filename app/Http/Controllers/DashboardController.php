<?php

namespace App\Http\Controllers;

use App\Models\Warga;
use App\Models\FasilitasUmum;
use App\Models\PeminjamanFasilitas;
use App\Models\PembayaranFasilitas;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        /* =====================
           STATISTIK UTAMA
        ====================== */
        $totalWarga = Warga::count();

        $totalFasilitas = FasilitasUmum::count();

        $peminjamanAktif = PeminjamanFasilitas::whereIn('status', [
            'menunggu',
            'disetujui',
            'dipakai'
        ])->count();

        $totalPembayaran = PembayaranFasilitas::sum('jumlah');

        /* =====================
           DATA CHART BULANAN
        ====================== */
        $chartBulanan = PeminjamanFasilitas::select(
                DB::raw('MONTH(tanggal_mulai) as bulan'),
                DB::raw('COUNT(*) as total')
            )
            ->whereYear('tanggal_mulai', date('Y'))
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->pluck('total', 'bulan');

        // Mapping agar bulan kosong tetap muncul
        $chartData = [];
        for ($i = 1; $i <= 12; $i++) {
            $chartData[] = $chartBulanan[$i] ?? 0;
        }

        return view('dashboard', compact(
            'totalWarga',
            'totalFasilitas',
            'peminjamanAktif',
            'totalPembayaran',
            'chartData'
        ));

        $statusPeminjaman = PeminjamanFasilitas::select('status', DB::raw('COUNT(*) as total'))
    ->groupBy('status')
    ->pluck('total', 'status');
    }

    
}
