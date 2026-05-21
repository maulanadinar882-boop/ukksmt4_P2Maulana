<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (isset($_SESSION['user'])) {
            return redirect('/dashboard');
        }
        return view('auth.login');
    }
    
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required'
        ]);
        
        // Debug: tampilkan input
        \Log::info('Login attempt', [
            'username' => $request->username,
            'password_raw' => $request->password
        ]);
        
        $user = DB::table('tb_user')
            ->where('username', $request->username)
            ->where('status_aktif', 1)
            ->first();
        
        // Debug: tampilkan user dari DB
        \Log::info('User from DB', [
            'found' => $user ? 'yes' : 'no',
            'username' => $user->username ?? null,
            'password_hash' => $user->password ?? null
        ]);
        
        if (!$user) {
            \Log::warning('User not found: ' . $request->username);
            return back()->with('error', 'Username tidak ditemukan!');
        }
        
        $hashedPassword = $this->encryptPassword($request->password);
        
        // Debug: bandingkan hash
        \Log::info('Password comparison', [
            'input_hash' => $hashedPassword,
            'db_hash' => $user->password,
            'match' => $hashedPassword === $user->password ? 'yes' : 'no'
        ]);
        
        if ($hashedPassword === $user->password) {
            $_SESSION['user'] = (array) $user;
            
            DB::table('tb_log_aktivitas')->insert([
                'id_user' => $user->id_user,
                'aktivitas' => 'Login ke sistem',
                'waktu_aktivitas' => now()
            ]);
            
            return redirect('/dashboard')->with('success', 'Selamat datang, ' . $user->nama_lengkap);
        }
        
        return back()->with('error', 'Password salah!');
    }
    
    public function logout()
    {
        if (isset($_SESSION['user'])) {
            DB::table('tb_log_aktivitas')->insert([
                'id_user' => $_SESSION['user']['id_user'],
                'aktivitas' => 'Logout dari sistem',
                'waktu_aktivitas' => now()
            ]);
        }
        session_destroy();
        return redirect('/login')->with('success', 'Anda telah logout');
    }
    
    private function encryptPassword($password)
    {
        return hash('sha256', $password);
    }
}