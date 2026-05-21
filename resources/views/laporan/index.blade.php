@extends('layouts.app')

@section('title', 'Laporan Parkir')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-chart-line me-2"></i> Laporan Parkir</h5>
            </div>
            <div class="card-body">
                <!-- Form Filter -->
                <form method="GET" action="{{ route('laporan.index') }}" class="mb-4">
                    <div class="row">
                        <div class="col-md-3 mb-2">
                            <label class="form-label">Dari Tanggal</label>
                            <input type="date" name="start_date" class="form-control" value="{{ $startDate }}">
                        </div>
                        <div class="col-md-3 mb-2">
                            <label class="form-label">Sampai Tanggal</label>
                            <input type="date" name="end_date" class="form-control" value="{{ $endDate }}">
                        </div>
                        <div class="col-md-3 mb-2">
                            <label class="form-label">Jenis Kendaraan</label>
                            <select name="jenis" class="form-select">
                                <option value="semua" {{ $jenis == 'semua' ? 'selected' : '' }}>Semua</option>
                                <option value="Motor" {{ $jenis == 'Motor' ? 'selected' : '' }}>Motor</option>
                                <option value="Mobil" {{ $jenis == 'Mobil' ? 'selected' : '' }}>Mobil</option>
                                <option value="Truk" {{ $jenis == 'Truk' ? 'selected' : '' }}>Truk</option>
                            </select>
                        </div>
                        <div class="col-md-3 mb-2 d-flex align-items-end">
                            <div class="d-flex gap-2 w-100">
                                <button type="submit" class="btn btn-primary flex-grow-1">
                                    <i class="fas fa-search me-2"></i> Filter
                                </button>
                                <a href="{{ route('laporan.excel', request()->all()) }}" class="btn btn-success">
                                    <i class="fas fa-file-excel me-1"></i> Excel
                                </a>
                                <a href="{{ route('laporan.pdf', request()->all()) }}" class="btn btn-danger">
                                    <i class="fas fa-file-pdf me-1"></i> PDF
                                </a>
                            </div>
                        </div>
                    </div>
                </form>
                
                <!-- Ringkasan Statistik -->
                <div class="row mb-4">
                    <div class="col-md-4 mb-3">
                        <div class="card bg-primary text-white">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-1">Total Transaksi</h6>
                                        <h2 class="mb-0">{{ number_format($totalTransaksi) }}</h2>
                                    </div>
                                    <i class="fas fa-receipt fa-3x opacity-50"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="card bg-success text-white">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-1">Total Pendapatan</h6>
                                        <h3 class="mb-0">Rp {{ number_format($totalPendapatan) }}</h3>
                                    </div>
                                    <i class="fas fa-money-bill-wave fa-3x opacity-50"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="card bg-info text-white">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-1">Total Durasi</h6>
                                        <h2 class="mb-0">{{ number_format($totalDurasi) }} Jam</h2>
                                    </div>
                                    <i class="fas fa-clock fa-3x opacity-50"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Statistik per Jenis Kendaraan -->
                <div class="row mb-4">
                    <div class="col-md-6 mb-3">
                        <div class="card h-100">
                            <div class="card-header bg-white">
                                <h6 class="mb-0"><i class="fas fa-chart-bar me-2"></i> Statistik per Jenis Kendaraan</h6>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Jenis</th>
                                                <th>Jumlah</th>
                                                <th>Pendapatan</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($statistikJenis as $item)
                                            <tr>
                                                <td>{{ $item->jenis_kendaraan }}</td>
                                                <td>{{ number_format($item->total) }}</td>
                                                <td>Rp {{ number_format($item->pendapatan) }}</td>
                                            </tr>
                                            @endforeach
                                            @if(count($statistikJenis) == 0)
                                            <tr><td colspan="3" class="text-center">Belum ada data</td></tr>
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="card h-100">
                            <div class="card-header bg-white">
                                <h6 class="mb-0"><i class="fas fa-map-marker-alt me-2"></i> Statistik per Area</h6>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Area</th>
                                                <th>Jumlah</th>
                                                <th>Pendapatan</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($statistikArea as $item)
                                            <tr>
                                                <td>{{ $item->nama_area }}</td>
                                                <td>{{ number_format($item->total) }}</td>
                                                <td>Rp {{ number_format($item->pendapatan) }}</td>
                                            </tr>
                                            @endforeach
                                            @if(count($statistikArea) == 0)
                                            <tr><td colspan="3" class="text-center">Belum ada data</td></tr>
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Tabel Detail Transaksi -->
                <div class="card">
                    <div class="card-header bg-white">
                        <h6 class="mb-0"><i class="fas fa-table me-2"></i> Detail Transaksi</h6>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>No</th>
                                        <th>Tanggal Masuk</th>
                                        <th>Tanggal Keluar</th>
                                        <th>Plat Nomor</th>
                                        <th>Jenis</th>
                                        <th>Area</th>
                                        <th>Durasi</th>
                                        <th>Biaya</th>
                                        <th>Petugas</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($transaksi as $index => $item)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ date('d/m/Y H:i', strtotime($item->waktu_masuk)) }}</td>
                                        <td>{{ $item->waktu_keluar ? date('d/m/Y H:i', strtotime($item->waktu_keluar)) : '-' }}</td>
                                        <td><strong>{{ $item->plat_nomor }}</strong></td>
                                        <td>{{ $item->jenis_kendaraan }}</td>
                                        <td>{{ $item->nama_area }}</td>
                                        <td>{{ $item->durasi_jam ?? '-' }} jam</td>
                                        <td>Rp {{ number_format($item->biaya_total ?? 0) }}</td>
                                        <td>{{ $item->nama_lengkap }}</td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="9" class="text-center py-4">
                                            <i class="fas fa-chart-line fa-3x text-muted mb-2 d-block"></i>
                                            Tidak ada data transaksi pada periode ini
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection