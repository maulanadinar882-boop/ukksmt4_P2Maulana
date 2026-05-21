@extends('layouts.app')

@section('title', 'Appeal Saya')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <h5 class="mb-0"><i class="fas fa-gavel me-2"></i> Appeal Saya</h5>
                    <a href="{{ route('appeal.create') }}" class="btn btn-success">
                        <i class="fas fa-plus me-2"></i> Ajukan Appeal
                    </a>
                </div>
            </div>
            <div class="card-body">
                <!-- Statistik -->
                <div class="row mb-4">
                    <div class="col-md-2 col-6 mb-2">
                        <div class="card bg-primary text-white">
                            <div class="card-body text-center py-2">
                                <h4 class="mb-0">{{ $totalAppeal }}</h4>
                                <small>Total</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2 col-6 mb-2">
                        <div class="card bg-warning text-white">
                            <div class="card-body text-center py-2">
                                <h4 class="mb-0">{{ $pendingAppeal }}</h4>
                                <small>Pending</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2 col-6 mb-2">
                        <div class="card bg-info text-white">
                            <div class="card-body text-center py-2">
                                <h4 class="mb-0">{{ $diprosesAppeal }}</h4>
                                <small>Diproses</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2 col-6 mb-2">
                        <div class="card bg-success text-white">
                            <div class="card-body text-center py-2">
                                <h4 class="mb-0">{{ $selesaiAppeal }}</h4>
                                <small>Selesai</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2 col-6 mb-2">
                        <div class="card bg-danger text-white">
                            <div class="card-body text-center py-2">
                                <h4 class="mb-0">{{ $ditolakAppeal }}</h4>
                                <small>Ditolak</small>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Tabel Appeal -->
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="table-light">
                            <tr>
                                <th width="5%">No</th>
                                <th width="20%">Judul</th>
                                <th width="40%">Deskripsi</th>
                                <th width="15%">Status</th>
                                <th width="10%">Tanggal</th>
                                <th width="10%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($appeals as $index => $appeal)
                            @php
                                $statusClass = [
                                    'pending' => 'warning',
                                    'diproses' => 'info',
                                    'selesai' => 'success',
                                    'ditolak' => 'danger'
                                ][$appeal->status] ?? 'secondary';
                                
                                $statusText = [
                                    'pending' => 'Pending',
                                    'diproses' => 'Diproses',
                                    'selesai' => 'Selesai',
                                    'ditolak' => 'Ditolak'
                                ][$appeal->status] ?? ucfirst($appeal->status);
                            @endphp
                            <tr>
                                <td>{{ $index + 1 }}</small></td>
                                <td><strong>{{ $appeal->judul }}</strong></small></td>
                                <td>{{ Str::limit($appeal->deskripsi, 80) }}</small></td>
                                <td>
                                    <span class="badge bg-{{ $statusClass }} px-3 py-2">
                                        {{ $statusText }}
                                    </span>
                                 </small></td>
                                <td>{{ date('d/m/Y', strtotime($appeal->created_at)) }}</small></td>
                                <td>
                                    <a href="{{ route('appeal.show', $appeal->id_appeal) }}" class="btn btn-info btn-sm">
                                        <i class="fas fa-eye me-1"></i> Detail
                                    </a>
                                 </small></td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-4">
                                    <i class="fas fa-gavel fa-3x text-muted mb-2 d-block"></i>
                                    Belum ada appeal yang diajukan
                                    <div class="mt-3">
                                        <a href="{{ route('appeal.create') }}" class="btn btn-success">
                                            <i class="fas fa-plus me-2"></i> Ajukan Appeal Sekarang
                                        </a>
                                    </div>
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
@endsection