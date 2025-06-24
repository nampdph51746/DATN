{{-- filepath: resources/views/admin/movies/ageLimit/index.blade.php --}}
@extends('layouts.admin.admin')

@section('content')
<div class="container-fluid">
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="row">
        <div class="col-xl-12">
            <div class="card mt-3">
                <div class="card-header d-flex justify-content-between align-items-center gap-2">
                    <h4 class="card-title flex-grow-1">Danh sách giới hạn độ tuổi</h4>

                    <form id="delete-selected-age-limit-form" method="POST" action="{{ route('admin.age_limits.bulkDelete') }}" style="display: none;">
                        @csrf
                        <input type="hidden" name="ids" id="selected-age-limit-ids">
                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Bạn có chắc muốn xóa các giới hạn đã chọn?')">
                            <iconify-icon icon="solar:trash-bin-trash-bold-duotone" class="me-1"></iconify-icon>
                            Xóa đã chọn
                        </button>
                    </form>
                    <form class="d-flex align-items-center gap-2 ms-2" method="GET" action="{{ route('admin.age_limits.index') }}" style="min-width:320px;">
                        <div class="position-relative flex-grow-1">
                            <input type="search" name="query" class="form-control form-control-sm ps-5 pe-3 rounded-2"
                                placeholder="Tìm kiếm tên giới hạn..." autocomplete="off" value="{{ request('query') }}">
                            <iconify-icon icon="solar:magnifer-linear"
                                class="position-absolute top-50 start-0 translate-middle-y ms-3 text-muted"
                                style="font-size: 16px;"></iconify-icon>
                        </div>
                        <button type="submit" class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-search"></i>
                        </button>
                    </form>
                    <div class="d-flex gap-2 flex-wrap">
                        <a href="{{ route('admin.age_limits.create') }}" class="btn btn-sm btn-primary">
                            <i class="bi bi-plus-circle"></i> Thêm mới
                        </a>
                    </div>
                </div>
                <div>
                    <div class="table-responsive">
                        <table class="table align-middle mb-0 table-hover table-centered">
                            <thead class="bg-light-subtle">
                                <tr>
                                    <th style="width: 20px;">
                                        <div class="form-check ms-1">
                                            <input type="checkbox" class="form-check-input" id="checkAllAgeLimits">
                                        </div>
                                    </th>
                                    <th>Tên giới hạn</th>
                                    <th>Mô tả</th>
                                    <th>Độ tuổi tối thiểu</th>
                                    <th>Thời gian tạo</th>
                                    <th>Thời gian cập nhật</th>
                                    <th class="text-center" style="width: 120px;">Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($ageLimits as $ageLimit)
                                    <tr>
                                        <td>
                                            <div class="form-check ms-1">
                                                <input type="checkbox" class="form-check-input age-limit-checkbox" value="{{ $ageLimit->id }}" id="ageLimitCheck{{ $ageLimit->id }}">
                                            </div>
                                        </td>
                                        <td class="fw-semibold">{{ $ageLimit->name }}</td>
                                        <td>{{ $ageLimit->description }}</td>
                                        <td>
                                            {{ $ageLimit->min_age !== null ? $ageLimit->min_age : 'Không giới hạn' }}
                                        </td>
                                        <td>
                                            {{ $ageLimit->created_at ? $ageLimit->created_at->format('d/m/Y H:i') : '' }}
                                        </td>
                                        <td>
                                            {{ $ageLimit->updated_at ? $ageLimit->updated_at->format('d/m/Y H:i') : '' }}
                                        </td>
                                        <td class="text-center">
                                            <div class="d-flex gap-2 justify-content-center">
                                                <a href="{{ route('admin.age_limits.edit', $ageLimit->id) }}" class="btn btn-soft-primary btn-sm" title="Sửa">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                                <form action="{{ route('admin.age_limits.destroy', $ageLimit->id) }}" method="POST" style="display:inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-soft-danger btn-sm"
                                                        onclick="return confirm('Bạn có chắc muốn xóa?')">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center text-muted">Chưa có dữ liệu.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer border-top">
                    <div class="d-flex justify-content-end">
                        {{ $ageLimits->appends(request()->query())->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Hiện/ẩn nút xóa đã chọn
    function updateDeleteAgeLimitButton() {
        const checked = document.querySelectorAll('.age-limit-checkbox:checked');
        const form = document.getElementById('delete-selected-age-limit-form');
        if (checked.length > 0) {
            form.style.display = 'inline-block';
        } else {
            form.style.display = 'none';
        }
    }

    // Chọn tất cả
    document.getElementById('checkAllAgeLimits')?.addEventListener('change', function() {
        document.querySelectorAll('.age-limit-checkbox').forEach(cb => {
            cb.checked = this.checked;
        });
        updateDeleteAgeLimitButton();
    });

    // Check từng dòng
    document.querySelectorAll('.age-limit-checkbox').forEach(cb => {
        cb.addEventListener('change', updateDeleteAgeLimitButton);
    });

    document.getElementById('delete-selected-age-limit-form').addEventListener('submit', function (e) {
        const checked = Array.from(document.querySelectorAll('.age-limit-checkbox:checked')).map(cb => cb.value);
        console.log('IDs được chọn:', checked);

        if (checked.length === 0) {
            alert('Bạn chưa chọn mục nào!');
            return;
        }

        if (confirm('Bạn có chắc muốn xóa các giới hạn đã chọn?')) {
            document.getElementById('selected-age-limit-ids').value = checked.join(',');
            this.submit(); // Submit sau khi đã gán
        }
    });
});
</script>
@endsection