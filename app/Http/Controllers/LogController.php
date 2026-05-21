<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
{
    $query = DB::table('tb_log_aktivitas')
        ->join('tb_user', 'tb_log_aktivitas.id_user', '=', 'tb_user.id_user')
        ->select('tb_log_aktivitas.*', 'tb_user.nama_lengkap', 'tb_user.role');
    
    // Filter user
    if ($request->has('user_id') && $request->user_id != '') {
        $query->where('tb_log_aktivitas.id_user', $request->user_id);
    }
    
    // Filter tanggal
    if ($request->has('start_date') && $request->start_date != '') {
        $query->whereDate('tb_log_aktivitas.waktu_aktivitas', '>=', $request->start_date);
    }
    
    if ($request->has('end_date') && $request->end_date != '') {
        $query->whereDate('tb_log_aktivitas.waktu_aktivitas', '<=', $request->end_date);
    }
    
    // Filter aktivitas
    if ($request->has('aktivitas') && $request->aktivitas != '') {
        $query->where('tb_log_aktivitas.aktivitas', 'like', '%' . $request->aktivitas . '%');
    }
    
    $perPage = $request->get('per_page', 20);
    $logs = $query->orderBy('tb_log_aktivitas.waktu_aktivitas', 'desc')
        ->paginate($perPage)
        ->appends($request->query());
    
    // Get users for filter
    $users = DB::table('tb_user')->select('id_user', 'nama_lengkap', 'role')->get();
    
    // Statistik
    $totalLogs = DB::table('tb_log_aktivitas')->count();
    $totalLogsHariIni = DB::table('tb_log_aktivitas')->whereDate('waktu_aktivitas', date('Y-m-d'))->count();
    
    return view('log.index', compact('logs', 'users', 'totalLogs', 'totalLogsHariIni'));
}
    
    /**
     * Export logs to Excel
     */
    public function exportExcel(Request $request)
    {
        $query = DB::table('tb_log_aktivitas')
            ->join('tb_user', 'tb_log_aktivitas.id_user', '=', 'tb_user.id_user')
            ->select('tb_log_aktivitas.*', 'tb_user.nama_lengkap', 'tb_user.role');
        
        if ($request->has('user_id') && $request->user_id != '') {
            $query->where('tb_log_aktivitas.id_user', $request->user_id);
        }
        
        if ($request->has('start_date') && $request->start_date != '') {
            $query->whereDate('tb_log_aktivitas.waktu_aktivitas', '>=', $request->start_date);
        }
        
        if ($request->has('end_date') && $request->end_date != '') {
            $query->whereDate('tb_log_aktivitas.waktu_aktivitas', '<=', $request->end_date);
        }
        
        $logs = $query->orderBy('tb_log_aktivitas.waktu_aktivitas', 'desc')->get();
        
        $filename = 'log_aktivitas_' . date('Y-m-d_H-i-s') . '.xls';
        
        header("Content-Type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=\"$filename\"");
        
        echo "<table border='1'>";
        echo "<tr>
                <th>No</th>
                <th>Tanggal & Waktu</th>
                <th>User</th>
                <th>Role</th>
                <th>Aktivitas</th>
              </tr>";
        
        $no = 1;
        foreach ($logs as $log) {
            echo "<tr>";
            echo "<td>" . $no++ . "</td>";
            echo "<td>" . date('d/m/Y H:i:s', strtotime($log->waktu_aktivitas)) . "</td>";
            echo "<td>" . $log->nama_lengkap . "</td>";
            echo "<td>" . $log->role . "</td>";
            echo "<td>" . $log->aktivitas . "</td>";
            echo "</tr>";
        }
        
        echo "</table>";
        exit;
    }
}