<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KendaraanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $kendaraan = DB::table('tb_kendaraan')
            ->orderBy('id_kendaraan', 'desc')
            ->get();
        
        // Hitung statistik
        $totalKendaraan = DB::table('tb_kendaraan')->count();
        $totalMotor = DB::table('tb_kendaraan')->where('jenis_kendaraan', 'Motor')->count();
        $totalMobil = DB::table('tb_kendaraan')->where('jenis_kendaraan', 'Mobil')->count();
        $totalTruk = DB::table('tb_kendaraan')->where('jenis_kendaraan', 'Truk')->count();
        
        return view('kendaraan.index', compact('kendaraan', 'totalKendaraan', 'totalMotor', 'totalMobil', 'totalTruk'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return redirect()->route('kendaraan.index');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'plat_nomor' => 'required|string|max:20|unique:tb_kendaraan,plat_nomor',
            'jenis_kendaraan' => 'required|in:Motor,Mobil,Truk',
            'warna' => 'nullable|string|max:30',
            'pemilik' => 'nullable|string|max:100'
        ]);

        $id_kendaraan = DB::table('tb_kendaraan')->insertGetId([
            'plat_nomor' => strtoupper($request->plat_nomor),
            'jenis_kendaraan' => $request->jenis_kendaraan,
            'warna' => $request->warna,
            'pemilik' => $request->pemilik,
            'id_user' => $_SESSION['user']['id_user'] ?? null,
            'created_at' => now()
        ]);

        $this->logActivity('Menambah kendaraan baru: ' . $request->plat_nomor . ' (' . $request->jenis_kendaraan . ')');
        
        return redirect()->route('kendaraan.index')
            ->with('success', 'Kendaraan berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $kendaraan = DB::table('tb_kendaraan')->where('id_kendaraan', $id)->first();
        
        if (!$kendaraan) {
            return redirect()->route('kendaraan.index')
                ->with('error', 'Kendaraan tidak ditemukan!');
        }
        
        // Cek apakah kendaraan sedang parkir
        $sedangParkir = DB::table('tb_transaksi')
            ->where('id_kendaraan', $id)
            ->where('status', 'Masuk')
            ->exists();
        
        // Ambil riwayat transaksi terakhir
        $transaksiTerakhir = DB::table('tb_transaksi')
            ->where('id_kendaraan', $id)
            ->orderBy('waktu_masuk', 'desc')
            ->first();
        
        return view('kendaraan.show', compact('kendaraan', 'sedangParkir', 'transaksiTerakhir'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return redirect()->route('kendaraan.index');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'plat_nomor' => 'required|string|max:20|unique:tb_kendaraan,plat_nomor,' . $id . ',id_kendaraan',
            'jenis_kendaraan' => 'required|in:Motor,Mobil,Truk',
            'warna' => 'nullable|string|max:30',
            'pemilik' => 'nullable|string|max:100'
        ]);

        $kendaraanLama = DB::table('tb_kendaraan')->where('id_kendaraan', $id)->first();
        
        DB::table('tb_kendaraan')->where('id_kendaraan', $id)->update([
            'plat_nomor' => strtoupper($request->plat_nomor),
            'jenis_kendaraan' => $request->jenis_kendaraan,
            'warna' => $request->warna,
            'pemilik' => $request->pemilik,
            'id_user' => $_SESSION['user']['id_user'] ?? null,
            'updated_at' => now()
        ]);

        $this->logActivity('Mengupdate kendaraan: ' . $kendaraanLama->plat_nomor . ' -> ' . $request->plat_nomor);
        
        return redirect()->route('kendaraan.index')
            ->with('success', 'Kendaraan berhasil diupdate!');
    }

    /**
     * Remove the specified resource from storage (HARD DELETE)
     */
    public function destroy($id)
    {
        $kendaraan = DB::table('tb_kendaraan')->where('id_kendaraan', $id)->first();
        
        if (!$kendaraan) {
            return redirect()->route('kendaraan.index')
                ->with('error', 'Kendaraan tidak ditemukan!');
        }
        
        // Cek apakah kendaraan sedang parkir
        $sedangParkir = DB::table('tb_transaksi')
            ->where('id_kendaraan', $id)
            ->where('status', 'Masuk')
            ->exists();
        
        if ($sedangParkir) {
            return redirect()->route('kendaraan.index')
                ->with('error', 'Kendaraan sedang parkir! Keluarkan kendaraan terlebih dahulu sebelum menghapus.');
        }
        
        // Hapus semua transaksi terkait terlebih dahulu
        DB::table('tb_transaksi')->where('id_kendaraan', $id)->delete();
        
        // Hapus kendaraan
        DB::table('tb_kendaraan')->where('id_kendaraan', $id)->delete();
        
        $this->logActivity('Menghapus kendaraan: ' . $kendaraan->plat_nomor . ' beserta riwayat transaksinya');
        
        return redirect()->route('kendaraan.index')
            ->with('success', 'Kendaraan ' . $kendaraan->plat_nomor . ' berhasil dihapus beserta riwayat transaksinya!');
    }
    
    /**
     * Search kendaraan by plat nomor (AJAX)
     */
    public function search(Request $request)
    {
        $plat = $request->get('q');
        $kendaraan = DB::table('tb_kendaraan')
            ->where('plat_nomor', 'LIKE', "%{$plat}%")
            ->limit(10)
            ->get();
        
        return response()->json($kendaraan);
    }
    
    /**
     * Export kendaraan to Excel
     */
    public function export(Request $request)
    {
        $kendaraan = DB::table('tb_kendaraan')
            ->orderBy('id_kendaraan', 'desc')
            ->get();
        
        $filename = 'data_kendaraan_' . date('Y-m-d_H-i-s') . '.xls';
        
        header("Content-Type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=\"$filename\"");
        
        echo "<table border='1'>";
        echo "<thead>";
        echo "<tr style='background: #4e73df; color: white;'>
                <th>No</th>
                <th>ID Kendaraan</th>
                <th>Plat Nomor</th>
                <th>Jenis Kendaraan</th>
                <th>Warna</th>
                <th>Pemilik</th>
                </tr>";
        echo "</thead>";
        echo "<tbody>";
        
        $no = 1;
        foreach ($kendaraan as $item) {
            echo "<tr>";
            echo "<td>" . $no++ . "</td>";
            echo "<td>" . $item->id_kendaraan . "</td>";
            echo "<td>" . $item->plat_nomor . "</td>";
            echo "<td>" . $item->jenis_kendaraan . "</td>";
            echo "<td>" . ($item->warna ?? '-') . "</td>";
            echo "<td>" . ($item->pemilik ?? '-') . "</td>";
            echo "</tr>";
        }
        
        echo "</tbody>";
        echo "</table>";
        exit;
    }
    
    /**
     * Export kendaraan to PDF
     */
    public function exportPdf(Request $request)
    {
        $kendaraan = DB::table('tb_kendaraan')
            ->orderBy('id_kendaraan', 'desc')
            ->get();
        
        $html = '
        <!DOCTYPE html>
        <html>
        <head>
            <title>Data Kendaraan - Sistem Parkir</title>
            <style>
                body { font-family: Arial, sans-serif; font-size: 12px; }
                .header { text-align: center; margin-bottom: 20px; }
                .header h2 { margin-bottom: 5px; }
                table { width: 100%; border-collapse: collapse; }
                th, td { border: 1px solid #000; padding: 8px; text-align: left; }
                th { background: #4e73df; color: white; }
                .footer { margin-top: 20px; text-align: center; font-size: 10px; }
                @media print { .no-print { display: none; } }
            </style>
        </head>
        <body>
            <div class="no-print" style="text-align: center; margin-bottom: 20px;">
                <button onclick="window.print()">Cetak PDF</button>
                <button onclick="window.close()">Tutup</button>
            </div>
            <div class="header">
                <h2>SISTEM MANAJEMEN PARKIR</h2>
                <p>Laporan Data Kendaraan</p>
                <p>Tanggal: ' . date('d/m/Y H:i:s') . '</p>
            </div>
            <table>
                <thead>
                    <tr><th>No</th><th>ID</th><th>Plat Nomor</th><th>Jenis</th><th>Warna</th><th>Pemilik</th></tr>
                </thead>
                <tbody>';
        
        $no = 1;
        foreach ($kendaraan as $item) {
            $html .= "<tr>
                        <td>{$no}</td>
                        <td>{$item->id_kendaraan}</td>
                        <td>{$item->plat_nomor}</td>
                        <td>{$item->jenis_kendaraan}</td>
                        <td>" . ($item->warna ?? '-') . "</td>
                        <td>" . ($item->pemilik ?? '-') . "</td>
                      </tr>";
            $no++;
        }
        
        $html .= '</tbody>
            </table>
            <div class="footer">
                <p>Dicetak oleh: ' . ($_SESSION['user']['nama_lengkap'] ?? 'Admin') . ' | ' . date('d/m/Y H:i:s') . '</p>
            </div>
        </body>
        </html>';
        
        echo $html;
        exit;
    }
    
    /**
     * Log activity
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