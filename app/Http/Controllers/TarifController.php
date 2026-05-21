<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TarifController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tarif = DB::table('tb_tarif')
            ->orderBy('id_tarif', 'asc')
            ->get();
        
        // Hitung statistik
        $totalTarif = DB::table('tb_tarif')->count();
        
        // Ambil semua jenis kendaraan yang unik
        $jenisKendaraanList = DB::table('tb_tarif')
            ->select('jenis_kendaraan')
            ->distinct()
            ->get();
        
        return view('tarif.index', compact('tarif', 'totalTarif', 'jenisKendaraanList'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'jenis_kendaraan' => 'required|string|max:30|unique:tb_tarif,jenis_kendaraan',
            'tarif_per_jam' => 'required|numeric|min:0|max:1000000'
        ]);

        DB::table('tb_tarif')->insert([
            'jenis_kendaraan' => ucfirst($request->jenis_kendaraan),
            'tarif_per_jam' => $request->tarif_per_jam,
            'created_at' => now()
        ]);

        $this->logActivity('Menambah tarif baru: ' . $request->jenis_kendaraan . ' - Rp ' . number_format($request->tarif_per_jam));
        
        return redirect()->route('tarif.index')
            ->with('success', 'Tarif ' . $request->jenis_kendaraan . ' berhasil ditambahkan!');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'tarif_per_jam' => 'required|numeric|min:0|max:1000000'
        ]);

        $tarifLama = DB::table('tb_tarif')->where('id_tarif', $id)->first();
        
        DB::table('tb_tarif')->where('id_tarif', $id)->update([
            'tarif_per_jam' => $request->tarif_per_jam,
            'updated_at' => now()
        ]);

        $this->logActivity('Mengupdate tarif ' . $tarifLama->jenis_kendaraan . ': Rp ' . number_format($tarifLama->tarif_per_jam) . ' -> Rp ' . number_format($request->tarif_per_jam));
        
        return redirect()->route('tarif.index')
            ->with('success', 'Tarif ' . $tarifLama->jenis_kendaraan . ' berhasil diupdate!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $tarif = DB::table('tb_tarif')->where('id_tarif', $id)->first();
        
        if (!$tarif) {
            return redirect()->route('tarif.index')
                ->with('error', 'Tarif tidak ditemukan!');
        }
        
        // Cek apakah tarif sudah digunakan di transaksi
        $digunakan = DB::table('tb_transaksi')
            ->where('id_tarif', $id)
            ->exists();
        
        if ($digunakan) {
            return redirect()->route('tarif.index')
                ->with('error', 'Tarif ' . $tarif->jenis_kendaraan . ' sudah digunakan pada transaksi! Tidak dapat dihapus.');
        }
        
        DB::table('tb_tarif')->where('id_tarif', $id)->delete();
        
        $this->logActivity('Menghapus tarif: ' . $tarif->jenis_kendaraan);
        
        return redirect()->route('tarif.index')
            ->with('success', 'Tarif ' . $tarif->jenis_kendaraan . ' berhasil dihapus!');
    }
    
    /**
     * Export tarif to Excel
     */
    public function export(Request $request)
    {
        $tarif = DB::table('tb_tarif')->orderBy('id_tarif')->get();
        
        $filename = 'data_tarif_' . date('Y-m-d_H-i-s') . '.xls';
        
        header("Content-Type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=\"$filename\"");
        
        echo "<table border='1'>";
        echo "<thead>";
        echo "<tr style='background: #4e73df; color: white;'>
                <th>No</th>
                <th>ID Tarif</th>
                <th>Jenis Kendaraan</th>
                <th>Tarif per Jam</th>
                <th>Status</th>
               </tr>";
        echo "</thead>";
        echo "<tbody>";
        
        $no = 1;
        foreach ($tarif as $item) {
            echo "<tr>";
            echo "<td>" . $no++ . "</td>";
            echo "<td>" . $item->id_tarif . "</td>";
            echo "<td>" . $item->jenis_kendaraan . "</td>";
            echo "<td>Rp " . number_format($item->tarif_per_jam) . "</td>";
            echo "<td>Aktif</td>";
            echo "</tr>";
        }
        
        echo "</tbody>";
        echo "</table>";
        exit;
    }
    
    /**
     * Export tarif to PDF
     */
    public function exportPdf(Request $request)
    {
        $tarif = DB::table('tb_tarif')->orderBy('id_tarif')->get();
        
        $html = '
        <!DOCTYPE html>
        <html>
        <head>
            <title>Data Tarif - Sistem Parkir</title>
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
                <p>Laporan Data Tarif Parkir</p>
                <p>Tanggal: ' . date('d/m/Y H:i:s') . '</p>
            </div>
            <table>
                <thead>
                    <tr><th>No</th><th>ID</th><th>Jenis Kendaraan</th><th>Tarif per Jam</th><th>Status</th></tr>
                </thead>
                <tbody>';
        
        $no = 1;
        foreach ($tarif as $item) {
            $html .= "<tr>
                        <td>{$no}</td>
                        <td>{$item->id_tarif}</td>
                        <td>{$item->jenis_kendaraan}</td>
                        <td>Rp " . number_format($item->tarif_per_jam) . "</td>
                        <td>Aktif</td>
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
     * Get all jenis kendaraan for dropdown (AJAX)
     */
    public function getJenisKendaraan()
    {
        $jenis = DB::table('tb_tarif')
            ->select('jenis_kendaraan', 'tarif_per_jam')
            ->orderBy('jenis_kendaraan', 'asc')
            ->get();
        
        return response()->json($jenis);
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