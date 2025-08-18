@extends('layouts.admin.admin')

@section('content')
<div class="container-xxl">
    <!-- Header Section -->
    <div class="row mb-5">
        <div class="col-12">
            <div class="edit-header rounded-5 p-5">
                <div class="d-flex align-items-center justify-content-between">
                    <div class="header-content">
                        <h1 class="display-6 fw-bold text-white mb-3">
                            <i class="bi bi-pencil-square me-3"></i>
                            Chỉnh sửa phòng chiếu
                        </h1>
                        <p class="lead text-white-50 mb-0">Cập nhật thông tin phòng: {{ $room->name }}</p>
                    </div>
                    <div class="header-icon d-none d-lg-block">
                        <div class="floating-icon">
                            <i class="bi bi-door-open"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Room Status Card -->
        <div class="col-xl-3 col-lg-4">
            <div class="status-card mb-4">
                <div class="status-header">
                    <div class="status-icon">
                        <i class="bi bi-info-circle"></i>
                    </div>
                    <div>
                        <h5 class="status-title">Trạng thái phòng</h5>
                        <p class="status-subtitle">Thông tin hiện tại</p>
                    </div>
                </div>
                
                <div class="status-body">
                    <div class="status-item">
                        <div class="status-item-label">
                            <i class="bi bi-building text-orange"></i>
                            <span>Rạp chiếu</span>
                        </div>
                        <div class="status-item-value">{{ $room->cinema->name }}</div>
                    </div>
                    
                    <div class="status-item">
                        <div class="status-item-label">
                            <i class="bi bi-grid-3x3 text-info"></i>
                            <span>Loại phòng</span>
                        </div>
                        <div class="status-item-value">{{ $room->roomType->name }}</div>
                    </div>
                    
                    <div class="status-item">
                        <div class="status-item-label">
                            <i class="bi bi-people text-success"></i>
                            <span>Sức chứa</span>
                        </div>
                        <div class="status-item-value">{{ $room->capacity }} ghế</div>
                    </div>
                    
                    <div class="status-item">
                        <div class="status-item-label">
                            <i class="bi bi-toggles text-warning"></i>
                            <span>Trạng thái</span>
                        </div>
                        <div class="status-item-value">
                            @if($room->status === 'active')
                                <span class="badge bg-success">Hoạt động</span>
                            @elseif($room->status === 'maintenance')
                                <span class="badge bg-warning">Bảo trì</span>
                            @else
                                <span class="badge bg-secondary">Không hoạt động</span>
                            @endif
                        </div>
                    </div>
                    
                    <div class="status-item">
                        <div class="status-item-label">
                            <i class="bi bi-calendar-plus text-primary"></i>
                            <span>Ngày tạo</span>
                        </div>
                        <div class="status-item-value">{{ $room->created_at->format('d/m/Y H:i') }}</div>
                    </div>
                    
                    <div class="status-item">
                        <div class="status-item-label">
                            <i class="bi bi-clock-history text-secondary"></i>
                            <span>Cập nhật</span>
                        </div>
                        <div class="status-item-value">{{ $room->updated_at->format('d/m/Y H:i') }}</div>
                    </div>
                </div>
            </div>
            
            <!-- Quick Actions -->
            <div class="quick-actions-card">
                <div class="quick-actions-header">
                    <h6 class="quick-actions-title">
                        <i class="bi bi-lightning-charge me-2"></i>Thao tác nhanh
                    </h6>
                </div>
                <div class="quick-actions-body">
                    <a href="{{ route('admin.rooms.show', $room->id) }}" class="quick-action-btn">
                        <i class="bi bi-eye"></i>
                        <span>Xem chi tiết</span>
                    </a>
                    <a href="{{ route('admin.rooms.index') }}" class="quick-action-btn">
                        <i class="bi bi-list"></i>
                        <span>Danh sách phòng</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Main Form -->
        <div class="col-xl-9 col-lg-8">
            <form action="{{ route('admin.rooms.update', $room->id) }}" method="POST">
                @csrf
                @method('PUT')
                
                <!-- Basic Information -->
                <div class="form-section mb-4">
                    <div class="section-card">
                        <div class="section-header">
                            <div class="section-icon bg-orange">
                                <i class="bi bi-pencil-square"></i>
                            </div>
                            <div>
                                <h5 class="section-title">Cập nhật thông tin</h5>
                                <p class="section-subtitle">Chỉnh sửa thông tin cơ bản của phòng chiếu</p>
                            </div>
                        </div>
                        
                        <div class="section-body">
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <div class="floating-input">
                                        <select name="cinema_id" id="cinema_id" class="floating-input-field @error('cinema_id') is-invalid @enderror" required>
                                            <option value="">-- Chọn rạp chiếu --</option>
                                            @foreach ($cinemas as $cinema)
                                                <option value="{{ $cinema->id }}" {{ old('cinema_id', $room->cinema_id) == $cinema->id ? 'selected' : '' }}>
                                                    {{ $cinema->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <label for="cinema_id" class="floating-input-label">
                                            <i class="bi bi-building me-2"></i>Rạp chiếu
                                        </label>
                                        @error('cinema_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="floating-input">
                                        <select name="room_type_id" id="room_type_id" class="floating-input-field @error('room_type_id') is-invalid @enderror" required>
                                            <option value="">-- Chọn loại phòng --</option>
                                            @foreach ($roomTypes as $roomType)
                                                <option value="{{ $roomType->id }}" {{ old('room_type_id', $room->room_type_id) == $roomType->id ? 'selected' : '' }}>
                                                    {{ $roomType->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <label for="room_type_id" class="floating-input-label">
                                            <i class="bi bi-grid-3x3 me-2"></i>Loại phòng
                                        </label>
                                        @error('room_type_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="floating-input">
                                        <input type="text" 
                                               name="name" 
                                               id="name" 
                                               class="floating-input-field @error('name') is-invalid @enderror" 
                                               value="{{ old('name', $room->name) }}" 
                                               placeholder=" "
                                               required>
                                        <label for="name" class="floating-input-label">
                                            <i class="bi bi-door-open me-2"></i>Tên phòng
                                        </label>
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="floating-input">
                                        <input type="number" 
                                               name="capacity" 
                                               id="capacity" 
                                               class="floating-input-field @error('capacity') is-invalid @enderror" 
                                               value="{{ old('capacity', $room->capacity) }}" 
                                               placeholder=" "
                                               min="1"
                                               required>
                                        <label for="capacity" class="floating-input-label">
                                            <i class="bi bi-people me-2"></i>Sức chứa
                                        </label>
                                        @error('capacity')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="floating-input">
                                        <select name="status" id="status" class="floating-input-field @error('status') is-invalid @enderror">
                                            <option value="active" {{ old('status', $room->status) == 'active' ? 'selected' : '' }}>Hoạt động</option>
                                            <option value="maintenance" {{ old('status', $room->status) == 'maintenance' ? 'selected' : '' }}>Bảo trì</option>
                                            <option value="full" {{ old('status', $room->status) == 'full' ? 'selected' : '' }}>Đã đầy</option>
                                        </select>
                                        <label for="status" class="floating-input-label">
                                            <i class="bi bi-toggle-on me-2"></i>Trạng thái
                                        </label>
                                        @error('status')
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
                                                  placeholder=" ">{{ old('description', $room->description) }}</textarea>
                                        <label for="description" class="floating-input-label">
                                            <i class="bi bi-card-text me-2"></i>Mô tả phòng chiếu
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
                            <a href="{{ route('admin.rooms.index') }}" class="btn btn-outline-secondary btn-lg rounded-pill px-4">
                                <i class="bi bi-arrow-left me-2"></i>Quay lại
                            </a>
                            <button type="submit" class="btn btn-orange btn-lg rounded-pill px-4">
                                <i class="bi bi-save me-2"></i>Cập nhật phòng
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
    --orange-primary: #f86c2c;
    --orange-secondary: #ff8547;
    --orange-light: #ffab7a;
    --orange-dark: #e55a1f;
}

/* Header */
.edit-header {
    background: linear-gradient(135deg, var(--orange-primary) 0%, var(--orange-dark) 100%);
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
    background: white;
    border-radius: 1.5rem;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    border: 1px solid rgba(248, 108, 44, 0.1);
    overflow: hidden;
}

.status-header {
    padding: 1.5rem;
    background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
    border-bottom: 1px solid #e2e8f0;
    display: flex;
    align-items: center;
    gap: 1rem;
}

.status-icon {
    width: 2.5rem;
    height: 2.5rem;
    border-radius: 0.5rem;
    background: linear-gradient(135deg, var(--orange-primary), var(--orange-dark));
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1rem;
}

.status-title {
    font-size: 1rem;
    font-weight: 700;
    color: #1f2937;
    margin-bottom: 0.25rem;
}

.status-subtitle {
    font-size: 0.875rem;
    color: #6b7280;
    margin-bottom: 0;
}

.status-body {
    padding: 1.5rem;
}

.status-item {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
    margin-bottom: 1.25rem;
}

.status-item:last-child {
    margin-bottom: 0;
}

.status-item-label {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.875rem;
    color: #6b7280;
    font-weight: 500;
}

.status-item-value {
    font-size: 0.95rem;
    color: #1f2937;
    font-weight: 600;
    margin-left: 1.5rem;
}

/* Quick Actions */
.quick-actions-card {
    background: white;
    border-radius: 1.5rem;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    border: 1px solid rgba(248, 108, 44, 0.1);
    overflow: hidden;
}

.quick-actions-header {
    padding: 1rem 1.5rem;
    background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
    border-bottom: 1px solid #e2e8f0;
}

.quick-actions-title {
    font-size: 0.95rem;
    font-weight: 700;
    color: #1f2937;
    margin-bottom: 0;
}

.quick-actions-body {
    padding: 1rem;
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.quick-action-btn {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 0.75rem 1rem;
    border-radius: 0.75rem;
    color: #6b7280;
    text-decoration: none;
    transition: all 0.2s ease;
    font-size: 0.875rem;
    font-weight: 500;
}

.quick-action-btn:hover {
    background: linear-gradient(135deg, var(--orange-primary), var(--orange-dark));
    color: white;
    transform: translateX(4px);
}

/* Form Sections */
.form-section {
    margin-bottom: 2rem;
}

.section-card {
    background: white;
    border-radius: 1.5rem;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    border: 1px solid rgba(248, 108, 44, 0.1);
    overflow: hidden;
    transition: all 0.3s ease;
}

.section-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 30px rgba(248, 108, 44, 0.15);
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

.section-icon.bg-orange {
    background: linear-gradient(135deg, var(--orange-primary), var(--orange-dark));
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
    border-color: var(--orange-primary);
    box-shadow: 0 0 0 3px rgba(248, 108, 44, 0.1);
}

.floating-input-field:focus + .floating-input-label,
.floating-input-field:not(:placeholder-shown) + .floating-input-label,
.floating-input-field:valid + .floating-input-label {
    transform: translateY(-0.75rem) scale(0.85);
    color: var(--orange-primary);
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

/* Action Card */
.action-card {
    background: white;
    border-radius: 1.5rem;
    padding: 2rem;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    border: 1px solid rgba(248, 108, 44, 0.1);
}

/* Buttons */
.btn-orange {
    background: linear-gradient(135deg, var(--orange-primary), var(--orange-dark));
    border: none;
    color: white;
    font-weight: 600;
    transition: all 0.3s ease;
}

.btn-orange:hover {
    background: linear-gradient(135deg, var(--orange-dark), var(--orange-primary));
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(248, 108, 44, 0.3);
    color: white;
}

/* Responsive */
@media (max-width: 768px) {
    .edit-header {
        padding: 2rem !important;
    }
    
    .header-content h1 {
        font-size: 1.75rem;
    }
    
    .status-body {
        padding: 1rem;
    }
    
    .quick-actions-body {
        padding: 0.75rem;
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