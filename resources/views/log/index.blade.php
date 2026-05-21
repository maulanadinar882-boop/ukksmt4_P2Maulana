@extends('layouts.app')

@section('title', 'Log Aktivitas')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-history me-2"></i> Log Aktivitas</h5>
                    <a href="{{ route('log.export', request()->query()) }}" class="btn btn-success">
                        <i class="fas fa-file-excel me-2"></i> Export Excel
                    </a>
                </div>
            </div>
            <div class="card-body">
                <!-- Statistik -->
                <div class="row mb-4">
                    <div class="col-md-6 mb-3">
                        <div class="card bg-primary text-white">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-1">Total Log</h6>
                                        <h2 class="mb-0">{{ number_format($totalLogs) }}</h2>
                                    </div>
                                    <i class="fas fa-database fa-3x opacity-50"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="card bg-success text-white">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-1">Log Hari Ini</h6>
                                        <h2 class="mb-0">{{ number_format($totalLogsHariIni) }}</h2>
                                    </div>
                                    <i class="fas fa-calendar-day fa-3x opacity-50"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Form Filter -->
                <form method="GET" action="{{ route('log.index') }}" class="mb-4">
                    <div class="row">
                        <div class="col-md-3 mb-2">
                            <label class="form-label">User</label>
                            <select name="user_id" class="form-select">
                                <option value="">Semua User</option>
                                @foreach($users as $user)
                                <option value="{{ $user->id_user }}" {{ request('user_id') == $user->id_user ? 'selected' : '' }}>
                                    {{ $user->nama_lengkap }} ({{ $user->role }})
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2 mb-2">
                            <label class="form-label">Dari Tanggal</label>
                            <input type="date" name="start_date" class="form-control" value="{{ request('start_date') }}">
                        </div>
                        <div class="col-md-2 mb-2">
                            <label class="form-label">Sampai Tanggal</label>
                            <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}">
                        </div>
                        <div class="col-md-3 mb-2">
                            <label class="form-label">Cari Aktivitas</label>
                            <input type="text" name="aktivitas" class="form-control" placeholder="Kata kunci..." value="{{ request('aktivitas') }}">
                        </div>
                        <div class="col-md-2 mb-2 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fas fa-search me-2"></i> Filter
                            </button>
                        </div>
                    </div>
                </form>
                
                <!-- Tabel Log -->
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="table-light">
                            <tr>
                                <th width="5%">No</th>
                                <th width="20%">Tanggal & Waktu</th>
                                <th width="15%">User</th>
                                <th width="10%">Role</th>
                                <th width="50%">Aktivitas</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($logs as $index => $log)
                            <tr>
                                <td>{{ $logs->firstItem() + $index }}</small></td>
                                <td>{{ date('d/m/Y H:i:s', strtotime($log->waktu_aktivitas)) }}</small></td>
                                <td>
                                    <strong>{{ $log->nama_lengkap }}</strong>
                                    <br><small class="text-muted">ID: {{ $log->id_user }}</small>
                                </small></td>
                                <td>
                                    <span class="badge bg-{{ $log->role == 'Admin' ? 'danger' : ($log->role == 'Petugas' ? 'info' : 'success') }}">
                                        {{ $log->role }}
                                    </span>
                                </small></td>
                                <td>{{ $log->aktivitas }}</small></td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center py-4">
                                    <i class="fas fa-history fa-3x text-muted mb-2 d-block"></i>
                                    Belum ada data log
                                </small></td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination Custom CSS -->
                @if($logs->hasPages())
                <div class="pagination-wrapper mt-4">
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
                        <div class="pagination-info">
                            <small class="text-muted">
                                Menampilkan <strong>{{ $logs->firstItem() }}</strong> 
                                sampai <strong>{{ $logs->lastItem() }}</strong> 
                                dari <strong>{{ $logs->total() }}</strong> data
                            </small>
                        </div>
                        <div class="pagination-links">
                            <ul class="pagination justify-content-center mb-0">
                                {{-- Previous Page Link --}}
                                @if ($logs->onFirstPage())
                                    <li class="page-item disabled">
                                        <span class="page-link">
                                            <i class="fas fa-chevron-left"></i> Sebelumnya
                                        </span>
                                    </li>
                                @else
                                    <li class="page-item">
                                        <a class="page-link" href="{{ $logs->previousPageUrl() }}" rel="prev">
                                            <i class="fas fa-chevron-left"></i> Sebelumnya
                                        </a>
                                    </li>
                                @endif

                                {{-- Pagination Elements --}}
                                @php
                                    $start = max(1, $logs->currentPage() - 2);
                                    $end = min($logs->lastPage(), $logs->currentPage() + 2);
                                    
                                    if ($start > 1) {
                                        echo '<li class="page-item"><a class="page-link" href="' . $logs->url(1) . '">1</a></li>';
                                        if ($start > 2) {
                                            echo '<li class="page-item disabled"><span class="page-link">...</span></li>';
                                        }
                                    }
                                    
                                    for ($i = $start; $i <= $end; $i++) {
                                        if ($i == $logs->currentPage()) {
                                            echo '<li class="page-item active"><span class="page-link">' . $i . '</span></li>';
                                        } else {
                                            echo '<li class="page-item"><a class="page-link" href="' . $logs->url($i) . '">' . $i . '</a></li>';
                                        }
                                    }
                                    
                                    if ($end < $logs->lastPage()) {
                                        if ($end < $logs->lastPage() - 1) {
                                            echo '<li class="page-item disabled"><span class="page-link">...</span></li>';
                                        }
                                        echo '<li class="page-item"><a class="page-link" href="' . $logs->url($logs->lastPage()) . '">' . $logs->lastPage() . '</a></li>';
                                    }
                                @endphp

                                {{-- Next Page Link --}}
                                @if ($logs->hasMorePages())
                                    <li class="page-item">
                                        <a class="page-link" href="{{ $logs->nextPageUrl() }}" rel="next">
                                            Selanjutnya <i class="fas fa-chevron-right"></i>
                                        </a>
                                    </li>
                                @else
                                    <li class="page-item disabled">
                                        <span class="page-link">
                                            Selanjutnya <i class="fas fa-chevron-right"></i>
                                        </span>
                                    </li>
                                @endif
                            </ul>
                        </div>
                        <div class="pagination-page-size">
                            <select class="form-select form-select-sm" id="perPage" style="width: auto;">
                                <option value="10" {{ request('per_page', 20) == 10 ? 'selected' : '' }}>10 per halaman</option>
                                <option value="20" {{ request('per_page', 20) == 20 ? 'selected' : '' }}>20 per halaman</option>
                                <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50 per halaman</option>
                                <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100 per halaman</option>
                            </select>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<style>
    /* Custom Pagination Styles */
    .pagination-wrapper {
        background: #f8f9fa;
        padding: 15px 20px;
        border-radius: 10px;
        border: 1px solid #e9ecef;
    }
    
    .pagination {
        gap: 5px;
    }
    
    .page-item {
        margin: 0 2px;
    }
    
    .page-link {
        color: #4e73df;
        background-color: #fff;
        border: 1px solid #e3e6f0;
        padding: 8px 14px;
        font-size: 14px;
        font-weight: 500;
        border-radius: 8px;
        transition: all 0.3s ease;
    }
    
    .page-link:hover {
        background-color: #4e73df;
        border-color: #4e73df;
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 2px 8px rgba(78, 115, 223, 0.3);
    }
    
    .page-item.active .page-link {
        background-color: #4e73df;
        border-color: #4e73df;
        color: white;
        box-shadow: 0 2px 8px rgba(78, 115, 223, 0.4);
    }
    
    .page-item.disabled .page-link {
        color: #858796;
        pointer-events: none;
        background-color: #f8f9fc;
        border-color: #e3e6f0;
        opacity: 0.6;
    }
    
    .pagination-info {
        background: white;
        padding: 8px 16px;
        border-radius: 8px;
        border: 1px solid #e3e6f0;
    }
    
    .pagination-info strong {
        color: #4e73df;
        font-weight: 600;
    }
    
    .pagination-page-size select {
        border-radius: 8px;
        border-color: #e3e6f0;
        padding: 6px 12px;
        font-size: 14px;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    
    .pagination-page-size select:hover {
        border-color: #4e73df;
    }
    
    .pagination-page-size select:focus {
        border-color: #4e73df;
        box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25);
    }
    
    /* Responsive */
    @media (max-width: 768px) {
        .pagination-wrapper {
            padding: 12px 15px;
        }
        
        .page-link {
            padding: 6px 10px;
            font-size: 12px;
        }
        
        .pagination-info {
            font-size: 12px;
        }
    }
    
    /* Animation for pagination */
    .page-link {
        position: relative;
        overflow: hidden;
    }
    
    .page-link::before {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        width: 0;
        height: 0;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.3);
        transform: translate(-50%, -50%);
        transition: width 0.3s, height 0.3s;
    }
    
    .page-link:hover::before {
        width: 100px;
        height: 100px;
    }
</style>

<script>
    // Per Page change handler
    document.getElementById('perPage')?.addEventListener('change', function() {
        const url = new URL(window.location.href);
        url.searchParams.set('per_page', this.value);
        window.location.href = url.toString();
    });
</script>
@endsection