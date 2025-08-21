@extends('layouts.admin.admin')

@section('content')
<div class="container-xxl py-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.genres.index') }}" class="text-decoration-none">Thể loại phim</a></li>
            <li class="breadcrumb-item active">Chỉnh sửa: {{ $genre->name }}</li>
        </ol>
    </nav>

    <!-- Header -->
    <div class="d-flex align-items-center mb-4">
        <div class="me-3">
            <div class="icon-wrapper">
                <i class="fas fa-edit text-white"></i>
            </div>
        </div>
        <div>
            <h2 class="mb-1 fw-bold text-primary">Chỉnh sửa thể loại phim</h2>
            <p class="text-muted mb-0">Cập nhật thông tin thể loại: <strong>{{ $genre->name }}</strong></p>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-8 col-md-10">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4">
                    <form action="{{ route('admin.genres.update', $genre->id) }}" method="POST" class="needs-validation" novalidate>
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-4">
                            <label for="name" class="form-label fw-semibold">
                                <i class="fas fa-tag text-primary me-2"></i>
                                Tên thể loại <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   id="name" 
                                   name="name" 
                                   class="form-control form-control-lg rounded-3 @error('name') is-invalid @enderror" 
                                   placeholder="Ví dụ: Hành động, Hài hước, Kinh dị..." 
                                   value="{{ old('name', $genre->name) }}" 
                                   required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Tên thể loại phải duy nhất và dễ hiểu</div>
                        </div>

                        <div class="mb-4">
                            <label for="description" class="form-label fw-semibold">
                                <i class="fas fa-align-left text-primary me-2"></i>
                                Mô tả (tuỳ chọn)
                            </label>
                            <textarea id="description" 
                                      name="description" 
                                      class="form-control rounded-3 @error('description') is-invalid @enderror" 
                                      rows="4" 
                                      placeholder="Nhập mô tả chi tiết về thể loại phim này...">{{ old('description', $genre->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Mô tả giúp người dùng hiểu rõ hơn về thể loại</div>
                        </div>

                        <!-- Preview Section -->
                        <div class="preview-section p-3 bg-light rounded-3 mb-4">
                            <h6 class="fw-semibold mb-3"><i class="fas fa-eye text-primary me-2"></i>Xem trước</h6>
                            <div class="d-flex align-items-center">
                                <div class="genre-icon me-3">
                                    <i class="fas fa-film text-primary"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0 fw-semibold preview-name">{{ $genre->name }}</h6>
                                    <small class="text-muted preview-description">{{ $genre->description ?: 'Chưa có mô tả' }}</small>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-3 pt-3 border-top">
                            <a href="{{ route('admin.genres.index') }}" class="btn btn-outline-secondary btn-lg rounded-pill px-4">
                                <i class="fas fa-times me-2"></i>Hủy bỏ
                            </a>
                            <button type="submit" class="btn btn-primary btn-lg rounded-pill px-4">
                                <i class="fas fa-save me-2"></i>Cập nhật
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
.icon-wrapper {
    width: 60px;
    height: 60px;
    background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    color: white;
    box-shadow: 0 4px 15px rgba(23, 162, 184, 0.3);
}

.card {
    transition: all 0.3s ease;
}

.form-control:focus {
    border-color: #17a2b8;
    box-shadow: 0 0 0 0.2rem rgba(23, 162, 184, 0.25);
}

.form-control-lg {
    padding: 0.75rem 1rem;
}

.btn {
    transition: all 0.3s ease;
}

.btn:hover {
    transform: translateY(-1px);
}

.btn-primary {
    background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
    border: none;
    box-shadow: 0 4px 15px rgba(23, 162, 184, 0.3);
}

.btn-primary:hover {
    background: linear-gradient(135deg, #138496 0%, #117a8b 100%);
    box-shadow: 0 6px 20px rgba(23, 162, 184, 0.4);
}

.breadcrumb-item a:hover {
    color: #17a2b8 !important;
}

.form-text {
    font-size: 0.875rem;
    color: #6c757d;
}

.preview-section {
    border: 1px solid #dee2e6;
}

.genre-icon {
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 50%;
    color: white;
}

@media (max-width: 768px) {
    .icon-wrapper {
        width: 50px;
        height: 50px;
        font-size: 1.25rem;
    }
    
    .btn-lg {
        padding: 0.75rem 1.5rem;
    }
    
    .genre-icon {
        width: 35px;
        height: 35px;
    }
}
</style>

<script>
// Form validation
(function() {
    'use strict';
    window.addEventListener('load', function() {
        var forms = document.getElementsByClassName('needs-validation');
        var validation = Array.prototype.filter.call(forms, function(form) {
            form.addEventListener('submit', function(event) {
                if (form.checkValidity() === false) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                form.classList.add('was-validated');
            }, false);
        });
    }, false);
})();

// Live preview
document.getElementById('name').addEventListener('input', function() {
    document.querySelector('.preview-name').textContent = this.value || '{{ $genre->name }}';
});

document.getElementById('description').addEventListener('input', function() {
    document.querySelector('.preview-description').textContent = this.value || 'Chưa có mô tả';
    
    // Auto-resize textarea
    this.style.height = 'auto';
    this.style.height = (this.scrollHeight) + 'px';
});
</script>
@endsection