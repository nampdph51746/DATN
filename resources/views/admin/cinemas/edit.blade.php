@extends('layouts.admin.admin')

@section('content')
<div class="container-fluid px-4 py-4">
    <!-- Header Section -->
    <div class="row mb-5">
        <div class="col-12">
            <div class="edit-header rounded-5 p-5">
                <div class="d-flex align-items-center justify-content-between">
                    <div class="header-content">
                        <h1 class="display-6 fw-bold text-white mb-3">
                            <i class="bi bi-pencil-square me-3"></i>
                            Chỉnh sửa rạp chiếu
                        </h1>
                        <p class="lead text-white-50 mb-0">Cập nhật thông tin: {{ $cinema->name }}</p>
                    </div>
                    <div class="header-icon d-none d-lg-block">
                        <div class="floating-icon">
                            <i class="bi bi-camera-reels"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row justify-content-center">
        <div class="col-xl-10">
            <form method="POST" action="{{ route('admin.cinemas.update', $cinema->id) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <!-- Current Status Info -->
                <div class="form-section mb-4">
                    <div class="status-card">
                        <div class="status-header">
                            <div class="status-icon">
                                <i class="bi bi-info-circle-fill"></i>
                            </div>
                            <div>
                                <h5 class="status-title">Thông tin hiện tại</h5>
                                <p class="status-subtitle">Trạng thái và dữ liệu cập nhật gần nhất</p>
                            </div>
                        </div>
                        
                        <div class="status-body">
                            <div class="row g-3">
                                <div class="col-md-3">
                                    <div class="status-item">
                                        <div class="status-label">Trạng thái</div>
                                        <div class="status-value">
                                            @if($cinema->status == 'active')
                                                <span class="badge bg-success-subtle text-success px-3 py-2 rounded-pill">
                                                    <i class="bi bi-check-circle me-1"></i>Hoạt động
                                                </span>
                                            @else
                                                <span class="badge bg-warning-subtle text-warning px-3 py-2 rounded-pill">
                                                    <i class="bi bi-pause-circle me-1"></i>Tạm dừng
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="status-item">
                                        <div class="status-label">Ngày tạo</div>
                                        <div class="status-value">{{ $cinema->created_at->format('d/m/Y H:i') }}</div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="status-item">
                                        <div class="status-label">Cập nhật cuối</div>
                                        <div class="status-value">{{ $cinema->updated_at->format('d/m/Y H:i') }}</div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="status-item">
                                        <div class="status-label">Thành phố</div>
                                        <div class="status-value">{{ $cinema->city->name ?? 'Chưa xác định' }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Basic Information -->
                <div class="form-section mb-4">
                    <div class="section-card">
                        <div class="section-header">
                            <div class="section-icon bg-warning">
                                <i class="bi bi-info-circle"></i>
                            </div>
                            <div>
                                <h5 class="section-title">Thông tin cơ bản</h5>
                                <p class="section-subtitle">Cập nhật thông tin chính của rạp chiếu</p>
                            </div>
                        </div>
                        
                        <div class="section-body">
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <div class="floating-input">
                                        <input type="text" 
                                               name="name" 
                                               id="name" 
                                               class="floating-input-field @error('name') is-invalid @enderror" 
                                               value="{{ old('name', $cinema->name) }}" 
                                               placeholder=" "
                                               required>
                                        <label for="name" class="floating-input-label">
                                            <i class="bi bi-camera-reels me-2"></i>Tên rạp chiếu
                                        </label>
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="floating-input">
                                        <select name="city_id" 
                                                id="city_id" 
                                                class="floating-input-field @error('city_id') is-invalid @enderror"
                                                required>
                                            <option value="">-- Chọn thành phố --</option>
                                            @foreach($cities as $city)
                                                <option value="{{ $city->id }}" 
                                                    {{ old('city_id', $cinema->city_id) == $city->id ? 'selected' : '' }}>
                                                    {{ $city->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <label for="city_id" class="floating-input-label">
                                            <i class="bi bi-buildings me-2"></i>Thành phố
                                        </label>
                                        @error('city_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                
                                <div class="col-12">
                                    <div class="floating-input">
                                        <textarea name="address" 
                                                  id="address" 
                                                  rows="3"
                                                  class="floating-input-field @error('address') is-invalid @enderror" 
                                                  placeholder=" ">{{ old('address', $cinema->address) }}</textarea>
                                        <label for="address" class="floating-input-label">
                                            <i class="bi bi-geo-alt me-2"></i>Địa chỉ chi tiết
                                        </label>
                                        @error('address')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Contact Information -->
                <div class="form-section mb-4">
                    <div class="section-card">
                        <div class="section-header">
                            <div class="section-icon bg-success">
                                <i class="bi bi-telephone"></i>
                            </div>
                            <div>
                                <h5 class="section-title">Thông tin liên hệ</h5>
                                <p class="section-subtitle">Cập nhật các phương thức liên hệ</p>
                            </div>
                        </div>
                        
                        <div class="section-body">
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <div class="floating-input">
                                        <input type="email" 
                                               name="email" 
                                               id="email" 
                                               class="floating-input-field @error('email') is-invalid @enderror" 
                                               value="{{ old('email', $cinema->email) }}" 
                                               placeholder=" ">
                                        <label for="email" class="floating-input-label">
                                            <i class="bi bi-envelope me-2"></i>Email liên hệ
                                        </label>
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="floating-input">
                                        <input type="text" 
                                               name="hotline" 
                                               id="hotline" 
                                               class="floating-input-field @error('hotline') is-invalid @enderror" 
                                               value="{{ old('hotline', $cinema->hotline) }}" 
                                               placeholder=" ">
                                        <label for="hotline" class="floating-input-label">
                                            <i class="bi bi-telephone me-2"></i>Số điện thoại
                                        </label>
                                        @error('hotline')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Media & Settings -->
                <div class="form-section mb-4">
                    <div class="section-card">
                        <div class="section-header">
                            <div class="section-icon bg-info">
                                <i class="bi bi-image"></i>
                            </div>
                            <div>
                                <h5 class="section-title">Media & Cấu hình</h5>
                                <p class="section-subtitle">Cập nhật ảnh và cấu hình hoạt động</p>
                            </div>
                        </div>
                        
                        <div class="section-body">
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <div class="upload-area">
                                        <!-- Current Image Display -->
                                        @if($cinema->image_url)
                                            <div class="current-image mb-3">
                                                <img src="{{ asset('assets/' . $cinema->image_url) }}" 
                                                     alt="{{ $cinema->name }}" 
                                                     class="current-image-preview">
                                                <div class="current-image-overlay">
                                                    <span class="current-image-label">Ảnh hiện tại</span>
                                                </div>
                                            </div>
                                        @endif
                                        
                                        <input type="file" 
                                               name="image" 
                                               id="image" 
                                               class="upload-input @error('image') is-invalid @enderror"
                                               accept="image/*">
                                        <label for="image" class="upload-label">
                                            <div class="upload-icon">
                                                <i class="bi bi-cloud-upload"></i>
                                            </div>
                                            <div class="upload-text">
                                                <span class="upload-title">{{ $cinema->image_url ? 'Thay đổi ảnh' : 'Tải ảnh rạp chiếu' }}</span>
                                                <span class="upload-subtitle">Kéo thả hoặc click để chọn file</span>
                                            </div>
                                        </label>
                                        @error('image')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="row g-3">
                                        <div class="col-12">
                                            <div class="floating-input">
                                                <input type="text" 
                                                       name="opening_hours" 
                                                       id="opening_hours" 
                                                       class="floating-input-field @error('opening_hours') is-invalid @enderror" 
                                                       value="{{ old('opening_hours', $cinema->opening_hours) }}" 
                                                       placeholder=" ">
                                                <label for="opening_hours" class="floating-input-label">
                                                    <i class="bi bi-clock me-2"></i>Giờ hoạt động
                                                </label>
                                                @error('opening_hours')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        
                                        <div class="col-12">
                                            <div class="floating-input">
                                                <select name="status" 
                                                        id="status" 
                                                        class="floating-input-field @error('status') is-invalid @enderror">
                                                    <option value="active" {{ old('status', $cinema->status) == 'active' ? 'selected' : '' }}>Hoạt động</option>
                                                    <option value="inactive" {{ old('status', $cinema->status) == 'inactive' ? 'selected' : '' }}>Tạm dừng</option>
                                                </select>
                                                <label for="status" class="floating-input-label">
                                                    <i class="bi bi-toggle-on me-2"></i>Trạng thái
                                                </label>
                                                @error('status')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-12">
                                    <div class="floating-input">
                                        <input type="url" 
                                               name="map_url" 
                                               id="map_url" 
                                               class="floating-input-field @error('map_url') is-invalid @enderror" 
                                               value="{{ old('map_url', $cinema->map_url) }}" 
                                               placeholder=" ">
                                        <label for="map_url" class="floating-input-label">
                                            <i class="bi bi-map me-2"></i>URL Google Maps
                                        </label>
                                        @error('map_url')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                
                                <div class="col-12">
                                    <div class="floating-input">
                                        <textarea name="description" 
                                                  id="description" 
                                                  rows="4"
                                                  class="floating-input-field @error('description') is-invalid @enderror" 
                                                  placeholder=" ">{{ old('description', $cinema->description) }}</textarea>
                                        <label for="description" class="floating-input-label">
                                            <i class="bi bi-card-text me-2"></i>Mô tả rạp chiếu
                                        </label>
                                        @error('description')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="form-section">
                    <div class="action-card">
                        <div class="d-flex justify-content-end gap-3">
                            <a href="{{ route('admin.cinemas.index') }}" class="btn btn-outline-secondary btn-lg rounded-pill px-4">
                                <i class="bi bi-arrow-left me-2"></i>Quay lại
                            </a>
                            <button type="submit" class="btn btn-warning btn-lg rounded-pill px-4">
                                <i class="bi bi-save me-2"></i>Cập nhật
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
:root {
    --purple-primary: #8b5cf6;
    --purple-secondary: #a78bfa;
    --purple-light: #c4b5fd;
    --purple-dark: #7c3aed;
    --warning-primary: #f59e0b;
}

/* Header */
.edit-header {
    background: linear-gradient(135deg, var(--warning-primary) 0%, #d97706 100%);
    position: relative;
    overflow: hidden;
}

.edit-header::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><circle cx="50" cy="50" r="2" fill="rgba(255,255,255,0.1)"/></svg>') repeat;
    opacity: 0.3;
}

.floating-icon {
    width: 80px;
    height: 80px;
    background: rgba(255, 255, 255, 0.15);
    border-radius: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2rem;
    color: white;
    animation: float 3s ease-in-out infinite;
}

@keyframes float {
    0%, 100% { transform: translateY(0px); }
    50% { transform: translateY(-10px); }
}

/* Status Card */
.status-card {
    background: linear-gradient(135deg, #fff7ed 0%, #fed7aa 100%);
    border-radius: 1.5rem;
    box-shadow: 0 4px 20px rgba(245, 158, 11, 0.1);
    border: 1px solid rgba(245, 158, 11, 0.2);
    overflow: hidden;
}

.status-header {
    padding: 1.5rem 2rem 1rem;
    display: flex;
    align-items: center;
    gap: 1rem;
}

.status-icon {
    width: 3rem;
    height: 3rem;
    border-radius: 0.75rem;
    background: var(--warning-primary);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.25rem;
}

.status-title {
    font-size: 1.125rem;
    font-weight: 700;
    color: #92400e;
    margin-bottom: 0.25rem;
}

.status-subtitle {
    font-size: 0.875rem;
    color: #b45309;
    margin-bottom: 0;
}

.status-body {
    padding: 0 2rem 1.5rem;
}

.status-item {
    text-align: center;
}

.status-label {
    font-size: 0.75rem;
    color: #b45309;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    margin-bottom: 0.5rem;
}

.status-value {
    font-size: 0.875rem;
    font-weight: 600;
    color: #92400e;
}

/* Current Image */
.current-image {
    position: relative;
    border-radius: 1rem;
    overflow: hidden;
    margin-bottom: 1rem;
}

.current-image-preview {
    width: 100%;
    height: 120px;
    object-fit: cover;
    border-radius: 1rem;
}

.current-image-overlay {
    position: absolute;
    top: 0.5rem;
    left: 0.5rem;
    background: rgba(0, 0, 0, 0.7);
    color: white;
    padding: 0.25rem 0.75rem;
    border-radius: 1rem;
    font-size: 0.75rem;
    font-weight: 600;
}

/* Form Components - Reuse from add.blade.php */
.form-section {
    margin-bottom: 2rem;
}

.section-card {
    background: white;
    border-radius: 1.5rem;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    border: 1px solid rgba(245, 158, 11, 0.1);
    overflow: hidden;
    transition: all 0.3s ease;
}

.section-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 30px rgba(245, 158, 11, 0.15);
}

.section-header {
    padding: 1.5rem 2rem;
    background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
    border-bottom: 1px solid #e2e8f0;
    display: flex;
    align-items: center;
    gap: 1rem;
}

.section-icon {
    width: 3rem;
    height: 3rem;
    border-radius: 0.75rem;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.25rem;
}

.section-icon.bg-warning {
    background: linear-gradient(135deg, var(--warning-primary), #d97706);
}

.section-title {
    font-size: 1.125rem;
    font-weight: 700;
    color: #1f2937;
    margin-bottom: 0.25rem;
}

.section-subtitle {
    font-size: 0.875rem;
    color: #6b7280;
    margin-bottom: 0;
}

.section-body {
    padding: 2rem;
}

/* Floating Inputs */
.floating-input {
    position: relative;
    margin-bottom: 1rem;
}

.floating-input-field {
    width: 100%;
    padding: 1.25rem 1rem 0.75rem;
    border: 2px solid #e5e7eb;
    border-radius: 1rem;
    font-size: 1rem;
    background: white;
    transition: all 0.3s ease;
    resize: vertical;
}

.floating-input-field:focus {
    outline: none;
    border-color: var(--warning-primary);
    box-shadow: 0 0 0 3px rgba(245, 158, 11, 0.1);
}

.floating-input-field:focus + .floating-input-label,
.floating-input-field:not(:placeholder-shown) + .floating-input-label {
    transform: translateY(-0.75rem) scale(0.85);
    color: var(--warning-primary);
}

.floating-input-label {
    position: absolute;
    left: 1rem;
    top: 1.25rem;
    color: #6b7280;
    font-size: 1rem;
    pointer-events: none;
    transition: all 0.3s ease;
    transform-origin: left top;
    background: white;
    padding: 0 0.25rem;
}

/* Upload Area */
.upload-area {
    position: relative;
}

.upload-input {
    display: none;
}

.upload-label {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    height: 120px;
    border: 2px dashed #d1d5db;
    border-radius: 1rem;
    background: #f9fafb;
    cursor: pointer;
    transition: all 0.3s ease;
}

.upload-label:hover {
    border-color: var(--warning-primary);
    background: rgba(245, 158, 11, 0.05);
}

.upload-icon {
    font-size: 1.5rem;
    color: var(--warning-primary);
    margin-bottom: 0.5rem;
}

.upload-title {
    font-size: 0.875rem;
    font-weight: 600;
    color: #374151;
    display: block;
    margin-bottom: 0.25rem;
}

.upload-subtitle {
    font-size: 0.75rem;
    color: #6b7280;
}

/* Action Card */
.action-card {
    background: white;
    border-radius: 1.5rem;
    padding: 2rem;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    border: 1px solid rgba(245, 158, 11, 0.1);
}

/* Buttons */
.btn-warning {
    background: linear-gradient(135deg, var(--warning-primary), #d97706);
    border: none;
    color: white;
    font-weight: 600;
    transition: all 0.3s ease;
}

.btn-warning:hover {
    background: linear-gradient(135deg, #d97706, var(--warning-primary));
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(245, 158, 11, 0.3);
    color: white;
}

/* Badge Colors */
.bg-success-subtle {
    background-color: rgba(34, 197, 94, 0.1) !important;
}

.bg-warning-subtle {
    background-color: rgba(245, 158, 11, 0.1) !important;
}

/* Responsive */
@media (max-width: 768px) {
    .edit-header {
        padding: 2rem !important;
    }
    
    .header-content h1 {
        font-size: 1.75rem;
    }
    
    .status-header {
        padding: 1rem 1.5rem 0.5rem;
        flex-direction: column;
        text-align: center;
        gap: 0.75rem;
    }
    
    .status-body {
        padding: 0 1.5rem 1rem;
    }
    
    .section-header {
        padding: 1rem 1.5rem;
        flex-direction: column;
        text-align: center;
        gap: 0.75rem;
    }
    
    .section-body {
        padding: 1.5rem;
    }
    
    .action-card {
        padding: 1.5rem;
    }
    
    .action-card .d-flex {
        flex-direction: column;
        gap: 1rem;
    }
}
</style>
@endsection