<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransaksiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Ambil semua jenis kendaraan dari tabel tarif (dinamis)
        $jenisKendaraan = DB::table('tb_tarif')
            ->select('jenis_kendaraan', 'tarif_per_jam')
            ->orderBy('jenis_kendaraan', 'asc')
            ->get();
        
        $kendaraan = DB::table('tb_kendaraan')->orderBy('plat_nomor')->get();
        $areas = DB::table('tb_area_parkir')->get();
        
        // Transaksi yang sedang aktif (belum keluar)
        $transaksiAktif = DB::table('tb_transaksi')
            ->join('tb_kendaraan', 'tb_transaksi.id_kendaraan', '=', 'tb_kendaraan.id_kendaraan')
            ->join('tb_area_parkir', 'tb_transaksi.id_area', '=', 'tb_area_parkir.id_area')
            ->join('tb_tarif', 'tb_transaksi.id_tarif', '=', 'tb_tarif.id_tarif')
            ->where('tb_transaksi.status', 'Masuk')
            ->orderBy('tb_transaksi.waktu_masuk', 'desc')
            ->select('tb_transaksi.*', 'tb_kendaraan.plat_nomor', 'tb_kendaraan.jenis_kendaraan', 'tb_area_parkir.nama_area')
            ->get();
        
        // Transaksi hari ini
        $transaksiHariIni = DB::table('tb_transaksi')
            ->join('tb_kendaraan', 'tb_transaksi.id_kendaraan', '=', 'tb_kendaraan.id_kendaraan')
            ->join('tb_area_parkir', 'tb_transaksi.id_area', '=', 'tb_area_parkir.id_area')
            ->join('tb_tarif', 'tb_transaksi.id_tarif', '=', 'tb_tarif.id_tarif')
            ->whereDate('tb_transaksi.waktu_masuk', date('Y-m-d'))
            ->orderBy('tb_transaksi.waktu_masuk', 'desc')
            ->limit(20)
            ->get();
        
        // Statistik hari ini
        $statistikHariIni = [
            'total_masuk' => DB::table('tb_transaksi')->whereDate('waktu_masuk', date('Y-m-d'))->count(),
            'total_keluar' => DB::table('tb_transaksi')->whereDate('waktu_keluar', date('Y-m-d'))->count(),
            'pendapatan' => DB::table('tb_transaksi')->whereDate('waktu_keluar', date('Y-m-d'))->where('status', 'Keluar')->sum('biaya_total'),
            'aktif' => DB::table('tb_transaksi')->where('status', 'Masuk')->count()
        ];
        
        return view('transaksi.index', compact('jenisKendaraan', 'kendaraan', 'areas', 'transaksiAktif', 'transaksiHariIni', 'statistikHariIni'));
    }

    /**
     * Kendaraan masuk
     */
    public function masuk(Request $request)
    {
        $request->validate([
            'plat_nomor' => 'required|string|max:20',
            'jenis_kendaraan' => 'required|string|max:30',
            'id_area' => 'required|exists:tb_area_parkir,id_area'
        ]);

        // Cek apakah kendaraan sudah ada
        $kendaraan = DB::table('tb_kendaraan')
            ->where('plat_nomor', strtoupper($request->plat_nomor))
            ->first();
        
        if (!$kendaraan) {
            // Insert kendaraan baru
            $idKendaraan = DB::table('tb_kendaraan')->insertGetId([
                'plat_nomor' => strtoupper($request->plat_nomor),
                'jenis_kendaraan' => $request->jenis_kendaraan,
                'warna' => $request->warna,
                'pemilik' => $request->pemilik,
                'id_user' => $_SESSION['user']['id_user'] ?? null
            ]);
        } else {
            $idKendaraan = $kendaraan->id_kendaraan;
        }
        
        // Cek apakah kendaraan sedang parkir
        $sedangParkir = DB::table('tb_transaksi')
            ->where('id_kendaraan', $idKendaraan)
            ->where('status', 'Masuk')
            ->exists();
        
        if ($sedangParkir) {
            return redirect()->route('transaksi.index')
                ->with('error', 'Kendaraan dengan plat ' . $request->plat_nomor . ' sedang parkir!');
        }
        
        // Cek kapasitas area
        $area = DB::table('tb_area_parkir')->where('id_area', $request->id_area)->first();
        if ($area->terisi >= $area->kapasitas) {
            return redirect()->route('transaksi.index')
                ->with('error', 'Area parkir ' . $area->nama_area . ' sudah penuh!');
        }
        
        // Cari tarif berdasarkan jenis kendaraan yang dipilih
        $tarif = DB::table('tb_tarif')
            ->where('jenis_kendaraan', $request->jenis_kendaraan)
            ->first();
        
        if (!$tarif) {
            return redirect()->route('transaksi.index')
                ->with('error', 'Tarif untuk ' . $request->jenis_kendaraan . ' belum diatur! Silakan tambah jenis kendaraan di menu Tarif.');
        }
        
        // Insert transaksi
        $idParkir = DB::table('tb_transaksi')->insertGetId([
            'id_kendaraan' => $idKendaraan,
            'waktu_masuk' => date('Y-m-d H:i:s'),
            'id_tarif' => $tarif->id_tarif,
            'status' => 'Masuk',
            'id_user' => $_SESSION['user']['id_user'] ?? null,
            'id_area' => $request->id_area
        ]);
        
        // Update kapasitas area
        DB::table('tb_area_parkir')
            ->where('id_area', $request->id_area)
            ->increment('terisi');
        
        // Log aktivitas
        $this->logActivity('Kendaraan masuk: ' . $request->plat_nomor . ' (' . $request->jenis_kendaraan . ') - Area: ' . $area->nama_area);
        
        return redirect()->route('transaksi.index')
            ->with('success', 'Kendaraan ' . $request->plat_nomor . ' masuk ke ' . $area->nama_area);
    }

    /**
     * Kendaraan keluar
     */
    public function keluar(Request $request)
    {
        $request->validate([
            'id_parkir' => 'required|exists:tb_transaksi,id_parkir'
        ]);
        
        $transaksi = DB::table('tb_transaksi')
            ->join('tb_kendaraan', 'tb_transaksi.id_kendaraan', '=', 'tb_kendaraan.id_kendaraan')
            ->join('tb_tarif', 'tb_transaksi.id_tarif', '=', 'tb_tarif.id_tarif')
            ->join('tb_area_parkir', 'tb_transaksi.id_area', '=', 'tb_area_parkir.id_area')
            ->where('tb_transaksi.id_parkir', $request->id_parkir)
            ->where('tb_transaksi.status', 'Masuk')
            ->first();
        
        if (!$transaksi) {
            return redirect()->route('transaksi.index')
                ->with('error', 'Transaksi tidak ditemukan atau kendaraan sudah keluar!');
        }
        
        // Hitung durasi
        $waktuMasuk = new \DateTime($transaksi->waktu_masuk);
        $waktuKeluar = new \DateTime();
        $diff = $waktuMasuk->diff($waktuKeluar);
        
        // Hitung jam (dibulatkan ke atas, minimal 1 jam)
        $durasiJam = ($diff->h) + ($diff->i > 0 ? 1 : 0);
        if ($durasiJam < 1) $durasiJam = 1;
        
        $biayaTotal = $durasiJam * $transaksi->tarif_per_jam;
        
        // Update transaksi
        DB::table('tb_transaksi')
            ->where('id_parkir', $request->id_parkir)
            ->update([
                'waktu_keluar' => date('Y-m-d H:i:s'),
                'durasi_jam' => $durasiJam,
                'biaya_total' => $biayaTotal,
                'status' => 'Keluar'
            ]);
        
        // Update kapasitas area
        DB::table('tb_area_parkir')
            ->where('id_area', $transaksi->id_area)
            ->decrement('terisi');
        
        // Log aktivitas
        $this->logActivity('Kendaraan keluar: ' . $transaksi->plat_nomor . ' - Durasi: ' . $durasiJam . ' jam - Bayar: Rp ' . number_format($biayaTotal));
        
        return redirect()->route('transaksi.index')
            ->with('success', 'Kendaraan ' . $transaksi->plat_nomor . ' keluar. Total bayar: Rp ' . number_format($biayaTotal));
    }
    
    /**
     * Cetak struk
     */
    public function struk($id)
    {
        $transaksi = DB::table('tb_transaksi')
            ->join('tb_kendaraan', 'tb_transaksi.id_kendaraan', '=', 'tb_kendaraan.id_kendaraan')
            ->join('tb_tarif', 'tb_transaksi.id_tarif', '=', 'tb_tarif.id_tarif')
            ->join('tb_area_parkir', 'tb_transaksi.id_area', '=', 'tb_area_parkir.id_area')
            ->join('tb_user', 'tb_transaksi.id_user', '=', 'tb_user.id_user')
            ->where('tb_transaksi.id_parkir', $id)
            ->first();
        
        if (!$transaksi) {
            return redirect()->route('transaksi.index')
                ->with('error', 'Struk tidak ditemukan!');
        }
        
        return view('transaksi.struk', compact('transaksi'));
    }
    
    /**
     * Cari kendaraan by plat (AJAX)
     */
    public function cariKendaraan(Request $request)
    {
        $plat = $request->get('q');
        $kendaraan = DB::table('tb_kendaraan')
            ->where('plat_nomor', 'LIKE', "%{$plat}%")
            ->limit(10)
            ->get();
        
        return response()->json($kendaraan);
    }
    
    /**
     * Log aktivitas
     */
    private function logActivity($aktivitas)
    {
        if (isset($_SESSION['user'])) {
            DB::table('tb_log_aktivitas')->insert([
                'id_user' => $_SESSION['user']['id_user'],
                'aktivitas' => $aktivitas,
                'waktu_aktivitas' => now()
            ]);
        }
    }
}