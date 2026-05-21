@extends('layouts.app')

@section('title', 'Ajukan Appeal')

@section('content')
<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card border-0 shadow-lg">
            <div class="card-header bg-gradient-success text-white">
                <div class="d-flex align-items-center">
                    <i class="fas fa-gavel fa-2x me-3"></i>
                    <div>
                        <h5 class="mb-0">Form Ajukan Appeal</h5>
                        <p class="mb-0 small opacity-75">Isi form berikut untuk mengajukan appeal</p>
                    </div>
                </div>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('appeal.store') }}" method="POST">
                    @csrf
                    
                    <div class="mb-4">
                        <label class="form-label fw-bold">
                            <i class="fas fa-heading text-primary me-2"></i> Judul Appeal <span class="text-danger">*</span>
                        </label>
                        <input type="text" name="judul" class="form-control form-control-lg" 
                               style="border-radius: 10px;"
                               placeholder="Contoh: Permintaan Perbaikan Sistem / Laporan Error" required>
                        <small class="text-muted">Buat judul yang jelas dan singkat agar mudah dipahami</small>
                    </div>
                    
                    <div class="mb-4">
                        <label class="form-label fw-bold">
                            <i class="fas fa-align-left text-primary me-2"></i> Deskripsi <span class="text-danger">*</span>
                        </label>
                        <textarea name="deskripsi" class="form-control" rows="6" 
                                  style="border-radius: 10px;"
                                  placeholder="Jelaskan detail appeal Anda secara lengkap..." required></textarea>
                        <small class="text-muted">Jelaskan secara detail tentang apa yang ingin Anda sampaikan</small>
                    </div>
                    
                    <div class="alert alert-info border-0 rounded-3 d-flex align-items-center">
                        <i class="fas fa-info-circle fa-2x me-3"></i>
                        <div>
                            <strong>Informasi Penting:</strong><br>
                            Appeal akan diproses oleh Admin. Status dapat dilihat di halaman "Appeal Saya".
                            Setiap perubahan status akan tercatat di Log Aktivitas.
                        </div>
                    </div>
                    
                    <hr class="my-4">
                    
                    <div class="d-flex justify-content-end gap-3">
                        <a href="{{ route('appeal.index') }}" class="btn btn-secondary btn-lg px-4" style="border-radius: 10px;">
                            <i class="fas fa-arrow-left me-2"></i> Kembali
                        </a>
                        <button type="submit" class="btn btn-gradient-success btn-lg px-5" style="border-radius: 10px;">
                            <i class="fas fa-paper-plane me-2"></i> Kirim Appeal
                        </button>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- Panduan dengan Icon -->
        <div class="row mt-4 g-3">
            <div class="col-md-4">
                <div class="card border-0 shadow-sm text-center h-100">
                    <div class="card-body py-4">
                        <i class="fas fa-flag-checkered fa-3x text-primary mb-3"></i>
                        <h6 class="fw-bold">Judul Jelas</h6>
                        <p class="small text-muted mb-0">Buat judul yang mewakili inti masalah</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm text-center h-100">
                    <div class="card-body py-4">
                        <i class="fas fa-file-alt fa-3x text-primary mb-3"></i>
                        <h6 class="fw-bold">Deskripsi Detail</h6>
                        <p class="small text-muted mb-0">Jelaskan masalah dengan lengkap</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm text-center h-100">
                    <div class="card-body py-4">
                        <i class="fas fa-clock fa-3x text-primary mb-3"></i>
                        <h6 class="fw-bold">Proses Cepat</h6>
                        <p class="small text-muted mb-0">Akan diproses maksimal 3x24 jam</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection