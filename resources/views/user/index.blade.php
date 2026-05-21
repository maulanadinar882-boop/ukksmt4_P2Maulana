@extends('layouts.app')

@section('title', 'Manajemen User')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <ul class="nav nav-tabs card-header-tabs" id="userTab" role="tablist">
                    <li class="nav-item">
                        <button class="nav-link active" id="admin-tab" data-bs-toggle="tab"
                            data-bs-target="#tab-admin" type="button" role="tab">
                            <i class="fas fa-user-shield"></i> Admin
                        </button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link" id="petugas-tab" data-bs-toggle="tab"
                            data-bs-target="#tab-petugas" type="button" role="tab">
                            <i class="fas fa-user-check"></i> Petugas
                        </button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link" id="owner-tab" data-bs-toggle="tab"
                            data-bs-target="#tab-owner" type="button" role="tab">
                            <i class="fas fa-chart-line"></i> Owner
                        </button>
                    </li>
                </ul>
            </div>
            <div class="card-body">
                <div class="tab-content">

                    <!-- ==================== TAB ADMIN ==================== -->
                    <div class="tab-pane fade show active" id="tab-admin" role="tabpanel">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="mb-0"><i class="fas fa-user-shield"></i> Data Admin</h6>
                            <button type="button" class="btn btn-success btn-lg" data-bs-toggle="modal" data-bs-target="#createAdminModal" style="padding: 10px 24px; font-size: 14px;">
                                <i class="fas fa-plus me-2"></i> Tambah Admin
                            </button>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th width="5%">No</th>
                                        <th width="8%">Avatar</th>
                                        <th>Nama Lengkap</th>
                                        <th>Username</th>
                                        <th>Role</th>
                                        <th>Status</th>
                                        <th width="18%">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($admins as $index => $admin)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>
                                            <div class="avatar-circle bg-danger text-white">
                                                {{ strtoupper(substr($admin->nama_lengkap, 0, 1)) }}
                                            </div>
                                        </td>
                                        <td>{{ $admin->nama_lengkap }}</td>
                                        <td>{{ $admin->username }}</td>
                                        <td><span class="badge bg-danger px-3 py-2"><i class="fas fa-user-shield me-1"></i> Admin</span></td>
                                        <td>
                                            @if($admin->status_aktif)
                                                <span class="badge bg-success px-3 py-2"><i class="fas fa-check-circle me-1"></i> Aktif</span>
                                            @else
                                                <span class="badge bg-secondary px-3 py-2"><i class="fas fa-ban me-1"></i> Nonaktif</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="d-flex gap-2 flex-wrap">
                                                <button class="btn btn-info" style="padding: 8px 16px; font-size: 13px;" data-bs-toggle="modal" data-bs-target="#viewAdminModal{{ $admin->id_user }}">
                                                    <i class="fas fa-eye me-1"></i> Detail
                                                </button>
                                                <button class="btn btn-warning" style="padding: 8px 16px; font-size: 13px;" data-bs-toggle="modal" data-bs-target="#editAdminModal{{ $admin->id_user }}">
                                                    <i class="fas fa-edit me-1"></i> Edit
                                                </button>
                                                <button class="btn btn-danger" style="padding: 8px 16px; font-size: 13px;" data-bs-toggle="modal" data-bs-target="#deleteAdminModal{{ $admin->id_user }}">
                                                    <i class="fas fa-trash me-1"></i> Hapus
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-4">
                                            <i class="fas fa-user-shield fa-3x text-muted mb-2 d-block"></i>
                                            Belum ada data Admin
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- ==================== TAB PETUGAS ==================== -->
                    <div class="tab-pane fade" id="tab-petugas" role="tabpanel">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="mb-0"><i class="fas fa-user-check"></i> Data Petugas</h6>
                            <button type="button" class="btn btn-success btn-lg" data-bs-toggle="modal" data-bs-target="#createPetugasModal" style="padding: 10px 24px; font-size: 14px;">
                                <i class="fas fa-plus me-2"></i> Tambah Petugas
                            </button>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th width="5%">No</th>
                                        <th width="8%">Avatar</th>
                                        <th>Nama Lengkap</th>
                                        <th>Username</th>
                                        <th>Role</th>
                                        <th>Status</th>
                                        <th width="18%">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($petugas as $index => $p)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>
                                            <div class="avatar-circle bg-info text-white">
                                                {{ strtoupper(substr($p->nama_lengkap, 0, 1)) }}
                                            </div>
                                        </td>
                                        <td>{{ $p->nama_lengkap }}</td>
                                        <td>{{ $p->username }}</td>
                                        <td><span class="badge bg-info px-3 py-2"><i class="fas fa-user-check me-1"></i> Petugas</span></td>
                                        <td>
                                            @if($p->status_aktif)
                                                <span class="badge bg-success px-3 py-2"><i class="fas fa-check-circle me-1"></i> Aktif</span>
                                            @else
                                                <span class="badge bg-secondary px-3 py-2"><i class="fas fa-ban me-1"></i> Nonaktif</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="d-flex gap-2 flex-wrap">
                                                <button class="btn btn-info" style="padding: 8px 16px; font-size: 13px;" data-bs-toggle="modal" data-bs-target="#viewPetugasModal{{ $p->id_user }}">
                                                    <i class="fas fa-eye me-1"></i> Detail
                                                </button>
                                                <button class="btn btn-warning" style="padding: 8px 16px; font-size: 13px;" data-bs-toggle="modal" data-bs-target="#editPetugasModal{{ $p->id_user }}">
                                                    <i class="fas fa-edit me-1"></i> Edit
                                                </button>
                                                <button class="btn btn-danger" style="padding: 8px 16px; font-size: 13px;" data-bs-toggle="modal" data-bs-target="#deletePetugasModal{{ $p->id_user }}">
                                                    <i class="fas fa-trash me-1"></i> Hapus
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-4">
                                            <i class="fas fa-user-check fa-3x text-muted mb-2 d-block"></i>
                                            Belum ada data Petugas
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- ==================== TAB OWNER ==================== -->
                    <div class="tab-pane fade" id="tab-owner" role="tabpanel">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="mb-0"><i class="fas fa-chart-line"></i> Data Owner</h6>
                            <button type="button" class="btn btn-success btn-lg" data-bs-toggle="modal" data-bs-target="#createOwnerModal" style="padding: 10px 24px; font-size: 14px;">
                                <i class="fas fa-plus me-2"></i> Tambah Owner
                            </button>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th width="5%">No</th>
                                        <th width="8%">Avatar</th>
                                        <th>Nama Lengkap</th>
                                        <th>Username</th>
                                        <th>Role</th>
                                        <th>Status</th>
                                        <th width="18%">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($owners as $index => $owner)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>
                                            <div class="avatar-circle bg-success text-white">
                                                {{ strtoupper(substr($owner->nama_lengkap, 0, 1)) }}
                                            </div>
                                        </td>
                                        <td>{{ $owner->nama_lengkap }}</td>
                                        <td>{{ $owner->username }}</td>
                                        <td><span class="badge bg-success px-3 py-2"><i class="fas fa-chart-line me-1"></i> Owner</span></td>
                                        <td>
                                            @if($owner->status_aktif)
                                                <span class="badge bg-success px-3 py-2"><i class="fas fa-check-circle me-1"></i> Aktif</span>
                                            @else
                                                <span class="badge bg-secondary px-3 py-2"><i class="fas fa-ban me-1"></i> Nonaktif</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="d-flex gap-2 flex-wrap">
                                                <button class="btn btn-info" style="padding: 8px 16px; font-size: 13px;" data-bs-toggle="modal" data-bs-target="#viewOwnerModal{{ $owner->id_user }}">
                                                    <i class="fas fa-eye me-1"></i> Detail
                                                </button>
                                                <button class="btn btn-warning" style="padding: 8px 16px; font-size: 13px;" data-bs-toggle="modal" data-bs-target="#editOwnerModal{{ $owner->id_user }}">
                                                    <i class="fas fa-edit me-1"></i> Edit
                                                </button>
                                                <button class="btn btn-danger" style="padding: 8px 16px; font-size: 13px;" data-bs-toggle="modal" data-bs-target="#deleteOwnerModal{{ $owner->id_user }}">
                                                    <i class="fas fa-trash me-1"></i> Hapus
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-4">
                                            <i class="fas fa-chart-line fa-3x text-muted mb-2 d-block"></i>
                                            Belum ada data Owner
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
    </div>
</div>

<style>
    .avatar-circle {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        font-size: 16px;
    }
    
    .table td {
        vertical-align: middle;
        padding: 12px;
    }
    
    .modal-lg {
        max-width: 800px;
    }
</style>

<!-- ==================== MODAL CREATE ADMIN ==================== -->
<div class="modal fade" id="createAdminModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title"><i class="fas fa-plus-circle me-2"></i> Tambah Admin Baru</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('user.store') }}" method="POST">
                @csrf
                <input type="hidden" name="role" value="Admin">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" name="nama_lengkap" class="form-control form-control-lg" required style="padding: 10px 15px;">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Username <span class="text-danger">*</span></label>
                            <input type="text" name="username" class="form-control form-control-lg" required style="padding: 10px 15px;">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Password <span class="text-danger">*</span></label>
                            <input type="password" name="password" class="form-control form-control-lg" required style="padding: 10px 15px;">
                            <small class="text-muted">Minimal 4 karakter</small>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Status</label>
                            <select name="status_aktif" class="form-select form-select-lg" style="padding: 10px 15px;">
                                <option value="1">Aktif</option>
                                <option value="0">Nonaktif</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-lg px-4" data-bs-dismiss="modal" style="padding: 10px 24px;">Batal</button>
                    <button type="submit" class="btn btn-danger btn-lg px-4" style="padding: 10px 24px;">
                        <i class="fas fa-save me-2"></i> Simpan Admin
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ==================== MODAL CREATE PETUGAS ==================== -->
<div class="modal fade" id="createPetugasModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title"><i class="fas fa-plus-circle me-2"></i> Tambah Petugas Baru</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('user.store') }}" method="POST">
                @csrf
                <input type="hidden" name="role" value="Petugas">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" name="nama_lengkap" class="form-control form-control-lg" required style="padding: 10px 15px;">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Username <span class="text-danger">*</span></label>
                            <input type="text" name="username" class="form-control form-control-lg" required style="padding: 10px 15px;">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Password <span class="text-danger">*</span></label>
                            <input type="password" name="password" class="form-control form-control-lg" required style="padding: 10px 15px;">
                            <small class="text-muted">Minimal 4 karakter</small>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Status</label>
                            <select name="status_aktif" class="form-select form-select-lg" style="padding: 10px 15px;">
                                <option value="1">Aktif</option>
                                <option value="0">Nonaktif</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-lg px-4" data-bs-dismiss="modal" style="padding: 10px 24px;">Batal</button>
                    <button type="submit" class="btn btn-info btn-lg px-4" style="padding: 10px 24px;">
                        <i class="fas fa-save me-2"></i> Simpan Petugas
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ==================== MODAL CREATE OWNER ==================== -->
<div class="modal fade" id="createOwnerModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title"><i class="fas fa-plus-circle me-2"></i> Tambah Owner Baru</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('user.store') }}" method="POST">
                @csrf
                <input type="hidden" name="role" value="Owner">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" name="nama_lengkap" class="form-control form-control-lg" required style="padding: 10px 15px;">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Username <span class="text-danger">*</span></label>
                            <input type="text" name="username" class="form-control form-control-lg" required style="padding: 10px 15px;">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Password <span class="text-danger">*</span></label>
                            <input type="password" name="password" class="form-control form-control-lg" required style="padding: 10px 15px;">
                            <small class="text-muted">Minimal 4 karakter</small>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Status</label>
                            <select name="status_aktif" class="form-select form-select-lg" style="padding: 10px 15px;">
                                <option value="1">Aktif</option>
                                <option value="0">Nonaktif</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-lg px-4" data-bs-dismiss="modal" style="padding: 10px 24px;">Batal</button>
                    <button type="submit" class="btn btn-success btn-lg px-4" style="padding: 10px 24px;">
                        <i class="fas fa-save me-2"></i> Simpan Owner
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ==================== MODAL VIEW, EDIT, DELETE UNTUK ADMIN ==================== -->
@foreach($admins as $admin)
<div class="modal fade" id="viewAdminModal{{ $admin->id_user }}" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title"><i class="fas fa-user-shield me-2"></i> Detail Admin</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <table class="table table-borderless">
                    <tr><th width="40%">ID User</th><td>: {{ $admin->id_user }}</td></tr>
                    <tr><th>Nama Lengkap</th><td>: {{ $admin->nama_lengkap }}</td></tr>
                    <tr><th>Username</th><td>: {{ $admin->username }}</td></tr>
                    <tr><th>Role</th><td>: <span class="badge bg-danger">Admin</span></td></tr>
                    <tr><th>Status</th><td>: @if($admin->status_aktif) <span class="badge bg-success">Aktif</span> @else <span class="badge bg-secondary">Nonaktif</span> @endif</td></tr>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="editAdminModal{{ $admin->id_user }}" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <h5 class="modal-title"><i class="fas fa-edit me-2"></i> Edit Admin</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('user.update', $admin->id_user) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nama Lengkap</label>
                            <input type="text" name="nama_lengkap" class="form-control form-control-lg" value="{{ $admin->nama_lengkap }}" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Username</label>
                            <input type="text" class="form-control form-control-lg" value="{{ $admin->username }}" disabled>
                            <small class="text-muted">Username tidak dapat diubah</small>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Password</label>
                            <input type="password" name="password" class="form-control form-control-lg" placeholder="Kosongkan jika tidak diubah">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Status</label>
                            <select name="status_aktif" class="form-select form-select-lg">
                                <option value="1" {{ $admin->status_aktif == 1 ? 'selected' : '' }}>Aktif</option>
                                <option value="0" {{ $admin->status_aktif == 0 ? 'selected' : '' }}>Nonaktif</option>
                            </select>
                        </div>
                    </div>
                    <input type="hidden" name="role" value="Admin">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-lg px-4" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning btn-lg px-4">Update Admin</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="deleteAdminModal{{ $admin->id_user }}" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title"><i class="fas fa-trash me-2"></i> Hapus Admin</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
                <i class="fas fa-exclamation-triangle fa-3x text-warning mb-3"></i>
                <p>Yakin ingin menghapus admin <strong>{{ $admin->nama_lengkap }}</strong>?</p>
                <p class="text-danger small">Data yang terkait dengan user ini juga akan terhapus!</p>
            </div>
            <div class="modal-footer">
                <form action="{{ route('user.destroy', $admin->id_user) }}" method="POST">
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

<!-- ==================== MODAL VIEW, EDIT, DELETE UNTUK PETUGAS ==================== -->
@foreach($petugas as $p)
<div class="modal fade" id="viewPetugasModal{{ $p->id_user }}" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title"><i class="fas fa-user-check me-2"></i> Detail Petugas</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <table class="table table-borderless">
                    <tr><th width="40%">ID User</th><td>: {{ $p->id_user }}</td></tr>
                    <tr><th>Nama Lengkap</th><td>: {{ $p->nama_lengkap }}</td></tr>
                    <tr><th>Username</th><td>: {{ $p->username }}</td></tr>
                    <tr><th>Role</th><td>: <span class="badge bg-info">Petugas</span></td></tr>
                    <tr><th>Status</th><td>: @if($p->status_aktif) <span class="badge bg-success">Aktif</span> @else <span class="badge bg-secondary">Nonaktif</span> @endif</td></tr>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="editPetugasModal{{ $p->id_user }}" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <h5 class="modal-title"><i class="fas fa-edit me-2"></i> Edit Petugas</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('user.update', $p->id_user) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nama Lengkap</label>
                            <input type="text" name="nama_lengkap" class="form-control form-control-lg" value="{{ $p->nama_lengkap }}" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Username</label>
                            <input type="text" class="form-control form-control-lg" value="{{ $p->username }}" disabled>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Password</label>
                            <input type="password" name="password" class="form-control form-control-lg" placeholder="Kosongkan jika tidak diubah">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Status</label>
                            <select name="status_aktif" class="form-select form-select-lg">
                                <option value="1" {{ $p->status_aktif == 1 ? 'selected' : '' }}>Aktif</option>
                                <option value="0" {{ $p->status_aktif == 0 ? 'selected' : '' }}>Nonaktif</option>
                            </select>
                        </div>
                    </div>
                    <input type="hidden" name="role" value="Petugas">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-lg px-4" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning btn-lg px-4">Update Petugas</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="deletePetugasModal{{ $p->id_user }}" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title"><i class="fas fa-trash me-2"></i> Hapus Petugas</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
                <i class="fas fa-exclamation-triangle fa-3x text-warning mb-3"></i>
                <p>Yakin ingin menghapus petugas <strong>{{ $p->nama_lengkap }}</strong>?</p>
            </div>
            <div class="modal-footer">
                <form action="{{ route('user.destroy', $p->id_user) }}" method="POST">
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

<!-- ==================== MODAL VIEW, EDIT, DELETE UNTUK OWNER ==================== -->
@foreach($owners as $owner)
<div class="modal fade" id="viewOwnerModal{{ $owner->id_user }}" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title"><i class="fas fa-chart-line me-2"></i> Detail Owner</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <table class="table table-borderless">
                    <tr><th width="40%">ID User</th><td>: {{ $owner->id_user }}</td></tr>
                    <tr><th>Nama Lengkap</th><td>: {{ $owner->nama_lengkap }}</td></tr>
                    <tr><th>Username</th><td>: {{ $owner->username }}</td></tr>
                    <tr><th>Role</th><td>: <span class="badge bg-success">Owner</span></td></tr>
                    <tr><th>Status</th><td>: @if($owner->status_aktif) <span class="badge bg-success">Aktif</span> @else <span class="badge bg-secondary">Nonaktif</span> @endif</td></tr>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="editOwnerModal{{ $owner->id_user }}" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <h5 class="modal-title"><i class="fas fa-edit me-2"></i> Edit Owner</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('user.update', $owner->id_user) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nama Lengkap</label>
                            <input type="text" name="nama_lengkap" class="form-control form-control-lg" value="{{ $owner->nama_lengkap }}" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Username</label>
                            <input type="text" class="form-control form-control-lg" value="{{ $owner->username }}" disabled>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Password</label>
                            <input type="password" name="password" class="form-control form-control-lg" placeholder="Kosongkan jika tidak diubah">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Status</label>
                            <select name="status_aktif" class="form-select form-select-lg">
                                <option value="1" {{ $owner->status_aktif == 1 ? 'selected' : '' }}>Aktif</option>
                                <option value="0" {{ $owner->status_aktif == 0 ? 'selected' : '' }}>Nonaktif</option>
                            </select>
                        </div>
                    </div>
                    <input type="hidden" name="role" value="Owner">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-lg px-4" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning btn-lg px-4">Update Owner</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="deleteOwnerModal{{ $owner->id_user }}" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title"><i class="fas fa-trash me-2"></i> Hapus Owner</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
                <i class="fas fa-exclamation-triangle fa-3x text-warning mb-3"></i>
                <p>Yakin ingin menghapus owner <strong>{{ $owner->nama_lengkap }}</strong>?</p>
            </div>
            <div class="modal-footer">
                <form action="{{ route('user.destroy', $owner->id_user) }}" method="POST">
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

<!-- JavaScript untuk tab hash -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var hash = window.location.hash;
        if (hash) {
            var tab = document.querySelector('.nav-tabs button[data-bs-target="' + hash + '"]');
            if (tab) {
                var tabTrigger = new bootstrap.Tab(tab);
                tabTrigger.show();
            }
        }
        
        // Simpan tab ke hash saat berganti
        var tabs = document.querySelectorAll('.nav-tabs button');
        tabs.forEach(function(tab) {
            tab.addEventListener('shown.bs.tab', function(e) {
                window.location.hash = e.target.getAttribute('data-bs-target');
            });
        });
    });
</script>
@endsection