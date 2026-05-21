<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AreaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $areas = DB::table('tb_area_parkir')
            ->orderBy('id_area', 'desc')
            ->get();
        
        // Hitung total kapasitas dan total terisi
        $totalKapasitas = DB::table('tb_area_parkir')->sum('kapasitas');
        $totalTerisi = DB::table('tb_area_parkir')->sum('terisi');
        $persentaseTerisi = $totalKapasitas > 0 ? round(($totalTerisi / $totalKapasitas) * 100) : 0;
        
        return view('area.index', compact('areas', 'totalKapasitas', 'totalTerisi', 'persentaseTerisi'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Tidak digunakan karena pakai modal
        return redirect()->route('area.index');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_area' => 'required|string|max:50|unique:tb_area_parkir,nama_area',
            'kapasitas' => 'required|integer|min:1|max:9999',
        ]);

        $id_area = DB::table('tb_area_parkir')->insertGetId([
            'nama_area' => $request->nama_area,
            'kapasitas' => $request->kapasitas,
            'terisi' => 0
        ]);

        $this->logActivity('Menambah area parkir baru: ' . $request->nama_area . ' (Kapasitas: ' . $request->kapasitas . ')');
        
        return redirect()->route('area.index')
            ->with('success', 'Area parkir berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $area = DB::table('tb_area_parkir')->where('id_area', $id)->first();
        
        if (!$area) {
            return redirect()->route('area.index')
                ->with('error', 'Area parkir tidak ditemukan!');
        }
        
        // Hitung persentase keterisian
        $persentase = $area->kapasitas > 0 ? round(($area->terisi / $area->kapasitas) * 100) : 0;
        $statusColor = $persentase >= 90 ? 'danger' : ($persentase >= 70 ? 'warning' : 'success');
        $statusText = $persentase >= 90 ? 'Penuh' : ($persentase >= 70 ? 'Hampir Penuh' : 'Tersedia');
        
        // Ambil transaksi yang sedang aktif di area ini
        $transaksiAktif = DB::table('tb_transaksi')
            ->join('tb_kendaraan', 'tb_transaksi.id_kendaraan', '=', 'tb_kendaraan.id_kendaraan')
            ->where('tb_transaksi.id_area', $id)
            ->where('tb_transaksi.status', 'Masuk')
            ->orderBy('tb_transaksi.waktu_masuk', 'desc')
            ->limit(10)
            ->get();
        
        return view('area.show', compact('area', 'persentase', 'statusColor', 'statusText', 'transaksiAktif'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        // Tidak digunakan karena pakai modal
        return redirect()->route('area.index');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_area' => 'required|string|max:50|unique:tb_area_parkir,nama_area,' . $id . ',id_area',
            'kapasitas' => 'required|integer|min:1|max:9999'
        ]);

        $areaLama = DB::table('tb_area_parkir')->where('id_area', $id)->first();
        
        DB::table('tb_area_parkir')->where('id_area', $id)->update([
            'nama_area' => $request->nama_area,
            'kapasitas' => $request->kapasitas
        ]);

        $this->logActivity('Mengupdate area parkir: ' . $areaLama->nama_area . ' -> ' . $request->nama_area);
        
        return redirect()->route('area.index')
            ->with('success', 'Area parkir berhasil diupdate!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $area = DB::table('tb_area_parkir')->where('id_area', $id)->first();
        
        if (!$area) {
            return redirect()->route('area.index')
                ->with('error', 'Area parkir tidak ditemukan!');
        }
        
        // Cek apakah area masih memiliki kendaraan yang parkir
        if ($area->terisi > 0) {
            return redirect()->route('area.index')
                ->with('error', 'Area parkir masih memiliki ' . $area->terisi . ' kendaraan! Kosongkan area terlebih dahulu.');
        }
        
        DB::table('tb_area_parkir')->where('id_area', $id)->delete();
        
        $this->logActivity('Menghapus area parkir: ' . $area->nama_area);
        
        return redirect()->route('area.index')
            ->with('success', 'Area parkir berhasil dihapus!');
    }
    
    /**
     * Reset kapasitas terisi ke 0
     */
    public function resetTerisi($id)
    {
        $area = DB::table('tb_area_parkir')->where('id_area', $id)->first();
        
        if (!$area) {
            return redirect()->route('area.index')
                ->with('error', 'Area parkir tidak ditemukan!');
        }
        
        DB::table('tb_area_parkir')->where('id_area', $id)->update([
            'terisi' => 0
        ]);
        
        $this->logActivity('Reset keterisian area parkir: ' . $area->nama_area);
        
        return redirect()->route('area.index')
            ->with('success', 'Keterisian area ' . $area->nama_area . ' berhasil direset!');
    }
    
    /**
     * Export area to Excel
     */
    public function export(Request $request)
    {
        $area = DB::table('tb_area_parkir')->orderBy('id_area')->get();
        
        $filename = 'data_area_' . date('Y-m-d_H-i-s') . '.xls';
        
        header("Content-Type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=\"$filename\"");
        
        echo "<table border='1'>";
        echo "<thead>";
        echo "<tr style='background: #4e73df; color: white;'>
                <th>No</th>
                <th>ID Area</th>
                <th>Nama Area</th>
                <th>Kapasitas</th>
                <th>Terisi</th>
                <th>Persentase</th>
                </tr>";
        echo "</thead>";
        echo "<tbody>";
        
        $no = 1;
        foreach ($area as $item) {
            $persentase = $item->kapasitas > 0 ? round(($item->terisi / $item->kapasitas) * 100) : 0;
            echo "<tr>";
            echo "<td>" . $no++ . "</td>";
            echo "<td>" . $item->id_area . "</td>";
            echo "<td>" . $item->nama_area . "</td>";
            echo "<td>" . number_format($item->kapasitas) . "</td>";
            echo "<td>" . number_format($item->terisi) . "</td>";
            echo "<td>" . $persentase . "%</td>";
            echo "</tr>";
        }
        
        echo "</tbody>";
        echo "</table>";
        exit;
    }
    
    /**
     * Export area to PDF
     */
    public function exportPdf(Request $request)
    {
        $area = DB::table('tb_area_parkir')->orderBy('id_area')->get();
        
        $html = '
        <!DOCTYPE html>
        <html>
        <head>
            <title>Data Area Parkir - Sistem Parkir</title>
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
                <p>Laporan Data Area Parkir</p>
                <p>Tanggal: ' . date('d/m/Y H:i:s') . '</p>
            </div>
            <table>
                <thead>
                    <tr><th>No</th><th>ID</th><th>Nama Area</th><th>Kapasitas</th><th>Terisi</th><th>Persentase</th></tr>
                </thead>
                <tbody>';
        
        $no = 1;
        foreach ($area as $item) {
            $persentase = $item->kapasitas > 0 ? round(($item->terisi / $item->kapasitas) * 100) : 0;
            $html .= "<tr>
                        <td>{$no}</td>
                        <td>{$item->id_area}</td>
                        <td>{$item->nama_area}</td>
                        <td>" . number_format($item->kapasitas) . "</td>
                        <td>" . number_format($item->terisi) . "</td>
                        <td>{$persentase}%</td>
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