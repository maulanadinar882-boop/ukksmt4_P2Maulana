@extends('layouts.app')

@section('title', 'Manajemen Shift')

@section('content')
<div class="row">
    <div class="col-12">
        <!-- Daftar Shift -->
        <div class="card mb-4">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-clock me-2"></i> Daftar Shift</h5>
                    <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#createShiftModal">
                        <i class="fas fa-plus me-2"></i> Tambah Shift
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="table-light">
                            <tr>
                                <th width="5%">No</th>
                                <th width="25%">Jam Masuk</th>
                                <th width="25%">Jam Keluar</th>
                                <th width="20%">Status</th>
                                <th width="25%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($shifts as $index => $shift)
                            <tr>
                                <td>{{ $index + 1 }}</small></td>
                                <td>{{ date('H:i', strtotime($shift->jam_masuk)) }} WIB</small></td>
                                <td>{{ date('H:i', strtotime($shift->jam_keluar)) }} WIB</small></td>
                                <td>
                                    <span class="badge bg-success">Aktif</span>
                                </small></td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editShiftModal{{ $shift->id_shift }}">
                                            <i class="fas fa-edit me-1"></i> Edit
                                        </button>
                                        <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteShiftModal{{ $shift->id_shift }}">
                                            <i class="fas fa-trash me-1"></i> Hapus
                                        </button>
                                    </div>
                                </small></td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center py-4">
                                    <i class="fas fa-clock fa-3x text-muted mb-2 d-block"></i>
                                    Belum ada data shift
                                </small></td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        
        <!-- Assign Shift -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-user-plus me-2"></i> Assign Shift ke Petugas</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('shift.assign') }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Pilih Petugas</label>
                            <select name="id_user" class="form-select" required>
                                <option value="">Pilih Petugas</option>
                                @foreach($petugas as $p)
                                <option value="{{ $p->id_user }}">{{ $p->nama_lengkap }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Pilih Shift</label>
                            <select name="id_shift" class="form-select" required>
                                <option value="">Pilih Shift</option>
                                @foreach($shifts as $shift)
                                <option value="{{ $shift->id_shift }}">{{ date('H:i', strtotime($shift->jam_masuk)) }} - {{ date('H:i', strtotime($shift->jam_keluar)) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Tanggal</label>
                            <input type="date" name="tanggal" class="form-control" value="{{ date('Y-m-d') }}" required>
                        </div>
                        <div class="col-md-2 mb-3 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fas fa-save me-2"></i> Assign
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- Shift Assignments Hari Ini -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-calendar-day me-2"></i> Shift Hari Ini ({{ date('d/m/Y') }})</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>No</th>
                                <th>Petugas</th>
                                <th>Shift</th>
                                <th>Jam</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($shiftAssignments as $index => $assignment)
                            <tr>
                                <td>{{ $index + 1 }}</small></td>
                                <td>
                                    <strong>{{ $assignment->nama_lengkap }}</strong>
                                    <br><small class="text-muted">({{ $assignment->role }})</small>
                                </small></td>
                                <td>Shift {{ $assignment->id_shift }}</small></td>
                                <td>{{ date('H:i', strtotime($assignment->jam_masuk)) }} - {{ date('H:i', strtotime($assignment->jam_keluar)) }} WIB</small></td>
                                <td>
                                    <form action="{{ route('shift.unassign', $assignment->id_user_shift) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Yakin hapus assignment ini?')">
                                            <i class="fas fa-trash me-1"></i> Hapus
                                        </button>
                                    </form>
                                </small></td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center py-4">
                                    <i class="fas fa-calendar fa-3x text-muted mb-2 d-block"></i>
                                    Belum ada assignment shift untuk hari ini
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

<!-- Modal Create Shift -->
<div class="modal fade" id="createShiftModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title"><i class="fas fa-plus-circle me-2"></i> Tambah Shift</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('shift.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Jam Mulai</label>
                        <input type="time" name="jam_masuk" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Jam Selesai</label>
                        <input type="time" name="jam_keluar" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Edit & Delete Shift -->
@foreach($shifts as $shift)
<div class="modal fade" id="editShiftModal{{ $shift->id_shift }}" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <h5 class="modal-title"><i class="fas fa-edit me-2"></i> Edit Shift</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('shift.update', $shift->id_shift) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Jam Mulai</label>
                        <input type="time" name="jam_masuk" class="form-control" value="{{ date('H:i', strtotime($shift->jam_masuk)) }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Jam Selesai</label>
                        <input type="time" name="jam_keluar" class="form-control" value="{{ date('H:i', strtotime($shift->jam_keluar)) }}" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="deleteShiftModal{{ $shift->id_shift }}" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title"><i class="fas fa-trash me-2"></i> Hapus Shift</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
                <i class="fas fa-exclamation-triangle fa-3x text-warning mb-3"></i>
                <p>Yakin ingin menghapus shift ini?</p>
            </div>
            <div class="modal-footer">
                <form action="{{ route('shift.destroy', $shift->id_shift) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">Hapus</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endforeach
@endsection