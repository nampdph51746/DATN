{{-- filepath: resources/views/admin/movies/ageLimit/index.blade.php --}}
@extends('layouts.admin.admin')

@section('content')
<div class="container-xxl">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card mt-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title mb-0">Danh sách giới hạn độ tuổi</h4>
                    <form id="delete-selected-age-limit-form" action="{{ route('admin.age_limits.bulkDelete') }}" method="POST" style="display: none;">
                            @csrf
                            @method('DELETE')
                            <input type="hidden" name="ids" id="selected-age-limit-ids">
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Bạn có chắc muốn xóa các giới hạn đã chọn?')">
                                Xóa đã chọn
                            </button>
                        </form>
                    <div class="d-flex gap-2">
                        <a href="{{ route('admin.age_limits.create') }}" class="btn btn-primary btn-sm">Thêm mới</a>
                    </div>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    <table class="table table-bordered align-middle">
                        <thead>
                            <tr>
                                <th style="width: 40px;">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="checkAllAgeLimits">
                                        <label class="form-check-label" for="checkAllAgeLimits"></label>
                                    </div>
                                </th>
                                <th>Tên</th>
                                <th>Mô tả</th>
                                <th>Độ tuổi tối thiểu</th>
                                <th>Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($ageLimits as $ageLimit)
                                <tr>
                                    <td>
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input age-limit-checkbox" value="{{ $ageLimit->id }}" id="ageLimitCheck{{ $ageLimit->id }}">
                                            <label class="form-check-label" for="ageLimitCheck{{ $ageLimit->id }}"></label>
                                        </div>
                                    </td>
                                    <td>{{ $ageLimit->name }}</td>
                                    <td>{{ $ageLimit->description }}</td>
                                    <td>{{ $ageLimit->min_age }}</td>
                                cd
                                    <td>
                                        <a href="{{ route('admin.age_limits.edit', $ageLimit->id) }}" class="btn btn-warning btn-sm">Sửa</a>
                                        <form action="{{ route('admin.age_limits.destroy', $ageLimit->id) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Xóa?')">Xóa</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center">Chưa có dữ liệu.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    <div class="mt-2">
                        {{ $ageLimits->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

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
    document.getElementById('checkAllAgeLimits').addEventListener('change', function() {
        document.querySelectorAll('.age-limit-checkbox').forEach(cb => {
            cb.checked = this.checked;
        });
        updateDeleteAgeLimitButton();
    });

    // Check từng dòng
    document.querySelectorAll('.age-limit-checkbox').forEach(cb => {
        cb.addEventListener('change', updateDeleteAgeLimitButton);
    });

    // Khi submit form xóa, lấy id các age limit đã chọn
    document.getElementById('delete-selected-age-limit-form').addEventListener('submit', function(e) {
        const checked = Array.from(document.querySelectorAll('.age-limit-checkbox:checked')).map(cb => cb.value);
        if (checked.length === 0) {
            e.preventDefault();
            return false;
        }
        document.getElementById('selected-age-limit-ids').value = checked.join(',');
    });
});
</script>