<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
{
    $admins = DB::table('tb_user')
        ->where('role', 'Admin')
        ->orderBy('id_user', 'desc')
        ->get();
    
    $petugas = DB::table('tb_user')
        ->where('role', 'Petugas')
        ->orderBy('id_user', 'desc')
        ->get();
    
    $owners = DB::table('tb_user')
        ->where('role', 'Owner')
        ->orderBy('id_user', 'desc')
        ->get();
    
    return view('user.index', compact('admins', 'petugas', 'owners'));
}

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('user.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_lengkap' => 'required|string|max:100',
            'username' => 'required|string|max:50|unique:tb_user,username',
            'password' => 'required|min:4',
            'role' => 'required|in:Admin,Petugas,Owner',
            'status_aktif' => 'sometimes|boolean'
        ]);

        DB::table('tb_user')->insert([
            'nama_lengkap' => $request->nama_lengkap,
            'username' => $request->username,
            'password' => $this->encryptPassword($request->password),
            'role' => $request->role,
            'status_aktif' => $request->status_aktif ?? 1
        ]);

        $this->logActivity('Menambah user baru: ' . $request->username);
        
        return redirect()->route('user.index')
            ->with('success', 'User berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $user = DB::table('tb_user')->where('id_user', $id)->first();
        
        if (!$user) {
            return redirect()->route('user.index')
                ->with('error', 'User tidak ditemukan!');
        }
        
        return view('user.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $user = DB::table('tb_user')->where('id_user', $id)->first();
        
        if (!$user) {
            return redirect()->route('user.index')
                ->with('error', 'User tidak ditemukan!');
        }
        
        return view('user.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_lengkap' => 'required|string|max:100',
            'role' => 'required|in:Admin,Petugas,Owner',
            'status_aktif' => 'sometimes|boolean',
            'password' => 'nullable|min:4'
        ]);

        $data = [
            'nama_lengkap' => $request->nama_lengkap,
            'role' => $request->role,
            'status_aktif' => $request->status_aktif ?? 1
        ];

        // Update password hanya jika diisi
        if ($request->filled('password')) {
            $data['password'] = $this->encryptPassword($request->password);
        }

        DB::table('tb_user')->where('id_user', $id)->update($data);

        $this->logActivity('Mengupdate user ID: ' . $id);
        
        return redirect()->route('user.index')
            ->with('success', 'User berhasil diupdate!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        // Cek apakah user ada
        $user = DB::table('tb_user')->where('id_user', $id)->first();
        
        if (!$user) {
            return redirect()->route('user.index')
                ->with('error', 'User tidak ditemukan!');
        }

        // Cek jangan sampai menghapus diri sendiri
        if (isset($_SESSION['user']) && $_SESSION['user']['id_user'] == $id) {
            return redirect()->route('user.index')
                ->with('error', 'Tidak dapat menghapus akun sendiri!');
        }

        // Hapus user
        DB::table('tb_user')->where('id_user', $id)->delete();

        $this->logActivity('Menghapus user: ' . $user->username);
        
        return redirect()->route('user.index')
            ->with('success', 'User berhasil dihapus!');
    }

    /**
     * Toggle status aktif/nonaktif user
     */
    public function toggleStatus($id)
    {
        $user = DB::table('tb_user')->where('id_user', $id)->first();
        
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'User tidak ditemukan!']);
        }

        $newStatus = $user->status_aktif == 1 ? 0 : 1;
        
        DB::table('tb_user')->where('id_user', $id)->update([
            'status_aktif' => $newStatus
        ]);

        $this->logActivity(($newStatus == 1 ? 'Mengaktifkan' : 'Menonaktifkan') . ' user: ' . $user->username);
        
        if (request()->ajax()) {
            return response()->json(['success' => true, 'status' => $newStatus]);
        }
        
        return redirect()->route('user.index')
            ->with('success', 'Status user berhasil diubah!');
    }

    /**
     * Encrypt password menggunakan SHA256
     */
    private function encryptPassword($password)
    {
        return hash('sha256', $password);
    }

    /**
     * Log aktivitas user
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

    /**
 * Export users to Excel based on role
 */
public function export(Request $request)
{
    $role = $request->get('role', 'semua');
    
    $query = DB::table('tb_user');
    
    if ($role != 'semua') {
        $query->where('role', $role);
    }
    
    $users = $query->orderBy('id_user', 'desc')->get();
    
    $filename = 'data_user_' . date('Y-m-d_H-i-s') . '.xls';
    
    header("Content-Type: application/vnd.ms-excel");
    header("Content-Disposition: attachment; filename=\"$filename\"");
    
    echo "<table border='1'>";
    echo "<thead>";
    echo "<tr style='background: #4e73df; color: white;'>
            <th>No</th>
            <th>ID User</th>
            <th>Nama Lengkap</th>
            <th>Username</th>
            <th>Role</th>
            <th>Status</th>
          </tr>";
    echo "</thead>";
    echo "<tbody>";
    
    $no = 1;
    foreach ($users as $user) {
        echo "<tr>";
        echo "<td>" . $no++ . "</td>";
        echo "<td>" . $user->id_user . "</td>";
        echo "<td>" . $user->nama_lengkap . "</td>";
        echo "<td>" . $user->username . "</td>";
        echo "<td>" . $user->role . "</td>";
        echo "<td>" . ($user->status_aktif ? 'Aktif' : 'Nonaktif') . "</td>";
        echo "</tr>";
    }
    
    echo "</tbody>";
    echo "</table>";
    exit;
}

/**
 * Export users to PDF
 */
public function exportPdf(Request $request)
{
    $role = $request->get('role', 'semua');
    
    $query = DB::table('tb_user');
    
    if ($role != 'semua') {
        $query->where('role', $role);
    }
    
    $users = $query->orderBy('id_user', 'desc')->get();
    $title = $role == 'semua' ? 'SEMUA USER' : 'USER ' . strtoupper($role);
    
    $html = '
    <!DOCTYPE html>
    <html>
    <head>
        <title>Data User - Sistem Parkir</title>
        <style>
            body { font-family: Arial, sans-serif; font-size: 12px; }
            .header { text-align: center; margin-bottom: 20px; }
            .header h2 { margin-bottom: 5px; }
            .header p { color: #666; margin-top: 0; }
            table { width: 100%; border-collapse: collapse; }
            th, td { border: 1px solid #000; padding: 8px; text-align: left; }
            th { background: #4e73df; color: white; }
            .footer { margin-top: 20px; text-align: center; font-size: 10px; color: #666; }
            @media print {
                .no-print { display: none; }
            }
        </style>
    </head>
    <body>
        <div class="no-print" style="text-align: center; margin-bottom: 20px;">
            <button onclick="window.print()">Cetak PDF</button>
            <button onclick="window.close()">Tutup</button>
        </div>
        <div class="header">
            <h2>SISTEM MANAJEMEN PARKIR</h2>
            <p>Laporan Data User - ' . $title . '</p>
            <p>Tanggal: ' . date('d/m/Y H:i:s') . '</p>
        </div>
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>ID</th>
                    <th>Nama Lengkap</th>
                    <th>Username</th>
                    <th>Role</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>';
    
    $no = 1;
    foreach ($users as $user) {
        $html .= '<tr>
                    <td>' . $no++ . '</td>
                    <td>' . $user->id_user . '</td>
                    <td>' . $user->nama_lengkap . '</td>
                    <td>' . $user->username . '</td>
                    <td>' . $user->role . '</td>
                    <td>' . ($user->status_aktif ? 'Aktif' : 'Nonaktif') . '</td>
                  </tr>';
    }
    
    $html .= '
            </tbody>
        </table>
        <div class="footer">
            <p>Dicetak oleh: ' . ($_SESSION['user']['nama_lengkap'] ?? 'Admin') . ' | ' . date('d/m/Y H:i:s') . '</p>
        </div>
    </body>
    </html>';
    
    echo $html;
    exit;
}
}