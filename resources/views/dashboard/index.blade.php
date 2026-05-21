@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<!-- Welcome Section -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card bg-gradient-primary text-white border-0">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center flex-wrap">
                    <div>
                        <h2 class="mb-1">Selamat Datang, {{ $_SESSION['user']['nama_lengkap'] ?? 'User' }}!</h2>
                        <p class="mb-0 opacity-75">Sistem Manajemen Parkir - {{ date('d F Y') }}</p>
                    </div>
                    <div class="text-center mt-3 mt-sm-0">
                        <i class="fas fa-parking fa-4x opacity-50"></i>
                        <div class="mt-2">
                            <span class="badge bg-light text-dark">
                                <i class="fas fa-clock me-1"></i> {{ date('H:i') }} WIB
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Statistik Utama -->
<div class="row mb-4">
    <div class="col-xl-3 col-md-6 mb-3">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            Total Kendaraan</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($totalKendaraan) }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-car fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6 mb-3">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                            Parkir Aktif</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($parkirAktif) }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-parking fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6 mb-3">
        <div class="card border-left-info shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                            Pendapatan Hari Ini</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">Rp {{ number_format($pendapatanHariIni) }}</div>
                        <small class="text-{{ $persentasePendapatan >= 0 ? 'success' : 'danger' }}">
                            <i class="fas fa-arrow-{{ $persentasePendapatan >= 0 ? 'up' : 'down' }}"></i>
                            {{ abs($persentasePendapatan) }}% dari kemarin
                        </small>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-money-bill-wave fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6 mb-3">
        <div class="card border-left-warning shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                            Kapasitas Area</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $persentaseKapasitas }}%</div>
                        <small>{{ number_format($totalTerisi) }} / {{ number_format($totalKapasitas) }} terisi</small>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-chart-pie fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Statistik Tambahan -->
<div class="row mb-4">
    <div class="col-md-4 mb-3">
        <div class="card shadow">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-1">Transaksi Masuk</h6>
                        <h3 class="mb-0">{{ number_format($transaksiMasukHariIni) }}</h3>
                        <small class="text-muted">Hari Ini</small>
                    </div>
                    <i class="fas fa-sign-in-alt fa-3x text-primary opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-3">
        <div class="card shadow">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-1">Transaksi Keluar</h6>
                        <h3 class="mb-0">{{ number_format($transaksiKeluarHariIni) }}</h3>
                        <small class="text-muted">Hari Ini</small>
                    </div>
                    <i class="fas fa-sign-out-alt fa-3x text-success opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-3">
        <div class="card shadow">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-1">Total Area</h6>
                        <h3 class="mb-0">{{ number_format($totalArea) }}</h3>
                        <small class="text-muted">Area Parkir</small>
                    </div>
                    <i class="fas fa-map-marker-alt fa-3x text-info opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
</div>

@if($role == 'Admin')
<!-- Admin Specific: User & Log -->
<div class="row mb-4">
    <div class="col-md-6 mb-3">
        <div class="card shadow">
            <div class="card-header bg-white">
                <h6 class="mb-0"><i class="fas fa-chart-pie me-2"></i> Distribusi User</h6>
            </div>
            <div class="card-body">
                <canvas id="userChart" height="250"></canvas>
            </div>
        </div>
    </div>
    <div class="col-md-6 mb-3">
        <div class="card shadow">
            <div class="card-header bg-white">
                <h6 class="mb-0"><i class="fas fa-history me-2"></i> Aktivitas Terbaru</h6>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-sm table-hover mb-0">
                        <thead class="table-light">
                            <tr><th>User</th><th>Aktivitas</th><th>Waktu</th></tr>
                        </thead>
                        <tbody>
                            @forelse($logTerbaru as $log)
                            <tr><td>{{ $log->nama_lengkap }}<br><small>({{ $log->role }})</small></td>
                                <td>{{ Str::limit($log->aktivitas, 40) }}</td>
                                <td>{{ \Carbon\Carbon::parse($log->waktu_aktivitas)->diffForHumans() }}</td></tr>
                            @empty<tr><td colspan="3" class="text-center">Belum ada aktivitas</td></tr>@endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

@if($role == 'Petugas')
<!-- Petugas Specific: Target & Shift -->
<div class="row mb-4">
    <div class="col-md-6 mb-3">
        <div class="card shadow">
            <div class="card-header bg-white">
                <h6 class="mb-0"><i class="fas fa-chart-line me-2"></i> Kinerja Hari Ini</h6>
            </div>
            <div class="card-body text-center">
                <div class="row">
                    <div class="col-6 border-end">
                        <small class="text-muted">Transaksi Anda</small>
                        <h2 class="text-primary">{{ $transaksiHariIniPetugas }}</h2>
                    </div>
                    <div class="col-6">
                        <small class="text-muted">Target</small>
                        <h2 class="text-success">{{ $targetHarian }}</h2>
                    </div>
                </div>
                <div class="progress mt-3" style="height: 10px;">
                    <div class="progress-bar bg-success" style="width: {{ ($transaksiHariIniPetugas / $targetHarian) * 100 }}%"></div>
                </div>
                <p class="mt-2 text-muted small">{{ round(($transaksiHariIniPetugas / $targetHarian) * 100) }}% Tercapai</p>
            </div>
        </div>
    </div>
    <div class="col-md-6 mb-3">
        <div class="card shadow">
            <div class="card-header bg-white">
                <h6 class="mb-0"><i class="fas fa-clock me-2"></i> Shift Hari Ini</h6>
            </div>
            <div class="card-body text-center">
                @if($shiftHariIni)
                    <i class="fas fa-check-circle fa-3x text-success mb-2"></i>
                    <h5>{{ date('d F Y') }}</h5>
                    <p class="mb-0">{{ date('H:i', strtotime($shiftHariIni->jam_masuk)) }} - {{ date('H:i', strtotime($shiftHariIni->jam_keluar)) }}</p>
                    <span class="badge bg-success mt-2">Aktif</span>
                @else
                    <i class="fas fa-clock fa-3x text-warning mb-2"></i>
                    <p>Belum ada shift hari ini</p>
                    <a href="{{ route('shift.my-shift') }}" class="btn btn-primary btn-sm">Lihat Jadwal</a>
                @endif
            </div>
        </div>
    </div>
</div>
@endif

@if($role == 'Owner')
<!-- Owner Specific: Bulanan -->
<div class="row mb-4">
    <div class="col-md-6 mb-3">
        <div class="card shadow">
            <div class="card-header bg-white">
                <h6 class="mb-0"><i class="fas fa-calendar-alt me-2"></i> Pendapatan Bulan Ini</h6>
            </div>
            <div class="card-body text-center">
                <h2 class="text-primary">Rp {{ number_format($pendapatanBulanIni) }}</h2>
                <small class="text-muted">Total pendapatan {{ date('F Y') }}</small>
                <hr>
                <div class="row">
                    <div class="col-6">
                        <small class="text-muted">Rata-rata/hari</small>
                        <h5>Rp {{ number_format($pendapatanBulanIni / date('t')) }}</h5>
                    </div>
                    <div class="col-6">
                        <small class="text-muted">Target bulanan</small>
                        <h5 class="text-success">Rp {{ number_format(50000000) }}</h5>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6 mb-3">
        <div class="card shadow">
            <div class="card-header bg-white">
                <h6 class="mb-0"><i class="fas fa-chart-line me-2"></i> Kinerja Tahunan</h6>
            </div>
            <div class="card-body text-center">
                <h2 class="text-success">Rp {{ number_format($pendapatanTahunIni) }}</h2>
                <small class="text-muted">Total pendapatan {{ date('Y') }}</small>
                <hr>
                <div class="row">
                    <div class="col-6">
                        <small class="text-muted">Rata-rata/bulan</small>
                        <h5>Rp {{ number_format($pendapatanTahunIni / 12) }}</h5>
                    </div>
                    <div class="col-6">
                        <small class="text-muted">Estimasi tahun depan</small>
                        <h5 class="text-info">Rp {{ number_format($pendapatanTahunIni * 1.1) }}</h5>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

<!-- Grafik Utama -->
<div class="row mb-4">
    <div class="col-md-8 mb-3">
        <div class="card shadow">
            <div class="card-header bg-white">
                <h6 class="mb-0"><i class="fas fa-chart-line me-2"></i> Grafik Pendapatan 7 Hari Terakhir</h6>
            </div>
            <div class="card-body">
                <canvas id="pendapatan7HariChart" height="280"></canvas>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-3">
        <div class="card shadow">
            <div class="card-header bg-white">
                <h6 class="mb-0"><i class="fas fa-chart-pie me-2"></i> Jenis Kendaraan</h6>
            </div>
            <div class="card-body">
                <canvas id="jenisKendaraanChart" height="280"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Grafik Tambahan -->
<div class="row mb-4">
    <div class="col-md-6 mb-3">
        <div class="card shadow">
            <div class="card-header bg-white">
                <h6 class="mb-0"><i class="fas fa-chart-line me-2"></i> Pendapatan 12 Bulan Terakhir</h6>
            </div>
            <div class="card-body">
                <canvas id="pendapatan12BulanChart" height="250"></canvas>
            </div>
        </div>
    </div>
    <div class="col-md-6 mb-3">
        <div class="card shadow">
            <div class="card-header bg-white">
                <h6 class="mb-0"><i class="fas fa-chart-bar me-2"></i> Transaksi per Jam (Hari Ini)</h6>
            </div>
            <div class="card-body">
                <canvas id="transaksiPerJamChart" height="250"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Kapasitas Area Parkir -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card shadow">
            <div class="card-header bg-white">
                <h6 class="mb-0"><i class="fas fa-map-marker-alt me-2"></i> Kapasitas Area Parkir</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    @foreach($areaData as $area)
                    @php
                        $persentase = $area->kapasitas > 0 ? round(($area->terisi / $area->kapasitas) * 100) : 0;
                        $color = $persentase >= 90 ? 'danger' : ($persentase >= 70 ? 'warning' : 'success');
                    @endphp
                    <div class="col-md-4 mb-3">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <h6 class="mb-0">{{ $area->nama_area }}</h6>
                                    <span class="badge bg-{{ $color }}">{{ $persentase }}%</span>
                                </div>
                                <div class="progress mb-2" style="height: 8px;">
                                    <div class="progress-bar bg-{{ $color }}" style="width: {{ $persentase }}%"></div>
                                </div>
                                <div class="d-flex justify-content-between small">
                                    <span>Terisi: {{ number_format($area->terisi) }}</span>
                                    <span>Kapasitas: {{ number_format($area->kapasitas) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Transaksi Terbaru -->
<div class="row">
    <div class="col-12">
        <div class="card shadow">
            <div class="card-header bg-white">
                <h6 class="mb-0"><i class="fas fa-history me-2"></i> Transaksi Terbaru</h6>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th>Plat Nomor</th>
                                <th>Jenis</th>
                                <th>Area</th>
                                <th>Waktu Masuk</th>
                                <th>Waktu Keluar</th>
                                <th>Biaya</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($transaksiTerbaru as $item)
                            <tr>
                                <td>{{ $item->id_parkir }}</small></td>
                                <td><strong>{{ $item->plat_nomor }}</strong></small></td>
                                <td>{{ $item->jenis_kendaraan }}</small></td>
                                <td>{{ $item->nama_area }}</small></td>
                                <td>{{ date('d/m/Y H:i', strtotime($item->waktu_masuk)) }}</small></td>
                                <td>{{ $item->waktu_keluar ? date('d/m/Y H:i', strtotime($item->waktu_keluar)) : '-' }}</small></td>
                                <td>{{ $item->biaya_total ? 'Rp ' . number_format($item->biaya_total) : '-' }}</small></td>
                                <td>
                                    <span class="badge bg-{{ $item->status == 'Masuk' ? 'warning' : 'success' }}">
                                        {{ $item->status }}
                                    </span>
                                </small></td>
                            </tr>
                            @empty
                            <tr><td colspan="8" class="text-center py-4">Belum ada transaksi</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .border-left-primary { border-left: 4px solid #4e73df; }
    .border-left-success { border-left: 4px solid #1cc88a; }
    .border-left-info { border-left: 4px solid #36b9cc; }
    .border-left-warning { border-left: 4px solid #f6c23e; }
    .bg-gradient-primary { background: linear-gradient(135deg, #4e73df 0%, #224abe 100%); }
</style>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Chart Pendapatan 7 Hari
    new Chart(document.getElementById('pendapatan7HariChart'), {
        type: 'line',
        data: {
            labels: @json($chart7HariLabels),
            datasets: [{
                label: 'Pendapatan (Rp)',
                data: @json($chart7HariData),
                borderColor: '#4e73df',
                backgroundColor: 'rgba(78, 115, 223, 0.05)',
                borderWidth: 2,
                pointBackgroundColor: '#4e73df',
                pointBorderColor: '#fff',
                pointRadius: 4,
                pointHoverRadius: 6,
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: { legend: { position: 'top' } },
            scales: { y: { beginAtZero: true, ticks: { callback: v => 'Rp ' + v.toLocaleString('id-ID') } } }
        }
    });
    
    // Chart Jenis Kendaraan
    new Chart(document.getElementById('jenisKendaraanChart'), {
        type: 'pie',
        data: {
            labels: @json($chartJenisLabels),
            datasets: [{
                data: @json($chartJenisData),
                backgroundColor: @json($chartJenisColors),
                borderWidth: 0
            }]
        },
        options: { responsive: true, maintainAspectRatio: true, plugins: { legend: { position: 'bottom' } } }
    });
    
    // Chart Pendapatan 12 Bulan
    new Chart(document.getElementById('pendapatan12BulanChart'), {
        type: 'bar',
        data: {
            labels: @json($chart12BulanLabels),
            datasets: [{
                label: 'Pendapatan (Rp)',
                data: @json($chart12BulanData),
                backgroundColor: '#4e73df',
                borderRadius: 5
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            scales: { y: { beginAtZero: true, ticks: { callback: v => 'Rp ' + v.toLocaleString('id-ID') } } }
        }
    });
    
    // Chart Transaksi per Jam
    new Chart(document.getElementById('transaksiPerJamChart'), {
        type: 'bar',
        data: {
            labels: @json($chartJamLabels),
            datasets: [{
                label: 'Jumlah Transaksi',
                data: @json($chartJamData),
                backgroundColor: '#36b9cc',
                borderRadius: 3
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } }
        }
    });
    
    @if($role == 'Admin')
    // Chart User Distribution
    new Chart(document.getElementById('userChart'), {
        type: 'bar',
        data: {
            labels: @json($userPerRole->pluck('role')),
            datasets: [{
                label: 'Jumlah User',
                data: @json($userPerRole->pluck('total')),
                backgroundColor: '#4e73df',
                borderRadius: 5
            }]
        },
        options: { responsive: true, maintainAspectRatio: true, scales: { y: { beginAtZero: true, stepSize: 1 } } }
    });
    @endif
</script>
@endpush