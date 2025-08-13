@extends('layouts.admin.admin')

@section('content')
<div class="container-fluid px-4 py-4">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 bg-gradient-primary rounded-4 shadow-lg overflow-hidden position-relative">
                <div class="card-body p-5">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center gap-4">
                            <div class="avatar-xl rounded-circle bg-white bg-opacity-20 backdrop-blur">
                                <span class="avatar-title rounded-circle text-white">
                                    <i class="bi bi-chair fs-2"></i>
                                </span>
                            </div>
                            <div class="text-white">
                                <h2 class="fw-bold mb-2">Chỉnh sửa ghế</h2>
                                <p class="mb-0 opacity-90 fs-5">
                                    Ghế <span class="fw-semibold">{{ $seat->row_char . $seat->seat_number }}</span> - Phòng <span class="fw-semibold">{{ $seat->room->name }}</span>
                                </p>
                            </div>
                        </div>
                        <div class="d-none d-lg-block">
                            <span class="badge bg-white text-primary rounded-pill px-4 py-3 fs-6 fw-semibold shadow">
                                <i class="bi bi-pencil-square me-2"></i>
                                Chỉnh sửa
                            </span>
                        </div>
                    </div>
                </div>
                <div class="position-absolute top-0 end-0 opacity-10">
                    <i class="bi bi-chair" style="font-size: 12rem;"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row justify-content-center">
        <div class="col-xl-8 col-lg-10">
            <form method="POST" action="{{ route('admin.seats.update', $seat->id) }}">
                @csrf
                @method('PUT')
                
                <!-- Thông tin ghế -->
                <div class="card border-0 shadow-lg rounded-4 mb-4">
                    <div class="card-header bg-primary-subtle border-0 py-4">
                        <h5 class="mb-0 fw-bold text-dark d-flex align-items-center gap-2">
                            <i class="bi bi-chair text-primary"></i>
                            Thông tin ghế
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-4">
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="text" 
                                           class="form-control" 
                                           value="{{ $seat->room->name }}" 
                                           id="room_name" 
                                           placeholder="Phòng chiếu" 
                                           readonly disabled>
                                    <label for="room_name">
                                        <i class="bi bi-film me-2"></i>Phòng chiếu
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="text" 
                                           class="form-control" 
                                           value="{{ $seat->seatType->name }}" 
                                           id="seat_type" 
                                           placeholder="Loại ghế" 
                                           readonly disabled>
                                    <label for="seat_type">
                                        <i class="bi bi-star me-2"></i>Loại ghế
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="row g-4 mt-2">
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="text" 
                                           class="form-control" 
                                           value="{{ $seat->row_char }}" 
                                           id="row_char" 
                                           placeholder="Hàng ghế" 
                                           readonly disabled>
                                    <label for="row_char">
                                        <i class="bi bi-view-stacked me-2"></i>Hàng ghế
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="text" 
                                           class="form-control" 
                                           value="{{ $seat->seat_number }}" 
                                           id="seat_number" 
                                           placeholder="Số ghế" 
                                           readonly disabled>
                                    <label for="seat_number">
                                        <i class="bi bi-hash me-2"></i>Số ghế
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="row g-4 mt-2">
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <select name="status" id="status" class="form-select @error('status') is-invalid @enderror" required>
                                        @foreach (\App\Enums\SeatStatus::cases() as $status)
                                            <option value="{{ $status->value }}" {{ $seat->status->value === $status->value ? 'selected' : '' }}>
                                                {{ ucfirst($status->value) }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <label for="status">
                                        <i class="bi bi-info-circle me-2"></i>Trạng thái <span class="text-danger">*</span>
                                    </label>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row g-4 mt-2">
                            <div class="col-12">
                                <div class="alert alert-primary border-0 rounded-4">
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-exclamation-triangle fs-4 me-3 text-primary"></i>
                                        <div>
                                            <h6 class="mb-1 fw-bold">Thông tin hiện tại:</h6>
                                            <p class="mb-0 small">
                                                • Được tạo: {{ $seat->created_at->format('d/m/Y H:i') }}<br>
                                                • Cập nhật lần cuối: {{ $seat->updated_at->format('d/m/Y H:i') }}<br>
                                                • Phòng: {{ $seat->room->name }}<br>
                                                • Loại ghế: {{ $seat->seatType->name }}
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
                            <a href="{{ route('admin.seats.index') }}" class="btn btn-outline-secondary btn-lg rounded-pill">
                                <i class="bi bi-arrow-left me-2"></i>Quay lại
                            </a>
                            <button type="submit" class="btn btn-primary btn-lg rounded-pill">
                                <i class="bi bi-save me-2"></i>Lưu thay đổi
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
.bg-gradient-primary {
    background: linear-gradient(135deg, #2196f3 0%, #1565c0 100%);
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
    border-color: var(--bs-primary);
    box-shadow: 0 0 0 3px rgba(var(--bs-primary-rgb), 0.1);
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
.bg-primary-subtle {
    background-color: rgba(var(--bs-primary-rgb), 0.1) !important;
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