@extends('layouts.app')

@section('title', 'Kelola Area Parkir')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-map-marker-alt me-2"></i> Manajemen Area Parkir</h5>
                    <button type="button" class="btn btn-success btn-lg" data-bs-toggle="modal" data-bs-target="#createAreaModal" style="padding: 10px 24px; font-size: 14px;">
                        <i class="fas fa-plus me-2"></i> Tambah Area
                    </button>
                </div>
            </div>
            <div class="card-body">
                <!-- Statistik Ringkasan -->
                <div class="row mb-4">
                    <div class="col-md-4 mb-3">
                        <div class="card bg-primary text-white">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-1">Total Area</h6>
                                        <h2 class="mb-0">{{ $areas->count() }}</h2>
                                    </div>
                                    <i class="fas fa-map-marked-alt fa-3x opacity-50"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="card bg-success text-white">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-1">Total Kapasitas</h6>
                                        <h2 class="mb-0">{{ number_format($totalKapasitas) }}</h2>
                                    </div>
                                    <i class="fas fa-parking fa-3x opacity-50"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="card bg-warning text-white">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-1">Terisi Saat Ini</h6>
                                        <h2 class="mb-0">{{ number_format($totalTerisi) }} / {{ number_format($totalKapasitas) }}</h2>
                                        <small>Persentase: {{ $persentaseTerisi }}%</small>
                                    </div>
                                    <i class="fas fa-chart-line fa-3x opacity-50"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="table-light">
                            <tr>
                                <th width="5%">No</th>
                                <th width="25%">Nama Area</th>
                                <th width="15%">Kapasitas</th>
                                <th width="20%">Terisi</th>
                                <th width="25%">Status</th>
                                <th width="20%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($areas as $index => $area)
                            @php
                                $persentase = $area->kapasitas > 0 ? round(($area->terisi / $area->kapasitas) * 100) : 0;
                                $statusColor = $persentase >= 90 ? 'danger' : ($persentase >= 70 ? 'warning' : 'success');
                                $statusText = $persentase >= 90 ? 'Penuh' : ($persentase >= 70 ? 'Hampir Penuh' : 'Tersedia');
                            @endphp
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="me-3">
                                            <i class="fas fa-{{ $area->id_area % 2 == 0 ? 'parking' : 'car' }} fa-2x text-primary"></i>
                                        </div>
                                        <div>
                                            <strong>{{ $area->nama_area }}</strong>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ number_format($area->kapasitas) }} <small>kendaraan</small></td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <span class="fw-bold me-2">{{ number_format($area->terisi) }}</span>
                                        <span class="text-muted">/ {{ number_format($area->kapasitas) }}</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex flex-column">
                                        <div class="progress mb-1" style="height: 8px;">
                                            <div class="progress-bar bg-{{ $statusColor }}" role="progressbar" 
                                                 style="width: {{ $persentase }}%" 
                                                 aria-valuenow="{{ $persentase }}" aria-valuemin="0" aria-valuemax="100">
                                            </div>
                                        </div>
                                        <span class="badge bg-{{ $statusColor }} d-inline-block" style="width: fit-content;">
                                            {{ $statusText }} ({{ $persentase }}%)
                                        </span>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex gap-2 flex-wrap">
                                        <button class="btn btn-info" style="padding: 8px 16px; font-size: 13px;" data-bs-toggle="modal" data-bs-target="#viewAreaModal{{ $area->id_area }}">
                                            <i class="fas fa-eye me-1"></i> Detail
                                        </button>
                                        <button class="btn btn-warning" style="padding: 8px 16px; font-size: 13px;" data-bs-toggle="modal" data-bs-target="#editAreaModal{{ $area->id_area }}">
                                            <i class="fas fa-edit me-1"></i> Edit
                                        </button>
                                        @if($area->terisi > 0)
                                        <button class="btn btn-secondary" style="padding: 8px 16px; font-size: 13px;" data-bs-toggle="modal" data-bs-target="#resetAreaModal{{ $area->id_area }}">
                                            <i class="fas fa-sync-alt me-1"></i> Reset
                                        </button>
                                        @endif
                                        <button class="btn btn-danger" style="padding: 8px 16px; font-size: 13px;" data-bs-toggle="modal" data-bs-target="#deleteAreaModal{{ $area->id_area }}">
                                            <i class="fas fa-trash me-1"></i> Hapus
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-4">
                                    <i class="fas fa-map-marked-alt fa-3x text-muted mb-2 d-block"></i>
                                    Belum ada data Area Parkir
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

<style>
    .progress {
        border-radius: 10px;
        background-color: #e9ecef;
    }
    .modal-lg {
        max-width: 700px;
    }
</style>

<!-- ==================== MODAL CREATE AREA ==================== -->
<div class="modal fade" id="createAreaModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title"><i class="fas fa-plus-circle me-2"></i> Tambah Area Parkir</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('area.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Nama Area <span class="text-danger">*</span></label>
                            <input type="text" name="nama_area" class="form-control form-control-lg" 
                                   style="padding: 12px 15px;" 
                                   placeholder="Contoh: Area A (Motor), Area B (Mobil)" required>
                            <small class="text-muted">Nama area harus unik dan mudah diingat</small>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Kapasitas <span class="text-danger">*</span></label>
                            <input type="number" name="kapasitas" class="form-control form-control-lg" 
                                   style="padding: 12px 15px;" 
                                   placeholder="Jumlah maksimal kendaraan" value="50" required min="1">
                            <small class="text-muted">Jumlah maksimal kendaraan yang bisa parkir di area ini</small>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-lg px-4" data-bs-dismiss="modal" style="padding: 10px 24px;">Batal</button>
                    <button type="submit" class="btn btn-success btn-lg px-4" style="padding: 10px 24px;">
                        <i class="fas fa-save me-2"></i> Simpan Area
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ==================== MODAL VIEW, EDIT, DELETE, RESET UNTUK AREA ==================== -->
@foreach($areas as $area)
@php
    $persentase = $area->kapasitas > 0 ? round(($area->terisi / $area->kapasitas) * 100) : 0;
    $statusColor = $persentase >= 90 ? 'danger' : ($persentase >= 70 ? 'warning' : 'success');
    $statusText = $persentase >= 90 ? 'Penuh' : ($persentase >= 70 ? 'Hampir Penuh' : 'Tersedia');
@endphp

<!-- Modal View Area -->
<div class="modal fade" id="viewAreaModal{{ $area->id_area }}" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title"><i class="fas fa-map-marker-alt me-2"></i> Detail Area Parkir</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12 text-center mb-4">
                        <i class="fas fa-{{ $area->id_area % 2 == 0 ? 'parking' : 'car' }} fa-4x text-primary"></i>
                        <h3 class="mt-2">{{ $area->nama_area }}</h3>
                        <p class="text-muted">ID Area: #{{ $area->id_area }}</p>
                    </div>
                    <div class="col-md-6">
                        <div class="border rounded p-3 text-center">
                            <h6 class="text-muted mb-1">Kapasitas</h6>
                            <h2 class="mb-0">{{ number_format($area->kapasitas) }}</h2>
                            <small>Slot Parkir</small>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="border rounded p-3 text-center">
                            <h6 class="text-muted mb-1">Terisi</h6>
                            <h2 class="mb-0">{{ number_format($area->terisi) }}</h2>
                            <small>Kendaraan</small>
                        </div>
                    </div>
                    <div class="col-md-12 mt-4">
                        <label class="form-label">Persentase Keterisian</label>
                        <div class="progress" style="height: 30px;">
                            <div class="progress-bar bg-{{ $statusColor }}" role="progressbar" 
                                 style="width: {{ $persentase }}%; font-weight: bold; font-size: 14px; line-height: 30px;">
                                {{ $persentase }}%
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 mt-3">
                        <div class="alert alert-{{ $statusColor }} mb-0">
                            <i class="fas fa-{{ $persentase >= 90 ? 'exclamation-triangle' : 'info-circle' }} me-2"></i>
                            @if($persentase >= 90)
                                <strong>Perhatian!</strong> Area parkir hampir penuh!
                            @elseif($persentase >= 70)
                                <strong>Informasi:</strong> Tersisa {{ number_format($area->kapasitas - $area->terisi) }} slot parkir.
                            @else
                                <strong>Informasi:</strong> Masih banyak slot parkir tersedia ({{ number_format($area->kapasitas - $area->terisi) }} slot).
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-lg px-4" data-bs-dismiss="modal" style="padding: 10px 24px;">Tutup</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Edit Area -->
<div class="modal fade" id="editAreaModal{{ $area->id_area }}" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <h5 class="modal-title"><i class="fas fa-edit me-2"></i> Edit Area Parkir</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('area.update', $area->id_area) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Nama Area <span class="text-danger">*</span></label>
                            <input type="text" name="nama_area" class="form-control form-control-lg" 
                                   style="padding: 12px 15px;" value="{{ $area->nama_area }}" required>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Kapasitas <span class="text-danger">*</span></label>
                            <input type="number" name="kapasitas" class="form-control form-control-lg" 
                                   style="padding: 12px 15px;" value="{{ $area->kapasitas }}" required min="1">
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Keterisian Saat Ini</label>
                            <div class="form-control-plaintext">
                                <span class="badge bg-info px-3 py-2" style="font-size: 14px;">
                                    <i class="fas fa-car me-1"></i> {{ number_format($area->terisi) }} / {{ number_format($area->kapasitas) }} Kendaraan
                                </span>
                            </div>
                            <small class="text-muted">Keterisian tidak dapat diubah dari sini. Akan otomatis terupdate saat transaksi parkir.</small>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-lg px-4" data-bs-dismiss="modal" style="padding: 10px 24px;">Batal</button>
                    <button type="submit" class="btn btn-warning btn-lg px-4" style="padding: 10px 24px;">
                        <i class="fas fa-save me-2"></i> Update Area
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Reset Area -->
@if($area->terisi > 0)
<div class="modal fade" id="resetAreaModal{{ $area->id_area }}" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-secondary text-white">
                <h5 class="modal-title"><i class="fas fa-sync-alt me-2"></i> Reset Keterisian Area</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
                <i class="fas fa-exclamation-triangle fa-3x text-warning mb-3"></i>
                <p>Yakin ingin mereset keterisian area <strong>{{ $area->nama_area }}</strong>?</p>
                <p class="text-danger small">Area ini saat ini terisi <strong>{{ $area->terisi }}</strong> kendaraan.<br>Reset akan mengosongkan semua data keterisian area!</p>
            </div>
            <div class="modal-footer">
                <form action="{{ route('area.reset', $area->id_area) }}" method="POST">
                    @csrf
                    <button type="button" class="btn btn-secondary btn-lg px-4" data-bs-dismiss="modal" style="padding: 10px 24px;">Batal</button>
                    <button type="submit" class="btn btn-secondary btn-lg px-4" style="padding: 10px 24px;">Ya, Reset</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endif

<!-- Modal Delete Area -->
<div class="modal fade" id="deleteAreaModal{{ $area->id_area }}" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title"><i class="fas fa-trash me-2"></i> Hapus Area Parkir</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
                <i class="fas fa-exclamation-triangle fa-3x text-warning mb-3"></i>
                <p>Yakin ingin menghapus area <strong>{{ $area->nama_area }}</strong>?</p>
                @if($area->terisi > 0)
                    <p class="text-danger">⚠️ Area ini masih memiliki {{ $area->terisi }} kendaraan yang parkir!<br>Kosongkan area terlebih dahulu sebelum menghapus.</p>
                @else
                    <p class="text-muted">Data yang terkait dengan area ini akan terhapus.</p>
                @endif
            </div>
            <div class="modal-footer">
                @if($area->terisi > 0)
                    <button type="button" class="btn btn-secondary btn-lg px-4" data-bs-dismiss="modal" style="padding: 10px 24px;">Tutup</button>
                @else
                    <form action="{{ route('area.destroy', $area->id_area) }}" method="POST">
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

<!-- JavaScript untuk tab hash -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Auto close modal after submit
        @if(session('success'))
            var myModal = bootstrap.Modal.getInstance(document.getElementById('createAreaModal'));
            if (myModal) myModal.hide();
        @endif
    });
</script>
@endsection