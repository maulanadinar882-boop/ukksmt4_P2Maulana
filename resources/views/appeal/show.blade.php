@extends('layouts.app')

@section('title', 'Detail Appeal')

@section('content')
<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="fas fa-gavel me-2"></i> Detail Appeal</h5>
            </div>
            <div class="card-body">
                <!-- Status -->
                <div class="mb-4 text-center">
                    @php
                        $statusClass = [
                            'pending' => 'warning',
                            'diproses' => 'info',
                            'selesai' => 'success',
                            'ditolak' => 'danger'
                        ][$appeal->status] ?? 'secondary';
                        
                        $statusText = [
                            'pending' => 'Pending - Menunggu Diproses',
                            'diproses' => 'Diproses - Sedang Ditangani',
                            'selesai' => 'Selesai',
                            'ditolak' => 'Ditolak'
                        ][$appeal->status] ?? ucfirst($appeal->status);
                    @endphp
                    <span class="badge bg-{{ $statusClass }} px-4 py-2" style="font-size: 14px;">
                        <i class="fas fa-{{ $appeal->status == 'pending' ? 'clock' : ($appeal->status == 'diproses' ? 'spinner' : ($appeal->status == 'selesai' ? 'check' : 'times')) }} me-2"></i>
                        {{ $statusText }}
                    </span>
                </div>
                
                <!-- Info Pengirim -->
                <div class="alert alert-secondary">
                    <div class="row">
                        <div class="col-md-6">
                            <small class="text-muted">Pengirim</small><br>
                            <strong>{{ $appeal->pengirim }}</strong>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted">Tanggal Diajukan</small><br>
                            <strong>{{ date('d F Y H:i:s', strtotime($appeal->created_at)) }}</strong>
                        </div>
                    </div>
                </div>
                
                <!-- Judul & Deskripsi -->
                <div class="mb-4">
                    <h4>{{ $appeal->judul }}</h4>
                    <hr>
                    <p class="lead">{{ $appeal->deskripsi }}</p>
                </div>
                
                <!-- Balasan (jika ada) -->
                @if($appeal->balasan)
                <div class="card border-{{ $appeal->status == 'ditolak' ? 'danger' : 'success' }} bg-light">
                    <div class="card-header bg-{{ $appeal->status == 'ditolak' ? 'danger' : 'success' }} text-white">
                        <h6 class="mb-0"><i class="fas fa-reply-all me-2"></i> Balasan Admin</h6>
                    </div>
                    <div class="card-body">
                        <p class="mb-2">{{ $appeal->balasan }}</p>
                        <hr>
                        <small class="text-muted">
                            Dibalas oleh: {{ $appeal->nama_pembalas ?? 'Admin' }} | 
                            {{ date('d F Y H:i:s', strtotime($appeal->updated_at)) }}
                        </small>
                    </div>
                </div>
                @endif
                
                <hr>
                
                <div class="d-flex justify-content-end">
                    <a href="{{ route('appeal.index') }}" class="btn btn-secondary btn-lg px-4">
                        <i class="fas fa-arrow-left me-2"></i> Kembali
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Informasi Status -->
        <div class="card mt-4">
            <div class="card-header bg-white">
                <h6 class="mb-0"><i class="fas fa-info-circle me-2"></i> Informasi Status</h6>
            </div>
            <div class="card-body">
                <ul class="mb-0">
                    <li><span class="badge bg-warning">Pending</span> - Appeal baru, menunggu diproses admin</li>
                    <li><span class="badge bg-info">Diproses</span> - Appeal sedang ditangani oleh admin</li>
                    <li><span class="badge bg-success">Selesai</span> - Appeal telah selesai diproses</li>
                    <li><span class="badge bg-danger">Ditolak</span> - Appeal tidak dapat diproses</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection