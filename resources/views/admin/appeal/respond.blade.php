@extends('layouts.app')

@section('title', 'Proses Appeal')

@section('content')
<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="fas fa-reply-all me-2"></i> Proses Appeal</h5>
            </div>
            <div class="card-body">
                <div class="alert alert-secondary">
                    <p><strong>Pengirim:</strong> {{ $appeal->pengirim }}</p>
                    <p><strong>Judul:</strong> {{ $appeal->judul }}</p>
                    <p><strong>Deskripsi:</strong></p>
                    <p class="bg-light p-3 rounded">{{ $appeal->deskripsi }}</p>
                </div>
                
                <form action="{{ route('admin.appeal.process', $appeal->id_appeal) }}" method="POST">
                    @csrf
                    
                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select" required>
                            <option value="pending" {{ $appeal->status == 'pending' ? 'selected' : '' }}>Pending (Menunggu)</option>
                            <option value="diproses" {{ $appeal->status == 'diproses' ? 'selected' : '' }}>Diproses (Sedang Ditangani)</option>
                            <option value="selesai" {{ $appeal->status == 'selesai' ? 'selected' : '' }}>Selesai</option>
                            <option value="ditolak" {{ $appeal->status == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Balasan</label>
                        <textarea name="balasan" class="form-control" rows="5" 
                                  placeholder="Tulis balasan untuk owner...">{{ $appeal->balasan }}</textarea>
                        <small class="text-muted">Balasan akan dikirim ke owner sebagai notifikasi</small>
                    </div>
                    
                    <hr>
                    
                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('admin.appeal.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i> Kembali
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i> Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection