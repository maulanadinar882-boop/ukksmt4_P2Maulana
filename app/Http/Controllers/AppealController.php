<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AppealController extends Controller
{
    /**
     * Display a listing of appeals (for Owner)
     */
    public function index()
    {
        $userId = $_SESSION['user']['id_user'] ?? null;
        
        $appeals = DB::table('tb_appeal')
            ->join('tb_user', 'tb_appeal.id_user', '=', 'tb_user.id_user')
            ->leftJoin('tb_user as pembalas', 'tb_appeal.dibalas_oleh', '=', 'pembalas.id_user')
            ->where('tb_appeal.id_user', $userId)
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
        
        return view('appeal.index', compact(
            'appeals', 'totalAppeal', 'pendingAppeal', 
            'diprosesAppeal', 'selesaiAppeal', 'ditolakAppeal'
        ));
    }
    
    /**
     * Show the form for creating a new appeal.
     */
    public function create()
    {
        return view('appeal.create');
    }
    
    /**
     * Store a newly created appeal.
     */
    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:100',
            'deskripsi' => 'required|string'
        ]);
        
        $userId = $_SESSION['user']['id_user'] ?? null;
        
        DB::table('tb_appeal')->insert([
            'id_user' => $userId,
            'judul' => $request->judul,
            'deskripsi' => $request->deskripsi,
            'status' => 'pending',
            'created_at' => now()
        ]);
        
        // Log activity
        $this->logActivity('Mengajukan appeal baru: ' . $request->judul);
        
        return redirect()->route('appeal.index')
            ->with('success', 'Appeal berhasil diajukan!');
    }
    
    /**
     * Display the specified appeal.
     */
    public function show($id)
    {
        $userId = $_SESSION['user']['id_user'] ?? null;
        
        $appeal = DB::table('tb_appeal')
            ->join('tb_user', 'tb_appeal.id_user', '=', 'tb_user.id_user')
            ->leftJoin('tb_user as pembalas', 'tb_appeal.dibalas_oleh', '=', 'pembalas.id_user')
            ->where('tb_appeal.id_appeal', $id)
            ->where('tb_appeal.id_user', $userId)
            ->select(
                'tb_appeal.*',
                'tb_user.nama_lengkap as pengirim',
                'pembalas.nama_lengkap as nama_pembalas'
            )
            ->first();
        
        if (!$appeal) {
            return redirect()->route('appeal.index')
                ->with('error', 'Appeal tidak ditemukan!');
        }
        
        return view('appeal.show', compact('appeal'));
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