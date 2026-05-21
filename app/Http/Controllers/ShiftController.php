<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ShiftController extends Controller
{
    /**
     * Display a listing of shifts (Admin)
     */
    public function index()
    {
        // Get all shifts
        $shifts = DB::table('shift')
            ->orderBy('jam_masuk', 'asc')
            ->get();
        
        // Get shift assignments for today
        $shiftAssignments = DB::table('user_shift')
            ->join('tb_user', 'user_shift.id_user', '=', 'tb_user.id_user')
            ->join('shift', 'user_shift.id_shift', '=', 'shift.id_shift')
            ->whereDate('user_shift.tanggal', date('Y-m-d'))
            ->select('user_shift.*', 'tb_user.nama_lengkap', 'tb_user.role', 'shift.jam_masuk', 'shift.jam_keluar')
            ->get();
        
        // Get all petugas for assignment
        $petugas = DB::table('tb_user')
            ->where('role', 'Petugas')
            ->where('status_aktif', 1)
            ->select('id_user', 'nama_lengkap')
            ->get();
        
        return view('shift.index', compact('shifts', 'shiftAssignments', 'petugas'));
    }
    
    /**
     * Store a new shift
     */
    public function store(Request $request)
    {
        $request->validate([
            'jam_masuk' => 'required|date_format:H:i',
            'jam_keluar' => 'required|date_format:H:i|after:jam_masuk'
        ]);
        
        DB::table('shift')->insert([
            'jam_masuk' => $request->jam_masuk,
            'jam_keluar' => $request->jam_keluar
        ]);
        
        $this->logActivity('Menambah shift baru: ' . $request->jam_masuk . ' - ' . $request->jam_keluar);
        
        return redirect()->route('shift.index')
            ->with('success', 'Shift berhasil ditambahkan!');
    }
    
    /**
     * Update shift
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'jam_masuk' => 'required|date_format:H:i',
            'jam_keluar' => 'required|date_format:H:i|after:jam_masuk'
        ]);
        
        $shiftLama = DB::table('shift')->where('id_shift', $id)->first();
        
        DB::table('shift')->where('id_shift', $id)->update([
            'jam_masuk' => $request->jam_masuk,
            'jam_keluar' => $request->jam_keluar
        ]);
        
        $this->logActivity('Mengupdate shift: ' . $shiftLama->jam_masuk . ' - ' . $shiftLama->jam_keluar . ' -> ' . $request->jam_masuk . ' - ' . $request->jam_keluar);
        
        return redirect()->route('shift.index')
            ->with('success', 'Shift berhasil diupdate!');
    }
    
    /**
     * Delete shift
     */
    public function destroy($id)
    {
        // Check if shift has assignments
        $hasAssignments = DB::table('user_shift')
            ->where('id_shift', $id)
            ->exists();
        
        if ($hasAssignments) {
            return redirect()->route('shift.index')
                ->with('error', 'Shift sudah digunakan! Hapus assignment terlebih dahulu.');
        }
        
        $shift = DB::table('shift')->where('id_shift', $id)->first();
        DB::table('shift')->where('id_shift', $id)->delete();
        
        $this->logActivity('Menghapus shift: ' . $shift->jam_masuk . ' - ' . $shift->jam_keluar);
        
        return redirect()->route('shift.index')
            ->with('success', 'Shift berhasil dihapus!');
    }
    
    /**
     * Assign shift to petugas
     */
    public function assignShift(Request $request)
    {
        $request->validate([
            'id_user' => 'required|exists:tb_user,id_user',
            'id_shift' => 'required|exists:shift,id_shift',
            'tanggal' => 'required|date'
        ]);
        
        // Check if already assigned
        $existing = DB::table('user_shift')
            ->where('id_user', $request->id_user)
            ->where('tanggal', $request->tanggal)
            ->first();
        
        if ($existing) {
            return redirect()->route('shift.index')
                ->with('error', 'Petugas sudah mendapat shift pada tanggal tersebut!');
        }
        
        DB::table('user_shift')->insert([
            'id_user' => $request->id_user,
            'id_shift' => $request->id_shift,
            'tanggal' => $request->tanggal
        ]);
        
        $user = DB::table('tb_user')->where('id_user', $request->id_user)->first();
        $shift = DB::table('shift')->where('id_shift', $request->id_shift)->first();
        
        $this->logActivity('Assign shift ke ' . $user->nama_lengkap . ' pada ' . $request->tanggal . ' (' . $shift->jam_masuk . ' - ' . $shift->jam_keluar . ')');
        
        return redirect()->route('shift.index')
            ->with('success', 'Shift berhasil diassign!');
    }
    
    /**
     * Remove shift assignment
     */
    public function unassignShift($id)
    {
        $assignment = DB::table('user_shift')->where('id_user_shift', $id)->first();
        
        if (!$assignment) {
            return redirect()->route('shift.index')
                ->with('error', 'Assignment tidak ditemukan!');
        }
        
        $user = DB::table('tb_user')->where('id_user', $assignment->id_user)->first();
        
        DB::table('user_shift')->where('id_user_shift', $id)->delete();
        
        $this->logActivity('Menghapus assignment shift untuk ' . ($user->nama_lengkap ?? 'Petugas'));
        
        return redirect()->route('shift.index')
            ->with('success', 'Assignment shift berhasil dihapus!');
    }
    
    /**
 * My Shift (for Petugas)
 */
public function myShift(Request $request)
{
    if (!isset($_SESSION['user'])) {
        return redirect('/login');
    }
    
    $userId = $_SESSION['user']['id_user'];
    $bulan = $request->get('bulan', date('m'));
    $tahun = $request->get('tahun', date('Y'));
    
    // Get shifts for this month
    $shifts = DB::table('user_shift')
        ->join('shift', 'user_shift.id_shift', '=', 'shift.id_shift')
        ->where('user_shift.id_user', $userId)
        ->whereYear('user_shift.tanggal', $tahun)
        ->whereMonth('user_shift.tanggal', $bulan)
        ->orderBy('user_shift.tanggal', 'asc')
        ->get();
    
    // Get today's shift
    $shiftHariIni = DB::table('user_shift')
        ->join('shift', 'user_shift.id_shift', '=', 'shift.id_shift')
        ->where('user_shift.id_user', $userId)
        ->whereDate('user_shift.tanggal', date('Y-m-d'))
        ->first();
    
    // Statistics
    $totalShiftBulanIni = $shifts->count();
    $completedShifts = DB::table('user_shift')
        ->join('shift', 'user_shift.id_shift', '=', 'shift.id_shift')
        ->where('user_shift.id_user', $userId)
        ->whereYear('user_shift.tanggal', $tahun)
        ->whereMonth('user_shift.tanggal', $bulan)
        ->whereDate('user_shift.tanggal', '<', date('Y-m-d'))
        ->count();
    
    $upcomingShifts = DB::table('user_shift')
        ->join('shift', 'user_shift.id_shift', '=', 'shift.id_shift')
        ->where('user_shift.id_user', $userId)
        ->whereYear('user_shift.tanggal', $tahun)
        ->whereMonth('user_shift.tanggal', $bulan)
        ->whereDate('user_shift.tanggal', '>=', date('Y-m-d'))
        ->count();
    
    return view('shift.my-shift', compact(
        'shifts', 'shiftHariIni', 'totalShiftBulanIni', 
        'completedShifts', 'upcomingShifts', 'bulan', 'tahun'
    ));
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