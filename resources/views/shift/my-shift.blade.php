@extends('layouts.app')

@section('title', 'Shift Saya')

@section('content')
<div class="row">
    <div class="col-12">
        <!-- Shift Hari Ini -->
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="fas fa-calendar-day me-2"></i> Shift Hari Ini</h5>
            </div>
            <div class="card-body text-center">
                @if($shiftHariIni)
                    <div class="row">
                        <div class="col-md-6">
                            <div class="border rounded p-3 mb-2">
                                <i class="fas fa-clock fa-2x text-primary mb-2"></i>
                                <h3>{{ date('H:i', strtotime($shiftHariIni->jam_masuk)) }} - {{ date('H:i', strtotime($shiftHariIni->jam_keluar)) }} WIB</h3>
                                <p class="text-muted mb-0">Anda bertugas hari ini</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="border rounded p-3 mb-2 bg-light">
                                <i class="fas fa-calendar-alt fa-2x text-success mb-2"></i>
                                <h4>{{ date('d F Y') }}</h4>
                                <p class="text-muted mb-0">Tanggal</p>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Anda tidak memiliki shift untuk hari ini.
                    </div>
                    <p>Silahkan hubungi admin untuk mendapatkan jadwal shift.</p>
                @endif
            </div>
        </div>
        
        <!-- Statistik -->
        <div class="row mb-4">
            <div class="col-md-4 mb-3">
                <div class="card bg-primary text-white">
                    <div class="card-body text-center">
                        <h2 class="mb-0">{{ $totalShiftBulanIni }}</h2>
                        <p class="mb-0">Total Shift Bulan Ini</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card bg-success text-white">
                    <div class="card-body text-center">
                        <h2 class="mb-0">{{ $completedShifts }}</h2>
                        <p class="mb-0">Shift Selesai</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card bg-warning text-white">
                    <div class="card-body text-center">
                        <h2 class="mb-0">{{ $upcomingShifts }}</h2>
                        <p class="mb-0">Shift Mendatang</p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Pilih Bulan -->
        <div class="card mb-4">
            <div class="card-header bg-white">
                <h6 class="mb-0"><i class="fas fa-filter me-2"></i> Filter Bulan</h6>
            </div>
            <div class="card-body">
                <form method="GET" action="{{ route('shift.my-shift') }}" class="row">
                    <div class="col-md-4">
                        <select name="bulan" class="form-select">
                            @for($i = 1; $i <= 12; $i++)
                            <option value="{{ $i }}" {{ $bulan == $i ? 'selected' : '' }}>
                                {{ date('F', mktime(0, 0, 0, $i, 1)) }}
                            </option>
                            @endfor
                        </select>
                    </div>
                    <div class="col-md-4">
                        <select name="tahun" class="form-select">
                            @for($i = date('Y') - 2; $i <= date('Y') + 2; $i++)
                            <option value="{{ $i }}" {{ $tahun == $i ? 'selected' : '' }}>{{ $i }}</option>
                            @endfor
                        </select>
                    </div>
                    <div class="col-md-4">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-search me-2"></i> Tampilkan
                        </button>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- Tabel Jadwal Shift -->
        <div class="card">
            <div class="card-header bg-white">
                <h6 class="mb-0"><i class="fas fa-table me-2"></i> Jadwal Shift Bulan {{ date('F', mktime(0, 0, 0, $bulan, 1)) }} {{ $tahun }}</h6>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th width="10%">No</th>
                                <th width="20%">Tanggal</th>
                                <th width="25%">Jam Masuk</th>
                                <th width="25%">Jam Keluar</th>
                                <th width="20%">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($shifts as $index => $shift)
                            @php
                                $isToday = date('Y-m-d') == $shift->tanggal;
                                $isPast = date('Y-m-d') > $shift->tanggal;
                            @endphp
                            <tr>
                                <td>{{ $index + 1 }}</small></td>
                                <td>
                                    {{ date('d/m/Y', strtotime($shift->tanggal)) }}
                                    @if($isToday)
                                        <span class="badge bg-danger ms-2">Hari Ini</span>
                                    @endif
                                </small></td>
                                <td>{{ date('H:i', strtotime($shift->jam_masuk)) }} WIB</small></td>
                                <td>{{ date('H:i', strtotime($shift->jam_keluar)) }} WIB</small></td>
                                <td>
                                    @if($isPast)
                                        <span class="badge bg-secondary">Selesai</span>
                                    @elseif($isToday)
                                        <span class="badge bg-success">Berlangsung</span>
                                    @else
                                        <span class="badge bg-info">Mendatang</span>
                                    @endif
                                </small></td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center py-4">
                                    <i class="fas fa-calendar fa-3x text-muted mb-2 d-block"></i>
                                    Tidak ada jadwal shift untuk bulan ini
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