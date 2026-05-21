@extends('layouts.app')

@section('title', 'Detail Kendaraan')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fas fa-car me-2"></i> Detail Kendaraan</h2>
    <div class="d-flex gap-2">
        @if(!$sedangParkir)
            <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal">
                <i class="fas fa-trash me-2"></i> Hapus Kendaraan
            </button>
        @endif
        <a href="{{ route('kendaraan.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i> Kembali
        </a>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-md-4 text-center">
                <i class="fas fa-{{ $kendaraan->jenis_kendaraan == 'Motor' ? 'motorcycle' : ($kendaraan->jenis_kendaraan == 'Mobil' ? 'car' : 'truck') }} fa-5x text-primary mb-3"></i>
                <h3>{{ $kendaraan->plat_nomor }}</h3>
                <p class="text-muted">ID Kendaraan: #{{ $kendaraan->id_kendaraan }}</p>
                
                @if($sedangParkir)
                    <span class="badge bg-danger px-4 py-2">
                        <i class="fas fa-parking me-1"></i> Sedang Parkir
                    </span>
                @else
                    <span class="badge bg-success px-4 py-2">
                        <i class="fas fa-check-circle me-1"></i> Tersedia
                    </span>
                @endif
            </div>
            <div class="col-md-8">
                <table class="table table-bordered">
                    <tr>
                        <th width="30%">Jenis Kendaraan</th>
                        <td>{{ $kendaraan->jenis_kendaraan }}</td>
                    </tr>
                    <tr>
                        <th>Warna</th>
                        <td>{{ $kendaraan->warna ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Pemilik</th>
                        <td>{{ $kendaraan->pemilik ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Terdaftar</th>
                        <td>{{ $kendaraan->created_at ?? '-' }}</td>
                    </tr>
                    @if($transaksiTerakhir)
                    <tr>
                        <th>Riwayat Terakhir</th>
                        <td>
                            {{ $transaksiTerakhir->status }} pada 
                            {{ date('d/m/Y H:i:s', strtotime($transaksiTerakhir->waktu_masuk)) }}
                        </td>
                    </tr>
                    @endif
                </table>
            </div>
        </div>
    </div>
</div>

@if(!$sedangParkir)
<!-- Modal Hapus Kendaraan -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title"><i class="fas fa-trash me-2"></i> Hapus Kendaraan</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
                <i class="fas fa-exclamation-triangle fa-3x text-warning mb-3"></i>
                <p>Yakin ingin menghapus kendaraan <strong>{{ $kendaraan->plat_nomor }}</strong>?</p>
                <p class="text-danger">⚠️ Semua riwayat transaksi kendaraan ini juga akan ikut terhapus!</p>
                <p class="text-muted">Tindakan ini tidak dapat dibatalkan.</p>
            </div>
            <div class="modal-footer">
                <form action="{{ route('kendaraan.destroy', $kendaraan->id_kendaraan) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">Ya, Hapus Permanen</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endif
@endsection