@extends('layouts.admin.admin')

@section('content')
<div class="container-xxl">
    <div class="row">
        <div class="col-xl-3 col-lg-4">
            <div class="card">
                <div class="card-body">
                    <div class="mt-3">
                        <h4>Phòng Chiếu Mới</h4>
                        <div class="row">
                            <div class="col-lg-6 col-6">
                                <p class="mb-1 mt-2">Rạp Chiếu:</p>
                                <h5 class="mb-0">{{ $cinemas->count() }} rạp</h5>
                            </div>
                            <div class="col-lg-6 col-6">
                                <p class="mb-1 mt-2">Loại Phòng:</p>
                                <h5 class="mb-0">{{ $roomTypes->count() }} loại</h5>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer border-top">
                    <div class="row g-2">
                        <div class="col-lg-6">
                            <button type="submit" form="room-create-form" class="btn btn-outline-secondary w-100">Tạo Phòng</button>
                        </div>
                        <div class="col-lg-6">
                            <a href="{{ route('admin.rooms.index') }}" class="btn btn-primary w-100">Hủy</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-9 col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Thông Tin Chung</h4>
                </div>
                <div class="card-body">
                    <form id="room-create-form" action="{{ route('admin.rooms.store') }}" method="post">
                        @csrf
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="cinema_id" class="form-label">Rạp Chiếu</label>
                                    <select class="form-control" id="cinema_id" name="cinema_id">
                                        <option value="">Chọn Rạp Chiếu</option>
                                        @foreach ($cinemas as $cinema)
                                            <option value="{{ $cinema->id }}">{{ $cinema->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('cinema_id')
                                        <span class="text-danger">{{ $message }}</span>
                                    @endError
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="room_type_id" class="form-label">Loại Phòng</label>
                                    <select class="form-control" id="room_type_id" name="room_type_id">
                                        <option value="">Chọn Loại Phòng</option>
                                        @foreach ($roomTypes as $roomType)
                                            <option value="{{ $roomType->id }}">{{ $roomType->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('room_type_id')
                                        <span class="text-danger">{{ $message }}</span>
                                    @endError
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Tên Phòng</label>
                                    <input type="text" id="name" name="name" class="form-control" placeholder="Nhập tên phòng">
                                    @error('name')
                                        <span class="text-danger">{{ $message }}</span>
                                    @endError
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="capacity" class="form-label">Sức Chứa</label>
                                    <input type="number" id="capacity" name="capacity" class="form-control" placeholder="Nhập sức chứa">
                                    @error('capacity')
                                        <span class="text-danger">{{ $message }}</span>
                                    @endError
                                    @if(session('capacity_suggestions'))
                                        <div class="mt-2">
                                            <small class="text-muted">💡 Đề xuất sức chứa phù hợp:</small>
                                            <div class="mt-1">
                                                @foreach(session('capacity_suggestions') as $suggestion)
                                                    <button type="button" class="btn btn-sm btn-outline-primary me-1 mb-1" onclick="setCapacity({{ $suggestion }})">
                                                        {{ $suggestion }} ghế
                                                    </button>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
                                    <div id="capacityHelp" class="mt-2" style="display: none;">
                                        <small class="text-info">
                                            <i class="fas fa-info-circle"></i> 
                                            <span id="capacityHelpText"></span>
                                        </small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="status" class="form-label">Trạng Thái</label>
                                    <select class="form-control" id="status" name="status">
                                        <option value="active">Hoạt động</option>
                                        <option value="maintenance">Bảo trì</option>
                                        <option  value="full" disabled>Đã đầy</option>
                                    </select>
                                    @error('status')
                                        <span class="text-danger">{{ $message }}</span>
                                    @endError
                                </div>
                            </div>
                            
                            <div class="col-lg-12">
                                <div class="mb-0">
                                    <label for="description" class="form-label">Mô Tả</label>
                                    <textarea class="form-control bg-light-subtle" id="description" name="description" rows="7" placeholder="Nhập mô tả phòng"></textarea>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            
        </div>
    </div>
</div>

@php
$roomTypesWithCoupleSeats = $roomTypes->mapWithKeys(function($rt) {
    $coupleSeats = collect();
    
    // Lấy seat types thông qua constraints
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

<script>
// Dữ liệu room types với seat types từ server
const roomTypesData = @json($roomTypesWithCoupleSeats);

// Debug: In ra dữ liệu để kiểm tra
console.log('Room Types Data:', roomTypesData);

function setCapacity(capacity) {
    document.getElementById('capacity').value = capacity;
    validateCapacity();
}

// Validate capacity on room type change
document.getElementById('room_type_id').addEventListener('change', function() {
    const roomTypeId = this.value;
    const capacityInput = document.getElementById('capacity');
    const capacityHelp = document.getElementById('capacityHelp');
    const helpText = document.getElementById('capacityHelpText');
    
    if (roomTypeId && capacityInput.value) {
        checkCapacityForRoomType(roomTypeId, capacityInput.value);
    } else {
        capacityHelp.style.display = 'none';
    }
});

// Validate capacity on input change
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
    
    // Lấy thông tin room type từ dữ liệu server
    const roomType = roomTypesData[roomTypeId];
    
    if (roomType && roomType.has_couple_seats) {
        const capacityNum = parseInt(capacity);
        const coupleSeatsStr = roomType.couple_seats.join(', ');
        
        if (capacityNum % 2 !== 0) {
            helpText.innerHTML = `⚠️ Phòng "${roomType.name}" có ghế đôi (${coupleSeatsStr}), sức chứa phải là số chẵn. Đề xuất: ${capacityNum + 1} ghế.`;
            capacityHelp.className = 'mt-2 alert alert-warning py-2';
            helpText.className = 'text-warning fw-bold';
        } else {
            helpText.innerHTML = `✓ Sức chứa phù hợp cho phòng có ghế đôi (${coupleSeatsStr}).`;
            capacityHelp.className = 'mt-2 alert alert-success py-2';
            helpText.className = 'text-success fw-bold';
        }
        capacityHelp.style.display = 'block';
    } else {
        capacityHelp.style.display = 'none';
    }
}

// Prevent form submission if there are validation errors
document.getElementById('room-create-form').addEventListener('submit', function(e) {
    const roomTypeId = document.getElementById('room_type_id').value;
    const capacity = document.getElementById('capacity').value;
    
    if (roomTypeId && capacity) {
        const roomType = roomTypesData[roomTypeId];
        
        if (roomType && roomType.has_couple_seats && parseInt(capacity) % 2 !== 0) {
            e.preventDefault();
            e.stopPropagation();
            
            alert(`❌ Không thể tạo phòng!\n\nPhòng "${roomType.name}" có chứa ghế đôi (${roomType.couple_seats.join(', ')}) nên sức chứa phải là số chẵn.\n\nVui lòng đổi sức chứa thành ${parseInt(capacity) + 1} ghế.`);
            
            // Focus vào input capacity
            document.getElementById('capacity').focus();
            document.getElementById('capacity').select();
            
            return false;
        }
    }
    
    // Additional validation: Check required fields
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
