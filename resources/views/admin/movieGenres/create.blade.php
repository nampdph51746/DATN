@extends('layouts.admin.admin')

@section('content')
<div class="container-xxl py-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.genres.index') }}" class="text-decoration-none">Thể loại phim</a></li>
            <li class="breadcrumb-item active">Thêm mới</li>
        </ol>
    </nav>

    <!-- Header -->
    <div class="d-flex align-items-center mb-4">
        <div class="me-3">
            <div class="icon-wrapper">
                <i class="fas fa-plus text-primary"></i>
            </div>
        </div>
        <div>
            <h2 class="mb-1 fw-bold text-primary">Thêm thể loại phim mới</h2>
            <p class="text-muted mb-0">Tạo thể loại phim mới trong hệ thống</p>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-8 col-md-10">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4">
                    <form action="{{ route('admin.genres.store') }}" method="POST" class="needs-validation" novalidate>
                        @csrf
                        
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
                                   value="{{ old('name') }}" 
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
                                      placeholder="Nhập mô tả chi tiết về thể loại phim này...">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Mô tả giúp người dùng hiểu rõ hơn về thể loại</div>
                        </div>

                        <div class="d-flex justify-content-end gap-3 pt-3 border-top">
                            <a href="{{ route('admin.genres.index') }}" class="btn btn-outline-secondary btn-lg rounded-pill px-4">
                                <i class="fas fa-times me-2"></i>Hủy bỏ
                            </a>
                            <button type="submit" class="btn btn-primary btn-lg rounded-pill px-4">
                                <i class="fas fa-save me-2"></i>Lưu thể loại
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
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    color: white;
    box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
}

.card {
    transition: all 0.3s ease;
}

.form-control:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
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
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
    box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
}

.btn-primary:hover {
    background: linear-gradient(135deg, #5a6fd8 0%, #6a4190 100%);
    box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
}

.breadcrumb-item a:hover {
    color: #667eea !important;
}

.form-text {
    font-size: 0.875rem;
    color: #6c757d;
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

// Auto-resize textarea
document.getElementById('description').addEventListener('input', function() {
    this.style.height = 'auto';
    this.style.height = (this.scrollHeight) + 'px';
});
</script>
@endsection
