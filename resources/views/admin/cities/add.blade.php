@extends('layouts.admin.admin')

@section('content')
<div class="container-fluid px-4 py-4">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 bg-gradient-info rounded-4 shadow-lg overflow-hidden">
                <div class="card-body p-5">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center gap-4">
                            <div class="avatar-xl rounded-circle bg-white bg-opacity-20 backdrop-blur">
                                <span class="avatar-title rounded-circle text-white">
                                    <i class="bi bi-building-add fs-2"></i>
                                </span>
                            </div>
                            <div class="text-white">
                                <h2 class="fw-bold mb-2">Thêm thành phố mới</h2>
                                <p class="mb-0 opacity-90 fs-5">Tạo thành phố mới trong hệ thống</p>
                            </div>
                        </div>
                        <div class="d-none d-lg-block">
                            <span class="badge bg-white text-info rounded-pill px-4 py-3 fs-6 fw-semibold shadow">
                                <i class="bi bi-plus-circle me-2"></i>
                                Tạo mới
                            </span>
                        </div>
                    </div>
                </div>
                <div class="position-absolute top-0 end-0 opacity-10">
                    <i class="bi bi-building-add" style="font-size: 12rem;"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row justify-content-center">
        <div class="col-xl-8 col-lg-10">
            <form method="POST" action="{{ route('admin.cities.store') }}">
                @csrf
                
                <!-- Thông tin cơ bản -->
                <div class="card border-0 shadow-lg rounded-4 mb-4">
                    <div class="card-header bg-info-subtle border-0 py-4">
                        <h5 class="mb-0 fw-bold text-dark d-flex align-items-center gap-2">
                            <i class="bi bi-buildings text-info"></i>
                            Thông tin thành phố
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-4">
                            <div class="col-md-12">
                                <div class="form-floating">
                                    <input type="text" 
                                           name="name" 
                                           id="name" 
                                           class="form-control @error('name') is-invalid @enderror" 
                                           value="{{ old('name') }}" 
                                           placeholder="Tên thành phố"
                                           required>
                                    <label for="name">
                                        <i class="bi bi-buildings me-2"></i>Tên thành phố <span class="text-danger">*</span>
                                    </label>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="row g-4 mt-2">
                            <div class="col-md-12">
                                <div class="form-floating">
                                    <select name="country_id" 
                                            id="country_id" 
                                            class="form-select @error('country_id') is-invalid @enderror"
                                            required>
                                        <option value="">-- Chọn quốc gia --</option>
                                        @foreach($countries as $country)
                                            <option value="{{ $country->id }}" {{ old('country_id') == $country->id ? 'selected' : '' }}>
                                                {{ $country->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <label for="country_id">
                                        <i class="bi bi-globe me-2"></i>Quốc gia <span class="text-danger">*</span>
                                    </label>
                                    @error('country_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="row g-4 mt-2">
                            <div class="col-12">
                                <div class="alert alert-info border-0 rounded-4">
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-info-circle fs-4 me-3 text-info"></i>
                                        <div>
                                            <h6 class="mb-1 fw-bold">Lưu ý:</h6>
                                            <p class="mb-0 small">
                                                • Tên thành phố phải là duy nhất trong cùng một quốc gia<br>
                                                • Vui lòng chọn quốc gia trước khi lưu thông tin
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="card border-0 shadow-lg rounded-4">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-end gap-3">
                            <a href="{{ route('admin.cities.index') }}" class="btn btn-outline-secondary btn-lg rounded-pill">
                                <i class="bi bi-arrow-left me-2"></i>Quay lại
                            </a>
                            <button type="submit" class="btn btn-info btn-lg rounded-pill">
                                <i class="bi bi-save me-2"></i>Lưu thành phố
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- CSS Styling -->
<style>
/* Gradient Background */
.bg-gradient-info {
    background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
}

/* Form Controls */
.form-floating > .form-control,
.form-floating > .form-select {
    height: calc(3.5rem + 2px);
    font-size: 1rem;
    border: 2px solid #e9ecef;
    border-radius: 0.75rem;
    transition: all 0.3s ease;
}

.form-floating > .form-control:focus,
.form-floating > .form-select:focus {
    border-color: var(--bs-info);
    box-shadow: 0 0 0 3px rgba(var(--bs-info-rgb), 0.1);
    transform: translateY(-1px);
}

.form-floating > label {
    font-weight: 600;
    color: #6c757d;
    padding: 1rem 1rem;
}

/* Avatar Sizes */
.avatar-xl {
    width: 5rem;
    height: 5rem;
}

.avatar-title {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 100%;
    height: 100%;
}

/* Button Enhancements */
.btn {
    transition: all 0.3s ease;
    font-weight: 500;
}

.btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}

.btn-lg {
    padding: 0.75rem 1.5rem;
    font-size: 0.95rem;
}

/* Card Enhancements */
.card {
    transition: all 0.3s ease;
    border: none;
}

.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15) !important;
}

/* Color Subtle Backgrounds */
.bg-info-subtle {
    background-color: rgba(var(--bs-info-rgb), 0.1) !important;
}

/* Responsive */
@media (max-width: 768px) {
    .container-fluid {
        padding: 1rem !important;
    }
    
    .card-body {
        padding: 1.5rem !important;
    }
    
    .btn-lg {
        padding: 0.5rem 1rem;
        font-size: 0.9rem;
    }
    
    .avatar-xl {
        width: 4rem;
        height: 4rem;
    }
}
</style>
@endsection