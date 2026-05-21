@extends('layouts.app')

@section('title', 'Manajemen Appeal')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-gavel me-2"></i> Manajemen Appeal Owner</h5>
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
                                <th>No</th>
                                <th>Pengirim</th>
                                <th>Judul</th>
                                <th>Deskripsi</th>
                                <th>Status</th>
                                <th>Tanggal</th>
                                <th>Aksi</th>
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
                            @endphp
                            <tr>
                                <td>{{ $index + 1 }}</small></td>
                                <td><strong>{{ $appeal->pengirim }}</strong></small></td>
                                <td>{{ $appeal->judul }}</small></td>
                                <td>{{ Str::limit($appeal->deskripsi, 60) }}</small></td>
                                <td><span class="badge bg-{{ $statusClass }}">{{ $appeal->status }}</span></small></td>
                                <td>{{ date('d/m/Y', strtotime($appeal->created_at)) }}</small></td>
                                <td>
                                    <a href="{{ route('admin.appeal.respond', $appeal->id_appeal) }}" class="btn btn-primary btn-sm">
                                        <i class="fas fa-reply me-1"></i> Proses
                                    </a>
                                 </small></td>
                            </tr>
                            @empty
                            <tr><td colspan="7" class="text-center">Belum ada appeal</small></td>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection