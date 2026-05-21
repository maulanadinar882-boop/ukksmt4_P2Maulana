<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LaporanController extends Controller
{
    /**
     * Display laporan page
     */
    public function index(Request $request)
    {
        // Filter tanggal
        $startDate = $request->get('start_date', date('Y-m-01'));
        $endDate = $request->get('end_date', date('Y-m-d'));
        $jenis = $request->get('jenis', 'semua');
        
        // Query transaksi berdasarkan filter
        $query = DB::table('tb_transaksi')
            ->join('tb_kendaraan', 'tb_transaksi.id_kendaraan', '=', 'tb_kendaraan.id_kendaraan')
            ->join('tb_area_parkir', 'tb_transaksi.id_area', '=', 'tb_area_parkir.id_area')
            ->join('tb_tarif', 'tb_transaksi.id_tarif', '=', 'tb_tarif.id_tarif')
            ->join('tb_user', 'tb_transaksi.id_user', '=', 'tb_user.id_user')
            ->whereBetween('tb_transaksi.waktu_masuk', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->where('tb_transaksi.status', 'Keluar');
        
        if ($jenis != 'semua') {
            $query->where('tb_kendaraan.jenis_kendaraan', $jenis);
        }
        
        $transaksi = $query->orderBy('tb_transaksi.waktu_masuk', 'desc')
            ->get();
        
        // Statistik laporan
        $totalTransaksi = $transaksi->count();
        $totalPendapatan = $transaksi->sum('biaya_total');
        $totalDurasi = $transaksi->sum('durasi_jam');
        
        // Statistik per jenis kendaraan
        $statistikJenis = DB::table('tb_transaksi')
            ->join('tb_kendaraan', 'tb_transaksi.id_kendaraan', '=', 'tb_kendaraan.id_kendaraan')
            ->whereBetween('tb_transaksi.waktu_masuk', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->where('tb_transaksi.status', 'Keluar')
            ->select('tb_kendaraan.jenis_kendaraan', 
                DB::raw('count(*) as total'),
                DB::raw('sum(tb_transaksi.biaya_total) as pendapatan'))
            ->groupBy('tb_kendaraan.jenis_kendaraan')
            ->get();
        
        // Statistik per area
        $statistikArea = DB::table('tb_transaksi')
            ->join('tb_area_parkir', 'tb_transaksi.id_area', '=', 'tb_area_parkir.id_area')
            ->whereBetween('tb_transaksi.waktu_masuk', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->where('tb_transaksi.status', 'Keluar')
            ->select('tb_area_parkir.nama_area', 
                DB::raw('count(*) as total'),
                DB::raw('sum(tb_transaksi.biaya_total) as pendapatan'))
            ->groupBy('tb_area_parkir.nama_area')
            ->get();
        
        // Statistik per hari
        $statistikHarian = DB::table('tb_transaksi')
            ->whereBetween('waktu_masuk', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->where('status', 'Keluar')
            ->select(DB::raw('DATE(waktu_masuk) as tanggal'), 
                DB::raw('count(*) as total'),
                DB::raw('sum(biaya_total) as pendapatan'))
            ->groupBy(DB::raw('DATE(waktu_masuk)'))
            ->orderBy('tanggal', 'desc')
            ->get();
        
        $data = [
            'startDate' => $startDate,
            'endDate' => $endDate,
            'jenis' => $jenis,
            'transaksi' => $transaksi,
            'totalTransaksi' => $totalTransaksi,
            'totalPendapatan' => $totalPendapatan,
            'totalDurasi' => $totalDurasi,
            'statistikJenis' => $statistikJenis,
            'statistikArea' => $statistikArea,
            'statistikHarian' => $statistikHarian
        ];
        
        return view('laporan.index', $data);
    }
    
    /**
     * Export to Excel
     */
    public function exportExcel(Request $request)
    {
        $startDate = $request->get('start_date', date('Y-m-01'));
        $endDate = $request->get('end_date', date('Y-m-d'));
        $jenis = $request->get('jenis', 'semua');
        
        $query = DB::table('tb_transaksi')
            ->join('tb_kendaraan', 'tb_transaksi.id_kendaraan', '=', 'tb_kendaraan.id_kendaraan')
            ->join('tb_area_parkir', 'tb_transaksi.id_area', '=', 'tb_area_parkir.id_area')
            ->join('tb_user', 'tb_transaksi.id_user', '=', 'tb_user.id_user')
            ->whereBetween('tb_transaksi.waktu_masuk', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->where('tb_transaksi.status', 'Keluar');
        
        if ($jenis != 'semua') {
            $query->where('tb_kendaraan.jenis_kendaraan', $jenis);
        }
        
        $transaksi = $query->orderBy('tb_transaksi.waktu_masuk', 'desc')->get();
        
        // Create Excel file
        $filename = 'laporan_parkir_' . $startDate . '_sd_' . $endDate . '.xls';
        
        header("Content-Type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=\"$filename\"");
        
        echo "<table border='1'>";
        echo "<tr>
                <th>No</th>
                <th>Tanggal Masuk</th>
                <th>Tanggal Keluar</th>
                <th>Plat Nomor</th>
                <th>Jenis</th>
                <th>Area</th>
                <th>Durasi (Jam)</th>
                <th>Biaya</th>
                <th>Petugas</th>
              </tr>";
        
        $no = 1;
        foreach ($transaksi as $item) {
            echo "<tr>";
            echo "<td>" . $no++ . "</td>";
            echo "<td>" . date('d/m/Y H:i:s', strtotime($item->waktu_masuk)) . "</td>";
            echo "<td>" . ($item->waktu_keluar ? date('d/m/Y H:i:s', strtotime($item->waktu_keluar)) : '-') . "</td>";
            echo "<td>" . $item->plat_nomor . "</td>";
            echo "<td>" . $item->jenis_kendaraan . "</td>";
            echo "<td>" . $item->nama_area . "</td>";
            echo "<td>" . ($item->durasi_jam ?? '-') . "</td>";
            echo "<td>Rp " . number_format($item->biaya_total ?? 0) . "</td>";
            echo "<td>" . $item->nama_lengkap . "</td>";
            echo "</tr>";
        }
        
        echo "</table>";
        exit;
    }
    
    /**
     * Export to PDF (menggunakan HTML2PDF sederhana)
     */
    public function exportPdf(Request $request)
    {
        $startDate = $request->get('start_date', date('Y-m-01'));
        $endDate = $request->get('end_date', date('Y-m-d'));
        $jenis = $request->get('jenis', 'semua');
        
        $query = DB::table('tb_transaksi')
            ->join('tb_kendaraan', 'tb_transaksi.id_kendaraan', '=', 'tb_kendaraan.id_kendaraan')
            ->join('tb_area_parkir', 'tb_transaksi.id_area', '=', 'tb_area_parkir.id_area')
            ->join('tb_user', 'tb_transaksi.id_user', '=', 'tb_user.id_user')
            ->whereBetween('tb_transaksi.waktu_masuk', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->where('tb_transaksi.status', 'Keluar');
        
        if ($jenis != 'semua') {
            $query->where('tb_kendaraan.jenis_kendaraan', $jenis);
        }
        
        $transaksi = $query->orderBy('tb_transaksi.waktu_masuk', 'desc')->get();
        $totalPendapatan = $transaksi->sum('biaya_total');
        $totalTransaksi = $transaksi->count();
        
        $html = view('laporan.pdf', compact('transaksi', 'startDate', 'endDate', 'totalPendapatan', 'totalTransaksi', 'jenis'))->render();
        
        // Simple PDF output (pastikan ada library atau gunakan browser print)
        echo "<!DOCTYPE html>
        <html>
        <head>
            <title>Laporan Parkir</title>
            <style>
                body { font-family: Arial, sans-serif; font-size: 12px; }
                .header { text-align: center; margin-bottom: 20px; }
                table { width: 100%; border-collapse: collapse; }
                th, td { border: 1px solid #000; padding: 8px; text-align: left; }
                th { background: #f0f0f0; }
                .footer { margin-top: 20px; text-align: center; }
                @media print {
                    .no-print { display: none; }
                }
            </style>
        </head>
        <body>
            <div class='no-print' style='text-align: center; margin-bottom: 20px;'>
                <button onclick='window.print()'>Cetak PDF</button>
                <button onclick='window.close()'>Tutup</button>
            </div>
            $html
        </body>
        </html>";
        exit;
    }
    
    /**
     * Laporan harian
     */
    public function laporanHarian(Request $request)
    {
        $tanggal = $request->get('tanggal', date('Y-m-d'));
        
        $transaksi = DB::table('tb_transaksi')
            ->join('tb_kendaraan', 'tb_transaksi.id_kendaraan', '=', 'tb_kendaraan.id_kendaraan')
            ->join('tb_area_parkir', 'tb_transaksi.id_area', '=', 'tb_area_parkir.id_area')
            ->whereDate('tb_transaksi.waktu_masuk', $tanggal)
            ->where('tb_transaksi.status', 'Keluar')
            ->orderBy('tb_transaksi.waktu_masuk', 'desc')
            ->get();
        
        $totalPendapatan = $transaksi->sum('biaya_total');
        $totalTransaksi = $transaksi->count();
        
        return view('laporan.harian', compact('transaksi', 'tanggal', 'totalPendapatan', 'totalTransaksi'));
    }
    
    /**
     * Laporan bulanan
     */
    public function laporanBulanan(Request $request)
    {
        $bulan = $request->get('bulan', date('m'));
        $tahun = $request->get('tahun', date('Y'));
        
        $transaksi = DB::table('tb_transaksi')
            ->join('tb_kendaraan', 'tb_transaksi.id_kendaraan', '=', 'tb_kendaraan.id_kendaraan')
            ->join('tb_area_parkir', 'tb_transaksi.id_area', '=', 'tb_area_parkir.id_area')
            ->whereYear('tb_transaksi.waktu_masuk', $tahun)
            ->whereMonth('tb_transaksi.waktu_masuk', $bulan)
            ->where('tb_transaksi.status', 'Keluar')
            ->orderBy('tb_transaksi.waktu_masuk', 'desc')
            ->get();
        
        $totalPendapatan = $transaksi->sum('biaya_total');
        $totalTransaksi = $transaksi->count();
        
        return view('laporan.bulanan', compact('transaksi', 'bulan', 'tahun', 'totalPendapatan', 'totalTransaksi'));
    }
    
    /**
     * Laporan tahunan
     */
    public function laporanTahunan(Request $request)
    {
        $tahun = $request->get('tahun', date('Y'));
        
        $statistikBulanan = DB::table('tb_transaksi')
            ->whereYear('waktu_masuk', $tahun)
            ->where('status', 'Keluar')
            ->select(DB::raw('MONTH(waktu_masuk) as bulan'), 
                DB::raw('count(*) as total'),
                DB::raw('sum(biaya_total) as pendapatan'))
            ->groupBy(DB::raw('MONTH(waktu_masuk)'))
            ->orderBy('bulan', 'asc')
            ->get();
        
        return view('laporan.tahunan', compact('statistikBulanan', 'tahun'));
    }
}