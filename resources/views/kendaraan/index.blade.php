@extends('layouts.app')

@section('title', 'Kelola Kendaraan')

@section('content')

                <!-- Statistik Ringkasan -->
                <div class="row mb-4">
                    <div class="col-md-3 mb-3">
                        <div class="card bg-primary text-white">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-1">Total Kendaraan</h6>
                                        <h2 class="mb-0">{{ number_format($totalKendaraan) }}</h2>
                                    </div>
                                    <i class="fas fa-car fa-3x opacity-50"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="card bg-info text-white">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-1">Motor</h6>
                                        <h2 class="mb-0">{{ number_format($totalMotor) }}</h2>
                                    </div>
                                    <i class="fas fa-motorcycle fa-3x opacity-50"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="card bg-success text-white">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-1">Mobil</h6>
                                        <h2 class="mb-0">{{ number_format($totalMobil) }}</h2>
                                    </div>
                                    <i class="fas fa-car-side fa-3x opacity-50"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="card bg-warning text-white">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-1">Truk</h6>
                                        <h2 class="mb-0">{{ number_format($totalTruk) }}</h2>
                                    </div>
                                    <i class="fas fa-truck fa-3x opacity-50"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Form Pencarian -->
                <div class="row mb-3">
                    <div class="col-md-6 mx-auto">
                        <div class="input-group">
                            <input type="text" id="searchPlat" class="form-control form-control-lg" 
                                   style="padding: 12px 16px;" placeholder="Cari plat nomor...">
                            <button class="btn btn-secondary btn-lg" type="button" id="btnSearch" style="padding: 12px 24px;">
                                <i class="fas fa-search me-2"></i> Cari
                            </button>
                            <button class="btn btn-outline-secondary btn-lg" type="button" id="btnReset" style="padding: 12px 24px;">
                                <i class="fas fa-sync-alt me-2"></i> Reset
                            </button>
                        </div>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered table-hover" id="kendaraanTable">
                        <thead class="table-light">
                            <tr>
                                <th width="5%">No</th>
                                <th width="15%">Plat Nomor</th>
                                <th width="12%">Jenis</th>
                                <th width="12%">Warna</th>
                                <th width="20%">Pemilik</th>
                                <th width="15%">Status</th>
                                <th width="15%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($kendaraan as $index => $item)
                            @php
                                $sedangParkir = DB::table('tb_transaksi')
                                    ->where('id_kendaraan', $item->id_kendaraan)
                                    ->where('status', 'Masuk')
                                    ->exists();
                            @endphp
                            <tr>
                                <td>{{ $index + 1 }} </small></td>
                                <td><strong>{{ $item->plat_nomor }}</strong></small></td>
                                <td>
                                    <span class="badge bg-{{ $item->jenis_kendaraan == 'Motor' ? 'info' : ($item->jenis_kendaraan == 'Mobil' ? 'success' : 'warning') }} px-3 py-2">
                                        <i class="fas fa-{{ $item->jenis_kendaraan == 'Motor' ? 'motorcycle' : ($item->jenis_kendaraan == 'Mobil' ? 'car' : 'truck') }} me-1"></i>
                                        {{ $item->jenis_kendaraan }}
                                    </span>
                                </small></td>
                                <td>{{ $item->warna ?? '-' }}</small></td>
                                <td>{{ $item->pemilik ?? '-' }}</small></td>
                                <td>
                                    @if($sedangParkir)
                                        <span class="badge bg-danger px-3 py-2">
                                            <i class="fas fa-parking me-1"></i> Sedang Parkir
                                        </span>
                                    @else
                                        <span class="badge bg-success px-3 py-2">
                                            <i class="fas fa-check-circle me-1"></i> Tersedia
                                        </span>
                                    @endif
                                </small></td>
                                <td>
                                    <div class="d-flex gap-2 flex-wrap">
                                        <button class="btn btn-info" style="padding: 8px 16px; font-size: 13px;" data-bs-toggle="modal" data-bs-target="#viewKendaraanModal{{ $item->id_kendaraan }}">
                                            <i class="fas fa-eye me-1"></i> Detail
                                        </button>
                                        <button class="btn btn-warning" style="padding: 8px 16px; font-size: 13px;" data-bs-toggle="modal" data-bs-target="#editKendaraanModal{{ $item->id_kendaraan }}">
                                            <i class="fas fa-edit me-1"></i> Edit
                                        </button>
                                        @if(!$sedangParkir)
                                            <button class="btn btn-danger" style="padding: 8px 16px; font-size: 13px;" data-bs-toggle="modal" data-bs-target="#deleteKendaraanModal{{ $item->id_kendaraan }}">
                                                <i class="fas fa-trash me-1"></i> Hapus
                                            </button>
                                        @endif
                                    </div>
                                </small></td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center py-4">
                                    <i class="fas fa-car fa-3x text-muted mb-2 d-block"></i>
                                    Belum ada data Kendaraan
                                </small></td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .modal-lg {
        max-width: 700px;
    }
</style>

<!-- ==================== MODAL CREATE KENDARAAN ==================== -->
<div class="modal fade" id="createKendaraanModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title"><i class="fas fa-plus-circle me-2"></i> Tambah Kendaraan</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('kendaraan.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Plat Nomor <span class="text-danger">*</span></label>
                            <input type="text" name="plat_nomor" class="form-control form-control-lg" 
                                   style="padding: 12px 15px; text-transform: uppercase;" 
                                   placeholder="Contoh: B 1234 ABC" required>
                            <small class="text-muted">Plat nomor harus unik</small>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Jenis Kendaraan <span class="text-danger">*</span></label>
                            <select name="jenis_kendaraan" class="form-select form-select-lg" style="padding: 12px 15px;" required>
                                <option value="">Pilih Jenis</option>
                                <option value="Motor">Motor</option>
                                <option value="Mobil">Mobil</option>
                                <option value="Truk">Truk</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Warna</label>
                            <input type="text" name="warna" class="form-control form-control-lg" 
                                   style="padding: 12px 15px;" placeholder="Contoh: Hitam, Putih, Merah">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Pemilik</label>
                            <input type="text" name="pemilik" class="form-control form-control-lg" 
                                   style="padding: 12px 15px;" placeholder="Nama pemilik kendaraan">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-lg px-4" data-bs-dismiss="modal" style="padding: 10px 24px;">Batal</button>
                    <button type="submit" class="btn btn-success btn-lg px-4" style="padding: 10px 24px;">
                        <i class="fas fa-save me-2"></i> Simpan Kendaraan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ==================== MODAL VIEW, EDIT, DELETE UNTUK KENDARAAN ==================== -->
@foreach($kendaraan as $item)
@php
    $sedangParkir = DB::table('tb_transaksi')
        ->where('id_kendaraan', $item->id_kendaraan)
        ->where('status', 'Masuk')
        ->exists();
    
    $transaksiTerakhir = DB::table('tb_transaksi')
        ->where('id_kendaraan', $item->id_kendaraan)
        ->orderBy('waktu_masuk', 'desc')
        ->first();
@endphp

<!-- Modal View Kendaraan -->
<div class="modal fade" id="viewKendaraanModal{{ $item->id_kendaraan }}" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title"><i class="fas fa-car me-2"></i> Detail Kendaraan</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12 text-center mb-4">
                        <i class="fas fa-{{ $item->jenis_kendaraan == 'Motor' ? 'motorcycle' : ($item->jenis_kendaraan == 'Mobil' ? 'car' : 'truck') }} fa-5x text-primary"></i>
                        <h3 class="mt-2">{{ $item->plat_nomor }}</h3>
                        <p class="text-muted">ID Kendaraan: #{{ $item->id_kendaraan }}</p>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr><th width="40%">Jenis Kendaraan</th><td>: {{ $item->jenis_kendaraan }}</small></tr>
                            <tr><th>Warna</th><td>: {{ $item->warna ?? '-' }}</small></tr>
                            <tr><th>Pemilik</th><td>: {{ $item->pemilik ?? '-' }}</small></tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr><th width="40%">Status</th><td>: 
                                @if($sedangParkir)
                                    <span class="badge bg-danger">Sedang Parkir</span>
                                @else
                                    <span class="badge bg-success">Tersedia</span>
                                @endif
                             </small></tr>
                            <tr><th>Terdaftar</th><td>: {{ $item->created_at ?? '-' }}</small></tr>
                        </table>
                    </div>
                    @if($transaksiTerakhir)
                    <div class="col-md-12 mt-3">
                        <div class="alert alert-secondary">
                            <strong><i class="fas fa-history me-2"></i> Riwayat Terakhir:</strong><br>
                            {{ $transaksiTerakhir->status }} pada {{ date('d/m/Y H:i:s', strtotime($transaksiTerakhir->waktu_masuk)) }}
                        </div>
                    </div>
                    @endif
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-lg px-4" data-bs-dismiss="modal" style="padding: 10px 24px;">Tutup</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Edit Kendaraan -->
<div class="modal fade" id="editKendaraanModal{{ $item->id_kendaraan }}" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <h5 class="modal-title"><i class="fas fa-edit me-2"></i> Edit Kendaraan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('kendaraan.update', $item->id_kendaraan) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Plat Nomor <span class="text-danger">*</span></label>
                            <input type="text" name="plat_nomor" class="form-control form-control-lg" 
                                   style="padding: 12px 15px; text-transform: uppercase;" 
                                   value="{{ $item->plat_nomor }}" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Jenis Kendaraan <span class="text-danger">*</span></label>
                            <select name="jenis_kendaraan" class="form-select form-select-lg" style="padding: 12px 15px;" required>
                                <option value="Motor" {{ $item->jenis_kendaraan == 'Motor' ? 'selected' : '' }}>Motor</option>
                                <option value="Mobil" {{ $item->jenis_kendaraan == 'Mobil' ? 'selected' : '' }}>Mobil</option>
                                <option value="Truk" {{ $item->jenis_kendaraan == 'Truk' ? 'selected' : '' }}>Truk</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Warna</label>
                            <input type="text" name="warna" class="form-control form-control-lg" 
                                   style="padding: 12px 15px;" value="{{ $item->warna }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Pemilik</label>
                            <input type="text" name="pemilik" class="form-control form-control-lg" 
                                   style="padding: 12px 15px;" value="{{ $item->pemilik }}">
                        </div>
                    </div>
                    @if($sedangParkir)
                    <div class="alert alert-warning mt-2">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Kendaraan sedang parkir. Edit data dapat dilakukan, namun plat nomor tidak akan berubah pada transaksi aktif.
                    </div>
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-lg px-4" data-bs-dismiss="modal" style="padding: 10px 24px;">Batal</button>
                    <button type="submit" class="btn btn-warning btn-lg px-4" style="padding: 10px 24px;">
                        <i class="fas fa-save me-2"></i> Update Kendaraan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Delete Kendaraan -->
<div class="modal fade" id="deleteKendaraanModal{{ $item->id_kendaraan }}" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title"><i class="fas fa-trash me-2"></i> Hapus Kendaraan</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
                <i class="fas fa-exclamation-triangle fa-3x text-warning mb-3"></i>
                <p>Yakin ingin menghapus kendaraan <strong>{{ $item->plat_nomor }}</strong>?</p>
                <p class="text-danger">⚠️ Semua riwayat transaksi kendaraan ini juga akan ikut terhapus!</p>
                @if($sedangParkir)
                    <p class="text-danger">Kendaraan sedang parkir! Keluarkan kendaraan terlebih dahulu sebelum menghapus.</p>
                @endif
            </div>
            <div class="modal-footer">
                @if($sedangParkir)
                    <button type="button" class="btn btn-secondary btn-lg px-4" data-bs-dismiss="modal" style="padding: 10px 24px;">Tutup</button>
                @else
                    <form action="{{ route('kendaraan.destroy', $item->id_kendaraan) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="button" class="btn btn-secondary btn-lg px-4" data-bs-dismiss="modal" style="padding: 10px 24px;">Batal</button>
                        <button type="submit" class="btn btn-danger btn-lg px-4" style="padding: 10px 24px;">Ya, Hapus</button>
                    </form>
                @endif
            </div>
        </div>
    </div>
</div>
@endforeach

<script>
    // Fungsi pencarian
    document.getElementById('btnSearch').addEventListener('click', function() {
        var searchText = document.getElementById('searchPlat').value.toLowerCase();
        var table = document.getElementById('kendaraanTable');
        var rows = table.getElementsByTagName('tr');
        
        for (var i = 1; i < rows.length; i++) {
            var platCell = rows[i].getElementsByTagName('td')[1];
            if (platCell) {
                var platText = platCell.textContent || platCell.innerText;
                if (platText.toLowerCase().indexOf(searchText) > -1) {
                    rows[i].style.display = '';
                } else {
                    rows[i].style.display = 'none';
                }
            }
        }
    });
    
    document.getElementById('btnReset').addEventListener('click', function() {
        document.getElementById('searchPlat').value = '';
        var table = document.getElementById('kendaraanTable');
        var rows = table.getElementsByTagName('tr');
        for (var i = 1; i < rows.length; i++) {
            rows[i].style.display = '';
        }
    });
    
    document.getElementById('searchPlat').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            document.getElementById('btnSearch').click();
        }
    });
</script>
@endsection