@extends('layouts.admin.admin')

@section('content')
<div class="container-fluid px-4 py-4">
    <!-- Header Section -->
    <div class="row mb-5">
        <div class="col-12">
            <div class="create-header rounded-5 p-5">
                <div class="d-flex align-items-center justify-content-between">
                    <div class="header-content">
                        <h1 class="display-6 fw-bold text-white mb-3">
                            <i class="bi bi-plus-square me-3"></i>
                            Thêm phòng chiếu mới
                        </h1>
                        <p class="lead text-white-50 mb-0">Tạo và cấu hình phòng chiếu mới trong hệ thống</p>
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
    
    <div class="row justify-content-center">
        <div class="col-xl-10">
            <form id="room-create-form" action="{{ route('admin.rooms.store') }}" method="post">
                @csrf
                
                <!-- Basic Information -->
                <div class="form-section mb-4">
                    <div class="section-card">
                        <div class="section-header">
                            <div class="section-icon bg-orange">
                                <i class="bi bi-info-circle"></i>
                            </div>
                            <div>
                                <h5 class="section-title">Thông tin cơ bản</h5>
                                <p class="section-subtitle">Cấu hình thông tin chính của phòng chiếu</p>
                            </div>
                            <div class="section-stats ms-auto">
                                <div class="stats-item">
                                    <span class="stats-label">Rạp chiếu:</span>
                                    <span class="stats-value">{{ $cinemas->count() }} rạp</span>
                                </div>
                                <div class="stats-item">
                                    <span class="stats-label">Loại phòng:</span>
                                    <span class="stats-value">{{ $roomTypes->count() }} loại</span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="section-body">
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <div class="floating-input">
                                        <select name="cinema_id" id="cinema_id" class="floating-input-field @error('cinema_id') is-invalid @enderror" required>
                                            <option value="">-- Chọn rạp chiếu --</option>
                                            @foreach ($cinemas as $cinema)
                                                <option value="{{ $cinema->id }}" {{ old('cinema_id') == $cinema->id ? 'selected' : '' }}>
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
                                                <option value="{{ $roomType->id }}" {{ old('room_type_id') == $roomType->id ? 'selected' : '' }}>
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
                                               value="{{ old('name') }}" 
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
                                               value="{{ old('capacity') }}" 
                                               placeholder=" "
                                               min="1"
                                               required>
                                        <label for="capacity" class="floating-input-label">
                                            <i class="bi bi-people me-2"></i>Sức chứa
                                        </label>
                                        @error('capacity')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        
                                        <!-- Capacity Suggestions -->
                                        @if(session('capacity_suggestions'))
                                            <div class="capacity-suggestions mt-3">
                                                <div class="suggestions-header">
                                                    <i class="bi bi-lightbulb text-warning"></i>
                                                    <span class="suggestions-title">Đề xuất sức chứa phù hợp:</span>
                                                </div>
                                                <div class="suggestions-buttons">
                                                    @foreach(session('capacity_suggestions') as $suggestion)
                                                        <button type="button" class="suggestion-btn" onclick="setCapacity({{ $suggestion }})">
                                                            {{ $suggestion }} ghế
                                                        </button>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endif
                                        
                                        <!-- Capacity Help -->
                                        <div id="capacityHelp" class="capacity-help" style="display: none;">
                                            <div class="help-content">
                                                <i class="help-icon bi bi-info-circle"></i>
                                                <span id="capacityHelpText" class="help-text"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="floating-input">
                                        <select name="status" id="status" class="floating-input-field @error('status') is-invalid @enderror">
                                            <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Hoạt động</option>
                                            <option value="maintenance" {{ old('status') == 'maintenance' ? 'selected' : '' }}>Bảo trì</option>
                                            <option value="full" disabled>Đã đầy</option>
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
                                                  placeholder=" ">{{ old('description') }}</textarea>
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
                                <i class="bi bi-save me-2"></i>Tạo phòng chiếu
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@php
$roomTypesWithCoupleSeats = $roomTypes->mapWithKeys(function($rt) {
    $coupleSeats = collect();
    
    $constraints = \App\Models\RoomSeatTypeConstraint::where('room_type_id', $rt->id)
        ->with('seatType')
        ->get();
        
    if ($constraints->isNotEmpty()) {
        $coupleSeats = $constraints->map(function($constraint) {
            return $constraint->seatType;
        })->filter(function($seatType) {
            if (!$seatType) return false;
            $name = strtolower($seatType->name);
            return str_contains($name, 'couple') || str_contains($name, 'sweetbox') || 
                   str_contains($name, 'bed') || str_contains($name, 'sofa');
        });
    }
    
    return [$rt->id => [
        'name' => $rt->name,
        'has_couple_seats' => $coupleSeats->count() > 0,
        'couple_seats' => $coupleSeats->pluck('name')->toArray()
    ]];
});
@endphp

<style>
:root {
    --orange-primary: #f86c2c;
    --orange-secondary: #ff8547;
    --orange-light: #ffab7a;
    --orange-dark: #e55a1f;
}

/* Header */
.create-header {
    background: linear-gradient(135deg, var(--orange-primary) 0%, var(--orange-dark) 100%);
    position: relative;
    overflow: hidden;
}

.create-header::before {
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

.section-stats {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.stats-item {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.875rem;
}

.stats-label {
    color: #6b7280;
}

.stats-value {
    font-weight: 600;
    color: var(--orange-primary);
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

/* Capacity Suggestions */
.capacity-suggestions {
    background: linear-gradient(135deg, #fef3e2, #fde68a);
    border-radius: 1rem;
    padding: 1rem;
    border: 1px solid #f59e0b;
}

.suggestions-header {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    margin-bottom: 0.75rem;
}

.suggestions-title {
    font-size: 0.875rem;
    font-weight: 600;
    color: #92400e;
}

.suggestions-buttons {
    display: flex;
    gap: 0.5rem;
    flex-wrap: wrap;
}

.suggestion-btn {
    background: white;
    border: 1px solid #f59e0b;
    color: #92400e;
    padding: 0.5rem 1rem;
    border-radius: 1rem;
    font-size: 0.875rem;
    font-weight: 500;
    transition: all 0.2s ease;
    cursor: pointer;
}

.suggestion-btn:hover {
    background: #f59e0b;
    color: white;
    transform: translateY(-1px);
}

/* Capacity Help */
.capacity-help {
    margin-top: 0.75rem;
    padding: 1rem;
    border-radius: 1rem;
    border: 1px solid;
}

.capacity-help.alert-warning {
    background: #fef3e2;
    border-color: #f59e0b;
}

.capacity-help.alert-success {
    background: #f0fdf4;
    border-color: #22c55e;
}

.help-content {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.help-icon {
    font-size: 1.1rem;
}

.help-text {
    font-size: 0.875rem;
    font-weight: 600;
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
    .create-header {
        padding: 2rem !important;
    }
    
    .header-content h1 {
        font-size: 1.75rem;
    }
    
    .section-header {
        padding: 1rem 1.5rem;
        flex-direction: column;
        text-align: center;
        gap: 0.75rem;
    }
    
    .section-stats {
        flex-direction: row;
        justify-content: center;
        gap: 1rem;
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

<script>
const roomTypesData = @json($roomTypesWithCoupleSeats);

function setCapacity(capacity) {
    document.getElementById('capacity').value = capacity;
    validateCapacity();
}

document.getElementById('room_type_id').addEventListener('change', function() {
    const roomTypeId = this.value;
    const capacityInput = document.getElementById('capacity');
    
    if (roomTypeId && capacityInput.value) {
        checkCapacityForRoomType(roomTypeId, capacityInput.value);
    } else {
        document.getElementById('capacityHelp').style.display = 'none';
    }
});

document.getElementById('capacity').addEventListener('input', function() {
    validateCapacity();
});

function validateCapacity() {
    const roomTypeId = document.getElementById('room_type_id').value;
    const capacity = document.getElementById('capacity').value;
    
    if (roomTypeId && capacity) {
        checkCapacityForRoomType(roomTypeId, capacity);
    }
}

function checkCapacityForRoomType(roomTypeId, capacity) {
    const capacityHelp = document.getElementById('capacityHelp');
    const helpText = document.getElementById('capacityHelpText');
    
    const roomType = roomTypesData[roomTypeId];
    
    if (roomType && roomType.has_couple_seats) {
        const capacityNum = parseInt(capacity);
        const coupleSeatsStr = roomType.couple_seats.join(', ');
        
        if (capacityNum % 2 !== 0) {
            helpText.innerHTML = `⚠️ Phòng "${roomType.name}" có ghế đôi (${coupleSeatsStr}), sức chứa phải là số chẵn. Đề xuất: ${capacityNum + 1} ghế.`;
            capacityHelp.className = 'capacity-help alert-warning';
            helpText.className = 'help-text text-warning fw-bold';
        } else {
            helpText.innerHTML = `✓ Sức chứa phù hợp cho phòng có ghế đôi (${coupleSeatsStr}).`;
            capacityHelp.className = 'capacity-help alert-success';
            helpText.className = 'help-text text-success fw-bold';
        }
        capacityHelp.style.display = 'block';
    } else {
        capacityHelp.style.display = 'none';
    }
}

document.getElementById('room-create-form').addEventListener('submit', function(e) {
    const roomTypeId = document.getElementById('room_type_id').value;
    const capacity = document.getElementById('capacity').value;
    
    if (roomTypeId && capacity) {
        const roomType = roomTypesData[roomTypeId];
        
        if (roomType && roomType.has_couple_seats && parseInt(capacity) % 2 !== 0) {
            e.preventDefault();
            e.stopPropagation();
            
            alert(`❌ Không thể tạo phòng!\n\nPhòng "${roomType.name}" có chứa ghế đôi (${roomType.couple_seats.join(', ')}) nên sức chứa phải là số chẵn.\n\nVui lòng đổi sức chứa thành ${parseInt(capacity) + 1} ghế.`);
            
            document.getElementById('capacity').focus();
            document.getElementById('capacity').select();
            
            return false;
        }
    }
    
    const requiredFields = ['cinema_id', 'room_type_id', 'name', 'capacity'];
    for (let field of requiredFields) {
        const element = document.getElementById(field);
        if (!element.value || element.value.trim() === '') {
            e.preventDefault();
            e.stopPropagation();
            
            let fieldName = {
                'cinema_id': 'Rạp chiếu',
                'room_type_id': 'Loại phòng', 
                'name': 'Tên phòng',
                'capacity': 'Sức chứa'
            }[field];
            
            alert(`❌ Vui lòng điền ${fieldName}!`);
            element.focus();
            return false;
        }
    }
});
</script>
@endsection
