@extends('layouts.app')

@section('title', 'Kelola Tarif Parkir')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <h5 class="mb-0"><i class="fas fa-tags me-2"></i> Manajemen Tarif Parkir</h5>
                    <div class="d-flex gap-2">
                        
                        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#createTarifModal">
                            <i class="fas fa-plus me-2"></i> Tambah Jenis Kendaraan
                        </button>
                    </div>
                </div>
            </div>
            <div class="card-body">
                
                <!-- Statistik -->
                <div class="row mb-4">
                    <div class="col-md-12">
                        <div class="card bg-primary text-white">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-1">Total Jenis Kendaraan</h6>
                                        <h2 class="mb-0">{{ $totalTarif }}</h2>
                                    </div>
                                    <i class="fas fa-tags fa-3x opacity-50"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Tabel Daftar Tarif -->
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="table-light">
                            <tr>
                                <th width="5%">No</th>
                                <th width="30%">Jenis Kendaraan</th>
                                <th width="30%">Tarif per Jam</th>
                                <th width="20%">Status</th>
                                <th width="15%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($tarif as $index => $item)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-{{ $item->jenis_kendaraan == 'Motor' ? 'motorcycle' : ($item->jenis_kendaraan == 'Mobil' ? 'car' : 'truck') }} fa-2x me-3 text-primary"></i>
                                        <strong>{{ $item->jenis_kendaraan }}</strong>
                                    </div>
                                </td>
                                <td>
                                    <h5 class="mb-0 text-primary">
                                        Rp {{ number_format($item->tarif_per_jam) }}
                                    </h5>
                                    <small class="text-muted">/ per jam</small>
                                </td>
                                <td>
                                    <span class="badge bg-success px-3 py-2">
                                        <i class="fas fa-check-circle me-1"></i> Aktif
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editTarifModal{{ $item->id_tarif }}">
                                            <i class="fas fa-edit me-1"></i> Edit
                                        </button>
                                        <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteTarifModal{{ $item->id_tarif }}">
                                            <i class="fas fa-trash me-1"></i> Hapus
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center py-4">
                                    <i class="fas fa-tags fa-3x text-muted mb-2 d-block"></i>
                                    Belum ada data Tarif<br>
                                    <button class="btn btn-primary mt-3" data-bs-toggle="modal" data-bs-target="#createTarifModal">
                                        <i class="fas fa-plus me-2"></i> Tambah Jenis Kendaraan Pertama
                                    </button>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        
        

<style>
    .modal-dialog {
        max-width: 500px;
    }
</style>

<!-- ==================== MODAL CREATE TARIF (TAMBAH JENIS KENDARAAN BARU) ==================== -->
<div class="modal fade" id="createTarifModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title"><i class="fas fa-plus-circle me-2"></i> Tambah Jenis Kendaraan</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('tarif.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Jenis Kendaraan <span class="text-danger">*</span></label>
                        <input type="text" name="jenis_kendaraan" class="form-control form-control-lg" 
                               style="padding: 12px 15px;" 
                               placeholder="Contoh: Bus, Minibus, Pickup, Sedan" required>
                        <small class="text-muted">Masukkan jenis kendaraan baru yang ingin ditambahkan</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Tarif per Jam <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input type="number" name="tarif_per_jam" class="form-control form-control-lg" 
                                   style="padding: 12px 15px;" placeholder="0" required min="0" step="500">
                        </div>
                        <small class="text-muted">Tarif per jam untuk jenis kendaraan ini</small>
                    </div>
                    
                    <div class="alert alert-info mt-3">
                        <i class="fas fa-info-circle me-2"></i>
                        Setelah disimpan, jenis kendaraan ini akan otomatis muncul di halaman Transaksi Parkir.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save me-2"></i> Simpan Jenis Kendaraan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ==================== MODAL EDIT DAN DELETE UNTUK TARIF ==================== -->
@foreach($tarif as $item)
<!-- Modal Edit Tarif -->
<div class="modal fade" id="editTarifModal{{ $item->id_tarif }}" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <h5 class="modal-title"><i class="fas fa-edit me-2"></i> Edit Tarif {{ $item->jenis_kendaraan }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('tarif.update', $item->id_tarif) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="text-center mb-3">
                        <h4>{{ $item->jenis_kendaraan }}</h4>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Tarif per Jam <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input type="number" name="tarif_per_jam" class="form-control form-control-lg" 
                                   style="padding: 12px 15px;" value="{{ $item->tarif_per_jam }}" required min="0" step="500">
                        </div>
                    </div>
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        Perubahan tarif akan berlaku untuk transaksi baru.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning">Update Tarif</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Delete Tarif -->
<div class="modal fade" id="deleteTarifModal{{ $item->id_tarif }}" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title"><i class="fas fa-trash me-2"></i> Hapus Tarif</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
                <i class="fas fa-exclamation-triangle fa-3x text-warning mb-3"></i>
                <p>Yakin ingin menghapus tarif untuk <strong>{{ $item->jenis_kendaraan }}</strong>?</p>
                <p class="text-muted">Tarif: <strong>Rp {{ number_format($item->tarif_per_jam) }}</strong> / jam</p>
                <p class="text-danger small">⚠️ Tarif yang sudah digunakan pada transaksi TIDAK DAPAT dihapus!</p>
            </div>
            <div class="modal-footer">
                <form action="{{ route('tarif.destroy', $item->id_tarif) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">Ya, Hapus</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endforeach
@endsection