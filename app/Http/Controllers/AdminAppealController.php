<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminAppealController extends Controller
{
    /**
     * Display all appeals (for Admin)
     */
    public function index()
    {
        $appeals = DB::table('tb_appeal')
            ->join('tb_user', 'tb_appeal.id_user', '=', 'tb_user.id_user')
            ->leftJoin('tb_user as pembalas', 'tb_appeal.dibalas_oleh', '=', 'pembalas.id_user')
            ->select(
                'tb_appeal.*',
                'tb_user.nama_lengkap as pengirim',
                'pembalas.nama_lengkap as nama_pembalas'
            )
            ->orderBy('tb_appeal.created_at', 'desc')
            ->get();
        
        // Statistik
        $totalAppeal = $appeals->count();
        $pendingAppeal = $appeals->where('status', 'pending')->count();
        $diprosesAppeal = $appeals->where('status', 'diproses')->count();
        $selesaiAppeal = $appeals->where('status', 'selesai')->count();
        $ditolakAppeal = $appeals->where('status', 'ditolak')->count();
        
        return view('admin.appeal.index', compact(
            'appeals', 'totalAppeal', 'pendingAppeal', 
            'diprosesAppeal', 'selesaiAppeal', 'ditolakAppeal'
        ));
    }
    
    /**
     * Show form to respond to appeal
     */
    public function respond($id)
    {
        $appeal = DB::table('tb_appeal')
            ->join('tb_user', 'tb_appeal.id_user', '=', 'tb_user.id_user')
            ->where('tb_appeal.id_appeal', $id)
            ->select('tb_appeal.*', 'tb_user.nama_lengkap as pengirim')
            ->first();
        
        if (!$appeal) {
            return redirect()->route('admin.appeal.index')
                ->with('error', 'Appeal tidak ditemukan!');
        }
        
        return view('admin.appeal.respond', compact('appeal'));
    }
    
    /**
     * Process appeal response
     */
    public function process(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,diproses,selesai,ditolak',
            'balasan' => 'nullable|string'
        ]);
        
        $userId = $_SESSION['user']['id_user'] ?? null;
        
        DB::table('tb_appeal')->where('id_appeal', $id)->update([
            'status' => $request->status,
            'balasan' => $request->balasan,
            'dibalas_oleh' => $userId,
            'updated_at' => now()
        ]);
        
        $this->logActivity('Memproses appeal ID: ' . $id . ' - Status: ' . $request->status);
        
        return redirect()->route('admin.appeal.index')
            ->with('success', 'Appeal berhasil diproses!');
    }
    
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