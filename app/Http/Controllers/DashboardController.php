<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $role = $_SESSION['user']['role'] ?? 'Admin';
        $userId = $_SESSION['user']['id_user'] ?? null;
        
        // ==================== DATA UMUM UNTUK SEMUA ROLE ====================
        
        // Total kendaraan terdaftar
        $totalKendaraan = DB::table('tb_kendaraan')->count();
        
        // Parkir aktif (sedang parkir)
        $parkirAktif = DB::table('tb_transaksi')->where('status', 'Masuk')->count();
        
        // Total area parkir
        $totalArea = DB::table('tb_area_parkir')->count();
        
        // Total kapasitas dan terisi
        $totalKapasitas = DB::table('tb_area_parkir')->sum('kapasitas');
        $totalTerisi = DB::table('tb_area_parkir')->sum('terisi');
        $persentaseKapasitas = $totalKapasitas > 0 ? round(($totalTerisi / $totalKapasitas) * 100) : 0;
        
        // Pendapatan hari ini
        $pendapatanHariIni = DB::table('tb_transaksi')
            ->whereDate('waktu_keluar', date('Y-m-d'))
            ->where('status', 'Keluar')
            ->sum('biaya_total');
        
        // Pendapatan kemarin
        $pendapatanKemarin = DB::table('tb_transaksi')
            ->whereDate('waktu_keluar', date('Y-m-d', strtotime('-1 day')))
            ->where('status', 'Keluar')
            ->sum('biaya_total');
        
        // Persentase perubahan pendapatan
        $persentasePendapatan = $pendapatanKemarin > 0 
            ? round((($pendapatanHariIni - $pendapatanKemarin) / $pendapatanKemarin) * 100) 
            : ($pendapatanHariIni > 0 ? 100 : 0);
        
        // Pendapatan bulan ini
        $pendapatanBulanIni = DB::table('tb_transaksi')
            ->whereYear('waktu_keluar', date('Y'))
            ->whereMonth('waktu_keluar', date('m'))
            ->where('status', 'Keluar')
            ->sum('biaya_total');
        
        // Pendapatan tahun ini
        $pendapatanTahunIni = DB::table('tb_transaksi')
            ->whereYear('waktu_keluar', date('Y'))
            ->where('status', 'Keluar')
            ->sum('biaya_total');
        
        // Transaksi hari ini
        $transaksiMasukHariIni = DB::table('tb_transaksi')
            ->whereDate('waktu_masuk', date('Y-m-d'))
            ->count();
        
        $transaksiKeluarHariIni = DB::table('tb_transaksi')
            ->whereDate('waktu_keluar', date('Y-m-d'))
            ->where('status', 'Keluar')
            ->count();
        
        // ==================== DATA UNTUK CHART ====================
        
        // Chart Pendapatan 7 Hari Terakhir
        $chart7HariLabels = [];
        $chart7HariData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = date('Y-m-d', strtotime("-$i days"));
            $chart7HariLabels[] = date('d/m', strtotime($date));
            $total = DB::table('tb_transaksi')
                ->whereDate('waktu_keluar', $date)
                ->where('status', 'Keluar')
                ->sum('biaya_total');
            $chart7HariData[] = $total;
        }
        
        // Chart Pendapatan 12 Bulan Terakhir
        $chart12BulanLabels = [];
        $chart12BulanData = [];
        for ($i = 11; $i >= 0; $i--) {
            $bulan = date('m', strtotime("-$i months"));
            $tahun = date('Y', strtotime("-$i months"));
            $chart12BulanLabels[] = date('M Y', strtotime("-$i months"));
            $total = DB::table('tb_transaksi')
                ->whereYear('waktu_keluar', $tahun)
                ->whereMonth('waktu_keluar', $bulan)
                ->where('status', 'Keluar')
                ->sum('biaya_total');
            $chart12BulanData[] = $total;
        }
        
        // Chart Jenis Kendaraan (Semua waktu)
        $jenisKendaraanAll = DB::table('tb_kendaraan')
            ->select('jenis_kendaraan', DB::raw('count(*) as total'))
            ->groupBy('jenis_kendaraan')
            ->get();
        
        $chartJenisLabels = [];
        $chartJenisData = [];
        $chartJenisColors = [];
        foreach ($jenisKendaraanAll as $item) {
            $chartJenisLabels[] = $item->jenis_kendaraan;
            $chartJenisData[] = $item->total;
            $color = $item->jenis_kendaraan == 'Motor' ? '#36b9cc' : ($item->jenis_kendaraan == 'Mobil' ? '#4e73df' : '#f6c23e');
            $chartJenisColors[] = $color;
        }
        
        // Chart Transaksi per Jam Hari Ini
        $chartJamLabels = [];
        $chartJamData = [];
        for ($i = 0; $i <= 23; $i++) {
            $jam = str_pad($i, 2, '0', STR_PAD_LEFT);
            $chartJamLabels[] = $jam . ':00';
            $total = DB::table('tb_transaksi')
                ->whereDate('waktu_masuk', date('Y-m-d'))
                ->whereTime('waktu_masuk', '>=', $jam . ':00:00')
                ->whereTime('waktu_masuk', '<', ($i + 1) . ':00:00')
                ->count();
            $chartJamData[] = $total;
        }
        
        // Data area parkir
        $areaData = DB::table('tb_area_parkir')
            ->select('nama_area', 'kapasitas', 'terisi')
            ->get();
        
        // Transaksi terbaru (5 data)
        $transaksiTerbaru = DB::table('tb_transaksi')
            ->join('tb_kendaraan', 'tb_transaksi.id_kendaraan', '=', 'tb_kendaraan.id_kendaraan')
            ->join('tb_area_parkir', 'tb_transaksi.id_area', '=', 'tb_area_parkir.id_area')
            ->select(
                'tb_transaksi.*', 
                'tb_kendaraan.plat_nomor', 
                'tb_kendaraan.jenis_kendaraan',
                'tb_kendaraan.warna',
                'tb_area_parkir.nama_area'
            )
            ->orderBy('tb_transaksi.waktu_masuk', 'desc')
            ->limit(10)
            ->get();
        
        // ==================== DATA KHUSUS ADMIN ====================
        $totalUser = 0;
        $userPerRole = [];
        $logTerbaru = [];
        
        if ($role == 'Admin') {
            $totalUser = DB::table('tb_user')->where('status_aktif', 1)->count();
            $userPerRole = DB::table('tb_user')
                ->select('role', DB::raw('count(*) as total'))
                ->groupBy('role')
                ->get();
            $logTerbaru = DB::table('tb_log_aktivitas')
                ->join('tb_user', 'tb_log_aktivitas.id_user', '=', 'tb_user.id_user')
                ->select('tb_log_aktivitas.*', 'tb_user.nama_lengkap', 'tb_user.role')
                ->orderBy('waktu_aktivitas', 'desc')
                ->limit(8)
                ->get();
        }
        
        // ==================== DATA KHUSUS PETUGAS ====================
        $transaksiHariIniPetugas = 0;
        $targetHarian = 50;
        $shiftHariIni = null;
        $kinerjaBulanIni = 0;
        
        if ($role == 'Petugas' && $userId) {
            $transaksiHariIniPetugas = DB::table('tb_transaksi')
                ->whereDate('waktu_masuk', date('Y-m-d'))
                ->where('id_user', $userId)
                ->count();
            
            // Shift hari ini
            $shiftHariIni = DB::table('user_shift')
                ->join('shift', 'user_shift.id_shift', '=', 'shift.id_shift')
                ->where('user_shift.id_user', $userId)
                ->whereDate('user_shift.tanggal', date('Y-m-d'))
                ->first();
            
            // Kinerja bulan ini (total transaksi yang ditangani)
            $kinerjaBulanIni = DB::table('tb_transaksi')
                ->whereYear('waktu_masuk', date('Y'))
                ->whereMonth('waktu_masuk', date('m'))
                ->where('id_user', $userId)
                ->count();
        }
        
        // ==================== DATA KHUSUS OWNER ====================
        $pendapatanPerBulan = [];
        $pertumbuhanBulanan = [];
        
        if ($role == 'Owner') {
            // Pendapatan per bulan untuk chart
            for ($i = 1; $i <= 12; $i++) {
                $total = DB::table('tb_transaksi')
                    ->whereYear('waktu_keluar', date('Y'))
                    ->whereMonth('waktu_keluar', $i)
                    ->where('status', 'Keluar')
                    ->sum('biaya_total');
                $pendapatanPerBulan[] = $total;
            }
            
            // Pertumbuhan bulanan
            for ($i = 1; $i <= 11; $i++) {
                $bulanIni = $pendapatanPerBulan[$i - 1] ?? 0;
                $bulanLalu = $pendapatanPerBulan[$i] ?? 0;
                $pertumbuhan = $bulanLalu > 0 ? round((($bulanIni - $bulanLalu) / $bulanLalu) * 100) : 0;
                $pertumbuhanBulanan[] = $pertumbuhan;
            }
        }
        
        return view('dashboard.index', compact(
            'role',
            'totalKendaraan',
            'parkirAktif',
            'totalArea',
            'totalKapasitas',
            'totalTerisi',
            'persentaseKapasitas',
            'pendapatanHariIni',
            'pendapatanKemarin',
            'persentasePendapatan',
            'pendapatanBulanIni',
            'pendapatanTahunIni',
            'transaksiMasukHariIni',
            'transaksiKeluarHariIni',
            'chart7HariLabels',
            'chart7HariData',
            'chart12BulanLabels',
            'chart12BulanData',
            'chartJenisLabels',
            'chartJenisData',
            'chartJenisColors',
            'chartJamLabels',
            'chartJamData',
            'areaData',
            'transaksiTerbaru',
            'totalUser',
            'userPerRole',
            'logTerbaru',
            'transaksiHariIniPetugas',
            'targetHarian',
            'shiftHariIni',
            'kinerjaBulanIni',
            'pendapatanPerBulan',
            'pertumbuhanBulanan'
        ));
    }
}