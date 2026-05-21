@extends('layouts.app')

@section('title', 'Transaksi Parkir')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-receipt me-2"></i> Transaksi Parkir</h5>
            </div>
            <div class="card-body">
                <!-- Statistik Ringkasan -->
                <div class="row mb-4">
                    <div class="col-md-3 mb-3">
                        <div class="card bg-primary text-white">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-1">Masuk Hari Ini</h6>
                                        <h2 class="mb-0">{{ number_format($statistikHariIni['total_masuk']) }}</h2>
                                    </div>
                                    <i class="fas fa-sign-in-alt fa-3x opacity-50"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="card bg-success text-white">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-1">Keluar Hari Ini</h6>
                                        <h2 class="mb-0">{{ number_format($statistikHariIni['total_keluar']) }}</h2>
                                    </div>
                                    <i class="fas fa-sign-out-alt fa-3x opacity-50"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="card bg-warning text-white">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-1">Pendapatan Hari Ini</h6>
                                        <h3 class="mb-0">Rp {{ number_format($statistikHariIni['pendapatan']) }}</h3>
                                    </div>
                                    <i class="fas fa-money-bill-wave fa-3x opacity-50"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="card bg-danger text-white">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-1">Sedang Parkir</h6>
                                        <h2 class="mb-0">{{ number_format($statistikHariIni['aktif']) }}</h2>
                                    </div>
                                    <i class="fas fa-parking fa-3x opacity-50"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Form Kendaraan Masuk -->
                <div class="row mb-4">
                    <div class="col-md-5 mb-3">
                        <div class="card border-primary">
                            <div class="card-header bg-primary text-white">
                                <h6 class="mb-0"><i class="fas fa-sign-in-alt me-2"></i> Kendaraan Masuk</h6>
                            </div>
                            <div class="card-body">
                                <form action="{{ route('transaksi.masuk') }}" method="POST">
                                    @csrf
                                    <div class="mb-3">
                                        <label class="form-label">Plat Nomor <span class="text-danger">*</span></label>
                                        <input type="text" name="plat_nomor" class="form-control form-control-lg" 
                                               placeholder="Contoh: B 1234 ABC" required autofocus>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Jenis Kendaraan <span class="text-danger">*</span></label>
                                        <select name="jenis_kendaraan" class="form-select form-select-lg" required>
                                            <option value="">Pilih Jenis Kendaraan</option>
                                            @foreach($jenisKendaraan as $jk)
                                                <option value="{{ $jk->jenis_kendaraan }}">
                                                    {{ $jk->jenis_kendaraan }} (Rp {{ number_format($jk->tarif_per_jam) }}/jam)
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Warna</label>
                                        <input type="text" name="warna" class="form-control" placeholder="Contoh: Hitam, Putih, Merah">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Pemilik</label>
                                        <input type="text" name="pemilik" class="form-control" placeholder="Nama pemilik kendaraan">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Area Parkir <span class="text-danger">*</span></label>
                                        <select name="id_area" class="form-select form-select-lg" required>
                                            <option value="">Pilih Area</option>
                                            @foreach($areas as $area)
                                                <option value="{{ $area->id_area }}" 
                                                    {{ $area->terisi >= $area->kapasitas ? 'disabled' : '' }}>
                                                    {{ $area->nama_area }} 
                                                    ({{ $area->terisi }}/{{ $area->kapasitas }})
                                                    {{ $area->terisi >= $area->kapasitas ? ' - PENUH' : '' }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <button type="submit" class="btn btn-primary btn-lg w-100">
                                        <i class="fas fa-save me-2"></i> Proses Masuk
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-7 mb-3">
                        <div class="card border-success">
                            <div class="card-header bg-success text-white">
                                <h6 class="mb-0"><i class="fas fa-sign-out-alt me-2"></i> Kendaraan Keluar</h6>
                            </div>
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-hover mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Plat Nomor</th>
                                                <th>Jenis</th>
                                                <th>Area</th>
                                                <th>Waktu Masuk</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($transaksiAktif as $item)
                                            <tr>
                                                <td><strong>{{ $item->plat_nomor }}</strong></td>
                                                <td>
                                                    <span class="badge bg-{{ $item->jenis_kendaraan == 'Motor' ? 'info' : ($item->jenis_kendaraan == 'Mobil' ? 'success' : 'warning') }}">
                                                        {{ $item->jenis_kendaraan }}
                                                    </span>
                                                </td>
                                                <td>{{ $item->nama_area }}</small></td>
                                                <td>{{ date('H:i:s', strtotime($item->waktu_masuk)) }}<br>
                                                    <small>{{ date('d/m/Y', strtotime($item->waktu_masuk)) }}</small>
                                                </td>
                                                <td>
                                                    <form action="{{ route('transaksi.keluar') }}" method="POST">
                                                        @csrf
                                                        <input type="hidden" name="id_parkir" value="{{ $item->id_parkir }}">
                                                        <button type="submit" class="btn btn-danger btn-sm">
                                                            <i class="fas fa-sign-out-alt me-1"></i> Keluar
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="5" class="text-center py-4">
                                                        <i class="fas fa-parking fa-3x text-muted mb-2 d-block"></i>
                                                        Tidak ada kendaraan yang parkir
                                                    </small></td>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Riwayat Transaksi Hari Ini -->
                <div class="card">
                    <div class="card-header bg-white">
                        <h6 class="mb-0"><i class="fas fa-history me-2"></i> Riwayat Transaksi Hari Ini</h6>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>No</th>
                                        <th>Plat Nomor</th>
                                        <th>Jenis</th>
                                        <th>Area</th>
                                        <th>Waktu Masuk</th>
                                        <th>Waktu Keluar</th>
                                        <th>Durasi</th>
                                        <th>Biaya</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($transaksiHariIni as $index => $item)
                                    <tr>
                                        <td>{{ $index + 1 }}</small></td>
                                        <td><strong>{{ $item->plat_nomor }}</strong></small></td>
                                        <td>
                                            <span class="badge bg-{{ $item->jenis_kendaraan == 'Motor' ? 'info' : ($item->jenis_kendaraan == 'Mobil' ? 'success' : 'warning') }}">
                                                {{ $item->jenis_kendaraan }}
                                            </span>
                                        </small></td>
                                        <td>{{ $item->nama_area }}</small></td>
                                        <td>{{ date('H:i:s', strtotime($item->waktu_masuk)) }}</small></td>
                                        <td>
                                            @if($item->waktu_keluar)
                                                {{ date('H:i:s', strtotime($item->waktu_keluar)) }}
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </small></td>
                                        <td>
                                            @if($item->durasi_jam)
                                                {{ $item->durasi_jam }} jam
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </small></td>
                                        <td>
                                            @if($item->biaya_total)
                                                Rp {{ number_format($item->biaya_total) }}
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </small></td>
                                        <td>
                                            @if($item->status == 'Masuk')
                                                <span class="badge bg-warning">Masuk</span>
                                            @else
                                                <span class="badge bg-success">Keluar</span>
                                            @endif
                                        </small></td>
                                    </tr>
                                    @empty
                                        <tr>
                                            <td colspan="9" class="text-center py-4">
                                                <i class="fas fa-receipt fa-3x text-muted mb-2 d-block"></i>
                                                Belum ada transaksi hari ini
                                            </small></td>
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

<style>
    .table td {
        vertical-align: middle;
    }
</style>
@endsection