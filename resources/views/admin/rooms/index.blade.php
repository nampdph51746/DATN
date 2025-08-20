@extends('layouts.admin.admin')

@section('content')
<div class="container-fluid">
    @include('admin.partials.notifications')

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center g-3">
                        <div class="col-md-4">
                            <h4 class="card-title mb-0">🎥 Danh Sách Phòng Chiếu</h4>
                        </div>
                        <div class="col-md-8">
                            <div class="d-flex flex-wrap gap-2 justify-content-md-end">
                                <!-- Form tìm kiếm -->
                                <form class="d-flex align-items-center gap-2" method="GET" action="{{ route('admin.rooms.index') }}">
                                    <div class="position-relative">
                                        <input type="search" name="search" class="form-control form-control-sm ps-4 pe-3" 
                                               style="min-width: 250px;" placeholder="Tìm kiếm tên phòng, rạp chiếu..." 
                                               autocomplete="off" value="{{ request('search') }}">
                                        <iconify-icon icon="solar:magnifer-linear"
                                            class="position-absolute top-50 start-0 translate-middle-y ms-2 text-muted"
                                            style="font-size: 14px;"></iconify-icon>
                                    </div>
                                    
                                    <select name="room_type_id" class="form-select form-select-sm" style="width:160px;">
                                        <option value="">Tất cả loại phòng</option>
                                        @foreach ($roomTypes as $type)
                                            <option value="{{ $type->id }}" {{ request('room_type_id') == $type->id ? 'selected' : '' }}>
                                                {{ $type->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    
                                    <select name="status" class="form-select form-select-sm" style="width:120px;">
                                        <option value="">Tất cả trạng thái</option>
                                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Hoạt động</option>
                                        <option value="maintenance" {{ request('status') == 'maintenance' ? 'selected' : '' }}>Bảo trì</option>
                                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Không hoạt động</option>
                                    </select>
                                    
                                    <button type="submit" class="btn btn-sm bg-primary text-white">
                                        <i class="bi bi-search">Tìm/lọc</i>
                                    </button>
                                </form>

                                <!-- Nút thêm phòng -->
                                @can('create room')
                                    <a href="{{ route('admin.rooms.create') }}" class="btn btn-sm btn-success">
                                        <i class="bi bi-plus-circle me-1"></i> Thêm phòng
                                    </a>
                                @endcan
                            </div>
                        </div>
                    </div>
                    
                    <!-- Form xóa hàng loạt -->
                    <form id="delete-selected-form" action="#" method="POST" style="display: none;" class="mt-2">
                        @csrf
                        @method('DELETE')
                        <input type="hidden" name="ids" id="selected-room-ids">
                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Bạn có chắc muốn xóa các phòng đã chọn?')">
                            <i class="bi bi-trash me-1"></i>Xóa đã chọn
                        </button>
                    </form>
                </div>
                <div>
                    <div class="table-responsive">
                        <table class="table align-middle mb-0 table-hover table-centered">
                            <thead class="bg-light-subtle">
                                <tr>
                                    <th style="width: 20px;">
                                        <div class="form-check ms-1">
                                            <input type="checkbox" class="form-check-input" id="checkAllRooms">
                                        </div>
                                    </th>
                                    <th style="width: 200px;">Tên Phòng</th>
                                    <th style="width: 200px;">Rạp Chiếu</th>
                                    <th style="width: 150px;">Loại Phòng</th>
                                    <th style="width: 100px;">Sức Chứa</th>
                                    <th style="width: 120px;">Trạng Thái</th>
                                    <th style="width: 80px;">ID</th>
                                    <th class="text-center" style="width: 120px;">Hành Động</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($rooms as $room)
                                    <tr>
                                        <td>
                                            <div class="form-check ms-1">
                                                <input type="checkbox" class="form-check-input room-checkbox" value="{{ $room->id }}">
                                            </div>
                                        </td>
                                        <td>
                                            <div>
                                                <h6 class="mb-1 fw-semibold">{{ $room->name }}</h6>
                                                <small class="text-muted">
                                                    Phòng số {{ $room->id }}
                                                </small>
                                            </div>
                                        </td>
                                        <td>
                                            <div>
                                                <div class="fw-medium">{{ $room->cinema->name }}</div>
                                                <small class="text-muted">{{ $room->cinema->address ?? 'N/A' }}</small>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-info-subtle text-info">{{ $room->roomType->name }}</span>
                                        </td>
                                        <td>
                                            <span class="fw-medium">{{ $room->capacity }}</span>
                                            <small class="text-muted d-block">chỗ ngồi</small>
                                        </td>
                                        <td>
                                            @if ($room->status === 'active')
                                                <span class="badge bg-success">Hoạt động</span>
                                            @elseif ($room->status === 'maintenance')
                                                <span class="badge bg-warning text-dark">Bảo trì</span>
                                            @else
                                                <span class="badge bg-danger">Không hoạt động</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="text-muted">#{{ $room->id }}</span>
                                        </td>
                                        <td>
                                            <div class="d-flex gap-2 justify-content-center">
                                                <a href="{{ route('admin.rooms.show', $room->id) }}"
                                                    class="btn btn-light btn-sm" title="Xem chi tiết">
                                                    <iconify-icon icon="solar:eye-broken" class="align-middle fs-18"></iconify-icon>
                                                </a>
                                                @can('edit room')
                                                    <a href="{{ route('admin.rooms.edit', $room->id) }}"
                                                    class="btn btn-soft-primary btn-sm" title="Chỉnh sửa">
                                                        <iconify-icon icon="solar:pen-2-broken" class="align-middle fs-18"></iconify-icon>
                                                    </a>
                                                @endcan
                                                @can('delete room')
                                                    <form action="{{ route('admin.rooms.destroy', $room->id) }}" method="POST" 
                                                          style="display: inline;" onsubmit="return confirm('Bạn có chắc muốn xóa phòng này?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-soft-danger btn-sm" title="Xóa">
                                                            <iconify-icon icon="solar:trash-bin-minimalistic-broken" class="align-middle fs-18"></iconify-icon>
                                                        </button>
                                                    </form>
                                                @endcan
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                                @if($rooms->isEmpty())
                                    <tr>
                                        <td colspan="8" class="text-center text-muted py-4">
                                            <i class="bi bi-camera-reels display-4 text-muted mb-3"></i>
                                            <div>Không tìm thấy phòng chiếu nào.</div>
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer border-top">
                    <div class="d-flex justify-content-end">
                        {{ $rooms->appends(['search' => request('search'), 'room_type_id' => request('room_type_id'), 'status' => request('status')])->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
/* Enhanced table styling */
.table {
    border-collapse: separate;
    border-spacing: 0;
}

.table th {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    font-weight: 600;
    color: #495057;
    border-top: none;
    border-bottom: 2px solid #dee2e6;
    padding: 1rem 0.75rem;
    font-size: 0.85rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.table td {
    vertical-align: middle;
    border-top: 1px solid #f1f3f4;
    padding: 0.875rem 0.75rem;
}

.table tbody tr {
    transition: all 0.2s ease;
}

.table tbody tr:hover {
    background-color: #f8f9ff;
    transform: translateY(-1px);
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
}

/* Card enhancements */
.card {
    border: none;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.07);
    border-radius: 12px;
    overflow: hidden;
}

.card-header {
    background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
    border-bottom: 1px solid #e9ecef;
    padding: 1.5rem;
}

/* Form controls */
.form-control-sm, .form-select-sm {
    border-radius: 8px;
    border: 1px solid #d1d9e0;
    transition: all 0.2s ease;
}

.form-control-sm:focus, .form-select-sm:focus {
    border-color: #4f46e5;
    box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
}

/* Button enhancements */
.btn-sm {
    padding: 0.375rem 0.75rem;
    font-size: 0.825rem;
    border-radius: 8px;
    font-weight: 500;
    transition: all 0.2s ease;
}

.btn-success {
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    border: none;
}

.btn-success:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(16, 185, 129, 0.4);
}

.btn-primary {
    background: linear-gradient(135deg, #4f46e5 0%, #4338ca 100%);
    border: none;
}

.btn-primary:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(79, 70, 229, 0.4);
}

.btn-soft-primary {
    background: rgba(79, 70, 229, 0.1);
    border: 1px solid rgba(79, 70, 229, 0.2);
    color: #4f46e5;
}

.btn-soft-primary:hover {
    background: rgba(79, 70, 229, 0.15);
    transform: translateY(-1px);
    box-shadow: 0 2px 8px rgba(79, 70, 229, 0.2);
}

.btn-soft-danger {
    background: rgba(239, 68, 68, 0.1);
    border: 1px solid rgba(239, 68, 68, 0.2);
    color: #ef4444;
}

.btn-soft-danger:hover {
    background: rgba(239, 68, 68, 0.15);
    transform: translateY(-1px);
    box-shadow: 0 2px 8px rgba(239, 68, 68, 0.2);
}

/* Badge styling */
.badge {
    font-size: 0.75rem;
    padding: 0.35rem 0.65rem;
    border-radius: 6px;
    font-weight: 500;
}

/* Status badges */
.bg-success {
    background: linear-gradient(135deg, #10b981 0%, #059669 100%) !important;
}

.bg-warning {
    background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%) !important;
}

.bg-danger {
    background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%) !important;
}

.bg-info-subtle {
    background: rgba(59, 130, 246, 0.1) !important;
    color: #3b82f6 !important;
}

/* Alert styling */
.alert {
    border-radius: 12px;
    border: none;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.07);
    margin-bottom: 1.5rem;
}

.alert-success {
    background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
    color: #065f46;
}

.alert-danger {
    background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
    color: #991b1b;
}

/* Responsive improvements */
@media (max-width: 768px) {
    .table td, .table th {
        padding: 0.5rem 0.25rem;
        font-size: 0.8rem;
    }
    
    .btn-sm {
        padding: 0.25rem 0.5rem;
        font-size: 0.75rem;
    }
    
    .card-header {
        padding: 1rem;
    }
    
    .badge {
        font-size: 0.65rem;
        padding: 0.25rem 0.5rem;
    }
}

/* Loading state */
.table tbody tr.loading {
    opacity: 0.6;
    pointer-events: none;
}

/* Enhanced room name styling */
.table td h6 {
    margin-bottom: 0.25rem;
    font-size: 0.95rem;
    color: #1f2937;
    line-height: 1.3;
}

.table td small {
    font-size: 0.75rem;
    color: #6b7280;
    line-height: 1.4;
}

/* Empty state styling */
.table tbody tr td i.bi-camera-reels {
    opacity: 0.3;
}
</style>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    function updateDeleteButton() {
        const checked = document.querySelectorAll('.room-checkbox:checked');
        const form = document.getElementById('delete-selected-form');
        if (checked.length > 0) {
            form.style.display = 'inline-block';
        } else {
            form.style.display = 'none';
        }
    }   

    document.getElementById('checkAllRooms')?.addEventListener('change', function() {
        document.querySelectorAll('.room-checkbox').forEach(cb => {
            cb.checked = this.checked;
        });
        updateDeleteButton();
    });

    document.querySelectorAll('.room-checkbox').forEach(cb => {
        cb.addEventListener('change', updateDeleteButton);
    });

    document.getElementById('delete-selected-form').addEventListener('submit', function(e) {
        const checked = Array.from(document.querySelectorAll('.room-checkbox:checked')).map(cb => cb.value);
        if (checked.length === 0) {
            e.preventDefault();
            return false;
        }
        document.getElementById('selected-room-ids').value = checked.join(',');
    });
});
</script>